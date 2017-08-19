<?php

//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValuesNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Fee Head Values New</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript" language="javascript">
var resourceAddCnt=0;
var showDelete='';
var valShow=0;
 var valShowGenerate=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

var dtArray=new Array();  

function reCalculate(){
    
      var a=document.getElementsByTagName('td');
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
    createRows(0,resourceAddCnt);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBranches() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/getBranches.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	degreeId: document.getElementById('degreeId').value	
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.branchId.length = null; 
			if(j== 0 || len == 0) {
				addOption(form.branchId,'', 'Select');
			}
			else{	
				addOption(form.branchId,'all', 'All');
				for(i=0;i<len;i++) {
					addOption(form.branchId, j[i].branchId, j[i].branchCode);
				}
			}
			getBatch();
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBatch() { 
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/getBatches.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	branchId: document.getElementById('branchId').value,
				degreeId: document.getElementById('degreeId').value
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.batchId.length = null;
			if(j==0 || len == 0) {
				addOption(form.batchId, '', 'Select');
			}
			else{
				addOption(form.batchId, 'all', 'All');
				for(i=0;i<len;i++) {
					addOption(form.batchId, j[i].batchId, j[i].batchName);
				}
			}
			getClass();
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Classes
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getSearchValue(){
if(document.allDetailsForm.activeRadio[0].checked==true) {  
     isActive=1; 
  }
  else if(document.allDetailsForm.activeRadio[1].checked==true) {  
     isActive=2;
  }
  else {
     isActive=3;
  }

       form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/getClases.php';	
	
	new Ajax.Request(url,
		{
		
		method:'post',
		
		parameters: {	isActive: isActive
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.generateClassId.length = null;
			

			for(i=0;i<len;i++) {					
			addOption(form.generateClassId, j[i].classId, j[i].className);

			}
			resetResult();
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	  });	
}

function getClass() { 

	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/getClases.php';	
		
	
	new Ajax.Request(url,
	 {		
		method:'post',
		
		parameters: {	branchId: document.getElementById('branchId').value,
				degreeId: document.getElementById('degreeId').value,
				batchId: document.getElementById('batchId').value
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.classId.length = null;
			if(j==0 || len == 0) {
			
				addOption(form.classId, '', 'Select');
			
				return false;
			}
			else{
				for(i=0;i<len;i++) {					
					addOption(form.classId, j[i].classId, j[i].className);
					
				}
			}
			resetResult();
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
	   	
}

//for deleting a row from the table 
function deleteRow(value){
   
    try {   
        var temp=resourceAddCnt;  
        //resourceAddCnt--;
        
        var rval=value.split('~');
        var tbody1 = document.getElementById('anyidBody');
          
        var tr=document.getElementById('row'+rval[0]);
        tbody1.removeChild(tr);
         
        if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
            resourceAddCnt=temp;
          }
        }
        else{
          if((tbody1.childNodes.length-1)==0){
            resourceAddCnt=temp;
          }
        }
    } catch(e){ }
    
    reCalculate();
    getTotal();
} 
                                 

//to add one row at the end of the list
function addOneRow(cnt)
{
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
     if (resourceAddCnt == 0) {
         document.getElementById('trAttendance').style.display='none';
     }
     else {
         document.getElementById('trAttendance').style.display='';
     }
     createRows(resourceAddCnt,cnt,eval("document.getElementById('quota').innerHTML"),eval("document.getElementById('feeHead').innerHTML"));
}


//to clean up table rows
function cleanUpTable() {
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


var bgclass='';

//create dynamic rows 
function createRows(start,rowCnt,quotaData){

       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
                         
     for(var i=0;i<rowCnt;i++) {
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+parseInt(start+i,10));
          var cell1=document.createElement('td');  
          var cell2=document.createElement('td');
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td'); 
          var cell5=document.createElement('td'); 
          var cell6=document.createElement('td'); 
          
          cell1.name='srNo'; 
          cell1.setAttribute('align','left');  
          cell1.setAttribute('align','left');  
          cell2.setAttribute('align','left');     
          cell3.setAttribute('align','left'); 
          cell4.setAttribute('align','left'); 
          cell5.setAttribute('align','left');  
          cell6.setAttribute('align','center'); 
          
          var txt0=document.createElement('label');   
          if(start==0){
            txt0.className='dataFont'; 
            txt0.innerHTML=start+i+1;    
            //txt0=document.createTextNode(start+i+1);
          }
          else{
            txt0.className='dataFont'; 
            txt0.innerHTML=start+i;   
            //txt0=document.createTextNode(start+i);
          }
        
          var txt1=document.createElement('select');
          var txt2=document.createElement('select');
          var txt3=document.createElement('select');
           
          var idStore=document.createElement('input');
          
          var txt4=document.createElement('input');
          var txt5=document.createElement('a');
          
          // To store table ids 
          idStore.setAttribute('type','hidden'); 
          idStore.setAttribute('name','idNos[]'); 
          idStore.setAttribute('value',parseInt(start+i,10));
          
          txt1.setAttribute('id','feeHeadId'+parseInt(start+i,10));
          txt1.setAttribute('name','feeHeadId[]'); 
          txt1.setAttribute('style','width:270px');    
          txt1.className='htmlElement';
          
          txt2.setAttribute('id','quotaId'+parseInt(start+i,10));
          txt2.setAttribute('name','quotaId[]'); 
          txt2.setAttribute('style','width:310px');    
          txt2.className='htmlElement';
     
          txt3.setAttribute('id','isLeet'+parseInt(start+i,10));
          txt3.setAttribute('name','isLeet[]'); 
          txt3.setAttribute('style','width:180px');    
          txt3.className='htmlElement';
              
          txt4.setAttribute('type','text');
          txt4.setAttribute('id','totalAmount'+parseInt(start+i,10));
          txt4.setAttribute('name','totalAmount[]');
          txt4.setAttribute('style','width:100px');
          txt4.setAttribute('onkeyup','getTotal();');  
          txt4.setAttribute('maxlength','8');      
          txt4.className='inputbox1';
            
          
          imgSrc = '<?php echo IMG_HTTP_PATH; ?>'+'/deactive.gif';
          
          txt5.setAttribute('id','rd'+parseInt(start+i,10));
          txt5.setAttribute('name','rd[]'); 
          txt5.className='inputbox1';   
          if(showDelete=='1') {
            txt5.setAttribute('title','Deactive');  
            txt5.innerHTML='<img src='+imgSrc+' border="0" alt="Deactive" title="Deactive" width="10" height="10">'; 
            txt5.style.cursor='pointer';   
            txt5.setAttribute('href','javascript:deactiveRecord()');  //for ie and ff  
          }
          else if(showDelete=='') {
            txt5.setAttribute('title','Delete');
            txt5.innerHTML='X';  
            txt5.style.cursor='pointer';
            txt5.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff      
          }
          
          cell1.appendChild(txt0);      
          cell2.appendChild(txt1);
          cell3.appendChild(txt2);
          cell4.appendChild(txt3);
          
       
          cell4.appendChild(idStore);
          
          cell5.appendChild(txt4);
          cell6.appendChild(txt5);
                  
          tr.appendChild(cell1);                
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
          tr.appendChild(cell5);
          tr.appendChild(cell6);
     
          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;

          tbody.appendChild(tr); 
          
          // add option Quota Data
          var quotaLen= document.getElementById('quota').options.length;
          var t=document.getElementById('quota');
          // add option Select initially
          if(quotaLen>0) {
             var tt='quotaId'+parseInt(start+i,10); 
             //alert(eval("document.getElementById(tt).length"));
             for(k=0;k<quotaLen;k++) { 
               addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
             }
          }
          
          // add option Fee Head Data
          var feeHeadLen= document.getElementById('feeHead').options.length;
          var t=document.getElementById('feeHead');
          // add option Select initially
          if(feeHeadLen>0) {
             var tt='feeHeadId'+parseInt(start+i,10); 
             //alert(eval("document.getElementById(tt).length"));
             for(k=0;k<feeHeadLen;k++) { 
               addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
             }
          } 
          
          // add option Applicable To (isLeet) Data
          var leetLen= document.getElementById('leet').options.length;
          var t=document.getElementById('leet');
          // add option Select initially
          if(leetLen>0) {
             var tt='isLeet'+parseInt(start+i,10); 
             //alert(eval("document.getElementById(tt).length"));
             for(k=0;k<leetLen;k++) { 
               addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
             }
          } 
     } 
     tbl.appendChild(tbody);   
     reCalculate();  
     showDelete = '';  
}

var percentFromArray=new Array();
var percentToArray=new Array();
var posArray=new Array();


function deactiveRecord() {
   messageBox ("<?php echo DEPENDENCY_CONSTRAINT; ?>");   
}

function validateAddForm(frm) {
    showDelete = '';
    dtArray.splice(0,dtArray.length); //empty the array  
    
    if(trim(document.getElementById('degreeId').value)==""){
      messageBox("<?php echo SELECT_DEGREE; ?>");
      document.getElementById('degreeId').focus();
      return false;
    }
     if(trim(document.getElementById('branchId').value)==""){
      messageBox("<?php echo SELECT_BRANCH; ?>");
      document.getElementById('branchId').focus();
      return false;
    }
     if(trim(document.getElementById('batchId').value)==""){
      messageBox("<?php echo SELECT_BATCH; ?>");
      document.getElementById('batchId').focus();
      return false;
    }
    
    if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
    } 
    
    if(document.getElementById('feeCycleId').value==""){
      messageBox("Select Fee Cycle");
      document.getElementById('feeCycleId').focus();
      return false;
    } 
    
    totalSeatArray=new Array();
    posArray=new Array();
    
    //alert(resourceAddCnt);
    if(resourceAddCnt==0) {
        //msg = confirm('All values for this subject type will be deleted. Are you sure?')
        msg = confirm('All Fee Head Value will be deleted. Are you sure?')
        if(msg == false) {
            return false;
        }
    }
    else {
            var chkDuplicateRecord = '';
            formx = document.allDetailsForm;
            var obj=formx.getElementsByTagName('INPUT');
            var total=obj.length;
            for(var i=0;i<total;i++) {
                if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) {
                   // blank value check 
                   id =obj[i].value;
                   if(eval("document.getElementById('feeHeadId"+id+"').value")=='') {
                     messageBox ("Select Fee Head");  
                     eval("document.getElementById('feeHeadId"+id+"').focus()");
                     return false;             
                   }
                   
                   if(eval("document.getElementById('isLeet"+id+"').value")=='') {
                     messageBox ("Select Applicable To");  
                     eval("document.getElementById('isLeet"+id+"').focus()");
                     return false;             
                   }
                 
                   chkDuplicateRecord = eval("document.getElementById('feeHeadId"+id+"').value");
                   chkDuplicateRecord = chkDuplicateRecord + "~"+eval("document.getElementById('quotaId"+id+"').value");
                   chkDuplicateRecord = chkDuplicateRecord + "~"+eval("document.getElementById('isLeet"+id+"').value");
                 
                   if(trim(eval("document.getElementById('totalAmount"+id+"').value"))=='') {                          
                     messageBox ("Enter numeric value for amount");
                     eval("document.getElementById('totalAmount"+id+"').focus()");  
                     return false;
                   }
                   
                   // Integer Value Checks updated
                   if(!isDecimal(trim(eval("document.getElementById('totalAmount"+id+"').value")))) {                          
                     messageBox ("Enter numeric value for amount");
                     eval("document.getElementById('totalAmount"+id+"').focus()");  
                     return false;
                   }
                   
                   if(checkDuplicate(chkDuplicateRecord)==0){
                     messageBox ("This column value already assign");  
                     eval("document.getElementById('feeHeadId"+id+"').focus()");   
                     return false;
                   }
                }
            }
    }
    
    addFeeHeadValue();
    return false;
}


function checkDuplicate(value) {
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

function resetValues() {
	form = document.allDetailsForm;
	document.getElementById('allDetailsForm').reset();
	document.getElementById('classId').selectedIndex=0;  
	document.getElementById('classId').focus();
	document.getElementById('trAttendance').style.display='none';
	document.getElementById('results').style.display='none';
	document.getElementById('results11').style.display='none';
	form.classId.length = null;
	addOption(form.classId, '', 'Select');
	form.branchId.length = null;
	addOption(form.branchId, '', 'Select');
	form.batchId.length = null;
	addOption(form.batchId, '', 'Select');
}

function resetResult(){
	document.getElementById('trAttendance').style.display='none';
	document.getElementById('results').style.display='none';
	document.getElementById('results11').style.display='none';
}

function hideValue() {
    document.getElementById("totAmount").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>"; 
    document.getElementById('trAttendance').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    cleanUpTable();   
    
}

function showFeeHeadValue(){
  
   var  url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/ajaxGetFeeCycleHeadValues.php';
   document.getElementById("totAmount").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>"; 
   var showDelete = '';  
   document.getElementById('trAttendance').style.display='none';    
   cleanUpTable();   
   
   if(trim(document.getElementById('degreeId').value)==""){
      messageBox("<?php echo SELECT_DEGREE; ?>");
      document.getElementById('degreeId').focus();
      return false;
    }
     if(trim(document.getElementById('branchId').value)==""){
      messageBox("<?php echo SELECT_BRANCH; ?>");
      document.getElementById('branchId').focus();
      return false;
    }
     if(trim(document.getElementById('batchId').value)==""){
      messageBox("<?php echo SELECT_BATCH; ?>");
      document.getElementById('batchId').focus();
      return false;
    }
     
   if(document.getElementById('classId').value==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
   } 
   
   document.getElementById('editFeeHeadId').value = '';
   
   //if(document.allDetailsForm.subjectTypeId.value!='') {
   if(document.allDetailsForm.classId.value!='') {
      document.getElementById('results').style.display='';    
      document.getElementById('results11').style.display='';    
      
      cleanUpTable();   
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {classId: document.getElementById('classId').value
                     },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('('+trim(transport.responseText)+')');   
                var len=j.length;
                if(len>0) {
                  document.getElementById('trAttendance').style.display='';                    
                  for(var i=0;i<len;i++) {
                    addOneRow(1);
                    var varFirst = i+1;
                    document.getElementById('editFeeHeadId').value=1; 
                    var id = "feeHeadId"+varFirst;
                    eval("document.getElementById(id).value = j[i]['feeHeadId']");   
                    
                    id = "quotaId"+varFirst;
                    if(j[i]['quotaId']=='0') {
                      eval("document.getElementById(id).value = 'all'");    
                    }
                    else {
                      eval("document.getElementById(id).value = j[i]['quotaId']");    
                    }
                    
                    id = "isLeet"+varFirst;
                    eval("document.getElementById(id).value = j[i]['isLeet']");   
                    
                    id = "totalAmount"+varFirst;
                    eval("document.getElementById(id).value = j[i]['feeHeadAmount']");
                    
                    id = "feeCycleId"; 
                    eval("document.getElementById(id).value = j[i]['feeCycleId']"); 
                  }
               } 
               getTotal();
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });  
   }
}

function addFeeHeadValue() {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/ajaxFeeHeadValueAdd.php';
   

   if(document.getElementById('classId').value==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
   } 
   
    if(document.getElementById('feeCycleId').value==""){
      messageBox("Select Fee Cycle");
      document.getElementById('feeCycleId').focus();
      return false;
    } 
   
   
   document.getElementById('trAttendance').style.display='none';
   if (document.allDetailsForm.classId.value != '') {
     document.getElementById('trAttendance').style.display='';
   }
   
   params = generateQueryString('allDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo FEE_HEAD_VALUE_ADDED_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FEE_HEAD_VALUE_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FEE_HEAD_VALUE_UPDATED_SUCCESSFULLY;?>") == trim(transport.responseText)) {
            messageBox(trim(transport.responseText));  
            cleanUpTable();
            resetValues();
            return false;
        }
        else {
           var ret=trim(transport.responseText).split('!~!');
           if(ret.length>0) {
              var j0 = trim(ret[0]);
              for(i=1;i<ret.length;i++) { 
                 id = "feeHeadId"+ret[i];
                 eval("document.getElementById('"+id+"').className='inputboxRed'"); 
                 eval("document.getElementById('"+id+"').focus()");   
              }
              messageBox(j0);
           }
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function getShowDetail() {
  	document.getElementById("showhideSeats").style.display='';
     	document.getElementById("lblMsg").innerHTML="Hide";
     	document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
  
   if(valShow==0) {
   	document.getElementById("showhideSeats").style.display='none';
   	document.getElementById("lblMsg").innerHTML="Show"; 
   	document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     	valShow=1;
   }
   else {
     	valShow=0;  
   }
}


function getShowGenerateDetail() {

   document.getElementById("generateDiv").style.display='none';
   document.getElementById("lblMsg11").innerHTML="Show"; 
   document.getElementById("showInfo11").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
   if(valShowGenerate==0) {
     document.getElementById("generateDiv").style.display='';
     document.getElementById("lblMsg11").innerHTML="Hide";
     document.getElementById("showInfo11").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
     valShowGenerate=1;
   }
   else {
     valShowGenerate=0;  
   }
}
function addFeeHeadValuesCopy() {
   var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/ajaxFeeHeadValuesCopy.php';
   
/* 
   if(document.getElementById('copyFeeCycleId').value==""){
      messageBox("Select Fee Cycle");
      document.getElementById('copyFeeCycleId').focus();
      return false;
   }
*/   
   if(trim(document.getElementById('souClassId').value)==""){
      messageBox("Select Class to Copy From");
      document.getElementById('souClassId').focus();
      return false;
   }
   
   if(trim(document.getElementById('copyClassId').value)==""){
      messageBox("<?php echo SELECT_COPY_TO_CLASS; ?>");
      document.getElementById('copyClassId').focus();
      return false;
   }
       
   if(false===confirm("Are you sure to Copy Fee Head Values?")) {
     return false;
   }
   else { 
       params = generateQueryString('allDetailsForm');  
       new Ajax.Request(url,
       {
         method:'post',
         parameters: params, 
         asynchronous:false,
         onCreate: function () {
            showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);    
            messageBox(trim(transport.responseText));
            if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) ) {
                
              return false;
            }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
   }
}




function getCopyFeeCylceClasses() { 
     var url = '<?php echo HTTP_LIB_PATH;?>/FeeHeadValues/ajaxGetFeeCycleClasses.php';   
     
     document.allDetailsForm.souClassId.length = null;
     addOption(document.allDetailsForm.souClassId, '', 'Select');
     document.allDetailsForm.copyClassId.length = null;
     
     feeCycleId = document.allDetailsForm.copyFeeCycleId.value;
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: { feeCycleId: feeCycleId },
         asynchronous:false,
         onCreate: function(transport){
           //showWaitDialog(true);
         },
         onSuccess: function(transport){
           //hideWaitDialog(true);
           j = eval('('+ transport.responseText+')');
           len = j.length;
           document.allDetailsForm.souClassId.length = null;
           document.allDetailsForm.copyClassId.length = null;
     
           addOption(document.allDetailsForm.souClassId, '', 'Select');
           for(i=0;i<len;i++) {
             addOption(document.allDetailsForm.souClassId, j[i]['classId'], j[i]['className']);
             addOption(document.allDetailsForm.copyClassId, j[i]['classId'], j[i]['className']);
           }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}

function getCopyClass() {
    
   document.allDetailsForm.copyClassId.length = null; 
   var len= document.getElementById('souClassId').options.length;
   var t=document.getElementById('souClassId');
   if(len>0) {
      for(k=1;k<len;k++) { 
         if(document.getElementById('souClassId').value != t.options[k].value) {       
           addOption(document.getElementById('copyClassId'), t.options[k].value,  t.options[k].text);
         }
      } 
   }
}

//populate list
window.onload=function(){
   valShow=1; 
   //getShowDetail();
   //getFeeCylceClasses();
}

/* function to print Subject report*/
function printReport() {
    path='<?php echo UI_HTTP_PATH;?>/listFeeHeadValuesPrint.php?classId='+document.getElementById('classId').value;
    window.open(path,"FeeHeadValuePrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printReportCSV() {
    path='<?php echo UI_HTTP_PATH;?>/listFeeHeadValuesCSV.php?classId='+document.getElementById('classId').value;     
    window.location = path;  
}

function getTotal() {
	
   var totAmount = 0; 
   var formx = document.allDetailsForm;
   var obj=formx.getElementsByTagName('INPUT');
   var total=obj.length;
   for(var i=0;i<total;i++) {
     if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) { 
       id =obj[i].value;
       if(trim(eval("document.getElementById('totalAmount"+id+"').value"))!='') {
         totAmount = totAmount +  parseFloat(trim(eval("document.getElementById('totalAmount"+id+"').value")),10);
       }
     }
   }
   document.getElementById('totAmount').innerHTML = totAmount;
}


function getUpdatePendingFee() { 
	
   if(trim(document.getElementById('generateClassId').value)==""){
      messageBox("Select Class To Generate Pending Fees");
      document.getElementById('generateClassId').focus();
      return false;
   }
     params = generateQueryString('allDetailsForm');
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHeadValues/ajaxUpdatePendingFee.php';   
     
    new Ajax.Request(url,
    {
         method:'post',
         parameters: params,
         asynchronous:false,
         onCreate: function(transport){
           showWaitDialog(true);
         },
         onSuccess: function(transport){
           hideWaitDialog(true);
           alert(trim(transport.responseText));
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
    });
}


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FeeHeadValues/feeHeadValuesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
