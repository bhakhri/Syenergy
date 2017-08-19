<?php
//-------------------------------------------------------
// Purpose: To generate the list of SUBJECT Topic from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : 24-01-2009
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherBulkSubjectTopic');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();     
//require_once(BL_PATH . "/SubjectTopic/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bulk Subject Topic Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
                     new Array('srNo','#','width="2%"','',false), 
                     new Array('subjectName','Subject Name','width="15%"','',true),  
                     new Array('subjectCode','Subject Code','width="12%"','',true), 
                     new Array('topic','Topic','width="35%"','',true), 
                     new Array('topicAbbr','Abbr.','width="30%"','',true),
                     new Array('action1','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddCourseTopicDiv';   
editFormName   = 'EditCourseTopicDiv';
winLayerWidth  = 480; //  add/edit form width
winLayerHeight = 300; // add/edit form height
deleteFunction = 'return deleteSubjectTopic';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function editWindow(id,w,h) {
    var dv='EditCourseTopicDiv';
        displayWindow(dv,w,h);
        populateValues(id);
} 

function showTopicDetails(id,dv,w,h) {

	displayFloatingDiv(dv,'', w, h, 400, 200)
    populateTopicValues(id);
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divTopic" DIV
//
//Author : Parveen Sharma
// Created on : 16.01.09
// Copyright 2009-2010 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateTopicValues(id) {     
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetValues.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {subjectTopicId: id},
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divTopic');
                        messageBox("This subject topic record does not exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                          //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    document.getElementById('topicInfo').innerHTML= j.topic;    
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
}


function validateAddForm(frm, act) {
   
   if(act=='Add') { 
        var fieldsArray = new Array( new Array("studentCourse","<?php echo SELECT_SUBJECT;?>"),
                                     new Array("courseTopic","<?php echo ENTER_SUBJECT_TOPIC;?>") );
   }
   else if(act=='Edit') { 
        var fieldsArray = new Array( new Array("studentSubject","<?php echo SELECT_SUBJECT;?>"),
                                     new Array("subjectTopic","<?php echo ENTER_SUBJECT_TOPIC;?>") );
   }
   
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) || trim(eval("frm."+(fieldsArray[i][0])+".value"))==',') {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    
    if(act=='Edit') { 
	    if(trim(document.editCourseTopic.subjectAbbr.value)==','){
		    messageBox("<?php echo ENTER_SUBJECT_TOPIC_ABBREVATION?>");
            document.editCourseTopic.subjectAbbr.focus();
            return false;
	    }
        
	    if(isEmpty(document.editCourseTopic.subjectAbbr.value)){
		    messageBox("<?php echo ENTER_SUBJECT_TOPIC_ABBREVATION?>");
            document.editCourseTopic.subjectAbbr.focus();
            return false;
	    }
	}
    
    if(act=='Add') {
		addCourseTopic();
        return false;
    }
    else if(act=='Edit') {
        editCourseTopic();
        return false;
    }
}

function changeText(textSeprator){

	if(textSeprator==',')
		document.getElementById('showText').innerHTML="<i>For eg. topic1,topic2,topic3</i>";
	if(textSeprator=='~')
		document.getElementById('showText').innerHTML="<i>For eg. topic1~topic2~topic3</i>";
	if(textSeprator==';')
		document.getElementById('showText').innerHTML="<i>For eg. topic1;topic2;topic3</i>";
	 
}
function addCourseTopic() {
         
	 url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxBulkInitAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {studentCourse: (document.addCourseTopic.studentCourse.value), courseTopic: (document.addCourseTopic.courseTopic.value), topicSeprator: (document.addCourseTopic.topicSeprator.value)},
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 blankValues();
					 }
					 else {
						 hiddenFloatingDiv('AddCourseTopicDiv');
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 //location.reload();
						 return false;
					 }
				 } 
				 else {
					messageBox(trim(transport.responseText)); 
				 }
		   
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function deleteSubjectTopic(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTopicId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){

                     hideWaitDialog(true);
                     
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
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

function blankValues() {
   document.addCourseTopic.studentCourse.value = '';
   document.addCourseTopic.courseTopic.value = '';
   document.addCourseTopic.topicSeprator.value = ',';
   document.addCourseTopic.studentCourse.focus();
}

function editCourseTopic() {  
         
	 url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitEdit.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		   parameters: { 
			   subjectTopicId: (document.editCourseTopic.subjectTopicId.value),   
			   studentSubject: (document.editCourseTopic.studentSubject.value), 
			   subjectTopic: (document.editCourseTopic.subjectTopic.value),
			   subjectAbbr: (document.editCourseTopic.subjectAbbr.value)}, 
		 onCreate: function(){
			 showWaitDialog(true);
		 },             
		 onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditCourseTopicDiv');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
					 //location.reload();
				 }
				 else {
					messageBox(trim(transport.responseText));                         
				 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
function populateValues(id) {
         
 url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetValues.php';
 new Ajax.Request(url,
   {
	 method:'post',
	 parameters: {subjectTopicId: id},
	 onCreate: function(){
		 showWaitDialog();
	 },
	 onSuccess: function(transport){
			hideWaitDialog();
			if(trim(transport.responseText)==0) {
				hiddenFloatingDiv('EditCourseTopicDiv');
				messageBox("<?php echo SUBJECT_TOPIC_NOT_EXIST;?>");
				sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                               //return false;
		   }
		   j = eval('('+trim(transport.responseText)+')');
           
		   document.editCourseTopic.subjectTopicId.value = j.subjectTopicId;                                      
		   document.editCourseTopic.studentSubject.value = j.subjectId;
		   document.editCourseTopic.subjectTopic.value = j.topic;
		   document.editCourseTopic.subjectAbbr.value=j.topicAbbr;
		   document.editCourseTopic.studentSubject.focus();
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function printReport() {
    var chk=document.searchForm.searchbox.value;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/listSubjectTopicPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&searchbox='+chk; 
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

/* function to print all fee collection to csv*/
function printReportCSV() {
     
	var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/listSubjectTopicPrintCSV.php?'+qstr;
    window.location = path;
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/bulkListSubjectTopicContents.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>
<?php
// $History: bulkListSubjectTopic.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/13/10    Time: 2:27p
//Updated in $/LeapCC/Interface/Teacher
//look & feel updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/13/10    Time: 1:04p
//Updated in $/LeapCC/Interface/Teacher
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/13/10    Time: 9:31a
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//
?>
