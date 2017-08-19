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
define('MODULE','AssignCourseToClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject to Class</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
                               new Array('srNo','#','width="3%"','',false),
                               new Array('subjectCode','Code','width="25%"','',true),
                               new Array('subjectName','Subject Name','width="50%"','',true),
                               new Array('isAlternate','Alternate Subject','width="8%"','',false),
                               new Array('subjectTypeName','Type','width="8%"','',true) ,
                               new Array('Optional','Optional','width="8%"','',false),
                               new Array('hasParentCategory1','Major/Minor','width="8%"','',false),
                               new Array('Offered','Offered','width="6%"','',false),
                               new Array('noofcredits','Credits','width="6%"','align="right"',false),
                               new Array('internalMarks1','Internal Marks','width="14%"','align="right"',false),
                               new Array('externalMarks1','External Marks','width="14%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SubjectToClass/ajaxInitList.php';
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
	path='<?php echo UI_HTTP_PATH;?>/assignSubjectToClassPrint.php?class='+form.classId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&className='+name.options[name.selectedIndex].text;
	window.open(path,"subjectToClassReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
}

/* function to print all subject to class report*/
function printCourseToClassCSV() {

	form = document.listForm;
	var name = document.getElementById('classId');
	path='<?php echo UI_HTTP_PATH;?>/assignSubjectToClassCSV.php?class='+form.classId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&className='+name.options[name.selectedIndex].text;//+'&subjectDetail='+form.subjectDetail.value;

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
function subjectQuery(frm){
	
	var formx = document.forms[frm];
	len = formx.length;
	labelId = document.getElementById('labelId').value;
	classId = document.getElementById('classId').value; 
	searchSubjectCode = document.getElementById('searchSubjectCode').value;
	listSubject = formx.elements['listSubject'].value;
	
	if(formx.elements['allSubject'][0].checked){
		allSubject = formx.elements['allSubject'][0].value;
	}
	else{
		allSubject = formx.elements['allSubject'][1].value;
	}
	queryString = 'labelId='+labelId+'&classId='+classId+'&searchSubjectCode'+searchSubjectCode+'&listSubject'+listSubject+'&allSubject'+allSubject;
	
	for(var i=0;i<len;i++) {
		if(formx.elements[i].type=="checkbox"){
			  fl=0;

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){

				chbValue=formx.elements[i].value;
				
				
				
				isAlternate= "isAlternate"+formx.elements[i].value;
				optional= "optional"+formx.elements[i].value;
				hasParentCategory= "hasParentCategory"+formx.elements[i].value;
				offered= "offered"+formx.elements[i].value;
				
				
				
				credit= "credit"+formx.elements[i].value;
				creditValue = document.getElementById(credit).value;
				
				internalMarks= "internalMarks"+formx.elements[i].value;
				internalMarksValue = document.getElementById(internalMarks).value;


				externalMarks= "externalMarks"+formx.elements[i].value;
				externalMarksValue = document.getElementById(externalMarks).value;
				
				if(queryString!='') {
					queryString +='&';
				}  
				queryString +="&chb[]="+chbValue+"&"+credit+"="+creditValue+"&"+internalMarks+"="+internalMarksValue+"&"+externalMarks+"="+externalMarksValue;
				if(formx.elements[isAlternate].checked){
					isAlternateValue ='1';
					queryString +="&"+isAlternate+"="+isAlternateValue;
				}
				
				if(formx.elements[optional].checked){
					optionalValue ='1';
					queryString +="&"+optional+"="+optionalValue;
				}
				
				if(formx.elements[hasParentCategory].checked){
					hasParentCategoryValue ='1';
					queryString +="&"+hasParentCategory+"="+hasParentCategoryValue;
				}
				
				if(formx.elements[offered].checked){
					offeredValue ='1';
					queryString +="&"+offered+"="+offeredValue;
				}
				
			}
		}
	
	}
	
	return queryString;
}
function insertForm() {
	
	 par=subjectQuery('listForm');
	 url = '<?php echo HTTP_LIB_PATH;?>/SubjectToClass/initAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: par,
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

				credit= "credit"+formx.elements[i].value;
				creditValue = document.getElementById(credit).value;

				//check for numeric value
				if(!isDecimal(creditValue)){

					messageBox("<?php echo ENTER_NON_NUMERIC?>");
					document.getElementById(credit).className = 'inputboxRed';
					document.getElementById(credit).focus();
					return false;
				}
				else{
					document.getElementById(credit).className = 'inputbox1';
				}

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

    var typeFormat = 'cs';

    var url ='<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTimeTableValues.php';

    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,
         parameters: {timeTabelId: timeTableLabelId,
                      timeTableType: timeTableType,
                      typeFormat: typeFormat },
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
<SCRIPT LANGUAGE="JavaScript">
	showWaitDialog(true);
</SCRIPT>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/SubjectToClass/listSubjectToClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
	hideWaitDialog(true);
</SCRIPT>
</body>
</html>
<?php
// $History: assignSubjectToClass.php $
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 09-12-14   Time: 11:58a
//Updated in $/LeapCC/Interface
//Changed Label "Has Category" to "Major/Minor"
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Interface
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 09-08-18   Time: 3:07p
//Updated in $/LeapCC/Interface
//Fixed 1090,1089,1088,1058 bugs
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/11/09    Time: 11:52a
//Updated in $/LeapCC/Interface
//0001009: Associate Subject to Class (Admin) > Print window Caption
//should be �Subject to Class Report Print� as clicked on �Print� button
//0001010: Associate Subject to Class (Admin) > Provide Save button on
//the right top of the grid.
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/10/09    Time: 11:13a
//Updated in $/LeapCC/Interface
//Fixed bug no 984,982
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/20/09    Time: 12:56p
//Updated in $/LeapCC/Interface
//Added "hasParentCategory" in subject to class module
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//added define variable for Role Permission
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 4/06/09    Time: 12:15p
//Updated in $/LeapCC/Interface
//Updated with subject type
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/12/09    Time: 10:23a
//Updated in $/LeapCC/Interface
//added required field and centralized message
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/05/09    Time: 6:00p
//Updated in $/LeapCC/Interface
//added internaltotalmarks and externaltotalmarks field
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Interface
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Interface
//updated formatting and added comments
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/25/08    Time: 12:04p
//Updated in $/Leap/Source/Interface
//done centralized messages
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/21/08    Time: 11:15a
//Updated in $/Leap/Source/Interface
//updated the formatting
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:35p
//Updated in $/Leap/Source/Interface
//updated print function
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/18/08    Time: 3:32p
//Updated in $/Leap/Source/Interface
//updated print report function for IE
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/14/08    Time: 2:09p
//Updated in $/Leap/Source/Interface
//added print report
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/11/08    Time: 12:29p
//Updated in $/Leap/Source/Interface
//updated cleartext function
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Interface
//updated the formatting and other issues
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:02p
//Updated in $/Leap/Source/Interface
//updated the functionality to map subject with class.
//made ajax based and removed study period and batch from search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/08    Time: 12:40p
//Updated in $/Leap/Source/Interface
//optimize the query
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:54p
//Created in $/Leap/Source/Interface
//intial checkin
?>