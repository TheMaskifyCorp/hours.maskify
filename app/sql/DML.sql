/**
  * Dummy Data creation
  */

-- create EmployeeTypes
INSERT INTO `EmployeeTypes` (`FunctionTypeID`, `Description`)
VALUES ("1", "Medewerker"),
       ("2", "Manager"),
       ("3", "Beheerder");
-- create DepartmentTypes
INSERT INTO `DepartmentTypes` (`DepartmentID`, `Description`)
VALUES ("1", "Verkoop"),
       ("2", "Personeel"),
       ("3", "Productie"),
       ("4", "Financieën "),
       ("5", "Inkoop"),
       ("6", "Design");
-- create TypeOfHoursTypes
INSERT INTO `TypeOfHours` (`TypeOfHoursID`, `Description`)
VALUES ("1", "Worked hours"),
       ("2", "Holidays"),
       ("3", "Sick Leave");
-- create AddManagers
INSERT INTO `Employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Sven','Muste','sven.muste@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `DepartmentMemberList`(`DepartmentID`,`EmployeeID`) VALUES (1,LAST_INSERT_ID());
                INSERT INTO `DepartmentMemberList`(`DepartmentID`,`EmployeeID`) VALUES (2,LAST_INSERT_ID());
INSERT INTO `Employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Gemma','Neeleman','gemma.neeleman@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `DepartmentMemberList`(`DepartmentID`,`EmployeeID`) VALUES (5,LAST_INSERT_ID());
                INSERT INTO `DepartmentMemberList`(`DepartmentID`,`EmployeeID`) VALUES (6,LAST_INSERT_ID());
INSERT INTO `Employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Cythia','van Hoek','cynthia.vanhoek@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `DepartmentMemberList`(`DepartmentID`,`EmployeeID`) VALUES (3,LAST_INSERT_ID());
INSERT INTO `Employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Jeroen','Rijkse','jeroen.rijkse@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `DepartmentMemberList`(`DepartmentID`,`EmployeeID`) VALUES (4,LAST_INSERT_ID());