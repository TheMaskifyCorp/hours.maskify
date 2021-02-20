<?php


class Auth
{
    protected $db;
    protected $hash;
    protected $table = 'users';
    public $session = 'user';

    public function __construct(Database $db, Hash $hash)
    {
        $this->db = $db;
        $this->hash = $hash;
        $this->hash = $hash;
    }

    public function create(array $data) : bool
    {
        if(isset($data['password']))
        {
            $data['password'] = $this->hash->make($data['password']);
        }
        return $this->db->table($this->table)->insert($data);
    }
    public function signin($data)
    {
        $user = $this->db->table($this->table)->where('username','=',$data['username']);

        if($user->count())
        {
            $user = $user->first();
            if($this->hash->verify($data['password'], $user->password))
            {
                $this->setAuthSession($user->id);
                return true;
            }
        }
        return false;
    }
    public function check()
    {
        return isset($_SESSION[$this->session]);
    }
    public function signout()
    {
        unset($_SESSION[$this->session]);
    }
    protected function setAuthSession($id)
    {
        $_SESSION[$this->session] = $id;
    }
    public function create(array $data) : bool
    {
        if(isset($data['password']))
        {
            $data['password'] = $this->hash->make($data['password']);
        }
        return $this->db->table($this->table)->insert($data);
    }
    public function signin($data)
    {
        $user = $this->db->table($this->table)->where('username','=',$data['username']);

        if($user->count())
        {
            $user = $user->first();
            if($this->hash->verify($data['password'], $user->password))
            {
                $this->setAuthSession($user->id);
                return true;
            }
        }
        return false;
    }
    public function check()
    {
        return isset($_SESSION[$this->session]);
    }
    public function signout()
    {
        unset($_SESSION[$this->session]);
    }
    protected function setAuthSession($id)
    {
        $_SESSION[$this->session] = $id;
    }
}