<?php
  /**
   * Link Class
   * Extension of Client Class
   * This class is used to maintain section data and provide methods
   * to Add/Edit/Delete section data.
  **/
  
  include_once 'DB.php';
  include_once 'AbstractClass.php';
  
  class Link extends Client {
    public $section_id;
    protected $tblName = 'links';
    
    public function __construct(DB $db, $params) {
      if (isset($params['id']) && !empty($params['id'])) {
        $this->id = $params['id'];
      }
      if (isset($params['name']) && !empty($params['name'])) {
        $this->name = $params['name'];
      }
      if (isset($params['section_id']) && !empty($params['section_id'])) {
        $this->section_id = $params['section_id'];
      }
      
      $this->db = $db;
    }
    
    public function child() {
      $this->data['section_id'] = $this->section_id;
    }
  }