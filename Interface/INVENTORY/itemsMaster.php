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
define('MODULE','ItemsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Items Description </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
    new Array('srNo','#','width="3%"','',false),
    new Array('categoryCode','Category Code','width="10%"','',true),
    new Array('categoryName','Category Name','width="10%"','',true),
    new Array('itemName','Item Name','width="15%"','',true),
    new Array('itemCode','Item Code','width="15%"','',true),
    new Array('reOrderLevel','Re-order Level<br><span style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;color:red;">(Alert to Min Stock Qty.)</span>','width="15%"','align="right"',true),
    new Array('units','Unit','width="8%"','align="left"',true),
    new Array('action','Action','width="3%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxItemsList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddItem';   
editFormName   = 'EditItem';
winLayerWidth  = 360; //  add/edit form widthreOrderLevel
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteItem';
divResultName  = 'results';
page=1; //default page
sortField = 'categoryCode';
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
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    populateValues(id);
    displayWindow(dv,w,h);   
}

//----------------------------------------------reOrderLevel---------
//THIS FUNCTION IS USED TO Provide help window data

//Author : Abhay Kant
// Created on : (28.07.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var topPos = 0;
var leftPos = 0;
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    
    if(document.getElementById('helpChk').checked == false) {
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title; 
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//sho
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {



    var fieldsArray = new Array(
        new Array("categoryCode","<?php echo SELECT_CATEGORY_CODE;?>"),
		new Array("categoryName","<?php echo ENTER_CATEGORY_NAME;?>"),
        new Array("itemName","<?php echo ENTER_ITEM_NAME;?>"),
        new Array("itemCode","<?php echo ENTER_ITEM_CODE;?>"), 
        new Array("reorderLevel","<?php echo ENTER_REORDER_LEVEL;?>"), 
        new Array("unit","<?php echo SELECT_UNIT;?>")
        );

    /*var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][0]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else{
           /* if(fieldsArray[i][0]=="itemName" && trim(eval("frm."+(fieldsArray[i][0])+".value")).length <3  )  {
                messageBox("<?php echo ITEM_NAME_LENGTH_VALIDATION; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
            /*if(fieldsArray[i][0]=="reorderLevel" && !isNumeric(trim(eval("frm."+(fieldsArray[i][0])+".value"))) )  {
                messageBox("<?php echo ENTER_NUMERIC_RL; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
            
            }
            

        }
    }*/
    if(act=='Add') {
		if(eval(document.AddItem.reorderLevel.value) <= 0) {
			messageBox("<?php echo NOT_LESS_THAN_ZERO; ?>");
			document.AddItem.reorderLevel.focus();
			return false;
		}
        addItem();
        return false;
    }
    else if(act=='Edit') {
		if(eval(document.EditItem.reorderLevel.value) <= 0) {
			messageBox("<?php echo NOT_LESS_THAN_ZERO; ?>");
			document.EditItem.reorderLevel.focus();
			return false;
		}
        editItem();
        return false;
    }

}


//for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
      reCalculate();
      
      if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
              resourceAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
    } 
    
var resourceAddCnt=0;
    // check browser
     var isMozilla = (document.all) ? 0 : 1;
//to add one row at the end of the list
    function addOneRow(cnt) {
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        document.getElementById('deleteFlag').value=true;
              
        if(cnt=='')
       cnt=1;  
         if(isMozilla){
             if(document.getElementById('anyidBody').childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody').childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        } 
        resourceAddCnt++; 
        
        createRows(resourceAddCnt,cnt);
    }

 
 var bgclass='';
    
//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt){
       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
     
                         
     for(var i=0;i<rowCnt;i++){
      var tr=document.createElement('tr');
      tr.setAttribute('id','row'+parseInt(start+i,10));
      
      var cell1=document.createElement('td');
      var cell2=document.createElement('td'); 
      var cell3=document.createElement('td');
      var cell4=document.createElement('td');
      var cell5=document.createElement('td');
      var cell6=document.createElement('td');
      
      cell1.setAttribute('align','left');
      cell1.name='srNo';
      cell2.setAttribute('align','left'); 
      cell3.setAttribute('align','left');
      cell4.setAttribute('align','center');
      cell5.setAttribute('align','center');
      cell6.setAttribute('align','center');
      
      
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
      
      var txt1=document.createElement('input');
      var txt2=document.createElement('input');
      var txt3=document.createElement('input');
      var txt4 =document.createElement('select');
      var txt5=document.createElement('a');
      var txt6=document.createElement('input');


      txt1.className="inputbox";
      //txt1.style.width="120px";
      txt1.setAttribute('id','itemName'+parseInt(start+i,10));
      txt1.setAttribute('name','itemName[]');
      txt1.className='inputBox';

      txt2.setAttribute('id','itemCode'+parseInt(start+i,10));
      txt2.setAttribute('name','itemCode[]');
      txt2.setAttribute('type','text');
      txt2.className='inputBox';
      txt2.setAttribute('style','width:80px;');
      txt2.setAttribute('maxlength','5');

      txt3.setAttribute('id','reorderLevel'+parseInt(start+i,10));
      txt3.setAttribute('name','reorderLevel[]');
      txt3.setAttribute('type','text');
      txt3.className='inputbox';
      txt3.setAttribute('style','width:80px;');
      txt3.setAttribute('maxlength','5');
      
      txt4.setAttribute('id','unit'+parseInt(start+i,10));
      txt4.setAttribute('name','unit[]');
      //txt4.setAttribute('type','text');
       txt1.className="selectField";
      txt4.setAttribute('style','width:80px;');
     


      txt5.setAttribute('id','rd');
      txt5.className='htmlElement';  
      txt5.setAttribute('title','Delete');       
      txt5.innerHTML='X';
      txt5.style.cursor='pointer';
      if(itemDel !=''){
      txt5.innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
       txt5.setAttribute('title','linked');
      }
      else{
      txt5.innerHTML='X';
      txt5.setAttribute('title','Delete');   
      txt5.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
       
}	


      txt6.setAttribute('id','hiddenBox'+parseInt(start+i,10));
      txt6.setAttribute('name','hiddenBox[]');
      txt6.setAttribute('type','hidden');
      txt6.className='inputBox';
      
      

      
      
      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      cell5.appendChild(txt4);
      cell6.appendChild(txt5);
      cell6.appendChild(txt6);
      
      
             
      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      tr.appendChild(cell5);
      tr.appendChild(cell6);
      
      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;
      tbody.appendChild(tr); 

     var  id='hiddenBox'+parseInt(start+i,10) ;
     eval("document.getElementById(id).value= -1");
  
     var len= document.getElementById('unitTypeHidden').options.length;
      var t=document.getElementById('unitTypeHidden');
      if(len>0) {
        var tt='unit'+parseInt(start+i,10) ;
        eval('document.addDescription.'+tt+'.length = null');
        for(k=0;k<len;k++) { 
           addOption(eval('document.addDescription.'+tt), t.options[k].value,  t.options[k].text);
        }
     }
}
   tbl.appendChild(tbody); 
    reCalculate();  
}  

//to clean up table rows
    function cleanUpTable(){
       var tbody = document.getElementById('anyidBody');
       for(var k=0;k<=resourceAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }
    
    
//to recalculate Serial no.
function reCalculate(){
  var a =document.getElementById('tableDiv').getElementsByTagName("td");
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo'){
    bgclass=(bgclass=='row0'? 'row1' : 'row0');
    a[i].parentNode.className=bgclass;
      a[i].innerHTML=j;
      j++;
    }
  }
  //resourceAddCnt=j-1;
}      


var typeArray=new Array();
function checkDuplicateValue(value){
    var i= typeArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(typeArray[k]==value){
          fl=0;
          break;
      }  
    }
   if(fl==1){
       typeArray.push(value);
   } 
   return fl;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Item
//
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//reorderLevel
//--------------------------------------------------------
function addItem() {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxAddItem.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCategoryId     : (document.AddItem.categoryCode.value),
				 itemName           : (document.AddItem.itemName.value), 
                 itemCode           : (document.AddItem.itemCode.value), 
                 reorderLevel       : (document.AddItem.reorderLevel.value),
				 unit				: (document.AddItem.unit.value)
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
                         else{
                            hiddenFloatingDiv('AddItem');
                            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if ("<?php echo ITEM_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.AddItem.itemName.focus();
						}
						if ("<?php echo ITEM_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.AddItem.itemCode.focus();
						}
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}




function addItemDescription() {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxAddItemDescription.php';
         new Ajax.Request(url,
           {
             method:'post',
            // asynchronous :false,
             parameters: $('addDescription').serialize(true),
                 
             
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
                         else{
                            hiddenFloatingDiv('AddItem');
                            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if ("<?php echo ITEM_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.AddItem.itemName.focus();
						}
						if ("<?php echo ITEM_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.AddItem.itemCode.focus();
						}
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW Item
//  id=cityId
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteItem(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxDeleteItem.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemId: id
             },
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//reorderLevel
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddItem.reset();
  //document.AddItem.categoryCode.focus();
}


//used to get lastet item code
function  getLastestItemCode(mode){
    
     var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxGetNewItemCode.php';
     document.AddItem.itemCode.value='';
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous :false,
             parameters: {
                 '1': 1
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    document.AddItem.itemCode.value=trim(transport.responseText);
                    
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT An Item
//
//Author : Jaineesh
// Created on : (27 July 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editItem() {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxEditItem.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemId             : (document.EditItem.itemId.value),
				 itemCategoryId     : (document.EditItem.categoryCode.value),
				 itemName           : (document.EditItem.itemName.value), 
                 itemCode           : (document.EditItem.itemCode.value), 
                 reorderLevel       : (document.EditItem.reorderLevel.value),
				 unit				: (document.EditItem.unit.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditItem');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
						if ("<?php echo ITEM_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.EditItem.itemName.focus();
						}
						if ("<?php echo ITEM_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.EditItem.itemCode.focus();
						}
					 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditItem" DIV
//
//Author : Jaineesh
// Created on : (27.07.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxGetItemValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                   
                    var j = eval('('+trim(transport.responseText)+')');
					document.EditItem.itemId.value				= j.itemId;
					//document.EditItem.categoryName.value        = j.categoryName;
                    document.EditItem.categoryCode.value        = j.itemCategoryId;
                    document.EditItem.itemName.value			= j.itemName;
                    document.EditItem.itemCode.value			= j.itemCode;
                    document.EditItem.reorderLevel.value        = j.reOrderLevel;
                    document.EditItem.unit.value				= j.units;
                    document.EditItem.categoryCode.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
var itemDel='';
function populateItemValues(id){
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemsMaster/ajaxGetItemValues1.php';
        cleanUpTable();
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 categoryCode: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                     var j = eval('('+trim(transport.responseText)+')');
		
                    len=j.length;
                 if(len>0) {
              for(i=0;i<len;i++) {
                itemDel='';
                if(j[i]['stockCategoryId'] !=-1){
                 itemDel='1';
                }
	        addOneRow(1);
                varFirst=i+1;
                id = "itemName"+varFirst;
                eval("document.getElementById(id).value = j[i]['itemName']");  
                id = "itemCode"+varFirst;
                eval("document.getElementById(id).value = j[i]['itemCode']");
                id = "reorderLevel"+varFirst;
                eval("document.getElementById(id).value = j[i]['reOrderLevel']");
                id = "unit"+varFirst;
                eval("document.getElementById(id).value = j[i]['units']");
                id = "hiddenBox"+varFirst;
                eval("document.getElementById(id).value = j[i]['itemId']");
              }
              
              itemDel='';
              reCalculate();     
           } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}






function getCategory(itemCategoryId,action) {
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
					if (j == 0 && action == 'Add') {
						document.AddItem.categoryName.value = '';
						return false;

					}
					if (j == 0 && action == 'Edit') {
						document.EditItem.categoryName.value = '';
						return false;

					}
					if(action == 'Add') {
						document.AddItem.categoryName.value = j.categoryName;
					}
					else {
						document.EditItem.categoryName.value = j.categoryName;
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload=function(){
    document.searchForm.reset();
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
var typeArray=new Array();
//function used for validations 

function validateItemDescription()
{

         typeArray.splice(0,typeArray.length); 
  //check for unselected category

          var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
         if(document.addDescription.categoryCode.value==''){
             messageBox("<?php echo "SELECT CATEGORY";?>");
              ele.className='inputBoxRed';
             ele.focus();
             return false;
         }
        

 

         var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");


         var len=ele.length;
      
         for(var i=0;i<len;i++){

//check for empty fields

             if(ele[i].name=='itemName[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER ITEM NAME";?>");
                ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
                else if(ele[i].name=='itemCode[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER ITEM CODE";?>");
                   ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
                 else if(ele[i].name=='reorderLevel[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER REORDER LEVEL";?>");
                   ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
                    
               

                   

//checks for reorder level to be whole num and greater than 0
                
		  if(ele[i].name=='reorderLevel[]' && ele[i].value != (parseInt(ele[i].value))){
		  messageBox("reorderlevel  should be a whole number");
		  ele[i].focus();
                  return false;
	}
             
                if(ele[i].name=='reorderLevel[]' && ele[i].value<0) {
                    messageBox("<?php echo "REORDER-LEVEL SHOULD BE GREATER THAN ZERO"; ?>");
                    ele[i].focus();
                    return false;
                }

		if(ele[i].name=='itemName[]') {
                   var str = trim(ele[i].value).toUpperCase()+"_"+ele[i].name;
                   if(checkDuplicateValue(str)==0) {
                    messageBox("<?php echo "DUPLICATE VALUES";?>");
                    ele[i].focus();
                    return false;
                   }
                }

//check for duplicate values 
		if(ele[i].name=='itemCode[]') {
                   var str = trim(ele[i].value).toUpperCase()+"_"+ele[i].name;
                   if(checkDuplicateValue(str)==0) {
                    messageBox("<?php echo "DUPLICATE VALUES";?>");
                    ele[i].focus();
                    return false;
                   }
                }
           

   
     }
               var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
                var len=ele.length;
                  for(var i=0;i<len;i++){
            
                 if(trim(ele[i].value)==''){
                     messageBox("<?php echo "SELECT UNIT";?>");
                     ele[i].focus();
                     return false;
                 }
	}
addItemDescription();

         
}
         
       

/* function to output data to a CSV*/

function printCSV() {
	var qstr = "searchbox="+trim(document.searchForm.searchbox.value);

	qstr = qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayItemsReportCSV.php?'+qstr;
	window.location = path;
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
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/ItemsMaster/itemsMasterContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script>
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: $ 
//
?>
