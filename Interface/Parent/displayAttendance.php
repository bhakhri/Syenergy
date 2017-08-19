<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
// Author :Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentDisplayAttendance');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();    
//require_once(BL_PATH . "/ScParent/scInitDisplayAttendance.php");
require_once(BL_PATH . "/Parent/initData.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Details </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
sortField = 'className';
sortOrderBy = 'ASC';

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

//function passes two parameters i.e dates to ajax file which returns table that contains data 

function validateData(){

      if(trim(document.getElementById('startDate2').value)==""){
       messageBox("Attendance Date Can Not Be Empty");
       document.getElementById('startDate2').focus();    
       return false;
      }
      
      /*if(trim(document.getElementById('toDate').value)==""){
       messageBox("Attendance Date Can Not Be Empty");
       document.getElementById('toDate').focus();        
       return false;
      }*/
    
      //calculate current date
       var d=new Date();
      var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
      
   /* if(!dateDifference(document.getElementById('toDate').value,cdate,"-")){
           
           messageBox("To Date Can Not be Greater Than Current Date");   
           document.getElementById('toDate').value="";  
           document.getElementById('toDate').focus();  
           return false;
         }

    if(!dateDifference(document.getElementById('startDate').value,document.getElementById('toDate').value,"-")){
           
           messageBox("From Date Can Not be Greater Than To Date");   
           document.getElementById('startDate').value="";  
           document.getElementById('startDate').focus();  
           return false;
         }*/
         
         getAttendance();
         return false;

}


//this variable is used to determine whether group wise or 
//consolidated attendance view is required
//Modified By : Dipanjan Bhattacharjee
//Date: 06.10.2009

var attendanceConsolidatedView=1;
var viewType=0;
var studentId="<?php echo $sessionHandler->getSessionVariable('StudentId')?>";
var classId="<?php echo $sessionHandler->getSessionVariable('ClassId')?>";

function getAttendance() {
 
var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php'; 

  //if consolidated view is not required
 if(attendanceConsolidatedView==1){
             var tbHeadArray = new Array(  
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
            
            //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
            
           
 }
 else{
     var tbHeadArray =new Array(
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
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','results','','',true,'listObj1',tbHeadArray,'','','&rClassId='+classId+'&startDate2='+document.attendance.startDate2.value+'&consolidatedView='+attendanceConsolidatedView);
     sendRequest(url, listObj1, '')
 
 return false;
}


function toggleAttendanceDataFormat(){
 var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php'; 
 
 //if consolidated view is not required
 if(viewType==1){ 
  var tbHeadArray = new Array(  
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
  var tbHeadArray =new Array(
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
 
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 
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
     listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','results','','',true,'listObj1',tbHeadArray,'','','&rClassId='+classId+'&startDate2='+document.attendance.startDate2.value+'&consolidatedView='+attendanceConsolidatedView);
     sendRequest(url, listObj1, '')
 
}


window.onload = function(){
   getAttendance();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "div_dutyLeave" DIV
//
//Author : Aditi Miglani
// Created on : (04.11.2011)
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
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

function printReport() {

	form = document.attendance;
	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceReportPrint.php?'+'&classId='+classId+'&startDate2='+form.startDate2.value+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField+'&consolidatedView='+attendanceConsolidatedView;
	hideUrlData(path,true);

}

function printCSV() {

	form = document.attendance;
	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceReportPrintCSV.php?'+'&classId='+classId+'&startDate2='+form.startDate2.value+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField+'&consolidatedView='+attendanceConsolidatedView;
	window.location = path;
    
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/displayAttendanceContents.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
