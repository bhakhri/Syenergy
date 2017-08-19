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
define('MODULE','VehicleTax');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Tax </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false), 
								new Array('busNo','Registration No.','width=10%','',true),
								new Array('busNoValidTill ','Registration No. Valid Till','width=12%','align="center"',true),
								new Array('passengerTaxValidTill','Passenger Tax Valid Till','width=12%','align="center"',true), 
								new Array('roadTaxValidTill','Road Tax Valid Till','width="12%"','align="center"',true),
								new Array('pollutionCheckValidTill','Pollution Tax Valid Till','width="12%"','align="center"',true),
								new Array('passingValidTill','Passing Valid Till','width="12%"','align="center"',true)
								//new Array('action','Action','width="5%"','align="center"',false)
								);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleTax/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddVehicleTax';   
editFormName   = 'EditVehicleTax';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleTyre';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(	new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE ?>"),
									new Array("busNo","<?php echo SELECT_BUS ?>"),
									new Array("regnNoValidTill","<?php echo ENTER_REGDNO_VALID_TILL ?>"),
									new Array("passengerTaxValidTill","<?php echo ENTER_PASSENGER_TAX_VALID_TILL ?>"),
									new Array("roadTaxValidTill","<?php echo ENTER_ROAD_TAX_VALID_TILL ?>"),
									new Array("pollutionCheckValidTill","<?php echo ENTER_POLLUTION_CHECK_VALID_TILL ?>"),
									new Array("passingValidTill","<?php echo ENTER_PASSING_VALID_TILL ?>")
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
        /*else {
            //unsetAlertStyle(fieldsArray[i][0]);

             if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='vehicleType' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo VEHICLE_TYPE_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
			var d=new Date();
			var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
			
			if(!dateDifference(document.getElementById('purchaseDate').value,cdate,"-")) {
			   messageBox("<?php echo PURCHASE_DATE_VALIDATION; ?>");
			   document.getElementById('purchaseDate').focus();
			   return false;
			 }
			 if(!dateDifference(document.getElementById('purchaseDate1').value,cdate,"-")) {
			   messageBox("<?php echo PURCHASE_DATE_VALIDATION; ?>");
			   document.getElementById('purchaseDate1').focus();
			   return false;
			 }
			 if(fieldsArray[i][0]=="busReading") {
				 if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
						messageBox("<?php echo ENTER_NUMBER ?>");
						eval("frm."+(fieldsArray[i][0])+".focus();");
						return false;
						break;
					}
             }
			 if(fieldsArray[i][0]=="modelNumber") {
				 if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
						messageBox("<?php echo ENTER_NUMBER ?>");
						eval("frm."+(fieldsArray[i][0])+".focus();");
						return false;
						break;
					}
             }
        }*/
    }
    if(act=='Add') {
        addVehicleTax();
        return false;
    }
    /*else if(act=='Edit') {
        editVehicleTax();
        return false;
    }*/
}

//-------------------------------------------------------
//THIS FUNCTION addDesignation() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addVehicleTax() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleTax/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	busNo: (document.addVehicleTax.busNo.value),
							regnNoValidTill: (document.addVehicleTax.regnNoValidTill.value),
							passengerTaxValidTill: (document.addVehicleTax.passengerTaxValidTill.value),
							roadTaxValidTill: (document.addVehicleTax.roadTaxValidTill.value),
							pollutionCheckValidTill: (document.addVehicleTax.pollutionCheckValidTill.value),
							passingValidTill: (document.addVehicleTax.passingValidTill.value)
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
                         hiddenFloatingDiv('AddVehicleTax');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo VEHICLE_TYRE_ALREADY_EXIST ?>"){
							document.addVehicleTyre.tyreNumber.focus();
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
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteVehicleTyre(id) {
         if(false===confirm("Do you really want to discard this tyre?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {tyreId: id},
             
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
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
  // document.addVehicleTax.busNo.value = '';
   document.addVehicleTax.vehicleType.value = '';
   document.addVehicleTax.busNo.length = null;
   addOption(document.addVehicleTax.busNo, '', 'Select');
   document.addVehicleTax.regnNoValidTill.value = '';
   document.addVehicleTax.passengerTaxValidTill.value = '';
   document.addVehicleTax.roadTaxValidTill.value = '';
   document.addVehicleTax.pollutionCheckValidTill.value = '';
   document.addVehicleTax.passingValidTill.value = '';
   document.addVehicleTax.vehicleType.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITVEHICLETYRE() IS USED TO populate edit the values
//
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editVehicleTyre() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	tyreId: (document.editVehicleTyre.tyreId.value), 
							tyreNumber: (document.editVehicleTyre.tyreNumber.value), 
							manufacturer: (document.editVehicleTyre.manufacturer.value),
							modelNumber: (document.editVehicleTyre.modelNumber.value),
							busNo: (document.editVehicleTyre.busNo.value),
							busReading: (document.editVehicleTyre.busReading.value),
							purchaseDate: (document.editVehicleTyre.purchaseDate1.value),
							isActive: (document.editVehicleTyre.isActive[0].checked ? 1 : 0),
							usedAsMainTyre: (document.editVehicleTyre.usedAsMainTyre[0].checked ? 1 : 0),
							placementReason: (document.editVehicleTyre.placementReason.value)
						},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditVehicleTyre');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo VEHICLE_TYRE_ALREADY_EXIST ?>"){
							document.editVehicleTyre.tyreNumber.focus();	
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
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {tyreId: id},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditVehicleType'); 
                        messageBox("<?php echo VEHICLE_TYRE_NOT_EXIST;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   document.editVehicleTyre.tyreNumber.value = j.tyreNumber;
                   document.editVehicleTyre.manufacturer.value = j.manufacturer;
				   document.editVehicleTyre.modelNumber.value = j.modelNumber;
				   document.editVehicleTyre.busNo.value = j.busId;
				   document.editVehicleTyre.busReading.value = j.busReadingOnInstallation;
                   document.editVehicleTyre.purchaseDate1.value = j.purchaseDate;
				   document.editVehicleTyre.isActive[0].checked = (j.isActive == "1" ? true : false);
				   document.editVehicleTyre.isActive[1].checked = (j.isActive == "1" ? false : true);
				   document.editVehicleTyre.usedAsMainTyre[0].checked = (j.usedAsMainTyre == "1" ? true : false);
				   document.editVehicleTyre.usedAsMainTyre[1].checked = (j.usedAsMainTyre == "1" ? false : true);
				   document.editVehicleTyre.placementReason.value = j.placementReason;
				   document.editVehicleTyre.tyreId.value = j.tyreId;
                   document.editVehicleTyre.tyreNumber.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Jaineesh
// Created on : (15.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBusDetail() {
	url = '<?php echo HTTP_LIB_PATH;?>/VehicleTax/getVehicleTaxDetail.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {
			 busId : trim(document.addVehicleTax.busNo.value)
		 },
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 res = eval('('+transport.responseText+')');
			 len = res.length;
			 if (len > 0 ) {
				document.addVehicleTax.regnNoValidTill.value = res[0]['busNoValidTill'];
				document.addVehicleTax.passengerTaxValidTill.value = res[0]['passengerTaxValidTill'];
				document.addVehicleTax.roadTaxValidTill.value = res[0]['roadTaxValidTill'];
				document.addVehicleTax.pollutionCheckValidTill.value = res[0]['pollutionCheckValidTill'];
				document.addVehicleTax.passingValidTill.value = res[0]['passingValidTill'];
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
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
	form = document.addVehicleTax;
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

function printReport() {
	//var qstr = "searchbox="+trim(document.searchForm.searchbox.value);
     //qstr .= "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleTaxReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayVehicleTaxReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	//var qstr = "searchbox="+trim(document.searchForm.searchbox.value);
     //qstr .= "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleTaxCSVReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

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
    require_once(TEMPLATES_PATH . "/VehicleTax/listVehicleTaxContents.php");
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
// $History: listVehicleTax.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/05/10    Time: 11:03a
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002484, 0002427
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Interface
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Interface
//fixed bug on fleet management
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:21p
//Created in $/Leap/Source/Interface
//new file for vehicle tax
//
//
?>