<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\HeaderController as HeaderController;
    
class Header extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }

    public function getMenu()
    {
        $data = array(1);
        $menu = $this->db->select('pages', '*', 'header = ?', $data);

        foreach ($menu as $k => $v) {
            $data = array($v['method']);
            $method = $this->db->select('methods', 'name, controller', 'id = ?', $data);
            $data = array($method['controller']);
            $controller = $this->db->select('controllers', 'name', 'id = ?', $data);
            $menu[$k]['class'] = $controller['name'].'/'.$method['name'];
        }
        if (!empty($menu)) {
            return $menu;
        }
        return 'Error';
    }
}
