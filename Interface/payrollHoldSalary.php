<?php
//-------------------------------------------------------
//  This File contains ajax functions for holding salary of an employee
//
//
// Author :Abhiraj Malhotra
// Created on : 11-May-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Payroll Hold Salary </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2();
require_once(TEMPLATES_PATH .'/autoSuggest.php'); 
echo UtilityManager::includeAutosuggest();
?>                                                
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                                new Array('employeeName','Employee Name','width="25%"','',true) , 
                                new Array('employeeCode','Employee Code','width="15%"','',false) , 
                                new Array('department','Department','width="20%"','',false),
                                new Array('designation','Designation','width="15%"','',false),
                                new Array('takeAction','Action','width="25%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitHoldSalaryList.php';
searchFormName = 'holdSalary'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'employeeName';
sortOrderBy    = 'ASC';

function noHistory()
{
    alert("<?php echo NO_HISTORY; ?>");
} 

function showList()
{
    
    if(document.getElementById('searchfield').value!="" && document.getElementById('searchfield').value.indexOf("(") && document.getElementById('searchfield').value.indexOf(")")<0)
    {
        alert("<?php echo EMPLOYEE_CODE_BLANK;?>");
        document.getElementById('nameRow2').style.display='none';
        return;
    }
     empDetail=document.getElementById('searchfield').value;
    

    var emp=empDetail.substring(empDetail.indexOf("(")+1,empDetail.lastIndexOf(")"));
    if(document.getElementById('searchfield').value!="" && trim(emp).length==0)
    {
        alert("<?php echo EMPLOYEE_CODE_BLANK;?>");
        document.getElementById('nameRow2').style.display='none';
        return;
    }
    document.getElementById('nameRow2').style.display='';  
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='
   +document.holdSalary.month.value+'&year='+document.holdSalary.year.value);
    
   changeColor(currentThemeId);
   
}
 function holdEmpSalary(empId,month,year)
{
    if(trim(document.holdUnholdSalary.holdReason.value)=="")
    {
        alert("<?php echo ENTER_REASON;?>");
        document.holdUnholdSalary.holdReason.focus();
        return;
    }
    if(trim(document.holdUnholdSalary.holdReason.value).length>60)
    {
         alert("<?php echo REASON_LENGTH_EXCEEDS;?>");
         return; 
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxHoldSalary.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {empId: empId, month:month, year:year,reason:trim(document.holdUnholdSalary.holdReason.value) },
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)=="saved")
                    {
                        hiddenFloatingDiv('holdUnhold');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='
                        +document.holdSalary.month.value+'&year='+document.holdSalary.year.value);
                        
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
           
}
function viewHistory(empId)
{
    var dv='viewHoldHistory';
    var w=400;
    var h=200;
    displayWindow(dv,w,h);
    populateHistory(empId);  
}

function populateHistory(empId)
{
    url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetHoldHistory.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {empId: empId},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(transport.responseText!=0 && trim(transport.responseText)!="")
                    {
                        j = trim(transport.responseText);
                        
                        document.getElementById('historyDiv').innerHTML = j;
                        
                        
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
     
function showHoldSalary(empId,month,year)
{
    var dv='holdUnhold';
   var w=400;
   var h=200;
   displayWindow(dv,w,h);
   document.holdUnholdSalary.employeeId.value=empId;
   document.holdUnholdSalary.month.value=month;
   document.holdUnholdSalary.year.value=year;
   document.getElementById('submitBtn').src="<?php echo IMG_HTTP_PATH;?>/hold.gif";
   changeColor(currentThemeId);   
}
function showUnholdSalary(empId,month,year)
{
   var dv='holdUnhold';
   var w=400;
   var h=200;
   displayWindow(dv,w,h);
   document.holdUnholdSalary.employeeId.value=empId;
   document.holdUnholdSalary.month.value=month;
   document.holdUnholdSalary.year.value=year;
   document.getElementById('submitBtn').src="<?php echo IMG_HTTP_PATH;?>/unhold.gif";  
   changeColor(currentThemeId);
}


 /*
function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/displayHeadsReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"SalaryHeadReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

 function to output data to a CSV
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayHeadsCSV.php?'+qstr;
    window.location = path;
} 
  
} */

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Payroll/payrollHoldSalary.php");   
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
 <script type="text/javascript">
 document.getElementById('nameRow2').style.display='';
 
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='+<?php 
 echo "'".date('M')."'"?>+'&year='+<?php echo "'".date('Y')."'";?>);
changeColor(currentThemeId); 
 </script> 
</body>
</html>
