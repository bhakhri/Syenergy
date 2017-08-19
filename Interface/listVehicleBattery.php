<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DESIGNATION ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (13.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleBattery');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Battery </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false), 
								new Array('busNo','Registration No.','width=10%','align="left"',true),
								new Array('batteryNo ','Battery No.','width=10%','align="left"',true),
								new Array('batteryMake','Make','width=12%','align="left"',true), 
								new Array('warrantyDate','Warranty Date','width="12%"','align="center"',true),
								new Array('replacementDate','Replacement Date','width="12%"','align="center"',true),
								new Array('action','Action','width="4%"','align="center"',false)
								);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleBattery/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddVehicleBattery';   
editFormName   = 'EditVehicleBattery';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleBattery';
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
    
   //alert(1);
    var fieldsArray = new Array(	new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE ?>"),
									new Array("busNo","<?php echo SELECT_BUS ?>"),
									new Array("batteryNo","<?php echo ENTER_BATTERY_NO ?>"),
									new Array("batteryMake","<?php echo ENTER_BATTERY_MAKE ?>"),
									//new Array("warrantyDate","<?php echo ENTER_WARRANTY_DATE ?>"),
									new Array("meterReading","<?php echo ENTER_METER_READING ?>"),
									new Array("replacementCost","<?php echo ENTER_REPLACEMENT_COST ?>")
									//new Array("replacementDate","<?php echo ENTER_REPLACEMENT_DATE ?>")
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
        else {
            //unsetAlertStyle(fieldsArray[i][0]);

			var d=new Date();
			var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
			
			if(!dateDifference(document.getElementById('replacementDate').value,cdate,"-")) {
			   messageBox("<?php echo REPLACEMENT_DATE_VALIDATION; ?>");
			   document.getElementById('replacementDate').focus();
			   return false;
			 }

			 if(!dateDifference(document.getElementById('replacementDate1').value,cdate,"-")) {
			   messageBox("<?php echo REPLACEMENT_DATE_VALIDATION; ?>");
			   document.getElementById('replacementDate1').focus();
			   return false;
			 }
			 
			 if(fieldsArray[i][0]=="meterReading") {
				 if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
						messageBox("<?php echo ENTER_NUMBER ?>");
						eval("frm."+(fieldsArray[i][0])+".focus();");
						return false;
						break;
					}
             }
			 
        }
    }
    if(act=='Add') {
		if(document.addVehicleBattery.replacementCost.value < 0) {
			messageBox("<?php echo BATTERY_COST_NOT_LESS ?>");
			document.addVehicleBattery.replacementCost.focus();
			return false;
		 }
        addVehicleBattery();
        return false;
    }
    else if(act=='Edit') {
		if(document.editVehicleBattery.replacementCost.value < 0) {
			messageBox("<?php echo BATTERY_COST_NOT_LESS ?>");
			document.editVehicleBattery.replacementCost.focus();
			return false;
		 }
        editVehicleBattery();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addDesignation() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addVehicleBattery() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleBattery/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	busNo: (document.addVehicleBattery.busNo.value),
							batteryNo: (document.addVehicleBattery.batteryNo.value),
							batteryMake: (document.addVehicleBattery.batteryMake.value),
							warrantyDate: (document.addVehicleBattery.warrantyDate.value),
							meterReading: (document.addVehicleBattery.meterReading.value),
							replacementCost: (document.addVehicleBattery.replacementCost.value),
							replacementDate: (document.addVehicleBattery.replacementDate.value)
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
                         hiddenFloatingDiv('Add/ReaplceVehicleBattery');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo VEHICLE_BATTERY_ALREADY_EXIST ?>"){
							document.addVehicleBattery.batteryNo.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//------------------------------------------------------
// THIS FUNCTION IS USED FOR HELP

//Author : Gagan Gill
//Created On : (15 Nov 2010)

//--------------------------------------------------------

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
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteVehicleBattery(id) {
         /*if(false===confirm("Do you really want to discard this tyre?")) {
             return false;
         }
         else {   */
        
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleBattery/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {batteryId: id},
             
             onCreate: function() {
                 showWaitDialog(true);
             },
             
             onSuccess: function(transport){
              
                  hideWaitDialog(true);
                     
                     /*if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                         return false;
                     }
					 else {*/
                         messageBox(trim(transport.responseText));
                     //}
              
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
        // }   
           
}

//-------------------------------------------------------
//THIS FUNCTION blanValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
   document.addVehicleBattery.vehicleType.value = '';
   document.addVehicleBattery.batteryNo.value = '';
   document.addVehicleBattery.batteryMake.value = '';
   var d=new Date();
   var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
   document.addVehicleBattery.warrantyDate.value = cdate;
   document.addVehicleBattery.meterReading.value = '';
   document.addVehicleBattery.replacementCost.value = '';
   document.addVehicleBattery.replacementDate.value = cdate;
   document.addVehicleBattery.vehicleType.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITVEHICLETYRE() IS USED TO populate edit the values
//
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editVehicleBattery() {
	 url = '<?php echo HTTP_LIB_PATH;?>/VehicleBattery/ajaxInitEdit.php';
	 
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {	batteryId: (document.editVehicleBattery.batteryId.value), 
						busNo: (document.editVehicleBattery.busNo.value),
						batteryNo: (document.editVehicleBattery.batteryNo.value),
						batteryMake: (document.editVehicleBattery.batteryMake.value),
						warrantyDate: (document.editVehicleBattery.warrantyDate1.value),
						meterReading: (document.editVehicleBattery.meterReading.value),
						replacementCost: (document.editVehicleBattery.replacementCost.value),
						replacementDate: (document.editVehicleBattery.replacementDate1.value)
					},
		 
		 onCreate: function() {
			  showWaitDialog(true);
		 },
			 
		 onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					hiddenFloatingDiv('EditVehicleBattery');
					sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
					return false;
				 }
				 else {
					 messageBox(trim(transport.responseText));
					 if (trim(transport.responseText)=="<?php echo VEHICLE_BATTERY_ALREADY_EXIST ?>"){
						document.editVehicleBattery.batteryNo.focus();	
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
	 url = '<?php echo HTTP_LIB_PATH;?>/VehicleBattery/ajaxGetValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 asynchronous:false,
		 parameters: {batteryId: id},
		 
		   onCreate: function() {
			  showWaitDialog(true);
		   },
			  
		 onSuccess: function(transport){
		   
				hideWaitDialog(true);
				if(trim(transport.responseText)==0){
					hiddenFloatingDiv('EditVehicleBattery');
					messageBox("<?php echo VEHICLE_BATTERY_NOT_EXIST;?>");
					
				  // exit();
				   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			   }
			   //alert(transport.responseText);
			   if (trim(transport.responseText)=="<?php echo BATTERY_NOT_EDIT; ?>") {
					hiddenFloatingDiv('EditVehicleBattery');
					messageBox("<?php echo BATTERY_NOT_EDIT;?>");
					sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					return false;
				}
		   
			   
				j = eval('('+transport.responseText+')');
			   document.editVehicleBattery.vehicleType.value = j.vehicleTypeId;
			   document.editVehicleBattery.busNo.value = j.busId;
			   document.editVehicleBattery.batteryNo.value = j.batteryNo;
			   document.editVehicleBattery.batteryMake.value = j.batteryMake;
			   document.editVehicleBattery.warrantyDate1.value = j.warrantyDate;
			   document.editVehicleBattery.meterReading.value = j.meterReading;
			   document.editVehicleBattery.replacementCost.value = j.replacementCost;
			   document.editVehicleBattery.replacementDate1.value = j.replacementDate;
			   document.editVehicleBattery.batteryId.value = j.batteryId;
			   document.editVehicleBattery.batteryNo.focus();
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
	form = document.addVehicleBattery;
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
	//var qstr = "searchbox="+trim(document.searchForm.searchbox.value);
     //qstr .= "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleBatteryReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayVehicleTyreReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	//var qstr = "searchbox="+trim(document.searchForm.searchbox.value);
     //qstr .= "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleBatteryCSVReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}


/*function printReport() {
	var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayDesignationReport.php?'+qstr;
    window.open(path,"DisplayDesignationReport","status=1,menubar=1,scrollbars=1, width=900");
}*/

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.addVehicleTyre;
 }
 else{
     var form = document.editVehicleTyre;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/VehicleBattery/listVehicleBatteryContents.php");
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
// $History: listVehicleBattery.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/04/10    Time: 6:27p
//Updated in $/Leap/Source/Interface
//fixed issues
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Interface
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Interface
//fixed bug in Fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Interface
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 1:21p
//Created in $/Leap/Source/Interface
//new file for vehicle battery
//
//
?>