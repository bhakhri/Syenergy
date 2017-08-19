<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DEPARTMENT ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (20.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DepartmentMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Department Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), new Array('departmentName','Department Name','"width=40%"','',true) ,
//new Array('employeeCount','Employees','width="20%"','align="right"',true), new Array('abbr','Abbr.','width="30%"','',true), new Array('action','Action','width="2%"','align="center"',false));

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), new Array('departmentName','Department Name','"width=30%"','',true) ,
new Array('employeeCount','Employees','width="10%"','align="right"',true), new Array('abbr','Abbr.','width="25%"','',true),
new Array('description','Description','width="55%"','',true), new Array('action','Action','width="2%"','align="center"',false));


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Department/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDepartment';   
editFormName   = 'EditDepartment';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDepartment';
divResultName  = 'results';
page=1; //default page
sortField = 'departmentName';
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
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("departmentName","<?php echo ENTER_DEPARTMENT_NAME; ?>"),
    new Array("abbr","<?php echo ENTER_ABBREVIATION; ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='departmentName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo DEPARTMENT_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            else if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }


            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addDepartment();
        return false;
    }
    else if(act=='Edit') {
        editDepartment();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addDepartment() {
         url = '<?php echo HTTP_LIB_PATH;?>/Department/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
           //  parameters: {	departmentName: (document.addDepartment.departmentName.value), 
		  //				abbr: (document.addDepartment.abbr.value)},
              parameters: {    departmentName: (document.addDepartment.departmentName.value), 
                               abbr: (document.addDepartment.abbr.value),
                               description: (document.addDepartment.description.value)
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
                             hiddenFloatingDiv('AddDepartment');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }

                     } 
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo DEPARTMENT_NAME_EXIST ?>"){
							document.addDepartment.departmentName.value='';
							document.addDepartment.departmentName.focus();	
						}
						else {
							document.addDepartment.abbr.value='';
							document.addDepartment.abbr.focus();
						}
					 }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE DEPARTMENT
//  id=departmentId
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDepartment(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Department/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {departmentId: id},
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
//THIS FUNCTION IS USED TO CLEAN DEPARTMENT VALUE
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.addDepartment.departmentName.value = '';
   document.addDepartment.abbr.value = '';
   document.addDepartment.description.value = '';
   document.addDepartment.departmentName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editDepartment() {
         url = '<?php echo HTTP_LIB_PATH;?>/Department/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
              parameters: {	departmentId: (document.editDepartment.departmentId.value),
							departmentName: (document.editDepartment.departmentName.value), 
							abbr: (document.editDepartment.abbr.value),
                            description: (document.editDepartment.description.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditDepartment');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo DEPARTMENT_NAME_EXIST ?>"){
							document.editDepartment.departmentName.value='';
							document.editDepartment.departmentName.focus();	
						}	
						else {
							document.editDepartment.abbr.value='';
							document.editDepartment.abbr.focus();
						}
                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Department/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {departmentId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditDepartment');
                        messageBox("<?php echo DEPARTMENT_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.editDepartment.departmentName.value = j.departmentName;
                   document.editDepartment.abbr.value = j.abbr;
                   document.editDepartment.departmentId.value = j.departmentId;
                   document.editDepartment.description.value = j.description;
                   document.editDepartment.departmentName.focus();
                  

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print city report*/
function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/displayDepartmentReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayDepartmentReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayDepartmentCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Department/listDepartmentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

	<SCRIPT LANGUAGE="JavaScript">
	
			sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

	</SCRIPT>
</body>
</html>

<?php 
// $History: listDepartment.php $ 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface
//Added Role Permission Variables
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:49
//Updated in $/LeapCC/Interface
//Bug fixing.
//bug ids---
//00000256,00000257,00000259,00000261,00000263,00000264.
//00000266,00000269,00000262
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/28/09    Time: 11:07a
//Updated in $/LeapCC/Interface
//put sendReq function 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/19/09    Time: 6:16p
//Updated in $/LeapCC/Interface
//show print during search
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:46p
//Created in $/Leap/Source/Interface
//new file for department
//
?>