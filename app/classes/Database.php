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
        unset($this->table);
        unset($this->stmt);
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

    /**
     * function to insert data in table. function must be called with update-values, and where-arrays.
     * Where-array structure = [$field, $operator, $value].
     * Example: ["EmployeeID","=",1]
     *
     *$database->table('tablename')->update([
     * 'field' => 'value',
     * 'field' => 'value',
     * 'field' => 'value'
     * ],[where-array],[where-array]);
     * @param array $data
     */
    public function update(array $data, ...$where) : bool
    {
        $keys = array_keys($data);
        $setValues = "";
        $whereString = "";
        $where = $this->deNestWhere($where);
        $values = [];
        $i = 0;
        foreach ($keys as $key){
            $setValues .= $key."= :$key ";
            if ($key != end($keys)) $setValues .= ",";
            $values[$key] = $data[$key];
        }
        foreach ($where as $here){
            if(is_array($here)){
                foreach($here as $her){
                    array_push( $where, $here );
                }
                break;
            }
            $values[$here[0]] = $here[2];
            $whereString .= "$here[0] $here[1] :$here[0]";

            if (  $here !== end($where) ) {
                $whereString .= " AND ";
            }
        }
        $sql = "UPDATE {$this->table} SET {$setValues} WHERE {$whereString}";

        $this->stmt = $this->pdo->prepare($sql);
        return $this->stmt->execute($values);

    }

    public function delete(array ...$where)
    {
        $whereString = " ";
        $values = [];
        $where = $this->deNestWhere($where);
        foreach ($where as $here){
            $field = $here[0];
            $placeholder = preg_replace("/\./","", $field);
            $operator = $here[1];
            $value = $here[2];
            if (strtolower($value) == "null"){
                $value = NULL;
            }
            if ($operator == '=' && $value == NULL){
                $operator = "IS";
            }
            if ($operator == '<>' && $value == NULL){
                $operator = "IS NOT";
            }
            $whereString .= "$field $operator :$placeholder";
            $values[$placeholder] = $value;
            if (  $here !== end($where) ) {
                $whereString .= " AND ";
            }
        }
        $sql = "DELETE FROM {$this->table} WHERE {$whereString}";
        $this->stmt = $this->pdo->prepare($sql);
        $response =  $this->stmt->execute($values);
        return ($response) ? true : $this->stmt->errorInfo();

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
     * Usage: $db->table('employees')->where(["EmployeeID",">",0],["FirstName","=","Rita"])->get();
     * Use for SELECT data from database in combination met ->get() of ->first();
     *
     * @param $field
     * @param $operator
     * @param $value
     * @return Database
     */
    public function where(array ...$where) : Database
    {

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
        $whereString = " ";
        $values = [];
        $where = $this->deNestWhere($where);

        foreach ($where as $here){

            $field = $here[0];
            $placeholder = preg_replace("/\./","", $field);
            $operator = $here[1];
            $value = $here[2];
            if (strtolower($value) == "null"){
                $value = NULL;
            }
            if ($operator == '=' && $value == NULL){
                $operator = "IS";
            }
            if ($operator == '<>' && $value == NULL){
                $operator = "IS NOT";
            }
            $whereString .= "$field $operator :$placeholder";
            $values[$placeholder] = $value;
            if (  $here !== end($where) ) {
                $whereString .= " AND ";
            }
        }
        $this->stmt = $this->pdo->prepare("SELECT $selection FROM $this->table $innerJoin WHERE $whereString");
        $this->stmt->execute($values);
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
        if ( !isset( $this->stmt) ) {
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

    /*
     * PRIVATE FUNCTIONS
     */

    private function deNestWhere(array $where) : array
    {
        $newArray = [];
        foreach ($where as $here) {
            if (is_array($here[0])) {
                foreach  ($here as $her) {
                    array_push($newArray, $her);
                }
            }else{
                array_push($newArray, $here);
            }
        }
        return $newArray;
    }
}