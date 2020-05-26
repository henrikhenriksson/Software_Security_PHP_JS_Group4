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
}

interface SessionAdapter
{
    public function get(string $key);
    public function set(string $key, $value): void;
    public function has(string $key): bool;
    public function unset(string $key): void;
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
}
