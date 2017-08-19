<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF TEMPORARY EMPLOYEE ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TemporaryEmployee');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/HostelVisitor/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Temporary Employee Detail </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
new Array('tempEmployeeName','Safaiwala','width="200"','',true) ,
new Array('address','Address','width="200"','',true),
new Array('contactNo','Contact No.','width="150"','align=right',true),
new Array('dateOfJoining','Date of Joining','width="150"','align="center"',true),
new Array('status','Status','width="100"','align="left"',true),
new Array('designationName','Designation','width="150"','',true),
new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeTemp/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTempEmployee';
editFormName   = 'EditTempEmployee';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTempEmployee';
divResultName  = 'results';
page=1; //default page
sortField = 'tempEmployeeName';
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
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {


    var fieldsArray = new Array(new Array("tempEmployeeName","<?php echo ENTER_TEMP_EMPLOYEE_NAME ?>"),
    new Array("address","<?php echo ENTER_EMPLOYEE_ADDRESS ?>"),
    new Array("contactNo","<?php echo ENTER_CONTACT_NUMBER ?>"),
    new Array("status","<?php echo SELECT_STATUS ?>"),
    new Array("designationName","<?php echo SELECT_DESIGNATION ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
        }
        else {
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='tempEmployeeName' ) {
                messageBox("<?php echo EMPLOYEE_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
            }
            if(fieldsArray[i][0]=="contactNo"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format
                 messageBox("<?php echo VALID_CONTACT_NO ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;
              }
            }
            if(fieldsArray[i][0]=='address' && !isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value"))," #,/\\'.\n") ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                //alert(trim(eval("frm."+(fieldsArray[i][0])+".value")));
                alert("<?php echo ENTER_ALPHABETS_NUMERIC1; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
     }
    if(act=='Add') {
        if(!dateDifference(document.AddTempEmployee.joiningDate1.value,cdate,'-')){
           messageBox("<?php echo FUTURE_DATE_VALIDATION; ?>");
           document.AddTempEmployee.joiningDate1.focus();
           return false;
        }
        addTempEmployee();
        return false;
    }
    else if(act=='Edit') {
        if(!dateDifference(document.EditTempEmployee.joiningDate2.value,cdate,'-')){
           messageBox("<?php echo FUTURE_DATE_VALIDATION; ?>");
           document.EditTempEmployee.joiningDate2.focus();
           return false;
        }
        editTempEmployee();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD AN EMPLOYEE
//
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTempEmployee() {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeTemp/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {  tempEmployeeName:          (document.AddTempEmployee.tempEmployeeName.value),
                            address:                   (document.AddTempEmployee.address.value),
                            contactNo:                 (document.AddTempEmployee.contactNo.value),
                            dateOfJoining:             (document.AddTempEmployee.joiningDate1.value),
                            status:                    (document.AddTempEmployee.status.value),
                            designationName:           (document.AddTempEmployee.designationName.value)
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
                             hiddenFloatingDiv('AddTempEmployee');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                         else {
                             messageBox(trim(transport.responseText));
                         }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE AN EMPLOYEE
//  id=tempEmployeeId
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTempEmployee(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeTemp/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {tempEmployeeId: id},
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addTempEmployee" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.getElementById('tempEmployeeId').value='';
   document.getElementById('divHeaderId').innerHTML='&nbsp; Add Temporary Employee';
   document.AddTempEmployee.reset();
   document.AddTempEmployee.tempEmployeeName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TEMPORARY EMPLOYEE
//
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTempEmployee() {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeTemp/ajaxInitEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                            tempEmployeeId:            (document.EditTempEmployee.tempEmployeeId.value),
                            tempEmployeeName:          (document.EditTempEmployee.tempEmployeeName.value),
                            address:                   (document.EditTempEmployee.address.value),
                            contactNo:                 (document.EditTempEmployee.contactNo.value),
                            dateOfJoining:             (document.EditTempEmployee.joiningDate2.value),
                            status:                    (document.EditTempEmployee.status.value),
                            designationName:           (document.EditTempEmployee.designationName.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditTempEmployee');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo EMPLOYEE_NAME_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo EMPLOYEE_NAME_EXIST ;?>");
                       document.EditTempEmployee.tempEmployeeName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditTempEmployee" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (29.4.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeTemp/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {tempEmployeeId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditTempEmployee');
                        messageBox("<?php echo EMPLOYEE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');

                   document.EditTempEmployee.tempEmployeeName.value = j.tempEmployeeName;
                   document.EditTempEmployee.address.value = j.address;
                   document.EditTempEmployee.contactNo.value = j.contactNo;
                   document.EditTempEmployee.joiningDate2.value = j.dateOfJoining;
                   document.EditTempEmployee.status.value = j.status;
                   document.EditTempEmployee.designationName.value = j.tempDesignationId;
                   document.EditTempEmployee.tempEmployeeId.value = j.tempEmployeeId;
                   document.EditTempEmployee.tempEmployeeName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print hostel visitor report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/employeeTempReportPrint.php?'+qstr;
    try{
    window.open(path,"EmployeeTempReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
    }
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayEmployeeTempCSV.php?'+qstr;
	window.location = path;
}
function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){
  var form = document.AddTempEmployee;
 }
 else{
     var form = document.EditTempEmployee;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeTemp/listTempEmployeeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
