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
define('MODULE','InsuranceClaim');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Insurance Claim </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="4%"','',false), 
								new Array('busNo','Registration No.','width=10%','',true),
								new Array('claimDate','Claim Date','width=8%','align="center"',true),
								new Array('claimAmount','Claim Amount','width=10%','align="right"',true),
								new Array('totalExpenses','Total Expenses','width=10%','align="right"',true),
								new Array('selfExpenses','Self Expenses','width=10%','align="right"',true),
								new Array('loggingClaim','Logging Claim','width=10%','align="right"',true),
								new Array('dateOfSettlement','Settlement Date','width=12%','align="center"',true),
								new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddInsuranceClaim';
editFormName   = 'EditInsuranceClaim';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteInsuranceClaim';
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

var cdate = "<?php echo date('Y-m-d'); ?>";

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
									new Array("busNo","<?php echo SELECT_BUS ?>"),
									//new Array("claimDate","<?php echo SELECT_CLAIM_DATE ?>"),
									new Array("claimAmount","<?php echo ENTER_CLAIM_AMOUNT ?>"),
									new Array("totalExpenses","<?php echo ENTER_TOTAL_EXPENSES ?>"),
									new Array("selfExpenses","<?php echo ENTER_SELF_EXPENSES ?>"),
									new Array("loggingClaim","<?php echo ENTER_LOGGING_CLAIM ?>")
									//new Array("settlementDate","<?php echo SELECT_SETTLEMENT_DATE ?>")
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

            /* if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='vehicleType' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo VEHICLE_TYPE_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }*/
            if(fieldsArray[i][0]=="claimAmount") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }
            else if(fieldsArray[i][0]=="claimAmount") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
				}
            }

			else if(fieldsArray[i][0]=="totalExpenses") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }

			else if(fieldsArray[i][0]=="selfExpenses") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }

			else if(fieldsArray[i][0]=="ncbClaim") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }

			else if(fieldsArray[i][0]=="loggingClaim") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }
        }
    }
    if(act=='Add') {
		if(!dateDifference(document.addInsuranceClaim.claimDate.value,cdate,"-")) {
		   messageBox("<?php echo CLAIM_DATE_VALIDATION; ?>");
		   document.addInsuranceClaim.claimDate.focus();
		   return false;
		 }

		 if(!dateDifference(document.addInsuranceClaim.settlementDate.value,cdate,"-")) {
		   messageBox("<?php echo SETTLEMENT_DATE_VALIDATION; ?>");
		   document.addInsuranceClaim.settlementDate.focus();
		   return false;
		 }
        addInsuranceClaim();
        return false;
    }
    else if(act=='Edit') {
		if(!dateDifference(document.getElementById('claimDate1').value,cdate,"-")) {
		   messageBox("<?php echo CLAIM_DATE_VALIDATION; ?>");
		   document.getElementById('claimDate1').focus();
		   return false;
		 }

		 if(!dateDifference(document.getElementById('settlementDate1').value,cdate,"-")) {
		   messageBox("<?php echo SETTLEMENT_DATE_VALIDATION; ?>");
		   document.getElementById('settlementDate1').focus();
		   return false;
		 }
        editInsuranceClaim();
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
function addInsuranceClaim() {
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	busNo: (document.addInsuranceClaim.busNo.value),
							claimDate: (document.addInsuranceClaim.claimDate.value),
							claimAmount: (document.addInsuranceClaim.claimAmount.value),
							totalExpenses: (document.addInsuranceClaim.totalExpenses.value),
							selfExpenses: (document.addInsuranceClaim.selfExpenses.value),
							ncbClaim: (document.addInsuranceClaim.ncbClaim.value),
							loggingClaim: (document.addInsuranceClaim.loggingClaim.value),
							settlementDate: (document.addInsuranceClaim.settlementDate.value)
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
                         hiddenFloatingDiv('AddInsuranceClaim');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
							document.addInsuranceClaim.busNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_CLAIM_DATE ?>"){
							document.addInsuranceClaim.claimDate.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_SETTLEMENT_DATE ?>"){
							document.addInsuranceClaim.settlementDate.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_CLAIM_AMOUNT ?>"){
							document.addInsuranceClaim.claimAmount.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_TOTAL_EXPENSES ?>"){
							document.addInsuranceClaim.totalExpenses.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_SELF_EXPENSES ?>"){
							document.addInsuranceClaim.selfExpenses.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_NO_CLAIM_BONUS ?>"){
							document.addInsuranceClaim.ncbClaim.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_LOGGING_CLAIM ?>"){
							document.addInsuranceClaim.loggingClaim.focus();
						}
						else if (trim(transport.responseText)=="<?php echo TOTAL_EXPENSES_NOT_LESS ?>"){
							document.addInsuranceClaim.totalExpenses.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//-----------------------------------------------------
//THIS FUNCTION IS FOR HELP
//Author : Gagan Gill
//Created On : 15 Nov 2010
//----------------------------------------------------
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteInsuranceClaim(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {claimId: id},
             
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
   document.addInsuranceClaim.vehicleType.value = '';
   document.addInsuranceClaim.busNo.length = null;
   addOption(document.addInsuranceClaim.busNo, '', 'Select');
   document.addInsuranceClaim.claimDate.value = cdate;
   document.addInsuranceClaim.claimAmount.value = '';
   document.addInsuranceClaim.totalExpenses.value = '';
   document.addInsuranceClaim.selfExpenses.value = '';
   document.addInsuranceClaim.ncbClaim.value = '';
   document.addInsuranceClaim.loggingClaim.value = '';
   document.addInsuranceClaim.settlementDate.value = cdate;
   document.addInsuranceClaim.vehicleType.focus();	
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

function editInsuranceClaim() {
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	claimId: (document.editInsuranceClaim.claimId.value),
							busNo: (document.editInsuranceClaim.busNo.value),
							claimDate: (document.editInsuranceClaim.claimDate1.value),
							claimAmount: (document.editInsuranceClaim.claimAmount.value),
							totalExpenses: (document.editInsuranceClaim.totalExpenses.value),
							selfExpenses: (document.editInsuranceClaim.selfExpenses.value),
							ncbClaim: (document.editInsuranceClaim.ncbClaim.value),
							loggingClaim: (document.editInsuranceClaim.loggingClaim.value),
							settlementDate: (document.editInsuranceClaim.settlementDate1.value)
						},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditInsuranceClaim');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
							document.editInsuranceClaim.busNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_CLAIM_DATE ?>"){
							document.editInsuranceClaim.claimDate.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_SETTLEMENT_DATE ?>"){
							document.editInsuranceClaim.settlementDate.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_CLAIM_AMOUNT ?>"){
							document.editInsuranceClaim.claimAmount.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_TOTAL_EXPENSES ?>"){
							document.editInsuranceClaim.totalExpenses.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_SELF_EXPENSES ?>"){
							document.editInsuranceClaim.selfExpenses.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_NO_CLAIM_BONUS ?>"){
							document.editInsuranceClaim.ncbClaim.focus();
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_LOGGING_CLAIM ?>"){
							document.editInsuranceClaim.loggingClaim.focus();
						}
						else if (trim(transport.responseText)=="<?php echo TOTAL_EXPENSES_NOT_LESS ?>"){
							document.editInsuranceClaim.totalExpenses.focus();
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
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {claimId: id},
			 asynchronous:false,
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditInsuranceClaim'); 
                        messageBox("<?php echo INSURANCE_CLAIM_NOT_EDIT;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   document.editInsuranceClaim.vehicleType.value = j.vehicleTypeId;
				   //alert(document.editInsuranceClaim.vehicleType.value);
				   //alert(document.editInsuranceClaim.busNo.value);
				   //alert(j.busId);
                   //document.editInsuranceClaim.busNo.value = j.busId;
				   //alert(document.editInsuranceClaim.busNo.value);
				   document.editInsuranceClaim.claimDate1.value = j.claimDate;
				   document.editInsuranceClaim.claimAmount.value = j.claimAmount;
				   document.editInsuranceClaim.totalExpenses.value = j.totalExpenses;
				   document.editInsuranceClaim.selfExpenses.value = j.selfExpenses;
				   document.editInsuranceClaim.ncbClaim.value = j.ncb;
				   document.editInsuranceClaim.loggingClaim.value = j.loggingClaim;
				   document.editInsuranceClaim.settlementDate1.value = j.dateOfSettlement;
				   document.editInsuranceClaim.vehicleTypeId.value = j.vehicleTypeId;
				   document.editInsuranceClaim.claimId.value = j.claimId;
				   document.editInsuranceClaim.vehicleType.focus();
				   getEditVehicleDetails(j.busId);
				   
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
	form = document.addInsuranceClaim;
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

function getEditVehicleDetails(id) {

	form = document.editInsuranceClaim;
	if (id == 'xx') {
		form.vehicleType.value;
	}
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
			x = trim(transport.responseText);
			if(x=='0') {
				form.busNo.length = null;
				addOption(form.busNo, '', 'Select');
				//form.busNo.value = '';
				return false;
			}
			len = j.length;
			form.busNo.length = null;
			
			for(i=0;i<len;i++) {
				addOption(form.busNo, j[i].busId, j[i].busNo);
			}
			// now select the value
			if (id == 'xx') {
				form.busNo.value = j[0].busId;
			}
			else {
				form.busNo.value = id;
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET SELF EXPENSES DATA
// 
//Author : Jaineesh
// Created on : (22.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getSelfExpenses() {
	form = document.addInsuranceClaim;
		form.selfExpenses.value = form.totalExpenses.value - form.claimAmount.value;
	
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET SELF EXPENSES DATA
// 
//Author : Jaineesh
// Created on : (22.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getEditSelfExpenses() {
	form = document.editInsuranceClaim;
	/*if(form.totalExpenses.value < form.claimAmount.value) {
		messageBox("<?php echo TOTAL_EXPENSES_NOT_LESS;?>");
		form.totalExpenses.value = '';
		form.totalExpenses.focus();
		
		//document.getElementById('totalExpenses').focus();
		return false;
	}
	else {*/
		form.selfExpenses.value = form.totalExpenses.value - form.claimAmount.value;
	//}
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getNcbDetails() {
	form = document.addInsuranceClaim;
	var url = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/getNcbClaim.php';
	var pars = 'busId='+form.busNo.value;
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
			form.ncbClaim.value = j[0].ncb;
			
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

function getEditNcbDetails() {
	form = document.editInsuranceClaim;
	var url = '<?php echo HTTP_LIB_PATH;?>/InsuranceClaim/getNcbClaim.php';
	var pars = 'busId='+form.busNo.value;
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
			form.ncbClaim.value = j[0].ncb;
			
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
    require_once(TEMPLATES_PATH . "/InsuranceClaim/listInsuranceClaimContents.php");
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
// $History: listInsuranceClaim.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/10/10    Time: 2:37p
//Updated in $/Leap/Source/Interface
//fixed bug nos.0002836, 0002835, 0002802, 0002801
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/08/10    Time: 6:47p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002810, 0002808, 0002807, 0002806, 0002803, 0002804
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 2/06/10    Time: 7:25p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002805, 0002809
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/06/10    Time: 12:16p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002793, 0002796, 0002797, 0002795, 0002798
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/23/10    Time: 11:44a
//Created in $/Leap/Source/Interface
//add new file for insurance claim
//
//
?>