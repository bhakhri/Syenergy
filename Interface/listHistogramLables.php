<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF HISTOGRAM LABLES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramLabelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 include_once(BL_PATH ."/HistogramLabels/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Histogram Label Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('histogramLabel','Histogram Label','width=20%','',true), new Array('action','Action','width="3%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/HistogramLabels/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddHistorgramLabel';   
editFormName   = 'EditHistogramLabel';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteHistogramLabel';
divResultName  = 'results';
page=1; //default page
sortField = 'histogramLabel';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("histogramLabel","<?php echo ENTER_HISTOGRAMLABEL_NAME ?>"));

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
             /*if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='designationName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo DESIGNATION_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }*/
            /*if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}

			if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))){
				messageBox("<?php echo 'Cannot add special characters' ?>");
				eval("frm."+(fieldsArray[i][0])+".focus();");
				return false;
				break;
				}
			}
        }
     
    if(act=='Add') {
        addHistogramLabel();
        return false;
    }
    else if(act=='Edit') {
        editHistogramLabel();
        return false;
    }
}
//-------------------------------------------------------
//THIS FUNCTION addHistogramLabel() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHistogramLabel() {
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramLabels/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {histogramLabel: (document.addHistogramLabel.histogramLabel.value)},
             
               OnCreate: function(){
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
                         hiddenFloatingDiv('AddHistorgramLabel');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo HISTOGRAMLABEL_ALREADY_EXIST ?>"){
							document.addHistogramLabel.histogramLabel.value='';
							document.addHistogramLabel.histogramLabel.focus();	
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEPERIOD() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILED THROUGH ID
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteHistogramLabel(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramLabels/ajaxInitDelete.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {histogramId: id},
             
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
//THIS FUNCTION blanValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
   document.addHistogramLabel.histogramLabel.value = '';
   document.addHistogramLabel.histogramLabel.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITDESIGNATION() IS USED TO populate edit the values & 
//save the values into the database by using histogramId
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editHistogramLabel() {
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramLabels/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {histogramId: (document.editHistogramLabel.histogramId.value), histogramLabel: (document.editHistogramLabel.histogramLabel.value)},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditHistogramLabel');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo HISTOGRAMLABEL_ALREADY_EXIST ?>"){
							document.editHistogramLabel.histogramLabel.value='';
							document.editHistogramLabel.histogramLabel.focus();	
						}
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramLabels/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {histogramId: id},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditHistogramLabel'); 
                        messageBox("<?php echo HISTOGRAMLABEL_NOT_EXIST;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.editHistogramLabel.histogramLabel.value = j.histogramLabel;
                   document.editHistogramLabel.histogramId.value = j.histogramId;
                   document.editHistogramLabel.histogramLabel.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayHistogramLabelReport.php';
    window.open(path,"DisplayHistogramLabelReport","status=1,menubar=1,scrollbars=1, width=900");
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/HistogramLabels/listHistogramLabelContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listHistogramLables.php $
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
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:40p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/24/08   Time: 7:08p
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:19p
//Created in $/Leap/Source/Interface
//contains add, edit & delete of histogram labels
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:49p
//Updated in $/Leap/Source/Interface
//embedded print option 
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 9/26/08    Time: 11:57a
//Updated in $/Leap/Source/Interface
//modification in code
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:42p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/20/08    Time: 2:32p
//Updated in $/Leap/Source/Interface
//modified for error message
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/11/08    Time: 12:41p
//Updated in $/Leap/Source/Interface
//modified for duplicate record check
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/07/08    Time: 3:30p
//Updated in $/Leap/Source/Interface
//modified in edit or delete messages
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/01/08    Time: 6:28p
//Updated in $/Leap/Source/Interface
//cursor show on designation name text box during textbox
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/01/08    Time: 11:52a
//Updated in $/Leap/Source/Interface
//modified for onSuccess function & transport 
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:22p
//Updated in $/Leap/Source/Interface
//change errormessage from echo
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:53p
//Updated in $/Leap/Source/Interface
//change alert in messagebox
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:10a
//Updated in $/Leap/Source/Interface
//fixed the bug.
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/01/08    Time: 12:42p
//Updated in $/Leap/Source/Interface
//modified in comments
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/30/08    Time: 10:10a
//Updated in $/Leap/Source/Interface
//Make the changes for ajax functions
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/25/08    Time: 4:15p
//Updated in $/Leap/Source/Interface
//modified in error occured during deletion
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:30p
//Updated in $/Leap/Source/Interface
//modified in validation function
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:21p
//Updated in $/Leap/Source/Interface
//put the delete function
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:00p
//Updated in $/Leap/Source/Interface
//modified with comments during checkin
?>