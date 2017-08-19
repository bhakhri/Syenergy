<?php
//used for showing subject wise performance report
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWisePerformanceReport');
define('ACCESS','view');
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 2){
	  UtilityManager::ifTeacherNotLoggedIn(); //for teachers
}
else{
	UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test wise performance report </title>
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
    if(trim(document.getElementById('testMarksRange').value)==''){
        messageBox("<?php echo ENTER_MARKS_RANGE;?>");
        document.getElementById('testMarksRange').focus();
        return false;
    }
    return true;
}
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getGraphData(){
    //check input data
    if(!checkInputData()){
        return false;
    }

    var testRange=trim(document.getElementById('testMarksRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    var dupString='';
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isNumeric(trim(tRange[k]))){
               messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
      if(dupString!=''){
          dupString +=',';
      }
      dupString +=tRange[0]+""+tRange[1];
    }
    if(!isDuplicate(dupString)){
        document.getElementById('testMarksRange').focus();
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
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTestMarksDistribution.php';
    var chartType=document.getElementById('chartTypeId').value;
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {
                 testIds         : testIds ,
                 testMarksRange  : testRange,
                 chartTypeId     : chartType
        },
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
        hideWaitDialog(true);
        if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
            showWaitDialog(true);

            if(chartType==1){
              //histogram chart
              showTestMarksDistributionBarChartResults();
            }
            else if(chartType==2){
             //3D stacked column chart
             showTestMarksDistributionColumnChartResults();
            }
            else if(chartType==3){
              //3D stacked row chart
              showTestMarksDistributionRowChartResults();
            }
            else{
                messageBox("<?php echo INVALID_CHART_TYPE; ?>");
                document.getElementById('chartTypeId').focus();
                return false;
            }

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

function showTestMarksDistributionBarChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "420", "5", "#FFFFFF");
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Student Count ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Marks Range( in percentage )---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Test wise performance : </text><text_size>18</text_size></label><label id='4'><x>350</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><balloon_text><![CDATA[{value} Students in ({series}) range for test: {title}</b>]]></balloon_text></column><legend><enabled></enabled><x>780</x><y>70</y><width>120</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting3.xml"));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/subjectPerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}

function showTestMarksDistributionColumnChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "420", "5", "#FFFFFF");
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label lid='0'><x>45</x><y>25</y><rotate /><width /><align /><text_color /><text_size>14</text_size><text></text></label><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Student Count ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Marks Range( in percentage )---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Test wise performance : </text><text_size>18</text_size></label><label id='4'><x>350</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><type>stacked</type><data_labels><![CDATA[{value}]]></data_labels><balloon_text><![CDATA[{value} Students in ({series}) range for test: {title}</b>]]></balloon_text></column><legend><enabled></enabled><x>780</x><y>70</y><width>120</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image><grid><category><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length></category><value><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length><approx_count></approx_count><fill_color></fill_color><fill_alpha></fill_alpha></value></grid></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/subjectPerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}

function showTestMarksDistributionRowChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "700", "5", "#FFFFFF");
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label lid='0'><x>45</x><y>25</y><rotate /><width /><align /><text_color /><text_size>14</text_size><text></text></label><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Marks Range( in percentage )---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>600</y><text>Student Count ---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Test wise performance : </text><text_size>18</text_size></label><label id='4'><x>350</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><balloon_text><![CDATA[{value} Students in ({series}) range for test: {title}</b>]]></balloon_text></column><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image><grid><category><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length></category><value><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length><approx_count></approx_count><fill_color></fill_color><fill_alpha></fill_alpha></value></grid></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettingsRowWise.xml"));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/subjectPerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}


function getFilterName(){
    var filterText='';
    var cAuthority   = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    var categoryName = document.getElementById('testTypeCategory').options[document.getElementById('testTypeCategory').selectedIndex].text;
    var subjectCode  = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    return filterText='Conducting Authority : '+cAuthority+' Test Type Category : '+categoryName+' Subject : '+subjectCode;
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
						 //var objOption = new Option(j[c].testAbbr+"-"+j[c].testIndex+'(Marks:'+j[c].maxMarks+')',j[c].testId);
		str = j[c].testAbbr+"-"+j[c].testIndex+" ("+j[c].groupShort+")";
                         var objOption = new Option(str,j[c].testId);
                         ele.options.add(objOption);
					 }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function showData(interval,testId){

    var cAuthority   = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    var categoryName = document.getElementById('testTypeCategory').options[document.getElementById('testTypeCategory').selectedIndex].text;
    var subjectCode  = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;

    var path='<?php echo UI_HTTP_PATH;?>/Teacher/testMarksDistributionDetail.php?cAuthority='+cAuthority+'&categoryName='+categoryName+'&subjectCode='+subjectCode+'&interval='+interval+'&testId='+testId;
    window.open(path,"TestMarksDistributionDetailReport","status=1,menubar=1,scrollbars=1, width=800, height=500, top=100,left=50");
}

/* function to print Test Marks Distribution report*/
function printReport() {

    //check input data
    if(!checkInputData()){
        return false;
    }

    var testRange=trim(document.getElementById('testMarksRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    var dupString='';
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isNumeric(trim(tRange[k]))){
               messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
        if(dupString!=''){
            dupString +=',';
        }
        dupString +=tRange[0]+""+tRange[1];
    }

    if(!isDuplicate(dupString)){
        document.getElementById('testMarksRange').focus();
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

    var qstr='testIds='+testIds+'&testMarksRange='+testRange;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/testMarksDistributionPrint.php?'+qstr+'&cAuthority='+cAuthority+'&categoryName='+categoryName+'&subjectCode='+subjectCode;
    hideUrlData(path,true);
}


/* function to export Test Marks Distribution report to a CSV*/
function printCSV() {

    //check input data
    if(!checkInputData()){
        return false;
    }

    var testRange=trim(document.getElementById('testMarksRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    var dupString='';
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isNumeric(trim(tRange[k]))){
               messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
        if(dupString!=''){
            dupString +=',';
        }
        dupString +=tRange[0]+""+tRange[1];
    }
    if(!isDuplicate(dupString)){
        document.getElementById('testMarksRange').focus();
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

    var qstr='testIds='+testIds+'&testMarksRange='+testRange;
    window.location='testMarksDistributionReportCSV.php?'+qstr;
}

function isDuplicate(arr){
    var dupArray=arr.split(',');
    var len=dupArray.length;
    for(var i=0;i<len;i++){
        for(var j=i+1;j<len;j++){
            if(dupArray[i]==dupArray[j]){
                messageBox("Duplicate range is not allowed");
                return false;
            }
        }
    }
    return true;
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
  flashMovie.exportImage('image.php?name=Test Wise Performance Report');
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
  alert(message);
}
/**************************FUNCTIONS NEEDED FOR EXPOTING GRAPH AS AN IMAGE*******************************/

</script>

</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listSubjectPerformanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
