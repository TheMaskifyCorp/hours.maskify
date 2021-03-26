
<?php

//database config file

//rename this file to DBCONF.php, and enter database credentials
//IMPORTANT: verify DBCONF.php is in .gitignore
class DBCONF{
    const HOSTNAME = 'localhost';
    const DBNAME = 'maskify_hours';
    const USER = 'root';
    const PASSWORD = 'rootpassword';
    // NAMESPACE should be a valid UUID. You can use the default one, or
    // generate one here: https://www.uuidgenerator.net/
    const NAMESPACE = 'c416205f-49fa-4e90-91f7-e39a1fa0c4c0';
}
