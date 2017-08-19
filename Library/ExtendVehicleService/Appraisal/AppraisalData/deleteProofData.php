<?php
//-------------------------------------------------------
// Purpose: To delete proof detail
// Author : Dipanjan Bhattacharjee
// Created on : (19.08.2010 )
// Copyright 2008-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','AppraisalForm');
define('ACCESS','delete');
if(SHOW_EMPLOYEE_APPRAISAL_FORM!=1){
  die(ACCESS_DENIED);
}
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
 UtilityManager::ifManagementNotLoggedIn();
}
else if($roleId!=1 and $roleId>5){
  UtilityManager::ifNotLoggedIn();    
}
else{
  die(ACCESS_DENIED);
}

UtilityManager::headerNoCache();

$proofId=trim($REQUEST_DATA['proofId']);
$appraisalId=trim($REQUEST_DATA['appraisalId']);
$mode=trim($REQUEST_DATA['mode']);

if($proofId<1 or $proofId>34){
    die("This Form Is Not Available");
}

$employeeId=$sessionHandler->getSessionVariable('EmployeeId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

if($proofId=='' or $appraisalId==''){
    echo 'Required Paramaters Missing';
    die;
}

require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
$appDataManager = AppraisalDataManager::getInstance();


function doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvalueation,$sessionId){
   global $appDataManager;
    
  $foundArray=$appDataManager->checkMainAppraisal($appraisalId,$employeeId,$sessionId);
  if($foundArray[0]['cnt']==0){
       //insert fresh data
       $ret=$appDataManager->insertMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvalueation,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
  }
  else{
       //update data
       $ret=$appDataManager->updateMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
   }  
}


if(SystemDatabaseManager::getInstance()->startTransaction()) {
 //fetch previous data
 $previousDataArray=$appDataManager->gerProofData($proofId,$employeeId,$sessionId);
 $fileNameToDelete=''; 
 if($proofId==18){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof18/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData18_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof18/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData18_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks; 
   } 
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==19){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof19/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData19_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof19/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData19_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks; 
   } 
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==20){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof20/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData20_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof20/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData20_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks; 
   } 
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==21){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     if($previousDataArray[0]['act3_marks']==''){
       $act3_marks=0;
     }
     else{
       $act3_marks=$previousDataArray[0]['act3_marks']; 
     }
     
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof21/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData21_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof21/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData21_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks; 
   }
  else if($mode==3){
      if($previousDataArray[0]['file3']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof21/Third/'.trim($previousDataArray[0]['file3']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData21_Adv($employeeId,$sessionId,3);
      if($ret==false){
          die(FAILURE);
      }
      $act3_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks; 
   }  
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==22){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     if($previousDataArray[0]['act3_marks']==''){
       $act3_marks=0;
     }
     else{
       $act3_marks=$previousDataArray[0]['act3_marks']; 
     }
     
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof22/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData22_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof22/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData22_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks; 
   }
  else if($mode==3){
      if($previousDataArray[0]['file3']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof22/Second/'.trim($previousDataArray[0]['file3']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData22_Adv($employeeId,$sessionId,3);
      if($ret==false){
          die(FAILURE);
      }
      $act3_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks; 
   }  
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==23){
     if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
     }
     else{
       $act_marks=$previousDataArray[0]['act_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof23/'.trim($previousDataArray[0]['file']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData23_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act_marks=0;
      $selfEvaluation=$act_marks;
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==24){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof24/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData24_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
    else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof24/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData24_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;  
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==25){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof25/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData25_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
    else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof25/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData25_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;  
    }
   else{
       die('No records selected for deletion');
   }
}


else if($proofId==26){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     if($previousDataArray[0]['act3_marks']==''){
       $act3_marks=0;
     }
     else{
       $act3_marks=$previousDataArray[0]['act3_marks']; 
     }
     
     if($previousDataArray[0]['act4_marks']==''){
       $act4_marks=0;
     }
     else{
       $act4_marks=$previousDataArray[0]['act4_marks']; 
     }
     
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof26/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData26_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks+$act4_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof26/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData26_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks+$act4_marks;
   }
  else if($mode==3){
      if($previousDataArray[0]['file3']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof26/Third/'.trim($previousDataArray[0]['file3']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData26_Adv($employeeId,$sessionId,3);
      if($ret==false){
          die(FAILURE);
      }
      $act3_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks+$act4_marks;
   }
 else if($mode==4){
      if($previousDataArray[0]['file4']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof26/Fourth/'.trim($previousDataArray[0]['file4']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData26_Adv($employeeId,$sessionId,4);
      if($ret==false){
          die(FAILURE);
      }
      $act4_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks+$act3_marks+$act4_marks;
   }      
   else{
       die('No records selected for deletion');
   }
}


else if($proofId==27){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof27/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData27_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof27/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData27_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
   }
   else{
       die('No records selected for deletion');
   }
}


else if($proofId==28){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof28/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData28_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
   else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof28/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData28_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
   }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==29){
     if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
     }
     else{
       $act_marks=$previousDataArray[0]['act_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof29/'.trim($previousDataArray[0]['file']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData29_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act_marks=0;
      $selfEvaluation=$act_marks;
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==30){
     if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
     }
     else{
       $act_marks=$previousDataArray[0]['act_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof30/'.trim($previousDataArray[0]['file']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData30_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act_marks=0;
      $selfEvaluation=$act_marks;
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==31){
     if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
     }
     else{
       $act_marks=$previousDataArray[0]['act_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof31/'.trim($previousDataArray[0]['file']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData31_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act_marks=0;
      $selfEvaluation=$act_marks;
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==32){
     if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
     }
     else{
       $act_marks=$previousDataArray[0]['act_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof32/'.trim($previousDataArray[0]['file']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData32_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act_marks=0;
      $selfEvaluation=$act_marks;
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==33){
     if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
     }
     else{
       $act_marks=$previousDataArray[0]['act_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof33/'.trim($previousDataArray[0]['file']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData33_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act_marks=0;
      $selfEvaluation=$act_marks;
    }
   else{
       die('No records selected for deletion');
   }
}

else if($proofId==34){
     if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
     }
     else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
     }
     
     if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
     }
     else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
     }
     
     if($mode==1){
      if($previousDataArray[0]['file1']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof34/First/'.trim($previousDataArray[0]['file1']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData34_Adv($employeeId,$sessionId,1);
      if($ret==false){
          die(FAILURE);
      }
      $act1_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
    else if($mode==2){
      if($previousDataArray[0]['file2']!=''){
         $fileNameToDelete=IMG_PATH.'/Appraisal/Proof34/Second/'.trim($previousDataArray[0]['file2']);
      }
      
      //delete proof data
      $ret=$appDataManager->deleteProofData34_Adv($employeeId,$sessionId,2);
      if($ret==false){
          die(FAILURE);
      }
      $act2_marks=0;
      $selfEvaluation=$act1_marks+$act2_marks;
    }
   else{
       die('No records selected for deletion');
   }
}


//now delete the proof file
if($fileNameToDelete!=''){
  if(file_exists($fileNameToDelete)){
     @unlink($fileNameToDelete);
  }
}
//now update main appraisal table
doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);   
 
 
 /******************COMMITING UPDATIONS******************/
 if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo DELETE.'~'.$selfEvaluation;
   die;
 }
 else {
   echo FAILURE;
   die;
 }
}
else{
    die(FAILURE);
}

    
   
    
// $History: ajaxInitDelete.php $    
?>