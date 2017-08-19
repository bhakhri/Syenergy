<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaveConflictReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


function createDutyLeaveStatusString(){
    global $globalDutyLeaveStatusArray;
    $returnString='<option value="-1">Select</option>';
    foreach($globalDutyLeaveStatusArray AS $key=>$value){
        $returnString .='<option value="'.$key.'" >'.$value.'</option>';
    }
    return '<select name="commonDutyLeaveStatus" id="commonDutyLeaveStatus" class="inputbox" style="width:100px;" onchange="changeDutyLeaveStatus(this.value);">'.$returnString.'</select>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Duty Leave Conflict Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray; 

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 2000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/DutyLeave/ajaxDutyLeaveConflictReport.php';
searchFormName = 'conflictReportForm'; // name of the form which will be used for search
/*
  addFormName    = 'AddCity';   
  editFormName   = 'EditCity';
  winLayerWidth  = 315; //  add/edit form width
  winLayerHeight = 250; // add/edit form height
  deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var serverDate="<?php echo date('Y-m-d');?>";

function changeDutyLeaveStatus(val){
  var c1 = document.getElementById(divResultName).getElementsByTagName('SELECT');
  var len=c1.length;
  for(var i=0;i<len;i++){
      if(c1[i].name=='dutyLeaveStatus'){
          c1[i].value=val;
          checkBulkAttendanceRestriction(c1[i].id,c1[i].value);
      }
  }
}

// Function to show instructions for Conflict and Non Conflict Report
function getShowDetail() {
   // document.getElementById("divshowHelp").innerHTML = "";
    displayWindow('showHelpMessage',200,200); 
}
// End of Function to show instructions for Conflict and Non Conflict Report

function validateData(){
    
   document.getElementById('labelId').className='htmlElement';  
   document.getElementById('classId').className='htmlElement';  
   document.getElementById('eventId').className='htmlElement';  
   document.getElementById('subjectId').className='htmlElement';  
    
   if(document.getElementById('labelId').value==''){
     document.getElementById('labelId').className='inputboxRed'; 
     messageBox("<?php echo SELECT_TIME_TABLE;?>");
     document.getElementById('labelId').focus();
     return false;
   }
   
   if(document.getElementById('classId').value==''){
     document.getElementById('classId').className='inputboxRed'; 
     messageBox("<?php echo SELECT_CLASS;?>");
     document.getElementById('classId').focus();
     return false;
   }
   
   if(document.getElementById("eventId").value==-1){
      document.getElementById('eventId').className='inputboxRed';  
      messageBox("<?php echo SELECT_DUTY_LEAVE_EVENT;?>");
      document.getElementById("eventId").focus();
      return true;
  }  
  
  if(document.getElementById('subjectId').value==''){
     document.getElementById('subjectId').className='inputboxRed'; 
     messageBox("<?php echo SELECT_SUBJECT;?>");
     document.getElementById('subjectId').focus();
     return false;
   }
   
   hideResults();
   page=1;
   sortField   = 'dutyDate';
   sortOrderBy = 'ASC';
   if(document.conflictReportForm.showConflict[0].checked==true || document.conflictReportForm.showConflict[2].checked==true){
       tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                //new Array('className','Class','width="15%"','',true) , 
                                new Array('dutyDate','Date','width="6%"','align="center"',false),  
                                new Array('periodNumber','Period','width="5%"','',false), 
                                new Array('rollNo','Roll No.','width="8%"','',false), 
                                new Array('studentName','Name','width="12%"','',false), 
                                new Array('subjectCode','Subject','width="10%"','',false), 
                                new Array('eventTitle','Event','width="8%"','',false) , 
                                new Array('conflictedWith','Conflicted with','width="8%"','',false), 
                                new Array('actionString','Action&nbsp;<?php echo createDutyLeaveStatusString();?>','width="12%"','',false) 
                              );
       
   }
   else{
     tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                //new Array('className','Class','width="15%"','',true) , 
                                new Array('dutyDate','Date','width="6%"','align="center"',false), 
                                new Array('periodNumber','Period','width="5%"','',false), 
                                new Array('studentName','Name','width="12%"','',false), 
                                new Array('rollNo','Roll No.','width="8%"','',false), 
                                new Array('eventTitle','Event','width="8%"','',false) , 
                                new Array('subjectCode','Subject','width="10%"','',false), 
                                new Array('actionString','Action&nbsp;<?php echo createDutyLeaveStatusString();?>','width="12%"','',false) 
                              );  
   }
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   scanForBulkAttendance();
   document.getElementById('savePrintRowId').style.display='';
   document.getElementById('printSpanId').style.display='';
   
}

var lectureAttendedObject  = new Object();
var lectureDeliveredObject = new Object();
function checkBulkAttendanceRestriction(id,value){
   document.getElementById('printSpanId').style.display='none'; 
   var eleArray=id.split('_');
   if(eleArray[6]==1){
       if(value=="<?php echo DUTY_LEAVE_APPROVE;?>"){
        var ele=eleArray[0]+'_'+eleArray[1]+'_'+eleArray[2];
        var lecAtt=lectureAttendedObject[ele];
        var lecDel=lectureDeliveredObject[ele];
        if((lecAtt+1)>lecDel){
            messageBox("<?php echo DUTY_LEAVE_RESTRICTION;?>");
            document.getElementById(id).focus();
            return false;
        }
       }
       else{
           return true;
       }
   }
   else{
       return true;
   }
}

function scanForBulkAttendance(){
    lectureAttendedObject  = new Object();
    lectureDeliveredObject = new Object();
    var elements=document.getElementById('results').getElementsByTagName('SELECT');
    var len=elements.length;
    for(var i=0;i<len;i++){
        if(elements[i].name=='dutyLeaveStatus'){
          var eleArray=elements[i].id.split('_');
          if(eleArray[6]==1){
            var ele=eleArray[0]+'_'+eleArray[1]+'_'+eleArray[2];
            if(isNaN(parseInt(lectureAttendedObject[ele]))){
              lectureAttendedObject[ele]=eleArray[7];
            }
            else{
              lectureAttendedObject[ele]=eleArray[7]+lectureAttendedObject[ele];  
            }
            if(isNaN(parseInt(lectureDeliveredObject[ele]))){
              lectureDeliveredObject[ele]=eleArray[8];
            }
            else{
              lectureDeliveredObject[ele]=eleArray[8]+lectureDeliveredObject[ele];  
            }
          }
        }
    }
}

function doDutyLeave(){
  
    var chkDutyStatus='';
    var elements=document.getElementById('results').getElementsByTagName('SELECT');
    var len=elements.length;
    var inputString='';
    for(var i=0;i<len;i++){
        if(elements[i].name=='dutyLeaveStatus') {
            elements[i].className='htmlElement'; 
            if(elements[i].value=='-1'){
              if(chkDutyStatus=='') {
                chkDutyStatus=i;  
              }
              elements[i].className='inputboxRed';
            }
            if(inputString!=''){
                inputString +=',';
            }
            inputString +=elements[i].id+'_'+elements[i].value;
        }
    }
    
    if(chkDutyStatus!='') {
       elements[chkDutyStatus].focus(); 
       messageBox("<?php echo "Select Duty Leave Status";?>");    
       return false;
    }
    
    
    if(inputString==''){
        messageBox("<?php echo NO_DATA_SUBMIT;?>");
        return false;
    }  
    
     var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeave/ajaxDoDutyLeave.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             classId     : document.getElementById("classId").value,
             eventId     : document.getElementById("eventId").value,
	     rollNo     : document.getElementById("rollNo").value,
             inputString : inputString
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true); 
                var ret=trim(transport.responseText); 
                if(ret=="<?php echo SUCCESS;?>"){
                    messageBox("<?php echo DUTY_LEAVES_GIVEN;?>");
                    hideResults();
                }
		        else if(ret=="<?php echo FAILURE;?>"){
                    messageBox("<?php echo FAILURE;?>");
                    hideResults();
                }
                else{
                    var retArray=ret.split('!~!');  
                    if(retArray[0]=='Duty Leave exceeding Maximum Limit'){
                         document.getElementById('divLimitExceed').innerHTML = trim(retArray[2]);
                         displayWindow('divMessage',200,200); 
                         if(document.getElementById('isApprove').checked==true) {
                             len=retArray[1].length;
                             listDuty = eval('('+retArray[1]+')');
                             var c1 = document.getElementById(divResultName).getElementsByTagName('SELECT');
                             var len=c1.length; 
                             for(var k=0;k<len;k++){
                                if(c1[k].name=='dutyLeaveStatus'){
                                    valueArray=c1[k].id; //alert(valueArray); return false;
                                    valueArray1=valueArray.split('_');
                                    var studentId=valueArray1[0]; 
                                    var classId=valueArray1[1];
                                    var subjectId=valueArray1[2]; 
                                    var isStatus = c1[k].value;
                                    if(isStatus == "<?php echo DUTY_LEAVE_APPROVE; ?>") {
                                      for(var i=0; i<listDuty.length; i++){ 
		                                if(listDuty[i]['studentId'] == studentId && listDuty[i]['classId'] == classId && listDuty[i]['subjectId'] == subjectId) {
                                            c1[k].className='inputboxRed'; 
	                                        break;
	                                    } // end If
                                      } // end If
                                    } // end If
                                   } // end If
                                } // for
                         }
		            }
		            else {
		               messageBox(retArray[1]);
		               try{
		                  document.getElementById(retArray[0]).focus();
		               }
		               catch(e){}
		            }
               }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function getTimeTableClasses() {
    hideResults();
    var form = document.conflictReportForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
    var pars = 'labelId='+form.labelId.value;
    form.classId.options.length = 1;
    if (form.labelId.value=='') {
        return false;
    }

    new Ajax.Request(url,
    {
        method:'post',
        asynchronous : false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            
            for(i=0;i<len;i++) {
                addOption(form.classId, j[i].classId, j[i].className);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function getDutyEvent(val) {
         hideResults();
         document.conflictReportForm.eventId.options.length=1;
         if(val==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeave/getDutyEvents.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {
                 labelId: val
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    var j = eval('('+trim(transport.responseText)+')');
                    var len=j.length;
					addOption(document.conflictReportForm.eventId, '-2', 'All');
                    for(var i=0;i<len;i++){
                       addOption(document.conflictReportForm.eventId,j[i].eventId,j[i].eventTitle);
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getClassSubjects() {
	form = document.conflictReportForm;
	var degree = form.classId.value;
	var labelId = form.labelId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassAttendanceSubject.php';
	var pars = 'timeTable='+labelId+'&degree='+degree;
	if (degree == '') {
		document.conflictReportForm.subjectId.length = null;
		addOption(document.conflictReportForm.subjectId, '', 'Select');
		return false;
	}
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
               hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				document.conflictReportForm.subjectId.length = null;
				if(len>0) {
					 addOption(document.conflictReportForm.subjectId, '', 'Select');
				   for(i=0;i<len;i++) { 
				     addOption(document.conflictReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
				   }
                 }
                 else {
                   addOption(document.conflictReportForm.subjectId, '', 'Select');
                 }
				// now select the value
			 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}


function hideResults(){
    document.getElementById('results').innerHTML='';
    document.getElementById('savePrintRowId').style.display='none';
    document.getElementById('printSpanId').style.display='none';
}


/* function to output data for printable version*/
function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/dutyLeaveConflictReportPrint.php?'+generateQueryString('conflictReportForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path +='&labelName='+document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text;
    path +='&className='+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    path +='&eventName='+document.getElementById('eventId').options[document.getElementById('eventId').selectedIndex].text;
    window.open(path,"DutyLeaveConflictReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {

    var path='<?php echo UI_HTTP_PATH;?>/dutyLeaveConflictReportCSV.php?'+generateQueryString('conflictReportForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path +='&labelName='+document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text;
    path +='&className='+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    path +='&eventName='+document.getElementById('eventId').options[document.getElementById('eventId').selectedIndex].text;

    window.location = path;
}

window.onload=function(){ 
   getTimeTableClasses(document.getElementById('labelId').value);
   getDutyEvent(document.getElementById('labelId').value);  
   return false;   
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/DutyLeave/dutyLeaveConflictReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listTeacherAttendanceReport.php $ 
?>
