<?php
require_once 'app/init.php';
/**
 * @var object $auth
 * @var object $db
 */

/*var_dump(
    $db->table('users')->selection([
    "users.username",
    "roles.description"
])->innerJoin("roles","users.role = roles.id")->get()
);*/

$install = new Installer($db);
$install->install();