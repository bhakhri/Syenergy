<?php 
//-------------------------------------------------------
//  This File contains the template file and data base file
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayRoomTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Class Time Table </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author : Dipanjan Bhattacharjee
// Created on : 31.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>

<script language="javascript">

queryString='';    

function populateRoom(){
    
    document.getElementById('roomId').length = null;
    addOption(document.getElementById('roomId'), '', 'Select');
    
    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false; 
    }
    
    var timeTable = document.getElementById('labelId').value;
     
    var rval=timeTable.split('~');
    var timeTableLabelId = rval[0];
    var timeTableType = rval[3];   
    
    var typeFormat = 'r';
     
    var url ='<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTimeTableValues.php';
        
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,
         parameters: {timeTabelId: timeTableLabelId,
                      timeTableType: timeTableType,
                      typeFormat: typeFormat },
         onCreate: function(transport){
              showWaitDialog();
         },
         onSuccess: function(transport){
           hideWaitDialog();
           j = eval('('+transport.responseText+')'); 
           for(var c=0;c<j.length;c++) {
             var objOption = new Option(j[c].roomName,j[c].roomId);
             document.getElementById('roomId').options.add(objOption);
           }
         },
         onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
      }); 
}


//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO get time table date for a class
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.082008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function getTimeTableData() {
 
     if(document.getElementById('labelId').value==""){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");   
        document.getElementById('labelId').focus();
        return false;
     } 
     if(document.getElementById('roomId').value==""){
        messageBox("<?php echo SELECT_ROOM; ?>");
        document.getElementById('roomId').focus();
        return false;
     } 
     
     var roomIds = document.getElementById('roomId').value;
     var timeTable = document.getElementById('labelId').value;
     
     
     var rval=timeTable.split('~');
     var timeTableLabelId = rval[0];
     var timeTableType = rval[3];
     
     parma = "&labelId="+timeTableLabelId+"&roomId="+roomIds+"&timeTableType="+timeTableType+'&typeFormat=r';
     if(timeTableType==2) {   
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;
        parma =parma+ "&fromDate="+fromDate+"&toDate="+toDate;
     }
     
     queryString = parma;
       
     url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxRoomTimeTable.php';  
     new Ajax.Request(url,
       {
         method:'post',
         parameters: parma,
         asynchronous: false,
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             document.getElementById('results').innerHTML=trim(transport.responseText);
             changeColor(currentThemeId);
          },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
       changeColor(currentThemeId);
}


function clearText() {
   
   document.getElementById("timeTableCheck").style.display='none';  
   timeTable = document.getElementById('labelId').value;
   
   if(timeTable=='') {
     return false; 
   }
   
   var rval=timeTable.split('~');
   timeTableLabelId = rval[0];
   timeTableType = rval[3]; 
   
   if(timeTableType==2) {
      document.getElementById("timeTableCheck").style.display='';
      if(rval[1]!='0000-00-00' && rval[1]!='') {
        document.getElementById('fromDate').value=rval[1];
      }
      if(rval[2]!='0000-00-00' && rval[2]!='') {
        document.getElementById('toDate').value=rval[2];
      }
   }
}

function printReport() {
    path='<?php echo UI_HTTP_PATH;?>/roomWiseTimeTableReportPrint.php?'+queryString;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=440, top=150,left=150");
}

window.onload=function() { 
   clearText(); 
   populateRoom();
}
 
</script> 

</head>
<body>
 <?php 
     require_once(TEMPLATES_PATH . "/header.php");
     require_once(TEMPLATES_PATH . "/TimeTable/listRoomTimeTableContents.php");
     require_once(TEMPLATES_PATH . "/footer.php");
 ?>
</body>
</html>
<?php
//History: $
?>