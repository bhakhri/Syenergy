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
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();

    $messageContents ='';         
    $emailContents ='';         
    $smsContents ='';         
    
    global $sessionHandler;      
    
    //  Student Information
        $tableHead =  '<h2 align="center">The <MESSAGE> message could not be sent to following student(s) </h2> <br>
                       <table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                            <tr> 
                                <th align="left" width="5%">Sr. No.</th>
                                <th align="left" width="15%">Name</th> 
                                <th align="left" width="15%">Comp. Exam. By</th> 
                                <th align="left" width="15%">Roll No.</th> 
                                <th align="left" width="15%">Rank</th> 
                                <th align="left" width="15%"><MESSAGE></th> 
                             </tr>';
    
        $emailStudentIds=$sessionHandler->getSessionVariable('emailStudentIds');
        $record=$sendMessageManager->getStudentEnquiryData(" WHERE studentId IN(".$emailStudentIds.")"); 
        if(count($record) > 0) {
            $emailContents = $tableHead;            
            $emailContents = str_replace("<MESSAGE>","Email",$emailContents); 
            $cnt = count($record);
            
            for($i=0; $i<$cnt; $i++) {
                $studentName    = trim($record[$i]['firstName']).' '.trim($record[$i]['lastName']);
                $compExamBy     = trim($record[$i]['compExamBy']);
                $compExamRollNo = trim($record[$i]['compExamRollNo']);
                $compExamRank   = trim($record[$i]['compExamRank']);

                if($studentName == '' || $studentName == null) {
                   $studentName = NOT_APPLICABLE_STRING;
                }
                
                if($compExamBy == '' || $compExamBy == null) {
                   $compExamBy = NOT_APPLICABLE_STRING;
                }
                
                if($compExamRollNo == '' || $compExamRollNo == null) {
                   $compExamRollNo = NOT_APPLICABLE_STRING;
                }
                
                if($compExamRank == '' || $compExamRank == null) {
                   $compExamRank = NOT_APPLICABLE_STRING;
                }
                
                $emailContents .= '<tr> 
                                       <td align="left">'.($i+1).'</td> 
                                       <td align="left">'.$studentName.'</td> 
                                       <td align="left">'.$compExamBy.'</td> 
                                       <td align="left">'.$compExamRollNo.'</td> 
                                       <td align="left">'.$compExamRank.'</td> 
                                       <td align="left">'.$record[$i]['studentEmail'].'</td> 
                                    </tr>';
            }
            $emailContents .= '</table>';   
        }

    $messageContents = $emailContents;
    
    echo $messageContents;
?>
<?php
//$History: adminSendMessageDetailsDocument.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/24/10    Time: 4:05p
//Created in $/LeapCC/Templates/StudentEnquiry
//initial checkin
//

?>