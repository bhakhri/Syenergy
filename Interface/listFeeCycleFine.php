<?php 

//-------------------------------------------------------
//  THIS FILE CONTAINS	VALIDATIONS AND AJAX FUNCTION USED IN "FeeCycleFine" MODULE
//
// Author :Arvind Singh Rawat
// Created on : 1st - JULY - 2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleFines');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeCycleFine/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Cycle Fine Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('cycleName','Fee Cycle','width="15%"','',true), 
                               //new Array('headName','Fee Head','width="15%"','',true), 
                               new Array('fromDate','From','width="10%"','align="center"',true),
                               new Array('toDate','To','width="10%"','align="center"',true),
                               new Array('fineAmount','Fine Amount','width="10%"','align="right"',true),
                               new Array('fineType','Fine Type','width="10%"','align="center"',true),
                               new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeeCycleFine/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeCycleFine';   
editFormName   = 'EditFeeCycleFine';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteFeeCycleFine';
divResultName  = 'results';
page=1; //default page
sortField = 'cycleName';
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
        
    var fieldsArray = new Array(new Array("feeCycleId","<?php echo SELECT_FEECYCLE;?>"),
	                            //new Array("feeHeadId","<?php echo SELECT_FEEHEAD;?>"),
                                new Array("fromDate","<?php echo ENTER_FEECYCLE_FROM_DATE;?>"),
                                new Array("toDate","<?php echo ENTER_FEECYCLE_TODATE;?>"),
	                            new Array("fineAmount","<?php echo ENTER_FEECYCLEFINE_AMOUNT;?>"),
	                            new Array("fineType","<?php echo SELECT_FEECYCLE_TYPE;?>"));
    
    var fieldsArray1 = new Array(new Array("feeCycleId","<?php echo SELECT_FEECYCLE;?>"),
                                // new Array("feeHeadId","<?php echo SELECT_FEEHEAD;?>"),
                                 new Array("fromDate1","<?php echo ENTER_FEECYCLE_FROM_DATE;?>"),
                                 new Array("toDate1","<?php echo ENTER_FEECYCLE_TODATE;?>"),
                                 new Array("fineAmount","<?php echo ENTER_FEECYCLEFINE_AMOUNT;?>"),
                                 new Array("fineType","<?php echo SELECT_FEECYCLE_TYPE;?>"));

    if(act=='Add') {
        var len = fieldsArray.length;
        for(i=0;i<len;i++) {
            if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
                messageBox (fieldsArray[i][1]);
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            else if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-')) {
               messageBox ("<?php echo DATE_VALIDATION;?>");
               eval("frm.fromDate.focus();");
               return false;
               break;
            } 
  	        else if(fieldsArray[i][0]=="fineAmount" && (!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")))){
                messageBox("<?php echo "Enter only decimal value";?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;                                
                break;
            }
            else if(fieldsArray[i][0]=="fineAmount" && parseFloat(eval("frm."+(fieldsArray[i][0])+".value"))<0){
                messageBox("<?php echo "Enter values greater than zero";?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;                                
                break;
            }
        }
    }
    else if(act=='Edit') {
        var len = fieldsArray1.length;
        for(i=0;i<len;i++) {
            if(isEmpty(eval("frm."+(fieldsArray1[i][0])+".value"))) {
                //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
                messageBox (fieldsArray1[i][1]);
                eval("frm."+(fieldsArray1[i][0])+".focus();");
                return false;
                break;
            }
            else if(!dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-')) {
               messageBox ("<?php echo DATE_VALIDATION;?>");
               eval("frm.fromDate1.focus();");
               return false;
               break;
            }
            else if(fieldsArray[i][0]=="fineAmount" && (!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")))){
                messageBox("<?php echo "Enter only decimal value";?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;                                
                break;
            }
            else if(fieldsArray[i][0]=="fineAmount" && parseFloat(eval("frm."+(fieldsArray[i][0])+".value"))<0){
                messageBox("<?php echo "Enter values greater than zero";?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;                                
                break;
            } 
        }
    }
    
    if(act=='Add') {
        addFeeCycleFine();
        return false;
    }
    else if(act=='Edit') {
        editFeeCycleFine();    
        return false;
    }
}

//This function adds form through ajax 


function addFeeCycleFine() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycleFine/ajaxInitAdd.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: (document.addFeeCycleFine.feeCycleId.value),fromDate: (document.addFeeCycleFine.fromDate.value),toDate: (document.addFeeCycleFine.toDate.value),fineAmount: (document.addFeeCycleFine.fineAmount.value),fineType: (document.addFeeCycleFine.fineType.value)},
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
                             hiddenFloatingDiv('AddFeeCycleFine');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
			  onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addFeeCycleFine.feeCycleId.value = '';
   //document.addFeeCycleFine.feeHeadId.value = '';
   document.addFeeCycleFine.fromDate.value = '';
   document.addFeeCycleFine.toDate.value = '';
    document.addFeeCycleFine.fineAmount.value = '';
   document.addFeeCycleFine.fineType.value = '';
   
   document.addFeeCycleFine.feeCycleId.focus();
}

//This function edit form through ajax 

function editFeeCycleFine() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycleFine/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleFineId: (document.editFeeCycleFine.feeCycleFineId.value), feeCycleId: (document.editFeeCycleFine.feeCycleId.value),fromDate: (document.editFeeCycleFine.fromDate1.value),toDate: (document.editFeeCycleFine.toDate1.value),fineAmount: (document.editFeeCycleFine.fineAmount.value),fineType: (document.editFeeCycleFine.fineType.value)},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
              else {
                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeeCycleFine');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                        else {
                         messageBox(trim(transport.responseText));
                     }
               }
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This function calls delete function through ajax

function deleteFeeCycleFine(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycleFine/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleFineId: id},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
              else {
                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                        else {
                         messageBox(trim(transport.responseText));
                     }
               }
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}


//This function populates values in edit form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycleFine/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleFineId: id},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                   
                  showWaitDialog(true);
               }
               else {
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                  
				   document.editFeeCycleFine.feeCycleFineId.value = j.feeCycleFineId;  
                   document.editFeeCycleFine.feeCycleId.value = j.feeCycleId;
                   document.editFeeCycleFine.feeCycleId.value = j.feeCycleId;
                   //document.editFeeCycleFine.feeHeadId.value = j.feeHeadId;
				   document.editFeeCycleFine.fromDate1.value = j.fromDate;
				   document.editFeeCycleFine.toDate1.value = j.toDate;
				   document.editFeeCycleFine.fineAmount.value = j.fineAmount;
				   document.editFeeCycleFine.fineType.value = j.fineType;
               }
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 function printReport() {  
     var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/listFeeCycleFinePrint.php?'+qstr;
    window.open(path,"DisplayFeeCycleFineList","status=1,menubar=1,scrollbars=1, width=900");
 }
 
 function printReportCSV() {  
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listFeeCycleFinePrintCSV.php?'+qstr;
    window.location=path;
 }
</script>

</head>
<body>
    <?php 
        require_once(TEMPLATES_PATH . "/header.php");
        require_once(TEMPLATES_PATH . "/FeeCycleFine/listFeeCycleFineContents.php");
        require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
    </SCRIPT>
</body>
</html>
<?php 

// $History: listFeeCycleFine.php $
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Interface
//updated with all the fees enhancements
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/11/09    Time: 3:54p
//Updated in $/LeapCC/Interface
//decimal value check updated (fine amount)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/10/09    Time: 10:21a
//Updated in $/LeapCC/Interface
//message update (fee Cyelce Fine) 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
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
//*****************  Version 10  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//Define Module, Access  Added
//
//*****************  Version 9  *****************
//User: Arvind       Date: 10/24/08   Time: 2:09p
//Updated in $/Leap/Source/Interface
//added print function
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/20/08    Time: 2:07p
//Updated in $/Leap/Source/Interface
//REPLACED VALIDATION MESSAGES WITH COMMON MESSAGES
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/07/08    Time: 3:28p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/01/08    Time: 6:07p
//Updated in $/Leap/Source/Interface
//added isint validation for fineamount
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function 
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/18/08    Time: 2:25p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/11/08    Time: 5:45p
//Updated in $/Leap/Source/Interface
//added validations for date fields
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/11/08    Time: 5:13p
//Updated in $/Leap/Source/Interface
//corrected the fieldNAme feeCycleId
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:14a
//Created in $/Leap/Source/Interface
//added a new module file

?>
