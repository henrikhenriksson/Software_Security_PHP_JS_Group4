<?php declare(strict_types=1);

class Session
{
    private static ?SessionAdapter $adapter = null;

    public static function init(SessionAdapter $adapter): void
    {
        self::$adapter = $adapter;
    }

    public static function get(string $key)
    {
        return self::$adapter->get($key);
    }

    public static function set(string $key, $value): void
    {
        self::$adapter->set($key, $value);
    }

    public static function has(string $key): bool
    {
        return self::$adapter->has($key);
    }

    public static function unset(string $key): void
    {
        self::$adapter->unset($key);
    }

    public static function kill(): void
    {
        self::$adapter->kill();
    }
}

interface SessionAdapter
{
    public function get(string $key);
    public function set(string $key, $value): void;
    public function has(string $key): bool;
    public function unset(string $key): void;
    public function kill(): void;
}

class WebSession implements SessionAdapter
{
    public function get(string $key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function kill(): void
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}

class TestSession implements SessionAdapter
{
    private $session;

    public function __construct()
    {
        $this->session = [];
    }

    public function get(string $key)
    {
        return isset($this->session[$key]) ? $this->session[$key] : null;
    }

    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->session[$key]) ? true : false;
    }

    public function unset(string $key): void
    {
        unset($this->session[$key]);
    }

    public function kill(): void
    {
        $this->session = [];
    }
}
