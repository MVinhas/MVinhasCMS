<?php
/*
namespace Database;

use \Database\Select;
use \Database\Create;
use \Database\Update;
use \Database\Insert;
use \Database\Delete;

require 'config/conf.php';

ini_set("display_errors", "On");
error_reporting(E_ALL);   

echo Create::table('users')->set([
    'id' => 'INT NOT NULL AUTO_INCREMENT',
    'name' => 'VARCHAR(64) NOT NULL'
])->raw();
echo "<br>";
echo Select::table('users')->fields('id', 'name')->where(['name' => 'Catarina'])->done();
echo "<br>";
echo Update::table('users')->set(['name' => 'Filipa'])->where(['id' => 1])->raw();
echo "<br>";
echo Insert::table('users')->set([
    'name' => 'Joana',
    'age' => 30
])->raw();
echo "<br>";
echo Delete::table('users')->where(['name' => 'Joana'])->raw();
*/
