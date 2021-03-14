<?php

class Installer
{
    protected $db;
    protected $hashedPassword = '$2y$10$.ucVmlERZRShNPvIoPP3..1ydWWHMn.kjg1KDJbr/1g8xU.Ke1I.K';
    protected $firstNames = DummyData::firstNames;
    protected $lastNames = DummyData::lastNames;
    protected $streets = DummyData::streets;
    protected $cities = DummyData::cities;
    protected $payrate = DummyData::payrate;
    protected $contracthours = DummyData::contracthours;
    protected $sickleave = DummyData::sickLeaveReason;

    protected $dates = array();
    protected $namespace = "c416205f-49fa-4e90-91f7-e39a1fa0c4c0";
    protected $return = array();

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    public function installSQL($file) : Installer
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
        $Street = $this->streets[array_rand($this->streets)];
        $HouseNumber = rand(1,200);
        $City = $this->cities[array_rand($this->cities)];
        $DateOfBirth = date("Y-m-d",rand(315532800,915148800));
        $PostalCode = rand(1000,9999).$this->randomLetter().$this->randomLetter();
        $DocumentNumberID = rand(10000000,99999999).$this->randomLetter().$this->randomLetter().rand(100,999);
        $DepartmentID = rand(1,6);
        $PayRate = $this->payrate[array_rand($this->payrate)];
        $ContractHours = $this->contracthours[array_rand($this->contracthours)];
        $password = $this->hashedPassword;
        $sql = "INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`HouseNumber`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`DocumentNumberID`)
                VALUES('$FirstName','$LastName','$Email','$PhoneNumber','$Street','$HouseNumber','$City','$DateOfBirth','$PostalCode','1','$DocumentNumberID');
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES ($DepartmentID,LAST_INSERT_ID());
                INSERT INTO `contracts`(EmployeeID, ContractStartDate, ContractEndDate, WeeklyHours, PayRate) VALUES (LAST_INSERT_ID(),'2020-09-01','2021-09-01',$ContractHours,$PayRate);
                INSERT INTO `logincredentials`(EmployeeID, Password) VALUES (LAST_INSERT_ID(),'$password');
                ";
        $this->db->query($sql);
    }

    public function createEmployees($num = 20) : Installer
    {
        $i = 1;
        while ($i <= $num) {
            $this->insertRandomEmployee();
            $i++;
        }
        $this->return["Added $num random Employees"] = "Success";
        return $this;
    }

    public function insertRandomHours() : Installer
    {
        $count = $this->db->table("employees")->where("EmployeeID",">","0")->count();
        $this->createDates();
        $i=1;
        while($i <= $count){
            foreach($this->dates as $date){
                $chance = rand(1,10);
                if ($chance == 1){
                    $accArray = ["NULL","0","1"];
                    $acc = $accArray[array_rand($accArray)];
                    if ($acc=="NULL"){
                        $man="NULL";
                    }else{
                        $emp = new Employee($i);
                        $man = $emp->getManager();
                    }
                    $UUID = UUID::createRandomUUID($this->namespace);
                    $qArray = [60,90,120,180];
                    $q = $qArray[array_rand($qArray)];
                    $sql= "INSERT INTO `employeehours`(`EmployeeHoursID`, `EmployeeID`, `AccordedByManager`, `DeclaratedDate`, `EmployeeHoursQuantityInMinutes`, `HoursAccorded`) 
                    VALUES ('$UUID', $i, $man,'$date', $q, $acc)";
                    $this->db->query($sql);
                }
            }
            $i++;
        }
        $this->return['Inserted hours for every Employee'] = "Success";
        return $this;
    }
    public function insertDuplicateEntries($num = 5) : Installer
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
            $sql= "INSERT INTO `employeehours`(`EmployeeHoursID`, `EmployeeID`, `AccordedByManager`, `DeclaratedDate`, `EmployeeHoursQuantityInMinutes`, `HoursAccorded`)
                VALUES ('$UUID', $rand->EmployeeID, $rand->AccordedByManager,'$rand->DeclaratedDate', $rand->EmployeeHoursQuantityInMinutes, $rand->HoursAccorded)";
/*            echo $sql."<br>".var_dump($rand)."<br>";*/
            $this->db->query($sql);
            $i++;
        }
        $this->return['Added duplicate hours for testing purposes'] = "Success";
        return $this;
    }
    public function createRandomSickLeave($amount = 20) : Installer
    {
        if(empty($this->dates))
        {
           $this->createDates();
        }
        $numEmp = $this->db->table('employees')->where('EmployeeID','>',0)->count();
        $i = 0;
        while($i<$amount) {
            $desc = $this->sickleave[array_rand($this->sickleave)];
            $duration = rand(0, 5);
            $startDate = $this->dates[array_rand($this->dates)];
            $endDate = date('Y-m-d',
                strtotime($startDate . " +$duration days"));
            $emp = new Employee(rand(1, $numEmp));
            $manager = $emp->getManager();

            $this->db->table('sickleave')->insert(['EmployeeID' => $emp->EmployeeID,
                'FirstSickDay' => $startDate,
                'LastSickDay' => $endDate,
                'AccordedByManager' => $manager,
                'Description' => $desc]);
            $i++;
        }
        $this->return['Added random sickness for random employee\'s'] = "Success";
        return $this;
    }

    protected function randomLetter() : string
    {
        $int = rand(0,25);
        $a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return $a_z[$int];
    }
    protected function createDates()
    {
        $startDate = "2020-09-01";
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
            die(json_encode(array($e->getMessage() => "Warning")));
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