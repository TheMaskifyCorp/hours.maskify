<?php

class Database
{
    protected $host = DBCONF::HOSTNAME;
    protected $db = DBCONF::DBNAME;
    protected $username = DBCONF::USER;
    protected $password = DBCONF::PASSWORD;
    protected $pdo;
    protected $debug = false;
    protected $table;
    protected $row;
    protected $selection;
    protected $stmt;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
        try
        {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db}",$this->username,$this->password);
            if ($this->debug)
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            die($this->debug ? $e->getMessage() : '');
        }
    }
    public function query($sql)
    {
        $this->pdo->query($sql);
    }

    /**
     * @param $table
     * @return $this
     */
    public function table($table): Database
    {
        unset($this->selection);
        $this->table = $table;
        return $this;
    }

    public function row($row): Database
    {
            $this->row = $this->table.".".$row;
            return $this;
    }
    public function selection(array $tablerows): Database
    {
        $this->selection = $tablerows;
        return $this;
    }

    /**
     * function to insert data in table. function must be called like so:
     *
     *$database->table('tablename')->insert([
     * 'field' => 'value',
     * 'field' => 'value',
     * 'field' => 'value'
     * ]);
     * @param array $data
     * @return bool
     */

    public function insert(array $data) : bool
    {
        $keys = array_keys($data);
        $fields = '`' . implode('`, `',$keys) . '`';
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $this->stmt = $this->pdo->prepare($sql);
        return $this->stmt->execute($data);
    }

    /**
     * @param string $table
     * @param string $on
     * @return $this
     *
     * $db->table('users')->selection([
     * "users.username",
     * "roles.description"
     * ])->innerJoin("roles","users.role = roles.id")->get();
     */
    public function innerJoin(string $table, string $on)
    {
        $selection = implode(', ',$this->selection);
        $sql = "SELECT {$selection}
                FROM {$this->table}
                INNER JOIN {$table}
                ON {$on}";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();
        return $this;
    }

    /**
     * @param $field
     * @param $operator
     * @param $value
     * @return Database
     */
    public function where($field, $operator, $value) : Database
    {
        if(!isset($this->selection)) {
            $selection="*";
        } else {
            $selection = implode(', ',$this->selection);
        }
        $this->stmt = $this->pdo->prepare("SELECT {$selection} FROM {$this->table} WHERE {$field} {$operator} :value");
        $this->stmt->execute(['value' => $value]);
        return $this;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return $this->stmt->rowCount();
    }

    /**
     * @param $data
     * @return bool
     */
    public function exists($data) : bool
    {
        $field = array_keys($data)[0];
        return $this->where($field,'=', $data[$field])->count() ? true : false;
    }
    public function get()
    {
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function first()
    {
        return $this->get()[0];
    }
    public function clear(){
        unset($this->table);
        unset($this->row);
        unset($this->selection);
        unset($this->stmt);
        return $this;
    }
}