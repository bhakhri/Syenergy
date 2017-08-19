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
define('MODULE','VehicleTyreMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Purchase/Replace Tyre </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var cdate="<?php echo date('Y-m-d'); ?>";

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false), 
								//new Array('busNo','Registration No.','width=10%','',true),
								new Array('tyreNumber','Tyre Number','width=12%','',true),
								new Array('busReadingOnInstallation','Reading','width=10%','align="right"',true),
								new Array('manufacturer','Manufacturer','width="12%"','',true),
								new Array('modelNumber','Model Number','width="12%"','',true),
								new Array('purchaseDate','Purchase Date','width="10%"','align="center"',true),
								new Array('usedAsMainTyre','Used as Main/Spare','width="15%"','align="center"',true),
								new Array('action1','Action','width="8%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/ajaxInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddVehicleTyre';   
editFormName   = 'EditVehicleTyre';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleTyre';
divResultName  = 'results';
page=1; //default page
sortField = 'tyreNumber';
sortOrderBy    = 'ASC';
var topPos = 0;
var leftPos = 0;
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id) {
    displayWindow('EditVehicleTyre',300,200);
    populateValues(id);
}

function editAddWindow(id) {
    displayWindow('AddVehicleTyre',300,200);
	blankValues();
	populateAddValues(id);
}

function addStockWindow(id) {
    displayWindow('AddStockVehicleTyre',300,200);
	blankStockValues();
	populateStockAddValues(id);
}

function getVehicleTyre(){

	if(isEmpty(document.getElementById('vehicleType').value)) {
		messageBox("<?php echo 'Please Select Vehicle Type';?>");
		document.getElementById('showTitle').style.display='none';
		document.getElementById('showData').style.display='none';
		document.listForm.vehicleType.focus();
		return false;
   }
   if(isEmpty(document.getElementById('vehicleNo').value)) {
		messageBox("<?php echo 'Please Select Registration No.';?>");
		document.getElementById('showTitle').style.display='none';
		document.getElementById('showData').style.display='none';
		document.listForm.vehicleNo.focus();
		return false;
   }
   else{
	   //document.getElementById('saveDiv').style.display='';
	   //document.getElementById('saveDiv1').style.display='';
	   document.getElementById('showTitle').style.display='';
	   document.getElementById('showData').style.display='';
	   document.getElementById('showPrint').style.display='';
	   //document.getElementById('txtReason').style.display='';
	   //document.getElementById('reason').value='';
       sendReq(listURL,divResultName,'listForm','');
   }
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
   
    var fieldsArray = new Array(	new Array("tyreNumber","<?php echo ENTER_TYRE_NUMBER ?>"),
									new Array("manufacturer","<?php echo ENTER_MANUFACTURER ?>"),
									new Array("modelNumber","<?php echo ENTER_MODEL_NUMBER ?>"),
									new Array("busNo","<?php echo ENTER_BUS_NUMBER ?>"),
									new Array("busReading","<?php echo ENTER_BUS_READING ?>")
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

             if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='vehicleType' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo VEHICLE_TYPE_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
			
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
			 /*if(fieldsArray[i][0]=="modelNumber") {
				 if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
						messageBox("<?php echo ENTER_NUMBER ?>");
						eval("frm."+(fieldsArray[i][0])+".focus();");
						return false;
						break;
					}
             }*/
        }
    }
    if(act=='Add') {
        addVehicleTyre();
        return false;
    }
    else if(act=='Edit') {
        editVehicleTyre();
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateStockAdddForm(frm, act) {
   
    var fieldsArray = new Array(	new Array("stockTyreNo","<?php echo SELECT_STOCK_TYRE_NUMBER ?>"),
									new Array("stockVehicleReading","<?php echo ENTER_VEHICLE_READING ?>")
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
			if(!dateDifference(document.getElementById('replacementDate').value,cdate,"-")) {
			   messageBox("<?php echo REPLACEMENT_DATE_VALIDATION; ?>");
			   document.getElementById('replacementDate').focus();
			   return false;
			 }
			 if(fieldsArray[i][0]=="stockVehicleReading") {
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
        addStockVehicleTyre();
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
function addVehicleTyre() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	addTyreId: (document.addVehicleTyre.addTyreId.value),
							tyreNumber: (document.addVehicleTyre.tyreNumber.value),
							manufacturer: (document.addVehicleTyre.manufacturer.value),
							modelNumber: (document.addVehicleTyre.modelNumber.value),
							busNo: (document.addVehicleTyre.busNo.value),
							busReading: (document.addVehicleTyre.busReading.value),
							//isActive: (document.addVehicleTyre.isActive[0].checked ? 1 : 0),
							usedAsMainTyre: (document.addVehicleTyre.usedAsMainTyre[0].checked ? 1 : 0),
							addTyreType: (document.addVehicleTyre.addTyreType.value),
							purchaseDate: (document.addVehicleTyre.purchaseDate.value),
							placementReason: (document.addVehicleTyre.placementReason.value)
						 },
             
               OnCreate: function(){
                  showWaitDialog(true);
               },

               onSuccess: function(transport){
                     hideWaitDialog(true);
               
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
						 messageBox(transport.responseText);
						 getStockVehicleTyres();
                         /*flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                         blankValues();
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     }
                     else {*/
						 hiddenFloatingDiv('AddVehicleTyre');
                         sendReq(listURL,divResultName,'listForm','');
						 //document.getElementById('results').innerHTML = '';
						 //document.getElementById('showTitle').style.display = 'none';
						 //document.getElementById('showData').style.display = 'none';
                         //location.reload();
                         return false;
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
//THIS FUNCTION addDesignation() IS USED TO ADD NEW Stock Tyre
//
//Author : Jaineesh
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addStockVehicleTyre() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/ajaxInitStockAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	addStockTyreId: (document.addStockVehicleTyre.addStockTyreId.value),
							stockTyreNo: (document.addStockVehicleTyre.stockTyreNo.value),
							stockRegnNo: (document.addStockVehicleTyre.stockRegnNo.value),
							stockVehicleReading: (document.addStockVehicleTyre.stockVehicleReading.value),
							replacementDate: (document.addStockVehicleTyre.replacementDate.value),
							replacementReason: (document.addStockVehicleTyre.replacementReason.value),
							addStockTyreType: (document.addStockVehicleTyre.addStockTyreType.value)
						 },
             
               OnCreate: function(){
                  showWaitDialog(true);
               },

               onSuccess: function(transport){
                     hideWaitDialog(true);
               
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
						 messageBox(transport.responseText);
						 getStockVehicleTyres();
                         /*flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                         blankStockValues();
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     }
                     else {*/
                         hiddenFloatingDiv('AddStockVehicleTyre');
						 sendReq(listURL,divResultName,'listForm','');
						 //document.getElementById('results').innerHTML = '';
						 //document.getElementById('showTitle').style.display = 'none';
						 //document.getElementById('showData').style.display = 'none';
                         //location.reload();
                         return false;
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

function getStockVehicleTyres(){
    url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getStockTyres.php';
    
         new Ajax.Request(url,
           {
             method:'post',   
             parameters: {},
              onCreate: function(transport){
                 // showWaitDialog(true);
             },
             onSuccess: function(transport){
                   // hideWaitDialog(true);
                    j = eval('('+ transport.responseText+')');
					len = j.length;
					document.addStockVehicleTyre.stockTyreNo.length = null;
					addOption(document.addStockVehicleTyre.stockTyreNo, '', 'Select');
					for(i=0;i<len;i++) { 
						addOption(document.addStockVehicleTyre.stockTyreNo, j[i].tyreId, j[i].tyreNumber);
					}
           },
           onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//-------------------------------------------------------
//THIS FUNCTION is used for HELP
//Author : Gagan Gill
//Created On : 15 nov 2010
//-------------------------------------------------------

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
   document.addVehicleTyre.tyreNumber.value = '';
   document.addVehicleTyre.manufacturer.value = '';
   document.addVehicleTyre.modelNumber.value = '';
   document.addVehicleTyre.purchaseDate.value = cdate;
   document.addVehicleTyre.busNo.value = '';
   document.addVehicleTyre.busReading.value = '';
   document.addVehicleTyre.usedAsMainTyre[0].checked = 1;
   document.addVehicleTyre.placementReason.value = '';
   document.addVehicleTyre.busReading.disabled = false;
   document.addVehicleTyre.tyreNumber.focus();
}

function blankStockValues() {
   document.getElementById('showStockTyreNo').innerHTML = '';
   document.addStockVehicleTyre.stockTyreNo.value = '';
   document.addStockVehicleTyre.stockVehicleReading.value = '';
   document.addVehicleTyre.purchaseDate.value = cdate;
   document.addStockVehicleTyre.replacementReason.value = '';
   document.addStockVehicleTyre.stockTyreNo.focus();
}

function clearText1() {
   document.getElementById('showTitle').style.display = 'none';
   document.getElementById('showData').style.display = 'none';
   document.getElementById('showPrint').style.display = 'none';
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
							//isActive: (document.editVehicleTyre.isActive[0].checked ? 1 : 0),
							usedAsMainTyre: (document.editVehicleTyre.usedAsMainTyre[0].checked ? 1 : 0),
							usedSpareTyre: (document.editVehicleTyre.usedSpareTyre.value),
							usedMainTyre: (document.editVehicleTyre.usedMainTyre.value),
							placementReason: (document.editVehicleTyre.placementReason.value)
						},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditVehicleTyre');
						//document.getElementById('results').innerHTML = '';
						//document.getElementById('showTitle').style.display = 'none';
						//document.getElementById('showData').style.display = 'none';
						//document.getElementById('vehicleNo').length = null;
						//addOption(document.getElementById('vehicleNo'), '', 'Select');
						document.getElementById('showSpareDiv').style.display = 'none';
						document.getElementById('showMainDiv').style.display = 'none';
						document.editVehicleTyre.usedSpareTyre.length = null;
						addOption(document.editVehicleTyre.usedSpareTyre, '', 'Select');
						document.getElementById('usedSpareTyre').disabled = true;
						document.editVehicleTyre.usedMainTyre.length = null;
						addOption(document.editVehicleTyre.usedMainTyre, '', 'Select');
						document.editVehicleTyre.usedMainTyre.disabled = true;
						//document.getElementById('vehicleNo').value = '';
						//document.getElementById('vehicleType').value = '';

                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
						sendReq(listURL,divResultName,'listForm','');
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
				   /*if (j.busReadingOnInstallation == 0) {
					document.editVehicleTyre.busReading.disabled = true;
					
				   }
				   else {
					document.editVehicleTyre.busReading.disabled = false;
					document.editVehicleTyre.busReading.value = j.busReadingOnInstallation;
				   }*/
                   document.editVehicleTyre.purchaseDate1.value = j.purchaseDate;
				   document.editVehicleTyre.tyreType.value = j.usedAsMainTyre;
				   document.editVehicleTyre.usedAsMainTyre[0].checked = (j.usedAsMainTyre == "1" ? true : false);
				   document.editVehicleTyre.usedAsMainTyre[1].checked = (j.usedAsMainTyre == "1" ? false : true);
				   if (document.editVehicleTyre.usedAsMainTyre[0].checked == true) {
						document.editVehicleTyre.usedMainTyre.length = null;
						addOption(document.editVehicleTyre.usedMainTyre, '', 'Select');
						document.getElementById('selectMainTyre').style.display = '';
						document.getElementById('usedMainTyre').disabled = true;
				   }
				   else {
						document.editVehicleTyre.usedSpareTyre.length = null;
						addOption(document.editVehicleTyre.usedSpareTyre, '', 'Select');
						document.getElementById('selectSpareTyre').style.display = '';
						document.getElementById('usedSpareTyre').disabled = true;
				   }
				   document.editVehicleTyre.placementReason.value = j.placementReason;
				   document.editVehicleTyre.tyreId.value = j.tyreId;
				   document.editVehicleTyre.busId.value = j.busId;
                   document.editVehicleTyre.tyreNumber.focus();
				   
				   /*else {
					getSpareTyreList();
				   }*/
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
function populateAddValues(id) {
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
						hiddenFloatingDiv('AddVehicleTyre'); 
                        messageBox("<?php echo VEHICLE_TYRE_NOT_EXIST;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   document.addVehicleTyre.busNo.value = j.busId;
					if(j.usedAsMainTyre == 1) {
						document.getElementById('showTyreNo').innerHTML = '';
						document.getElementById('showTyreNo').innerHTML = 'Note :- This New Tyre will be used in replace of <b>'+j.tyreNumber+'</b> as Main Tyre';
					}

					else {
						document.getElementById('showTyreNo').innerHTML = '';
						document.getElementById('showTyreNo').innerHTML = 'Note :- This New Tyre will be used in replace of <b>'+j.tyreNumber+'</b> as Spare Tyre';
					}
					document.getElementById('showOldTyreNo').innerHTML = 'Old Tyre will be used as Damage/Extra.'
	
				   //document.editVehicleTyre.usedAsMainTyre[0].checked = (j.usedAsMainTyre == "1" ? true : false);
				   //document.editVehicleTyre.usedAsMainTyre[1].checked = (j.usedAsMainTyre == "1" ? false : true);
				   /*if (document.editVehicleTyre.usedAsMainTyre[0].checked == true) {
						document.editVehicleTyre.usedMainTyre.length = null;
						addOption(document.editVehicleTyre.usedMainTyre, '', 'Select');
						document.getElementById('selectMainTyre').style.display = '';
						document.getElementById('usedMainTyre').disabled = true;
				   }
				   else {
						document.editVehicleTyre.usedSpareTyre.length = null;
						addOption(document.editVehicleTyre.usedSpareTyre, '', 'Select');
						document.getElementById('selectSpareTyre').style.display = '';
						document.getElementById('usedSpareTyre').disabled = true;
				   }*/
				   //document.editVehicleTyre.placementReason.value = j.placementReason;
				   document.addVehicleTyre.addTyreId.value = j.tyreId;
				   document.addVehicleTyre.addTyreType.value = j.usedAsMainTyre;
				   //document.editVehicleTyre.busId.value = j.busId;
                   document.addVehicleTyre.tyreNumber.focus();
				   
				   /*else {
					getSpareTyreList();
				   }*/
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
function populateStockAddValues(id) {
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
						hiddenFloatingDiv('AddStockVehicleTyre'); 
                        messageBox("<?php echo VEHICLE_TYRE_NOT_EXIST;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   document.addStockVehicleTyre.stockRegnNo.value = j.busId;
					if(j.usedAsMainTyre == 1) {
						document.getElementById('showStockTyreNo').innerHTML = '';
						document.getElementById('showStockTyreNo').innerHTML = 'Note:- This Stock Tyre will be used in replace of <b>'+j.tyreNumber+'</b> as Main Tyre';
					}
					else {
						document.getElementById('showStockTyreNo').innerHTML = '';
						document.getElementById('showStockTyreNo').innerHTML = 'Note:- This Stock Tyre will be used in replace of <b>'+j.tyreNumber+'</b> as Spare Tyre';
					}
					//document.getElementById('showOldTyreNo').innerHTML = 'Old Tyre will be used as Damage/Extra.'
	
				   //document.editVehicleTyre.usedAsMainTyre[0].checked = (j.usedAsMainTyre == "1" ? true : false);
				   //document.editVehicleTyre.usedAsMainTyre[1].checked = (j.usedAsMainTyre == "1" ? false : true);
				   /*if (document.editVehicleTyre.usedAsMainTyre[0].checked == true) {
						document.editVehicleTyre.usedMainTyre.length = null;
						addOption(document.editVehicleTyre.usedMainTyre, '', 'Select');
						document.getElementById('selectMainTyre').style.display = '';
						document.getElementById('usedMainTyre').disabled = true;
				   }
				   else {
						document.editVehicleTyre.usedSpareTyre.length = null;
						addOption(document.editVehicleTyre.usedSpareTyre, '', 'Select');
						document.getElementById('selectSpareTyre').style.display = '';
						document.getElementById('usedSpareTyre').disabled = true;
				   }*/
				   //document.editVehicleTyre.placementReason.value = j.placementReason;
				   document.addStockVehicleTyre.addStockTyreId.value = j.tyreId;
				   document.addStockVehicleTyre.addStockTyreType.value = j.usedAsMainTyre;
                   document.addVehicleTyre.tyreNumber.focus();
				   
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
	document.getElementById('showTitle').style.display = 'none';
	document.getElementById('showData').style.display = 'none';
	document.getElementById('showPrint').style.display = 'none';
	form = document.listForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.vehicleNo.length = null;
		addOption(form.vehicleNo, '', 'Select');
		document.getElementById('showTitle').style.display='none';
		document.getElementById('showData').style.display='none';
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
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.vehicleNo.length = null;
			for(i=0;i<len;i++) {
				addOption(form.vehicleNo, j[i].busId, j[i].busNo);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function clearText() {
	document.getElementById('showMainDiv').style.display = 'none';
	document.getElementById('showSpareDiv').style.display = 'none';
	document.editVehicleTyre.usedSpareTyre.length = null;
	addOption(document.editVehicleTyre.usedSpareTyre, '', 'Select');
	document.editVehicleTyre.usedSpareTyre.disabled = true;
	document.getElementById('selectSpareTyre').style.display = 'none';
	document.editVehicleTyre.usedMainTyre.length = null;
	addOption(document.editVehicleTyre.usedMainTyre, '', 'Select');
	document.editVehicleTyre.usedMainTyre.disabled = true;
	document.getElementById('selectMainTyre').style.display = 'none';
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getSpareTyreList() {

	if (document.editVehicleTyre.tyreType.value == '0') {
	//form = document.listForm;
	//document.getElementById('selectSpareTyre').style.display = '';
		document.getElementById('showMainDiv').style.display = 'none';
		document.getElementById('selectSpareTyre').style.display = '';
		document.getElementById('selectMainTyre').style.display = 'none';
		document.editVehicleTyre.usedSpareTyre.length = 0;
		addOption(document.editVehicleTyre.usedSpareTyre, '', 'Select');
		document.editVehicleTyre.usedSpareTyre.disabled = true;
	}
	else {
		document.getElementById('showSpareDiv').style.display = '';
		document.getElementById('selectMainTyre').style.display = 'none';
		document.getElementById('selectSpareTyre').style.display = '';
		document.editVehicleTyre.usedSpareTyre.length = 0;
		document.editVehicleTyre.usedSpareTyre.disabled = false;
		var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleSpareTyres.php';
		var pars = 'busId='+document.editVehicleTyre.busId.value+'&tyreTypeId='+document.editVehicleTyre.tyreType.value;

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
				//alert(transport.responseText);
				len = j.length;
				//form.vehicleNo.length = null;
				for(i=0;i<len;i++) {
					addOption(document.editVehicleTyre.usedSpareTyre, j[i].tyreId, j[i].tyreNumber);
				}
				// now select the value
				//form.blockName.value = j[0].blockId;
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getMainTyreList() {
	
	if (document.editVehicleTyre.tyreType.value == '1') {
		document.getElementById('showSpareDiv').style.display = 'none';
		document.getElementById('selectSpareTyre').style.display = 'none';
		document.getElementById('selectMainTyre').style.display = '';
		document.editVehicleTyre.usedMainTyre.length = 0;
		addOption(document.editVehicleTyre.usedMainTyre, '', 'Select');
		document.editVehicleTyre.usedMainTyre.disabled = true;
	}
	else {

		document.getElementById('showMainDiv').style.display = '';
		document.getElementById('selectSpareTyre').style.display = 'none';
		document.getElementById('selectMainTyre').style.display = '';
		document.editVehicleTyre.usedMainTyre.length = 0;
		document.editVehicleTyre.usedMainTyre.disabled = false;
		var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleMainTyres.php';
		var pars = 'busId='+document.editVehicleTyre.busId.value+'&tyreTypeId='+document.editVehicleTyre.tyreType.value;

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
				//alert(transport.responseText);
				len = j.length;
				//form.vehicleNo.length = null;
				for(i=0;i<len;i++) {
					addOption(document.editVehicleTyre.usedMainTyre, j[i].tyreId, j[i].tyreNumber);
				}
				// now select the value
				//form.blockName.value = j[0].blockId;
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
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
	//var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    var qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleTyreReport.php?vehicleType='+document.getElementById('vehicleType').value+'&vehicleNo='+document.getElementById('vehicleNo').value+qstr;
    window.open(path,"DisplayVehicleTyreReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	//var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    var qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleTyreCSVReport.php?vehicleType='+document.getElementById('vehicleType').value+'&vehicleNo='+document.getElementById('vehicleNo').value+qstr;
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
    require_once(TEMPLATES_PATH . "/VehicleTyre/listVehicleTyreContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		//sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	//-->
	</SCRIPT>
</body>
</html>
<?php 
// $History: listVehicleTyre.php $
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 2/04/10    Time: 6:27p
//Updated in $/Leap/Source/Interface
//fixed issues
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 2/03/10    Time: 7:00p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002555, 0002722
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 1/15/10    Time: 4:39p
//Updated in $/Leap/Source/Interface
//make changes and list will be showing not close.
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/13/10    Time: 11:00a
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Interface
//bug fixed for fleet management
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/07/10    Time: 2:44p
//Updated in $/Leap/Source/Interface
//fixed bug for fleet management
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Interface
//fixed bug on fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Interface
//fixed bug during self testing
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/04/09   Time: 6:56p
//Updated in $/Leap/Source/Interface
//changes for vehicle tyre
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/01/09   Time: 6:59p
//Updated in $/Leap/Source/Interface
//changes in interface of vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/25/09   Time: 3:30p
//Created in $/Leap/Source/Interface
//new file for vehicle tyre
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:44p
//Created in $/Leap/Source/Interface
//add new file for vehicle
//
?>