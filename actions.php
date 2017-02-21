<?php
  /**
   * This file contains all the logic for performing database actions
   * relating to the clients table
  **/
  
  session_start();
  
  include_once 'classes/DB.php';      // include the DB class
  include_once 'classes/Client.php';  // include the Client class
  include_once 'classes/Section.php'; // include the Section class
  include_once 'classes/Link.php';    // include the Link class
  
  $db = new DB();       // instantiate new DB class
  $tblName = $_REQUEST['table_name']; // set table name
  
  if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {  // check if action type is present and not empty
    if ($tblName == 'clients') {
      $class = new Client($db, $_REQUEST);
    } elseif ($tblName == 'sections') {
      $class = new Section($db, $_REQUEST);
    } elseif ($tblName == 'links') {
      $class = new Link($db, $_REQUEST);
    }
    
    if ($_REQUEST['action_type'] == 'add') {
      $_SESSION['statusMsg'] = $class->add();   // invoke method and store returned message
      header('Location:index.php');             // redirect browser to index.php
    } elseif ($_REQUEST['action_type'] == 'edit') {
      if (!empty($_POST['id'])) {
        $_SESSION['statusMsg'] = $class->edit();
        header('Location:index.php');
      }
    } elseif ($_REQUEST['action_type'] == 'delete') {
      if (!empty($_GET['id'])) {
        $_SESSION['statusMsg'] = $class->delete();
        header('Location:index.php');
      }
    }
  }