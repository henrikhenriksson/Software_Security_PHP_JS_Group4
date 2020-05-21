<?php


/**
 * Summary. Singleton class that handles the connection to the database and provides an interface to post and get data from the database.
 */
class DatabaseHandler
{
    private static $instance = null; ///< Holds the class instance
    private $dbconn; ///< The connection to the database

    /**
     * Summary. Default constructor.
     */
    private function __construct()
    {
        //        $this->dbconn = null;
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Summary. Gets an instance of DatabaseHandler class.
     * @return DatabaseHandler An instance of DatabaseHandler class.
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DatabaseHandler();
        }
        return self::$instance;
    }

    /**
     * Summary. Gets the requested posts from the database.
     * @param resource|false Result from pg_query.
     * @return Post[] Array of posts.
     */
    private function getPostsFromPgResult($result)
    {
        $posts = [];

        while ($row = pg_fetch_array($result)) {
            $posts[] = new Post($row['name'], $row['message'], $row['iplog'], $row['timelog']);
        }
        return $posts;
    }

    /**
     * Summary. Makes a query to find posts from searched author. Search is case insensitive.
     * @param String Author of searched posts.
     * @return Post[] Array of posts.
     */
    public function searchUserPosts($username)
    {
        $query = "SELECT * FROM dt167g.posts WHERE name ILIKE $1;";
        $result = pg_query_params($this->dbconn, $query, array($username));

        $posts = $this->getPostsFromPgResult($result);
        pg_free_result($result);

        return $posts;
    }

    /**
     * Summary. Makes a query to find posts containing searched keyword. Search is case insensitive.
     * @param String Keyword of searched posts.
     * @return Post[] Array of posts.
     */
    public function searchKeywordPosts($keyword)
    {
        $query = "SELECT * FROM dt167g.posts WHERE message ILIKE $1;";
        $result = pg_query_params($this->dbconn, $query, array("%{$keyword}%"));

        $posts = $this->getPostsFromPgResult($result);
        pg_free_result($result);

        return $posts;
    }

    public function checkUserCredentials($username, $password)
    {
        // Prepare a query for execution
        $result = pg_prepare(
            $this->dbconn,
            "user_check",
            'SELECT id FROM dt167g.users WHERE username = $1 AND userpassword = $2;'
        );

        $result = pg_execute($this->dbconn, "user_check", array($username, $password));

        return $result;
    }

    /**
     * Summary. Adds a post to the database.
     * @param array Array containing a guestbook post. Accepts normal arrays and associative arrays.
     */
    public function addPost(array $post)
    {
        //        if ($this->connect()) {

        $query = "INSERT INTO dt167g.messages (name, message, iplog, timelog)VALUES ($1, $2, $3, $4);";
        $result = pg_query_params($this->dbconn, $query, $post);

        //$this->disconnect();
        //        }
    }

    /**
     * Summary. Gets the guestbook posts from the database.
     * @return array Array of Post objects. If no connection to the database was established, null is returned.
     */
    public function getPosts()
    {
        //if ($this->connect()) {

        $query = 'SELECT * FROM dt167g.posts;';
        $result = pg_query($this->dbconn, $query);

        $posts = $this->getPostsFromPgResult($result);
        pg_free_result($result);

        return $posts;
        //}

        // If unable to connect
        return null;
    }

    public function likePost($userId, $postId)
    {
        if ($this->validUserId($userId)) {
            $data = array($postId, $userId);
            $query = "Insert into dt167g.likes (postId, userId)values($1, $2)";
            $result = pg_query_params($this->dbconn, $query, $data);
        }
    }

    public function query(string $query, array $params = []): bool
    {
        $this->result = empty($params)
            ? pg_query($this->dbconn, $query)
            : pg_query_params($this->dbconn, $query, $params);
        return $this->result !== false;
    }

    public function getFirstResult(): array
    {
        $row = pg_fetch_assoc($this->result);
        return $row ? $row : [];
    }

    public function getResult(): array
    {
        $rows = pg_fetch_all($this->result);
        return $rows ?? [];
    }

    /**
     * Checks that the caller has provided a valid user id
     * @param $userId
     * @return bool
     */
    private function validUserId($userId)
    {
        return $userId > 0;
    }

    /**
     * Summary. Establishes a connection to the database.
     * @return bool True if connection was successful.
     * False if no connection was established.
     */
    private function connect()
    {
        $this->dbconn = pg_connect(Config::getInstance()->getDbDsn());
        return $this->dbconn ? true : false;
    }

    /**
     * Summary. Disconnects from the database.
     * @return bool Returns true if disconnect was successful, otherwise false.
     */
    private function disconnect()
    {
        return pg_close($this->dbconn);
    }
}
