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
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InternalMarksFoxproReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);  
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();    

require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentManager = StudentReportsManager::getInstance();


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
    
    $studentId = add_slashes($REQUEST_DATA['studentId']); 
    $collegeCode = add_slashes($REQUEST_DATA['collegeCode']); 
    $streamCode = add_slashes($REQUEST_DATA['streamCode']);
    $branchCode = add_slashes($REQUEST_DATA['branchCode']);
    $degreeName = add_slashes($REQUEST_DATA['degreeName']); 
    $classId = add_slashes($REQUEST_DATA['classId']);
    
    if($classId=='') {
      $classId = 0;  
    }
    
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
    if(!is_writable($xmlFilePath) ) {    
        logError("unable to open user activity data xml file...");
        echo  NOT_WRITEABLE_FOLDER; 
        die;
    }   
    
    
    $degreeName = trim(clearSpecialChar($degreeName)); 
   

    // Remove Existing file
    $dbfFileName = $degreeName.'.dbf';
    if(file_exists(TEMPLATES_PATH."/Xml/".$dbfFileName)) {
      @unlink(TEMPLATES_PATH."/Xml/".$dbfFileName);
    }  
  
  
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

    //fetch classId which match the criteria
   
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
    
    
    $subjectHeadArray = array();
    
    $resultData1 = "";
    if(count($optionalSubjectArray)>0) {
        for($i=0; $i<count($subjectListArray); $i++) {                                         
           $subjectListArray[$i]['subjectCode'] =  clearSpecialChar($subjectListArray[$i]['subjectCode']); 
           $subjectListArray[$i]['subjectName'] =  clearSpecialChar($subjectListArray[$i]['subjectName']);  
           if(strlen($subjectListArray[$i]['subjectCode']) > 8 ) {
              $subjectListArray[$i]['subjectCode'] = clearSpecialChar(substr(trim($subjectListArray[$i]['subjectCode']),0,6).trim($i+1));  
           }
           if(strlen($subjectListArray[$i]['subjectName']) > 8 ) {
              $subjectListArray[$i]['subjectName'] = clearSpecialChar(substr(trim($subjectListArray[$i]['subjectName']),0,6).trim($i+1));  
           }
           if($subjectListArray[$i]['subjectCode']=='') {
             $subjectListArray[$i]['subjectCode'] = clearSpecialChar($subjectListArray[$i]['subjectName']);
             $subjectListArray[$i]['subjectCode'] = clearSpecialChar(substr(trim($subjectListArray[$i]['subjectCode']),0,6).trim($i+1));
           }
           $subCode=$subjectListArray[$i]['subjectCode']; 
           $subName=$subjectListArray[$i]['subjectName']; 
           if($subjectListArray[$i]['hasParentCategory']==1) { 
              $subjectHeadArray[$i]['subjectName']=$subName;   
              $subjectHeadArray[$i]['subjectCode']=$subCode;   
              $subjectHeadArray[$i]['hasParentCategory']=$subjectListArray[$i]['hasParentCategory'];
           }
           else {
             $subjectHeadArray[$i]['subjectCode']=$subjectListArray[$i]['subjectCode'];   
             $subjectHeadArray[$i]['hasParentCategory']=$subjectListArray[$i]['hasParentCategory'];
           }
        }
    }                                   
    else {
        for($i=0; $i<count($subjectListArray); $i++) { 
           $subName = $subjectListArray[$i]['subjectName']; 
           $subCode = $subjectListArray[$i]['subjectCode'];   
           if(strlen($subjectListArray[$i]['subjectCode']) > 8 ) {
              $subjectListArray[$i]['subjectCode'] = clearSpecialChar(substr(trim($subjectListArray[$i]['subjectCode']),0,6).trim($i+1));  
           }
           if($subjectListArray[$i]['subjectCode']=='') {
             $subjectListArray[$i]['subjectCode'] = clearSpecialChar($subjectListArray[$i]['subjectName']);
             $subjectListArray[$i]['subjectCode'] = clearSpecialChar(substr(trim($subjectListArray[$i]['subjectCode']),0,6).trim($i+1));
           }
           $subCode=$subjectListArray[$i]['subjectCode']; 
           $subjectHeadArray[$i]['subjectCode']=$subjectListArray[$i]['subjectCode'];    
        }
    }    
    
   
        

    
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
        
        define ('BOOLEAN_FIELD',   'L');
        define ('CHARACTER_FIELD', 'C');
        define ('DATE_FIELD',      'D');
        define ('NUMBER_FIELD',    'N');

        // Constants for dbf file open modes
        define ('READ_ONLY',  '0');
        define ('WRITE_ONLY', '1');
        define ('READ_WRITE', '2');
        
        
        // Remove Existing file
        $dbfFileName = $degreeName.'.dbf';
        if(file_exists(TEMPLATES_PATH."/Xml/".$dbfFileName)) {
          @unlink(TEMPLATES_PATH."/Xml/".$dbfFileName);
        }  
        
        
        // Path to dbf file
        $db_file = TEMPLATES_PATH."/Xml/".$degreeName.'.dbf';     

        // dbf database definition
        // Each element in the first level of the array represents a row
        // Each array stored in the various elements represent the properties for the row
        $subject = array();
        $subject[] = array('collcode',    CHARACTER_FIELD,  10);    # char  
        $subject[] = array('strecode',    CHARACTER_FIELD,  10);    # char  
        $subject[] = array('brchcode',    CHARACTER_FIELD,  10);    # char  
        $subject[] = array('branch',   CHARACTER_FIELD,  30);    # char  
        $subject[] = array('cname',    CHARACTER_FIELD,  80);    # char  
        $subject[] = array('urollno',  CHARACTER_FIELD,  20);    # char 
        $subject[] = array('name',     CHARACTER_FIELD,  30);    # char
        $subject[] = array('fname',    CHARACTER_FIELD,  40);    # char
        if(count($optionalSubjectArray)>0) {
          for($k=0; $k<count($subjectHeadArray); $k++) { 
            if($subjectHeadArray[$k]['hasParentCategory']==1) {
              $subject[] = array($subjectHeadArray[$k]['subjectName'], CHARACTER_FIELD, 20);    # char          
              $subject[] = array($subjectHeadArray[$k]['subjectCode'], NUMBER_FIELD, 4, 0 );    # number  
            }
            else {
              $subject[] = array($subjectHeadArray[$k]['subjectCode'], NUMBER_FIELD, 4, 0 );    # number       
            }
          }                                    
        }
        else {
          for($k=0; $k<count($subjectHeadArray); $k++) {      
            $subject[] = array($subjectHeadArray[$k]['subjectCode'], NUMBER_FIELD, 4, 0 );    # number       
          }                                      
        }

        $dbase_definition = array_merge($subject);  
        
        # create dbf file using the
        $create = dbase_create($db_file, $dbase_definition);

        // open dbf file for reading and writing
        $id =  dbase_open ($db_file, READ_WRITE);
        //$id =  dbase_open ($db_file, WRITE_ONLY);
        

        
        $valueArray = array();    
        // Fetch Student Information
        for($i=0; $i < $studentCount; $i++) {
            $branch     = $studentArray[$i]['branchName'];
            $cname      = $studentArray[$i]['instituteName'];
            $urollno    = $studentArray[$i]['universityRollNo'];
            $name       = $studentArray[$i]['studentName'];
            $fname      = $studentArray[$i]['fatherName'];
            $studentId  = $studentArray[$i]['studentId'];

          
           // Fetch Subject Information  
           $jmks = 0;   
           $mks = array();
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
                      $subjectMarks=null; 
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
                         $subjectMarks=null;   
                         $optSubjectCode =null; 
                         
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
                         $mks[$jmks]=$optSubjectCode;
                         $jmks++;
                         //$resultData .= "<td valign='top' class='padding_top' align='right'>".$optSubjectCode."</td>"; 
                     }
                     $mks[$jmks]=$subjectMarks;
                     $subjectMarks=null; 
                     $jmks++;
                     //$resultData .= "<td valign='top' class='padding_top' align='right'>".$subjectMarks."</td>";
                 }
              }
              else {
                  for($j=0; $j<count($subjectListArray); $j++) {
                      $chkFind=0;  
                      $subjectId   = $subjectListArray[$j]['subjectId']; 
                      $subjectMarks=null;  
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
                     $mks[$jmks]=$subjectMarks;
                     $subjectMarks=null;
                     $jmks++;  
                  }
            }
         }
         if($find==1) {
           $valueArray = array_merge(array($collegeCode, $streamCode, $branchCode, $branch, $cname, $urollno, $name, $fname),$mks);      
           dbase_add_record ($id, $valueArray);
        }
     }
      
        // close the dbf file
        dbase_close($id);
        echo $degreeName;
        die;
    }  
    else {
       echo FOXPRO_LIST_EMPTY;
    }
?>