<?php
  /**
   * Client Class
   * This class is used to maintain client data and provide methods to
   * Add/Edit/Delete client data.
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
    
    public function child() {}
  }
