<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(BL_PATH.'/HtmlFunctions.inc.php');
if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
 define('MODULE','EmployeeAppraisal');
}
else{
 define('MODULE','AppraisalForm');   
}
define('ACCESS','edit');
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
require_once(BL_PATH.'/helpMessage.inc.php');
$proofId=trim($REQUEST_DATA['proofId']);
$appraisalId=trim($REQUEST_DATA['appraisalId']);

if($proofId<1 or $proofId>34){
    die("This Form Is Not Available");
}


if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
 $employeeId=$sessionHandler->getSessionVariable('EmployeeToBeAppraised');
 $employeeCode=$sessionHandler->getSessionVariable('EmployeeCodeToBeAppraised');
 $genEmployeeId=$sessionHandler->getSessionVariable('EmployeeId'); //for special forms where HOD has access
}
else{
  $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
  $employeeCode=$sessionHandler->getSessionVariable('EmployeeCode');
  $genEmployeeId=$employeeId;
}

$sessionId=$sessionHandler->getSessionVariable('SessionId');

if($proofId=='' or $appraisalId==''){
    echo 'Required Paramaters Missing';
    die;
}


require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
$appDataManager = AppraisalDataManager::getInstance();

//determine type of proof form
$typeArray=$appDataManager->getProofFormType($proofId);
if(count($typeArray)==0){
    echo 'This Proof Form Is Not Available';
    die;
}

$proofType=$typeArray[0]['editableBySelf'];
$disabled='';
if($proofType==0){
 $disabled='disabled="disabled"';    
}

$not_applicable_form_string='If Not Applicable ,Simply Write NA';

//get appropriate proof data
$proofArray=$appDataManager->gerProofData($proofId,$employeeId,$sessionId);

//get weightage
$weightageArray=$appDataManager->gerWeightageData($appraisalId);
if(trim($weightageArray[0]['appraisalWeightage'])==''){
    $weightageArray[0]['appraisalWeightage']=NOT_APPLICABLE_STRING;
}
$weightage=$weightageArray[0]['appraisalWeightage'];

$hodEditDisabled='';	
$hodEditFlag=1;
if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
    $hodEditDisabled='style="display:none"';
    $hodEditFlag=0;
}


//building the form
if($proofId==2){
    if(trim($proofArray[0]['cert_process'])==''){
         $proofArray[0]['cert_process']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['devoted'])==''){
         $proofArray[0]['devoted']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['supervision'])==''){
         $proofArray[0]['supervision']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['certification'])==''){
         $proofArray[0]['certification']=0;
    }
    if(trim($proofArray[0]['assistance'])==''){
         $proofArray[0]['assistance']=0;
    }
    if(trim($proofArray[0]['super'])==''){
         $proofArray[0]['super']=0;
    }
    ?>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr>
     <td colspan="6" class="contenttab_internal_rows">
      <b>Proofs Against Assistance In Certification/Inspection Process (Max : <?php echo $weightage; ?>) </b> <?php echo HtmlFunctions::getInstance()->getHelpLink('help for assistance in certification process',HELP_FOR_ASSISTANCE_IN_CERTIFICATION_INSPECTION_PROCESS); ?></b>
     </td>
    </tr>
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr class="rowheading">
     <td colspan="3" class="searchhead_text" align="center"><b>Key</b></td>
     <td class="searchhead_text" align="center"><b>Description</b></td>
     <td class="searchhead_text" align="center"><b>Marks</b></td>
    </tr> 
     
    <tr>
       <td colspan="2" class="contenttab_internal_rows" valign="top"><b>Assistance In Certification<br/> Process(10)</b></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" valign="top">
           <textarea <?php echo $disabled; ?> name="cert_process" id="cert_process" rows="5" cols="50" maxlength="449" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['cert_process']); ?></textarea>
       </td>
       <td class="padding" valign="top">
        <input type="text" alt="10" <?php echo $disabled; ?> value="<?php echo trim($proofArray[0]['certification']); ?>" name="certification" id="certification" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
     <tr>
       <td colspan="2" class="contenttab_internal_rows" valign="top"><b>Number Of Hours Devoted<br/> In Preparing Documents(20)</b></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" valign="top">
           <textarea <?php echo $disabled; ?> name="devoted" id="devoted" rows="5" cols="50" maxlength="449" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['devoted']); ?></textarea>
       </td>
       <td class="padding" valign="top">
        <input type="text" alt="20" <?php echo $disabled; ?> value="<?php echo trim($proofArray[0]['assistance']); ?>" name="assistance" id="assistance" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
      <tr>
       <td colspan="2" class="contenttab_internal_rows" valign="top"><b>Supervision/Consolidation<br/> Of Documents(20)</b></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" valign="top">
           <textarea <?php echo $disabled; ?> name="supervision" id="supervision" rows="5" cols="50" maxlength="449" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['supervision']); ?></textarea>
       </td>
       <td class="padding" valign="top">
        <input type="text" alt="20" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['super']); ?>" name="superValue" id="superValue" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
      </tr>
      <tr>
     <tr> 
      <td align="center" colspan="6" <?php echo $hodEditDisabled ?> >
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm2();return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
        </td>
      </tr>
  </table>
    <?php
}
else if($proofId==3){
    if(trim($proofArray[0]['central'])==''){
         $proofArray[0]['central']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['facilities'])==''){
         $proofArray[0]['facilities']=0;
    }
    ?>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr>
     <td colspan="6" class="contenttab_internal_rows">
      <b>Proofs Against Development of Central Facilities (Max : <?php echo $weightage; ?>) </b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for development of central facilities',HELP_FOR_DEVELOPMENT_OF_CENTRAL_FACILITIES); ?></b>
     </td>
    </tr>
    <tr class="rowheading">
     <td colspan="3" class="searchhead_text" align="center"><b>Key</b></td>
     <td class="searchhead_text" align="center"><b>Description</b></td>
     <td class="searchhead_text" align="center"><b>Marks</b></td>
    </tr>
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr>
       <td colspan="2" class="contenttab_internal_rows" valign="top"><b>Write a Paragraph How You<br/> Contributed In Development of Central Facilities</b></td>
       <td class="padding" valign="top">:</td>
       <td class="padding" valign="top">
           <textarea <?php echo $disabled; ?> name="central" id="central" rows="5" cols="60" maxlength="449" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['central']); ?></textarea>
       </td>
       <td class="padding" valign="top">
        <input type="text" alt="50" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['facilities']); ?>" name="facilities" id="facilities" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
    <tr> 
      <td align="center" colspan="6" <?php echo $hodEditDisabled ?>>
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm3();return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
        </td>
      </tr>
  </table>
    <?php
}

else if($proofId==4){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['act1'])==''){
         $proofArray[0]['act1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act2'])==''){
         $proofArray[0]['act2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act3'])==''){
         $proofArray[0]['act3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act4'])==''){
         $proofArray[0]['act4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act5'])==''){
         $proofArray[0]['act5']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act6'])==''){
         $proofArray[0]['act6']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act7'])==''){
         $proofArray[0]['act7']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act8'])==''){
         $proofArray[0]['act8']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act9'])==''){
         $proofArray[0]['act9']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act10'])==''){
         $proofArray[0]['act10']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    for($i=1;$i<21;$i++){
      if($proofArray[0]['test_input'.$i]=='' or $proofArray[0]['test_input'.$i]=='0000-00-00'){
       $proofArray[0]['test_input'.$i]=''; 
      }    
    }
  ?>
     <table border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td colspan="6" align="left" class="contenttab_internal_rows">
         <b>Arranging Meeting/Activity of Professional Bodies(Max : <?php echo $weightage; ?>)</b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for Arranging Meeting/Activity of Professional Bodies',HELP_FOR_ARRANGING_MEETING_ACTIVITY_OF_PROFEESIONAL_BODIES); ?></b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Activity</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text" colspan="2" align="center">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Initiation In Setting Up a Professional Society</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act1" id="act1" maxlength="59" value="<?php echo trim($proofArray[0]['act1']); ?>" onchange="updateMarks4_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput1',trim($proofArray[0]['test_input1']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput2',trim($proofArray[0]['test_input2']));
        ?>    
        </td>
        <td class="padding" valign="top">
         <input disabled="disabled" type="text" alt="5" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act1_marks']); ?>" name="act1_marks" id="act1_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act1').value='';document.getElementById('testinput1').value='';document.getElementById('testinput2').value='';document.getElementById('act1_marks').value='0';document.getElementById('act2').value='';document.getElementById('testinput3').value='';document.getElementById('testinput4').value=''">Reset</a>
       </td>
    </tr>
    <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act2" id="act2" maxlength="59" value="<?php echo trim($proofArray[0]['act2']); ?>" onchange="updateMarks4_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput3',trim($proofArray[0]['test_input3']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput4',trim($proofArray[0]['test_input4']));
        ?>
        </td>
     </tr>
     
      <tr class="row1">
        <td class="contenttab_internal_rows" align="left"><b>Arranging & Coordinating Activity</b></td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act3" id="act3" maxlength="59" value="<?php echo trim($proofArray[0]['act3']); ?>" onchange="updateMarks4_2(document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput5',trim($proofArray[0]['test_input5']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput6',trim($proofArray[0]['test_input6']));
        ?>
        </td>
        <td class="padding" valign="top">
         <input disabled="disabled" type="text" alt="25" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act2_marks']); ?>" name="act2_marks" id="act2_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act3').value='';document.getElementById('testinput5').value='';document.getElementById('testinput6').value='';document.getElementById('act2_marks').value='0';document.getElementById('act4').value='';document.getElementById('testinput7').value='';document.getElementById('testinput8').value='';document.getElementById('act5').value='';document.getElementById('testinput9').value='';document.getElementById('testinput10').value='';document.getElementById('act6').value='';document.getElementById('testinput11').value='';document.getElementById('testinput12').value='';">Reset</a>
       </td>
        </tr>
        <tr class="row1"> 
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act4" id="act4" maxlength="59" value="<?php echo trim($proofArray[0]['act4']); ?>" onchange="updateMarks4_2(document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput7',trim($proofArray[0]['test_input7']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput8',trim($proofArray[0]['test_input8']));
        ?>
        </td>
        </tr>
        <tr class="row1">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act5" id="act5" maxlength="59" value="<?php echo trim($proofArray[0]['act5']); ?>" onchange="updateMarks4_2(document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput9',trim($proofArray[0]['test_input9']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput10',trim($proofArray[0]['test_input10']));
        ?>
        </td>
        </tr>
        <tr class="row1">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act6" id="act6" maxlength="59" value="<?php echo trim($proofArray[0]['act6']); ?>" onchange="updateMarks4_2(document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput11',trim($proofArray[0]['test_input11']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput12',trim($proofArray[0]['test_input12']));
        ?>
        </td>
        </tr>      

        <tr class="row0">
         <td class="contenttab_internal_rows" align="left"><b>Providing Assistance</b></td>
         <td class="padding">
          <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act7" id="act7" maxlength="59" value="<?php echo trim($proofArray[0]['act7']); ?>" onchange="updateMarks4_3(document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act3_marks);" />
          </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput13',trim($proofArray[0]['test_input13']));
         ?>
         </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput14',trim($proofArray[0]['test_input14']));
         ?>
         </td> 
         <td class="padding" valign="top">
          <input disabled="disabled" type="text" alt="20" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act3_marks']); ?>" name="act3_marks" id="act3_marks" maxlength="2" class="inputbox" style="width:30px;" />
         </td>
         <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act7').value='';document.getElementById('testinput13').value='';document.getElementById('testinput14').value='';document.getElementById('act3_marks').value='0';document.getElementById('act8').value='';document.getElementById('testinput15').value='';document.getElementById('testinput16').value='';document.getElementById('act9').value='';document.getElementById('testinput17').value='';document.getElementById('testinput18').value='';document.getElementById('act10').value='';document.getElementById('testinput19').value='';document.getElementById('testinput20').value='';">Reset</a>
       </td>
         </tr>
         <tr class="row0">
         <td class="contenttab_internal_rows" align="left">&nbsp;</td>
         <td class="padding">
          <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act8" id="act8" maxlength="59" value="<?php echo trim($proofArray[0]['act8']); ?>" onchange="updateMarks4_3(document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act3_marks);" />
          </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput15',trim($proofArray[0]['test_input15']));
         ?>
         </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput16',trim($proofArray[0]['test_input16']));
         ?>
         </td> 
         </tr>
         <tr class="row0">
         <td class="contenttab_internal_rows" align="left">&nbsp;</td>
         <td class="padding">
          <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act9" id="act9" maxlength="59" value="<?php echo trim($proofArray[0]['act9']); ?>" onchange="updateMarks4_3(document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act3_marks);" />
          </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput17',trim($proofArray[0]['test_input17']));
         ?>
         </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput18',trim($proofArray[0]['test_input18']));
         ?>
         </td> 
         </tr>
         <tr class="row0">
         <td class="contenttab_internal_rows" align="left">&nbsp;</td>
         <td class="padding">
          <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act10" id="act10" maxlength="59" value="<?php echo trim($proofArray[0]['act10']); ?>" onchange="updateMarks4_3(document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act3_marks);" />
          </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput19',trim($proofArray[0]['test_input19']));
         ?>
         </td>
         <td class="padding" align="left">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput20',trim($proofArray[0]['test_input20']));
         ?>
         </td> 
         </tr>
        <tr> 
         <td align="center" colspan="6" <?php echo $hodEditDisabled ?>>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm4();return false;" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
         </td>
       </tr> 
       </table>
    <?php
}

else if($proofId==5){
   $not_applicable_form_string='';
   for($i=1;$i<9;$i++){ 
    if(trim($proofArray[0]['act'.$i])==''){
         $proofArray[0]['act'.$i]=$not_applicable_form_string;
    }
   } 
   for($i=1;$i<17;$i++){
      if($proofArray[0]['test_input'.$i]=='' or $proofArray[0]['test_input'.$i]=='0000-00-00'){
       $proofArray[0]['test_input'.$i]=''; 
      }    
   }
   if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
    ?>
     <table border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td colspan="6" align="left" class="contenttab_internal_rows">
         <b>Developing and Conducting IOHC(Max : <?php echo $weightage; ?>)</b>)</b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for Developing and Conducting IOHC',HELP_FOR_DEVELOPING_AND_CONDUCTING_IOHC); ?></b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Activity</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text" colspan="2" align="center">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Conceiving,Organizing,Developing IOHC(30)</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act1" id="act1" maxlength="59" value="<?php echo trim($proofArray[0]['act1']); ?>" onchange="updateMarks5_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput1',trim($proofArray[0]['test_input1']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput2',trim($proofArray[0]['test_input2']));
        ?>    
        </td>
        <td class="padding" valign="top">
          <input disabled="disabled" type="text" alt="30" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act1_marks']); ?>" name="act1_marks" id="act1_marks" maxlength="2" class="inputbox" style="width:30px;" />
         </td>
         <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act1').value='';document.getElementById('testinput1').value='';document.getElementById('testinput2').value='';document.getElementById('act1_marks').value='0';document.getElementById('act2').value='';document.getElementById('testinput3').value='';document.getElementById('testinput4').value='';document.getElementById('act3').value='';document.getElementById('testinput5').value='';document.getElementById('testinput6').value='';">Reset</a>
       </td>
    </tr>
    <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act2" id="act2" maxlength="59" value="<?php echo trim($proofArray[0]['act2']); ?>" onchange="updateMarks5_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act1_marks);" />
      </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput3',trim($proofArray[0]['test_input3']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput4',trim($proofArray[0]['test_input4']));
        ?>
        </td>
     </tr>
     <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act3" id="act3" maxlength="59" value="<?php echo trim($proofArray[0]['act3']); ?>" onchange="updateMarks5_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act1_marks);" />
      </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput5',trim($proofArray[0]['test_input5']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput6',trim($proofArray[0]['test_input6']));
        ?>
        </td>
     </tr>
     <!--
     <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act4" id="act4" maxlength="59" value="<?php echo trim($proofArray[0]['act4']); ?>" /></td>
       <td class="padding" align="left">
        <?php
            //require_once(BL_PATH.'/HtmlFunctions.inc.php');
            //echo HtmlFunctions::getInstance()->datePicker('testinput7',trim($proofArray[0]['test_input7']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            //require_once(BL_PATH.'/HtmlFunctions.inc.php');
            //echo HtmlFunctions::getInstance()->datePicker('testinput8',trim($proofArray[0]['test_input8']));
        ?>
        </td>
     </tr>
     -->
      <tr class="row1">
        <td class="contenttab_internal_rows" align="left"><b>Executing,Assisting,Developed IOHC(20)</b></td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act4" id="act4" maxlength="59" value="<?php echo trim($proofArray[0]['act4']); ?>" onchange="updateMarks5_2(document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput7',trim($proofArray[0]['test_input7']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput8',trim($proofArray[0]['test_input8']));
        ?>
        </td>
        <td class="padding" valign="top">
          <input disabled="disabled" type="text" alt="20" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act2_marks']); ?>" name="act2_marks" id="act2_marks" maxlength="2" class="inputbox" style="width:30px;" />
         </td>
         <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act4').value='';document.getElementById('testinput7').value='';document.getElementById('testinput8').value='';document.getElementById('act2_marks').value='0';document.getElementById('act5').value='';document.getElementById('testinput9').value='';document.getElementById('testinput10').value='';document.getElementById('act6').value='';document.getElementById('testinput11').value='';document.getElementById('testinput12').value='';document.getElementById('act7').value='';document.getElementById('testinput13').value='';document.getElementById('testinput14').value='';">Reset</a>
       </td>
        </tr>
      <tr class="row1"> 
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act5" id="act5" maxlength="59" value="<?php echo trim($proofArray[0]['act5']); ?>" onchange="updateMarks5_2(document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput9',trim($proofArray[0]['test_input9']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput10',trim($proofArray[0]['test_input12']));
        ?>
        </td>
        </tr>
        <tr class="row1">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act6" id="act6" maxlength="59" value="<?php echo trim($proofArray[0]['act6']); ?>" onchange="updateMarks5_2(document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput11',trim($proofArray[0]['test_input11']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput12',trim($proofArray[0]['test_input12']));
        ?>
        </td>
        </tr>
        
        <tr class="row1">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act7" id="act7" maxlength="59" value="<?php echo trim($proofArray[0]['act7']); ?>" onchange="updateMarks5_2(document.proofForm.act4.value,document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput13',trim($proofArray[0]['test_input13']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput14',trim($proofArray[0]['test_input14']));
        ?>
        </td>
        </tr>      
        <tr> 
         <td align="center" colspan="6" <?php echo $hodEditDisabled ?>>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm5();return false;" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
         </td>
       </tr> 
       </table>
    <?php
}

else if($proofId==6){
    $not_applicable_form_string='';
   for($i=1;$i<15;$i++){ 
    if(trim($proofArray[0]['act'.$i])==''){
         $proofArray[0]['act'.$i]=$not_applicable_form_string;
    }
   } 
   for($i=1;$i<29;$i++){
      if($proofArray[0]['test_input'.$i]=='' or $proofArray[0]['test_input'.$i]=='0000-00-00'){
       $proofArray[0]['test_input'.$i]=''; 
      }    
   }
   if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
   }
    ?>
     <table border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td colspan="6" align="left" class="contenttab_internal_rows">
         <b>Organizing Symposia/Conferences/Workshops(S/C/W) (Max : <?php echo $weightage; ?>)</b>)</b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for Organizing Symposia/Conferences/Workshops(S/C/W)',HELP_FOR_ORGANIZING_SYMPOSIA_CONFERENCES_WORKSHOPS); ?></b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Activity</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text" colspan="2" align="center">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Organizing/Convening(S/C/W) National/International</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act1" id="act1" maxlength="59" value="<?php echo trim($proofArray[0]['act1']); ?>" onchange="updateMarks6_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput1',trim($proofArray[0]['test_input1']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput2',trim($proofArray[0]['test_input2']));
        ?>    
        </td>
        <td class="padding" valign="top">
          <input disabled="disabled" type="text" alt="80" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act1_marks']); ?>" name="act1_marks" id="act1_marks" maxlength="2" class="inputbox" style="width:30px;" />
         </td>
         <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act1').value='';document.getElementById('testinput1').value='';document.getElementById('testinput2').value='';document.getElementById('act1_marks').value='0';document.getElementById('act2').value='';document.getElementById('testinput3').value='';document.getElementById('testinput4').value='';document.getElementById('act3').value='';document.getElementById('testinput5').value='';document.getElementById('testinput6').value='';document.getElementById('act4').value='';document.getElementById('testinput7').value='';document.getElementById('testinput8').value='';">Reset</a>
       </td>
    </tr>
    <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act2" id="act2" maxlength="59" value="<?php echo trim($proofArray[0]['act2']); ?>" onchange="updateMarks6_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput3',trim($proofArray[0]['test_input3']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput4',trim($proofArray[0]['test_input4']));
        ?>
        </td>
     </tr>
     <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act3" id="act3" maxlength="59" value="<?php echo trim($proofArray[0]['act3']); ?>" onchange="updateMarks6_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act1_marks);" />
      </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput5',trim($proofArray[0]['test_input5']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput6',trim($proofArray[0]['test_input6']));
        ?>
        </td>
     </tr>
     <tr class="row0">
      <td class="contenttab_internal_rows">&nbsp;</td>                          
      <td class="padding" align="left">
        <input <?php echo $disabled; ?> class="inputbox" type="text" size="20" name="act4" id="act4" maxlength="59" value="<?php echo trim($proofArray[0]['act4']); ?>" onchange="updateMarks6_1(document.proofForm.act1.value,document.proofForm.act2.value,document.proofForm.act3.value,document.proofForm.act4.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput7',trim($proofArray[0]['test_input7']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput8',trim($proofArray[0]['test_input8']));
        ?>
        </td>
     </tr>
     
      <tr class="row1">
        <td class="contenttab_internal_rows" align="left"><b>Organizing/Convening(S/C/W)National Level</b></td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act5" id="act5" maxlength="59" value="<?php echo trim($proofArray[0]['act5']); ?>" onchange="updateMarks6_2(document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput9',trim($proofArray[0]['test_input9']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput10',trim($proofArray[0]['test_input10']));
        ?>
        </td>
        <td class="padding" valign="top">
          <input disabled="disabled" type="text" alt="40" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act2_marks']); ?>" name="act2_marks" id="act2_marks" maxlength="2" class="inputbox" style="width:30px;" />
         </td>
         <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act5').value='';document.getElementById('testinput9').value='';document.getElementById('testinput10').value='';document.getElementById('act2_marks').value='0';document.getElementById('act6').value='';document.getElementById('testinput11').value='';document.getElementById('testinput12').value='';document.getElementById('act7').value='';document.getElementById('testinput13').value='';document.getElementById('testinput14').value='';document.getElementById('act8').value='';document.getElementById('testinput15').value='';document.getElementById('testinput16').value='';">Reset</a>
       </td>
        </tr>
      <tr class="row1"> 
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act6" id="act6" maxlength="59" value="<?php echo trim($proofArray[0]['act6']); ?>" onchange="updateMarks6_2(document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput11',trim($proofArray[0]['test_input11']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput12',trim($proofArray[0]['test_input12']));
        ?>
        </td>
        </tr>
        <tr class="row1">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act7" id="act7" maxlength="59" value="<?php echo trim($proofArray[0]['act7']); ?>" onchange="updateMarks6_2(document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput13',trim($proofArray[0]['test_input13']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput14',trim($proofArray[0]['test_input14']));
        ?>
        </td>
        </tr>
        <tr class="row1">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act8" id="act8" maxlength="59" value="<?php echo trim($proofArray[0]['act8']); ?>" onchange="updateMarks6_2(document.proofForm.act5.value,document.proofForm.act6.value,document.proofForm.act7.value,document.proofForm.act8.value,document.proofForm.act2_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput15',trim($proofArray[0]['test_input15']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput16',trim($proofArray[0]['test_input16']));
        ?>
        </td>
        </tr>
        
        <tr class="row0">
        <td class="contenttab_internal_rows" align="left"><b>Member of The Committee of (S/C/W)</b></td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act9" id="act9" maxlength="59" value="<?php echo trim($proofArray[0]['act9']); ?>" onchange="updateMarks6_3(document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act11.value,document.proofForm.act12.value,document.proofForm.act13.value,document.proofForm.act14.value,document.proofForm.act3_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput17',trim($proofArray[0]['test_input17']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput18',trim($proofArray[0]['test_input18']));
        ?>
        </td>
        <td class="padding" valign="top">
          <input disabled="disabled" type="text" alt="30" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act3_marks']); ?>" name="act3_marks" id="act3_marks" maxlength="2" class="inputbox" style="width:30px;" />
         </td>
         <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('act9').value='';document.getElementById('testinput17').value='';document.getElementById('testinput18').value='';document.getElementById('act3_marks').value='0';document.getElementById('act10').value='';document.getElementById('testinput19').value='';document.getElementById('testinput20').value='';document.getElementById('act11').value='';document.getElementById('testinput21').value='';document.getElementById('testinput22').value='';document.getElementById('act12').value='';document.getElementById('testinput23').value='';document.getElementById('testinput24').value='';document.getElementById('act13').value='';document.getElementById('testinput25').value='';document.getElementById('testinput26').value='';document.getElementById('act14').value='';document.getElementById('testinput27').value='';document.getElementById('testinput28').value='';">Reset</a>
       </td>
        </tr>
      <tr class="row0"> 
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act10" id="act10" maxlength="59" value="<?php echo trim($proofArray[0]['act10']); ?>" onchange="updateMarks6_3(document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act11.value,document.proofForm.act12.value,document.proofForm.act13.value,document.proofForm.act14.value,document.proofForm.act3_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput19',trim($proofArray[0]['test_input19']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput20',trim($proofArray[0]['test_input20']));
        ?>
        </td>
        </tr>
        <tr class="row0">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act11" id="act11" maxlength="59" value="<?php echo trim($proofArray[0]['act11']); ?>" onchange="updateMarks6_3(document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act11.value,document.proofForm.act12.value,document.proofForm.act13.value,document.proofForm.act14.value,document.proofForm.act3_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput21',trim($proofArray[0]['test_input21']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput22',trim($proofArray[0]['test_input22']));
        ?>
        </td>
        </tr>
        <tr class="row0">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act12" id="act12" maxlength="59" value="<?php echo trim($proofArray[0]['act12']); ?>" onchange="updateMarks6_3(document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act11.value,document.proofForm.act12.value,document.proofForm.act13.value,document.proofForm.act14.value,document.proofForm.act3_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput23',trim($proofArray[0]['test_input23']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput24',trim($proofArray[0]['test_input24']));
        ?>
        </td>
        </tr>
        <tr class="row0">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act13" id="act13" maxlength="59" value="<?php echo trim($proofArray[0]['act13']); ?>" onchange="updateMarks6_3(document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act11.value,document.proofForm.act12.value,document.proofForm.act13.value,document.proofForm.act14.value,document.proofForm.act3_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput25',trim($proofArray[0]['test_input25']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput26',trim($proofArray[0]['test_input26']));
        ?>
        </td>
        </tr>
        <tr class="row0">
        <td class="contenttab_internal_rows" align="left">&nbsp;</td>
        <td class="padding" align="left">
          <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="act14" id="act14" maxlength="59" value="<?php echo trim($proofArray[0]['act14']); ?>" onchange="updateMarks6_3(document.proofForm.act9.value,document.proofForm.act10.value,document.proofForm.act11.value,document.proofForm.act12.value,document.proofForm.act13.value,document.proofForm.act14.value,document.proofForm.act3_marks);" />
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput27',trim($proofArray[0]['test_input27']));
        ?>
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('testinput28',trim($proofArray[0]['test_input28']));
        ?>
        </td>
        </tr>
        
        <tr> 
         <td align="center" colspan="6" <?php echo $hodEditDisabled ?>>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm6();return false;" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
         </td>
       </tr> 
       </table>
    <?php
}
else if($proofId==7){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['newlab'])==''){
         $proofArray[0]['newlab']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['major_equip'])==''){
         $proofArray[0]['major_equip']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['maint_equip'])==''){
         $proofArray[0]['maint_equip']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['testing_meas'])==''){
         $proofArray[0]['testing_meas']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['testing_meas2'])==''){
         $proofArray[0]['testing_meas2']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    
    if(trim($proofArray[0]['major_equip2'])==''){
         $proofArray[0]['major_equip2']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['testing_meas2'])==''){
         $proofArray[0]['testing_meas2']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['labname'])==''){
         $proofArray[0]['labname']=$not_applicable_form_string;
    }
    
    function major_euip($val=''){
        //do not make  index of '301k And Above' as 4, HERE 5 is the required value
        $array=array(1=>'10k-50k',2=>'51k-100k',3=>'101k-300k',5=>'301k & Above');
        $retunValue='';
        $selected='';
        foreach($array as $key=>$value){
           if($key==$val){
               $selected='selected="selected"';
           } 
           else{
               $selected='';
           }
           $retunValue .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        return $retunValue;
    }
    
    function testing_meas($val=''){
        $array=array(1=>'10k-50k',2=>'51k-100k',3=>'101k-300k');
        $retunValue='';
        $selected='';
        foreach($array as $key=>$value){
           if($key==$val){
               $selected='selected="selected"';
           } 
           else{
               $selected='';
           }
           $retunValue .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        return $retunValue;
    }
    ?>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr><td colspan="8" height="5px;"></td></tr>
    <tr>
     <td colspan="8" class="contenttab_internal_rows">
	 <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');?>
      <b> Infrastructure Development (Max : <?php echo $weightage; ?>) <?php echo HtmlFunctions::getInstance()->getHelpLink('help for infrastructure development',HELP_INFRASTRUCTURE_DEVELOPMENT); ?></b>
     </td>
    </tr>
    <tr><td colspan="8" height="5px;"></td></tr>
    <tr class="rowheading">
        <td class="searchhead_text" colspan="2">Subject</td>
        <td class="searchhead_text" colspan="4">Details</td>
        <td class="searchhead_text">Marks</td>
    </tr>
    <tr>
       <td class="contenttab_internal_rows"><b>Establishment of new Lab(s)/Others(60)</b></td>
       <td class="padding" valign="top">:</td>
       <td class="contenttab_internal_rows"><b>No. of labs</b></td>
       <td class="padding" >
           <input type="text" class="inputbox" <?php echo $disabled; ?> name="newlab" id="newlab" maxlength="1" value="<?php echo trim($proofArray[0]['newlab']); ?>" style="width:30px;" onblur="changeValue0(this.value);" />
       </td>
       <td class="contenttab_internal_rows" ><b>Lab Names</b></td>
       <td class="padding" valign="top">
           <input type="text" class="inputbox" <?php echo $disabled; ?> name="labname" id="labname" maxlength="70" value="<?php echo trim($proofArray[0]['labname']); ?>"  />
       </td>
       <td class="padding" >
          <input type="text" disabled="disabled"  value="<?php echo trim($proofArray[0]['act1_marks']); ?>" name="act1_marks" id="act1_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
     <tr>
       <td class="contenttab_internal_rows"><b>Major Equipment/Software Purchase(50).<br/>Give Details Along With Dates and PO Number</b></td>
       <td class="padding" >:</td>
       <td class="contenttab_internal_rows"><b>Amount</b></td>
       <td class="padding">
        <select name="major_equip" id="major_equip" class="inputbox" style="width:120px" onchange="changeValue1(this.value);">
         <option value="0">Not Applicable</option>
         <?php
          echo major_euip(trim($proofArray[0]['major_equip']));
         ?>
        </select>
       </td>
       <td class="contenttab_internal_rows"><b>Details</b></td>
       <td class="padding" >
           <input type="text" class="inputbox" <?php echo $disabled; ?> name="major_equip2" id="major_equip2" maxlength="150" value="<?php echo trim($proofArray[0]['major_equip2']); ?>" />
       </td>
       <td class="padding">
          <input type="text" disabled="disabled"  value="<?php echo trim($proofArray[0]['act2_marks']); ?>" name="act2_marks" id="act2_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
     <tr>
       <td class="contenttab_internal_rows" valign="top"><b>Maintenance of Equipments/Softwares/Others(20)</b></td>
       <td class="padding" valign="top">:</td>
       <td colspan="4" class="padding" valign="top">
           <textarea <?php echo $disabled; ?> name="maint_equip" id="maint_equip" rows="5" cols="60" maxlength="400" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['maint_equip']); ?></textarea>
       </td>
       <td class="padding" valign="top">
          <input type="text" alt="20" <?php echo $disabled; ?>  value="<?php echo trim($proofArray[0]['act3_marks']); ?>" name="act3_marks" id="act3_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
      <tr>
       <td class="contenttab_internal_rows"><b>Development of New Testing Measuring, Equipment,<br/>User Friendly Kits, Software(30)</b></td>
       <td class="padding">:</td>
       <td class="contenttab_internal_rows"><b>Amount</b></td>
       <td class="padding" >
        <select name="testing_meas" id="testing_meas" class="inputbox" style="width:120px" onchange="changeValue2(this.value);">
          <option value="0">Not Applicable</option>
          <?php
          echo testing_meas(trim($proofArray[0]['testing_meas']));
         ?>
        </select>
       </td>
       <td class="contenttab_internal_rows"><b>Details</b></td>
       <td class="padding">
           <input type="text" class="inputbox" <?php echo $disabled; ?> name="testing_meas2" id="testing_meas2" maxlength="150" value="<?php echo trim($proofArray[0]['testing_meas2']); ?>" />
       </td>
       <td class="padding">
          <input type="text" disabled="disabled"  value="<?php echo trim($proofArray[0]['act4_marks']); ?>" name="act4_marks" id="act4_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
      </tr>
      <tr>
     <tr> 
      <td align="center" colspan="7" <?php echo $hodEditDisabled ?>>
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm7();return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
        </td>
      </tr>
     
  </table>
    <?php
}
else if($proofId==8){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['new_manual'])==''){
         $proofArray[0]['new_manual']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['existing_manual'])==''){
         $proofArray[0]['existing_manual']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['qty_lab'])=='' or trim($proofArray[0]['qty_lab'])<'0'){
         $proofArray[0]['qty_lab']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    ?>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr><td colspan="7" height="5px;"></td></tr>
    <tr>
     <td colspan="7" class="contenttab_internal_rows">
      <b> Development of Lab Manual (Max : <?php echo $weightage; ?>) </b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for Development Lab manual',HELP_DEVELOPMENT_LAB_MANUAL); ?></b>
     </td>
    </tr>
    <tr class="rowheading">
        <td class="searchhead_text" colspan="2">Subject</td>
        <td class="searchhead_text" colspan="4">Details</td>
        <td class="searchhead_text">Marks</td>
    </tr>
    <tr><td colspan="7" height="5px;"></td></tr>
    <tr>
       <td class="contenttab_internal_rows"><b>New Manual(20)</b></td>
       <td class="padding" valign="top">:</td>
       <td class="contenttab_internal_rows"><b>No. of manuals</b></td>
       <td class="padding">
           <input type="text" class="inputbox" <?php echo $disabled; ?> name="qty_lab" id="qty_lab" maxlength="2" value="<?php echo trim($proofArray[0]['qty_lab']); ?>" style="width:30px;" onchange="updateMarks8(this.value,document.proofForm.act1_marks);" />
       </td>
       <td class="contenttab_internal_rows"><b>Details</b></td>
       <td class="padding">
           <input type="text" class="inputbox" <?php echo $disabled; ?> name="new_manual" id="new_manual" maxlength="150" value="<?php echo trim($proofArray[0]['new_manual']); ?>" style="width:327px;" />
       </td>
       <td class="padding">
          <input disabled="disabled" type="text" alt="20" <?php echo $disabled; ?> value="<?php echo trim($proofArray[0]['act1_marks']); ?>" name="act1_marks" id="act1_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
     <tr>
       <td  class="contenttab_internal_rows" valign="top"><b>Updradation/Improvement In Existing Manual(10)</b></td>
       <td class="padding" valign="top">:</td>
       <td colspan="4" class="padding" valign="top">
           <textarea <?php echo $disabled; ?> name="existing_manual" id="existing_manual" rows="5" cols="60" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['existing_manual']); ?></textarea>
       </td>
       <td class="padding" valign="top">
          <input type="text" alt="10" <?php echo $disabled; ?> value="<?php echo trim($proofArray[0]['act2_marks']); ?>" name="act2_marks" id="act2_marks" maxlength="2" class="inputbox" style="width:30px;" />
       </td>
     </tr>
     <tr> 
      <td align="center" colspan="7" <?php echo $hodEditDisabled ?>>
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm8();return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
        </td>
      </tr>
  </table>
    <?php
}

else if($proofId==9){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    if(trim($proofArray[0]['act5_marks'])==''){
         $proofArray[0]['act5_marks']=0;
    }
    if(trim($proofArray[0]['reportSubmitted'])==1){
        $reportSubmitted1='checked="checked"';
        $reportSubmitted2='';
    }
    else{
        $reportSubmitted2='checked="checked"';
        $reportSubmitted1='';
    }
    if(trim($proofArray[0]['strength_indus'])=='' or trim($proofArray[0]['strength_indus'])<0){
         $proofArray[0]['strength_indus']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['strength_trips'])=='' or trim($proofArray[0]['strength_trips'])<0){
         $proofArray[0]['strength_trips']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['strength_indus2'])=='' or trim($proofArray[0]['strength_indus2'])<0) {
         $proofArray[0]['strength_indus2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['strength_trips2'])=='' or trim($proofArray[0]['strength_trips2'])<0){
         $proofArray[0]['strength_trips2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['location_indus'])==''){
         $proofArray[0]['location_indus']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['location_trips'])==''){
         $proofArray[0]['location_trips']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['location_indus2'])==''){
         $proofArray[0]['location_indus2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['location_trips2'])==''){
         $proofArray[0]['location_trips2']=$not_applicable_form_string;
    }
    
   if($proofArray[0]['indus_datefrom']=='' or $proofArray[0]['indus_datefrom']=='0000-00-00'){
       $proofArray[0]['indus_datefrom']=''; 
   }
   if($proofArray[0]['indus_dateto']=='' or $proofArray[0]['indus_dateto']=='0000-00-00'){
       $proofArray[0]['indus_dateto']=''; 
   }
   if($proofArray[0]['trips_datefrom']=='' or $proofArray[0]['trips_datefrom']=='0000-00-00'){
       $proofArray[0]['trips_datefrom']=''; 
   }
   if($proofArray[0]['trips_dateto']=='' or $proofArray[0]['trips_dateto']=='0000-00-00'){
       $proofArray[0]['trips_dateto']=''; 
   }
   
   if($proofArray[0]['test_input6']=='' or $proofArray[0]['test_input6']=='0000-00-00'){
       $proofArray[0]['test_input6']=''; 
   }
   if($proofArray[0]['test_input7']=='' or $proofArray[0]['test_input7']=='0000-00-00'){
       $proofArray[0]['test_input7']=''; 
   }
   if($proofArray[0]['test_input8']=='' or $proofArray[0]['test_input8']=='0000-00-00'){
       $proofArray[0]['test_input8']=''; 
   }
   if($proofArray[0]['test_input9']=='' or $proofArray[0]['test_input9']=='0000-00-00'){
       $proofArray[0]['test_input9']=''; 
   }
   
  ?>
     <table border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td colspan="7" align="left" class="contenttab_internal_rows">
         <b>Assisting In Trips / Industrial Visits (Max : <?php echo $weightage; ?>)</b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for assisting in trips/industrial visits',ASSISTING_TRIPS_AND_INDUSTRIAL_VISITS); ?></b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Strength</td>
        <td class="searchhead_text">Location</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text" colspan="2" align="center">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Number of Indus. Visits Assisted(10)</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text" name="strength_indus" id="strength_indus" maxlength="3" value="<?php echo trim($proofArray[0]['strength_indus']); ?>" onchange="updateMarks9_1(this.value,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" name="location_indus" id="location_indus" maxlength="80" value="<?php echo trim($proofArray[0]['location_indus']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('indus_datefrom',trim($proofArray[0]['indus_datefrom']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('indus_dateto',trim($proofArray[0]['indus_dateto']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input disabled="disabled" alt="5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act1_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act1_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('strength_indus').value='';document.getElementById('location_indus').value='';document.getElementById('indus_datefrom').value='';document.getElementById('indus_dateto').value='';document.getElementById('act1_marks').value='0'">Reset</a>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text" name="strength_indus2" id="strength_indus2" maxlength="3" value="<?php echo trim($proofArray[0]['strength_indus2']); ?>" onchange="updateMarks9_2(this.value,document.proofForm.act2_marks);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" name="location_indus2" id="location_indus2" maxlength="80" value="<?php echo trim($proofArray[0]['location_indus2']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('indus_datefrom2',trim($proofArray[0]['test_input6']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('indus_dateto2',trim($proofArray[0]['test_input7']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input disabled="disabled" alt="5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act2_marks" id="act2_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act2_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('strength_indus2').value='';document.getElementById('location_indus2').value='';document.getElementById('indus_datefrom2').value='';document.getElementById('indus_dateto2').value='';document.getElementById('act2_marks').value='0'">Reset</a>
       </td>
    </tr>
    
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Industrial Report Submitted(5)</b></td>
       <td class="padding" align="left">
         <?php echo NOT_APPLICABLE_STRING; ?>
       </td>
       <td class="padding" align="left">
         <?php echo NOT_APPLICABLE_STRING; ?>
       </td>
       <td class="padding" align="left">
         <?php echo NOT_APPLICABLE_STRING; ?>
       </td>
        <td class="padding" align="left">
         Report Submitted<br/> <input type="radio" name="reportSubmitted" id="reportSubmitted1" value="1" onclick="document.getElementById('act3_marks').value=5;" <?php echo $reportSubmitted1; ?> />Yes&nbsp;
         <input type="radio" name="reportSubmitted" id="reportSubmitted2" value="0" onclick="document.getElementById('act3_marks').value=0;" <?php echo $reportSubmitted2; ?> />No
       </td>
        <td class="padding" align="left">
         <input alt="5" disabled="disabled" class="inputbox" style="width:30px;"  type="text" name="act3_marks" id="act3_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act3_marks']); ?>" />
       </td>
    </tr>
    
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Number of Trips Assisted(20)</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text"  name="strength_trips" id="strength_trips" maxlength="3" value="<?php echo trim($proofArray[0]['strength_trips']); ?>" onchange="updateMarks9_3(this.value,document.proofForm.act4_marks);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="location_trips" id="location_trips" maxlength="80" value="<?php echo trim($proofArray[0]['location_trips']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('trips_datefrom',trim($proofArray[0]['trips_datefrom']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('trips_dateto',trim($proofArray[0]['trips_dateto']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input disabled="disabled" alt="10" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act4_marks" id="act4_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act4_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('strength_trips').value='';document.getElementById('location_trips').value='';document.getElementById('trips_datefrom').value='';document.getElementById('trips_dateto').value='';document.getElementById('act4_marks').value='0'">Reset</a>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text"  name="strength_trips2" id="strength_trips2" maxlength="3" value="<?php echo trim($proofArray[0]['strength_trips2']); ?>" onchange="updateMarks9_4(this.value,document.proofForm.act5_marks);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox"  type="text" size="20" name="location_trips2" id="location_trips2" maxlength="80" value="<?php echo trim($proofArray[0]['location_trips2']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('trips_datefrom2',trim($proofArray[0]['test_input8']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('trips_dateto2',trim($proofArray[0]['test_input9']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input disabled="disabled" alt="10" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act5_marks" id="act5_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act5_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('strength_trips2').value='';document.getElementById('location_trips2').value='';document.getElementById('trips_datefrom2').value='';document.getElementById('trips_dateto2').value='';document.getElementById('act5_marks').value='0'">Reset</a>
       </td>
    </tr>
     <tr> 
         <td align="center" colspan="6" <?php echo $hodEditDisabled ?>>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm9();return false;" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
         </td>
       </tr> 
     </table>
    <?php
}

else if($proofId==10){
    $not_applicable_form_string='';
    
    if(trim($proofArray[0]['eventname_org'])==''){
         $proofArray[0]['eventname_org']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['eventname_assisted'])==''){
         $proofArray[0]['eventname_assisted']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['org_strength'])=='' or trim($proofArray[0]['org_strength']) < 0){
         $proofArray[0]['org_strength']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['assisted_strength'])=='' or trim($proofArray[0]['assisted_strength'])<0){
         $proofArray[0]['assisted_strength']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['org_budget'])=='' or trim($proofArray[0]['org_budget'])=='0'){
         $proofArray[0]['org_budget']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['assisted_budget'])=='' or trim($proofArray[0]['assisted_budget'])=='0'){
        $proofArray[0]['assisted_budget']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['location_trips'])==''){
         $proofArray[0]['location_trips']=$not_applicable_form_string;
    }
    if($proofArray[0]['org_heldfrom']=='' or $proofArray[0]['org_heldfrom']=='0000-00-00'){
       $proofArray[0]['org_heldfrom']=''; 
    }
    if($proofArray[0]['assisted_heldfrom']=='' or $proofArray[0]['assisted_heldfrom']=='0000-00-00'){
       $proofArray[0]['assisted_heldfrom']=''; 
    }
    if($proofArray[0]['org_heldto']=='' or $proofArray[0]['org_heldto']=='0000-00-00'){
       $proofArray[0]['org_heldto']=''; 
    }
    if($proofArray[0]['assisted_heldto']=='' or $proofArray[0]['assisted_heldto']=='0000-00-00'){
       $proofArray[0]['assisted_heldto']=''; 
    }
   
    if(trim($proofArray[0]['eventname_org2'])==''){
         $proofArray[0]['eventname_org2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['eventname_assisted2'])==''){
         $proofArray[0]['eventname_assisted2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['org_strength2'])=='' or trim($proofArray[0]['org_strength2'])<0){
         $proofArray[0]['org_strength2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['assisted_strength2'])=='' or trim($proofArray[0]['assisted_strength2'])<0){
         $proofArray[0]['assisted_strength2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['org_budget2'])=='' or trim($proofArray[0]['org_budget2'])=='0'){
         $proofArray[0]['org_budget2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['assisted_budget2'])=='' or trim($proofArray[0]['assisted_budget2'])=='0'){
         $proofArray[0]['assisted_budget2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['location_trips2'])==''){
         $proofArray[0]['location_trips2']=$not_applicable_form_string;
    }
    if($proofArray[0]['org_heldfrom2']=='' or $proofArray[0]['org_heldfrom2']=='0000-00-00'){
       $proofArray[0]['org_heldfrom2']=''; 
    }
    if($proofArray[0]['assisted_heldfrom2']=='' or $proofArray[0]['assisted_heldfrom2']=='0000-00-00'){
       $proofArray[0]['assisted_heldfrom2']=''; 
    }
    if($proofArray[0]['org_heldto2']=='' or $proofArray[0]['org_heldto2']=='0000-00-00'){
       $proofArray[0]['org_heldto2']=''; 
    }
    if($proofArray[0]['assisted_heldto2']=='' or $proofArray[0]['assisted_heldto2']=='0000-00-00'){
       $proofArray[0]['assisted_heldto2']=''; 
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
  ?>
     <table border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td colspan="8" align="left" class="contenttab_internal_rows">
         <b>Organizing events of departmental clubs (Max : <?php echo $weightage; ?>)</b><?php echo HtmlFunctions::getInstance()->getHelpLink('Organizing events of departmental clubs',ORGANIZING_EVENTS_OF_DEPARTMENTAL_CLUBS); ?></b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Event Name</td>
        <td class="searchhead_text">Strength</td>
        <td class="searchhead_text">Budget</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text" colspan="2" align="center">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Number of Events Organized(20)</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:100px;" type="text" name="eventname_org" id="eventname_org" maxlength="55" value="<?php echo trim($proofArray[0]['eventname_org']); ?>" onblur="changeValue10_1(this.value);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text" name="org_strength" id="org_strength" maxlength="3" value="<?php echo trim($proofArray[0]['org_strength']); ?>" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:150px;" type="text" name="org_budget" id="org_budget" maxlength="6" value="<?php echo trim($proofArray[0]['org_budget']); ?>" />
       </td>

      <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('org_heldfrom',trim($proofArray[0]['org_heldfrom']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('org_heldto',trim($proofArray[0]['org_heldto']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input alt="10" disabled="disabled" class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act1_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act1_marks']); ?>" />
       </td>
        <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('eventname_org').value='';document.getElementById('org_strength').value='';document.getElementById('org_budget').value='';document.getElementById('org_heldfrom').value='';document.getElementById('org_heldto').value='';document.getElementById('act1_marks').value='0'">Reset</a>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:100px;" type="text" name="eventname_org2" id="eventname_org2" maxlength="55" value="<?php echo trim($proofArray[0]['eventname_org2']); ?>" onblur="changeValue10_2(this.value);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text" name="org_strength2" id="org_strength2" maxlength="3" value="<?php echo trim($proofArray[0]['org_strength2']); ?>" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:150px;" type="text" name="org_budget2" id="org_budget2" maxlength="6" value="<?php echo trim($proofArray[0]['org_budget2']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('org_heldfrom2',trim($proofArray[0]['org_heldfrom2']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('org_heldto2',trim($proofArray[0]['org_heldto2']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input alt="10" disabled="disabled" class="inputbox" style="width:30px;"  type="text" name="act2_marks" id="act2_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act2_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('eventname_org2').value='';document.getElementById('org_strength2').value='';document.getElementById('org_budget2').value='';document.getElementById('org_heldfrom2').value='';document.getElementById('org_heldto2').value='';document.getElementById('act2_marks').value='0'">Reset</a>
       </td>
    </tr>
    
    
    <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Event Name</td>
        <td class="searchhead_text">Strength</td>
        <td class="searchhead_text">Your Role</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text" colspan="2" align="center">Marks</td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Number of Events Assisted(10)</b></td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:100px;"  type="text"  name="eventname_assisted" id="eventname_assisted" maxlength="55" value="<?php echo trim($proofArray[0]['eventname_assisted']); ?>" onblur="changeValue10_3(this.value);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text"  name="assisted_strength" id="assisted_strength" maxlength="5" value="<?php echo trim($proofArray[0]['assisted_strength']); ?>" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:150px;" type="text" size="20" name="assisted_budget" id="assisted_budget" maxlength="50" value="<?php echo trim($proofArray[0]['assisted_budget']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('assisted_heldfrom',trim($proofArray[0]['assisted_heldfrom']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('assisted_heldto',trim($proofArray[0]['assisted_heldto']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input alt="5" disabled="disabled" class="inputbox" style="width:30px;"  type="text" name="act3_marks" id="act3_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act3_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('eventname_assisted').value='';document.getElementById('assisted_strength').value='';document.getElementById('assisted_budget').value='';document.getElementById('assisted_heldfrom').value='';document.getElementById('assisted_heldto').value='';document.getElementById('act3_marks').value='0'">Reset</a>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left">&nbsp;</td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:100px;"  type="text"  name="eventname_assisted2" id="eventname_assisted2" maxlength="55" value="<?php echo trim($proofArray[0]['eventname_assisted2']); ?>" onblur="changeValue10_4(this.value);" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:50px;"  type="text"  name="assisted_strength2" id="assisted_strength2" maxlength="5" value="<?php echo trim($proofArray[0]['assisted_strength2']); ?>" />
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> class="inputbox" style="width:150px;" type="text" size="20" name="assisted_budget2" id="assisted_budget2" maxlength="50" value="<?php echo trim($proofArray[0]['assisted_budget2']); ?>" />
       </td>
       <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('assisted_heldfrom2',trim($proofArray[0]['assisted_heldfrom2']));
        ?>    
        </td>
        <td class="padding" align="left">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('assisted_heldto2',trim($proofArray[0]['assisted_heldto2']));
        ?>    
        </td>
        <td class="padding" align="left">
         <input alt="5" disabled="disabled" class="inputbox" style="width:30px;"  type="text" name="act4_marks" id="act4_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act4_marks']); ?>" />
       </td>
       <td class="padding">
        <a href="javascript:void(0);" title="Reset data of this row" onclick="document.getElementById('eventname_assisted2').value='';document.getElementById('assisted_strength2').value='';document.getElementById('assisted_budget2').value='';document.getElementById('assisted_heldfrom2').value='';document.getElementById('assisted_heldto2').value='';document.getElementById('act4_marks').value='0'">Reset</a>
       </td>
    </tr>
    
        <tr> 
         <td align="center" colspan="7" <?php echo $hodEditDisabled ?>>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm10();return false;" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
         </td>
       </tr> 
       </table>
    <?php
}

else if($proofId==11){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['even_incharge'])==''){
         $proofArray[0]['even_incharge']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_cases'])==''){
         $proofArray[0]['even_cases']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_cases_count'])==''){
         $proofArray[0]['even_cases_count']=0;
    }
    if(trim($proofArray[0]['even_achieve1'])==''){
         $proofArray[0]['even_achieve1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_achieve2'])==''){
         $proofArray[0]['even_achieve2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_achieve3'])==''){
         $proofArray[0]['even_achieve3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_achieve4'])==''){
         $proofArray[0]['even_achieve4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_achieve5'])==''){
         $proofArray[0]['even_achieve5']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_desc1'])==''){
         $proofArray[0]['even_desc1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_desc2'])==''){
         $proofArray[0]['even_desc2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_desc3'])==''){
         $proofArray[0]['even_desc3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_desc4'])==''){
         $proofArray[0]['even_desc4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_desc5'])==''){
         $proofArray[0]['even_desc5']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_role1'])==''){
         $proofArray[0]['even_role1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_role2'])==''){
         $proofArray[0]['even_role2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_role2'])==''){
         $proofArray[0]['even_role2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_role3'])==''){
         $proofArray[0]['even_role3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_role4'])==''){
         $proofArray[0]['even_role4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_role5'])==''){
         $proofArray[0]['even_role5']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_incharge'])==''){
         $proofArray[0]['odd_incharge']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_cases_count'])==''){
         $proofArray[0]['odd_cases_count']=0;
    }
    if(trim($proofArray[0]['odd_cases'])==''){
         $proofArray[0]['odd_cases']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_achieve1'])==''){
         $proofArray[0]['odd_achieve1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_achieve2'])==''){
         $proofArray[0]['odd_achieve2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_achieve3'])==''){
         $proofArray[0]['odd_achieve3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_achieve4'])==''){
         $proofArray[0]['odd_achieve4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_achieve5'])==''){
         $proofArray[0]['odd_achieve5']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_desc1'])==''){
         $proofArray[0]['odd_desc1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_desc2'])==''){
         $proofArray[0]['odd_desc2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_desc3'])==''){
         $proofArray[0]['odd_desc3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_desc3'])==''){
         $proofArray[0]['odd_desc3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_desc4'])==''){
         $proofArray[0]['odd_desc4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_desc5'])==''){
         $proofArray[0]['odd_desc5']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_role1'])==''){
         $proofArray[0]['odd_role1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_role2'])==''){
         $proofArray[0]['odd_role2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_role3'])==''){
         $proofArray[0]['odd_role3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_role4'])==''){
         $proofArray[0]['odd_role4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_role5'])==''){
         $proofArray[0]['odd_role5']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    if(trim($proofArray[0]['act5_marks'])==''){
         $proofArray[0]['act5_marks']=0;
    }
    if(trim($proofArray[0]['act6_marks'])==''){
         $proofArray[0]['act6_marks']=0;
    }
    
  ?>
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Acting as a class coordinator (Max : <?php echo $weightage; ?>)<?php echo HtmlFunctions::getInstance()->getHelpLink('help for class coordinator',ACTING_AS_CLASS_COORDINATOR); ?></b>
  </td>
 </tr>
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Even Semester</td>
        <td class="searchhead_text">Details</td>
        <td class="searchhead_text">Yes/No</td>
        <td class="searchhead_text">Marks</td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Class Incharge</b></td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="even_incharge" id="even_incharge" cols="70" maxlength="75" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_incharge']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <?php
         $chk='';
         if(trim($proofArray[0]['even_checked'])==1){
             $chk='checked="checked"';
         }
        ?>
         <input type="checkbox" <?php echo $disabled; ?> name="even_checked" id="even_checked" <?php echo $chk; ?> onclick="updateMarks11_1(this.checked,document.proofForm.act1_marks);" />
       </td>
       <td class="padding" align="left">
         <input disabled="disabled" alt="5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act1_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act1_marks']); ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Indiscipline Cases</b></td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="even_cases" id="even_cases" cols="70" maxlength="75" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_cases']); ?></textarea>
       </td>
       <td class="padding" align="left">
         <input alt="5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="even_cases_count" id="even_cases_count" maxlength="2" value="<?php echo trim($proofArray[0]['even_cases_count']); ?>" onchange="updateMarks11_2(this.value,document.proofForm.act2_marks);" />
       </td>
       <td class="padding" align="left">
         <input disabled="disabled" alt="-5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act2_marks" id="act2_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act2_marks']); ?>" />
       </td>
    </tr>
   <tr><td colspan="4" height="5px;"></td></tr> 
   </table>
  </td>
 </tr>
 <tr>
  <td valign="top">   
   <table border="0" cellpadding="0" cellspacing="1" width="100%">
      <tr>
        <td colspan="3" align="left" class="contenttab_internal_rows">
         <b>Achievements of this class(15)</b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Achievements of class</td>
        <td class="searchhead_text">Description</td>
        <td class="searchhead_text">Your role in this achievements</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="even_achieve1" id="even_achieve1" class="inputbox" maxlength="249" value="<?php echo trim($proofArray[0]['even_achieve1']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="even_desc1" id="even_desc1" class="inputbox" maxlength="349" value="<?php echo trim($proofArray[0]['even_desc1']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="even_role1" id="even_role1" class="inputbox" maxlength="200" value="<?php echo trim($proofArray[0]['even_role1']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input alt="15" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act3_marks" id="act3_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act3_marks']); ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="even_achieve2" id="even_achieve2" class="inputbox" maxlength="249" value="<?php echo trim($proofArray[0]['even_achieve2']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="even_desc2" id="even_desc2" class="inputbox" maxlength="349" value="<?php echo trim($proofArray[0]['even_desc2']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="even_role2" id="even_role2" class="inputbox" maxlength="200" value="<?php echo trim($proofArray[0]['even_role2']); ?>" /> 
       </td>
       <td>&nbsp;</td>
    </tr>
   </table>
  </td>
  </tr>
  <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Odd Semester</td>
        <td class="searchhead_text">Details</td>
        <td class="searchhead_text">Yes/No</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Class Incharge</b></td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="odd_incharge" id="odd_incharge" cols="70" maxlength="75" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_incharge']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <?php
         $chk='';
         if(trim($proofArray[0]['odd_checked'])==1){
             $chk='checked="checked"';
         }
        ?>
         <input type="checkbox" <?php echo $disabled; ?> name="odd_checked" id="odd_checked" <?php echo $chk; ?> onclick="updateMarks11_3(this.checked,document.proofForm.act4_marks);" />
       </td>
       <td class="padding" align="left">
         <input disabled="disabled" alt="5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act4_marks" id="act4_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act4_marks']); ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Indiscipline Cases</b></td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="odd_cases" id="odd_cases" cols="70" maxlength="75" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_cases']); ?></textarea>
       </td>
       <td class="padding" align="left">
         <input alt="5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="odd_cases_count" id="odd_cases_count" maxlength="2" value="<?php echo trim($proofArray[0]['odd_cases_count']); ?>" onchange="updateMarks11_4(this.value,document.proofForm.act5_marks);" />
       </td>
       <td class="padding" align="left">
         <input disabled="disabled" alt="-5" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act5_marks" id="act5_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act5_marks']); ?>" />
       </td>
    </tr>
   <tr><td colspan="4" height="5px;"></td></tr> 
   </table>
  </td>
 </tr>
 <tr>
  <td valign="top">   
   <table border="0" cellpadding="0" cellspacing="1" width="100%">
      <tr>
        <td colspan="3" align="left" class="contenttab_internal_rows">
         <b>Achievements of this class(15)</b>
        </td>
      </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Achievements of class</td>
        <td class="searchhead_text">Description</td>
        <td class="searchhead_text">Your role in this achievements</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="odd_achieve1" id="odd_achieve1" class="inputbox" maxlength="249" value="<?php echo trim($proofArray[0]['odd_achieve1']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="odd_desc1" id="odd_desc1" class="inputbox" maxlength="349" value="<?php echo trim($proofArray[0]['odd_desc1']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="odd_role1" id="odd_role1" class="inputbox" maxlength="200" value="<?php echo trim($proofArray[0]['odd_role1']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input alt="15" <?php echo $disabled; ?> class="inputbox" style="width:30px;"  type="text" name="act6_marks" id="act6_marks" maxlength="2" value="<?php echo trim($proofArray[0]['act6_marks']); ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="odd_achieve2" id="odd_achieve2" class="inputbox" maxlength="249" value="<?php echo trim($proofArray[0]['odd_achieve2']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="odd_desc2" id="odd_desc2" class="inputbox" maxlength="349" value="<?php echo trim($proofArray[0]['odd_desc2']); ?>" /> 
       </td>
       <td class="padding" align="left">
         <input <?php echo $disabled; ?> type="text" name="odd_role2" id="odd_role2" class="inputbox" maxlength="200" value="<?php echo trim($proofArray[0]['odd_role2']); ?>" /> 
       </td>
       <td>&nbsp;</td>
    </tr>
   </table>
  </td>
  </tr>
  <tr> 
     <td align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm11();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr>
  </table>
    <?php
}

else if($proofId==12){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['track1'])==''){
         $proofArray[0]['track1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['track2'])==''){
         $proofArray[0]['track2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['track3'])==''){
         $proofArray[0]['track3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['track4'])==''){
         $proofArray[0]['track4']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    if(trim($proofArray[0]['act5_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act6_marks'])==''){
         $proofArray[0]['act6_marks']=0;
    }
    if(trim($proofArray[0]['act7_marks'])==''){
         $proofArray[0]['act7_marks']=0;
    }
    if(trim($proofArray[0]['act8_marks'])==''){
         $proofArray[0]['act8_marks']=0;
    }
    
    //check for co-ordinator
    $empArray=$appDataManager->checkCoordinator($employeeId);
    $isCoordinator=$empArray[0]['isCoordinator'];
  ?>
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Departmental Administration (Max : <?php echo $weightage; ?>)</b>
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left"><b>
  <?php
   if($isCoordinator==1){
       echo 'Development And Planning Track';
   }
   else{
       echo 'Execution & Implementation Track';
   }
  ?> 
  </b></td>
 </tr> 
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Describe</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
        <?php
         if($isCoordinator==1){
          echo 'What were the strengths & weeknesses of the<br/> department you took over as coordinator(10)';
         }
        else{
         echo 'What were the roles you were assigned or took yourself<br/> during the period of evaluation(10)';
        }
       ?></b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="3" name="track1" id="track1" cols="35" maxlength="400" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['track1']); ?></textarea>
       </td>
       <td class="padding" align="left">
         <?php
          if($isCoordinator==1){
              $value=$proofArray[0]['act1_marks'];
          }
          else{
              $value=$proofArray[0]['act2_marks'];
          }
         ?>
         <input alt="10"  class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act1_marks" maxlength="2" value="<?php echo $value; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
        <?php
         if($isCoordinator==1){
          echo 'Your action plan to strengthen the department in weak<br/> areas & further strengthen the strong areas(10)';
         }
        else{
         echo 'Your action plan to execute each of the above tasks(10)';
        }
       ?></b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="3" name="track2" id="track2" cols="35" maxlength="400" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['track2']); ?></textarea>
       </td>
       <td class="padding" align="left">
       <?php
          if($isCoordinator==1){
              $value=$proofArray[0]['act3_marks'];
          }
          else{
              $value=$proofArray[0]['act4_marks'];
          }
         ?>
         <input alt="10"  class="inputbox" style="width:30px;"  type="text" name="act2_marks" id="act2_marks" maxlength="2" value="<?php echo $value; ?>" />
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
        <?php
         if($isCoordinator==1){
          echo 'What innovative practices you initiated in the<br/> department and what were the outcomes(20)';
         }
        else{
         echo 'Write Specifically what innovative practices you initiated<br/> in the department and what were the outcomes(20)';
        }
       ?></b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="3" name="track3" id="track3" cols="35" maxlength="400" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['track3']); ?></textarea>
       </td>
       <td class="padding" align="left">
       <?php
          if($isCoordinator==1){
              $value=$proofArray[0]['act5_marks'];
          }
          else{
              $value=$proofArray[0]['act6_marks'];
          }
         ?>
         <input alt="20"  class="inputbox" style="width:30px;"  type="text" name="act3_marks" id="act3_marks" maxlength="2" value="<?php echo $value; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
        <?php
         if($isCoordinator==1){
          echo 'Your failures & achievements at the end of the term(20)';
         }
        else{
         echo 'Your failures & achievements at the end of the term(20)';
        }
       ?></b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="3" name="track4" id="track4" cols="35" maxlength="400" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['track4']); ?></textarea>
       </td>
       <td class="padding" align="left">
       <?php
          if($isCoordinator==1){
              $value=$proofArray[0]['act7_marks'];
          }
          else{
              $value=$proofArray[0]['act8_marks'];
          }
         ?>
         <input alt="20"  class="inputbox" style="width:30px;"  type="text" name="act4_marks" id="act4_marks" maxlength="2" value="<?php echo $value; ?>" />
       </td>
    </tr>
   <tr><td colspan="4" height="5px;"></td></tr> 
   </table>
  </td>
 </tr>
 
 <tr> 
     <td align="center" colspan="4" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm12();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
    <?php
}

else if($proofId==13){
   if(trim($proofArray[0]['feed_even'])==''){
         $proofArray[0]['feed_even']=0;
   }
   if(trim($proofArray[0]['feed_odd'])==''){
         $proofArray[0]['feed_odd']=0;
   }
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left" colspan="2">
    <b>
     Students Feedback(Max : <?php echo $weightage; ?> ) = (A+B)/2 <?php echo $weightage; ?>)</b><?php echo HtmlFunctions::getInstance()->getHelpLink('help for Students Feedback',HELP_FOR_STUDENT_FEEDBACK); ?></b>
    </b>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Semester</td>
        <td class="searchhead_text">Feedback Points</td>
    </tr>         
    <tr class="row0">
      <td class="contenttab_internal_rows" align="left"><b>Even Semester</b></td>
      <td class="padding">
       <input type="text" alt="50" <?php echo $disabled; ?> name="feed_even" id="feed_even" style="width:50px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['feed_even'];?>" />
      </td>
    </tr>
    <tr class="row1">
      <td class="contenttab_internal_rows" align="left"><b>Odd Semester</b></td>
      <td class="padding">
       <input type="text" alt="50" <?php echo $disabled; ?> name="feed_odd" id="feed_odd" style="width:50px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['feed_odd'];?>" />
      </td>
    </tr>
    <tr><td colspan="2" height="5px;"></td></tr>
    <tr> 
     <td colspan="2" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm13();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==15){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['even_prob_design'])==''){
         $proofArray[0]['even_prob_design']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_avg_marks'])==''){
         $proofArray[0]['even_avg_marks']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_eminent'])==''){
         $proofArray[0]['even_eminent']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_prob_design'])==''){
         $proofArray[0]['odd_prob_design']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_avg_marks'])==''){
         $proofArray[0]['odd_avg_marks']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_eminent'])==''){
         $proofArray[0]['odd_eminent']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['times1'])==''){
         $proofArray[0]['times1']=0;
    }
    if(trim($proofArray[0]['times2'])==''){
         $proofArray[0]['times2']=0;
    }
    if(trim($proofArray[0]['times3'])==''){
         $proofArray[0]['times3']=0;
    }
    if(trim($proofArray[0]['times4'])==''){
         $proofArray[0]['times4']=0;
    }
    if(trim($proofArray[0]['times5'])==''){
         $proofArray[0]['times5']=0;
    }
    if(trim($proofArray[0]['times6'])==''){
         $proofArray[0]['times6']=0;
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    if(trim($proofArray[0]['act5_marks'])==''){
         $proofArray[0]['act5_marks']=0;
    }
    if(trim($proofArray[0]['act6_marks'])==''){
         $proofArray[0]['act6_marks']=0;
    }
  ?>
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Students Performance in Application Oriented Excercises(PBL) (Max : <?php echo $weightage; ?>)</b>
  </td>
 </tr>
 <tr>
  <td class="rowheading" align="left"><b>Even Semester(PBL)</b></td>
 </tr> 
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Describe</td>
        <td class="searchhead_text">pbls/Avg Marks/<br/>Eminent Projects<br/>(No. of times)</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of Problem Statements Designed<br/> and Uploaded(A) and For Which Subject(s)(10)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="even_prob_design" id="even_prob_design" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_prob_design']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="20" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times1" id="times1" maxlength="2" value="<?php echo $proofArray[0]['times1']; ?>" onblur="change15_1(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act1_marks" maxlength="2" value="<?php echo $proofArray[0]['act1_marks']; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Average Marks(B) Achieved By Student Teams<br/> In The Groups You Guided and Handled, Mention<br/> Details(10)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="even_avg_marks" id="even_avg_marks" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_avg_marks']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="10" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times2" id="times2" maxlength="2" value="<?php echo $proofArray[0]['times2']; ?>" onblur="change15_2(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act2_marks" id="act2_marks" maxlength="2" value="<?php echo $proofArray[0]['act2_marks']; ?>" />
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of Eminent Project(s) Guided(10)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="even_eminent" id="even_eminent" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_eminent']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="2" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times3" id="times3" maxlength="2" value="<?php echo $proofArray[0]['times3']; ?>" onblur="change15_3(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act3_marks" id="act3_marks" maxlength="2" value="<?php echo $proofArray[0]['act3_marks']; ?>" />
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="rowheading" align="left"><b>Odd Semester(PBL)</b></td>
 </tr>
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Describe</td>
        <td class="searchhead_text">pbls/Avg Marks/<br/>Eminent Projects<br/>(No. of times)</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of Problem Statements Designed<br/> and Uploaded(A) and For Which Subject(s)(10)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="odd_prob_design" id="odd_prob_design" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_prob_design']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="20" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times4" id="times4" maxlength="2" value="<?php echo $proofArray[0]['times4']; ?>" onblur="change15_4(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act4_marks" id="act4_marks" maxlength="2" value="<?php echo $proofArray[0]['act4_marks']; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Average Marks(B) Achieved By Student Teams<br/> In The Groups You Guided and Handled, Mention<br/> Details(10)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="odd_avg_marks" id="odd_avg_marks" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_avg_marks']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="10" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times5" id="times5" maxlength="2" value="<?php echo $proofArray[0]['times5']; ?>" onblur="change15_5(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act5_marks" id="act5_marks" maxlength="2" value="<?php echo $proofArray[0]['act5_marks']; ?>" />
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of Eminent Project(s) Guided(10)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="odd_eminent" id="odd_eminent" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_eminent']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="2" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times6" id="times6" maxlength="2" value="<?php echo $proofArray[0]['times6']; ?>" onblur="change15_6(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act6_marks" id="act6_marks" maxlength="2" value="<?php echo $proofArray[0]['act6_marks']; ?>" />
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr> 
    <td align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm15();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
    </td>
  </tr> 
</table>
    <?php
}

else if($proofId==16){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['even_advisor'])==''){
         $proofArray[0]['even_advisor']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_avg_gfs'])==''){
         $proofArray[0]['even_avg_gfs']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_adv_mt'])==''){
         $proofArray[0]['even_adv_mt']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['even_indis'])==''){
         $proofArray[0]['even_indis']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_advisor'])==''){
         $proofArray[0]['odd_advisor']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_avg_gfs'])==''){
         $proofArray[0]['odd_avg_gfs']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_adv_mt'])==''){
         $proofArray[0]['odd_adv_mt']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['odd_indis'])==''){
         $proofArray[0]['odd_indis']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['times1'])==''){
         $proofArray[0]['times1']=0;
    }
    if(trim($proofArray[0]['times2'])==''){
         $proofArray[0]['times2']=0;
    }
    if(trim($proofArray[0]['times3'])==''){
         $proofArray[0]['times3']=0;
    }
    if(trim($proofArray[0]['times4'])==''){
         $proofArray[0]['times4']=0;
    }
    if(trim($proofArray[0]['times5'])==''){
         $proofArray[0]['times5']=0;
    }
    if(trim($proofArray[0]['times6'])==''){
         $proofArray[0]['times6']=0;
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    if(trim($proofArray[0]['act5_marks'])==''){
         $proofArray[0]['act5_marks']=0;
    }
    if(trim($proofArray[0]['act6_marks'])==''){
         $proofArray[0]['act6_marks']=0;
    }
  ?>
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Students Mentoring Through Advisory System (Max : <?php echo $weightage; ?>)</b>
  </td>
 </tr>
 <tr>
  <td class="rowheading" align="left"><b>Even Semester(Advisory)</b></td>
 </tr> 
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Describe</td>
        <td class="searchhead_text">GFS marks/advisory meetings<br/>Indiscipline cases</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Mention the details of group, you were advisor(eg, 08BCAX1)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="even_advisor" id="even_advisor" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_advisor']); ?></textarea>
       </td>
       <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Average GFS marks scored by this group(say C)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="even_avg_gfs" id="even_avg_gfs" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_avg_gfs']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="100" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times1" id="times1" maxlength="3" value="<?php echo $proofArray[0]['times1']; ?>" onblur="change16_1(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act1_marks" maxlength="2" value="<?php echo $proofArray[0]['act1_marks']; ?>" />
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of advisory meetings you conducted, mention dates(E)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="even_adv_mt" id="even_adv_mt" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_adv_mt']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="2" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times2" id="times2" maxlength="2" value="<?php echo $proofArray[0]['times2']; ?>" onblur="change16_2(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act1_marks" id="act2_marks" maxlength="2" value="<?php echo $proofArray[0]['act2_marks']; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of indiscipline case(s)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="even_indis" id="even_indis" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['even_indis']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="3" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times3" id="times3" maxlength="2" value="<?php echo $proofArray[0]['times3']; ?>" onblur="change16_3(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act3_marks" id="act3_marks" maxlength="2" value="<?php echo $proofArray[0]['act3_marks']; ?>" />
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="rowheading" align="left"><b>Odd Semester(Advisory)</b></td>
 </tr> 
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Subject</td>
        <td class="searchhead_text">Describe</td>
        <td class="searchhead_text">GFS marks/advisory meetings<br/>Indiscipline cases</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Mention the details of group, you were advisor(eg, 08BCAX1)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="odd_advisor" id="odd_advisor" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_advisor']); ?></textarea>
       </td>
       <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Average GFS marks scored by this group(say C)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="odd_avg_gfs" id="odd_avg_gfs" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_avg_gfs']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="100" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times4" id="times4" maxlength="3" value="<?php echo $proofArray[0]['times4']; ?>" onblur="change16_4(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act4_marks" id="act4_marks" maxlength="2" value="<?php echo $proofArray[0]['act4_marks']; ?>" />
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of advisory meetings you conducted, mention dates(E)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="odd_adv_mt" id="odd_adv_mt" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_adv_mt']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="2" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times5" id="times5" maxlength="2" value="<?php echo $proofArray[0]['times5']; ?>" onblur="change16_5(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act5_marks" id="act5_marks" maxlength="2" value="<?php echo $proofArray[0]['act5_marks']; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Number of indiscipline case(s)
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="1" name="odd_indis" id="odd_indis" cols="35" maxlength="350" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['odd_indis']); ?></textarea>
       </td>
       <td class="padding" align="left">
        <input alt="3" <?php echo $disabled; ?>  class="inputbox" style="width:30px;"  type="text" name="times6" id="times6" maxlength="2" value="<?php echo $proofArray[0]['times6']; ?>" onblur="change16_6(this.value);" />
       </td>
       <td class="padding" align="left">
        <input disabled="disabled"  class="inputbox" style="width:30px;"  type="text" name="act6_marks" id="act6_marks" maxlength="2" value="<?php echo $proofArray[0]['act6_marks']; ?>" />
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr> 
    <td align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm16();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
    </td>
  </tr> 
</table>
    <?php
}

else if($proofId==17){
    
    function budgetDetails($val){
        //do not change INDEX of this array, These are required values HERE
        $array=array(
                     0=>'Not Applicable',
                     10=>'50k or less',
                     11=>'Budget 60k',
                     12=>'Budget 70k',
                     13=>'Budget 80k',
                     14=>'Budget 90k',
                     15=>'Budget 100k',
                     16=>'Budget 110k',
                     17=>'Budget 120k',
                     18=>'Budget 130k',
                     19=>'Budget 140k',
                     20=>'Budget 150k',
                     21=>'Budget 160k',
                     22=>'Budget 170k',
                     23=>'Budget 180k',
                     24=>'Budget 190k',
                     25=>'Budget 200k'
                  );
        $retunValue='';
        $selected='';
        foreach($array as $key=>$value){
           if($key==$val){
               $selected='selected="selected"';
           } 
           else{
               $selected='';
           }
           $retunValue .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        return $retunValue;
    }
    
    $not_applicable_form_string='';
    if(trim($proofArray[0]['act1'])==''){
         $proofArray[0]['act1']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act2'])==''){
         $proofArray[0]['act2']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act3'])==''){
         $proofArray[0]['act3']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['act4'])==''){
         $proofArray[0]['act4']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['budget1'])==''){
         $proofArray[0]['budget1']=0;
    }
    if(trim($proofArray[0]['budget2'])==''){
         $proofArray[0]['budget2']=0;
    }
    if(trim($proofArray[0]['budget3'])==''){
         $proofArray[0]['budget3']=0;
    }
    if(trim($proofArray[0]['budget4'])==''){
        $proofArray[0]['budget4']=0;
    }
    if(trim($proofArray[0]['org_duties'])==''){
        $proofArray[0]['org_duties']=$not_applicable_form_string;
    }
    if(trim($proofArray[0]['imp_duties'])==''){
        $proofArray[0]['imp_duties']=$not_applicable_form_string;
    }
    
    if(trim($proofArray[0]['act1_marks'])==''){
         $proofArray[0]['act1_marks']=0;
    }
    if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
    }
    if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
    }
    if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
    }
    if(trim($proofArray[0]['act5_marks'])==''){
         $proofArray[0]['act5_marks']=0;
    }
    if(trim($proofArray[0]['act6_marks'])==''){
         $proofArray[0]['act6_marks']=0;
    }
    
    if(trim($proofArray[0]['duties1'])==''){
         $proofArray[0]['duties1']=0;
    }
    if(trim($proofArray[0]['duties2'])==''){
         $proofArray[0]['duties2']=0;
    }
    
    
    //check for student welfare department
    $disabled2='disabled="disabled"';
    $studentWelfareArray=$appDataManager->checkStudentWelfareDepartment($employeeId);
    if($studentWelfareArray[0]['isStudentWelfare']==1){
       $disabled2='';
    }
  ?>
  
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Coordinating Students' Affairs (Max : <?php echo $weightage; ?>)</b>
  </td>
 </tr>
 <tr>
  <td class="rowheading" align="left"><b>Development And Planning Track(Max 100)(For Faculty in Students Welfare Department to Fill)</b></td>
 </tr> 
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Activity/Fests</td>
        <td class="searchhead_text">Budget(With Details)</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="padding" align="left">
       <input type="text" <?php echo $disabled2;?> name="act1" id="act1" class="inputbox" style="width:330px;" maxlength="49" value="<?php echo trim($proofArray[0]['act1']); ;?>" />
       </td>
       <td class="padding" align="left">
         <select name="budget1" id="budget1" class="inputbox" onchange="changeValue17_1(this.value);" <?php echo $disabled2; ?> >
          <?php
            echo budgetDetails($proofArray[0]['budget1']);
          ?>
         </select>
       </td>
       <td class="padding" align="left">
         <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['act1_marks']; ?>" />
       </td>
    </tr>
    
    <tr class="row1">
       <td class="padding" align="left">
       <input type="text" <?php echo $disabled2;?> name="act2" id="act2" class="inputbox" style="width:330px;" maxlength="49" value="<?php echo trim($proofArray[0]['act2']); ;?>" />
       </td>
       <td class="padding" align="left">
         <select name="budget2" id="budget2" class="inputbox" onchange="changeValue17_2(this.value);" <?php echo $disabled2; ?> >
          <?php
            echo budgetDetails($proofArray[0]['budget2']);
          ?>
         </select>
       </td>
       <td class="padding" align="left">
         <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['act2_marks']; ?>" />
       </td>
    </tr>
    
    <tr class="row0">
       <td class="padding" align="left">
       <input type="text" <?php echo $disabled2;?> name="act3" id="act3" class="inputbox" style="width:330px;" maxlength="49" value="<?php echo trim($proofArray[0]['act3']); ;?>" />
       </td>
       <td class="padding" align="left">
         <select name="budget3" id="budget3" class="inputbox" onchange="changeValue17_3(this.value);" <?php echo $disabled2; ?> >
          <?php
            echo budgetDetails($proofArray[0]['budget3']);
          ?>
         </select>
       </td>
       <td class="padding" align="left">
         <input type="text" disabled="disabled" name="act3_marks" id="act3_marks" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['act3_marks']; ?>" />
       </td>
    </tr>
    
    <tr class="row1">
       <td class="padding" align="left">
       <input type="text" <?php echo $disabled2;?> name="act4" id="act4" class="inputbox" style="width:330px;;" maxlength="49" value="<?php echo trim($proofArray[0]['act4']); ;?>" />
       </td>
       <td class="padding" align="left">
         <select name="budget4" id="budget4" class="inputbox" onchange="changeValue17_4(this.value);" <?php echo $disabled2; ?> >
          <?php
            echo budgetDetails($proofArray[0]['budget4']);
          ?>
         </select>
       </td>
       <td class="padding" align="left">
         <input type="text" disabled="disabled" name="act4_marks" id="act4_marks" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['act4_marks']; ?>" />
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="rowheading" align="left"><b>Execution & Implementation Track(Max 100)(For All Other Faculty to Fill)</b></td>
 </tr> 
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
     <tr class="rowheading">
        <td class="searchhead_text">Activity</td>
        <td class="searchhead_text">Details</td>
        <td class="searchhead_text">Duties</td>
        <td class="searchhead_text">Marks</td>
    </tr>         
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>
       Organizational duties performed, Score = n*10
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="org_duties" id="org_duties" cols="35" maxlength="249" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['org_duties']); ?></textarea>
       </td>
       <td class="padding"  align="left">
         <input type="text" alt="5" <?php echo $disabled?> name="duties1" id="duties1" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['duties1']; ?>" onchange="changeValue17_5(this.value);" />
       </td>
       <td class="padding"  align="left">
         <input type="text" disabled="disabled" name="act5_marks" id="act5_marks" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['act5_marks']; ?>" />
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>
       Implementation Duties Performed, Score = m*5
        </b> 
       </td>
       <td class="padding" align="left">
         <textarea <?php echo $disabled; ?> rows="2" name="imp_duties" id="imp_duties" cols="35" maxlength="249" onkeyup="return ismaxlength(this);"><?php echo trim($proofArray[0]['imp_duties']); ?></textarea>
       </td>
       <td class="padding"  align="left">
         <input type="text" alt="10" <?php echo $disabled?> name="duties2" id="duties2" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['duties2']; ?>" onchange="changeValue17_6(this.value);" />
       </td>
       <td class="padding"  align="left">
         <input type="text" disabled="disabled" name="act6_marks" id="act6_marks" class="inputbox" style="width:30px;" value="<?php echo $proofArray[0]['act6_marks']; ?>" />
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr> 
    <td align="center" colspan="4" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm17();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
    </td>
  </tr> 
</table>
    <?php
}

else if($proofId==14){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['oddsem'])==''){
         $proofArray[0]['oddsem']=0;
    }
    if(trim($proofArray[0]['evensem'])==''){
         $proofArray[0]['evensem']=0;
    }
    if(trim($proofArray[0]['score_gained'])==''){
         $proofArray[0]['score_gained']=0;
    }
    
    //fetch employee department
    $deptArray=$appDataManager->getEmployeeDepartment($employeeId);
    if(trim($deptArray[0]['departmentName'])=='' and trim($deptArray[0]['abbr'])==''){
        $employee_dept=NOT_APPLICABLE_STRING;
    }
    else{
      $employee_dept=trim($deptArray[0]['departmentName']).'( '.trim($deptArray[0]['abbr']).' )';
    }
  ?>
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Student's Performance : Theory/Practicals</b>
  </td>
 </tr>
 <tr>
  <td valign="top" colspan="2" align="left">
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Employee Code</b></td>
       <td class="contenttab_internal_rows" align="left">
         <?php
          if($hodEditFlag==0){
           echo $sessionHandler->getSessionVariable('EmployeeCodeToBeAppraised');
          }
          else{
           echo $sessionHandler->getSessionVariable('EmployeeCode');
          }
          ?>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Department</b></td>
       <td class="contenttab_internal_rows" align="left">
         <?php 
           echo $employee_dept;
         ?>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>No. of Subjects Teached In Odd Sem</b></td>
       <td class="padding_top" align="right">
         <?php 
         if($hodEditFlag==1){
          echo $proofArray[0]['oddsem']; 
         }
         else{
           ?>
            <input type="text" name="oddsem" id="oddsem" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['oddsem'];?>" />
           <?php
         }
         ?>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>No. of Subjects Teached In Even Sem</b></td>
       <td class="padding_top" align="right">
         <?php 
          if($hodEditFlag==1){
            echo $proofArray[0]['evensem']; 
          }
          else{
            ?>
            <input type="text" name="evensem" id="evensem" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['evensem'];?>" />
           <?php
          }
          ?>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Score Gained</b></td>
       <td class="padding_top" align="right">
         <?php 
          if($hodEditFlag==1){
           echo $proofArray[0]['score_gained']; 
          }
          else{
            ?>
            <input type="text" name="score_gained" id="score_gained" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['score_gained'];?>" />
           <?php  
          }
         ?>
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" colspan="2" align="left">
   <b><u>Evaluation Criteria</u></b>
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <ul>
    <li>Marks below 40%</li>
   </ul> 
  </td>
  <td class="contenttab_internal_rows">
   0 Marks
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <ul>
    <li>Marks between 40-50%</li>
   </ul> 
  </td>
  <td class="contenttab_internal_rows">
  1 Marks(for each student) 
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <ul>
    <li>Marks below 50-60%</li>
   </ul> 
  </td>
  <td class="contenttab_internal_rows">
  2 Marks(for each student) 
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <ul>
    <li>Marks below 60-70%</li>
   </ul> 
  </td>
  <td class="contenttab_internal_rows">
  3 Marks(for each student) 
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <ul>
    <li>Marks below 70-80%</li>
   </ul> 
  </td>
  <td class="contenttab_internal_rows">
  4 Marks(for each student) 
  </td>
 </tr>
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <ul>
    <li>Marks below 80-100%</li>
   </ul> 
  </td>
  <td class="contenttab_internal_rows">
  5 Marks(for each student) 
  </td>
 </tr>
 <?php
   if($hodEditFlag==0){
   ?> 
    <tr> 
    <td align="center">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm14();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
    </td>
  </tr>
  <?php
   }
   ?>
</table>
    <?php
}

else if($proofId==1){
    $not_applicable_form_string='';
    if(trim($proofArray[0]['internal'])==''){
         $proofArray[0]['internal']=0;
    }
    if(trim($proofArray[0]['external'])==''){
         $proofArray[0]['external']=0;
    }
    if(trim($proofArray[0]['copies_checked'])==''){
         $proofArray[0]['copies_checked']=0;
    }
    if(trim($proofArray[0]['super'])==''){
         $proofArray[0]['super']=0;
    }
    if(trim($proofArray[0]['weekends'])==''){
         $proofArray[0]['weekends']=0;
    }
    if(trim($proofArray[0]['score_gained'])==''){
         $proofArray[0]['score_gained']=0;
    }
    
    //fetch employee department
    $deptArray=$appDataManager->getEmployeeDepartment($employeeId);
    if(trim($deptArray[0]['departmentName'])=='' and trim($deptArray[0]['abbr'])==''){
        $employee_dept=NOT_APPLICABLE_STRING;
    }
    else{
      $employee_dept=trim($deptArray[0]['departmentName']).'( '.trim($deptArray[0]['abbr']).' )';
    }
  ?>
 <table border="0" cellpadding="0" cellpadding="0" width="100%">
 <tr>
  <td class="contenttab_internal_rows" align="left">
   <b>Examination Performance(Exam Internal/External Duties)</b>
  </td>
 </tr>
 <tr>
  <td valign="top">   
  <table border="0" cellpadding="0" cellspacing="1" width="100%">
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>Employee Code</b></td>
       <td class="contenttab_internal_rows" align="left">
         <?php echo $sessionHandler->getSessionVariable('EmployeeCode'); ?>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Department</b></td>
       <td class="contenttab_internal_rows" align="left">
         <?php echo $employee_dept; ?>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>No. of Invigilation Duties(Internal)</b></td>
       <td class="padding_top" align="right">
         <?php 
             if($hodEditFlag==1){
              echo $proofArray[0]['internal']; 
             } 
             else{
              ?>
               <input type="text" name="internal" id="internal" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['internal'];?>" />
              <?php   
             }   
              
             ?>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>No. of Invigilation Duties(External)</b></td>
       <td class="padding_top" align="right">
         <?php 
          if($hodEditFlag==1){
           echo $proofArray[0]['external'];
          }
          else{
           ?>
            <input type="text" name="external" id="external" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['external'];?>" />
           <?php   
          }
         
         ?>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>No. of Copies Checked</b></td>
       <td class="padding_top" align="right">
         <?php 
          if($hodEditFlag==1){
           echo $proofArray[0]['copies_checked']; 
          }
          else{
             ?>
             <input type="text" name="copies_checked" id="copies_checked" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['copies_checked'];?>" />  
             <?php 
          } 
         ?>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>No. of Times Act as External<br/> Supreintendent</b></td>
       <td class="padding_top" align="right">
         <?php 
          if($hodEditFlag==1){
           echo $proofArray[0]['super']; 
          }
          else{
           ?>
            <input type="text" name="super" id="super" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['super'];?>" />  
           <?php   
          }
         ?>
       </td>
    </tr>
    <tr class="row0">
       <td class="contenttab_internal_rows" align="left"><b>No. of Invigilation Duties Performed<br/> on Weekends</b></td>
       <td class="padding_top" align="right">
         <?php 
          if($hodEditFlag==1){
            echo $proofArray[0]['weekends']; 
          }
          else{
             ?>
             <input type="text" name="weekends" id="weekends" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['weekends'];?>" />  
             <?php 
          }
         ?>
       </td>
    </tr>
    <tr class="row1">
       <td class="contenttab_internal_rows" align="left"><b>Score Gained</b></td>
       <td class="padding_top" align="right">
         <?php
          if($hodEditFlag==1){
           echo $proofArray[0]['score_gained'];
          }
          else{
            ?>
            <input type="text" name="score_gained" id="score_gained" style="width:30px;" class="inputbox" maxlength="3" value="<?php echo $proofArray[0]['score_gained'];?>" />  
            <?php  
          }
         ?>
       </td>
    </tr>
   </table>
  </td>
 </tr>
 <?php
   if($hodEditFlag==0){
   ?> 
    <tr> 
    <td align="center">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm1();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
    </td>
  </tr>
  <?php
   }
   ?>
</table>
    <?php
} //*****************FORMS WITH FILE UPLOADING*********************
else if($proofId==18){
   if(trim($proofArray[0]['patent_name'])==''){
      $proofArray[0]['patent_name']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['cofiler1'])==''){
      $proofArray[0]['cofiler1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   if(trim($proofArray[0]['patent_granted'])==''){
      $proofArray[0]['patent_granted']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['cofiler2'])==''){
         $proofArray[0]['cofiler2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;  
   }
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="4">
    <b>
     Patent Proofs ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Patent Names</td>
        <td class="searchhead_text">Co-Filer Names</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="patent_name" id="patent_name" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['patent_name'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="cofiler1" id="cofiler1" class="inputbox" maxlength="100" value="<?php echo $proofArray[0]['cofiler1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="download('/Proof18/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="patent1" id="patent1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Patent Granted</td>
        <td class="searchhead_text">Co-Filer Names</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="patent_granted" id="patent_granted" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['patent_granted'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="cofiler2" id="cofiler2" class="inputbox" maxlength="100" value="<?php echo $proofArray[0]['cofiler2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof18/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="patent2" id="patent2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
       <?php echo $deleteString2; ?>
      </td>
    </tr>
    <tr><td colspan="5" height="5px;"></td></tr>
    <tr> 
     <td colspan="5" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm18();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}
else if($proofId==19){
   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['jname2'])==''){
      $proofArray[0]['jname2']='';
   }
   
   if(trim($proofArray[0]['impact1'])==''){
      $proofArray[0]['impact1']='';
   }
   if(trim($proofArray[0]['impact2'])==''){
      $proofArray[0]['impact2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;  
   }
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Research Publications ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
    <b>Impact factor 1.0 or above</b>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname1" id="jname1" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact1" id="impact1" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	 <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof19/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname2" id="jname2" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact2" id="impact2" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact2'];?>" />
      </td>
	  
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="actq_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof19/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
    
    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm19();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==20){
   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['jname2'])==''){
      $proofArray[0]['jname2']='';
   }
   
   if(trim($proofArray[0]['impact1'])==''){
      $proofArray[0]['impact1']='';
   }
   if(trim($proofArray[0]['impact2'])==''){
      $proofArray[0]['impact2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;  
   }
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     International Publication ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
    <b>Impact factor 0.3 to 0.9</b>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname1" id="jname1" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact1" id="impact1" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof20/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>

      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
	    <td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname2" id="jname2" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact2" id="impact2" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof20/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
    
    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm20();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==21){
   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['impact1'])==''){
      $proofArray[0]['impact1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['jname2'])==''){
      $proofArray[0]['jname2']='';
   }
   if(trim($proofArray[0]['impact2'])==''){
      $proofArray[0]['impact2']='';
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if(trim($proofArray[0]['pub3'])==''){
      $proofArray[0]['pub3']='';
      $deleteString3=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString3='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(3);"/>'; 
   }
   if(trim($proofArray[0]['co3'])==''){
         $proofArray[0]['co3']='';
   }
   if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
   }
   if(trim($proofArray[0]['jname3'])==''){
      $proofArray[0]['jname3']='';
   }
   if(trim($proofArray[0]['impact3'])==''){
      $proofArray[0]['impact3']='';
   }
   if(trim($proofArray[0]['file3'])==''){
         $proofArray[0]['file3']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
     $deleteString3=NOT_APPLICABLE_STRING;
   }
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     International Publication ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
    <b>Impact factor less than 0.3</b>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname1" id="jname1" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact1" id="impact1" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof21/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
	    <td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname2" id="jname2" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact2" id="impact2" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof21/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub3" id="pub3" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co3" id="co3" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname3" id="jname3" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact3" id="impact3" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact3'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act3_marks" id="act3_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act3_marks'];?>" />
      </td>
		<td class="padding">
		<?php
			if($proofArray[0]['file3']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof21/Third/<?php echo $proofArray[0]['file3']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof3" id="proof3" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString3; ?>
      </td>
    </tr>
    
    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm21();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==22){
	/*echo "<pre>";
	print_r($proofArray);
	die;
*/   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['impact1'])==''){
      $proofArray[0]['impact1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['jname2'])==''){
      $proofArray[0]['jname2']='';
   }
   if(trim($proofArray[0]['impact2'])==''){
      $proofArray[0]['impact2']='';
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if(trim($proofArray[0]['pub3'])==''){
      $proofArray[0]['pub3']='';
      $deleteString3=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString3='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(3);"/>'; 
   }
   if(trim($proofArray[0]['co3'])==''){
         $proofArray[0]['co3']='';
   }
   if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
   }
   if(trim($proofArray[0]['jname3'])==''){
      $proofArray[0]['jname3']='';
   }
   if(trim($proofArray[0]['impact3'])==''){
      $proofArray[0]['impact3']='';
   }
   if(trim($proofArray[0]['file3'])==''){
         $proofArray[0]['file3']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
     $deleteString3=NOT_APPLICABLE_STRING;
   }
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     National Publication in refered journals ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
    <b>Impact factor less than 0.3</b>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname1" id="jname1" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact1" id="impact1" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	<td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof22/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>

      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname2" id="jname2" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact2" id="impact2" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof22/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub3" id="pub3" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co3" id="co3" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname3" id="jname3" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact3" id="impact3" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact3'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act3_marks" id="act3_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act3_marks'];?>" />
      </td>
	 <td class="padding">
		<?php
			if($proofArray[0]['file3']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof22/Third/<?php echo $proofArray[0]['file3']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>

	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof3" id="proof3" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString3; ?>
      </td>
    </tr>
    
    <tr><td colspan="7" height="5px;" ></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm22();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==23){
	
   if(trim($proofArray[0]['pub'])==''){
      $proofArray[0]['pub']='';
      $deleteString=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co'])==''){
      $proofArray[0]['co']='';
   }
   if(trim($proofArray[0]['publish'])==''){
      $proofArray[0]['publish']='';
   }
   if(trim($proofArray[0]['act_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file'])==''){
      $proofArray[0]['file']='';
   }
   
   if($hodEditFlag==0){
     $deleteString=NOT_APPLICABLE_STRING;
   }
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Books Published by International publishing house ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Publishing-House</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         

    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub" id="pub" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['pub'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co" id="co" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['co'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="publish" id="publish" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['publish'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act_marks" id="act_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof23/<?php echo $proofArray[0]['file']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>

      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof" id="proof" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString; ?>
      </td>
    </tr>
    
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr> 
     <td colspan="6" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm23();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==24){
	//echo "<pre>";
	//print_r($proofArray);
	//die;
   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['publish1'])==''){
      $proofArray[0]['publish1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['publish2'])==''){
      $proofArray[0]['publish2']='';
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
   }
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Books Published by National publishing house ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Publishing-House</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="publish1" id="publish1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['publish1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof24/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Publishing-House</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="publish2" id="publish2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['publish2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof24/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
    
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr> 
     <td colspan="6" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm24();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==25){
   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['conf_name1'])==''){
      $proofArray[0]['conf_name1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['conf_name2'])==''){
      $proofArray[0]['conf_name2']='';
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
   }
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Research Publication in International Conferences ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Conference-Name</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="conf_name1" id="conf_name1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['conf_name1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof25/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Conference-Name</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="conf_name2" id="conf_name2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['conf_name2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof25/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
   
    <tr><td colspan="6" height="5px;"></td></tr>
    <tr> 
     <td colspan="6" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm25();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==26){
   if(trim($proofArray[0]['pub1'])==''){
      $proofArray[0]['pub1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['co1'])==''){
      $proofArray[0]['co1']='';
   }
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['impact1'])==''){
      $proofArray[0]['impact1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['pub2'])==''){
      $proofArray[0]['pub2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['co2'])==''){
         $proofArray[0]['co2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
         $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['jname2'])==''){
      $proofArray[0]['jname2']='';
   }
   if(trim($proofArray[0]['impact2'])==''){
      $proofArray[0]['impact2']='';
   }
   if(trim($proofArray[0]['file2'])==''){
         $proofArray[0]['file2']='';
   }
   
   if(trim($proofArray[0]['pub3'])==''){
      $proofArray[0]['pub3']='';
      $deleteString3=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString3='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(3);"/>'; 
   }
   if(trim($proofArray[0]['co3'])==''){
         $proofArray[0]['co3']='';
   }
   if(trim($proofArray[0]['act3_marks'])==''){
         $proofArray[0]['act3_marks']=0;
   }
   if(trim($proofArray[0]['jname3'])==''){
      $proofArray[0]['jname3']='';
   }
   if(trim($proofArray[0]['impact3'])==''){
      $proofArray[0]['impact3']='';
   }
   if(trim($proofArray[0]['file3'])==''){
         $proofArray[0]['file3']='';
   }
   
   if(trim($proofArray[0]['pub4'])==''){
      $proofArray[0]['pub4']='';
      $deleteString4=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString4='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(4);"/>'; 
   }
   if(trim($proofArray[0]['co4'])==''){
         $proofArray[0]['co4']='';
   }
   if(trim($proofArray[0]['act4_marks'])==''){
         $proofArray[0]['act4_marks']=0;
   }
   if(trim($proofArray[0]['jname4'])==''){
      $proofArray[0]['jname4']='';
   }
   if(trim($proofArray[0]['impact4'])==''){
      $proofArray[0]['impact4']='';
   }
   if(trim($proofArray[0]['file4'])==''){
         $proofArray[0]['file4']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
     $deleteString3=NOT_APPLICABLE_STRING;
     $deleteString4=NOT_APPLICABLE_STRING;
   }
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     National Publication in refered journals ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
    <b>Impact factor greater than 0</b>
  </td>
 </tr>
     <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub1" id="pub1" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co1" id="co1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname1" id="jname1" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact1" id="impact1" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact1'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
		<?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof26/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub2" id="pub2" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co2" id="co2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname2" id="jname2" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact2" id="impact2" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact2'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	 <td class="padding">
	 <?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof26/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
		</td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub3" id="pub3" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co3" id="co3" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname3" id="jname3" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname3'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact3" id="impact3" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact3'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act3_marks" id="act3_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act3_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file3']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof26/Third/<?php echo $proofArray[0]['file3']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>

      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof3" id="proof3" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString3; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Publication-Title</td>
        <td class="searchhead_text">Co-Author</td>
        <td class="searchhead_text">Journal Name</td>
        <td class="searchhead_text">Impact</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="pub4" id="pub4" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['pub4'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="co4" id="co4" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['co4'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="jname4" id="jname4" class="inputbox" maxlength="70" value="<?php echo $proofArray[0]['jname4'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="impact4" id="impact4" class="inputbox" maxlength="4" style="width:30px;" value="<?php echo $proofArray[0]['impact4'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act4_marks" id="act4_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act4_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file3']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof26/Third/<?php echo $proofArray[0]['file3']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof4" id="proof4" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString4; ?>
      </td>
    </tr>
    
    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm26();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}


else if($proofId==27){
   if(trim($proofArray[0]['workshop1'])==''){
      $proofArray[0]['workshop1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['institute1'])==''){
      $proofArray[0]['institute1']='';
   }
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['test_input1'])=='' or trim($proofArray[0]['test_input1'])=='0000-00-00'){
      $proofArray[0]['test_input1']='';
   }
   if(trim($proofArray[0]['test_input2'])=='' or trim($proofArray[0]['test_input2'])=='0000-00-00'){
      $proofArray[0]['test_input2']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['workshop2'])==''){
      $proofArray[0]['workshop2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['institute2'])==''){
      $proofArray[0]['institute2']='';
   }
   if(trim($proofArray[0]['test_input3'])=='' or trim($proofArray[0]['test_input3'])=='0000-00-00'){
      $proofArray[0]['test_input3']='';
   }
   if(trim($proofArray[0]['test_input4'])=='' or trim($proofArray[0]['test_input4'])=='0000-00-00'){
      $proofArray[0]['test_input4']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
      $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['file2'])==''){
      $proofArray[0]['file2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
   }
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     AICTE/QIP workshops at IITS,NITs, NITTRs, IUCEE ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Workshop-Name</td>
        <td class="searchhead_text">Institute-Name</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="workshop1" id="workshop1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['workshop1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="institute1" id="institute1" class="inputbox" maxlength="50" value="<?php echo $proofArray[0]['institute1'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input1',$proofArray[0]['test_input1']); 
       ?>
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input2',$proofArray[0]['test_input2']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof27/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Workshop-Name</td>
        <td class="searchhead_text">Institute-Name</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="workshop2" id="workshop2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['workshop2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="institute2" id="institute2" class="inputbox" maxlength="50" value="<?php echo $proofArray[0]['institute2'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input3',$proofArray[0]['test_input3']); 
       ?>
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input4',$proofArray[0]['test_input4']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof27/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>

    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateProofForm27();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==28){
   if(trim($proofArray[0]['workshop1'])==''){
      $proofArray[0]['workshop1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['institute1'])==''){
      $proofArray[0]['institute1']='';
   }
   if(trim($proofArray[0]['jname1'])==''){
      $proofArray[0]['jname1']='';
   }
   if(trim($proofArray[0]['test_input1'])=='' or trim($proofArray[0]['test_input1'])=='0000-00-00'){
      $proofArray[0]['test_input1']='';
   }
   if(trim($proofArray[0]['test_input2'])=='' or trim($proofArray[0]['test_input2'])=='0000-00-00'){
      $proofArray[0]['test_input2']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['workshop2'])==''){
      $proofArray[0]['workshop2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['institute2'])==''){
      $proofArray[0]['institute2']='';
   }
   if(trim($proofArray[0]['test_input3'])=='' or trim($proofArray[0]['test_input3'])=='0000-00-00'){
      $proofArray[0]['test_input3']='';
   }
   if(trim($proofArray[0]['test_input4'])=='' or trim($proofArray[0]['test_input4'])=='0000-00-00'){
      $proofArray[0]['test_input4']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
      $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['file2'])==''){
      $proofArray[0]['file2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
   }
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Other STTPs/Workshops ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Workshop-Name</td>
        <td class="searchhead_text">Institute-Name</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="workshop1" id="workshop1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['workshop1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="institute1" id="institute1" class="inputbox" maxlength="50" value="<?php echo $proofArray[0]['institute1'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input1',$proofArray[0]['test_input1']); 
       ?>
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input2',$proofArray[0]['test_input2']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	 <td class="padding">
	   <?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof28/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Workshop-Name</td>
        <td class="searchhead_text">Institute-Name</td>
        <td class="searchhead_text">Held From</td>
        <td class="searchhead_text">Held To</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>         
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="workshop2" id="workshop2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['workshop2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="institute2" id="institute2" class="inputbox" maxlength="50" value="<?php echo $proofArray[0]['institute2'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input3',$proofArray[0]['test_input3']); 
       ?>
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input4',$proofArray[0]['test_input4']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	   <td class="padding">
	   <?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof28/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>

    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm28();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==29){
   if(trim($proofArray[0]['proposal'])==''){
      $proofArray[0]['proposal']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['agency'])==''){
      $proofArray[0]['agency']='';
   }
   if(trim($proofArray[0]['costing'])==''){
      $proofArray[0]['costing']='';
   }
   if(trim($proofArray[0]['test_input'])=='' or trim($proofArray[0]['test_input'])=='0000-00-00'){
      $proofArray[0]['test_input']='';
   }
   if(trim($proofArray[0]['act_marks'])==''){
      $proofArray[0]['act_marks']=0;
   }
   if(trim($proofArray[0]['file'])==''){
      $proofArray[0]['file']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
   }
   
function  getCostingData($val){
    $costingArray=array(
                        0=>'Select',
                        110=>'10lac-20lac',
                        120=>'21lac-25lac',
                        130=>'26lac-30lac',
                        140=>'31lac-35lac',
                        150=>'36lac-40lac',
                        160=>'41lac-45lac',
                        170=>'46lac-50lac',
                        180=>'51lac-55lac',
                        200=>'56lac-60lac',
                        210=>'61lac-65lac',
                        220=>'66lac-70lac',
                        230=>'71lac-75lac',
                        240=>'76lac-80lac',
                        250=>'81lac-85lac',
                        260=>'86lac-90lac',
                        270=>'91lac-95lac',
						280=>'95lac-1crore',
						290=>'1.1crore-1.5crore',
						300=>'1.6crore-2.0crore',
						310=>'2.1crore-2.5crore',
						320=>'2.6crore-3.0crore',
						330=>'3.1crore-3.5crore',
						340=>'3.6crore-4.0crore',
						350=>'4.1crore-4.5crore',
						360=>'4.6crore-5.0crore',
						370=>'5.1crore-5.5crore',
						380=>'5.6crore-6.0crore',
						390=>'6.1crore-6.5crore',
						400=>'6.6crore-7.0crore',
						410=>'7.1crore-7.5crore',
						420=>'7.6crore-8.0crore',
						430=>'8.1crore-8.5crore',
						440=>'8.6crore-9.0crore',
						450=>'9.1crore-9.5crore',
						460=>'9.6crore-10.0crore',
						470=>'10.1crore-10.5crore',
						480=>'10.6crore-11.0crore',
						490=>'11.1crore-11.5crore',
						500=>'11.6crore-12.0crore'
						
						
                       );
					   
    $returnString='';
    foreach($costingArray as $key=>$value){
        $selected='';
        if($key==$val){
            $selected='selected="selected"';
        }
        $returnString .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    }
    
    return $returnString;
}  
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Research Proposal Submitted ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Proposal-Name</td>
        <td class="searchhead_text">Agency-Name</td>
        <td class="searchhead_text">Submitted Date</td>
        <td class="searchhead_text">Costing</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="proposal" id="proposal" class="inputbox" maxlength="100" value="<?php echo $proofArray[0]['proposal'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="agency" id="agency" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['agency'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input',$proofArray[0]['test_input']); 
       ?>
      </td>
      <td class="padding">
        <select name="costing" id="costing" class="inputbox" style="width:120px;"  <?php $disabled; ?> >
         <?php
           echo getCostingData($proofArray[0]['costing']);
         ?>
        </select>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act_marks" id="act_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof29/<?php echo $proofArray[0]['file']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>

      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof" id="proof" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>

    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm29();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}

else if($proofId==30){
   if(trim($proofArray[0]['proposal'])==''){
      $proofArray[0]['proposal']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['agency'])==''){
      $proofArray[0]['agency']='';
   }
   if(trim($proofArray[0]['costing'])==''){
      $proofArray[0]['costing']='';
   }
   if(trim($proofArray[0]['test_input'])=='' or trim($proofArray[0]['test_input'])=='0000-00-00'){
      $proofArray[0]['test_input']='';
   }
   if(trim($proofArray[0]['act_marks'])==''){
      $proofArray[0]['act_marks']=0;
   }
   if(trim($proofArray[0]['file'])==''){
      $proofArray[0]['file']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
   }
   
function  getCostingData2($val){
    $costingArray=array(
                        0=>'Select',
                        100=>'10lac-20lac',
                        120=>'21lac-25lac',
                        140=>'26lac-30lac',
                        160=>'31lac-35lac',
                        180=>'36lac-40lac',
                        200=>'41lac-45lac',
                        220=>'46lac-51lac',
                        240=>'51lac-55lac',
                        260=>'56lac-60lac',
                        280=>'61lac-65lac',
                        300=>'66lac-70lac',
                        320=>'71lac-75lac',
                        340=>'76lac-80lac',
                        360=>'81lac-85lac',
                        380=>'86lac-90lac',
                        400=>'91lac-95lac',
						420=>'95lac-1crore',
						440=>'1.1crore-1.5crore',
						460=>'1.6crore-2.0crore',
						480=>'2.1crore-2.5crore',
						500=>'2.6crore-3.0crore',
						520=>'3.1crore-3.5crore',
						540=>'3.6crore-4.0crore',
						560=>'4.1crore-4.5crore',
						580=>'4.6crore-5.0crore',
						600=>'5.1crore-5.5crore',
						620=>'5.6crore-6.0crore',
						640=>'6.1crore-6.5crore',
						660=>'6.6crore-7.0crore',
						680=>'7.1crore-7.5crore',
						700=>'7.6crore-8.0crore',
						720=>'8.1crore-8.5crore',
						740=>'8.6crore-9.0crore',
						760=>'9.1crore-9.5crore',
						780=>'9.6crore-10.0crore',
						800=>'10.1crore-10.5crore',
						820=>'10.6crore-11.0crore',
						840=>'11.1crore-11.5crore',
						860=>'11.6crore-12.0crore',
						880=>'12.1crore-12.5crore',
						900=>'12.6crore-13.0crore',
						920=>'13.1crore-13.5crore',
						940=>'13.6crore-14.0crore',
						960=>'14.1crore-14.5crore',
						980=>'14.6crore-15.0crore',
						1000=>'11.1crore-11.5crore'
						
                       );
    $returnString='';
    foreach($costingArray as $key=>$value){
        $selected='';
        if($key==$val){
            $selected='selected="selected"';
        }
        $returnString .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    }
    
    return $returnString;
}  
   
?>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="7">
    <b>
     Research Proposal Accepted ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Proposal-Name</td>
        <td class="searchhead_text">Agency-Name</td>
        <td class="searchhead_text">Submitted Date</td>
        <td class="searchhead_text">Costing</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="proposal" id="proposal" class="inputbox" maxlength="100" value="<?php echo $proofArray[0]['proposal'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="agency" id="agency" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['agency'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input',$proofArray[0]['test_input']); 
       ?>
      </td>
      <td class="padding">
        <select name="costing" id="costing" class="inputbox" style="width:120px;"  <?php $disabled; ?> >
         <?php
           echo getCostingData2($proofArray[0]['costing']);
         ?>
        </select>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act_marks" id="act_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act_marks'];?>" />
      </td>
		<td class="padding">
	   <?php
			if($proofArray[0]['file']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof30/<?php echo $proofArray[0]['file']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>

      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof" id="proof" class="inputbox" size="17" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>

    <tr><td colspan="7" height="5px;"></td></tr>
    <tr> 
     <td colspan="7" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm30();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>
<?php    
}


else if($proofId==31){
   if(trim($proofArray[0]['project'])==''){
      $proofArray[0]['project']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['agency'])==''){
      $proofArray[0]['agency']='';
   }
   if(trim($proofArray[0]['costing'])==''){
      $proofArray[0]['costing']='';
   }
   if(trim($proofArray[0]['test_input'])=='' or trim($proofArray[0]['test_input'])=='0000-00-00'){
      $proofArray[0]['test_input']='';
   }
   if(trim($proofArray[0]['add_amount'])==''){
      $proofArray[0]['add_amount']=0;
   }
   if(trim($proofArray[0]['act_marks'])==''){
      $proofArray[0]['act_marks']=0;
   }
   if(trim($proofArray[0]['file'])==''){
      $proofArray[0]['file']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
   }
   
function  getCostingData3($val){
    $costingArray=array(
                        0=>'Select',
                        50=>'1lac-2lac'
						/*100=>'1lac-2lac',
						150=>'1lac-2lac',
						200=>'1lac-2lac',
						250=>'1lac-2lac',
						300=>'1lac-2lac'
						*/
                       );
    $returnString='';
    foreach($costingArray as $key=>$value){
        $selected='';
        if($key==$val){
            $selected='selected="selected"';
        }
        $returnString .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    }
    
    return $returnString;
}  
   
?>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="8">
    <b>
     Industrial/Consultancy Project Working On ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Project-Name</td>
        <td class="searchhead_text">Agency-Name</td>
        <td class="searchhead_text">Starting Date</td>
        <td class="searchhead_text">Costing</td>
        <td class="searchhead_text">Addit. Amt.</td>
        <td class="searchhead_text">Points</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="project" id="project" class="inputbox" maxlength="100" value="<?php echo $proofArray[0]['project'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="agency" id="agency" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['agency'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input',$proofArray[0]['test_input']); 
       ?>
      </td>
      <td class="padding">
        <select name="costing" id="costing" class="inputbox" style="width:100px;"  <?php $disabled; ?> >
         <?php
           echo getCostingData3($proofArray[0]['costing']);
         ?>
        </select>
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="add_amount" id="add_amount" class="inputbox" maxlength="10" style="width:70px;" value="<?php echo $proofArray[0]['add_amount'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act_marks" id="act_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act_marks'];?>" />
      </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof" id="proof" class="inputbox" size="12" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>

    <tr><td colspan="8" height="5px;"></td></tr>
    <tr> 
     <td colspan="8" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm31();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>

<?php    
}

else if($proofId==32){
   if(trim($proofArray[0]['project'])==''){
      $proofArray[0]['project']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['agency'])==''){
      $proofArray[0]['agency']='';
   }
   if(trim($proofArray[0]['costing'])==''){
      $proofArray[0]['costing']='';
   }
   if(trim($proofArray[0]['test_input'])=='' or trim($proofArray[0]['test_input'])=='0000-00-00'){
      $proofArray[0]['test_input']='';
   }
   if(trim($proofArray[0]['add_amount'])==''){
      $proofArray[0]['add_amount']=0;
   }
   if(trim($proofArray[0]['act_marks'])==''){
      $proofArray[0]['act_marks']=0;
   }
   if(trim($proofArray[0]['file'])==''){
      $proofArray[0]['file']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
   }
   
function  getCostingData4($val){
    $costingArray=array(
                        0=>'Select',
                        100=>'1lac-2lac'
                       );
    $returnString='';
    foreach($costingArray as $key=>$value){
        $selected='';
        if($key==$val){
            $selected='selected="selected"';
        }
        $returnString .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    }
    
    return $returnString;
}  
   
?>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="8">
    <b>
     Industrial/Consultancy Project Delivered Successfully ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Project-Name</td>
        <td class="searchhead_text">Agency-Name</td>
        <td class="searchhead_text">Delivered On</td>
        <td class="searchhead_text">Costing</td>
        <td class="searchhead_text">Addit. Amt.</td>
        <td class="searchhead_text">Points</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="project" id="project" class="inputbox" maxlength="100" value="<?php echo $proofArray[0]['project'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="agency" id="agency" class="inputbox" maxlength="80" value="<?php echo $proofArray[0]['agency'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input',$proofArray[0]['test_input']); 
       ?>
      </td>
      <td class="padding">
        <select name="costing" id="costing" class="inputbox" style="width:100px;"  <?php $disabled; ?> >
         <?php
           echo getCostingData4($proofArray[0]['costing']);
         ?>
        </select>
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="add_amount" id="add_amount" class="inputbox" maxlength="10" style="width:70px;" value="<?php echo $proofArray[0]['add_amount'];?>" />
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act_marks" id="act_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act_marks'];?>" />
      </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof" id="proof" class="inputbox" size="12" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>

    <tr><td colspan="8" height="5px;"></td></tr>
    <tr> 
     <td colspan="8" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm32();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>

<?php    
}

else if($proofId==33){
   if(trim($proofArray[0]['description'])==''){
      $proofArray[0]['description']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['byWhom'])==''){
      $proofArray[0]['byWhom']='';
   }
   if(trim($proofArray[0]['test_input'])=='' or trim($proofArray[0]['test_input'])=='0000-00-00'){
      $proofArray[0]['test_input']='';
   }
   if(trim($proofArray[0]['act_marks'])==''){
      $proofArray[0]['act_marks']=0;
   }
   if(trim($proofArray[0]['file'])==''){
      $proofArray[0]['file']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
   }
?>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="6">
    <b>
     International Awards/Recognition Recieved ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Description</td>
        <td class="searchhead_text">By Whom</td>
        <td class="searchhead_text">When Recieved</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="description" id="description" class="inputbox" maxlength="200" value="<?php echo $proofArray[0]['description'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="byWhom" id="byWhom" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['byWhom'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input',$proofArray[0]['test_input']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act_marks" id="act_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof33/<?php echo $proofArray[0]['file']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
	  </td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof" id="proof" class="inputbox" size="12" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>

    <tr><td colspan="6" height="5px;"></td></tr>
    <tr> 
     <td colspan="6" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm33();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>

<?php    
}


else if($proofId==34){
   if(trim($proofArray[0]['description1'])==''){
      $proofArray[0]['description1']='';
      $deleteString1=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString1='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(1);"/>'; 
   }
   if(trim($proofArray[0]['byWhom1'])==''){
      $proofArray[0]['byWhom1']='';
   }
   if(trim($proofArray[0]['test_input1'])=='' or trim($proofArray[0]['test_input1'])=='0000-00-00'){
      $proofArray[0]['test_input1']='';
   }
   if(trim($proofArray[0]['act1_marks'])==''){
      $proofArray[0]['act1_marks']=0;
   }
   if(trim($proofArray[0]['file1'])==''){
      $proofArray[0]['file1']='';
   }
   
   if(trim($proofArray[0]['description2'])==''){
      $proofArray[0]['description2']='';
      $deleteString2=NOT_APPLICABLE_STRING;
   }
   else{
      $deleteString2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteRecord(2);"/>'; 
   }
   if(trim($proofArray[0]['byWhom2'])==''){
      $proofArray[0]['byWhom2']='';
   }
   if(trim($proofArray[0]['test_input2'])=='' or trim($proofArray[0]['test_input2'])=='0000-00-00'){
      $proofArray[0]['test_input2']='';
   }
   if(trim($proofArray[0]['act2_marks'])==''){
      $proofArray[0]['act2_marks']=0;
   }
   if(trim($proofArray[0]['file2'])==''){
      $proofArray[0]['file2']='';
   }
   
   if($hodEditFlag==0){
     $deleteString1=NOT_APPLICABLE_STRING;
     $deleteString2=NOT_APPLICABLE_STRING;
   }
?>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
 <tr>                                                            
  <td class="contenttab_internal_rows" align="left" colspan="6">
    <b>
     National Awards/Recognition Recieved ( Max : <?php echo $weightage; ?> )
    </b>
    No Points will be awarded until you upload proof.<br/>
  </td>
 </tr>
    <tr class="rowheading">
        <td class="searchhead_text">Description</td>
        <td class="searchhead_text">By Whom</td>
        <td class="searchhead_text">When Recieved</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">Attachment</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="description1" id="description1" class="inputbox" maxlength="200" value="<?php echo $proofArray[0]['description1'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="byWhom1" id="byWhom1" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['byWhom1'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input1',$proofArray[0]['test_input1']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act1_marks" id="act1_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act1_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file1']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof34/First/<?php echo $proofArray[0]['file1']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
		</td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof1" id="proof1" class="inputbox" size="12" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString1; ?>
      </td>
    </tr>
    
    <tr class="rowheading">
        <td class="searchhead_text">Description</td>
        <td class="searchhead_text">By Whom</td>
        <td class="searchhead_text">When Recieved</td>
        <td class="searchhead_text">Points</td>
		<td class="searchhead_text">AttachmentSS</td>
        <td class="searchhead_text">Upload Proof</td>
        <td class="searchhead_text">Delete</td>
    </tr>
    <tr class="row0">
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="description2" id="description2" class="inputbox" maxlength="200" value="<?php echo $proofArray[0]['description2'];?>" />
      </td>
      <td class="padding">
       <input type="text" <?php echo $disabled; ?> name="byWhom2" id="byWhom2" class="inputbox" maxlength="75" value="<?php echo $proofArray[0]['byWhom2'];?>" />
      </td>
      <td class="padding">
       <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('test_input2',$proofArray[0]['test_input2']); 
       ?>
      </td>
      <td class="padding">
       <input type="text" disabled="disabled" name="act2_marks" id="act2_marks" class="inputbox" style="width:30px;" maxlength="3" value="<?php echo $proofArray[0]['act2_marks'];?>" />
      </td>
	  <td class="padding">
	   <?php
			if($proofArray[0]['file2']!='') {
		?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  onclick="download('/Proof34/Second/<?php echo $proofArray[0]['file2']; ?>');return false;">
			<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" /></a>
		<?php
			} else { echo NOT_APPLICABLE_STRING; }
		?>
		</td>
      <td class="padding">
       <input type="file" <?php echo $disabled; ?> name="proof2" id="proof2" class="inputbox" size="12" />
      </td>
      <td class="padding" align="center">
        <?php echo $deleteString2; ?>
      </td>
    </tr>

    <tr><td colspan="6" height="5px;"></td></tr>
    <tr> 
     <td colspan="6" align="center" <?php echo $hodEditDisabled ?>>
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onclick="return validateProofForm34();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('ProofDiv');return false;" />
     </td>
  </tr> 
  </table>

<?php    
}




// $History: ajaxGetValues.php $
?>