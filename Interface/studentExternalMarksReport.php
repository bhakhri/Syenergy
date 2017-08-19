<?php 
//-------------------------------------------------------
//  This File contains Student External Marks Reports 
//
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExternalMarksReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student External Marks Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentExternalMarksReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentExternalMarksFrm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy    = 'ASC';
valShow=1;
queryString='';

function validateAddForm(frm) {
    
    queryString = '';
    var fieldsArray = new Array(new Array("timeTable","<?php echo SELECT_TIME_TABLE;?>"),
                                new Array("classId","<?php echo SELECT_DEGREE;?>"));
    var len = fieldsArray.length;
    
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
   
    hideResults();   
    page=1;
    showReport(page);  
    return false;
}

function showReport(page) {
 
    form = document.studentExternalMarksFrm;
    
    timeTable = form.timeTable.value;
    classId = form.classId.value;
    
    sortOrderBy1='ASC';
    if(document.studentExternalMarksFrm.sortOrderBy1[1].checked==true) {
      sortOrderBy1='DESC';
    }
    sortOrderBy = sortOrderBy1;
    sortField = document.getElementById('sortField1').value;
    
    
    queryString = 'timeTableLabelId='+timeTable+'&classId='+classId;
    queryString = queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
    url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentExternalMarksReport.php';
    new Ajax.Request(url,
    {
     method:'post',
     parameters: {
         timeTable: document.getElementById('timeTable').value,
         classId: document.getElementById('classId').value,
         sortOrderBy: sortOrderBy,
         sortField : sortField,
         page: page
     },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
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



function resetStudyPeriod() {
	document.studentExternalMarksFrm.studyPeriodId.selectedIndex = 0;
}

function printReport() {
    path='<?php echo UI_HTTP_PATH;?>/studentExternalMarksReportPrint.php?'+queryString; 
    window.open(path,"ExternalMarksReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printReportCSV() {

    path='<?php echo UI_HTTP_PATH;?>/studentExternalMarksReportCSV.php?'+queryString; 
    window.location = path;  
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
    document.getElementById("pageRow").style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("pagingDiv").innerHTML = '';
    document.getElementById("pagingDiv1").innerHTML = '';
}

function getShowSubject(){
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetExternalTimeTableSubject.php';
    
    form = document.studentExternalMarksFrm;
    
    var timeTable = form.timeTable.value;
    var classId = form.classId.value;
   
    if(timeTable=='') {
      return false;
    } 
  
    if(classId=='') {
      return false;
    } 
    
    var pars = 'timeTableLabelId='+timeTable+'&classId='+classId;
    
    document.getElementById('showSubjectEmployeeList').style.display='none';
    document.getElementById('showSubjectEmployeeList11').style.display='none';
               
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
             if(trim(transport.responseText)==false) {
               document.getElementById("subjectTeacherInfo").innerHTML=''; 
             }
             else {
               document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif";
               document.getElementById('showSubjectEmployeeList').style.display='';
               //document.getElementById('showSubjectEmployeeList11').style.display='';
               document.getElementById("subjectTeacherInfo").innerHTML=trim(transport.responseText); 
             }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       valShow=1; 
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
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetExternalTimeTableClass.php';
    
    form = document.studentExternalMarksFrm;
    var timeTable = form.timeTable.value;
    var pars = 'timeTableLabelId='+timeTable;
    
    document.studentExternalMarksFrm.classId.length = null; 
    addOption(document.studentExternalMarksFrm.classId, '', 'Select');   
    
    document.getElementById('showSubjectEmployeeList').style.display='none';
    document.getElementById('showSubjectEmployeeList11').style.display='none';
    
    if(timeTable=='') {
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

                document.studentExternalMarksFrm.classId.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(document.studentExternalMarksFrm.classId, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.studentExternalMarksFrm.classId, '', 'Select'); 
                }
                // now select the value                                     
                document.internalMarksFoxproFrm.classId.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       getShowSubject();
}

window.onload=function(){
   //loads the data
   document.studentExternalMarksFrm.timeTable.focus();
   getLabelClass();
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentExternalMarksContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: studentExternalMarksReport.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Interface
//validation & condition format updated 
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/29/09    Time: 4:13p
//Updated in $/LeapCC/Interface
//print code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/29/09    Time: 11:28a
//Created in $/LeapCC/Interface
//file added
//

?>
