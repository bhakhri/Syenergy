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
define('MODULE','FuelUsageReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
//$flashPath = IMG_HTTP_PATH."/ampie.swf";  
//require_once(BL_PATH . "/City/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fuel Usage Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js");
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//var tableHeadArray = new Array(new Array('srNo','#','width="10%"','',false), new Array('cityName','City Name','','',true) , new Array('cityCode','City Code','width="35%"','',true), new Array('stateName','State Name','width="25%"','',false) , new Array('action','Action','width="10%"','align="right"',false));

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
//linksPerPage = <?php echo LINKS_PER_PAGE;?>;
//listURL = '<?php echo HTTP_LIB_PATH;?>/City/ajaxInitList.php';
//searchFormName = 'searchForm'; // name of the form which will be used for search
//addFormName    = 'AddCity';   
//editFormName   = 'EditCity';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteCity';
//divResultName  = 'results';
//page=1; //default page
//sortField = 'cityName';
//sortOrderBy    = 'ASC';

// ajax search results ---end ///

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
    try {
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
         
         if(document.getElementById('fuelConsumed').checked == false) {
            var fuelConsumed = 0;
            document.getElementById('fuelConsumed').value = 0;
            fuelConsumed = document.getElementById('fuelConsumed').value;
         }
         else {
            document.getElementById('fuelConsumed').value = 1;
            fuelConsumed = document.getElementById('fuelConsumed').value;
         }

         if(document.getElementById('totalKM').checked == false) {
            var totalKM = 0;
            document.getElementById('totalKM').value = 0;
            totalKM = document.getElementById('totalKM').value;
         }
         else {
            document.getElementById('totalKM').value = 2;
            totalKM = document.getElementById('totalKM').value;
         }

         if(document.getElementById('fuelAverage').checked == false) {
            var fuelAverage = 0;
            document.getElementById('fuelAverage').value = 0;
            fuelAverage = document.getElementById('fuelAverage').value;
         }
         else {
            document.getElementById('fuelAverage').value = 3;    
            fuelAverage = document.getElementById('fuelAverage').value;
         }
         
         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxGetFuelUses.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                           busId    : busStr,
                           busNames :busNameStr,
                           fromDate : document.getElementById('fromDate').value,
                           toDate   : document.getElementById('toDate').value,
                           fuelConsumed  : fuelConsumed,
                           totalKM       : totalKM,
                           fuelAverage   : fuelAverage
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
    catch(e) 
    { }
}

//This functiion used to show graphs
function showBarChartResults() {
	 x = Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amcolumn","900", "400", "8", "#FFFFFF");
    so.addVariable("path", "./");  
    so.addVariable("chart_id", "amcolumn");
    x = Math.random() * Math.random();
    so.addVariable("additional_chart_settings", "<settings><graphs><graph><type></type><title><![CDATA[Fuel Consumed (in litres)]]></title><color>add981</color></graph><graph><type></type><title><![CDATA[Total KMs Run]]></title><color>7F8DA9</color></graph><graph><type></type><title><![CDATA[Fuel Average]]></title><color>FEC514</color></graph><graph><type></type></graph></graphs><labels><labelid='1'><x>1</x><y>140</y><rotate>true</rotate><text>Average Attendance ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size></category></values><plot_area><margins><bottom>60</bottom></margins></plot_area></settings>");
    so.addParam("wmode", "transparent");
    so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/columnChartSettings.xml"));
    so.addVariable("data_file", encodeURIComponent("../Templates/Xml/fuelUserData.xml?tt="+x));
    so.addVariable("preloader_color", "#999999");
    so.write("flashcontent");
}

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='item';
window.onload=function(){
    //used to show graph on page loading
    makeSelection("busId","All");
    //makeDDHide('busId','d2','d3');
    //totalSelected('busId','d3');
    if(document.getElementById('busId').options.length > 0){
      getData();
   }
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Fuel/listFuelReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listFuelReport.php $ 
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Interface
//Made UI changes
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/08/09   Time: 11:09
//Updated in $/LeapCC/Interface
//done bug fixing.
//bug id---00001236
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/09    Time: 3:34p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1183
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//added define variable for Role Permission
//
//*****************  Version 3  *****************
//User: Administrator Date: 4/06/09    Time: 11:39
//Updated in $/LeapCC/Interface
//Fixed bugs----
//bug ids--Leap bugs2.doc(10 to 15)
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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/04/09    Time: 13:18
//Updated in $/SnS/Interface
//Modied label
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/09    Time: 10:54
//Created in $/SnS/Interface
//Added "Fuel Uses Report" module
?>