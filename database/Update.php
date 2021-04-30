<?php
namespace Database;

use \Database\Interfaces\QueryInterface;

class Update
{
    protected $fields = array();

    protected $value = array();

    protected $table;

    public function __construct($table)
    {
        $this->table = $table;    
    }

    public function set($args)
    {
        foreach ($args as $k => $v) {
            $this->fields[] = "`$k` = '{$v}'";
        }
        return $this;
    }

    public function where(array $condition)
    {
        foreach ($condition as $k => $v) {
            $this->where[] = strpos('!', (string)$v) === false ?  "`$k` = '{$v}'" : "`$k` != '{$v}'";
            //PHP8 str_starts_with ( string $haystack , string $needle ) : bool

        }
        return $this;
    }

    public function queryBuilder()
    {
        $query = array();

        $fields = implode(', ', $this->fields);

        $query[] = "UPDATE `$this->table` SET $fields";

        if (!empty($this->where)) {
            $query[] = "WHERE ".implode(" AND ", $this->where);
        }
        
        return implode(" ", $query);
    }

    public function raw()
    {
        return $this->queryBuilder();
    }  

}