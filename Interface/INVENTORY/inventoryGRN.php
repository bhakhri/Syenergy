<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DEPARTMENT ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (04 Aug 2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGRN');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Department/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Recieve Goods</title>
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

var sum = 0;
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false),
                                new Array('partyCode','Party Code','"width=20%"','',true) ,
                                new Array('billNo','Bill No.','width="20%"','align="left"',true),
								new Array('billDate','Date','width="15%"','align="center"',true),
                                new Array('action1','Action','width="5%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxInitGRNList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGRN';
editFormName   = 'EditGRN';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGRN';
divResultName  = 'results';
page=1; //default page
sortField = 'partyCode';
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
function cancelledGRN(Id) {
   rejectGRN(Id);
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
									new Array("partyCode","<?php echo SELECT_PARTY_CODE; ?>"),
									new Array("billNo","<?php echo ENTER_BILL_NO; ?>")
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
        addGRN();
        return false;
    }
    else if(act=='Edit') {
        editGRN();
        return false;
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
	document.AddGRN.reset();
	document.AddGRN.partyCode.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addGRN() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxInitGRNAdd.php';
		 var pars = generateQueryString('AddGRN');
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
							 populateGRN();
                         }
                         else {
                             hiddenFloatingDiv('AddGRN');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
							 cleanUpTable;
							 cleanUpEditTable;
							 populateGRN();
                             //location.reload();
                             return false;
                         }

                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo BILL_ALREADY_EXIST ?>"){
							document.AddGRN.billNo.focus();
						}
						else if (trim(transport.responseText)=="<?php echo SELECT_PARTY_CODE ?>"){
							document.AddGRN.partyCode.focus();
						}

					 }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
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
function editGRN() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxInitGRNEdit.php';
         var pars = generateQueryString('EditIndent');

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
                         hiddenFloatingDiv('EditIndent');
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
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function rejectGRN(Id) {
	 if(false===confirm("Do you want to cancel this record?")) {
             return false;
         }
	 else {
	 url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxInitRejectGRN.php';

	 new Ajax.Request(url,
	   {
		 method:'post',
		  parameters: {grnId: Id},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 //hiddenFloatingDiv('EditApprovedRequisition');
				 alert(transport.responseText);
				 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				 return false;
				 //location.reload();
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
	   }
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
var resourceGRNAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

//for deleting a row from the table
    function deleteGRNRow(value){
    var temp=resourceGRNAddCnt;
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_add');
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceGRNAddCnt=0;
			  document.getElementById('totalAmount').value = '';
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceGRNAddCnt=0;
			  document.getElementById('totalAmount').value = '';
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
       for(var k=0;k<=resourceGRNAddCnt;k++){
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
       for(var k=0;k<=resourceGRNAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row_exp'+k));
             }
             catch(e){
				 //alert(e);
                 //alert(k);  // to take care of deletion problem
             }
          }
    }

	function addGRNOneRow(cnt,mode) {

        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 3){
                resourceGRNAddCnt=0;
             }
        }
        else{
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 1){
               resourceGRNAddCnt=0;
             }
        }
        resourceGRNAddCnt++;

        createGRNRows(resourceGRNAddCnt,cnt,mode);
    }


	function createGRNRows(start,rowCnt,mode){
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
		  var cell6=document.createElement('td');
		  var cell7=document.createElement('td');
		  var cell8=document.createElement('td');
		  var cell9=document.createElement('td');

          cell1.setAttribute('align','left');
          cell2.setAttribute('align','left');
          cell3.setAttribute('align','left');
		  cell4.setAttribute('align','left');
		  cell5.setAttribute('align','left');
		  cell6.setAttribute('align','left');
		  cell7.setAttribute('align','left');
		  cell8.setAttribute('align','left');
		  cell9.setAttribute('align','center');

          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
          var txt1=document.createElement('select');
		  var txt2=document.createElement('select');
		  var txt3=document.createElement('select');
		  var txt4=document.createElement('input');
		  var txt5=document.createElement('input');
		  var txt6=document.createElement('input');
		  var txt7=document.createElement('input');
          var txt8=document.createElement('a');

          txt1.setAttribute('id','poType'+parseInt(start+i,10));
          txt1.setAttribute('name','poType[]');
          txt1.className='selectfield1';
		  thisCtr = parseInt(start+i,10);
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/
		  txt1.onblur = new Function("getItemCategory('"+thisCtr+"')");

		  txt2.setAttribute('id','itemCategoryType'+parseInt(start+i,10));
          txt2.setAttribute('name','itemCategoryType[]');
          txt2.className='selectfield1';
		  thisCtr = parseInt(start+i,10);
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/
		  txt2.onblur = new Function("getItem('"+thisCtr+"')");

          txt3.setAttribute('id','item'+parseInt(start+i,10));
          txt3.setAttribute('name','item[]');
          txt3.className='selectfield1';
		  thisCtr = parseInt(start+i,10);
		  txt3.onblur = new Function("getQtyRate('"+thisCtr+"')");
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/

		  txt4.setAttribute('id','qty'+parseInt(start+i,10));
          txt4.setAttribute('name','qty[]');
          txt4.className='inputbox1';
          txt4.setAttribute('size','"10"');
		  txt4.setAttribute('maxLength',"6");
          txt4.setAttribute('type','text');
		  txt4.setAttribute('readonly','readonly');

		  txt5.setAttribute('id','rate'+parseInt(start+i,10));
          txt5.setAttribute('name','rate[]');
          txt5.className='inputbox1';
          txt5.setAttribute('size','"10"');
		  txt5.setAttribute('maxLength',"6");
          txt5.setAttribute('type','text');
		  txt5.setAttribute('readonly','readonly');

		  txt6.setAttribute('id','qtyRec'+parseInt(start+i,10));
          txt6.setAttribute('name','qtyRec[]');
          txt6.className='inputbox1';
          txt6.setAttribute('size','"10"');
		  txt6.setAttribute('maxLength',"6");
          txt6.setAttribute('type','text');
		  txt6.onchange = new Function("getAmount('"+thisCtr+"')");

		  txt7.setAttribute('id','amount'+parseInt(start+i,10));
          txt7.setAttribute('name','amount[]');
          txt7.className='inputbox1';
          txt7.setAttribute('size','"10"');
		  txt7.setAttribute('maxLength',"6");
          txt7.setAttribute('type','text');
		  txt7.setAttribute('readonly','readonly');

		  //hiddenIds.innerHTML=optionData;
          txt8.setAttribute('id','rd');
          txt8.className='htmlElement';
          txt8.setAttribute('title','Delete');
          txt8.innerHTML='X';
          txt8.style.cursor='pointer';

		  if(mode == 'add') {
			//txt7.setAttribute('onclick','javascript:deleteExpRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
            txt8.onclick = new Function("deleteGRNRow('" + parseInt(start+i,10)+'~0' + "')");
		  }

          cell1.appendChild(txt0);
          //cell1.appendChild(hiddenId);
          cell2.appendChild(txt1);
		  cell3.appendChild(txt2);
		  cell4.appendChild(txt3);
		  cell5.appendChild(txt4);
		  cell6.appendChild(txt5);
		  cell7.appendChild(txt6);
		  cell8.appendChild(txt7);
		  cell9.appendChild(txt8);

          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
		  tr.appendChild(cell5);
		  tr.appendChild(cell6);
		  tr.appendChild(cell7);
		  tr.appendChild(cell8);
		  tr.appendChild(cell9);

          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;

          tbody.appendChild(tr);

          // add option Teacher

		  if(mode == 'add') {
			  var len= document.getElementById('poNo').options.length;
			  var t=document.getElementById('poNo');
			  // add option Select initially
			  if(len>0) {
				var tt='poType'+parseInt(start+i,10) ;
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) {
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }

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
			  var len= document.getElementById('poNo1').options.length;
			  var t=document.getElementById('poNo1');
			  // add option Select initially
			  if(len>0) {
				var tt='poType'+parseInt(start+i,10) ;
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) {
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }

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


   /*function getPONo(id) {
	//form = document.AddGRN;

	if (id == '') {
		messageBox("<?php echo SELECT_PARTY_CODE ?>");
		return false;
	}

	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetPOValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				 partyId : id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);

                    var j = eval('('+trim(transport.responseText)+')');
					len = j.length;
					document.getElementById('poType'+id).length = null;
					addOption(document.getElementById('poType'+id), '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.getElementById('poType'+id), j[i].poId, j[i].poNo);
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}*/

   function getItemCategory(poId) {

	poNo = document.getElementById('poType'+poId).value;
	//form = document.AddRequisition;

	if (poNo == '') {
		document.getElementById('itemCategoryType'+poId).length = null;
		addOption(document.getElementById('itemCategoryType'+poId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetItemCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 poId: poNo
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);

                    var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						document.getElementById('itemCategoryType'+poId).value = '';
						return false;
					}
					len = j.length;
					document.getElementById('itemCategoryType'+poId).length = null;
					addOption(document.getElementById('itemCategoryType'+poId), '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.getElementById('itemCategoryType'+poId), j[i].itemCategoryId, j[i].categoryName);
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}

	function getEditItemCategory(poId,itemCategoryId) {
	poNo = document.getElementById('poType'+poId).value;
	//form = document.AddRequisition;
	if (poNo == '') {
		document.getElementById('itemCategoryType'+poId).length = null;
		addOption(document.getElementById('itemCategoryType'+poId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetEditItemCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 poId: poNo
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
					hideWaitDialog(true);

					var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						document.getElementById('itemCategoryType'+poId).value = '';
						return false;
					}
					len = j.length;
					document.getElementById('itemCategoryType'+poId).length = null;
					addOption(document.getElementById('itemCategoryType'+poId), '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.getElementById('itemCategoryType'+poId), j[i].itemCategoryId, j[i].categoryCode);
					}
					if(itemCategoryId != '') {
						document.getElementById('itemCategoryType'+poId).value = itemCategoryId;
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}

   function getItem(itemCategoryId) {
	poNo = document.getElementById('poType'+itemCategoryId).value;
	itemCategory = document.getElementById('itemCategoryType'+itemCategoryId).value;

	/*if (poNo == '') {
		document.getElementById('itemCategoryType'+itemCategoryId).length = null;
		addOption(document.getElementById('itemCategoryType'+itemCategoryId), '', 'Select');
		return false;
	}*/
	if (itemCategory == '') {
		document.getElementById('item'+itemCategoryId).length = null;
		addOption(document.getElementById('item'+itemCategoryId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetItemValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				 poId : poNo,
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
						addOption(document.getElementById('item'+itemCategoryId), j[i].itemId, j[i].itemName);
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}

	function getEditItem(poId,itemId) {
	//	alert(itemId);
	poNo = document.getElementById('poType'+poId).value;
	//alert(poNo);
	//alert(poNo);
	itemCategory = document.getElementById('itemCategoryType'+poId).value;
	//alert(itemCategory);
	//alert(poNo);

	if (poNo == '') {
		document.getElementById('itemCategoryType'+poId).length = null;
		addOption(document.getElementById('itemCategoryType'+poId), '', 'Select');
		return false;
	}
	if (itemCategory == '') {
		document.getElementById('item'+poId).length = null;
		addOption(document.getElementById('item'+poId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetEditItemValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				 poId : poNo,
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
						addOption(document.getElementById('item'+itemCategoryId), j[i].itemId, j[i].itemCode);
					}
					if(itemId != '') {
						document.getElementById('item'+itemCategoryId).value = itemId;
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}

function getAmount(thisValue) {
	if (document.getElementById('amount'+thisValue).value != '') {
		sum -= parseInt(document.getElementById('amount'+thisValue).value);
	}
	quantity = document.getElementById('qtyRec'+thisValue).value;
	rate = document.getElementById('rate'+thisValue).value;
	document.getElementById('amount'+thisValue).value = quantity * rate;
	sum += parseInt(document.getElementById('amount'+thisValue).value);
	document.getElementById('totalAmount').value = sum;
}


function getQtyRate(thisValue) {
	poNo = document.getElementById('poType'+thisValue).value;
	itemCategory = document.getElementById('itemCategoryType'+thisValue).value;
	itemTemp = document.getElementById('item'+thisValue).value;
	if(itemTemp == '') {
		document.getElementById('qty'+thisValue).value = '';
		document.getElementById('rate'+thisValue).value = '';
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetQtyValues.php';
		 new Ajax.Request(url,
		   {
			 method:'post',
			 parameters: {
				 poId: poNo,
				 itemCategoryId: itemCategory,
				 itemId: itemTemp
			 },
			 onCreate: function() {
				 showWaitDialog(true);
			 },
			 onSuccess: function(transport){
					hideWaitDialog(true);

					var j = eval('('+trim(transport.responseText)+')');
					document.getElementById('qty'+thisValue).value = j[0].quantityRequired;
					document.getElementById('rate'+thisValue).value = j[0].rate;

			 },
			 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
	cleanUpTable();
	cleanUpEditTable();
	 url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetGRNValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 asynchronous: false,
		 parameters: {grnId: id},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
				 hideWaitDialog(true);
				 if(trim(transport.responseText)==0) {
					hiddenFloatingDiv('EditGRN');
					messageBox("<?php echo INDENT_NOT_EDIT; ?>");
					sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					//return false;
			   }

			   j = eval('('+trim(transport.responseText)+')');
			   var len = j['indentDetail'].length;
			   document.EditGRN.partyCode.value = j['indentDetail'][0].partyId;
			   document.EditGRN.billNo.value = j['indentDetail'][0].billNo;
			   document.EditGRN.billDate1.value = j['indentDetail'][0].billDate;

			   if(len > 0 ) {
				addGRNOneRow(len,'edit');
				resourceGRNAddCnt = len;
				for(i=0;i<len;i++) {
					varFirst = i+1;
					poType = 'poType'+varFirst;
					//item = 'item'+varFirst;
					//qty = 'qty'+varFirst;

					/*if (j['vehicleServiceRepairDetail'][i]['amount'].length > 6 ) {
						j['vehicleServiceRepairDetail'][i]['amount'] = j['vehicleServiceRepairDetail'][i]['amount'].split('.00')[0];
					}*/
					document.getElementById(poType).value = j['indentDetail'][i].poId;
					//document.getElementById(qty).value = j['indentDetail'][i].quantityRequired;
					getEditItemCategory(varFirst,j['indentDetail'][i].itemCategoryId);
					getEditItem(varFirst,j['indentDetail'][i].itemId);
				}
			}
			   document.EditIndent.indentNo.focus();
		 },
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//used to get lastet item code
function  getLastestPOCode(){

     var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetNewPOCode.php';
     document.AddPO.poNo.value='';
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
                    document.AddPO.poNo.value=trim(transport.responseText);

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
//
//Author : Jaineesh
// Created on : (03.09.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
					document.AddPO.partyName.value = j.partyName;

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to Print GRN report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;  
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayGRNReport.php?'+qstr;
    window.open(path,"DisplayGRNReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayGRNReportCSV.php?'+qstr;
    //alert(path);
	window.location = path;
}

window.onload = function () {
	populateGRN();
}

function populateGRN(){
//	document.AddGRN.poNo.length = null;
//	addOption(document.AddGRN.poNo, 1,  'hello');
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GRN/ajaxGetPOValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
					hideWaitDialog(true);
					var j = eval('('+trim(transport.responseText)+')');
					document.AddGRN.poNo.length = null;
					var len = j['PODetail'].length;
					for (i=0; i<len; i++) {
					addOption(document.AddGRN.poNo,j['PODetail'][i].poId,j['PODetail'][i].poNo);
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(INVENTORY_TEMPLATES_PATH . "/GRN/listGRNContents.php");
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
