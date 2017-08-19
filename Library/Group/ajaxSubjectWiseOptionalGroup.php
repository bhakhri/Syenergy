<?php
//-------------------------------------------------------
// Purpose: To store the records of opiton subject group in array from the database, pagination and search, delete 
// functionality
// Author : Parveen Sharma
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SubjectWiseOptionalGroup');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1); 
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/OptionalSubjectGroupManager.inc.php");
    $groupManager = OptionalSubjectGroupManager::getInstance();

    
    $labelId = $REQUEST_DATA['labelId'];
    
    if($labelId=='') {
      $labelId=0;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';
    
    $orderBy = " $sortField $sortOrderBy";         

  
    $condition = " AND sp.periodValue IN (4,5,6)  AND
                   CONCAT_WS(',',c.degreeId, c.batchId, c.branchId) IN (SELECT 
                                            CONCAT_WS(',',degreeId, batchId, branchId) 
                                    FROM 
                                            time_table_classes ttc, class cls 
                                    WHERE 
                                            cls.classId = ttc.classId  AND
                                            ttc.timeTableLabelId = $labelId ) ";
    $foundArray = $groupManager->getRegistrationClassListNew($condition);

    $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'> 
                      <td width='2%'  style='padding-left:2px'  class='searchhead_text'  ><b>#</b></td>
                      <td width='25%' style='padding-left:2px'  class='searchhead_text'  align='left'><strong>Class Name</strong></td>
                      <td width='25%' style='padding-left:2px'  class='searchhead_text'  align='left'><strong>Subject Name</strong></td>
                      <td width='6%' style='padding-right:2px' class='searchhead_text'   align='right'><strong>Career</strong></td>
                      <td width='7%' style='padding-right:2px' class='searchhead_text'   align='right'><strong>Elective</strong></td>
                      <td width='12%' style='padding-right:2px' class='searchhead_text'  align='right'><strong>Total Students</strong></td>
                      <td width='11%' style='padding-left:2px'  class='searchhead_text'  align='left'><strong>Group Name</strong></td>
                      <td width='15%' style='padding-right:2px' class='searchhead_text'  align='center'>
                         <strong><nobr>Create Group?<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\"></nobr></strong>
                      </td>  
                    </tr>";    
                    
       
    $k=0;             
    $className = '';                                 
    for($i=0;$i<count($foundArray);$i++) {
          $bg = $bg =='trow0' ? 'trow1' : 'trow0';        
          
          
          $classId     = $foundArray[$i]['classId'];
          $subjectId   = $foundArray[$i]['subjectId'];
          $groupTypeId = $foundArray[$i]['groupTypeId'];
          $subjectCode = $foundArray[$i]['subjectCode'];  
          $groupId     = $foundArray[$i]['groupId'];  
              
          $val = $classId."~".$subjectId."~".$groupTypeId."~'".$subjectCode."'~".$groupId;
          
          $checkStatus ="";
          if($groupId!=-1) {
            $checkStatus="checked=checked";  
          }
          
          $checkall = '<input type="checkbox" name="chb[]"  id="chb_'.($i+1).'"  '.$checkStatus.' value="'.$val.'">
                       <input style="display:none;" type="checkbox" name="chb1[]" id="chb1_'.($i+1).'" '.$checkStatus.' value="'.$val.'">'; 
          if($className!=$foundArray[$i]['className']) {
             $tclassName = strip_slashes($foundArray[$i]['className']);
             $k=0;
          }  
          else {
            $tclassName = '';  
          }
          
          $result .= "<tr class='$bg'>
             <td style='padding-left:2px'  class='padding_top' >".($k+1)."</td>
             <td style='padding-left:2px' class='padding_top' ><b>".$tclassName."</b></td>
             <td style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($foundArray[$i]['subjectName'])."</td>
             <td style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($foundArray[$i]['careerStudent'])."</td>
             <td style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($foundArray[$i]['electiveStudent'])."</td>
             <td style='padding-right:2px' class='padding_top' align='right'>".strip_slashes($foundArray[$i]['totalStudent'])."</td>
             <td style='padding-left:2px'  class='padding_top' align='left'>".strip_slashes($foundArray[$i]['subjectCode'])."</td>
             <td style='padding-right:2px' class='padding_top' align='center'>".$checkall."</td>
          </tr>";     
          
          $className = strip_slashes($foundArray[$i]['className']);          
          $k++;           
    }
    
    if($i==0) {
      $bg = $bg =='trow0' ? 'trow1' : 'trow0';        
      $result .= "<tr class='$bg'><td style='padding-left:2px' colspan='8' class='padding_top' align='center'><b>No Data Found</b></td>"; 
    }
    
    $result .="</table>";
    
    echo  $tableHead.$result;
die;                  