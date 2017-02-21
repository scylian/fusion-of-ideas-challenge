<?php
  /**
   * Client Class
   * This class is used to maintain client data and provide methods to
   * Add/Edit/Delete client data.
   * 
   * Additionally it will be used as the base class to be inherited
   * by Sections and Links.
  **/
  
  include_once 'DB.php';
  include_once 'AbstractClass.php';
  
  class Client extends AbstractClass {
    protected $tblName = 'clients';
    
    public function __construct(DB $db, $params) {
      if (isset($params['id']) && !empty($params['id'])) {
        $this->id = $params['id'];
      }
      if (isset($params['name']) && !empty($params['name'])) {
        $this->name = $params['name'];
      }
      
      $this->db = $db;
    }
    
    public function add() {
      $this->child();
      $this->data['name'] = $this->name;
      $insert = $this->db->insert($this->tblName, $this->data);
      $statusMsg = $insert ? 'Client data has been inserted successfully.' : 'Error occurred, try again.';
      return $statusMsg;
    }
    
    public function edit() {
      $this->child();
      $this->data['name'] = $this->name;
      $this->condition['id'] = $this->id;
      $update = $this->db->update($this->tblName, $this->data, $this->condition);
      $statusMsg = $update ? 'Client data has been updated successfully.' : 'Error occurred, try again.';
      return $statusMsg;
    }
    
    public function delete() {
      $this->condition['id'] = $this->id;
      $delete = $this->db->delete($this->tblName, $this->condition);
      $statusMsg = $delete ? 'Client data has been deleted successfully.' : 'Error occurred, try again.';
      return $statusMsg;
    }
    
    public function child() {}
  }