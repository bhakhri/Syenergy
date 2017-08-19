<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/PreAdmissionManager.inc.php");
	$preAdmissionManager = PreAdmissionManager::getInstance();
    
    $courseId = $REQUEST_DATA['courseId'];
    
    if($courseId=='') {
      $courseId='0';  
    }
   
    $condition = " AND ppc.courseId = '$courseId' ";
    $foundArray = $preAdmissionManager->getPreadmissionPreferance($condition);
    
    $tableData ='';
    if(is_array($foundArray) && count($foundArray)>0 ) {   
    
      $pref="<input type='hidden' readonly='readonly' id='totalPref' name='totalPref' value='".count($foundArray)."'>";
      
      $tableData ="<table border='0'  cellpadding='0px' cellspacing='0px' >
                    <tr>";
      $optionList='';              
      for($i=0;$i<count($foundArray);$i++) {
        $optionList .= "<option value='".($i+1)."'>".($i+1)."</option>"; 
      }              
      
      for($i=0;$i<count($foundArray);$i++) {
         $id= $foundArray[$i]['id'];
         $name= $foundArray[$i]['name'];
         if(($i+1)%7==0) {
           $tableData .= "</tr><tr>";  
         }
         $str = '<span class="contenttab_internal_rows">'.strip_slashes($name).'</span>&nbsp;';
         $tableData .= "<td><b>$str</b></td>";  
         $str = '<select style="width:90px" class="inputbox" name="coursePreference'.($id).'" id="coursePreference'.($id).'">
                  <option value="">Select</option>'.$optionList.'</select>
                  <input type="hidden" readonly   value="'.$id.'" name="coursePreferenceId'.($id).'" id="coursePreferenceId'.($id).'"> 
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';     
        $tableData .= "<td>$str</td>";                    
      }
      $tableData .= "</tr></table>$pref";         
    }
    
    if(trim($tableData)=='') {
      echo 0;
    }    
    else {
      echo $tableData;   
    }
?>