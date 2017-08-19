<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AuthorizeEmployeeLeave');
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
<title><?php echo SITE_NAME;?>: Authorize Employee Leaves </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var roleId="<?php echo $sessionHandler->getSessionVariable('RoleId'); ?>";
var serverDate="<?php echo date('Y-m-d'); ?>";

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('employeeCode','Employee Code','width="11%"','',true) ,
    new Array('employeeName','Employee Name','width="11%"','',true), 
    new Array('leaveTypeName','Leave Type','width="10%"','align="left"',true), 
    new Array('leaveFromDate','From','width="5%"','align="center"',true), 
    new Array('leaveToDate','To','width="5%"','align="center"',true), 
    new Array('leaveStatus','Status','width="7%"','align="left"',true),
    new Array('substitute','Substitute','width="7%"','align="left"',true),
    new Array('attachment','Attachment','width="10%"','align="center"',true),  
    new Array('actionString','Authorize','width="2%"','align="center"',false)
  );
  

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveAuthorization/ajaxLeavesForAuthorizeList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddLeaveAuthorization';   
editFormName   = 'EditLeaveAuthorization';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteLeaveAuthorization';
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

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
    populateValues(id,'EditLeaveAuthorization',winLayerWidth,winLayerHeight);   
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
   editLeaveAuthorization();
   return false;
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editLeaveAuthorization() {
         var url = '<?php echo HTTP_LIB_PATH;?>/LeaveAuthorization/ajaxDoAuthorization.php';
         var reason='';
         var leaveStaus='';
         if(document.EditLeaveAuthorization.authorizer.value==1){
             if(document.EditLeaveAuthorization.firstAuthorizeStatus.value==''){
                 messageBox("<?php echo SELECT_LEAVE_STATUS;?>");
                 document.EditLeaveAuthorization.firstAuthorizeStatus.focus();
                 return false;
             }
             if(trim(document.EditLeaveAuthorization.firstAuthorizeReason.value)==''){
                 messageBox("<?php echo ENTER_FIRST_AUTHORIZER_REASON;?>");
                 document.EditLeaveAuthorization.firstAuthorizeReason.focus();
                 return false;
             }
             reason=trim(document.EditLeaveAuthorization.firstAuthorizeReason.value);
             leaveStaus=trim(document.EditLeaveAuthorization.firstAuthorizeStatus.value);
         }
         else if (document.EditLeaveAuthorization.authorizer.value==2 &&  document.getElementById('hiddenLeaveAuthorizersId').value==2 ) {
             
             if(document.EditLeaveAuthorization.secondAuthorizeStatus.value==''){
                 messageBox("<?php echo SELECT_LEAVE_STATUS;?>");
                 document.EditLeaveAuthorization.secondAuthorizeStatus.focus();
                 return false;
             }
             if(trim(document.EditLeaveAuthorization.secondAuthorizeReason.value)==''){
                 messageBox("<?php echo ENTER_SECOND_AUTHORIZER_REASON;?>");
                 document.EditLeaveAuthorization.secondAuthorizeReason.focus();
                 return false;
             }
             reason=trim(document.EditLeaveAuthorization.secondAuthorizeReason.value);
             leaveStaus=trim(document.EditLeaveAuthorization.secondAuthorizeStatus.value);
         }
         else{
             messageBox("<?php echo NO_AUTHORIZATION_RESTRICTION ?>");
             return false;
         }
         
         if(!confirm("Are you sure to authorize this leave request ?")){
             return false;
         }
         
       /////////////////////////////////////////////////////////////////////////////////
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 mappingId   : document.EditLeaveAuthorization.mappingId.value,
                 leaveStatus : leaveStaus,
                 reason      : reason 
			},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditLeaveAuthorization');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else {
                        messageBox(trim(transport.responseText)); 
                    }
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
       /////////////////////////////////////////////////////////////////////////////////
		 
		 
		 
		 /*
		 new Ajax.Request(url
           {
		     method:'post',
             parameters: { 
                 mappingId   : document.EditLeaveAuthorization.mappingId.value,
                 leaveStatus : leaveStaus,
                 reason      : reason 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditLeaveAuthorization');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else {
                        messageBox(trim(transport.responseText)); 
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
		   */
		   
}


function populateValues(id,dv,w,h) {
         var url = '<?php echo HTTP_LIB_PATH;?>/LeaveAuthorization/ajaxGetEmployeeLeaveRecord.php';
         document.getElementById('empCodeDiv').innerHTML='';
         document.getElementById('empNameDiv').innerHTML='';
        document.getElementById('empLeaveTypeDiv').innerHTML='';
         document.getElementById('empLeaveRecordsDiv').innerHTML='';
         document.getElementById('empLeaveFromDiv').innerHTML='';
         document.getElementById('empLeaveToDiv').innerHTML='';
         document.getElementById('empLeaveStatusDiv').innerHTML='';
         document.getElementById('empLeaveReasonDiv').innerHTML='';
	 document.getElementById('empsubsDiv').innerHTML='';
         document.EditLeaveAuthorization.authorizer.value='';
         document.EditLeaveAuthorization.mappingId.value='';
         
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
                         var j = eval('('+trim(transport.responseText)+')');
			 var val=0.0;
                         var strleave = j.leaveTypeName+" ("+j.leaveDay+")";
 			 document.getElementById('empCodeDiv').innerHTML=j.employeeCode;
                         document.getElementById('empNameDiv').innerHTML=j.employeeName;
                         document.getElementById('empLeaveTypeDiv').innerHTML=strleave;
                         document.getElementById('empLeaveRecordsDiv').innerHTML=j.leaveRecord;
                         document.getElementById('empLeaveFromDiv').innerHTML=j.leaveFromDate;
			 document.getElementById('empLeaveToDiv').innerHTML=j.leaveToDate;
			if(j.leaveDay=="Full Day")
			{
			document.getElementById('empLeaveAppliedDiv').innerHTML=j.leaveAppliedFor
			}
			else
			{
			document.getElementById('empLeaveAppliedDiv').innerHTML='0.5 day';
			}
                         document.getElementById('empLeaveStatusDiv').innerHTML=j.leaveStatusName;
                         document.getElementById('empLeaveReasonDiv').innerHTML=j.reason;
			 document.getElementById('empsubsDiv').innerHTML=j.substituteEmployee;
                         if(j.authorizer==1){
                           if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {   
                             document.EditLeaveAuthorization.secondAuthorizeStatus.value='';
                             document.EditLeaveAuthorization.secondAuthorizeStatus.disabled=true;
                             document.EditLeaveAuthorization.secondAuthorizeReason.value='';
                             document.EditLeaveAuthorization.secondAuthorizeReason.disabled=true;
                           }

                           document.EditLeaveAuthorization.firstAuthorizeStatus.disabled=false;
                           document.EditLeaveAuthorization.firstAuthorizeReason.disabled=false;
                           document.EditLeaveAuthorization.firstAuthorizeStatus.value='';
                           document.EditLeaveAuthorization.firstAuthorizeReason.value=''; 
                         }
                         else if(j.authorizer==2){
                           document.EditLeaveAuthorization.firstAuthorizeStatus.value=j.leaveStatus;
                           document.EditLeaveAuthorization.firstAuthorizeStatus.disabled=true;
                           document.EditLeaveAuthorization.firstAuthorizeReason.value=j.reason1;
                           document.EditLeaveAuthorization.firstAuthorizeReason.disabled=true;
                           
                           if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {   
                             document.EditLeaveAuthorization.secondAuthorizeStatus.disabled=false;
                             document.EditLeaveAuthorization.secondAuthorizeReason.disabled=false;
                             document.EditLeaveAuthorization.secondAuthorizeStatus.value='';
                             document.EditLeaveAuthorization.secondAuthorizeReason.value='';
                           }
                         }
                         document.EditLeaveAuthorization.authorizer.value=j.authorizer;
                         document.EditLeaveAuthorization.mappingId.value=j.leaveId;
                         showAttachment = "<?php echo NOT_APPLICABLE_STRING; ?>";
			if(j.documentAttachment!='' && j.documentAttachment!=null){  
      showAttachment='<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif"  name="'+j.documentAttachment+'" onClick="download(this.name);" title="Download File" />';

                         }
			 document.getElementById('empLeaveAttachmentDiv').innerHTML=showAttachment;
				
                         displayWindow(dv,w,h); 
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}




/* function to print FeedBack Grades report*/
function printReport() {
    
	var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/authorizerEmployeeLeavePrint.php?'+qstr;
    window.open(path,"authorizerEmployeeLeavePrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printReportCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location = '<?php echo UI_HTTP_PATH;?>/authorizerEmployeeLeaveCSV.php?'+qstr;
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

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/LeaveAuthorization/listLeaveAuthorizationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    
    <SCRIPT LANGUAGE="JavaScript">
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    </SCRIPT>
</body>
</html>

<?php 
// $History: listLeaveAuthorizationAdv.php $ 
?>
