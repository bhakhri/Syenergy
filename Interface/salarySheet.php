<?php 
//-------------------------------------------------------
//  This File contains ajax function used in generate salary sheet
// Author :Abhiraj
// Created on : 01.05.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
<title><?php echo SITE_NAME;?>: Payroll Report </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
var i=0;
var countChecked=0;
var str='';
function showSalarySheet()
{
    <?php
    global $sessionHandler;
    $sessionHandler->unsetSessionVariable('selectedHeads');
    $sessionHandler->unsetSessionVariable('dataArray');
    $sessionHandler->unsetSessionVariable('total');
    ?>
    var url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitSalarySheet.php';
    var j;               
    new Ajax.Request(url,
    {
        method:'post',
        parameters: $('heads').serialize(true),
             onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            
            hideWaitDialog(true);
            //alert(transport.responseText);
            
            if(trim(transport.responseText)!="" && trim(transport.responseText)!=0)
            {  
              
                j= trim(transport.responseText);
                if(j==1)
                {
                    alert("Please select atleast one head");
                    document.getElementById('nameRow2').style.display= 'none';
                    document.getElementById('nameRow3').style.display= 'none';
                    document.getElementById('nameRow1').style.display= 'none';
                    return;
                }
                else
                {
                    document.getElementById('nameRow2').style.display= '';
                    document.getElementById('nameRow3').style.display= '';
                    document.getElementById('nameRow1').style.display= '';
                    document.getElementById('results').innerHTML= j;
                }
                
            }
           
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
    });
}

function printSalarySheet(month, year) {
   // alert(document.heads.headsArray[].value);
    var path='<?php echo UI_HTTP_PATH;?>/salarySheetPrint.php?month='+month+'&year='+year;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"SalarySheet","status=1,menubar=1,scrollbars=1, width=990, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

function downloadSalarySheet(month, year) {
    var path='<?php echo UI_HTTP_PATH;?>/displaySalarySheetCSV.php?month='+month+'&year='+year;
    try{
     var a=window.open(path,"SalarySheet","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
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
    require_once(TEMPLATES_PATH . "/Payroll/salarySheet.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    
    ?>
</body>
</html>

