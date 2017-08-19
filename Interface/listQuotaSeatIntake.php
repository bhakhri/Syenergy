<?php
//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Parveen Sharma
// Created on : 27-01-09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','QuotaSeatIntake');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Quota Seat Intake</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script type="text/javascript" language="javascript">

var resourceAddCnt=0;
var showDelete='';
var valShow=0;
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
      calculateTotalSeats();
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
     //createRows(resourceAddCnt,cnt);
     createRows(resourceAddCnt,cnt,eval("document.getElementById('quota').innerHTML"));
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
          cell1.name='srNo'; 
          
          cell1.setAttribute('class','searchhead_text');  
          cell1.setAttribute('align','left');  
          cell2.setAttribute('align','left');     
          cell3.setAttribute('align','left'); 
          cell4.setAttribute('align','center'); 
           
          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
         // var txt0=document.createTextNode(i+1);
          
          var idStore=document.createElement('input');   
          
          var txt1=document.createElement('select');
          var txt2=document.createElement('input');
          var txt3=document.createElement('a');
          
          // To store table ids 
          idStore.setAttribute('type','hidden'); 
          idStore.setAttribute('name','idNos[]');
          idStore.setAttribute('id','idNos'+parseInt(start+i,10));  
          idStore.setAttribute('value',parseInt(start+i,10));
                    
          txt1.setAttribute('id','quotaId'+parseInt(start+i,10));
          txt1.setAttribute('name','quotaId[]'); 
          txt1.setAttribute('style','width:270px');    
          txt1.className='htmlElement';
          
          txt2.setAttribute('type','text');
          txt2.setAttribute('id','totalSeat'+parseInt(start+i,10));
          txt2.setAttribute('name','totalSeat[]');
          txt2.setAttribute('style','width:100px');  
          txt2.setAttribute('maxlength','3');      
          txt2.onkeyup = new Function('calculateTotalSeats()');
          txt2.className='inputbox1';
            
          
          imgSrc = '<?php echo IMG_HTTP_PATH; ?>'+'/deactive.gif';
          
          txt3.setAttribute('id','rd'+parseInt(start+i,10));
          txt3.setAttribute('name','rd[]'); 
          txt3.className='inputbox1';   
          if(showDelete=='1') {
            txt3.setAttribute('title','Deactive');  
            txt3.innerHTML='<img src='+imgSrc+' border="0" alt="Deactive" title="Deactive" width="10" height="10">'; 
            txt3.style.cursor='pointer';   
            txt3.setAttribute('href','javascript:deactiveRecord()');  //for ie and ff  
          }
          else if(showDelete=='') {
            txt3.setAttribute('title','Delete');
            txt3.innerHTML='X';  
            txt3.style.cursor='pointer';
            txt3.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff      
          }
          
          cell1.appendChild(txt0);      
          cell2.appendChild(txt1);
          cell3.appendChild(txt2);
          cell3.appendChild(idStore);  
          cell4.appendChild(txt3);
          
                  
          tr.appendChild(cell1);                
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
     
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
     
    if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
    } 
    
    totalSeatArray=new Array();
    posArray=new Array();
    
    //alert(resourceAddCnt);
    if(resourceAddCnt==0) {
        //msg = confirm('All values for this subject type will be deleted. Are you sure?')
        msg = confirm('All quota seat will be deleted. Are you sure?')
        if(msg == false) {
            return false;
        }
    }
    else {
            formx = document.allDetailsForm;
            //var obj=formx.getElementsByTagName('SELECT');
            var obj=formx.getElementsByTagName('INPUT'); 
            var total=obj.length;
            for(var i=0;i<total;i++) {
               if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) {
                  var id =obj[i].value;
                  var qId = eval("document.getElementById('quotaId"+id+"').value");
                  var tSeat = trim(eval("document.getElementById('totalSeat"+id+"').value")); 
                     
                     // Quota value check 
                  if(qId=='') { 
                    messageBox ("Quota cannot be left blank");  
                    //obj[i].focus();
                    eval("document.getElementById('quotaId"+id+"').className='inputboxRed'"); 
                    eval("document.getElementById('quotaId"+id+"').focus()");
                    return false;             
                  }
                  if(checkDuplicateQuota(qId)==0) {
                    messageBox ("Duplicate quota selected");  
                    eval("document.getElementById('quotaId"+id+"').className='inputboxRed'"); 
                    eval("document.getElementById('quotaId"+id+"').focus()");    
                    return false;
                  }
                     
                  // Total Seats value check 
                  if(tSeat == "") {
                    messageBox ("Seat Intakes field cannot be left blank");
                    eval("document.getElementById('totalSeat"+id+"').className='inputboxRed'"); 
                    eval("document.getElementById('totalSeat"+id+"').focus()");
                    return false;             
                  }
                  
                  if(!isInteger(tSeat)) {
                     messageBox ("Enter numeric value for Seat Intake");
                     eval("document.getElementById('totalSeat"+id+"').className='inputboxRed'"); 
                     eval("document.getElementById('totalSeat"+id+"').focus()");
                     return false;
                  }
                  
                  // Ranges Checks 
                  if(parseInt(tSeat,10) < 0 ) {
                     messageBox ("Seat intakes cannot be zero");
                     eval("document.getElementById('totalSeat"+id+"').className='inputboxRed'"); 
                     eval("document.getElementById('totalSeat"+id+"').focus()");
                     return false;
                  }
                   
                   // Ranges Checks 
                  if(parseInt(tSeat,10) > 999 ) {
                    messageBox ("Seat intakes value between 1 to 999");
                    eval("document.getElementById('totalSeat"+id+"').className='inputboxRed'"); 
                    eval("document.getElementById('totalSeat"+id+"').focus()");
                    return false;
                  }
               }
            }
    }
    addSeatIntakes();
    return false;
}


function calculateTotalSeats() {
     
      formx = document.allDetailsForm;         
      var obj=formx.getElementsByTagName('INPUT');
      var total=obj.length;   
      var totSeats =0;   
      for(i=0;i<total;i++) {
         if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('totalSeat[]')>-1) {
            obj[i].value=obj[i].value.substring(0,3);
            if(trim(obj[i].value)!="" && isInteger(trim(obj[i].value))) {
              totSeats += parseInt(obj[i].value);     
            }
         }
      }
      if(totSeats==0) {
        document.getElementById("totSeats").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>";  
      }
      else { 
        document.getElementById("totSeats").innerHTML = totSeats;
      }
      return true;   
}


function checkDuplicateQuota(value) {
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
    document.getElementById('allDetailsForm').reset();
}

function hideValue() {
    document.getElementById("totSeats").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>"; 
    document.getElementById('trAttendance').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    cleanUpTable();   
}

function showSeatIntake(){
   
   var  url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxQuotaSeatsGetValues.php';
   document.getElementById("totSeats").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>"; 
   var showDelete = '';  
   document.getElementById('trAttendance').style.display='none';    
   cleanUpTable();   
   
   if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
   } 
 
   document.getElementById('classSeatId').value = '';
   
   //if(document.allDetailsForm.subjectTypeId.value!='') {
   if(document.allDetailsForm.classId.value!='') {
      document.getElementById('results').style.display='';    
      document.getElementById('results11').style.display='';    
      
      cleanUpTable();   
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {  classId: document.getElementById('classId').value },
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
                    showDelete='1';
                    if(j[i]['seatsAllocated']==-1 || j[i]['seatsAllocated']==0) {
                      showDelete=''; 
                    }  
                    addOneRow(1);
                    var varFirst = i+1;
                    document.getElementById('classSeatId').value=1; 
                    id = "quotaId"+varFirst;
                    eval("document.getElementById(id).value = j[i]['quotaId']");    
                    id = "totalSeat"+varFirst;
                    eval("document.getElementById(id).value = j[i]['seats']");
                  }
               } 
               calculateTotalSeats();
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       
   }
}

function addSeatIntakes() {
   url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxQuotaSeatsAdd.php';
   
   if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
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
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo QUOTA_SLAB_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo QUOTA_SLAB_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText)) {
            messageBox(trim(transport.responseText));  
            cleanUpTable();
            resetValues();
            document.getElementById('classId').selectedIndex=0;  
            document.getElementById('classId').focus();
            document.getElementById('trAttendance').style.display='none';
            document.getElementById('results').style.display='none';
            document.getElementById('results11').style.display='none';
            return false;
        }
        else {
           var ret=trim(transport.responseText).split('!~~!');
           var j0 = trim(ret[0]);
           var j1 = trim(ret[1]);  
           messageBox(j0);
           if(j1!='') {
             id = "totalSeat"+j1;
             eval("document.getElementById('"+id+"').className='inputboxRed'"); 
             eval("document.getElementById('"+id+"').focus()");
           } 
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}


function getCopyClass() {

   document.allDetailsForm.copyClassId.length = null; 
   var len= document.getElementById('mainClassId').options.length;
   var t=document.getElementById('mainClassId');
   if(len>0) {
      for(k=1;k<len;k++) { 
         if(document.getElementById('mainClassId').value != t.options[k].value) {       
           addOption(document.getElementById('copyClassId'), t.options[k].value,  t.options[k].text);
         }
      } 
   }
}

function getShowDetail() {
   document.getElementById("showhideSeats").style.display='none';
   document.getElementById("lblMsg").innerHTML="Show Copy Seats"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
   if(valShow==1) {
     document.getElementById("showhideSeats").style.display='';
     document.getElementById("lblMsg").innerHTML="Hide Copy Seats";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
     valShow=0;
   }
   else {
     valShow=1;  
   }
}

function addCopySeats() {
   url = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxQuotaCopySeatsAdd.php';
   
   if(trim(document.getElementById('mainClassId').value)==""){
      messageBox("Please select Class to copy From");
      document.getElementById('mainClassId').focus();
      return false;
   }
   
   if(trim(document.getElementById('copyClassId').value)==""){
      messageBox("<?php echo SELECT_COPY_TO_CLASS; ?>");
      document.getElementById('copyClassId').focus();
      return false;
   }
       
   if(false===confirm("Are you sure to copy seats?")) {
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

//populate list
window.onload=function(){
   valShow=1; 
   getShowDetail();
}



</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Quota/listSeatIntakeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                