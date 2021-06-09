<?php


class Auth
{
    protected $db;
    protected $hash;
    public $session = 'employee';


    /**
     * Auth constructor.
     * @param Database $db
     * @param Hash $hash
     */
    public function __construct(Database $db, Hash $hash)
    {
        $this->db = $db;
        $this->hash = $hash;
        $this->hash = $hash;
    }

    /**
     * @return bool
     */
    public function check()
    {
        return isset($_SESSION[$this->session]);
    }

    public function signout()
    {
        unset($_SESSION[$this->session]);
        unset($_SESSION['manager']);
    }

    /**
     * @param $id
     * @param false $manager
     */
    protected function setAuthSession($id, $manager = false)
    {
        $_SESSION[$this->session] = $id;
        $_SESSION['manager'] = $manager;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data) : bool
    {
        if(isset($data['password']))
        {
            $data['password'] = $this->hash->make($data['password']);
        }
        return $this->db->table('logincredentials')->insert($data);
    }

    /**
     * @param $data
     * @return bool
     */
    public function signin($data): bool
    {
        if($this->db->table('employees')->where(['Email','=',$data['username'] ])->count() > 0) {
            $user = $this->db->table('employees')->where(['Email','=',$data['username']])->first();
            $employee = new Employee($user->EmployeeID);
            $password = $employee->getPassword();
            if ($user->FunctionTypeID > 1) {
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