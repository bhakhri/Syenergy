<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------

?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Stop Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 

?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
new Array('cityName','Vehicle Stop City','width="250"','',true),
new Array('stopName','Vehicle Stop','width="250"','',true),
new Array('stopAbbr','Abbr','width="150"','',true),
new Array('action','Action','width="4%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = "<?php echo HTTP_LIB_PATH;?>/BusStopNew/ajaxInitList.php";
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusStop';   
editFormName   = 'EditBusStop';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBusStop';
divResultName  = 'results';
page=1; //default page
sortField = 'cityName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    getBusStopCity('edit');
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("stopName","<?php echo ENTER_STOP_NAME ?>"),
    new Array("stopAbbr","<?php echo ENTER_STOP_ABBR ?>"),
     new Array("stopHeadName","Select Vehicle Stop City")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='stopName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo STOP_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(fieldsArray[i][0]=='scheduleTime' && !isTime(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("Invalid schedule time");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if((fieldsArray[i][0]!='scheduleTime' &&  fieldsArray[i][0]!='transportCharges' ) && !isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           
        }
    } 
    if(frm.stopHeadName.value == "other"){
    	if(frm.busStopcity.value == "Enter City Name"){
    		alert("Please Insert City Name");
    		 eval(frm.stopHeadName.focus());
    		 return false;
    	}
    }
    
    if(act=='Add') {
        addBusStop();
        return false;
    }
    else if(act=='Edit') {
        editBusStop();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BUS STOP
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBusStopCity(mode) { 
	if(mode == 'add'){ 
		form = document.AddBusStop;
	}
	else{
		form = document.EditBusStop;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/BusStopNew/getBusStopCity.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: { nonId :'0'	
			},
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.stopCityName.length = null;
			if(j==0 || len == 0) {
				addOption(form.stopCityName, '', 'Select');
				return false;
			}
			else{
				for(i=0;i<len;i++) {
					addOption(form.stopCityName, j[i].busStopCityId, j[i].cityName);
				}
				addOption(form.stopCityName, 'other', 'other');
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBusStop() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopNew/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stopName: (document.AddBusStop.stopName.value), 
             stopAbbr: (document.AddBusStop.stopAbbr.value),
             busStopCityId: (document.AddBusStop.stopHeadName.value),
             busStopCityNew: (document.AddBusStop.cityName.value)
             },
             onCreate: function() {
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
                             hiddenFloatingDiv('AddBusStop');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else if("<?php echo STOP_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STOP_ALREADY_EXIST ;?>"); 
                       document.AddBusStop.stopName.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO Enter New Bus Stop City In case of Other
//Author : NIshu bindal
// Created on : (24.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function addNewStopCity(fieldValue,divId,eleName){ 
	if(fieldValue == 'other'){
		document.getElementById(divId).style.display='';
	}
	else{ document.getElementById(eleName).value='';
		document.getElementById(divId).style.display='none';
	}
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO Remove Or Insert Default Values
//Author : NIshu bindal
// Created on : (24.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function removeInsertDefault(cityValue,eleName){
	if(cityValue == 'Enter City Name'){
		document.getElementById(eleName).value='';
	}
	else if(isEmpty(cityValue)){
		document.getElementById(eleName).value='Enter City Name';
	}
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteBusStop(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopNew/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addBusStop" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues(){
   document.AddBusStop.stopName.value = '';
   document.AddBusStop.stopAbbr.value = '';
   document.AddBusStop.stopHeadName.selectedIndex=0;
   document.getElementById('cityName').value='Enter City Name';
   document.getElementById('newCity').style.display='none';
   document.AddBusStop.stopHeadName.focus();
   getBusStopCity('add');
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
             
function editBusStop() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopNew/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopCityId: (document.EditBusStop.stopHeadName.value),
             busStopId: (document.EditBusStop.busStopId.value),
              stopName: (document.EditBusStop.stopName.value), 
              stopAbbr: (document.EditBusStop.stopAbbr.value),
              busStopCityNew: (document.EditBusStop.editCityName.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){ 
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         alert(trim(transport.responseText));
                         hiddenFloatingDiv('EditBusStop');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                   else if("<?php echo STOP_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STOP_ALREADY_EXIST ;?>"); 
                       document.EditBusStop.stopName.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusStop" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
	document.getElementById('editNewCity').style.display='none';
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopNew/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBusStop');
                        messageBox("<?php echo BUSSTOP_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   document.EditBusStop.stopHeadName.value = j.busStopCityId;
                   document.EditBusStop.stopName.value = j.stopName;
                   document.EditBusStop.stopAbbr.value = j.stopAbbr;
                   document.EditBusStop.busStopId.value = j.busStopId;
                   document.EditBusStop.stopHeadName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
</script>
</head>
<body>
	<?php 
	 require_once(TEMPLATES_PATH . "/header.php");
	    require_once(TEMPLATES_PATH . "/BusStopNew/listBusStopContents.php");
	    require_once(TEMPLATES_PATH . "/footer.php");
	   
    ?>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
    
</body>
</html>


