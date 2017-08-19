<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF Apply Employee Leave
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyEmployeeLeave');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==1){
	UtilityManager::ifNotLoggedIn();
}
else if($roleId==2){
	UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
	UtilityManager::ifManagementNotLoggedIn();
}
else{
	UtilityManager::ifNotLoggedIn();  
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Apply Leaves </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var roleId="<?php echo $sessionHandler->getSessionVariable('RoleId'); ?>";
var serverDate="<?php echo date('Y-m-d'); ?>";

// ajax search results -///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('employeeCode','Emp. Code','width="10%"','',true) ,
    new Array('employeeName','Emp. Name','width="15%"','',true), 
    new Array('leaveTypeName','Leave Type','width="12%"','align="left"',true), 
    new Array('leaveFromDate','From','width="7%"','align="center"',true),     
    new Array('leaveToDate','To','width="7%"','align="center"',true), 
    new Array('leaveStatus','Status','width="8%"','align="left"',true),
    new Array('substitute','Substitute','width="15%"','align="left"',true),
    new Array('attachment','Attachment','width="10%"','align="center"',true), 
    new Array('actionString','Action','width="2%"','align="center"',false)
  );
  
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddApplyLeave';   
editFormName   = 'EditApplyLeave';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteApplyLeave';
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';
var globalFL=1;
// ajax search results--end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id) {
    //displayWindow(dv,w,h);
    populateValues(id,'EditApplyLeave',winLayerWidth,winLayerHeight);   
}


function viewWindow(id) 
{
    //displayWindow(dv,w,h);
    populateValuesForViewing(id,'ViewApplyLeave',winLayerWidth,winLayerHeight);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {

        if(act=='Add') {
	       addApplyLeave();
               return false;
    }
    else if(act=='Edit') {
       		editApplyLeave();
          	return false;
    }
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addApplyLeave() {
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxInitAdd.php';

         if(trim(document.addApplyLeave1.employeeCode.value)==''){
             messageBox("Enter Employee Code");
             document.addApplyLeave1.employeeCode.focus();
             return false;
         }
         
         if(document.addApplyLeave1.employeeId.value==''){
             messageBox("<?php  echo ENTER_VALID_EMPLOYEE_INFO?>");
             document.addApplyLeave1.employeeCode.focus();
             return false;

         }
         if(document.addApplyLeave1.leaveType.value==''){
             messageBox("<?php  echo SELECT_LEAVE_TYPE; ?>");
             document.addApplyLeave1.leaveType.focus();
             return false;
         }
         /*if(!dateDifference(document.getElementById('fromDate1').value,document.getElementById('toDate1').value,'-')){
             messageBox("<?php  echo LEAVE_DATE_RESTRICTION; ?>");
             document.getElementById('fromDate1').focus();
             return false;
         }*/
      
     /*  if(!dateDifference(document.getElementById('applyDate1').value,document.getElementById('fromDate1').value,'-')){
             messageBox("<?php  echo APPLY_LEAVE_DATE_RESTRICTION1; ?>");
             document.getElementById('applyDate1').focus();
             return false;
         }
         if(!dateDifference(document.getElementById('applyDate1').value,document.getElementById('toDate1').value,'-')){
             messageBox("<?php  echo APPLY_LEAVE_DATE_RESTRICTION2; ?>");
             document.getElementById('applyDate1').focus();
             return false;
         }
     */   
	var applydte=document.getElementById('applyDate1').value;
	var fromdte=document.getElementById('fromDate1').value;
	if(applydte > fromdte)
	{
		messageBox("Application date shouldnt be greater then From date");
		return false;
		
	}
         if(trim(document.addApplyLeave1.leaveReason.value)==''){
             messageBox("<?php  echo ENTER_LEAVE_REASON;?>");
             document.addApplyLeave1.leaveReason.focus();
             return false;
         }
         var form = document.addApplyLeave1;
         var showLeave = form.leaveFormat[0].checked==true?'1':'2';
         var toDate='';
         if(showLeave=='2') {
           toDate = document.getElementById('fromDate1').value;
	   
         }
         else {
           toDate = document.getElementById('toDate1').value;
	  
         }
         
         globalFL=0; 
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId  : document.addApplyLeave1.employeeId.value,
                 leaveType   : document.addApplyLeave1.leaveType.value,
                 fromDate    : document.getElementById('fromDate1').value,
                 toDate      : toDate,
                 applyDate   : document.getElementById('applyDate1').value,
                 leaveReason : trim(document.addApplyLeave1.leaveReason.value),
		         substituteEmployee: trim(document.addApplyLeave1.substituteEmployee.value),
		         leaveFormat : showLeave ,
                 hiddenFile  : document.addApplyLeave1.documentAttachment.value 
             },
             onCreate: function() {
                //showWaitDialog(true);
             },
             onSuccess: function(transport){
                 //hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                    if(document.addApplyLeave1.documentAttachment.value!='') { 
                      initAdd(1);
                    }
                    else {
                      fileUploadError("<?php echo SUCCESS;?>",1);  
                    }
                    globalFL=1;
                 } 
                 else {
                    messageBox(trim(transport.responseText)); 
                    if(trim(transport.responseText) == "Substitute Employee Code not correct") {
                     document.addApplyLeave1.substituteEmployee.focus();    
                    } 
                    globalFL=1;
                 }
              },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A optionLabel
//  id=degreeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function cancelAppliedLeave(id) {
         if(false===confirm("Do you want to cancel this leave request ?")) {
             return false;
         }
         else {   
         	var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxInitDelete.php';
         	new Ajax.Request(url,
           	{
             		method:'post',
             		parameters: {mappingId: id},
             		onCreate: function() {
                 	showWaitDialog(true);
             	        },
             	onSuccess: function(transport){
                hideWaitDialog(true);
                if("<?php echo SUCCESS;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                 }
                 else {
                      messageBox(trim(transport.responseText));                         
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}



//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddApplyLeave" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function blankValues() {
  document.addApplyLeave1.leaveType.options.length=1;     
  if(roleId==1){
     document.addApplyLeave1.employeeCode.value='';
     document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>";
     //document.AddApplyLeave.leaveStatus.value='';
     document.addApplyLeave1.employeeCode.focus();
  }
  else{
     document.addApplyLeave1.employeeCode.value="<?php echo $sessionHandler->getSessionVariable('EmployeeCode');?>";
     document.addApplyLeave1.employeeId.value="<?php echo $sessionHandler->getSessionVariable('EmployeeId');?>";
     document.getElementById('employeeName').innerHTML="<?php echo $sessionHandler->getSessionVariable('EmployeeName');?>";  
     document.addApplyLeave1.employeeCode.disabled=true;
     getEmployeeLeaveTypes(document.addApplyLeave1.employeeId.value,1);
  }
  document.addApplyLeave1.leaveReason.value='';
  document.addApplyLeave1.substituteEmployee.value='';
  document.getElementById('fromDate1').value=serverDate;
  document.getElementById('toDate1').value=serverDate;
  document.getElementById('applyDate1').value=serverDate;
  document.addApplyLeave1.leaveReason.value='';
  document.addApplyLeave1.documentAttachment.value='';
  document.addApplyLeave1.leaveFormat[0].checked=true; 
  getLeaveStatus('1','A');
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editApplyLeave() {
 	 
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxInitEdit.php';
         
         if(document.editApplyLeave1.leaveType.value==''){
             messageBox("<?php  echo SELECT_LEAVE_TYPE; ?>");
             document.editApplyLeave1.leaveType.focus();
             return false;
         }
         
         if(!dateDifference(document.getElementById('fromDate2').value,document.getElementById('toDate2').value,'-')){
             messageBox("<?php  echo LEAVE_DATE_RESTRICTION; ?>");
             document.getElementById('fromDate2').focus();
             return false;
         }
      
      /* if(!dateDifference(document.getElementById('applyDate2').value,document.getElementById('fromDate2').value,'-')){
             messageBox("<?php  echo APPLY_LEAVE_DATE_RESTRICTION1; ?>");
             document.getElementById('fromDate2').focus();
             return false;
         }
         if(!dateDifference(document.getElementById('applyDate2').value,document.getElementById('toDate2').value,'-')){
             messageBox("<?php  echo APPLY_LEAVE_DATE_RESTRICTION2; ?>");
             document.getElementById('toDate2').focus();
             return false;
         }
      */     
      
         var form = document.editApplyLeave1;
         var showLeave = form.leaveFormat[0].checked==true?'1':'2';
         var toDate='';
         if(showLeave=='2') {
           toDate = document.getElementById('fromDate2').value;
         }
         else {
           toDate = document.getElementById('toDate2').value;  
         }
         
         globalFL=0;  
         
         new Ajax.Request(url,
         {
             method:'post',
             parameters: { mappingId   : document.editApplyLeave1.mappingId.value,
                           leaveType   : document.editApplyLeave1.leaveType.value,
                           fromDate    : document.getElementById('fromDate2').value,
                           toDate      : toDate,
                           applyDate   : document.getElementById('applyDate2').value,
                           leaveReason : trim(document.editApplyLeave1.leaveReason.value),
		                   leaveFormat : showLeave,
                           substituteEmployee: trim(document.editApplyLeave1.substituteEmployee.value),
                           hiddenFile  : document.editApplyLeave1.documentAttachment.value 
                         },
             onCreate: function() {
                // showWaitDialog(true);
             },
             onSuccess: function(transport){
                 
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                    if(document.editApplyLeave1.documentAttachment.value!='') { 
                      initAdd(2);
                    }
                    else {
                      fileUploadError("<?php echo SUCCESS;?>",2);  
                    }
                 } 
                 else {
                    messageBox(trim(transport.responseText)); 
                    if(trim(transport.responseText) == "Substitute Employee Code not correct") {
                      document.editApplyLeave1.substituteEmployee.focus();    
                    } 
                    globalFL=1;
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

function getLeaveRecord(mode) {
     var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetValues1.php';
    
     document.getElementById('leaveRecordAdd').style.display=''; 
     document.getElementById('emplLeaveRecordAddDiv').innerHTML = '&nbsp;&nbsp;&nbsp;<?php echo NOT_APPLICABLE_STRING; ?>';
     
     if(mode=='Add') {
       employeeId = document.addApplyLeave1.employeeId.value;
       leaveType  = document.addApplyLeave1.leaveType.value;
     }
     else {
       employeeId = document.editApplyLeave1.employeeId.value;
       leaveType  = document.editApplyLeave1.leaveType.value;
     }
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters:{ employeeId: employeeId,
                      leaveType: leaveType },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             var j = eval('('+trim(transport.responseText)+')');
             if(j.leaveRecord!='<?php echo NOT_APPLICABLE_STRING; ?>') {
               document.getElementById('emplLeaveRecordAddDiv').innerHTML = '&nbsp;&nbsp;'+j.leaveRecord;
             }
         },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditApplyLeave" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetValues.php';
         document.editApplyLeave1.employeeCode.value  = '';
         document.getElementById('employeeName2').innerHTML = '';
         document.getElementById('fromDate1').value         = serverDate;
         document.getElementById('toDate2').value           = serverDate;
         document.getElementById('applyDate2').value        = serverDate;
         document.editApplyLeave1.leaveType.options.length   = 1;
         document.editApplyLeave1.leaveType.value            = '';
         document.editApplyLeave1.leaveReason.value          = '';
         document.editApplyLeave1.substituteEmployee.value   = ''; 
         document.editApplyLeave1.mappingId.value            = '';
	//document.getElementById('leaveRecordEdit').style.display=''; 
         document.getElementById('emplLeaveRecordDiv').innerHTML = '';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mappingId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        messageBox("<?php echo EMPLOYEE_LEAVE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                     }
                     else if(trim(transport.responseText)=="<?php echo EMPLOYEE_LEAVE_CAN_NOT_MOD_DEL; ?>"){
                       messageBox("<?php echo EMPLOYEE_LEAVE_CAN_NOT_MOD_DEL; ?>");
                     }
                    else{
                         displayWindow(dv,w,h); 
                         var j = eval('('+trim(transport.responseText)+')');
                         //document.editApplyLeave.leaveType.value            = j.leaveType;
                         document.editApplyLeave1.employeeCode.value         = j.employeeCode;
                         document.getElementById('employeeName2').innerHTML = j.employeeName;
                         document.getElementById('fromDate2').value         = j.leaveFromDate;
                         document.getElementById('toDate2').value           = j.leaveToDate;
                         document.getElementById('applyDate2').value        = j.applicateDate;
                         document.editApplyLeave1.leaveFormat[0].checked=true; 
                         if(j.leaveFormat==2) {
                           document.editApplyLeave1.leaveFormat[1].checked=true;
                         }
                         getLeaveStatus(j.leaveFormat,'E');
                         getEmployeeLeaveTypes(j.employeeId,2);
                         document.editApplyLeave1.leaveType.value            = j.leaveTypeId
                         document.editApplyLeave1.leaveReason.value          = j.reason;
                         document.editApplyLeave1.mappingId.value            = j.leaveId;
                         document.editApplyLeave1.substituteEmployee.value   = j.substituteEmployee;    
                         document.getElementById('emplLeaveRecordDiv').innerHTML = j.leaveRecord;
                         document.editApplyLeave1.documentAttachment.value = '';
                         document.getElementById('downloadFileName').value = '';
                         if(j.documentAttachment=='' || j.documentAttachment==null){
                           document.getElementById('editLogoPlace').style.display = 'none';
                         }
                         else{
                           document.getElementById('downloadFileName').value = j.documentAttachment;
                           document.getElementById('editLogoPlace').style.display = '';
                         }
                         document.getElementById('uploadIconLabel').innerHTML='';
                         if(j.documentAttachment!='' && j.documentAttachment!=null){  
                            document.getElementById('uploadIconLabel').innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/delete.gif"  onclick="deatach('+j.leaveId+');" title="Delete Uploaded File" />';
                         }
                         document.editApplyLeave1.documentAttachment.value = '';  
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}



function populateValuesForViewing(id,dv,w,h) {
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetValuesForViewing.php';
         document.getElementById('emplCodeDiv').innerHTML='';
         document.getElementById('emplNameDiv').innerHTML='';
         document.getElementById('emplLeaveTypeDiv').innerHTML='';
         document.getElementById('emplLeaveFromDiv').innerHTML='';
         document.getElementById('emplLeaveToDiv').innerHTML='';
         document.getElementById('emplLeaveRecordDiv1').innerHTML='';
         document.getElementById('emplLeaveReasonDiv').innerHTML='';
         document.getElementById('emplLeaveApplicationDateDiv').innerHTML='';
         document.getElementById('emplLeaveStatusDiv').innerHTML='';
         document.getElementById('firstCommentsDiv').innerHTML='';   
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
            document.getElementById('secondCommentsDiv').innerHTML='';
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mappingId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        messageBox("<?php echo EMPLOYEE_LEAVE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                     }
                    else{
			             var val=0.0;
                         var j = eval('('+trim(transport.responseText)+')');
                         var strleave = j.leaveTypeName+" ("+j.leaveDay+")";
                         document.getElementById('emplCodeDiv').innerHTML=j.employeeCode;
                         document.getElementById('emplNameDiv').innerHTML=j.employeeName;
                         document.getElementById('emplLeaveTypeDiv').innerHTML=strleave;
                         document.getElementById('emplLeaveFromDiv').innerHTML=j.leaveFromDate;
			             document.getElementById('emplLeaveRecordDiv1').innerHTML=j.leaveRecord;
			             document.getElementById('emplLeaveToDiv').innerHTML=j.leaveToDate;
                         document.getElementById('emplLeaveReasonDiv').innerHTML=j.reason;
                         document.getElementById('emplLeaveApplicationDateDiv').innerHTML=j.applicateDate;
                         document.getElementById('emplLeaveStatusDiv').innerHTML=j.leaveStatus;
                         document.getElementById('firstCommentsDiv').innerHTML=j.reason1;
                         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
                           document.getElementById('secondCommentsDiv').innerHTML=j.reason2;
                         }
			showAttachment = "<?php echo NOT_APPLICABLE_STRING; ?>";
			if(j.documentAttachment!='' && j.documentAttachment!=null){  
      showAttachment='<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif"  name="'+j.documentAttachment+'" onClick="download(this.name);" title="Download File" />';

                         }
			 document.getElementById('emplLeaveAttachmentDiv').innerHTML=showAttachment;
				
                         displayWindow(dv,w,h); 
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeCode(val) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetEmployeeCode.php';
         document.addApplyLeave1.employeeId.value='';
         document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>";
         if(trim(val)==''){
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {val: val},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        document.addApplyLeave1.employeeId.value='';
                        document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>"; 
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        document.addApplyLeave1.employeeId.value           = j.employeeId;
                        document.getElementById('employeeName').innerHTML = j.employeeName;
                        getEmployeeLeaveTypes(j.employeeId,1);
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeLeaveTypes(val,mode) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetEmployeeLeaveTypes.php';
         if(mode==1){
            document.addApplyLeave1.leaveType.options.length=1;
         }
         else{
            document.editApplyLeave1.leaveType.options.length=1;
         }
         
         if(trim(val)==''){
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {val: val},                     
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
         
                     }
                     else{
                        var j = eval('('+trim(transport.responseText)+')');
                        var len=j.length;
                        for(var i=0;i<len;i++){
                            if(mode==1){
                                var objOption = new Option(j[i].leaveTypeName,j[i].leaveTypeId);
                                document.addApplyLeave1.leaveType.options.add(objOption);
                            }
                            else{
                               var objOption = new Option(j[i].leaveTypeName,j[i].leaveTypeId);
                               document.editApplyLeave1.leaveType.options.add(objOption);
                            }
                        }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function initAdd(mode) {
    //document.getElementById('addNotice').target = 'uploadTargetAdd';
    showWaitDialog(true);
    if(mode==1){
        document.getElementById('addApplyLeave1').target = 'uploadTargetAdd';
        document.getElementById('addApplyLeave1').action= "<?php echo HTTP_LIB_PATH;?>/ApplyLeave/fileUpload.php"
        document.getElementById('addApplyLeave1').submit();
    }
   else{
      document.getElementById('editApplyLeave1').target = 'uploadTargetEdit';
      document.getElementById('editApplyLeave1').action= "<?php echo HTTP_LIB_PATH;?>/ApplyLeave/fileUpload.php"
      document.getElementById('editApplyLeave1').submit();
   }
}


function fileUploadError(str,mode){
   hideWaitDialog(true);
   globalFL=1;
   if("<?php echo SUCCESS;?>" != trim(str)) {
       messageBox(trim(str));
   }
   if(mode==1){
      if("<?php echo SUCCESS;?>" == trim(str)) {
         flag = true;
         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
            blankValues();
         }
         else {
            hiddenFloatingDiv('AddApplyLeave');
            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
            return false;
         }
      }
   }
   else if(mode==2){
      if("<?php echo SUCCESS;?>" == trim(str)) {
          hiddenFloatingDiv('EditApplyLeave');
          sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
          return false;
      }
   }
   else{
      messageBox(trim(str));
   }
}

function  download1(){

   var address="<?php echo IMG_HTTP_PATH;?>/EmployeeLeave/"+document.getElementById('downloadFileName').value;
//   window.location = address;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
   return false;
}

function  download(str){
    var address="<?php echo IMG_HTTP_PATH;?>/EmployeeLeave/"+str;
	//alert(address);
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}


/* function to print FeedBack Grades report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/leaveSetMappingReportPrint.php?'+qstr;
    window.open(path,"ApplyLeaveReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='leaveSetMappingReportCSV.php?'+qstr;
}

function getLeaveStatus(str,leaveMode) {
 
   if(leaveMode=='A') { 
     document.getElementById("hiddenLeaveFormatA1").style.display=''; 
     document.getElementById("hiddenLeaveFormatA2").style.display=''; 
     document.getElementById("hiddenLeaveFormatA3").style.display=''; 
   }
   else {
     document.getElementById("hiddenLeaveFormatE1").style.display=''; 
     document.getElementById("hiddenLeaveFormatE2").style.display=''; 
     document.getElementById("hiddenLeaveFormatE3").style.display='';   
   }
   if(str=='2') {
     if(leaveMode=='A') {   
       document.getElementById("hiddenLeaveFormatA1").style.display='none'; 
       document.getElementById("hiddenLeaveFormatA2").style.display='none'; 
       document.getElementById("hiddenLeaveFormatA3").style.display='none'; 
     }
     else {
       document.getElementById("hiddenLeaveFormatE1").style.display='none'; 
       document.getElementById("hiddenLeaveFormatE2").style.display='none'; 
       document.getElementById("hiddenLeaveFormatE3").style.display='none';   
     }
   } 
   return false;
}
function deatach(id){
        if(false===confirm("Do you want to delete this file?")) {
              return false;
        }
        else {
              var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxDeleteUploadedFile.php';
              new Ajax.Request(url,
              {
               method:'post',
               parameters: {
                  leaveId: id
               },
              onCreate: function() {
                  showWaitDialog(true);
               },
             onSuccess: function(transport){
                       hideWaitDialog(true);
                       if("<?php echo DELETE;?>"==trim(transport.responseText)) {

                        // messageBox("File Deleted");
                        // document.getElementById('uploadIconLabel').innerHTML='';
                         //document.getElementById('uploadIconLabel2').innerHTML='';
						document.getElementById('editLogoPlace').style.display = 'none';
                       // hiddenFloatingDiv('EditNoticeDiv');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
          });
         }
//alert(document.editNotice.noticeId.value);
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ApplyLeave/listApplyLeaveContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    
    <SCRIPT LANGUAGE="JavaScript">
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    </SCRIPT>
</body>
</html>

<?php 
// $History: listApplyLeaveAdv.php $ 
?>
