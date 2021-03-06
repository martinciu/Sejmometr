<?
  class DB extends MySQLi {
    var $status;
    var $reserved_words = array('NOW()');
    
    function DB(){      
      $this->status = @parent::MySQLi(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
      if( $this->status===false ) { echo 'Can\'t connect to the database.'; die(); }
      $this->query('SET NAMES UTF8');      
    }
    
    function q($q){ return $this->query($q); }
    
    function query($q){
      // echo $q."\n\n";
      if( $this->status===false ) return false;
      
      $data = parent::query($q);
      if( $data===false ) {
        // error
        throw new Exception($this->error);
      } else { return $data; }
    }
    
    // SELECT ASSOCS    
    function selectAssocs($q){
      $data = $this->query($q);
      if( $data===false ) { return false; }
      $result = array();
      while( $row=$data->fetch_assoc() ){
        $result[] = $row;
      }      
      return $result;
    }
        
    function selectAssoc($q){
      $data = $this->query($q);
      return ($data===false) ? false : $data->fetch_assoc();
    }
    
    
    // SELECT ROWS
    function selectRows($q){
      $data = $this->query($q);
      if( $data===false ) { return false; }
      $result = array();
      while( $row=$data->fetch_row() ){
        $result[] = $row;
      }      
      return $result;
    }
    
    function selectRow($q){
      $data = $this->query($q);
      return ($data===false) ? false : $data->fetch_row();      
    }
    
    // SELECT PAIRS
    function selectPairs($q){
      $data = $this->query($q);
      if( $data===false ) { return false; }
      $result = array();
      while( $row=$data->fetch_row() ){
        $id = array_shift($row);
        $values = array_values($row);
        if( count($row)==1 ) { $values = $values[0]; }
        $result[$id] = $row;        
      }      
      return $result;
    }
    
    function selectPair($q){
      $data = $this->query($q);
      if( $data===false ) { return false; }
      $row = $data->fetch_row();
      return array( $row[0]=>$row[1] );   
    }
    
    // SELECT VALUES
    function selectValues($q){
      $data = $this->query($q);
      if( $data===false ) { return false; }
      $result = array();
      while( $row=$data->fetch_row() ){
        $result[] = $row[0];
      }      
      return $result;
    }
    
    function selectValue($q){
      $data = $this->selectRow($q);
      return ($data===false) ? false : $data[0];
    }
    
    // SELECT COUNT
    function selectCount($q){
      $data = $this->query($q);
      if( $data===false ) { return false; }
      $data = $data->fetch_row();
      return (int) $data[0];
    }
    
    function selectCountBoolean($q){
      return (boolean) $this->selectCount($q);
    }
    
    
    
    
    
    
    
    
    // INSERT
    
    function generate_id($table){
      if( empty($table) ) return false;
      $_chars = 'bcdfghjklmnpqrstwvxzBCDFGJKLMNPQRSTVXYWZ';
      $_length = 5;
      do{$id=''; for($i=0;$i<$_length;$i++){$id.=$_chars[rand(0,strlen($_chars)-1)];}}
      while($this->selectCount("SELECT COUNT(*) FROM `$table` WHERE `id`='$id'")>0);
      return $id;
    }
    
    function insert_assoc_create_id($table, $data){
      if( empty($table) || empty($data) ) return false;
      $id = $this->generate_id($table);
      $data['id'] = $id;
      
      $keys = array_keys($data);
      $values = array_values($data);
      
      $q = "INSERT IGNORE INTO `$table` (`".implode("`, `", $keys)."`) VALUES ('".implode("', '", $values)."')";
      $this->query($q);
      // echo $q; 
      return ($this->affected_rows==1) ? $id : false;
    }
    
    function insert_assoc($table, $data){
      if( empty($table) || empty($data) ) return false;      
      
      $keys = array_keys($data);
      $values = array_values($data);
      
      $q = "INSERT INTO `$table` (`".implode("`, `", $keys)."`) VALUES ('".implode("', '", $values)."')";
      $this->query($q);
      // echo $q; 
      return $this->affected_rows==1;
    }
    
    function insert_ignore_assoc($table, $data){
      if( empty($table) || empty($data) ) return false;      
      
      $keys = array_keys($data);
      $values = array_values($data);
      
      $q = "INSERT IGNORE INTO `$table` (`".implode("`, `", $keys)."`) VALUES ('".implode("', '", $values)."')";
      $this->query($q);
      // echo $q; 
      return $this->affected_rows==1;
    }
    
    
    
    // UPDATE
    
    function update_assoc($table, $data, $where){
      if( !is_array($data) ) return false;
      if( !is_array($where) && !is_string($where) ) return false;
      
      if( is_string($where) ) { $where_k = 'id'; $where_v = $where; }
      else { foreach( $where as $where_k=>$where_v ) {break;} }
      
      $sets = array();
      foreach( $data as $k=>$v ){
        if($k!=$where_k) {
          
          if( in_array($v, $this->reserved_words) ) {
            $sets[] = "`$k`=$v";
          } else {
	          $sets[] = "`$k`='$v'";
          }
        }
      }
      
      
      $q = "UPDATE $table SET ".implode(", ", $sets)." WHERE (`$where_k`='$where_v')";
      return $this->q($q);
    }
    
    
    function found_rows(){
      return $this->selectCount("SELECT FOUND_ROWS()");
    }
  }  
?>