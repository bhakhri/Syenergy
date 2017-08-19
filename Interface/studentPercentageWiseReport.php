<?php 
// This file generate a list Student Percentage wise Attendance
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PercentageWiseAttendanceReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
UtilityManager::headerNoCache(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Percentage Wise Attendance Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var attendanceConsolidatedView=1;
var viewType=1;
var showDetail = 0;

 //This function Validates Form 
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
sortField = 'rollNo';
sortOrderBy  = 'ASC';
valShow=1;
queryString = '';

function refreshAttendanceData() {    
    
        hideResults(); 
      
        queryString='';
     
        var url='<?php echo HTTP_LIB_PATH;?>/StudentReports/studentInitPercentageWiseReportNEW.php';
        form = document.studentAttendanceForm;   

        endDate =  form.endDate.value; 
        startDate =  form.startDate.value; 
        reportType=form.reportType.value;
    
        var timeTable = form.timeTable.value;   
        var rval=timeTable.split('~');
        var timeTableLabelId = rval[0]; 
    

        subjectId = document.getElementById('subjectId').value;;
        groupId = document.getElementById('groupId').value;  
        
        if(form.incAll.checked) {
          incAll = 1;  
        }
        else {
          incAll = 0;  
        }
        
        if(form.incDutyLeave.checked) {
          incDutyLeave = 1;  
        }
        else {
          incDutyLeave = 0;  
        }
        
		//medical leave check
        if(form.incMedicalLeave.checked) {
          incMedicalLeave = 1;  
        }
        else {
          incMedicalLeave = 0;  
        }
        
        //if(form.consolidatedId.checked) {
          consolidatedId = 1;           // Consolidated (Lecture + Tutorial)
        //}
        //else {
        //  consolidatedId = 0;  
        //}
        
        average=form.average.value;
        percentage=form.percentage.value;
        degreeId=form.degreeId.value;
        consolidatedView=attendanceConsolidatedView;
        
        sortOrderBy1='ASC';
        if(document.studentAttendanceForm.sortOrderBy1[1].checked==true) {
          sortOrderBy1='DESC';
        }
        sortOrderBy = sortOrderBy1;
        sortField = document.getElementById('sortField1').value;
        
        
        document.getElementById('showSubjectEmployeeList').style.display='none';
        document.getElementById('showSubjectEmployeeList11').style.display='none';
                
        queryString = 'average='+average+'&percentage='+percentage+'&degreeId='+degreeId;
        queryString = queryString+'&groupId='+groupId+'&subjectId='+subjectId+'&startDate='+startDate+'&endDate='+endDate;
        queryString = queryString+'&reportType='+reportType+'&incAll='+incAll+'&incDutyLeave='+incDutyLeave+'&incMedicalLeave='+incMedicalLeave;
        queryString = queryString+'&timeTableLabelId='+timeTableLabelId+'&consolidatedId='+consolidatedId;
        queryString = queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
        
        new Ajax.Request(url,
        {
          method:'post',
          parameters:{   timeTableLabelId:  timeTableLabelId,
                         degreeId: degreeId,
                         average : average,
                         subjectId : subjectId,
                         groupId : groupId,
                         percentage: percentage,
                         consolidatedView: consolidatedView,
                         sortOrderBy: sortOrderBy,
                         sortField : sortField,
                         endDate: endDate,
                         startDate: startDate,
                         reportType: reportType,
                         incAll: incAll,
                         incDutyLeave: incDutyLeave,
                         incMedicalLeave: incMedicalLeave,
                         consolidatedId: consolidatedId
                      },
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo "No Data Found"; ?>");  
             }
             else {                                                                              
                var ret=trim(transport.responseText).split('!~~!~~!');
                j0 = ret[0];              
                j1 = ret[1];
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById('resultsDiv').innerHTML=j1;
                document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"  
                document.getElementById('showSubjectEmployeeList').style.display='';
                //document.getElementById('showSubjectEmployeeList11').style.display='';
                document.getElementById("subjectTeacherInfo").innerHTML=j0;
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });

   showDetail=1;
   valShow=1;
}


function validateAddForm(frm) {

     var fieldsArray = new Array( new Array("degreeId","<?php echo SELECT_DEGREE;?>"),
                                  new Array("average","<?php echo SELECT_CRITERIA;?>"), 
                                  new Array("percentage","<?php echo ENTER_AVERAGE;?>"));
                                  
     var len = fieldsArray.length; 
      
     form = document.studentAttendanceForm;       
     frm = document.studentAttendanceForm;   

   
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="percentage" && (!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))){
            messageBox("<?php echo ENTER_NUMBER;?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }                                                 
    }
 
    if( eval(document.getElementById('percentage').value) > 100) {
        messageBox("<?php echo "Average not greater than to 100"; ?>");
        document.getElementById('percentage').focus();
        return false;
    }  
         
     
    if(isEmpty(document.getElementById('subjectId').value)) {
       messageBox("<?php echo "Select Subject"; ?>");
       document.getElementById('subjectId').focus();
       return false;
    } 
    if(isEmpty(document.getElementById('groupId').value)) {
       messageBox("<?php echo "Select Group"; ?>");
       document.getElementById('groupId').focus();
       return false;
    } 
    
    //openStudentLists(frm.name,'rollNo','Asc');    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';                                                 
    
    //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    refreshAttendanceData();    
    
    return false;
}


function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/studentPercentageWiseReportPrint.php?'+queryString;
    a = hideUrlData(path,true);
}

function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/studentPercentageWiseReportCSV.php?'+queryString;
    window.location=path; 
    //a = window.open(path,"StudentPercentageWiseAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('showSubjectEmployeeList').style.display='none';
    showDetail = 0;
}

function getShowDetail() {
   document.getElementById("idSubjects").innerHTML="Show Subject & Teacher Details"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSubjectEmployeeList11").style.display='none';
   if(valShow==1) {
     document.getElementById("showSubjectEmployeeList11").style.display='';
     document.getElementById("idSubjects").innerHTML="Hide Subject & Teacher Details";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=0;
   }
   else {
     valShow=1;  
   } 
}

    
function getSubjectGroups() {

    hideResults();
    
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/studentGetSubjectGroups.php';

    frm = document.studentAttendanceForm; 
    
    timeTable = frm.timeTable.value;
    degree = frm.degreeId.value;
    subjectId = frm.subjectId.value;

    frm.groupId.length = null; 
    addOption(frm.groupId, '', 'Select');
    
    if(timeTable=='') {
      return false;  
    }
    
    if(degree=='') {
      return false  
    }

    if(subjectId=='') {
      return false  
    }
    
    var rval=timeTable.split('~');
    
    pars = 'timeTableLabelId='+rval[0]+'&classId='+degree+'&subjectId='+subjectId;
    
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,
        parameters: pars,
        onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            frm.groupId.length = null;
            if (len > 0) {
              frm.groupId.length = null;
              addOption(frm.groupId, 'all', 'All');
            }
            else {
              addOption(frm.groupId, '', 'Select');  
            }
            for(i=0;i<len;i++) {        
               addOption(frm.groupId, j[i].groupId, j[i].groupName);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function getSubjectClasses() {
   
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/studentGetSubjectClasses.php';
    
    frm = document.studentAttendanceForm; 
    timeTable = frm.timeTable.value;
    degree = frm.degreeId.value;
        
    frm.subjectId.length = null; 
    addOption(frm.subjectId, '', 'Select');
    
    frm.groupId.length = null; 
    addOption(frm.groupId, '', 'Select');
    
    if(timeTable=='') {
      return false;  
    }
    
    if(degree=='') {
      return false  
    }


    var rval=timeTable.split('~');
    
    pars = 'timeTableLabelId='+rval[0]+'&classId='+degree;
    
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            
            var ret=trim(transport.responseText).split('!~!~!');  
            if(ret.length > 0 ) {
                var j = eval('(' + ret[0] + ')');
                len = j.length;
                frm.subjectId.length = null;
                if (len > 0) {
                  addOption(frm.subjectId, 'all', 'All');
                }
                else {
                  addOption(frm.subjectId, '', 'Select');  
                }
                for(i=0;i<len;i++) { 
                  if(j[i].hasAttendance==1) {   
                    addOption(frm.subjectId, j[i].subjectId, j[i].subjectCode);
                  }
                }
            
                var j = eval('(' + ret[1] + ')');
                len = j.length;
                frm.groupId.length = null;
                if (len > 0) {
                  addOption(frm.groupId, 'all', 'All');
                }
                else {
                  addOption(frm.groupId, '', 'Select');  
                }
                for(i=0;i<len;i++) {        
                   addOption(frm.groupId, j[i].groupId, j[i].groupName);
                }
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function getLabelClass(){
     var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTimeTableClass.php';
    
     frm = document.studentAttendanceForm;
     var timeTable = frm.timeTable.value;
     
     frm.degreeId.length = null; 
     addOption(frm.degreeId, '', 'Select');
    
     frm.subjectId.length = null; 
     addOption(frm.subjectId, '', 'Select');
    
     frm.groupId.length = null; 
     addOption(frm.groupId, '', 'Select');
     
     if(timeTable=='') {
       return false;  
     }
     
     var rval=timeTable.split('~');
     var pars = 'timeTableLabelId='+rval[0];     
     
     if(rval[1]!='0000-00-00' && rval[1]!='') {
       document.getElementById('startDate').value=rval[1];
     }
     if(rval[2]!='0000-00-00' && rval[2]!='') {
       document.getElementById('endDate').value=rval[2];
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
                document.studentAttendanceForm.degreeId.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(document.studentAttendanceForm.degreeId, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.studentAttendanceForm.degreeId, '', 'Select'); 
                }
                // now select the value                                     
                //document.attendanceForm.degree.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       
       getSubjectClasses();  
}

/*
function toggleAttendanceDataFormat(chk) {
    attendanceConsolidatedView=viewType;
    if(viewType==0){
        viewType=1;
        //document.getElementById('consolidatedDiv').innerHTML='Consolidated View';
        document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/consolidated.gif" />';
        document.getElementById('consolidatedDiv').title='Consolidated View';    
        //document.getElementById("filter1").style.display='';
        //document.getElementById("filter3").style.display='';
       // document.getElementById("filter2").style.display='none';
        document.getElementById("subjectId").disabled=false;  
        //document.getElementById("groupId").disabled=false;  
        document.getElementById("average").disabled=false;  
        document.getElementById("percentage").disabled=false;  
        //document.getElementById("subjectId").selectedIndex=0;
        //document.getElementById("groupId").selectedIndex=0;
        document.getElementById('sortingFormat1').style.display='none'; 
        document.getElementById('sortingFormat2').style.display='none';
        document.getElementById('sortingFormat3').style.display='none';
    }
    else if(viewType==1){  
        viewType=0;
        //document.getElementById('consolidatedDiv').innerHTML='Detailed View';
        document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/detailed.gif" />';
        document.getElementById('consolidatedDiv').title='Detailed View';
        //document.getElementById("filter1").style.display='none';
        document.getElementById("subjectId").disabled=true;  
        //document.getElementById("groupId").disabled=true;  
        //document.getElementById("filter3").style.display='none';
        //document.getElementById("filter2").style.display='';
        document.getElementById("subjectId").selectedIndex=0;
        //document.getElementById("groupId").selectedIndex=0;
        
        document.getElementById('sortingFormat1').style.display=''; 
        document.getElementById('sortingFormat2').style.display='';
        document.getElementById('sortingFormat3').style.display='';
    }
    if(chk=='' && showDetail==1) {
       refreshAttendanceData();    
    }
} 


function toggleReportType() {
    if(document.getElementById("reportType").value==1) {
      document.getElementById("note").style.display='none';  
      document.getElementById("average").disabled=false;  
      document.getElementById("percentage").disabled=false; 
    }
    else if(document.getElementById("reportType").value==2) {
       document.getElementById("note").style.display='none';  
       document.getElementById("average").disabled=true;  
       document.getElementById("percentage").disabled=true; 
    }
    else if(document.getElementById("reportType").value==3) {
      document.getElementById("note").style.display='';  
      document.getElementById("average").disabled=false;  
      document.getElementById("percentage").disabled=false; 
    }
}
*/ 

//populate list
window.onload=function(){
   getLabelClass();
   //toggleReportType();
   //toggleAttendanceDataFormat(1);
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentPercentageWiseReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: studentPercentageWiseReport.php $
//
//*****************  Version 22  *****************
//User: Parveen      Date: 4/06/10    Time: 5:13p
//Updated in $/LeapCC/Interface
//option subject condition format updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 3/22/10    Time: 2:22p
//Updated in $/LeapCC/Interface
//time table Label Id base check updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 2/15/10    Time: 5:43p
//Updated in $/LeapCC/Interface
//added check box (Include All Students) and validation format updated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 12/21/09   Time: 6:22p
//Updated in $/LeapCC/Interface
//sorting order updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 12/21/09   Time: 4:28p
//Updated in $/LeapCC/Interface
//sorting format updated
//
//*****************  Version 17  *****************
//User: Parveen      Date: 12/04/09   Time: 12:05p
//Updated in $/LeapCC/Interface
//report format updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 12/03/09   Time: 5:12p
//Updated in $/LeapCC/Interface
//format updated
//
//*****************  Version 15  *****************
//User: Parveen      Date: 11/26/09   Time: 5:39p
//Updated in $/LeapCC/Interface
//dutyLeavewise report format updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 11/16/09   Time: 3:55p
//Updated in $/LeapCC/Interface
//sorting updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 11/13/09   Time: 5:39p
//Updated in $/LeapCC/Interface
//validation added refershStudentData
//
//*****************  Version 12  *****************
//User: Parveen      Date: 11/13/09   Time: 9:54a
//Updated in $/LeapCC/Interface
//format updated all subjects view 
//
//*****************  Version 11  *****************
//User: Parveen      Date: 11/06/09   Time: 2:43p
//Updated in $/LeapCC/Interface
//sorting order updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/06/09   Time: 10:37a
//Updated in $/LeapCC/Interface
//new column added report type base conditions updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/14/09   Time: 12:14p
//Updated in $/LeapCC/Interface
//CSV & Query Format updated 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/13/09   Time: 2:44p
//Updated in $/LeapCC/Interface
//consolidated & details report print
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Interface
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/17/08   Time: 11:47a
//Updated in $/LeapCC/Interface
//added define('MANAGEMENT_ACCESS',1); for admin reports
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/08/08   Time: 11:44a
//Created in $/LeapCC/Interface
//student percentagewise report added
//


?>
