<?php


class Auth
{
    protected $db;
    protected $hash;
    public $session = 'employee';


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
        unset($_SESSION['manager']);
    }
    protected function setAuthSession($id, $manager = false)
    {
        $_SESSION[$this->session] = $id;
        $_SESSION['manager'] = $manager;
    }
    public function create(array $data) : bool
    {
        if(isset($data['password']))
        {
            $data['password'] = $this->hash->make($data['password']);
        }
        return $this->db->table('logincredentials')->insert($data);
    }
    public function signin($data): bool
    {
        if($this->db->table('employees')->where(['Email','=',$data['username'] ])->count() > 0) {
            $user = $this->db->table('employees')->where(['Email','=',$data['username']])->first();
            $employee = new Employee($user->EmployeeID);
            $password = $employee->getPassword();
            if ($user->FunctionTypeID == 2) {
                $manager = true;
            } else $manager = false ;
            if ($this->hash->verify($data['password'], $password)) {
                $this->setAuthSession($user->EmployeeID, $manager);
                return true;
            }
        }
        return false;
    }
}