<?php
namespace Database;

use \Database\Interfaces\QueryInterface;
use Database\Query;
use Exception;

class Insert extends Query implements QueryInterface
{
    use Traits\PrepareTrait;
    use Traits\SanitizeQueryTrait;

    protected $fields;

    protected $values;

    protected $valuesPacked;

    protected $table;

    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;    
    }

    public static function table(string $table)
    {
        return parent::insert($table);
    }

    public function set($args)
    {
        foreach ($args as $k => $v) {
            if (empty($k) || empty($v)) continue;
            $fields[] = "`".$k."`";
            if ((int)$v === $v) {
                $values[] = $v;
            } else {
                $values[] = "$v";
            }
        }

        $this->fields = implode(',', $fields);
        $this->values = implode(',', $values);
        $this->valuesPacked = $values;

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
        $values = $this->valuesPacked;
        $prepared = array();

        foreach ($values as $k => $v) {
            $prepared[] = str_replace($v, '?', $v);
        }
        
        $preparedQuery = str_replace($this->values, implode(',', $prepared), $this->queryBuilder());
        $this->entityEncode($values);
        
        $statement = $this->preparedStatement($preparedQuery, count($prepared), $values);
        return $statement->execute();

    }

}