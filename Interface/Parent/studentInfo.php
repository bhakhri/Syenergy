<?php
//-------------------------------------------------------
// Purpose: To generate student list
//
// Author : Parveen Sharma
// Created on : 10.12.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentStudentInfo');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
require_once(BL_PATH . "/Parent/initData.php"); 
?>
<?php
//this will fetch the Latest Registration Details from the student_registration table
require_once(MODEL_PATH . "/StudentRegistration.inc.php");
$studentRegistration = StudentRegistration::getInstance();
$getStudentRegistrationInfo=$studentRegistration->getStudentInfo($sessionHandler->getSessionVariable('StudentId'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Information</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("ajax.js");  
echo UtilityManager::includeJS("tab-view.js");  


function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

function parseOutput($data){
     
     return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
    
}
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?> 

<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>

<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;


/****************************************************************/
//Overriding tabClick() function of tab.js
//Dipanjan Bhattacharjee
//Date:14.02.2009
/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick()  {
    var idArray = this.id.split('_');
    showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
    tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
    
    //refresshes data for this tab
    refreshStudentData(<?php echo $sessionHandler->getSessionVariable('StudentId'); ?>,document.getElementById('studyPeriod').value,tabNumber); 
 }

//Global variables for classId countres for different tabs
var aId=-1;
var bId=-1;
var cId=-1;
var dId=-1;
var eId=-1;
var fId=-1;
var gId=-1;
var hId=-1;
var attRegId=-1;
var feeId=-1;
  
//this function shows duty leave details when we click on them
function showDutyLeaveDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateDutyLeaveValues(id);
}

//this function shows medical leave details when we click on them
function showMedicalLeaveDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMedicalLeaveValues(id);
}


//this function is uded to refresh tab data based uplon selection of study periods
function refreshStudentData(studentId,classId,tabIndex){
    
    //get the data of course based upon selected study period
    if(tabIndex==2 && classId!=aId){ 
        var groupData=refreshGroupData(studentId,classId);
        aId=classId;
        return;
    }
    
    
    //get the data of time table based upon selected study period
    if(tabIndex==4 && classId!=eId){ 
        var timeTableData=refreshTimeTableData(studentId,classId);
        eId=classId;
        return;
    }
    
    //get the data of marks based upon selected study period
    if(tabIndex==5 && classId!=bId){ 
        var gradeData=refreshGradeData(studentId,classId);
        bId=classId;
        return;
    }
    
    //get the data of attendance based upon selected study period
    if(tabIndex==6 && classId!=cId){ 
        var attendanceData=refreshAttendanceData(document.getElementById('startDate2').value);
        cId=classId;
        return;
    }
    
     //get the data of fees based upon selected study period
    if(tabIndex==7 && classId!=hId){ 
        var timeTableData=refreshFeesResultData(studentId,classId);
        hId=classId;
        return;
    }

    //get the data of resource based upon selected study period
    if(tabIndex==8 && classId!=dId){ 
        var resourceData=refreshResourceData(studentId,classId);
        dId=classId;
        return;
    }

    //get the data of final result based upon selected study period
    if(tabIndex==9 && classId!=fId){ 
        var finalResultData=refreshFinalResultData(studentId,classId);
        fId=classId;
        return;
    }

    //get the data of offence based upon selected study period
    if(tabIndex==10 && classId!=gId){ 
        var timeTableData=refreshOffenceData(studentId,classId);
        gId=classId;
        return;
    }
    
    
    if(tabIndex==11 && classId!=attRegId) {
      var getAttendanceRegisterData= refreshAttendanceRegisterData(classId); 
      attRegId=classId;  
      return;
    }

     if(tabIndex==12 && classId!=feeId) {
      var getStudentFeesData= refreshStudentFeesData(classId); 
      feeId=classId;
      return;
    }
   
}

function refreshStudentFeesData(Id){
		url = '<?php echo HTTP_LIB_PATH;?>/Fee/PaymentHistory/ajaxFeeList.php';
	
		var tbHeadArray =new Array(new Array('srNos','#','width="2%"','',false),
                               new Array('receiptDate','Receipt Date','width="12%"','align="center"',true),  
                               new Array('receiptNo','Receipt No.','width="10%"','align="left"',true),
                               new Array('studentName','Name','width="12%"','',true) , 
                               new Array('rollNo','Roll No.','width="10%"','',true), 
                               new Array('className','Fee Class','width="15%"','',true),  
                               new Array('cycleName','Fee Cycle','width="9%"','',true),
                               new Array('feeTypeOf','Pay Fee Of','width="11%"','',true),   
                               new Array('receiveCash','Cash(Rs.)','width="10%"','align="right"',true), 
                               new Array('receiveDD','DD(Rs.)','width="10%"','align="right"',true), 
                               new Array('ddDetail', 'DD Detail','width="10%"','align="right"',true), 
                               new Array('amount','Total Receipt','width="10%"','align="right"',true) 
					
					);	
								 
//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
listObj13 = new initPage(url,recordsPerPage,linksPerPage,1,'','receiptDate','ASC','feeDataDiv','','',true,'listObj13',tbHeadArray,'','','');
		sendRequest(url, listObj13, '')

}


function refreshAttendanceRegisterData(Id){
    
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxAttendanceRegisterList.php';
  
    document.getElementById('attendanceRegister').innerHTML='';
    
    new Ajax.Request(url,
    {
          method:'post',
          asynchronous:false,
          parameters: {classId: (Id)},
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('attendanceRegister').innerHTML=trim(transport.responseText);
             }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
}   
 

function refreshGroupData(studentId,classId){
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxGroup.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('groupName','Group Name','width="15%" align="left"',true),
                        new Array('groupTypeName','Group Type','width="14%" align="left"',true), 
                        new Array('groupTypeCode','Group Code','width="14%" align="left"',true),
                        new Array('className','Study Period','width="15%" align="left"',true),
                        new Array('subjectCode','Subjet Code','width="15%"',true), 
                        new Array('subjectName','Subject','width="20%"',true)
                       );
 

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','groupName','ASC','courseResultDiv','','',true,'listObj1',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj1, '',true )
 
}
 
//this function fetches records corresponding to student grades/marks
function refreshGradeData(studentId,classId){
    //alert(studentId);
    //alert(classId);
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentMarks.php';
  var tableColumns1 = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('studyPeriod','Study Period','width="12%" align="left"',true),
                        new Array('subjectName','Subject','width="21%" align="left"',true),
                        new Array('testTypeName','Type','width="12%" align="left"',true), 
                        new Array('testDate','Date','width="8%" align="left"',true),
                        new Array('employeeName','Teacher','width="16%" align="left"',true),
                        new Array('testName','Test Name','width="10%" align="left"',true),
                        new Array('totalMarks','Max.Marks','width="11%" align="right"',true),
                        new Array('obtainedMarks','Scored','width="8%" align="right"',true)
                       );
 
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','studyPeriod','ASC','gradeResultDiv','','',true,'listObj2',tableColumns1,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj2, '',true )
}


var attendanceConsolidatedView=1;
var viewType=0;

//this function fetches records corresponding to student attendance
function refreshAttendanceData(value){
     //var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentAttendance.php';
     var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php'; 

      //if consolidated view is not required
     var classId = document.getElementById('studyPeriod').value;
   
   //if consolidated view is not required
   if(attendanceConsolidatedView==1){
        var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="14% align="left"',true), 
                                new Array('groupName','Group','width="8%" align="left"',true),
                                new Array('employeeName','Teacher','width="15%" align="left"',true),
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',false),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                new Array('delivered','Delivered','width="10%" align="right"',false),
                                new Array('per','%age','width="10%" align="right"',false)
                               );
    }
    else{
        var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="12% align="left"',true), 
                                new Array('employeeName','Teacher','width="15%" align="left"',true), 
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',false),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                new Array('medicalLeaveTaken','Medical Leaves','width="10%" align="right"',false),
                                new Array('delivered','Delivered','width="10%" align="right"',false),
                                new Array('per','%age','width="10%" align="right"',false)
                               );
    }
     
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','attendanceResultDiv','','',true,'listObj1',tableColumns2,'','','&rClassId='+classId+'&startDate2='+value+'&consolidatedView='+attendanceConsolidatedView);
     sendRequest(url, listObj1, '')
     }

function toggleAttendanceDataFormat(value){
 //var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentAttendance.php';
 
  var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php';  
    
    var classId = document.getElementById('studyPeriod').value;  
    //if consolidated view is not required
    if(viewType==1){ 
         var tableColumns2 =new Array(
                                    new Array('srNo','#','width="2%" align="left"',false), 
                                    new Array('subjectName1','Subject','width="25%" align="left"',true),
                                    new Array('periodName','Study Period','width="14% align="left"',true), 
                                    new Array('groupName','Group','width="8%" align="left"',true),
                                    new Array('employeeName','Teacher','width="15%" align="left"',true),
                                    new Array('fromDate','From','width="8%" align="center"',true),
                                    new Array('toDate','To','width="8%" align="center"',true),
                                     new Array('attended','Attended','width="10%" align="right"',true),
                                    new Array('leaveTaken','Duty Leaves','width="10%" align="right"',true),
                                    new Array('delivered','Delivered','width="10%" align="right"',true),
                                    new Array('per','%age','width="10%" align="right"',true)
                                   );
        }
        else{
            var tableColumns2 =new Array(
                                    new Array('srNo','#','width="2%" align="left"',false), 
                                    new Array('subjectName1','Subject','width="25%" align="left"',true),
                                    new Array('periodName','Study Period','width="12% align="left"',true), 
                                    new Array('employeeName','Teacher','width="15%" align="left"',true), 
                                    new Array('fromDate','From','width="8%" align="center"',true),
                                    new Array('toDate','To','width="8%" align="center"',true),
                                    new Array('attended','Attended','width="10%" align="right"',true),
                                    new Array('leaveTaken','Duty Leaves','width="10%" align="right"',true),
                                    new Array('medicalLeaveTaken','Medical Leaves','width="10%" align="right"',true),
                                    new Array('delivered','Delivered','width="10%" align="right"',true),
                                    new Array('per','%age','width="10%" align="right"',true)
                                   );
        }
 
    attendanceConsolidatedView=viewType;
    if(viewType==1){
        viewType=0;
        //document.getElementById('consolidatedDiv').innerHTML='Consolidated View';
        document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/consolidated.gif" />';
        document.getElementById('consolidatedDiv').title='Consolidated View';    
    }
    else{
        viewType=1;
        //document.getElementById('consolidatedDiv').innerHTML='Detailed View';
        document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/detailed.gif" />';
        document.getElementById('consolidatedDiv').title='Detailed View';
    }
    
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','attendanceResultDiv','','',true,'listObj1',tableColumns2,'','','&rClassId='+classId+'&startDate2='+value+'&consolidatedView='+attendanceConsolidatedView);
     sendRequest(url, listObj1, '')
 
} 

//this function fetches records corresponding to student fees detail
function refreshFeesResultData(studentId,classId){
    
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentFees.php';
  /*
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('receiptNo','Receipt No.','width="11%" valign="middle"',true),
                            new Array('receiptDate','Receipt Date','width="12%" align="center" valign="middle"',true) , 
                            new Array('periodName','Study Period','width="12%" align="center" valign="middle"',true) , 
                            new Array('totalFeePayable','Total Fees(Rs.)','width="13%" valign="middle" align="right"',true), 
                            new Array('discountedFeePayable','Payable(Rs.)','width="12%" valign="middle" align="right"',true), 
                            new Array('totalAmountPaid','Paid(Rs.)','width="11%" valign="middle" align="right"',true), 
                            new Array('paymentInstrument1','Payment<br>Instrument','width="10%" valign="middle" align="left"',false), 
                            new Array('receiptStatus1','Receipt<br>Status','width="9%" valign="middle" align="left"',false), 
                            new Array('instrumentStatus1','Instrument<br>Status','width="15%" valign="middle" align="left"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','feesResultsDiv','','',true,'listObj6',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj6, '')
 */
  var tableColumns   = new Array(new Array('srNos',             '#',                    'width="2%"',false),
                               new Array('receiptDate',         'Receipt Date',         'width="15%"  align="center"',true),  
                               new Array('receiptNo',           'Receipt',              'width="10%" align="left"',true),
                               new Array('className',           'Fee Class',            'width="15%" align="left"',true),  
                               new Array('cycleName',           'Fee Cycle',            'width="12%"  align="left"',true),  
                               new Array('installmentCount',    'Installment',          'width="12%" align="left"',true), 
                               new Array('discountedFeePayable','Payable<br>(Rs.)',     'width="10%" align="right"',false), 
                               new Array('amountPaid',          'Paid<br>(Rs.)',        'width="8%"  align="right"',false), 
                               new Array('previousDues',        'Outstanding<br>(Rs.)', 'width="12%" align="right"',false),
                               new Array('instStatus',          'Instrument',           'width="12%" align="left"',false), 
                               new Array('retStatus',           'Status',               'width="12%" align="left"',false));  
  
   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'','receiptDate','ASC','feesResultsDiv','','',true,'listObj6',tableColumns,'','','&studentId='+studentId+'&classId='+classId);
   sendRequest(url, listObj6, '')
} 

//this function fetches records corresponding to student attendance
function refreshResourceData(studentId,classId){
    
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentResource.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('subject','Subject','width="10%" valign="middle"',true) , 
                            new Array('description','Description','width="15%" valign="middle"',false), 
                            new Array('resourceName','Type','width="10%" valign="middle"',true), 
                            new Array('postedDate','Date','width="8%"  valign="middle"',true),
                            new Array('resourceLink','Link','width="8%" valign="middle"',false),
                            new Array('attachmentLink','Attachment','width="7%" valign="middle" align="center"',false),
                            new Array('employeeName','Creator','width="10%" align="left" valign="middle"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','resourceResultsDiv','','',true,'listObj4',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId+'&searchbox='+document.getElementById('searchbox').value);
 sendRequest(url, listObj4, '')
} 

//this function fetches records corresponding to student final exam
//this function fetches records corresponding to student final exam
function refreshFinalResultData(studentId,classId){
    
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentResult.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('periodName','Study Period','width="10%" valign="middle"',true),
                            new Array('subjectCode','Subject','width="40%" valign="middle"',true) , 
                            new Array('attendance','Attendance','width="12%" valign="middle" align="right"',false),    
                            new Array('preCompre','Internal','width="12%" align="right"',false), 
                            new Array('compre','External','width="10%" align="right"',false)                       
                       );

                       //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj5 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectCode','ASC','finalResultsDiv','','',true,'listObj5',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj5, '')
 
} 

//this function fetches records corresponding to student offence
function refreshOffenceData(studentId,classId){
    
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentOffence.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('offenseName','Offense','width="15%" valign="middle"',true),
                            new Array('offenseDate','Date','width="10%" align="center" valign="middle"',true) , 
                            new Array('periodName','Study Period','width="13%" valign="middle"',true) , 
                            new Array('remarks','Remarks','width="65%" valign="middle" align="left"',true) 
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj7 = new initPage(url,recordsPerPage,linksPerPage,1,'','offenseName','ASC','offenceResultsDiv','','',true,'listObj7',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj7, '')
} 

//this function fetches records corresponding to student attendance
function refreshTimeTableData(studentId,classId){

  currentClassId = "<?php echo $studentDataArr[0]['classId']?>";     
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxStudentTimeTable.php';
  new Ajax.Request(url,
   {
     method:'post',
     asynchronous:false,
     parameters: {
         currentClassId: (currentClassId),studentId: (studentId),classId: (classId)
         },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
             hideWaitDialog(true);
             document.getElementById('timeTableResultDiv').innerHTML=trim(transport.responseText);
             changeColor(currentThemeId);    
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
} 

window.onload=function(){
  refreshStudentData(<?php echo $studentDataArr[0]['studentId']; ?>,<?php echo $studentDataArr[0]['classId']; ?>);  
}


function printTimeTableReport() {
    form = document.addForm;
    path='<?php echo UI_HTTP_PATH;?>/Parent/displayTimeTableReport.php?currentClassId='+<?php echo $studentDataArr[0]['classId']; ?>+'&rClassId='+document.getElementById('studyPeriod').value;
    window.open(path,"StudentTimeTableReport","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");  
}

function getAttendance(startDate) { 

   //alert(studentId,document.getElementById('studyPeriod').value); 
   var attendanceData=refreshAttendanceData(startDate);  
}
 
var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('receiptNo','Receipt No','width="10%"','',true) , new Array('receiptDate','Receipt Date','width="12%"','',false), new Array('totalFeePayable','Total fees payable','width="15%"','',false) , new Array('totalAmountPaid','Amount Paid','width="12%"','',false) ,  new Array('receiptStatus','Receipt Status','width="12%"','',false), new Array('paymentInstrument','Payment Instrument','width="15%"','',false) , new Array('instrumentStatus','Instrument Status','width="18%"','align="right"',false));


function  download(str){    
var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+escape(str);
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function initAdd() {
    document.getElementById('addForm').onsubmit=function() {
        document.getElementById('addForm').target = 'uploadTargetAdd';
    }
} 

//---------------------------------------------------------------------------------------------------------

//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Rajeev Aggarwal
// Created on : (17.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------
//id:id 
//type:states/city
//target:taget dropdown box

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "div_dutyLeave" DIV
//
//Author : Aditi Miglani
// Created on : (04.11.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateDutyLeaveValues(id) {
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetDutyLeaveValue.php';   
    document.getElementById('div_dutyLeave').innerHTML ='';
    new Ajax.Request(url,
    {      
		method:'post',
		parameters: {id: id },
		onCreate: function() {
			showWaitDialog();
		},
		onSuccess: function(transport){
		    hideWaitDialog(true); 
		    var ret=trim(transport.responseText); 
		    var retArray=ret.split('!~!');  
		    document.getElementById('div_dutyLeave').innerHTML = trim(retArray[0]);
		             //displayWindow('divMessage',200,200); 
		},
	onFailure: function(){ alert('Something went wrong...') }
   });
}


function populateMedicalLeaveValues(id) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetMedicalLeaveValue.php';   
    
    document.getElementById('div_medicalLeave').innerHTML ='';
    new Ajax.Request(url,
    {      
		method:'post',
		parameters: {id: id },
		onCreate: function() {
			showWaitDialog();
		},
		onSuccess: function(transport){
		    hideWaitDialog(true); 
		    var ret=trim(transport.responseText);  
		    var retArray=ret.split('!~!');  
		    document.getElementById('div_medicalLeave').innerHTML = trim(retArray[0]);
		             //displayWindow('divMessage',200,200); 
		},
	onFailure: function(){ alert('Something went wrong...') }
   });
}

function autoPopulate(val,type,frm,fieldSta,fieldCty)
{
    //alert(val+"--"+type+"--"+frm+"--"+fieldSta+"--"+fieldCty);
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   var fieldState = document.getElementById(fieldSta);
   var fieldCity = document.getElementById(fieldCty);
   // alert(fieldState);
   if(frm=="Add"){
        
       if(type=="states"){
          
            fieldState.options.length=0;
            var objOption = new Option("SELECT","");
            fieldState.options.add(objOption); 
          
            var objOption = new Option("SELECT","");
            fieldCity.options.length=0;
            fieldCity.options.add(objOption); 
       }
       else if(type=="hostel"){
           
                fieldState.options.length=0;
                var objOption = new Option("SELECT","");
                fieldState.options.add(objOption); 
            
              
       }
      else{
          
            fieldCity.options.length=0;
            var objOption = new Option("SELECT","");
            fieldCity.options.add(objOption);
          
      } 
   }
   else{                        //for edit
        if(type=="states"){
            document.addForm.correspondenceStates.options.length=0;
            var objOption = new Option("SELECT","");            
             document.addForm.correspondenceStates.add(objOption); 
       }
      else{
            document.EditInstitute.city.options.length=0;
            var objOption = new Option("SELECT","");          
            document.EditInstitute.city.options.add(objOption);
      } 
   }
   
new Ajax.Request(url,
           {
             method:'post',
             parameters: {type: type,id: val},
              onCreate:function(transport){ showWaitDialog();},
             onSuccess: function(transport){
             
                    hideWaitDialog();
                     j = eval('('+transport.responseText+')'); 
                     
                     for(var c=0;c<j.length;c++){
                         if(frm=="Add"){
                             if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 fieldState.options.add(objOption); 
                             }
                            else if(type=="hostel"){
                                 var objOption = new Option(j[c].roomName,j[c].hostelRoomId);
                                 fieldState.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 fieldCity.options.add(objOption); 
                            } 
                          }
                      else{
                            if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.EditInstitute.states.options.add(objOption); 
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.EditInstitute.city.options.add(objOption); 
                            } 
                          }
                     }
                     
                  
             },
             onFailure: function(){ alert('Something went wrong...') }
           }); 
}

 
function copyText()
{
    if(document.addForm.sameText.checked==true)
    {
        document.addForm.permanentAddress1.value    = "";
        document.addForm.permanentAddress1.disabled = true;
        document.addForm.permanentAddress1.value    = ""; 
        document.addForm.permanentAddress2.disabled = true;
        document.addForm.permanentPincode.value     = "";   
        document.addForm.permanentPincode.disabled  = true;
        document.addForm.permanentCountry.value     = "";
        document.addForm.permanentCountry.disabled  = true;
        document.addForm.permanentStates.value      = "";
        document.addForm.permanentStates.disabled   = true;
        document.addForm.permanentCity.value        = "";
        document.addForm.permanentCity.disabled     = true;
        document.addForm.permanentPhone.value       = "";
        document.addForm.permanentPhone.disabled    = true;
    }
    else
    {
        document.addForm.permanentAddress1.disabled=false;
        document.addForm.permanentAddress2.disabled=false;
        document.addForm.permanentPincode.disabled=false;
        document.addForm.permanentCountry.disabled=false;
        document.addForm.permanentStates.disabled=false;
        document.addForm.permanentCity.disabled=false;
        document.addForm.permanentPhone.disabled=false;
    }
     
}
function sendKeys(eleName, e) {
	var ev = e||window.event;
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == 13 && document.addForm.searchbox.value != '') {
		refreshResourceData(<?php echo $studentDataArr[0]['studentId']?>,document.addForm.studyPeriod.value);document.getElementById('saveDiv3').style.display='';return false;
	}

	/*
	var form = document.addForm;
	//alert(form.searchbox.value);
	

		form.submit();

	}
	else {
	eval('form.'+eleName+'.focus()');
	return false;
	}
	*/
}
function preventEnterKey(evt) {
    var evt  = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13)) {
      return false;
    }
  }
document.onkeypress = preventEnterKey;
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/studentDetailContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
// $History: studentInfo.php $
//
//*****************  Version 22  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Interface/Parent
//validation & condition format updated 
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 2/04/10    Time: 11:07a
//Updated in $/LeapCC/Interface/Parent
//changes in code to show final result tab in find student & parent 
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 14/12/09   Time: 15:51
//Updated in $/LeapCC/Interface/Parent
//Corrected "Attendance" display  problems in "Parent" login
//
//*****************  Version 19  *****************
//User: Parveen      Date: 10/22/09   Time: 5:02p
//Updated in $/LeapCC/Interface/Parent
//className added (StudentAttendance/Consolidated Attendance)  
//
//*****************  Version 18  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Interface/Parent
//updated access rights
//
//*****************  Version 17  *****************
//User: Parveen      Date: 9/24/09    Time: 12:29p
//Updated in $/LeapCC/Interface/Parent
//study period added (student fee)
//
//*****************  Version 16  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Interface/Parent
//alignment & condition format updated
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 9/04/09    Time: 4:31p
//Updated in $/LeapCC/Interface/Parent
//Gurkeerat: resolved issue 1261
//
//*****************  Version 14  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Interface/Parent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 13  *****************
//User: Parveen      Date: 9/02/09    Time: 2:15p
//Updated in $/LeapCC/Interface/Parent
//attendance, course Info validation & format updated 
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/19/09    Time: 5:09p
//Updated in $/LeapCC/Interface/Parent
//Gurkeerat: resolved issue 387
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/18/09    Time: 3:11p
//Updated in $/LeapCC/Interface/Parent
//Gurkeerat: resolved issues 386,385,384
//
//*****************  Version 10  *****************
//User: Parveen      Date: 7/02/09    Time: 5:03p
//Updated in $/LeapCC/Interface/Parent
//formatting, conditions, validations updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/23/08   Time: 2:41p
//Updated in $/LeapCC/Interface/Parent
//sorting formatting setting
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/18/08   Time: 5:25p
//Updated in $/LeapCC/Interface/Parent
//code update
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/17/08   Time: 5:05p
//Updated in $/LeapCC/Interface/Parent
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/16/08   Time: 5:15p
//Updated in $/LeapCC/Interface/Parent
//attendance update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/16/08   Time: 10:45a
//Updated in $/LeapCC/Interface/Parent
//Intial Checkin 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/15/08   Time: 5:30p
//Updated in $/LeapCC/Interface/Parent
//update info
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/10/08   Time: 4:57p
//Updated in $/LeapCC/Interface/Parent
//code updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/10/08   Time: 3:28p
//Updated in $/LeapCC/Interface/Parent
//condition update
//
//

?>
