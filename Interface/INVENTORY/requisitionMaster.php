<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DEPARTMENT ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (02 Aug 2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RequisitionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Department/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Create Requisition  </title>
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
								new Array('requisitionStatus','Status','width="15%"','align="center"',true),
								new Array('totalCount','Items Count','width="10%"','align="right"',true),
                                //new Array('categoryCode','Category Name','width="15%"','',true),
								//new Array('itemCode','Item Name','width="15%"','',true),
								//new Array('quantityRequired','Quantity Required','width="15%"','align="right"',true),
								//new Array('requisitionStatus','Status','width="10%"','',true),
                                new Array('action1','Action','width="5%"','align="right"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxInitRequisitionList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRequisition';   
editFormName   = 'EditRequisition';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRequisition';
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
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (29 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array	(
									new Array("requisitionNo","<?php echo ENTER_REQUISITION_NO; ?>")
									//new Array("itemCategory","<?php echo ENTER_ITEM_CATEGORY; ?>"),
									//new Array("itemCode","<?php echo ENTER_ITEM_CODE; ?>"),
									//new Array("quantityRequired","<?php echo ENTER_QUANTITY_REQUIRED; ?>")
									
								);

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
            /*if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='departmentName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo DEPARTMENT_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            else if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/


            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
       
        addRequisition();
        return false;
    }
    else if(act=='Edit') {
        
        editRequisition();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addRequisition() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxInitRequisitionAdd.php';
		 var pars = generateQueryString('AddRequisition');
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
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddRequisition');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
							 cleanUpTable;
							 cleanUpEditTable;
                             //location.reload();
                             return false;
                         }

                     } 
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo REQUSITION_ALREADY_EXIST ?>"){
							document.AddRequisition.requisitionNo.focus();	
						}
						else if (trim(transport.responseText)=="<?php echo INVALID_REQUISITION_QUANTITY ?>"){
							document.AddRequisition.quantityRequired.focus();	
						}
                        
					 }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE DEPARTMENT
//  id=departmentId
//Author : Jaineesh
// Created on : (02 Aug 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function cancelledRequisition(id) {
         if(false===confirm("Do you want to cancel this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxInitRequisitionDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {requisitionId: id},
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
//THIS FUNCTION IS USED TO CLEAN DEPARTMENT VALUE
//
//Author : Jaineesh
// Created on : (02 Aug 2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   /*document.addDepartment.departmentName.value = '';
   document.addDepartment.abbr.value = '';*/
   cleanUpTable();
   cleanUpEditTable();
   document.AddRequisition.reset();
   getLastestRequistionCode('Add'); //generates new item code
   document.AddRequisition.requisitionNo.focus();
   
}


//used to get lastet item code
function  getLastestRequistionCode(mode){
    
     var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxGetNewRequisitionCode.php';
     document.AddRequisition.requisitionNo.value='';
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
                    document.AddRequisition.requisitionNo.value=trim(transport.responseText);
                    
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editRequisition() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxInitRequisitionEdit.php';
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
                   else {
                         messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=="<?php echo REQUSITION_ALREADY_EXIST ?>"){
                            document.EditRequisition.requisitionNo.focus();    
                        }
						else if (trim(transport.responseText)=="<?php echo INVALID_REQUISITION_QUANTITY ?>"){
							document.EditRequisition.quantityRequired.focus();	
						}
                        
                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

function reCalculate(mode){
  var a=document.getElementsByTagName('td');
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo1'){
    if(mode==1){
     bgclass=(bgclass=='row0'? 'row1' : 'row0');
     a[i].parentNode.className=bgclass;
    }
      a[i].innerHTML=j;
      j++;
    }
  }
 // resourceExpAddCnt=j-1;
}

var bgclass='';
var resourceRequisitionAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

//for deleting a row from the table 
    function deleteRequisitionRow(value){
    var temp=resourceRequisitionAddCnt;    
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_add');
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceRequisitionAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceRequisitionAddCnt=0;
          }
      }
	  
      var tr=document.getElementById('row_exp'+rval[0]);
      tbody1.removeChild(tr);
	  reCalculate(1);
	}
	catch (e) {
	}
   }

   function deleteEditRequisitionRow(value){
    var temp=resourceRequisitionAddCnt;    
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_edit');
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceRequisitionAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceRequisitionAddCnt=0;
          }
      }
	  
      var tr=document.getElementById('row_exp'+rval[0]);
      tbody1.removeChild(tr);
	  reCalculate(1);

	}
	catch (e) {
	}
   }

   function cleanUpTable(){
       var tbody = document.getElementById('anyidBody1_add');
       for(var k=0;k<=resourceRequisitionAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row_exp'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }

	function cleanUpEditTable(){
       var tbody = document.getElementById('anyidBody1_edit');
	   //alert(tbody);
       for(var k=0;k<=resourceRequisitionAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row_exp'+k));
             }
             catch(e){
				 //alert(e);
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }


	function addRequisitionOneRow(cnt,mode) {
	
        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 3){
                resourceRequisitionAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 1){
               resourceRequisitionAddCnt=0;  
             }       
        }
		
        resourceRequisitionAddCnt++; 
        createRequisitionRows(resourceRequisitionAddCnt,cnt,mode);
    }


	

	function createRequisitionRows(start,rowCnt,mode){
           // alert(start+'  '+rowCnt);
		 var serverDate = "<?php echo date('Y-m-d') ?>";
         var tbl=document.getElementById('anyid1_'+mode);
         var tbody = document.getElementById('anyidBody1_'+mode);
         
         for(var i=0;i<rowCnt;i++){
          var tr=document.createElement('tr');
          tr.setAttribute('id','row_exp'+parseInt(start+i,10));
          
          var cell1=document.createElement('td');
		  cell1.setAttribute('align','right');
		  cell1.name='srNo1';
          var cell2=document.createElement('td'); 
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td');
		  var cell5=document.createElement('td');
          
          cell1.setAttribute('align','left');
          cell2.setAttribute('align','left');
          cell3.setAttribute('align','left');
		  cell4.setAttribute('align','left');
		  cell5.setAttribute('align','center');

          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
          var txt1=document.createElement('select');
		  var txt2=document.createElement('select');
		  var txt3=document.createElement('input');
          var txt4=document.createElement('a');
          
          txt1.setAttribute('id','itemCategoryType'+parseInt(start+i,10));
          txt1.setAttribute('name','itemCategoryType[]'); 
          txt1.className='selectfield';
		  thisCtr = parseInt(start+i,10);
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/
		  txt1.onblur = new Function("getItem('"+thisCtr+"')");

          txt2.setAttribute('id','item'+parseInt(start+i,10));
          txt2.setAttribute('name','item[]'); 
          txt2.className='selectfield';
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/

		  txt3.setAttribute('id','qty'+parseInt(start+i,10));
          txt3.setAttribute('name','qty[]');
          txt3.className='inputbox1';
          txt3.setAttribute('size','"20"');
		  txt3.setAttribute('maxLength',"6");
          txt3.setAttribute('type','text');

		  //hiddenIds.innerHTML=optionData;         
          txt4.setAttribute('id','rd');
          txt4.className='htmlElement';  
          txt4.setAttribute('title','Delete');       
          
          txt4.innerHTML='X';
          txt4.style.cursor='pointer';
          
		  if(mode == 'add') {
			//txt7.setAttribute('onclick','javascript:deleteExpRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
            txt4.onclick = new Function("deleteRequisitionRow('" + parseInt(start+i,10)+'~0' + "')");
		  }
		  else if (mode == 'edit') {
			txt4.onclick = new Function("deleteEditRequisitionRow('" + parseInt(start+i,10)+'~0' + "')");  //for ie and ff
		  }
          
          cell1.appendChild(txt0);
          //cell1.appendChild(hiddenId);
          cell2.appendChild(txt1);
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
      
          // add option Teacher   
		 
		  if(mode == 'add') {
			  var len= document.getElementById('itemCategory').options.length;
			  var t=document.getElementById('itemCategory');
			  // add option Select initially
			  if(len>0) {
				var tt='itemCategoryType'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }

			  var len= document.getElementById('itemCode').options.length;
			  var t=document.getElementById('itemCode');
			  // add option Select initially
			  if(len>0) {
				var tt='item'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }
		  }

		  if(mode == 'edit') {
			  var len= document.getElementById('itemCategory1').options.length;
			  var t=document.getElementById('itemCategory1');
			  // add option Select initially
			  if(len>0) {
				var tt='itemCategoryType'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }
			  
			  var len= document.getElementById('itemCode1').options.length;
			  var t=document.getElementById('itemCode1');
			  // add option Select initially
			  if(len>0) {
				var tt='item'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }
		  }
      }
      tbl.appendChild(tbody);
	  reCalculate(0);
   }


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
		cleanUpTable();
		cleanUpEditTable();
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxGetRequisitionValues.php';
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
				   var len = j['requisitionDetail'].length;
				   document.EditRequisition.requisitionNo.value = j['requisitionDetail'][0].requisitionNo;

				   if(len > 0 ) {
					addRequisitionOneRow(len,'edit');
					resourceRequisitionAddCnt = len;
					
					for(i=0;i<len;i++) {
						varFirst = i+1;
						itemCategoryType = 'itemCategoryType'+varFirst;
						//item = 'item'+varFirst;
						qty = 'qty'+varFirst;
						/*if (j['vehicleServiceRepairDetail'][i]['amount'].length > 6 ) {
							j['vehicleServiceRepairDetail'][i]['amount'] = j['vehicleServiceRepairDetail'][i]['amount'].split('.00')[0];
						}*/
						document.getElementById(itemCategoryType).value = j['requisitionDetail'][i].itemCategoryId;
						document.getElementById(qty).value = j['requisitionDetail'][i].quantityRequired;
						getEditItem(varFirst,j['requisitionDetail'][i].itemId);
					}
				}
                   document.EditRequisition.requisitionNo.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/*function getItem(itemCategoryId) {
	alert(itemCategoryId);
	form = document.AddRequisition;
	if (form.itemCategory.value=='') {
		form.itemCode.length = null;
		addOption(form.itemCode, '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxGetItemCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCategoryId: form.itemCategory.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    
                    var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						form.itemCode.value = '';
						return false;

					}
					len = j.length;
					form.itemCode.length = null;
					addOption(form.itemCode, '', 'Select');
					for(i=0;i<len;i++) {
						addOption(form.itemCode, j[i].itemId, j[i].itemCode);
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}*/

function getItem(itemCategoryId) {
	//alert(itemId);
	itemCategory = document.getElementById('itemCategoryType'+itemCategoryId).value;
	//form = document.AddRequisition;
	
	if (itemCategory == '') {
		document.getElementById('item'+itemCategoryId).length = null;
		addOption(document.getElementById('item'+itemCategoryId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxGetItemCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCategoryId: itemCategory
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    
                    var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						document.getElementById('item'+itemCategoryId).value = '';
						return false;
					}
					len = j.length;
					document.getElementById('item'+itemCategoryId).length = null;
					addOption(document.getElementById('item'+itemCategoryId), '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.getElementById('item'+itemCategoryId), j[i].itemId, j[i].itemName+"("+j[i].itemCode+")");
					}
					
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getEditItem(itemCategoryId,itemId) {
	//alert(itemId);
	itemCategory = document.getElementById('itemCategoryType'+itemCategoryId).value;
	
	if (itemCategory == '') {
		document.getElementById('item'+itemCategoryId).length = null;
		addOption(document.getElementById('item'+itemCategoryId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequisitionMaster/ajaxGetItemCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 itemCategoryId: itemCategory
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    
                    var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						document.getElementById('item'+itemCategoryId).value = '';
						return false;
					}
					len = j.length;
					document.getElementById('item'+itemCategoryId).length = null;
					addOption(document.getElementById('item'+itemCategoryId), '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.getElementById('item'+itemCategoryId), j[i].itemId, j[i].itemName+"("+j[i].itemCode+")");
					}
					if(itemId != '') {
						document.getElementById('item'+itemCategoryId).value = itemId;
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printRequisitionReport(requisitionId) {
	var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayRequisitionDetailReport.php?requisitionId='+requisitionId;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"RequisitionDetailReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddRequisition;
 }
 else{
     var form = document.EditRequisition;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

/* function to print city report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;  
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayRequisitionReport.php?'+qstr;
    window.open(path,"DisplayRequisitionReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayRequisitionReportCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(INVENTORY_TEMPLATES_PATH . "/RequisitionMaster/listRequisitionContents.php");
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
