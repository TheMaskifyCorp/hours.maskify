<?php

/**
 * Class Hash
 */
class Hash
{
    /**
     * @param $plain
     * @return false|string|null
     */
    public function make($plain)
    {
        return password_hash($plain, PASSWORD_BCRYPT,['cost' => 10]);
    }

    /**
     * @param $plain
     * @param $hashed
     * @return bool
     */
    public function verify($plain, $hashed)
    {
        return password_verify($plain, $hashed);
    }
}