<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApproveGeneratedPO');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Approve Generated PO </title>
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

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 340; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///


// ajax search results ---end ///
var tableHeadArray = new Array(
								new Array('srNo','#','width="4%" align="left"',false), 
								new Array('poNo','PO No.','width="20%"',' align="left"',true),
								new Array('poDate','Date','width="20%"',' align="center"',true),
								new Array('userName','PO Generated By','width="10%"', 'align="left"',true),
								new Array('action1','Action','width="10%"', 'align="center"',false)
								);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ApprovedGeneratedPO/ajaxInitApprovedGeneratedPOList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRequisition';
editFormName   = 'EditRequisition';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRequisition';
divResultName  = 'results';
page=1; //default page
sortField = 'poNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
	
function validateAddForm(frm, act) {


    var fieldsArray = new Array	(
									new Array("quantityRequired","<?php echo Enter_Quantity_Required; ?>"),
									new Array("rate","<?php echo Enter_Rate; ?>")		
								);
  	if(act=='Edit') {

        editItem();
        return false;
    }
}

	function editItem() {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ApprovedGeneratedPO/ajaxInitUpdatePO.php';
		 var pars = generateQueryString('EditPO');
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
						 hiddenFloatingDiv('EditGeneratedPO');
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
//THIS FUNCTION IS USED TO CANCELL A GENERATED PO
//
// Created on : (9.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


	function cancellGeneratedPO(Id,itmId) {
        if(!confirm("<?php echo PO_CANCEL_CONFIRM; ?>")){
			return false;
		}
		rejectGeneratedPO(Id,itmId);
    }
	
	function rejectGeneratedPO(Id,itmId) {
	 var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ApprovedGeneratedPO/ajaxInitRejectGeneratedPO.php';

	 new Ajax.Request(url,
	   {
		 method:'post',
		  parameters: {poId: Id, itemId: itmId},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				 document.getElementById('tr_'+itmId).style.display='none';
				 var ele=document.getElementById('repairDetail').getElementsByTagName('TR');
				 var len=ele.length;
				 var flag=0;
				 for(var i=0;i<len;i++){
					 if(ele[i].getAttribute('name')=='itemRow'){
						 if(ele[i].style.display==''){
							 flag=1;
						 }
					 }
				 }
				 if(!flag){
                  hiddenFloatingDiv('EditGeneratedPO');
				  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				 }
				 return false;
	
			}
			 else{
				 alert(transport.responseText);
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
	}

	//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A GENERATED PO
//
// Created on : (9.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	function editGeneratedPO(id,dv,w,h) {
		populateValues(id,dv,w,h); 
	}
	
	//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE A GENERATED PO
//
// Created on : (10.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	function populateValues(id,dv,w,h) {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ApprovedGeneratedPO/ajaxGetPoValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 poId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
					var j = trim(transport.responseText);
					document.getElementById('repairDetail').innerHTML=j;
					displayWindow(dv,w,h);
                    
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}
		//-------------------------------------------------------
//THIS FUNCTION IS USED TO MAKE A FLOAT
//
// Created on : (9.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function makeFloat(val){
	var arr=val.toString().split('.');
	if(arr.length==1){
		val =val+".00";
	}
	return val;
}
	//-------------------------------------------------------
//THIS FUNCTION IS USED TO CALCULATE NET PAYBLE AMOUNT & GRAND TOTAL
//
// Created on : (9.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	function updateTotal(){
	 var ele=document.EditPO.elements;
	 var totalAmt=0, grandtotal=0,vat=0,discount=0,Adtnlcharges=0;
	 var form = document.EditPO;
	 var length =ele.length;
	 for(i=0; i<length; i++){
		 //alert(ele[i].name)
	   if(ele[i].name=="quantityRequired[]"){
		   if(!isNaN(ele[i].value) && !isNaN(ele[i+1].value)){
			   ele[i+2].value=(ele[i].value)*(ele[i+1].value);
		   }
		   else{
			   ele[i+2].value="0.00";
		   }
		   totalAmt +=parseFloat(ele[i+2].value);
	   }
	 }
     form.totalAmount.value=makeFloat(totalAmt);
	if((form.Discount.value) >= totalAmt){
		alert("discount can't be greater than total amount");
		form.Discount.style.backgroundColor="red";
		return false;
	}
	else{
		form.Discount.style.backgroundColor="white";
	}
	if(form.Discount.value == ''){
		form.Discount.value=discount;
	}
	if(form.vat.value==''){
		form.vat.value=vat;
	}
	if(form.aditionalCharges.value == ''){
		form.aditionalCharges.value = Adtnlcharges;
	}
	form.Discount.value = makeFloat(form.Discount.value);
	if(!((form.Discount.value == 0) || (form.Discount.value == " "))) {
		discount =form.Discount.value;
	}
	grandtotal = totalAmt - parseFloat(discount);
	if(!((form.vat.value == 0) || (form.vat.value == " "))){
		vat = ((form.vat.value)/ 100) * grandtotal;
	}
	form.vat.value = makeFloat(form.vat.value);
	if(!((form.aditionalCharges.value == " ") || (form.aditionalCharges.value == 0))){
		Adtnlcharges=form.aditionalCharges.value;
	}
	form.aditionalCharges.value = makeFloat(form.aditionalCharges.value);
	if(form.aditionalCharges.value == ''){
		 form.aditionalCharges.value = Adtnlcharges;
	}
	grandtotal = grandtotal + parseFloat(Adtnlcharges) + parseFloat(vat);
	if(totalAmt == 0){
		 grandtotal = 0;
		 alert("please enter correct data for quantity required & rate ");
	}
	form.grandTotal.value = makeFloat(grandtotal);

	}


	//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT GET PARTY NAME
//
// Created on : (9.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	

	function getPartyName(id) {
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetPartyValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 partyId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);

                    var j = eval('('+trim(transport.responseText)+')');
					document.EditGeneratedPO.partyName.value = j.partyName;

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


	function sendKeys(mode,eleName, e) {
		var ev = e||window.event;
		thisKeyCode = ev.keyCode;
		if (thisKeyCode == '13') {
				if(mode==1){    
					var form = document.AddItem;
				}
				else{
					var form = document.EditItem;
				}
		 eval('form.'+eleName+'.focus()');
		 return false;
	    }
    }

	/* function to print city report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;  
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayGeneratedPOReport.php?'+qstr;
    window.open(path,"displayGeneratedPOReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/approveGeneratedPOCSV.php?'+qstr;
 	window.location = path;
}

	


</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(INVENTORY_TEMPLATES_PATH . "/ApproveGeneratedPO/approveGeneratedPO.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>

<script language="javascript">
sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
	
</body>
</html>