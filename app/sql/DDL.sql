DROP TABLE IF EXISTS `employeehours`;
DROP TABLE IF EXISTS `departmentmemberlist`;
DROP TABLE IF EXISTS `logincredentials`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `employeetypes`;
DROP TABLE IF EXISTS `departmenttypes`;
DROP TABLE IF EXISTS `typeofhourstypes`;

CREATE TABLE employeetypes(
    FunctionTypeID INT(1) NOT NULL,
    Description VARCHAR(255),
    PRIMARY KEY(FunctionTypeID)
) ENGINE = INNODB;

CREATE TABLE departmenttypes(
    DepartmentID INT(11) NOT NULL,
    Description VARCHAR(255),
    PRIMARY KEY(DepartmentID)
) ENGINE = INNODB;

CREATE TABLE typeofhourstypes(
    TypeOfHoursID INT(4),
    Description VARCHAR(255),
    PRIMARY KEY(TypeOfHoursID)
) ENGINE = INNODB;

CREATE TABLE `employees`(
    EmployeeID INT(11) NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE ,
    PhoneNumber VARCHAR(20) NOT NULL,
    Street VARCHAR(100) NOT NULL,
    City VARCHAR(100) NOT NULL,
    DateOfBirth DATE NOT NULL,
    PostalCode VARCHAR(6) NOT NULL,
    FunctionTypeID INT(1) NOT NULL,
    PayRate int(5),
    DocumentNumberID VARCHAR(22),
    IDfile MEDIUMBLOB,
    StartOfContract DATE,
    EndOfContract DATE,
    OutOfContract BOOLEAN,
    PRIMARY KEY(EmployeeID),
    FOREIGN KEY(FunctionTypeID) REFERENCES employeetypes(FunctionTypeID)
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
      TypeOfHoursID INT(4) NOT NULL,
      HoursAccorded BOOL,
      PRIMARY KEY(EmployeeHoursID),
      FOREIGN KEY(EmployeeID) REFERENCES employees(EmployeeID),
      FOREIGN KEY(AccordedByManager) REFERENCES employees(EmployeeID),
      FOREIGN KEY(TypeOfHoursID) REFERENCES typeofhourstypes(TypeOfHoursID)
) ENGINE = INNODB;