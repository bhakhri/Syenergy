<?php
//used for showing subject wise performance report
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWisePerformanceComparisonReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if ($roleId == '' or empty($roleId)) {
	redirectBrowser(UI_HTTP_PATH.'/sessionError.php');
}
if($roleId==1){

  UtilityManager::ifNotLoggedIn(); //for admin
}
else if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(); //for teachers
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Wise Performance Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script type="text/javascript" src="<?php echo JS_PATH;?>/swfobject.js"></script>
<script language="javascript">

var dtArray=new Array();
var queryString='';

function checkInputData(){

    queryString = '';
    if(document.getElementById('timeTableId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE;?>");
        document.getElementById('timeTableId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS;?>");
        document.getElementById('classId').focus();
        return false;
    }
    if(document.getElementById('groupId').value==''){
        messageBox("<?php echo SELECT_GROUP;?>");
        document.getElementById('groupId').focus();
        return false;
    }
    if(document.getElementById('subjectType').value==''){
        messageBox("<?php echo SELECT_SUBJECT_TYPE;?>");
        document.getElementById('subjectType').focus();
        return false;
    }
    if(document.getElementById('subjectId').value==''){
        messageBox("<?php echo SELECT_SUBJECT;?>");
        //document.getElementById('subjectId').focus();
        popupMultiSelectDiv('subjectId','d1','containerDiv','d3');
        return false;
    }
    if(trim(document.getElementById('testMarksRange').value)==''){
        messageBox("<?php echo ENTER_MARKS_RANGE;?>");
        document.getElementById('testMarksRange').focus();
        return false;
    }
    return true;
}



function checkDuplicateRanke(value) {
    var i= dtArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(dtArray[k]==value){
        fl=0;
        break;
      }
    }
    if(fl==1){
      dtArray.push(value);
    }
    return fl;
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

    dtArray.splice(0,dtArray.length); //empty the array

    queryString = '';
    //check input data
    if(!checkInputData()){
        return false;
    }
    closeTargetDiv('d1','containerDiv');
    var testRange=trim(document.getElementById('testMarksRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isDecimal(trim(tRange[k]))){
               messageBox("<?php echo ENTER_DECIMAL_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
        if(checkDuplicateRanke(parseInt(tRange[0])+'-'+parseInt(tRange[1]))==0){
           messageBox("<?php echo "Duplicate range should not be accepted"; ?>");
           document.getElementById('testMarksRange').focus();
           return false;
        }
    }
    var ele=document.getElementById('subjectId');
    var l=ele.length;
    var subjectIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(subjectIds!=''){
                subjectIds +=',';
             }
             subjectIds +=ele.options[ i ].value;
         }
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSubjectMarksDistribution.php';
    var chartType=document.getElementById('chartTypeId').value;
    var examType=0;
    if(document.searchForm.examType[0].checked==true){
        examType=1;
    }
    else if(document.searchForm.examType[1].checked==true){
        examType=2;
    }
    else{
        examType=3;
    }

    var graceMks = document.searchForm.showGraceMarks[0].checked==true?1:0;

    var timeTableName   = document.getElementById('timeTableId').options[document.getElementById('timeTableId').selectedIndex].text;
    var subjectTypeName = document.getElementById('subjectType').options[document.getElementById('subjectType').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var groupName  = document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;

    new Ajax.Request(url,
    {
        method:'post',
        parameters: {
                 timeTableId     : document.getElementById('timeTableId').value,
                 subjectType     : document.getElementById('subjectType').value,
                 subjectIds      : subjectIds ,
                 testMarksRange  : testRange,
                 classId         : document.getElementById('classId').value,
                 groupId         : document.getElementById('groupId').value,
                 chartTypeId     : chartType,
                 rangeType       : document.getElementById('rangeType').value,
                 showGraceMarks  : document.searchForm.showGraceMarks[0].checked==true?1:0,
                 examType        : examType
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
    var perString='';
    if(document.getElementById('rangeType').value==1){
        perString='( in percentage )';
    }
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>No of Students</text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Marks Variation"+perString+"</text><text_size>10</text_size></label><label id='3'><x>140</x><y>10</y><rotate>false</rotate><text>Subject wise performance : </text><text_size>18</text_size></label><label id='4'><x>370</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>15</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><balloon_text><![CDATA[{value} Students in ({series}) range for subject: {title}</b>]]></balloon_text></column><legend><enabled></enabled><x>50</x><y>380</y><width>1200</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting3.xml"));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/subjectWisePerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}

function showTestMarksDistributionColumnChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "420", "5", "#FFFFFF");
    var perString='';
    if(document.getElementById('rangeType').value==1){
        perString='( in percentage )';
    }
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label lid='0'><x>45</x><y>25</y><rotate /><width /><align /><text_color /><text_size>14</text_size><text></text></label><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>No of Students</text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Marks Variation"+perString+"</text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Subject wise performance : </text><text_size>18</text_size></label><label id='4'><x>370</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><type>stacked</type><balloon_text><![CDATA[{value} Students in ({series}) range for subject: {title}</b>]]></balloon_text><data_labels><![CDATA[{value}]]></data_labels><data_labels_text_color>#000000</data_labels_text_color></column><legend><enabled></enabled><x>50</x><y>380</y><width>1200</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image><grid><category><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length></category><value><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length><approx_count></approx_count><fill_color></fill_color><fill_alpha></fill_alpha></value></grid></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/subjectWisePerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}

function showTestMarksDistributionRowChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "800", "5", "#FFFFFF");
    var perString='';
    if(document.getElementById('rangeType').value==1){
        perString='( in percentage )';
    }
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart
    so.addVariable("additional_chart_settings", "<settings><labels><label lid='0'><x>45</x><y>25</y><rotate /><width /><align /><text_color /><text_size>14</text_size><text></text></label><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Marks Variation"+perString+"</text><text_size>10</text_size></label><label id='2'><x>50</x><y>700</y><text>No of Students</text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Subject wise performance : </text><text_size>18</text_size></label><label id='4'><x>370</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>22</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><balloon_text><![CDATA[{value} Students in ({series}) range for subject: {title}</b>]]></balloon_text></column><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image><grid><category><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length></category><value><color></color><alpha>10</alpha><dashed>true</dashed><dash_length></dash_length><approx_count></approx_count><fill_color></fill_color><fill_alpha></fill_alpha></value></grid></settings>");
    so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettingsRowWise.xml"));
    so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/subjectWisePerformanceStackData.xml?t="+x));
    so.write("resultsDiv1");
}


function getFilterName(){
    var filterText='';
    var timeTableName   = document.getElementById('timeTableId').options[document.getElementById('timeTableId').selectedIndex].text;
    var subjectTypeName = document.getElementById('subjectType').options[document.getElementById('subjectType').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var groupName  = document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    if(document.searchForm.examType[0].checked==true){
        examType="Exam Type : All";
    }
    else if(document.searchForm.examType[1].checked==true){
        examType="Exam Type : Internal";
    }
    else{
        examType="Exam Type : External";
    }
    return filterText='Time table : '+timeTableName+' Subject Type : '+subjectTypeName+' Class : '+className+' Group : '+groupName+' '+examType;
}



//to populate subjects based on choosen class
function populateClasses(timeTabelId){
    cleanUpData(1);
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestWiseClasses.php';

    if(timeTabelId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId   : timeTabelId,
                 callingModule : 2
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    for(var c=0;c<len;c++){
                        var objOption = new Option(j[c].className,j[c].classId);
                        document.searchForm.classId.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//to populate subjects based on choosen class
function populateSubjectTypes(timeTabelId,classId){
    cleanUpData(2);
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestSubjectTypes.php';

    if(timeTabelId=='' || classId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId   : timeTabelId,
                 classId       : classId,
                 callingModule : 2
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    if(len>0){
                      var objOption = new Option('All',0);
                      document.searchForm.subjectType.options.add(objOption);
                    }
                    for(var c=0;c<len;c++){
                        var objOption = new Option(j[c].subjectTypeName,j[c].subjectTypeId);
                        document.searchForm.subjectType.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


function populateGroups(timeTabelId,classId){
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';
    document.searchForm.groupId.options.length=1;

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAllGroupPopulate.php';

    if(timeTabelId=='' || classId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId   : timeTabelId,
                 classId            : classId,
                 callingModule      : 2
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
                    if(len>0){
                        var objOption = new Option("All",-1);
                        document.searchForm.groupId.options.add(objOption);
                    }
                    for(var c=0;c<len;c++){
                        var objOption = new Option(j[c].groupName,j[c].groupId);
                        document.searchForm.groupId.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//var percentageString = "1-4,5-9,10-14,15-19,20-24,25-29,30-34,35-39,40-44,45-49,50-54,55-59,60-64,65-69,70-74,75-79,80-84,85-89,90-94,95-100";
var percentageString = "1-9,10-19,20-29,30-39,40-49,50-59,60-69,70-79,80-89,90-100";
var theoryString     = "0-12,13-15,16-24,25-30,31-35,36-40";
var practicalString  = "0-9,10-11,12-15,16-18,19-22,23-27,28-30";
var trainingString   = "0-9,10-11,12-15,16-18,19-22,23-27,28-30";

//to populate subjects based on choosen class
function populateSubject(timeTabelId,subjectTypeId,classId){
    cleanUpData(3);
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestWiseSubjects.php';
    var tRange=document.getElementById('testMarksRange');
    var rangeType=document.getElementById('rangeType');
    var rtDiv=document.getElementById('rtDiv');
    if(subjectTypeId==0){
       rangeType.value=1;
       rangeType.disabled=true;
       tRange.value=percentageString;
       rtDiv.innerHTML='in&nbsp;&nbsp;%';
    }
    else {
       rangeType.disabled=false;
       rangeType.value=2;
       tRange.value=theoryString;
       rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
    }
    /*
    else if(subjectTypeId==1){
       rangeType.disabled=false;
       rangeType.value=2;
       tRange.value=theoryString;
       rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    else if(subjectTypeId==2){
       rangeType.disabled=false;
       rangeType.value=2;
       tRange.value=practicalString;
       rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    else if(subjectTypeId==3){
       rangeType.disabled=false;
       rangeType.value=2;
       tRange.value=trainingString;
       rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    */
    
    if(timeTabelId=='' || subjectTypeId=='' || classId ==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId   : timeTabelId,
                 subjectTypeId : subjectTypeId,
                 classId       : classId,
                 callingModule : 2
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


function changeCriteriaString(subjectType,criteria){
   var tRange=document.getElementById('testMarksRange');
   var rtDiv=document.getElementById('rtDiv');
   if(subjectType==1){
       if(criteria==1){
           tRange.value=percentageString;
           rtDiv.innerHTML='in&nbsp;&nbsp;%';
       }
       else{
           tRange.value=theoryString;
           rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       }
   }
   else if(subjectType==2){
       if(criteria==1){
           tRange.value=percentageString;
           rtDiv.innerHTML='in&nbsp;&nbsp;%';
       }
       else{
           tRange.value=practicalString;
           rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       }
   }
   else if(subjectType==3){
       if(criteria==1){
           tRange.value=percentageString;
           rtDiv.innerHTML='in&nbsp;&nbsp;%';
       }
       else{
           tRange.value=trainingString;
           rtDiv.innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       }
   }
   cleanUpData();
}


function showData(interval,classId,subjectId){
    var timeTableName   = document.getElementById('timeTableId').options[document.getElementById('timeTableId').selectedIndex].text;
    var subjectTypeName = document.getElementById('subjectType').options[document.getElementById('subjectType').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var groupName  = document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    var examType=0;
    if(document.searchForm.examType[0].checked==true){
        examType=1;
    }
    else if(document.searchForm.examType[1].checked==true){
        examType=2;
    }
    else{
        examType=3;
    }

    var showGraceMarks = document.searchForm.showGraceMarks[0].checked==true?1:0;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/subjectMarksDistributionDetail.php?timeTableName='+timeTableName+'&subjectTypeName='+subjectTypeName+'&className='+className+'&interval='+interval+'&classId='+classId+'&subjectId='+subjectId+'&rangeType='+document.getElementById('rangeType').value+'&showGraceMarks='+showGraceMarks+'&examType='+examType+'&groupId='+document.getElementById('groupId').value+'&groupName='+groupName;
    window.open(path,"SubjectMarksDistributionDetailReport","status=1,menubar=1,scrollbars=1, width=800, height=500, top=100,left=50");
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
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isDecimal(trim(tRange[k]))){
               messageBox("<?php echo ENTER_DECIMAL_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
    }
    var ele=document.getElementById('subjectId');
    var l=ele.length;
    var subjectIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(subjectIds!=''){
                subjectIds +=',';
             }
             subjectIds +=ele.options[ i ].value;
         }
    }

    var timeTableName   = document.getElementById('timeTableId').options[document.getElementById('timeTableId').selectedIndex].text;
    var subjectTypeName = document.getElementById('subjectType').options[document.getElementById('subjectType').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var groupName  = document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    var showGraceMarks = document.searchForm.showGraceMarks[0].checked==true?1:0;
    var examType=0;
    if(document.searchForm.examType[0].checked==true){
        examType=1;
    }
    else if(document.searchForm.examType[1].checked==true){
        examType=2;
    }
    else{
        examType=3;
    }

    var qstr='subjectIds='+subjectIds+'&testMarksRange='+testRange+'&timeTableId='+document.getElementById('timeTableId').value+'&subjectType='+document.getElementById('subjectType').value+'&subjectIds='+subjectIds+'&testMarksRange='+testRange+'&classId='+document.getElementById('classId').value+'&rangeType='+document.getElementById('rangeType').value+'&showGraceMarks='+showGraceMarks+'&examType='+examType+'&groupId='+document.getElementById('groupId').value+'&groupName='+groupName;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/subjectWiseMarksDistributionPrint.php?'+qstr+'&timeTableName='+timeTableName+'&subjectTypeName='+subjectTypeName+'&className='+className;
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
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isDecimal(trim(tRange[k]))){
               messageBox("<?php echo ENTER_DECIMAL_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
    }
    var ele=document.getElementById('subjectId');
    var l=ele.length;
    var subjectIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(subjectIds!=''){
                subjectIds +=',';
             }
             subjectIds +=ele.options[ i ].value;
         }
    }

    var timeTableName   = document.getElementById('timeTableId').options[document.getElementById('timeTableId').selectedIndex].text;
    var subjectTypeName = document.getElementById('subjectType').options[document.getElementById('subjectType').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var groupName  = document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    var showGraceMarks = document.searchForm.showGraceMarks[0].checked==true?1:0;
    var examType=0;
    if(document.searchForm.examType[0].checked==true){
        examType=1;
    }
    else if(document.searchForm.examType[1].checked==true){
        examType=2;
    }
    else{
        examType=3;
    }

    var qstr='subjectIds='+subjectIds+'&testMarksRange='+testRange+'&timeTableId='+document.getElementById('timeTableId').value+'&subjectType='+document.getElementById('subjectType').value+'&subjectIds='+subjectIds+'&testMarksRange='+testRange+'&classId='+document.getElementById('classId').value+'&rangeType='+document.getElementById('rangeType').value+'&showGraceMarks='+showGraceMarks+'&examType='+examType+'&groupId='+document.getElementById('groupId').value+'&groupName='+groupName;
    var path='<?php echo UI_HTTP_PATH;?>/Teacher/subjectWiseMarksDistributionReportCSV.php?'+qstr+'&timeTableName='+timeTableName+'&subjectTypeName='+subjectTypeName+'&className='+className;

    window.location=path;

    //var qstr='subjectIds='+subjectIds+'&testMarksRange='+testRange+'&timeTableId='+document.getElementById('timeTableId').value+'&subjectType='+document.getElementById('subjectType').value+'&subjectIds='+subjectIds+'&testMarksRange='+testRange+'&classId='+document.getElementById('classId').value+'&rangeType='+document.getElementById('rangeType').value+'&showGraceMarks='+showGraceMarks+'&examType='+examType+'&groupId='+document.getElementById('groupId').value;
    //window.location='subjectWiseMarksDistributionReportCSV.php?'+qstr;
}

function cleanUpData(mode){
    if(mode==1){
        document.getElementById('subjectId').options.length=0;
        //to make it show "Click to show...."
        totalSelected('subjectId','d3');
        closeTargetDiv('d1','containerDiv');

        document.getElementById('classId').options.length=1;
        document.getElementById('subjectType').options.length=1;
    }
    else if(mode==2){
        document.getElementById('subjectId').options.length=0;
        //to make it show "Click to show...."
        totalSelected('subjectId','d3');
        closeTargetDiv('d1','containerDiv');

        document.getElementById('subjectType').options.length=1;
    }
    else if(mode==3){
        document.getElementById('subjectId').options.length=0;
        //to make it show "Click to show...."
        totalSelected('subjectId','d3');
        closeTargetDiv('d1','containerDiv');
    }
    document.getElementById('resultsDiv1').innerHTML='';
    document.getElementById('saveDiv').style.display='none';
}

var previousGraceMarksValue='';
function graceMarksToggle(value){
    if(value!=previousGraceMarksValue){
     cleanUpData();
     previousGraceMarksValue=value;
    }
}

function vanishData(){
  document.getElementById('resultsDiv1').innerHTML='';
  document.getElementById('saveDiv').style.display='none';
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

     //check input data
    if(!checkInputData()){
        return false;
    }

    var testRange=trim(document.getElementById('testMarksRange').value);
    var tR=testRange.split(',');
    var len1=tR.length;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_MARKS_RANGE;?>");
            document.getElementById('testMarksRange').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isDecimal(trim(tRange[k]))){
               messageBox("<?php echo ENTER_DECIMAL_VALUE_FOR_MARKS_RANGE;?>");
               document.getElementById('testMarksRange').focus();
               return false;
            }
        }
    }
    var ele=document.getElementById('subjectId');
    var l=ele.length;
    var subjectIds="";
    for(var i=0 ; i < l ;i++){
         if(ele.options[ i ].selected){
             if(subjectIds!=''){
                subjectIds +=',';
             }
             subjectIds +=ele.options[ i ].value;
         }
    }

    var timeTableName   = document.getElementById('timeTableId').options[document.getElementById('timeTableId').selectedIndex].text;
    var subjectTypeName = document.getElementById('subjectType').options[document.getElementById('subjectType').selectedIndex].text;
    var className  = document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    var groupName  = document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    var showGraceMarks = document.searchForm.showGraceMarks[0].checked==true?1:0;
    var examType=0;
    if(document.searchForm.examType[0].checked==true){
        examType=1;
    }
    else if(document.searchForm.examType[1].checked==true){
        examType=2;
    }
    else{
        examType=3;
    }

    //form = document.searchForm;
    flashMovie.exportImage('<?php echo UI_HTTP_PATH; ?>/Teacher/image.php?name=Subject Wise Performance Report');
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

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='items';
window.onload=function(){
    makeDDHide('subjectId','d2','d3');
}

</script>

</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listSubjectWisePerformanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>