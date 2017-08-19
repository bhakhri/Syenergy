<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ReceiveMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Order Receive Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript"> 
var tableHeadArray = new Array(
        new Array('srNo','#','width="2%"','',false), 
        new Array('orderNo','Order No.','width="10%"','',true),
        new Array('dispatchDate','Dispatch Date','width="10%"','align="center"',true),
        new Array('receiveDate','Receive Date','width="10%"','align="center"',true),
        new Array('supplierCode','Supplier','width="15%"','',true), 
        new Array('companyName','Company','width="25%"','',true), 
        new Array('actionStr','Action','width="3%"','align="center"',false)
  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ReceiveMaster/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddReceiveDiv';   
editFormName   = 'EditReceiveDiv';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteReceive';
divResultName  = 'results';
page=1; //default page
sortField = 'orderNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

function editWindow(orderId){
    populateValues(orderId);
    return false;
}


var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//this function will fetch item name corresponding to item names
function getOrderDetails(value){
    document.getElementById('orderDetailsDiv1').innerHTML='';
    document.getElementById('orderDate1').innerHTML=''; 
    document.getElementById('orderSupplier1').innerHTML='';
    document.getElementById('dispatchDate1').innerHTML='';
    document.addReceiveForm.orderId.value=''; 
    document.addReceiveForm.dispatchDate.value='';
    document.addReceiveForm.totalItems.value=0;
    var orderNo=trim(value);
    if(orderNo==''){
        return false;
    }
    
    var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ReceiveMaster/ajaxGetOrderDetails.php';
    
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 orderNo    : orderNo
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
                        messageBox("<?php echo INVALID_OR_UNDISPATCHED_OR_RECEIVED_ORDER;?>");
                       // document.addReceiveForm.orderNo.focus();
                        return false;
                    }
                    var ret=trim(transport.responseText);
                    var k = eval('('+ret+')'); 
                    var cnt=k.length;
                    if(cnt==0){
                        messageBox("<?php echo NO_ITEMS_FOUND;?>");
                        document.addReceiveForm.orderNo.focus();
                        return false;
                    }
                   document.addReceiveForm.orderId.value=k[0].orderId; 
                   document.addReceiveForm.dispatchDate.value=k[0].dispatchDate1;
                   document.addReceiveForm.totalItems.value=cnt;
                   
                   document.getElementById('orderDate1').innerHTML=k[0].orderDate; 
                   document.getElementById('orderSupplier1').innerHTML=k[0].supplierCode;
                   document.getElementById('dispatchDate1').innerHTML=k[0].dispatchDate;
                   var tableStr='<table border="0" cellpadding="1" cellspacing="1" width="100%">';
                   tableStr +='<tr class="rowheading"><td class="searchhead_text">#</td><td class="searchhead_text">Item Name</td><td class="searchhead_text" align="right">Price</td><td class="searchhead_text" align="right">Ordered</td><td class="searchhead_text" align="right">Received</td><td class="searchhead_text" align="right">Rejected</td></tr>';
                   var str='';
                   var totalOrdered=0;var totalReceived=0;var totalRejected=0;
                      for(var i=0;i<cnt;i++){
                       var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                       str +='<tr '+bg+'><td class="padding_right">'+(i+1)+'</td>';
                       str +='<td class="padding_right">'+k[i].itemCode+'</td>';
                       str +='<td class="padding_top" align="right"><input type="text" name="price_addTxt" id="price_addTxt'+i+'" class="inputbox" style="width:50px;text-align:right" value="0" onblur="calculateTotalAmountForPrice(this.value,'+cnt+',1,'+i+');"/></td>';
                       str +='<td class="padding_right" align="right">'+k[i].quantity+'</td>';
                       str +='<td class="padding_top" align="right"><input type="text" name="received_addTxt" id="received_addTxt'+i+'" class="inputbox" style="width:50px;text-align:right" value="0" alt="'+k[i].quantity+'" onblur="return calculateRejected(this.value,this.alt,'+i+','+cnt+',1);"/></td>';
                       str +='<td class="padding_top" align="right"><input type="text" name="rejected_addTxt" id="rejected_addTxt'+i+'" class="inputbox" style="width:50px;text-align:right" value="'+k[i].quantity+'" readonly="readonly" /></td>';
                       str +='</tr>';
                       totalOrdered +=Math.abs(k[i].quantity);
                       totalReceived=0;
                       totalRejected +=Math.abs(k[i].quantity);
                   }
                   tableStr +=str;
                   tableStr +='<tr class="rowheading"><td class="searchhead_text" align="right" colspan="3">Total</td><td class="searchhead_text" align="right">'+totalOrdered+'</td><td class="searchhead_text" align="right"><div id="total_received_add_div" style="display:inline">'+totalReceived+'</div></td><td class="searchhead_text" align="right"><div id="total_rejected_add_div" style="display:inline">'+totalRejected+'</div></td></tr>';
                   tableStr +='</table>';
                   document.getElementById('orderDetailsDiv1').innerHTML=tableStr;
                    
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
    
}


function calculateTotalAmount(value,counter,mode){
  if(trim(value)==''){
      messageBox("<?php echo ENTER_TAX_AMOUNT;?>");
      if(mode=='add'){
          document.addReceiveForm.taxAmount.focus();
      }
      else{
          document.editReceiveForm.taxAmount.focus();
      }
      return false;
  }
  if(!isNumeric(value)){
      messageBox("<?php echo ENTER_TAX_AMOUNT_IN_NUMERIC;?>");
      if(mode=='add'){
          document.addReceiveForm.taxAmount.focus();
      }
      else{
          document.editReceiveForm.taxAmount.focus();
      }
      return false;
  }
  var totalAmount=Math.abs(value);
  for(var i=0;i<counter;i++){
        if(isNumeric(document.getElementById('price_'+mode+'Txt'+i).value) && trim(document.getElementById('price_'+mode+'Txt'+i).value)!=''){
            totalAmount +=Math.abs(document.getElementById('price_'+mode+'Txt'+i).value);
        }
   }
 if(mode=='add'){
    document.addReceiveForm.totalAmount.value=totalAmount; 
 }
 else{
     document.editReceiveForm.totalAmount.value=totalAmount;
 }  
}

function calculateTotalAmountForPrice(value,counter,mode,index){
  if(mode==1){
      var mStr='add';
  }  
  else{
      var mStr='edit';
  }
  if(trim(value)==''){
      messageBox("<?php echo ENTER_PRICE_AMOUNT;?>");
      document.getElementById('price_'+mStr+'Txt'+index).focus();
      return false;
  }
  if(!isNumeric(value)){
      messageBox("<?php echo ENTER_PRICE_AMOUNT_IN_NUMERIC;?>");  
      document.getElementById('price_'+mStr+'Txt'+index).focus();
      return false;
  }
  
  if(mStr=='add'){
    var val=document.addReceiveForm.taxAmount.value;
    if(trim(val)==''){
      messageBox("<?php echo ENTER_TAX_AMOUNT;?>");
      document.addReceiveForm.taxAmount.focus();
      return false;
    }
   if(!isNumeric(val)){
      messageBox("<?php echo ENTER_TAX_AMOUNT_IN_NUMERIC;?>");
      document.addReceiveForm.taxAmount.focus();
      return false;
   }  
    var totalAmount=Math.abs(val);
  }
  else{
    var val=document.editReceiveForm.taxAmount.value;
    if(trim(val)==''){
      messageBox("<?php echo ENTER_TAX_AMOUNT;?>");
      document.editReceiveForm.taxAmount.focus();
      return false;
    }
   if(!isNumeric(val)){
      messageBox("<?php echo ENTER_TAX_AMOUNT_IN_NUMERIC;?>");
      document.editReceiveForm.taxAmount.focus();
      return false;
   }  
    var totalAmount=Math.abs(val);
  }
  for(var i=0;i<counter;i++){
        if(isNumeric(document.getElementById('price_'+mStr+'Txt'+i).value) && trim(document.getElementById('price_'+mStr+'Txt'+i).value)!=''){
            totalAmount +=Math.abs(document.getElementById('price_'+mStr+'Txt'+i).value);
        }
   }
 if(mStr=='add'){
    document.addReceiveForm.totalAmount.value=totalAmount; 
 }
 else{
     document.editReceiveForm.totalAmount.value=totalAmount;
 }  
}

function calculateRejected(rec,ord,id,total,mode){
    
    if(mode==1){
      var received=parseInt(rec,10);
      var ordered=parseInt(ord,10);
      document.getElementById('rejected_addTxt'+id).value=0;
      updateTotals(total,mode); 
      if(!isNumeric(rec) || trim(rec)==''){
          messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_RECEIVED ?>");
          document.getElementById('received_addTxt'+id).focus();
          return false;
      }
      if(received>ordered){
        messageBox("<?php echo ORDER_RECEIVED_VALIDATION; ?>");
        document.getElementById('received_addTxt'+id).focus();
        return false;  
      }
      document.getElementById('rejected_addTxt'+id).value=Math.abs(ordered-received);
      updateTotals(total,mode);
      return false;
    }
    else if(mode==2){
      var received=parseInt(rec,10);
      var ordered=parseInt(ord,10);
      document.getElementById('rejected_editTxt'+id).value=0;
      updateTotals(total,mode); 
      if(!isNumeric(rec) || trim(rec)=='' ){
          messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_RECEIVED ?>"); 
          document.getElementById('received_editTxt'+id).focus();
          return false;
      }
      if(received>ordered){
        messageBox("<?php echo ORDER_RECEIVED_VALIDATION; ?>");
        document.getElementById('received_editTxt'+id).focus();
        return false;  
      }
      document.getElementById('rejected_editTxt'+id).value=Math.abs(ordered-received);
      updateTotals(total,mode);
      return false; 
    }
}

function updateTotals(total,mode){
    var totalReceived=0;totalRejected=0;
    if(mode==1){
        for(var i=0;i<total;i++){
            if(isNumeric(document.getElementById('received_addTxt'+i).value) && trim(document.getElementById('received_addTxt'+i).value)!=''){
                totalReceived +=Math.abs(document.getElementById('received_addTxt'+i).value);
            }
            if(isNumeric(document.getElementById('rejected_addTxt'+i).value)){
                totalRejected +=Math.abs(document.getElementById('rejected_addTxt'+i).value);
            }
        }
       document.getElementById('total_received_add_div').innerHTML=totalReceived; 
       document.getElementById('total_rejected_add_div').innerHTML=totalRejected;
        
    }
   else if(mode==2){
       for(var i=0;i<total;i++){
            if(isNumeric(document.getElementById('received_editTxt'+i).value) && trim(document.getElementById('received_editTxt'+i).value)!=''){
                totalReceived +=Math.abs(document.getElementById('received_editTxt'+i).value);
            }
            if(isNumeric(document.getElementById('rejected_editTxt'+i).value)){
                totalRejected +=Math.abs(document.getElementById('rejected_editTxt'+i).value);
            }
        }
      document.getElementById('total_received_edit_div').innerHTML=totalReceived; 
      document.getElementById('total_rejected_edit_div').innerHTML=totalRejected;  
   } 
}
    
var bgclass='';

var serverDate="<?php echo date('Y-m-d');?>";

function validateForm(mode,total){
    var receivedStr='';
    var priceStr='';
    
    if(mode=='add'){
      if(trim(document.addReceiveForm.orderNo.value)==''){
        messageBox("<?php echo ENTER_ORDER_NO; ?>");
        document.addReceiveForm.orderNo.focus();
        return false;
      }
     var orderId=document.addReceiveForm.orderId.value;
     
     if(!dateDifference(document.addReceiveForm.dispatchDate.value,document.getElementById('receiveDate1').value,'-')){
          messageBox("<?php echo RECEIVE_DATE_VALIDATION1;?>");
          document.getElementById('receiveDate1').focus();
          return false;
     }
     
     if(!dateDifference(document.getElementById('receiveDate1').value,serverDate,'-')){
          messageBox("<?php echo RECEIVE_DATE_VALIDATION2;?>");
          document.getElementById('receiveDate1').focus();
          return false;
     }
     
     var receiveDate=document.getElementById('receiveDate1').value;
     var total=document.addReceiveForm.totalItems.value;
   }
   else{
       if(trim(document.editReceiveForm.orderNo.value)==''){
        messageBox("<?php echo ENTER_ORDER_NO; ?>");
        document.editReceiveForm.orderNo.focus();
        return false;
      }
     var orderId=document.editReceiveForm.orderId.value;
     
     if(!dateDifference(document.editReceiveForm.dispatchDate.value,document.getElementById('receiveDate2').value,'-')){
          messageBox("<?php echo RECEIVE_DATE_VALIDATION1;?>");
          document.getElementById('receiveDate2').focus();
          return false;
     }
     
     if(!dateDifference(document.getElementById('receiveDate2').value,serverDate,'-')){
          messageBox("<?php echo RECEIVE_DATE_VALIDATION2;?>");
          document.getElementById('receiveDate2').focus();
          return false;
     }
     
     var receiveDate=document.getElementById('receiveDate2').value;
     var total=document.editReceiveForm.totalItems.value;
   }
   
   if(total==0){
       if(mode=='add'){
           messageBox("<?php echo ITEM_INFO_MISSING;?>");
           document.addReceiveForm.orderNo.focus();
           return false;
       }
       else{
           messageBox("<?php echo ITEM_INFO_MISSING;?>");
           document.editReceiveForm.orderNo.focus();
           return false;
       }
   } 
    
    for(var i=0;i<total;i++){
        
            if(trim(document.getElementById('price_'+mode+'Txt'+i).value)==''){
                messageBox("<?php echo ENTER_PRICE_AMOUNT; ?>");
                document.getElementById('price_'+mode+'Txt'+i).focus();
                return false;
            }
            if(!isNumeric(document.getElementById('price_'+mode+'Txt'+i).value)){
                messageBox("<?php echo ENTER_PRICE_AMOUNT_IN_NUMERIC; ?>");
                document.getElementById('price_'+mode+'Txt'+i).focus();
                return false;
            }
            if(document.getElementById('price_'+mode+'Txt'+i).value<=0){
                messageBox("<?php echo ENTER_PRICE_AMOUNT_GREATER_THAN_ZERO; ?>");
                document.getElementById('price_'+mode+'Txt'+i).focus();
                return false;
            }
            
            if(trim(document.getElementById('received_'+mode+'Txt'+i).value)==''){
                messageBox("<?php echo ENTER_ITEMS_RECIVED; ?>");
                document.getElementById('received_'+mode+'Txt'+i).focus();
                return false;
            }
            if(!isNumeric(document.getElementById('received_'+mode+'Txt'+i).value)){
                messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_RECEIVED; ?>");
                document.getElementById('received_'+mode+'Txt'+i).focus();
                return false;
            }
            
            if(parseInt(document.getElementById('received_'+mode+'Txt'+i).value,10)>parseInt(document.getElementById('received_'+mode+'Txt'+i).alt,10)){
                messageBox("<?php echo ORDER_RECEIVED_VALIDATION; ?>");
                document.getElementById('received_'+mode+'Txt'+i).focus();
                return false;
            }
           
            if(receivedStr!=''){
              if(trim(document.getElementById('received_'+mode+'Txt'+i).value)!=''){   
                receivedStr +=',';
              }
            }
            if(priceStr!=''){
              if(trim(document.getElementById('price_'+mode+'Txt'+i).value)!=''){   
                priceStr +=',';
              }
            }

           if(trim(document.getElementById('received_'+mode+'Txt'+i).value)!=''){ 
             receivedStr  +=trim(document.getElementById('received_'+mode+'Txt'+i).value);
           }
           
           if(trim(document.getElementById('price_'+mode+'Txt'+i).value)!=''){ 
             priceStr  +=trim(document.getElementById('price_'+mode+'Txt'+i).value);
           }
           
    }


   doReceive(orderId,receivedStr,priceStr,receiveDate,mode);
   return false;
}

//this variable will be used for preventing double clicks
var globalFl=1;

//this function is used for adding
function doReceive(orderId,itemsReceived,priceStr,receiveDate,mode){
  
  if(globalFl==0){
      messageBox("Another request is in progress");
      return false;
  }
  
  if(mode=='add'){
      if(trim(document.addReceiveForm.taxAmount.value)==''){
       messageBox("<?php echo ENTER_TAX_AMOUNT;?>");
       document.addReceiveForm.taxAmount.focus();
       return false;
      }
      
      if(!isNumeric(document.addReceiveForm.taxAmount.value)){
       messageBox("<?php echo ENTER_TAX_AMOUNT_IN_NUMERIC;?>");
       document.addReceiveForm.taxAmount.focus();
       return false;
      }
    var taxAmount=trim(document.addReceiveForm.taxAmount.value);
    var totalAmount=trim(document.addReceiveForm.totalAmount.value);
  }
  else{
      if(trim(document.editReceiveForm.taxAmount.value)==''){
       messageBox("<?php echo ENTER_TAX_AMOUNT;?>");
       document.editReceiveForm.taxAmount.focus();
       return false;
      }
      
      if(!isNumeric(document.editReceiveForm.taxAmount.value)){
       messageBox("<?php echo ENTER_TAX_AMOUNT_IN_NUMERIC;?>");
       document.editReceiveForm.taxAmount.focus();
       return false;
      }
    var taxAmount=trim(document.editReceiveForm.taxAmount.value);
    var totalAmount=trim(document.editReceiveForm.totalAmount.value);
  }
 
  
  
  globalFl=0;
  
  if(mode=='add'){
      var remarks=trim(document.addReceiveForm.commentsTxt.value);
      var updateStore=document.addReceiveForm.updateStore[0].checked?1:0;
  }
  else{
      var remarks=trim(document.editReceiveForm.commentsTxt.value);
      var updateStore=document.editReceiveForm.updateStore[0].checked?1:0;
  }
  var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ReceiveMaster/doReceiveOrder.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 orderId       : orderId,
                 receiveDate   : receiveDate,
                 itemsReceived : itemsReceived,
                 remarks       : remarks,
                 taxAmount     : taxAmount,
                 totalAmount   : totalAmount,
                 priceStr      : priceStr,
                 updateStore   : updateStore
                  
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    globalFl=1;
                    hideWaitDialog(true);
                    var ret=trim(transport.responseText);
                    
                    if("<?php echo SUCCESS;?>" == ret) {
                      flag = true;
                      if(mode=='add'){  
                        if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                           blankValues();
                       }
                       else {
                             hiddenFloatingDiv('AddReceiveDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                      }
                     else{
                         hiddenFloatingDiv('EditReceiveDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     } 
                    }
                    else if("<?php echo INVALID_ITEM_RECEIVED;?>" == ret){
                         messageBox("<?php echo INVALID_ITEM_RECEIVED ;?>"); 
                         return false;
                    }
                    else if("<?php echo ORDER_RECEIVED_VALIDATION;?>"==ret){
                         messageBox("<?php echo ORDER_RECEIVED_VALIDATION ;?>"); 
                         return false;
                    }
                    else {
                        messageBox(ret);
                    }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });  
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteReceive(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ReceiveMaster/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {orderId: id},
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
//THIS FUNCTION IS USED TO POPULATE "dutyLeaveDiv" DIV
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(orderId) {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ReceiveMaster/ajaxGetReceiveOrderDetails.php';
         
         document.editReceiveForm.updateStore[1].checked=true;
         document.getElementById('orderDetailsDiv2').innerHTML='';
         document.getElementById('orderDate2').innerHTML=''; 
         document.getElementById('orderSupplier2').innerHTML='';
         document.getElementById('dispatchDate2').innerHTML='';
         
         document.editReceiveForm.receiveDate2.value='';
         document.editReceiveForm.orderNo.value='';
         document.editReceiveForm.commentsTxt.value='';
         
         document.editReceiveForm.orderId.value=''; 
         document.editReceiveForm.dispatchDate.value='';
         document.editReceiveForm.totalItems.value=0;
         document.editReceiveForm.taxAmount.value=0;
         document.editReceiveForm.totalAmount.value=0;
    
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 orderId   : orderId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditReceiveDiv');
                        messageBox("<?php echo INVALID_OR_UNDISPATCHED_OR_RECEIVED_ORDER; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                    }
                    
                    //show the div
                    displayWindow('EditReceiveDiv',550,200);
                    
                    var ret=trim(transport.responseText);
                    var k = eval('('+ret+')'); 
                    var cnt=k.length;
                    if(cnt==0){
                        messageBox("<?php echo NO_ITEMS_FOUND;?>");
                        document.editReceiveForm.orderNo.focus();
                        return false;
                    }
                    
                   document.editReceiveForm.receiveDate2.value=k[0].receiveDate;
                   document.editReceiveForm.orderNo.value=k[0].orderNo;
                   document.editReceiveForm.orderId.value=k[0].orderId; 
                   document.editReceiveForm.dispatchDate.value=k[0].dispatchDate1;
                   document.editReceiveForm.commentsTxt.value=k[0].remarks;
                   document.editReceiveForm.totalItems.value=cnt;
                   document.editReceiveForm.taxAmount.value=k[0].taxAmount;
                   document.editReceiveForm.totalAmount.value=k[0].totalAmount;
                   
                   document.getElementById('orderDate2').innerHTML=k[0].orderDate; 
                   document.getElementById('orderSupplier2').innerHTML=k[0].supplierCode;
                   document.getElementById('dispatchDate2').innerHTML=k[0].dispatchDate;
                   var tableStr='<table border="0" cellpadding="1" cellspacing="1" width="100%">';
                   tableStr +='<tr class="rowheading"><td class="searchhead_text">#</td><td class="searchhead_text">Item Name</td><td class="searchhead_text" align="right">Price</td><td class="searchhead_text" align="right">Ordered</td><td class="searchhead_text" align="right">Received</td><td class="searchhead_text" align="right">Rejected</td></tr>';
                   var str='';
                   var totalOrdered=0;var totalReceived=0;var totalRejected=0;
                      for(var i=0;i<cnt;i++){
                       var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                       str +='<tr '+bg+'><td class="padding_right">'+(i+1)+'</td>';
                       str +='<td class="padding_right">'+k[i].itemCode+'</td>';
                       str +='<td class="padding_top" align="right"><input type="text" name="price_editTxt" id="price_editTxt'+i+'" class="inputbox" style="width:50px;text-align:right" onblur="calculateTotalAmountForPrice(this.value,'+cnt+',2,'+i+');" value="'+k[i].itemPrice+'"/></td>';
                       str +='<td class="padding_right" align="right">'+k[i].quantityOrdered+'</td>';
                       str +='<td class="padding_top" align="right"><input type="text" name="received_editTxt" id="received_editTxt'+i+'" class="inputbox" style="width:50px;text-align:right" value="'+k[i].quantityReceived+'" alt="'+k[i].quantityOrdered+'" onblur="return calculateRejected(this.value,this.alt,'+i+','+cnt+',2);"/></td>';
                       str +='<td class="padding_top" align="right"><input type="text" name="rejected_editTxt" id="rejected_editTxt'+i+'" class="inputbox" style="width:50px;text-align:right" value="'+Math.abs(k[i].quantityOrdered-k[i].quantityReceived)+'" readonly="readonly" /></td>';
                       str +='</tr>';
                       totalOrdered +=Math.abs(k[i].quantityOrdered);
                       totalReceived +=Math.abs(k[i].quantityReceived);
                       totalRejected +=Math.abs(k[i].quantityOrdered-k[i].quantityReceived);
                   }
                   tableStr +=str;
                   tableStr +='<tr class="rowheading"><td class="searchhead_text" align="right" colspan="3">Total</td><td class="searchhead_text" align="right">'+totalOrdered+'</td><td class="searchhead_text" align="right"><div id="total_received_edit_div" style="display:inline">'+totalReceived+'</div></td><td class="searchhead_text" align="right"><div id="total_rejected_edit_div" style="display:inline">'+totalRejected+'</div></td></tr>';
                   tableStr +='</table>';
                   document.getElementById('orderDetailsDiv2').innerHTML=tableStr;
                   document.editReceiveForm.taxAmount.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}  



function blankValues(){
    document.addReceiveForm.reset();
    document.getElementById('orderDetailsDiv1').innerHTML='';
    document.getElementById('orderDate1').innerHTML=''; 
    document.getElementById('orderSupplier1').innerHTML='';
    document.getElementById('dispatchDate1').innerHTML='';
    document.addReceiveForm.orderNo.focus();
}  
window.onload=function(){
    document.searchForm.reset();
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/ReceiveMaster/receiveMasterContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: receiveMaster.php $ 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/09/09    Time: 15:14
//Updated in $/Leap/Source/Interface/INVENTORY
//Updated "Order Receive Master"----Added "update stock" field and added
//the code : if update stock option is yes then main item master table is
//also updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/09    Time: 15:31
//Updated in $/Leap/Source/Interface/INVENTORY
//Updated "Receive Order Master" : Added tax ,total amount and item price
//amount fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/09/09    Time: 16:52
//Created in $/Leap/Source/Interface/INVENTORY
//Created module "Order Receive Master"
?>