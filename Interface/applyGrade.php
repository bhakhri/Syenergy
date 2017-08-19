<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in attendanceMissedReport Form
//
//
// Author :Ajinder Singh
// Created on : 23-oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyGrade');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Apply Grade </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), new Array('subjectCode','Course','width=25%  align=left',' align=left',false), new Array('sectionName','Section','width="20%"  align=left',' align=left',false), new Array('testName','Test Name','width="20%"  align=left',' align=left',false));

 //This function Validates Form
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'marksNotEnteredForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'Asc';
pendingStudents = 0;
 //This function Validates Form
 

function getLabelClass(){

	form = document.marksNotEnteredForm;
	var timeTable = form.labelId.value;

    document.getElementById('gradeDiv').innerHTML=''; 
    document.getElementById('gradeRow').style.display='none';
    document.getElementById('msgRow').style.display='none';
    form.gadeLabelId.value = '';
    
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelMarksTransferredClass.php';
	var pars = 'timeTable='+timeTable;
	form.degreeId.length = null;
	addOption(form.degreeId, '', 'Select');
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

				form.degreeId.length = null;
				addOption(form.degreeId, '', 'Select');
				if (len > 0) {
				 //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) {
				  addOption(form.degreeId, j[i].classId, j[i].className);
				}
				// now select the value
				//form.degreeId.value = j[0].classId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}


function getClassSubjects() {
	form = document.marksNotEnteredForm;
	var degree = form.degreeId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjects.php';
	var timeTable = form.labelId.value;
	var pars = 'timeTable='+timeTable+'&degree='+degree;
    
    document.getElementById('gradeDiv').innerHTML=''; 
    document.getElementById('gradeRow').style.display='none';
    document.getElementById('msgRow').style.display='none';
    form.gadeLabelId.value = '';
    
	if (degree == '') {
		form.subjectId.length = null;
		addOption(form.subjectId, '', 'Select');
		return false;
	}
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
				form.subjectId.length = null;
				addOption(form.subjectId, '', 'Select');
				/*
				if (len > 0) {
					addOption(document.testWiseMarksReportForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) {
					addOption(form.subjectId, j[i].subjectId, j[i].subjectCode);
				}
				// now select the value
				//form.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function validateAddForm() {
	hideResults();
	form = document.marksNotEnteredForm;
    /*
    totalDegreeId = form.elements['degreeId[]'].length;
    selectedDegrees=0;
    for(i=0;i<totalDegreeId;i++) {
        if (form.elements['degreeId[]'][i].selected == true) {
            selectedDegrees++;
            break;
        }
    }
    */
    
	if (form.labelId.value == '') {
		messageBox("<?php echo SELECT_TIMETABLE;?>");
		form.labelId.focus();
		return false;
	}
	
	if (form.degreeId.value == '') {
		messageBox("<?php echo SELECT_CLASS;?>");
		document.getElementById('degreeId').focus();
		return false;
	}
    
    if (form.subjectId.value == '') {
        messageBox("<?php echo SELECT_SUBJECT;?>");
        form.subjectId.focus();
        return false;
    }
    
	if (form.gadeLabelId.value == '') {
		messageBox("Please select grading");
		document.getElementById('gadeLabelId').focus();
		return false;
	}


	if (form.gradingFormula.value == '') {
		messageBox("<?php echo SELECT_ROUNDING;?>");
		form.gradingFormula.focus();
		return false;
	}
    
    
    formx = document.marksNotEnteredForm;   
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    var gradeFrom=0;
    for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('hiddenGradeId[]')>-1) {
         var id = trim(obj[i].value); 
         var gradeValue = eval("trim(document.getElementById('ttGradeTo"+id+"').value)");
         var ttGradeFrom = eval("trim(document.getElementById('ttGradeFrom"+id+"').value)"); 
         var ttGradeTo = eval("trim(document.getElementById('ttGradeTo"+id+"').value)"); 
         if(gradeValue=='') {
            messageBox("Please Enter To Value");
            eval("document.getElementById('ttGradeTo"+id+"').focus();"); 
            eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
            return false;    
         }
         if(!isDecimal(gradeValue)) {                          
           messageBox("Only for decimal value entered");  
           eval("document.getElementById('ttGradeTo"+id+"').focus();"); 
           eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
           return false;    
         }
         if(parseFloat(ttGradeFrom,2) > parseFloat(ttGradeTo,2) ) {
            messageBox("Grading Range To cannot smaller than Grading Range From");               
            eval("document.getElementById('ttGradeTo"+id+"').focus();"); 
            eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
            return false;
         }
      } // End for if Condition
   } // End for Loop
   
	
	showGrades();
    return false;
//	showGraph();
//	showSliders();
//	saveGrades();

//	openStudentLists(frm.name,'class','Asc');
}



function getTTSubjects() {
	hideResults();
	form = document.marksNotEnteredForm;
	if (form.labelId.value == '') {
		return false;
	}
	form.subjectId.value='';
	form.subjectId.length = 1;
	var url = '<?php echo HTTP_LIB_PATH;?>/ScStudent/scGetTTSubjects.php';
	var pars = 'labelId='+form.labelId.value;


	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			//alert(j['subjects'].length);
			len = j['subjects'].length;
			form.subjectId.length = null;
			//addOption(form.sectionId, '', 'Select');
			addOption(form.subjectId, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.subjectId, j['subjects'][i].subjectId, j['subjects'][i].subjectCode);
			}
			// now select the value
			form.subjectId.value = j['subjects'][0].subjectId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getSubjectTransferredClasses() {
	hideResults();
	form = document.marksNotEnteredForm;
	form.degreeId.value='';
	if (form.labelId.value == '' || form.subjectId.value == '') {
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/ScStudent/scGetSubjectTransferredClasses.php';
	var pars = 'subjectId='+form.subjectId.value+'&labelId='+form.labelId.value;
	if (form.subjectId.value=='') {
		form.sectionId.length = null;
		addOption(form.sectionId, '', 'Select');
		form.degreeId.length = null;
		addOption(form.degreeId, '', 'Select');
		return false;
	}

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.degreeId.length = null;
			//addOption(form.degreeId, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.degreeId, j[i].classId, j[i].className);
			}
			// now select the value
			form.degreeId.value = j[0].degreeId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}
/*
function validateAddForm() {
	hideResults();
	form = document.marksNotEnteredForm;

	if (form.labelId.value == '') {
		messageBox("<?php echo SELECT_TIMETABLE;?>");
		form.labelId.focus();
		return false;
	}
	if (form.subjectId.value == '') {
		messageBox("<?php echo SELECT_COURSE;?>");
		form.subjectId.focus();
		return false;
	}

	totalDegreeId = form.elements['degreeId[]'].length;
	selectedDegrees=0;
	for(i=0;i<totalDegreeId;i++) {
		if (form.elements['degreeId[]'][i].selected == true) {
			selectedDegrees++;
			break;
		}
	}
	if (selectedDegrees == 0) {
		messageBox("<?php echo SELECT_DEGREE;?>");
		document.getElementById('degreeId').focus();
		return false;
	}
	if (form.gadeLabelId.value == '') {
		messageBox("<?php echo SELECT_GRADE_LABEL;?>");
		form.gadeLabelId.focus();
		return false;
	}
	if (form.gradingFormula.value == '') {
		messageBox("<?php echo SELECT_ROUNDING;?>");
		form.gradingFormula.focus();
		return false;
	}


	document.getElementById("nameRow").style.display='';
	document.getElementById("resultRow").style.display='';
	showGrades();
//	saveGrades();

//	openStudentLists(frm.name,'class','Asc');
}
*/

function showGrades() {
	//hideResults();
	form = document.marksNotEnteredForm;
	queryString = generateQueryString('marksNotEnteredForm');
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showGradesNew.php';
	var pars = queryString;//'subjectId='+form.subjectId.value+'&gadeLabelId='+form.gadeLabelId.value;
	document.getElementById("nameRow").style.display='';
	document.getElementById("resultRow").style.display='';

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');

				var tableData = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">';
//				tableData += '<tr class="rowheading"><td width="8%" class="searchhead_text reportBorder">Students</td><td width="20%" class="searchhead_text reportBorder">Grade</td></tr>';
				total = j['studentArray'].length;
				pendingStudents = j['pendingStudents'];
				mgpaMarks = 10;
				totalStudents = 0;
				nominatorValue = 0;

				for (i=0;i<total ; i++) {
					mgpaStudents = parseInt(j['studentArray'][i]['studentCount']);
                    mgpaMarks = parseInt(j['studentArray'][i]['gradePoints']);
                    if(mgpaStudents==null) {
                      mgpaStudents=0;  
                    }
                    if(mgpaMarks==null) {
                      mgpaMarks=0;  
                    }
					totalStudents  += mgpaStudents;
					nominatorValue += (mgpaStudents * mgpaMarks);
					tableData += '<tr><td width="100%" class="" colspan="1">Students with Grade: '+j['studentArray'][i]['gradeLabel']+' are : '+mgpaStudents+'</tr>';
				}
				mgpa = Math.round((nominatorValue / totalStudents)*100)/100;
				tableData += '<tr><td width="8%" class="searchhead_text ">MGPA: '+mgpa+'</td></tr>';
				tableData += '</table>';
				document.getElementById("resultsDiv").innerHTML = tableData;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function saveGrades() {
	gradeAssignmentConfirm = "<?php echo GRADE_ASSIGNMENT_CONFIRM;?>";
	if (pendingStudents > 0) {
		gradeAssignmentConfirm = "<?php echo PENDING_GRADE_ASSIGNMENT_CONFIRM;?>"
	}
	if(confirm(gradeAssignmentConfirm)) {
		form = document.marksNotEnteredForm;
		//var url = '<?php echo HTTP_LIB_PATH;?>/Student/saveGrades.php';
        var url = '<?php echo HTTP_LIB_PATH;?>/Student/saveGradesNew.php';
		queryString = generateQueryString('marksNotEnteredForm');
		var pars = queryString;//'subjectId='+form.subjectId.value+'&gadeLabelId='+form.gadeLabelId.value;

		new Ajax.Request(url,
		{
			method:'post',
			parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
					hideWaitDialog(true);
					messageBox(trim(transport.responseText));
					hideResults();
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}



    

function getGradeList() {
    
     var url = '<?php echo HTTP_LIB_PATH;?>/Student/getGradeValue.php';       
    
     var id = document.getElementById("gadeLabelId").value;
     if(id=='') {
       return false;  
     }
    
     queryString = 'id='+id;
     new Ajax.Request(url,
     {
          method:'post',
          asynchronous:false,
          parameters: queryString, 
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById("gradeRow").style.display='';  
               document.getElementById('msgRow').style.display='';
               document.getElementById('gradeDiv').innerHTML=trim(transport.responseText);
             } 
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
}

function hideResults() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   //document.getElementById('gradeRow').style.display='none';
   //document.getElementById('msgRow').style.display='none';
   //document.getElementById('gradeDiv').innerHTML=''; 
}

function hideResults1() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('gradeRow').style.display='none';
   document.getElementById('msgRow').style.display='none';
   document.getElementById('gradeDiv').innerHTML=''; 
}

function printReport() {
	form = document.marksNotEnteredForm;
	path='<?php echo UI_HTTP_PATH;?>/scCourseMarksTransferredPrint.php?subjectId='+form.subjectId.value+'&sectionId='+form.sectionId.value;
	window.open(path,"MarksNotEnteredReport","status=1,menubar=1,scrollbars=1, width=900");
}
window.onload = function () {
   getLabelClass(); 
   document.marksNotEnteredForm.labelId.focus();
}

function setGradeValue() {
    
    document.getElementById("nameRow").style.display='none';
    document.getElementById("resultRow").style.display='none';
    
    formx = document.marksNotEnteredForm;   
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    var gradeFrom=0;
    var isCheck=0;
    for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('hiddenGradeId[]')>-1) {
         var id = trim(obj[i].value); 
         var gradeValue = eval("trim(document.getElementById('ttGradeTo"+id+"').value)");
         if(gradeValue=='') {
           eval("document.getElementById('ttGradeTo"+id+"').className='htmlElement2'");     
         }
         if(!isDecimal(gradeValue)) {                          
           eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
         }
         if(isCheck==0) {   
           isCheck++;
         }
         else {
           eval("document.getElementById('ttGradeFrom"+id+"').value=gradeFrom");  
         }
         if(isDecimal(gradeValue)) {                          
            eval("document.getElementById('ttGradeTo"+id+"').className='htmlElement2'");   
            if(parseInt(gradeValue)==gradeValue) {
              gradeValue= parseFloat(gradeValue,2)+1;  
            }
            else {
              gradeValue= parseFloat(gradeValue,2)+parseFloat(.01,2);  
            }
            gradeFrom =  gradeValue;
         }
         if(gradeValue!='') {
             ttFrom = eval("document.getElementById('ttGradeFrom"+id+"').value");
             ttTo = eval("document.getElementById('ttGradeTo"+id+"').value");
             if(parseFloat(ttFrom,2)>parseFloat(ttTo,2)) {
               eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
             }
         }
     } // End for if Condition
   } // End for Loop
}
    

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listApplyGrade.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php

//$History: scApplyGrade.php $
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 2/09/09    Time: 5:41p
//Updated in $/Leap/Source/Interface
//fixed code change issue.
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 1/29/09    Time: 5:10p
//Updated in $/Leap/Source/Interface
//done the coding to make the flow work in both ways:
//1. Transfer - Grading - Promotion
//2. Promotion - Transfer - Grading
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/20/08   Time: 1:56p
//Updated in $/Leap/Source/Interface
//added defines for access level checks
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/14/08   Time: 4:43p
//Updated in $/Leap/Source/Interface
//added global variable pendingStudents for checking students for which
//marks have not been transferred
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/11/08   Time: 4:03p
//Updated in $/Leap/Source/Interface
//improved code and fetched gradewise points from db
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/10/08   Time: 4:27p
//Updated in $/Leap/Source/Interface
//removed unnecessary code
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/06/08   Time: 5:12p
//Updated in $/Leap/Source/Interface
//removed border from report data
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/03/08   Time: 3:44p
//Updated in $/Leap/Source/Interface
//added function showGrades(), to show the grades and mgpa
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/23/08   Time: 5:26p
//Updated in $/Leap/Source/Interface
//changed title text
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/23/08   Time: 5:14p
//Created in $/Leap/Source/Interface
//file added for applying grades to students
//


?>
