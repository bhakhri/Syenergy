<?php
//-------------------------------------------------------
//  This File contains starting code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PromoteStudentsAdvanced');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Promote Students </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>";
</script>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
?>
<script language="javascript">
/*
var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), new Array('className','Class','width=25%  align=left',' align=left',false), new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), new Array('groupName','Group','width="10%"  align=left',' align=left',false), new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), new Array('testName','Test Name','width="20%"  align=left',' align=left',false));
*/
 //This function Validates Form
listURL = '<?php echo HTTP_LIB_PATH;?>/SubjectToClass/ajaxInitList.php';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTimeTableLabel';   
editFormName   = 'EditTimeTableLabel';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTimeTableLabel';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form

divResultName  = 'EditSubject';
function hideDetails() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';
}

//This function Displays Div Window
function editSubjectWindow(id,dv,w,h) {
   dv = 'EditSubject';
    displayWindow(dv,w,h);
    populateSubjectValues(id);
}

function editWindow(id,dv,w,h) {
	clearTimeTableText();
    displayWindow(dv,w,h);
    populateTimeTableLabelValues(id);   
}


function populateSubjectValues(id) {

     url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxGetValues.php';
     document.editSubject.subjectCategoryId.selectedIndex=0;
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {subjectId: id},
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
               hideWaitDialog(true);
               j = eval('('+transport.responseText+')');
               document.editSubject.subjectCode.value = j.subjectCode;
               document.editSubject.subjectName.value = j.subjectName;
               document.editSubject.subjectAbbreviation.value = j.subjectAbbreviation;
               document.editSubject.subjectId.value = j.subjectId;
               document.editSubject.subjectTypeId.value = j.subjectTypeId;
               if(j.subjectCategoryId!=0) {
                 document.editSubject.subjectCategoryId.value = j.subjectCategoryId;
               }
               else {
                 document.editSubject.subjectCategoryId.selectedIndex=0;
               }
               document.editSubject.hasAttendance.value=j.hasAttendance;
               document.editSubject.hasMarks.value=j.hasMarks;
               document.editSubject.courseTopic.value=j.topic;
               document.editSubject.subjectAbbr.value=j.topicAbbr;
           }
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function addEditSubject(frm, act) {
    var fieldsArray =
                     new Array(new Array("subjectName","<?php echo ENTER_SUBJECT_NAME;?>"),
                     new Array("subjectCode","<?php echo ENTER_SUBJECT_CODE;?>"),
                     new Array("subjectTypeId","<?php echo SELECT_SUBJECT_TYPE;?>"),
                     new Array("subjectCategoryId","<?php echo SUBJECT_CATEGORY_NAME;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    if(act=='Add') {
        addSubject();
        return false;
    }
    else if(act=='Edit') {
        editSubject();
        return false;
    }
}


function deleteSubject(id) {  
	if(false == confirm("Do you want to delete this subject?")) {
		 return false;
	}
	else {   
  
	url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitDelete.php';
	new Ajax.Request(url,
	  {
		 method:'post',
		 parameters: {subjectId: id},
		 onSuccess: function(transport){
			if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
				showWaitDialog(true);
			}
			 else {
					hideWaitDialog(true);
				//   messageBox(trim(transport.responseText));
					if("<?php echo DELETE;?>"==trim(transport.responseText)) {
						 getSubject();
					}
					 else {
						 messageBox(trim(transport.responseText));
					}
			}
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	  });
	}    
           
}

function addSubject() {
   url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitAdd.php';
   new Ajax.Request(url,
     {
       method:'post',
       parameters: {subjectName: trim(document.addSubject.subjectName.value),
                    subjectCode: trim(document.addSubject.subjectCode.value),
                    subjectAbbreviation: trim(document.addSubject.subjectAbbreviation.value),
                    subjectTypeId: trim(document.addSubject.subjectTypeId.value),
                    subjectCategoryId: trim(document.addSubject.subjectCategoryId.value),
                    hasAttendance: document.addSubject.hasAttendance.value,
                    hasMarks:document.addSubject.hasMarks.value,
						  courseTopic:document.addSubject.courseTopic.value,
						  subjectAbbr: document.addSubject.subjectAbbr.value
                   },
       onSuccess: function(transport){
         if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
            showWaitDialog(true);
         }
         else {
               hideWaitDialog(true);

               if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                   flag = true;
                   if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                       blankSubjectForm();
                   }
                   else {
                       hiddenFloatingDiv('AddSubject');
                   }
						 getSubject();
               }
               else {
                  messageBox(trim(transport.responseText));
               }
         }
       },
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
     });

}


function editSubject() {
	url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitEdit.php';
	new Ajax.Request(url,
	  {
		 method:'post',
		 parameters: {subjectId: trim(document.editSubject.subjectId.value),
						  subjectName: trim(document.editSubject.subjectName.value),
						  subjectCode: trim(document.editSubject.subjectCode.value),
						  subjectAbbreviation: trim(document.editSubject.subjectAbbreviation.value),
						  subjectTypeId: trim(document.editSubject.subjectTypeId.value),
						  subjectCategoryId: trim(document.editSubject.subjectCategoryId.value),
						  hasAttendance: document.editSubject.hasAttendance.value,
						  hasMarks:document.editSubject.hasMarks.value,
						  courseTopic:document.editSubject.courseTopic.value,
						  subjectAbbr: document.editSubject.subjectAbbr.value
						},
		 onSuccess: function(transport){
			if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
				showWaitDialog(true);
			}
			else {
				hideWaitDialog(true);
				 messageBox(trim(transport.responseText));
				if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					hiddenFloatingDiv('EditSubject');
					getSubject();
				}
			}
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	  });
}


function getClassesForPromotion() {
  url = '<?php echo HTTP_LIB_PATH;?>/PromoteStudentsAdvanced/getClassesForPromotion.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false),
                        new Array('select','<input type="checkbox" name="selPromotionClasses" onClick="selAllClassesForPromotion();"/>Sel. All','width="4%" align="left"',false),
                        new Array('className','Class','width="12%" align="left"',false),
                        new Array('copyGroups','<input type="checkbox" name="selCopyGroups" onClick="selAllCopyGroups();"/>Copy Groups After Promotion','width="12%" align="left"',false),
                        new Array('copyPrivileges','<input type="checkbox" name="selCopyPrivileges" onClick="selAllCopyPrivileges();"/>Copy Privileges After Promotion','width="12%" align="left"',false)
                     );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','getClassForPromotionDiv','getClassForPromotionDiv','',true,'listObj2',tableColumns,'editWindow','');
 sendRequest(url, listObj2, '', true);
}

function selAllClassesForPromotion() {
	formx = document.promotionForm;
	selProValue = 0;
	formx.selCopyGroups.checked = false;
	formx.selCopyPrivileges.checked = false;
	for(var i=1;i<formx.length;i++) {
		if(formx.elements[i].type=="checkbox"){
			if(formx.elements[i].name=="promoteClass[]") {
				formx.elements[i].checked = formx.selPromotionClasses.checked;
				selProValue = formx.elements[i].value;
			}
			else {
				if(formx.elements[i].name=="copyGroups[]") {
					if (formx.elements[i].value == selProValue) {
						if (formx.selPromotionClasses.checked == true) {
							formx.elements[i].disabled = false;
						}
						else {
							formx.elements[i].checked = false;
							formx.elements[i].disabled = true;
						}
					}
				}
				if(formx.elements[i].name=="copyPrivileges[]") {
					if (formx.elements[i].value == selProValue) {
						if (formx.selPromotionClasses.checked == false) {
							formx.elements[i].checked = false;
							formx.elements[i].disabled = true;
						}
					}
				}
			}
		}
	}
}

function selAllCopyGroups() {
	formx = document.promotionForm;
	selProValue = 0;
	for(var i=1;i<formx.length;i++) {
		if(formx.elements[i].type=="checkbox"){
			if(formx.elements[i].name=="copyGroups[]" && formx.elements[i].disabled == false) {
				formx.elements[i].checked = formx.selCopyGroups.checked;
				selProValue = formx.elements[i].value;
			}
			else {
				if(formx.elements[i].name=="copyPrivileges[]") {
					if (formx.elements[i].value == selProValue) {
						if (formx.selCopyGroups.checked == true) {
							formx.elements[i].disabled = false;
						}
						else {
							formx.elements[i].disabled = true;
						}
					}
				}
			}
		}
	}
}

function selThisCopyGroups(classId, selChecked) {
	formx = document.promotionForm;
	for(var i=1;i<formx.length;i++) {
		if(formx.elements[i].type=="checkbox"){
			if(formx.elements[i].name=="copyGroups[]") {
				if (formx.elements[i].value == classId) {
					if (selChecked == true) {
						formx.elements[i].disabled = false;
					}
					else {
						formx.elements[i].checked = false;
						formx.elements[i].disabled = true;
					}
				}
			}
			else {
				if(formx.elements[i].name=="copyPrivileges[]") {
					if (formx.elements[i].value == classId) {
						if (selChecked == false) {
							formx.elements[i].checked = false;
							formx.elements[i].disabled = true;
						}
					}
				}
			}
		}
	}
}

function selAllCopyPrivileges() {
	formx = document.promotionForm;
	selProValue = 0;
	for(var i=1;i<formx.length;i++) {
		if(formx.elements[i].type=="checkbox"){
			if(formx.elements[i].name=="copyPrivileges[]" && formx.elements[i].disabled == false) {
				formx.elements[i].checked = formx.selCopyPrivileges.checked;
			}
		}
	}
}



function selThisCopyPrivileges(classId, selChecked) {
	formx = document.promotionForm;
	for(var i=1;i<formx.length;i++) {
		if(formx.elements[i].type=="checkbox"){
			if(formx.elements[i].name=="copyPrivileges[]") {
				if (formx.elements[i].value == classId) {
					if (selChecked == true) {
						formx.elements[i].disabled = false;
					}
					else {
						formx.elements[i].checked = false;
						formx.elements[i].disabled = true;
					}
				}
			}
		}
	}
}


function promoteClasses(process) {


   formName = '';
   pars = '';
   if (process == 'promote') {
      url = '<?php echo HTTP_LIB_PATH;?>/PromoteStudentsAdvanced/promoteStudentsAdvanced.php';
      form = document.promotionForm;
      formName = 'promotionForm';
      pars = generateQueryString(formName);
   }
   else if (process == 'classSubjects') {
      url = '<?php echo HTTP_LIB_PATH;?>/PromoteStudentsAdvanced/promoteStudentsAdvanced.php';
      form = document.listForm;
      formName = 'listForm';
      pars = generateQueryString(formName);
   }
   if (pars != '') {
      pars += '&process='+process;
   }
   else {
      pars = 'process='+process;
   }

    new Ajax.Request(url,
       {
          method:'post',
          parameters:pars,
          onCreate: function(){
                showWaitDialog(true);
          },
          onSuccess: function(transport){
               hideWaitDialog(true);
               res = trim(transport.responseText);
               messageBox(res);
               if (res == "<?php echo STUDENTS_PROMOTED;?>") {
                  getClassesForPromotion();
                  getTimeTableLabelResuts();
                  getClassesForSubjects();
               }
               else if (res == "<?php echo SUCCESS;?>" ) {
                  clearText();
                  getClassesForSubjects();
               }

          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function blankSubjectValues() {
   document.listForm.reset();
}

function blankSubjectForm() {
	document.addSubject.reset();
}

function getSubject(){
   if(isEmpty(document.getElementById('classId').value)){
       messageBox("<?php echo ENTER_SUBJECT_TO_CLASS?>");
      document.getElementById('saveDiv').style.display='none';
      document.getElementById('saveDiv1').style.display='none';
      document.getElementById('saveDiv2').style.display='none';
      document.getElementById('showTitle').style.display='none';
      document.getElementById('showData').style.display='none';
      document.getElementById('results').innerHTML=" ";
      document.listForm.classId.focus();
      return false;
   }
   else{
      document.getElementById('saveDiv').style.display='';
      document.getElementById('saveDiv1').style.display='';
      document.getElementById('saveDiv2').style.display='';
      document.getElementById('showTitle').style.display='';
      document.getElementById('showData').style.display='';

      tableHeadArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),new Array('srNo','#','width="3%"','',false), new Array('subjectCode','Code','width="25%"','',true),new Array('subjectName','Subject Name','width="50%"','',true), new Array('subjectTypeName','Type','width="8%"','',true) ,new Array('Optional','Optional','width="8%"','',false),new Array('hasParentCategory1','Major/Minor','width="8%"','',false), new Array('internalMarks1','Internal Marks','width="14%"','align="right"',false), new Array('externalMarks1','External Marks','width="14%"','align="right"',false), new Array('actionString','Subject Action','width="10%"','align="right"',false));
      thisUrl = '<?php echo HTTP_LIB_PATH;?>/PromoteStudentsAdvanced/classSubjectsInitList.php';
      listObj2 = new initPage(thisUrl,1000,1000,1,'','subjectCode','ASC','results','results','',true,'listObj2',tableHeadArray,'editWindow','');
      sendRequest(thisUrl, listObj2, generateQueryString('listForm'), true);
   }
}

function getTimeTableLabelResuts() {
      
		getSessionTimeTables();
		
		timeTableLabelArray = new Array(
                                new Array('srNo',       '#',            'width="3%"','',false),
                                new Array('labelName',  'Name',         'width="32%"','',true) ,
                                new Array('startDate',  'From Date',   'width="20%"','align="center"',true) ,
                                new Array('endDate',    'To Date',     'width="20%"','align="center"',true) ,
                                new Array('isActive',   'Active',       'width="20%"','',true),
                                new Array('action',     'Action',       'width="1%"','align="center"',false));
      thisUrl = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitList.php';
      listObj3 = new initPage(thisUrl,1000,1000,1,'','subjectCode','ASC','timeTableLabelResuts','EditTimeTableLabel','',true,'listObj3',timeTableLabelArray,'editWindow','deleteTimeTableLabel','');
      sendRequest(thisUrl, listObj3, '', true);
}


function getSessionTimeTables() {
   form = document.listTimeTableClassesForm;
   url = '<?php echo HTTP_LIB_PATH;?>/PromoteStudentsAdvanced/getSessionTimeTables.php';
   new Ajax.Request(url,
   {
      method:'post',
		asynchronous:false,
      onCreate: function(){
      showWaitDialog(true);
   },
   onSuccess: function(transport){
      hideWaitDialog(true);
      form.labelId.length = null;
      res = eval('('+transport.responseText+')');
      total = res.length;
      addOption(form.labelId, '', 'Select');
      if (total > 0) {
         for (i=0; i < total ; i++) {
            addOption(form.labelId, res[i].timeTableLabelId, res[i].labelName);
         }
      }
   },
   onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function getMarkAttendanceShow(val,act) {
   if(act=='Add') {
     if(val==5) {  
       document.addSubject.hasAttendance.selectedIndex =1;    
       document.addSubject.hasMarks.selectedIndex=1;    
     }
     else {
       document.addSubject.hasAttendance.selectedIndex =0;    
       document.addSubject.hasMarks.selectedIndex=0;    
     }
   }
   else
   if(act=='Edit') {
     if(val==5) {  
       document.editSubject.hasAttendance.selectedIndex =1;    
       document.editSubject.hasMarks.selectedIndex=1;    
     }
     else {
       document.editSubject.hasAttendance.selectedIndex =0;    
       document.editSubject.hasMarks.selectedIndex=0;    
     }
   }
}
function getClassesForSubjects() {
   form = document.listForm;
   url = '<?php echo HTTP_LIB_PATH;?>/PromoteStudentsAdvanced/getClassesForSubjects.php';
   new Ajax.Request(url,
   {
      method:'post',
      onCreate: function(){
      showWaitDialog(true);
   },
   onSuccess: function(transport){
      hideWaitDialog(true);
      form.classId.length = null;
      res = eval('('+transport.responseText+')');
      total = res.length;
      addOption(form.classId, '', 'Select');
      if (total > 0) {
         for (i=0; i < total ; i++) {
            addOption(form.classId, res[i].classId, res[i].className);
         }
      }
   },
   onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}


function getTimeTableLabelClasses(){

   if(isEmpty(document.getElementById('labelId').value)){
       messageBox("<?php echo 'Please select time table label';?>");
      //document.getElementById('saveDiv').style.display='none';
      document.getElementById('saveDiv1').style.display='none';
      document.getElementById('showTitle').style.display='none';
      document.getElementById('showData').style.display='none';
      document.getElementById('results').innerHTML=" ";
      document.listForm.labelId.focus();
      return false;
   }
   else{
     // document.getElementById('saveDiv').style.display='';
     /*
      */
      document.getElementById('showTitle2').style.display='';
      document.getElementById('saveDiv3').style.display='';
      document.getElementById('showData2').style.display='';


      timeTableLabelClassArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),new Array('srNo','#','width="3%"','',false), new Array('className','Class Name','width="94%"','',true));

      thisUrl2 = '<?php echo HTTP_LIB_PATH;?>/TimeTable/scAjaxInitList.php';
      listObj3 = new initPage(thisUrl2,1000,1000,1,'','className','ASC','timeTableClassResults','timeTableClassResults','',true,'listObj3',timeTableLabelClassArray,'editWindow','');
      sendRequest(thisUrl2, listObj3, generateQueryString('listTimeTableClassesForm'), true);
   }
}



function validateTimeTableClassForm(frm, act) {

   var selected=0;
   var selected1=0;
   var midsemValueString = [];
   var finalExamString = [];
   formx = document.listTimeTableClassesForm;
   for(var i=1;i<formx.length;i++){

      if(formx.elements[i].disabled){

          {selected1++;}
      }
   }
   if(selected1>0){

      alert("<?php echo TIMETABLE_INACTIVE_CLASS?>");
      return false;
   }
   for(var i=1;i<formx.length;i++){

      if(formx.elements[i].type=="checkbox"){

         if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
         {selected++;}

      }
   }
   if(selected==0){

      alert("<?php echo SELECT_ATLEAST_ONE_CLASS?>");
      return false;
   }

    insertTimeTableClassForm();
   return false;
}


function insertTimeTableClassForm() {
 
	 url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/initLabelAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('listTimeTableClassesForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
				 flag = true;
					 alert(trim(transport.responseText));
					 
					 return false;
			 } 
			 else {
					str = trim(transport.responseText);
					messageBox(trim(str));
					//document.getElementById('listForm').reset(); 
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


function printReport() {

   form = document.listForm;
   var name = document.getElementById('classId');
   path='<?php echo UI_HTTP_PATH;?>/assignSubjectToClassPrint.php?class='+form.classId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&className='+name.options[name.selectedIndex].text+'&subjectDetail='+form.subjectDetail.value;
   window.open(path,"subjectToClassReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
}

/* function to print all subject to class report*/
function printCourseToClassCSV() {

   form = document.listForm;
   var name = document.getElementById('classId');
   path='<?php echo UI_HTTP_PATH;?>/assignSubjectToClassCSV.php?class='+form.classId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&className='+name.options[name.selectedIndex].text+'&subjectDetail='+form.subjectDetail.value;

   window.location=path;
}

function clearText(){

    document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv1').style.display='none';
    document.getElementById('saveDiv2').style.display='none';
   document.getElementById('showTitle').style.display='none';
   document.getElementById('showData').style.display='none';
   document.getElementById('results').innerHTML="";
}


function clearTimeTableText(){

  //  document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv3').style.display='none';	 	
	document.getElementById('showTitle2').style.display='none';	 	
	document.getElementById('showData2').style.display='none';
	document.getElementById('timeTableClassResults').innerHTML="";
}

function insertForm() {

    url = '<?php echo HTTP_LIB_PATH;?>/SubjectToClass/initAdd.php';
    new Ajax.Request(url,
      {
       method:'post',
       parameters: $('listForm').serialize(true),
       onCreate: function() {
          showWaitDialog(true);
       },
       onSuccess: function(transport){

          hideWaitDialog(true);
          if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
             flag = true;
                alert(trim(transport.responseText));
                return false;
          }
          else {
            messageBox(trim(transport.responseText));
            document.getElementById('addForm').reset();
          }
       },
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
      });
}

function validateAddForm(frm, act) {

   var selected=0;
   formx = document.listForm;
   for(var i=1;i<formx.length;i++){

      if(formx.elements[i].type=="checkbox"){

         if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
         {selected++;}
      }
   }
   if(selected==0){

      alert("<?php echo SUBJECT_TO_CLASS_ONE?>");
      return false;
   }

   var j=0;
   for(var i=1;i<formx.length;i++){

      if(formx.elements[i].type=="checkbox"){
           fl=0;

         if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){


            //check for numeric value

            internalMarks= "internalMarks"+formx.elements[i].value;
            internalMarksValue = document.getElementById(internalMarks).value;

            //check for numeric value
            if(!isInteger(internalMarksValue)){

               messageBox("<?php echo ENTER_NON_NUMERIC?>");
               document.getElementById(internalMarks).className = 'inputboxRed';
               document.getElementById(internalMarks).focus();
               return false;
            }
            else{
               document.getElementById(internalMarks).className = 'inputbox1';
            }

            externalMarks= "externalMarks"+formx.elements[i].value;
            externalMarksValue = document.getElementById(externalMarks).value;

            //check for numeric value
            if(!isInteger(externalMarksValue)){

               messageBox("<?php echo ENTER_NON_NUMERIC?>");
               document.getElementById(externalMarks).className = 'inputboxRed';
               document.getElementById(externalMarks).focus();
               return false;
            }
            else{
               document.getElementById(externalMarks).className = 'inputbox1';
            }

         }
      }
   }
    insertForm();
   return false;
}

function doAll(){

   formx = document.listForm;
   if(formx.checkbox2.checked){
      for(var i=1;i<formx.length;i++){
         if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            formx.elements[i].checked=true;
         }
      }
   }
   else{
      for(var i=1;i<formx.length;i++){
         if(formx.elements[i].type=="checkbox"){
            formx.elements[i].checked=false;
         }
      }
   }
}

function checkSelect(){

   var selected=0;
   formx = document.listForm;
   for(var i=1;i<formx.length;i++){
      if(formx.elements[i].type=="checkbox"){
         if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
            selected++;
         }
      }
   }
   if(selected==0)   {
      messageBox("<?php echo SUBJECT_TO_CLASS_ONE?>");
      return false;
   }
}

function CheckStatus(value){

   if(document.getElementById('optional'+value).checked){

      document.getElementById('hasParentCategory'+value).disabled=false;
   }else{

      document.getElementById('hasParentCategory'+value).checked=false;
      document.getElementById('hasParentCategory'+value).disabled=true;
   }
}



function validateTimeTableLabelForm(frm, act) {
               
    if(act=='Add') {
        var fieldsArray = new Array( new Array("labelName","<?php echo ENTER_LABEL_NAME; ?>"),
                                     new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"),
                                     new Array("toDate","<?php echo EMPTY_TO_DATE;?>")
                                    );
    }
    else if(act=='Edit') {
        var fieldsArray = new Array( new Array("labelName","<?php echo ENTER_LABEL_NAME; ?>"),
                                     new Array("fromDate1","<?php echo EMPTY_FROM_DATE;?>"),
                                     new Array("toDate1","<?php echo EMPTY_TO_DATE;?>")
                                    );
    }
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length <3 && fieldsArray[i][0]=='labelName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo LABEL_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"),"-._ ") && fieldsArray[i][0]=='labelName') {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC_LABEL; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
        
        if(act=='Add' && eval("frm.fromDate.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_FROM_DATE;?>");
            eval("frm.fromDate.focus();");
            return false;
            break; 
        }
        
        if(act=='Add' && eval("frm.toDate.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_TO_DATE;?>");
            eval("frm.toDate.focus();");
            return false;
            break; 
        }
        
        if(act=='Edit' && eval("frm.fromDate1.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_FROM_DATE;?>");
            eval("frm.fromDate1.focus();");
            return false;
            break; 
        }
        
        if(act=='Edit' && eval("frm.toDate1.value")=="0000-00-00") {
            messageBox ("<?php echo EMPTY_TO_DATE;?>");
            eval("frm.toDate1.focus();");
            return false;
            break; 
        }
        
        if(act=='Add' && !dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-')) {
                messageBox ("<?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate.focus();");
                return false;
                break;
         } 
         else if(act=='Edit' && !dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-')) {
                messageBox ("<?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate1.focus();");
                return false;
                break;
         } 
    }   
    
    
    if(act=='Add') {
        addTimeTableLabel();
        return false;
    }
    else if(act=='Edit') {
        editTimeTableLabel();
        return false;
    }
}



function addTimeTableLabel() {
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                    labelName: (trim(document.AddTimeTableLabel.labelName.value)),
                    fromDate:  (trim(document.AddTimeTableLabel.fromDate.value)),
                    toDate:    (trim(document.AddTimeTableLabel.toDate.value)),
                    isActive: (document.AddTimeTableLabel.isActive[0].checked ? 1 : 0 )
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
								    getTimeTableLabelResuts();
                             blankTimeTableLabelValues();
                         }
                      else if("<?php echo LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LABEL_ALREADY_EXIST ;?>"); 
                        document.AddTimeTableLabel.lebelName.focus();
                      }  
                      else if("<?php echo DATE_VALIDATION;?>" == trim(transport.responseText)){
                        messageBox("<?php echo DATE_VALIDATION ; ?>"); 
                        document.AddTimeTableLabel.fromDate.focus();
                       }  
                       else if("<?php echo EMPTY_FROM_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_FROM_DATE ; ?>"); 
                        document.AddTimeTableLabel.fromDate.focus();
                       } 
                       else if("<?php echo EMPTY_TO_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_TO_DATE ; ?>"); 
                        document.AddTimeTableLabel.toDate.focus();
                       } 
                        else if("<?php echo FROM_TO_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo FROM_TO_ALREADY_EXIST ; ?>"); 
                        document.AddTimeTableLabel.fromDate.focus();
                        }
                         else {
                             hiddenFloatingDiv('AddTimeTableLabel');
								    getTimeTableLabelResuts();
                             
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A AddTimeTable Label
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTimeTableLabel(id) {

         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
			clearTimeTableText();
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {labelId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getTimeTableLabelResuts();
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddTimeTableLabel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankTimeTableLabelValues() {
   document.AddTimeTableLabel.labelName.value = '';
   document.AddTimeTableLabel.fromDate.value = '';
   document.AddTimeTableLabel.toDate.value = '';
   document.AddTimeTableLabel.isActive[0].checked =true;
   document.AddTimeTableLabel.labelName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSROUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTimeTableLabel() {
    
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
              method:'post',
              parameters: {labelId: (document.EditTimeTableLabel.labelId.value),
              labelName: (trim(document.EditTimeTableLabel.labelName.value)), 
              fromDate1: (trim(document.EditTimeTableLabel.fromDate1.value)), 
              toDate1: (trim(document.EditTimeTableLabel.toDate1.value)), 
              isActive: (document.EditTimeTableLabel.isActive[0].checked ? 1 : 0 )
             },
             onCreate: function() {
                 showWaitDialog(true);
                 
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditTimeTableLabel');
								 getTimeTableLabelResuts();
								  blankTimeTableLabelValues();
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo LABEL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo LABEL_ALREADY_EXIST ; ?>"); 
                        document.EditTimeTableLabel.labelName.focus();
                    }  
                    else if("<?php echo EMPTY_FROM_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_FROM_DATE ; ?>"); 
                        document.EditTimeTableLabel.fromDate1.focus();
                       } 
                       else if("<?php echo EMPTY_TO_DATE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo EMPTY_TO_DATE ; ?>"); 
                        document.EditTimeTableLabel.toDate1.focus();
                       } 
                    else if("<?php echo DATE_VALIDATION;?>" == trim(transport.responseText)){
                        messageBox("<?php echo DATE_VALIDATION ; ?>"); 
                        document.EditTimeTableLabel.fromDate1.focus();
                    }  
                     else if("<?php echo FROM_TO_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo FROM_TO_ALREADY_EXIST ; ?>"); 
                        document.EditTimeTableLabel.fromDate1.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditTimeTableLabel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.09.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateTimeTableLabelValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/TimeTableLabel/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {labelId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditTimeTableLabel');
                        messageBox("<?php echo LABEL_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   document.EditTimeTableLabel.labelName.value = j.labelName;
                   document.EditTimeTableLabel.fromDate1.value = j.startDate;  
                   document.EditTimeTableLabel.toDate1.value = j.endDate;  
                   document.EditTimeTableLabel.isActive[0].checked = (j.isActive=="1" ? true : false) ;
                   document.EditTimeTableLabel.isActive[1].checked = (j.isActive=="1" ? false : true) ;
                   document.EditTimeTableLabel.labelId.value = j.timeTableLabelId;
                   document.EditTimeTableLabel.labelName.focus();
            },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/PromoteStudentsAdvanced/listPromoteStudentsAdvanced.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="JavaScript">
<!--
   getClassesForPromotion();
   getClassesForSubjects();
   getTimeTableLabelResuts();
//-->
</script>
</body>
</html>

<?php

//$History: promoteStudentsAdvanced.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:32p
//Created in $/LeapCC/Interface
//file added for new interface of session end activities




?>
