<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OpeningStock');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Opening Stock Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
    new Array('srNo','#','width="3%"','',false),
	new Array('itemCode','Item Code','width="10%"','',false),
    new Array('itemName','Item Name','width="10%"','',false),
	new Array('quantityAction','Opening Balance','width="10%"','',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsStock/ajaxItemsList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
//addFormName    = '';
//editFormName   = '';
//winLayerWidth  = 360; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = '';
divResultName  = 'resultDiv';
page=1; //default page
sortField = 'categoryCode';
sortOrderBy    = 'ASC';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function getOpeningStockData() {
	if(document.getElementById('categoryCode').value == '') {
		messageBox("<?php echo SELECT_CATEGORY_CODE ?>");
		document.getElementById('categoryCode').focus();
		return false;
	}

	//document.getElementById('nameRow').style.display='';
	document.getElementById('resultRow').style.display='';
	sendReq(listURL,divResultName,'listForm','',false);
	var len = j['info'].length;
	if(len > 0) {
		document.getElementById('saveData').style.display='';
	}
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";


//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Item
//
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addStock() {
		if(document.getElementById('categoryCode').value == '') {
			messageBox("<?php echo SELECT_CATEGORY_CODE ?>");
			document.getElementById('categoryCode').focus();
			return false;
		}
		if(!dateDifference(document.getElementById('stockDate').value,serverDate,"-")) {
			   messageBox("<?php echo STOCK_DATE_VALIDATION; ?>");
			   document.getElementById('stockDate').focus();
			   return false;
			 }
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsStock/ajaxAddOpeningStock.php';
		 var pars = generateQueryString('listForm');
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
						 messageBox(trim(transport.responseText));
						 blankValues();
                     }
					 else {
						messageBox(trim(transport.responseText));
					 }
                     
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
	//document.listForm.reset();
	document.getElementById('nameRow').style.display='none';
	document.getElementById('resultRow').style.display='none';
	document.getElementById('saveData').style.display='none';
}


function getCategory(itemCategoryId) {
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxGetCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCategoryId: itemCategoryId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    
                    var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						document.getElementById('categoryName').value = '';
						return false;

					}
					else {
						document.getElementById('categoryName').value = j.categoryName;
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function printReport() {

	var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayItemsReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"ItemsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayItemsReportCSV.php?itemName='+document.getElementById('itemName').value+'&itemCode='+document.getElementById('itemCode').value+'&startQty='+document.getElementById('startQty').value+'&endQty='+document.getElementById('endQty').value+'&itemType='+document.getElementById('itemType').value+'&abbr='+document.getElementById('abbr').value+'&keyWord='+document.getElementById('keyWord').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/ItemsStock/itemsStockContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: $ 
//
?>