<?php

/**
 * Member handles member operations such as login, create and storing
 * information about the member.
 */
class Member
{
    private $id;
    private $username;
    private $error = false;
    private $error_message;
    private $roles = [];

    /**
     * Summary. Initializing constructor.
     * @param string $id Member id.
     * @param string $username Member username.
     */
    public function __construct(int $id = -1, string $username = "", $roles = null)
    {
        $this->id = $id;
        $this->username = $username;
        if ($roles) {
            $this->roles = $roles;
        }
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

        $data = $db->getFirstResult();
        if (empty($data) || !self::validPassword($pass, $data['password'])) {
            $user = new Member();
            $user->setError("Invalid credentials!");
            return $user;
        }

        $_SESSION['user'] = $data['id'];
        return new Member($data['id'], $data['username']);
    }

    public static function fromSession(): Member
    {
        $db = DatabaseHandler::getInstance();
        $id = $_SESSION['user'];
        $sql = "SELECT id, username FROM dt167g.users WHERE id=$1";
        $ok = $db->query($sql, [ $id ]);

        if (!$ok) {
            $user = new Member();
            $user->setError("Database error");
            return $user;
        }

        // TODO fix this method
        $data = $db->getFirstResult();
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
