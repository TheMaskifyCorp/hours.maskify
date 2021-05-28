<?php

class Database
{
    protected $pdo;
    protected $debug = false;
    protected $table;
    protected $row;
    protected $selection;
    protected $innerJoin;
    protected $stmt;
    protected $update;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
        try
        {
            $this->pdo = new PDO("mysql:host={$_ENV['HOSTNAME']};dbname={$_ENV['DATABASE']}",$_ENV['USERNAME'],$_ENV['PASSWORD']);
            if ($this->debug)
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            die($this->debug ? $e->getMessage() : json_encode(array($e->getMessage() => "Warning")));
        }
    }

    /**
     * @param string $sql
     * @return Database
     */
    public function query(string $sql) : Database
    {
        $this->pdo->query($sql);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function table($table): Database
    {
        unset($this->selection);
        unset($this->innerJoin);
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
     * @return mixed
     */
    public function randomTuple()
    {
        $sql = "SELECT * FROM $this->table ORDER BY RAND()";
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute();
        return $this->first();
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
     */

    public function insert(array $data)
    {
        $keys = array_keys($data);
        $fields = '`' . implode('`, `', $keys) . '`';
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";
        $this->stmt = $this->pdo->prepare($sql);
        $return = $this->stmt->execute($data);
        if ($return) {
            return true;
        } else return $this->stmt->errorInfo();
    }
    public function update(array $data,...$args)
    {
        $keys = array_keys($data);
        //$fields = '`' . implode('`, `', $keys) . '`';
        //$placeholders = ':' . implode(', :', $keys);
        $setValues = "";
        foreach ($keys as $key){
            $setValues .= $key."=:$key";
        }
        $where = "";
        foreach ($args as $arg){
            var_dump($args);
/*            $where .= "$arg[0] $arg[1] $arg[2]";
            if ( ! $arg === array_key_last($args)) $where .= " AND ";*/
        }
/*        $this->stmt = "UPDATE $this->table SET $setValues $where";
        return $this->stmt->execute();*/
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
        $on = $this->table.".".$on." = ".$table.".".$on;

        if(!empty($this->innerJoin)){
            $this->innerJoin .= " INNER JOIN $table ON $on";
        } else {
            $this->innerJoin = "INNER JOIN $table ON $on";
        }
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
        if (isset($this->update)){
            $this->stmt = $this->pdo->prepare("$this->update WHERE $field $operator :value");
            return $this->stmt->execute();
        }

        if(!isset($this->selection)) {
            $selection="*";
        } else {
            $selection = implode(', ',$this->selection);
        }
        if(!isset($this->innerJoin)) {
            $innerJoin="";
        } else {
            $innerJoin = $this->innerJoin;
        }
        if (strtolower($value) == "null"){
            $value = NULL;
        }
        if ($operator == '=' && $value == NULL){
            $operator = "IS";
        }
        if ($operator == '<>' && $value == NULL){
            $operator = "IS NOT";
        }
        $this->stmt = $this->pdo->prepare("SELECT $selection FROM $this->table $innerJoin WHERE $field $operator :value");
        $this->stmt->execute(['value' => $value]);
        return $this;
    }


    public function group($group){
        $this->group = "GROUP BY $group";
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
    public function get(): array
    {
        if (!$this->stmt) {
            $this->stmt = $this->pdo->prepare("SELECT * FROM $this->table");
            $this->stmt->execute();
        }
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function returnstmt()
    {
        if (!$this->stmt) {
            $this->stmt = $this->pdo->prepare("SELECT * FROM $this->table");
        }
        return $this->stmt;
    }
    public function first()
    {
        return $this->get()[0];
    }
    public function lastID()
    {
        return $this->pdo->lastInsertId();
    }
}