DROP TABLE IF EXISTS `EmployeeHours`;
DROP TABLE IF EXISTS `DepartmentMemberList`;
DROP TABLE IF EXISTS `LoginCredentials`;
DROP TABLE IF EXISTS `Employees`;
DROP TABLE IF EXISTS `EmployeeTypes`;
DROP TABLE IF EXISTS `DepartmentTypes`;
DROP TABLE IF EXISTS `TypeOfHours`;

CREATE TABLE EmployeeTypes(
    FunctionTypeID INT(1) NOT NULL,
    Description VARCHAR(255),
    PRIMARY KEY(FunctionTypeID)
) ENGINE = INNODB;

CREATE TABLE DepartmentTypes(
    DepartmentID INT(11) NOT NULL,
    Description VARCHAR(255),
    PRIMARY KEY(DepartmentID)
) ENGINE = INNODB;

CREATE TABLE TypeOfHours(
    TypeOfHoursID INT(4),
    Description VARCHAR(255),
    PRIMARY KEY(TypeOfHoursID)
) ENGINE = INNODB;

CREATE TABLE `Employees`(
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
    FOREIGN KEY(FunctionTypeID) REFERENCES EmployeeTypes(FunctionTypeID)
) ENGINE = INNODB;

CREATE TABLE LoginCredentials(
     EmployeeID INT(11) NOT NULL,
     Password VARCHAR(255) NOT NULL,
     PRIMARY KEY(EmployeeID),
     FOREIGN KEY(EmployeeID) REFERENCES Employees(EmployeeID)
) ENGINE = INNODB;

CREATE TABLE DepartmentMemberList(
     EmployeeID INT(11) NOT NULL,
     DepartmentID INT(11) NOT NULL,
     PRIMARY KEY(EmployeeID, DepartmentID),
     FOREIGN KEY(EmployeeID) REFERENCES Employees(EmployeeID),
     FOREIGN KEY(DepartmentID) REFERENCES DepartmentTypes(DepartmentID)
) ENGINE = INNODB;

CREATE TABLE EmployeeHours(
      EmployeeHoursID VARCHAR(36) NOT NULL,
      EmployeeID INT(11) NOT NULL,
      AccordedByManager INT(11),
      DeclaratedDate DATE NOT NULL ,
      EmployeeHoursQuantityInMinutes INT(4) NOT NULL,
      TypeOfHoursID INT(4) NOT NULL,
      HoursAccorded BOOL,
      PRIMARY KEY(EmployeeHoursID),
      FOREIGN KEY(EmployeeID) REFERENCES Employees(EmployeeID),
      FOREIGN KEY(TypeOfHoursID) REFERENCES TypeOfHours(TypeOfHoursID)
) ENGINE = INNODB;