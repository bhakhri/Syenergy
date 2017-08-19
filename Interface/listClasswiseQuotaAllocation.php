<?php
//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Parveen Sharma
// Created on : 27-01-09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClasswiseQuotaAllocation');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Class wise Quota Allocation</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script type="text/javascript" language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'attendancePercentFrm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubject';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'quotaName';
sortOrderBy = 'ASC';


function refreshQuotaData() {
   var  url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxClasswiseQuotaValues.php';  
   
   hideValue();
  
   var serverDate="<?php echo date('Y-m-d'); ?>";   
    
   if(!dateDifference(document.getElementById('allocationDate').value,serverDate,"-")){
       messageBox("<?php echo FUTURE_DATE_VALIDATION; ?>");   
       document.getElementById('allocationDate').focus();  
       return false;
   }  
    
   allocationDate = document.getElementById('allocationDate').value;
   classId = document.getElementById('classId').value;
   roundId = document.getElementById('roundId').value;
   
   if(trim(classId)==""){
      messageBox ("<?php echo SELECT_CLASS;?>");
      eval("document.getElementById('classId').focus();");
      return false;
   } 
   
   if(trim(roundId)==""){
      messageBox ("<?php echo "Select round";?>");
      eval("document.getElementById('roundId').focus();"); 
      return false;
   } 
   
   document.getElementById('trAttendance').style.display='';
   document.getElementById('results').style.display='';
   document.getElementById('results11').style.display='';
   document.getElementById('resultsDiv').innerHTML='';
   
   var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('quotaName','Quota','width="25%" align="left"',false),
                        new Array('totalSeats','Total Seats','width="15%" align="right"',false),
                        new Array('seatsAllocated','Allocated Seats','width="15%" align="right"',false), 
                        new Array('newAllocatedSeats','New Allocation Seats','width="15%" align="center"',false)
                       );
   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','quotaName','ASC','resultsDiv','','',true,'listObj1',tableColumns,'','','&allocationDate='+allocationDate+'&classId='+classId+'&roundId='+roundId);
   sendRequest(url, listObj1, '',true )
}

function validateAddForm(frm) {

    
    if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
    } 
    
    if(trim(document.getElementById('roundId').value)==""){
      messageBox("<?php echo "Select round"; ?>");
      document.getElementById('roundId').focus();
      return false;
    }
    
     formx = document.attendancePercentFrm;
     obj=formx.getElementsByTagName('INPUT');
     total=obj.length;  
     for(i=0;i<total;i++) {
       if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('newAllocatedSeats[]')>-1) {
           obj[i].value=trim(obj[i].value);
           // blank value check 
           /* if(trim(obj[i].value) == "") {
                messageBox ("New allocation seats field cannot be left blank");
                obj[i].focus();
                return false;             
              }
           */  
           // Integer Value Checks updated
           if(!isInteger(obj[i].value)) {
             messageBox ("Enter numeric value for new allocation seats");
             obj[i].focus();
             return false;
           }
           
           // Ranges Checks 
           if(parseInt(obj[i].value,10) < 0 ) {
             messageBox ("New allocation seats cannot be zero");
             obj[i].focus();
             return false;
           }
        }
     }
    
    addClassWiseQuotaAllocation();
    return false;
}


function addClassWiseQuotaAllocation() {
   
   url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxClasswiseQuotaAllocationAdd.php';
   
   document.getElementById('trAttendance').style.display='none';
   if (document.attendancePercentFrm.classId.value != '') {
     document.getElementById('trAttendance').style.display='';
   }
   
   params = generateQueryString('attendancePercentFrm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo QUOTA_SLAB_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo QUOTA_SLAB_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText)) {                                                                                                              
           messageBox(trim(transport.responseText)); 
           resetValues();
           document.getElementById('classId').selectedIndex=0;  
           document.getElementById('classId').focus();
           document.getElementById('trAttendance').style.display='none';
           document.getElementById('results').style.display='none';
           document.getElementById('results11').style.display='none';
           return false;
        }
        else {
           var ret=trim(transport.responseText).split('!~~!');
           var j0 = trim(ret[0]);
           var j1 = trim(ret[1]);  
           messageBox(j0);    
           if(j1!='') {
             id = "newAllocatedSeats"+j1;
             eval("document.getElementById('"+id+"').className='inputboxRed'"); 
             eval("document.getElementById('"+id+"').focus()");
           }
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}



function resetValues() {
    document.getElementById('attendancePercentFrm').reset();
}

function hideValue() {
    document.getElementById('trAttendance').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
}


function getShowRound(){
   
   hideValue();
   document.attendancePercentFrm.classAllocationId.value=-1;
   var  url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxClasswiseRoundValues.php';  
   
   allocationDate = document.getElementById('allocationDate').value;
   classId = document.getElementById('classId').value;
   
   document.attendancePercentFrm.roundId.length = null; 
   addOption(document.attendancePercentFrm.roundId, '', 'Select');

   if(trim(classId)==""){
      return false;
   } 
   
   new Ajax.Request(url,
   {
     method:'post',
     parameters: {classId: classId,
                  allocationDate: allocationDate 
                 },                  
     asynchronous:false,
     onCreate: function () {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);
        var j = eval('(' + transport.responseText + ')');
        len = j.length;
        if(len>0) {
          document.attendancePercentFrm.roundId.length = null;                  
          //addOption(document.attendancePercentFrm.roundId, '', 'Select');    
          for(i=0;i<len;i++) { 
            addOption(document.attendancePercentFrm.roundId, j[i].roundId, j[i].roundName);
          }
          document.attendancePercentFrm.classAllocationId.value=j[0].classAllocationId;
        }
        else {
          document.attendancePercentFrm.roundId.length = null;                    
          // add option round Data
          var len= document.getElementById('roundIds').options.length;
          var t=document.getElementById('roundIds');
          // add option Select initially
          if(len>0) {
            for(k=0;k<len;k++) { 
              addOption(document.getElementById('roundId'), t.options[k].value,  t.options[k].text);
            }
          }
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function sendKeys(e,formname) {

    var ev = e||window.event;
    thisKeyCode = ev.keyCode;
    if (thisKeyCode == '13') {
       validateAddForm(this);
       return false;
    }
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Quota/listClasswiseQuotaAllocationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listClasswiseQuotaAllocation.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/04/10    Time: 1:14p
//Created in $/LeapCC/Interface
//initial checkin
//

?>