<?php 
// This file generate a list Student Test Wise Marks Report
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTestWiseMarksReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Test Wise Marks Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

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
sortField = 'universityRollNo';
sortOrderBy  = 'ASC';
allSubjectId = '';
queryString = '';
var viewType=1;    

function validateAddForm(frm) {

     var fieldsArray = new Array(new Array("timeTable","<?php echo SELECT_TIME_TABLE;?>"), 
                                 new Array("degreeId","<?php echo SELECT_DEGREE;?>") 
                                );
     var len = fieldsArray.length; 
      
     frm = document.studentAttendanceForm;   

     for(i=0;i<len;i++) {
         if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
         }
     }
     
     document.getElementById('resultsDiv').innerHTML='';
     document.getElementById("pagingDiv").innerHTML = '';
     document.getElementById("pagingDiv1").innerHTML = '';
     
     page=1;
     showReport(page);    
     return false;
}

function showReport(page) {


     var url='<?php echo HTTP_LIB_PATH;?>/StudentReports/studentInitTestWiseMarksReport.php';
     form = document.studentAttendanceForm;   
  
     var rval=timeTable.split('~');
  
     timeTableLabelId= +rval[0];
     classId=form.degreeId.value;
     if(form.subjectId.value=='all') {
       subjectId = allSubjectId;
       subjectId1 = 'all';
     }
     else {
       subjectId = form.subjectId.value;
       subjectId1 = 's';
     }
     groupId =  form.groupId.value;
     testTypeCategoryId = form.testTypeCategoryId.value ;   
     
     sortOrderBy1='ASC';
     if(document.studentAttendanceForm.sortOrderBy1[1].checked==true) {
          sortOrderBy1='DESC';
     }
     sortOrderBy = sortOrderBy1;
     sortField =  document.getElementById('sortField1').value;
     
     queryString = "?timeTableLabelId="+timeTableLabelId+"&classId="+classId+"&subjectId="+subjectId;  
     queryString = queryString+"&groupId="+groupId+"&testTypeCategoryId="+testTypeCategoryId+"&subjectId1="+subjectId1; 
     queryString = queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
      
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters:{timeTableLabelId: timeTableLabelId,
                     classId: classId,
                     subjectId : subjectId,
                     groupId : groupId,
                     testTypeCategoryId: testTypeCategoryId,
                     sortOrderBy: sortOrderBy,
                     sortField : sortField,
                     page: page
                    },
         asynchronous:true,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else
             if("<?php echo FOXPRO_LIST_EMPTY;?>" == trim(transport.responseText)) {
                messageBox("<?php echo FOXPRO_LIST_EMPTY?>");  
             }
             else {
                var ret=trim(transport.responseText).split('!~~!');
                var j0 = ret[0];
                var j1 = ret[1];
                
                if(j1=='') {
                  totalRecords = 0;
                }
                else {
                  totalRecords = j1; 
                }
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById("pageRow").style.display='';    
                document.getElementById('resultsDiv').innerHTML=j0;
                //document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
                
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("pagingDiv1").innerHTML = pagingData;
                
                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                if (totalRecords > 0) {
                    pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                    document.getElementById("pagingDiv").innerHTML = pagingData;
                    document.getElementById("pagingDiv1").innerHTML = "<b>Total Records&nbsp;:&nbsp;</b>"+totalRecords; 
                }
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });

}

function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/studentTestWiseMarksReportPrint.php'+queryString;
    a = window.open(path,"StudentTestWiseMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/studentTestWiseMarksReportCSV.php'+queryString;
    window.location=path; 
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("pagingDiv").innerHTML = '';
    document.getElementById("pagingDiv1").innerHTML = '';
    document.getElementById("pageRow").style.display='none';
}

    
function getSubjectGroups() {

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
   
    document.getElementById('showSubjectEmployeeList').style.display='none';
    document.getElementById('showSubjectEmployeeList11').style.display='none';
    
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

    allSubjectId = '';
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
                   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"  
                   document.getElementById('showSubjectEmployeeList').style.display='';
                   //document.getElementById('showSubjectEmployeeList11').style.display='';
                   document.getElementById("subjectTeacherInfo").innerHTML=ret[2]; 
                   valShow=1;  
                   addOption(frm.subjectId, 'all', 'All');
                }
                else {
                  addOption(frm.subjectId, '', 'Select');  
                }
                
                for(i=0;i<len;i++) { 
                  if(j[i].hasAttendance==1) {   
                     addOption(frm.subjectId, j[i].subjectId, j[i].subjectCode);
                     if(allSubjectId=='') {
                       allSubjectId = j[i].subjectId;   
                     }
                     else {
                       allSubjectId = allSubjectId+","+j[i].subjectId;  
                     }
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

function getLabelClass(){
     var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTimeTableClass.php';
    
     document.getElementById('showSubjectEmployeeList').style.display='none';
     document.getElementById('showSubjectEmployeeList11').style.display='none';
     
     frm = document.studentAttendanceForm;
     var timeTable = frm.timeTable.value;
     
     frm.degreeId.length = null; 
     addOption(frm.degreeId, '', 'Select');
    
     frm.subjectId.length = null; 
     addOption(frm.subjectId, '', 'Select');
    
     frm.groupId.length = null; 
     addOption(frm.groupId, '', 'Select');
     
     allSubjectId='';
     
     if(timeTable=='') {
       return false;  
     }
     
     var rval=timeTable.split('~');

     var pars = 'timeTableLabelId='+rval[0];     
     
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


//populate list
window.onload=function(){
   getLabelClass();
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentTestMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: studentTestWiseMarksReport.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 4/13/10    Time: 7:13p
//Updated in $/LeapCC/Interface
//fixed bugs. FCNS No. 1574
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/08/10    Time: 11:21a
//Updated in $/LeapCC/Interface
//pagination added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/08/10    Time: 9:27a
//Updated in $/LeapCC/Interface
//function getSubjectGroups updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/05/10    Time: 1:14p
//Updated in $/LeapCC/Interface
//query format updated (optional subject code udpated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/02/10    Time: 2:15p
//Updated in $/LeapCC/Interface
//showReport Function updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/01/10    Time: 2:33p
//Created in $/LeapCC/Interface
//initial checkin
//

?>
