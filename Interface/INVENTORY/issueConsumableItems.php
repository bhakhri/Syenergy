<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','IssueConsumableItems');
define('ACCESS','view');

UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Issue Consumable Items </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript"> 
var tableHeadArray = new Array(
        new Array('srNo','#','width="2%"','',false), 
        new Array('issuedFrom','Issued From','width="10%"','align="left"',true) , 
        new Array('categoryName','Item Category','width="10%"','align="left"',true), 
        new Array('itemName','Item Name','width="10%"','',true), 
        new Array('itemQuantity','Quantity','width="10%"','align="right"',true) , 
        new Array('issuedTo','Issued To','width="10%"','align="left"',true),
		new Array('action1','Action','width="5%"','align="center"',false)
  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddConsumableItemsDiv';
editFormName   = 'EditConsumableItemsDiv';
winLayerWidth  = 250; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteConsumableItems';
divResultName  = 'results';
page=1; //default page
sortField = 'issuedFrom';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

/*function editWindow(id,dv,w,h){
    displayWindow(dv,w,h);
	populateValues(id);
    return false;
}*/

function editWindow(id){
	displayWindow('EditConsumableItemsDiv',300,200);
    //displayWindow(dv,w,h);
	populateValues(id);
    return false;
}


var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteConsumableItems(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/ajaxDeleteConsumableItems.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {invConsumableIssuedId: id},
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
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function cancelConsumableItem(id) {
         if(false===confirm("<?php echo CANCEL_CONFIRM;?>")) {
             return false;
         }
         else { 
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/ajaxCancelConsumableItems.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {invConsumableIssuedId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true); 
                    if("<?php echo CANCEL_ITEMS;?>"==trim(transport.responseText)) {
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
function populateValues(id) {
		//cleanUpEditTable();
		/*document.getElementById('editConsumableResults').style.display = '';
		document.getElementById('itemRow1').style.display = '';
		document.getElementById('comments1').style.display = '';*/
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/ajaxGetValues.php';

         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous:false,
             parameters: {
				invConsumableIssuedId : id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditConsumableItemsDiv');
                        messageBox("<?php echo INDENT_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                    }
                    //if the order is dispatched

					j = eval('('+trim(transport.responseText)+')');
					//alert(transport.responseText);
					len = j.consumableArr.length;
					
                    //alert(len);
                    //clean up text boxes
                    
                    mode='edit';

                    if(len>0){
						
						document.editConsumableItems.store.value = j['consumableArr'][0]['invDepttId'];
						document.editConsumableItems.itemCategory.value = j['consumableArr'][0]['itemCategoryId'];
						//alert(j['consumableArr'][0]['itemId']);
						document.editConsumableItems.editItemQuantity.value = j['consumableArr'][0]['itemQuantity'];
						document.editConsumableItems.issuedDate1.value = j['consumableArr'][0]['issuedDate'];
						document.editConsumableItems.commentsTxt.value = j['consumableArr'][0]['comments'];
						document.editConsumableItems.invConsumableIssuedId.value = j['consumableArr'][0]['invConsumableIssuedId'];
						getEditItemName(j['consumableArr'][0]['itemCategoryId'],j['consumableArr'][0]['invDepttId']);
						document.editConsumableItems.editItemName.value = j['consumableArr'][0]['itemId'];
						
						getEditDepttName(j['consumableArr'][0]['invDepttId']);
						//alert(j['consumableArr'][0]['issuedTo']);
						document.editConsumableItems.editIssuedTo.value = j['consumableArr'][0]['issuedTo'];
						//alert(document.editConsumableItems.editIssuedTo.value);
						
						//alert(j['consumableArr'][0]['itemId'])
						/*alert('i m here');
						
						getEditDepttName(document.editConsumableItems.store.value);
						addOneRow(len,mode);

						resourceAddCnt=len;*/

                        //for(var i=0;i<len;i++){
		                	//varFirst = 1;
							//itemId = 'itemId'+varFirst;
							//quantity = 'quantity'+varFirst;
							//issuedTo = 'issuedTo'+varFirst;
							//alert('=======>'+1);
							//alert(j['consumableArr'][0]['itemId']);
							//document.getElementById(itemId).value = j['consumableArr'][0]['itemId'];
							//alert(document.getElementById(itemId).value);
							//alert(j['consumableArr'][i]['itemQuantity']);
							//document.getElementById(quantity).value = j['consumableArr'][i]['itemQuantity'];
							//document.getElementById(issuedTo).value = j['consumableArr'][i]['issuedTo'];
                        //}
                    }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
} 


function blankValues(){
    document.addConsumableItems.reset();
	cleanUpTable();
	document.getElementById('showImage').style.display = '';
	document.getElementById('addConsumableResults').style.display = 'none';
	document.getElementById('itemRow').style.display = 'none';
	document.getElementById('dateOfIssue').style.display = 'none';
	document.getElementById('comments').style.display = 'none';
	document.getElementById('button').style.display = 'none';

    //document.addIndentForm.employeeCode.readOnly=false;
}

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************

var resourceAddCnt=0;
    // check browser
     var isMozilla = (document.all) ? 0 : 1;

    function addDetailRows(value){
         var tbl=document.getElementById('anyid');
         var tbody = document.getElementById('anyidBody');
         //var tblB    = document.createElement("tbody");
         if(!isInteger(value)){
            return false;
         }
         
         if(resourceAddCnt>0){     //if user reenter no of rows
          if(confirm('Previous Data Will Be Erased.\n Are You Sure ?')){
               cleanUpTable();
          }
          else{
              return false;
          }
        } 
        resourceAddCnt=parseInt(value); 
        createRows(0,resourceAddCnt,0);
    }


    //for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody_add');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);

	  reCalculate(1);
     
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
				document.getElementById('showImage').style.display = '';
				document.getElementById('addConsumableResults').style.display = 'none';
				document.getElementById('itemRow').style.display = 'none';
				document.getElementById('dateOfIssue').style.display = 'none';
				document.getElementById('comments').style.display = 'none';
				document.getElementById('button').style.display = 'none';
				resourceAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-1)==0){
			document.getElementById('showImage').style.display = '';
			document.getElementById('addConsumableResults').style.display = 'none';
			document.getElementById('itemRow').style.display = 'none';
			document.getElementById('dateOfIssue').style.display = 'none';
			document.getElementById('comments').style.display = 'none';
			document.getElementById('button').style.display = 'none';
            resourceAddCnt=0;
          }
      }
    }
	

	function reCalculate(mode){
	  var a=document.getElementsByTagName('td');
	  var l=a.length;
	  var j=1;
	  for(var i=0;i<l;i++){     
		if(a[i].name=='srNo'){
			if(mode==1){
				bgclass=(bgclass=='row0'? 'row1' : 'row0');
				a[i].parentNode.className=bgclass;
			}
		  a[i].innerHTML=j;
		  j++;
		}
	  }
	  //resourceAddCnt=j-1;
	}

	//for deleting a row from the table during edit
	function deleteEditRow(value){

      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody_edit');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
     
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


    //to add one row at the end of the list
    function addOneRow(cnt,mode) {
			document.getElementById('showImage').style.display = 'none';
			document.getElementById('addConsumableResults').style.display = '';
			document.getElementById('itemRow').style.display = '';
			document.getElementById('dateOfIssue').style.display = '';
			document.getElementById('comments').style.display = '';
			document.getElementById('button').style.display = '';
        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody_'+mode).childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody_'+mode).childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        }
		
        resourceAddCnt++; 
        createRows(resourceAddCnt,cnt,mode);
    }

    //to clean up table rows
    function cleanUpTable(){
       var tbody = document.getElementById('anyidBody_add');
       for(var k=0;k<=resourceAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }


    var bgclass='';

    //create dynamic rows 
    
    function createRows(start,rowCnt,mode){
           // alert(start+'  '+rowCnt);
         var tbl=document.getElementById('anyid_'+mode);
         var tbody = document.getElementById('anyidBody_'+mode);
         for(var i=0;i<rowCnt;i++){
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+parseInt(start+i,10));
          
          var cell1=document.createElement('td');
		  cell1.setAttribute('align','right');
		  cell1.name='srNo';
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
          var txt2=document.createElement('input');
		  var txt3=document.createElement('select');
          var txt4=document.createElement('a');
          
          txt1.setAttribute('id','itemId'+parseInt(start+i,10));
          txt1.setAttribute('name','itemId[]'); 
          txt1.className='htmlElement';
                    
          txt2.setAttribute('id','quantity'+parseInt(start+i,10));
          txt2.setAttribute('name','quantity[]');
          txt2.className='htmlElement';
          txt2.setAttribute('size','"6"');
		  txt2.setAttribute('maxLength',"5");
          //txt4.onBlur='isIntegerComma(this)';
          txt2.setAttribute('type','text');

		  txt3.setAttribute('id','issuedTo'+parseInt(start+i,10));
          txt3.setAttribute('name','issuedTo[]'); 
          txt3.className='htmlElement';

          //hiddenIds.innerHTML=optionData;
          txt4.setAttribute('id','rd');
          txt4.className='htmlElement';  
          txt4.setAttribute('title','Delete');       
          
          txt4.innerHTML='X';
          txt4.style.cursor='pointer';
          
		  if(mode == 'add') {
			txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
		  }
		  else if (mode == 'edit') {
			txt4.setAttribute('href','javascript:deleteEditRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
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
			  var len= document.getElementById('hiddenItemName').options.length;
			  var t=document.getElementById('hiddenItemName');
			  // add option Select initially
			  if(len>0) {
				var tt='itemId'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }

			  var lenIssue= document.getElementById('hiddenIssuedTo').options.length;
			  var issuet=document.getElementById('hiddenIssuedTo');
			  // add option Select initially
			  if(lenIssue>0) {
				var issuett='issuedTo'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(issuett).length"));
				for(j=0;j<lenIssue;j++) { 
				  addOption(document.getElementById(issuett), issuet.options[j].value,  issuet.options[j].text);
				 }
			  }
		  }
      }
      tbl.appendChild(tbody);
	  reCalculate(0);
   }

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************

function getAddRowList() {
	document.getElementById('addConsumableResults').style.display = 'none';
	document.getElementById('itemRow').style.display = 'none';
	document.getElementById('dateOfIssue').style.display = 'none';
	document.getElementById('comments').style.display = 'none';
	document.getElementById('button').style.display = 'none';

	if (document.getElementById('store').value == '') {
		messageBox("<?php echo ISSUE_DEPARTMENT; ?>");
		document.getElementById('store').focus();
		return false;
	}
	if (document.getElementById('itemCategory').value == '') {
		messageBox("<?php echo ISSUE_ITEMS; ?>");
		document.getElementById('itemCategory').focus();
		return false;
	}
	addOneRow('','add');
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getItemName() {
	document.getElementById('showImage').style.display = '';
	document.getElementById('addConsumableResults').style.display = 'none';
	document.getElementById('itemRow').style.display = 'none';
	document.getElementById('comments').style.display = 'none';
	document.getElementById('dateOfIssue').style.display = 'none';
	document.getElementById('button').style.display = 'none';
	cleanUpTable();
	
	if (document.getElementById('store').value == '') {
		messageBox("<?php echo ISSUE_DEPARTMENT; ?>");
		document.getElementById('store').focus();
		return false;
	}
	/*
	if (document.getElementById('itemCategory').value == '') {
		messageBox("<?php echo ISSUE_ITEMS; ?>");
		document.getElementById('itemCategory').focus();
		return false;
	}*/

	form = document.addConsumableItems;
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/getItemName.php';
	var pars = 'itemCategoryId='+form.itemCategory.value+'&invDepttId='+form.store.value;
	if (form.itemCategory.value=='') {
		form.hiddenItemName.length = null;
		addOption(form.hiddenItemName, '', 'Select');
		return false;
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			
			if(j==0) {
				form.hiddenItemName.length = null;
				addOption(form.hiddenItemName, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.hiddenItemName.length = null;
			addOption(form.hiddenItemName, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.hiddenItemName, j[i].itemId, j[i].itemName);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getDepttName() {
	document.getElementById('showImage').style.display = '';
	document.getElementById('addConsumableResults').style.display = 'none';
	document.getElementById('itemRow').style.display = 'none';
	document.getElementById('dateOfIssue').style.display = 'none';
	document.getElementById('comments').style.display = 'none';
	document.getElementById('button').style.display = 'none';
	document.getElementById('itemCategory').value = '';
	cleanUpTable();
	form = document.addConsumableItems;
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/getDepttName.php';
	var pars = 'invDepttId='+form.store.value;
	if (form.store.value=='') {
		form.hiddenIssuedTo.length = null;
		addOption(form.hiddenIssuedTo, '', 'Select');
		return false;
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		//asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			
			if(j==0) {
				form.hiddenIssuedTo.length = null;
				addOption(form.hiddenIssuedTo, '', 'Select');
				return false;
			}
			len = j.length;
			form.hiddenIssuedTo.length = null;
			addOption(form.hiddenIssuedTo, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.hiddenIssuedTo, j[i].invDepttId, j[i].invDepttAbbr);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getEditDepttName(value) {
	form = document.editConsumableItems;
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/getDepttName.php';
	var pars = 'invDepttId='+value;
	if (value=='') {
		form.editIssuedTo.length = null;
		addOption(form.editIssuedTo, '', 'Select');
		return false;
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			if(j==0) {
				form.editIssuedTo.length = null;
				addOption(form.editIssuedTo, '', 'Select');
				return false;
			}
			len = j.length;
			form.editIssuedTo.length = null;
			//addOption(form.hiddenEditIssuedTo, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.editIssuedTo, j[i].invDepttId, j[i].invDepttAbbr);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getEditItemName(value,depttId) {
	//alert(value);
	//alert('getEditItemName==>'+value);
	/*document.getElementById('editConsumableResults').style.display = 'none';
	document.getElementById('itemRow1').style.display = 'none';
	document.getElementById('comments1').style.display = 'none';
	cleanUpEditTable();*/
	form = document.editConsumableItems;
	
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/getItemName.php';
	//alert(value);
	var pars = 'itemCategoryId='+value+'&invDepttId='+depttId;
	if (form.itemCategory.value=='') {
		form.editItemName.length = null;
		addOption(form.editItemName, '', 'Select');
		return false;
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			if(j==0) {
				form.editItemName.length = null;
				addOption(form.editItemName, '', 'Select');
				return false;
			}
			len = j.length;

			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.editItemName.length = null;
			for(i=0;i<len;i++) {
				addOption(form.editItemName, j[i].itemId, j[i].itemName);
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getAvailableQuantity() {
	form = document.addIndentForm;
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RequestMaster/getItemQuantity.php';
	var pars = 'itemId='+form.itemName.value;
	if (form.itemName.value=='') {
		
	}
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			
			if(j==0) {
				form.itemName.length = null;
				addOption(form.itemName, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.itemName.length = null;
			document.getElementById('quantityRow').style.display = '';
			form.quantity.value = j.availableQty;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function validateAddForm(frm,act) {

    /*
    if(trim(document.getElementById('subjectTypeId').value)==""){
        messageBox("<?php echo SELECT_SUBJECT_TYPE; ?>");
        document.getElementById('subjectTypeId').focus();
        return false;
    }       
   */ 
   
if(act == 'Add') {
    addConsumableItem();
    return false;
}
else if (act == 'Edit') {
	editConsumableItem();
	return false;
 }
}

function addConsumableItem() {
   url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/ajaxInitAddConsumableItem.php';
   
/* if (document.lecturePercentFrm.subjectTypeId.value != '') {
	   document.getElementById('trLecture').style.display='';
   }
*/   
   params = generateQueryString('addConsumableItems');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        //messageBox(trim(transport.responseText)); 
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
			flag = true;
            if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
				blankValues();
			}
			else {
				hiddenFloatingDiv('AddConsumableItemsDiv');
				sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				blankValues();
			}
        }
		else if(trim("<?php echo AVAILABLE_QUANTITY_NOT_MORE;?>") == trim(transport.responseText)) {
			messageBox("<?php echo AVAILABLE_QUANTITY_NOT_MORE;?>")
		}
		else if(trim("<?php echo SELECT_ITEM_NAME;?>") == trim(transport.responseText)) {
			messageBox("<?php echo SELECT_ITEM_NAME;?>")
		}
		else if(trim("<?php echo FILL_VALUE;?>") == trim(transport.responseText)) {
			messageBox("<?php echo FILL_VALUE;?>")
		}
		else if(trim("<?php echo SELECT_ISSUED_TO;?>") == trim(transport.responseText)) {
			messageBox("<?php echo SELECT_ISSUED_TO;?>")
		}
		else if(trim("<?php echo NOT_LESS_THAN_ZERO;?>") == trim(transport.responseText)) {
			messageBox("<?php echo NOT_LESS_THAN_ZERO;?>")
		}
		else if(trim("<?php echo INVALID_VALUE;?>") == trim(transport.responseText)) {
			messageBox("<?php echo INVALID_VALUE;?>")
		}
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function editConsumableItem() {
	
   url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/IssueConsumableItems/ajaxInitEditConsumableItem.php';
   
/* if (document.lecturePercentFrm.subjectTypeId.value != '') {
	   document.getElementById('trLecture').style.display='';
   }
*/   
   params = generateQueryString('editConsumableItems');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        //messageBox(trim(transport.responseText)); 
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {  
             hiddenFloatingDiv('EditConsumableItemsDiv');
			sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
            return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

window.onload=function(){
    document.searchForm.reset();
}

function printReport() {
	var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayConsumableReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"ConsumableReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayConsumableReportCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/IssueConsumableItems/issueConsumableItemsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
<script type="text/javascript" language="javascript">
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: issueConsumableItems.php $ 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:05a
//Created in $/Leap/Source/Interface/INVENTORY
//new files for inventory issue items
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/09/09   Time: 14:24
//Updated in $/Leap/Source/Interface/INVENTORY
//Fixed bugs found during self-testing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:21
//Created in $/Leap/Source/Interface/INVENTORY
//Created  "Indent Master" module under "Inventory Management"
?>