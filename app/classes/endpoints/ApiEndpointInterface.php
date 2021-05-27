<?php

namespace API;

interface ApiEndpointInterface
{
    public function get (array $body) : array;
    public function put (array $body) : array;
    public function post (array $body) : array;
    public function delete (array $body) : array;
}