<?php
  /**
   * Abstract Class
   * This class is used as the base class to be inherited.
   * It maintains data and provides methods for Add/Edit/Delete.
  **/
  
  include_once 'DB.php';
  
  abstract class AbstractClass {
    protected $id;
    public $name;
    protected $db;
    protected $data = array();
    protected $condition = array();
    
    public function __construct(DB $db, $params) {
      if (isset($params['id']) && !empty($params['id'])) {
        $this->id = $params['id'];
      }
      if (isset($params['name']) && !empty($params['name'])) {
        $this->name = $params['name'];
      }
      
      $this->db = $db;
    }
    
    abstract public function child();
    
    public function add() {
      $this->child();
      $this->data['name'] = $this->name;
      $insert = $this->db->insert($this->tblName, $this->data);
      $statusMsg = $insert ? 'Data has been inserted successfully.' : 'Error occurred, try again.';
      return $statusMsg;
    }
    
    public function edit() {
      $this->child();
      $this->data['name'] = $this->name;
      $this->condition['id'] = $this->id;
      $update = $this->db->update($this->tblName, $this->data, $this->condition);
      $statusMsg = $update ? 'Data has been updated successfully.' : 'Error occurred, try again.';
      return $statusMsg;
    }
    
    public function delete() {
      $this->condition['id'] = $this->id;
      $delete = $this->db->delete($this->tblName, $this->condition);
      $statusMsg = $delete ? 'Data has been deleted successfully.' : 'Error occurred, try again.';
      return $statusMsg;
    }
  }