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
define('MODULE','TyreRetreading');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Tyre Retreading </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="5%"','',false), 
								new Array('tyreNumber','Tyre No.','width=10%','',true), 
								new Array('busNo','Registration No.','width="10%"','',true),
								new Array('retreadingDate','Retreading Date','width="10%"','align="center"',true),
								new Array('totalRun','Reading','width="10%"','align="right"',true),
								new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTyreRetreading';   
editFormName   = 'EditTyreRetreading';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTyreRetreading';
divResultName  = 'results';
page=1; //default page
sortField = 'tyreNumber';
sortOrderBy    = 'ASC';
var topPosHistory = 0;
var leftPosHistory = 0;
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
    
   
    var fieldsArray = new Array(	new Array("tyreNo","<?php echo ENTER_TYRE_NO ?>"),
									new Array("reading","<?php echo ENTER_READING?>")
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
            else if(fieldsArray[i][0]=="reading") {
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
                
				}
            }
			var d=new Date();
			var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
			
			if(!dateDifference(document.getElementById('retreadingDate').value,cdate,"-")) {
			   messageBox("<?php echo RETREADING_DATE_NOT_GREATER; ?>");
			   document.getElementById('retreadingDate').focus();
			   return false;
			 }

			 if(!dateDifference(document.getElementById('retreadingDate1').value,cdate,"-")) {
			   messageBox("<?php echo RETREADING_DATE_NOT_GREATER; ?>");
			   document.getElementById('retreadingDate1').focus();
			   return false;
			 }
        }
    }
    if(act=='Add') {
        addTyreRetreading();
        return false;
    }
    else if(act=='Edit') {
        editTyreRetreading();
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
function addTyreRetreading() {
         url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	tyreNo: (document.addTyreRetreading.tyreNo.value), 
							retreadingDate: (document.addTyreRetreading.retreadingDate.value),
							reading: (document.addTyreRetreading.reading.value),
							replacementReason: (document.addTyreRetreading.replacementReason.value)
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
                         hiddenFloatingDiv('AddTyreRetreading');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo VEHICLE_TYRE_NOT_EXIST ?>"){
							document.addTyreRetreading.tyreNo.focus();	
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

function deleteTyreRetreading(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {retreadingId: id},
             
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
   document.addTyreRetreading.tyreNo.value = '';
   document.addTyreRetreading.reading.value = '';
   document.addTyreRetreading.replacementReason.value = '';
   document.getElementById('busNo').innerHTML = '';
   document.addTyreRetreading.tyreNo.focus();
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

function editTyreRetreading() {
         url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	retreadingId: (document.editTyreRetreading.retreadingId.value), 
							tyreNo: (document.editTyreRetreading.tyreNo.value), 
							retreadingDate: (document.editTyreRetreading.retreadingDate1.value),
							reading: (document.editTyreRetreading.reading.value),
							replacementReason: (document.editTyreRetreading.replacementReason.value)
						},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditTyreRetreading');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo VEHICLE_TYRE_NOT_EXIST ?>"){
							document.editTyreRetreading.tyreNo.focus();	
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
         url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {retreadingId: id},
             
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
                    j = eval('('+transport.responseText+')');
                   document.editTyreRetreading.tyreNo.value = j.tyreNumber;
                   document.getElementById('editBusNo').innerHTML = j.busNo;
				   document.editTyreRetreading.retreadingDate1.value = j.retreadingDate;
                   document.editTyreRetreading.reading.value = j.totalRun;
				   document.editTyreRetreading.replacementReason.value = j.reason;
				   document.editTyreRetreading.retreadingId.value = j.retreadingId;
                   document.editTyreRetreading.tyreNo.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Bus No" DIV
//
//Author : Jaineesh
// Created on : (28.04.09)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getHistoryTyreBus() {
	//hideResults();
    document.getElementById('linkHistory').style.display=''; 
	form = document.addTyreRetreading;
	var url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/getTyreRetreading.php';
	var pars = 'tyreNo='+form.tyreNo.value;

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			if(trim(transport.responseText)==0) {
				//hiddenFloatingDiv('EditVehicleType'); 
				messageBox("<?php echo VEHICLE_TYRE_NOT_EXIST;?>");
				document.addTyreRetreading.tyreNo.value='';
				document.addTyreRetreading.tyreNo.focus();
				document.addTyreRetreading.busNo.innerHTML='';
				//sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				return false;
		   }

			var j = eval('(' + transport.responseText + ')');
			
			//len = j['otherBusesArray'].length;
			document.getElementById('busNo').innerHTML = j[0].busNo;
         //   document.getElementById('tyreHistory').innerHTML = j[0].busNo; 
			/*for (i=0;i<len;i++) {
				addOption(form.toBus, j['otherBusesArray'][i]['busId'], j['otherBusesArray'][i]['busNo']);
			}*/
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Bus No" DIV
//
//Author : Jaineesh
// Created on : (28.04.09)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getEditHistoryTyreBus() {
	//hideResults();
	form = document.editTyreRetreading;
	var url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/getTyreRetreading.php';
	var pars = 'tyreNo='+form.tyreNo.value;

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			if(trim(transport.responseText)==0) {
				//hiddenFloatingDiv('EditVehicleType'); 
				messageBox("<?php echo VEHICLE_TYRE_NOT_EXIST;?>");
                    
				document.editTyreRetreading.tyreNo.value='';
				document.editTyreRetreading.tyreNo.focus();
				document.editTyreRetreading.busNo.innerHTML='';
				//sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				return false;
			   
		   }

			var j = eval('(' + transport.responseText + ')');

			document.getElementById('editBusNo').innerHTML = j[0].busNo;
        //    document.getElementById('tyreHistory').innerHTML = j[0].busNo;     
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showHistoryDetails() {
    displayFloatingDiv('divHistoryInfo', 'Tyre History', 300, 150, leftPosHistory, topPosHistory,1);   
    leftPosHistory = document.getElementById('divHistoryInfo').style.left;
    topPosHistory = document.getElementById('divHistoryInfo').style.top;
    form = document.addTyreRetreading;
    var url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/getTyreHistory.php';
    var pars = 'tyreNo='+form.tyreNo.value;

    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog();
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            if(trim(transport.responseText)==0) {
                //hiddenFloatingDiv('EditVehicleType'); 
                hiddenFloatingDiv('divHistoryInfo');
                document.getElementById('linkHistory').style.display='none';   
                messageBox("<?php echo 'Tyre history does not exist';?>");
               
                //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                return false;
           }     
           
         //   var j = eval('(' + transport.responseText + ')');    
            j =  transport.responseText ;
            document.getElementById('tyreHistory').innerHTML = j;
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
    document.getElementById('linkHistory').style.display='none'; 
    return false;
}    
function printReport() {
	//var qstr = "searchbox="+trim(document.searchForm.searchbox.value);
     //qstr .= "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayTyreRetreadingReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayTyreRetreadingReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	//var qstr = "searchbox="+trim(document.searchForm.searchbox.value);
     //qstr .= "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayTyreRetreadingCSVReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TyreRetreading/listTyreRetreadingContents.php");
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
// $History: listTyreRetreading.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 2/05/10    Time: 11:03a
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002484, 0002427
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/07/10    Time: 6:51p
//Updated in $/Leap/Source/Interface
//bug fixed for fleet management
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/06/10    Time: 2:42p
//Updated in $/Leap/Source/Interface
//fixed bug no.0002421
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
//User: Jaineesh     Date: 12/04/09   Time: 3:34p
//Created in $/Leap/Source/Interface
//new ajax file for tyre retreading
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