<?php
namespace Database;

use \Database\Interfaces\QueryInterface;
use Database\Query;

class Delete extends Query implements QueryInterface
{
    public $table;

    public $fields;

    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;    
    }

    public static function table(string $table)
    {
        return parent::delete($table);
    }

    public function queryBuilder()
    {
        $query = array();

        $query[] = "DELETE FROM `$this->table` WHERE ".implode(" AND ", $this->where);

        return implode(' ', $query);
    }

    public function where(array $condition)
    {
        foreach ($condition as $k => $v) {
            $this->where[] = strpos('!', (string)$v) === false ?  "`$k` = '$v'" : "`$k` != '$v'";
            //PHP8 str_starts_with ( string $haystack , string $needle ) : bool

        }
        return $this;
    }

    public function raw()
    {
        return $this->queryBuilder();
    } 

}