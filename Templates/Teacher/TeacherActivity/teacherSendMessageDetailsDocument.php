<?php
//--------------------------------------------------------  
//  This File to create a document file to store Send Message Details 
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once($FE . "/Library/HtmlFunctions.inc.php");      
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();

    $messageContents ='';         
    $emailContents ='';         
    $smsContents ='';         
    
  
    //  Employee Information
    if($REQUEST_DATA['type']=='e') {
        $tableHead =  '<h2 align="center">The <MESSAGE> message could not be sent to following employee(s) </h2> <br>
                       <table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                            <tr>
                                <th align="left" width="5%">Sr. No.</th>             
                                <th align="left" width="15%">Name</th> 
                                <th align="left" width="10%">Code</th> 
                                <th align="left" width="15%">Designation</th>
                                <th align="left" width="10%">Teaching</th> 
                                <th align="left" width="10%">Branch</th> 
                                <th align="left" width="15%"><MESSAGE></th> 
                             </tr>';
    
        $record=$sendMessageManager->getEmployeeList(" AND e.employeeId IN(".$REQUEST_DATA['emailEmployeeIds'].")");     
        if(count($record) > 0) {
            $emailContents = $tableHead;            
            $emailContents = str_replace("<MESSAGE>","Email",$emailContents); 
            $cnt = count($record);                                                                         
            for($i=0; $i<$cnt; $i++) {
                $emailContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td> 
                                       <td align="left">'.$record[$i]['employeeName'].'</td> 
                                       <td align="left">'.$record[$i]['employeeCode'].'</td> 
                                       <td align="left">'.$record[$i]['designationName'].'</td> 
                                       <td align="left">'.$record[$i]['isTeaching'].'</td> 
                                       <td align="left">'.$record[$i]['branchCode'].'</td> 
                                       <td align="left">'.$record[$i]['emailAddress'].'</td> 
                                    </tr>';
            }
            $emailContents .= '</table>';   
        }
        
        /*
        $record=$sendMessageManager->getEmployeeList(" AND e.employeeId IN(".$REQUEST_DATA['smsEmployeeIds'].")");         
        if(count($record) > 0) {
            $smsContents = $tableHead;            
            $smsContents = str_replace("<MESSAGE>","Mobile",$smsContents);      
            $cnt = count($record);                                                                      
            for($i=0; $i<$cnt; $i++) {
                $smsContents .= '<tr> 
                                     <td align="left">'.($i+1).'</td>    
                                     <td align="left">'.$record[$i]['employeeName'].'</td> 
                                     <td align="left">'.$record[$i]['employeeCode'].'</td> 
                                     <td align="left">'.$record[$i]['designationName'].'</td> 
                                     <td align="left">'.$record[$i]['isTeaching'].'</td> 
                                     <td align="left">'.$record[$i]['branchCode'].'</td> 
                                     <td align="left">'.$record[$i]['mobileNumber'].'</td> 
                                 </tr>';
            }
            $smsContents .= '</table>';                                                     
        }
        */
    }

    //  Parent Information
    else if($REQUEST_DATA['type']=='p') {
        $headNameE = "<h2 align='center'>The e-mail could not be sent to following parent(s) </h2> <br>";
        $tableHead =  "<table width='100%' border='0' cellpadding='5px' cellspacing='0px' align='center' class='reportData'>
                            <tr> 
                                <th align='left' width='5%'>Sr. No.</th>
                                <th align='left' width='15%'>Name</th> 
                                <th align='left' width='10%'>Roll No</th> 
                                <th align='left' width='10%'>Class</th> 
                                <th align='left' width='15%'><NAME> Name</th> 
                                <th align='left' width='15%'><MESSAGE></th> 
                             </tr>";
    
        // Email Information
        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['emailFatherIds'].")");     
        if(count($record) > 0) {
            $emailContents = $tableHead;            
            $emailContents = str_replace("<MESSAGE>","message",$emailContents); 
            $emailContents = str_replace("<NAME>","Father's",$emailContents); 
            $cnt = count($record);                                                                         
            for($i=0; $i<$cnt; $i++) {
                $emailContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td> 
                                       <td align="left">'.$record[$i]['studentName'].'</td> 
                                       <td align="left">'.$record[$i]['rollNo'].'</td> 
                                       <td align="left">'.$record[$i]['className'].'</td> 
                                       <td align="left">'.$record[$i]['fatherName'].'</td> 
                                       <td align="left">'.$record[$i]['fatherEmail'].'</td> 
                                    </tr>';
            }
            $emailContents .= '</table>';   
            $emailContentsF = $emailContents;
        }
        
        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['emailMotherIds'].")");     
        if(count($record) > 0) {
            $emailContents = $tableHead;            
            $emailContents = str_replace("<MESSAGE>","Email",$emailContents); 
            $emailContents = str_replace("<NAME>","Mother's",$emailContents);  
            $cnt = count($record);                                                                         
            for($i=0; $i<$cnt; $i++) {
                $emailContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td> 
                                       <td align="left">'.$record[$i]['studentName'].'</td> 
                                       <td align="left">'.$record[$i]['rollNo'].'</td> 
                                       <td align="left">'.$record[$i]['className'].'</td> 
                                       <td align="left">'.$record[$i]['motherName'].'</td> 
                                       <td align="left">'.$record[$i]['motherEmail'].'</td> 
                                    </tr>';
            }
            $emailContents .= '</table>';   
            $emailContentsM = $emailContents;
        }

        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['emailGuardianIds'].")");     
        if(count($record) > 0) {
            $emailContents = $tableHead;            
            $emailContents = str_replace("<MESSAGE>","Email",$emailContents); 
            $emailContents = str_replace("<NAME>","Guardian's",$emailContents); 
            $cnt = count($record);                                                                         
            for($i=0; $i<$cnt; $i++) {
                $emailContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td> 
                                       <td align="left">'.$record[$i]['studentName'].'</td> 
                                       <td align="left">'.$record[$i]['rollNo'].'</td> 
                                       <td align="left">'.$record[$i]['className'].'</td> 
                                       <td align="left">'.$record[$i]['guardianName'].'</td> 
                                       <td align="left">'.$record[$i]['guardianEmail'].'</td> 
                                    </tr>';
            }
            $emailContents .= '</table>';
            $emailContentsG = $emailContents; 
        }
       
       
       
     /* // SMS Information
        $headNameS = "<h2 align='center'>The mobile message could not be sent to following parent(s) </h2> <br>";
        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['smsFatherIds'].")");         
        if(count($record) > 0) {
            $smsContents = $tableHead;            
            $smsContents = str_replace("<MESSAGE>","Mobile",$smsContents);    
            $smsContents  = str_replace("<NAME>","Father's",$smsContents);   
            $cnt = count($record);                                                                      
            for($i=0; $i<$cnt; $i++) {
                $smsContents .= '<tr> 
                                     <td align="left">'.($i+1).'</td> 
                                     <td align="left">'.$record[$i]['studentName'].'</td> 
                                     <td align="left">'.$record[$i]['rollNo'].'</td> 
                                     <td align="left">'.$record[$i]['className'].'</td> 
                                     <td align="left">'.$record[$i]['fatherName'].'</td> 
                                     <td align="left">'.$record[$i]['fatherMobileNo'].'</td> 
                                 </tr>';
            }
            $smsContents .= '</table>';                                                     
            $smsContentsF = $smsContents;
        }
        
       
        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['smsMotherIds'].")");         
        if(count($record) > 0) {
            $smsContents = $tableHead;            
            $smsContents = str_replace("<MESSAGE>","Mobile",$smsContents);      
            $smsContents = str_replace("<NAME>","Mother's",$smsContents);    
            $cnt = count($record);                                                                      
            for($i=0; $i<$cnt; $i++) {
                $smsContents .= '<tr> 
                                     <td align="left">'.($i+1).'</td> 
                                     <td align="left">'.$record[$i]['studentName'].'</td> 
                                     <td align="left">'.$record[$i]['rollNo'].'</td> 
                                     <td align="left">'.$record[$i]['className'].'</td> 
                                     <td align="left">'.$record[$i]['motherName'].'</td> 
                                     <td align="left">'.$record[$i]['motherMobileNo'].'</td> 
                                 </tr>';
            }
            $smsContents .= '</table>';      
            $smsContentsM = $smsContents;                                                               
        }
        
        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['smsGuardianIds'].")");         
        if(count($record) > 0) {
            $smsContents = $tableHead;            
            $smsContents = str_replace("<MESSAGE>","Mobile",$smsContents);      
            $smsContents = str_replace("<NAME>","Guardian",$smsContents);   
            $cnt = count($record);                                                                      
            for($i=0; $i<$cnt; $i++) {
                $smsContents .= '<tr> 
                                     <td align="left">'.($i+1).'</td> 
                                     <td align="left">'.$record[$i]['studentName'].'</td> 
                                     <td align="left">'.$record[$i]['rollNo'].'</td> 
                                     <td align="left">'.$record[$i]['className'].'</td> 
                                     <td align="left">'.$record[$i]['guardianName'].'</td> 
                                     <td align="left">'.$record[$i]['guardianMobileNo'].'</td> 
                                 </tr>';
            }
            $smsContents .= '</table>';     
            $smsContentsG = $smsContents;
        } 
        */ 
    }
    
    //  Student Information
    else if($REQUEST_DATA['type']=='s') {
        $tableHead =  '<h2 align="center">The <MESSAGE> message could not be sent to following student(s) </h2> <br>
                       <table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                            <tr> 
                                <th align="left" width="5%">Sr. No.</th>
                                <th align="left" width="15%">Name</th> 
                                <th align="left" width="15%">Roll No</th> 
                                <th align="left" width="15%">Class</th> 
                                <th align="left" width="15%"><MESSAGE></th> 
                             </tr>';
    
        $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['emailStudentIds'].")");     
        if(count($record) > 0) {
            $emailContents = $tableHead;            
            $emailContents = str_replace("<MESSAGE>","Email",$emailContents); 
            $cnt = count($record);                                                                         
            for($i=0; $i<$cnt; $i++) {
                $emailContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td> 
                                       <td align="left">'.$record[$i]['studentName'].'</td> 
                                       <td align="left">'.$record[$i]['rollNo'].'</td> 
                                       <td align="left">'.$record[$i]['className'].'</td> 
                                       <td align="left">'.$record[$i]['studentEmail'].'</td> 
                                    </tr>';
            }
            $emailContents .= '</table>';   
        } 
       
     /* $record=$sendMessageManager->getStudentList(" AND s.studentId IN(".$REQUEST_DATA['smsStudentIds'].")");         
        if(count($record) > 0) {
            $smsContents = $tableHead;            
            $smsContents = str_replace("<MESSAGE>","Mobile",$smsContents);      
            $cnt = count($record);                                                                      
            for($i=0; $i<$cnt; $i++) {
                $smsContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td>  
                                       <td align="left">'.$record[$i]['studentName'].'</td> 
                                       <td align="left">'.$record[$i]['rollNo'].'</td> 
                                       <td align="left">'.$record[$i]['className'].'</td> 
                                       <td align="left">'.$record[$i]['studentMobileNo'].'</td> 
                                    </tr>';
            }
            $smsContents .= '</table>';                                                     
        }
     */   
    }
    
    if($REQUEST_DATA['type']=='p') {
        $messageContents = $headNameE."<br>".$emailContentsF."<br>".$emailContentsM."<br>".$emailContentsG;
        //."<br><br>".$headNameS."<br>".$smsContentsF."<br>".$smsContentsM."<br>".$smsContentsG;
    }
    else  {
        $messageContents = $emailContents;
        //."<br><br>".$smsContents;
    }
    echo $messageContents;
?>
<?php
//$History: teacherSendMessageDetailsDocument.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/08/09    Time: 2:54p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//file added

?>