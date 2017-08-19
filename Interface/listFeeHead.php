<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in "Fee Head" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeads');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeHead/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Head </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('headName',' Name','width="25%"','',true),
                               new Array('headAbbr','Abbr.','width="10%"','',true),
                               new Array('isRefundable','Refundable Security','width="18%" align="center"','align="center"',true), 
                               new Array('isConsessionable','Concessionable','width="14%" align="center"','align="center"',true),  
                               new Array('isVariable','Miscellaneous','width="12%" align="center"','align="center"',true),
                               /*
                               new Array('parentHead','Parent Head','width="12%"','',false),
                               new Array('applicableToAll','Applicable to all<br><span style="font-size:9px">(Categories i.e. Gen/SC/ST)</span>','width="15%" align="center"','align="center"',true),
                               new Array('transportHead','Transport Head','width="14%" align="center"','align="center"',true),
                               new Array('hostelHead','Hostel Head','width="12%" align="center"','align="center"',true),
							   new Array('miscHead','Misc. Head','width="12%" align="center"','align="center"',true),
                               new Array('isConsessionable','Concessionable','width="13%" align="center"','align="center"',true), 
                               */
							   new Array('sortingOrder','Display Order','width="12%" align="right"','align="right"',true), 
                               new Array('action','Action','width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeHead';   
editFormName   = 'EditFeeHead';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteFeeHead';
divResultName  = 'results';
page=1; //default page
sortField = 'headName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 
function validateAddForm(frm, act) {
   
    var fieldsArray = new Array(new Array("headName","<?php echo ENTER_FEEHEAD_NAME;?>"),
                                new Array("headAbbr","<?php echo ENTER_FEEHEAD_ABBR;?>"),
                                new Array("sortOrder","<?php echo ENTER_FEEHEAD_ORDER;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
        /*if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='headName' ) {
                //winmessageBox ("Enter string",fieldsArray[i][0]);
                messageBox ("<?php echo FEEHEAD_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/              
            //unsetmessageBox Style(fieldsArray[i][0]);
            if(fieldsArray[i][0]=='sortOrder' ) {
               if(!isNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                  //winmessageBox ("Enter string",fieldsArray[i][0]);
                  messageBox ("Enter the numeric value");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }
               if(eval("frm."+(fieldsArray[i][0])+".value")<=0)  {
                  messageBox ("Display Order should not be zero ");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }
            }
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winmessageBox ("Enter string",fieldsArray[i][0]);
                messageBox ("<?php echo ENTER_ALPHABETS_NUMERIC;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetmessageBox Style(fieldsArray[i][0]);
            //}
        }
    }
    
    
    if(act=='Add') {
        addFeeHead();
        return false;
    }
    else if(act=='Edit') {
        /*if(document.editFeeHead.feeHeadId.value==document.editFeeHead.parentHeadId.value) {
          messageBox("<?php echo FEEHEAD_NOT_ITSELF; ?>");
          document.editFeeHead.parentHeadId.focus();    
          return false;
        } */
        editFeeHead();    
        return false;
    }
}

//This function adds form through ajax 


function addFeeHead() {
     var url = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxInitAdd.php';
     
     var r=0;
     var v=0;
     var c=0;
     if(document.addFeeHead.isRefundable[0].checked==true) {
       r = 1;
     }
     if(document.addFeeHead.isVariable[0].checked==true) {
       v = 1;
     } 
     if(document.addFeeHead.isConsessionable[0].checked==true) {
       c = 1;
     } 
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {headName: trim(document.addFeeHead.headName.value), 
                      headAbbr: trim(document.addFeeHead.headAbbr.value),
                      isConsessionable: c,
                      isRefundable: r,
                      isVariable: v,
				      sortOrder: trim(document.addFeeHead.sortOrder.value)},
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
                         hiddenFloatingDiv('AddFeeHead');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText)); 
                    if("<?php echo FEEHEAD_NAME_EXIST;?>" == trim(transport.responseText)) {  
                      document.addFeeHead.headName.focus(); 
                    }
                    else if("<?php echo FEEHEAD_ABBR_EXIST;?>" == trim(transport.responseText)) {  
                      document.addFeeHead.headAbbr.focus(); 
                    }
                    else if("<?php echo FEEHEAD_DISPLAY_ORDER_EXIST;?>" == trim(transport.responseText)) {  
                      document.addFeeHead.sortOrder.focus(); 
                    }
                 }
           
         },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function getHeadName(){
     groupUrl = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxInitFeeHeadName.php';
     new Ajax.Request(groupUrl,
     {
         method:'post',   
         asynchronous :(false),
         parameters: {},
         onCreate: function(transport){
              showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                j = trim(transport.responseText).evalJSON();     
                len=j.info.length;
                document.addFeeHead.parentHeadId.length=null;
                addOption(document.addFeeHead.parentHeadId,'', 'Select');
                for(i=0;i<len;i++) { 
                   addOption(document.addFeeHead.parentHeadId, j.info[i].feeHeadId, j.info[i].headName);
                }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}

function blankValues() {
   //document.addFeeHead.reset(); 
   document.addFeeHead.headName.value = '';
   document.addFeeHead.headAbbr.value = '';
   document.addFeeHead.isVariable[1].checked=true;
   document.addFeeHead.isRefundable[1].checked=true;
   document.addFeeHead.isConsessionable[1].checked=true;  
   document.addFeeHead.sortOrder.value = '';
   
   document.addFeeHead.headName.focus();
   getHeadName();
}

//This function edit form through ajax 

function editFeeHead() {
     var url = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxInitEdit.php';
     
     var r=0;
     var v=0;
     var c=0;
     var abbr='';   
     if(document.editFeeHead.isRefundable[0].checked==true) {
       r = 1;
     }
     if(document.editFeeHead.isVariable[0].checked==true) {
       v = 1;
     } 
     if(document.editFeeHead.isConsessionable[0].checked==true) {
       c = 1;
     }
     abbr = trim(document.editFeeHead.headAbbr.value); 
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {feeHeadId: trim(document.editFeeHead.feeHeadId.value), 
                      headName: trim(document.editFeeHead.headName.value), 
                      headAbbr: abbr,
                      isConsessionable: c, 
                      isRefundable: r,
                      isVariable: v,
                      sortOrder: trim(document.editFeeHead.sortOrder.value)},
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                 messageBox(trim(transport.responseText));
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditFeeHead');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                     //location.reload();
                 }
                 else {
                    if("<?php echo FEEHEAD_NAME_EXIST;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.headName.focus(); 
                    }
                    else if("<?php echo FEEHEAD_ABBR_EXIST;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.headAbbr.focus(); 
                    }
                    else if("<?php echo FEEHEAD_DISPLAY_ORDER_EXIST;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.sortOrder.focus(); 
                    }
                    else if("<?php echo FEEHEAD_PARENT_RELATION;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.parentHeadId.focus(); 
                    }
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//This function calls delete function through ajax

function deleteFeeHead(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeHeadId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       return false;
                     }
                     else {
                       messageBox(trim(transport.responseText));
                     }
               
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}


function getEditHeadName(){
     groupUrl = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxInitFeeHeadName.php';
     new Ajax.Request(groupUrl,
     {
         method:'post',   
         asynchronous :(false),
         parameters: {},
         onCreate: function(transport){
              showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            j = trim(transport.responseText).evalJSON();     
            len=j.info.length;
            document.editFeeHead.parentHeadId.length=null;
            addOption(document.editFeeHead.parentHeadId,'', 'Select');
            for(i=0;i<len;i++) { 
                addOption(document.editFeeHead.parentHeadId, j.info[i].feeHeadId, j.info[i].headName);
            }
         },
      onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}


//This function populates values in edit form through ajax 

function populateValues(id) {
          url = '<?php echo HTTP_LIB_PATH;?>/FeeHead/ajaxGetValues.php';
          //blankValues();
          getEditHeadName();
          new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeHeadId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                hideWaitDialog(true);
                j = eval('('+transport.responseText+')');
                document.editFeeHead.feeHeadId.value = j.feeHeadId; 
                document.editFeeHead.headName.value = j.headName;
                document.editFeeHead.headAbbr.value = j.headAbbr;
                document.editFeeHead.isRefundable[1].checked=true;
                document.editFeeHead.isVariable[1].checked=true;
                document.editFeeHead.isConsessionable[1].checked=true; 
                if(j.isRefundable==1) {
                  document.editFeeHead.isRefundable[0].checked=true;
                }
                if(j.isVariable==1) {
                  document.editFeeHead.isVariable[0].checked=true;
                }
                if(j.isConsessionable==1) {
                  document.editFeeHead.isConsessionable[0].checked=true;
                }
			    document.editFeeHead.sortOrder.value = j.sortingOrder;
                document.editFeeHead.headName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/listFeeHeadReport.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"FeeHeadReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    path='<?php echo UI_HTTP_PATH;?>/listFeeHeadReportCSV.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location = path;
}

</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeHead/listFeeHeadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>
</body>
</html>
<?php 
//$History: listFeeHead.php $
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Interface
//updated with all the fees enhancements
//
//*****************  Version 16  *****************
//User: Parveen      Date: 10/21/09   Time: 11:14a
//Updated in $/LeapCC/Interface
//print & csv functionality added
//
//*****************  Version 15  *****************
//User: Parveen      Date: 8/21/09    Time: 12:28p
//Updated in $/LeapCC/Interface
//sorting order check updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Interface
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 12  *****************
//User: Parveen      Date: 7/30/09    Time: 10:23a
//Updated in $/LeapCC/Interface
//validation updated (edit/delete relation checks updated)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 7/24/09    Time: 1:50p
//Updated in $/LeapCC/Interface
//duplicate checks validation added
//
//*****************  Version 10  *****************
//User: Parveen      Date: 7/24/09    Time: 1:03p
//Updated in $/LeapCC/Interface
//parent checks udpated 
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/24/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//parent checks updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/24/09    Time: 12:35p
//Updated in $/LeapCC/Interface
//populateValue function update(parentHeadId condition added)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/22/09    Time: 12:56p
//Updated in $/LeapCC/Interface
//editFeeHead, populateValues function validation updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/22/09    Time: 12:34p
//Updated in $/LeapCC/Interface
//alignment & drop down value updated (parent head name) search condition
//udpated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/13/09    Time: 10:06a
//Updated in $/LeapCC/Interface
//alignment,sorting order & conditions updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/01/09    Time: 1:08p
//Updated in $/LeapCC/Interface
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/08   Time: 5:13p
//Updated in $/LeapCC/Interface
//print sorting order set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 17  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//Define Module, Access  Added
//
//*****************  Version 16  *****************
//User: Arvind       Date: 8/29/08    Time: 4:49p
//Updated in $/Leap/Source/Interface
//modified 
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/20/08    Time: 1:42p
//Updated in $/Leap/Source/Interface
//REPLACED VALIDATION MESSAGES WITH COMMON MESSAGES
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/07/08    Time: 3:28p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/06/08    Time: 6:21p
//Updated in $/Leap/Source/Interface
//modify the width of the table
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/05/08    Time: 6:06p
//Updated in $/Leap/Source/Interface
//modified getheadname()
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function 
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/29/08    Time: 4:30p
//Updated in $/Leap/Source/Interface
//added yes as deafult for checkboxes
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/29/08    Time: 4:20p
//Updated in $/Leap/Source/Interface
//no change
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/29/08    Time: 3:51p
//Updated in $/Leap/Source/Interface
//modified the Ajax table parameter headName
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/29/08    Time: 12:01p
//Updated in $/Leap/Source/Interface
//removed sorting in parenthead
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/18/08    Time: 2:24p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/18/08    Time: 1:13p
//Updated in $/Leap/Source/Interface
//corrected form name in editFeehead Function()
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/15/08    Time: 6:53p
//Updated in $/Leap/Source/Interface
//added a new funciton for parent head dropdown which callas ajax file 
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/11/08    Time: 3:59p
//Updated in $/Leap/Source/Interface
//modified the width of the table rows
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:14a
//Created in $/Leap/Source/Interface
//added a new module file


?>
