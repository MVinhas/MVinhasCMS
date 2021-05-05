<?php
namespace Database;

use Database\Interfaces\QueryInterface;
use Database\Query;

class Select extends Query implements QueryInterface 
{
    use Traits\PrepareTrait;
    public $table;

    public $fields;

    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;    
    }

    public function fields(...$fields)
    {
        if (empty($fields))
            return $this;
        
        if ($fields[0] === '*') {
            $this->fields = '*';
            return $this;
        }
        
        foreach ($fields as &$field) {
            $field = "`".$field."`";
        }

        $this->fields = implode(', ', $fields);

        return $this;
    }

    public function queryBuilder()
    {
        $query = array();
        
        $fields = $this->fields ?? '*';

        $query[] = "SELECT $fields FROM `$this->table`";

        if (!empty($this->where))
            $query[] = "WHERE ".implode(" AND ", $this->where);

        return implode(' ', $query);
    }

    public function where(array $condition)
    {
        foreach ($condition as $k => $v) {
            $this->where[] = strpos('!', (string)$v) === false ?  "`$k` = '{$v}'" : "`$k` != '{$v}'";
            //PHP8 str_starts_with ( string $haystack , string $needle ) : bool

        }
        return $this;
    }

    public function raw()
    {
        return $this->queryBuilder();
    }

    public function done()
    {
        $i = 0;
        foreach ($this->where as &$v) {
            $condition = preg_split('/ !{0,}={0,}<{0,}>{0,} /', $v);
            $conditional = explode(' ', $v);
            $values[] = $condition[1];
            $condition[1] = '?';
            $v = implode(" $conditional[1] ", [$condition[0], $condition[1]]);
            $i++;
        }

        $this->entityEncode($values);

        $sql = $this->queryBuilder();
        
        $statement = $this->preparedStatement($sql, $i, $values);

        if ($statement->execute()) {
            return $statement->execute();
        }
        return "KO";
    }

    public function one()
    {
        if ($execute = $this->done()) {
            $result = $execute->get_result();
            return $result->fetch_assoc();   
        }
    }

    public function all()
    {
        if ($execute = $this->done()) {
            $result = $execute->get_result();
            while ($sql_retrieve = $result->fetch_assoc()) 
                $sql_fetch[] = $sql_retrieve;

            return $sql_fetch;
        }
    }
    

}