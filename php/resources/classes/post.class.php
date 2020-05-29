<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: post.class.php
 ******************************************************************************/

require_once __DIR__ . '/../functions/strings.php';
require_once __DIR__ . '/../functions/sql.php';

use ParagonIE\EasyDB\EasyDB;
use function Latitude\QueryBuilder\field;

/**
 * Post handles post data and operations such as CRUD
 */
class Post
{
    /**
     * @var EasyDB EasyDB instance to use for queries.
     */
    private EasyDB $db;
    /**
     * @var int Post id.
     */
    private int $id;
    /**
     * @var string Author of the post.
     */
    private $name = "";
    /**
     * @var string Message of the post.
     */
    private $message = "";
    /**
     * @var string Post authors ip.
     */
    private $iplog = "";
    /**
     * @var string Timestamp when post was created.
     */
    private $timelog = "";
    /**
     * @var string Error message for posting in guestbook
     */
    private string $error_message = "";

    /**
     * Summary. Initializing constructor
     * Description. If post a new post is created $iplog and $timelog does not have to be supplied and will be generated. If a post is loaded from a database all parameters should be used.
     * @param string Name of the post author.
     * @param string The message of the guestbook post.
     * @param string The IP of the post author.
     * @param string Timestamp of when post was created.
     */
    public function __construct(EasyDB $db = null)
    {
        $this->db = ($db != null) ? $db : getEasyDB();
    }
    /**
     * Fetch all posts in the database.
     *
     * Convenience method for fetchPosts without limit or offset
     */
    public static function fetchAll(EasyDB $db = null)
    {
        return static::fetchPosts(0, 0, $db);
    }
    /**
     * Returns an array of posts from the database.
     */
    public static function fetchPosts(int $lim = 0, int $off = 0, EasyDB $db = null)
    {
        $factory = makeQueryFactory();
        $query = $factory
            ->select('*')
            ->from('dt167g.posts')
            ->limit($lim > 0 ? $lim : null) // LIMIT 0 will return 0 rows
            ->offset($off)
            ->compile();
        if ($db == null) {
            $db = getEasyDB();
        }
        $posts = [];
        foreach ($db->run($query->sql()) as $row) {
            $posts[] = static::fromRow($db, $row);
        }
        return $posts;
    }

    private static function postError(string $error_msg, EasyDB $db): Post
    {
        $post = new Post($db);
        $post->setError($error_msg);
        return $post;
    }

    private static function fromRow(EasyDB $db, array $row): Post
    {
        $post = new Post($db);
        $post->id = (int) $row['id'];
        $post->setName($row['name']);
        $post->setMessage($row['message']);
        $post->setIplog($row['iplog']);
        $post->setTimelog($row['timelog']);
        return $post;
    }

    public static function fromForm(string $name, string $message): Post
    {
        $db = getEasyDB();
        $post = new Post($db);
        $post->setName($name);
        $post->setMessage($message);
        $post->setIplog($_SERVER['REMOTE_ADDR']);
        $post->setTimelog(date("Y-m-d H:i:s"));
        return $post;
    }

    public static function fromUsername(string $username)
    {
        $db = getEasyDB();
        $query = "SELECT * FROM dt167g.posts WHERE name ILIKE :1;";
        $params = [
            ':1' => $username
        ];

        return $db->safeQuery($query, $params, \PDO::FETCH_ASSOC, false, false);
    }

    public static function fromKeyword(string $keyword)
    {
        $db = getEasyDB();
        $query = "SELECT * FROM dt167g.posts WHERE message ILIKE :1;";
        $params = [
            ':1' => "%{$keyword}%"
        ];

        return $db->safeQuery($query, $params, \PDO::FETCH_ASSOC, false, false);
    }

    private function setName(string $name): void
    {
        $this->name = $name;
    }

    private function setMessage(string $message): void
    {
        $this->message = $message;
    }

    private function setIplog(string $iplog): void
    {
        $this->iplog = $iplog;
    }

    private function setTimelog(string $timelog): void
    {
        $this->timelog = $timelog;
    }

    public function save(): bool
    {
        if (!Member::loggedIn()) {
            $this->setError("Must be logged in");
            return false;
        }
        if (empty(trim($this->message))) {
            $this->setError("Must enter a message");
            return false;
        }
        $this->clearError();
        return $this->insert($this->db);
    }

    private function insert(EasyDB $db = null): bool
    {
        if ($db == null) {
            $db = getEasyDB();
        }

        try {
            $this->id = $db->insertGet('dt167g.posts', [
                'name' => $this->name,
                'message' => $this->message,
                'iplog' => $_SERVER['REMOTE_ADDR']
            ], 'id');
            $this->clearError();
            return true;
        } catch (\Exception $e) { // @codeCoverageIgnoreStart
            $this->setError($e->getMessage());
            $this->id = -1;
            return false;
        } // @codeCoverageIgnoreEnd
    }

    public function setError(string $msg)
    {
        $this->error_message = $msg;
    }

    public function isSetError(): bool
    {
        return !empty($this->error_message);
    }

    public function getErrorMessage(): string
    {
        return $this->error_message;
    }

    public function clearError(): void
    {
        $this->error_message = "";
    }

    /**
     * Summary. Gets post id.
     * @return int Post id.
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Summary. Gets post authors name.
     * @return string Authors name.
     */
    public function getName()
    {
        return escape($this->name);
    }
    /**
     * Summary. Gets Message of the post.
     * @return string Message of the post.
     */
    public function getMessage()
    {
        return escape($this->message);
    }
    /**
     * Summary. Gets IP of the post author.
     * @return string IP of the post author.
     */
    public function getIplog()
    {
        return $this->iplog;
    }
    /**
     * Summary. Gets timestamp of when the post was created.
     * @return string Timestamp of when the post was created.
     */
    public function getTimelog()
    {
        return \substr($this->timelog, 0, 19);
    }

    public static function setRating(Int $post_id, Int $user_id, String $rating_action): void
    {
        $db = getEasyDB();
        $query = "INSERT INTO dt167g.likes (postid, userid, rating_action) VALUES (:1, :2, :3) ON CONFLICT (postid, userid) DO UPDATE SET rating_action=:3";
        $params = [
            ':1' => $post_id,
            ':2' => $user_id,
            ':3' => $rating_action
        ];
        $db->safeQuery($query, $params);
    }

    public static function unsetRating(Int $post_id, Int $user_id): void
    {
        $db = getEasyDB();
        $query = "DELETE FROM dt167g.likes WHERE postid = :1 AND userid = :2;";
        $params = [
            ':1' => $post_id,
            ':2' => $user_id,
        ];
        $db->safeQuery($query, $params);
    }

    public static function getRatingCount(Int $post_id, String $rating_action): int
    {
        $db = getEasyDB();

        $query = "SELECT COUNT(*) FROM dt167g.likes WHERE postid = :1 AND rating_action = :2;";
        $params = [
            ':1' => $post_id,
            ':2' => $rating_action
        ];

        $result = $db->safeQuery($query, $params);

        return $result[0]['count'];
    }

    public static function isRatedByUser(Int $post_id, Int $user_id, String $rating_action): bool
    {
        $db = getEasyDB();
        $query = "SELECT COUNT(*) FROM dt167g.likes WHERE postid = :1 AND userid = :2 AND rating_action = :3;";
        $params = [
            ':1' => $post_id,
            ':2' => $user_id,
            ':3' => $rating_action
        ];

        return $db->single($query, $params) != false;
    }

    public static function deletePost(Int $post_id): int
    {
        $db = getEasyDB();
        $query = "DELETE FROM dt167g.posts WHERE id = :1;";
        $params = [
            ':1' => $post_id,
        ];
        return $db->safeQuery($query, $params, \PDO::FETCH_NUM, true, false);
    }
}
