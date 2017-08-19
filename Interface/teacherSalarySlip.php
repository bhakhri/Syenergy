<?php 
//-------------------------------------------------------
//  This File contains ajax function used in generate teacher salary slip
//
//
// Author :Abhiraj
// Created on : 01.05.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');

if($sessionHandler->getSessionVariable('RoleId')==2)
{
    UtilityManager::ifTeacherNotLoggedIn();
}
else
{
    UtilityManager::ifNotLoggedIn(); 
}    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Salary Slip Report </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
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
                    //alert(trim(transport.responseText).substring(0,2));
                    if(trim(transport.responseText)!="" && trim(transport.responseText)!=0 && 
                    trim(transport.responseText).substring(0,2)!=-1)
                    { 
                      document.getElementById('listTitle').innerHTML='Salary Slip For '+month+' '+year;
                      document.getElementById('nameRow1').style.display=''; 
                      document.getElementById('nameRow2').style.display='';
                      document.getElementById('holdSalary1').style.display='none';
                      document.getElementById('holdReason').innerHTML=''; 
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
                      //document.getElementById("downloadButtons").innerHTML='<img src=<?php //echo IMG_HTTP_PATH;?>/download2.gif onclick=downloadPdf('+empId+',"'+month+'","'+year+'"); /> 
                      document.getElementById("downloadButtons").innerHTML='<img src=<?php echo IMG_HTTP_PATH;?>/print.gif onclick=printReport('+empId+',"'+month+'","'+year+'") />';                      
                      displayWindow('ViewSalarySlip',700,500); 
                      
                      //alert(j.salary_head_temp[0].userId);
                        
                    }
                    else if(trim(transport.responseText).substring(0,2)==-1)
                    {
                       document.getElementById('nameRow1').style.display='none'; 
                       document.getElementById('nameRow2').style.display='none';
                       document.getElementById('holdSalary1').style.display='';
                       document.getElementById('holdReason').innerHTML=trim(transport.responseText).substring(3);
                        
                    }
                    else
                    {
                        document.getElementById('nameRow1').style.display='none'; 
                        document.getElementById('nameRow2').style.display='none';
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
    require_once(TEMPLATES_PATH . "/Payroll/teacherSalarySlip.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    
    ?>
</body>
</html>

