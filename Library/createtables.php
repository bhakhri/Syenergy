<html>
<head>
<title>Table Copy Utility</title>
</head>
<body>
<?php
  //THIS FILE WILL BE USED TO CREATE TABLES FOR  DIFFERENT INSTITUTES
  //Author : Dipanjan Bhattacharjee
  //Date: 24.08.2009
  
  die("Stopped for time being as told by Ajinder Sir");
  
  $server='localhost';
  $userName='root';
  $userPassword='';
  $db='testdb';

  /*
  define('ATTENDANCE_TABLE','attendance');
  define('TEST_TABLE','test');
  define('TEST_MARKS_TABLE','test_marks');
  define('TEST_TRANSFERRED_MARKS_TABLE','test_transferred_marks');
  define('TOTAL_TRANSFERRED_MARKS_TABLE','total_transferred_marks');
  define('QUARANTINE_ATTENDANCE_TABLE','quarantine_attendance');
  define('QUARANTINE_TEST_TABLE','quarantine_test');
  define('QUARANTINE_TEST_MARKS_TABLE','quarantine_test_marks');
  define('TEST_GRACE_MARKS_TABLE','test_grace_marks');
 */ 
  //get all used defined variables   
  //$definedArray=get_defined_constants(1);
  //$userDefinedArray=$definedArray['user'];
  
  $userDefinedArray=array('ATTENDANCE_TABLE'=>'attendance',
                          'TEST_TABLE'=>'test',
                          'TEST_MARKS_TABLE'=>'test_marks',
                          'TEST_TRANSFERRED_MARKS_TABLE'=>'test_transferred_marks',
                          'TOTAL_TRANSFERRED_MARKS_TABLE'=>'total_transferred_marks',
                          'QUARANTINE_ATTENDANCE_TABLE'=>'quarantine_attendance',
                          'QUARANTINE_TEST_TABLE'=>'quarantine_test',
                          'QUARANTINE_TEST_MARKS_TABLE'=>'quarantine_test_marks',
                          'TEST_GRACE_MARKS_TABLE'=>'test_grace_marks'
                          );
  
  
  $conn=@mysql_connect($server,$userName,$userPassword);
  if(!$conn){
      echo 'Could not connect to '.$server;
      die;
  }
  
  $selDb=@mysql_select_db($db,$conn);
  if(!$selDb){
      echo 'Could not connect to '.$db.' database';
      die;
  }
  
  //*******used to create table*************
  function createTable($sourceTable,$destinationTable) {
        global $conn;
        $s="CREATE TABLE IF NOT EXISTS ".$destinationTable." LIKE ".$sourceTable;
        return mysql_query($s,$conn);
  }
    
  
  $str1='select instituteId from institute';
  $res1=mysql_query($str1,$conn);
  $instituteIdArray=array();
  while($info1=@mysql_fetch_array($res1)){
     $instituteIdArray[]=$info1['instituteId']; 
  }
  $count=count($instituteIdArray);
  
  if(!is_array($instituteIdArray) or $count <1){
      echo 'No institutes found';
      die;
  }
  
  $fl=0;
  $str1='show tables';
  $res1=mysql_query($str1,$conn);
  $tablesArray=array();
  while($info2=mysql_fetch_array($res1)){
     $tablesArray[]=$info2['Tables_in_'.$db]; 
  }
  
 $createdTablesArray=array();

for($i=0;$i<$count;$i++){    
   
   foreach($userDefinedArray as $key=>$tableValue){
      if(!strpos($key,'_TABLE')){ //a small check to get only table name defines
          continue;
      }
       if(in_array($tableValue,$tablesArray) and !in_array($tableValue.$instituteIdArray[$i],$tablesArray)){   
         createTable($tableValue,$tableValue.$instituteIdArray[$i]); //this function create tables
         $createdTablesArray[]=$tableValue.$instituteIdArray[$i];
       }
       else{
           if(!in_array($tableValue,$tablesArray)){
               echo '<b>'.$tableValue.'</b> table not found';
               $fl=1;
               break;
           }
           else{
               echo '<b>'.$tableValue.$instituteIdArray[$i].'</b> table already exists';
               $fl=1;
               break;
           }
      }
   }
   if($fl==1){
       break;
   }
}
  
  
 if($fl==1){
     $cnt=count($createdTablesArray);
     for($i=0;$i<$cnt;$i++){
         $s="DROP TABLE ".$createdTablesArray[$i];
         mysql_query($s,$conn);
     }
     die;
 } 
 else{
     echo "<b>All Table Are Copied</b>";
 }


//coping of "config" table
mysql_query('START TRANSACTION');
$s='SELECT DISTINCT instituteId FROM institute WHERE instituteId NOT IN (SELECT instituteId FROM config)';
$res2=mysql_query($s);
$insArray=array();
while($info3=mysql_fetch_array($res2)){
   $insArray[]=$info3['instituteId']; 
}
$insCnt=count($insArray);
for($i=0;$i<$insCnt;$i++){
    $q="INSERT INTO `config`(`param`, `labelName`, `value`, `tabGroup`, `instituteId`) SELECT DISTINCT `param`, `labelName`, `value`,`tabGroup`,$insArray[$i] FROM config ";
    if(!mysql_query($q)){
        echo 'Error in coping Config table entries';
        die;
    }
}
echo "Config table entries copied for $insCnt institutes";
mysql_query('COMMIT');
?>
</body>
</html>
