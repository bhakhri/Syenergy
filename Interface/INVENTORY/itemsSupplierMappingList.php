<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ItemsSupplierMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Items & Supplier Mapping Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
    new Array('srNo','#','width="1%"','',false),
    new Array('sups','<input type=\"checkbox\" id=\"supList\" name=\"supList\" onclick=\"selectSups(this.checked);\">','width="2%"','align=\"left\"',false),
    new Array('companyName','Comapany','width="20%"','',true),
    new Array('supplierCode','Supplier','width="20%"','',true),
    new Array('price','Price','width="5%"','',false)
);

recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxSupplierList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
//addFormName    = 'AddItem';   
//editFormName   = 'EditItem';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteItem';
divResultName  = 'results';
page=1; //default page
sortField = 'companyName';
sortOrderBy    = 'ASC';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";

//-------------------------------------------------------
//THIS FUNCTION IS USED TO do item-supplier mapping
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function doMapping() {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxDoItemSupplierMapping.php';
         if(trim(document.searchForm.itemCode.value)==''){
             messageBox("<?php echo ENTER_ITEM_CODE; ?>");
             document.searchForm.itemCode.focus();
             return false;
         }
         var itemCode=trim(document.searchForm.itemCode.value);
         if(trim(document.searchForm.itemId.value)==''){
             messageBox("<?php echo INVALID_ITEM_RECORD;?>");
             document.searchForm.itemCode.focus();
             return false;
         }
         var ele=document.getElementsByTagName('input');
         var eleCnt=ele.length;
         var supStr='';
         var priceStr='';
         var supplierNameStr=''
         var fl=0;
         for(var k=0;k<eleCnt;k++){
             if(ele[k].type=='checkbox' && ele[k].name=='sups'){
                if(ele[k].checked==true){
                    fl++;
                    if(supStr!=''){
                        supStr +=',';
                        supplierNameStr +=',';
                    }
                    supStr +=ele[k].value;
                    supplierNameStr +=ele[k].value+'~!~'+ele[k].alt;
                    //alert(ele[k].alt);
                var price=document.getElementById('priceTxt'+ele[k].value);
                if(trim(price.value)==''){
                    messageBox("<?php echo ENTER_ITEM_PRICE; ?>");
                    price.focus();
                    return false;
                }
                if(!isNumeric(trim(price.value))){
                    messageBox("<?php echo ENTER_ITEM_PRICE_IN_NUMERIC; ?>");
                    price.focus();
                    return false;
                }
                if(trim(price.value)<=0){
                    messageBox("<?php echo ENTER_ITEM_PRICE_GREATER_THAN_ZERO; ?>");
                    price.focus();
                    return false;
                }
                if(priceStr!=''){
                    priceStr +=',';
                }
                priceStr +=price.value;
               }
             }
         }
         
         if(fl==0){
             if(false==confirm("Mapping with suppliers will be lost\nAre you sure?")){
                 return false;
             }
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemId                : (document.searchForm.itemId.value), 
                 supplierIds           : (supStr), 
                 priceStr              : (priceStr),
                 supplierNameStr       : supplierNameStr
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         if(fl!=0){
                             messageBox(itemCode +' mapped with '+fl+' suppliers');
                         }
                         else{
                             messageBox(itemCode +' is not mapped with any suppliers now');
                         }
                         document.searchForm.itemCode.value='';
                         document.searchForm.itemCode.focus();
                         blankValues();
                         
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.searchForm.itemCode.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
    document.getElementById('itemId').value='';
    var ele=document.getElementsByTagName('input');
    var eleCnt=ele.length;
    for(var k=0;k<eleCnt;k++){
        if(ele[k].type=='checkbox'){
            ele[k].checked=false;
        }
        if(ele[k].type=='text' && ele[k].name=='priceTxt'){
            ele[k].value=0;
        }
    }
    document.getElementById('saveTd').style.display='none';
}

function selectSups(state){
   var ele=document.getElementsByTagName('input');
   var eleCnt=ele.length;
   for(var k=0;k<eleCnt;k++){
        if(ele[k].type=='checkbox' && ele[k].name=='sups'){
            ele[k].checked=state;
        }
   } 
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditItem" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getMappingValue(itemCode) {
         blankValues();
         if(trim(itemCode)==''){
             messageBox("<?php echo ENTER_ITEM_CODE; ?>");
             document.searchForm.itemCode.focus();
             return false;
         }
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxGetItemSupplierMapping.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCode: itemCode
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==''){
                        messageBox("<?php echo INVALID_ITEM_RECORD;?>");
                        document.searchForm.itemCode.focus();
                        return false;
                    }
                    var ret=trim(transport.responseText).split('!~!~!');
                    if(ret[0]==-1){
                        messageBox("<?php echo INVALID_ITEM_RECORD;?>");
                        document.searchForm.itemCode.focus();
                        return false;
                    }

                    document.getElementById('saveTd').style.display='';
                    document.getElementById('itemId').value=ret[1];

                    if(ret[0]==0){
                        return false;
                    }
                    
                    var j = eval('('+ret[0]+')');
                    var cnt=j.length;
                    var ele=document.getElementsByTagName('input');
                    var eleCnt=ele.length;
                    for(var i=0;i<cnt;i++){
                        for(var k=0;k<eleCnt;k++){
                            if(ele[k].type=='checkbox'){
                                if(ele[k].value==j[i].supplierId){
                                    document.getElementById('sups'+j[i].supplierId).checked=true;
                                    document.getElementById('priceTxt'+j[i].supplierId).value=j[i].itemPrice;
                                    break;
                                }
                            }
                        }
                    }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

window.onload=function(){
    document.searchForm.reset();
    document.searchForm.itemCode.focus();
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/ItemsMaster/itemsSupplierMappingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script>
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);
</script>
<?php 
// $History: itemsSupplierMappingList.php $ 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/09/09   Time: 11:24
//Updated in $/Leap/Source/Interface/INVENTORY
//Corrected javascript code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/09/09   Time: 11:11
//Updated in $/Leap/Source/Interface/INVENTORY
//Added links for "Items & Supplier Mapping" module and corrected page
//title.
?>