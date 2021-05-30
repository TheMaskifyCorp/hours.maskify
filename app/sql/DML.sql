/**
  * Dummy Data creation
  */

-- create employeetypes
INSERT INTO `employeetypes` (`FunctionTypeID`, `Description`)
VALUES (1, 'Medewerker'),
       (2, 'Manager'),
       (3, 'Beheerder');
-- create departmenttypes
INSERT INTO `departmenttypes` (`DepartmentID`, `Description`)
VALUES (1, 'Verkoop'),
       (2, 'Personeel'),
       (3, 'Productie'),
       (4, 'Financieën '),
       (5, 'Inkoop'),
       (6, 'Design');
INSERT INTO `faq` (`FAQContent`, `FAQTitle`)
VALUES ('the content of a specific solution 1', 'How to do 1'),
       ('the content of a specific solution 2', 'How to do 2'),
       ('the content of a specific solution 3', 'How to do 3'),
       ('the content of a specific solution 4', 'How to do 4'),
       ('the content of a specific solution 5', 'How to do 5'),
       ('the content of a specific solution 6', 'How to do 6');
INSERT INTO `searchresults` (`SearchTerm`,`SolutionID`,`SearchTermCounter`)
VALUES ('solution 1',1,0),
       ('solution 2',2,0),
       ('solution 3',3,0),
       ('solution 4',4,3);