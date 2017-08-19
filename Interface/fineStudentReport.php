<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Student Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),  
                               new Array('studentName','Name ','width="15%"','',true), 
                               new Array('rollNo','Roll No.','width="10%"','',true), 
                               new Array('instituteClassName','Class','width="20%"','',true), 
                               new Array('fineCategory','Fine Category','width="20%"','align="center"',true),
                               new Array('totalAmount','Total Fine', 'width="12%" align="right"','align="right"',true), 
                               new Array('paidAmount','Paid Fine','width="12%" align="right"','align="right"',true), 
                               new Array('balanceAmount', 'Balance','width="12%" align="right"','align="right"',true),
			                   new Array('action1','Action','width="2%"','align="center"',false)); 
                        
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FineReport/ajaxFineStudentList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddRoomAllocation';   
editFormName   = 'EditRoomAllocation';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRoomAllocation';
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';
queryString='';
// ajax search results ---end ///
valShow = '0';
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function validateAddForm() {                               
   
    page=1;
    /*  if(document.getElementById('searchDate').value!='') {
           if(document.getElementById('fromDate').value=='') {
              messageBox("Select From Date");
              document.getElementById('fromDate').focus();
              return false; 
           } 
           if(document.getElementById('toDate').value=='') {
              messageBox("Select To Date");
              document.getElementById('toDate').focus();
              return false; 
           } 
           if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-') ) {
              messageBox ("From Date cannot greater than To Date");
              document.getElementById('fromDate').focus(); 
              return false;
           } 
        }
    */
    classId = document.allDetailsForm.classId.value;
    formx = document.allDetailsForm.classId;
    var classLen= document.getElementById('classId').options.length;
    var t=document.getElementById('classId');
    if(trim(classId)=='') {
       for(k=0;k<classLen;k++) {
         if(t.options[k].value!='') {
           if(classId!='') {
             classId +=",";  
           }  
           classId += trim(t.options[k].value);
         }
       }
    }
    
    showPending='1';
    if(document.allDetailsForm.showPending[0].checked==true) {        // Paid
      showPending='1'; 
    }
    else if(document.allDetailsForm.showPending[1].checked==true) {   // Unpaid
      showPending='2'; 
    }
    else if(document.allDetailsForm.showPending[2].checked==true) {    // Both
      showPending='3'; 
    }
    
    queryString = "&classId="+classId+"&studentName="+trim(document.getElementById('studentName').value);
    queryString += "&rollNo="+trim(document.getElementById('rollNo').value)+"&showPending="+showPending;
    queryString += "&fatherName="+trim(document.getElementById('fatherName').value);
    queryString += "&receiptNo="+trim(document.getElementById('receiptNo').value);
    queryString += "&fromDate="+trim(document.getElementById('fromDate').value);
    queryString += "&toDate="+trim(document.getElementById('toDate').value);
    
    document.getElementById('nameRow1').style.display='';
    document.getElementById('nameRow2').style.display='';
    document.getElementById('nameRow3').style.display='';
    document.getElementById('printRow').style.display='';
    document.getElementById('printRow2').style.display='none';
    sendReq(listURL,divResultName,'','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&'+queryString);
    return false;  
}



window.onload=function(){
   valShow=1;
   getShowDetail();
}

function getShowSearch(val) {
   
   showStatus=''; 
   if(val=='') {
     showStatus='none';  
   } 
   
   for(i=1;i<=6;i++) {
     id = "searchDt"+i;  
     eval("document.getElementById('"+id+"').style.display=showStatus");
   }
}



function getShowDetail() {
   document.getElementById("showhideSeats").style.display='';
   document.getElementById("lblMsg").innerHTML="Please Click to Hide Advance Search";
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif";
   if(valShow==0) {
     document.getElementById("showhideSeats").style.display='none';
     document.getElementById("lblMsg").innerHTML="Please Click to Show Advance Search"; 
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif";
     valShow=1;
   }
   else {
     valShow=0;  
   }
}

function showFineDetails(id,dv,str,w,h) {
   //displayWindow('divMessage',600,600);
   displayFloatingDiv(dv,'', w, h, 800, 400)
   refreshStudentFineData(id,str);
   return false;
}



function printReport() {
   
    //var params = generateQueryString('allDetailsForm');
    var qstr=queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField; 
    qstr += "&startingRecord="+trim(document.getElementById('startingRecord').value);
    qstr += "&totalRecords="+trim(document.getElementById('totalRecords').value);
    path='<?php echo UI_HTTP_PATH;?>/studentFineReportPrint.php?'+qstr;  
    window.open(path,"RoomAllocationReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    //var params = generateQueryString('allDetailsForm');
    var qstr=queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    qstr += "&startingRecord="+trim(document.getElementById('startingRecord').value);
    qstr += "&totalRecords="+trim(document.getElementById('totalRecords').value);
    path='<?php echo UI_HTTP_PATH;?>/studentFineReportCSV.php?'+qstr;
    window.location = path;
}


function resetForm() {
   document.getElementById('results').innerHTML='';   
   document.getElementById('printRow').style.display='none';
   document.getElementById('printRow2').style.display='';
}

function refreshStudentFineData(Id,str) {

	document.getElementById('searchStudentFineList').innerHTML = str;	
    document.getElementById('message').innerHTML = '';    

	url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxinitStudentFine.php';
	var tbHeadArray =new Array(
				new Array('srNo','#','width="5%"',false),
				new Array('fineDate','Date','width="15%", align="center"',true), 
				new Array('fineCategoryName','Fine/Receipt No.<span class="redColorBig">*</span>','width="20%"',true),
				new Array('reason','Reason','width="20%"',false),
				new Array('amount','Fine Amount','width="10%", align="right"',false),
				new Array('paidAmount','Paid Amount','width="10%", align="right"',false)
				//new Array('balance','Balance','width="10%", align="right"',true)
		       );	
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	listObj11 = new initPage(url,recordsPerPage,linksPerPage,1,'','fineDate','ASC','message','','',true,'listObj11',tbHeadArray,'','','&id='+Id);
	sendRequest(url, listObj11, '')
}

</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FineReport/listFineReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
    <script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

