	<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
// Author :Parveen Sharma
// Created on : 16-02-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherSubstitutions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Display Teacher Substitutions Report</title>
<?php 
    require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
                            new Array('srNo',           '#',                 'width="2%"',   '', false), 
                            new Array('employeeName1',  "Teacher Name",      'width="18%"',  '', true) , 
                            new Array('contactNumber',  "Contact Number",    'width="18%"',  '', true), 
                            new Array('subjectName',    'Subject ',          'width="22%"',  '', true), 
                            new Array('periodNumber',   'Teaching Periods',  'width="15%"',  '', true),
                            new Array('periodFree',     'Free Periods',      'width="12%"',  '', false));
                            //new Array('periodFree1',    'Selected Periods',  'width="12%"',  '', false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxTeacherSubstitutions.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'employeeName1';
sortOrderBy  = 'ASC';

// ajax search results ---end ///
 
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       

//This function Validates Form 
function validateAddForm(form) {
    
    page=1;
    
    var timeTable = document.getElementById('labelId').value;
    var rval=timeTable.split('~');
    var timeTabelId=rval[0];    
    
    if(trim(document.getElementById('labelId').value)==""){
         messageBox("<?php echo SELECT_TIMETABLE; ?>");
         document.getElementById('labelId').focus();
         return false;
    } 
    
    if(rval[3]==1) {                // Weekly   
        if(trim(document.getElementById('employeeId').value)==""){
             messageBox("Please Select Teacher Name");
             document.getElementById('employeeId').focus();
             return false;
        }   
        if(trim(document.getElementById('daysOfWeek').value)==""){
             messageBox("Please Select Days");
             document.getElementById('daysOfWeek').focus();
             return false;
        }   
    }
    else if(rval[3]==2) {           // Daily
       if(trim(document.getElementById('employeeId1').value)==""){
         messageBox("Please Select Teacher Name");
         document.getElementById('employeeId1').focus();
         return false;
       }  
    }
    
    if(trim(document.getElementById('periodId').value)==""){
         messageBox("Please Select Periods");
         document.getElementById('periodId').focus();
         return false;
    }   
    
    hideDetails();
    
    periodIds = document.allDetailsForm.periodId.value;
    subjectIds = document.allDetailsForm.subjectId.value;
    
    if(rval[3]==1) {           // Weekly 
       timeTableType=1;
       employeeId = document.allDetailsForm.employeeId.value;
       daysOfWeek = document.allDetailsForm.daysOfWeek.value;
       param1='&timeTableType='+timeTableType+'&employeeId='+employeeId+'&daysOfWeek='+daysOfWeek;
    }
    else if(rval[3]==2) {      // Daily 
       timeTableType=2;
       employeeId = document.allDetailsForm.employeeId1.value;
       fromDate = document.allDetailsForm.fromDate.value;
       param1='&timeTableType='+timeTableType+'&employeeId='+employeeId+'&fromDate='+fromDate; 
    }  
    totalId = form.elements['subjectId[]'].length;
    var name = document.getElementById('subjectId');
    subjectId='';
    for(i=0;i<totalId;i++) {
        if (form.elements['subjectId[]'][i].selected == true) {
            if (subjectId != '') {
                subjectId += ',';
            }
            subjectId += document.allDetailsForm.elements['subjectId[]'][i].value;
        }
    }
    
    totalId = form.elements['periodId[]'].length;
    var name = document.getElementById('periodId');
    periodId='';
    for(i=0;i<totalId;i++) {
        if (form.elements['periodId[]'][i].selected == true) {
            if (periodId != '') {
                periodId += ',';
            }
            periodId += document.allDetailsForm.elements['periodId[]'][i].value;
        }
    }
    
    queryString ='&labelId='+timeTabelId+"&periodId="+periodId+"&subjectId="+subjectId+param1;
    
    sendReq(listURL,divResultName,'',queryString+'&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField, false);
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listTeacherSubstitutionsPrint.php?'+queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;   
    //alert(path);
    window.open(path,"TeacherObservationReport","status=1,menubar=1,scrollbars=1, width=850, height=540, top=150,left=150");
}

function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/listTeacherSubstitutionsPrintCSV.php?'+queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;   
    //alert(path);
    //window.open(path,"TeacherObservationReportCSV","status=1,menubar=1,scrollbars=1, width=800, height=440, top=150,left=150");
    window.location=path;
}

function getDaysofWeekP() {
    hideDetails();
    var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTeacherDays.php';
    
    document.allDetailsForm.daysOfWeek.length = null;
    addOption(document.allDetailsForm.daysOfWeek, '', 'Select');
    
    document.allDetailsForm.periodId.length = null;
    document.allDetailsForm.subjectId.length = null;
    
    var timeTable = document.getElementById('labelId').value;
    var rval=timeTable.split('~');
    var timeTabelId=rval[0];   
    
    param1='';
    if(rval[3]==1) {           // Weekly 
      timeTableType=1;
    }
    else if(rval[3]==2) {      // Daily 
       timeTableType=2;
    }
     
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {timeTabelId: timeTabelId,  
                     employeeId: (document.allDetailsForm.employeeId.value),
                     timeTableType: timeTableType
                    },
             onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            document.allDetailsForm.daysOfWeek.length = null;
            addOption(document.allDetailsForm.daysOfWeek, '', 'Select');
            len = j.length;    
            for(i=0;i<len;i++) { 
                addOption(document.allDetailsForm.daysOfWeek, j[i].daysOfWeek, j[i].daysOfWeekC);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function populateTeachers(){
    
    document.getElementById("weeklyFormat").style.display='none';  
    document.getElementById("dailyFormat").style.display='none';
    document.getElementById("weeklyEmployee").style.display='none';  
    document.getElementById("dailyEmployee").style.display='none';
    document.getElementById("ttFormat1").style.display=''; 
    document.getElementById("ttFormat2").style.display='';
    document.getElementById("dailyWeeklyTeacher").style.display='';  
     
    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false; 
    }  
    
    var timeTable = document.getElementById('labelId').value;
    var rval=timeTable.split('~');
    var timeTabelId=rval[0];   
    
   
    param1 = "";
    if(rval[3]==1) {           // Weekly 
       timeTableType=1;
       document.getElementById("weeklyFormat").style.display='';  
       document.getElementById("dailyFormat").style.display='none';
       document.getElementById("weeklyEmployee").style.display='';  
       document.getElementById("dailyEmployee").style.display='none';
       document.allDetailsForm.employeeId.length = null;
       addOption(document.allDetailsForm.employeeId, '', 'Select');
       document.getElementById("ttFormat1").style.display='none'; 
       document.getElementById("ttFormat2").style.display='none';
       document.getElementById("dailyWeeklyTeacher").style.display='none'; 
    }
    else if(rval[3]==2) {      // Daily 
       timeTableType=2;
       document.getElementById("weeklyFormat").style.display='none';  
       document.getElementById("dailyFormat").style.display='';
       document.getElementById("weeklyEmployee").style.display='none';  
       document.getElementById("dailyEmployee").style.display='';
       param1 = "&fromDate="+document.getElementById("fromDate").value;
       document.allDetailsForm.employeeId1.length = null;
       addOption(document.allDetailsForm.employeeId1, '', 'Select');
       document.getElementById("ttFormat1").style.display='none';   
       document.getElementById("ttFormat2").style.display='';
       document.getElementById("dailyWeeklyTeacher").style.display='none';   
    }
    
    param="timeTabelId="+timeTabelId+"&timeTableType="+timeTableType+param1;
    
    document.allDetailsForm.daysOfWeek.length = null;
    addOption(document.allDetailsForm.daysOfWeek, '', 'Select');
    document.allDetailsForm.periodId.length = null;
    document.allDetailsForm.subjectId.length = null;
    
    
    var url ='<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTeacher.php';
    new Ajax.Request(url,
    {
             method:'post',
             asynchronous:false,
             parameters: param, 
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                       var objOption = new Option(j[c].employeeName1,j[c].employeeId);    
                       if(rval[3]==1) {  
                         document.allDetailsForm.employeeId.options.add(objOption);
                       }
                       else if(rval[3]==2) {  
                         document.allDetailsForm.employeeId1.options.add(objOption);
                       }
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function getPeriods() {
    hideDetails();
    
    var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTeacherPeriods.php';
    
    document.allDetailsForm.periodId.length = null;    
     
    var timeTable = document.getElementById('labelId').value;
    var rval=timeTable.split('~');
    var timeTabelId=rval[0];    
    
    param1='';
    if(rval[3]==1) {           // Weekly 
      timeTableType=1;
      employeeId = document.allDetailsForm.employeeId.value;
      daysOfWeek = document.allDetailsForm.daysOfWeek.value;
      param1='&employeeId='+employeeId+'&daysOfWeek='+daysOfWeek;
    }
    else if(rval[3]==2) {      // Daily 
       timeTableType=2;
       employeeId = document.allDetailsForm.employeeId1.value;
       fromDate = document.allDetailsForm.fromDate.value;
       param1='&employeeId='+employeeId+'&fromDate='+fromDate; 
    }
    
    param="timeTabelId="+timeTabelId+"&timeTableType="+timeTableType+param1;    
    
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,   
         parameters: param,  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            document.allDetailsForm.periodId.length = null;  
            for(i=0;i<len;i++) { 
               addOption(document.allDetailsForm.periodId, j[i].periodId, j[i].periodNumber);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function getSubjects() {
    
    hideDetails();
    var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTeacherSubjects.php';  
    
    document.allDetailsForm.subjectId.length = null;  
    
    var timeTable = document.getElementById('labelId').value;
    var rval=timeTable.split('~');
    var timeTabelId=rval[0];    
    
    param1='';
    if(rval[3]==1) {           // Weekly 
      timeTableType=1;
      employeeId = document.allDetailsForm.employeeId.value;
      daysOfWeek = document.allDetailsForm.daysOfWeek.value;
      param1='&employeeId='+employeeId+'&daysOfWeek='+daysOfWeek;
    }
    else if(rval[3]==2) {      // Daily 
       timeTableType=2;
       employeeId = document.allDetailsForm.employeeId1.value;
       fromDate = document.allDetailsForm.fromDate.value;
       param1='&employeeId='+employeeId+'&fromDate='+fromDate; 
    }
    param="timeTabelId="+timeTabelId+"&timeTableType="+timeTableType+param1;   
    
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,   
         parameters: param,  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            document.allDetailsForm.subjectId.length = null;  
            for(i=0;i<len;i++) { 
                addOption(document.allDetailsForm.subjectId, j[i].subjectId, j[i].subjectName);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

window.onload=function(){
   populateTeachers();
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/listTeacherSubstitutionsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
  
   //     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
  
</SCRIPT>
</body>
</html>

<?php 
//$History: listTeacherSubstitutions.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 2/19/10    Time: 5:36p
//Updated in $/LeapCC/Interface
//timetableLabelId base check updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 2/12/10    Time: 2:17p
//Updated in $/LeapCC/Interface
//sortin order updated (employeeName1)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 2/12/10    Time: 2:11p
//Updated in $/LeapCC/Interface
//time Table label added (validation format updated)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 2/12/10    Time: 1:39p
//Updated in $/LeapCC/Interface
//timeTable Label Id base code updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/27/10    Time: 11:22a
//Updated in $/LeapCC/Interface
//format updated free period view
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/23/10    Time: 6:13p
//Updated in $/LeapCC/Interface
//validation & condition updated (free period show)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/18/09    Time: 5:34p
//Updated in $/LeapCC/Interface
//instituteId check added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 3/31/09    Time: 1:17p
//Updated in $/LeapCC/Interface
//paging formatting setting
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/31/09    Time: 12:44p
//Updated in $/LeapCC/Interface
//code update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 4:00p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 3:31p
//Created in $/SnS/Interface
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/20/09    Time: 12:03p
//Updated in $/Leap/Source/Interface
//print & printCSV paramter settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/20/09    Time: 11:42a
//Created in $/Leap/Source/Interface
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/18/09    Time: 3:21p
//Updated in $/SnS/Interface
//rights set
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/18/09    Time: 3:14p
//Created in $/SnS/Interface
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/17/09    Time: 6:26p
//Updated in $/Leap/Source/Interface
// time table label id update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/17/09    Time: 12:48p
//Updated in $/Leap/Source/Interface
//rights set
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/17/09    Time: 12:43p
//Updated in $/Leap/Source/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/17/09    Time: 11:59a
//Created in $/Leap/Source/Interface
//initial checkin
//

?>
