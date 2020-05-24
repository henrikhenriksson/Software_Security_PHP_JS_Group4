<?php declare(strict_types=1);

require_once __DIR__.'/../functions/strings.php';
require_once __DIR__.'/../functions/sql.php';

use \ParagonIE\EasyDB\EasyDB;

/**
 * Member handles member data and operations such as CRUD, login, restore.
 *
 * The class escapes any returned strings so that they are XSS-safe.
 */
class Member
{
    private EasyDB $db;
    private int $id;
    // No password storage
    private string $username = "";
    private array $roles = [];
    private string $error_message = "";

    const MAX_PASSWORD_LENGTH = 64;  // BCrypt only allows 64 characters

    /**
     * Summary. Initializing constructor.
     * @param DB $db the DB instance to use for queries.
     * @param string $id Member id.
     */
    public function __construct(EasyDB $db = null, int $id = -1)
    {
        $this->id = $id;
        $this->db = ($db != null) ? $db : getEasyDB();
    }

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
            $db = getEasyDB();
        }
        foreach ($db->run($query->sql()) as $row) {
            $members[] = static::fromRow($db, $row);
        }
        return $members;
    }

    private static function fromRow(EasyDB $db, array $row): Member
    {
        $member = new Member($db, (int) $row['id']);
        $member->setUsername($row['username']);
        return $member;
    }

    public function setUsername(string $username): void
    {
        $this->username = escape($username);
    }

    public function username(): string
    {
        return $this->username();
    }

    public function id(): int
    {
        return $this->id;
    }

    public static function loggedIn()
    {
        //valid user id TODO check that id in session is an existing id in database.
        return isset($_SESSION['user']);
    }

    public static function login(DB $db = null, string $uname, string $pass): Member
    {
        $factory = makeQueryFactory();
        $query = $factory
            ->select('id', 'username')
            ->from('dt167g.users')
            ->where(field('username')->eq(5))
            ->compile();


        $data = $db->row($query->sql(), $query->params());
        if (empty($data) || !password_verify($pass, $data['password'])) {
            // The only error information sent back is if the combination of
            // username and password was correct. No hints about if the
            // username or password exists individually.
            $user = new Member();
            $user->setError("Invalid credentials!");
            return $user;
        }

        $_SESSION['user'] = $data['id'];
        return new Member($data['id'], $data['username']);
    }

    // TODO fix this method
    public static function fromSession(DB $db = null): Member
    {
        if (!isset($_SESSION['user'])) {
            $user = new Member();
            $user->setError("No user in current session!");
            return $user;
        }

        if ($db == null) {
            $db = getDBInstance();
        }

        $id = $_SESSION['user'];
        $sql = "SELECT id, username FROM dt167g.users WHERE id=$1";
        $ok = $db->query($sql, [ $id ]);

        if (!$ok) {
            $user = new Member();
            $user->setError("Database error");
            return $user;
        }

        $data = $db->getNextRow();
        if (empty($data)) {
            $user = new Member();
            $user->setError("Invalid user id: ${id}");
            return $user;
        }

        return new Member($data['id'], $data['username']);
    }

    private function setError(string $msg)
    {
        $this->error = true;
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
}
