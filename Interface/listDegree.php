<?php
//-----------------------------------------------------------------------------------------
// THIS FILE SHOWS A LIST OF DEGREES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DegreeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Degree/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Degree Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                     new Array('degreeName','Degree Name','"width=200"','',true) , 
					 new Array('degreeCode','Degree Code','width="250"','',true), 
					 new Array('degreeAbbr','Abbr.','width="200"','',true) , 
					 new Array('studentId','Student Count','width="200"','align="right"',true), 
					 new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Degree/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDegree';   
editFormName   = 'EditDegree';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDegree';
divResultName  = 'results';
page=1; //default page
sortField = 'degreeName';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("degreeName","<?php echo ENTER_DEGREE_NAME; ?>"),
    new Array("degreeCode","<?php echo ENTER_DEGREE_CODE; ?>"),
    new Array("degreeAbbr","<?php echo ENTER_DEGREE_ABBR; ?>"));

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
            
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='degreeName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_DEGREE_NAME; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
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
     
    }
    if(act=='Add') {
        addDegree();
        return false;
    }
    else if(act=='Edit') {
        editDegree();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEGREE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addDegree() {
         url = '<?php echo HTTP_LIB_PATH;?>/Degree/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {degreeName: (trim(document.AddDegree.degreeName.value)), degreeCode: (trim(document.AddDegree.degreeCode.value)), degreeAbbr: (trim(document.AddDegree.degreeAbbr.value))},
             onCreate: function() {
                 showWaitDialog(false);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddDegree');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else if("<?php echo DEGREE_NAME_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo DEGREE_NAME_ALREADY_EXIST ;?>"); 
                         document.AddDegree.degreeName.focus();
                     } 
                     else if("<?php echo DEGREE_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo DEGREE_ALREADY_EXIST ;?>"); 
                         document.AddDegree.degreeCode.focus();
                     }
                     else if("<?php echo DEGREE_ABBR_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo DEGREE_ABBR_ALREADY_EXIST ;?>"); 
                         document.AddDegree.degreeAbbr.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DEGREE
//  id=degreeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDegree(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Degree/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {degreeId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddDegree" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddDegree.degreeCode.value = '';
   document.AddDegree.degreeName.value = '';
   document.AddDegree.degreeAbbr.value = '';
   document.AddDegree.degreeName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DEGREE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editDegree() {
         url = '<?php echo HTTP_LIB_PATH;?>/Degree/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {degreeId: (document.EditDegree.degreeId.value),degreeName: (trim(document.EditDegree.degreeName.value)), degreeCode: (trim(document.EditDegree.degreeCode.value)), degreeAbbr: (trim(document.EditDegree.degreeAbbr.value))},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditDegree');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else if("<?php echo DEGREE_NAME_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo DEGREE_NAME_ALREADY_EXIST ;?>"); 
                         document.EditDegree.degreeName.focus();
                     } 
                     else if("<?php echo DEGREE_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo DEGREE_ALREADY_EXIST ;?>"); 
                         document.EditDegree.degreeCode.focus();
                     }
                     else if("<?php echo DEGREE_ABBR_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo DEGREE_ABBR_ALREADY_EXIST ;?>"); 
                         document.EditDegree.degreeAbbr.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Degree/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {degreeId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditDegree');
                        messageBox("<?php echo DEGREE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.EditDegree.degreeCode.value = j.degreeCode;
                   document.EditDegree.degreeName.value = j.degreeName;
                   document.EditDegree.degreeAbbr.value = j.degreeAbbr;
                   document.EditDegree.degreeId.value = j.degreeId;
                   document.EditDegree.degreeName.focus();
                  

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print city report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/degreeReportPrint.php?'+qstr;
    window.open(path,"DegreeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='degreeReportCSV.php?'+qstr;
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Degree/listDegreeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listDegree.php $ 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 31/07/09   Time: 14:38
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---0000803,0000804
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 5  *****************
//User: Administrator Date: 29/05/09   Time: 11:43
//Updated in $/LeapCC/Interface
//Done bug fixing------ Issues [28-May-09]Build# cc0007
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 26/05/09   Time: 17:38
//Updated in $/LeapCC/Interface
//Corrected table column widths
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
//*****************  Version 14  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:22p
//Updated in $/Leap/Source/Interface
//Added functionality for quota report print
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Interface
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/20/08    Time: 2:10p
//Updated in $/Leap/Source/Interface
//Added standard messages
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:35p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/28/08    Time: 1:48p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//Added AjaxList Functinality
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:22p
//Updated in $/Leap/Source/Interface
//Adding AjaxEnabled Delete functionality
//Added deleteDegree Function
//*****************Solved the problem :**************
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/18/08    Time: 2:38p
//Updated in $/Leap/Source/Interface
//Removing errors done
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Interface
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Interface
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:05a
//Created in $/Leap/Source/Interface
//Initial Checkin
?>