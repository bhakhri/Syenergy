<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DESIGNATION ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Extended Vehicle Service </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array	(	new Array('srNo','#','width="5%"','',false), 
									new Array('busNo','Registration No.','width=50%','',true),
									new Array('totalFreeServices','Total Free Services','width=40%','',true)
								);
								//new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ExtendVehicleService/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddExtendVehicleService';
//editFormName   = 'EditVehicleAccident';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteVehicleAccident';
divResultName  = 'results';
page=1; //default page
sortField = 'busNo';
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
// Created on : (24.11.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(	new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE ?>"),
									new Array("busNo","<?php echo SELECT_BUS ?>")
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
    }
    if(act=='Add') {
        addExtendVehicleService();
        return false;
    }
    
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function checkList(frm, act) {
     
    var fieldsArray = new Array(	new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE ?>"),
									new Array("busNo","<?php echo SELECT_BUS ?>"),
									new Array("extendedServices","<?php echo SELECT_EXTENDED_SERVICES ?>")
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
    }
    if(act=='Add') {
        getExtendedFreeServices();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addVehicleInsurance() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (26.11.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addExtendVehicleService() {
	form = document.addExtendVehicleService;
	var pars = generateQueryString('addExtendVehicleService');
	 url = '<?php echo HTTP_LIB_PATH;?>/ExtendVehicleService/ajaxInitAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 
		   OnCreate: function(){
			  showWaitDialog(true);
		   },

		   onSuccess: function(transport){
				 hideWaitDialog(true);
		   
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 /*flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
					 blankValues();
				 }
				 else {*/
					 hiddenFloatingDiv('AddExtendVehicleService');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 //location.reload();
					 return false;
					 }
				 else {
					messageBox(trim(transport.responseText));
					if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
						document.addExtendVehicleService.busNo.focus();
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
	document.addExtendVehicleService.vehicleType.value = '';
	document.addExtendVehicleService.busNo.value = '';
	document.addExtendVehicleService.extendedServices.value = '';
	document.getElementById('vehicleService').innerHTML = '';
	document.getElementById('showVehicleService').innerHTML = '';
	document.getElementById('extendedVehicleService').style.display = 'none';

	document.addExtendVehicleService.vehicleType.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITDESIGNATION() IS USED TO populate edit the values & 
//save the values into the database by using designationId
//
//Author : Jaineesh
// Created on : (26.11.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleDetails() {
	form = document.addExtendVehicleService;
	document.getElementById('vehicleService').innerHTML = '';
	document.getElementById('showVehicleService').innerHTML = '';
	form.extendedServices.value = '';
	document.getElementById('extendedVehicleService').style.display = 'none';
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
			form.busNo.length = null;
			addOption(form.busNo, '', 'Select');
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleFreeService() {
	form = document.addExtendVehicleService;
	document.getElementById('showVehicleService').innerHTML = '';
	form.extendedServices.value = '';
	document.getElementById('extendedVehicleService').style.display = 'none';
	var url = '<?php echo HTTP_LIB_PATH;?>/ExtendVehicleService/getVehicleFreeService.php';
	var pars = 'busId='+form.busNo.value;
	/*if (form.vehicleType.value=='') {
		form.busNo.length = null;
		addOption(form.busNo, '', 'Select');
		return false;
	}*/
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
			document.getElementById('vehicleService').innerHTML = 'The selected vehicle has <b>'+j[0].countRecords+'</b> free services';
	},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getExtendedFreeServices() {
	document.getElementById('extendedVehicleService').style.display = '';
	form = document.addExtendVehicleService;
	url = '<?php echo HTTP_LIB_PATH;?>/ExtendVehicleService/getVehicleService.php';
	var pars = 'extendedServiceValue='+form.extendedServices.value+'&busId='+form.busNo.value;
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 //alert(transport.responseText);
			 //res = eval('('+transport.responseText+')');
			 document.getElementById('showVehicleService').innerHTML = trim(transport.responseText);
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
	   });
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ExtendVehicleService/listExtendVehicleServiceContents.php");
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
// $History: listExtendService.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/21/10    Time: 2:51p
//Created in $/Leap/Source/Interface
//new file to extend vehicle service
//
//
?>