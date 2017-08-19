<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DESIGNATION ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Type Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false),
								new Array('vehicleType','Vehicle Type','width=50%','',true),
								new Array('mainTyres','Main Tyres','width="20%" align="right"','align="right"',true),
								new Array('spareTyres','Spare Tyres','width="20%" align="right"','align="right"',true),
								new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleType/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddVehicleType';
editFormName   = 'EditVehicleType';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleType';
divResultName  = 'results';
page=1; //default page
sortField = 'vehicleType';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
var topPos = 0;
var leftPos = 0;
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


    var fieldsArray = new Array(	new Array("vehicleType","<?php echo ENTER_VEHICLE_TYPE ?>"),
									new Array("mainTyres","<?php echo ENTER_MAIN_TYRE ?>"),
									new Array("spareTyres","<?php echo ENTER_SPARE_TYRE ?>")
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

           if(fieldsArray[i][0]=="mainTyres") {
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

        }
    }
    if(act=='Add') {
		if (document.addVehicleType.mainTyres.value < 1) {
				messageBox("<?php echo VALUE_NOT_LESS_ONE; ?>");
				document.addVehicleType.mainTyres.focus();
				return false;
			}

			if (document.addVehicleType.spareTyres.value < 1) {
				messageBox("<?php echo VALUE_NOT_LESS_ONE; ?>");
				document.addVehicleType.spareTyres.focus();
				return false;

			}
        addVehicleType();
        return false;
    }
    else if(act=='Edit') {
		if (document.editVehicleType.mainTyres.value < 1) {
				messageBox("<?php echo VALUE_NOT_LESS_ONE; ?>");
				document.editVehicleType.mainTyres.focus();
				return false;
			}

			if (document.editVehicleType.spareTyres.value < 1) {
				messageBox("<?php echo VALUE_NOT_LESS_ONE; ?>");
				document.editVehicleType.spareTyres.focus();
				return false;
			}
        editVehicleType();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addDesignation() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (24.11.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addVehicleType() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleType/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	vehicleType: (document.addVehicleType.vehicleType.value),
							mainTyres: (document.addVehicleType.mainTyres.value),
							spareTyres: (document.addVehicleType.spareTyres.value)
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
                         hiddenFloatingDiv('AddVehicleType');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo VEHICLE_TYPE_ALREADY_EXIST ?>"){
							document.addVehicleType.vehicleType.focus();
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

function deleteVehicleType(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/VehicleType/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {vehicleTypeId: id},

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
   document.addVehicleType.vehicleType.value = '';
   document.addVehicleType.mainTyres.value = '';
   document.addVehicleType.spareTyres.value = '';
   document.addVehicleType.vehicleType.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITDESIGNATION() IS USED TO populate edit the values &
//save the values into the database by using designationId
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editVehicleType() {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleType/ajaxInitEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	vehicleTypeId: (document.editVehicleType.vehicleTypeId.value),
							vehicleType: (document.editVehicleType.vehicleType.value),
							mainTyres: (document.editVehicleType.mainTyres.value),
							spareTyres: (document.editVehicleType.spareTyres.value)},

             onCreate: function() {
                  showWaitDialog(true);
             },

             onSuccess: function(transport){
                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditVehicleType');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo VEHICLE_TYPE_ALREADY_EXIST ?>"){
							document.editVehicleType.vehicleType.focus();
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleType/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous:false,
             parameters: {vehicleTypeId: id},

               onCreate: function() {
                  showWaitDialog(true);
               },

             onSuccess: function(transport){

                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditVehicleType');
                        messageBox("<?php echo VEHICLE_TYPE_NOT_EXIST;?>");

                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
				   if (trim(transport.responseText)=="<?php echo DEPENDENCY_CONSTRAINT_EDIT ?>"){
					   hiddenFloatingDiv('EditVehicleType');
						messageBox("<?php echo DEPENDENCY_CONSTRAINT_EDIT;?>");
						sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				   }

                    j = eval('('+transport.responseText+')');

                   document.editVehicleType.vehicleType.value = j.vehicleType;
                   document.editVehicleType.mainTyres.value = j.mainTyres;
				   document.editVehicleType.spareTyres.value = j.spareTyres;
                   document.editVehicleType.vehicleTypeId.value = j.vehicleTypeId;
                   document.editVehicleType.vehicleType.focus();
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
	path='<?php echo UI_HTTP_PATH;?>/displayVehicleTypeReport.php?'+qstr;
    window.open(path,"DisplayVehicleTypeReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayVehicleTypeCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/VehicleType/listVehicleTypeContents.php");
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
// $History: listVehicleType.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Interface
//fixed bug in Fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:23p
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/24/09   Time: 12:01p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002354, 0002353, 0002351, 0002352, 0002350, 0002347,
//0002348, 0002355, 0002349
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/24/09   Time: 6:06p
//Updated in $/Leap/Source/Interface
//make new master vehicle type
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/09   Time: 2:44p
//Created in $/Leap/Source/Interface
//add new file for vehicle
//
?>