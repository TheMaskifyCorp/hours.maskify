<?php


class Auth
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function build()
    {
        return $this->db->query("
            CREATE TABLE IF NOT EXISTS users
            (id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(200) NOT NULL UNIQUE,
            username VARCHAR(20) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL)
        ");
    }
}