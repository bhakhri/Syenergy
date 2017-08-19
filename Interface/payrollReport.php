<?php 
//-------------------------------------------------------
//  This File contains ajax function used in Generating All Payroll Reports
//
//
// Author :Abhiraj Malhotra
// Created on : 24.Apr.10
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleWiseList');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Payroll Report </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
//For autosuggest
require_once(TEMPLATES_PATH .'/autoSuggest.php');
echo UtilityManager::includeAutosuggest(); 
//Autosuggest inclusion ends
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="3%" align=left','align=left',false), 
								new Array('employeeName','Name','width=12%  align=left',' align=left',false),
                                new Array('employeeCode','Emp. Code','width="10%"  align=left',' align=left',false),
                                new Array('employeeDesignation','Desig.','width="15%"  align=left',' align=left',false),
                                new Array('employeeDepartment','Dept.','width="15%"  align=left',' align=left',false),
                                new Array('dateOfJoining','Date of Joining','width="10%"  align=left',' align=left',false),
                                new Array('salary','Salary','width="10%"  align=left',' align=left',false),
								new Array('act','Action','width="15%"  align=center',' align=center',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Payroll/initPayrollReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'heads'; // name of the form which will be used for search
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'employeeName';
sortOrderBy  = 'ASC';

function showHoldReason(empId,month,year)
{
   var dv='holdReason';
   var w=500;
   var h=500;
   displayWindow(dv,w,h);
    url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetHoldHistory.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {empId: empId, month:month, year:year, param:'getReason'},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(transport.responseText!=0 && trim(transport.responseText)!="")
                    {
                        j = trim(transport.responseText);
                    
                         document.getElementById('reason').innerHTML = j;
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
  
}
function populateDropDown()
{
    empDetail=document.getElementById('searchfield').value;
    

    var emp=empDetail.substring(empDetail.indexOf("(")+1,empDetail.lastIndexOf(")"));
    if(empDetail!="" && trim(emp).length==0)
    {
        document.getElementById('monthDD').innerHTML='<select name="month" id="month" disabled="true"><option value=""></option></select>';
        document.getElementById('yearDD').innerHTML='<select name="year" id="year" disabled="true"><option value=""></option></select>';
        document.getElementById('month').value="";
        document.getElementById('year').value="";
        document.getElementById('month').disabled=true;  
        document.getElementById('year').disabled=true; 
        return;
        
    } 
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxPopulateMonthYearDropdown.php';
             var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {empId:emp, txtField:empDetail, sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)!="" || trim(transport.responseText)!=0)
                    {  
                        
                      j= trim(transport.responseText).evalJSON();
                      if(j.monthCount>0 && j.yearCount>0)
                      {
                        
                        document.getElementById('monthDD').innerHTML=j.monthHTML;  
                        document.getElementById('yearDD').innerHTML=j.yearHTML;
                        //document.getElementById('showBtn').display=''; 
                      }
                      else
                      {
                          
                          document.getElementById('monthDD').innerHTML='<select name="month" id="month" disabled="true"><option value=""></option></select>';
                          document.getElementById('yearDD').innerHTML='<select name="year" id="year" disabled="true"><option value=""></option></select>';
                          document.getElementById('month').value="";
                          document.getElementById('year').value="";
                          document.getElementById('month').disabled=true;  
                         document.getElementById('year').disabled=true; 
                      }
                        
                    }
                    else
                      {
                           
                          document.getElementById('monthDD').innerHTML='<select name="month" id="month" disabled="true"><option value=""></option></select>';
                          document.getElementById('yearDD').innerHTML='<select name="year" id="year" disabled="true"><option value=""></option></select>';
                          document.getElementById('year').value="";
                          document.getElementById('month').value=""; 
                          document.getElementById('month').disabled=true;  
                         document.getElementById('year').disabled=true; 
                      }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });
    

}
function showList() {
     empDetail=document.getElementById('searchfield').value;
    var emp=empDetail.substring(empDetail.indexOf("(")+1,empDetail.lastIndexOf(")"));
    if(trim(document.getElementById('month').value)=="" || trim(document.getElementById('year').value)=="")
    {
        alert("Please select month and year");
        document.getElementById('overallSummary').style.display='none';
        document.getElementById('nameRow1').style.display='none';
        document.getElementById('nameRow2').style.display='none';
    }
    else if(trim(empDetail)!="" && trim(emp)=="")
    {
         alert("Invalid Employee Code");
        document.getElementById('overallSummary').style.display='none';
        document.getElementById('nameRow1').style.display='none';
        document.getElementById('nameRow2').style.display='none';
    }
    else
    {
    <?php 
    global $sessionHandler;
    $sessionHandler->unsetSessionVariable('month');
    $sessionHandler->unsetSessionVariable('year');
    $sessionHandler->unsetSessionVariable('searchfield');
    ?>
    if(trim(document.getElementById('searchfield').value)=="")
    {
        document.getElementById('overallSummary').style.display='';
        var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitReportSummary.php';
             var j;               
            new Ajax.Request(url,
            {
                method:'post',
                parameters: {month:document.getElementById('month').value, year:document.getElementById('year').value, sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)!="")
                    {  
                      j= trim(transport.responseText).evalJSON();
                      str= 'Salary of <span style=color:red>'+j.count+'</span> employee(s) for the month of <span style=color:red>'+
                      document.getElementById('month').value+' '+document.getElementById('year').value+'</span> is Rs: <span style=color:red>'+j.amount+'/-</span>';
                      
                      if(j.holdCount!=0)
                      {
                          var diff=j.empCount-j.count;
                          str=str+'<br />&nbsp;&nbsp;Salary of <span style=color:red>'+j.holdCount+'</span> employee(s) on hold';
                      }
                      
                      if(j.count!=j.empCount)
                      {
                          var diff=j.empCount-j.count;
                          str=str+'<br />&nbsp;&nbsp;Salary of <span style=color:red>'+diff+'</span> employee(s) still to be defined in the system';
                      }
                      document.getElementById('summary').innerHTML=str;
                      //alert(j.salary_head_temp[0].userId);
                        
                    }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });   
    }
	else
    {
      document.getElementById('overallSummary').style.display='none';  
    }
    document.getElementById('listTitle').innerHTML='Payroll Report For '+document.getElementById('month').value+" "+document.getElementById('year').value;
    document.getElementById('nameRow1').style.display='';
    document.getElementById('nameRow2').style.display=''; 
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&month='+document.getElementById('month').value
    +'&year='+document.getElementById('year').value+'&employee='+document.getElementById('searchfield').value);

}
}

function showSalaryDetail(empId, month, year)
{
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetSalarySlip.php';
    var j;
    new Ajax.Request(url,
            {
                method:'post',
                parameters: {salMonth:month, salYear:year, empId:empId, sid:Math.random()},
                asynchronous:false,
                     onCreate: function(){
                     showWaitDialog(true);
                 },
                onSuccess: function(transport){
                    
                    hideWaitDialog(true);
                    //alert(transport.responseText);
                    
                    if(trim(transport.responseText)!="")
                    {  
                      j= trim(transport.responseText).evalJSON();
                      document.getElementById('empName').innerHTML=j.employeeInfo[0].employeeName;
                      document.getElementById('empCode').innerHTML=j.employeeInfo[0].employeeCode;
                      document.getElementById('empDept').innerHTML=j.employeeInfo[0].employeeDept;
                      if(trim(j.employeeInfo[0].esiNumber)=="")
                      {
                         document.getElementById('esi').innerHTML='--';
                      }
                      else
                      {  
                        document.getElementById('esi').innerHTML=j.employeeInfo[0].esiNumber;
                      }
                      if(trim(j.employeeInfo[0].providentFundNo)=="")
                      {
                         document.getElementById('pf').innerHTML='--';
                      }
                      else
                      {
                        document.getElementById('pf').innerHTML=j.employeeInfo[0].providentFundNo;
                      }
                      if(trim(j.employeeInfo[0].panNo)=="")
                      {
                         document.getElementById('pan').innerHTML='--';
                      }
                      else
                      {
                        document.getElementById('pan').innerHTML=j.employeeInfo[0].panNo;
                      }
                      document.getElementById('empDesignation').innerHTML=j.employeeInfo[0].employeeDesignation;
                      document.getElementById('totEarnings').innerHTML=j.total[0].totalEarning;
                      document.getElementById('totDeductions').innerHTML=j.total[0].totalDeduction;
                      document.getElementById('net').innerHTML=j.total[0].net;
                      document.getElementById('salMonth').innerHTML=month;
                      document.getElementById('salYear').innerHTML=year;
                      document.getElementById('yearMonth').innerHTML=month+' '+year;
                      
                      var str_earning='<table width=100% border=0 cellspacing=0 cellpadding=0>';
                      var headArraySize_Earning=j.infoEarning.length;
                      for(var i=0;i<headArraySize_Earning;i++)
                      {
                        str_earning=str_earning+'<tr><td width=65% style="border-right:1px solid #000;padding:8px">'+j.infoEarning[i].headName+'</td><td width=35% style="padding:8px">'+j.infoEarning[i].headValue+' INR </td></tr>';                       
                      }
                      var str_deduction='<table width=100% border=0 cellspacing=0 cellpadding=0>';
                      var headArraySize_Deduction=j.infoDeduction.length;
                      for(var i=0;i<headArraySize_Deduction;i++)
                      {
                        str_deduction=str_deduction+'<tr><td width=65% style="border-right:1px solid #000;padding:8px">'+j.infoDeduction[i].headName+'</td><td width=35% style="padding:8px">'+j.infoDeduction[i].headValue+' INR </td></tr>';                       
                      }
                      
                      if(headArraySize_Earning > headArraySize_Deduction)
                      {
                         headArraySize_Diff = headArraySize_Earning - headArraySize_Deduction;
                         for(var i=0;i<headArraySize_Diff;i++)
                         {
                            str_deduction=str_deduction+'<tr><td width=65% style="border-right:1px solid #000;padding:8px">&nbsp;</td><td width=35% style="padding:8px">&nbsp;</td></tr>'; 
                         } 
                      }
                      if(headArraySize_Deduction > headArraySize_Deduction)
                      {
                         headArraySize_Diff = headArraySize_Deduction - headArraySize_Earning;
                         for(var i=0;i<headArraySize_Diff;i++)
                         {
                            str_earning=str_earning+'<tr><td width=65% style="border-right:1px solid #000;padding:8px">&nbsp;</td><td width=35% style="padding:8px">&nbsp;</td></tr>'; 
                         } 
                      }
                      str_earning=str_earning+'</table>'; 
                      str_deduction=str_deduction+'</table>';
                      document.getElementById("earningHeads").innerHTML=str_earning;
                      document.getElementById("deductionHeads").innerHTML=str_deduction;
                      //document.getElementById("downloadButtons").innerHTML='<input type=image src=<?php echo IMG_HTTP_PATH;?>/download2.gif onclick=downloadPdf('+empId+',"'+month+'","'+year+'"); /> 
                      document.getElementById("downloadButtons").innerHTML='<input type=image src=<?php echo IMG_HTTP_PATH;?>/print.gif onclick=printReport('+empId+',"'+month+'","'+year+'") />';                      
                      changeColor(currentThemeId);
                      displayWindow('ViewSalarySlip',700,500); 
                      
                      //alert(j.salary_head_temp[0].userId);
                         
                    }
                   
                },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
            });
    

}

function printReport(empId, month, year) {
    var path='<?php echo UI_HTTP_PATH;?>/salarySlipPrint.php?empId='+empId+'&month='+month+'&year='+year;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"SalarySlip","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

function downloadPdf(empId, month, year) {
    var path='<?php echo UI_HTTP_PATH;?>/salarySlipPdf.php?empId='+empId+'&month='+month+'&year='+year;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"SalarySlip","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

</script>

<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js"); 

?> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Payroll/payrollReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    
    ?>
</body>
</html>
