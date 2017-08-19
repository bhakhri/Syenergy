<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeavesHistoryReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Leaves History Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript" src="<?php echo JS_PATH;?>/swfobject.js"></script>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                   new Array('srNo','#','width="2%"','',false), 
                                   new Array('leaveTypeName','Leave Type','width="10%"','',true) , 
                                   new Array('leaveFromDate','From','width="10%"','align="center"',true) , 
                                   new Array('leaveToDate','To','width="10%"','align="center"',true) , 
                                   new Array('noOfDays','Days','width="5%"','align="right"',true),
                                   new Array('leaveStatus','Status','width="10%"','align="left"',true),
                                   new Array('leaveDetails','Details','width="3%"','align="center"',false)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveReports/ajaxLeavesHistoryList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'leaveTypeName';
sortOrderBy    = 'ASC';


function viewWindow(id) {
    populateValuesForViewing(id,'ViewApplyLeave',315,250);
}


function populateValuesForViewing(id,dv,w,h) {
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetValuesForViewing.php';
         document.getElementById('emplCodeDiv').innerHTML='';
         document.getElementById('emplNameDiv').innerHTML='';
         document.getElementById('emplLeaveTypeDiv').innerHTML='';
         document.getElementById('emplLeaveFromDiv').innerHTML='';
         document.getElementById('emplLeaveToDiv').innerHTML='';
         document.getElementById('emplLeaveReasonDiv').innerHTML='';
         document.getElementById('emplLeaveApplicationDateDiv').innerHTML='';
         document.getElementById('emplLeaveStatusDiv').innerHTML='';
         document.getElementById('firstCommentsDiv').innerHTML='';
         document.getElementById('secondCommentsDiv').innerHTML='';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mappingId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        messageBox("<?php echo EMPLOYEE_LEAVE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                     }
                    else{
                         var j = eval('('+trim(transport.responseText)+')');
                         document.getElementById('emplCodeDiv').innerHTML=j.employeeCode;
                         document.getElementById('emplNameDiv').innerHTML=j.employeeName;
                         document.getElementById('emplLeaveTypeDiv').innerHTML=j.leaveTypeName;
                         document.getElementById('emplLeaveFromDiv').innerHTML=j.leaveFromDate;
                         document.getElementById('emplLeaveToDiv').innerHTML=j.leaveToDate;
                         document.getElementById('emplLeaveReasonDiv').innerHTML=j.reason;
                         document.getElementById('emplLeaveApplicationDateDiv').innerHTML=j.applicateDate;
                         document.getElementById('emplLeaveStatusDiv').innerHTML=j.leaveStatus;
                         document.getElementById('firstCommentsDiv').innerHTML=j.reason1;
                         document.getElementById('secondCommentsDiv').innerHTML=j.reason2;
                         displayWindow(dv,w,h); 
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function inputValidation(){
    if(document.getElementById('employeeDD').value==''){
        messageBox("Select an employee");
        document.getElementById('employeeDD').focus();
        return false;
    }
    return true;
}

function generateReport(){
  
  if(!inputValidation()){
      return false;
  }
  
  if(document.getElementById('reportType').value==1){
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
   document.getElementById('printTrId').style.display='';
  }
  else{
     showLeavesHistoryChartResults(); 
  }
}


function getFilterName(){
    var filterText='Leave Session : '+document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    filterText +=' &nbsp;Employee : '+document.getElementById('employeeDD').options[document.getElementById('employeeDD').selectedIndex].text;
    return  filterText;
}

function showLeavesHistoryChartResults(){
    var url = '<?php echo HTTP_LIB_PATH;?>/LeaveReports/ajaxLeavesHistoryGraphData.php';
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {
                 leaveSessionId   : document.getElementById('leaveSessionId').value,
                 employeeId : document.getElementById('employeeDD').value
        },
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
        hideWaitDialog(true);
        if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
            showWaitDialog(true);
            showLeavesHistoryBarChartResults();
            hideWaitDialog(true);
        }                                                                           
        else{
            document.getElementById('results').innerHTML="<center><br><br><?php echo NO_DATA_FOUND; ?></center>";
        }
    },
    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function showLeavesHistoryBarChartResults() {
    var filterText=getFilterName();
    var x = Math.random() * Math.random();
    var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "420", "5", "#FFFFFF");
    so.addVariable("path", "./");
    so.addParam("wmode", "transparent");
    so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart    
    so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>No of Days</text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Leave Status</text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Employee Leaves History : </text><text_size>18</text_size></label><label id='4'><x>370</x><y>15</y><rotate>false</rotate><text>"+filterText+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area><column><balloon_text><![CDATA[{value} days(s) of {title} Status : ({series})]]></balloon_text></column><legend><enabled></enabled><x>50</x><y>380</y><width>1200</width><max_columns></max_columns><color></color><alpha>0</alpha><border_color></border_color><border_alpha></border_alpha><text_color></text_color><text_size></text_size><spacing>5</spacing><margins></margins><reverse_order>false</reverse_order><align></align><key><size></size><border_color></border_color></key></legend><export_as_image><file>image.php</file><target>_top</target><x>0</x><y></y><color></color><alpha></alpha><text_color></text_color><text_size></text_size></export_as_image></settings>");
    so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting3.xml"));
    so.addVariable("data_file", encodeURIComponent("../Templates/Xml/leavesHistoryStackData.xml?t="+x));
    so.write("results");
}


function vanishData(){
    document.getElementById('results').innerHTML='';
    document.getElementById('printTrId').style.display='none';
}


function printReport() {
    if(!inputValidation()){
      return false;
    }
    
    var yearName=document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    var employeeName=document.getElementById('employeeDD').options[document.getElementById('employeeDD').selectedIndex].text;
    var path='<?php echo UI_HTTP_PATH;?>/leavesHistoryReportPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&leaveSessionId='+document.getElementById('leaveSessionId').value+'&employeeDD='+document.getElementById('employeeDD').value+'&yearName='+yearName+'&employeeName='+employeeName;
    window.open(path,"LeavesHistoryReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    if(!inputValidation()){
      return false;
    }

    var yearName=document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    var employeeName=document.getElementById('employeeDD').options[document.getElementById('employeeDD').selectedIndex].text;
    var path='<?php echo UI_HTTP_PATH;?>/leavesHistoryReportCSV.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&leaveSessionId='+document.getElementById('leaveSessionId').value+'&employeeDD='+document.getElementById('employeeDD').value+'&yearName='+yearName+'&employeeName='+employeeName;
    
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/LeaveReports/leavesHistoryReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listCity.php $ 
?>