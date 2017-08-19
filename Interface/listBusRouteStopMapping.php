<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRouteStopMapping');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Bus Route Stop Mapping</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period                               
var tableHeadArray = new Array(
                         new Array('srNo','#','width="2%"','',false),
                         new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                         new Array('stopName','Stop Name','width="10%"','',true), 
                         new Array('stopAbbr','Abbr.','width="10%"','',true),
                         new Array('transportCharges','Charges','width="10%"','align="right"',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitRouteStopMappingList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'stopName';
sortOrderBy  = 'ASC';

function getStop(){

    document.getElementById("editStopRouteId").value='';
	if(document.getElementById("labelId").value=='') {
       messageBox ("<?php echo SELECT_TIME_TABLE;?>");
       document.getElementById("labelId").focus();
       return false;  
    }
    
    if(document.getElementById("routeId").value=='') {
       messageBox ("<?php echo SELECT_BUS_ROUTE_CODE;?>");
       document.getElementById("routeId").focus();
       return false;   
    }
    
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
    document.getElementById("saveDiv2").style.display='';
    document.getElementById("saveDiv1").style.display='';
    document.getElementById("showData").style.display='';
    document.getElementById("showTitle").style.display='';

    checkEditStatus();
    return false;
}

function checkEditStatus() {
   formx = document.listForm;
   for(var i=1;i<formx.length;i++){
      if(formx.elements[i].checked==true && formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
        document.getElementById("editStopRouteId").value='1';
        break;
      }
   } 
}

function insertForm() {
        
	 url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitAddMapping.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('listForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 flag = true;
				 alert(trim(transport.responseText));
                 checkEditStatus();
				 return false;
			 }
			 else {
			    messageBox(trim(transport.responseText));
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function validateAddForm() {
    var selected=0;
	formx = document.listForm;
    
    if(document.getElementById("editStopRouteId").value=='') {
	    for(var i=1;i<formx.length;i++){
	      if(formx.elements[i].type=="checkbox"){
    	    if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
              selected++;
            }
	      }
	    }
	    if(selected==0){
    	    alert("<?php echo STOP_TO_ROUTE_ONE?>");
		    return false;
	    }
    }
    insertForm();
	return false;
}

function hideResults() {
   document.getElementById("saveDiv2").style.display='none';
   document.getElementById("saveDiv1").style.display='none';
   document.getElementById("showData").style.display='none';
   document.getElementById("showTitle").style.display='none';
}


function doAll(){

   formx = document.listForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"){
                formx.elements[i].checked=false;
            }
        }
    }
}


function printReport() {

	form = document.listForm;
	var routeName = document.getElementById('routeId').options[document.getElementById('routeId').selectedIndex].text;
	var timeTableName = document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text;

	path='<?php echo UI_HTTP_PATH;?>/assignStopRouteMappingPrint.php?routeId='+form.routeId.value+'&labelId='+form.labelId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&routeName='+routeName;
	window.open(path,"subjectToClassReport","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
}

/* function to print all payment history to csv*/
function printCourseToClassCSV() {

	form = document.listForm;
	var routeName = document.getElementById('routeId').options[document.getElementById('routeId').selectedIndex].text;
	var timeTableName = document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text;

	path='<?php echo UI_HTTP_PATH;?>/assignStopRouteMappingCSV.php?routeId='+form.routeId.value+'&labelId='+form.labelId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&routeName='+routeName;
	window.location=path;
}

 
</script>
</head>
<body>
<?php   
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/BusStop/listBusStopMappingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

