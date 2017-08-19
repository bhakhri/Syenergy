<?php
//-------------------------------------------------------
// Purpose: To generate student list for subject centric
// functionality
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAssignment');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/ScStudent/scInitData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Teacher Assignment Detail</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');

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

	//get the data of student grade card based upon selected study period
    var inboxData=refreshInboxData();


}
//this function fetches records corresponding to student grades detail
function refreshInboxData(){

  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentAssigmentList.php';
  //var value=document.getElementById('searchbox').value;

  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false),
						new Array('topicTitle','Topic','width="11%" align="left"',true),
						new Array('topicDescription','Description','width="35%" align="left"',true),
                        new Array('assignedOn','Assigned','width="6%" align="left"',true),
						new Array('tobeSubmittedOn','Due','width="6%" align="left"',true),
						new Array('replyAttachmentFile','Reply Attach.','width="8%" align="left"',false),
                        new Array('action1','Action','width="4%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','messageId','ASC','LeaveTypeResultDiv','StudentTeacherActionDiv','',true,'listObj',tableColumns,'editWindow','deleteStudentTeacher','');
 sendRequest(url, listObj, '')
}



window.onload=function(){
  refreshMessageData();
}

//This function is used to convert the month in 3 letter format
//to corresponding number in 2 digit format
function getNumberFromMonth(strTempMonth)
{
    var tempMonth;  
    switch(strTempMonth)
    {
       case 'Jan':
       tempMonth="01";
           break;
           case 'Feb':
       tempMonth="02";
           break;
           case 'Mar':
       tempMonth="03";
           break;
           case 'Apr':
       tempMonth="04";
           break;
           case 'May':
       tempMonth="05";
           break;
           case 'Jun':
       tempMonth="06";
           break;
           case 'Jul':
       tempMonth="07";
           break;
           case 'Aug':
       tempMonth="08";
           break;
           case 'Sep':
       tempMonth="09";
           break;
           case 'Oct':
       tempMonth="10";
           break;
           case 'Nov':
       tempMonth="11";
           break;
           case 'Dec':
       tempMonth="12";
           break;
       
    }
    return tempMonth;
}


function checkFileExtensionsUpload(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);

      var extArr = new Array('gif','jpg','jpeg','png','ppt','doc','docx','txt','pdf','xls','xlsx','ppt','pptx','pps');

      var fl=0;
      var ln=extArr.length;
      
      for(var i=0; i <ln; i++){
          if(val.toUpperCase()==extArr[i].toUpperCase()){
              fl=1;
              break;
          }
      }
      
      if(fl==1){
        return true;
      }
      else{
        return false;
      }   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {

    var dueDate=document.getElementById('hiddenDueDate').value;
    var fieldsArray = new Array(
        new Array("messageText","<?php echo ENTER_ASSIGNMENT_TEXT; ;?>")
    );
    var maximumsize=<?php echo MAXIMUM_FILE_SIZE; ?>;
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    if(trim(document.getElementById('noticeAttachment').value)=='') {
      messageBox("Please attach the assignment");  
      document.getElementById('noticeAttachment').focus();
	   return false; 
        
    }//==============================check size of file ===========
	else if(document.getElementById('noticeAttachment').files[0].size > maximumsize){
		messageBox("File NOT UPLOADED. File Size cannot be greater than "+maximumsize/1024+"kb");  
		document.getElementById('noticeAttachment').focus();
		 return false; 
	}
	//===============================================================================
    if(!checkFileExtensionsUpload(document.getElementById('noticeAttachment').value)){
      messageBox("<?php echo "File NOT UPLOADED because ".INCORRECT_FILE_EXTENSION; ?>");
      document.getElementById('noticeAttachment').focus();
      return false;
    } 
    var tempDueDate=dueDate.split('-');  
    var submittedOn;   
    tempDueDateMonth=getNumberFromMonth(tempDueDate[1]);
    dueDate="20"+tempDueDate[2]+"-"+tempDueDateMonth+"-"+tempDueDate[0];
    submittedOn="<?php echo date('Y-m-d');?>"    
    if(dateCompare(submittedOn,dueDate)>0)
    {
        alert("The assignment submission date is over");
        return false;
    }

 	 initAdd();
	 editStudentTeacher();
   }

function initAdd() {
//document.getElementById('addNotice').target = 'uploadTargetAdd';
    document.getElementById('StudentTeacher').onsubmit=function() {
        document.getElementById('StudentTeacher').target = 'uploadTargetAdd';
    }
}


function blankValues() {

   document.getElementById('divHeaderId').innerHTML='&nbsp; Add Student Teacher Message';
   document.StudentTeacher.messageSubject.value = '';
   document.StudentTeacher.messageText.value = '';
   document.StudentTeacher.messageId.value = '';
   document.StudentTeacher.noticeAttachment.value = '';
   document.getElementById('editLogoPlace').style.display = 'none';
   document.getElementById('editLogoPlace1').style.display = 'inline';
   document.StudentTeacher.teacherId.focus();
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
    document.getElementById('divHeaderId').innerHTML='&nbsp; Upload Assignment';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);
}

function  download(str){

	var address="<?php echo IMG_HTTP_PATH;?>/TeacherAssignment/"+str;
	window.open(address,"Attachment","status=1,resizable=1,width=800,height=600");
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editStudentTeacher() {

	 url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentAssignmentEdit.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {

			messageText: trim(document.StudentTeacher.messageText.value),
			assignmentId: trim(document.StudentTeacher.assignmentId.value),
			assignmentDetailId: trim(document.StudentTeacher.assignmentDetailId.value)

		 },
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){

				 hideWaitDialog();
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {

					hiddenFloatingDiv('StudentTeacherActionDiv');
					refreshMessageData();
					return false;
				 }

				 else {

					messageBox(trim(transport.responseText));
					document.StudentTeacher.salaryHeadTitle.focus();
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

	 sendData = id.split('~');
	 // alert(sendData);die();
	 //alert(sendData[1]);
	 url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetStudentAssignmentValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {assignmentDetailId: sendData[0],assignmentType: sendData[1]},
		 onCreate: function() {
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
				 hideWaitDialog();
				 if(trim(transport.responseText)==0) {
					hiddenFloatingDiv('StudentTeacherActionDiv');
					messageBox("This assignment does not exists");
					getStudentTeacherData();
			   }
				var j = eval('('+trim(transport.responseText)+')');
				//-----calculating current date and due date of assignment-------
				var dueDate=trim(j.tobeSubmittedOn);
				var tempDueDate=dueDate.split('-');  
				var submittedOn;   
				tempDueDateMonth=getNumberFromMonth(tempDueDate[1]);
				dueDate="20"+tempDueDate[2]+"-"+tempDueDateMonth+"-"+tempDueDate[0];
				submittedOn="<?php echo date('Y-m-d');?>"    
				//--------end of date calcilation------------------
			   

			   document.getElementById('updateRecord').style.display = 'none';
			   document.getElementById('senderName').innerHTML = trim(j.employeeName);
			   document.getElementById('senderDate').innerHTML = trim(j.assignedOn);
			   document.getElementById('dueDate').innerHTML = trim(j.tobeSubmittedOn);
			   document.getElementById('assignmentTopic').innerHTML = trim(j.topicTitle);
			   document.getElementById('assignmentDescription').innerHTML = trim(j.topicDescription);
               document.getElementById('hiddenDueDate').value=trim(j.tobeSubmittedOn);
               document.getElementById('hiddenSubmittedDate').value=trim(j.submittedOn);
			   if(j.attachmentFile){
					 document.getElementById('editLogoPlace').style.display = 'block';
					 document.getElementById('editLogoPlace').innerHTML =
				"<a href='<?php echo IMG_HTTP_PATH?>/TeacherAssignment/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif' title='Attachment'></a>";
			   }
			   else{

					 document.getElementById('editLogoPlace').innerHTML = '&nbsp;--';
			   }
			   document.getElementById('assignmentDetailId').value = trim(j.assignmentDetailId);
			   document.getElementById('assignmentId').value = trim(j.assignmentId);
			   //==================checking remarks added r not===========================
			   if(j.studentRemarks){
				   document.getElementById('messageText').value = trim(j.studentRemarks);
				   //document.getElementById('messageText').disabled=true;

				   document.getElementById('repliedRow').style.display = '';

					document.getElementById('repliedOn').innerHTML = trim(j.submittedOn);
				   
					// document.getElementById('editRecord').style.display = 'none';

			   }
			   else{

				   document.getElementById('messageText').value = "";
				   document.getElementById('messageText').disabled=false;

					// document.getElementById('editLogoPlace1').style.display = '';
					// document.getElementById('editLogoPlace2').innerHTML = '';
					
					document.getElementById('repliedRow').style.display = 'none';
				  /* if(j.replyAttachmentFile){

						 document.getElementById('editLogoPlace1').style.display = 'none';
						 document.getElementById('editLogoPlace2').innerHTML =
					"<a href='<?php echo IMG_HTTP_PATH?>/TeacherAssignment/"+j.replyAttachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/file.gif'></a>";
				   }
				   else{

						 document.getElementById('editLogoPlace2').innerHTML = '&nbsp;--';
				   }*/
					document.getElementById('editRecord').style.display = '';
			   }
			   //==================================end remarks check============================
			   //==================interchange the file upload tag===================================
			   if(document.getElementById('editLogoPlace1').innerHTML == "")
			   {
				document.getElementById('editLogoPlace1').innerHTML=document.getElementById('update').innerHTML;
				document.getElementById('update').innerHTML = "";
			   }
			   //========================end of interchange======================================
			    //==================checking file uploaded added r not===========================
				if(j.replyAttachmentFile){
						
						 document.getElementById('editRecord').style.display = 'none';
						 document.getElementById('editLogoPlace1').style.display = 'none';
						 document.getElementById('editLogoPlace2').style.display = '';
						 document.getElementById('editLogoPlace2').innerHTML =
					"<a href='<?php echo IMG_HTTP_PATH?>/TeacherAssignment/"+j.replyAttachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/file.gif' title='Assignment'></a>";
						//-------------show only if due date not over---------------------
						if(dateCompare(submittedOn,dueDate)<=0){
							document.getElementById('update').innerHTML = document.getElementById('editLogoPlace1').innerHTML;
							document.getElementById('editLogoPlace1').innerHTML = "";
							document.getElementById('updateRecord').style.display = '';
							document.getElementById('editRecord').style.display = '';
						}
						//----------------------------------------------------
				}
				else{

						 document.getElementById('editLogoPlace1').style.display = '';
						 document.getElementById('noticeAttachment').value = "";
						 document.getElementById('editRecord').style.display = '';
						 document.getElementById('editLogoPlace2').style.display = 'none';
				}
				//======================end check file uploaded=================================
		 },
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentAllocatedTaskContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: studentAllocatedTask.php $
?>
