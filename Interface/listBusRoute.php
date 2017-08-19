<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSROUTE ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
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
define('MODULE','BusRouteMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/BusRoute/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Route Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="3%"','',false), 
				new Array('routeName','Vehicle Route Name','width="250"','',true),
                                new Array('studentCount','No. of Students','width="190"','align="right"',true),  
				new Array('routeCode','Vehicle Route Code','width="250"','',true),
                                new Array('busNo','Bus No.','width="250"','align="left"',true),
                                new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusRoute/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusRoute';   
editFormName   = 'EditBusRoute';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBusRoute';
divResultName  = 'results';
page=1; //default page
sortField = 'routeName';
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
    
   
    var fieldsArray = new Array(	new Array("routeName","<?php echo ENTER_ROUTE_NAME; ?>"),
					new Array("routeCode","<?php echo ENTER_ROUTE_CODE; ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='routeName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ROUTE_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
     
    }
    if(act=='Add') {
        addBusRoute();
        return false;
    }
    else if(act=='Edit') {
        editBusRoute();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW BusRoute
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBusRoute() {
        var url = '<?php echo HTTP_LIB_PATH;?>/BusRoute/ajaxInitAdd.php';
		
        var form = document.AddBusRoute;   
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	routeName: (document.AddBusRoute.routeName.value),
				routeCode: (document.AddBusRoute.routeCode.value),
                            	busId: document.AddBusRoute.busId.value
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
                             hiddenFloatingDiv('AddBusRoute');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                      else if("<?php echo ROUTE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo ROUTE_ALREADY_EXIST ;?>"); 
                        document.AddBusRoute.routeCode.focus();
                      }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSROUTE
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteBusRoute(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/BusRoute/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busRouteId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddBusRoute" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddBusRoute.routeName.value = '';
   document.AddBusRoute.routeCode.value = '';
   document.AddBusRoute.routeName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSROUTE
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editBusRoute() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusRoute/ajaxInitEdit.php';
         var alertStr='';
        var form = document.EditBusRoute;  
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	busRouteId: (document.EditBusRoute.busRouteId.value),
				routeName: (document.EditBusRoute.routeName.value),
				routeCode: (document.EditBusRoute.routeCode.value),
            			busId: (document.EditBusRoute.busId.value)
				 },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     	 messageBox(transport.responseText);
                         hiddenFloatingDiv('EditBusRoute');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else if("<?php echo ROUTE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo ROUTE_ALREADY_EXIST ;?>"); 
                        document.EditBusRoute.routeCode.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusRoute" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
  
         url = '<?php echo HTTP_LIB_PATH;?>/BusRoute/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busRouteId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBusRoute');
                        messageBox("<?php echo BUSROUTE_NOT_EXIST; ?>");
                        
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditBusRoute.routeName.value = j[0].routeName;
                   document.EditBusRoute.routeCode.value = j[0].routeCode;
		   document.EditBusRoute.busRouteId.value = j[0].busRouteId;
                   document.EditBusRoute.busId.value = j[0].busId;
                   document.EditBusRoute.routeName.focus();
            },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

/* function to print bus route report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/busRouteReportPrint.php?'+qstr;
    window.open(path,"BusRouteReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/busRouteReportCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BusRoute/listBusRouteContents.php");
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
// $History: listBusRoute.php $ 
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/21/09   Time: 6:50p
//Updated in $/LeapCC/Interface
//Fixed bug nos. 0001822, 0001823, 0001824, 0001847, 0001850, 0001825
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/14/09    Time: 6:36p
//Updated in $/LeapCC/Interface
//put route charges and check box 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/12/08   Time: 12:02
//Updated in $/LeapCC/Interface
//Corrected Bugs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 11/21/08   Time: 10:12a
//Updated in $/Leap/Source/Interface
//Corrected Issues[19-11-08] Build
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:00p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:55p
//Updated in $/Leap/Source/Interface
//Added functionality for bus route report print
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Interface
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/20/08    Time: 5:06p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:58p
//Updated in $/Leap/Source/Interface
//Added AjaxList Funtioality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:07p
//Updated in $/Leap/Source/Interface
//Created BusRoute Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:32p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>
