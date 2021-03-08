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
        if($this->db->table('employees')->where('Email','=',$data['username'])->count() > 0) {
            $user = $this->db->table('employees')->where('Email','=',$data['username'])->first();
            $employee = new Employee($user->EmployeeID);
            $password = $employee->getPassword();

            if ($this->hash->verify($data['password'], $password)) {
                $this->setAuthSession($user->EmployeeID);
                return true;
            }
        }
        return false;
    }
}