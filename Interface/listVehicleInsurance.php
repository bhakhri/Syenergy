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
define('MODULE','VehicleInsuranceMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Insurance Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false), 
								new Array('insuringCompanyName','Company Name','width=30%','',true), 
                                new Array('insuringCompanyDetails','Detail','width=60%','',true),
								new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddVehicleInsurance';
editFormName   = 'EditVehicleInsurance';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleInsurance';
divResultName  = 'results';
page=1; //default page
sortField = 'insuringCompanyName';
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


    var fieldsArray = new Array(	new Array("insuringCompanyName","<?php echo ENTER_INSURING_COMPANY ?>")
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
       /* else {
            //unsetAlertStyle(fieldsArray[i][0]);

             if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='vehicleType' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo VEHICLE_TYPE_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            else if(fieldsArray[i][0]=="mainTyres") {
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;

				}
            }
            else if(fieldsArray[i][0]=="spareTyres") {
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;

				}
            }
        } */
    }
    if(act=='Add') {
        addVehicleInsurance();
        return false;
    }
    else if(act=='Edit') {
        editVehicleInsurance();
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
function addVehicleInsurance() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	insuringCompanyName: (document.addVehicleInsurance.insuringCompanyName.value),
							insuringCompanyDetails: (document.addVehicleInsurance.insuringCompanyDetails.value)
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
                         hiddenFloatingDiv('AddVehicleInsurance');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo INSURANCE_COMPANY_ALREADY_EXIST ?>"){
							document.addVehicleInsurance.insuringCompanyName.focus();
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

function deleteVehicleInsurance(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {insuringCompanyId: id},

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
   document.addVehicleInsurance.insuringCompanyName.value = '';
   document.addVehicleInsurance.insuringCompanyDetails.value = '';
   document.addVehicleInsurance.insuringCompanyName.focus();
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

function editVehicleInsurance() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxInitEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	insuringCompanyId: (document.editVehicleInsurance.insuringCompanyId.value),
							insuringCompanyName: (document.editVehicleInsurance.insuringCompanyName.value),
							insuringCompanyDetails: (document.editVehicleInsurance.insuringCompanyDetails.value)},

             onCreate: function() {
                  showWaitDialog(true);
             },

             onSuccess: function(transport){
                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditVehicleInsurance');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo INSURANCE_COMPANY_ALREADY_EXIST ?>"){
							document.editVehicleInsurance.insuringCompanyName.focus();
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
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {insuringCompanyId: id},

               onCreate: function() {
                  showWaitDialog(true);
               },

             onSuccess: function(transport){

                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditVehicleInsurance');
                        messageBox("<?php echo INSURANCE_NAME_NOT_EXIST;?>");

                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');

                   document.editVehicleInsurance.insuringCompanyName.value = j.insuringCompanyName;
                   document.editVehicleInsurance.insuringCompanyDetails.value = j.insuringCompanyDetails;
				   document.editVehicleInsurance.insuringCompanyId.value = j.insuringCompanyId;
                   document.editVehicleInsurance.insuringCompanyName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayInsuranceReport.php?'+qstr;
    window.open(path,"DisplayInsuranceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayInsuranceCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/VehicleInsurance/listVehicleInsuranceContents.php");
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
// $History: listVehicleInsurance.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/24/09   Time: 12:01p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002354, 0002353, 0002351, 0002352, 0002350, 0002347,
//0002348, 0002355, 0002349
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/26/09   Time: 5:28p
//Created in $/Leap/Source/Interface
//new file for vehicle insurance
//
//
?>