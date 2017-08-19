 <?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Leave ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Leave/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Leave Type Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="4%"','',false),
                               new Array('leaveTypeName','Leave Type Name','width="30%"','',true), 
                               new Array('carryForward','Carry Forward','width="20%"','align="center"',true),  
                               new Array('reimbursed','Reimbursement','width="20%"','align="center"',true),  
                               new Array('isActive','Active','width="20%"','align="center"',true),
                               new Array('action','Action','width="7%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Leave/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddLeave';   
editFormName   = 'EditLeave';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteLeave';
divResultName  = 'results';
page=1; //default page
sortField = 'leaveTypeName';
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
// Created on : (10.7.2008)
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
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) 
{
    
   
    var fieldsArray = new Array(new Array("leaveName","<?php echo ENTER_LEAVE_NAME; ?>"));
    

    var len = fieldsArray.length;
    for(i=0;i<len;i++) 
	{
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) 
		{
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else 
		    {
            //unsetAlertStyle(fieldsArray[i][0]);
            /*if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='leaveName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo LEAVE_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
			}
           /*             
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           */ 
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        
     
    }
    if(act=='Add')
	{
        addLeave();
        return false;
    }
    else if(act=='Edit') 
	{
        editLeave();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Leave
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addLeave() {
         url = '<?php echo HTTP_LIB_PATH;?>/Leave/ajaxInitAdd.php';
		 var isActive=document.AddLeave.isActive[0].checked==true?1:0;
         var carryForward=document.AddLeave.carryForward[0].checked==true?1:0;
         var reimbursed=document.AddLeave.reimbursed[0].checked==true?1:0;                                                                              
                                                                              
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
			 leaveName: (document.AddLeave.leaveName.value), 
             carryForward: carryForward,
             reimbursed: reimbursed,    
             isActive:isActive
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
                             hiddenFloatingDiv('AddLeave');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo LEAVE_TYPE_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo LEAVE_TYPE_EXIST ;?>"); 
                           document.AddLeave.leaveName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A Leave
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteLeave(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Leave/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveTypeId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddLeave" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddLeave.leaveName.value = '';
   document.AddLeave.isActive[0].checked=true;
   document.AddLeave.carryForward[1].checked=true;
   document.AddLeave.reimbursed[1].checked=true;   
   document.AddLeave.leaveName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Leave
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editLeave() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Leave/ajaxInitEdit.php';
		 
		 var isActive=document.EditLeave.isActive[0].checked==true?1:0;
         var carryForward=document.EditLeave.carryForward[0].checked==true?1:0;
         var reimbursed=document.EditLeave.reimbursed[0].checked==true?1:0;   

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveTypeName: (document.EditLeave.leaveName.value),
             leaveTypeId: (document.EditLeave.leaveTypeId.value), 
             carryForward: carryForward,
             reimbursed:   reimbursed,
             isActive:isActive},
             onCreate: function() {
                 showWaitDialog(true);
             },
			 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditLeave');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo LEAVE_TYPE_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo LEAVE_TYPE_EXIST ;?>"); 
                           document.EditLeave.leaveName.focus();
                    }
                    
                     else {
                        messageBox(trim(transport.responseText));
                        hiddenFloatingDiv('EditLeave');
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeave" DIV
//
//Author : Dipanjan Bhattacharjee 
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Leave/ajaxGetValues.php';
         
         document.EditLeave.reset();
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveTypeId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditLeave');
                        messageBox("<?php echo LEAVE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
				   document.EditLeave.leaveTypeId.value = j.leaveTypeId;
                   document.EditLeave.leaveName.value = j.leaveTypeName;
                   
				   if(j.isActive=="1") {
					 document.EditLeave.isActive[0].checked=true;
				   }
				   else if(j.isActive =="0") {
					 document.EditLeave.isActive[1].checked=true; 
                   }
                   
                   if(j.carryForward=="1") {
                     document.EditLeave.carryForward[0].checked=true;
                   }
                   else if(j.carryForward =="1") {
                     document.EditLeave.carryForward[1].checked=true; 
                   }
                   if(j.reimbursed=="1") {
                     document.EditLeave.reimbursed[0].checked=true;
                   }
                   else if(j.reimbursed =="1") {
                     document.EditLeave.reimbursed[1].checked=true; 
                   }
                   document.EditLeave.leaveName.focus();
             },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

/* function to print nuilding report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/leaveReportPrint.php?'+qstr;
    window.open(path,"LeaveReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='leaveReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Leave/listLeaveContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listLeave.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 5  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Interface
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 4  *****************
//User: Administrator Date: 4/06/09    Time: 11:26
//Updated in $/LeapCC/Interface
//Corrected bugs----
//bug ids--Leap bugs2.doc(10 to 15)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/12/08   Time: 12:02
//Updated in $/LeapCC/Interface
//Corrected Bugs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:57p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:38a
//Updated in $/Leap/Source/Interface
//Added functionality for Leave report print
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/20/08    Time: 6:19p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 6:54p
//Updated in $/Leap/Source/Interface
//Created Leave Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:27p
//Created in $/Leap/Source/Interface
//Initial checkin
?>