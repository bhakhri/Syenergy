<?php
//used for showing subject wise performance report
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestWisePerformanceComparisonReport');
define('ACCESS','view');
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	UtilityManager::ifTeacherNotLoggedIn();
}
else{
	UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Test wise performance comparison </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script type="text/javascript" src="<?php echo JS_PATH;?>/swfobject.js"></script>
<script language="javascript">

function checkInputData(){
    if(document.getElementById('condunctingAuthority').value==''){
        messageBox("<?php echo SELECT_CONDUCTING_AUTHORITY;?>");
        document.getElementById('condunctingAuthority').focus();
        return false;
    }
    if(document.getElementById('testTypeCategory').value==''){
        messageBox("<?php echo SELECT_TESTTYPE_CATEGORY;?>");
        document.getElementById('testTypeCategory').focus();
        return false;
    }
    if(document.getElementById('subjectId').value==''){
        messageBox("<?php echo SELECT_SUBJECT;?>");
        document.getElementById('subjectId').focus();
        return false;
    }
    if(document.getElementById('testId').value==''){
        messageBox("<?php echo SELECT_TEST;?>");
        document.getElementById('testId').focus();
        return false;
    }
    if(trim(document.getElementById('studentRollNo').value)==''){
        messageBox("<?php echo ENTER_STUDENT_ROLL_NOS;?>");
        document.getElementById('studentRollNo').focus();
        return false;
    }
    return true;
}
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getGraphData(){
    //check input data
    if(!checkInputData()){
        return false;
    }


    var ele=document.getElementById('testId');
    var l=ele.length;
    var testIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(testIds!=''){
                testIds +=',';
             }
             testIds +=ele.options[ i ].value;
         }
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTestMarksComparisonDistribution.php';
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {
                 testIds         : testIds ,
                 studentRollNos  : trim(document.getElementById('studentRollNo').value)
        },
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
        hideWaitDialog(true);
        if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
            showTestMarksDistributionComparisonChartResults();
            hideWaitDialog(true);
            document.getElementById('saveDiv').style.display='';
        }
        else{
            document.getElementById('resultsDiv1').innerHTML='No Data Found';
            document.getElementById('saveDiv').style.display='none';
        }
    },
    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function showTestMarksDistributionComparisonChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "950", "420", "5", "#FF00FF");
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Marks Range ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>345</y><text>Tests---></text><text_size>10</text_size></label><label id='3'><x>100</x><y>25</y><text>Test wise performance comparison : </text><text_size>18</text_size></label><label id='4'><x>393</x><y>29</y><text>"+getFilterName()+"</text><text_size>12</text_size></label></labels><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image></settings>");

    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/lineChartSettings.xml?t="+x));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/testMarksComparisonData.xml?t="+x));
    so.write("resultsDiv1");
}

function getFilterName(){
    var filterText='';
    var cAuthority   = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    var categoryName = document.getElementById('testTypeCategory').options[document.getElementById('testTypeCategory').selectedIndex].text;
    var subjectCode  = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    //var rollNos      = trim(document.getElementById('studentRollNo').value);
    return filterText='Conducting Authority : '+cAuthority+' Test Type Category : '+categoryName+' Subject : '+subjectCode;
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function  populateTestTypeCategory(value){
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestTypeCategory.php';
    var ele=document.getElementById('testTypeCategory');
    ele.options.length=1;
    document.getElementById('subjectId').options.length=1;
    document.getElementById('testId').options.length=0;
    //to make it show "Click to show...."
    totalSelected('testId','d3');
    closeTargetDiv('d1','containerDiv1');
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 condunctingAuthorityId:(value)
                },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('('+trim(transport.responseText)+')');
                     var len=j.length;
                     for(var c=0;c<len;c++){
                         var objOption = new Option(j[c].testTypeName,j[c].testTypeCategoryId);
                         ele.options.add(objOption);
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//to populate subjects based on choosen class
function populateSubject(condunctingAuthority,testTypeCategory){
    var ele=document.getElementById('subjectId');
    ele.options.length=1;
    document.getElementById('testId').options.length=0;
    //to make it show "Click to show...."
    totalSelected('testId','d3');
    closeTargetDiv('d1','containerDiv1');
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestSubjects.php';

    if(condunctingAuthority=='' || testTypeCategory==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 condunctingAuthorityId : condunctingAuthority,
                 testTypeCategoryId     : testTypeCategory
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subjectId.options.add(objOption);
                   }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate test drop down
// Author : Dipanajan Bhattacharjee
// Created on : (26.10.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateTest(conductingAuthority,testTypeCategory,subject) {

  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestNames.php';

  var ele=document.getElementById('testId');
  ele.options.length=0;
  //to make it show "Click to show...."
  totalSelected('testId','d3');
  closeTargetDiv('d1','containerDiv1');
  document.getElementById('resultsDiv1').innerHTML='';
  document.getElementById('saveDiv').style.display='none';

   if(document.conductingAuthority=='' || testTypeCategory=='' || subject==''){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 conductingAuthorityId : conductingAuthority,
                 testTypeCategoryId    : testTypeCategory,
                 subjectId             : subject
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
				    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
						 var objOption = new Option(j[c].testAbbr+"-"+j[c].testIndex+'(Marks:'+j[c].maxMarks+')',j[c].testId);
                         ele.options.add(objOption);
					 }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}



/* function to print Test Marks Distribution report*/
function printReport() {

    //check input data
    if(!checkInputData()){
        return false;
    }


    var ele=document.getElementById('testId');
    var l=ele.length;
    var testIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(testIds!=''){
                testIds +=',';
             }
             testIds +=ele.options[ i ].value;
         }
    }

    var cAuthority   = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    var categoryName = document.getElementById('testTypeCategory').options[document.getElementById('testTypeCategory').selectedIndex].text;
    var subjectCode  = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;

    var qstr='testIds='+testIds+'&studentRollNos='+trim(document.getElementById('studentRollNo').value);
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/testMarksComparisonPrint.php?'+qstr+'&cAuthority='+cAuthority+'&categoryName='+categoryName+'&subjectCode='+subjectCode;
   hideUrlData(path,true);
}


/* function to export Test Marks Distribution report to a CSV*/
function printCSV() {

    //check input data
    if(!checkInputData()){
        return false;
    }

    var ele=document.getElementById('testId');
    var l=ele.length;
    var testIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(testIds!=''){
                testIds +=',';
             }
             testIds +=ele.options[ i ].value;
         }
    }

    var qstr='testIds='+testIds+'&studentRollNos='+trim(document.getElementById('studentRollNo').value);
    window.location='testMarksComparisonReportCSV.php?'+qstr;
}

function cleanUpData(){
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';
}

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='items';
window.onload=function(){
    makeDDHide('testId','d2','d3');
}


/**************************FUNCTIONS NEEDED FOR EXPOTING GRAPH AS AN IMAGE***************************************/
var flashMovie;

function setData() {
  var data = document.getElementById("data").value;
  flashMovie.setData(data);
}

function appendData() {
  var data = document.getElementById("data").value;
  flashMovie.appendData(data, 0);
}

function setSettings() {
  var settings = document.getElementById("settings").value;
  flashMovie.setSettings(settings, true);
}

function rebuild() {
  flashMovie.rebuild();
}

function reloadData() {
  flashMovie.reloadData();
}


function reloadSettings() {
  flashMovie.reloadSettings();
}

function reloadAll() {
  flashMovie.reloadAll();
}

function setParam() {
  var param = document.getElementById("param").value;
  var value = document.getElementById("value").value;
  flashMovie.setParam(param, value);
}

function getParam() {
  var param = document.getElementById("getparam").value;
  flashMovie.getParam(param);
}

function getData() {
  flashMovie.getData();
}

function getSettings() {
  flashMovie.getSettings();
}

function exportImage() {
  form = document.searchForm;
  flashMovie.exportImage('image.php?name=Test Wise Performance Comparison Report');
}

function print(){
  flashMovie.print();
}

function printAsBitmap(){
  flashMovie.printAsBitmap();
}

function amChartInited(chart_id){
  flashMovie = document.getElementById(chart_id);
}

function amProcessCompleted(chart_id, process_name){
  document.getElementById("processcompleted").value = process_name;
}

function amReturnData(chart_id, data){
  document.getElementById("data").value = unescape(data);
}

function amReturnParam(chart_id, param){
  document.getElementById("returnedparam").value = unescape(param);
}

function amReturnSettings(chart_id, settings){
  document.getElementById("settings").value = unescape(settings);
}

function amClickedOnBullet(chart_id, graph_index, value, series, url, description){
  document.getElementById("column_clicked").value = value;
}

function amRolledOverBullet(chart_id, graph_index, value, series, url, description){
  document.getElementById("column_hover").value = value;
}

function amReturnImageData(chart_id, data){
  // your own functions here
}

function amError(chart_id, message){
  //alert("<?php echo NO_DATA_FOUND;?>");
  alert(message);
}
/**************************FUNCTIONS NEEDED FOR EXPOTING GRAPH AS AN IMAGE*******************************/

</script>

</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listSubjectPerformanceComparisonContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>