DROP TABLE IF EXISTS `employeehours`;
DROP TABLE IF EXISTS `departmentmemberlist`;
DROP TABLE IF EXISTS `contracts`;
DROP TABLE IF EXISTS `logincredentials`;
DROP TABLE IF EXISTS `sickleave`;
DROP TABLE IF EXISTS `holidays`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `employeetypes`;
DROP TABLE IF EXISTS `departmenttypes`;

CREATE TABLE employeetypes(
    FunctionTypeID INT(3) NOT NULL,
    Description VARCHAR(255),
    PRIMARY KEY(FunctionTypeID)
) ENGINE = INNODB;

CREATE TABLE departmenttypes(
    DepartmentID INT(3) NOT NULL,
    Description VARCHAR(255),
    PRIMARY KEY(DepartmentID)
) ENGINE = INNODB;

CREATE TABLE `employees`(
    EmployeeID INT(11) NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE ,
    PhoneNumber VARCHAR(20) NOT NULL,
    Street VARCHAR(100) NOT NULL,
    HouseNumber VARCHAR(10) NOT NULL,
    City VARCHAR(100) NOT NULL,
    PostalCode VARCHAR(6) NOT NULL,
    DateOfBirth DATE NOT NULL,
    FunctionTypeID INT(1) NOT NULL,
    DocumentNumberID VARCHAR(22),
    PRIMARY KEY(EmployeeID),
    FOREIGN KEY(FunctionTypeID) REFERENCES employeetypes(FunctionTypeID)
) ENGINE = INNODB;

CREATE TABLE contracts(
     EmployeeID INT(11) NOT NULL,
     ContractStartDate DATE NOT NULL,
     ContractEndDate DATE,
     WeeklyHours INT(4) NOT NULL,
     PayRate int(5),
     PRIMARY KEY(EmployeeID, ContractStartDate),
     FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID)
) ENGINE = INNODB;

CREATE TABLE logincredentials(
     EmployeeID INT(11) NOT NULL,
     Password VARCHAR(255) NOT NULL,
     PRIMARY KEY(EmployeeID),
     FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID)
) ENGINE = INNODB;

CREATE TABLE departmentmemberlist(
     EmployeeID INT(11) NOT NULL,
     DepartmentID INT(11) NOT NULL,
     PRIMARY KEY(EmployeeID, DepartmentID),
     FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID),
     FOREIGN KEY(DepartmentID) REFERENCES departmenttypes(DepartmentID)
) ENGINE = INNODB;

CREATE TABLE employeehours(
      EmployeeHoursID VARCHAR(36) NOT NULL,
      EmployeeID INT(11) NOT NULL,
      AccordedByManager INT(11),
      DeclaratedDate DATE NOT NULL ,
      EmployeeHoursQuantityInMinutes INT(4) NOT NULL,
      HoursAccorded BOOLEAN,
      PRIMARY KEY(EmployeeHoursID),
      FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID),
      FOREIGN KEY(AccordedByManager) REFERENCES employees(EmployeeID)
) ENGINE = INNODB;

CREATE TABLE sickleave(
     EmployeeID INT(11) NOT NULL,
     FirstSickDay DATE NOT NULL,
     LastSickDay DATE,
     AccordedByManager INT(11) NOT NULL,
     Description TEXT NOT NULL,
     PRIMARY KEY(EmployeeID, FirstSickDay),
     FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID),
     FOREIGN KEY(AccordedByManager) REFERENCES employees(EmployeeID)
) ENGINE = INNODB;

CREATE TABLE holidays(
      EmployeeID INT(11) NOT NULL,
      HolidayStartDate DATE NOT NULL,
      HolidayEndDate DATE NOT NULL,
      TotalHoursInMinutes INT(4) NOT NULL,
      Accorded BOOLEAN,
      AccordedByManager INT(11),
      PRIMARY KEY(EmployeeID, HolidayStartDate),
      FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID),
      FOREIGN KEY(AccordedByManager) REFERENCES employees(EmployeeID)
) ENGINE = INNODB;

/*STORED PROCEDURES*/

/*CREATE PROCEDURE FOR LOGIN*/
DROP PROCEDURE IF EXISTS MatchEmployeeIDToFirstNameLastName;
CREATE PROCEDURE MatchEmployeeIDToFirstNameLastName(
    IN Fname varchar(10),
    IN Lname varchar(10),
    OUT ID INT(1)
)
BEGIN
    SELECT
        employees.FirstName AS firstname,
        employees.LastName AS lastname,
        employees.EmployeeID AS ID
    FROM employees

    WHERE Fname=firstname AND Lname=lastname;
END;

/*CREATE PROCEDURE VERLONING*/

DROP PROCEDURE IF EXISTS verloning;
CREATE PROCEDURE verloning(
    IN Fname varchar(50),
    IN Lname varchar(50),
    IN startd DATE,
    IN endd DATE,
    OUT Month_name varchar(50),
    OUT ID INT(3)
)
BEGIN
    SELECT DISTINCT
        employees.FirstName AS firstname,
        employees.LastName AS lastname,
        employees.EmployeeID AS ID,
        contracts.PayRate,
        employeehours.EmployeeHoursQuantityInMinutes,
        (employeehours.EmployeeHoursQuantityInMinutes)/60 AS declaratedhours,
        SUM(employeehours.EmployeeHoursQuantityInMinutes)/60 Totalhours,
        startd AS Startdate,
        endd AS Enddate,
        employeehours.DeclaratedDate,
        MONTHNAME(employeehours.DeclaratedDate) AS Month_name
    FROM
        employees, employeehours, contracts
    WHERE Fname = firstname
      AND Lname = lastname
      AND employeehours.HoursAccorded = 1
      AND contracts.EmployeeID = employeehours.EmployeeID
      AND employees.EmployeeID = employeehours.EmployeeID
      AND employees.EmployeeID = contracts.EmployeeID
      AND employeehours.DeclaratedDate BETWEEN startd AND endd
    GROUP BY employeehours.DeclaratedDate WITH ROLLUP;
END;

/*CREATE PROCEDURE TO MATCH EMAIL AND PASSWORD*/

DROP PROCEDURE IF EXISTS MatchEmailToID;
CREATE PROCEDURE MatchEmailToID(
    IN Email varchar(50),
    IN Paswinp varchar(255),
    OUT EmployeeID INT(3)
)
BEGIN
    SELECT
        employees.Email AS Emailemp,
        employees.EmployeeID AS EmployeeID,
        logincredentials.EmployeeID AS ID,
        logincredentials.Password AS Pasw
    FROM employees
             LEFT JOIN logincredentials ON employees.EmployeeID = logincredentials.EmployeeID
    WHERE employees.Email = Email AND Paswinp = logincredentials.Password;
END;

/*CREATE PROCEDURE FOR ALL ACCORDED HOURS*/

DROP PROCEDURE IF EXISTS AccordedHoursBetween;
CREATE PROCEDURE AccordedHoursBetween(
    IN ID INT(3),
    IN startdate DATE,
    IN enddate DATE,
    OUT Monthname varchar(50)
)
BEGIN
    SELECT
        employeehours.EmployeeID,
        employeehours.DeclaratedDate,
        SUM(employeehours.EmployeeHoursQuantityInMinutes)/60 TotalWorkedHours,
        MONTHNAME(employeehours.DeclaratedDate) AS Monthname
    FROM employeehours
    WHERE employeehours.EmployeeID = ID
      AND employeehours.HoursAccorded = 1
      AND employeehours.DeclaratedDate BETWEEN startdate AND enddate;
END;

/*VIEWS*/

DROP VIEW IF EXISTS managers;
CREATE VIEW managers AS
SELECT departmenttypes.Description as Department, departmentmemberlist.DepartmentID, CONCAT(FirstName, ' ',LastName) as Name, employees.EmployeeID
FROM employees
         INNER JOIN departmentmemberlist
                    ON employees.EmployeeID = departmentmemberlist.EmployeeID
         INNER JOIN departmenttypes
                    ON departmentmemberlist.DepartmentID = departmenttypes.DepartmentID
WHERE employees.FunctionTypeID > 1;