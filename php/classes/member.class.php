<?php declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: member.class.php
 ******************************************************************************/

require_once __DIR__.'/../globals.php';
require_once __DIR__ . '/../functions/strings.php';
require_once __DIR__ . '/../functions/sql.php';

use \ParagonIE\EasyDB\EasyDB;
use function Latitude\QueryBuilder\field;

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
            $db = getEasyDB();
        }
        foreach ($db->run($query->sql()) as $row) {
            $members[] = static::fromRow($db, $row);
        }
        return $members;
    }

    /**
     * Returns true if a user is logged into this session
     */
    public static function loggedIn(): bool
    {
        //valid user id TODO check that id in session is an existing id in database.
        return isset($_SESSION['user']);
    }

    /**
     * Logs in a new user
     */
    public static function login(string $uname, string $pass, EasyDB $db = null): Member
    {
        $factory = makeQueryFactory();
        $query = $factory
            ->select('id', 'username', 'password')
            ->from('dt167g.users')
            ->where(field('username')->eq($uname))
            ->compile();

        if ($db == null) {
            $db = getEasyDB();
        }

        $row = $db->row($query->sql(), $query->params()[0]);
        if (empty($row) || !password_verify($pass, $row['password'])) {
            // The only error information sent back is if the combination of
            // username and password was correct. No hints about if the
            // username or password exists individually.
            return static::memberError("Invalid credentials!");
        }

        $_SESSION['user'] = $row['id'];
        return static::fromRow($db, $row);
    }

    /**
     * Fetches the user logged into this session.
     */
    public static function fromSession(EasyDB $db = null): Member
    {
        if (!isset($_SESSION['user'])) {
            return static::memberError("No user in current session!");
        }

        if ($db == null) {
            $db = getEasyDB();
        }

        $factory = makeQueryFactory();
        $query = $factory
            ->select('id', 'username')
            ->from('dt167g.users')
            ->where(field('id')->eq($_SESSION['user']))
            ->compile();

        $row = $db->row($query->sql(), $query->params()[0]);
        if (empty($row)) {
            return static::memberError("Invalid user id: {$_SESSION['user']}");
        }
        return static::fromRow($db, $row);
    }

    private static function memberError(string $error_msg): Member
    {
        $user = new Member();
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

    private function setError(string $msg)
    {
        $this->error_message = $msg;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function username(): string
    {
        return escape($this->username());
    }

    public function id(): int
    {
        return $this->id;
    }

    // TODO save new user
    // TODO get(username)
    // TODO update (password)
    // TODO delete

    public function error(): bool
    {
        return !empty($this->error_message);
    }

    public function errorMessage(): string
    {
        return $this->error_message;
    }
}
