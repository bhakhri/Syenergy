<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeEmployeeLeaveSetMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Leave Set Mapping </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('employeeCode','Employee Code','width="30%"','',true) ,
    new Array('employeeName','Employee Name','width="30%"','',true), 
    new Array('leaveSetName','Leave Set','width="15%"','align="left"',true), 
    new Array('action','Action','width="2%"','align="center"',false)
  );
  

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddEmployeeLeaveSetMapping';   
editFormName   = 'EditEmployeeLeaveSetMapping';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteEmployeeLeaveSetMapping';
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    populateValues(id,dv,w,h);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    if(act=='Add') {
        addEmployeeLeaveSetMapping();
        return false;
    }
    else if(act=='Edit') {
        editEmployeeLeaveSetMapping();
        return false;
    }
}
   
var typeArray=new Array();
function checkDuplicateValue(value){
    var i= typeArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(typeArray[k]==value){
          fl=0;
          break;
      }  
    }
   if(fl==1){
       typeArray.push(value);
   } 
   return fl;
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEmployeeLeaveSetMapping() {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxInitAdd.php';
         typeArray.splice(0,typeArray.length);
         var leaveSetString='';
         if(document.AddEmployeeLeaveSetMapping.employeeId.value==''){
             messageBox("<?php  echo ENTER_VALID_EMPLOYEE_INFO?>");
             document.AddEmployeeLeaveSetMapping.employeeCode.focus();
             return false;
         }
         if(document.AddEmployeeLeaveSetMapping.leaveSet.value==''){
             messageBox("<?php  echo SELECT_LEAVE_SET; ?>");
             document.AddEmployeeLeaveSetMapping.leaveSet.focus();
             return false;
         }

         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 leaveSet    : document.AddEmployeeLeaveSetMapping.leaveSet.value, 
                 employeeId  : document.AddEmployeeLeaveSetMapping.employeeId.value,
                 leaveSessionId : document.getElementById('leaveSessionId').value 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddEmployeeLeaveSetMapping');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A optionLabel
//  id=degreeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteEmployeeLeaveSetMapping(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mappingId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}



//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddEmployeeLeaveSetMapping" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function blankValues() {
   document.AddEmployeeLeaveSetMapping.employeeCode.value=''
   document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>";
   document.getElementById('empSearchDiv').innerHTML='';
   document.getElementById('empName').value='';
   document.AddEmployeeLeaveSetMapping.reset();
   document.AddEmployeeLeaveSetMapping.employeeCode.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editEmployeeLeaveSetMapping() {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxInitEdit.php';
         
         if(document.EditEmployeeLeaveSetMapping.leaveSet.value==''){
             messageBox("<?php  echo SELECT_LEAVE_SET; ?>");
             document.EditEmployeeLeaveSetMapping.leaveSet.focus();
             return false;
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                 mappingId : document.EditEmployeeLeaveSetMapping.mappingId.value,
                 leaveSet  : document.EditEmployeeLeaveSetMapping.leaveSet.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditEmployeeLeaveSetMapping');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else if("<?php echo DUPLICATE_LEAVE_SET;?>" == trim(transport.responseText)){
                         messageBox("<?php echo DUPLICATE_LEAVE_SET ;?>"); 
                         document.EditEmployeeLeaveSetMapping.leaveSet.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText)); 
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditEmployeeLeaveSetMapping" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxGetValues.php';
         document.EditEmployeeLeaveSetMapping.employeeCode.value  = '';
         document.getElementById('employeeName2').innerHTML  = '';
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
                        messageBox("<?php echo EMPLOYEE_LEAVE_SET_MAPPING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                     }
                     else if(trim(transport.responseText)=="<?php echo EMPLOYEE_LEAVE_SET_MAPPING_CAN_NOT_MOD_DEL; ?>"){
                       messageBox("<?php echo EMPLOYEE_LEAVE_SET_MAPPING_CAN_NOT_MOD_DEL; ?>");
                     }
                    else{
                         displayWindow(dv,w,h); 
                         var j = eval('('+trim(transport.responseText)+')');
                         document.EditEmployeeLeaveSetMapping.leaveSet.value      = j.leaveSetId;
                         document.EditEmployeeLeaveSetMapping.employeeCode.value  = j.employeeCode;
                         document.getElementById('employeeName2').innerHTML       = j.employeeName;
                         document.EditEmployeeLeaveSetMapping.mappingId.value     = j.employeeLeaveSetMappingId;
                         document.EditEmployeeLeaveSetMapping.leaveSet.focus();
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeCode(val) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxGetEmployeeCode.php';
         document.AddEmployeeLeaveSetMapping.employeeId.value='';
         document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>";
         //cleanUpTable(); 
         if(trim(val)==''){
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {val: val},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        document.AddEmployeeLeaveSetMapping.employeeId.value='';
                        document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>"; 
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        //fetchPreviousRecords(j.employeeId);
                        document.AddEmployeeLeaveSetMapping.employeeId.value  = j.employeeId;
                        document.getElementById('employeeName').innerHTML     = j.employeeName;
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/*
function fetchPreviousRecords(val){
    var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxGetMappedValues.php';
    new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {val: val},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        return false
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        if(j.length>0){
                         cleanUpTable();
                         resourceAddCnt=j.length; 
                         createRows(1,resourceAddCnt,0);
                         for(var i=0;i<resourceAddCnt;i++){
                           document.getElementById('leaveSetId'+(i+1)).value=j[i].leaveSetId; 
                         }
                      }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
}
*/
/* function to print FeedBack Grades report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/leaveSetMappingReportPrint.php?'+qstr;
    window.open(path,"EmployeeLeaveSetMappingReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='leaveSetMappingReportCSV.php?'+qstr;
}

function searchEmployees(){
    document.getElementById('empSearchDiv').innerHTML='';
    var eName=trim(document.getElementById('empName').value);
    if(eName==''){
        messageBox("Enter employee name");
        document.getElementById('empName').focus();
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxGetEmployeeList.php';
  
    var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('employeeName','Name','width="10%" align="left"',true),
                        new Array('employeeCode','Emp.Code','width="10%" align="left"',true)
                       );

   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','employeeName','ASC','empSearchDiv','','',true,'listObj',tableColumns,'','','&empName='+eName);
   sendRequest(url, listObj, '')
}


function setEmployeeCode(val) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxGetEmployeeCode.php';
         document.AddEmployeeLeaveSetMapping.employeeId.value='';
         document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>";
         //cleanUpTable(); 
         if(trim(val)==''){
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {
                 val: val,
                 id : 1
              },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        document.AddEmployeeLeaveSetMapping.employeeId.value='';
                        document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>"; 
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        //fetchPreviousRecords(j.employeeId);
                        document.AddEmployeeLeaveSetMapping.employeeId.value   = j.employeeId;
                        document.AddEmployeeLeaveSetMapping.employeeCode.value = j.employeeCode;
                        document.getElementById('employeeName').innerHTML      = j.employeeName;
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function selectEmployee(val){
   //document.AddEmployeeLeaveSetMapping.employeeCode.value=val; 
   setEmployeeCode(val); 
}


function sendKeys(eleName, e) {
 var ev = e||window.event;
 
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13' &&!isMozilla) {
  searchEmployees();
  return false;
 }
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/listEmployeeLeaveSetMappingPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"EmployeeLeaveSetMappingReport","status=1,menubar=1,scrollbars=1,height=500, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listEmployeeLeaveSetMappingCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeLeaveSetMapping/listEmployeeLeaveSetMappingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    
    <SCRIPT LANGUAGE="JavaScript">
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    </SCRIPT>
</body>
</html>

<?php 
// $History: listEmployeeLeaveSetMappingAdv.php $ 
?>