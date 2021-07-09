<?php
namespace engine;

class DbOperations
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \config\Connector::init();
    }

    protected function fetchQuery($query_res)
    {
        $sql_fetch = array();
        if ($query_res->num_rows === 1) 
            return $query_res->fetch_assoc();
        
        while ($sql_retrieve = $query_res->fetch_assoc()) 
            $sql_fetch[] = $sql_retrieve;

        return $sql_fetch;
    }

    public function create(string $table, string $fields, array $data)
    {
        $data = array_values($this->convertHtmlEntities($data));
        $prepare_array = array();

        foreach ($data as $k => $v) {
            $prepare_array[$k] = str_replace($v, '?', $data[$k]);
        }
        $prepare = implode(',', $prepare_array);
        $sql = "INSERT INTO $table ($fields) VALUES ($prepare)";
        $number_of_fields = substr_count($prepare, '?');
        $sql = $this->preparedStatement($sql, $number_of_fields, $data);
        
        if ($sql->execute())
            return $sql->insert_id;
        else
            return $this->db->connection->error;  
    }

    public function select(string $table, string $fields = '*', string $filter = '1=?', array $field_values = array(1))
    {
        if ($fields === '*' && $filter === '1=?')
            mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT ^ MYSQLI_REPORT_INDEX);     
        else
            mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT); 
        
        $sql = "SELECT $fields FROM $table WHERE $filter";

        $data = array_values($field_values);
        $number_of_fields = substr_count($filter, '?');
        $data = $this->convertHtmlEntities($data);

        $sql_prepare = $this->preparedStatement($sql, $number_of_fields, $data);
        if ($sql_prepare === false || $sql_prepare === null) 
            return $this->db->connection->error;
       
        if ($sql_prepare->execute()) {
            $result = $sql_prepare->get_result();
            $sql_fetch = $this->fetchQuery($result);
            $sql_fetch = $this->htmlentitiesToUTF8($sql_fetch);
            return $sql_fetch;
        } else {
            return $this->db->connection->error;
        }
    }

    public function update(string $table, string $fields, array $fields_value, string $where = '1=?', array $where_value = array(1))
    {   
        $fields_value = $this->convertHtmlEntities($fields_value);
        $where_value = $this->convertHtmlEntities($where_value);
        $data = array_values($fields_value);

        $data_where = array_values($where_value);

        $sql = "UPDATE $table SET $fields WHERE $where";

        $number_of_fields = substr_count($fields, '?');
        $number_of_fields_where = substr_count($where, '?');

        $sql_prepare = $this->preparedStatement($sql, $number_of_fields, $data, $number_of_fields_where, $data_where);
        if ($sql_prepare === false)
            return $this->db->connection->error;
        if ($sql_prepare->execute()) {
            return true;
        } else {
            return $this->db->connection->error;
        }
    }

    public function delete(string $table, string $condition = '1=?', array $condition_values = array(1))
    {
        $sql = "DELETE FROM $table WHERE $condition";
        $data = array_values($condition_values);
         
        $number_of_fields = substr_count($condition, '?');

        $sql_prepare = $this->preparedStatement($sql, $number_of_fields, $data);
        
        if ($sql_prepare === false)
            return $this->db->connection->error;
        
        if ($sql_prepare->execute())
            return true;
        else
            return $this->db->connection->error;
    }

    public function checkTable(string $table)
    {
        $sql = "SELECT 1 FROM $table LIMIT 1";
        $sql = $this->db->real_escape_string($sql);
        $this->db->query($sql);
        if ($this->db->connection->error)
            return $this->db->connection->error;

        return true;
    }
    
    public function createTable(string $table, array $fields)
    {
        $values = array();
        foreach ($fields as $k => $v) {
            $values[] = $k.' '.$v;
        }
        
        $sql = "CREATE TABLE $table (".implode(',',$values).")";
        $sql = $this->db->real_escape_string($sql);
        $this->db->query($sql);
        if ($this->db->connection->errno)
            return false;
        else
            return true;
    }

    public function createIndex(string $table, string $constraint, string $value)
    {
        $sql = "ALTER TABLE $table ADD CONSTRAINT $constraint $value";
        $sql = $this->db->real_escape_string($sql);
        $this->db->query($sql);
        if ($this->db->connection->errno)
            return false;
        else
            return true;
    }

    private function preparedStatement($sql, $number_of_fields, $data, $number_of_fields_where = '', $data_where = array())
    {
        $fields = $this->getValueTypes($number_of_fields, $data);
        if (strlen($number_of_fields_where) > 0 ) {
            $fields_where = $this->getValueTypes($number_of_fields_where, $data_where);
            $value_types = $fields.$fields_where;
            $sql_prepare = $this->db->prepare($sql);
            $sql_prepare->bind_param($value_types, ...$data, ...$data_where);
        } else {
            $sql_prepare = $this->db->prepare($sql);
            $sql_prepare->bind_param($fields, ...$data);
        }

        return $sql_prepare;
    }

    private function getValueTypes($number_of_fields, $data)
    {
        $value_types = array();
        for ($i=0; $i < $number_of_fields; $i++) {
            $value_types[$i] = strtolower(substr(gettype($data[$i]), 0, 1));
        }
        
        $value_types = implode('', $value_types);
        
        return $value_types;
    }

    private function convertHtmlEntities($input)
    {
        array_walk_recursive(
            $input, function (&$value) {
                    if (gettype($value) === 'string')
                        $value = htmlentities($value);
                }
        );
        return $input;
    }

    private function htmlEntitiesToUTF8($input)
    {
        array_walk_recursive(
            $input, function (&$value) {
                    if (gettype($value) === 'string')    
                        $value = html_entity_decode($value);
                }
        );
        return $input;
    }
}