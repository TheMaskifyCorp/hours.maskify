<?php

namespace API;

interface ApiEndpointInterface
{
    public function get (array $body, array $params) :array;
    public function put (array $body, array $params) :array;
    public function post (array $body, array $params) :array;
    public function delete (array $body, array $params) :array;
}