<?php

namespace API;

class API
{
    protected object $db;
    protected string $endpoint;
    protected string $request;
    protected array $body;
    protected array $params;
    protected int $requesterID;
    protected bool $manager;

    public function __construct($JWT)
    {
        $decoded = \Firebase\JWT\JWT::decode($JWT,$_ENV['JWTSECRET'], ['HS256']);

        $this->db = new \Database;

        $this->manager = $decoded->manager;
        $this->requesterID = $decoded->eid;

    }
    public function endpoint(string $endpoint) : self
    {
        $this->endpoint = $endpoint;
        return $this;
    }
    public function request(string $request) : self
    {
        $this->request = $request;
        return $this;
    }
    public function body(array $body)
    {
        $this->body = $body;
        return $this;
    }
    public function params (array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function execute()
    {
        $endpoint = new $this->endpoint($this->requesterID,$this->manager);
        return $endpoint->{$this->request}($this->body, $this->params);
    }
}