<?php
namespace models;

use \Database\Select;
use \Database\Create;
use \Database\Update;
use \Database\Insert;
use \Database\Delete;


class Model
{
    protected $select;
    protected $create;
    protected $update;
    protected $insert;
    protected $delete;

    public function __construct($table)
    {
        $this->select = Select::table($table);
        $this->create = Create::table($table);
        $this->update = Update::table($table);
        $this->insert = Insert::table($table);
        $this->delete = Delete::table($table);
    }
}