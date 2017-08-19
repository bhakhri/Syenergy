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
define('MODULE','InsuranceDueReport');
define('ACCESS','view');
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 5){
	UtilityManager::ifManagementNotLoggedIn();
}
else{
	UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Insurance Due Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js");
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";
function getData() {

		 if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-')){
             messageBox("<?php echo DATE_VALIDATION; ?>");
             document.getElementById('toDate').focus();
             return false;
         }
     
         if(document.getElementById('busId').selectedIndex==-1){
            messageBox("Select atleast one bus");
            document.getElementById('busId').focus();
            return false; 
         }
     
         var busStr='';
         var len=document.getElementById('busId').options.length;
         for(var i=0;i<len;i++){
             if(document.getElementById('busId').options[i].selected==true){
               if(busStr!=''){
                 busStr +=',';
               }  
               busStr +=document.getElementById('busId').options[i].value;   
             }
         }
         
         var url = '<?php echo HTTP_LIB_PATH;?>/Bus/ajaxGetInsuranceDueData.php';
         var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('busName','Name','width="10%" align="left"',true),
                        new Array('busNo','Registration No.','width="8%" align="left"',true),
                        new Array('isActive','In Service','width="5%" align="left"',true),
                        new Array('insuringCompanyName','Insuring Company','width="10%" align="left"',true),
						new Array('policyNo','Policy No.','width="10%" align="left"',true),
                        new Array('insuranceDueDate','Due Date','width="5%" align="center"',true)
                       );

        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
        listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','busName','ASC','resultDiv','','',true,'listObj',tableColumns,'','','&busId='+busStr+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value);
        sendRequest(url, listObj, '');
}

/* function to print insurance due report*/
function printReport() {
    if(document.getElementById('busId').selectedIndex==-1){
            messageBox("Select atleast one bus");
            document.getElementById('busId').focus();
            return false; 
    }
    var queryString=generateQueryString('allDetailsForm');
    
     var busNameStr='';
     var len=document.getElementById('busId').options.length;
     for(var i=0;i<len;i++){
         if(document.getElementById('busId').options[i].selected==true){
           if(busNameStr!=''){
             busNameStr +=',';
           }  
           busNameStr +=escape(document.getElementById('busId').options[i].text);
         }
     }
     
    queryString +='&busNameStr='+busNameStr+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;

    form = document.allDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/insuranceDueReportPrint.php?listInsurance=1&'+queryString;
    window.open(path,"InsuranceDueReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to generate insurance due reportt in CSV format*/
function printCSV() {
    if(document.getElementById('busId').selectedIndex==-1){
            messageBox("Select atleast one bus");
            document.getElementById('busId').focus();
            return false; 
    }

    queryString=generateQueryString('allDetailsForm'); 
    queryString += '&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;

    window.location='insuranceDueReportCSV.php?&'+queryString;
}

window.onload=function(){
    //used to show graph on page loading
    makeSelection("busId","All");
    getData();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Bus/listInsuranceDueReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listInsuranceDueReport.php $ 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/01/10    Time: 7:13p
//Updated in $/Leap/Source/Interface
//Add new report for insurance due report
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 10:30
//Updated in $/Leap/Source/Interface
//done bug fixing.
//bug ids---
//0000844,0000845,0000847,0000850,000843
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Interface
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/05/09    Time: 12:11
//Updated in $/SnS/Interface
//Fixed bugs---943,944,945
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:25
//Created in $/SnS/Interface
//Added "InsuranceDue Report" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:16
//Created in $/SnS/Interface
//Added "Bus Repair Cost Report" module
?>