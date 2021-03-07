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
