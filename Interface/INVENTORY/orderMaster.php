<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OrderMaster');
define('ACCESS','view');

UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Order Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript"> 
var tableHeadArray = new Array(
        new Array('srNo','#','width="2%"','',false), 
        new Array('orderNo','Order No.','width="10%"','',true) , 
        new Array('orderDate','Order Date','width="10%"','align="center"',true) , 
        new Array('supplierCode','Supplier','width="15%"','',true), 
        new Array('companyName','Company','width="20%"','',true), 
        new Array('dispatched','Dispatched','width="10%"','',true) , 
        new Array('dispatchDate','Dispatch Date','width="10%"','align="center"',true) , 
        new Array('action','Action','width="3%"','align="center"',false)
  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/OrderMaster/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddOrderDiv';   
editFormName   = 'EditOrderDiv';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteOrder';
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
function getItemName(value,mode,target){
    var itemCode=trim(value);
    //document.getElementById('hiddenItemCodeId_'+mode+target).value='';
    document.getElementById('itemNameId_'+mode+target).value='';
    if(itemCode==''){
        return false;
    }
    if(mode=='add'){
        var supplierId=document.addOrderForm.supplierId.value;
        if(supplierId==''){
            messageBox("<?php echo SELECT_SUPPLIER; ?>");;
            document.addOrderForm.supplierId.focus();
            return false;
        }
    }
    else{
        var supplierId=document.editOrderForm.supplierId.value;
        if(supplierId==''){
            messageBox("<?php echo SELECT_SUPPLIER; ?>");;
            document.editOrderForm.supplierId.focus();
            return false;
        }
    }
    
    var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/OrderMaster/ajaxGetItemName.php';
    
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCode    : itemCode,
                 supplierId  : supplierId
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
                        return false;
                    }
                    var j = eval('('+trim(transport.responseText)+')'); 
                    //document.getElementById('hiddenItemCodeId_'+mode+target).value=j.itemId;
                    document.getElementById('itemNameId_'+mode+target).value=j.itemName;
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
    
}


//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************
var resourceAddCnt=0;

//for deleting a row from the table 
function deleteRow(value,mode){
  var rval=value.split('~');
  var tbody1 = document.getElementById('orderDetailTableBody_'+mode);
  
  var tr=document.getElementById('row_'+mode+rval[0]);
  tbody1.removeChild(tr);
  

  //reCalculate();
  
  if(isMozilla){
      if((tbody1.childNodes.length-3)==0){
          resourceAddCnt=0;
      }
  }
  else{
      if((tbody1.childNodes.length-1)==0){
          resourceAddCnt=0;
      }
  }
} 


//to add one row at the end of the list
function addOneRow(mode){
 resourceAddCnt++; 
 createRows(mode,resourceAddCnt,1,0);
}

function createMultipleRows(mode,value){
  if(mode=='add'){
      if(document.addOrderForm.supplierId.value==''){
        messageBox("<?php echo SELECT_SUPPLIER; ?>");  
        document.addOrderForm.supplierId.focus();
        return false;
      }
   }
   else{
       if(document.editOrderForm.supplierId.value==''){
        messageBox("<?php echo SELECT_SUPPLIER; ?>");  
        document.editOrderForm.supplierId.focus();
        return false;
      }
  }
  if(!isNumeric(trim(value))){
      messageBox("Please enter numeric values for no. of items");
      if(mode=='add'){
          document.addOrderForm.itemCountTxt.focus();
      }
      else{
          document.editOrderForm.itemCountTxt.focus();
      }
      return false;
  }
  if(trim(value)>20){
      messageBox("No. of items should be less than or equal to 20");
      if(mode=='add'){
          document.addOrderForm.itemCountTxt.focus();
      }
      else{
          document.editOrderForm.itemCountTxt.focus();
      }
      return false;
  }
  cleanUpTable(mode);
  resourceAddCnt=trim(value); 
  createRows(mode,1,trim(value),0); 
}


//to clean up table rows
function cleanUpTable(mode){
   var tbody = document.getElementById('orderDetailTableBody_'+mode);
   //alert(resourceAddCnt);
   for(var k=0;k<=resourceAddCnt;k++){
         try{
          tbody.removeChild(document.getElementById('row_'+mode+k));
         }
         catch(e){
             //alert(k);  // to take care of deletion problem
         }
        /* 
         try{
             if(mode=='add'){
                 document.addOrderForm.removeChild(document.getElementById('hiddenItemCodeId_'+mode+k));
             }
             else{
                 document.editOrderForm.removeChild(document.getElementById('hiddenItemCodeId_'+mode+k));
             }
         }
         catch(e){
             //alert(e);
         }
       */  
   }
  resourceAddCnt=0;    
}


var bgclass='';

var serverDate="<?php echo date('Y-m-d');?>";

//create dynamic rows 
function createRows(mode,start,rowCnt,rowData){
 if(mode=='add'){
      if(document.addOrderForm.supplierId.value==''){
        messageBox("<?php echo SELECT_SUPPLIER; ?>");  
        document.addOrderForm.supplierId.focus();
        return false;
      }
   }
   else{
       if(document.editOrderForm.supplierId.value==''){
        messageBox("<?php echo SELECT_SUPPLIER; ?>");  
        document.editOrderForm.supplierId.focus();
        return false;
      }
 }
    
 var tbl=document.getElementById('orderDetailTable_'+mode);
 var tbody = document.getElementById('orderDetailTableBody_'+mode);
 
 for(var i=0;i<rowCnt;i++){
 /*    
  var hiddenInput=document.createElement('input');
  hiddenInput.setAttribute('type','hidden');
  hiddenInput.setAttribute('id','hiddenItemCodeId_'+mode+parseInt(start+i,10));
  hiddenInput.setAttribute('value','');
  if(mode=='add'){
   document.addOrderForm.appendChild(hiddenInput);
  }
  else{
      document.editOrderForm.appendChild(hiddenInput);
  }
 */ 
  var tr=document.createElement('tr');
  tr.setAttribute('id','row_'+mode+parseInt(start+i,10));
  
  var cell1=document.createElement('td');
  cell1.setAttribute('align','left');
  cell1.name='srNo';
  var cell2=document.createElement('td'); 
  var cell3=document.createElement('td'); 
  var cell4=document.createElement('td');
  
  var cell5=document.createElement('td');
  
  cell2.setAttribute('align','center'); 
  cell3.setAttribute('align','left'); 
  cell4.setAttribute('align','right'); 
  cell5.setAttribute('align','center'); 
  
  
  if(start==0){
  var txt0=document.createTextNode(start+i+1);
  }
  else{
      var txt0=document.createTextNode(start+i);
  }
  
  var txt1=document.createElement('input');
  txt1.setAttribute('type','text');
  txt1.className="inputbox";
  txt1.style.width="120px";
  txt1.setAttribute('id','itemCodeId_'+mode+parseInt(start+i,10));
  txt1.setAttribute('name','itemCode');
  txt1.onblur = new Function("getItemName(this.value,'"+mode+"',"+parseInt(start+i,10)+")");
  
  var txt2=document.createElement('input');
  txt2.setAttribute('type','text');
  txt2.className="inputbox";
  //txt2.setAttribute('style','width:120px');
  txt2.style.width="120px";
  txt2.readOnly=true;
  txt2.setAttribute('id','itemNameId_'+mode+parseInt(start+i,10));
  txt2.setAttribute('name','itemName');
  
  
  var txt3=document.createElement('input');
  txt3.setAttribute('type','text');
  txt3.className="inputbox";
  txt3.style.width="120px"; 
  txt3.style.textAlign="right";
  txt3.setAttribute('id','itemQuantityId_'+mode+parseInt(start+i,10));
  txt3.setAttribute('name','itemQuantity');
  
  
  
  var txt4=document.createElement('a');
  txt4.setAttribute('id','rd');
  txt4.setAttribute('title','Delete');       
  txt4.innerHTML='X';
  txt4.style.cursor='pointer';
  
  if(rowData !='0'){
   txt1.setAttribute('value',rowData[i]['item']);
   txt2.setAttribute('value',rowData[i]['price'])
  }
  txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0","'+mode+'")');  //for ie and ff    
  
  cell1.appendChild(txt0);
  cell2.appendChild(txt1)
  cell3.appendChild(txt2);
  cell4.appendChild(txt3);
  cell5.appendChild(txt4);
         
  tr.appendChild(cell1);
  tr.appendChild(cell2);
  tr.appendChild(cell3);
  tr.appendChild(cell4);
  tr.appendChild(cell5);
  
  
  bgclass=(bgclass=='row0'? 'row1' : 'row0');
  tr.className=bgclass;
  
  tbody.appendChild(tr); 
 } 

 tbl.appendChild(tbody);
}

function reCalculate(){
  var a=document.getElementsByTagName('td');
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo'){
    bgclass=(bgclass=='row0'? 'row1' : 'row0');
    a[i].parentNode.className=bgclass;
      //a[i].innerHTML=j;
      j++;
    }
  }
  resourceAddCnt=j-1;
}

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************

var dtArray=new Array();

function checkDuplicateValue(value){
    var i= dtArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(dtArray[k]==value){
          fl=0;
          break;
      }  
    }
   if(fl==1){
       dtArray.push(value);
   } 
   return fl;
}

var serverDate="<?php echo date('Y-m-d'); ?>";

function validateForm(mode,print){
    var codeStr='';
    var qtyStr='';
    
    if(mode=='add'){
      if(document.addOrderForm.supplierId.value==''){
        messageBox("<?php echo SELECT_SUPPLIER; ?>");
        document.addOrderForm.supplierId.focus();
        return false;
      }
     var supplierId=document.addOrderForm.supplierId.value;
     
     if(!dateDifference(document.getElementById('orderDate1').value,serverDate,'-')){
          messageBox("<?php echo ORDER_DATE_VALIDATION;?>");
          document.getElementById('orderDate1').focus();
          return false;
     }
     
     var orderDate=document.getElementById('orderDate1').value;
   }
   else{
       if(document.editOrderForm.supplierId.value==''){
        messageBox("<?php echo SELECT_SUPPLIER; ?>");  
        document.editOrderForm.supplierId.focus();
        return false;
      }
      var supplierId=document.editOrderForm.supplierId.value;
      
      if(!dateDifference(document.getElementById('orderDate2').value,serverDate,'-')){
          messageBox("<?php echo ORDER_DATE_VALIDATION;?>");
          document.getElementById('orderDate2').focus();
          return false;
     }
     var orderDate=document.getElementById('orderDate2').value;  
   }
    
    var cntFl=0; //to keep track of how many items are added
    dtArray.splice(0,dtArray.length); //empty the array
    
    for(var i=0;i<resourceAddCnt;i++){
      try{  

            if(trim(document.getElementById('itemCodeId_'+mode+(i+1)).value)==''){
                messageBox("<?php echo ENTER_ITEM_CODE; ?>");
                document.getElementById('itemCodeId_'+mode+(i+1)).focus();
                return false;
            }
            if(trim(document.getElementById('itemNameId_'+mode+(i+1)).value)==''){ //if name is empty
                messageBox("<?php echo INVALID_ITEM_CODE; ?>");
                document.getElementById('itemCodeId_'+mode+(i+1)).focus();
                return false;
            }
            
            if(trim(document.getElementById('itemQuantityId_'+mode+(i+1)).value)==''){
                messageBox("<?php echo ENTER_ITEM_QUANTITY; ?>");
                document.getElementById('itemQuantityId_'+mode+(i+1)).focus();
                return false;
            }
            
            if(!checkDuplicateValue(trim(document.getElementById('itemCodeId_'+mode+(i+1)).value))){
                 messageBox("<?php echo DUPLICATE_ITEM_CODE_RESTRICTION; ?>");
                 document.getElementById('itemCodeId_'+mode+(i+1)).focus();
                 return false;
            }
            
            if(!isNumeric(trim(document.getElementById('itemQuantityId_'+mode+(i+1)).value))){
                messageBox("<?php echo ENTER_ITEM_QUANTITY_NUMERIC; ?>");
                document.getElementById('itemQuantityId_'+mode+(i+1)).focus();
                return false;
            }
  
            if(codeStr!=''){
              if(trim(document.getElementById('itemCodeId_'+mode+(i+1)).value)!=''){   
                codeStr +=',';
              }
            }
            if(qtyStr!=''){
              if(trim(document.getElementById('itemCodeId_'+mode+(i+1)).value)!=''){  
                qtyStr +=',';
              }
            }

           if(trim(document.getElementById('itemCodeId_'+mode+(i+1)).value)!=''){ 
             codeStr  +=trim(document.getElementById('itemCodeId_'+mode+(i+1)).value);
           }
           if(trim(document.getElementById('itemCodeId_'+mode+(i+1)).value)!=''){
            qtyStr  +=trim(document.getElementById('itemQuantityId_'+mode+(i+1)).value);
           }
            
          cntFl++;  

      }
      catch(e){}
    }
    
    if(cntFl>20){
        messageBox("You cannot add more than 20 items at a time");
        if(mode=='add'){
         document.getElementById('itemCodeId_'+mode+'1').focus();
        }
        else{
          document.getElementById('itemCodeId_'+mode+'1').focus();
        }
        return false;
    }

   if(mode=='add'){
        addOrder(codeStr,qtyStr,supplierId,orderDate,print);
   }
   else if(mode=='edit'){
       editOrder(codeStr,qtyStr,supplierId,orderDate,print);
   }
   return false;
}

//this variable will be used for preventing double clicks
var globalFl=1;

//this function is used for adding
function addOrder(itemCodes,itemQuantity,supplierId,orderDate,print){
 
  if(globalFl==0){
      messageBox("Another request is in progress");
      return false;
  }  
  if(document.addOrderForm.dispatchChk[0].checked){
      if(!dateDifference(document.getElementById('orderDate1').value,document.getElementById('dispatchDate1').value,'-')){
          messageBox("<?php echo DISPATCH_DATE_VALIDATION;?>");
          document.getElementById('dispatchDate1').focus();
          return false;
     }      
  }
 else{
    if(document.getElementById('dispatchDate1').value!=''){
        messageBox("Select dispatch as yes");
        document.addOrderForm.dispatchChk[1].focus();
        return false;
    } 
 } 
  
 if(document.addOrderForm.dispatchChk[0].checked){
     var dispatched=1;
     var dispatchDate=document.getElementById('dispatchDate1').value;
 } 
 else{
     var dispatched=0;
     var dispatchDate='0000-00-00';
 }
  
  globalFl=0;
  var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/OrderMaster/addPurchaseOrder.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 supplierId    : supplierId,
                 orderDate     : orderDate,
                 itemCodes     : itemCodes,
                 itemQuantity  : itemQuantity,
                 dispatched    : dispatched,
                 dispatchDate  : dispatchDate
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    globalFl=1;
                    hideWaitDialog(true);
                    var ret=trim(transport.responseText).split('~!~');
                    
                    if("<?php echo SUCCESS;?>" == ret[0]) {
                      flag = true;  
                      if(print==1){
                          hiddenFloatingDiv('AddOrderDiv');
                          sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                          var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/orderPrint.php?orderId='+ret[1];
                          window.open(path,"OrderMasterPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");  
                          return false;
                      }                     
                         
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddOrderDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo INVALID_ITEM_CODE;?>" == ret[0]){
                         messageBox("<?php echo INVALID_ITEM_CODE ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(trim(document.getElementById('itemCodeId_add'+(i+1)).value)==err[k]){
                                      document.getElementById('itemCodeId_add'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else if("<?php echo DUPLICATE_ITEM_CODE;?>" == ret[0]){
                         messageBox("<?php echo DUPLICATE_ITEM_CODE ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(trim(document.getElementById('itemCodeId_add'+(i+1)).value)==err[k]){
                                      document.getElementById('itemCodeId_add'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else if("<?php echo BLANK_QUANTITY;?>" == ret[0]){
                         messageBox("<?php echo BLANK_QUANTITY ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(i==err[k]){
                                      document.getElementById('itemQuantityId_add'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else if("<?php echo INVALID_QUANTITY;?>" == ret[0]){
                         messageBox("<?php echo INVALID_QUANTITY ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(trim(document.getElementById('itemQuantityId_add'+(i+1)).value)==err[k]){
                                      document.getElementById('itemQuantityId_add'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else {
                        messageBox(ret[0]);
                        //document.AddCity.cityCode.focus(); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });  
    
}


//this function is used for editing
function editOrder(itemCodes,itemQuantity,supplierId,orderDate,print){

  if(document.editOrderForm.dispatchChk[0].checked){
      if(!dateDifference(document.getElementById('orderDate2').value,document.getElementById('dispatchDate2').value,'-')){
          messageBox("<?php echo DISPATCH_DATE_VALIDATION;?>");
          document.getElementById('dispatchDate2').focus();
          return false;
     }      
  }
 else{
    if(document.getElementById('dispatchDate2').value!=''){
        messageBox("Select dispatch as yes");
        document.editOrderForm.dispatchChk[1].focus();
        return false;
    } 
 } 
  
 if(document.editOrderForm.dispatchChk[0].checked){
     var dispatched=1;
     var dispatchDate=document.getElementById('dispatchDate2').value;
 } 
 else{
     var dispatched=0;
     var dispatchDate='0000-00-00';
 }
  var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/OrderMaster/editPurchaseOrder.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 supplierId    : supplierId,
                 orderDate     : orderDate,
                 itemCodes     : itemCodes,
                 itemQuantity  : itemQuantity,
                 dispatched    : dispatched,
                 dispatchDate  : dispatchDate,
                 orderId       : document.editOrderForm.orderId.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var ret=trim(transport.responseText).split('~!~');
                   
                    if("<?php echo SUCCESS;?>" == ret[0]) {                     
                         hiddenFloatingDiv('EditOrderDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         if(print==1){
                           var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/orderPrint.php?orderId='+ret[1];
                           window.open(path,"OrderMasterPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");  
                         }
                        return false;
                     }
                     else if("<?php echo INVALID_ITEM_CODE;?>" == ret[0]){
                         messageBox("<?php echo INVALID_ITEM_CODE ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(trim(document.getElementById('itemCodeId_edit'+(i+1)).value)==err[k]){
                                      document.getElementById('itemCodeId_edit'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else if("<?php echo DUPLICATE_ITEM_CODE;?>" == ret[0]){
                         messageBox("<?php echo DUPLICATE_ITEM_CODE ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(trim(document.getElementById('itemCodeId_edit'+(i+1)).value)==err[k]){
                                      document.getElementById('itemCodeId_edit'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else if("<?php echo BLANK_QUANTITY;?>" == ret[0]){
                         messageBox("<?php echo BLANK_QUANTITY ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(i==err[k]){
                                      document.getElementById('itemQuantityId_edit'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else if("<?php echo INVALID_QUANTITY;?>" == ret[0]){
                         messageBox("<?php echo INVALID_QUANTITY ;?>"); 
                         var err=ret[1].split('@');
                         var c=err.length;
                         for(var i=0;i<resourceAddCnt;i++){
                           for(var k=0;k<c;k++){  
                               try{  
                                  if(trim(document.getElementById('itemQuantityId_edit'+(i+1)).value)==err[k]){
                                      document.getElementById('itemQuantityId_edit'+(i+1)).focus();
                                      return false;
                                  }
                               }
                               catch(e){}
                           }
                         }
                     }
                     else {
                        messageBox(ret[0]);
                        //document.AddCity.cityCode.focus(); 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteOrder(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/OrderMaster/ajaxInitDelete.php';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(orderId) {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/OrderMaster/ajaxGetValues.php';
         document.editOrderForm.reset();
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
                        hiddenFloatingDiv('EditOrderDiv');
                        messageBox("<?php echo ORDER_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                    }
                    //if the order is dispatched
                    if(trim(transport.responseText)=="<?php echo DISPATCHED_EDIT_RESTRICTION;?>") {
                        hiddenFloatingDiv('EditOrderDiv');
                        messageBox("<?php echo DISPATCHED_EDIT_RESTRICTION; ?>");
                        return false;
                    }
                    
                    //clean up text boxes
                    var tbody1 = document.getElementById('orderDetailTableBody_edit');
                    
                    var cnt=tbody1.childNodes.length;
                    for(var i=0;i<cnt;i++){
                        try{
                         var tr=document.getElementById('row_edit'+i);
                         tbody1.removeChild(tr);
                        }
                        catch(e){}
                    }
                    
                    //show the div
                    displayWindow('EditOrderDiv',550,200);
                    
                    
                    var j = eval('('+trim(transport.responseText)+')');                    
                    document.getElementById('divHeaderId1').innerHTML=' Edit Order Details ( Order No. : '+j[0].orderNo+' )';
                    
                    document.editOrderForm.orderId.value=j[0].orderId;
                    document.editOrderForm.supplierId.value=j[0].supplierId;
                    document.editOrderForm.orderDate2.value=j[0].orderDate;
                    
                    if(j.length>0){
                        resourceAddCnt=j.length; 
                        createRows('edit',1,resourceAddCnt,0);
                        for(var i=0;i<resourceAddCnt;i++){
                           document.getElementById('itemCodeId_edit'+(i+1)).value=j[i].itemCode; 
                           document.getElementById('itemNameId_edit'+(i+1)).value=j[i].itemName;
                           document.getElementById('itemQuantityId_edit'+(i+1)).value=j[i].quantity;
                           
                        }
                    }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}  


function dispatchToggle(target,state){
    if(target==1){
        if(state==1){
            document.getElementById('dispatchDate1').value=serverDate;
        }
        else{
            document.getElementById('dispatchDate1').value='';
        }
    }
    else{
        if(state==1){
            document.getElementById('dispatchDate2').value=serverDate;
        }
        else{
            document.getElementById('dispatchDate2').value='';
        }
    }
}

function blankValues(){
    document.addOrderForm.reset();
    //clean up text boxes
    var tbody1 = document.getElementById('orderDetailTableBody_add');
    
    var cnt=tbody1.childNodes.length;
    for(var i=0;i<cnt;i++){
        try{
         var tr=document.getElementById('row_add'+i);
         tbody1.removeChild(tr);
        }
        catch(e){}
    }
}  
window.onload=function(){
    //document.getElementById('timeTableLabelId').focus();
    document.searchForm.reset();
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/OrderMaster/orderMasterContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: orderMaster.php $ 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Interface/INVENTORY
//Fixed bugs found during self-testing
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/09/09   Time: 10:56
//Updated in $/Leap/Source/Interface/INVENTORY
//Corrected add/edit code during order no entry and corrected interface
//path in print file
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/09/09    Time: 14:32
//Updated in $/Leap/Source/Interface/INVENTORY
//Integrated Inventory Management with Leap
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:33
//Created in $/Leap/Source/Interface/INVENTORY
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:04
//Updated in $/Leap/Source/Interface
//Order Master Updated : User can not add more than 20 items at  time
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:46
//Created in $/Leap/Source/Interface
//Added files for "Order Master" module
?>