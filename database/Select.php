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

    public $orderBy;

    public $groupBy;

    public $offset;

    public $limit;

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

        $this->fields = implode(',', $fields);

        return $this;
    }

    public function queryBuilder()
    {
        $query = array();
        
        $fields = $this->fields ?? '*';

        $query[] = "SELECT $fields FROM `$this->table`";

        if (!empty($this->where))
            $query[] = "WHERE ".implode(" ", $this->where);

        if ($this->groupBy !== '')
            $query[] = $this->groupBy;
        
        if ($this->orderBy !== '')
            $query[] = $this->orderBy;
        
        if ($this->limit !== '')
            $query[] = $this->limit;

        if ($this->offset !== '')
            $query[] = $this->offset;

        return implode(' ', $query);
    }

    public function where(array $condition, string $flag = '', string $delimiter = '')
    {
        
        
        foreach ($condition as $k => $v) {
            
            if (!empty($this->where) && $delimiter === '') {
                $delimiter = 'AND';
            } elseif (empty($this->where)) {
                $delimiter = '';
            }
            if (is_array($v)) {
                $this->where($v, $v[0]);
            } else {
                if ($flag === '') {
                    //PHP8 str_starts_with ( string $haystack , string $needle ) : bool
                    $k = str_replace(' ','', $k);    
                    $this->where[] = strpos('!', (string)$v) === false ?  trim("$delimiter $k = '{$v}'") : trim("$delimiter $k != '{$v}'");
                }
            }
            
        }
        if ($flag === '') return $this;
        
        $replaceConst = str_replace('$1', $condition[array_key_first($condition)], $flag);
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

    public function groupBy(string $orderBy)
    {
        $this->groupBy = "GROUP BY ".$orderBy;
        return $this;
    }

    public function orderBy(string $orderBy)
    {
        $this->orderBy = "ORDER BY ".$orderBy;
        return $this;
    }

    public function limit(string $limit = '0,1')
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset(int $offset = 0)
    {
        $this->offset = "OFFSET $offset";
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
        if (isset($this->where)) {
            foreach ($this->where as &$v) {
                $condition = preg_split('/ !{0,}={0,}<{0,}>{0,}(LIKE|NOT LIKE){0,}/', $v, -1, PREG_SPLIT_NO_EMPTY);
                if (in_array($condition[0], ['AND', 'OR'])) {
                    $condition[0] .= " $condition[1]";
                    unset($condition[1]);
                }
                
                preg_match('/(=|!=|like|not like|<|>)/i', $v, $matches);
                $lastvalue = array_key_last($condition);
                $values[] = trim($condition[$lastvalue]);
                $condition[$lastvalue] = '?';
                $v = implode(" $matches[0] ", [$condition[0], $condition[$lastvalue]]);
                $i++;
            }
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