<?php
namespace database;

use \Database\Create;
use \Database\Delete;
use \Database\Insert;
use \Database\Select;
use \Database\Update;
use \Database\TableIndex;

class Query
{
    protected $db;

    public function __construct()
    {
        $this->db = \config\Connector::init();
    }

    public static function create($table)
    { 
        return new Create($table);
    }

    public static function delete($table)
    {
        return new Delete($table);
    }

    public static function insert($table)
    {
        return new Insert($table);
    }

    public static function select($table)
    {
        return new Select($table);
    }

    public static function update($table)
    {
        return new Update($table);
    }

    public static function tableIndex($table)
    {
        return new TableIndex($table);
    }
}