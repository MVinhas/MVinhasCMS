<?php
namespace Database;

use Database\Interfaces\QueryInterface;
use Database\SanitizeQuery;

class Select extends SanitizeQuery implements QueryInterface 
{
    use Traits\PrepareTrait;
    public $table;

    public $fields;

    public function __construct($table)
    {
        $this->table = $table;    
    }

    public static function table(string $table)
    {
        return new Select($table);
    }

    public function fields(...$fields)
    {
        foreach ($fields as &$field) {
            $field = "`".$field."`";
        }

        $this->fields = $fields;
        return $this;
    }

    public function queryBuilder()
    {
        $query = array();

        $fields = implode(', ', $this->fields) ?? '*';

        $query[] = "SELECT $fields FROM `$this->table`";
        
        if (!empty($this->where)) {
            $query[] = "WHERE ".implode(" AND ", $this->where);
        }

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
        foreach ($this->where as $k => &$v) {
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
            return "OK";
        }
        return "KO";
    }

    public function one()
    {
        if ($this->done()) {
            $result = $sql_prepare->get_result();
            return $result->fetch_assoc();   
        }
    }

    public function all()
    {
        if ($this->done()) {
            $result = $sql_prepare->get_result();
            while ($sql_retrieve = $result->fetch_assoc()) 
                $sql_fetch[] = $sql_retrieve;

            return $sql_fetch;
        }
    }
    

}