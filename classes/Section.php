<?php
  /**
   * Section Class
   * Extension of Client Class
   * This class is used to maintain section data and provide methods
   * to Add/Edit/Delete section data.
  **/
  
  include_once 'DB.php';
  include_once 'AbstractClass.php';
  
  class Section extends Client {
    public $client_id;
    protected $tblName = 'sections';
    
    public function __construct(DB $db, $params) {
      if (isset($params['id']) && !empty($params['id'])) {
        $this->id = $params['id'];
      }
      if (isset($params['name']) && !empty($params['name'])) {
        $this->name = $params['name'];
      }
      if (isset($params['client_id']) && !empty($params['client_id'])) {
        $this->client_id = $params['client_id'];
      }
      
      $this->db = $db;
    }
    
    public function child() {
      $this->data['client_id'] = $this->client_id;
    }
  }