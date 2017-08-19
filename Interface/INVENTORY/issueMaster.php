<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DEPARTMENT ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RequisitionIssueMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Department/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Issue Master</title>
<style>
/*Used For Color Picker*/
.cell_color {
    cursor:pointer;
    width:7px;
    height:6px;
}
</style>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('requisitionNo','Requisition No.','"width=20%"','',true) , 
                                new Array('requisitionDate','Requisition Date','width="20%"','align="center"',true),
								new Array('employeeName','Approved By','width="15%"','align="left"',true),
								new Array('approvedOn','Approved On','width="15%"','align="center"',true),
                                new Array('action1','Action','width="5%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueMaster/ajaxInitIssueItemsList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRequisitions';
editFormName   = 'EditRequisition';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteItems';
divResultName  = 'results';
page=1; //default page
sortField = 'requisitionNo';
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
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
   populateValues(id,dv,w,h);
   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function issuedRequisition() {
   editIssuedRequisition();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED Cancel requisiton
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (29 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function cancelledRequisition() {
   rejectRequisition();
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO Reject Requisition
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function rejectRequisition() {
	 url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueMaster/ajaxInitRejectRequisition.php';
	 var pars = generateQueryString('EditRequisition');

	 new Ajax.Request(url,
	   {
		 method:'post',
		  parameters: pars,
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 hiddenFloatingDiv('EditRequisition');
				 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				 return false;
				 //location.reload();
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DEPARTMENT
//
//Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editIssuedRequisition() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueMaster/ajaxInitApprovedRequisition.php';
         var pars = generateQueryString('EditRequisition');

         new Ajax.Request(url,
           {
             method:'post',
              parameters: pars,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
					 var ret=trim(transport.responseText).split('~!@~!~@!~');
                     if("<?php echo SUCCESS;?>" ==trim(ret[1])) {
						 if(trim(ret[0])!=''){
							 messageBox(trim(ret[0]));
						 }
                         hiddenFloatingDiv('EditRequisition');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
					 else {
					 alert (trim(ret[1]));
					 }	


             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
		//cleanUpTable();
		//cleanUpEditTable();
		
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueMaster/ajaxGetApprovedRequisitionValues.php';
         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous: false,
             parameters: {requisitionId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditRequisition');
                        messageBox("<?php echo REQUISITION_NOT_EDIT; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   
				   j = eval('('+trim(transport.responseText)+')');
				   //var len = j['requisitionDetail'].length;
				   document.getElementById('issuedRequisitionDiv').style.display = '';
				   document.getElementById('issuedRequisitionDiv').innerHTML = '';
				   document.getElementById('issuedRequisitionDiv').innerHTML = j['approvedRequisitionDiv'];
				   displayWindow(dv,w,h);
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
		   changeColor(currentThemeId);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO PRINT Issue Items List
//
//Author : Jaineesh
// Created on : (31.08.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------

function printReport() {
	 var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayIssueReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"IssueReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to export to excel */
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayIssueReportCSV.php?'+qstr;
    //alert(path);
	window.location = path;
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(INVENTORY_TEMPLATES_PATH . "/IssueMaster/listApprovedIssueItemsContents.php");
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
// $History:  $ 
//
//
?>