-- create AddManagers
INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`HouseNumber`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`DocumentNumberID`)
VALUES('Sven','Muste','sven.muste@maskify.nl','+31612345678','lagelandenlaan','4','Groningen','1985-1-1','1234AB','3','31827070UB215');
INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (1,LAST_INSERT_ID());
INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (2,LAST_INSERT_ID());
INSERT INTO `contracts`(EmployeeID, ContractStartDate, ContractEndDate, WeeklyHours, PayRate) VALUES (LAST_INSERT_ID(),'2020-09-01','2100-01-01',40,3600);
INSERT INTO `logincredentials`(EmployeeID, Password) VALUES (LAST_INSERT_ID(),'$2y$10$.ucVmlERZRShNPvIoPP3..1ydWWHMn.kjg1KDJbr/1g8xU.Ke1I.K');

INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`HouseNumber`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`DocumentNumberID`)
VALUES('Gemma','Neeleman','gemma.neeleman@maskify.nl','+31703502591','Strandweg','3 B','Scheveningen','1983-05-28','2586JK','3','31827070UB215');
INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (5,LAST_INSERT_ID());
INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (6,LAST_INSERT_ID());
INSERT INTO `contracts`(EmployeeID, ContractStartDate, ContractEndDate, WeeklyHours, PayRate) VALUES (LAST_INSERT_ID(),'2020-09-01','2100-01-01',40,3600);
INSERT INTO `logincredentials`(EmployeeID, Password) VALUES (LAST_INSERT_ID(),'$2y$10$.ucVmlERZRShNPvIoPP3..1ydWWHMn.kjg1KDJbr/1g8xU.Ke1I.K');

INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`HouseNumber`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`DocumentNumberID`)
VALUES('Cynthia','de Jong','cynthia.dejong@maskify.nl','+31612345678','lagelandenlaan',' 4','Groningen','1985-1-1','1234AB','3','31827070UB215');
INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (3,LAST_INSERT_ID());
INSERT INTO `contracts`(EmployeeID, ContractStartDate, ContractEndDate, WeeklyHours, PayRate) VALUES (LAST_INSERT_ID(),'2020-09-01','2100-01-01',40,3600);
INSERT INTO `logincredentials`(EmployeeID, Password) VALUES (LAST_INSERT_ID(),'$2y$10$.ucVmlERZRShNPvIoPP3..1ydWWHMn.kjg1KDJbr/1g8xU.Ke1I.K');

INSERT INTO `employees`(`FirstName`,`LastName`,`Email`,`PhoneNumber`,`Street`,`HouseNumber`,`City`,`DateOfBirth`,`PostalCode`,`FunctionTypeID`,`DocumentNumberID`)
VALUES('Jeroen','Rijkse','jeroen.rijkse@maskify.nl','+31612345678','lagelandenlaan','4','Groningen','1985-1-1','1234AB','3','31827070UB215');
INSERT INTO `departmentmemberlist`(`DepartmentID`,`EmployeeID`) VALUES (4,LAST_INSERT_ID());
INSERT INTO `contracts`(EmployeeID, ContractStartDate, ContractEndDate, WeeklyHours, PayRate) VALUES (LAST_INSERT_ID(),'2020-09-01','2100-01-01',40,3600);
INSERT INTO `logincredentials`(EmployeeID, Password) VALUES (LAST_INSERT_ID(),'$2y$10$.ucVmlERZRShNPvIoPP3..1ydWWHMn.kjg1KDJbr/1g8xU.Ke1I.K');