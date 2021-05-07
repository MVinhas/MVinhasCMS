<?php
namespace Database;

use Database\Interfaces\QueryInterface;
use Database\Query;

class Select extends Query implements QueryInterface 
{
    use Traits\PrepareTrait;
    use Traits\SanitizeQueryTrait;
    public $table;

    public $fields;

    public $result;

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
            $query[] = "WHERE ".implode(" ", $this->where);

        return implode(' ', $query);
    }

    public function where(array $condition, string $flag = '', string $delimiter = '')
    {
        if (!empty($this->where) && $delimiter === '') {
            $delimiter = 'AND';
        } elseif (empty($this->where)) {
            $delimiter = '';
        }

        foreach ($condition as $k => $v) {
            if (is_array($v)) {
                $this->where($v, $v[0]);
            } else {
                if ($flag === '')
                    $this->where[] = strpos('!', (string)$v) === false ?  "$delimiter `$k` = '{$v}'" : "$delimiter `$k` != '{$v}'";
            }
            //PHP8 str_starts_with ( string $haystack , string $needle ) : bool
        }

        //$condition[array_key_first($condition)] -> 04
        //$condition[0] = LIKE $1
        //array_key_first($condition) = DATE_FORMAT(date, "%m")
        //WHERE array_key_first($condition)." ".$condition[0];
        if ($flag === '') return $this;
    
        $replaceConst = str_replace('$1', $condition[array_key_first($condition)], $condition[0]);
        $this->where[] = ltrim($delimiter.' '.str_replace(' ','',(array_key_first($condition)))." ".$replaceConst);
        return $this;
    }

    public function andWhere(array $condition, string $flag = '')
    {
        $this->where($condition, $flag, 'AND');
        return $this;
    }

    public function orWhere(array $condition, string $flag = '')
    {  
        $this->where($condition, $flag, 'OR');
        return $this;
    }

    public function raw()
    {
        return $this->queryBuilder();
    }

    public function done()
    {
        $i = 0;
        $values = array();
        
        foreach ($this->where as &$v) {
            $condition = preg_split('/!{0,}={0,}<{0,}>{0,}LIKE{0,}/', $v);
           
            preg_match('/!{0,}={0,}<{0,}>{0,}LIKE{0,}/', $v, $matches);
            
            $lastvalue = array_key_last($condition);
            $values[] = trim($condition[$lastvalue]);
            $condition[$lastvalue] = '?';
            $v = implode("$matches[0] ", [$condition[0], $condition[$lastvalue]]);
            $i++;
        }
    
        $this->entityEncode($values);

        $sql = $this->queryBuilder();
        $statement = $this->preparedStatement($sql, $i, $values);
        
        $statement->execute();
        
        $this->result = $statement;
        return $this;
    }

    public function one()
    {     
        $result = $this->result->get_result();
        return $result->fetch_assoc();  
    }

    public function all()
    {
        $result = $this->result->get_result();
        $sql_fetch = array();
        while ($sql_retrieve = $result->fetch_assoc()) 
            $sql_fetch[] = $sql_retrieve;

        return $sql_fetch;
    }
    

}