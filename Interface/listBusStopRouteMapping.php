<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Nishu Bindal
// Created on : (26.Feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------

?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleStopRouteMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Stop Route Mapping </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 

?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
new Array('routeName','Vehicle Route','width="250"','',true),
new Array('cityName','Vehicle Stop City','width="250"','',true),
new Array('stopName','Vehicle Stop Name','width="250"','',true),
new Array('scheduledTime','Scheduled Time','width="150"','',true),
new Array('action','Action','width="4%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusStopRouteMapping';   
editFormName   = 'EditBusStopRouteMapping';
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
    new Array("stopCityName","Select Vehicle Stop City"),
     new Array("stopName","Select Vehicle Stop Name"),
     new Array("routeCode","Select Vehicle Route"),
      new Array("scheduleTime","Select Scheduled Arival Time")
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
            if(fieldsArray[i][0]=='scheduleTime' && !isTime(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("Invalid schedule time");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
        }
    } 
 
    if(act=='Add') {
        addBusStopRouteMapping();
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

function getBusStopCity(mode,selectedId) { 
	if(mode == 'add'){ 
		form = document.AddBusStopRoute;
	}
	else{
		form = document.EditBusStopRoute;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/getBusStopCity.php';
	new Ajax.Request(url,
	{
		method:'post',
		asynchronous :false,
		parameters: {	
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
				addOption(form.stopCityName, '', 'Select');
				for(i=0;i<len;i++) {
					if(selectedId == j[i].busStopCityId){ 
   						var objOption = new Option(j[i].cityName,j[i].busStopCityId);
   						objOption.setAttribute("selected", "selected");
						form.stopCityName.options.add(objOption);
					}
					else{
						addOption(form.stopCityName, j[i].busStopCityId, j[i].cityName);
					}
				}
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Stop Names
//Author : Nishu Bindal
// Created on : (29.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	function getStopNames(cityId,act,selectedId){
		 if(act == 'add'){   
			form = document.AddBusStopRoute; 
		}
		else{
			form = document.EditBusStopRoute;
		}
		var url = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/getStopNames.php';
		new Ajax.Request(url,
		{
			method:'post',
			asynchronous :false,
			parameters:{	cityId:cityId
				},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){ 
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.stopName.length = null;
				if(j==0 || len == 0) {
					addOption(form.stopName, '', 'Select');
					return false;
				}
				else{
					addOption(form.stopName, '', 'Select'); 
					for(i=0;i<len;i++) {
						if(selectedId == j[i].busStopId){ 
   							 var objOption = new Option(j[i].stopName,j[i].busStopId);
   							 objOption.setAttribute("selected", "selected");
							form.stopName.options.add(objOption);
						}
						else{
							addOption(form.stopName, j[i].busStopId, j[i].stopName);
						}
					}
				}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
	

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author :NISHU BINDAL
// Created on : (29.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBusStopRouteMapping(){
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous :false,
             parameters: {cityId: (document.AddBusStopRoute.stopCityName.value), 
             stopName: (document.AddBusStopRoute.stopName.value),
             routeCode: (document.AddBusStopRoute.routeCode.value),
             scheduleTime: (document.AddBusStopRoute.scheduleTime.value)
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
                             hiddenFloatingDiv('AddBusStopRouteMapping');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
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
        
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {busRouteStopMappingId: id},
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
	form = document.AddBusStopRoute;
	form.routeCode.selectedIndex = 0;
	form.stopCityName.selectedIndex=0;
	form.stopName.length = null;
	addOption(form.stopName, '', 'Select');
	form.scheduleTime.value = '';
	form.stopCityName.focus();
	getBusStopCity('add','');
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
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {busRouteStopMappingId: (document.busRouteStopMappingId.value),
             cityId: ( document.EditBusStopRoute.stopCityName.value),
              routeCode : ( document.EditBusStopRoute.routeCode.value), 
              scheduleTime : (document.EditBusStopRoute.scheduleTime.value),
              stopId : (document.EditBusStopRoute.stopName.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){ 
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         alert(trim(transport.responseText));
                         hiddenFloatingDiv('EditBusStopRouteMapping');
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusStop Mapping" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopRouteMapping/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {busRouteStopMapping : id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                   document.EditBusStopRoute.busRouteStopMappingId.value = j.busRouteStopMappingId;
                   document.EditBusStopRoute.routeCode.value = j.busRouteId;
                   document.EditBusStopRoute.scheduleTime.value = j.scheduledTime;
                   document.EditBusStopRoute.stopCityName.focus();
                   getBusStopCity('edit',j.busStopCityId);
                   getStopNames(j.busStopCityId,'edit',j.busStopId)
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
</script>
</head>
<body>
	<?php 
	 	require_once(TEMPLATES_PATH . "/header.php");
	 	require_once(TEMPLATES_PATH . "/BusStopRouteMapping/listBusStopRouteContents.php");
	 	require_once(TEMPLATES_PATH . "/footer.php");  
    	?>
<script language="javascript"> 

    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>


