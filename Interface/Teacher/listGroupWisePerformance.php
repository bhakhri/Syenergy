<?php
//used for showing subject wise performance report
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupWisePerformanceReport');
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
<title><?php echo SITE_NAME;?>: Display Group wise performance </title>
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
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS;?>");
        document.getElementById('classId').focus();
        return false;
    }
    if(document.getElementById('subjectId').value==''){
        messageBox("<?php echo SELECT_SUBJECT;?>");
        document.getElementById('subjectId').focus();
        return false;
    }
    if(document.getElementById('groupId').value==''){
        messageBox("<?php echo SELECT_GROUP;?>");
        document.getElementById('groupId').focus();
        return false;
    }
   /*
   //checking of condition types and range
   if(document.getElementById('conditionTypeId').value>2){
       if(trim(document.getElementById('conditionTypeRange').value)==''){
           messageBox("<?php echo EMPTY_CONDITION_RANGE;?>")
           document.getElementById('conditionTypeRange').focus();
           return false;
       }
       if(!isNumeric(trim(document.getElementById('conditionTypeRange').value))){
          messageBox("<?php echo INVALID_CONDITION_RANGE;?>")
          document.getElementById('conditionTypeRange').focus();
          return false;
       }
   }
   */
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


    var ele=document.getElementById('groupId');
    var l=ele.length;
    var groupIds="";
    var groupNames='';
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(groupIds!=''){
                groupIds +=',';
                groupNames +=',';
             }
             groupIds +=ele.options[ i ].value;
             groupNames +=ele.options[ i ].text;
         }
    }

    var ele1=document.getElementById('testTypeCategory');
    var l1=ele1.length;
    var testTypeCategoryIds="";
    for(var i=0 ; i < l1 ;i++){
         if(ele1.options[ i ].selected){
             if(testTypeCategoryIds!=''){
                testTypeCategoryIds +=',';
             }
             testTypeCategoryIds +=ele1.options[ i ].value;
         }
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupWiseMarksDistribution.php';
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {
                 testTypeCategoryIds : testTypeCategoryIds,
                 classId             : document.getElementById('classId').value,
                 subjectId           : document.getElementById('subjectId').value,
                 groupIds            : groupIds,
                 groupNames          : groupNames
                 /*,conditionType       : document.getElementById('conditionTypeId').value,
                 conditionTypeRange  : trim(document.getElementById('conditionTypeRange').value)*/
        },
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
        hideWaitDialog(true);
        if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
            showTestMarksDistributionBarChartResults();
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

function changeCondition(value){
    return false;
    document.getElementById('conditionTypeRange').value='';
    if(value >2){
       document.getElementById('conditionTypeRange').disabled=false;
    }
    else{
        document.getElementById('conditionTypeRange').disabled=true;
    }
}

function showTestMarksDistributionBarChartResults() {
    var filterText=getFilterName();
    var condition='';
    /*
    var conditionValue=document.getElementById('conditionTypeId').value;
    var conditionRange=trim(document.getElementById('conditionTypeRange').value);
    if(conditionValue==1){
        condition ="Group {title} got {value} as maximum marks in {series}";
    }
    else if(conditionValue==2){
        condition ="Group {title} got {value} as average marks in {series}";
    }
    else if(conditionValue==3){
        condition ="Group {title} got {value} percentage for percentage greater than or equal to "+conditionRange+" in {series}";
    }
    else if(conditionValue==4){
        condition ="Group {title} got {value} percentage for percentage less than "+conditionRange+" in {series}";
    }
    else{
        messageBox("Invalid input data");
        document.getElementById('conditionTypeId').focus();
        return false;
    }
    */
    condition ="Group {title} got {value} as average marks in {series}";
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "950", "420", "5", "#FFFFFF");
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    //so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Marks Range ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Group---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Group wise performance : </text><text_size>18</text_size></label><label id='4'><x>360</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><balloon_text><![CDATA["+condition+"]]></balloon_text></column><legend><enabled></enabled><x>780</x><y>70</y><width>120</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image></settings>");
    //so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting3.xml"));
    so.addVariable("additional_chart_settings", "<settings><labels><label lid='0'><x>45</x><y>25</y><rotate /><width /><align /><text_color /><text_size>14</text_size><text></text></label><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Average Percentage ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Groups---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Group wise performance : </text><text_size>18</text_size></label><label id='4'><x>355</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><type>stacked</type><data_labels><![CDATA[{value}]]></data_labels><balloon_text><![CDATA[Average percentage for {series} in  {title} : {value}</b>]]></balloon_text></column><legend><enabled></enabled><x>780</x><y>70</y><width>120</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image><grid><category><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length></category><value><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length><approx_count></approx_count><fill_color></fill_color><fill_alpha></fill_alpha></value></grid></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml?t="+x));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/groupPerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}




function getFilterName(){
    var filterText='';
    /*
    var conditionValue=document.getElementById('conditionTypeId').value;
    var conditionName=document.getElementById('conditionTypeId').options[document.getElementById('conditionTypeId').selectedIndex].text;
    var conditionRange=trim(document.getElementById('conditionTypeRange').value);
    if(conditionValue>2){
        var range=conditionRange;
    }
    else{
        var range='';
    }
    */
    var cAuthority   = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    //var categoryName = document.getElementById('testTypeCategory').options[document.getElementById('testTypeCategory').selectedIndex].text;
    var className = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var subjectCode  = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    //return filterText='Conducting Authority : '+cAuthority+' Class : '+className+' Subject : '+subjectCode+' Condition : '+conditionName+' '+range;
    return filterText='Conducting Authority : '+cAuthority+' Class : '+className+' Subject : '+subjectCode;
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
    ele.options.length=0;
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';

    //to make it show "Click to show...."
    totalSelected('testTypeCategory','d3');
    closeTargetDiv('d1','containerDiv1');

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

function populateSubjects(classId){
    document.getElementById('subjectId').options.length=1;
    document.getElementById('groupId').options.length=0;

    //to make it show "Click to show...."
    totalSelected('groupId','d33');
    closeTargetDiv('d11','containerDiv11');

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';

    if(classId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+trim(transport.responseText)+')');
                    for(var c=0;c<j.length;c++){
                      if(j[c].hasMarks==1) {
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.searchForm.subjectId.options.add(objOption);
                      }
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function groupPopulate(value) {
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   document.getElementById('groupId').options.length=0;

    //to make it show "Click to show...."
    totalSelected('groupId','d33');
    closeTargetDiv('d11','containerDiv11');

   if(document.getElementById('subjectId').value==""){
       return false;
   }
   if(document.getElementById('classId').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subjectId').value,
                 classId  : document.getElementById('classId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.groupId.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function washoutData(){
    document.getElementById('saveDiv').style.display='none';
    document.getElementById('resultsDiv1').innerHTML='';
}

function showData(classId,groupId,subjectId,testTypeCategoryId){

    var cAuthority     = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    var subjectCode    = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    /*
    var conditionValue = document.getElementById('conditionTypeId').value;
    var conditionRange = trim(document.getElementById('conditionTypeRange').value);*/

    //var path='<?php echo UI_HTTP_PATH;?>/Teacher/groupWisePeformanceDetailPrint.php?cAuthority='+cAuthority+'&subjectCode='+subjectCode+'&conditionValue='+conditionValue+'&conditionRange='+conditionRange+'&groupId='+groupId+'&testTypeCategoryId='+testTypeCategoryId+'&subjectId='+subjectId+'&classId='+classId;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/groupWisePeformanceDetailPrint.php?cAuthority='+cAuthority+'&subjectCode='+subjectCode+'&groupId='+groupId+'&testTypeCategoryId='+testTypeCategoryId+'&subjectId='+subjectId+'&classId='+classId;
    try{
     window.open(path,"GroupWisePerformanceDetailReport","status=1,menubar=1,scrollbars=1, width=800, height=500, top=100,left=50");
    }
    catch(e){}
}

/* function to print Test Marks Distribution report*/
function printReport() {

    //check input data
    if(!checkInputData()){
        return false;
    }

    var ele=document.getElementById('groupId');
    var l=ele.length;
    var groupIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(groupIds!=''){
                groupIds +=',';
             }
             groupIds +=ele.options[ i ].value;
         }
    }

    var ele1=document.getElementById('testTypeCategory');
    var l1=ele1.length;
    var testTypeCategoryIds="";
    for(var i=0 ; i < l1 ;i++){
         if(ele1.options[ i ].selected){
             if(testTypeCategoryIds!=''){
                testTypeCategoryIds +=',';
             }
             testTypeCategoryIds +=ele1.options[ i ].value;
         }
    }

    var cAuthority   = document.getElementById('condunctingAuthority').options[document.getElementById('condunctingAuthority').selectedIndex].text;
    var subjectCode  = document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;

    var qstr='classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subjectId').value+'&testTypeCategoryIds='+testTypeCategoryIds;
    /*
    qstr +='&groupIds='+groupIds+'&conditionType='+document.getElementById('conditionTypeId').value;
    qstr +='&conditionTypeRange='+trim(document.getElementById('conditionTypeRange').value);
    */
    qstr +='&groupIds='+groupIds;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/groupWisePerformanceReportPrint.php?'+qstr+'&cAuthority='+cAuthority+'&subjectCode='+subjectCode+'&className='+className;
    hideUrlData(path,true);
}


/* function to export Test Marks Distribution report to a CSV*/
function printCSV() {

    //check input data
    if(!checkInputData()){
        return false;
    }

    var ele=document.getElementById('groupId');
    var l=ele.length;
    var groupIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(groupIds!=''){
                groupIds +=',';
             }
             groupIds +=ele.options[ i ].value;
         }
    }

    var ele1=document.getElementById('testTypeCategory');
    var l1=ele1.length;
    var testTypeCategoryIds="";
    for(var i=0 ; i < l1 ;i++){
         if(ele1.options[ i ].selected){
             if(testTypeCategoryIds!=''){
                testTypeCategoryIds +=',';
             }
             testTypeCategoryIds +=ele1.options[ i ].value;
         }
    }

    var qstr='classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subjectId').value+'&testTypeCategoryIds='+testTypeCategoryIds;
    /*
    qstr +='&groupIds='+groupIds+'&conditionType='+document.getElementById('conditionTypeId').value;
    qstr +='&conditionTypeRange='+trim(document.getElementById('conditionTypeRange').value);
    */
    qstr +='&groupIds='+groupIds;
    window.location='groupWisePerformanceCSV.php?'+qstr;
}

function cleanUpData(){
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';
}

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='items';
window.onload=function(){
    makeDDHide('testTypeCategory','d2','d3');
    makeDDHide('groupId','d22','d33');
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
  flashMovie.exportImage('image.php?name=Group Wise Performance Report');
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
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listGroupWisePerformanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>