<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF HISTOGRAM SCALES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (22.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HistogramScaleMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 include_once(BL_PATH ."/HistogramScale/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Histogram Scale Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('histogramRangeFrom','Range From','width=20%','',true), new Array('histogramRangeTo','Range To','width=20%','',true),new Array('histogramLabel','Histogram Label','width=20%','',true), new Array('action','Action','width="3%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/HistogramScale/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddHistorgramScale';   
editFormName   = 'EditHistogramScale';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteHistogramScale';
divResultName  = 'results';
page=1; //default page
sortField = 'histogramRangeFrom';
sortOrderBy = 'DESC';

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
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("histogramRangeFrom","<?php echo ENTER_HISTOGRAMRANGEFROM_NAME ?>"),new Array("histogramRangeTo","<?php echo ENTER_HISTOGRAMRANGETO_NAME ?>"),new Array("histogramLabel","<?php echo ENTER_HISTOGRAMLABEL_NAME ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value"))) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
		}

			if(fieldsArray[i][0]=="histogramRangeFrom"){
				 if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))
					{
						messageBox("<?php echo ENTER_NUMBER ?>");
						eval("frm."+(fieldsArray[i][0])+".focus();");
						return false;
						break;
					}
			}
			if(fieldsArray[i][0]=="histogramRangeTo"){
				 if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))
					{
						messageBox("<?php echo ENTER_NUMBER ?>");
						eval("frm."+(fieldsArray[i][0])+".focus();");
						return false;
						break;
					}
			}
	}
		
		if (act=='Add'){
			 
			 if (parseInt(document.addHistogramScale.histogramRangeTo.value) == 0) {
				messageBox("<?php echo INVALID_RANGETO_ZERO ?>");
				return false;
			 }

			 if (parseInt(document.addHistogramScale.histogramRangeTo.value) < parseInt(document.addHistogramScale.histogramRangeFrom.value)) {
				messageBox("<?php echo INVALID_RANGE ?>");
				return false;
				//break;
			 }

			 if (parseInt(document.addHistogramScale.histogramRangeFrom.value) > 100) {
				messageBox("<?php echo INVALID_RANGELIMIT?>");
				return false;
				//break;
			 }

			 if (parseInt(document.addHistogramScale.histogramRangeTo.value) > 100) {
				messageBox("<?php echo INVALID_RANGELIMIT?>");
				return false;
				//break;
			 }
		}

		if (act=='Edit'){
			 if (parseInt(document.editHistogramScale.histogramRangeTo.value) < parseInt(document.editHistogramScale.histogramRangeFrom.value)) {
				messageBox("<?php echo INVALID_RANGE ?>");
				return false;
				//break;
			 }
			 if (parseInt(document.editHistogramScale.histogramRangeFrom.value) > 100 ) {
				messageBox("<?php echo INVALID_RANGELIMIT ?>");
				return false;
				//break;
			 }

			 if (parseInt(document.editHistogramScale.histogramRangeTo.value) > 100 ) {
				messageBox("<?php echo INVALID_RANGELIMIT ?>");
				return false;
				//break;
				
			 }
		}
    
    if(act=='Add') {
		addHistogramScale();
        return false;
    }
    else if(act=='Edit') {
        editHistogramScale();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addHistogramScale() IS USED TO ADD NEW HISTOGRAM SCALE
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHistogramScale() {
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramScale/ajaxInitAdd.php';

		 
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {	histogramRangeFrom: (document.addHistogramScale.histogramRangeFrom.value), 
							histogramRangeTo: (document.addHistogramScale.histogramRangeTo.value), 
							histogramLabel: (document.addHistogramScale.histogramLabel.value)
						},
             
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
                         hiddenFloatingDiv('AddHistorgramScale');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEHISTOGRAMSCALE() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILED THROUGH ID
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteHistogramScale(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramScale/ajaxInitDelete.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {histogramScaleId: id},
             
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
//THIS FUNCTION blankValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
   document.addHistogramScale.histogramRangeFrom.value = '';
   document.addHistogramScale.histogramRangeTo.value = '';
   document.addHistogramScale.histogramLabel.value = '';
   document.addHistogramScale.histogramRangeFrom.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITHISTOGRAM() IS USED TO populate edit the values & 
//save the values into the database by using histogramId
//
//Author : Jaineesh
// Created on : (22.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editHistogramScale() {
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramScale/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	histogramRangeFrom: (document.editHistogramScale.histogramRangeFrom.value), 
							histogramRangeTo: (document.editHistogramScale.histogramRangeTo.value), 
							histogramLabel: (document.editHistogramScale.histogramLabel.value),
							histogramScaleId: (document.editHistogramScale.histogramScaleId.value)
						},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditHistogramScale');
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
         url = '<?php echo HTTP_LIB_PATH;?>/HistogramScale/ajaxGetValues.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {histogramScaleId: id},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditHistogramScale'); 
                        messageBox("<?php echo HISTOGRAMSCALE_NOT_EXIST;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.editHistogramScale.histogramRangeFrom.value = j.histogramRangeFrom;
				   document.editHistogramScale.histogramRangeTo.value = j.histogramRangeTo;
                   document.editHistogramScale.histogramLabel.value = j.histogramId;
				   document.editHistogramScale.histogramScaleId.value = j.histogramScaleId;
                   document.editHistogramScale.histogramRangeFrom.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/HistogramScale/listHistogramScaleContents.php");
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
// $History: listHistogramScale.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/11/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//show mandatory field histogram label
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
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:40p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/25/08   Time: 6:17p
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/25/08   Time: 3:16p
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:15a
//Updated in $/Leap/Source/Interface
//modified for sorting
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/24/08   Time: 4:20p
//Created in $/Leap/Source/Interface
//contains add,edit & delete histogram scale
//

?>