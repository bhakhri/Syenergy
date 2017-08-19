<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Handle Complaints Form
//
//
// Author :Jaineesh
// Created on : 28.04.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HandleComplaints');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Handle Complaints </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('subject','Subject','width=15%','',true),
								new Array('categoryName','Category Name','width=12%','',true),
								new Array('roomName','Room No.','width="10%"','',true),
								new Array('hostelName','Hostel','width="8%"','',true),
								new Array('studentName','Reported By','width="10%"','',false),
								new Array('complaintOn','Complaint On','width="8%"','align=center',true),
								new Array('completionDate','Complete On','width="8%"','align=center',true),
								new Array('updateComplaintStatus','Status','width="8%"','',false),
								new Array('completionRemarks','Remarks','width="8%"','',true),
								new Array('handleComplaint','Action','width="8%"','',true)
							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/ReportComplaints/initComplaintsReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'HanldeComplaintForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'subject';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("startDate","<?php echo SELECT_DATE;?>"),
								new Array("toDate","<?php echo SELECT_TODATE;?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function resetStudyPeriod() {
	document.studentAttendanceForm.studyPeriodId.selectedIndex = 0;
}

function printReport() {
	form = document.HanldeComplaintForm;
	path='<?php echo UI_HTTP_PATH;?>/handleComplaintsReportPrint.php?hostelId='+form.hostel.value+'&roomId='+form.room.value+'&studentId='+form.reportedBy.value+'&startDate='+form.startDate.value+'&toDate='+form.toDate.value;
	a = window.open(path,"HandleComplaintReport","status=1,menubar=1,scrollbars=1, width=900");

}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "ROOMNAME" DIV
//
//Author : Jaineesh
// Created on : (28.04.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getHostelRoom() {
	//hideResults();
	form = document.HanldeComplaintForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/getRoom.php';
	var pars = 'hostel='+form.hostel.value;

	if (form.hostel.value == "") {
		form.room.length = null;
		addOption(form.room, '', 'ALL');
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
				addOption(form.room, '', 'ALL');
				return false;
			}
			
			for(i=0;i<len;i++) {
				addOption(form.room, j[i].hostelRoomId, j[i].roomName );
			}
			// now select the value
			//form.subject.value = j[0].subjectId;
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

function getStudentHostelData() {
	//hideResults();
	form = document.HanldeComplaintForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/getStudent.php';
	var pars = 'room='+form.room.value;

	if (form.room.value == "") {
		form.reportedBy.length = null;
		addOption(form.reportedBy, '', 'ALL');
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
			form.reportedBy.length = null;
			//addOption(form.hostel, 'NULL', 'All');
			
			if (len == "") {
				addOption(form.reportedBy, '', 'ALL');
				return false;
			}
			
			for(i=0;i<len;i++) {
				addOption(form.reportedBy, j[i].studentId, j[i].studentName );
			}
			// now select the value
			//form.subject.value = j[0].subjectId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function validateAddHandleComplaint(frm,act) {
  
     /*if(trim(document.getElementById('periodId1').value)=="") {
         messageBox("Please select periods");
         return false;
    }   

	if(trim(document.getElementById('subjectId1').value)=="") {
         messageBox("Please select subject");
         return false;
    }
	
	
	if(!dateDifference(curdate,document.getElementById('dated1').value,"-")) {
           messageBox("<?php echo DATE_SUBVALIDATION; ?>");
           document.getElementById('dated1').focus();  
		   return false;
     }
*/
	 
	 
	 if(act=='Add') {
        addHandleComplaint();
        return false;
    }

}

function showHandleComplaintDetail(complaintId,dv,w,h) {
	displayWindow('divHandleComplaint',320,320);
	//periodId = periodId.replace(/~/g,",");
	//displayFloatingDiv(dv,'', w, h, 400, 400)
	getHandleComplaint(complaintId);
}

function getHandleComplaint(complaintId) {
	
    var url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxGetHandleComplaints.php';
    
    new Ajax.Request(url,
    {
        method:'post',
         parameters: {	complaintId: (complaintId) 
					 },  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
			if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divHandleComplaint');
                        messageBox("<?php echo COMPLAINT_NOT_EXIST; ?>");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
            }
			//alert(transport.responseText);
            j = eval( '('+trim(transport.responseText)+')');

			document.HandleComplaintForm.subject.value = j.subject;
			document.HandleComplaintForm.complaintOn.value = j.complaintOn;
			document.HandleComplaintForm.reportedBy.value = j.studentName;
			document.HandleComplaintForm.roomName.value = j.roomName;
			document.HandleComplaintForm.complaintStatus.value = j.complaintStatus;
			document.HandleComplaintForm.remarks.value = j.completionRemarks;
			document.HandleComplaintForm.complaintId.value = j.complaintId;

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function addHandleComplaint() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/ReportComplaints/ajaxInitHandleComplaintsAdd.php';
		//pars = generateQueryString('SubstitutionForm');
		//alert(pars);

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				complaintId:		trim(document.HandleComplaintForm.complaintId.value),
				complaintStatus:	trim(document.HandleComplaintForm.complaintStatus.value),
				complaintOn:		trim(document.HandleComplaintForm.complaintOn.value),
				completionDate:		trim(document.HandleComplaintForm.endDate.value),
				remarks:			trim(document.HandleComplaintForm.remarks.value)	
				 },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if("<?php echo SUCCESS;?>") {
                             //blankValues();
							 hiddenFloatingDiv('divHandleComplaint');
							 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

                         }
                         
                     }
					 else if ("<?php echo WRONG_DATE;?>" == trim(transport.responseText)) {
						messageBox("<?php echo WRONG_DATE?>");
						document.HandleComplaintForm.endDate.focus();
						return false;
					 }
					else {
						messageBox(trim(transport.responseText)); 
					 }

					 
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ReportComplaints/listHandleComplaintsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: listHandleComplaints.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:06p
//Updated in $/LeapCC/Interface
//changes in handle complaints
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 4:34p
//Updated in $/LeapCC/Interface
//show some fields on list
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:13p
//Created in $/LeapCC/Interface
//new file for handle complaints
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/17/08   Time: 11:14a
//Updated in $/LeapCC/Interface
//Added define('MANAGEMENT_ACCESS',1); for management access
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:32p
//Updated in $/Leap/Source/Interface
//code applied for reducing unnecessary server trip
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 8/18/08    Time: 5:55p
//Updated in $/Leap/Source/Interface
//file modified for setting print button and improving design.
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:05p
//Updated in $/Leap/Source/Interface
//done minor changes
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/07/08    Time: 6:23p
//Updated in $/Leap/Source/Interface
//removed field "lectures missed" as told
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:10p
//Updated in $/Leap/Source/Interface
//removed unused code
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:04p
//Updated in $/Leap/Source/Interface
//file changed for making it as per new format
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/02/08    Time: 5:19p
//Updated in $/Leap/Source/Interface
//done cosmetic changes
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 7/29/08    Time: 11:08a
//Updated in $/Leap/Source/Interface
//made the changes as per new query
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/25/08    Time: 2:38p
//Updated in $/Leap/Source/Interface
//file updated and made working with new attendance table
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/18/08    Time: 4:33p
//Updated in $/Leap/Source/Interface
//done the coding for report printing part
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/17/08    Time: 7:26p
//Updated in $/Leap/Source/Interface
//done the coding for studentAttendanceReport completion
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:30a
//Created in $/Leap/Source/Interface
//File made for : StudentAttendanceReport
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/15/08    Time: 12:39p
//Created in $/Leap/Source/Interface
//Added one new file for StudentLabels Report Module


?>
