<?php
//--------------------------------------------------------
//This file fetch student final marks details
//
// Author :Parveen Sharma
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();

  
    define('MODULE','InternalMarksFoxproReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);  
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();    
    
    // This function clearSpecialChars in subject code
    function clearSpecialChar($text) {
        if($text!="") {
          $text=strtolower($text);
          $code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
          $code_entities_replace = array('','','','','','','','','','','','','','','','','','','','','','','','','','');
          $text = str_replace($code_entities_match, $code_entities_replace, $text);
        }
        return $text;
    }
    
    $classId = add_slashes($REQUEST_DATA['classId']);
    
    if($classId=='') {
      $classId = 0;  
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.',10000';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
    
    if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"")="" OR universityRollNo = "'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)';
    }
    else 
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"")="" OR firstName = "'.NOT_APPLICABLE_STRING.'",s.studentId, studentName)';
    }
    else
    if ($sortField == 'fatherName') {
        $sortField1 = 'IF(IFNULL(fatherName,"")="" OR fatherName = "'.NOT_APPLICABLE_STRING.'",s.studentId, fatherName)';
    }
    else {
        $sortField = 'universityRollNo';
        $sortField1 = 'IF(IFNULL(universityRollNo,"")="" OR universityRollNo = "'.NOT_APPLICABLE_STRING.'",s.studentId, universityRollNo)'; 
    }
    
    $orderBy = " $sortField1 $sortOrderBy";     
    
    $countColSpan= 5;
    $resultData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'>
                      <td width='2%'  class='searchhead_text'><b>#</b></td>
                      <td width='2%'  class='searchhead_text'><input type='checkbox' id='checkbox2' name='checkbox2' onclick='doAll();'></td>
                      <td width='8%'  class='searchhead_text' align='left'><strong>Univ. Roll No.</strong></td>
                      <td width='14%' class='searchhead_text' align='left'><strong>Student Name</strong></td>
                      <td width='14%' class='searchhead_text' align='left'><strong>Father's Name</strong></td>";
                      
         
    // Subject Array 
    $subjectListField = "DISTINCT ss.hasParentCategory, ss.subjectId, ss.classId, sub.subjectName, sub.subjectCode";
    $subjectListTable = "`subject_to_class` ss, `subject` sub ";
    $subjectListCondition="WHERE 
                                ss.subjectId = sub.subjectId AND
                                ss.classId = $classId 
                           ORDER BY
                                sub.subjectCode";
    $subjectListArray = $studentManager->getSingleField($subjectListTable,$subjectListField, $subjectListCondition);
    
         
    // Get Final Optional Subject List   
    $optionalField = "DISTINCT ss.classId, 
                               sub.subjectId AS parentSubjectId, sub.subjectName  AS parentSubjectName,  sub.subjectCode AS parentSubjectCode,
                               sub1.subjectId AS subjectId, sub1.subjectName AS subjectName, sub1.subjectCode AS subjectCode ";
    $optionalTable = "`student_optional_subject` ss, `subject` sub, `subject` sub1  ";
    $optionalCondition=" WHERE 
                             classId = $classId  AND
                             sub.subjectId  = ss.parentOfSubjectId AND
                             sub1.subjectId = ss.subjectId AND
                             parentOfSubjectId IN (SELECT subjectId FROM subject_to_class WHERE classId = $classId AND hasParentCategory=1) 
                             ORDER BY sub.subjectId";
    $optionalSubjectArray = $studentManager->getSingleField($optionalTable,$optionalField, $optionalCondition);
    
    
    // Get Final Optional Studentwise 
    $optionalField = "ss.classId, ss.studentId,
                      sub.subjectId AS parentSubjectId, sub.subjectName  AS parentSubjectName,  sub.subjectCode AS parentSubjectCode,
                      sub1.subjectId AS subjectId, sub1.subjectName AS subjectName, sub1.subjectCode AS subjectCode ";
    $optionalTable = "`student_optional_subject` ss, `subject` sub, `subject` sub1  ";
    $optionalCondition=" WHERE 
                             classId = $classId  AND
                             sub.subjectId  = ss.parentOfSubjectId AND
                             sub1.subjectId = ss.subjectId AND
                             parentOfSubjectId IN (SELECT subjectId FROM subject_to_class WHERE classId = $classId AND hasParentCategory=1) 
                             ORDER BY sub.subjectId";
    $optionalSubjectArray1 = $studentManager->getSingleField($optionalTable,$optionalField, $optionalCondition);
   
    
    
    $resultData1 = "";
    if(count($optionalSubjectArray)>0) {
        for($i=0; $i<count($subjectListArray); $i++) {
           $subName = $subjectListArray[$i]['subjectName']; 
           $subCode = $subjectListArray[$i]['subjectCode']; 
           if($subjectListArray[$i]['hasParentCategory']==1) { 
              $resultData   .= " <td width='5%' class='searchhead_text' align='right'><strong>$subName</strong></td>
                                 <td width='5%' class='searchhead_text' align='right'><strong>$subCode</strong></td>";  
              $resultData1 .=  " <td width='5%' class='searchhead_text' align='right'><strong>".NOT_APPLICABLE_STRING."</strong></td>
                                 <td width='5%' class='searchhead_text' align='right'><strong>".NOT_APPLICABLE_STRING."</strong></td>";  
              $countColSpan+=2;                
           }
           else {
              $resultData  .=  "<td width='5%' class='searchhead_text' align='right'><strong>$subCode</strong></td>"; 
              $resultData1 .=  "<td width='5%' class='searchhead_text' align='right'><strong>".NOT_APPLICABLE_STRING."</strong></td>";  
              $countColSpan++;
           }
        }
    }                                   
    else {
        for($i=0; $i<count($subjectListArray); $i++) { 
           $subName = $subjectListArray[$i]['subjectName']; 
           $subCode = $subjectListArray[$i]['subjectCode'];   
           $resultData .=  "<td width='5%' class='searchhead_text' align='right'><strong>$subCode</strong></td>";
           $countColSpan++;
        }
    }    
    $resultData .= "</tr>";  
        

    
    // fetch all students 
    $condition = "AND sg.classId IN ($classId) ";
    $studentArray = $studentManager->getFoxproStudentInfo($condition, $orderBy );
    $studentCount = count($studentArray);
    
    
    // fetch final marks 
    $condition = "AND ttm1.classId IN ($classId)  ";
    $finalMarksArray = $studentManager->getFoxproFinalMarksInfo($condition);
    $finalMarksCount = count($finalMarksArray);
    
    if($finalMarksCount==0) {
       $resultData .= "<tr><td colspan='$countColSpan' align='center'>No Data Found </td></tr>";  
       $resultData .= "</table>";     
       echo $resultData;
       die;
    }

    
   if($studentCount > 0 ) {
       
        $optSubjectFind =  array();
        $optSF = 0;
        for($i=0; $i < $studentCount; $i++) {
            $studentId        =  $studentArray[$i]['studentId'];
            $universityRollNo =  $studentArray[$i]['universityRollNo'];   
            $studentName      =  $studentArray[$i]['studentName'];   
            $fatherName       =  $studentArray[$i]['fatherName'];   

            $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
            $checkall = '<input type="checkbox" name="chb[]"  value="'.$studentId.'">';
            $resultData .= "<tr class='$bg'>
                           <td valign='top' class='padding_top' align='left'>".($i+1)."</td>  
                           <td valign='top' class='padding_top' align='left'>".$checkall."</td>  
                           <td valign='top' class='padding_top' align='left'>".$universityRollNo."</td>
                           <td valign='top' class='padding_top' align='left'>".$studentName."</td> 
                           <td valign='top' class='padding_top' align='left'>".$fatherName."</td>";
          
           // Fetch Subject Information     
           $find=0;
           $findPos=-1;
           for($k=0;$k<$finalMarksCount; $k++) {
             if($finalMarksArray[$k]['studentId']==$studentId) {
                $findPos=$k;
                $find=1; 
                break; 
             }  
           }
           
           
           if($find==0) {
             $resultData .= $resultData1;   
           }
           else if($find==1) { 
                // Fetch Subject Information       
                if(count($optionalSubjectArray)>0) {
                    for($j=0; $j<count($subjectListArray); $j++) {
                      $chkFind=0;  
                      $subjectId   = $subjectListArray[$j]['subjectId']; 
                      $subjectMarks=NOT_APPLICABLE_STRING;  
                      $smks = $findPos;
                      
                      if($subjectListArray[$j]['hasParentCategory']==0) { 
                         while($smks<$finalMarksCount) {
                            $studentIdF = $finalMarksArray[$smks]['studentId'];     
                            $subjectIdF = $finalMarksArray[$smks]['subjectId'];  
                            if($studentIdF==$studentId && $subjectIdF==$subjectId) {
                              $subjectMarks=$finalMarksArray[$smks]['marksScored'];
                              break;
                            }
                            if($studentIdF!=$studentId) {
                               break;  
                            }
                            $smks++;
                         } 
                      }
                      else {
                         $subjectMarks=NOT_APPLICABLE_STRING;  
                         $optSubjectCode = NOT_APPLICABLE_STRING;
                         
                         $smks = $findPos;
                         $chkOptional=0;  
                         $chkFind=0;   
                         
                         // Fetch Option Subject  Information     
                         $optFind=0;
                         $optFindPos=-1;
                         for($k=0;$k<count($optionalSubjectArray1); $k++) {
                             $optSubjectId       = $optionalSubjectArray1[$k]['parentSubjectId']; 
                             $optStudentId       = $optionalSubjectArray1[$k]['studentId']; 
                          
                             if($optSubjectId==$subjectId && $optStudentId==$studentId ) {    
                                $optFindPos=$k;
                                $optFind=1; 
                                break; 
                             }  
                         }
                        
                         for($osub=$optFindPos; $osub<count($optionalSubjectArray1); $osub++) { 
                            $optParentSubjectId = $optionalSubjectArray1[$osub]['parentSubjectId'];
                            $optSubjectId       = $optionalSubjectArray1[$osub]['subjectId']; 
                            $optStudentId       = $optionalSubjectArray1[$osub]['studentId']; 
                            
                            if($optParentSubjectId==$subjectId && $optStudentId==$studentId ) {
                                while($smks<$finalMarksCount) {
                                   $studentIdF = $finalMarksArray[$smks]['studentId'];     
                                   $subjectIdF = $finalMarksArray[$smks]['subjectId'];  
                                   for($kF=0; $kF<count($optSubjectFind); $kF++) {
                                     if($optSubjectFind[$kF]['optSubjectId'] == $optSubjectId && $optSubjectFind[$kF]['optStudentId'] == $studentId && $optSubjectFind[$kF]['parentSubjectId'] == $optParentSubjectId) {
                                       $chkOptional=1;
                                       break;  
                                     }
                                   }
                                   if($chkOptional==0) {
                                     if($studentIdF==$studentId && $subjectIdF==$optSubjectId && $optStudentId == $studentId) {
                                        $optSubjectFind[$optSF]['parentSubjectId'] =  $optParentSubjectId;  
                                        $optSubjectFind[$optSF]['optSubjectId'] =  $optSubjectId;
                                        $optSubjectFind[$optSF]['optStudentId'] =  $studentId;
                                        $optSF++; 
                                        $chkFind=1;      
                                        $optSubjectCode  = $optionalSubjectArray1[$osub]['subjectCode'];  
                                        $subjectMarks=$finalMarksArray[$smks]['marksScored'];
                                        break;
                                     }
                                   }
                                   if($studentIdF!=$studentId) {
                                      break;  
                                   }
                                   $smks++;
                                } 
                            }
                            if($chkFind=1) {
                              break;
                            }
                         }
                     }
                     if($chkFind==1) {
                       $resultData .= "<td valign='top' class='padding_top' align='right'>".$optSubjectCode."</td>"; 
                     }
                     $resultData .= "<td valign='top' class='padding_top' align='right'>".$subjectMarks."</td>";
                 }
                 $resultData .= "</tr>"; 
              }
              else {
                  for($j=0; $j<count($subjectListArray); $j++) {
                      $chkFind=0;  
                      $subjectId   = $subjectListArray[$j]['subjectId']; 
                      $subjectMarks=NOT_APPLICABLE_STRING;  
                      $smks = $findPos;
                      if($subjectListArray[$j]['hasParentCategory']==0) { 
                         while($smks<$finalMarksCount) {
                            $studentIdF = $finalMarksArray[$smks]['studentId'];     
                            $subjectIdF = $finalMarksArray[$smks]['subjectId'];  
                            if($studentIdF==$studentId && $subjectIdF==$subjectId) {
                              $subjectMarks=$finalMarksArray[$smks]['marksScored'];
                              break;
                            }
                            if($studentIdF!=$studentId) {
                               break;  
                            }
                            $smks++;
                         } 
                      } 
                     $resultData .= "<td valign='top' class='padding_top' align='right'>".$subjectMarks."</td>";  
                  }
                  $resultData .= "</tr>";   
              }
          }  
      }
   }
   else {
      $resultData .= "<tr><td colspan='$countColSpan' align='center'>No Data Found </td></tr>";  
   }
    
   $resultData .= "</table>";    
    
   echo $resultData;
   

//$History: initStudentList.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 3/27/10    Time: 5:18p
//Updated in $/LeapCC/Library/StudentReports
//all query format updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 3/02/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//validation & condition format updated 
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 9  *****************
//User: Parveen      Date: 2/05/10    Time: 5:40p
//Updated in $/LeapCC/Library/StudentReports
//condition format updated (student_group base check updated)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 2/05/10    Time: 1:03p
//Updated in $/LeapCC/Library/StudentReports
//Time Table Label base format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/02/10    Time: 11:36a
//Updated in $/LeapCC/Library/StudentReports
//sorting format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/06/10    Time: 4:12p
//Updated in $/LeapCC/Library/StudentReports
//blank values check added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/18/09   Time: 5:31p
//Updated in $/LeapCC/Library/StudentReports
//sorting format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/21/09   Time: 1:24p
//Updated in $/LeapCC/Library/StudentReports
//format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Library/StudentReports
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/30/09    Time: 3:55p
//Updated in $/LeapCC/Library/StudentReports
//access right added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/30/09    Time: 2:19p
//Created in $/LeapCC/Library/StudentReports
//file added
//

?>