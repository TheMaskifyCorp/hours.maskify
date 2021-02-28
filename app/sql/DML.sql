/**
  * Dummy Data creation
  */

-- create employeetypes
INSERT INTO `employeetypes` (`FunctionTypeID`, `Description`)
VALUES ("1", "Medewerker"),
       ("2", "Manager"),
       ("3", "Beheerder");
-- create departmenttypes
INSERT INTO `departmenttypes` (`DepartmentID`, `Description`)
VALUES ("1", "Verkoop"),
       ("2", "Personeel"),
       ("3", "Productie"),
       ("4", "Financieën "),
       ("5", "Inkoop"),
       ("6", "Design");
-- create TypeOfHoursTypes
INSERT INTO `typeofhourstypes` (`TypeOfHoursID`, `Description`)
VALUES ("1", "Worked hours"),
       ("2", "Holidays"),
       ("3", "Sick Leave");
-- create AddManagers
INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Sven','Muste','sven.muste@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (1,LAST_INSERT_ID());
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (2,LAST_INSERT_ID());
INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Gemma','Neeleman','gemma.neeleman@maskify.nl','+31703502591','Strandweg 3 B','Scheveningen','1983-05-28','2586JK','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (5,LAST_INSERT_ID());
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (6,LAST_INSERT_ID());
INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Cynthia','de Jong','cynthia.dejong@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (3,LAST_INSERT_ID());
INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`PayRate`,`DocumentNumberID`,`IDfile`,`StartOfContract`,`EndOfContract`,`OutOfContract`)
                VALUES('Jeroen','Rijkse','jeroen.rijkse@maskify.nl','+31612345678','lagelandenlaan 4','Groningen','1985-1-1','1234AB','3','3600','31827070UB215',NULL,'2020-10-01','2022-01-01','0');
                INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (4,LAST_INSERT_ID());
