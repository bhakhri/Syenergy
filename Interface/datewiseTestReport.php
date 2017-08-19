<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in test time period Form
//
//
// Author :Arvind Singh Rawat
// Created on : 22-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DateWiseTestReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Date Wise Test Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('testType','Test Type','width=12%','',true), 
                               new Array('subjectName','Subject Name','width="22%"','align="left"',true),
                               new Array('subjectCode','Subject Code','width="12%"','align="left"',true),
                               new Array('groupName','Group','width="12%"','align="left"',true), 
                               new Array('employeeName','Employee','width="18%"','align="left"',true),
                               new Array('testDate','Test Date','width="10%"','align="center"',true)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initDatewiseTestReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentAttendanceForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'testType';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"), 
                                new Array("toDate","<?php echo EMPTY_TO_DATE;?>"),
                                new Array("degreeId","<?php echo SELECT_DEGREE;?>"),
                                new Array("subjectId","<?php echo SELECT_SUBJECT;?>"),
                                new Array("groupId","<?php echo SELECT_GROUP;?>"));
                                
                                
    
if (document.getElementById("degreeId").value != "all"){
	
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
           if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
                messageBox ("<?php echo DATE_CONDITION;?>");
                eval("frm.fromDate.focus();");
                return false;
                break;
         } 
    }
   }
   else{
   	  if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
                messageBox ("<?php echo DATE_CONDITION;?>");
                eval("frm.fromDate.focus();");
                return false;
               
         } 
   	
   	
   }
	//openStudentLists(frm.name,'rollNo','Asc');    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';                                                 
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function printReport() {
    form = document.studentAttendanceForm;  
    subjectName=document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    groupName=document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    testTypeName=document.getElementById('testTypeCategoryId').options[document.getElementById('testTypeCategoryId').selectedIndex].text;
    className=document.getElementById('degreeId').options[document.getElementById('degreeId').selectedIndex].text;

    $str = "&subjectName="+subjectName+"&groupName="+groupName+"&testTypeName="+testTypeName+"&className="+className;
    path='<?php echo UI_HTTP_PATH;?>/datewiseTestPrint.php?testTypeCategoryId='+form.testTypeCategoryId.value+'&toDate='+form.toDate.value+'&fromDate='+form.fromDate.value+'&classId='+form.degreeId.value+'&groupId='+form.groupId.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+$str;
    
    a = window.open(path,"DatewiseTestReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printReportCSV() {
    form = document.studentAttendanceForm;  
    subjectName=document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    groupName=document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    testTypeName=document.getElementById('testTypeCategoryId').options[document.getElementById('testTypeCategoryId').selectedIndex].text;
    className=document.getElementById('degreeId').options[document.getElementById('degreeId').selectedIndex].text;

    $str = "&subjectName="+subjectName+"&groupName="+groupName+"&testTypeName="+testTypeName+"&className="+className;
    path='<?php echo UI_HTTP_PATH;?>/datewiseTestPrintCSV.php?testTypeCategoryId='+form.testTypeCategoryId.value+'&toDate='+form.toDate.value+'&fromDate='+form.fromDate.value+'&classId='+form.degreeId.value+'&groupId='+form.groupId.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+$str;
    
    window.location=path;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}


function getSubjectGroups() {
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetSubjectGroups.php';
    hideResults();
    form = document.studentAttendanceForm;    
  
    document.studentAttendanceForm.groupId.length = null;       
    addOption(form.groupId, '', 'Select');
  
    if (form.degreeId.value=='') {
        return false;
    }
     
    if (form.subjectId.value=='All') {
       subjectId="";
    }
    else {
       subjectId=form.subjectId.value;
    }
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {  
                       classId: (document.studentAttendanceForm.degreeId.value),
                       subjectId: subjectId 
                     },
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;

            document.studentAttendanceForm.groupId.length = null;       
            if (len > 0) {
                addOption(form.groupId, 'all', 'All');
                for(i=0;i<len;i++) { 
                    addOption(form.groupId, j[i].groupId, j[i].groupName);
                }
            }
            else {
               addOption(form.groupId, '', 'Select');    
            }
            // now select the value
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function getSubjectData() {
    hideResults();
   
    form = document.studentAttendanceForm;    
    
    form.subjectId.length = null;
    addOption(form.subjectId, 'all', 'Select');
    
    form.groupId.length = null;
    addOption(form.groupId, 'all', 'Select');
    
    if(form.degreeId.value=='') {
       //messageBox(<?php "Select Degree"?>);
       form.degreeId.focus();
       return false;
    }
 
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetSubject.php';
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {
                        classId: (document.studentAttendanceForm.degreeId.value) 
                     },  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            
            var ret=trim(transport.responseText).split('!~!~!');  
            
            if(ret.length > 0 ) {
                var j = eval('(' + ret[0] + ')');
                len = j.length;
                form.subjectId.length = null;
                if (len > 0) {
                  addOption(form.subjectId, 'all', 'All');
                }
                else {
                  addOption(form.subjectId, 'all', 'Select');  
                }
                for(i=0;i<len;i++) { 
                  //if(j[i].hasAttendance==1) {   
                    addOption(form.subjectId, j[i].subjectId, j[i].subjectCode);
                  //}
                }
            
                var j = eval('(' + ret[1] + ')');
                len = j.length;
                form.groupId.length = null;
                if (len > 0) {
                  addOption(form.groupId, 'all', 'All');
                }
                else {
                  addOption(form.groupId, 'all', 'Select');  
                }
                for(i=0;i<len;i++) {        
                   addOption(form.groupId, j[i].groupId, j[i].groupName);
                } 
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

window.onload=function() {
   getSubjectData();
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/datewiseTestReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: datewiseTestReport.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/24/09   Time: 3:16p
//Updated in $/LeapCC/Interface
//report heading name udpated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Interface
//class base format updated
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/07/09    Time: 5:43p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/25/09    Time: 4:43p
//Updated in $/LeapCC/Interface
//report format update 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/19/09    Time: 5:21p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 5/19/09    Time: 2:09p
//Updated in $/Leap/Source/Interface
//search for & condition update
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 3/06/09    Time: 6:36p
//Updated in $/Leap/Source/Interface
//changed label name
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:57a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Arvind       Date: 10/23/08   Time: 11:44a
//Updated in $/Leap/Source/Interface
//removed getSubjectClasses()
//
//*****************  Version 2  *****************
//User: Arvind       Date: 10/22/08   Time: 5:46p
//Updated in $/Leap/Source/Interface
//fields data display corredcted
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/22/08   Time: 5:41p
//Created in $/Leap/Source/Interface
//initial checkin


?>
