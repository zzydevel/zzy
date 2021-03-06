<?php

Class MysqliMaframe{

    public function __construct($config = array()){

        $host = $GLOBALS['config']['db']['hostname'];
        $user = $GLOBALS['config']['db']['username'];
        $password = $GLOBALS['config']['db']['password'];
        $dbname = $GLOBALS['config']['db']['database'];

        $this->conn = mysqli_connect($host,$user,$password,$dbname)or die("Connection error");

        //$this->setChar($charset);

    }
    public function query($query)
    {
        return mysqli_query($this->conn,$query);
    }
    public function fetch($a)
    {
        $r = mysqli_fetch_array($a);
        return $r;
    }
    public function assoc($a)
    {
        $r = mysqli_fetch_assoc($a);
        return $r;
    }
    public function count_rows($a)
    {
        $r = mysqli_num_rows($a);
        return $r;
    }
    public function error()
    {
        $r = mysqli_error($this->conn);
        return $r;
    }
    public function select($table,$column = '*')
    {
        $sql = "SELECT $column FROM $table";
        return $this->query($sql);
    }
    public function select_where($table,$where = array(),$column = '*')
    {
        $sql = "SELECT $column FROM $table WHERE";
        $n=0;
        $j=count($where)-1;
        foreach($where as $tb=>$cl)
        {
            $sql.= "$tb='$cl'";
            if($n++ != $j){
            $sql.=" AND ";
            }
        }
        $sql.= "";
        return $this->query($sql);
    }
    public function insert($table,$data = array())
    {
        $sql = "INSERT INTO $table VALUES(";
        for ($i=0; $i <= count($data)-1; $i++) { 
        $sql.= "'$data[$i]'";
    if($i != count($data)-1)
    {
        $sql.= ',';
    }
    }   
    $sql.= ")";
    return $this->query($sql);
    }
    public function update($table,$set = array(),$where)
    {
        $sql = "UPDATE $table SET ";
        $j = count($set)-1;
        $n = 0;
        foreach($set as $col=>$val)
        {
            $sql.="$col='$val'";
        if($n++ != $j){
        $sql.=",";}
        }
        $sql.= "WHERE ";
        foreach($where as $ww=>$kemana)
        {
            $sql.= "$ww='$kemana'";
        }
        return $this->query($sql);
    }
    public function delete($table,$where)
    {
        $sql = "DELETE FROM $table WHERE";
        foreach($where as $target=>$value)
        {
            $sql.= " $target='$value' ";
        }
        $sql.= "";
        return $this->query($sql);
    }
    public function query_result($result)
    {
        echo "<table border=\"1\" style=\"margin: 2px;border-collapse:collapse;border:1px solid #888\">".
           "<thead style=\"font-size: 80%\">";
      $numFields = mysqli_num_fields($result);
      // BEGIN HEADER
      $tables    = array();
      $nbTables  = -1;
      $lastTable = "";
      $fields    = array();
      $nbFields  = -1;
      while ($column = mysqli_fetch_field($result)) {
        if ($column->table != $lastTable) {
          $nbTables++;
          $tables[$nbTables] = array("name" => $column->table, "count" => 1);
        } else
          $tables[$nbTables]["count"]++;
        $lastTable = $column->table;
        $nbFields++;
        $fields[$nbFields] = $column->name;
      }
      for ($i = 0; $i <= $nbTables; $i++)
        echo "<th colspan=".$tables[$i]["count"].">".$tables[$i]["name"]."</th>";
      echo "</thead>";
      echo "<thead style=\"font-size: 80%\">";
      for ($i = 0; $i <= $nbFields; $i++)
        echo "<th>".$fields[$i]."</th>";
      echo "</thead>";
      // END HEADER
      while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        for ($i = 0; $i < $numFields; $i++)
          echo "<td>".htmlentities($row[$i])."</td>";
        echo "</tr>";
      }
      echo "</table></div>";
    }
    public function closecon()
    {
        return mysqli_close();
    }
    public function free($data)
    {
        return mysqli_free_result($data);
    }
}