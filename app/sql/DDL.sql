DROP TABLE IF EXISTS `employeehours`;
DROP TABLE IF EXISTS `departmentmemberlist`;
DROP TABLE IF EXISTS `contracts`;
DROP TABLE IF EXISTS `logincredentials`;
DROP TABLE IF EXISTS `sickleave`;
DROP TABLE IF EXISTS `holidays`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `employeetypes`;
DROP TABLE IF EXISTS `departmenttypes`;
DROP TABLE IF EXISTS `searchresults`;
DROP TABLE IF EXISTS `faq`;


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
      HolidaysAccorded BOOLEAN,
      AccordedByManager INT(11),
      PRIMARY KEY(EmployeeID, HolidayStartDate),
      FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID),
      FOREIGN KEY(AccordedByManager) REFERENCES employees(EmployeeID)
) ENGINE = INNODB;

CREATE TABLE faq(
      SolutionID INT(11) NOT NULL AUTO_INCREMENT,
      FAQContent VARCHAR(5000),
      FAQTitle VARCHAR(200) NOT NULL,
      PRIMARY KEY(SolutionID)
) ENGINE = INNODB;

CREATE TABLE searchresults(
      SearchTerm VARCHAR(200) NOT NULL,
      SearchTermCounter INT(10) NOT NULL,
      SolutionID INT(11),
      PRIMARY KEY(SearchTerm),
      FOREIGN KEY(SolutionID) REFERENCES faq(SolutionID)
) ENGINE = INNODB;

DROP VIEW IF EXISTS managers;
CREATE VIEW managers AS
SELECT departmenttypes.Description as Department, departmentmemberlist.DepartmentID, CONCAT(FirstName, ' ',LastName) as Name, employees.EmployeeID
FROM employees
         INNER JOIN departmentmemberlist
                    ON employees.EmployeeID = departmentmemberlist.EmployeeID
         INNER JOIN departmenttypes
                    ON departmentmemberlist.DepartmentID = departmenttypes.DepartmentID
WHERE employees.FunctionTypeID > 1;
