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
define('MODULE','EmployeeLeaveAuthorizer');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Leave Authorizer </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddEmployeeLeaveAuthorizer';   
editFormName   = 'EditEmployeeLeaveAuthorizer';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteEmployeeLeaveAuthorizer';
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function refereshLeaveAuthorizerList() {
    
        var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitList.php'; 
        var tableColumns = new Array(new Array('srNo','#','width="2%"',false),
                                     new Array('employeeName','Employee Name','width="20%" ',true), 
                                     new Array('employeeCode','Employee Code','width="20%"',true) ,
                                     new Array('firstApprovingEmployee','First Authorizer','width="20%"',true));
                                     if(document.getElementById('hiddenLeaveAuthorizersId').value==2) { 
                                       tableColumns.push(new Array('secondApprovingEmployee', 'Second Authorizer','width="20%" ',true));     
                                     }
                                     tableColumns.push(new Array('leaveTypeName','LeaveType','width="15%" ',true));
                                     tableColumns.push(new Array('action1','Action','width="2%" ',false));
        
        listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','employeeName','ASC','results','','',true,'listObj4',tableColumns,'','','&searchbox='+trim(document.searchForm.searchbox.value));
        sendRequest(url, listObj4, '',true)
}
    

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
        addEmployeeLeaveAuthorizer();
        return false;
    }
    else if(act=='Edit') {
        editEmployeeLeaveAuthorizer();
        return false;
    }
}
   


//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEmployeeLeaveAuthorizer() {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitAdd.php';
         if(document.AddEmployeeLeaveAuthorizer.employeeId.value==''){
             messageBox("<?php  echo ENTER_VALID_EMPLOYEE_INFO?>");
             document.AddEmployeeLeaveAuthorizer.employeeCode.focus();
             return false;
         }
         
         if(document.AddEmployeeLeaveAuthorizer.firstEmployee.value==''){
             messageBox("<?php  echo SELECT_FIRST_AUTHORIZER?>");
             document.AddEmployeeLeaveAuthorizer.firstEmployee.focus();
             return false;
         }
         
         secondEmployee=0;
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
             if(document.AddEmployeeLeaveAuthorizer.secondEmployee.value==''){
                 messageBox("<?php  echo SELECT_SECOND_AUTHORIZER?>");
                 document.AddEmployeeLeaveAuthorizer.secondEmployee.focus();
                 return false;
             }
             /* if(document.AddEmployeeLeaveAuthorizer.firstEmployee.value==document.AddEmployeeLeaveAuthorizer.secondEmployee.value){
                  messageBox("<?php  echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION?>");
                  document.AddEmployeeLeaveAuthorizer.secondEmployee.focus();
                  return false;
                }
             */
            secondEmployee= document.AddEmployeeLeaveAuthorizer.secondEmployee.value
         }
         
         if(document.AddEmployeeLeaveAuthorizer.leaveType.value==''){
             messageBox("<?php  echo SELECT_LEAVE_TYPE?>");
             document.AddEmployeeLeaveAuthorizer.leaveType.focus();
             return false;
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId     : document.AddEmployeeLeaveAuthorizer.employeeId.value, 
                 firstEmployee  : document.AddEmployeeLeaveAuthorizer.firstEmployee.value, 
                 secondEmployee : secondEmployee,
                 leaveType      : document.AddEmployeeLeaveAuthorizer.leaveType.value
                 
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
                             hiddenFloatingDiv('AddEmployeeLeaveAuthorizer');
                             //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             refereshLeaveAuthorizerList();
                             return false;
                         }
                     }
                     else if("<?php echo ENTER_VALID_EMPLOYEE_INFO;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo ENTER_VALID_EMPLOYEE_INFO?>");
                         document.AddEmployeeLeaveAuthorizer.employeeCode.focus();
                     }
                     else if("<?php echo SELECT_FIRST_AUTHORIZER;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_FIRST_AUTHORIZER?>");
                         document.AddEmployeeLeaveAuthorizer.firstEmployee.focus();
                     }
                     else if("<?php echo SELECT_SECOND_AUTHORIZER;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_SECOND_AUTHORIZER?>");
                         document.AddEmployeeLeaveAuthorizer.secondEmployee.focus();
                     }
                     else if("<?php echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION?>");
                         document.AddEmployeeLeaveAuthorizer.secondEmployee.focus();
                     }
                     else if("<?php echo SELECT_LEAVE_TYPE;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_LEAVE_TYPE?>");
                         document.AddEmployeeLeaveAuthorizer.leaveType.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        //document.AddEmployeeLeaveAuthorizer.employeeCode.focus();
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
function deleteEmployeeLeaveAuthorizer(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitDelete.php';
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
                     //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     refereshLeaveAuthorizerList();
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddEmployeeLeaveAuthorizer" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function blankValues() {
   document.AddEmployeeLeaveAuthorizer.employeeCode.value=''
   document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>";
   document.AddEmployeeLeaveAuthorizer.reset();
   document.AddEmployeeLeaveAuthorizer.firstEmployee.options.length=1;
   if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
     document.AddEmployeeLeaveAuthorizer.secondEmployee.options.length=1;
   }
   document.AddEmployeeLeaveAuthorizer.leaveType.options.length=1;
   document.AddEmployeeLeaveAuthorizer.employeeCode.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editEmployeeLeaveAuthorizer() {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitEdit.php';
         
         if(document.EditEmployeeLeaveAuthorizer.firstEmployee.value==''){
             messageBox("<?php  echo SELECT_FIRST_AUTHORIZER?>");
             document.EditEmployeeLeaveAuthorizer.firstEmployee.focus();
             return false;
         }
         
         secondEmployee=0;
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
             if(document.EditEmployeeLeaveAuthorizer.secondEmployee.value==''){
                messageBox("<?php  echo SELECT_SECOND_AUTHORIZER?>");
                document.EditEmployeeLeaveAuthorizer.secondEmployee.focus();
                return false;
             }
           /*  if(document.EditEmployeeLeaveAuthorizer.firstEmployee.value==document.EditEmployeeLeaveAuthorizer.secondEmployee.value){
                messageBox("<?php  echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION?>");
                document.EditEmployeeLeaveAuthorizer.secondEmployee.focus();
                return false;
             }
           */  
            secondEmployee=document.EditEmployeeLeaveAuthorizer.secondEmployee.value;
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                 mappingId      : document.EditEmployeeLeaveAuthorizer.mappingId.value,
                 firstEmployee  : document.EditEmployeeLeaveAuthorizer.firstEmployee.value, 
                 secondEmployee : secondEmployee,
                 leaveType      : document.EditEmployeeLeaveAuthorizer.leaveType.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditEmployeeLeaveAuthorizer');
                         //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         refereshLeaveAuthorizerList();
                         return false;
                     }
                     else if("<?php echo ENTER_VALID_EMPLOYEE_INFO;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo ENTER_VALID_EMPLOYEE_INFO?>");
                         document.EditEmployeeLeaveAuthorizer.employeeCode.focus();
                     }
                     else if("<?php echo SELECT_FIRST_AUTHORIZER;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_FIRST_AUTHORIZER?>");
                         document.EditEmployeeLeaveAuthorizer.firstEmployee.focus();
                     }
                     else if("<?php echo SELECT_SECOND_AUTHORIZER;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_SECOND_AUTHORIZER?>");
                         document.EditEmployeeLeaveAuthorizer.secondEmployee.focus();
                     }
                     else if("<?php echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION?>");
                         document.EditEmployeeLeaveAuthorizer.secondEmployee.focus();
                     }
                     else if("<?php echo SELECT_LEAVE_TYPE;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_LEAVE_TYPE?>");
                         document.EditEmployeeLeaveAuthorizer.leaveType.focus();
                     }
                    else {
                        messageBox(trim(transport.responseText)); 
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditEmployeeLeaveAuthorizer" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
       
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxGetValues.php';
         
         document.EditEmployeeLeaveAuthorizer.employeeCode.value  = '';
         document.getElementById('employeeName2').innerHTML  = '';
         document.EditEmployeeLeaveAuthorizer.firstEmployee.options.length=1;
         
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
           document.EditEmployeeLeaveAuthorizer.secondEmployee.options.length=1;
         }
         document.EditEmployeeLeaveAuthorizer.leaveType.options.length=1;
         
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
                        messageBox("<?php echo EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                     }
                    else{
                         displayWindow(dv,w,h); 
                         var j = eval('('+trim(transport.responseText)+')');
                         getEmployeeAuthorizer(j.employeeId,2);
                         getEmployeeLeaveTypes(j.employeeId,2);
                         document.EditEmployeeLeaveAuthorizer.firstEmployee.value  = j.firstApprovingEmployeeId;
                         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                           document.EditEmployeeLeaveAuthorizer.secondEmployee.value = j.secondApprovingEmployeeId;
                         }
                         document.EditEmployeeLeaveAuthorizer.leaveType.value      = j.leaveTypeId;
                         document.EditEmployeeLeaveAuthorizer.employeeCode.value   = j.employeeCode;
                         document.getElementById('employeeName2').innerHTML        = j.employeeName;
                         document.EditEmployeeLeaveAuthorizer.mappingId.value      = j.approvingId;
                         document.EditEmployeeLeaveAuthorizer.leaveSet.focus();
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeCode(val) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxGetEmployeeCode.php';
         document.AddEmployeeLeaveAuthorizer.employeeId.value='';
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
                        document.AddEmployeeLeaveAuthorizer.employeeId.value='';
                        document.getElementById('employeeName').innerHTML="<?php echo NOT_APPLICABLE_STRING;?>"; 
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        //fetchPreviousRecords(j.employeeId);
                        document.AddEmployeeLeaveAuthorizer.employeeId.value  = j.employeeId;
                        getEmployeeAuthorizer(j.employeeId,1);
                        getEmployeeLeaveTypes(j.employeeId,1);
                        document.getElementById('employeeName').innerHTML     = j.employeeName;
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeAuthorizer(val,mode) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxGetEmployeeAuthorizer.php';
         if(mode==1){
             var firstEmp=document.AddEmployeeLeaveAuthorizer.firstEmployee;
             if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
               var secondEmp=document.AddEmployeeLeaveAuthorizer.secondEmployee;
             }
         }
         else{
             var firstEmp=document.EditEmployeeLeaveAuthorizer.firstEmployee;
             if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                var secondEmp=document.EditEmployeeLeaveAuthorizer.secondEmployee;
             }
         }
         
         firstEmp.options.length=1;
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
           secondEmp.options.length=1;
         }
         
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
                        
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        var len=j.length;
                        for(var i=0;i<len;i++){
                           addOption(firstEmp,j[i].employeeId,j[i].employeeName);
                           if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                             addOption(secondEmp,j[i].employeeId,j[i].employeeName);
                           }
                        }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeLeaveTypes(val,mode) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetEmployeeLeaveTypes.php';
         if(mode==1){
             var leaveType=document.AddEmployeeLeaveAuthorizer.leaveType;
         }
         else{
             var leaveType=document.EditEmployeeLeaveAuthorizer.leaveType;
         }
         
         leaveType.options.length=1;
         
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
                        
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        var len=j.length;
                        for(var i=0;i<len;i++){
                           addOption(leaveType,j[i].leaveTypeId,j[i].leaveTypeName);
                        }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print FeedBack Grades report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/listEmployeeLeaveAuthorizerPrint.php?'+qstr;
    window.open(path,"EmployeeLeaveAuthorizerReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='listEmployeeLeaveAuthorizerCSV.php?'+qstr;
}

window.onload=function(){
   refereshLeaveAuthorizerList();
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeLeaveAuthorizer/listEmployeeLeaveAuthorizerContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listEmployeeLeaveAuthorizerAdv.php $ 
?>