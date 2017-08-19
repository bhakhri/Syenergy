<?php
//-------------------------------------------------------
// Purpose: To generate Parent list for subject centric
// functionality 
//
// Author : Parveen Sharma
// Created on : (04.02.2008 )
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//define('MODULE','ParentInfo');
//define('ACCESS','edit');
//UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/ScParent/scInitData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Parent Teacher Message Detail</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
?> 
<?php
function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

// ajax search results ---start///

winLayerWidth  = 680; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//this function is uded to refresh tab data based uplon selection of study periods
function refreshMessageData(){
    
	//get the data of Parent grade card based upon selected study period
    var inboxData=refreshInboxData();

	//get the data of offence based upon selected study period
    var sentItemData=refreshSentItemData();
    
}
//this function fetches records corresponding to Parent grades detail
function refreshInboxData(){
	
  url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxInitParentMessageList.php';
  //var value=document.getElementById('searchbox').value;
   	 	
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
						new Array('messageSubject','Subject','width="12%" align="left"',true),                
						new Array('employeeName','From','width="10%" align="left"',true),
                        new Array('messageDate','Date','width="12%" align="left"',true),
						new Array('message','Message','width="37%" align="left"',true),
						new Array('attachmentFile','File','width="2%" align="left"',false),
                        new Array('action1','Action','width="4%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','messageId','ASC','LeaveTypeResultDiv','ParentTeacherActionDiv','',true,'listObj',tableColumns,'editWindow','deleteParentTeacher','');
 sendRequest(url, listObj, '')
}

//this function fetches records corresponding to Parent offence
function refreshSentItemData(){
	
  url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxInitSentItemList.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
						new Array('messageSubject','Subject','width="12%" align="left"',true),                
						new Array('employeeName','To','width="10%" align="left"',true),
                        new Array('messageDate','Date','width="12%" align="left"',true),
						new Array('message','Message','width="37%" align="left"',true),
						new Array('attachmentFile','File','width="2%" align="left"',false),
                        new Array('action1','Action','width="4%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj9 = new initPage(url,recordsPerPage,linksPerPage,1,'','offenseName','ASC','sentItemResultsDiv','','',true,'listObj9',tableColumns,'','','');
 sendRequest(url, listObj9, '')
} 

window.onload=function(){
  refreshMessageData();  
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Parveen Sharma
// Created on : (04.02.2009)
// Copyright 2009-2010 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    var fieldsArray = new Array( 
                                new Array("messageSubject","<?php echo ENTER_MESSAGE_SUBJECT; ;?>"),
                                new Array("messageText","<?php echo ENTER_MESSAGE_TEXT; ;?>")

        );
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {

            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	 
    if(document.getElementById('messageId').value=='') {
		initAdd();
		addParentTeacher();
    }
    else{
		initAdd();
		editParentTeacher();
   }
}
function initAdd() {
     //document.getElementById('addNotice').target = 'uploadTargetAdd';   
        document.getElementById('ParentTeacher').onsubmit=function() {         
        document.getElementById('ParentTeacher').target = 'uploadTargetAdd';   
    }
}

function showSentData(id,dv) {
	 
    document.getElementById('divHeaderId').innerHTML='&nbsp; Sent Message Detail';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateSentData(id);   
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FINANCIAL YEAR
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addParentTeacher() {
	
	 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxInitParentTeacherAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {
			 teacherId: trim(document.ParentTeacher.teacherId.value),
			 messageSubject: trim(document.ParentTeacher.messageSubject.value), 	 
			 messageText: trim(document.ParentTeacher.messageText.value)
		 },
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				 hideWaitDialog();
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 blankValues();
					 }
					 else {
						 hiddenFloatingDiv('ParentTeacherActionDiv');
						 refreshMessageData();
						 return false;
					 }
				 } 
				 
				 else {
					messageBox(trim(transport.responseText)); 
					document.ParentTeacher.salaryHeadTitle.focus();
				 }

		 },
		 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
	   });
}

function blankValues() {

   document.getElementById('divHeaderId').innerHTML='&nbsp; Add Parent Teacher Message';
   
  // document.ParentTeacher.teacherId.value = '';
   document.ParentTeacher.messageSubject.value = '';
   document.ParentTeacher.messageText.value = '';
   document.ParentTeacher.messageId.value = '';
   document.ParentTeacher.noticeAttachment.value = '';
   document.getElementById('editLogoPlace').style.display = 'none';
   document.getElementById('replyMessage').style.display = 'none';
   document.getElementById('replyMessage1').style.display = 'block';

   document.ParentTeacher.teacherId.disabled = false;
   document.ParentTeacher.messageSubject.disabled = false;
   document.ParentTeacher.messageText.disabled = false;
   document.ParentTeacher.attachmentFile.Readonly = false;
   //document.getElementById('rowStatus').style.display = 'block';
   document.getElementById('editLogoPlace1').style.display = 'inline';
   
   document.ParentTeacher.teacherId.focus();
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A yearLabel
//  id=yearId
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteParentTeacher(id) {

	 if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
		 return false;
	 }
	 else {   
	
	 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxInitParentTeacherDelete.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {messageId: id},
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				 hideWaitDialog();
				 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
					 refreshMessageData(); 
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
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv) {
    document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Parent Teacher Message';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}

/*function editWindow1(id,dv) {
    document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Parent Teacher Message';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues1(id);   
}
*/
 

function  download(str){    
     
    var address="<?php echo IMG_HTTP_PATH;?>/StudentMessage/"+str;
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editParentTeacher() {

	 
	 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxInitParentTeacherEdit.php';
	 
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: { 
			 
			//teacherId: trim(document.ParentTeacher.teacherId.value),
			messageSubject: trim(document.ParentTeacher.messageSubject.value), 	 
			messageText: trim(document.ParentTeacher.messageText.value),
			messageId: trim(document.ParentTeacher.messageId.value),
			senderId: trim(document.ParentTeacher.senderId.value),
			receiverId: trim(document.ParentTeacher.receiverId.value)	
				
		 },
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			
				 hideWaitDialog();
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {

					hiddenFloatingDiv('ParentTeacherActionDiv1');
					refreshMessageData();        
					return false;
				 }
			     
				 else {
					
					messageBox(trim(transport.responseText)); 
					document.ParentTeacher.salaryHeadTitle.focus();                        
				 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {

	  
	 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxGetParentTeacherValues1.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {messageId: id},
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				 hideWaitDialog();
				 if(trim(transport.responseText)==0) {
					hiddenFloatingDiv('LeaveTypeActionDiv');
					messageBox("<?php echo LEAVE_TYPE_NOT_EXIST; ?>");
					getParentTeacherData();           
			   }

			   j = eval('('+trim(transport.responseText)+')');
				
              // alert(transport.responseText);
			   document.getElementById('replyMessage').style.display = 'block';
			   document.getElementById('replyMessage1').style.display = 'none';
			  
				document.getElementById('senderName').innerHTML = trim(j.employeeName);
			   document.getElementById('senderDate').innerHTML = trim(j.messageDate);
			   //document.getElementById('senderSubject').innerHTML = trim(j.messageSubject);
			   //document.getElementById('senderText').innerHTML = trim(j.message);
			   document.getElementById('receiverId').value = trim(j.receiverId);
			   document.getElementById('senderId').value = trim(j.senderId);
			   document.getElementById('messageId').value = trim(j.messageId);

			   
			   document.ParentTeacher.messageSubject.value = "Re:"+j.messageSubject;	
			   document.ParentTeacher.messageText.value = "\n\n------Reply Text-----\n\n"+j.message;				
			  // alert(j.attachmentFile);	
			   if(j.attachmentFile){

					 document.getElementById('editLogoPlace').style.display = 'block';
					 document.getElementById('editLogoPlace').innerHTML = 
				"<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
			   }
			   else{

					 document.getElementById('editLogoPlace').innerHTML = '--';
			   }
			   /*document.ParentTeacher.teacherId.disabled = true;
			   document.ParentTeacher.messageSubject.disabled = true;
			   document.ParentTeacher.messageText.disabled = true;
			   document.ParentTeacher.attachmentFile.Readonly = true;
			   document.ParentTeacher.teacherId.value = j.receiverId;
			   document.ParentTeacher.messageSubject.value = j.messageSubject;	
			   document.ParentTeacher.messageText.value    = j.message; 
			   document.ParentTeacher.messageId.value    = j.messageId; 
			   document.ParentTeacher.attachmentFile.value    = j.attachmentFile; 
			   document.ParentTeacher.readStatus.value    = j.readStatus; 
			   document.getElementById('rowStatus').style.display = 'none';
			   document.getElementById('editLogoPlace1').style.display = 'none';*/

			  
		 },
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues1(id) {

	 

	 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxGetParentTeacherValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {messageId: id},
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				 hideWaitDialog();
				 if(trim(transport.responseText)==0) {
					hiddenFloatingDiv('LeaveTypeActionDiv');
					messageBox("<?php echo LEAVE_TYPE_NOT_EXIST; ?>");
					getParentTeacherData();           
			   }

			   j = eval('('+trim(transport.responseText)+')');
				
              //alert(transport.responseText);
			   //return false;
			   document.getElementById('receiverId1').value = trim(j.receiverId);
			   document.getElementById('senderId1').value = trim(j.senderId);
			   document.getElementById('messageId1').value = trim(j.messageId);

			   document.getElementById('senderName').innerHTML = trim(j.employeeName);
			   document.getElementById('senderDate').innerHTML = trim(j.messageDate);
			   document.ParentTeacher1.messageSubject.value = "Re:"+j.messageSubject;	
			   document.ParentTeacher1.messageText.value = "\n\n------Reply Text-----\n\n"+j.message;

			   if(j.attachmentFile){

					//alert(j.attachmentFile);
					 document.getElementById('editLogoPlace2').style.display = 'block';
					 document.getElementById('editLogoPlace2').innerHTML = 
				"<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
			   }
			   else{

					 document.getElementById('editLogoPlace2').innerHTML = '--';
			   }
			   
			   /*document.ParentTeacher1.teacherId.disabled = true;
			   document.ParentTeacher1.messageSubject.disabled = true;
			   document.ParentTeacher1.messageText.disabled = true;
			   document.ParentTeacher1.attachmentFile.Readonly = true;
			   document.ParentTeacher1.teacherId.value = j.senderId;
			   document.ParentTeacher1.messageSubject.value = j.messageSubject;	
			   document.ParentTeacher1.messageText.value    = j.message; 
			   document.ParentTeacher1.messageId.value    = j.messageId; 
			   document.ParentTeacher1.attachmentFile.value    = j.attachmentFile; 
			   document.ParentTeacher1.readStatus.value    = j.readStatus; 
			   document.getElementById('rowStatus1').style.display = 'none';
			   document.getElementById('editLogoPlace11').style.display = 'none';

			   if(j.attachmentFile){

					 document.getElementById('editLogoPlace1').style.display = 'block';
					 document.getElementById('editLogoPlace1').innerHTML = 
				"<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
			   }
			   else{
					 document.getElementById('editLogoPlace1').innerHTML = "--";
					 document.getElementById('editLogoPlace1').style.display = 'none';
					 
			   }*/
		 },
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateSentData(id) {

	 url = '<?php echo HTTP_LIB_PATH;?>/ScParent/ajaxGetParentTeacherValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {messageId: id},
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				 hideWaitDialog();
				 if(trim(transport.responseText)==0) {
					hiddenFloatingDiv('LeaveTypeActionDiv');
					messageBox("<?php echo LEAVE_TYPE_NOT_EXIST; ?>");
					getParentTeacherData();           
			   }

			   j = eval('('+trim(transport.responseText)+')');

			   //alert(transport.responseText);
			  // document.getElementById('replyMessage').style.display = 'block';
			   document.getElementById('senderNameEmployee').innerHTML = trim(j.employeeName);
			   document.getElementById('senderDateEmployee').innerHTML = trim(j.messageDate);
			   document.getElementById('senderSubjectEmployee').innerHTML = trim(j.messageSubject);
			   document.getElementById('senderMessageEmployee').innerHTML = trim(j.message);

			   if(j.attachmentFile){

					 document.getElementById('editLogoPlaceEmployee').style.display = 'block';
					 document.getElementById('editLogoPlaceEmployee').innerHTML = 
				"<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
			   }
			   else{

					 //document.getElementById('editLogoPlaceEmployee').style.display = 'none';
					 document.getElementById('editLogoPlaceEmployee').innerHTML = "--";
			   }
		 },
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ScParent/parentTeacherMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: parentTeacherMessage.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/17/09    Time: 5:02p
//Created in $/LeapCC/Interface/Parent
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 4:25p
//Created in $/Leap/Source/Interface/Parent
//initial checkin 
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 2/03/09    Time: 2:38p
//Updated in $/Leap/Source/Interface/Student
//Updated Validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/03/09    Time: 10:55a
//Updated in $/Leap/Source/Interface/Student
//Updated validations
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:26p
//Created in $/Leap/Source/Interface/Student
//Intial checkin
?>
