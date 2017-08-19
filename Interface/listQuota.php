<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF QUOTAS ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','QuotaMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Quota/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Quota Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
new Array('quotaName','Quota Name','width="150"','',true) , 
new Array('quotaAbbr','Quota Abbr.','width="150"','',true),  
new Array('parentQuota','Parent Quota','width="150"','',true) , 
new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddQuota';   
editFormName   = 'EditQuota';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteQuota';
divResultName  = 'results';
page=1; //default page
sortField = 'parentQuota';
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
	 populateParentQuota("Edit") ; //it will filup parentQuota dropdown  in "EditQuota" Div
    populateValues(id);   
    
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("quotaName","<?php echo ENTER_QUOTA_NAME;?>"),
    new Array("quotaAbbr","<?php echo ENTER_QUOTA_ABBR;?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
             messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='quotaName' ) {
                messageBox("<?php echo QUOTA_NAME_LENGTH;?>" );
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
				/*
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("Special characters are not allowed");
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
        addQuota();
        return false;
    }
    else if(act=='Edit') {
        editQuota();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW QUOTA
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addQuota() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {quotaName: (document.AddQuota.quotaName.value), 
             quotaAbbr: (document.AddQuota.quotaAbbr.value),
             parentQuotaId:(document.AddQuota.parentQuota.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         //now populate "parentQuota" dropdown with the new values 
                         populateParentQuota("Add") ; //it will filup parentQuota dropdown  in "AddQuota" Div 
                         
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddQuota');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                    else if("<?php echo QUOTA_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo QUOTA_NAME_ALREADY_EXIST;?>"); 
                           document.AddQuota.quotaName.focus();
                    }
                    else if("<?php echo QUOTA_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo QUOTA_ALREADY_EXIST;?>"); 
                           document.AddQuota.quotaAbbr.focus();
                    }
                    else {
                        messageBox(trim(transport.responseText)); 
                    }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A QUOTA
//  id=quotaId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteQuota(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {quotaId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addQuota" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   
   populateParentQuota("Add") ; //it will filup parentQuota dropdown  in "AddQuota" Div
    
   document.AddQuota.quotaName.value = '';
   document.AddQuota.quotaAbbr.value = '';
   document.AddQuota.parentQuota.selectedIndex=0;
   document.AddQuota.quotaName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A QUOTA
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function editQuota() {
         url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxInitEdit.php';
         
         //solving the problem of parent-child hierarchy(partially) 
         if(document.EditQuota.parentQuota.value == document.EditQuota.quotaId.value){
             alert("A quota can not be parent of itself");
             return false;
         }  
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {quotaId: (document.EditQuota.quotaId.value), 
             quotaName: (document.EditQuota.quotaName.value), 
             quotaAbbr: (document.EditQuota.quotaAbbr.value),
             parentQuotaId:(document.EditQuota.parentQuota.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditQuota');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo QUOTA_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo QUOTA_NAME_ALREADY_EXIST;?>"); 
                           document.EditQuota.quotaName.focus();
                    } 
                    else if("<?php echo QUOTA_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo QUOTA_ALREADY_EXIST ;?>"); 
                           document.EditQuota.quotaAbbr.focus();
                         }
                    else{
                        messageBox(trim(transport.responseText));
                    }       
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editQuota" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.   
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {quotaId: id},
				 asynchronous:false,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditQuota');
                        messageBox("<?php echo QUOTA_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');

                   document.EditQuota.quotaName.value = j.quotaName;
                   document.EditQuota.quotaAbbr.value = j.quotaAbbr;
                   document.EditQuota.parentQuota.value = j.parentQuotaId;
                   document.EditQuota.quotaName.focus();
                   
                   document.EditQuota.quotaId.value = j.quotaId;
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "parentQuota" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (17.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateParentQuota(mode) {
         url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxGetParentQuota.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { },
				asynchronous:false,
             onCreate: function() {
                 //showWaitDialog(true);
             },
             onSuccess: function(transport){
                    //hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    var i=0;
                    var objOption = new Option("SELECT","0");
                    if(mode=="Add"){
                     document.AddQuota.parentQuota.options.length=0;
                     document.AddQuota.parentQuota.options.add(objOption);
                    }
                   else{
                       document.EditQuota.parentQuota.options.length=0;
                       document.EditQuota.parentQuota.options.add(objOption);
                    } 

                    for(i=0 ; i < j.length ; i++){
                      var objOption = new Option(j[ i ].quotaName , j[ i ].quotaId);
                      if(mode=="Add"){
                       document.AddQuota.parentQuota.options.add(objOption);    
                      }
                     else{
                       document.EditQuota.parentQuota.options.add(objOption);      
                     } 
                    }
                  if(mode=="Add"){
                     document.AddQuota.parentQuota.options.selectedIndex=0; //for edit it will depend on database value 
                    }
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


/* function to print quota report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/quotaReportPrint.php?'+qstr;
    window.open(path,"QuotaReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='quotaReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Quota/listQuotaContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listQuota.php $ 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 21/10/09   Time: 11:42
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00001796,00001794,00001786,00001630
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 1:01p
//Updated in $/LeapCC/Interface
//resolved issue 1622
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 18/06/09   Time: 15:24
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---00000113,00000114,00000115,00000141,00000142,
//00000143,00000144,00000146,00000147
//
//*****************  Version 3  *****************
//User: Administrator Date: 12/06/09   Time: 12:55
//Updated in $/LeapCC/Interface
//Corrected quota master
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
//*****************  Version 18  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:49p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:12p
//Updated in $/Leap/Source/Interface
//Added functionality for quota report print
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:39p
//Updated in $/Leap/Source/Interface
//Added standard messages
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/08/08    Time: 1:14p
//Updated in $/Leap/Source/Interface
//Modified according to Task: 6 
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/01/08    Time: 6:34p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 7/18/08    Time: 11:41a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/17/08    Time: 4:57p
//Updated in $/Leap/Source/Interface
//Added parent quota field
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 7/02/08    Time: 11:44a
//Updated in $/Leap/Source/Interface
//Removed State Field from the quota master
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:10p
//Updated in $/Leap/Source/Interface
//Removed validation(min characters check) from quotaAbbr field
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/30/08    Time: 7:31p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:40p
//Updated in $/Leap/Source/Interface
//Added AjaxListing Functionality
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:31p
//Updated in $/Leap/Source/Interface
//****Solved The Problem*********
//Open 2 browsers opening quota Masters page. On one page, delete a
//quota. On the second page, the deleted quota is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted quota in the second page which was left
//untouched. Provide the new quota Code and click Submit button.A blank
//popup is displayed. It should rather display "The quota you are trying
//to edit no longer exists".
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:53p
//Updated in $/Leap/Source/Interface
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//Added deleteQuota() Function 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/16/08    Time: 3:51p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:20a
//Updated in $/Leap/Source/Interface
//Modification Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 6:38p
//Updated in $/Leap/Source/Interface
//Adding Comments complete
?>
