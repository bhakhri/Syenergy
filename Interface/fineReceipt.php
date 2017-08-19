<?php
//-------------------------------------------------------
// Purpose: To generate student fee receipt
// functionality 
//
// Author : Saurabh Thukral
// Created on : (13.08.2012)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CollectFine');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
$serverRollNo = trim($REQUEST_DATA['rollNo']);
if($serverRollNo==''){
    $serverRollNo='';
}

require_once(BL_PATH . "/Student/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Collect Fine</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">

resourceAddCnt = 0;
function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	//height=screen.height/2;
	//width=screen.width/2;
	displayFloatingDiv(dv,w,h);
    populateReason(id);   
}

function populateReason(id) {

	 url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetReason.php';
 
	 new Ajax.Request(url,
	   {      
		 method:'post',
		 parameters: {fineStudentId: id},
			 
		  onCreate: function() {
			showWaitDialog();
		 },
			 
		 onSuccess: function(transport){
			  hideWaitDialog();
			  j= trim(transport.responseText).evalJSON();
			  document.getElementById("innerReason").innerHTML = j.reason;
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:validate the data before insertion
//Date:17.7.2008
//------------------------------------------------------------------------
function validateForm(frm, act) {
	
	var fieldsArray = new Array(new Array("studentRoll","<?php echo STUDENT_FEES_ROLL?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {

		if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
		}
	}
	
	lc = document.feeForm.totalCheck.value;
	formx = document.feeForm; 
	if(lc){
	
		feeCycleId ='';
		count=0;
		for(var i=1;i<formx.length;i++){
		
			 
			if(formx.elements[i].checked && formx.elements[i].name=="chb1[]"){
				
				 count++;
			}
			 
		}
	}
	else{
			
		messageBox("<?php echo NO_FINE_CATEGORY?>");
		return false;
	} 
	 
	if(document.feeForm.studentId.value!=''){

		addStudentFees(act);
	}
	else{

		 messageBox("<?php echo STUDENT_CORRECT_ROLL?>");
		 return false;
	}
	
	return false;
	 
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:To insert data
//Date:17.7.2008
//------------------------------------------------------------------------
function addStudentFees(act){
	var ttAct = act;	
	var url = '<?php echo HTTP_LIB_PATH;?>/Fine/initAddFine.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: $('feeForm').serialize(true),
		onCreate:function(transport){ showWaitDialog(true);},
		onSuccess: function(transport){
		hideWaitDialog(true);

		responseValue = (transport.responseText).split('~');

		if(responseValue.length>=1) {
		     document.getElementById('results').innerHTML='<table border="0" cellspacing="1" cellpadding="3" width="100%"><tr class="rowheading"><td valign="middle" width="3%"><B>#</B></td><td valign="middle" width="20%"><B>Fine Type</B></td><td valign="middle" width="10%"><B>Amount</B></td><td valign="middle" width="10%"><B>Is Due?</B></td><td valign="middle" width="12%"><B>Fine Date</B></td><td valign="middle" width="30%"><B>Reason</B></td></tr><tr class="row0"><td valign="middle" colspan="7" align="center">No detail found</td></tr></table>';
		    flag = true;
		    document.getElementById('studentRoll').focus();
		    document.getElementById('myReceipt').innerHTML="--";
		    document.getElementById('myClass').innerHTML="--";
		    document.getElementById('myFirst').innerHTML="--";
            document.getElementById('fatherName').innerHTML="--";
		    document.getElementById('feeForm').reset(); 
	 	    document.feeForm.studentRoll.focus();
		    if(ttAct=='AddandPrint') {
		      printReport(responseValue[1]);
		    } 
		    else {
		      messageBox(trim(responseValue[0])); 
		    }		 
			getLastEntry();
			if(resourceAddCnt > 0) {
 			  cleanUpTable();
			}
			return false;
	 	} 
	 	else {
		  messageBox(trim(transport.responseText)); 
		  document.getElementById('addForm').reset(); 
	 	}
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}
//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:Populates "student data" before edit
//Date:17.7.2008
//------------------------------------------------------------------------
function populateValues(id) {

url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxStudentBasicValues.php';
if(document.getElementById('receiptDate').value!='' && document.getElementById('studentRoll').value!='')
{
new Ajax.Request(url,
   {
		method:'post',
		parameters: {
						receiptDate: (document.getElementById('receiptDate').value),
						rollNo: (document.getElementById('studentRoll').value)
					},
		onCreate:function(transport){ showWaitDialog(true);},
		onSuccess: function(transport){
		hideWaitDialog(true);
		if(trim(transport.responseText)){
			j= trim(transport.responseText).evalJSON();
			
            var rowspan1='width="3%"';
            var rowspan='width="8%"';
            var colspan = "width='10%'";
            rowspan  += " rowspan=2";
            rowspan1 += " rowspan=2";  
            colspan  += " colspan=2";  
        
            var tdArray = new Array(new Array('srNo','#',rowspan1,'',false), 
                                    new Array('fineDate','Fine Date',rowspan,'',false), 
                                    new Array('fineCategoryAbbr','Fine',rowspan,'',false), 
                                    new Array('amount','Amount',rowspan,'align="right"',false),
                                    new Array('reason','Reason',rowspan,'',false));
            tdArray.push(new Array('aa','Previous Paid Fine',colspan,'align="center"',false,true));                                   
            tdArray.push(new Array("receiptNo","Receipt No.",'width="5%"','align="left"',false));
            tdArray.push(new Array("paidAmount","Paid Amount",'width="5%"','align="right"',false));
           
          /*
            var tbHeadArray = new Array(
								new Array('srNo','#','width="3%"',''), 
								new Array('fineDate','Fine Date','width="12%" nowrap','align="center"'), 
                                new Array('fineCategoryAbbr','Fine','width="20%" nowrap',''), 
							  	new Array('reason','Reason','width="25%" nowrap',''),
							  	new Array('amount','Assign By','width="10%" nowrap','align="right"'),
                                new Array('receiptNo','Prev. Paid Rec. No.','width="20%" nowrap',''), 
							  	new Array('paidAmount','Prev. Paid Amount','width="15%" nowrap','align="right"')	
							  	//new Array('employeeAbbreviation','Fine By','width="10%" nowrap','align="left"')							  	
							  	);
			*/ 
			printResultsNoSortingColSpan('results', j.info, tdArray);
			//obj = document.feeForm.elements['chb[]'];
			//obj[0].focus();
			document.feeForm.studentId.value = j.studentinfo[0].studentId;
			document.feeForm.receiptNo.value = j.serialNo;
			document.getElementById('myClass').innerHTML=j.studentinfo[0].className;
            document.getElementById('fatherName').innerHTML=j.studentinfo[0].fatherName;  
			document.getElementById('myReceipt').innerHTML="<?php echo FEE_RECEIPT_PREFIX ?>"+''+j.serialNo;
			document.getElementById('myFirst').innerHTML=j.studentinfo[0].studentName;
			document.feeForm.receivedFrom.value =j.studentinfo[0].studentName;
			document.feeForm.studentClass.value = j.studentinfo[0].classId;
			document.feeForm.totalCheck.value = j.info.length;
			
			document.getElementById('payableAmount').value = j.totalAmount;

			document.getElementById('payableSpan').innerHTML = j.totalAmount;			
		}
		else{
			document.getElementById('myClass').innerHTML='--';
			document.getElementById('myReceipt').innerHTML='--';
			document.getElementById('myFirst').innerHTML='--';
			document.feeForm.receivedFrom.value ='';
			cleanUpTable();
		}
		if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
		  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
		  return false;
 	    }
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });

		   return false;
		 
	}
	else
	{
		document.getElementById('myClass').innerHTML='--';
		document.getElementById('myReceipt').innerHTML='--';
		document.getElementById('myFirst').innerHTML='--';
		document.feeForm.receivedFrom.value ='';
		//printResultsNoSorting('results', j.info, tbHeadArray);
		document.getElementById('results').innerHTML='<table border="0" cellspacing="1" cellpadding="3" width="100%"><tr class="rowheading"><td valign="middle" width="3%"><B>#</B></td><td valign="middle" width="4%"><input type="checkbox" name="checkbox2" value="checkbox"></td><td valign="middle" width="20%"><B>Fine Type</B></td><td valign="middle" width="10%"><B>Amount</B></td><td valign="middle" width="10%"><B>Is Due?</B></td><td valign="middle" width="12%"><B>Fine Date</B></td><td valign="middle" width="30%"><B>Reason</B></td></tr><tr class="row0"><td valign="middle" colspan="7" align="center">No detail found</td></tr></table>';
	}
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:to print fees receipt
//Date:17.7.2008
//------------------------------------------------------------------------
function printReport(id) {

	form = document.feeForm;
	path='<?php echo UI_HTTP_PATH;?>/studentFineReceipt.php?receiptNo='+id;
	
	window.open(path,"evaluationList","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
	return false;
}


function sendKeys(eleName, e) {
	var ev = e||window.event;
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == '13') {
		var form = document.feeForm;
		eval('form.'+eleName+'.focus()');
		return false;
	}
}
function test(){
	if(!isMozilla){
		var ev = window.event;
		thisKeyCode = ev.keyCode;
		if (thisKeyCode == '13') {
			return false;
		}
	}
}

window.onload=function(){
	document.getElementById('lastEntry').innerHTML='';     
	document.feeForm.studentRoll.focus();
	var roll = document.getElementById("studentRoll");
	getLastEntry(); 
	if("<?php echo $serverRollNo; ?>"!=''){
	  roll.value="<?php echo $serverRollNo; ?>";
	  populateValues();
	}
	//autoSuggest(roll);
	document.onkeydown=test;
 
}

function getLastEntry() {
    var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetLastEntry.php';  
    //document.getElementById('lastEntry').innerHTML='';     
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,
         onCreate: function() {
            showWaitDialog(true); 
         },
         onSuccess: function(transport){
           hideWaitDialog(true);
           if(trim(transport.responseText)==false) {
              document.getElementById('lastEntry').innerHTML  = '---';    
           }
           else {
              var ret=trim(transport.responseText).split('!~~!');
              document.getElementById('lastEntry').innerHTML  = ret[0];
              document.getElementById('receiptDate').value = ret[1];
           }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
    }); 
}

function studentFineReceipt(frm) {   
   rollNo=document.getElementById('studentRoll').value;
   url='<?php echo UI_HTTP_PATH;?>/collectStudentFine.php?rollNo='+rollNo;
   window.open(url,"StudentFinePrint","status=1,menubar=1,scrollbars=1, width=1000px");    	 
}

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


   //to clean up table rows
   function cleanUpTable(){ 

	 if(resourceAddCnt==0) {
       return false;
	 }
     try {
          var tbody = document.getElementById('anyidBody');
          for(var k=0;k<=resourceAddCnt;k++) {
              tbody.removeChild(document.getElementById('row'+k));
          }
      } catch(e){}
      resourceAddCnt=0;  
    }

    var bgclass='';

    //create dynamic rows 
    
    //function createRows(start,rowCnt,optionData,sectionData,roomData){
var serverDate="<?php echo date('Y-m-d');?>";
function createRows(start,rowCnt){
	   
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
	  var cell7=document.createElement('td');
	  
	  cell1.setAttribute('align','left'); 
	  cell1.setAttribute('style','padding: 0px 0px 0px 0px');     
	  cell2.setAttribute('align','left'); 
	  cell2.setAttribute('style','padding: 0px 0px 0px 10px');
	  cell3.setAttribute('align','right');
	  cell3.setAttribute('style','padding: 0px 10px 0px 0px'); 
	  cell4.setAttribute('align','right'); 
	  cell4.setAttribute('style','padding: 0px 10px 0px 0px'); 
	  cell5.setAttribute('align','left');
	  cell5.setAttribute('style','padding: 0px 0px 0px 10px'); 

	cell6.setAttribute('align','center');
	cell7.setAttribute('align','center'); 
      
	  if(start==0){
		var txt0=document.createTextNode(start+i+1);
	  }
	  else{
		var txt0=document.createTextNode(start+i);
	  }
      
      var idStore=document.createElement('input');   
      
	  var txt1=document.createElement('select');
	  var txt2=document.createElement('input');
      var txt3=document.createElement('input');
	  var txt4=document.createElement('select');
	  var txt5=document.createElement('select');
	  var txt6=document.createElement('a');
	 
     // To store table ids 
      idStore.setAttribute('type','hidden'); 
      idStore.setAttribute('name','idNos[]'); 
      idStore.setAttribute('value',parseInt(start+i,10));
     
      txt1.setAttribute('id','paymentTypeId'+parseInt(start+i,10));
	  txt1.setAttribute('name','paymentTypeId[]');
	  thisCtr = parseInt(start+i,10);
	  txt1.className='htmlElement';

	  txt2.setAttribute('id','number'+parseInt(start+i,10));
	  txt2.setAttribute('name','number[]');
	  txt2.className='inputbox';
	  txt2.style.width='50px';

	  txt3.setAttribute('id','amount'+parseInt(start+i,10));
	  txt3.setAttribute('name','amount[]'); 
	  txt3.className='inputbox';
	  txt3.style.width='50px';
	  txt3.value='0';
	  txt4.setAttribute('id','issuingBankId'+parseInt(start+i,10));
	  txt4.setAttribute('name','issuingBankId[]');
	  txt4.className='htmlElement';
          
      
	  txt6.setAttribute('id','rd');
	  txt6.className='htmlElement';  
	  txt6.setAttribute('title','Delete');       
	  
	  txt6.innerHTML='X';
	  txt6.style.cursor='pointer';
	  

	  txt6.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
	  
	  cell1.appendChild(txt0);
	  cell1.appendChild(idStore);
	  cell2.appendChild(txt1);
	  cell3.appendChild(txt2);
	  cell4.appendChild(txt3);
	  cell5.appendChild(txt4);   
	  cell6.innerHTML='<input type="text" id="dated'+parseInt(start+i,10)+'" name="dated[]" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
	  cell6.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('dated"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
	  cell7.appendChild(txt6);
			 
	  tr.appendChild(cell1);
	  tr.appendChild(cell2);
	  tr.appendChild(cell3);
	  tr.appendChild(cell4);
	  tr.appendChild(cell5);
	  tr.appendChild(cell6);
	  tr.appendChild(cell7); 
	  
	  bgclass=(bgclass=='row0'? 'row1' : 'row0');
	  tr.className=bgclass;
	  
      
	  tbody.appendChild(tr); 
	  var len= document.getElementById('paymentType').options.length;
	  var t=document.getElementById('paymentType');
	  if(len>0) {
		var tt='paymentTypeId'+parseInt(start+i,10) ;
		for(k=0;k<len;k++) { 
		   addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
           document.getElementById(tt).selectedIndex=k; 
		 }
	  }
    
	  var len= document.getElementById('issuingBank').options.length;
	  var t=document.getElementById('issuingBank'); 
	  if(len>0) {
		var tt='issuingBankId'+parseInt(start+i,10) ;
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text); 
		 }
	  }
  } 
  tbl.appendChild(tbody);
}


function deleteRow(value){
     var rval=value.split('~');
     var tbody1 = document.getElementById('anyidBody');
      
     var tr=document.getElementById('row'+rval[0]);
     tbody1.removeChild(tr);
     //resourceAddCnt= resourceAddCnt -1 ; 
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
	 //document.feeForm.rowCnt.value=parseInt(document.feeForm.rowCnt.value)-1;
}


function checkValue(fee,id){
		if(!isEmpty(fee)){
			if(!isDecimal(fee)){
				form= document.feeForm;
				alert("Fee Should Be Numeric.");
				eval("document.getElementById('"+id+"').focus()");
				return false;
			}
		}
}

function paymentMode(){
	//alert("drsg");
	if(document.feeForm.fineType[0].checked==true){
		//alert("Cash Selected")
		document.getElementById('collectionData2').style.display='';
		document.getElementById('collectionData3').style.display='';
	}
	else if(document.feeForm.fineType[1].checked==true){
		//alert("Cheque Selected")
		document.getElementById('collectionData3').style.display='';
		document.getElementById('collectionData2').style.display='none';
	}
	else if(document.feeForm.fineType[2].checked==true){
		//alert("Cheque Selected")
		document.getElementById('collectionData3').style.display='none';
		document.getElementById('collectionData2').style.display='';
	}
}

function getReceiptOn() {
   if(document.getElementById('paidAt').value==2) {
   	 document.getElementById('trRow2').style.display='none';     
   }
   else if(document.getElementById('paidAt').value==1) {
     //document.getElementById('trRow1').style.display='none';
     document.getElementById('trRow2').style.display='';
     //document.getElementById('trRow3').style.display='';
   }
   else{
   	 document.getElementById('trRow2').style.display='none';
   }
   
}


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/studentFineContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
