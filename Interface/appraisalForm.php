<?php
//-------------------------------------------------------
// Purpose: To generate student list for subject centric
// functionality
// Author : Dipanjan Bhattacharjee
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
//----------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
 define('MODULE','EmployeeAppraisal');
}
else{
 define('MODULE','AppraisalForm');
}
define('ACCESS','view');

$roleId=$sessionHandler->getSessionVariable('RoleId');
if(SHOW_EMPLOYEE_APPRAISAL_FORM!=1){
  redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}
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
  redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}

require_once(BL_PATH . "/Appraisal/AppraisalData/initData.php");
$allowedFilesForAppraisal=array('gif','jpg','jpeg','png','bmp','pdf');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Appraisal</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
//require_once(CSS_PATH .'/tab-view.css');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");

//pareses input and returns "-" if the input is blank
function parseOutput($data){
     return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );
}


function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
var topPos = 0;
var leftPos = 0;


var tid, tid2, tmode, ttargetElementId;

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;


function validateForm(){
    var form=document.addForm;
    var elements=form.elements;
    var len=elements.length;
    var selfString='';
    for(var i=0;i<len;i++){
        //if(elements[i].type.toUpperCase()=='TEXT' && elements[i].disabled==false){
        if(elements[i].disabled==false){
            var arr=elements[i].id.split('_');
            var tc=arr[arr.length-1];
            if(trim(elements[i].value)==''){
                messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                elements[i].focus();
                return false;
            }
            if(elements[i].name=='selfEvaluation'){
                var maxVal=arr[3];
                if(!isInteger(trim(elements[i].value))){
                   messageBox("Enter numeric value");
                   showTab('dhtmlgoodies_tabView1',tc);
                   elements[i].focus();
                   return false;
                }
                if(parseFloat(trim(elements[i].value))>parseFloat(maxVal)){
                   messageBox("Maximum value for this field is "+maxVal);
                   showTab('dhtmlgoodies_tabView1',tc);
                   elements[i].focus();
                   return false;
                }
               if(selfString!=''){
                   selfString +=',';
               }
               selfString +=arr[2]+'_'+trim(elements[i].value);
            }
           if(elements[i].name!='overallAppraisalText'){
              if(!isInteger(trim(elements[i].value))){
                 messageBox("Enter numeric values");
                 showTab('dhtmlgoodies_tabView1',tc);
                 elements[i].focus();
                 return false;
              }
           }
        }
    }

   var cl=trim(form.casualLeave.value);
   var el=trim(form.earnedLeave.value);
   var pl=trim(form.maternityLeave.value);
   var sl=trim(form.studyLeave.value);
   var lwp=trim(form.lwpLeave.value);
   var lwpC=trim(form.lwpLeaveCount.value);
   var overAll=trim(form.overallAppraisalText.value);

   var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doEmployeeAppraisal.php';

   new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             selfString : selfString,
             cl         : cl,
             el         : el,
             pl         : pl,
             sl         : sl,
             lwp        : lwp,
             lwpC       : lwpC,
             overAll    : overAll
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo SUCCESS;?>" != trim(transport.responseText)) {
                 messageBox(trim(transport.responseText));
             }
             else{
                 messageBox("<?php echo EMPLOYEE_APPRAISAL_GIVEN; ?>");
             }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });

}


function validateForm2(){
    var form=document.addForm;
    var elements=form.elements;
    var len=elements.length;
    var hodString='';
    var reviwerString='';

    for(var i=0;i<len;i++){
        if(elements[i].disabled==false){
            var arr=elements[i].id.split('_');
            var tc=arr[arr.length-1];
            var maxVal=arr[3];
            if(trim(elements[i].value)==''){
                messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                elements[i].focus();
                return false;
            }
            if(!isInteger(trim(elements[i].value))){
                   messageBox("Enter numeric value");
                   showTab('dhtmlgoodies_tabView1',tc);
                   elements[i].focus();
                   return false;
                }
            if(parseFloat(trim(elements[i].value))>parseFloat(maxVal)){
               messageBox("Maximum value for this field is "+maxVal);
               showTab('dhtmlgoodies_tabView1',tc);
               elements[i].focus();
               return false;
            }
            if(elements[i].name=='hodEvaluation'){
               if(hodString!=''){
                   hodString +=',';
               }
               hodString +=arr[2]+'_'+trim(elements[i].value);
            }
           else{
               if(elements[i].name!='hodEvaluation' && elements[i].name!='scoregained' && elements[i].name!='dutiesweekend' && elements[i].name!='extsupreintendent' && elements[i].name!='copychecked' && elements[i].name!='dutiesexternal' && elements[i].name!='dutiesinternal'){
				 if(reviwerString!=''){
				  reviwerString +=',';
				 }
			     reviwerString += trim(elements[i].value);
			   }
           }
        }
    }
/*
	if(trim(document.getElementById('scoregained').value)=='') {
				messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                document.getElementById('scoregained').value.focus();
                return false;
	}
	if(trim(document.getElementById('dutiesweekend').value)=='') {
		messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                document.getElementById('dutiesweekend').value.focus();
                return false;
	}
	if(trim(document.getElementById('extsupreintendent').value)=='') {
		messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                document.getElementById('dutiesweekend').value.focus();
                return false;
	}
	if(trim(document.getElementById('copychecked').value)=='') {
		messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                document.getElementById('copychecked').value.focus();
                return false;
	}
	if(trim(document.getElementById('dutiesexternal').value)=='') {
		messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                document.getElementById('dutiesexternal').value.focus();
                return false;
	}
	if(trim(document.getElementById('dutiesinternal').value)=='') {
				messageBox("Please enter value");
                showTab('dhtmlgoodies_tabView1',tc);
                document.getElementById('dutiesinternal').value.focus();
                return false;
	}
*/

   var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doEmployeeAppraisalHOD.php';

   new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             hodString : hodString,
             reviwerString : reviwerString,
			 scoregained : document.getElementById('scoregained').value,
			 dutiesweekend : document.getElementById('dutiesweekend').value,
 		     extsupreintendent : document.getElementById('extsupreintendent').value,
			 copychecked : document.getElementById('copychecked').value,
			 dutiesexternal : document.getElementById('dutiesexternal').value,
 		     dutiesinternal : document.getElementById('dutiesinternal').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo SUCCESS;?>" != trim(transport.responseText)) {
                 messageBox(trim(transport.responseText));
             }
             else{
                 messageBox("<?php echo EMPLOYEE_APPRAISAL_GIVEN; ?>");
             }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });

}

function checkNaN(val){
    if(isNaN(val)){
      return 0;
    }
    else{
      return val;
    }
}
function updateGrandTotal(){
  var grandTotal= checkNaN(parseInt(trim(document.addForm.initiative.value),10))+checkNaN(parseInt(trim(document.addForm.responsibility.value),10));
  grandTotal +=checkNaN(parseInt(trim(document.addForm.punctuality.value),10))+checkNaN(parseInt(trim(document.addForm.committment.value),10));
  grandTotal +=checkNaN(parseInt(trim(document.addForm.loyality.value),10))+checkNaN(parseInt(trim(document.addForm.develop.value),10));
  grandTotal +=checkNaN(parseInt(trim(document.addForm.oral.value),10))+checkNaN(parseInt(trim(document.addForm.written_com.value),10));
  grandTotal +=checkNaN(parseInt(trim(document.addForm.teamwork.value),10))+checkNaN(parseInt(trim(document.addForm.leadership.value),10));
  grandTotal +=checkNaN(parseInt(trim(document.addForm.relation.value),10))+checkNaN(parseInt(trim(document.addForm.maturity.value),10));
  grandTotal +=checkNaN(parseInt(trim(document.addForm.temper.value),10))+checkNaN(parseInt(trim(document.addForm.rel_stud.value),10));
  document.getElementById('grandsum').value=parseInt(grandTotal,10);
}


var targetElement='';
function getForm(id,id2,mode,targetElementId) {
	
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/getAppraisalProofFormData.php';
         document.getElementById('proofForm').target = '';
         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous:false,
             parameters: {
                 proofId     : id,
                 appraisalId : id2
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    document.getElementById('proofContentDiv').innerHTML='';
                    document.getElementById('proofContentDiv').innerHTML=trim(transport.responseText);
                    document.getElementById('proofId').value=id;
                    document.getElementById('appraisalId').value=id2;
                    if(mode==1){
                       document.getElementById('divHeaderId').innerHTML='&nbsp;Proof'
                    }
                    else{
                       document.getElementById('divHeaderId').innerHTML='&nbsp;View Performance'
                    }
                    if(targetElementId!=-1){
                     targetElement=targetElementId;
                    }
                    else{
                      targetElement='';
                    }
                    displayWindow('ProofDiv',500,250);
                    changeColor(currentThemeId);
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
		   changeHelpFacility(document.getElementById('helpChk'));
}

function updateSelfEvaluation(newValue){
    if(targetElement!=''){
       var targetArray=targetElement.split('_');
       var totalId='totalSelfPoints_'+targetArray[targetArray.length-1];
       var oldValue=( parseInt(document.getElementById(totalId).innerHTML,10)-parseInt(document.getElementById(targetElement).value,10));
       document.getElementById(targetElement).value=parseInt(newValue,10);
       document.getElementById(totalId).innerHTML=parseInt(oldValue,10)+parseInt(document.getElementById(targetElement).value,10);
    }
}

function updateHODEvaluation(newValue){
    if(targetElement!=''){
       var hodTargetElement=targetElement.replace('selfEvaluation','hodEvaluation');
       var targetArray=hodTargetElement.split('_');
       var totalId='totalHODPoints_'+targetArray[targetArray.length-1];
       var oldValue=( parseInt(document.getElementById(totalId).innerHTML,10)-parseInt(document.getElementById(hodTargetElement).value,10));
       document.getElementById(hodTargetElement).value=parseInt(newValue,10);
       document.getElementById(totalId).innerHTML=parseInt(oldValue,10)+parseInt(document.getElementById(hodTargetElement).value,10);
    }
}
//--------------------------------------
//function for help bar
//Author : Gagan Gill
//Date : 1 november 2010
//--------------------------------------------------------
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;
    document.getElementById('helpInfo').innerHTML= msg;
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);

    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}
//---------------------------------------------------------
function validateProofForm2(){
       var cert_process=trim(document.getElementById('cert_process').value);
       var devoted=trim(document.getElementById('devoted').value);
       var supervision=trim(document.getElementById('supervision').value);

       var certification=trim(document.getElementById('certification').value);
       var assistance=trim(document.getElementById('assistance').value);
       var superValue=trim(document.getElementById('superValue').value);

       if(cert_process==''){
           messageBox('This field can not be empty');
           document.getElementById('cert_process').focus();
           return false;
       }
       if(devoted==''){
           messageBox('This field can not be empty');
           document.getElementById('devoted').focus();
           return false;
       }
       if(supervision==''){
           messageBox('This field can not be empty');
           document.getElementById('supervision').focus();
           return false;
       }

       if(certification==''){
           messageBox('Enter marks');
           document.getElementById('certification').focus();
           return false;
       }
       if(!isNumeric(certification)){
           messageBox('Enter numeric value');
           document.getElementById('certification').focus();
           return false;
       }
       if(parseInt(certification,10) > parseInt(document.getElementById('certification').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('certification').alt);
           document.getElementById('certification').focus();
           return false;
       }
       if(assistance==''){
           messageBox('Enter marks');
           document.getElementById('assistance').focus();
           return false;
       }
       if(!isNumeric(assistance)){
           messageBox('Enter numeric value');
           document.getElementById('assistance').focus();
           return false;
       }
       if(parseInt(assistance,10) > parseInt(document.getElementById('assistance').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('assistance').alt);
           document.getElementById('assistance').focus();
           return false;
       }
       if(superValue==''){
           messageBox('Enter marks');
           document.getElementById('superValue').focus();
           return false;
       }
       if(!isNumeric(superValue)){
           messageBox('Enter numeric value');
           document.getElementById('superValue').focus();
           return false;
       }
       if(parseInt(superValue,10) > parseInt(document.getElementById('superValue').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('superValue').alt);
           document.getElementById('superValue').focus();
           return false;
       }

       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 cert_process: cert_process,
                 devoted: devoted,
                 supervision: supervision,
                 certification : certification,
                 assistance :assistance,
                 superValue : superValue,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                            hiddenFloatingDiv('ProofDiv');
                            //updates total
                            updateSelfEvaluation(parseInt(certification,10)+parseInt(assistance,10)+parseInt(superValue,10));
                            //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
    }


function validateProofForm3(){
       var central=trim(document.getElementById('central').value);
       var facilities=trim(document.getElementById('facilities').value);
       if(central==''){
           messageBox('This field can not be empty');
           document.getElementById('central').focus();
           return false;
       }

       if(facilities==''){
           messageBox('Enter marks');
           document.getElementById('facilities').focus();
           return false;
       }
       if(!isNumeric(facilities)){
           messageBox('Enter numeric value');
           document.getElementById('facilities').focus();
           return false;
       }
       if(parseInt(facilities,10) > parseInt(document.getElementById('facilities').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('facilities').alt);
           document.getElementById('facilities').focus();
           return false;
       }

       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 central: central,
                 proofId : document.getElementById('proofId').value,
                 facilities : facilities,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                             hiddenFloatingDiv('ProofDiv');
                             //updates total
                             updateSelfEvaluation(parseInt(facilities,10));
                             //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
    }

var serverDate="<?php echo date('Y-m-d'); ?>"
function validateProofForm4(){
     var k=0;
     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);
     var act3_marks=trim(document.getElementById('act3_marks').value);

     if(act1_marks==''){
           messageBox('Enter marks');
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(!isNumeric(act1_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(parseInt(act1_marks,10) > parseInt(document.getElementById('act1_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act1_marks').alt);
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(parseInt(act1_marks,10)>0){
         var fl=0;
         for(var i=1;i<3;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act1').focus();
             return false;
         }
     }

      if(act2_marks==''){
           messageBox('Enter marks');
           document.getElementById('act2_marks').focus();
           return false;
      }
      if(!isNumeric(act2_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act2_marks').focus();
           return false;
      }
      if(parseInt(act2_marks,10) > parseInt(document.getElementById('act2_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act2_marks').alt);
           document.getElementById('act2_marks').focus();
           return false;
      }

      if(parseInt(act2_marks,10)>0){
         var fl=0;
         for(var i=3;i<7;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act3').focus();
             return false;
         }
      }

      if(act3_marks==''){
           messageBox('Enter marks');
           document.getElementById('act3_marks').focus();
           return false;
      }
      if(!isNumeric(act3_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act3_marks').focus();
           return false;
      }
      if(parseInt(act3_marks,10) > parseInt(document.getElementById('act3_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act3_marks').alt);
           document.getElementById('act3_marks').focus();
           return false;
      }

      if(parseInt(act3_marks,10)>0){
         var fl=0;
         for(var i=7;i<11;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act7').focus();
             return false;
         }
      }

     for(var i=1;i<11;i++){
         if(trim(document.getElementById('act'+i).value)!=''){
           if(trim(document.getElementById('testinput'+(i+k)).value)==''){
               messageBox("Select held from date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k+1)).value)==''){
               messageBox("Select held to date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       if(trim(document.getElementById('testinput'+(i+k)).value)!=''){
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(trim(document.getElementById('act'+(i)).value)==''){
               messageBox("Please enter value");
               document.getElementById('act'+(i)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k+1)).value)==''){
               messageBox("Select held to date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       if(trim(document.getElementById('testinput'+(i+k+1)).value)!=''){
           if(!dateDifference(document.getElementById('testinput'+(i+k+1)).value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(trim(document.getElementById('act'+(i)).value)==''){
               messageBox("Please enter value");
               document.getElementById('act'+(i)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k)).value)==''){
               messageBox("Select held from date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       k++;
     }


       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 act1 : trim(document.getElementById('act1').value),
                 testinput1 : trim(document.getElementById('testinput1').value),
                 testinput2 : trim(document.getElementById('testinput2').value),
                 act2 : trim(document.getElementById('act2').value),
                 testinput3 : trim(document.getElementById('testinput3').value),
                 testinput4 : trim(document.getElementById('testinput4').value),
                 act3 : trim(document.getElementById('act3').value),
                 testinput5 : trim(document.getElementById('testinput5').value),
                 testinput6 : trim(document.getElementById('testinput6').value),
                 act4 : trim(document.getElementById('act4').value),
                 testinput7 : trim(document.getElementById('testinput7').value),
                 testinput8 : trim(document.getElementById('testinput8').value),
                 act5 : trim(document.getElementById('act5').value),
                 testinput9 : trim(document.getElementById('testinput9').value),
                 testinput10 : trim(document.getElementById('testinput10').value),
                 act6 : trim(document.getElementById('act6').value),
                 testinput11 : trim(document.getElementById('testinput11').value),
                 testinput12 : trim(document.getElementById('testinput12').value),
                 act7 : trim(document.getElementById('act7').value),
                 testinput13 : trim(document.getElementById('testinput13').value),
                 testinput14 : trim(document.getElementById('testinput14').value),
                 act8 : trim(document.getElementById('act8').value),
                 testinput15 : trim(document.getElementById('testinput15').value),
                 testinput16 : trim(document.getElementById('testinput16').value),
                 act9 : trim(document.getElementById('act9').value),
                 testinput17 : trim(document.getElementById('testinput17').value),
                 testinput18 : trim(document.getElementById('testinput18').value),
                 act10 : trim(document.getElementById('act10').value),
                 testinput19 : trim(document.getElementById('testinput19').value),
                 testinput20 : trim(document.getElementById('testinput20').value),
                 proofId : document.getElementById('proofId').value,
                 act1_marks : act1_marks,
                 act2_marks : act2_marks,
                 act3_marks : act3_marks,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                             hiddenFloatingDiv('ProofDiv');
                             //updates total
                             updateSelfEvaluation(parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10));
                             //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
    }

function validateProofForm5(){
     var k=0;
     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);

     if(act1_marks==''){
           messageBox('Enter marks');
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(!isNumeric(act1_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(parseInt(act1_marks,10) > parseInt(document.getElementById('act1_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act1_marks').alt);
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(parseInt(act1_marks,10)>0){
         var fl=0;
         //for(var i=1;i<5;i++){
         for(var i=1;i<4;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act1').focus();
             return false;
         }
     }

      if(act2_marks==''){
           messageBox('Enter marks');
           document.getElementById('act2_marks').focus();
           return false;
      }
      if(!isNumeric(act2_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act2_marks').focus();
           return false;
      }
      if(parseInt(act2_marks,10) > parseInt(document.getElementById('act2_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act2_marks').alt);
           document.getElementById('act2_marks').focus();
           return false;
      }

      if(parseInt(act2_marks,10)>0){
         var fl=0;
         for(var i=4;i<8;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act5').focus();
             return false;
         }
      }
     for(var i=1;i<8;i++){
         if(trim(document.getElementById('act'+i).value)!=''){
           if(trim(document.getElementById('testinput'+(i+k)).value)==''){
               messageBox("Select held from date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k+1)).value)==''){
               messageBox("Select held to date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       if(trim(document.getElementById('testinput'+(i+k)).value)!=''){
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(trim(document.getElementById('act'+(i)).value)==''){
               messageBox("Please enter value");
               document.getElementById('act'+(i)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k+1)).value)==''){
               messageBox("Select held to date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       if(trim(document.getElementById('testinput'+(i+k+1)).value)!=''){
           if(!dateDifference(document.getElementById('testinput'+(i+k+1)).value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(trim(document.getElementById('act'+(i)).value)==''){
               messageBox("Please enter value");
               document.getElementById('act'+(i)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k)).value)==''){
               messageBox("Select held from date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       k++;
     }

       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 act1 : trim(document.getElementById('act1').value),
                 testinput1 : trim(document.getElementById('testinput1').value),
                 testinput2 : trim(document.getElementById('testinput2').value),
                 act2 : trim(document.getElementById('act2').value),
                 testinput3 : trim(document.getElementById('testinput3').value),
                 testinput4 : trim(document.getElementById('testinput4').value),
                 act3 : trim(document.getElementById('act3').value),
                 testinput5 : trim(document.getElementById('testinput5').value),
                 testinput6 : trim(document.getElementById('testinput6').value),
                 act4 : trim(document.getElementById('act4').value),
                 testinput7 : trim(document.getElementById('testinput7').value),
                 testinput8 : trim(document.getElementById('testinput8').value),
                 act5 : trim(document.getElementById('act5').value),
                 testinput9 : trim(document.getElementById('testinput9').value),
                 testinput10 : trim(document.getElementById('testinput10').value),
                 act6 : trim(document.getElementById('act6').value),
                 testinput11 : trim(document.getElementById('testinput11').value),
                 testinput12 : trim(document.getElementById('testinput12').value),
                 act7 : trim(document.getElementById('act7').value),
                 testinput13 : trim(document.getElementById('testinput13').value),
                 testinput14 : trim(document.getElementById('testinput14').value),
                 //act8 : trim(document.getElementById('act8').value),
                 //testinput15 : trim(document.getElementById('testinput15').value),
                 //testinput16 : trim(document.getElementById('testinput16').value),
                 act1_marks  : act1_marks,
                 act2_marks  : act2_marks,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                             hiddenFloatingDiv('ProofDiv');
                             //updates total
                             updateSelfEvaluation(parseInt(act1_marks,10)+parseInt(act2_marks,10));
                             //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function validateProofForm6(){
     var k=0;

     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);
     var act3_marks=trim(document.getElementById('act3_marks').value);

     if(act1_marks==''){
           messageBox('Enter marks');
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(!isNumeric(act1_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(parseInt(act1_marks,10) > parseInt(document.getElementById('act1_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act1_marks').alt);
           document.getElementById('act1_marks').focus();
           return false;
     }
     if(parseInt(act1_marks,10)>0){
         var fl=0;
         for(var i=1;i<5;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act1').focus();
             return false;
         }
     }

      if(act2_marks==''){
           messageBox('Enter marks');
           document.getElementById('act2_marks').focus();
           return false;
      }
      if(!isNumeric(act2_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act2_marks').focus();
           return false;
      }
      if(parseInt(act2_marks,10) > parseInt(document.getElementById('act2_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act2_marks').alt);
           document.getElementById('act2_marks').focus();
           return false;
      }

      if(parseInt(act2_marks,10)>0){
         var fl=0;
         for(var i=5;i<9;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act5').focus();
             return false;
         }
      }

      if(act3_marks==''){
           messageBox('Enter marks');
           document.getElementById('act3_marks').focus();
           return false;
      }
      if(!isNumeric(act3_marks)){
           messageBox('Enter numeric value');
           document.getElementById('act3_marks').focus();
           return false;
      }
      if(parseInt(act3_marks,10) > parseInt(document.getElementById('act3_marks').alt,10)){
           messageBox('Marks can not be greater than '+document.getElementById('act3_marks').alt);
           document.getElementById('act3_marks').focus();
           return false;
      }

      if(parseInt(act3_marks,10)>0){
         var fl=0;
         for(var i=9;i<15;i++){
           if(trim(document.getElementById('act'+(i)).value)!=''){
               fl=1;
           }
         }
         if(fl==0){
             messageBox("Please enter value");
             document.getElementById('act9').focus();
             return false;
         }
      }

     for(var i=1;i<15;i++){
         if(trim(document.getElementById('act'+i).value)!=''){
           if(trim(document.getElementById('testinput'+(i+k)).value)==''){
               messageBox("Select held from date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k+1)).value)==''){
               messageBox("Select held to date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       if(trim(document.getElementById('testinput'+(i+k)).value)!=''){
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(trim(document.getElementById('act'+(i)).value)==''){
               messageBox("Please enter value");
               document.getElementById('act'+(i)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k+1)).value)==''){
               messageBox("Select held to date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       if(trim(document.getElementById('testinput'+(i+k+1)).value)!=''){
           if(!dateDifference(document.getElementById('testinput'+(i+k+1)).value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
           if(trim(document.getElementById('act'+(i)).value)==''){
               messageBox("Please enter value");
               document.getElementById('act'+(i)).focus();
               return false;
           }
           if(trim(document.getElementById('testinput'+(i+k)).value)==''){
               messageBox("Select held from date");
               document.getElementById('testinput'+(i+k)).focus();
               return false;
           }
           if(!dateDifference(document.getElementById('testinput'+(i+k)).value,document.getElementById('testinput'+(i+k+1)).value,'-')){
               messageBox("Held to date can not be less than held from date");
               document.getElementById('testinput'+(i+k+1)).focus();
               return false;
           }
       }
       k++;
     }

       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 act1 : trim(document.getElementById('act1').value),
                 testinput1 : trim(document.getElementById('testinput1').value),
                 testinput2 : trim(document.getElementById('testinput2').value),
                 act2 : trim(document.getElementById('act2').value),
                 testinput3 : trim(document.getElementById('testinput3').value),
                 testinput4 : trim(document.getElementById('testinput4').value),
                 act3 : trim(document.getElementById('act3').value),
                 testinput5 : trim(document.getElementById('testinput5').value),
                 testinput6 : trim(document.getElementById('testinput6').value),
                 act4 : trim(document.getElementById('act4').value),
                 testinput7 : trim(document.getElementById('testinput7').value),
                 testinput8 : trim(document.getElementById('testinput8').value),
                 act5 : trim(document.getElementById('act5').value),
                 testinput9 : trim(document.getElementById('testinput9').value),
                 testinput10 : trim(document.getElementById('testinput10').value),
                 act6 : trim(document.getElementById('act6').value),
                 testinput11 : trim(document.getElementById('testinput11').value),
                 testinput12 : trim(document.getElementById('testinput12').value),
                 act7 : trim(document.getElementById('act7').value),
                 testinput13 : trim(document.getElementById('testinput13').value),
                 testinput14 : trim(document.getElementById('testinput14').value),
                 act8 : trim(document.getElementById('act8').value),
                 testinput15 : trim(document.getElementById('testinput15').value),
                 testinput16 : trim(document.getElementById('testinput16').value),
                 act9 : trim(document.getElementById('act9').value),
                 testinput17 : trim(document.getElementById('testinput17').value),
                 testinput18 : trim(document.getElementById('testinput18').value),
                 act10 : trim(document.getElementById('act10').value),
                 testinput19 : trim(document.getElementById('testinput19').value),
                 testinput20 : trim(document.getElementById('testinput20').value),
                 act11 : trim(document.getElementById('act11').value),
                 testinput21 : trim(document.getElementById('testinput21').value),
                 testinput22 : trim(document.getElementById('testinput22').value),
                 act12 : trim(document.getElementById('act12').value),
                 testinput23 : trim(document.getElementById('testinput23').value),
                 testinput24 : trim(document.getElementById('testinput24').value),
                 act13 : trim(document.getElementById('act13').value),
                 testinput25 : trim(document.getElementById('testinput25').value),
                 testinput26 : trim(document.getElementById('testinput26').value),
                 act14 : trim(document.getElementById('act14').value),
                 testinput27 : trim(document.getElementById('testinput27').value),
                 testinput28 : trim(document.getElementById('testinput28').value),
                 act1_marks  : act1_marks,
                 act2_marks  : act2_marks,
                 act3_marks  : act3_marks,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                             hiddenFloatingDiv('ProofDiv');
                             //updates total
                             updateSelfEvaluation(parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10));
                             //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function changeValue0(val){
    var value=trim(val);
    if(value==''){
        document.getElementById('act1_marks').value='0';
        document.getElementById('labname').value='';
        return false;
    }
    if(!isNumeric(value)){
        messageBox("Enter numeric value");
        document.getElementById('newlab').focus();
        document.getElementById('act1_marks').value='0';
        document.getElementById('labname').value='';
        return false;
    }
    if(value>3){
        messageBox("Enter value between 1 and 3");
        document.getElementById('newlab').focus();
        document.getElementById('act1_marks').value='0';
        document.getElementById('labname').value='';
        return false;
    }
    document.getElementById('act1_marks').value=20*parseInt(value,10);
}

function changeValue1(val){
    if(val=='' || val==0){
        document.getElementById('act2_marks').value='0';
        document.getElementById('major_equip2').value='';
        return false;
    }
    document.getElementById('act2_marks').value=10*parseInt(val,10);
}

function changeValue2(val){
    if(val=='' || val==0){
        document.getElementById('act4_marks').value='0';
        document.getElementById('testing_meas2').value='';
        return false;
    }
    document.getElementById('act4_marks').value=10*parseInt(val,10);
}

function validateProofForm7(){
       var newlab=trim(document.getElementById('newlab').value);
       var labname=trim(document.getElementById('labname').value);
       var act1_marks=trim(document.getElementById('act1_marks').value);
       if(newlab!=''){
           if(labname==''){
               messageBox("Enter lab names");
               document.getElementById('labname').focus();
               return false;
           }
       }
       if(labname!=''){
           if(newlab==''){
               messageBox("Enter no. of labs");
               document.getElementById('newlab').focus();
               return false;
           }
       }
       var major_equip=document.getElementById('major_equip').value;
       var major_equip2=trim(document.getElementById('major_equip2').value);
       var act2_marks=trim(document.getElementById('act2_marks').value);

       if(major_equip!=0){
           if(major_equip2==''){
               messageBox("Enter details");
               document.getElementById('major_equip2').focus();
               return false;
           }
       }

       if(major_equip2!=''){
           if(major_equip=='0'){
               messageBox("Select amount");
               document.getElementById('major_equip').focus();
               return false;
           }
       }

       var maint_equip=trim(document.getElementById('maint_equip').value);
       var act3_marks=trim(document.getElementById('act3_marks').value);
       if(maint_equip!=''){
           if(act3_marks==''){
               messageBox("Enter marks");
               document.getElementById('act3_marks').focus();
               return false;
           }
       }

       if(act3_marks!='' && act3_marks!=0){
          if(maint_equip==''){
             messageBox("This field can not be left blank");
             document.getElementById('maint_equip').focus();
             return false;
           }
          if(!isNumeric(act3_marks)){
             messageBox("Enter numeric value");
             document.getElementById('act3_marks').focus();
             return false;
          }
          if(parseInt(act3_marks,10) > parseInt(document.getElementById('act3_marks').alt,10)){
             messageBox("Marks can not be greater than "+document.getElementById('act3_marks').alt);
             document.getElementById('act3_marks').focus();
             return false;
          }
       }

       var testing_meas=trim(document.getElementById('testing_meas').value);
       var testing_meas2=trim(document.getElementById('testing_meas2').value);
       var act4_marks=trim(document.getElementById('act4_marks').value);
       if(testing_meas!='0'){
           if(testing_meas2==''){
               messageBox("Enter details");
               document.getElementById('testing_meas2').focus();
               return false;
           }
       }
       if(testing_meas2!=''){
           if(testing_meas=='0'){
               messageBox("Select amount");
               document.getElementById('testing_meas').focus();
               return false;
           }
       }

       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 newlab  : newlab,
                 labname : labname,
                 act1_marks : act1_marks,
                 major_equip : major_equip,
                 major_equip2 : major_equip2,
                 act2_marks   : act2_marks,
                 maint_equip  : maint_equip,
                 act3_marks   : act3_marks,
                 testing_meas : testing_meas,
                 testing_meas2 : testing_meas2,
                 act4_marks   : act4_marks,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                            hiddenFloatingDiv('ProofDiv');
                            //updates total
                            updateSelfEvaluation(parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10)+parseInt(act4_marks,10));
                             //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function validateProofForm8(){
       var qty_lab=trim(document.getElementById('qty_lab').value);
       var new_manual=trim(document.getElementById('new_manual').value);
       var act1_marks=trim(document.getElementById('act1_marks').value);
       if(qty_lab!=''){
           if(!isNumeric(qty_lab)){
               messageBox("Enter numeric value");
               document.getElementById('qty_lab').focus();
               return false;
           }
           if(new_manual==''){
               messageBox("This field can not be left blank");
               document.getElementById('new_manual').focus();
               return false;
           }
           if(act1_marks==''){
               messageBox("Enter marks");
               document.getElementById('act1_marks').focus();
               return false;
           }
           if(!isNumeric(act1_marks)){
               messageBox("Enter numeric value");
               document.getElementById('act1_marks').focus();
               return false;
           }

           if(parseInt(act1_marks,10) > parseInt(document.getElementById('act1_marks').alt,10)){
               messageBox("Marks can not greater than "+document.getElementById('act1_marks').alt);
               document.getElementById('act1_marks').focus();
               return false;
           }
       }

       if(new_manual!=''){
           if(!isNumeric(qty_lab)){
               messageBox("Enter numeric value");
               document.getElementById('qty_lab').focus();
               return false;
           }
           /*if(qty_lab==0){
               messageBox("Enter value greater than zero");
               document.getElementById('qty_lab').focus();
               return false;
           }*/
           if(act1_marks==''){
               messageBox("Enter marks");
               document.getElementById('act1_marks').focus();
               return false;
           }
           if(!isNumeric(act1_marks)){
               messageBox("Enter numeric value");
               document.getElementById('act1_marks').focus();
               return false;
           }

           if(parseInt(act1_marks,10) > parseInt(document.getElementById('act1_marks').alt,10)){
               messageBox("Marks can not greater than "+document.getElementById('act1_marks').alt);
               document.getElementById('act1_marks').focus();
               return false;
           }
       }

       if(act1_marks!='' && act1_marks!=0){
           if(!isNumeric(qty_lab)){
               messageBox("Enter numeric value");
               document.getElementById('qty_lab').focus();
               return false;
           }
          /* if(qty_lab==0){
               messageBox("Enter values greater than zero");
               document.getElementById('qty_lab').focus();
               return false;
           }*/
           if(new_manual==''){
               messageBox("This field can not be left blank");
               document.getElementById('new_manual').focus();
               return false;
           }
           if(!isNumeric(act1_marks)){
               messageBox("Enter numeric value");
               document.getElementById('act1_marks').focus();
               return false;
           }

           if(parseInt(act1_marks,10) > parseInt(document.getElementById('act1_marks').alt,10)){
               messageBox("Marks can not greater than "+document.getElementById('act1_marks').alt);
               document.getElementById('act1_marks').focus();
               return false;
           }
       }

       var existing_manual=trim(document.getElementById('existing_manual').value);
       var act2_marks=trim(document.getElementById('act2_marks').value);

       if(existing_manual!=''){
          if(act2_marks==''){
               messageBox("Enter marks");
               document.getElementById('act2_marks').focus();
               return false;
           }
           if(!isNumeric(act2_marks)){
               messageBox("Enter numeric value");
               document.getElementById('act2_marks').focus();
               return false;
           }

           if(parseInt(act2_marks,10) > parseInt(document.getElementById('act2_marks').alt,10)){
               messageBox("Marks can not greater than "+document.getElementById('act2_marks').alt);
               document.getElementById('act2_marks').focus();
               return false;
           }
       }

       if(act2_marks!='' && act2_marks!=0){
           if(existing_manual==''){
               messageBox("This field can not be left blank");
               document.getElementById('existing_manual').focus();
               return false;
           }

           if(!isNumeric(act2_marks)){
               messageBox("Enter numeric value");
               document.getElementById('act2_marks').focus();
               return false;
           }

           if(parseInt(act2_marks,10) > parseInt(document.getElementById('act2_marks').alt,10)){
               messageBox("Marks can not greater than "+document.getElementById('act2_marks').alt);
               document.getElementById('act2_marks').focus();
               return false;
           }
       }



       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 qty_lab : qty_lab,
                 new_manual: new_manual,
                 act1_marks : act1_marks,
                 existing_manual: existing_manual,
                 act2_marks : act2_marks,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                             hiddenFloatingDiv('ProofDiv');
                            //updates total
                            updateSelfEvaluation(parseInt(act1_marks,10)+parseInt(act2_marks,10));
                            //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function validateProofForm9(){

     var act1_marks=document.getElementById('act1_marks');
     var strength_indus=document.getElementById('strength_indus');
     var location_indus=document.getElementById('location_indus');
     var indus_datefrom=document.getElementById('indus_datefrom');
     var indus_dateto=document.getElementById('indus_dateto');

     var act2_marks=document.getElementById('act2_marks');
     var strength_indus2=document.getElementById('strength_indus2');
     var location_indus2=document.getElementById('location_indus2');
     var indus_datefrom2=document.getElementById('indus_datefrom2');
     var indus_dateto2=document.getElementById('indus_dateto2');

     var reportSubmitted=(document.proofForm.reportSubmitted[0].checked==true?1:0);
     var act3_marks=(reportSubmitted==1?5:0);

     var act4_marks=document.getElementById('act4_marks');
     var strength_trips=document.getElementById('strength_trips');
     var location_trips=document.getElementById('location_trips');
     var trips_datefrom=document.getElementById('trips_datefrom');
     var trips_dateto=document.getElementById('trips_dateto');

     var act5_marks=document.getElementById('act5_marks');
     var strength_trips2=document.getElementById('strength_trips2');
     var location_trips2=document.getElementById('location_trips2');
     var trips_datefrom2=document.getElementById('trips_datefrom2');
     var trips_dateto2=document.getElementById('trips_dateto2');

     if(trim(act1_marks.value)!='' && trim(act1_marks.value)!='0'){
         if(!isNumeric(trim(act1_marks.value))){
             messageBox("Please enter numeric values");
             act1_marks.focus();
             return false;
         }
         if(trim(strength_indus.value)==''){
           messageBox("Please enter value");
           strength_indus.focus();
           return false;
         }
         if(trim(location_indus.value)==''){
           messageBox("Please enter value");
           location_indus.focus();
           return false;
         }
         if(trim(indus_datefrom.value)==''){
           messageBox("Please select held from date");
           indus_datefrom.focus();
           return false;
         }
         if(trim(indus_dateto.value)==''){
           messageBox("Please select held to date");
           indus_dateto.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom.focus();
               return false;
         }

         if(!dateDifference(indus_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom.value,indus_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto.focus();
               return false;
         }
     }

     if(trim(strength_indus.value)!=''){

         if(!isNumeric(trim(strength_indus.value))){
             messageBox("Please enter numeric values");
             strength_indus.focus();
             return false;
         }

         if(trim(act1_marks.value)==''){
           messageBox("Please enter value");
           act1_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act1_marks.value))){
             messageBox("Please enter numeric values");
             act1_marks.focus();
             return false;
         }

         if(trim(location_indus.value)==''){
           messageBox("Please enter value");
           location_indus.focus();
           return false;
         }
         if(trim(indus_datefrom.value)==''){
           messageBox("Please select held from date");
           indus_datefrom.focus();
           return false;
         }
         if(trim(indus_dateto.value)==''){
           messageBox("Please select held to date");
           indus_dateto.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom.focus();
               return false;
         }

         if(!dateDifference(indus_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom.value,indus_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto.focus();
               return false;
         }
     }

     if(trim(location_indus.value)!=''){
         if(trim(act1_marks.value)==''){
           messageBox("Please enter value");
           act1_marks.focus();
           return false;
         }
         if(!isNumeric(trim(act1_marks.value))){
             messageBox("Please enter numeric values");
             act1_marks.focus();
             return false;
         }
         if(trim(strength_indus.value)==''){
           messageBox("Please enter value");
           strength_indus.focus();
           return false;
         }
         if(trim(indus_datefrom.value)==''){
           messageBox("Please select held from date");
           indus_datefrom.focus();
           return false;
         }
         if(trim(indus_dateto.value)==''){
           messageBox("Please select held to date");
           indus_dateto.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom.focus();
               return false;
         }

         if(!dateDifference(indus_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom.value,indus_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto.focus();
               return false;
         }
     }

     if(trim(indus_datefrom.value)!=''  || trim(indus_dateto.value)!=''){
         if(trim(act1_marks.value)==''){
           messageBox("Please enter value");
           act1_marks.focus();
           return false;
         }
         if(!isNumeric(trim(act1_marks.value))){
             messageBox("Please enter numeric values");
             act1_marks.focus();
             return false;
         }
         if(trim(strength_indus.value)==''){
           messageBox("Please enter value");
           strength_indus.focus();
           return false;
         }
         if(trim(location_indus.value)==''){
           messageBox("Please enter value");
           location_indus.focus();
           return false;
         }
         if(trim(indus_datefrom.value)==''){
           messageBox("Please select held from date");
           indus_datefrom.focus();
           return false;
         }
         if(trim(indus_dateto.value)==''){
           messageBox("Please select held to date");
           indus_dateto.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom.focus();
               return false;
         }

         if(!dateDifference(indus_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom.value,indus_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto.focus();
               return false;
         }
     }

     if(trim(act2_marks.value)!='' && trim(act2_marks.value)!='0'){
         if(!isNumeric(trim(act2_marks.value))){
             messageBox("Please enter numeric values");
             act2_marks.focus();
             return false;
         }
         if(trim(strength_indus2.value)==''){
           messageBox("Please enter value");
           strength_indus2.focus();
           return false;
         }
         if(trim(location_indus2.value)==''){
           messageBox("Please enter value");
           location_indus2.focus();
           return false;
         }
         if(trim(indus_datefrom2.value)==''){
           messageBox("Please select held from date");
           indus_datefrom2.focus();
           return false;
         }
         if(trim(indus_dateto2.value)==''){
           messageBox("Please select held to date");
           indus_dateto2.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom2.focus();
               return false;
         }

         if(!dateDifference(indus_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto2.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom2.value,indus_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto2.focus();
               return false;
         }
     }

     if(trim(strength_indus2.value)!=''){

         if(!isNumeric(trim(strength_indus2.value))){
             messageBox("Please enter numeric values");
             strength_indus2.focus();
             return false;
         }

         if(trim(act2_marks.value)==''){
           messageBox("Please enter value");
           act2_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act2_marks.value))){
             messageBox("Please enter numeric values");
             act2_marks.focus();
             return false;
         }

         if(trim(location_indus2.value)==''){
           messageBox("Please enter value");
           location_indus2.focus();
           return false;
         }
         if(trim(indus_datefrom2.value)==''){
           messageBox("Please select held from date");
           indus_datefrom2.focus();
           return false;
         }
         if(trim(indus_dateto2.value)==''){
           messageBox("Please select held to date");
           indus_dateto2.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom2.focus();
               return false;
         }

         if(!dateDifference(indus_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto2.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom2.value,indus_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto2.focus();
               return false;
         }
     }

     if(trim(location_indus2.value)!=''){
         if(trim(act2_marks.value)==''){
           messageBox("Please enter value");
           act2_marks.focus();
           return false;
         }
         if(!isNumeric(trim(act2_marks.value))){
             messageBox("Please enter numeric values");
             act2_marks.focus();
             return false;
         }
         if(trim(strength_indus2.value)==''){
           messageBox("Please enter value");
           strength_indus2.focus();
           return false;
         }
         if(trim(indus_datefrom2.value)==''){
           messageBox("Please select held from date");
           indus_datefrom2.focus();
           return false;
         }
         if(trim(indus_dateto2.value)==''){
           messageBox("Please select held to date");
           indus_dateto2.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom2.focus();
               return false;
         }

         if(!dateDifference(indus_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto2.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom2.value,indus_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto2.focus();
               return false;
         }
     }

     if(trim(indus_datefrom2.value)!=''  || trim(indus_dateto2.value)!=''){
         if(trim(act2_marks.value)==''){
           messageBox("Please enter value");
           act2_marks.focus();
           return false;
         }
         if(!isNumeric(trim(act2_marks.value))){
             messageBox("Please enter numeric values");
             act2_marks.focus();
             return false;
         }
         if(trim(strength_indus2.value)==''){
           messageBox("Please enter value");
           strength_indus2.focus();
           return false;
         }
         if(trim(location_indus2.value)==''){
           messageBox("Please enter value");
           location_indus2.focus();
           return false;
         }
         if(trim(indus_datefrom2.value)==''){
           messageBox("Please select held from date");
           indus_datefrom2.focus();
           return false;
         }
         if(trim(indus_dateto2.value)==''){
           messageBox("Please select held to date");
           indus_dateto2.focus();
           return false;
         }

         if(!dateDifference(indus_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               indus_datefrom2.focus();
               return false;
         }

         if(!dateDifference(indus_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               indus_dateto2.focus();
               return false;
         }

         if(!dateDifference(indus_datefrom2.value,indus_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               indus_dateto2.focus();
               return false;
         }
     }


     if(trim(act4_marks.value)!='' && trim(act4_marks.value)!='0'){
         if(!isNumeric(trim(act4_marks.value))){
             messageBox("Please enter numeric values");
             act4_marks.focus();
             return false;
         }
         if(trim(strength_trips.value)==''){
           messageBox("Please enter value");
           strength_trips.focus();
           return false;
         }
         if(trim(location_trips.value)==''){
           messageBox("Please enter value");
           location_trips.focus();
           return false;
         }
         if(trim(trips_datefrom.value)==''){
           messageBox("Please select held from date");
           trips_datefrom.focus();
           return false;
         }
         if(trim(trips_dateto.value)==''){
           messageBox("Please select held to date");
           trips_dateto.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom.focus();
               return false;
         }

         if(!dateDifference(trips_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom.value,trips_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto.focus();
               return false;
         }
     }

     if(trim(strength_trips.value)!=''){

         if(!isNumeric(trim(strength_trips.value))){
             messageBox("Please enter numeric values");
             strength_trips.focus();
             return false;
         }

         if(trim(act4_marks.value)==''){
           messageBox("Please enter value");
           act4_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act4_marks.value))){
             messageBox("Please enter numeric values");
             act4_marks.focus();
             return false;
         }

         if(trim(location_trips.value)==''){
           messageBox("Please enter value");
           location_trips.focus();
           return false;
         }
         if(trim(trips_datefrom.value)==''){
           messageBox("Please select held from date");
           trips_datefrom.focus();
           return false;
         }
         if(trim(trips_dateto.value)==''){
           messageBox("Please select held to date");
           trips_dateto.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom.focus();
               return false;
         }

         if(!dateDifference(trips_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom.value,trips_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto.focus();
               return false;
         }
     }

     if(trim(location_trips.value)!=''){
         if(trim(act4_marks.value)==''){
           messageBox("Please enter value");
           act4_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act4_marks.value))){
             messageBox("Please enter numeric values");
             act4_marks.focus();
             return false;
         }
         if(trim(strength_trips.value)==''){
           messageBox("Please enter value");
           strength_trips.focus();
           return false;
         }
         if(trim(trips_datefrom.value)==''){
           messageBox("Please select held from date");
           trips_datefrom.focus();
           return false;
         }
         if(trim(trips_dateto.value)==''){
           messageBox("Please select held to date");
           trips_dateto.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom.focus();
               return false;
         }

         if(!dateDifference(trips_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom.value,trips_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto.focus();
               return false;
         }
     }

     if(trim(trips_datefrom.value)!=''  || trim(trips_dateto.value)!=''){
         if(trim(act4_marks.value)==''){
           messageBox("Please enter value");
           act4_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act4_marks.value))){
             messageBox("Please enter numeric values");
             act4_marks.focus();
             return false;
         }
         if(trim(strength_trips.value)==''){
           messageBox("Please enter value");
           strength_trips.focus();
           return false;
         }
         if(trim(location_trips.value)==''){
           messageBox("Please enter value");
           location_trips.focus();
           return false;
         }
         if(trim(trips_datefrom.value)==''){
           messageBox("Please select held from date");
           trips_datefrom.focus();
           return false;
         }
         if(trim(trips_dateto.value)==''){
           messageBox("Please select held to date");
           trips_dateto.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom.focus();
               return false;
         }

         if(!dateDifference(trips_dateto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom.value,trips_dateto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto.focus();
               return false;
         }
     }

     if(trim(act5_marks.value)!='' && trim(act5_marks.value)!='0'){
         if(!isNumeric(trim(act5_marks.value))){
             messageBox("Please enter numeric values");
             act5_marks.focus();
             return false;
         }
         if(trim(strength_trips2.value)==''){
           messageBox("Please enter value");
           strength_trips2.focus();
           return false;
         }
         if(trim(location_trips2.value)==''){
           messageBox("Please enter value");
           location_trips2.focus();
           return false;
         }
         if(trim(trips_datefrom2.value)==''){
           messageBox("Please select held from date");
           trips_datefrom2.focus();
           return false;
         }
         if(trim(trips_dateto2.value)==''){
           messageBox("Please select held to date");
           trips_dateto2.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom2.focus();
               return false;
         }

         if(!dateDifference(trips_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto2.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom2.value,trips_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto2.focus();
               return false;
         }
     }

     if(trim(strength_trips2.value)!=''){

         if(!isNumeric(trim(strength_trips2.value))){
             messageBox("Please enter numeric values");
             strength_trips2.focus();
             return false;
         }

         if(trim(act5_marks.value)==''){
           messageBox("Please enter value");
           act5_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act5_marks.value))){
             messageBox("Please enter numeric values");
             act5_marks.focus();
             return false;
         }


         if(trim(location_trips2.value)==''){
           messageBox("Please enter value");
           location_trips2.focus();
           return false;
         }
         if(trim(trips_datefrom2.value)==''){
           messageBox("Please select held from date");
           trips_datefrom2.focus();
           return false;
         }
         if(trim(trips_dateto2.value)==''){
           messageBox("Please select held to date");
           trips_dateto2.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom2.focus();
               return false;
         }

         if(!dateDifference(trips_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto2.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom2.value,trips_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto2.focus();
               return false;
         }
     }

     if(trim(location_trips2.value)!=''){
         if(trim(act5_marks.value)==''){
           messageBox("Please enter value");
           act5_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act5_marks.value))){
             messageBox("Please enter numeric values");
             act5_marks.focus();
             return false;
         }
         if(trim(strength_trips2.value)==''){
           messageBox("Please enter value");
           strength_trips2.focus();
           return false;
         }
         if(trim(trips_datefrom2.value)==''){
           messageBox("Please select held from date");
           trips_datefrom2.focus();
           return false;
         }
         if(trim(trips_dateto2.value)==''){
           messageBox("Please select held to date");
           trips_dateto2.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom2.focus();
               return false;
         }

         if(!dateDifference(trips_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto2.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom2.value,trips_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto2.focus();
               return false;
         }
     }

     if(trim(trips_datefrom2.value)!=''  || trim(trips_dateto2.value)!=''){
         if(trim(act5_marks.value)==''){
           messageBox("Please enter value");
           act5_marks.focus();
           return false;
         }

         if(!isNumeric(trim(act5_marks.value))){
             messageBox("Please enter numeric values");
             act5_marks.focus();
             return false;
         }
         if(trim(strength_trips2.value)==''){
           messageBox("Please enter value");
           strength_trips2.focus();
           return false;
         }
         if(trim(location_trips2.value)==''){
           messageBox("Please enter value");
           location_trips2.focus();
           return false;
         }
         if(trim(trips_datefrom2.value)==''){
           messageBox("Please select held from date");
           trips_datefrom2.focus();
           return false;
         }
         if(trim(trips_dateto2.value)==''){
           messageBox("Please select held to date");
           trips_dateto2.focus();
           return false;
         }

         if(!dateDifference(trips_datefrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               trips_datefrom2.focus();
               return false;
         }

         if(!dateDifference(trips_dateto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               trips_dateto2.focus();
               return false;
         }

         if(!dateDifference(trips_datefrom2.value,trips_dateto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               trips_dateto2.focus();
               return false;
         }
     }


       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 act1_marks:(act1_marks.value),
                 strength_indus:(strength_indus.value),
                 location_indus:(location_indus.value),
                 indus_datefrom:(indus_datefrom.value),
                 indus_dateto:(indus_dateto.value),
                 act2_marks:(act2_marks.value),
                 strength_indus2:(strength_indus2.value),
                 location_indus2:(location_indus2.value),
                 indus_datefrom2:(indus_datefrom2.value),
                 indus_dateto2:(indus_dateto2.value),
                 reportSubmitted:(reportSubmitted),
                 act3_marks:(act3_marks),
                 act4_marks:(act4_marks.value),
                 strength_trips:(strength_trips.value),
                 location_trips:(location_trips.value),
                 trips_datefrom:(trips_datefrom.value),
                 trips_dateto:(trips_dateto.value),
                 act5_marks:(act5_marks.value),
                 strength_trips2:(strength_trips2.value),
                 location_trips2:(location_trips2.value),
                 trips_datefrom2:(trips_datefrom2.value),
                 trips_dateto2:(trips_dateto2.value),
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                             hiddenFloatingDiv('ProofDiv');
                             //updates total
                             updateSelfEvaluation(parseInt(act1_marks.value,10)+parseInt(act2_marks.value,10)+parseInt(act3_marks,10)+parseInt(act4_marks.value,10));
                             //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function changeValue10_1(val){
    var value=trim(val);
    if(val==''){
        document.getElementById('act1_marks').value=0;
        return false;
    }
    document.getElementById('act1_marks').value=10;
    return false;
}

function changeValue10_2(val){
    var value=trim(val);
    if(val==''){
        document.getElementById('act2_marks').value=0;
        return false;
    }
    document.getElementById('act2_marks').value=10;
    return false;
}

function changeValue10_3(val){
    var value=trim(val);
    if(val==''){
        document.getElementById('act3_marks').value=0;
        return false;
    }
    document.getElementById('act3_marks').value=5;
    return false;
}

function changeValue10_4(val){
    var value=trim(val);
    if(val==''){
        document.getElementById('act4_marks').value=0;
        return false;
    }
    document.getElementById('act4_marks').value=5;
    return false;
}

function validateProofForm10(){

     var eventname_org=document.getElementById('eventname_org');
     var org_strength=document.getElementById('org_strength');
     var org_budget=document.getElementById('org_budget');
     var org_heldfrom=document.getElementById('org_heldfrom');
     var org_heldto=document.getElementById('org_heldto');

     var eventname_org2=document.getElementById('eventname_org2');
     var org_strength2=document.getElementById('org_strength2');
     var org_budget2=document.getElementById('org_budget2');
     var org_heldfrom2=document.getElementById('org_heldfrom2');
     var org_heldto2=document.getElementById('org_heldto2');

     var eventname_assisted=document.getElementById('eventname_assisted');
     var assisted_strength=document.getElementById('assisted_strength');
     var assisted_budget=document.getElementById('assisted_budget');
     var assisted_heldfrom=document.getElementById('assisted_heldfrom');
     var assisted_heldto=document.getElementById('assisted_heldto');

     var eventname_assisted2=document.getElementById('eventname_assisted2');
     var assisted_strength2=document.getElementById('assisted_strength2');
     var assisted_budget2=document.getElementById('assisted_budget2');
     var assisted_heldfrom2=document.getElementById('assisted_heldfrom2');
     var assisted_heldto2=document.getElementById('assisted_heldto2');

     if(trim(eventname_org.value)!=''){
         if(trim(org_strength.value)==''){
           messageBox("Please enter value");
           org_strength.focus();
           return false;
         }
         if(trim(org_budget.value)==''){
           messageBox("Please enter value");
           org_budget.focus();
           return false;
         }
         if(trim(org_heldfrom.value)==''){
           messageBox("Please select held from date");
           org_heldfrom.focus();
           return false;
         }
         if(trim(org_heldto.value)==''){
           messageBox("Please select held to date");
           org_heldto.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom.focus();
               return false;
         }

         if(!dateDifference(org_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom.value,org_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto.focus();
               return false;
         }
     }

     if(trim(org_strength.value)!=''){
         if(!isNumeric(trim(org_strength.value))){
             messageBox("Please enter numeric values");
             org_strength.focus();
             return false;
         }

         if(trim(eventname_org.value)==''){
           messageBox("Please enter value");
           eventname_org.focus();
           return false;
         }
		  if(!isNumeric(trim(org_budget.value))){
             messageBox("Please enter numeric values");
             org_budget.focus();
             return false;
         }
         if(trim(org_budget.value)==''){
           messageBox("Please enter value");
           org_budget.focus();
           return false;
         }
         if(trim(org_heldfrom.value)==''){
           messageBox("Please select held from date");
           org_heldfrom.focus();
           return false;
         }
         if(trim(org_heldto.value)==''){
           messageBox("Please select held to date");
           org_heldto.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom.focus();
               return false;
         }

         if(!dateDifference(org_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom.value,org_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto.focus();
               return false;
         }
     }

     if(trim(org_budget.value)!=''){

         if(trim(eventname_org.value)==''){
           messageBox("Please enter value");
           eventname_org.focus();
           return false;
         }
         if(trim(org_strength.value)==''){
           messageBox("Please enter value");
           org_strength.focus();
           return false;
         }
         if(trim(org_budget.value)==''){
           messageBox("Please enter value");
           org_budget.focus();
           return false;
         }
         if(trim(org_heldfrom.value)==''){
           messageBox("Please select held from date");
           org_heldfrom.focus();
           return false;
         }
         if(trim(org_heldto.value)==''){
           messageBox("Please select held to date");
           org_heldto.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom.focus();
               return false;
         }

         if(!dateDifference(org_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom.value,org_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto.focus();
               return false;
         }
     }

     if(trim(org_heldfrom.value)!=''  || trim(org_heldto.value)!=''){
         if(trim(eventname_org.value)==''){
           messageBox("Please enter value");
           eventname_org.focus();
           return false;
         }
         if(trim(org_strength.value)==''){
           messageBox("Please enter value");
           org_strength.focus();
           return false;
         }
         if(trim(org_budget.value)==''){
           messageBox("Please enter value");
           org_budget.focus();
           return false;
         }
         if(trim(org_heldfrom.value)==''){
           messageBox("Please select held from date");
           org_heldfrom.focus();
           return false;
         }
         if(trim(org_heldto.value)==''){
           messageBox("Please select held to date");
           org_heldto.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom.focus();
               return false;
         }

         if(!dateDifference(org_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom.value,org_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto.focus();
               return false;
         }
     }

     if(trim(eventname_org2.value)!=''){
         if(trim(org_strength2.value)==''){
           messageBox("Please enter value");
           org_strength2.focus();
           return false;
         }
         if(trim(org_budget2.value)==''){
           messageBox("Please enter value");
           org_budget2.focus();
           return false;
         }
         if(trim(org_heldfrom2.value)==''){
           messageBox("Please select held from date");
           org_heldfrom2.focus();
           return false;
         }
         if(trim(org_heldto2.value)==''){
           messageBox("Please select held to date");
           org_heldto2.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(org_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto2.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom2.value,org_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto2.focus();
               return false;
         }
     }

     if(trim(org_strength2.value)!=''){
         if(!isNumeric(trim(org_strength2.value))){
             messageBox("Please enter numeric values");
             org_strength2.focus();
             return false;
         }

         if(trim(eventname_org2.value)==''){
           messageBox("Please enter value");
           eventname_org2.focus();
           return false;
         }
         if(trim(org_budget2.value)==''){
           messageBox("Please enter value");
           org_budget2.focus();
           return false;
         }
         if(trim(org_heldfrom2.value)==''){
           messageBox("Please select held from date");
           org_heldfrom2.focus();
           return false;
         }
         if(trim(org_heldto2.value)==''){
           messageBox("Please select held to date");
           org_heldto2.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(org_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto2.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom2.value,org_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto2.focus();
               return false;
         }
     }

     if(trim(org_budget2.value)!=''){

         if(trim(eventname_org2.value)==''){
           messageBox("Please enter value");
           eventname_org2.focus();
           return false;
         }
         if(trim(org_strength2.value)==''){
           messageBox("Please enter value");
           org_strength2.focus();
           return false;
         }
         if(trim(org_budget2.value)==''){
           messageBox("Please enter value");
           org_budget2.focus();
           return false;
         }
         if(trim(org_heldfrom2.value)==''){
           messageBox("Please select held from date");
           org_heldfrom2.focus();
           return false;
         }
         if(trim(org_heldto2.value)==''){
           messageBox("Please select held to date");
           org_heldto2.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(org_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto2.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom2.value,org_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto2.focus();
               return false;
         }
     }

     if(trim(org_heldfrom2.value)!=''  || trim(org_heldto2.value)!=''){
         if(trim(eventname_org2.value)==''){
           messageBox("Please enter value");
           eventname_org2.focus();
           return false;
         }
         if(trim(org_strength2.value)==''){
           messageBox("Please enter value");
           org_strength2.focus();
           return false;
         }
         if(trim(org_budget2.value)==''){
           messageBox("Please enter value");
           org_budget2.focus();
           return false;
         }
         if(trim(org_heldfrom2.value)==''){
           messageBox("Please select held from date");
           org_heldfrom2.focus();
           return false;
         }
         if(trim(org_heldto2.value)==''){
           messageBox("Please select held to date");
           org_heldto2.focus();
           return false;
         }

         if(!dateDifference(org_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               org_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(org_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               org_heldto2.focus();
               return false;
         }

         if(!dateDifference(org_heldfrom2.value,org_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               org_heldto2.focus();
               return false;
         }
     }


     if(trim(eventname_assisted.value)!=''){
         if(trim(assisted_strength.value)==''){
           messageBox("Please enter value");
           assisted_strength.focus();
           return false;
         }
         if(trim(assisted_budget.value)==''){
           messageBox("Please enter value");
           assisted_budget.focus();
           return false;
         }
         if(trim(assisted_heldfrom.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom.focus();
           return false;
         }
         if(trim(assisted_heldto.value)==''){
           messageBox("Please select held to date");
           assisted_heldto.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom.value,assisted_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto.focus();
               return false;
         }
     }

     if(trim(assisted_strength.value)!=''){

         if(!isNumeric(trim(assisted_strength.value))){
             messageBox("Please enter numeric values");
             assisted_strength.focus();
             return false;
         }

         if(trim(eventname_assisted.value)==''){
           messageBox("Please enter value");
           eventname_assisted.focus();
           return false;
         }
         if(trim(assisted_budget.value)==''){
           messageBox("Please enter value");
           assisted_budget.focus();
           return false;
         }
         if(trim(assisted_heldfrom.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom.focus();
           return false;
         }
         if(trim(assisted_heldto.value)==''){
           messageBox("Please select held to date");
           assisted_heldto.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom.value,assisted_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto.focus();
               return false;
         }
     }

     if(trim(assisted_budget.value)!=''){

         if(trim(eventname_assisted.value)==''){
           messageBox("Please enter value");
           eventname_assisted.focus();
           return false;
         }
         if(trim(assisted_strength.value)==''){
           messageBox("Please enter value");
           assisted_strength.focus();
           return false;
         }
         if(trim(assisted_budget.value)==''){
           messageBox("Please enter value");
           assisted_budget.focus();
           return false;
         }
         if(trim(assisted_heldfrom.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom.focus();
           return false;
         }
         if(trim(assisted_heldto.value)==''){
           messageBox("Please select held to date");
           assisted_heldto.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom.value,assisted_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto.focus();
               return false;
         }
     }

     if(trim(assisted_heldfrom.value)!=''  || trim(assisted_heldto.value)!=''){
         if(trim(eventname_assisted.value)==''){
           messageBox("Please enter value");
           eventname_assisted.focus();
           return false;
         }
         if(trim(assisted_strength.value)==''){
           messageBox("Please enter value");
           assisted_strength.focus();
           return false;
         }
         if(trim(assisted_budget.value)==''){
           messageBox("Please enter value");
           assisted_budget.focus();
           return false;
         }
         if(trim(assisted_heldfrom.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom.focus();
           return false;
         }
         if(trim(assisted_heldto.value)==''){
           messageBox("Please select held to date");
           assisted_heldto.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom.value,assisted_heldto.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto.focus();
               return false;
         }
     }

     if(trim(eventname_assisted2.value)!=''){
         if(trim(assisted_strength2.value)==''){
           messageBox("Please enter value");
           assisted_strength2.focus();
           return false;
         }
         if(trim(assisted_budget2.value)==''){
           messageBox("Please enter value");
           assisted_budget2.focus();
           return false;
         }
         if(trim(assisted_heldfrom2.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom2.focus();
           return false;
         }
         if(trim(assisted_heldto2.value)==''){
           messageBox("Please select held to date");
           assisted_heldto2.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,assisted_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto2.focus();
               return false;
         }
     }

     if(trim(assisted_strength2.value)!=''){

         if(!isNumeric(trim(assisted_strength2.value))){
             messageBox("Please enter numeric values");
             assisted_strength2.focus();
             return false;
         }

         if(trim(eventname_assisted2.value)==''){
           messageBox("Please enter value");
           eventname_assisted2.focus();
           return false;
         }
         if(trim(assisted_budget2.value)==''){
           messageBox("Please enter value");
           assisted_budget2.focus();
           return false;
         }
         if(trim(assisted_heldfrom2.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom2.focus();
           return false;
         }
         if(trim(assisted_heldto2.value)==''){
           messageBox("Please select held to date");
           assisted_heldto2.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,assisted_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto2.focus();
               return false;
         }
     }

     if(trim(assisted_budget2.value)!=''){

         if(trim(eventname_assisted2.value)==''){
           messageBox("Please enter value");
           eventname_assisted2.focus();
           return false;
         }
         if(trim(assisted_strength2.value)==''){
           messageBox("Please enter value");
           assisted_strength2.focus();
           return false;
         }
         if(trim(assisted_budget2.value)==''){
           messageBox("Please enter value");
           assisted_budget2.focus();
           return false;
         }
         if(trim(assisted_heldfrom2.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom2.focus();
           return false;
         }
         if(trim(assisted_heldto2.value)==''){
           messageBox("Please select held to date");
           assisted_heldto2.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,assisted_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto2.focus();
               return false;
         }
     }

     if(trim(assisted_heldfrom2.value)!=''  || trim(assisted_heldto2.value)!=''){
         if(trim(eventname_assisted2.value)==''){
           messageBox("Please enter value");
           eventname_assisted2.focus();
           return false;
         }
         if(trim(assisted_strength2.value)==''){
           messageBox("Please enter value");
           assisted_strength2.focus();
           return false;
         }
         if(trim(assisted_budget2.value)==''){
           messageBox("Please enter value");
           assisted_budget2.focus();
           return false;
         }
         if(trim(assisted_heldfrom2.value)==''){
           messageBox("Please select held from date");
           assisted_heldfrom2.focus();
           return false;
         }
         if(trim(assisted_heldto2.value)==''){
           messageBox("Please select held to date");
           assisted_heldto2.focus();
           return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,serverDate,'-')){
               messageBox("Held from date can not be greater than current date");
               assisted_heldfrom2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldto2.value,serverDate,'-')){
               messageBox("Held to date can not be greater than current date");
               assisted_heldto2.focus();
               return false;
         }

         if(!dateDifference(assisted_heldfrom2.value,assisted_heldto2.value,'-')){
               messageBox("Held to date can not be less than held from date");
               assisted_heldto2.focus();
               return false;
         }
     }


       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 eventname_org : eventname_org.value,
                 org_strength : org_strength.value,
                 org_budget : org_budget.value,
                 org_heldfrom : org_heldfrom.value,
                 org_heldto : org_heldto.value,
                 eventname_org2 : eventname_org2.value,
                 org_strength2 : org_strength2.value,
                 org_budget2 : org_budget2.value,
                 org_heldfrom2 : org_heldfrom2.value,
                 org_heldto2 : org_heldto2.value,
                 eventname_assisted : eventname_assisted.value,
                 assisted_strength : assisted_strength.value,
                 assisted_budget : assisted_budget.value,
                 assisted_heldfrom : assisted_heldfrom.value,
                 assisted_heldto : assisted_heldto.value,
                 eventname_assisted2 : eventname_assisted2.value,
                 assisted_strength2 : assisted_strength2.value,
                 assisted_budget2 : assisted_budget2.value,
                 assisted_heldfrom2 : assisted_heldfrom2.value,
                 assisted_heldto2 : assisted_heldto2.value,
                 act1_marks : document.getElementById('act1_marks').value,
                 act2_marks : document.getElementById('act2_marks').value,
                 act3_marks : document.getElementById('act3_marks').value,
                 act4_marks : document.getElementById('act4_marks').value,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                            hiddenFloatingDiv('ProofDiv');
                            //updates total
                            updateSelfEvaluation(parseInt(document.getElementById('act1_marks').value,10)+parseInt(document.getElementById('act2_marks').value,10)+parseInt(document.getElementById('act3_marks').value,10)+parseInt(document.getElementById('act4_marks').value,10));
                            //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function validateProofForm11(){

     var even_incharge=document.getElementById('even_incharge');
     var even_cases=document.getElementById('even_cases');
     var even_achieve1=document.getElementById('even_achieve1');
     var even_desc1=document.getElementById('even_desc1');
     var even_role1=document.getElementById('even_role1');
     var even_achieve2=document.getElementById('even_achieve2');
     var even_desc2=document.getElementById('even_desc2');
     var even_role2=document.getElementById('even_role2');

     var odd_incharge=document.getElementById('odd_incharge');
     var odd_cases=document.getElementById('odd_cases');
     var odd_achieve1=document.getElementById('odd_achieve1');
     var odd_desc1=document.getElementById('odd_desc1');
     var odd_role1=document.getElementById('odd_role1');
     var odd_achieve2=document.getElementById('odd_achieve2');
     var odd_desc2=document.getElementById('odd_desc2');
     var odd_role2=document.getElementById('odd_role2');

     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);
     var act3_marks=trim(document.getElementById('act3_marks').value);
     var act4_marks=trim(document.getElementById('act4_marks').value);
     var act5_marks=trim(document.getElementById('act5_marks').value);
     var act6_marks=trim(document.getElementById('act6_marks').value);


     if(trim(even_incharge.value)!=''){
         /*
         if(trim(even_cases.value)==''){
             messageBox('Please enter value');
             even_cases.focus();
             return false;
         }
         */
         if(document.proofForm.even_checked.checked==false){
             messageBox("Please check the yes/no checkbox");
             document.proofForm.even_checked.focus();
             return false;
         }

     }
     if(trim(even_cases.value)!=''){
         if(trim(even_incharge.value)==''){
             messageBox('Please enter value');
             even_incharge.focus();
             return false;
         }
     }
     if(trim(even_achieve1.value)!=''){
         if(trim(even_desc1.value)==''){
             messageBox('Please enter value');
             even_desc1.focus();
             return false;
         }
         if(trim(even_role1.value)==''){
             messageBox('Please enter value');
             even_role1.focus();
             return false;
         }
     }
     if(trim(even_desc1.value)!=''){
         if(trim(even_achieve1.value)==''){
             messageBox('Please enter value');
             even_achieve1.focus();
             return false;
         }
         if(trim(even_role1.value)==''){
             messageBox('Please enter value');
             even_role1.focus();
             return false;
         }
     }
     if(trim(even_role1.value)!=''){
         if(trim(even_achieve1.value)==''){
             messageBox('Please enter value');
             even_achieve1.focus();
             return false;
         }
         if(trim(even_desc1.value)==''){
             messageBox('Please enter value');
             even_desc1.focus();
             return false;
         }
     }
     if(trim(even_achieve2.value)!=''){
         if(trim(even_desc2.value)==''){
             messageBox('Please enter value');
             even_desc2.focus();
             return false;
         }
         if(trim(even_role2.value)==''){
             messageBox('Please enter value');
             even_role2.focus();
             return false;
         }
     }
     if(trim(even_desc2.value)!=''){
         if(trim(even_achieve1.value)==''){
             messageBox('Please enter value');
             even_achieve2.focus();
             return false;
         }
         if(trim(even_role2.value)==''){
             messageBox('Please enter value');
             even_role2.focus();
             return false;
         }
     }
     if(trim(even_role2.value)!=''){
         if(trim(even_achieve1.value)==''){
             messageBox('Please enter value');
             even_achieve2.focus();
             return false;
         }
         if(trim(even_desc2.value)==''){
             messageBox('Please enter value');
             even_desc2.focus();
             return false;
         }
     }

     if(trim(odd_incharge.value)!=''){
         /*if(trim(odd_cases.value)==''){
             messageBox('Please enter value');
             odd_cases.focus();
             return false;
         }
         */
         if(document.proofForm.odd_checked.checked==false){
             messageBox("Please check the yes/no checkbox");
             document.proofForm.odd_checked.focus();
             return false;
         }
     }
     if(trim(odd_cases.value)!=''){
         if(trim(odd_incharge.value)==''){
             messageBox('Please enter value');
             odd_incharge.focus();
             return false;
         }
     }
     if(trim(odd_achieve1.value)!=''){
         if(trim(odd_desc1.value)==''){
             messageBox('Please enter value');
             odd_desc1.focus();
             return false;
         }
         if(trim(odd_role1.value)==''){
             messageBox('Please enter value');
             odd_role1.focus();
             return false;
         }
     }
     if(trim(odd_desc1.value)!=''){
         if(trim(odd_achieve1.value)==''){
             messageBox('Please enter value');
             odd_achieve1.focus();
             return false;
         }
         if(trim(odd_role1.value)==''){
             messageBox('Please enter value');
             odd_role1.focus();
             return false;
         }
     }
     if(trim(odd_role1.value)!=''){
         if(trim(odd_achieve1.value)==''){
             messageBox('Please enter value');
             odd_achieve1.focus();
             return false;
         }
         if(trim(odd_desc1.value)==''){
             messageBox('Please enter value');
             odd_desc1.focus();
             return false;
         }
     }
     if(trim(odd_achieve2.value)!=''){
         if(trim(odd_desc2.value)==''){
             messageBox('Please enter value');
             odd_desc2.focus();
             return false;
         }
         if(trim(odd_role2.value)==''){
             messageBox('Please enter value');
             odd_role2.focus();
             return false;
         }
     }
     if(trim(odd_desc2.value)!=''){
         if(trim(odd_achieve1.value)==''){
             messageBox('Please enter value');
             odd_achieve2.focus();
             return false;
         }
         if(trim(odd_role2.value)==''){
             messageBox('Please enter value');
             odd_role2.focus();
             return false;
         }
     }
     if(trim(odd_role2.value)!=''){
         if(trim(odd_achieve1.value)==''){
             messageBox('Please enter value');
             odd_achieve2.focus();
             return false;
         }
         if(trim(odd_desc2.value)==''){
             messageBox('Please enter value');
             odd_desc2.focus();
             return false;
         }
     }

   if(act1_marks==''){
       messageBox("Enter marks");
       document.getElementById('act1_marks').focus();
       return false;
   }

   if(act1_marks!='' && act1_marks!=0){
      if(!isNumeric(act1_marks)){
         messageBox("Enter numeric value");
         document.getElementById('act1_marks').focus();
         return false;
      }

      if(parseInt(act1_marks,10)>parseInt(document.getElementById('act1_marks').alt,10)) {
         messageBox("Marks can not be greater than "+document.getElementById('act1_marks').alt);
         document.getElementById('act1_marks').focus();
         return false;
      }

      if(trim(even_incharge.value)==''){
         messageBox("Please enter value");
         even_incharge.focus();
         return false;
      }
   }

   if(act2_marks==''){
       messageBox("Enter marks");
       document.getElementById('act2_marks').focus();
       return false;
   }

   if(act2_marks!='' && act2_marks!=0){
      if(!isNumeric(Math.abs(act2_marks))){
         messageBox("Enter numeric value");
         document.getElementById('act2_marks').focus();
         return false;
      }

      if(parseInt(act2_marks,10)>0 || parseInt(act2_marks,10)<-5) {
         messageBox("Enter marks between 0 and -5");
         document.getElementById('act2_marks').focus();
         return false;
      }

      if(trim(even_cases.value)==''){
         messageBox("Please enter value");
         even_cases.focus();
         return false;
      }
   }

   if(act3_marks!='' && act3_marks!='0'){

      if(!isNumeric(act3_marks)){
         messageBox("Enter numeric value");
         document.getElementById('act3_marks').focus();
         return false;
      }

      if(parseInt(act3_marks,10)>parseInt(document.getElementById('act3_marks').alt,10)) {
         messageBox("Marks can not be greater than "+document.getElementById('act3_marks').alt);
         document.getElementById('act3_marks').focus();
         return false;
      }

      if(trim(even_achieve1.value)=='' || trim(even_achieve2.value)==''){
         messageBox("Please enter value");
         even_achieve1.focus();
         return false;
      }
   }

   if(act4_marks==''){
       messageBox("Enter marks");
       document.getElementById('act4_marks').focus();
       return false;
   }

   if(act4_marks!='' && act4_marks!=0){
      if(!isNumeric(act4_marks)){
         messageBox("Enter numeric value");
         document.getElementById('act4_marks').focus();
         return false;
      }

      if(parseInt(act4_marks,10)>parseInt(document.getElementById('act4_marks').alt,10)) {
         messageBox("Marks can not be greater than "+document.getElementById('act4_marks').alt);
         document.getElementById('act4_marks').focus();
         return false;
      }

      if(trim(odd_incharge.value)==''){
         messageBox("Please enter value");
         odd_incharge.focus();
         return false;
      }
   }

   if(act5_marks==''){
       messageBox("Enter marks");
       document.getElementById('act5_marks').focus();
       return false;
   }

   if(act5_marks!='' && act5_marks!=0){
      if(!isNumeric(Math.abs(act5_marks))){
         messageBox("Enter numeric value");
         document.getElementById('act5_marks').focus();
         return false;
      }

      if(parseInt(act5_marks,10)>0 || parseInt(act5_marks,10)<-5) {
         messageBox("Enter marks between 0 and -5");
         document.getElementById('act5_marks').focus();
         return false;
      }

      if(trim(odd_cases.value)==''){
         messageBox("Please enter value");
         odd_cases.focus();
         return false;
      }
   }

   if(act6_marks!='' && act6_marks!=0){

      if(!isNumeric(act6_marks)){
         messageBox("Enter numeric value");
         document.getElementById('act6_marks').focus();
         return false;
      }

      if(parseInt(act6_marks,10)>parseInt(document.getElementById('act6_marks').alt,10)) {
         messageBox("Marks can not be greater than "+document.getElementById('act6_marks').alt);
         document.getElementById('act6_marks').focus();
         return false;
      }

      if(trim(odd_achieve1.value)=='' || trim(odd_achieve2.value)==''){
         messageBox("Please enter value");
         odd_achieve1.focus();
         return false;
      }
   }

   var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
   new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             even_incharge : trim(even_incharge.value),
             even_cases : trim(even_cases.value),
             even_achieve1 : trim(even_achieve1.value),
             even_desc1 : trim(even_desc1.value),
             even_role1 : trim(even_role1.value),
             even_achieve2 : trim(even_achieve2.value),
             even_desc2 : trim(even_desc2.value),
             even_role2 : trim(even_role2.value),
             odd_incharge : trim(odd_incharge.value),
             odd_cases : trim(odd_cases.value),
             odd_achieve1 : trim(odd_achieve1.value),
             odd_desc1 : trim(odd_desc1.value),
             odd_role1 : trim(odd_role1.value),
             odd_achieve2 : trim(odd_achieve2.value),
             odd_desc2 : trim(odd_desc2.value),
             odd_role2 : trim(odd_role2.value),
             act1_marks : act1_marks,
             act2_marks : act2_marks,
             act3_marks : act3_marks,
             act4_marks : act4_marks,
             act5_marks : act5_marks,
             act6_marks : act6_marks,
             even_checked : (document.proofForm.even_checked.checked==true?1:0),
             even_cases_count : trim(document.proofForm.even_cases_count.value),
             odd_checked : (document.proofForm.odd_checked.checked==true?1:0),
             odd_cases_count : trim(document.proofForm.odd_cases_count.value),
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        messageBox(trim(transport.responseText));
                        hiddenFloatingDiv('ProofDiv');
                        //updates total
						var str = parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10)+parseInt(act4_marks,10)+parseInt(act5_marks,10)+parseInt(act6_marks,10);
						updateSelfEvaluation(math.ceil(((str/2)*100)/100));

                        //return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}


function validateProofForm12(){

     var track1=document.getElementById('track1');
     var track2=document.getElementById('track2');
     var track3=document.getElementById('track3');
     var track4=document.getElementById('track4');

     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);
     var act3_marks=trim(document.getElementById('act3_marks').value);
     var act4_marks=trim(document.getElementById('act4_marks').value);

     if(act1_marks==''){
         messageBox("Enter marks");
         document.getElementById('act1_marks').focus();
         return false;
     }

     if(act1_marks!='' && act1_marks!=0){
         if(!isNumeric(act1_marks)){
             messageBox("Enter numeric value");
             document.getElementById('act1_marks').focus();
             return false;
         }
         if(parseInt(act1_marks,10)>parseInt(document.getElementById('act1_marks').alt,10)){
             messageBox("Marks can not be greater than "+document.getElementById('act1_marks').alt);
             document.getElementById('act1_marks').focus();
             return false;
         }
         if(trim(track1.value)==''){
             messageBox("Please enter value");
             track1.focus();
             return false;
         }
     }

     if(act2_marks!='' && act2_marks!=0){
         if(!isNumeric(act2_marks)){
             messageBox("Enter numeric value");
             document.getElementById('act2_marks').focus();
             return false;
         }
         if(parseInt(act2_marks,10)>parseInt(document.getElementById('act2_marks').alt,10)){
             messageBox("Marks can not be greater than "+document.getElementById('act2_marks').alt);
             document.getElementById('act2_marks').focus();
             return false;
         }
         if(trim(track2.value)==''){
             messageBox("Please enter value");
             track2.focus();
             return false;
         }
     }

     if(act3_marks!='' && act3_marks!=0){
         if(!isNumeric(act3_marks)){
             messageBox("Enter numeric value");
             document.getElementById('act3_marks').focus();
             return false;
         }
         if(parseInt(act3_marks,10)>parseInt(document.getElementById('act3_marks').alt,10)){
             messageBox("Marks can not be greater than "+document.getElementById('act3_marks').alt);
             document.getElementById('act3_marks').focus();
             return false;
         }
         if(trim(track3.value)==''){
             messageBox("Please enter value");
             track3.focus();
             return false;
         }
     }

     if(act4_marks!='' && act4_marks!=0){
         if(!isNumeric(act4_marks)){
             messageBox("Enter numeric value");
             document.getElementById('act4_marks').focus();
             return false;
         }
         if(parseInt(act4_marks,10)>parseInt(document.getElementById('act4_marks').alt,10)){
             messageBox("Marks can not be greater than "+document.getElementById('act4_marks').alt);
             document.getElementById('act4_marks').focus();
             return false;
         }
         if(trim(track4.value)==''){
             messageBox("Please enter value");
             track4.focus();
             return false;
         }
     }


   var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
   new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             track1 : trim(track1.value),
             track2 : trim(track2.value),
             track3 : trim(track3.value),
             track4 : trim(track4.value),
             act1_marks : act1_marks,
             act2_marks : act2_marks,
             act3_marks : act3_marks,
             act4_marks : act4_marks,
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        messageBox(trim(transport.responseText));
                        hiddenFloatingDiv('ProofDiv');
                        //updates total
                        updateSelfEvaluation(parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10)+parseInt(act4_marks,10));
                        //return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}

function validateProofForm13(){

     var feed_even=document.getElementById('feed_even');
     var feed_odd=document.getElementById('feed_odd');

     if(trim(feed_even.value)==''){
         messageBox("Please enter value");
         feed_even.focus();
         return false;
     }

     if(!isNumeric(trim(feed_even.value))){
         messageBox("Please enter numeric value");
         feed_even.focus();
         return false;
     }

     if(parseInt(feed_even.value,10)>parseInt(feed_even.alt,10)){
         messageBox("Value can not be greater than "+feed_even.alt);
         feed_even.focus();
         return false;
     }

     if(trim(feed_odd.value)==''){
         messageBox("Please enter value");
         feed_odd.focus();
         return false;
     }

     if(!isNumeric(trim(feed_odd.value))){
         messageBox("Please enter numeric value");
         feed_odd.focus();
         return false;
     }

     if(parseInt(feed_odd.value,10)>parseInt(feed_odd.alt,10)){
         messageBox("Value can not be greater than "+feed_odd.alt);
         feed_odd.focus();
         return false;
     }


   var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
   new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             feed_even : trim(feed_even.value),
             feed_odd : trim(feed_odd.value),
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        messageBox(trim(transport.responseText));
                        hiddenFloatingDiv('ProofDiv');
                        //updates total
                        var str = parseInt(feed_even.value,10)+parseInt(feed_odd.value,10);
						updateSelfEvaluation(math.ceil(str/2));
                        //return false;
	                }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}


function change15_1(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act1_marks').value=0;
        return false;
     }
     document.getElementById('act1_marks').value=Math.ceil(parseInt(val,10)/2);
}

function change15_2(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act2_marks').value=0;
        return false;
     }
     document.getElementById('act2_marks').value=parseInt(val,10);
}

function change15_3(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act3_marks').value=0;
        return false;
     }
     document.getElementById('act3_marks').value=parseInt(val,10)*5;
}


function change15_4(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act4_marks').value=0;
        return false;
     }
     document.getElementById('act4_marks').value=Math.ceil(parseInt(val,10)/2);
}

function change15_5(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act5_marks').value=0;
        return false;
     }
     document.getElementById('act5_marks').value=parseInt(val,10);
}

function change15_6(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act6_marks').value=0;
        return false;
     }
     document.getElementById('act6_marks').value=parseInt(val,10)*5;
}


function validateProofForm15(){

     var even_prob_design=document.getElementById('even_prob_design');
     var even_avg_marks=document.getElementById('even_avg_marks');
     var even_eminent=document.getElementById('even_eminent');

     var odd_prob_design=document.getElementById('odd_prob_design');
     var odd_avg_marks=document.getElementById('odd_avg_marks');
     var odd_eminent=document.getElementById('odd_eminent');

     var times1=trim(document.getElementById('times1').value);
     var times2=trim(document.getElementById('times2').value);
     var times3=trim(document.getElementById('times3').value);
     var times4=trim(document.getElementById('times4').value);
     var times5=trim(document.getElementById('times5').value);
     var times6=trim(document.getElementById('times6').value);

     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);
     var act3_marks=trim(document.getElementById('act3_marks').value);
     var act4_marks=trim(document.getElementById('act4_marks').value);
     var act5_marks=trim(document.getElementById('act5_marks').value);
     var act6_marks=trim(document.getElementById('act6_marks').value);

     /* if(times1==''){
        messageBox("Enter value");
        document.getElementById('times1').focus();
        return false;
     } */
     if(times1!='' && times1!=0){
        if(!isNumeric(times1)){
           messageBox("Enter numeric value");
           document.getElementById('times1').focus();
           return false;
        }
        if(parseInt(times1,10)>parseInt(document.getElementById('times1').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times1').alt);
           document.getElementById('times1').focus();
           return false;
        }
        if(trim(even_prob_design.value)==''){
           messageBox("Please enter value");
           even_prob_design.focus();
           return false;
        }
     }

    /* if(times2==''){
        messageBox("Enter value");
        document.getElementById('times2').focus();
        return false;
     } */
     if(times2!='' && times2!=0){
        if(!isNumeric(times2)){
           messageBox("Enter numeric value");
           document.getElementById('times2').focus();
           return false;
        }
        if(parseInt(times2,10)>parseInt(document.getElementById('times2').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times2').alt);
           document.getElementById('times2').focus();
           return false;
        }
        if(trim(even_avg_marks.value)==''){
           messageBox("Please enter value");
           even_avg_marks.focus();
           return false;
        }
     }

   /*  if(times3==''){
        messageBox("Enter value");
        document.getElementById('times3').focus();
        return false;
     }  */
     if(times3!='' && times3!=0){
        if(!isNumeric(times3)){
           messageBox("Enter numeric value");
           document.getElementById('times3').focus();
           return false;
        }
        if(parseInt(times3,10)>parseInt(document.getElementById('times3').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times3').alt);
           document.getElementById('times3').focus();
           return false;
        }
        if(trim(even_eminent.value)==''){
           messageBox("Please enter value");
           even_eminent.focus();
           return false;
        }
     }

  /*   if(times4==''){
        messageBox("Enter value");
        document.getElementById('times4').focus();
        return false;
     }  */
     if(times4!='' && times4!=0){
        if(!isNumeric(times4)){
           messageBox("Enter numeric value");
           document.getElementById('times4').focus();
           return false;
        }
        if(parseInt(times4,10)>parseInt(document.getElementById('times4').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times4').alt);
           document.getElementById('times4').focus();
           return false;
        }
        if(trim(odd_prob_design.value)==''){
           messageBox("Please enter value");
           odd_prob_design.focus();
           return false;
        }
     }

    /* if(times5==''){
        messageBox("Enter value");
        document.getElementById('times5').focus();
        return false;
     }  */
     if(times5!='' && times5!=0){
        if(!isNumeric(times5)){
           messageBox("Points numeric value");
           document.getElementById('times5').focus();
           return false;
        }
        if(parseInt(times5,10)>parseInt(document.getElementById('times5').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times5').alt);
           document.getElementById('times5').focus();
           return false;
        }
        if(trim(odd_avg_marks.value)==''){
           messageBox("Please enter value");
           odd_avg_marks.focus();
           return false;
        }
     }

  /*   if(times6==''){
        messageBox("Enter value");
        document.getElementById('times6').focus();
        return false;
     }  */
     if(times6!='' && times6!=0){
        if(!isNumeric(times6)){
           messageBox("Enter numeric value");
           document.getElementById('times6').focus();
           return false;
        }
        if(parseInt(times6,10)>parseInt(document.getElementById('times6').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times6').alt);
           document.getElementById('times6').focus();
           return false;
        }
        if(trim(odd_eminent.value)==''){
           messageBox("Please enter value");
           odd_eminent.focus();
           return false;
        }
     }

     var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             even_prob_design : trim(even_prob_design.value),
             even_avg_marks : trim(even_avg_marks.value),
             even_eminent : trim(even_eminent.value),
             odd_prob_design : trim(odd_prob_design.value),
             odd_avg_marks : trim(odd_avg_marks.value),
             odd_eminent : trim(odd_eminent.value),
             times1 : times1,
             times2 : times2,
             times3 : times3,
             times4 : times4,
             times5 : times5,
             times6 : times6,
             act1_marks : act1_marks,
             act2_marks : act2_marks,
             act3_marks : act3_marks,
             act4_marks : act4_marks,
             act5_marks : act5_marks,
             act6_marks : act6_marks,
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        messageBox(trim(transport.responseText));
                        hiddenFloatingDiv('ProofDiv');
                        //updates total

						var str = parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10)+parseInt(act4_marks,10)+parseInt(act5_marks,10)+parseInt(act6_marks,10);
                        updateSelfEvaluation(math.ceil(((str/2)*100)/100));
                        //return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}


function change16_1(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act1_marks').value=0;
        return false;
     }
     document.getElementById('act1_marks').value=Math.ceil(parseInt(val,10)/2);
}

function change16_2(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act2_marks').value=0;
        return false;
     }
     document.getElementById('act2_marks').value=parseInt(val,10)*25;
}

function change16_3(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act3_marks').value=0;
        return false;
     }
     document.getElementById('act3_marks').value=parseInt(val,10)*(-10);
}


function change16_4(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act4_marks').value=0;
        return false;
     }
     document.getElementById('act4_marks').value=Math.ceil(parseInt(val,10)/2);
}

function change16_5(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act5_marks').value=0;
        return false;
     }
     document.getElementById('act5_marks').value=parseInt(val,10)*25;
}

function change16_6(val){
    var value=trim(val);
     if(val==''){
        document.getElementById('act6_marks').value=0;
        return false;
     }
     document.getElementById('act6_marks').value=parseInt(val,10)*(-10);
}

function validateProofForm16(){

     var even_advisor=document.getElementById('even_advisor');
     var even_avg_gfs=document.getElementById('even_avg_gfs');
     var even_adv_mt=document.getElementById('even_adv_mt');
     var even_indis=document.getElementById('even_indis');

     var odd_advisor=document.getElementById('odd_advisor');
     var odd_avg_gfs=document.getElementById('odd_avg_gfs');
     var odd_adv_mt=document.getElementById('odd_adv_mt');
     var odd_indis=document.getElementById('odd_indis');

     var times1=trim(document.getElementById('times1').value);
     var times2=trim(document.getElementById('times2').value);
     var times3=trim(document.getElementById('times3').value);
     var times4=trim(document.getElementById('times4').value);
     var times5=trim(document.getElementById('times5').value);
     var times6=trim(document.getElementById('times6').value);

     var act1_marks=trim(document.getElementById('act1_marks').value);
     var act2_marks=trim(document.getElementById('act2_marks').value);
     var act3_marks=trim(document.getElementById('act3_marks').value);
     var act4_marks=trim(document.getElementById('act4_marks').value);
     var act5_marks=trim(document.getElementById('act5_marks').value);
     var act6_marks=trim(document.getElementById('act6_marks').value);

     if(times1==''){
        messageBox("Enter value");
        document.getElementById('times1').focus();
        return false;
     }
     if(times1!='' && times1!=0){
        if(!isNumeric(times1)){
           messageBox("Enter numeric value");
           document.getElementById('times1').focus();
           return false;
        }
        if(parseInt(times1,10)>parseInt(document.getElementById('times1').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times1').alt);
           document.getElementById('times1').focus();
           return false;
        }
        if(trim(even_avg_gfs.value)==''){
           messageBox("Please enter value");
           even_avg_gfs.focus();
           return false;
        }
     }

     if(times2==''){
        messageBox("Enter value");
        document.getElementById('times2').focus();
        return false;
     }
     if(times2!='' && times2!=0){
        if(!isNumeric(times2)){
           messageBox("Enter numeric value");
           document.getElementById('times2').focus();
           return false;
        }
        if(parseInt(times2,10)>parseInt(document.getElementById('times2').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times2').alt);
           document.getElementById('times2').focus();
           return false;
        }
        if(trim(even_adv_mt.value)==''){
           messageBox("Please enter value");
           even_adv_mt.focus();
           return false;
        }
     }

     if(times3==''){
        messageBox("Enter value");
        document.getElementById('times3').focus();
        return false;
     }
     if(times3!='' && times3!=0){
        if(!isNumeric(times3)){
           messageBox("Enter numeric value");
           document.getElementById('times3').focus();
           return false;
        }
        if(parseInt(times3,10)>parseInt(document.getElementById('times3').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times3').alt);
           document.getElementById('times3').focus();
           return false;
        }
        if(trim(even_indis.value)==''){
           messageBox("Please enter value");
           even_indis.focus();
           return false;
        }
     }

     if(times4==''){
        messageBox("Enter value");
        document.getElementById('times4').focus();
        return false;
     }
     if(times4!='' && times4!=0){
        if(!isNumeric(times4)){
           messageBox("Enter numeric value");
           document.getElementById('times4').focus();
           return false;
        }
        if(parseInt(times4,10)>parseInt(document.getElementById('times4').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times4').alt);
           document.getElementById('times4').focus();
           return false;
        }
        if(trim(odd_avg_gfs.value)==''){
           messageBox("Please enter value");
           odd_avg_gfs.focus();
           return false;
        }
     }

     if(times5==''){
        messageBox("Enter value");
        document.getElementById('times5').focus();
        return false;
     }
     if(times5!='' && times5!=0){
        if(!isNumeric(times5)){
           messageBox("Enter numeric value");
           document.getElementById('times5').focus();
           return false;
        }
        if(parseInt(times5,10)>parseInt(document.getElementById('times5').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times5').alt);
           document.getElementById('times5').focus();
           return false;
        }
        if(trim(odd_adv_mt.value)==''){
           messageBox("Please enter value");
           odd_adv_mt.focus();
           return false;
        }
     }

     if(times6==''){
        messageBox("Enter value");
        document.getElementById('times6').focus();
        return false;
     }
     if(times6!='' && times6!=0){
        if(!isNumeric(times6)){
           messageBox("Enter numeric value");
           document.getElementById('times6').focus();
           return false;
        }
        if(parseInt(times6,10)>parseInt(document.getElementById('times6').alt,10)){
           messageBox("Points can not be greater than "+document.getElementById('times6').alt);
           document.getElementById('times6').focus();
           return false;
        }
        if(trim(odd_indis.value)==''){
           messageBox("Please enter value");
           odd_indis.focus();
           return false;
        }
     }


     var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             even_advisor : trim(even_advisor.value),
             even_avg_gfs : trim(even_avg_gfs.value),
             even_adv_mt : trim(even_adv_mt.value),
             even_indis : trim(even_indis.value),
             odd_advisor : trim(odd_advisor.value),
             odd_avg_gfs : trim(odd_avg_gfs.value),
             odd_adv_mt : trim(odd_adv_mt.value),
             odd_indis : trim(odd_indis.value),
             times1 : times1,
             times2 : times2,
             times3 : times3,
             times4 : times4,
             times5 : times5,
             times6 : times6,
             act1_marks : act1_marks,
             act2_marks : act2_marks,
             act3_marks : act3_marks,
             act4_marks : act4_marks,
             act5_marks : act5_marks,
             act6_marks : act6_marks,
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					   messageBox(trim(transport.responseText));
                        hiddenFloatingDiv('ProofDiv');
                        //updates total
						var str = parseInt(act1_marks,10)+parseInt(act2_marks,10)+parseInt(act3_marks,10)+parseInt(act4_marks,10)+parseInt(act5_marks,10)+parseInt(act6_marks,10);
						updateSelfEvaluation(math.ceil(((str/2)*100)/100));

						return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}

function changeValue17_1(val){
    var value=trim(val);
    if(value=='' || value==0){
       document.getElementById('act1_marks').value=0;
    }
    document.getElementById('act1_marks').value=value;
}
function changeValue17_2(val){
    var value=trim(val);
    if(value=='' || value==0){
       document.getElementById('act2_marks').value=0;
    }
    document.getElementById('act2_marks').value=value;
}

function changeValue17_3(val){
    var value=trim(val);
    if(value=='' || value==0){
       document.getElementById('act3_marks').value=0;
    }
    document.getElementById('act3_marks').value=value;
}

function changeValue17_4(val){
    var value=trim(val);
    if(value=='' || value==0){
       document.getElementById('act4_marks').value=0;
    }
    document.getElementById('act4_marks').value=value;
}

function changeValue17_5(val){
    var value=trim(val);
    if(parseInt(value,10)>5){
        messageBox("Please enter a value between 0 and 5");
        document.proofForm.duties1.value='';
        document.getElementById('act5_marks').value=0;
        document.proofForm.duties1.focus();
        return false;
    }
    if(value==''){
       document.getElementById('act5_marks').value=0;
    }
    document.getElementById('act5_marks').value=value*10;
}

function changeValue17_6(val){
    var value=trim(val);
    if(parseInt(value,10)>10){
        messageBox("Please enter a value between 0 and 10");
        document.proofForm.duties2.value='';
        document.getElementById('act6_marks').value=0;
        document.proofForm.duties2.focus();
        return false;
    }
    if(value==''){
       document.getElementById('act6_marks').value=0;
    }
    document.getElementById('act6_marks').value=value*5;
}

function validateProofForm17(){

     var act1=document.getElementById('act1');
     var act2=document.getElementById('act2');
     var act3=document.getElementById('act3');
     var act4=document.getElementById('act4');

     var budget1=document.getElementById('budget1');
     var budget2=document.getElementById('budget2');
     var budget3=document.getElementById('budget3');
     var budget4=document.getElementById('budget4');

     var duties1=document.getElementById('duties1');
     var duties2=document.getElementById('duties2');

     var org_duties=document.getElementById('org_duties');
     var imp_duties=document.getElementById('imp_duties');

     var act1_marks = document.getElementById('act1_marks');
     var act2_marks = document.getElementById('act2_marks');
     var act3_marks = document.getElementById('act3_marks');
     var act4_marks = document.getElementById('act4_marks');
     var act5_marks = document.getElementById('act5_marks');
     var act6_marks = document.getElementById('act6_marks');

     if(trim(act1.value)!=''){
        if(budget1.value==0){
           messageBox('Please select budget(with details)');
           budget1.focus();
           return false;
        }
     }
     if(budget1.value!=0){
         if(trim(act1.value)==''){
            messageBox('Please enter value');
            act1.focus();
            return false;
         }
     }

     if(trim(act2.value)!=''){
        if(budget2.value==0){
           messageBox('Please select budget(with details)');
           budget2.focus();
           return false;
        }
     }
     if(budget2.value!=0){
         if(trim(act2.value)==''){
            messageBox('Please enter value');
            act2.focus();
            return false;
         }
     }

     if(trim(act3.value)!=''){
        if(budget3.value==0){
           messageBox('Please select budget(with details)');
           budget3.focus();
           return false;
        }
     }
     if(budget3.value!=0){
         if(trim(act3.value)==''){
            messageBox('Please enter value');
            act3.focus();
            return false;
         }
     }

     if(trim(act4.value)!=''){
        if(budget4.value==0){
           messageBox('Please select budget(with details)');
           budget4.focus();
           return false;
        }
     }
     if(budget4.value!=0){
         if(trim(act4.value)==''){
            messageBox('Please enter value');
            act4.focus();
            return false;
         }
     }

     if(trim(org_duties.value)!=''){
       if(trim(duties1.value)==''){
          messageBox('Please enter value for duties');
          duties1.focus();
          return false;
       }
       if(!isNumeric(duties1.value)){
          messageBox('Enter numeric value');
          duties1.focus();
          return false;
       }
       if(duties1.value==0){
          messageBox('Value can not be equal to 0');
          duties1.focus();
          return false;
       }
       if(parseInt(duties1.value,10)>parseInt(duties1.alt,10)){
          messageBox('Value can not be greater than '+duties1.alt);
          duties1.focus();
          return false;
       }
     }

     if(trim(duties1.value)!='' && trim(duties1.value)!=0){
        if(trim(org_duties.value)==''){
            messageBox('Please enter value');
            org_duties.focus();
            return false;
        }
     }

     if(trim(imp_duties.value)!=''){
       if(trim(duties2.value)==''){
          messageBox('Please enter value for duties');
          duties2.focus();
          return false;
       }
       if(!isNumeric(duties2.value)){
          messageBox('Enter numeric value');
          duties2.focus();
          return false;
       }
       if(duties2.value==0){
          messageBox('Value can not be equal to 0');
          duties2.focus();
          return false;
       }

	   if(parseInt(duties2.value,10) > parseInt(duties2.alt,10)){
		  messageBox('Value can not be greater than '+duties2.alt);
          duties2.focus();
          return false;
       }
     }

     if(trim(duties2.value)!='' && trim(duties2.value)!=0){
        if(trim(imp_duties.value)==''){
            messageBox('Please enter value');
            imp_duties.focus();
            return false;
        }
     }


     var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             act1 : trim(act1.value),
             act2 : trim(act2.value),
             act3 : trim(act3.value),
             act4 : trim(act4.value),
             budget1 : (budget1.value),
             budget2 : (budget2.value),
             budget3 : (budget3.value),
             budget4 : (budget4.value),
             org_duties : trim(org_duties.value),
             imp_duties : trim(imp_duties.value),
             duties1 : trim(duties1.value),
             duties2 : trim(duties2.value),
             act1_marks : trim(act1_marks.value),
             act2_marks : trim(act2_marks.value),
             act3_marks : trim(act3_marks.value),
             act4_marks : trim(act4_marks.value),
             act5_marks : trim(act5_marks.value),
             act6_marks : trim(act6_marks.value),
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                    messageBox(trim(transport.responseText));
                    hiddenFloatingDiv('ProofDiv');
                    //updates total
                    updateSelfEvaluation(parseInt(act1_marks.value,10)+parseInt(act2_marks.value,10)+parseInt(act3_marks.value,10)+parseInt(act4_marks.value,10)+parseInt(act5_marks.value,10)+parseInt(act6_marks.value,10));
                    //return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}

function checkAllowdExtensions(value){
  //get the extension of the file
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="<?php echo implode(",",$allowedFilesForAppraisal );?>";

  var extArr=str.split(",");
  var fl=0;
  var ln=extArr.length;

  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }
  if(fl){
   return true;
  }
 else{
  return false;
 }
}

function deleteRecord(mode){
   if(mode==''){
    messageBox("No records selected for deletion");
    return false;
   }
   if(!confirm("<?php echo DELETE_CONFIRM; ?>")){
       return false;
   }

    var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/deleteProofData.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             mode : mode,
             proofId : document.getElementById('proofId').value,
             appraisalId : document.getElementById('appraisalId').value
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 var ret=trim(transport.responseText).split('~');

                 if("<?php echo DELETE;?>" == trim(ret[0])) {
                    hiddenFloatingDiv('ProofDiv');
                    //updates total
                    updateSelfEvaluation(parseInt(trim(ret[1]),10));
                    //return false;
                 }
                 else {
                    messageBox(trim(ret[0]));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}

function validateProofForm18(){
    var patent_name=trim(document.getElementById('patent_name').value);
    var cofiler1=trim(document.getElementById('cofiler1').value);
    var file1=trim(document.getElementById('patent1').value);

    var patent_granted=trim(document.getElementById('patent_granted').value);
    var cofiler2=trim(document.getElementById('cofiler2').value);
    var file2=trim(document.getElementById('patent2').value);

    if(patent_name!=''){
        if(cofiler1==''){
            messageBox("Enter co-filler names");
            document.getElementById('cofiler1').focus();
            return false;
        }
    }

    if(cofiler1!=''){
        if(patent_name==''){
            messageBox("Enter patent names");
            document.getElementById('patent_name').focus();
            return false;
        }
    }

    if(file1!=''){
       if(patent_name==''){
           messageBox("Enter patent names");
           document.getElementById('patent_name').focus();
           return false;
       }

       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('patent1').focus();
           return false;
       }
    }

    if(patent_granted!=''){
        if(cofiler2==''){
            messageBox("Enter co-filler names");
            document.getElementById('cofiler2').focus();
            return false;
        }
    }

    if(cofiler2!=''){
        if(patent_granted==''){
            messageBox("Enter patents granted");
            document.getElementById('patent_granted').focus();
            return false;
        }
    }

    if(file2!=''){
       if(patent_granted==''){
           messageBox("Enter patents granted");
           document.getElementById('patent_granted').focus();
           return false;
       }

       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('patent2').focus();
           return false;
       }
    }
    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm19(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var jname1=trim(document.getElementById('jname1').value);
    var impact1=trim(document.getElementById('impact1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var jname2=trim(document.getElementById('jname2').value);
    var impact2=trim(document.getElementById('impact2').value);
    var file2=trim(document.getElementById('proof2').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(jname1==''){
            messageBox("Enter journal name");
            document.getElementById('jname1').focus();
            return false;
        }
    }

    if(jname1!=''){
        if(impact1==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact1').focus();
            return false;
        }
    }

    if(impact1!=''){
        if(!isDecimal(impact1)){
          messageBox("Enter decimal value");
          document.getElementById('impact1').focus();
          return false;
        }
        if(parseFloat(impact1)<1){
          messageBox("Impact value can not be less than 1");
          document.getElementById('impact1').focus();
          return false;
        }
        if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }


    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(jname2==''){
            messageBox("Enter journal name");
            document.getElementById('jname2').focus();
            return false;
        }
    }

    if(jname2!=''){
        if(impact2==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact2').focus();
            return false;
        }
    }

    if(impact2!=''){
        if(!isDecimal(impact2)){
          messageBox("Enter decimal value");
          document.getElementById('impact2').focus();
          return false;
        }
        if(parseFloat(impact2)<1){
          messageBox("Impact value can not be less than 1");
          document.getElementById('impact2').focus();
          return false;
        }
        if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm20(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var jname1=trim(document.getElementById('jname1').value);
    var impact1=trim(document.getElementById('impact1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var jname2=trim(document.getElementById('jname2').value);
    var impact2=trim(document.getElementById('impact2').value);
    var file2=trim(document.getElementById('proof2').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(jname1==''){
            messageBox("Enter journal name");
            document.getElementById('jname1').focus();
            return false;
        }
    }

    if(jname1!=''){
        if(impact1==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact1').focus();
            return false;
        }
    }

    if(impact1!=''){
        if(!isDecimal(impact1)){
          messageBox("Enter decimal value");
          document.getElementById('impact1').focus();
          return false;
        }
        if(parseFloat(impact1)<.3 || parseFloat(impact1)>.9 ){
          messageBox("Impact value must be between 0.3 and 0.9");
          document.getElementById('impact1').focus();
          return false;
        }
        if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }


    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(jname2==''){
            messageBox("Enter journal name");
            document.getElementById('jname2').focus();
            return false;
        }
    }

    if(jname2!=''){
        if(impact2==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact2').focus();
            return false;
        }
    }

    if(impact2!=''){
        if(!isDecimal(impact2)){
          messageBox("Enter decimal value");
          document.getElementById('impact2').focus();
          return false;
        }
        if(parseFloat(impact2)<.3 || parseFloat(impact2)>.9 ){
          messageBox("Impact value must be between 0.3 and 0.9");
          document.getElementById('impact2').focus();
          return false;
        }
        if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}



function validateProofForm21(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var jname1=trim(document.getElementById('jname1').value);
    var impact1=trim(document.getElementById('impact1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var jname2=trim(document.getElementById('jname2').value);
    var impact2=trim(document.getElementById('impact2').value);
    var file2=trim(document.getElementById('proof2').value);

    var pub3=trim(document.getElementById('pub3').value);
    var co3=trim(document.getElementById('co3').value);
    var jname3=trim(document.getElementById('jname3').value);
    var impact3=trim(document.getElementById('impact3').value);
    var file3=trim(document.getElementById('proof3').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(jname1==''){
            messageBox("Enter journal name");
            document.getElementById('jname1').focus();
            return false;
        }
    }

    if(jname1!=''){
        if(impact1==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact1').focus();
            return false;
        }
    }

    if(impact1!=''){
        if(!isDecimal(impact1)){
          messageBox("Enter decimal value");
          document.getElementById('impact1').focus();
          return false;
        }
        if(parseFloat(impact1)<0 || parseFloat(impact1)>.3 ){
          messageBox("Impact value must be between 0 and 0.3");
          document.getElementById('impact1').focus();
          return false;
        }
        if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }


    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(jname2==''){
            messageBox("Enter journal name");
            document.getElementById('jname2').focus();
            return false;
        }
    }

    if(jname2!=''){
        if(impact2==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact2').focus();
            return false;
        }
    }

    if(impact2!=''){
        if(!isDecimal(impact2)){
          messageBox("Enter decimal value");
          document.getElementById('impact2').focus();
          return false;
        }
        if(parseFloat(impact2)<0 || parseFloat(impact2)>.3 ){
          messageBox("Impact value must be between 0 and 0.3");
          document.getElementById('impact2').focus();
          return false;
        }
        if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    if(pub3!=''){
        if(co3==''){
            messageBox("Enter co-author name");
            document.getElementById('co3').focus();
            return false;
        }
    }

    if(co3!=''){
        if(jname3==''){
            messageBox("Enter journal name");
            document.getElementById('jname3').focus();
            return false;
        }
    }

    if(jname3!=''){
        if(impact3==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact3').focus();
            return false;
        }
    }

    if(impact3!=''){
        if(!isDecimal(impact3)){
          messageBox("Enter decimal value");
          document.getElementById('impact3').focus();
          return false;
        }
        if(parseFloat(impact3)<0 || parseFloat(impact3)>.3 ){
          messageBox("Impact value must be between 0 and 0.3");
          document.getElementById('impact3').focus();
          return false;
        }
        if(pub3==''){
            messageBox("Enter publication-title");
            document.getElementById('pub3').focus();
            return false;
        }

    }

    if(file3!=''){
       if(pub3==''){
            messageBox("Enter publication-title");
            document.getElementById('pub3').focus();
            return false;
        }
       if(!checkAllowdExtensions(file3)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof3').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm22(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var jname1=trim(document.getElementById('jname1').value);
    var impact1=trim(document.getElementById('impact1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var jname2=trim(document.getElementById('jname2').value);
    var impact2=trim(document.getElementById('impact2').value);
    var file2=trim(document.getElementById('proof2').value);

    var pub3=trim(document.getElementById('pub3').value);
    var co3=trim(document.getElementById('co3').value);
    var jname3=trim(document.getElementById('jname3').value);
    var impact3=trim(document.getElementById('impact3').value);
    var file3=trim(document.getElementById('proof3').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(jname1==''){
            messageBox("Enter journal name");
            document.getElementById('jname1').focus();
            return false;
        }
    }

    if(jname1!=''){
        if(impact1==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact1').focus();
            return false;
        }
    }

    if(impact1!=''){
        if(!isDecimal(impact1)){
          messageBox("Enter decimal value");
          document.getElementById('impact1').focus();
          return false;
        }
        if(parseFloat(impact1)<0){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact1').focus();
          return false;
        }
        if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }


    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(jname2==''){
            messageBox("Enter journal name");
            document.getElementById('jname2').focus();
            return false;
        }
    }

    if(jname2!=''){
        if(impact2==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact2').focus();
            return false;
        }
    }

    if(impact2!=''){
        if(!isDecimal(impact2)){
          messageBox("Enter decimal value");
          document.getElementById('impact2').focus();
          return false;
        }
        if(parseFloat(impact2)<0){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact2').focus();
          return false;
        }
        if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    if(pub3!=''){
        if(co3==''){
            messageBox("Enter co-author name");
            document.getElementById('co3').focus();
            return false;
        }
    }

    if(co3!=''){
        if(jname3==''){
            messageBox("Enter journal name");
            document.getElementById('jname3').focus();
            return false;
        }
    }

    if(jname3!=''){
        if(impact3==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact3').focus();
            return false;
        }
    }

    if(impact3!=''){
        if(!isDecimal(impact3)){
          messageBox("Enter decimal value");
          document.getElementById('impact3').focus();
          return false;
        }
        if(parseFloat(impact3)<0){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact3').focus();
          return false;
        }
        if(pub3==''){
            messageBox("Enter publication-title");
            document.getElementById('pub3').focus();
            return false;
        }

    }

    if(file3!=''){
       if(pub3==''){
            messageBox("Enter publication-title");
            document.getElementById('pub3').focus();
            return false;
        }
       if(!checkAllowdExtensions(file3)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof3').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm23(){
    var pub=trim(document.getElementById('pub').value);
    var co=trim(document.getElementById('co').value);
    var publish=trim(document.getElementById('publish').value);
    var file=trim(document.getElementById('proof').value);

    if(pub!=''){
        if(co==''){
            messageBox("Enter co-author name");
            document.getElementById('co').focus();
            return false;
        }
    }

    if(co!=''){
        if(publish==''){
            messageBox("Enter publishing house name");
            document.getElementById('publish').focus();
            return false;
        }
    }

    if(publish!=''){
        if(pub==''){
            messageBox("Enter publication title");
            document.getElementById('pub').focus();
            return false;
        }
    }

    if(file!=''){
       if(pub==''){
            messageBox("Enter publication title");
            document.getElementById('pub').focus();
            return false;
       }
       if(!checkAllowdExtensions(file)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm24(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var publish1=trim(document.getElementById('publish1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var publish2=trim(document.getElementById('publish2').value);
    var file2=trim(document.getElementById('proof2').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(publish1==''){
            messageBox("Enter publishing house name");
            document.getElementById('publish1').focus();
            return false;
        }
    }

    if(publish1!=''){
        if(pub1==''){
            messageBox("Enter publication title");
            document.getElementById('pub1').focus();
            return false;
        }
    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication title");
            document.getElementById('pub1').focus();
            return false;
       }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }

    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(publish2==''){
            messageBox("Enter publishing house name");
            document.getElementById('publish2').focus();
            return false;
        }
    }

    if(publish2!=''){
        if(pub2==''){
            messageBox("Enter publication title");
            document.getElementById('pub2').focus();
            return false;
        }
    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication title");
            document.getElementById('pub2').focus();
            return false;
       }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm25(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var conf_name1=trim(document.getElementById('conf_name1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var conf_name2=trim(document.getElementById('conf_name2').value);
    var file2=trim(document.getElementById('proof2').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(conf_name1==''){
            messageBox("Enter conference name");
            document.getElementById('conf_name1').focus();
            return false;
        }
    }

    if(conf_name1!=''){
        if(pub1==''){
            messageBox("Enter publication title");
            document.getElementById('pub1').focus();
            return false;
        }
    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication title");
            document.getElementById('pub1').focus();
            return false;
       }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }

    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(conf_name2==''){
            messageBox("Enter conference name");
            document.getElementById('conf_name2').focus();
            return false;
        }
    }

    if(conf_name2!=''){
        if(pub2==''){
            messageBox("Enter publication title");
            document.getElementById('pub2').focus();
            return false;
        }
    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication title");
            document.getElementById('pub2').focus();
            return false;
       }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm26(){
    var pub1=trim(document.getElementById('pub1').value);
    var co1=trim(document.getElementById('co1').value);
    var jname1=trim(document.getElementById('jname1').value);
    var impact1=trim(document.getElementById('impact1').value);
    var file1=trim(document.getElementById('proof1').value);

    var pub2=trim(document.getElementById('pub2').value);
    var co2=trim(document.getElementById('co2').value);
    var jname2=trim(document.getElementById('jname2').value);
    var impact2=trim(document.getElementById('impact2').value);
    var file2=trim(document.getElementById('proof2').value);

    var pub3=trim(document.getElementById('pub3').value);
    var co3=trim(document.getElementById('co3').value);
    var jname3=trim(document.getElementById('jname3').value);
    var impact3=trim(document.getElementById('impact3').value);
    var file3=trim(document.getElementById('proof3').value);

    var pub4=trim(document.getElementById('pub4').value);
    var co4=trim(document.getElementById('co4').value);
    var jname4=trim(document.getElementById('jname4').value);
    var impact4=trim(document.getElementById('impact4').value);
    var file4=trim(document.getElementById('proof4').value);

    if(pub1!=''){
        if(co1==''){
            messageBox("Enter co-author name");
            document.getElementById('co1').focus();
            return false;
        }
    }

    if(co1!=''){
        if(jname1==''){
            messageBox("Enter journal name");
            document.getElementById('jname1').focus();
            return false;
        }
    }

    if(jname1!=''){
        if(impact1==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact1').focus();
            return false;
        }
    }

    if(impact1!=''){
        if(!isDecimal(impact1)){
          messageBox("Enter decimal value");
          document.getElementById('impact1').focus();
          return false;
        }
        if(parseFloat(impact1)<0 ){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact1').focus();
          return false;
        }
        if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(pub1==''){
            messageBox("Enter publication-title");
            document.getElementById('pub1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }


    if(pub2!=''){
        if(co2==''){
            messageBox("Enter co-author name");
            document.getElementById('co2').focus();
            return false;
        }
    }

    if(co2!=''){
        if(jname2==''){
            messageBox("Enter journal name");
            document.getElementById('jname2').focus();
            return false;
        }
    }

    if(jname2!=''){
        if(impact2==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact2').focus();
            return false;
        }
    }

    if(impact2!=''){
        if(!isDecimal(impact2)){
          messageBox("Enter decimal value");
          document.getElementById('impact2').focus();
          return false;
        }
        if(parseFloat(impact2)<0 ){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact2').focus();
          return false;
        }
        if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(pub2==''){
            messageBox("Enter publication-title");
            document.getElementById('pub2').focus();
            return false;
        }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    if(pub3!=''){
        if(co3==''){
            messageBox("Enter co-author name");
            document.getElementById('co3').focus();
            return false;
        }
    }

    if(co3!=''){
        if(jname3==''){
            messageBox("Enter journal name");
            document.getElementById('jname3').focus();
            return false;
        }
    }

    if(jname3!=''){
        if(impact3==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact3').focus();
            return false;
        }
    }

    if(impact3!=''){
        if(!isDecimal(impact3)){
          messageBox("Enter decimal value");
          document.getElementById('impact3').focus();
          return false;
        }
        if(parseFloat(impact3)<0 ){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact3').focus();
          return false;
        }
        if(pub3==''){
            messageBox("Enter publication-title");
            document.getElementById('pub3').focus();
            return false;
        }

    }

    if(file3!=''){
       if(pub3==''){
            messageBox("Enter publication-title");
            document.getElementById('pub3').focus();
            return false;
        }
       if(!checkAllowdExtensions(file3)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof3').focus();
           return false;
       }
    }


    if(pub4!=''){
        if(co4==''){
            messageBox("Enter co-author name");
            document.getElementById('co4').focus();
            return false;
        }
    }

    if(co4!=''){
        if(jname4==''){
            messageBox("Enter journal name");
            document.getElementById('jname4').focus();
            return false;
        }
    }

    if(jname4!=''){
        if(impact4==''){
            messageBox("Enter value for imapact");
            document.getElementById('impact4').focus();
            return false;
        }
    }

    if(impact4!=''){
        if(!isDecimal(impact4)){
          messageBox("Enter decimal value");
          document.getElementById('impact3').focus();
          return false;
        }
        if(parseFloat(impact4)<0 ){
          messageBox("Impact value must be greater than zero");
          document.getElementById('impact4').focus();
          return false;
        }
        if(pub4==''){
            messageBox("Enter publication-title");
            document.getElementById('pub4').focus();
            return false;
        }

    }

    if(file4!=''){
       if(pub4==''){
            messageBox("Enter publication-title");
            document.getElementById('pub4').focus();
            return false;
        }
       if(!checkAllowdExtensions(file4)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof4').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm27(){
    var workshop1=trim(document.getElementById('workshop1').value);
    var institute1=trim(document.getElementById('institute1').value);
    var test_input1=trim(document.getElementById('test_input1').value);
    var test_input2=trim(document.getElementById('test_input2').value);
    var file1=trim(document.getElementById('proof1').value);

    var workshop2=trim(document.getElementById('workshop2').value);
    var institute2=trim(document.getElementById('institute2').value);
    var test_input3=trim(document.getElementById('test_input3').value);
    var test_input4=trim(document.getElementById('test_input4').value);
    var file2=trim(document.getElementById('proof2').value);


    if(workshop1!=''){
        if(institute1==''){
            messageBox("Enter institute name");
            document.getElementById('institute1').focus();
            return false;
        }
    }

    if(institute1!=''){
        if(test_input1==''){
            messageBox("Enter held from date");
            document.getElementById('test_input1').focus();
            return false;
        }
    }

    if(test_input1!=''){
        if(test_input2==''){
            messageBox("Enter held to date");
            document.getElementById('test_input2').focus();
            return false;
        }
    }

    if(test_input2!=''){
        if(workshop1==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(workshop1==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }

    if(test_input1!='' && test_input2!=''){
      if(!dateDifference(test_input1,serverDate,'-')){
          messageBox("Held from date can not be greater than current date");
          document.getElementById('test_input1').focus();
          return false;
      }
      if(!dateDifference(test_input2,serverDate,'-')){
          messageBox("Held to date can not be greater than current date");
          document.getElementById('test_input2').focus();
          return false;
      }
      if(!dateDifference(test_input1,test_input2,'-')){
          messageBox("Held to date can not be less than held from date");
          document.getElementById('test_input2').focus();
          return false;
      }
    }


    if(workshop2!=''){
        if(institute2==''){
            messageBox("Enter institute name");
            document.getElementById('institute2').focus();
            return false;
        }
    }

    if(institute2!=''){
        if(test_input3==''){
            messageBox("Enter held from date");
            document.getElementById('test_input3').focus();
            return false;
        }
    }

    if(test_input3!=''){
        if(test_input4==''){
            messageBox("Enter held to date");
            document.getElementById('test_input4').focus();
            return false;
        }
    }

    if(test_input4!=''){
        if(workshop2==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(workshop2==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop2').focus();
            return false;
        }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    if(test_input3!='' && test_input4!=''){
      if(!dateDifference(test_input3,serverDate,'-')){
          messageBox("Held from date can not be greater than current date");
          document.getElementById('test_input3').focus();
          return false;
      }
      if(!dateDifference(test_input4,serverDate,'-')){
          messageBox("Held to date can not be greater than current date");
          document.getElementById('test_input4').focus();
          return false;
      }
      if(!dateDifference(test_input4,test_input4,'-')){
          messageBox("Held to date can not be less than held from date");
          document.getElementById('test_input4').focus();
          return false;
      }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}



function validateProofForm28(){
    var workshop1=trim(document.getElementById('workshop1').value);
    var institute1=trim(document.getElementById('institute1').value);
    var test_input1=trim(document.getElementById('test_input1').value);
    var test_input2=trim(document.getElementById('test_input2').value);
    var file1=trim(document.getElementById('proof1').value);

    var workshop2=trim(document.getElementById('workshop2').value);
    var institute2=trim(document.getElementById('institute2').value);
    var test_input3=trim(document.getElementById('test_input3').value);
    var test_input4=trim(document.getElementById('test_input4').value);
    var file2=trim(document.getElementById('proof2').value);


    if(workshop1!=''){
        if(institute1==''){
            messageBox("Enter institute name");
            document.getElementById('institute1').focus();
            return false;
        }
    }

    if(institute1!=''){
        if(test_input1==''){
            messageBox("Enter held from date");
            document.getElementById('test_input1').focus();
            return false;
        }
    }

    if(test_input1!=''){
        if(test_input2==''){
            messageBox("Enter held to date");
            document.getElementById('test_input2').focus();
            return false;
        }
    }

    if(test_input2!=''){
        if(workshop1==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop1').focus();
            return false;
        }

    }

    if(file1!=''){
       if(workshop1==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop1').focus();
            return false;
        }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }

    if(test_input1!='' && test_input2!=''){
      if(!dateDifference(test_input1,serverDate,'-')){
          messageBox("Held from date can not be greater than current date");
          document.getElementById('test_input1').focus();
          return false;
      }
      if(!dateDifference(test_input2,serverDate,'-')){
          messageBox("Held to date can not be greater than current date");
          document.getElementById('test_input2').focus();
          return false;
      }
      if(!dateDifference(test_input1,test_input2,'-')){
          messageBox("Held to date can not be less than held from date");
          document.getElementById('test_input2').focus();
          return false;
      }
    }


    if(workshop2!=''){
        if(institute2==''){
            messageBox("Enter institute name");
            document.getElementById('institute2').focus();
            return false;
        }
    }

    if(institute2!=''){
        if(test_input3==''){
            messageBox("Enter held from date");
            document.getElementById('test_input3').focus();
            return false;
        }
    }

    if(test_input3!=''){
        if(test_input4==''){
            messageBox("Enter held to date");
            document.getElementById('test_input4').focus();
            return false;
        }
    }

    if(test_input4!=''){
        if(workshop2==''){
            messageBox("Enter workshop name");
            document.getElementById('workshop2').focus();
            return false;
        }

    }

    if(file2!=''){
       if(workshop2==''){
          messageBox("Enter workshop name");
          document.getElementById('workshop2').focus();
          return false;
        }
       if(!checkAllowdExtensions(file2)){
          messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
          document.getElementById('proof2').focus();
          return false;
       }
    }

    if(test_input3!='' && test_input4!=''){
      if(!dateDifference(test_input3,serverDate,'-')){
          messageBox("Held from date can not be greater than current date");
          document.getElementById('test_input3').focus();
          return false;
      }
      if(!dateDifference(test_input4,serverDate,'-')){
          messageBox("Held to date can not be greater than current date");
          document.getElementById('test_input4').focus();
          return false;
      }
      if(!dateDifference(test_input4,test_input4,'-')){
          messageBox("Held to date can not be less than held from date");
          document.getElementById('test_input4').focus();
          return false;
      }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm29(){
    var proposal    = trim(document.getElementById('proposal').value);
    var agency      = trim(document.getElementById('agency').value);
    var test_input  = trim(document.getElementById('test_input').value);
    var costing     = trim(document.getElementById('costing').value);
    var file        = trim(document.getElementById('proof').value);

    if(proposal!=''){
        if(agency==''){
            messageBox("Enter name of agency");
            document.getElementById('agency').focus();
            return false;
        }
    }

    if(agency!=''){
        if(test_input==''){
            messageBox("Select date of submission");
            document.getElementById('test_input').focus();
            return false;
        }
    }

    if(test_input!=''){
       if(!dateDifference(test_input,serverDate,'-')){
           messageBox("Date of sumission can not be greater than current date");
           document.getElementById('test_input').focus();
           return false;
       }
       if(costing=='' || costing=='0'){
           messageBox("Select costing");
           document.getElementById('costing').focus();
           return false;
       }
    }

    if(costing!='' && costing!='0'){
        if(proposal==''){
            messageBox("Enter proposal name");
            document.getElementById('proposal').focus();
            return false;
        }

    }

    if(file!=''){
       if(proposal==''){
            messageBox("Enter proposal name");
            document.getElementById('proposal').focus();
            return false;
       }
       if(!checkAllowdExtensions(file)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}

function validateProofForm30(){
    var proposal    = trim(document.getElementById('proposal').value);
    var agency      = trim(document.getElementById('agency').value);
    var test_input  = trim(document.getElementById('test_input').value);
    var costing     = trim(document.getElementById('costing').value);
    var file        = trim(document.getElementById('proof').value);

    if(proposal!=''){
        if(agency==''){
            messageBox("Enter name of agency");
            document.getElementById('agency').focus();
            return false;
        }
    }

    if(agency!=''){
        if(test_input==''){
            messageBox("Select date of submission");
            document.getElementById('test_input').focus();
            return false;
        }
    }

    if(test_input!=''){
       if(!dateDifference(test_input,serverDate,'-')){
           messageBox("Date of sumission can not be greater than current date");
           document.getElementById('test_input').focus();
           return false;
       }
       if(costing=='' || costing=='0'){
           messageBox("Select costing");
           document.getElementById('costing').focus();
           return false;
       }
    }

    if(costing!='' && costing!='0'){
        if(proposal==''){
            messageBox("Enter proposal name");
            document.getElementById('proposal').focus();
            return false;
        }

    }

    if(file!=''){
       if(proposal==''){
            messageBox("Enter proposal name");
            document.getElementById('proposal').focus();
            return false;
       }
       if(!checkAllowdExtensions(file)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm31(){
    var project     = trim(document.getElementById('project').value);
    var agency      = trim(document.getElementById('agency').value);
    var test_input  = trim(document.getElementById('test_input').value);
    var costing     = trim(document.getElementById('costing').value);
    var add_amount  = trim(document.getElementById('add_amount').value);
    var file        = trim(document.getElementById('proof').value);

    if(project!=''){
        if(agency==''){
            messageBox("Enter name of agency");
            document.getElementById('agency').focus();
            return false;
        }
    }

    if(agency!=''){
        if(test_input==''){
            messageBox("Select starting date");
            document.getElementById('test_input').focus();
            return false;
        }
    }

    if(test_input!=''){
       if(!dateDifference(test_input,serverDate,'-')){
           messageBox("Starting date can not be greater than current date");
           document.getElementById('test_input').focus();
           return false;
       }
       if(costing=='' || costing=='0'){
           messageBox("Select costing");
           document.getElementById('costing').focus();
           return false;
       }
    }

    if(costing!='' && costing!='0'){
        if(project==''){
            messageBox("Enter project name");
            document.getElementById('project').focus();
            return false;
        }

    }

    if(add_amount==''){
       messageBox("Please enter value in addit. amt.");
       document.getElementById('add_amount').focus();
       return false;
    }

    if(!isNumeric(add_amount)){
        messageBox("Please enter numeric value in addit. amt.");
        document.getElementById('add_amount').focus();
        return false;
    }

    if(add_amount!='0'){
        if(parseFloat(add_amount) > (10000*250) ){
           messageBox("Addit. amt. can not be more than "+(10000*250));
           document.getElementById('add_amount').focus();
           return false;
        }
        if(project==''){
            messageBox("Enter project name");
            document.getElementById('project').focus();
            return false;
        }

    }

    if(file!=''){
       if(project==''){
            messageBox("Enter project name");
            document.getElementById('project').focus();
            return false;
       }
       if(!checkAllowdExtensions(file)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm32(){
    var project     = trim(document.getElementById('project').value);
    var agency      = trim(document.getElementById('agency').value);
    var test_input  = trim(document.getElementById('test_input').value);
    var costing     = trim(document.getElementById('costing').value);
    var add_amount  = trim(document.getElementById('add_amount').value);
    var file        = trim(document.getElementById('proof').value);

    if(project!=''){
        if(agency==''){
            messageBox("Enter name of agency");
            document.getElementById('agency').focus();
            return false;
        }
    }

    if(agency!=''){
        if(test_input==''){
            messageBox("Select delivery date");
            document.getElementById('test_input').focus();
            return false;
        }
    }

    if(test_input!=''){
       if(!dateDifference(test_input,serverDate,'-')){
           messageBox("Delivery date can not be greater than current date");
           document.getElementById('test_input').focus();
           return false;
       }
       if(costing=='' || costing=='0'){
           messageBox("Select costing");
           document.getElementById('costing').focus();
           return false;
       }
    }

    if(costing!='' && costing!='0'){
        if(project==''){
            messageBox("Enter project name");
            document.getElementById('project').focus();
            return false;
        }

    }

    if(add_amount==''){
       messageBox("Please enter value in addit. amt.");
       document.getElementById('add_amount').focus();
       return false;
    }

    if(!isNumeric(add_amount)){
        messageBox("Please enter numeric value in addit. amt.");
        document.getElementById('add_amount').focus();
        return false;
    }

    if(add_amount!='0'){
        if(parseFloat(add_amount) > (10000*250) ){
           messageBox("Addit. amt. can not be more than "+(10000*250));
           document.getElementById('add_amount').focus();
           return false;
        }
        if(project==''){
            messageBox("Enter project name");
            document.getElementById('project').focus();
            return false;
        }

    }

    if(file!=''){
       if(project==''){
            messageBox("Enter project name");
            document.getElementById('project').focus();
            return false;
       }
       if(!checkAllowdExtensions(file)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm33(){
    var description = trim(document.getElementById('description').value);
    var byWhom      = trim(document.getElementById('byWhom').value);
    var test_input  = trim(document.getElementById('test_input').value);
    var file        = trim(document.getElementById('proof').value);

    if(description!=''){
        if(byWhom==''){
            messageBox("Enter value");
            document.getElementById('byWhom').focus();
            return false;
        }
    }

    if(byWhom!=''){
        if(test_input==''){
            messageBox("Enter receiving date");
            document.getElementById('test_input').focus();
            return false;
        }
    }

    if(test_input!=''){
       if(!dateDifference(test_input,serverDate,'-')){
           messageBox("Receiving date can not be greater than current date");
           document.getElementById('test_input').focus();
           return false;
       }

       if(description==''){
          messageBox("Enter description");
          document.getElementById('description').focus();
          return false;
       }
    }


    if(file!=''){
       if(description==''){
            messageBox("Enter description");
            document.getElementById('description').focus();
            return false;
       }
       if(!checkAllowdExtensions(file)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}


function validateProofForm34(){
    var description1 = trim(document.getElementById('description1').value);
    var byWhom1      = trim(document.getElementById('byWhom1').value);
    var test_input1  = trim(document.getElementById('test_input1').value);
    var file1        = trim(document.getElementById('proof1').value);

    var description2 = trim(document.getElementById('description2').value);
    var byWhom2      = trim(document.getElementById('byWhom2').value);
    var test_input2  = trim(document.getElementById('test_input2').value);
    var file2        = trim(document.getElementById('proof2').value);

    if(description1!=''){
        if(byWhom1==''){
            messageBox("Enter value");
            document.getElementById('byWhom1').focus();
            return false;
        }
    }

    if(byWhom1!=''){
        if(test_input1==''){
            messageBox("Enter receiving date");
            document.getElementById('test_input1').focus();
            return false;
        }
    }

    if(test_input1!=''){
       if(!dateDifference(test_input1,serverDate,'-')){
           messageBox("Receiving date can not be greater than current date");
           document.getElementById('test_input1').focus();
           return false;
       }

       if(description1==''){
          messageBox("Enter description");
          document.getElementById('description1').focus();
          return false;
       }
    }


    if(file1!=''){
       if(description1==''){
            messageBox("Enter description");
            document.getElementById('description1').focus();
            return false;
       }
       if(!checkAllowdExtensions(file1)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof1').focus();
           return false;
       }
    }


    if(description2!=''){
        if(byWhom2==''){
            messageBox("Enter value");
            document.getElementById('byWhom2').focus();
            return false;
        }
    }

    if(byWhom2!=''){
        if(test_input2==''){
            messageBox("Enter receiving date");
            document.getElementById('test_input2').focus();
            return false;
        }
    }

    if(test_input2!=''){
       if(!dateDifference(test_input2,serverDate,'-')){
           messageBox("Receiving date can not be greater than current date");
           document.getElementById('test_input2').focus();
           return false;
       }

       if(description2==''){
          messageBox("Enter description");
          document.getElementById('description2').focus();
          return false;
       }
    }


    if(file2!=''){
       if(description2==''){
            messageBox("Enter description");
            document.getElementById('description2').focus();
            return false;
       }
       if(!checkAllowdExtensions(file2)){
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           document.getElementById('proof2').focus();
           return false;
       }
    }

    showWaitDialog(true);
    document.getElementById('proofForm').onsubmit=function() {
       document.getElementById('proofForm').target = 'uploadTargetAdd';
    }
}

function fileUploadError(message,mode){
    hideWaitDialog(true);
    hiddenFloatingDiv('ProofDiv');
    if(mode==1){
      //updates total
      updateSelfEvaluation(parseInt(message,10));
    }
    else{
      messageBox(message);
    }
}


function validateProofForm14(){
       var oddsem=trim(document.getElementById('oddsem').value);
       var evensem=trim(document.getElementById('evensem').value);
       var score_gained=trim(document.getElementById('score_gained').value);

       if(oddsem==''){
           messageBox('This field can not be empty');
           document.getElementById('oddsem').focus();
           return false;
       }
       if(evensem==''){
           messageBox('This field can not be empty');
           document.getElementById('evensem').focus();
           return false;
       }
       if(score_gained==''){
           messageBox('This field can not be empty');
           document.getElementById('score_gained').focus();
           return false;
       }

       if(!isNumeric(oddsem)){
           messageBox('Enter numeric value');
           document.getElementById('oddsem').focus();
           return false;
       }
       if(!isNumeric(evensem)){
           messageBox('Enter numeric value');
           document.getElementById('evensem').focus();
           return false;
       }
       if(!isNumeric(score_gained)){
           messageBox('Enter numeric value');
           document.getElementById('score_gained').focus();
           return false;
       }


       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 oddsem: oddsem,
                 evensem: evensem,
                 score_gained: score_gained,
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                            hiddenFloatingDiv('ProofDiv');
                            //updates total
                            updateHODEvaluation(parseInt(score_gained,10));
                            //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
    }

function validateProofForm1(){
       var internal=trim(document.getElementById('internal').value);
       var external=trim(document.getElementById('external').value);
       var copies_checked=trim(document.getElementById('copies_checked').value);
       var superValue=trim(document.getElementById('super').value);
       var weekends=trim(document.getElementById('weekends').value);
       var score_gained=trim(document.getElementById('score_gained').value);

       if(internal==''){
           messageBox('This field can not be empty');
           document.getElementById('internal').focus();
           return false;
       }
       if(external==''){
           messageBox('This field can not be empty');
           document.getElementById('external').focus();
           return false;
       }
       if(copies_checked==''){
           messageBox('This field can not be empty');
           document.getElementById('copies_checked').focus();
           return false;
       }
       if(superValue==''){
           messageBox('This field can not be empty');
           document.getElementById('super').focus();
           return false;
       }
       if(weekends==''){
           messageBox('This field can not be empty');
           document.getElementById('weekends').focus();
           return false;
       }
       if(score_gained==''){
           messageBox('This field can not be empty');
           document.getElementById('score_gained').focus();
           return false;
       }

       if(!isNumeric(internal)){
           messageBox('Enter numeric value');
           document.getElementById('internal').focus();
           return false;
       }
       if(!isNumeric(external)){
           messageBox('Enter numeric value');
           document.getElementById('external').focus();
           return false;
       }
       if(!isNumeric(copies_checked)){
           messageBox('Enter numeric value');
           document.getElementById('copies_checked').focus();
           return false;
       }
       if(!isNumeric(superValue)){
           messageBox('Enter numeric value');
           document.getElementById('super').focus();
           return false;
       }
       if(!isNumeric(weekends)){
           messageBox('Enter numeric value');
           document.getElementById('weekends').focus();
           return false;
       }
       if(!isNumeric(score_gained)){
           messageBox('Enter numeric value');
           document.getElementById('score_gained').focus();
           return false;
       }


       var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/doAppraisalForm.php';
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 internal : trim(document.getElementById('internal').value),
                 external : trim(document.getElementById('external').value),
                 copies_checked : trim(document.getElementById('copies_checked').value),
                 superValue : trim(document.getElementById('super').value),
                 weekends : trim(document.getElementById('weekends').value),
                 score_gained : trim(document.getElementById('score_gained').value),
                 proofId : document.getElementById('proofId').value,
                 appraisalId : document.getElementById('appraisalId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            messageBox(trim(transport.responseText));
                            hiddenFloatingDiv('ProofDiv');
                            //updates total
                            updateHODEvaluation(parseInt(score_gained,10));
                            //return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
    }

function listPage(path){
    window.location=path;
}

function updateMarks4_1(val1,val2,target){
   if(trim(val1)=='' && trim(val2)==''){
     target.value=0;
   }
   else{
     target.value=5;
   }
}

function updateMarks4_2(val1,val2,val3,val4,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=10;
   }

   if(trim(val2)!=''){
       oldVal +=5;
   }

   if(trim(val3)!=''){
       oldVal +=5;
   }

   if(trim(val4)!=''){
       oldVal +=5;
   }

   target.value=oldVal;

}

function updateMarks4_3(val1,val2,val3,val4,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=5;
   }

   if(trim(val2)!=''){
       oldVal +=5;
   }

   if(trim(val3)!=''){
       oldVal +=5;
   }

   if(trim(val4)!=''){
       oldVal +=5;
   }

   target.value=oldVal;
}

function updateMarks5_1(val1,val2,val3,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=10;
   }

   if(trim(val2)!=''){
       oldVal +=10;
   }

   if(trim(val3)!=''){
       oldVal +=10;
   }

   target.value=oldVal;
}

function updateMarks5_2(val1,val2,val3,val4,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=5;
   }

   if(trim(val2)!=''){
       oldVal +=5;
   }

   if(trim(val3)!=''){
       oldVal +=5;
   }

   if(trim(val4)!=''){
       oldVal +=5;
   }

   target.value=oldVal;
}


function updateMarks6_1(val1,val2,val3,val4,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=20;
   }

   if(trim(val2)!=''){
       oldVal +=20;
   }

   if(trim(val3)!=''){
       oldVal +=20;
   }

   if(trim(val4)!=''){
       oldVal +=20;
   }

   target.value=oldVal;
}


function updateMarks6_2(val1,val2,val3,val4,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=10;
   }

   if(trim(val2)!=''){
       oldVal +=10;
   }

   if(trim(val3)!=''){
       oldVal +=10;
   }

   if(trim(val4)!=''){
       oldVal +=10;
   }

   target.value=oldVal;
}


function updateMarks6_3(val1,val2,val3,val4,val5,val6,target){
   var oldVal=0;
   if(trim(val1)!=''){
       oldVal=5;
   }

   if(trim(val2)!=''){
       oldVal +=5;
   }

   if(trim(val3)!=''){
       oldVal +=5;
   }

   if(trim(val4)!=''){
       oldVal +=5;
   }

   if(trim(val5)!=''){
       oldVal +=5;
   }

   if(trim(val6)!=''){
       oldVal +=5;
   }

   target.value=oldVal;
}


function updateMarks8(val,target){
   if(trim(val)!=''){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.qty_lab.focus();
          return false;
       }
   }
   var v=parseInt(trim(val));
    if(v==0){
      target.value=0;
   }
   if(v==1){
      target.value=10;
   }
   else if(v==2){
      target.value=15;
   }
   else if(v==3){
      target.value=20;
   }
   else if(v>3){
     target.value=0;
     messageBox("No. of manuals can not be greater than 3");
     document.proofForm.qty_lab.focus();
     return false;
   }
   else{
      target.value=0;
   }
}


function updateMarks9_1(val,target){
	 if(trim(val)!='' && trim(val)!=0){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.strength_indus.focus();
          return false;
       }
	   else {
	      target.value=5;
	   }
   }
  else {
	target.value=0;
  }
}

function updateMarks9_2(val,target){
    if(trim(val)!='' && trim(val)!=0){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.strength_indus.focus();
          return false;
       }
	   else {
	      target.value=5;
	   }
   }
  else {
	target.value=0;
  }
}

function updateMarks9_3(val,target){
    if(trim(val)!='' && trim(val)!=0){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.strength_indus.focus();
          return false;
       }
	   else {
	      target.value=10;
	   }
   }
  else {
	target.value=0;
  }
}

function updateMarks9_4(val,target){
  if(trim(val)!='' && trim(val)!=0){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.strength_indus.focus();
          return false;
       }
	   else {
		  target.value=10;
	   }
   }
  else {
	target.value=0;
  }
}

function updateMarks11_1(state,target){
    if(state==true){
      target.value=5;
    }
    else{
      target.value=0;
    }
}

function updateMarks11_3(state,target){
    if(state==true){
      target.value=5;
    }
    else{
      target.value=0;
    }
}

function updateMarks11_2(val,target){
   if(trim(val)!=''){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.even_cases_count.focus();
          return false;
       }
   }
   var v=parseInt(trim(val));
   if(v==1){
      target.value=-1;
   }
   else if(v==2){
      target.value=-2;
   }
   else if(v==3){
      target.value=-3;
   }
   else if(v==4){
      target.value=-4;
   }
   else if(v==5){
      target.value=-5;
   }
   else if(v>5){
     target.value=0;
     messageBox("No. of indiscipline cases can not be greater than 5");
     document.proofForm.even_cases_count.focus();
     return false;
   }
   else{
      target.value=0;
   }
}

function updateMarks11_4(val,target){
   if(trim(val)!=''){
       if(!isNumeric(trim(val))){
          messageBox("Enter numeric values only");
          document.proofForm.odd_cases_count.focus();
          return false;
       }
   }
   var v=parseInt(trim(val));
   if(v==1){
      target.value=-1;
   }
   else if(v==2){
      target.value=-2;
   }
   else if(v==3){
      target.value=-3;
   }
   else if(v==4){
      target.value=-4;
   }
   else if(v==5){
      target.value=-5;
   }
   else if(v>5){
     target.value=0;
     messageBox("No. of indiscipline cases can not be greater than 5");
     document.proofForm.odd_cases_count.focus();
     return false;
   }
   else{
      target.value=0;
   }
}

</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Appraisal/AppraisalData/appraisalFormContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: employeeInfo.php $
?>