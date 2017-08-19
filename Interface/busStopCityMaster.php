<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Bus Stop Head Master
// Author :Nishu Bindal
// Created on : 21-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Country/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Stop City Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('cityName','Vehicle Stop City','width="35%"','',true),
                               new Array('action','Action','width="7%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusStopCity/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusStopHead';   
editFormName   = 'EditBusStopHead';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteBusStopHead';
divResultName  = 'results';
page=1; //default page
sortField = 'cityName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 
function validateAddForm(frm, act) {
    var fieldsArray = new Array(new Array("busStopHead","Enter Vehicle Stop City"));
                                
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
		if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='busStopHead' ) {
			messageBox("Vehicle Stop City can't be less than 3 characters");
			eval("frm."+(fieldsArray[i][0])+".focus();");
			return false;
			break;
		}          
       }
    }
    if(act=='Add') {
        addBusStopHead();
        return false;
    }
    else if(act=='Edit') {
        editBusStopHead();    
        return false;
    }
}

//This function adds form through ajax                              
function addBusStopHead() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopCity/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopCity: trim(document.getElementById('busStopHead').value)},
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
                             hiddenFloatingDiv('AddBusStopHead');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
			  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.getElementById('busStopHead').value= '';
}

//This function edit form through ajax                   

function editBusStopHead() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopCity/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopCityId:(document.getElementById('busStopHeadId').value), cityName: trim(document.getElementById('editBusStopHeadName').value)},
            onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
            
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     	messageBox("<?php echo SUCCESS;?>");
                         hiddenFloatingDiv('EditBusStopHead');
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

//This function calls delete function through ajax

function deleteBusStopHead(id){
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else{
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopCity/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopCityId: id},
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


//This function populates values in edit form through ajax 

function populateValues(id){
         url = '<?php echo HTTP_LIB_PATH;?>/BusStopCity/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopCityId: id},
            onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
          
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                   document.getElementById('busStopHeadId').value = j.busStopCityId;
                   document.getElementById('editBusStopHeadName').value = j.cityName; 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listCountryPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"CountryReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listCountryCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/BusStopCity/listBusStopHeadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

