<?php
namespace Database;

use \Database\Interfaces\QueryInterface;

class Insert extends SanitizeQuery implements QueryInterface
{
    use Traits\PrepareTrait;

    protected $fields;

    protected $values;

    protected $table;

    public function __construct($table)
    {
        $this->table = $table;    
    }

    public static function table(string $table)
    {
        return new Insert($table);
    }

    public function set($args)
    {
        foreach ($args as $k => $v) {
            if (empty($k) || empty($v)) continue;
            $fields[] = "`".$k."`";
            $values[] = "'{$v}'";
        }

        $this->fields = implode(', ', $fields);
        $this->values = implode(', ', $values);

        return $this;
    }

    public function queryBuilder()
    {
        $query = array();

        $query = "INSERT INTO `$this->table` ($this->fields) VALUES ($this->values)";
        
        return $query;
    }

    public function raw()
    {
        return $this->queryBuilder();
    }
    
    public function done()
    {
        $values = explode(', ', $this->values);

        foreach ($values as $value) {
            $prepared[] = str_replace($value, '?', $values);
        }

        $preparedQuery = str_replace($this->values, implode(', ', $prepared), $this->queryBuilder());

        $this->entityEncode($values);

        $statement = $this->preparedStatement($preparedQuery, count($prepared), $values);

        if ($statement->execute()) {
            return $statement->execute();
        }
        return "KO";

    }

}