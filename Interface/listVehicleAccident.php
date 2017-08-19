<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DESIGNATION ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleAccident');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Accident </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var topPos = 0;
var leftPos = 0;

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false), 
								new Array('busNo','Registration No.','width=10%','',true),
								new Array('name','Staff Name','width=10%','',true),
								new Array('routeCode','Route Code','width=10%','',true),
								new Array('accidentDate','Accident Date','width=10%','',true),
								new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleAccident/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddVehicleAccident';
editFormName   = 'EditVehicleAccident';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleAccident';
divResultName  = 'results';
page=1; //default page
sortField = 'busNo';
sortOrderBy    = 'ASC';
var topPos = 0;
var leftPos = 0;
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
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(	new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE ?>"),
									new Array("busNo","<?php echo SELECT_BUS ?>"),
									new Array("transportStaff","<?php echo SELECT_TRANSPORT_STAFF ?>"),
									new Array("busRoute","<?php echo SELECT_BUS_ROUTE ?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		var d=new Date();
		var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
			
		if(!dateDifference(document.getElementById('accidentDate').value,cdate,"-")) {
			messageBox("<?php echo ACCIDENT_DATE_NOT_GREATER; ?>");
			document.getElementById('accidentDate').focus();
			return false;
		}
		if(!dateDifference(document.getElementById('accidentDate1').value,cdate,"-")) {
			messageBox("<?php echo ACCIDENT_DATE_NOT_GREATER; ?>");
			document.getElementById('accidentDate1').focus();
			return false;
		}
    }
    if(act=='Add') {
        addVehicleAccident();
        return false;
    }
    else if(act=='Edit') {
        editVehicleAccident();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addVehicleInsurance() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (26.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addVehicleAccident() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleAccident/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	busNo: (document.addVehicleAccident.busNo.value),
							transportStaff: (document.addVehicleAccident.transportStaff.value),
							busRoute: (document.addVehicleAccident.busRoute.value),
							accidentDate: (document.addVehicleAccident.accidentDate.value),
							remarks: (document.addVehicleAccident.remarks.value)
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
                         hiddenFloatingDiv('AddVehicleAccident');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
							document.addVehicleAccident.busNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_TRANSPORT_STAFF ?>"){
							document.addVehicleAccident.transportStaff.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_ROUTE ?>"){
							document.addVehicleAccident.busRoute.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_ACCIDENT_DATE ?>"){
							document.addVehicleAccident.accidentDate.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//---------------------------------------------------------------
//THIS FUNCTION IS FOR HELP DETAILS
//Author : Gagan Gill
//Created on : 15 Nov 2010
//--------------------------------------------------------------


function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}
//-------------------------------------------------------
//THIS FUNCTION DELETEPERIOD() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILED THROUGH ID
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteVehicleAccident(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleAccident/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {accidentId: id},
             
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
   document.addVehicleAccident.vehicleType.value = '';
   document.addVehicleAccident.busNo.length = null;
   addOption(document.addVehicleAccident.busNo, '', 'Select');
   var d=new Date();
   var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
   //document.addVehicleAccident.accidentDate.value = cdate;
   document.addVehicleAccident.transportStaff.value = '';
   document.addVehicleAccident.busRoute.value = '';
   document.addVehicleAccident.remarks.value = '';
   document.addVehicleAccident.vehicleType.focus();	
}

//-------------------------------------------------------
//THIS FUNCTION EDITDESIGNATION() IS USED TO populate edit the values & 
//save the values into the database by using designationId
//
//Author : Jaineesh
// Created on : (26.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editVehicleAccident() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleAccident/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	accidentId: (document.editVehicleAccident.accidentId.value),
							busNo: (document.editVehicleAccident.busNo.value),
							transportStaff: (document.editVehicleAccident.transportStaff.value),
							busRoute: (document.editVehicleAccident.busRoute.value),
							accidentDate1: (document.editVehicleAccident.accidentDate1.value),
							remarks: (document.editVehicleAccident.remarks.value)
				 },
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditVehicleAccident');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
							document.editVehicleAccident.busNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_TRANSPORT_STAFF ?>"){
							document.editVehicleAccident.transportStaff.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_ROUTE ?>"){
							document.editVehicleAccident.busRoute.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_ACCIDENT_DATE ?>"){
							document.editVehicleAccident.accidentDate.focus();
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
// Created on : (26.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
	 url = '<?php echo HTTP_LIB_PATH;?>/VehicleAccident/ajaxGetValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {accidentId: id},
		 asynchronous:false,
		 
		   onCreate: function() {
			  showWaitDialog(true);
		   },
			  
		 onSuccess: function(transport){
		   
				hideWaitDialog(true);
				if(trim(transport.responseText)==0){
					hiddenFloatingDiv('EditVehicleAccident'); 
					messageBox("<?php echo DISCARD_VEHICLE_NOT_EDIT;?>");
					
				  // exit();
				   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			   }
				j = eval('('+transport.responseText+')');
			   
			   document.editVehicleAccident.vehicleType.value = j.vehicleTypeId;
			   document.editVehicleAccident.busNo.value = j.busId;
			   document.editVehicleAccident.transportStaff.value = j.staffId;
			   document.editVehicleAccident.busRoute.value = j.busRouteId;
			   document.editVehicleAccident.accidentDate1.value = j.accidentDate;
			   document.editVehicleAccident.remarks.value = j.remarks;
			   document.editVehicleAccident.accidentId.value = j.accidentId;
			   document.editVehicleAccident.vehicleType.focus();
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleDetails() {
	form = document.addVehicleAccident;
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.busNo.length = null;
		addOption(form.busNo, '', 'Select');
		return false;
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			
			if(j==0) {
				form.busNo.length = null;
				addOption(form.busNo, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.busNo.length = null;
			for(i=0;i<len;i++) {
				addOption(form.busNo, j[i].busId, j[i].busNo);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getEditVehicleDetails() {
	form = document.editVehicleAccident;
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.busNo.length = null;
		addOption(form.busNo, '', 'Select');
		return false;
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			
			if(j==0) {
				form.busNo.length = null;
				addOption(form.busNo, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.busNo.length = null;
			for(i=0;i<len;i++) {
				addOption(form.busNo, j[i].busId, j[i].busNo);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
     if(document.getElementById('helpChk').checked == false) {
         return false;
     }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}
function printReport() {
	var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayDesignationReport.php?'+qstr;
    window.open(path,"DisplayDesignationReport","status=1,menubar=1,scrollbars=1, width=900");
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/VehicleAccident/listVehicleAccidentContents.php");
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
// $History: listVehicleAccident.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Interface
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Interface
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/18/09   Time: 12:34p
//Updated in $/Leap/Source/Interface
//put new fields accident date as datetime, add remarks field
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 1:02p
//Created in $/Leap/Source/Interface
//new file for vehicle accident
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Interface
//new file for vehicle insurance
//
//
?>