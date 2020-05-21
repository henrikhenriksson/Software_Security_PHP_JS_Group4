<?php

/**
 * Member handles member data and operations such as CRUD, login, restore.
 */
class Member
{
    private $id;
    private $username;
    // No password storage
    private $error = false;
    private $error_message;
    private $roles;

    /**
     * Summary. Initializing constructor.
     * @param string $id Member id.
     * @param string $username Member username.
     */
    public function __construct(int $id = -1, string $username = "", array $roles = [])
    {
        // XSS escaping in constructor
        // Returned values from methods are considered safe.
        $this->id = $id;
        $this->username = escape($username);
        $this->roles = $roles;
    }

    public static function getAll(int $limit = 0, int $offset = 0): array
    {
        $db = DatabaseHandler::getInstance();
        $sql = "SELECT id, username FROM sdt167g.users";
        if ($limit < 0) {
            $sql += " LIMIT $limit";
        }
        if ($offset < 0) {
            $sql += " OFFSET $offset";
        }
        $ok = $db->query($sql);
        // TODO
        return $result ? pg_fetch_all($result) : [];
    }

    public static function login(string $uname, string $pass): Member
    {
        $db = DatabaseHandler::getInstance();
        $sql = "SELECT id, username, password FROM dt167g.users WHERE username=$1";
        $ok = $db->query($sql, [$uname]);

        if (!$ok) {
            $user = new Member();
            $user->setError("Database error");
            return $user;
        }

        $data = $db->getNextRow();
        if (empty($data) || !self::validPassword($pass, $data['password'])) {
            $user = new Member();
            // The only information sent back is if the combination of username
            // and password was correct. No hints about if the username or
            // password exists individually.
            $user->setError("Invalid credentials!");
            return $user;
        }

        $_SESSION['user'] = $data['id'];
        return new Member($data['id'], $data['username']);
    }

    // TODO fix this method
    public static function fromSession(): Member
    {
        $db = DatabaseHandler::getInstance();
        // TODO check session user exists
        $id = $_SESSION['user'];
        $sql = "SELECT id, username FROM dt167g.users WHERE id=$1";
        $ok = $db->query($sql, [ $id ]);

        if (!$ok) {
            $user = new Member();
            $user->setError("Database error");
            return $user;
        }

        $data = $db->getNextRow();
        if (empty($data) || !self::validPassword($pass, $data['password'])) {
            $user = new Member();
            $user->setError("Invalid user id: ${id}");
            return $user;
        }

        $_SESSION['user'] = $data['id'];
        return new Member($data['id'], $data['username']);
    }

    private static function validPassword(string $entered, string $actual): bool
    {
        // TODO hash
        return $entered === $actual;
    }

    private function setError(string $msg)
    {
        $this->error = true;
        $this->error_message = $msg;
    }

    public function error(): bool
    {
        return $this->error;
    }

    public function errorMessage(): string
    {
        return $this->error_message;
    }
}
