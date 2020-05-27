<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: config.class.php
 ******************************************************************************/

/**
 * Summary. Represents a post in the guestbook.
 */
class Post
{
    private $name; ///< Author of the post.
    private $message; ///< Message of the post.
    private $iplog; ///< Post authors ip.
    private $timelog; ///< Timestamp when post was created.

    /**
     * Summary. Initializing constructor
     * Description. If post a new post is created $iplog and $timelog does not have to be supplied and will be generated. If a post is loaded from a database all parameters should be used.
     * @param string Name of the post author.
     * @param string The message of the guestbook post.
     * @param string The IP of the post author.
     * @param string Timestamp of when post was created.
     */
    public function __construct($name, $message, $iplog = null, $timelog = null)
    {
        $this->name = $name;
        $this->message = $message;

        $iplog ? $this->iplog = $iplog : $this->iplog = $_SERVER['REMOTE_ADDR'];
        $timelog ? $this->timelog = $timelog : $this->timelog = date("Y-m-d H:i:s");
    }
    /**
     * Summary. Gets post authors name.
     * @return string Authors name.
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Summary. Gets Message of the post.
     * @return string Message of the post.
     */
    public function getMessage()
    {
        return $this->message;
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
        return $this->timelog;
    }
    /**
     * Summary. Gets the post converted to an associative array.
     * @return array Associative array containing the post data.
     */
    public function toArray()
    {
        return [
            "name" => $this->name,
            "message" => $this->message,
            "iplog" => $this->iplog,
            "timelog" => $this->timelog
        ];
    }
}
