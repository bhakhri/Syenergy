<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Cleaning ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CleaningMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Cleaning Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 470; //  add/edit form width
winLayerHeight = 390; // add/edit form height

// ajax search results ---end ///

function getCleaningRoomDetail(){
  url = '<?php echo HTTP_LIB_PATH;?>/CleaningRoom/ajaxInitCleaningRoomDetailList.php';
  var value=document.getElementById('searchbox').value;
  
 var tableColumns = new Array(
                        new Array('srNo','#','width="4%" align="left"',false), 
                        new Array('hostelName','Hostel Name','width="12%",align="left"',true),
						new Array('Dated','Date','width="8%", align="center"',true),
						new Array('tempEmployeeName','Safaiwala','width="10%" align="left"',true),
						new Array('toiletsCleaned','Toilet(s) Cleaned','width="8%" align="right"',true),
						new Array('noOfRoomsCleaned','Room(s) Cleaned','width="10%" align="right"',true),
						new Array('attachedRoomToiletsCleaned','Attached Room Toilet(s) Cleaned','width="10%" align="right"',true),
						new Array('dryMoppingInSqMeter','Dry Mopping','width="8%" align="right"',true),
						new Array('wetMoppingInSqMeter','Wet Mopping','width="8%" align="right"',true),
						new Array('roadCleanedInSqMeter','Road Cleaned','width="8%" align="right"',true),
						new Array('noOfGarbageBinsDisposal','Garbage Disposal','width="8%" align="right"',true),
						new Array('noOfHoursWorked','Working hrs.','width="12%" align="right"',true),
						new Array('action','Action','width="8%" align="right"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','hostelName','ASC','CleaningRoomDetailResultDiv','CleaningRoomDetailDiv','',true,'listObj',tableColumns,'editWindow','deleteCleaningRoomDetail','&searchbox='+trim(value));
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
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Cleaning Room Detail';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   var fieldsArray = new Array(	new Array("safaiwala","<?php echo CHOOSE_SAFAIWALA; ?>"),
								//new Array("date","<?php echo SELECT_DATE; ?>"),
								new Array("hostelName","<?php echo CHOOSE_HOSTEL_NAME; ?>"),
								//new Array("toiletsNo","<?php echo ENTER_TOILET_NO; ?>"),
								//new Array("roomsNo","<?php echo ENTER_ROOM; ?>"),
								new Array("noOfhrs","<?php echo ENTER_NO_HRS; ?>")
							   );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value"))) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
			messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
	}
	
	var curdate = "<?php echo date('Y-m-d'); ?>";

	if(document.getElementById('startDate').value > curdate) {
		messageBox("<?php echo DATE_NOT_GREATER ?>");
		document.getElementById('startDate').focus();
        return false;
	}

	 if(!isInteger(document.getElementById('toiletsNo').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('toiletsNo').focus();
                return false;
     }

	if(!isInteger(document.getElementById('roomsNo').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('roomsNo').focus();
                return false;
    }
	if(!isInteger(document.getElementById('roomsAttachedBath').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('roomsAttachedBath').focus();
                return false;
    }
	if(!isAlphaNumericdot(document.getElementById('dryMopping').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('dryMopping').focus();
                return false;
    }

	if(!isAlphaNumericdot(document.getElementById('wetMopping').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('wetMopping').focus();
                return false;
    }
	if(!isAlphaNumericdot(document.getElementById('areaRoad').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('dryMopping').focus();
                return false;
    }
	if(!isInteger(document.getElementById('garbageBins').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('garbageBins').focus();
                return false;
    }
	if(!isInteger(document.getElementById('noOfhrs').value)) {
                messageBox("<?php echo ENTER_NUMBER ?>");
				document.getElementById('noOfhrs').focus();
                return false;
    }

	if(document.getElementById('dryMopping').value > "9999") {
		messageBox("<?php echo VALUE_CANNOT_GREATER; ?>");
		document.getElementById('dryMopping').focus();	
		return false;
	}

	if(document.getElementById('wetMopping').value > "9999") {
		messageBox("<?php echo VALUE_CANNOT_GREATER; ?>");
		document.getElementById('wetMopping').focus();	
		return false;
	}

	if(document.getElementById('garbageBins').value > 100) {
		messageBox("<?php echo VALUE_NOT_MORE; ?>");
		document.getElementById('garbageBins').focus();	
		return false;
	}

	if(document.getElementById('areaRoad').value > "9999") {
		messageBox("<?php echo VALUE_CANNOT_GREATER; ?>");
		document.getElementById('areaRoad').focus();	
		return false;
	}
    
	if(document.getElementById('cleanId').value=='') {
        //alert('add slot');
		addCleaningRoomDetail();
        return false;
    }
    else{
		//alert('edit slot');
        editCleaningRoomDetail();
        return false;
    }
}


//-------------------------------------------------------
//THIS FUNCTION addDocument() IS USED TO ADD NEW Hostel room type
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addCleaningRoomDetail() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/CleaningRoom/ajaxInitCleaningRoomDetailAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				safaiwala:			trim(document.CleaningRoomDetail.safaiwala.value),
				date:				trim(document.CleaningRoomDetail.startDate.value),
				hostelName:			trim(document.CleaningRoomDetail.hostelName.value),
				toiletsNo:			trim(document.CleaningRoomDetail.toiletsNo.value),
				roomsNo:			trim(document.CleaningRoomDetail.roomsNo.value),
				roomsAttachedBath:	trim(document.CleaningRoomDetail.roomsAttachedBath.value),
				dryMopping:			trim(document.CleaningRoomDetail.dryMopping.value),
				wetMopping:			trim(document.CleaningRoomDetail.wetMopping.value),
				areaRoad:			trim(document.CleaningRoomDetail.areaRoad.value),
				garbageBins:		trim(document.CleaningRoomDetail.garbageBins.value),
				noOfhrs:			trim(document.CleaningRoomDetail.noOfhrs.value)

             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('CleaningRoomDetailDiv');
                             getCleaningRoomDetail();
                             return false;
                         }
                     }
					 else if ("<?php echo SAFAIWALA_ALREADY_EXIST ?>" == trim(transport.responseText)) {
							messageBox("<?php echo SAFAIWALA_ALREADY_EXIST; ?>");	
							document.CleaningRoomDetail.safaiwala.focus();
							return false;
					 }
				 else {
					messageBox(trim(transport.responseText)); 
				 }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteCleaningRoomDetail(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/CleaningRoom/ajaxInitCleaningRoomDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {cleanId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getCleaningRoomDetail(); 
                         return false;
                     }
					 else if ("<?php echo SAFAIWALA_ALREADY_EXIST ?>" == trim(transport.responseText)) {
							messageBox("<?php echo SAFAIWALA_ALREADY_EXIST; ?>");	
							document.CleaningRoomDetail.safaiwala.focus();
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
//THIS FUNCTION IS USED TO CLEAN UP THE "cleaning room" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Cleaning Room Detail';
	document.CleaningRoomDetail.safaiwala.value = '';
	document.CleaningRoomDetail.hostelName.value = '';
	document.CleaningRoomDetail.toiletsNo.value = '';
	document.CleaningRoomDetail.roomsNo.value = '';
	document.CleaningRoomDetail.roomsAttachedBath.value = '';
	document.CleaningRoomDetail.dryMopping.value = '';
	document.CleaningRoomDetail.wetMopping.value = '';
	document.CleaningRoomDetail.areaRoad.value = '';
	document.CleaningRoomDetail.garbageBins.value = '';
	document.CleaningRoomDetail.noOfhrs.value = '';
	document.CleaningRoomDetail.cleanId.value = '';
	document.CleaningRoomDetail.safaiwala.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editCleaningRoomDetail() {
         url = '<?php echo HTTP_LIB_PATH;?>/CleaningRoom/ajaxInitCleaningRoomDetailEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					cleanId:			(document.CleaningRoomDetail.cleanId.value),
					safaiwala:		trim(document.CleaningRoomDetail.safaiwala.value),
					date:				trim(document.CleaningRoomDetail.startDate.value),
					hostelName:			trim(document.CleaningRoomDetail.hostelName.value),
					toiletsNo:			trim(document.CleaningRoomDetail.toiletsNo.value),
					roomsNo:			trim(document.CleaningRoomDetail.roomsNo.value),
					roomsAttachedBath:	trim(document.CleaningRoomDetail.roomsAttachedBath.value),
					dryMopping:			trim(document.CleaningRoomDetail.dryMopping.value),
					wetMopping:			trim(document.CleaningRoomDetail.wetMopping.value),
					areaRoad:			trim(document.CleaningRoomDetail.areaRoad.value),
					garbageBins:		trim(document.CleaningRoomDetail.garbageBins.value),
					noOfhrs:			trim(document.CleaningRoomDetail.noOfhrs.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('CleaningRoomDetailDiv');
                         getCleaningRoomDetail();
                         return false;
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/CleaningRoom/ajaxCleaningRoomGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {cleanId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('CleaningRoomDetailDiv');
                        messageBox("<?php echo CLEANING_ROOM_DETAIL_NOT_EXIST; ?>");
                        getCleaningRoomDetail();           
                   }

                   j = eval('('+trim(transport.responseText)+')');
				   
                   
				   document.CleaningRoomDetail.safaiwala.value			= j.tempEmployeeId;
				   document.CleaningRoomDetail.hostelName.value			= j.hostelId;
				   document.CleaningRoomDetail.startDate.value			= j.Dated;
				   document.CleaningRoomDetail.toiletsNo.value			= j.toiletsCleaned;
				   document.CleaningRoomDetail.roomsNo.value			= j.noOfRoomsCleaned;
				   document.CleaningRoomDetail.roomsAttachedBath.value	= j.attachedRoomToiletsCleaned;
				   if (j.dryMoppingInSqMeter.length > 4 ) {
						j.dryMoppingInSqMeter = j.dryMoppingInSqMeter.split('.0')[0];
					}
				   document.CleaningRoomDetail.dryMopping.value			= j.dryMoppingInSqMeter;
				   if (j.wetMoppingInSqMeter.length > 4 ) {
						j.wetMoppingInSqMeter = j.wetMoppingInSqMeter.split('.0')[0];
					}
				   document.CleaningRoomDetail.wetMopping.value			= j.wetMoppingInSqMeter;
				   if (j.roadCleanedInSqMeter.length > 4 ) {
						j.roadCleanedInSqMeter = j.roadCleanedInSqMeter.split('.0')[0];
					}
				   document.CleaningRoomDetail.areaRoad.value			= j.roadCleanedInSqMeter;
				   document.CleaningRoomDetail.garbageBins.value		= j.noOfGarbageBinsDisposal;
				   document.CleaningRoomDetail.noOfhrs.value			= j.noOfHoursWorked;
				   document.CleaningRoomDetail.cleanId.value			= j.cleanId;
                   document.CleaningRoomDetail.safaiwala.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

	function printReport() {
		sortField = listObj.sortField;
		sortOrderBy = listObj.sortOrderBy;
		path='<?php echo UI_HTTP_PATH;?>/cleaningRecordReportPrint.php?searchbox='+trim(document.searchBox1.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
		try{
		window.open(path,"EmployeeTempReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
		}
		catch(e){
		}
	}

	function printCSV() {
		sortField = listObj.sortField;
		sortOrderBy = listObj.sortOrderBy;
		path='<?php echo UI_HTTP_PATH;?>/cleaningRecordReportCSV.php?searchbox='+trim(document.searchBox1.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
		window.location = path;
	}


window.onload=function(){
        //loads the data
        getCleaningRoomDetail();    
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/CleaningRoom/listCleaningRoomContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<?php 
// $History: listCleaningRecord.php $ 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Interface
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:04p
//Updated in $/LeapCC/Interface
//show the fields in list and make them sortable
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/02/09    Time: 3:32p
//Updated in $/LeapCC/Interface
//remove mendatory fields 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 3:04p
//Updated in $/LeapCC/Interface
//date cannot greater than current date
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:20p
//Created in $/LeapCC/Interface
//new files for cleaning room
//
//
?>