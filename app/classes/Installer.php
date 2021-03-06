<?php

class Installer
{
    protected $db;
    protected $firstNames = DummyData::firstNames;
    protected $lastNames = DummyData::lastNames;
    protected $streets = DummyData::streets;
    protected $cities = DummyData::cities;
    protected $employees = array();
    protected $dates = array();
    protected $namespace = "c416205f-49fa-4e90-91f7-e39a1fa0c4c0";
    protected $return = array();

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function installSQL($file)
    {
        $sql=file_get_contents($file);
        $this->db->query($sql);
        $this->return["Imported SQL-file: $file"] = "Success";
        return $this;
    }

    protected function insertRandomEmployee(){
        $Email = false;
        while(!$Email){
            $FirstName = $this->firstNames[array_rand($this->firstNames)];
            $LastName = $this->lastNames[array_rand($this->lastNames)];
            $Email = $FirstName.".".str_replace(' ', '', $LastName)."@maskify.nl";
            $Email = strtolower($Email);
            if($this->db->table('employees')->where('Email','=',$Email)->count()) {
                $Email = false;
            }
        }
        $PhoneNumber = "+316".rand(10000000,99999999);
        $Street = $this->streets[array_rand($this->streets)]." ".rand(1,200);
        $City = $this->cities[array_rand($this->cities)];
        $DateOfBirth = date("Y-m-d",rand(315532800,915148800));
        $PostalCode = rand(1000,9999).$this->randomLetter().$this->randomLetter();
        $DocumentNumberID = rand(10000000,99999999).$this->randomLetter().$this->randomLetter().rand(100,999);
        $DepartmentID = rand(1,6);

        $sql = "INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('$FirstName','$LastName','$Email','$PhoneNumber','$Street','$City','$DateOfBirth','$PostalCode','1','2000','$DocumentNumberID',NULL,'2021-01-01','2022-01-01','0');
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES ($DepartmentID,LAST_INSERT_ID());";
        $this->db->query($sql);
    }
    public function insertRandomHours(){
        $count = $this->db->table("employees")->where("EmployeeID",">","0")->count();
        $this->createDates();
        $i=1;
        while($i <= $count){
            foreach($this->dates as $date){
                $type=rand(1,3);
                $accArray = ["NULL","0","1"];
                $acc = $accArray[array_rand($accArray)];
                if ($acc=="NULL"){
                    $man="NULL";
                }else{
                    $emp = new Employee($i,$this->db);
                    $man = $emp->getManager();
                }
                $UUID = UUID::createRandomUUID($this->namespace);
                $qArray = [180,240,360,480];
                $q = $qArray[array_rand($qArray)];
                $sql= "INSERT INTO `employeehours`(`EmployeeHoursID`, `EmployeeID`, `AccordedByManager`, `DeclaratedDate`, `EmployeeHoursQuantityInMinutes`, `TypeOfHoursID`, `HoursAccorded`) 
                VALUES ('$UUID', $i, $man,'$date', $q, $type, $acc)";
                $this->db->query($sql);
            }
            $i++;
        }
        $this->return['Inserted hours for every Employee'] = "Success";
        return $this;
    }
    public function insertDuplicateEntries($num = 5)
    {
        $i = 0;
        while($i < $num)
        {
            $rand = $this->db->table("employeehours")->randomTuple();
            $UUID = UUID::createRandomUUID($this->namespace);
            if (is_null($rand->HoursAccorded)){
                $rand->HoursAccorded = "NULL";
                $rand->AccordedByManager = "NULL";
            }
            $sql= "INSERT INTO `employeehours`(`EmployeeHoursID`, `EmployeeID`, `AccordedByManager`, `DeclaratedDate`, `EmployeeHoursQuantityInMinutes`, `TypeOfHoursID`, `HoursAccorded`)
                VALUES ('$UUID', $rand->EmployeeID, $rand->AccordedByManager,'$rand->DeclaratedDate', $rand->EmployeeHoursQuantityInMinutes, $rand->TypeOfHoursID, $rand->HoursAccorded)";
/*            echo $sql."<br>".var_dump($rand)."<br>";*/
            $this->db->query($sql);
            $i++;
        }
        $this->return['Added duplicate hours for testing purposes'] = "Success";
        return $this;
    }

    public function createEmployees($num = 20)
    {
        $i = 1;
        while ($i <= $num) {
            $this->insertRandomEmployee();
            $i++;
        }
        $this->return["Added $num random Employees"] = "Success";
        return $this;
    }
    protected function randomLetter()
    {
        $int = rand(0,25);
        $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rand_letter = $a_z[$int];
        return $rand_letter;
    }
    protected function createDates()
    {
        $startDate = "2021-01-01";
        $endDate = "2021-02-28";
        $date = $startDate;
        while(strtotime($date) <= strtotime($endDate))
        {
            if (date("w",strtotime($date)) > 0 && date("w",strtotime($date)) < 6)
            {
                $this->dates[] = $date;
            }
            $date = date('Y-m-d',
                strtotime( $date . " +1 days"));
        }
    }
    public function returnStatus()
    {
        $status = json_encode($this->return);
        unset($this->return);
        return $status;
    }

    /**
     * @param string $hostname
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $namespace
     * @return string
     */
    public static function createDBCONF(string $hostname, string $database, string $username, string $password,string $namespace = 'c416205f-49fa-4e90-91f7-e39a1fa0c4c0') : string
    {
        if (gethostbyname($hostname . ".") == $hostname . ".") {
            return json_encode(array("Hostname <strong>$hostname</strong> not resolvable" => "Warning"));
        }
        try
        {
            $pdo = new PDO("mysql:host={$hostname};dbname={$database}",$username,$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
            return json_encode(array($e->getMessage() => "Warning"));
        }
        $filename = '../app/conf/DBCONF.php';
        $dbconf = "
<?php

//database config file

//rename this file to DBCONF.php, and enter database credentials
//IMPORTANT: verify DBCONF.php is in .gitignore
class DBCONF{
    const HOSTNAME = '$hostname';
    const DBNAME = '$database';
    const USER = '$username';
    const PASSWORD = '$password';
    // NAMESPACE should be a valid UUID. You can use the default one, or
    // generate one here: https://www.uuidgenerator.net/
    const NAMESPACE = '$namespace';
}
";
        file_put_contents($filename, $dbconf);
        chmod($filename, 00644);
        return json_encode(array("DBCONF.php created!" => "Succes"));
    }
}