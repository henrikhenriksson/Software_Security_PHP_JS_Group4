<?php declare(strict_types=1);

require_once __DIR__.'/../functions/strings.php';
require_once __DIR__.'/../functions/sql.php';

use \ParagonIE\EasyDB\EasyDB;
use \Latitude\QueryBuilder as Q;

/**
 * Member handles member data and operations such as CRUD, login, restore.
 *
 * The class escapes any returned strings so that they are XSS-safe.
 */
class Member
{
    private EasyDB $db;
    private int $id = -1;
    // No password storage
    private string $username = "";
    private array $roles = [];
    private string $error_message = "";

    const MAX_PASSWORD_LENGTH = 64;  // BCrypt only allows 64 characters

    /**
     * Summary. Initializing constructor.
     * @param EasyDB $db the EasyDB instance to use for queries.
     * @param string $id Member id.
     */
    public function __construct(EasyDB $db = null)
    {
        $this->db = ($db != null) ? $db : getEasyDB();
    }

    /**
     * Fetch all members in the database.
     *
     * Convenience method for fetchMembers without limit or offset
     */
    public static function fetchAll(EasyDB $db = null)
    {
        return static::fetchMembers(0, 0, $db);
    }

    /**
     * Returns an array of members from the database.
     */
    public static function fetchMembers(int $lim = 0, int $off = 0, EasyDB $db = null): array
    {
        $factory = makeQueryFactory();
        $query = $factory
            ->select('id', 'username')
            ->from('dt167g.users')
            ->limit($lim > 0 ? $lim : null)  // LIMIT 0 will return 0 rows
            ->offset($off)
            ->compile();
        if ($db == null) {
            $db = getEasyDB();  // @codeCoverageIgnore
        }
        foreach ($db->run($query->sql()) as $row) {
            $members[] = static::fromRow($db, $row);
        }
        return $members;
    }

    private static function fetchByUsername(string $username, EasyDB $db = null): Member
    {
    }

    private static function fetchById(int $id, EasyDB $db = null): Member
    {
    }

    /**
     * Returns true if a user is logged into this session
     */
    public static function loggedIn(): bool
    {
        //valid user id TODO check that id in session is an existing id in database.
        return Session::has('userid');
    }

    /**
     * Logs in a new user
     */
    public static function login(string $uname, string $pass, EasyDB $db = null): Member
    {
        if ($db == null) {
            $db = getEasyDB();  // @codeCoverageIgnore
        }

        if (empty(trim($uname)) || empty(trim($pass))) {
            return static::memberError("Invalid credentials!", $db);
        }

        $factory = makeQueryFactory();
        $query = $factory
            ->select('id', 'username', 'password')
            ->from('dt167g.users')
            ->where(Q\field('username')->eq($uname))
            ->compile();

        $row = $db->row($query->sql(), $query->params()[0]);
        if (empty($row) || !password_verify($pass, $row['password'])) {
            // The only error information sent back is if the combination of
            // username and password was correct. No hints about if the
            // username or password exists individually.
            return static::memberError("Invalid credentials!", $db);
        }

        Session::set('userid', $row['id']);
        return static::fromRow($db, $row);
    }

    /**
     * Fetches the user logged into this session.
     */
    public static function fromSession(EasyDB $db = null): Member
    {
        if (!self::loggedIn()) {
            return static::memberError("No user in current session!", $db);
        }

        if ($db == null) {
            $db = getEasyDB();  // @codeCoverageIgnore
        }

        $factory = makeQueryFactory();
        $query = $factory
            ->select('id', 'username')
            ->from('dt167g.users')
            ->where(Q\field('id')->eq(Session::get('userid')))
            ->compile();

        $row = $db->row($query->sql(), $query->params()[0]);
        if (empty($row)) {
            return static::memberError(
                "Invalid user id: " . Session::get('userid'),
                $db
            );
        }
        return static::fromRow($db, $row);
    }

    private static function memberError(string $error_msg, EasyDB $db): Member
    {
        $user = new Member($db);
        $user->setError($error_msg);
        return $user;
    }

    private static function fromRow(EasyDB $db, array $row): Member
    {
        $member = new Member($db);
        $member->id = (int) $row['id'];
        $member->setUsername($row['username']);
        return $member;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function username(): string
    {
        return escape($this->username);
    }

    public function id(): int
    {
        return $this->id;
    }

    /**
     * Insert a new member into the database
     *
     * If the member could not be inserted an error message is generated.
     *
     * @param string $password the user entered password
     *
     * @return bool true if member was inserted successfully, false otherwise
     */
    public function save(string $password): bool
    {
        if (empty($this->username)) {
            $this->setError("Must set username before saving member");
            return false;
        }
        if (strlen($password) > static::MAX_PASSWORD_LENGTH) {
            $this->setError("Maximum password length is 64 characters");
            return false;
        }
        if ($this->usernameExists()) {
            $this->setError("User ".$this->username()." already exists");
            return false;
        }
        $this->clearError();
        return $this->insert($password);
    }

    public function usernameExists(): bool
    {
        $factory = makeQueryFactory();
        $query = $factory
            ->select(Q\func('COUNT', 'id'))
            ->from('users')
            ->where(Q\field('username')->eq($this->username))  // uses raw username
            ->compile();
        return $this->db->single($query->sql(), $query->params()) != false;
    }

    public function idExists(): bool
    {
        $factory = makeQueryFactory();
        $query = $factory
            ->select(Q\func('COUNT', 'id'))
            ->from('users')
            ->where(Q\field('id')->eq($this->id))
            ->compile();
        return $this->db->single($query->sql(), $query->params()) !== false;
    }

    private function insert(string $password): bool
    {
        try {
            $this->id = $this->db->insertGet('users', [
                'username' => $this->username,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ], 'id');
            $this->clearError();
            return true;
        } catch (\Exception $e) { // @codeCoverageIgnoreStart
            $this->setError($e->getMessage());
            $this->id = -1;
            return false;
        }// @codeCoverageIgnoreEnd
    }

    // TODO update (password)
    public function changePassword(string $newPassword): bool
    {
        if ($this->id === -1) {
            $this->setError("User must be fetched from database to change password");
            return false;
        }
        $this->clearError();
        return true;
    }

    // TODO delete
    public function remove(): bool
    {
    }

    private function setError(string $msg)
    {
        $this->error_message = $msg;
    }

    public function error(): bool
    {
        return !empty($this->error_message);
    }

    public function errorMessage(): string
    {
        return $this->error_message;
    }

    private function clearError(): void
    {
        $this->error_message = "";
    }
}
