<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Slot Name & abbr. ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (15.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodSlotMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/PeriodSlot/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Period Slot Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
sortField = 'slotName';
sortOrderBy    = 'ASC';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getPeriodSlotData(){
  url = '<?php echo HTTP_LIB_PATH;?>/PeriodSlot/ajaxInitPeriodSlotList.php';
   var value=document.searchForm.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="3%" align="left"',false), 
                        new Array('slotName','Slot Name','width="35%" align="left"',true),
                        new Array('slotAbbr','Abbr.','width="30%" align="left"',true),
						new Array('isActive','Active','width="20%" align="center"',true),
                        new Array('action','Action','width="2%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','slotName','ASC','PeriodSlotResultDiv','PeriodSlotActionDiv','',true,'listObj',tableColumns,'editWindow','deletePeriodSlot','&searchbox='+trim(value));
 sendRequest(url, listObj, '')
}
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Period Slot';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("slotName","<?php echo ENTER_SLOT_NAME ?>"),new Array("slotAbbr","<?php echo ENTER_SLOT_ABBR ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
         
       /* else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='slotName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo SLOT_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
            
        else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='slotName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_OFFENSE_ALPHABETS_NUMERIC ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }
            
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
  
    if(document.getElementById('periodSlotId').value=='') {
        //alert('add slot');
		addPeriodSlot();
        return false;
    }
    else{
		//alert('edit slot');
        editPeriodSlot();
        return false;
    }
}

function emptySlotId() {
	document.getElementById('periodSlotId').value='';
}

//-------------------------------------------------------
//THIS FUNCTION addPeriods() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addPeriodSlot() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/PeriodSlot/ajaxInitPeriodSlotAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                slotName:   trim(document.PeriodSlotDetail.slotName.value), 
                slotAbbr:   trim(document.PeriodSlotDetail.slotAbbr.value),
				isActive: (document.PeriodSlotDetail.isActive[0].checked ? 1 : 0 )
                 
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
                             hiddenFloatingDiv('PeriodSlotActionDiv');
                             getPeriodSlotData();
                             return false;
                         }
                     } 
                     else if("<?php echo PERIOD_SLOT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PERIOD_SLOT_ALREADY_EXIST ;?>"); 
						 document.PeriodSlotDetail.slotAbbr.value="";
                         document.PeriodSlotDetail.slotAbbr.focus();
                        } 
                     else {
						 messageBox("<?php echo PERIOD_SLOTNAME_ALREADY_EXIST ;?>"); 
						 document.PeriodSlotDetail.slotName.value="";
                         document.PeriodSlotDetail.slotName.focus();
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=periodSlotId
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deletePeriodSlot(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/PeriodSlot/ajaxInitPeriodSlotDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodSlotId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getPeriodSlotData(); 
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
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDPERIODSLOT" DIV
//
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Period Slot';
	document.PeriodSlotDetail.slotName.value = '';
	document.PeriodSlotDetail.slotAbbr.value = '';
	document.PeriodSlotDetail.isActive[0].checked =true;
	document.PeriodSlotDetail.slotName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A PERIOD SLOT
//
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editPeriodSlot() {
         url = '<?php echo HTTP_LIB_PATH;?>/PeriodSlot/ajaxInitPeriodSlotEdit.php';
                  
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					periodSlotId: (document.PeriodSlotDetail.periodSlotId.value),
					slotName:   trim(document.PeriodSlotDetail.slotName.value), 
					slotAbbr :   trim(document.PeriodSlotDetail.slotAbbr.value),
					isActive: (document.PeriodSlotDetail.isActive[0].checked ? 1 : 0 )				

             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('PeriodSlotActionDiv');
                         getPeriodSlotData();
						 emptySlotId();
                         return false;

                     }
                    else if("<?php echo PERIOD_SLOT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo PERIOD_SLOT_ALREADY_EXIST ;?>"); 
						 document.PeriodSlotDetail.slotAbbr.value="";
                         document.PeriodSlotDetail.slotAbbr.focus();
                        }
					else if("<?php echo ACTIVE_PERIOD_SLOT_UPDATE;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ACTIVE_PERIOD_SLOT_UPDATE ;?>"); 
                        }
                     else {
						 messageBox("<?php echo PERIOD_SLOTNAME_ALREADY_EXIST ;?>"); 
						 document.PeriodSlotDetail.slotName.value="";
                         document.PeriodSlotDetail.slotName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITPERIODLIST" DIV
//
//Author : Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/PeriodSlot/ajaxGetPeriodSlotValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodSlotId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('PeriodSlotActionDiv');
                        messageBox("<?php echo PERIOD_SLOT_NOT_EXIST; ?>");
                        getPeriodSlotData();           
                   }

                   j = eval('('+trim(transport.responseText)+')');
                   
                   document.PeriodSlotDetail.slotName.value			= j.slotName;
                   document.PeriodSlotDetail.slotAbbr.value			= j.slotAbbr;
				   document.PeriodSlotDetail.isActive[0].checked = (j.isActive=="1" ? true : false) ;
                   document.PeriodSlotDetail.isActive[1].checked = (j.isActive=="1" ? false : true) ;
                   document.PeriodSlotDetail.periodSlotId.value     = j.periodSlotId;
                   document.PeriodSlotDetail.slotName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;
   path='<?php echo UI_HTTP_PATH;?>/displayPeriodSlotReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayPeriodSlotReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayPeriodSlotCSV.php?'+qstr;
	window.location = path;
}


window.onload=function(){
        //loads the data
        getPeriodSlotData();    
}

/*function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayPeriodsReport.php';
    window.open(path,"DisplayPeriodsReport","status=1,menubar=1,scrollbars=1, width=900, height=700");
}*/

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/PeriodSlot/listPeriodSlotContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listPeriodSlot.php $
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 4/20/10    Time: 5:55p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003312, 0003311, 0003298, 0003299
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/20/09   Time: 1:02p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001811, 0001800, 0001798, 0001795, 0001793, 0001782,
//0001800, 0001813
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:41p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/22/09    Time: 7:24p
//Updated in $/LeapCC/Interface
//changes to fix bugs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/22/09    Time: 3:21p
//Updated in $/LeapCC/Interface
//fixed bug no. 0000148
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:30p
//Created in $/LeapCC/Interface
//new for period slot edit, delete & add
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:13p
//Created in $/Leap/Source/Interface
//new file for edit, delete, add for period slot
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:10p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 10/30/08   Time: 11:27a
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 10/25/08   Time: 5:43p
//Updated in $/Leap/Source/Interface
//add new field time table label Id
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:00p
//Updated in $/Leap/Source/Interface
//embedded print option
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 9/26/08    Time: 11:32a
//Updated in $/Leap/Source/Interface
//remove the delete message
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 8/29/08    Time: 11:07a
//Updated in $/Leap/Source/Interface
//modification in indentation
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/26/08    Time: 5:27p
//Updated in $/Leap/Source/Interface
//modified message
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 8/01/08    Time: 4:46p
//Updated in $/Leap/Source/Interface
//modified in OnCreate & OnSuccess functions
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 7/28/08    Time: 7:39p
//Updated in $/Leap/Source/Interface
//modified for institute id
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:37p
//Updated in $/Leap/Source/Interface
//change alert in message box
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:24p
//Updated in $/Leap/Source/Interface
//modified in message box
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 7/18/08    Time: 10:18a
//Updated in $/Leap/Source/Interface
//modification during duplicate record, text box should be empty and
//cursoe on text box
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:04p
//Updated in $/Leap/Source/Interface
//fixed the bug
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/12/08    Time: 12:07p
//Updated in $/Leap/Source/Interface
//concat AM & PM fields in database query
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:23p
//Updated in $/Leap/Source/Interface
//modified for special characters check
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:15p
//Updated in $/Leap/Source/Interface
//modified for add, edit & delete
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:19p
//Updated in $/Leap/Source/Interface
//modification with new ajax functions
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/25/08    Time: 4:15p
//Updated in $/Leap/Source/Interface
//modified in error occured during deletion
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:45p
//Updated in $/Leap/Source/Interface
//modified in add period
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:31p
//Updated in $/Leap/Source/Interface
//modified in validation function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//modified delete with ajax
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:48p
//Updated in $/Leap/Source/Interface
?>
