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
define('MODULE','InsuranceVehicle');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Insurance </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="4%"','',false), 
								new Array('busNo','Registration No.','width=10%','',true),
								new Array('lastInsuranceDate','Insurance Date','width=10%','align="center"',true),
                                new Array('insuranceDueDate','Insurance Due Date','width=10%','align="center"',true),
								new Array('insuringCompanyName','Insurance Company','width=10%','',true),
								new Array('valueInsured','Value Insured','width=10%','',true),
								new Array('insurancePremium','Insurance Premium','width=10%','',true),
								new Array('ncb','NCB(%)','width=10%','',true),
								new Array('paymentMode','Payment Mode','width=10%','',true),
								new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/InsuranceVehicle/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddInsuranceVehicle';
editFormName   = 'EditInsuranceVehicle';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteInsuranceVehicle';
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
									new Array("insuringCompany","<?php echo SELECT_INSURANCE_COMPANY ?>"),
									new Array("policyNo","<?php echo ENTER_POLICY_NUMBER ?>"),
									new Array("valueInsured","<?php echo ENTER_VALUE_INSURED ?>"),
									new Array("insurancePremium","<?php echo ENTER_INSURANCE_PREMIUM ?>"),
									new Array("branchName","<?php echo ENTER_BRANCH_NAME ?>"),
									new Array("agentName","<?php echo ENTER_INSURANCE_AGENT_NAME ?>"),
									new Array("paymentMode","<?php echo SELECT_PAYMENT_MODE ?>")
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
            if(fieldsArray[i][0]=="valueInsured") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }
            else if(fieldsArray[i][0]=="insurancePremium") {
             if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }

			/*var d=new Date();
			var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
			
			if(!dateDifference(document.getElementById('insuranceDate').value,cdate,"-")) {
			   messageBox("<?php echo INSURANCE_DATE_VALIDATION; ?>");
			   document.getElementById('insuranceDate').focus();
			   return false;
			 }

			 if(!dateDifference(document.getElementById('insuranceDate1').value,cdate,"-")) {
			   messageBox("<?php echo INSURANCE_DATE_VALIDATION; ?>");
			   document.getElementById('insuranceDate1').focus();
			   return false;
			 }*/
			 if(!dateDifference(document.getElementById('insuranceDate').value,document.getElementById('insuranceDueDate').value,"-")) {
			   messageBox("<?php echo VEHICLE_INSURANCE_DATE_NOT_GREATER; ?>");
			   document.getElementById('insuranceDate').focus();
			   return false;
			 }

			 if(!dateDifference(document.getElementById('insuranceDate1').value,document.getElementById('insuranceDueDate1').value,"-")) {
			   messageBox("<?php echo VEHICLE_INSURANCE_DATE_NOT_GREATER; ?>");
			   document.getElementById('insuranceDate').focus();
			   return false;
			 }
			

        }
    }
    if(act=='Add') {
        addInsuranceVehicle();
        return false;
    }
    else if(act=='Edit') {
        editInsuranceVehicle();
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
function addInsuranceVehicle() {
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceVehicle/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	busNo: (document.addInsuranceVehicle.busNo.value),
							insuranceDate: (document.addInsuranceVehicle.insuranceDate.value),
							insuranceDueDate: (document.addInsuranceVehicle.insuranceDueDate.value),
							insuringCompanyId: (document.addInsuranceVehicle.insuringCompany.value),
							policyNo: (document.addInsuranceVehicle.policyNo.value),
							valueInsured: (document.addInsuranceVehicle.valueInsured.value),
							insurancePremium: (document.addInsuranceVehicle.insurancePremium.value),
							ncb: (document.addInsuranceVehicle.ncb.value),
							paymentMode: (document.addInsuranceVehicle.paymentMode.value),
							branchName: (document.addInsuranceVehicle.branchName.value),
							agentName: (document.addInsuranceVehicle.agentName.value),
							paymentDescription: (document.addInsuranceVehicle.paymentDescription.value)
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
                         hiddenFloatingDiv('AddInsuranceVehicle');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
							document.addInsuranceVehicle.busNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_INSURANCE_DATE ?>"){
							document.addInsuranceVehicle.insuranceDate.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_INSURANCE_COMPANY ?>"){
							document.addInsuranceVehicle.insuringCompany.focus();
						}
						else if (trim(transport.responseText)=="<?php echo ENTER_VALUE_INSURED ?>"){
							document.addInsuranceVehicle.valueInsured.focus();
						}
						else if (trim(transport.responseText)=="<?php echo ENTER_INSURANCE_PREMIUM ?>"){
							document.addInsuranceVehicle.insurancePremium.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_PAYMENT_MODE ?>"){
							document.addInsuranceVehicle.paymentMode.focus();
						}
						else if (trim(transport.responseText)=="<?php echo VEHICLE_INSURANCE_DATE_NOT_GREATER ?>"){
							document.addInsuranceVehicle.insuranceDate.focus();
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteInsuranceVehicle(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceVehicle/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {insuranceId: id},
             
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
   document.addInsuranceVehicle.vehicleType.value = '';
   document.addInsuranceVehicle.busNo.length = null;
   addOption(document.addInsuranceVehicle.busNo, '', 'Select');
   var d=new Date();
   var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
   document.addInsuranceVehicle.insuranceDate.value = cdate;

   var d=new Date();
   var insuranceDuedate=(d.getFullYear()+1)+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
   
   document.addInsuranceVehicle.insuranceDueDate.value = insuranceDuedate;
   document.addInsuranceVehicle.insuringCompany.value = '';
   document.addInsuranceVehicle.policyNo.value = '';
   document.addInsuranceVehicle.valueInsured.value = '';
   document.addInsuranceVehicle.insurancePremium.value = '';
   document.addInsuranceVehicle.ncb.value = '';
   document.addInsuranceVehicle.paymentMode.value = '';
   document.addInsuranceVehicle.branchName.value = '';
   document.addInsuranceVehicle.agentName.value = '';
   document.addInsuranceVehicle.paymentDescription.value = '';
   document.addInsuranceVehicle.vehicleType.focus();	
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

function editInsuranceVehicle() {
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceVehicle/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	insuranceId: (document.editInsuranceVehicle.insuranceId.value),
							busNo: (document.editInsuranceVehicle.busNo.value),
							insuranceDate: (document.editInsuranceVehicle.insuranceDate1.value),
							insuranceDueDate: (document.editInsuranceVehicle.insuranceDueDate1.value),
							insuringCompanyId: (document.editInsuranceVehicle.insuringCompany.value),
							policyNo: (document.editInsuranceVehicle.policyNo.value),
							valueInsured: (document.editInsuranceVehicle.valueInsured.value),
							insurancePremium: (document.editInsuranceVehicle.insurancePremium.value),
							ncb: (document.editInsuranceVehicle.ncb.value),
							paymentMode: (document.editInsuranceVehicle.paymentMode.value),
							branchName: (document.editInsuranceVehicle.branchName.value),
							agentName: (document.editInsuranceVehicle.agentName.value),
							paymentDescription: (document.editInsuranceVehicle.paymentDescription.value)},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditInsuranceVehicle');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo SELECT_BUS ?>"){
							document.editInsuranceVehicle.busNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_INSURANCE_DATE ?>"){
							document.editInsuranceVehicle.insuranceDate.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_INSURANCE_COMPANY ?>"){
							document.editInsuranceVehicle.insuringCompany.focus();
						}
						else if (trim(transport.responseText)=="<?php echo ENTER_VALUE_INSURED ?>"){
							document.editInsuranceVehicle.valueInsured.focus();
						}
						else if (trim(transport.responseText)=="<?php echo ENTER_INSURANCE_PREMIUM ?>"){
							document.editInsuranceVehicle.insurancePremium.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_PAYMENT_MODE ?>"){
							document.editInsuranceVehicle.paymentMode.focus();
						}
						else if (trim(transport.responseText)=="<?php echo VEHICLE_INSURANCE_DATE_NOT_GREATER ?>"){
							document.editInsuranceVehicle.insuranceDate.focus();
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
         url = '<?php echo HTTP_LIB_PATH;?>/InsuranceVehicle/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {insuranceId: id},
			 asynchronous:false,
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditInsuranceVehicle'); 
                        messageBox("<?php echo DISCARD_VEHICLE_NOT_EDIT;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   document.editInsuranceVehicle.vehicleType.value = j.vehicleTypeId;
                   document.editInsuranceVehicle.busNo.value = j.busId;
				   document.editInsuranceVehicle.insuranceDate1.value = j.lastInsuranceDate;
				   document.editInsuranceVehicle.insuranceDueDate1.value = j.insuranceDueDate;
				   document.editInsuranceVehicle.insuringCompany.value = j.insuringCompanyId;
				   document.editInsuranceVehicle.valueInsured.value = j.valueInsured;
				   document.editInsuranceVehicle.policyNo.value = j.policyNo;
				   document.editInsuranceVehicle.insurancePremium.value = j.insurancePremium;
				   document.editInsuranceVehicle.ncb.value = j.ncb;
				   document.editInsuranceVehicle.paymentMode.value = j.paymentMode;
				   document.editInsuranceVehicle.branchName.value = j.branchName;
				   document.editInsuranceVehicle.agentName.value = j.agentName;
				   document.editInsuranceVehicle.paymentDescription.value = j.paymentDescription;
				   document.editInsuranceVehicle.insuranceId.value = j.insuranceId;
                   document.editInsuranceVehicle.insuranceDate1.focus();
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
	form = document.addInsuranceVehicle;
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
    require_once(TEMPLATES_PATH . "/InsuranceVehicle/listInsuranceVehicleContents.php");
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
// $History: listInsuranceVehicle.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Interface
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Interface
//bug fixed for fleet management
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
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Interface
//fixed bug during self testing
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:15p
//Created in $/Leap/Source/Interface
//new file for vehicle insurance
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Interface
//new file for vehicle insurance
//
//
?>