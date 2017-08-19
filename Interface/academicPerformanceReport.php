<?php 
//-------------------------------------------------------
//  This File contains consolidated report for the student.
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','StudentAcademicReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Academic Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("swfobject.js");
?>

<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="2%"','',false), 
                               new Array('rollNo','Roll No.','width=10%','',true), 
                               new Array('universityRegNo','Univ. Reg No.','width="12%"','',true),
                               new Array('studentName','Student Name','width="20%"','',true), 
                               new Array('studentMobileNo','Mobile','width="8%"','align="left"',false), 
                               new Array('studentEmail','Email','width="8%"','align="left"',false), 
                               new Array('studentGender','Gender','width="5%"','align="left"',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initInternalPerformanceReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'testWiseMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
document.getElementById('resultRow').style.display=''; 
	document.getElementById('resultRow2').style.display='';
	var fieldsArray = new Array(new Array("timeTable","<?php echo SELECT_TIME_TABLE;?>"),new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("testCategoryId","<?php echo SELECT_TEST_TYPE_CATEGORY;?>" ));
	var len = fieldsArray.length;
	for(i=0;i<len;i++) {
		if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
			messageBox(fieldsArray[i][1]);
			eval("frm."+(fieldsArray[i][0])+".focus();");
			return false;
			break;
		}
	}
	form = document.testWiseMarksReportForm;
	var timeTable = form.timeTable.value;
	var degree	  = form.degree.value;
	var testCategoryId = form.testCategoryId.value;
	 
	var name = document.getElementById('timeTable'); 
	var name1 = document.getElementById('degree'); 
	var name2 = document.getElementById('testCategoryId'); 

	var pars = '&timeName='+name.options[name.selectedIndex].text+'&className='+name1.options[name1.selectedIndex].text+'&testTypeName='+name2.options[name2.selectedIndex].text;
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+pars);
	document.getElementById('resultRow2').style.display='';
}

function validateAddForm1(frm, act) {
    
	var selected=0;
	formx = document.testWiseMarksReportForm;
	for(var i=1;i<formx.length;i++){
    	if(formx.elements[i].type=="checkbox"){
          if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
			{selected++;}
		}
	}
	if(selected==0){
		alert("<?php echo STUDENT_TO_ONE?>");
		return false;
	}
	 
    printDetailReport();
	return false;
} 
function printDetailReport() {
 

	var selected=0;
    var studentCheck='';
    formx = document.testWiseMarksReportForm;

    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
    }
	 
	var name = document.getElementById('timeTable');
	var name1 = document.getElementById('degree');
	var name2 = document.getElementById('testCategoryId');

   var signature=0;
   var address=0;
   var photo=0;
   var signatureContents='';
   
   if(document.testWiseMarksReportForm.signature.checked==true) {
     signature = 1;
     signatureContents = document.testWiseMarksReportForm.signatureContents.value;
   }
   if(document.testWiseMarksReportForm.addressChk.checked==true) {
     if(document.testWiseMarksReportForm.address[0].checked==true) {
       address = 1;   // Correspondence Address
     }  
     else {
       address = 2;   // Permanent Address
     }
   }
   if(document.testWiseMarksReportForm.photo.checked==true) {
     photo = 1;
   }
	
	var pars = 'timeTable='+document.getElementById('timeTable').value+'&degree='+document.getElementById('degree').value+'&testCategoryId='+document.getElementById('testCategoryId').value+'&timeName='+name.options[name.selectedIndex].text+'&className='+name1.options[name1.selectedIndex].text+'&categoryName='+name2.options[name2.selectedIndex].text+'&studentCheck='+studentCheck+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value;
    pars +='&signature='+escape(signature)+'&address='+escape(address)+'&photo='+escape(photo)+'&signatureContents='+escape(signatureContents);
    
	path='<?php echo UI_HTTP_PATH;?>/internalPerformanceReportPrint.php?'+pars;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900,height=600");
}

function hideResult(){
	document.getElementById('resultRow').style.display='none'; 
	document.getElementById('resultRow2').style.display='none';
}

function getLabelClass(){
	document.getElementById('resultRow').style.display='none'; 
	document.getElementById('resultRow2').style.display='none';
	form = document.testWiseMarksReportForm;
	var timeTable = form.timeTable.value;
	 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelClass.php';
	var pars = 'timeTable='+timeTable;
	document.testWiseMarksReportForm.degree.length = null; 
	addOption(document.testWiseMarksReportForm.degree, '', 'Select');
	new Ajax.Request(url,
   {
	 method:'post',
	 parameters: pars,
	 asynchronous:false,
	 onCreate: function(){
		 showWaitDialog(true);
	 },
	 onSuccess: function(transport){
		   hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			 
			document.testWiseMarksReportForm.degree.length = null;
			addOption(document.testWiseMarksReportForm.degree, '', 'Select');
			if (len > 0) {
				//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
			}
			for(i=0;i<len;i++) { 
				addOption(document.testWiseMarksReportForm.degree, j[i].classId, j[i].className);
			}
			// now select the value
			//document.testWiseMarksReportForm.groupId.value = j[0].groupId;
	   },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function doAll(){
    formx = document.testWiseMarksReportForm;
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

function setAddress() {
    
   document.getElementById('addressHide').style.display='none'; 
   if(document.testWiseMarksReportForm.addressChk.checked) {
     document.getElementById('addressHide').style.display=''; 
   } 
}

function setSignature() {

   document.getElementById('signatureContents').value='';
   document.getElementById('signatureHide').style.display='none'; 
   if(document.testWiseMarksReportForm.signature.checked) {
     document.getElementById('signatureHide').style.display=''; 
   } 
   
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listAcademicPerformanceReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: academicPerformanceReport.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Interface
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:31p
//Updated in $/LeapCC/Interface
//Gurkeerat: Updated access defines
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/02/09    Time: 11:37a
//Updated in $/LeapCC/Interface
//Updated with validation message
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/02/09    Time: 10:48a
//Created in $/LeapCC/Interface
//Intial checkin
?>
