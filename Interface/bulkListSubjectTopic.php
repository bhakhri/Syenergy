<?php
//-------------------------------------------------------
// Purpose: To generate the list of SUBJECT Topic from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : 24-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BulkSubjectTopic');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

//TO STORE MODULE NAME
$sessionHandler->setSessionVariable('Module',MODULE);
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
                     new Array('srNo','#','width="5%"','',false), 
                     //new Array('subjectCode','Subject Code','width="15%"','',true) , 
                     new Array('topic','Topic','width="45%"','',true), 
                     new Array('topicAbbr','Abbr.','width="25%"','',true) ,
                     new Array('action1','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitList.php';
searchFormName = 'totalMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddCourseTopicDiv';   
editFormName   = 'EditCourseTopicDiv';
winLayerWidth  = 480; //  add/edit form width
winLayerHeight = 300; // add/edit form height
deleteFunction = 'return deleteSubjectTopic';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';
queryString ='';  

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


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function validateSearchForm() {
    queryString='';
    hideResults();
    if(document.getElementById("tSubjectId").value=='') {
       messageBox("<?php echo SELECT_SUBJECT;?>");       
       document.getElementById("tSubjectId").focus(); 
       return false;  
    }
    var moduleName = ("<?php echo MODULE;?>");
    queryString =  document.getElementById("tSubjectId").value;
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divTopic" DIV
//
//Author : Parveen Sharma
// Created on : 16.01.09
// Copyright 2009-2010 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateTopicValues(id) {   
		 var moduleName = ("<?php echo MODULE;?>");
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxGetValues.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {subjectTopicId: id,mod:moduleName},
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divTopic');
                        messageBox("This subject topic record does not exists");
                        //sendReq(listURL,divResultName,'','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&searchbox='+queryString);                          //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    document.getElementById('topicInfo').innerHTML= j.topic;    
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
}


function validateAddForm(frm, act) {
   
   if(act=='Add') { 
        var fieldsArray = new Array( new Array("studentCourse","Select Subject Code"),
                                     new Array("courseTopic","<?php echo ENTER_SUBJECT_TOPIC;?>") );
   }
   else if(act=='Edit') { 
        var fieldsArray = new Array( new Array("studentSubject","Select Subject Code"),
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
		    messageBox("Enter Subject Topic Abbr.");
            document.editCourseTopic.subjectAbbr.focus();
            return false;
	    }
        
	    if(isEmpty(document.editCourseTopic.subjectAbbr.value)){
		    messageBox("Enter Subject Topic Abbr.");
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
	 var moduleName = ("<?php echo MODULE;?>");
	 url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxBulkInitAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {studentCourse: (document.addCourseTopic.studentCourse.value), courseTopic: (document.addCourseTopic.courseTopic.value), topicSeprator: (document.addCourseTopic.topicSeprator.value),mod:moduleName},
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
						 sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
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
	var moduleName = ("<?php echo MODULE;?>");
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTopicId: id,mod:moduleName},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
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
      var moduleName = ("<?php echo MODULE;?>");   
	 url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitEdit.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		   parameters: { 
			   subjectTopicId: (document.editCourseTopic.subjectTopicId.value),   
			   studentSubject: (document.editCourseTopic.studentSubject.value), 
			   subjectTopic: (document.editCourseTopic.subjectTopic.value),
			   subjectAbbr: (document.editCourseTopic.subjectAbbr.value),mod:moduleName}, 
		 onCreate: function(){
			 showWaitDialog(true);
		 },             
		 onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditCourseTopicDiv');
					 sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
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
     var moduleName = ("<?php echo MODULE;?>");    
 url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxGetValues.php';
 new Ajax.Request(url,
   {
	 method:'post',
	 parameters: {subjectTopicId: id,mod:moduleName},
	 onCreate: function(){
		 showWaitDialog();
	 },
	 onSuccess: function(transport){
			hideWaitDialog();
			if(trim(transport.responseText)==0) {
				hiddenFloatingDiv('EditCourseTopicDiv');
				messageBox("<?php echo SUBJECT_TOPIC_NOT_EXIST;?>");
				sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);                              //return false;
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
    var moduleName = ("<?php echo MODULE;?>");
	queryString =  document.getElementById("tSubjectId").value;
    //var chk=document.searchForm.searchbox.value;
	var qry = "&tSubjectId="+queryString+'&mod='+moduleName; 
    path='<?php echo UI_HTTP_PATH;?>/listSubjectTopicPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+qry;

    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

/* function to print all subject topic to csv*/
function printCSV() {
     var moduleName = ("<?php echo MODULE;?>");
	 queryString =  document.getElementById("tSubjectId").value;
	//var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField+"&tSubjectId="+queryString; 
    path='<?php echo UI_HTTP_PATH;?>/listSubjectTopicPrintCSV.php?'+qstr+'&mod='+moduleName;
    window.location = path;
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/SubjectTopic/bulkListSubjectTopicContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     //sendReq(listURL,divResultName,'','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&searchbox='+queryString);
</script>    
</body>
</html>
<?php
// $History: bulkListSubjectTopic.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Interface
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/29/09    Time: 6:49p
//Updated in $/LeapCC/Interface
//0000779: Bulk Subject Topic Master - Admin> “Action” field is right
//aligned by default. It must be center aligned along with buttons.
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//added define variable for Role Permission
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/01/09    Time: 3:43p
//Updated in $/LeapCC/Interface
//spelling correct 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/12/09    Time: 3:14p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 2/26/09    Time: 2:56p
//Updated in $/Leap/Source/Interface
//Added topic seprator while adding bulk topic
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 2/11/09    Time: 12:27p
//Updated in $/Leap/Source/Interface
//Updated validations and fixed bugs
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 2/02/09    Time: 12:16p
//Updated in $/Leap/Source/Interface
//added validations and removed bugs
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/24/09    Time: 2:42p
//Created in $/Leap/Source/Interface
//Intial checkin
?>
