<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DEPARTMENT ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (04 Aug 2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryGeneratePO');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Department/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Generate PO</title>
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
                                new Array('poNo','PO No.','"width=20%"','',true) ,
								new Array('poDate','Date','width="20%"','align="center"',true),
								new Array('status','Status','width="20%"','align="left"',true),
								new Array('userName','User Name','width="15%"','align="left"',true),
                                new Array('action1','Action','width="5%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxInitGeneratePOList.php';
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



//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (29 July 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function cancelledPO(Id) {
	if(!confirm("<?php echo CANCEL_CONFIRM; ?>")){
			return false;
		}
    rejectPO(Id);
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Jaineesh
// Created on : (29 July 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {


    var fieldsArray = new Array	(
									new Array("poNo","<?php echo ENTER_PO_NO; ?>"),
									new Array("partyCode","<?php echo SELECT_PARTY_CODE; ?>")
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

        addPO();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN DEPARTMENT VALUE
//
//Author : Jaineesh
// Created on : (02 Aug 2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   /*document.addDepartment.departmentName.value = '';
   document.addDepartment.abbr.value = '';*/
   cleanUpTable();
   document.AddPO.reset();
   getLastestPOCode(); //generates new item code
   getIndentValues();
   //document.AddPO.poNo.focus();

}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addPO() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxInitPOAdd.php';
		 var pars = generateQueryString('AddPO');
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
							 populateGeneratePO();
                         }
                         else {
                             hiddenFloatingDiv('AddPO');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
							 cleanUpTable;
							 cleanUpEditTable;
							 populateGeneratePO();
                             //location.reload();
                             return false;
                         }

                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo SELECT_PARTY_CODE; ?>"){
							document.AddPO.partyCode.focus();
						}
						else if (trim(transport.responseText)=="<?php echo PO_ALREADY_EXIST ?>"){
							document.AddPO.poNo.focus();
						}

					 }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getIndentValues() {
		//cleanUpTable();
		//cleanUpEditTable();
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetPOValues.php';
         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous: false,
             //parameters: pars,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     /*if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('AddPO');
                        messageBox("<?php echo REQUISITION_NOT_EDIT; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }*/

                   j = eval('('+trim(transport.responseText)+')');
				   //var len = j['requisitionDetail'].length;
				   document.getElementById('indentDiv').style.display = '';
				   document.getElementById('indentDiv').innerHTML = '';

				   document.getElementById('indentDiv').innerHTML = j['indentDiv'];
				   displayWindow(dv,w,h);
				   //getLastestPOCode();

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DEPARTMENT
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function rejectPO(Id) {
	 url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxInitRejectPO.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		  parameters: {poId: Id},
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 //hiddenFloatingDiv('EditApprovedRequisition');
				 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				 return false;
				 //location.reload();
			 }
			 else{
				 messageBox(trim(transport.responseText));
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
var resourcePOAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

//for deleting a row from the table
    function deletePORow(value,currentId){
    var temp=resourcePOAddCnt;
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_add');
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourcePOAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourcePOAddCnt=0;
          }
      }

      var tr=document.getElementById('row_exp'+rval[0]);
      tbody1.removeChild(tr);
	  reCalculate(1);
	  getAmount(currentId);
	}
	catch (e) {
	}

	
   }

   function cleanUpTable(){
       var tbody = document.getElementById('anyidBody1_add');
       for(var k=0;k<=resourcePOAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row_exp'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }
    }


	function addPOOneRow(cnt,mode) {

        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 3){
                resourcePOAddCnt=0;
             }
        }
        else{
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 1){
               resourcePOAddCnt=0;
             }
        }

        resourcePOAddCnt++;
        createPORows(resourcePOAddCnt,cnt,mode);
    }


	function createPORows(start,rowCnt,mode){
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

          cell1.setAttribute('align','left');
          cell2.setAttribute('align','left');
          cell3.setAttribute('align','left');
		  cell4.setAttribute('align','left');
		  cell5.setAttribute('align','left');
		  cell6.setAttribute('align','left');
		  cell7.setAttribute('align','left');
		  cell8.setAttribute('align','center');

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
          var txt7=document.createElement('a');

          txt1.setAttribute('id','indentType'+parseInt(start+i,10));
          txt1.setAttribute('name','indentType[]');
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
		  /*txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');*/

		  txt4.setAttribute('id','qty'+parseInt(start+i,10));
          txt4.setAttribute('name','qty[]');
          txt4.className='inputbox1';
          txt4.setAttribute('size','"17"');
		  txt4.setAttribute('maxLength',"6");
          txt4.setAttribute('type','text');
		  txt4.setAttribute('style','text-align:right');
		  txt4.onchange = new Function("getAmount('"+thisCtr+"')");

		  txt5.setAttribute('id','rate'+parseInt(start+i,10));
          txt5.setAttribute('name','rate[]');
          txt5.className='inputbox1';

          txt5.setAttribute('size','"10"');
		  txt5.setAttribute('maxLength',"6");
          txt5.setAttribute('type','text');
		  txt5.setAttribute('style','text-align:right');
		  txt5.onchange = new Function("getAmount('"+thisCtr+"')");

		  txt6.setAttribute('id','amount'+parseInt(start+i,10));
          txt6.setAttribute('name','amount[]');
          txt6.className='inputbox1';
          txt6.setAttribute('size','"10"');
		  txt6.setAttribute('maxLength',"6");
		  txt6.setAttribute('style','text-align:right');
          txt6.setAttribute('type','text');
		  txt6.setAttribute('readonly','readonly');

		  //hiddenIds.innerHTML=optionData;
          txt7.setAttribute('id','rd');
          txt7.className='htmlElement';
          txt7.setAttribute('title','Delete');
		  //txt7.onClick = 
          txt7.innerHTML='X';
          txt7.style.cursor='pointer';

		  if(mode == 'add') {
			//txt7.setAttribute('onclick','javascript:deleteExpRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
            txt7.onclick = new Function("deletePORow('" + parseInt(start+i,10)+'~0' + "','"+thisCtr+"')");//new Function("getAmount('"+thisCtr+"')");
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

          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
		  tr.appendChild(cell5);
		  tr.appendChild(cell6);
		  tr.appendChild(cell7);
		  tr.appendChild(cell8);

          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;

          tbody.appendChild(tr);

          // add option Teacher

		  if(mode == 'add') {
			  var len= document.getElementById('indentNo').options.length;
			  var t=document.getElementById('indentNo');
			  // add option Select initially
			  if(len>0) {
				var tt='indentType'+parseInt(start+i,10) ;
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
   function getItemCategory(indentId) {
	indentNo = document.getElementById('indentType'+indentId).value;
	//form = document.AddRequisition;

	if (indentNo == '') {
		document.getElementById('itemCategoryType'+indentId).length = null;
		addOption(document.getElementById('itemCategoryType'+indentId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetItemCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 indentId: indentNo
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);

                    var j = eval('('+trim(transport.responseText)+')');
					if (j == 0) {
						document.getElementById('itemCategoryType'+indentId).value = '';
						return false;
					}
					len = j.length;
					document.getElementById('itemCategoryType'+indentId).length = null;
					addOption(document.getElementById('itemCategoryType'+indentId), '', 'Select');
					for(i=0;i<len;i++) {
						addOption(document.getElementById('itemCategoryType'+indentId), j[i].itemCategoryId, j[i].categoryName);
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}


   function getItem(itemCategoryId) {
	indentNo = document.getElementById('indentType'+itemCategoryId).value;
	itemCategory = document.getElementById('itemCategoryType'+itemCategoryId).value;

	if (indentNo == '') {
		document.getElementById('itemCategoryType'+itemCategoryId).length = null;
		addOption(document.getElementById('itemCategoryType'+itemCategoryId), '', 'Select');
		return false;
	}
	if (itemCategory == '') {
		document.getElementById('item'+itemCategoryId).length = null;
		addOption(document.getElementById('item'+itemCategoryId), '', 'Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetItemValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				 indentId : indentNo,
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

	function getAmount(thisValue) {
	 var totalAmt=0;
	 var ele=document.AddPO.elements;
	 var length =ele.length;
	 for(i=0; i<length; i++){
		 if(ele[i].name == "qty[]"){
			if(!isNaN(ele[i].value) && !isNaN(ele[i+1].value)){
			   ele[i+2].value=(ele[i].value)*(ele[i+1].value);
			}
		   else{
			   ele[i+2].value="0.00";
		   }
		   totalAmt +=parseFloat(ele[i+2].value);
		}
	 }

	//alert("m called");
	/*document.getElementById('totalAmount').value = 0;
	document.getElementById('total').value = 0;
	//start the total from 0, and then add for every row in the table. and then show the total below
	if (document.getElementById('amount'+thisValue).value != '') {
		sum -= parseInt(document.getElementById('amount'+thisValue).value);
	}
	quantity = document.getElementById('qty'+thisValue).value;
	rate = document.getElementById('rate'+thisValue).value;
	document.getElementById('amount'+thisValue).value = quantity * rate;
	sum += parseInt(document.getElementById('amount'+thisValue).value);
	//document.getElementById('totalAmount').value = 0;
	document.getElementById('totalAmount').value = sum;
	//sum is store in hidden filed for further use*/
	document.AddPO.total.value=totalAmt;
	document.AddPO.grandTotal.value=totalAmt;
	updateTotal();
}
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
// Created on : (15.DEC.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function updateTotal(){
	var totalAmt=0, grandtotal=0,vat=0,discount=0.00,adtnlcharges=0,amount,discount1;
	var form = document.AddPO;
	if(form.discount.value == ''){
		form.discount.value = discount;
	}
	if(form.vat.value == ''){
		form.vat.value = vat;
	}
	if(form.aditionalCharges.value == ''){
		form.aditionalCharges.value = adtnlcharges;
	}
	if(!(form.total.value =='')){
		totalAmt=form.total.value;
	}
	form.totalAmount.value=makeFloat(totalAmt);
	if(!form.discount.value == '' || form.discount.value == 0 ){
		discount = form.discount.value;
	}
	form.discount.value=makeFloat(form.discount.value);
	grandtotal = totalAmt - discount;
	if(parseFloat(form.totalAmount.value) < parseFloat(form.discount.value)){
		alert("discount can't be greater than total amount");
		form.discount.style.backgroundColor="red";
		return false;
	}
	else{
		form.discount.style.backgroundColor="white";
	}	
	if(!(form.vat.value == '' || form.vat.value == 0)){
		vat = ((form.vat.value)/100 * grandtotal);
	}
	form.vat.value=makeFloat(form.vat.value);
	grandtotal += parseFloat(vat);
	if(!(form.aditionalCharges.value == '' || form.aditionalCharges.value == 0)){
		adtnlcharges = form.aditionalCharges.value;
	}
	form.aditionalCharges.value = makeFloat(form.aditionalCharges.value);
	grandtotal += parseFloat(adtnlcharges);
	form.grandTotal.value = makeFloat(grandtotal);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditDegree" DIV
//
//Author : Jaineesh
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
		//cleanUpTable();
		//cleanUpEditTable();

         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetPOValues.php';
         new Ajax.Request(url,
           {
             method:'post',
			 asynchronous: false,
             parameters: {indentId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditGeneratePO');
                        messageBox("<?php echo REQUISITION_NOT_EDIT; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }

                   j = eval('('+trim(transport.responseText)+')');
				   //var len = j['requisitionDetail'].length;
				   document.getElementById('indentDiv').style.display = '';
				   document.getElementById('indentDiv').innerHTML = '';

				   document.getElementById('indentDiv').innerHTML = j['indentDiv'];
				   displayWindow(dv,w,h);
				  // getLastestPOCode();


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
					document.AddPO.partyName.value = j.partyName;

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload = function () {
	populateGeneratePO();
}

function populateGeneratePO(){
//	document.AddPO.indentNo.length = null;
//	addOption(document.AddPO.indentNo, 1,  'hello');
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/GeneratePO/ajaxGetIndentValues.php';
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
					document.AddPO.indentNo.length = null;
					var len = j['indentDetail'].length;
					for (i=0; i<len; i++) {
					addOption(document.AddPO.indentNo,j['indentDetail'][i].indentId,j['indentDetail'][i].indentNo);
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to Generate PO report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;  
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayGeneratePOReport.php?'+qstr;
    window.open(path,"DisplayGeneratePOReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayGeneratePOReportCSV.php?'+qstr;
  	window.location = path;
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(INVENTORY_TEMPLATES_PATH . "/GeneratePO/listGeneratePOContents.php");
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
