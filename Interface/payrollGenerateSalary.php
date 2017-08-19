<?php
//-------------------------------------------------------
//  This File contains ajax functions for salary generation
//
//
// Author :Abhiraj Malhotra
// Created on : 07-May-2010
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
<title><?php echo SITE_NAME;?>: Generate Salary </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>                                                 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','left',false), 
                                new Array('employeeName','Employee Name','width="25%"','left',true) , 
                                new Array('employeeCode','Employee Code','width="15%"','left',true) , 
                                new Array('department','Department','width="20%"','left',false),
                                new Array('designation','Designation','width="15%"','left',false),
                                new Array('status','Status','width="25%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitGeneratedSalaryList.php';
searchFormName = 'generateSalary'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'employeeName';
sortOrderBy    = 'ASC';
 
function generateSalaryConfirm(month,year)
{
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGenerateSalary.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {month: month, year: year, param:'confirm'},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                     generateSalary(month,year);
                     
                     }
                     else if(trim(transport.responseText)==-1)
                     {
                        alert("Error: Please generate previous month's salary") 
                     }
                     else {
                        if(confirm("<?php echo "Salary already generated once for this period. Regenerate Salary?";?>")) {
                         generateSalary(month,year);
                     }
                 } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });  
}

function hidediv()
{
 document.getElementById('msg').innerHTML='';
 document.getElementById('statusDiv').style.display='none';     
}
// For adding a new head
function generateSalary(month,year) {
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGenerateSalary.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {param:'', month:month, year:year},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                  //alert(transport.responseText);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     //var x=(screen.width/2-200);                    
                     //document.getElementById('statusDiv').style.display='';   
                     //document.getElementById('statusDiv').style.left=x+'px';
                    // document.getElementById('msg').innerHTML='<b>Salary generated successfully</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href=# onclick="hidediv();" style="font-family:arial; font-size:12px; font-weight:bold; text-decoration:underline; color:#2a5db0">hide</a>';   
                     alert("Salary generated successfully");
                     }
                     else {
                         alert("Error occurred while generating salary");
                         return false;
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
           //document.getElementById('nameRow2').style.display='none';
           sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='+month+'&           year='+year);
           
           
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
function showDateWiseRecords(month,year)
{
    if(month!="" && year!="")
    {
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='+document.getElementById('month').value+'&year='+<?php echo "'".date('Y')."'";?>);
    }
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Payroll/generateSalary.php");   
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
 <script type="text/javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='+document.getElementById('month').value+'&year='+<?php echo "'".date('Y')."'";?>);
 </script>
</body>
</html>
