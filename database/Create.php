<?php
namespace Database;

use Database\Interfaces\QueryInterface;
use Database\SanitizeQuery;

class Create extends SanitizeQuery implements QueryInterface 
{
    use Traits\PrepareTrait;
    public $table;

    public $set;

    public function __construct($table)
    {
        $this->table = $table;    
    }

    public function set($args)
    {
        foreach ($args as $k => $v) {
            if (empty($k) || empty($v)) continue;
            $set[] = "`$k` $v";
        }
        $this->set = implode(', ', $set);

        return $this;
    }

    public function queryBuilder()
    {
        $query = array();

        $query = "CREATE TABLE `$this->table` ($this->set)";
        
        return $query;
    }

    public function raw()
    {
        return $this->queryBuilder();
    }

    public function done()
    {
        $sql = $this->db->real_escape_string($this->queryBuilder());
        $this->db->query($sql);
        //A ver depois como vamos tratar os erros
        /*
        if ($this->db->connection->errno)
            return false;
        else
            return true;
        */
    }
    

}