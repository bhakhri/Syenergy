<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Training ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (23.04.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ReportComplaintsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?> Report Complaints Master :</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxReportComplaintsDetailList.php';   
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'ReportComplaintDiv';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubject';
divResultName  = 'ReportComplaintResultDiv';
page=1; //default page
sortField = 'subject';
sortOrderBy    = 'ASC';


// ajax search results ---end ///

function getReportComplaintData(){
  url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxReportComplaintsDetailList.php';
  var value=document.searchForm.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="4%" align="left"',false), 
						new Array('complaintId','Tracking No.','width="10%" align="right"',false),
                        new Array('subject','Subject','width="10%" align="left"',true),
						new Array('categoryName','Category Name','width="12%" align="left"',true),
						new Array('hostelName','Hostel','width="10%" align="left"',true),
						new Array('roomName','Room','width="10%" align="left"',true),
						new Array('studentName','Reported By','width="10%" align="left"',true),
						new Array('complaintOn','Complaint On','width="12%" align="center"',true),
						new Array('complaintStatus','Status','width="8%" align="left"',true),
                        new Array('action','Action','width="4%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','ReportComplaintResultDiv','ReportComplaintDiv','',true,'listObj',tableColumns,'editWindow','deleteReportComplaints','&searchbox='+trim(value));
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
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Report Complaint';
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

var curdate = "<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {   
    
   var fieldsArray = new Array(	new Array("subject","<?php echo ENTER_SUBJECT; ?>"),
								new Array("category","<?php echo ENTER_COMPLAINT_CATEGORY; ?>"),
								new Array("hostel","<?php echo CHOOSE_HOSTEL; ?>"),
								new Array("room","<?php echo CHOOSE_ROOM; ?>"),
								new Array("reportedBy","<?php echo CHOOSE_STUDENT; ?>"));

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

	 if(document.ReportComplaint.complaintStatus.value == 2) {
		if(document.ReportComplaint.trackingNumber.value == '') {
			messageBox("<?php echo ENTER_TRACKING_NUMBER; ?>");
			document.ReportComplaint.trackingNumber.focus();
			return false;
		}
	 }

	 if(!isAlphabetCharacters(document.ReportComplaint.subject.value)) {
		messageBox("<?php echo ENTER_ALPHABETS; ?>");
		document.ReportComplaint.subject.focus();
		return false;
	 }

	 if(!dateDifference(document.ReportComplaint.startDate.value,curdate,"-")){
           
           messageBox("Date Can Not be Greater Than Current Date");
           //document.getElementById('startDate').value="";  
           document.getElementById('startDate').focus();  
           return false;
         }
	
    if(document.getElementById('complaintId').value=='') {
        //alert('add slot');
		addReportComplaints();
        return false;
    }
    else{
		//alert('edit slot');
        editReportComplaints();
        return false;
    }
}

function checkStatus() {
	if(document.ReportComplaint.complaintStatus.value == 2) {
		document.ReportComplaint.trackingNumber.disabled = false;
		//document.ReportComplaint.trackingNumber.focus();
	}
	else {
		document.ReportComplaint.trackingNumber.value='';	
		document.ReportComplaint.trackingNumber.disabled = true;
	}
}

function getValues() {
	url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxReportComplaintsTrackingValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {trackingNumber: document.ReportComplaint.trackingNumber.value},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('ReportComplaintDiv');
                        messageBox("<?php echo TRACKING_NOT_EXIST; ?>");
						document.ReportComplaint.complaintStatus.focus();
						return false;
                        //getReportComplaintData();
                   }

                   j = eval('('+trim(transport.responseText)+')');
				   
                   document.ReportComplaint.subject.value				= j.subject;
				   document.ReportComplaint.description.value			= j.description;
				   document.ReportComplaint.hostel.value				= j.hostelRoomId;
				   document.ReportComplaint.room.value					= j.hostelRoomId;
				   document.ReportComplaint.reportedBy.value			= j.studentId;
				   document.ReportComplaint.startDate.value				= j.complaintOn;
                   document.ReportComplaint.subject.focus();
				   getHostelRoom();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });

}

//-------------------------------------------------------
//THIS FUNCTION addDocument() IS USED TO ADD NEW Hostel room type
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addReportComplaints() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxInitReportComplaintsAdd.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				subject:			trim(document.ReportComplaint.subject.value),
				description:		trim(document.ReportComplaint.description.value),
				category:			trim(document.ReportComplaint.category.value),
				hostel:				trim(document.ReportComplaint.hostel.value),
				room:				trim(document.ReportComplaint.room.value),
				reportedBy:			trim(document.ReportComplaint.reportedBy.value),
				complaintOn:		trim(document.ReportComplaint.startDate.value),
				complaintStatus:	trim(document.ReportComplaint.complaintStatus.value),
				trackingNumber:		trim(document.ReportComplaint.trackingNumber.value)
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
                             hiddenFloatingDiv('ReportComplaintDiv');
                             getReportComplaintData();
                             return false;
                         }
                     }
					 else {
						 messageBox(trim(transport.responseText));
					  if ("<?php echo NO_COMPLAINT_FOUND;?>" == trim(transport.responseText)) {
						 //messageBox("<?php echo NO_COMPLAINT_FOUND;?>");
						 return false;
					 }
					  if ("<?php echo NO_TRACKING_NUMBER_FOUND;?>" == trim(transport.responseText)) {
						 //messageBox("<?php echo NO_TRACKING_NUMBER_FOUND;?>");
						 document.ReportComplaint.trackingNumber.focus();
						 return false;
					 }
					   if ("<?php echo RECORD_ALREADY_EXIST;?>" == trim(transport.responseText)) {
						 //messageBox("<?php echo RECORD_ALREADY_EXIST;?>");
						 document.ReportComplaint.subject.focus();
						 return false;
					 }
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
function deleteReportComplaints(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxReportComplaintsDelete.php';
		 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {complaintId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getReportComplaintData(); 
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
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

//var curdate = "<?php echo date('Y-m-d'); ?>";

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Report Complaint';
	document.ReportComplaint.subject.value = '';
	document.ReportComplaint.description.value = '';
	document.ReportComplaint.category.value = '';
	document.ReportComplaint.hostel.value = '';
	if (document.ReportComplaint.hostel.value == "") {
		document.ReportComplaint.room.length = null;
		addOption(document.ReportComplaint.room, '', 'Select');
	}
	document.ReportComplaint.room.value = '';
	document.ReportComplaint.reportedBy.value = '';
	document.ReportComplaint.complaintId.value = '';
	//document.ReportComplaint.complaintStatus.length = null;
	document.ReportComplaint.complaintStatus.value = 1;
	document.ReportComplaint.complaintStatus.length = 0;
	addOption(document.ReportComplaint.complaintStatus,'1','Pending');
	addOption(document.ReportComplaint.complaintStatus,'2','Escalate');
	addOption(document.ReportComplaint.complaintStatus,'3','Complete');
	document.ReportComplaint.startDate.value = curdate;
	document.ReportComplaint.trackingNumber.value='';
	document.ReportComplaint.trackingNumber.disabled = true;
	document.ReportComplaint.subject.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editReportComplaints() {
         url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxReportComplaintsEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					complaintId	:			(document.ReportComplaint.complaintId.value),
					subject:			trim(document.ReportComplaint.subject.value),
					description:		trim(document.ReportComplaint.description.value),
					category:			trim(document.ReportComplaint.category.value),
					hostel:				trim(document.ReportComplaint.hostel.value),
					room:				trim(document.ReportComplaint.room.value),
					reportedBy:			trim(document.ReportComplaint.reportedBy.value),
					complaintOn:		trim(document.ReportComplaint.startDate.value),
					complaintStatus:	trim(document.ReportComplaint.complaintStatus.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('ReportComplaintDiv');
                         getReportComplaintData();
						 //emptySlotId();
                         return false;
                     }
					 else {
						 messageBox(trim(transport.responseText));
					   if ("<?php echo RECORD_ALREADY_EXIST;?>" == trim(transport.responseText)) {
						 //messageBox("<?php echo RECORD_ALREADY_EXIST;?>");
						 document.ReportComplaint.subject.focus();
						 return false;
					 }
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
         url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxReportComplaintsGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {complaintId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('ReportComplaintDiv');
                        messageBox("<?php echo HOSTEL_COMPLAINT_NOT_EXIST; ?>");
                        getReportComplaintData();           
                   }
				   else if ("<?php echo DEPENDENCY_EDIT_CONSTRAINT ?>" == trim(transport.responseText)) {
					   hiddenFloatingDiv('ReportComplaintDiv');
					   messageBox("<?php echo DEPENDENCY_EDIT_CONSTRAINT ?>");
					   return false;
				   }


                   j = eval('('+trim(transport.responseText)+')');
				   
                   document.ReportComplaint.subject.value				= j.subject;
				   document.ReportComplaint.description.value			= j.description;
				   document.ReportComplaint.category.value				= j.complaintCategoryId;
				   document.ReportComplaint.hostel.value				= j.hostelId;
				   document.ReportComplaint.room.value					= j.hostelRoomId;
				   document.ReportComplaint.reportedBy.value			= j.studentId;
				   document.ReportComplaint.startDate.value				= j.complaintOn;
				   if (j.complaintStatus != 2) {
					  document.ReportComplaint.trackingNumber.disabled = true;
				   }
				   else {
					  document.ReportComplaint.trackingNumber.disabled = false;
				   }
				   document.ReportComplaint.complaintStatus.value		= j.complaintStatus;

				   if(j.trackingNumber == 0 ) {
						document.ReportComplaint.trackingNumber.value = "";
				   }
				   else {
					document.ReportComplaint.trackingNumber.value		= j.trackingNumber;
				   }

				   document.ReportComplaint.complaintId.value			= j.complaintId;
                   document.ReportComplaint.subject.focus();
				   getHostelRoom(j.hostelRoomId);
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "ROOMNAME" DIV
//
//Author : Jaineesh
// Created on : (28.04.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getHostelRoom(hostelRoomId) {
	//hideResults();
	form = document.ReportComplaint;
	var url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/getRoom.php';
	var pars = 'hostel='+form.hostel.value;

	if (form.hostel.value == "") {
		form.room.length = null;
		addOption(form.room, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
			hideWaitDialog();
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.room.length = null;
			//addOption(form.hostel, 'NULL', 'All');
			
			if (len == "") {
				addOption(form.room, '', 'Select');
				return false;
			}
			
			for(i=0;i<len;i++) {
				addOption(form.room, j[i].hostelRoomId, j[i].roomName );
			}
			// now select the value
			form.room.value = j[0].hostelRoomId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


window.onload=function(){
        //loads the data
        getReportComplaintData();    
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/ReportComplaints/listReportComplaintsContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<?php 
// $History: listReportComplaints.php $ 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Interface
//fixed bugs during self testing
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/18/09    Time: 7:16p
//Updated in $/LeapCC/Interface
//fixed bug during self testing
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/01/09    Time: 7:23p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001374, 0001375, 0001376, 0001379, 0001373
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/02/09    Time: 6:22p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000193,0000194,0000359
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/22/09    Time: 3:03p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000193, 0000192,0000190,0000194
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/04/09    Time: 6:59p
//Updated in $/LeapCC/Interface
//changes done as per discussed with Pushpender sir
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:13p
//Created in $/LeapCC/Interface
//new file for report complaints
//
//
?>