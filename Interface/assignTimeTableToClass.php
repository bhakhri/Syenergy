<?php
//-------------------------------------------------------
// Purpose: To generate assign time table label to class from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssociateTimeTableToClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign time table to class</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
                               new Array('srNo','#','width="3%"','',false), 
                               new Array('className','Class Name','width="94%"','',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TimeTable/scAjaxInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
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

function getClasses() {

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
   else {
	  // document.getElementById('saveDiv').style.display='';
	   document.getElementById('saveDiv1').style.display='';
	   document.getElementById('showTitle').style.display='';	 	
	   document.getElementById('showData').style.display='';	 
       sendReq(listURL,divResultName,'listForm',''); 
   }      
		 
}

function printReport() {
	form = document.listForm;
	var name = document.getElementById('classId');
	path='<?php echo UI_HTTP_PATH;?>/scAssignCourseToClassPrint.php?class='+form.classId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&className='+name.options[name.selectedIndex].text;
	window.open(path,"subjectToClassReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
}

/* function to print all subject to class report*/
function printCourseToClassCSV() {

	form = document.listForm;
	var name = document.getElementById('classId');
	path='<?php echo UI_HTTP_PATH;?>/scAssignCourseToClassCSV.php?class='+form.classId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&className='+name.options[name.selectedIndex].text;

	document.getElementById('generateCSV').href=path;
	document.getElementById('generateCSV1').href=path;
}

function clearText(){

  //  document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv1').style.display='none';	 	
	document.getElementById('showTitle').style.display='none';	 	
	document.getElementById('showData').style.display='none';
	document.getElementById('results').innerHTML="";
}

function insertForm() {
 
   formx = document.listForm;      
   pars = generateQueryString('listForm');
  
	 url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/initLabelAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 //parameters: $('listForm').serialize(true),
         parameters: pars,  
         asynchronous:false,                         
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 messageBox(trim(transport.responseText));
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function validateAddForm(frm, act) {
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
		alert("Please select atleast 1 record to delete!");
		return false;
	}
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/timeTableToClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: assignTimeTableToClass.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/09    Time: 6:03p
//Updated in $/LeapCC/Interface
//Fixed Bug no  0000644
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/18/09    Time: 11:54a
//Updated in $/LeapCC/Interface
//Modified module so that incactive time table labels cannot be
//associated with current class
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/08/08   Time: 6:26p
//Updated in $/Leap/Source/Interface
//changed folder name
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:42p
//Updated in $/Leap/Source/Interface
//applied role level access
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/30/08    Time: 6:12p
//Created in $/Leap/Source/Interface
//intial checkin
 
?>