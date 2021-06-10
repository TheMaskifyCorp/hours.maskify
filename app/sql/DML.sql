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
VALUES ('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'standaard Lorem Ipsum'),
       ('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.', 'Finibus Bonorum'),
       ('But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.', 'Vertaling door H. Rackham'),
       ('At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.', 'Sectie 1.10.33'),
       ('Lorem Ipsum komt uit de secties 1.10.32 en 1.10.33 van "de Finibus Bonorum et Malorum" (De uitersten van goed en kwaad) door Cicero, geschreven in 45 v.Chr. Dit boek is een verhandeling over de theorie der ethiek, erg populair tijdens de renaissance. De eerste regel van Lorem Ipsum, "Lorem ipsum dolor sit amet..", komt uit een zin in sectie 1.10.32.', 'Waar komt het vandaan?'),
       ('Om je wachtwoord aan te passen kun je contact opnemen met je manager, zodat het standaard wachtwoord kan worden ingesteld', 'Wachtwoord vergeten');
INSERT INTO `searchresults` (`SearchTerm`,`SolutionID`,`SearchTermCounter`)
VALUES ('lorem%20ipsum',1,0),
       ('finibus',2,0),
       ('vertaling',3,0),
       ('wachtwoord',6,3);