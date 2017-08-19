<?php
//-------------------------------------------------------
// Purpose: To generate assign subject to class from the database, and have add/edit/delete, search
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalCourseToClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Optional Subject to Class</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),new Array('srNo','#','width="3%"','',false), new Array('subjectCode','Code','width="10%"','',true),new Array('subjectName','Subject Name','width="20%"','',true),new Array('subjectTypeName','Subject Type','width="20%"','',true),new Array('categoryName','Subject Category','width="20%"','',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/OptionalSubjectToClass/ajaxInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
       displayWindow(dv,w,h);
       populateValues(id);
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
	else if(isEmpty(document.getElementById('mmSubjectId').value)){
       messageBox("<?php echo SELECT_SUBJECT?>");
	   document.getElementById('saveDiv').style.display='none';
	   document.getElementById('saveDiv1').style.display='none';
	   document.getElementById('saveDiv2').style.display='none';
	   document.getElementById('showTitle').style.display='none';
	   document.getElementById('showData').style.display='none';
	   document.getElementById('results').innerHTML=" ";
	   document.listForm.mmSubjectId.focus();
	   return false;
   }
   else{
	   document.getElementById('saveDiv').style.display='';
	   document.getElementById('saveDiv1').style.display='';
	   document.getElementById('saveDiv2').style.display='';
	   document.getElementById('showTitle').style.display='';
	   document.getElementById('showData').style.display='';
       sendReq(listURL,divResultName,'listForm','');
   }

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

function insertForm() {

	 url = '<?php echo HTTP_LIB_PATH;?>/OptionalSubjectToClass/initAdd.php';
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
	if(selected==0)	{
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

function getMMSubjects() {
    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false;
    }
    if(document.getElementById('classId').value=='') {
       document.getElementById('classId').focus();
       return false;
    }

    var timeTable = document.getElementById('labelId').value;
	 var rval=timeTable.split('~');
    var timeTableLabelId = rval[0];
    var timeTableType = rval[3];
	 var classId = document.getElementById('classId').value;

    var url ='<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetClassMMSubjects.php';

    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,
         parameters: {timeTabelId: timeTableLabelId,
                      classId: classId
							},
         onCreate: function(transport){
              showWaitDialog();
         },
         onSuccess: function(transport){
           hideWaitDialog();
           j = eval('('+transport.responseText+')');
           for(var c=0;c<j.length;c++) {
             var objOption = new Option(j[c].subjectCode,j[c].subjectId);
             document.getElementById('mmSubjectId').options.add(objOption);
           }
         },
         onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}

function populateClass(){

    document.getElementById('classId').length = null;
    addOption(document.getElementById('classId'), '', 'Select');

    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false;
    }

    var timeTable = document.getElementById('labelId').value;

    var rval=timeTable.split('~');
    var timeTableLabelId = rval[0];
    var timeTableType = rval[3];

    var typeFormat = 'mapped';

    var url ='<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTimeTableValues.php';

    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,
         parameters: {timeTabelId: timeTableLabelId,
                      timeTableType: timeTableType,
                      typeFormat: 'mapped' },
         onCreate: function(transport){
              showWaitDialog();
         },
         onSuccess: function(transport){
           hideWaitDialog();
           j = eval('('+transport.responseText+')');
           for(var c=0;c<j.length;c++) {
             var objOption = new Option(j[c].className,j[c].classId);
             document.getElementById('classId').options.add(objOption);
           }
         },
         onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}
window.onload=function() {

   populateClass();
}
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/OptionalSubjectToClass/listSubjectToClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: assignSubjectToClass.php $
//
?>