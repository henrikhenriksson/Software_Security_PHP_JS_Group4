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
            setError("Querying bad database connection");
            return false;
        }

        // Query should not contain user input, but this makes sure it is safe.
        $query = pg_escape_string($query);

        // Reset any errors and release old result, if any.
        $this->resetResult();

        $this->result = empty($params)
            ? @ pg_query($this->dbconn, $query)
            : @ pg_query_params($this->dbconn, $query, $params);

        if (!$this->result && !empty($this->result)) {
            setError("Invalid query");
            return false;
        }

        return true;
    }

    /**
     * Performs a select query on the database.
     *
     * @param $tbl The table to query
     * @param $fields The fields to select, given as an array of strings
     * @param $crit The criteria to use in the query
     */
    public function select(string $tbl, array $fields = ["*"], array $crit = []): bool
    {
        $fields = implode(",", $fields);
        $sql = "SELECT {$fields} from {$tbl}";
        return $this->query($sql);
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
        if (!$this->result) {
            $this->setError("Tried getting result before making query");
            return [];
        }

        $row = pg_fetch_assoc($this->result);
        if (!$row) {
            // No more rows in result.
            $this->freeResult();
            return [];
        }
        return $row;
    }

    public function getAllRows(): array
    {
        if (!$this->result) {
            $this->setError("Requested result on invalid or not yet executed query");
            return [];
        }

        $rows = pg_fetch_all($this->result);
        $this->freeResult();
        return $rows ? $rows : [];
    }

    private function setError(string $msg)
    {
        $this->error_message = $msg;
    }

    private function resetResult(): void
    {
        $this->freeResult();
        $this->error_message = "";
        $this->result = null;
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
                $this->setError("Could not connect to database");
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
