<?php 
//-------------------------------------------------------
//  This File contains starting code for employee Info uploading and Pie Charts
//
//
// Author :Gurkeerat Sidhu
// Created on : 17-Nov-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadEmployeeDetail');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload/Export Employee Info</title>
<?php 
require_once(BL_PATH . "/Index/getEmployeeGraph.php");
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js"); 
$flashPath = IMG_HTTP_PATH."/ampie.swf"; 
?> 
<script language="javascript">
var  valShow=0;
 x = Math.random() * Math.random();
function getSearch(){

    if(isEmpty(document.getElementById('searchStudent').value)){
       
       messageBox("Please select search parameter");
       document.searchForm.searchStudent.focus();
       return false;
    }
    showGraph();
    return false;
}
function showGraph() {

     
    var url = '<?php echo HTTP_LIB_PATH;?>/Index/getEmployeeGraph2.php';
    var pars = 'searchStudent='+document.getElementById('searchStudent').value;
    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
        asynchronous:false,
         onCreate: function(){
             showWaitDialog();
         },
        onSuccess: function(transport){
        hideWaitDialog();
        
        if("rolewise" == trim(transport.responseText)) {
            
            showRolePieResults();
        }
        
        if("marital" == trim(transport.responseText)) {
            
            showMaritalPieResults();
        }
       
        if("state" == trim(transport.responseText)) {
            
            showStatePieResults();
        }
      
    },
    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}
function showRolePieResults() {

     
      var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
      so.addVariable("path", "ampie/");  
      so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
      so.addParam("wmode", "transparent");
      so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
      so.addVariable("additional_chart_settings","<settings><pie><x>110</x><y>100</y></pie></settings>");
      so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeRoleWiseData.xml?t="+x));
      so.addVariable("preloader_color", "#999999");
      so.write("resultsDiv");
}
function showMaritalPieResults() {

       var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
      so.addVariable("path", "ampie/");  
      so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart    
      so.addParam("wmode", "transparent");
      so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
      so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
      so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeMaritalData.xml?t="+x));
      so.addVariable("preloader_color", "#999999");
      so.write("resultsDiv");
}
function showStatePieResults() {

      var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
      so.addVariable("path", "ampie/");  
      so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart    
      so.addParam("wmode", "transparent");
      so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
      so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
      so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeStateDetailData.xml?t="+x));
      so.addVariable("preloader_color", "#999999");
      so.write("resultsDiv");
}

//////////////////////////////////////////////////////////////////////////////////////////
// Functions that are called by the chart ////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
      
// CHART INITED //////////////////////////////////////////////////////////////////////////    
// amChartInited(chart_id)
// This function is called when the chart is fully loaded and initialized.
function amChartInited(chart_id){
  // get the flash object into "flashMovie" variable   
  flashMovie = document.getElementById(chart_id);
  // tell the field with id "chartfinished" that this chart was initialized
  document.getElementById("chartfinished").value = "chart " + chart_id + " is finished";           
}      
      
// RETURN DATA ///////////////////////////////////////////////////////////////////////////
// amReturnData(chart_id, data)
// This function is called when you request data from a chart 
//  by calling the flashMove.getData() function.
function amReturnData(chart_id, data){
  document.getElementById("data").value = unescape(data);
}

// RETURN PARAM //////////////////////////////////////////////////////////////////////////
// amReturnParam(chart_id, param)
// This function is called when you request a setting from a chart  
// by calling the flashMovie.getParam(param) function.
function amReturnParam(chart_id, param){
  document.getElementById("returnedparam").value = unescape(param);
}

// RETURN SETTINGS ///////////////////////////////////////////////////////////////////////
// amReturnSettings(chart_id, settings)
// This function is called when you request settings from a chart 
// by calling flashMovie.getSettings() function.  
function amReturnSettings(chart_id, settings){
  document.getElementById("settings").value = unescape(settings);
}      

// RETURN IMAGE DATA /////////////////////////////////////////////////////////////////////
// amReturnImageData(chart_id, data)
// This function is called when the export to image process is finished and might be used
// as alternative way to get image data (instead of posting it to some file)
function amReturnImageData(chart_id, data){
  // your own functions here
}

// ERROR /////////////////////////////////////////////////////////////////////////////////
// amError(chart_id, message)
// This function is called when an error occurs, such as no data, or file not found.
function amError(chart_id, message){
  alert(message);
}    

// FIND OUT WHICH SLICE WAS HOVERED //////////////////////////////////////////////////////
// amSliceOver(chart_id, index, title, value, percents, color, description)
// This function is called when the viewer rolls over the slice. It returns chart_id, the 
// sequential number of the slice (index), the title, value, percent value, 
// color and description.
function amSliceOver(chart_id, index, title, value, percents, color, description){
  document.getElementById("sliceover").value = index;
}

// FIND OUT WHEN THE MOUSE WAS MOVED AWAY FROM A SLICE ///////////////////////////////////
// amSliceOut(chart_id)
// This function is called when the viewer rolls away from the slice.
function amSliceOut(chart_id){
  document.getElementById("sliceover").value = "";
} 
   

// EXPORT AS IMAGE ///////////////////////////////////////////////////////////////////////
// flashMovie.exportImage([file_name]) 
// This function will start the process of exporting the chart as an image. The file_name
// is a name of a file to which image data will be posted (files provided in the download 
// package are export.php and export.aspx). The file_name is optional and can be set in 
// the <export_as_image><file> setting.  

function exportImage() {
  flashMovie.exportImage('ampie/export.php');  
}

window.onload = function(){
 getShowDetail(); 
    document.searchForm.reset();
    document.addForm.reset();
}

function exportEmployee() {
    document.getElementById('editForm').onsubmit=function() {
        document.getElementById('editForm').target = 'uploadTargetAdd';
    }
}
function getShowDetail() {

   document.getElementById("idSubjects").innerHTML="Expand Sample Format for .xls file and instructions"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSubjectEmployeeList11").style.display='none';
   if(valShow==1) {
     document.getElementById("showSubjectEmployeeList11").style.display='';
     document.getElementById("idSubjects").innerHTML="Collapse Sample Format for .xls file and instructions";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=0;
   }
   else {
     valShow=1;  
   }
}




</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeInfoUpload/employeeInfoUploadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: listEmployeeInfoUpload.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 11/26/09   Time: 1:01p
//Created in $/LeapCC/Interface
//added file related to 'employee export/import' module.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:39a
//Created in $/LeapCC/Interface
//new file for student roll no. uploading
//
//
?>
