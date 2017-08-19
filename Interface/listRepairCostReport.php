<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRepairCost');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Repair Cost Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js");
?> 
<script language="javascript">

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";
function getData() {
         if(!dateDifference(document.getElementById('fromDate').value,serverDate,'-')){
             messageBox("From date can not be greater than current date");
             document.getElementById('fromDate').focus();
             return false;
         }
         if(!dateDifference(document.getElementById('toDate').value,serverDate,'-')){
             messageBox("To date can not be greater than current date");
             document.getElementById('toDate').focus();
             return false;
         }
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
         
         var busNameStr='';
         for(var i=0;i<len;i++){
             if(document.getElementById('busId').options[i].selected==true){
               if(busNameStr!=''){
                 busNameStr +='~';
               }  
               busNameStr +=document.getElementById('busId').options[i].text;   
             }
         }
         url = '<?php echo HTTP_LIB_PATH;?>/BusRepair/ajaxGetRepairCost.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                           busId    : busStr,
                           busNames :busNameStr,
                           fromDate : document.getElementById('fromDate').value,
                           toDate   : document.getElementById('toDate').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    showBarChartResults();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This functiion used to show graphs
function showBarChartResults() {
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amcolumn","900", "400", "8", "#FFFFFF");
    so.addVariable("path", "./");  
    so.addVariable("chart_id", "amcolumn");
    x = Math.random() * Math.random();
    so.addVariable("additional_chart_settings", "<settings><graphs><graph><type></type><title><![CDATA[Cost (in Rs.)]]></title><color>add981</color></graph></graphs><labels><labelid='1'><x>1</x><y>140</y><rotate>true</rotate><text>Average Attendance ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size></category></values><plot_area><margins><bottom>60</bottom></margins></plot_area></settings>");
    so.addParam("wmode", "transparent");
    so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/columnChartSettings2.xml"));
    so.addVariable("data_file", encodeURIComponent("../Templates/Xml/repairCostData.xml?t="+x));
    so.addVariable("preloader_color", "#999999");
    so.write("flashcontent");
}

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='item';
window.onload=function(){
    //used to show graph on page loading
    makeSelection("busId","All");
    makeDDHide('busId','d2','d3');
    totalSelected('busId','d3');
    if(document.getElementById('busId').options.length > 0){
      getData();  
    }
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BusRepair/listRepairCostReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listRepairCostReport.php $ 
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Interface
//Made UI changes
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/09    Time: 3:34p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1183
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//added define variable for Role Permission
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/02/09    Time: 4:42p
//Updated in $/LeapCC/Interface
//Updated with random number parameter in flash reports
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 13:15
//Created in $/LeapCC/Interface
//Add modified files for bus masters
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 11:42
//Created in $/Leap/Source/Interface
//Added new files for bus masters in Leap
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 11:16
//Created in $/SnS/Interface
//Added "Bus Repair Cost Report" module
?>