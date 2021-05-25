<?php

namespace API;

interface ApiEndpointInterface
{
    public function get ($department, $itemID) : array;
    public function put () : array;
    public function post () : array;
    public function delete () : array;
    public function validate () : boolean;
}