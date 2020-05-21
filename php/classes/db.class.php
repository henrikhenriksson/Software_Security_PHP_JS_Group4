<?php


/**
 * Summary. Singleton class that handles the connection to the database
 * and provides an interface to post and get data from the database.
 */
class DB
{
    private $dbconn = null;
    private $result = null;
    private string $error_message = "";

    /**
     * Summary. Default constructor.
     */
    public function __construct(string $dsn)
    {
        $this->connect($dsn);
    }

    public function __destruct()
    {
        $this->freeResult();
        $this->disconnect();
    }

    public function query(string $query, array $params = []): bool
    {
        if ($this->badConnection()) {
            return false;
        }
        $this->resetResult();
        $this->result = empty($params)
            ? @ pg_query($this->dbconn, $query)
            : @ pg_query_params($this->dbconn, $query, $params);
        return $this->result !== false;
    }

    private function resetResult(): void
    {
        $this->error_message = "";
        $this->result = null;
    }

    public function error(): bool
    {
        return !empty($this->error_message);
    }

    public function errorMessage(): string
    {
        return $this->error_message;
    }

    public function resultCount(): int
    {
        return $this->result ? pg_num_rows($this->result) : 0;
    }

    public function getNextRow(): array
    {
        $row = pg_fetch_assoc($this->result);
        if (!$row) {
            $this->freeResult();
            return [];
        }
        return $row;
    }

    public function getAllRows(): array
    {
        $rows = pg_fetch_all($this->result);
        $this->freeResult();
        return $rows ? $rows : [];
    }

    /**
     * Summary. Establishes a connection to the database.
     * @return bool True if connection was successful.
     * False if no connection was established.
     */
    private function connect(string $dsn)
    {
        if (!$this->dbconn) {
            // Ignore connection error and return false instead
            $this->dbconn = @ pg_connect($dsn);
            if (!$this->dbconn) {
                $this->error_message = "Could not connect to database";
                return false;
            }
        }
        return true;
    }

    private function badConnection(): bool
    {
        return !$this->dbconn ||
            pg_connection_status($this->dbconn) !== PGSQL_CONNECTION_OK;
    }

    /**
     * Summary. Disconnects from the database.
     */
    private function disconnect()
    {
        if ($this->dbconn) {
            pg_close($this->dbconn);
        }
    }

    /**
     * Frees the result resource.
     *
     * Should be done automatically when script ends, but might as well be
     * explicit.
     */
    private function freeResult()
    {
        if ($this->result) {
            pg_free_result($this->result);
            $this->result = null;
        }
    }
}
