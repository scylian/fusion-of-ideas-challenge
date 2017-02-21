<?php
  /**
   * DB Class
   * This class is used for database related operations using PHP Data Objects (PDO)
  **/
  
  class DB {
    // Replace with local database credentials
    private $dbHost     = "localhost";
    private $dbUsername = "xiily";
    private $dbPassword = "";
    private $dbName     = "c9";
    
    public function __construct() {
      if (!isset($this->db)) {
        // Connect to the database
        try {
          $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error handling method for PDO
          $this->db = $conn;
        } catch(PDOException $e) {
          die("Failed to connect to MySQL: ".$e->getMessage()); // catch failed connection error
        }
      }
    }
    
    /**
     * Method for returning rows from database based on conditions
     * Receives parameter $table for string name of table
     * Receives parameter $conditions for associative array of SELECT and WHERE conditions
    **/
    public function getRows($table,$conditions) {
      $sql = 'SELECT '; // initialize sql statement
      $sql .= array_key_exists('select',$conditions) ? $conditions['select'] : '*'; // check for select value, else *
      $sql .= ' FROM '.$table;
      
      if (array_key_exists('where',$conditions)) { // check for 'where' condition values
        $sql .= ' WHERE ';
        $i = 0;
        foreach($conditions['where'] as $key=>$val) {
          $pre = ($i > 0) ? ' AND ' : '';
          $sql .= $pre.$key.' = "'.$val.'"';
          $i++;
        }
      }
      
      if (array_key_exists('order_by',$conditions)) { // check for 'order_by' condition values
        $sql .= ' ORDER BY '.$conditions['order_by'];
      }
      
      if (array_key_exists('start',$conditions) && array_key_exists('limit',$conditions)) {
        $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit'];
      } elseif (!array_key_exists('start',$conditions) && array_key_exists('limit',$conditions)) {
        $sql .= ' LIMIT '.$conditions['limit'];
      }
      
      $query = $this->db->prepare($sql);
      $query->execute();
      
      if (array_key_exists('return_type',$conditions) && $conditions['return_type'] != 'all') {
        switch($conditions['return_type']) {
          case 'count':
            $data = $query->rowCount();
            break;
          case 'single':
            $data = $query->fetch(PDO::FETCH_ASSOC);
            break;
          default:
            $data = '';
        }
      } else {
        if ($query->rowCount() > 0) {
          $data = $query->fetchAll();
        }
      }
      
      return !empty($data) ? $data : false;
    }
    
    /**
     * Method for inserting data into database
     * Receives parameter $table for string name of table
     * Receives parameter $data for associative array of data to insert
    **/
    public function insert($table, $data) {
      if (!empty($data) && is_array($data)) { // check if incoming data is present and of correct type
        
        $columnString = implode(',', array_keys($data));      // comma separate all columns being inserted
        $valueString = ":".implode(',:', array_keys($data));  // comma separate all values being inserted
        $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")"; // form sql statement
        $query = $this->db->prepare($sql); // prepare sql statement in PDO
        
        // loop through data array to bind each value to a parameter in the prepared statement
        foreach($data as $key=>$val) {
          $query->bindValue(':'.$key, $val);
        }
        
        $insert = $query->execute(); // execute sql query
        
        return $insert ? $this->db->lastInsertId() : false; // return last inserted id if successful, else return false
      }
    } // end of "insert" method
    
    /**
     * Method for updating data in the database
     * Receives parameter $table for string name of table
     * Receives parameter $data for associative array of data to insert
     * Receives parameter $conditions for associative array of WHERE conditions
    **/
    public function update($table, $data, $conditions) {
      if (!empty($data) && is_array($data)) { // check if incoming data is present and of correct type
        $colvalSet = "";
        $whereSql = "";
        $i = 0;
        
        // loop through data array to form SET clause statement
        foreach(array_keys($data) as $key) {
          $pre = ($i > 0) ? ', ' : '';    // comma separate each column=value pair
          $colvalSet .= $pre.$key.'= ?';  // concatenate each column=value pair
          $i++; // increment $i above 0 to ensure comma separation in $pre
        }
        
        if (!empty($conditions) && is_array($conditions)) { //check if $conditions parameter is present and of correct type
          $whereSql .= ' WHERE '; // initialize $whereSql
          $i = 0;                 // initialize $i
          
          // loop through conditions array to form WHERE clause statement
          foreach(array_keys($conditions) as $key) {
            $pre = ($i > 0) ? ' AND ' : ''; // add AND operator between each condition
            $whereSql .= $pre.$key.' = ?';  // concatenate each column=value pair
            $i++; // increment $i above 0 to ensure AND separation
          }
        }
        
        $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql; // form sql statement
        $query = $this->db->prepare($sql);  // prepare sql statement in PDO
        $i = 1; // initialize $i for PDO value binding
        
        // loop through data array to bind each value to a parameter in the UPDATE clause
        foreach($data as $val) {
          $query->bindValue($i, $val);
          $i++; // increment $i to correctly reference question mark placeholder
        }
        
        // loop through conditions array to bind each value to a parameter in the WHERE clause
        foreach($conditions as $val) {
          $query->bindValue($i, $val);
          $i++; // increment $i to correctly reference question mark placeholder
        }
        
        $update = $query->execute();  // execute sql query
        
        return $update ? $query->rowCount() : false;  // return affected rows count if successful, else return false
      }
    } // end of "update" method
    
    /**
     * Method for deleting data from the database
     * Receives parameter $table for string name of table
     * Receives parameter $conditions for associative array of WHERE conditions
    **/
    public function delete($table, $conditions) {
      $whereSql = "";
      
      if (!empty($conditions) && is_array($conditions)) { // check if $conditions parameter is present and of correct type
        $whereSql .= ' WHERE '; // initialize $whereSql
        $i = 0;                 // initialize $i
        
        // loop through conditions array to form WHERE clause statement
        foreach(array_keys($conditions) as $key) {
          $pre = ($i > 0) ? ' AND ' : ''; // add AND operator between each condition
          $whereSql .= $pre.$key.' = ?';  // concatenate each column=value pair
          $i++; // increment $i above 0 to ensure AND separation
        }
      }
      
      $sql = "DELETE FROM ".$table.$whereSql; // form sql statement
      $query = $this->db->prepare($sql);      // prepare sql statement in PDO
      $i = 1; // initialize $i for PDO value binding
      
      // loop through conditions array to bind each value to a parameter in the WHERE clause
      foreach($conditions as $val) {
        $query->bindValue($i, $val);
        $i++;
      }
      
      $delete = $query->execute();  // execute sql query
      
      return $delete ? $query->rowCount() : false; // return affected rows count if successful, else return false
    } // end of "delete" method
    
  } // end of db class
  