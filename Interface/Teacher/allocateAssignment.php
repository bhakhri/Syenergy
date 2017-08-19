<?php
//-------------------------------------------------------
// Purpose: To generate student list for subject centric
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AllocateAssignment');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
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
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
 return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
recordsPerPageTeacher = <?php echo RECORDS_PER_PAGE_TEACHER;?>;


// ajax search results ---start///
winLayerWidth  = 770; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//this function is uded to refresh tab data based uplon selection of study periods
isProcessing = false;
function doProcessingF() {
	isProcessing = true;
}
function doProcessingT() {
	isProcessing = false;
}
function checkProcessing(str) {
	return isProcessing;
}

function refreshMessageData(){
   var inboxData=refreshInboxData();
}

function refreshInboxData(){

  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitTeacherAssignmentList.php';
  var search=trim(document.searchForm2.searchbox.value);
  var tableColumns = new Array(

	new Array('srNo','#','width="2%" align="left"',false),
	new Array('topicTitle','Topic','width="15%" align="left"',true),
	new Array('topicDescription','Description','width="30%" align="left"',true),
	new Array('assignedOn','Assigned','width="7%" align="left"',true),
	new Array('tobeSubmittedOn','Due Date','width="8%" align="left"',true),
	new Array('addedOn','Added','width="6%" align="left"',true),
	new Array('totalAssignment','Total','width="6%" align="right"',true),
	new Array('isVisible','Visible','width="7%" align="left"',true),
	new Array('action1','Action','width="4%" align="center"',false)
   );

 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','assignmentId','ASC','LeaveTypeResultDiv','StudentTeacherActionDiv','',true,'listObj',tableColumns,'editWindow','deleteStudentTeacher','&search='+search);
 sendRequest(url, listObj, '')
}

window.onload=function(){

	refreshMessageData();
}


function checkAllowdExtensions(value){
  //get the extension of the file
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="<?php echo implode(",",$allowedExtensionsArray );?>";

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

function blankValues() {

   document.getElementById('divHeaderId').innerHTML='&nbsp; Assign Task';
   document.getElementById('showListDisplay').style.display='';
   document.searchForm.reset();
   document.searchForm.subject.value='';
   document.searchForm.group.value='';
   document.searchForm.classId.value='';
   document.searchForm.assignmentId.value='';
   document.getElementById('results').innerHTML ='';
   document.getElementById('editLogoPlace').innerHTML ='';
   document.searchForm.msgSubject.value='';
   document.searchForm.assignedDate.value='';
   document.searchForm.elm1.value='';
   document.searchForm.submissionDate.value='';
   document.searchForm.studentRollNo.value='';
   document.getElementById('showSubmit').style.display='none';
   document.getElementById('showSubmit1').style.display='';
   document.getElementById('msgLogo').value='';
   document.searchForm.msgLogo.value='';
   document.searchForm.subject.disabled = false;
   document.searchForm.group.disabled = false;
   document.searchForm.classId.disabled = false;
   document.searchForm.studentRollNo.disabled = false;
   document.searchForm.msgSubject.disabled = false;
   document.searchForm.elm1.disabled = false;
   document.searchForm.assignedDate.disabled = false;
   document.searchForm.submissionDate.disabled = false;
   document.searchForm.msgLogo.disabled = false;
   document.searchForm.classId.focus();
}
function deleteRollNo(){

   document.getElementById('results').innerHTML='';
   //document.getElementById('studentRollNo').value="";
}

function editWindow(id,dv) {
   document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Task Allocation';
   populateValues(id,dv);
}

function showWindow(id,dv) {
   document.getElementById('divHeaderId').innerHTML='&nbsp; Display Assignment Status';
   //displayWindow(dv,winLayerWidth,winLayerHeight);
   populateAssignment(id,dv);
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (26.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv) {
	document.searchForm.subject.disabled = false;
	document.searchForm.classId.disabled = false;
	document.searchForm.group.disabled = false;
	document.searchForm.studentRollNo.disabled = false;
	document.searchForm.msgSubject.disabled = false;
	document.searchForm.elm1.disabled = false;

	document.searchForm.assignedDate.disabled = false;
	document.searchForm.submissionDate.disabled = false;
	document.searchForm.msgLogo.disabled = false;
	url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTeacherAssignmentValues.php';
	new Ajax.Request(url,
    {
		method:'post',
		parameters: {assignmentId: id},
		onCreate: function() {

			showWaitDialog(true);
		},
		onSuccess: function(transport){

			hideWaitDialog(true);
			if(trim(transport.responseText)==0) {
				hiddenFloatingDiv(dv);
				messageBox("This Assignment does not exists");
		    }
		    var j= trim(transport.responseText).evalJSON();
			var tbHeadArray = new Array(
					 new Array('srNo','#','width="2%"','',false),
					 new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="4%"','align=\"left\"',false),
					 new Array('studentName','Name','width="30%"','',true),
					 new Array('rollNo','Roll No.','width="15%"','',true) ,
					 new Array('regNo','Regn. No.','width="15%"','',true) ,
					 new Array('universityRollNo','Univ. Roll No.','width="15%"','',true)
			 );
			printResultsNoSorting('results', j.info, tbHeadArray);
			document.searchForm.reset();
			document.searchForm.classId.value = j.assignmentinfo[0].classId;
            populateSubjects(j.assignmentinfo[0].classId);
            document.searchForm.subject.value = j.assignmentinfo[0].subjectId;
            groupPopulate(j.assignmentinfo[0].subjectId);
            document.searchForm.group.value = j.assignmentinfo[0].groupId;
			document.searchForm.msgSubject.value = j.assignmentinfo[0].topicTitle;
			document.searchForm.assignedDate.value = j.assignmentinfo[0].assignedOn;
			document.searchForm.elm1.value = j.assignmentinfo[0].topicDescription;
			document.searchForm.submissionDate.value = j.assignmentinfo[0].tobeSubmittedOn;

			document.searchForm.msgLogo.value = '';
			document.searchForm.assignmentId.value = j.assignmentinfo[0].assignmentId;
            if(j.assignmentinfo[0].isVisible==1){
              document.searchForm.isVisible[0].checked=true;
            }
            else{
              document.searchForm.isVisible[1].checked=true;
            }
			if(j.assignmentinfo[0].attachmentFile){
			  document.getElementById('editLogoPlace').innerHTML = "<a href='<?php echo IMG_HTTP_PATH?>/TeacherAssignment/"+j.assignmentinfo[0].attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
			  document.getElementById('deleteLogoDiv').innerHTML ='<img src="<?php echo IMG_HTTP_PATH; ?>/delete.gif" onclick="deatach('+j.assignmentinfo[0].assignmentId+');" title="Delete Uploaded File" />';
		    }
		    else{
  			  document.getElementById('editLogoPlace').innerHTML = '';
			  document.getElementById('deleteLogoDiv').innerHTML= '';
		    }
			document.getElementById('showSubmit').style.display='';
			document.getElementById('showSubmit1').style.display='none';
			document.getElementById('showListDisplay').style.display='none';
			if(j.studentSubmit>0){
				document.searchForm.subject.disabled = true;
				document.searchForm.classId.disabled = true;
				document.searchForm.group.disabled = true;
				document.searchForm.studentRollNo.disabled = true;
				document.searchForm.msgSubject.disabled = true;
				document.searchForm.elm1.disabled = true;
				document.searchForm.assignedDate.disabled = true;
				document.searchForm.submissionDate.disabled = true;
				document.searchForm.msgLogo.disabled = true;
				document.getElementById('deleteLogoDiv').innerHTML= '';
				doProcessingF();
			}

            displayWindow(dv,winLayerWidth,winLayerHeight);

	 },
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function deatach(id){
if(false===confirm("Do you want to delete this file?")) {
              return false;
           }
          else {

           var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxDeleteUploadedFile.php';
              new Ajax.Request(url,
              {
               method:'post',
               parameters: {
                  assignmentId: id
               },
              onCreate: function() {
                  showWaitDialog(true);
               },
             onSuccess: function(transport){
                       hideWaitDialog(true);
                       if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                        /*
                         messageBox("File Deleted");
                         document.getElementById('uploadIconLabel').innerHTML='';
                         document.getElementById('uploadIconLabel2').innerHTML='';
                        */
                         hiddenFloatingDiv('StudentTeacherActionDiv');
						 var listUrl = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTeacherAssignmentValues.php';
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
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (26.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateAssignment(id,dv) {
 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetStudentAssignmentValues.php';
 new Ajax.Request(url,
   {
	 method:'post',
	 parameters: {assignmentId: id},
	 onCreate: function() {

		 showWaitDialog(true);
	 },
	 onSuccess: function(transport){

		 hideWaitDialog(true);
		 if(trim(transport.responseText)==0) {

		 	hiddenFloatingDiv('TaxHeadActionDiv');
			messageBox("<?php echo TAX_HEAD_NOT_EXIST; ?>");
			getTaxHeadData();
		 }

		 var j= trim(transport.responseText).evalJSON();
		 var tbHeadArray = new Array(
		     new Array('srNo','#','width="2%"','',false),
			 new Array('studentName','Name','width="35%"','',true),
			 new Array('rollNo','Roll No.','width="15%"','',true) ,
             new Array('regNo','Regn. No.','width="15%"','',true) ,
			 new Array('universityRollNo','Univ. Roll No.','width="15%"','',true),
			 new Array('submitDate','Submitted On','width="15%"','align="center"',true),
			 new Array('replyAttachmentFile','Attachment ','width="15%"','align="center"',true)
		 );
		 printResultsNoSorting('results12', j.info, tbHeadArray);
         document.getElementById('classId1').innerHTML=j.assignmentinfo[0].className
         document.getElementById('subject1').innerHTML=j.assignmentinfo[0].subjectCode;
         document.getElementById('group1').innerHTML=j.assignmentinfo[0].groupShort
         document.getElementById('isVisibleDiv').innerHTML=j.assignmentinfo[0].isVisible;
		 document.getElementById("DateAssignedOn").innerHTML = j.assignmentinfo[0].assignedOn;
		 document.getElementById("DateDueOn").innerHTML = j.assignmentinfo[0].tobeSubmittedOn;
		 document.getElementById("DateAddedOn").innerHTML = j.assignmentinfo[0].addedOn;
		 document.getElementById("AssignmentTopic").innerHTML = j.assignmentinfo[0].topicTitle;
		 document.getElementById("AssignmentDescription").innerHTML =  j.assignmentinfo[0].topicDescription;
		 if(j.assignmentinfo[0].attachmentFile){
			 document.getElementById('editLogoPlaceDetail').innerHTML = "<a href='<?php echo IMG_HTTP_PATH?>/TeacherAssignment/"+j.assignmentinfo[0].attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
		 }
		 else{
			 document.getElementById('editLogoPlaceDetail').innerHTML = '--';
		 }
         displayWindow(dv,winLayerWidth,winLayerHeight);

	 },
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}



function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';

    if(classId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                    for(var c=0;c<j.length;c++){
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subject.options.add(objOption);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function washoutData(){
   document.getElementById('results').innerHTML='';
   document.getElementById('showSubmit').style.display='none';
   //document.getElementById('showSubmit1').style.display='none';
}

function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption);

   if(document.getElementById('subject').value==""){
       return false;
   }
   if(document.getElementById('classId').value==""){
       return false;
   }


 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId  : document.getElementById('classId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var r=1;
                    var tname='';

                    for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}




//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Rajeev Aggarwal
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function getData(){

    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS;?> ");
        document.getElementById('classId').focus();
        return false;
    }

    if(document.getElementById('subject').value==''){
        messageBox("<?php echo SELECT_SUBJECT;?> ");
        document.getElementById('subject').focus();
        return false;
    }

    if(document.getElementById('group').value==''){
        messageBox("<?php echo SELECT_GROUP;?> ");
        document.getElementById('group').focus();
        return false;
    }

	var url1 = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxStudentAssignmentList.php';
	var tableHeadArray = new Array(
		new Array('srNo','#','width="2%" align="left"',false),
		new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="4%" align=\"left\"',false),
		new Array('studentName','Name','width="45%" align="left"',true),
		new Array('rollNo','Roll No.','width="15%"',true) ,
        new Array('regNo','Regn. No.','width="15%"',true) ,
		new Array('universityRollNo','Univ. Roll No.','width="15%"',true)
    );



	//new Array('topicDescription','Description','width="33%" align="left"',true),
	//new Array('assignedOn','Assigned','width="7%" align="left"',true),
	listObj1 = new initPage(url1,recordsPerPageTeacher,linksPerPage,1,'','studentName','ASC','results','','',true,'listObj1',tableHeadArray,'','','&group='+document.getElementById('group').value+'&subject='+document.getElementById('subject').value+'&class='+document.getElementById('classId').value+'&studentRollNo='+document.getElementById('studentRollNo').value);
	sendRequest(url1, listObj1, '',false);

	document.getElementById('showSubmit').style.display='';
	document.getElementById('showSubmit1').style.display='none';
	//listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','assignmentId','ASC','LeaveTypeResultDiv','StudentTeacherActionDiv','',true,'listObj',tableColumns,'editWindow','deleteStudentTeacher','');
	sendRequest(url, listObj, '')
	if(listObj1.totalRecords==0){
		document.getElementById('showSubmit').style.display='none';
	}
	//setTimeout('document.getElementById("results").style="height:100px;overflow:auto";',2000);

	//document.getElementById('results').style.overFlow='auto';

	//document.getElementById('showSubmit').style.display='';
	//document.getElementById('showSubmit1').style.display='none';

/*
	if(trim(document.getElementById('studentRollNo').value)!=""){


		sendReq(listURL,divResultName,searchFormName,'&subject='+document.searchForm.subject.value+'&section='+document.searchForm.section.value+'&classes='+document.searchForm.classes.value+'&studentRollNo='+document.searchForm.studentRollNo.value,false);
        document.getElementById('showSubmit').style.display='';
	    document.getElementById('showSubmit1').style.display='none';
    }
    else if((document.getElementById('subject').value != "") && (document.getElementById('section').value != "") ){

		sendReq(listURL,divResultName,searchFormName,'&subject='+document.searchForm.subject.value+'&section='+document.searchForm.section.value+'&classes='+document.searchForm.classes.value+'&studentRollNo='+document.searchForm.studentRollNo.value,false);
        document.getElementById('showSubmit').style.display='';
	    document.getElementById('showSubmit1').style.display='none';
    }
	else{

	    messageBox("<?php echo STUDENT_ASS_SELECT_STUDENT_LIST_SC; ?>");
        document.getElementById('subject').focus();
    } */
}
//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Rajeev Aggarwal
// Created on : (24.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function chkObject(id){

  obj = document.searchForm.elements[id];
  if(obj.length > 0) {

	  return true;
  }
  else{

	  return false;;
  }
}
//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Rajeev Aggarwal
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectStudents(){

    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    if(!chkObject('students')){

		document.searchForm.students.checked =state;
		return true;
    }
    formx = document.searchForm;
    var l=formx.students.length;
    for(var i=0 ;i < l ; i++){   //started from 2 for two dummy fields.

		formx.students[ i ].checked=state;
    }
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Rajeev Aggarwal
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {

	if((document.searchForm.students.length) == 0){
		messageBox("<?php echo NO_DATA_SUBMIT; ?>");
		return false;
	}

	if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS;?> ");
        document.getElementById('classId').focus();
        return false;
    }

    if(document.getElementById('subject').value==''){
        messageBox("<?php echo SELECT_SUBJECT;?> ");
        document.getElementById('subject').focus();
        return false;
    }

    if(document.getElementById('group').value==''){
        messageBox("<?php echo SELECT_GROUP;?> ");
        document.getElementById('group').focus();
        return false;
    }

	if(trim(document.getElementById('msgSubject').value)=="") {

		messageBox("<?php echo EMPTY_TASK_TITLE; ?>");
		document.getElementById('msgSubject').focus();
		return false;
	}
	if(trim(document.getElementById('elm1').value)=="") {

		messageBox("<?php echo EMPTY_TASK_DESCRIPTION; ?>");
		document.getElementById('elm1').focus();
		return false;
	}
	if(trim(document.getElementById('assignedDate').value)=="") {

		messageBox("<?php echo EMPTY_MSG_ASSIGNED_DATE; ?>");
		document.getElementById('assignedDate').focus();
		return false;
	}
	if(trim(document.getElementById('submissionDate').value)=="") {

		messageBox("<?php echo EMPTY_MSG_SUBMISSION_DATE; ?>");
		document.getElementById('submissionDate').focus();
		return false;
	}
	if(dateCompare(document.getElementById('assignedDate').value,document.getElementById('submissionDate').value)==1){

		messageBox("<?php echo ASSIGNED_DATE_LESS_DUE_DATE?>");
		document.getElementById('assignedDate').focus();
		return false;
	}

	var currDate = "<?php echo date('Y-m-d')?>";
	if(dateCompare(currDate,document.getElementById('assignedDate').value)==1){

		messageBox("<?php echo ASSIGNED_LESS_THAN_CURRENT?>");
		document.getElementById('assignedDate').focus();
		return false;
	}

    if(trim(document.getElementById('msgLogo').value)!=''){
        if(!checkAllowdExtensions(trim(document.getElementById('msgLogo').value))){
             document.getElementById('msgLogo').focus();
             messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
             return false;
         }
    }

	if(!(checkStudents())){  //checkes whether any student/parent checkboxes selected or not

		messageBox("<?php echo SELECT_STUDENT_AASSIGNMENT; ?>");
		document.getElementById('studentList').focus();
		return false;
	}
	else{

		if(document.getElementById('assignmentId').value=='') {
		initUpload(); //upload the attachment
		sendMessage(); //sends the message
    }
    else{

		initUpload(); //upload the attachment
		editSendMessage(); //sends the message
    }
  }
}
//Used to upload message attachments
function initUpload() {
	document.getElementById('searchForm').onsubmit=function() {
		document.getElementById('searchForm').target = 'uploadTargetAdd';
	}
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Rajeev Aggarwal
// Created on : (21.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {

		var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSendStudentAssignment.php';
        formx = document.searchForm;
        var student="";  //get studentIds when student checkboxes are selected

        if((document.searchForm.students.length - 2)<=1){

		   student=(document.searchForm.students[1].checked ? document.searchForm.students[1].value : "0" );
         }
         else{

			var m=formx.students.length;
			for(var k=0 ; k < m ; k++){ //started from 2 for two dummy fields.

				if(formx.students[ k ].checked==true){
					if(student==""){
						student= formx.students[ k ].value;
					}
					else{
						student+="," + formx.students[ k ].value;
					}
				}
			}
         }

         new Ajax.Request(url,
         {
             method:'post',
             parameters: {
             msgBody: (document.getElementById('elm1').value),
             assignedDate: ((document.getElementById('assignedDate').value)),
             submissionDate: ((document.getElementById('submissionDate').value)),
             subject: ((document.getElementById('subject').value)),
			 group: ((document.getElementById('group').value)),
             classId: ((document.getElementById('classId').value)),
			 student: (student),
             isVisible : document.searchForm.isVisible[0].checked==true?1:0,
             msgSubject:(trim(document.getElementById('msgSubject').value))
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){

					 hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {

						flag = true;
                        messageBox("<?php echo ASSIGNMENT_SENT_OK; ?>");
						hiddenFloatingDiv('StudentTeacherActionDiv');
						refreshMessageData();
						return false;
                     }
                     else {

						messageBox(trim(transport.responseText));
                     }
                     resetForm();
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Rajeev Aggarwal
// Created on : (21.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editSendMessage() {

	var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxEditSendStudentAssignment.php';
    formx = document.searchForm;
	var student="";  //get studentIds when student checkboxes are selected

	if((document.searchForm.students.length - 2)<=1){
		student=(document.searchForm.students[2].checked ? document.searchForm.students[2].value : "0" );
	}
	else{

		var m=formx.students.length;
		for(var k=0 ; k < m ; k++){ //started from 2 for two dummy fields.
			if(formx.students[ k ].checked==true){
				if(student==""){
					student= formx.students[ k ].value;
				}
				else{
					student+="," + formx.students[ k ].value;
				}
			}
		}
	}
    new Ajax.Request(url,
    {
		 method:'post',
		 parameters: {
		   assignmentId: ((document.getElementById('assignmentId').value)),
           msgBody: (document.getElementById('elm1').value),
           assignedDate: ((document.getElementById('assignedDate').value)),
           submissionDate: ((document.getElementById('submissionDate').value)),
           subject: ((document.getElementById('subject').value)),
           group: ((document.getElementById('group').value)),
           classId: ((document.getElementById('classId').value)),
           student: (student),
           isVisible : document.searchForm.isVisible[0].checked==true?1:0,
           msgSubject:(trim(document.getElementById('msgSubject').value))
		 },
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 flag = true;
					messageBox("<?php echo ASSIGNMENT_SENT_OK; ?>");
					hiddenFloatingDiv('StudentTeacherActionDiv');
					 refreshMessageData();
					 return false;

				 }
				 else {
					messageBox(trim(transport.responseText));
				 }
				 resetForm();
		 },
		 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
	   });
}
//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any student checkboxes selected or not
//
//Author : Rajeev Aggarwal
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkStudents(){

    var fl=0;
    if(!chkObject('students')){

		if(document.searchForm.students.checked==true){


			fl=1;
		}

		return fl;
    }
    formx = document.searchForm;
    var l=formx.students.length;

    for(var i=1 ;i < l ; i++){   //started from 2 for two dummy fields.

		if((formx.students[ i ].checked==true) ){

			fl=1;
            break;
        }
    }
    return (fl);
}

/* function to print TestType report*/
function printReport() {
    var qstr="search="+trim(document.searchForm2.searchbox.value);
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/allocateAssignmentReportPrint.php?'+qstr;
     hideUrlData(path,true);
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="search="+trim(document.searchForm2.searchbox.value);
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    window.location='<?php echo UI_HTTP_PATH;?>/Teacher/allocateAssignmentReportCSV.php?'+qstr;
}

function  download(str){
 var address="<?php echo IMG_HTTP_PATH;?>/TeacherAssignment/"+str;
 window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/allocateAssignmentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: allocateAssignment.php $
?>
