<?php
//-----------------------------------------------------------------------------
//  To generate Studnet Attendance Short functionality      
//
//
// Author :Parveen Sharma
// Created on : 06-03-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Head Wise Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

//This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/FeeHeadReport/ajaxFeeHeadList.php'; 
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'Asc';
 //This function Validates Form 
queryString='';

function validateAddForm() {
    
    hideResults();
    queryString='';
    
    var fromDate = document.studentAttendanceForm.fromDate.value;
    var toDate = document.studentAttendanceForm.toDate.value;
    
    if(fromDate!='' || toDate!='') {
      if(fromDate==""){
        messageBox("<?php echo EMPTY_FROM_DATE; ?>");
        document.getElementById('fromDate').focus();
        return false;
      }
      if(toDate==""){
        messageBox("<?php echo EMPTY_TO_DATE; ?>");
        document.getElementById('toDate').focus();
        return false;
      }  
    }
    
    if(fromDate!='' && toDate!='') { 
      if(dateCompare(fromDate,toDate)==1){
        messageBox("<?php echo FR_CORRECT_DATE?>");
        document.studentAttendanceForm.toDate.select();
        return false;
      }
    }
 
    page=1;
    showReport(page);    
    return false;   
}


function showReport(page) {

     document.getElementById('resultsDiv').innerHTML = '';
     document.getElementById("pagingDiv").innerHTML  = '';
     document.getElementById("pagingDiv1").innerHTML = '';
     
     var fromDate = document.studentAttendanceForm.fromDate.value;
     var toDate = document.studentAttendanceForm.toDate.value;
 
     var url='<?php echo HTTP_LIB_PATH;?>/FeeHeadReport/ajaxFeeHeadList.php';
     form = document.studentAttendanceForm;   
  
     var feeHeadIds = '';
     totalFeeHead = form.elements['feeHead[]'].length;
     for(i=0;i<totalFeeHead;i++) {
        if(form.elements['feeHead[]'][i].selected == true) {
          if(feeHeadIds != '') {
            feeHeadIds += ',';
          }
          feeHeadIds += form.elements['feeHead[]'][i].value;
        }
     }
     
     var consolidatedId=0;
     if(document.getElementById('consolidatedId').checked==true){
       consolidatedId=1;
     }
     
     var studentStatus=1;
     if(document.studentAttendanceForm.studentStatus[0].checked==true){
       studentStatus=1; 
     }
     else if(document.studentAttendanceForm.studentStatus[1].checked==true){
        studentStatus=2; 
     }
     else{
        studentStatus=3; 
     }
     
     queryString = 'reportFormat='+document.getElementById('reportFormat').value+"&feeCycleId="+document.getElementById('feeCycleId').value;
     queryString = queryString+'&feeHead='+feeHeadIds+"&feeClassId="+document.getElementById('feeClassId').value;
     queryString = queryString+'&rollNo='+document.getElementById('rollNo').value+"&receiptNo="+document.getElementById('receiptNo').value;
     queryString = queryString+'&fromDate='+fromDate+"&toDate="+toDate+'&consolidatedId='+consolidatedId;
     queryString = queryString+'&studentStatus='+studentStatus;
     
     
     //queryString = queryString+'&fromDate='+document.getElementById('fromDate').value+"&toDate="+document.getElementById('toDate').value; 
     //fromDate: document.getElementById('fromDate').value,
     //toDate: document.getElementById('toDate').value,      
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters:{reportFormat: document.getElementById('reportFormat').value,
                     feeCycleId: document.getElementById('feeCycleId').value,
                     feeHead: feeHeadIds,
                     feeClassId: document.getElementById('feeClassId').value,
                     rollNo: document.getElementById('rollNo').value,
                     receiptNo: document.getElementById('receiptNo').value,
                     consolidatedId: consolidatedId,
                     studentStatus: studentStatus,
                     fromDate: fromDate,
                     toDate: toDate,
                     sortOrderBy: sortOrderBy,
                     sortField : sortField,
                     page: page
                    },
         asynchronous:true,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else
             if("<?php echo FOXPRO_LIST_EMPTY;?>" == trim(transport.responseText)) {
                messageBox("<?php echo FOXPRO_LIST_EMPTY?>");  
             }
             else {
                var ret=trim(transport.responseText).split('!~~!');
                var j0 = ret[0];
                var j1 = ret[1];
                
                if(j1=='') {
                  totalRecords = 0;
                }
                else {
                  totalRecords = j1; 
                }
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById("pageRow").style.display='';    
                document.getElementById('resultsDiv').innerHTML=j0;
                //document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
                
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("pagingDiv1").innerHTML = pagingData;
                
                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                if (totalRecords > 0) {
                    pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                    document.getElementById("pagingDiv").innerHTML = pagingData;
                    document.getElementById("pagingDiv1").innerHTML = "<b>Total Records&nbsp;:&nbsp;</b>"+totalRecords; 
                }
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });

}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("pagingDiv").innerHTML = '';
    document.getElementById("pagingDiv1").innerHTML = '';
    document.getElementById("pageRow").style.display='none';
}


function printReport() {                    
    
   var qstr=queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
   path='<?php echo UI_HTTP_PATH;?>/feeHeadWiseReportPrint.php?'+qstr;
   window.open(path,"FeeHeadWiseReportPrint","status=1,menubar=1,scrollbars=1, width=900,height=600");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr=queryString+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField; 
    path='<?php echo UI_HTTP_PATH;?>/feeHeadWiseReportCSV.php?'+qstr;
    window.location = path;  
}

function populateClass(){
    
    document.allDetailsForm.classId.length = null;
    addOption(document.allDetailsForm.classId, '', 'Select');
    
    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false; 
    }
    
     
    var url ='<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxTeacherGetClasses.php';
        
    new Ajax.Request(url,
    {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId: document.getElementById('labelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                      var objOption = new Option(j[c].className,j[c].classId);
                      document.allDetailsForm.classId.options.add(objOption);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function getConsolidated() {
   
   document.getElementById('consolidatedId').checked=false;           
   if(document.getElementById('reportFormat').value==2) {
     document.getElementById('consolidatedId').disabled=true;         
   } 
   if(document.getElementById('reportFormat').value==3) {
     document.getElementById('consolidatedId').disabled=true;         
   } 
   else {
     document.getElementById('consolidatedId').disabled=false;   
   }
   return false;   
}

window.onload=function() {
   getConsolidated();
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeHeadReport/listFeeHeadReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
