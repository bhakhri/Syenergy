<?php
//-------------------------------------------------------
// Purpose: To generate student list
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceIncentiveDetails');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Student/initData.php");
global $sessionHandler;
$optionalField = $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Attendance Incentive Details</title>
<script>
var getTab;
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
?> 
<?php

function parseOutput($data){
  return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
}

function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
page=1; //default page
var resourceAddCnt=0;
var resourceAddCnt1=0;


function editWindow(previousClassId,id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(previousClassId);  
}



    //for deleting a row from the table(Second Tab)  
    function deleteSingleRow(value){
      var rval=value.split('~');
      var tbody = document.getElementById('anySingleidBody');
      
      var tr=document.getElementById('rowSingle'+rval[0]);
      tbody.removeChild(tr);
      reCalculateSIngleRow();
      
      if(isMozilla){
          if((tbody.childNodes.length-2)==0){
              resourceAddCnt1=0;
          }
      }
      else{
          if((tbody.childNodes.length-1)==0){
              resourceAddCnt1=0;
          }
      }
    } 
//for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody.removeChild(tr);
      reCalculate();
      
      if(isMozilla){
          if((tbody.childNodes.length-2)==0){
              resourceAddCnt=0;
          }
      }
      else{
          if((tbody.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
    } 

 // check browser
     var isMozilla = (document.all) ? 0 : 1;
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
     createRows(resourceAddCnt,cnt);
}
 var bgclass='';
 var bgclass1='';
//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt){

       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
     var showDelete = ''; 
                         
     for(var i=0;i<rowCnt;i++) {
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+parseInt(start+i,10));
          var cell1=document.createElement('td');  
          var cell2=document.createElement('td');
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td'); 
          var cell5=document.createElement('td');
          
          cell1.name='srNo'; 
          cell1.setAttribute('class','searchhead_text');  
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
         // var txt0=document.createTextNode(i+1);
          
          var idStore=document.createElement('input');   
          
          var txt1=document.createElement('input');
          var txt2=document.createElement('input');
          var txt3=document.createElement('input');
          var txt4=document.createElement('a');
            
          
          // To store table ids 
          idStore.setAttribute('type','hidden'); 
          idStore.setAttribute('name','idNos[]');
          idStore.setAttribute('id','idNos'+parseInt(start+i,10));  
          idStore.setAttribute('value',parseInt(start+i,10));
                    
          txt1.setAttribute('id','attendancePerFrom'+parseInt(start+i,10));
          txt1.setAttribute('name','attendancePerFrom[]'); 
          txt1.setAttribute('style','width:100px');    
          txt1.setAttribute('maxlength','5');      
          txt1.className='htmlElement';
          
          txt2.setAttribute('id','attendancePerTo'+parseInt(start+i,10));
          txt2.setAttribute('name','attendancePerTo[]'); 
          txt2.setAttribute('style','width:100px');    
          txt2.setAttribute('maxlength','5');      
          txt2.className='htmlElement';
          
          txt3.setAttribute('type','text');
          txt3.setAttribute('id','common'+parseInt(start+i,10));
          txt3.setAttribute('name','common[]');
          txt3.setAttribute('style','width:100px');  
          txt3.setAttribute('maxlength','5');      
          txt3.className='inputbox1';
          
          
          
            
          
          imgSrc = '<?php echo IMG_HTTP_PATH; ?>'+'/deactive.gif';
          
          txt4.setAttribute('id','rd'+parseInt(start+i,10));
          txt4.setAttribute('name','rd[]'); 
          txt4.className='inputbox1';   
          if(showDelete=='1') {
            txt4.setAttribute('title','Deactive');  
            txt4.innerHTML='<img src='+imgSrc+' border="0" alt="Deactive" title="Deactive" width="10" height="10">'; 
            txt4.style.cursor='pointer';   
            txt4.setAttribute('href','javascript:deactiveRecord()');  //for ie and ff  
          }
          else if(showDelete=='') {
            txt4.setAttribute('title','Delete');
            txt4.innerHTML='X';  
            txt4.style.cursor='pointer';
            txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff      
          }
          
          cell1.appendChild(txt0);      
          cell2.appendChild(txt1);
          cell3.appendChild(txt2);
          cell4.appendChild(txt3);
          cell4.appendChild(idStore);  
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
     reCalculate();     
     showDelete = '';  
}
function addSingleRow(cnt)
{
     if(cnt=='')
       cnt=1;  
     if(isMozilla){
         if(document.getElementById('anySingleidBody').childNodes.length <= 3){
            resourceAddCnt1=0; 
         }       
     }  
     else{
         if(document.getElementById('anySingleidBody').childNodes.length <= 1){
           resourceAddCnt1=0;  
         }       
     }
     resourceAddCnt1++; 
     if (resourceAddCnt1 == 0) {
         document.getElementById('trAttendance1').style.display='none';
     }
     else {
         document.getElementById('trAttendance1').style.display='';
     }
     //createRows(resourceAddCnt,cnt);
     createSingleRows(resourceAddCnt1,cnt);
}
 var bgclass='';
 
 

//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createSingleRows(start,rowCnt){

       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anySingleid');
     var tbody= document.getElementById('anySingleidBody');
     var showDelete = ''; 
                         
     for(var i=0;i<rowCnt;i++) {
          var tr=document.createElement('tr');
          tr.setAttribute('id','rowSingle'+parseInt(start+i,10));
          var cell1=document.createElement('td');  
          var cell2=document.createElement('td');
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td');
          var cell5=document.createElement('td');
  
          
          
          cell1.name='srNo'; 
          cell1.setAttribute('class','searchhead_text');  
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
         // var txt0=document.createTextNode(i+1);
          
          var idStore=document.createElement('input');   
          
          var txt1=document.createElement('input');
          var txt2=document.createElement('input');
          var txt3=document.createElement('input');
          var txt4=document.createElement('a');
            
          
          // To store table ids 
          idStore.setAttribute('type','hidden'); 
          idStore.setAttribute('name','idNos[]');
          idStore.setAttribute('id','idNos'+parseInt(start+i,10));  
          idStore.setAttribute('value',parseInt(start+i,10));
                    
          txt1.setAttribute('id','attendancePerFrom1'+parseInt(start+i,10));
          txt1.setAttribute('name','attendancePerFrom1[]'); 
          txt1.setAttribute('style','width:100px');    
          txt1.setAttribute('maxlength','5');      
          txt1.className='htmlElement';
          
          txt2.setAttribute('id','attendancePerTo1'+parseInt(start+i,10));
          txt2.setAttribute('name','attendancePerTo1[]'); 
          txt2.setAttribute('style','width:100px');    
          txt2.setAttribute('maxlength','5');      
          txt2.className='htmlElement';
          

          txt3.setAttribute('type','text');
          txt3.setAttribute('id','common1'+parseInt(start+i,10));
          txt3.setAttribute('name','common1[]');
          txt3.setAttribute('style','width:100px');  
          txt3.setAttribute('maxlength','5');      
          txt3.className='inputbox1';
          
          imgSrc = '<?php echo IMG_HTTP_PATH; ?>'+'/deactive.gif';
          
          txt4.setAttribute('id','rdSingle'+parseInt(start+i,10));
          txt4.setAttribute('name','rdSingle[]'); 
          txt4.className='inputbox1';   
          if(showDelete=='1') {
            txt4.setAttribute('title','Deactive');  
            txt4.innerHTML='<img src='+imgSrc+' border="0" alt="Deactive" title="Deactive" width="10" height="10">'; 
            txt4.style.cursor='pointer';   
            txt4.setAttribute('href','javascript:deactiveRecord()');  //for ie and ff  
          }
          else if(showDelete=='') {
            txt4.setAttribute('title','Delete');
            txt4.innerHTML='X';  
            txt4.style.cursor='pointer';
            txt4.setAttribute('href','javascript:deleteSingleRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff      
          }
          
          cell1.appendChild(txt0);      
          cell2.appendChild(txt1);
          cell3.appendChild(txt2);
          cell4.appendChild(txt3);
          cell4.appendChild(idStore);  
          cell5.appendChild(txt4);   
             
          
                  
          tr.appendChild(cell1);                
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
          tr.appendChild(cell5); 
           
     
          bgclass1=(bgclass1=='row0'? 'row1' : 'row0');
          tr.className=bgclass1;

          tbody.appendChild(tr); 
     } 
     tbl.appendChild(tbody);  
     reCalculateSIngleRow();   
     showDelete = '';  
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
     var a =document.getElementById('anyidBody').getElementsByTagName("td");
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
function reCalculateSIngleRow(){
     var a =document.getElementById('anySingleidBody').getElementsByTagName("td");
      var l=a.length;
      var j=1;
      for(var i=0;i<l;i++){     
        if(a[i].name=='srNo'){
          bgclass1=(bgclass1=='row0'? 'row1' : 'row0');
          a[i].parentNode.className=bgclass1;
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
function resetValues() {
    //document.getElementById('attendanceMarksDetailsForm').reset();
}

function addMarksDescription() {
          var url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxAddMarksDescription.php';
   
   params = generateQueryString('incentiveMarksDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo FINAL_GRADE_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FINAL_GRADE_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText)) {
            messageBox(trim(transport.responseText));  
            //cleanUpTable();
            resetValues();
          //  document.getElementById('trAttendance').style.display='';
           // showFinalGrade();
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
function addFeeDescription() {
          var url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxAddMarksDescription.php';
   
   params = generateQueryString('incentiveFeeDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo FINAL_GRADE_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo FINAL_GRADE_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText)) {
            messageBox(trim(transport.responseText));  
            //  cleanUpTable();
            resetValues();
          //  document.getElementById('trAttendance1').style.display='';
           // showFinalGrade();
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


//this variable is used to determine whether group wise or 
//consolidated attendance view is required
//Modified By : Dipanjan Bhattacharjee
//Date: 06.10.2009
var attendanceConsolidatedView=1;
var viewType=0;
//this function fetches records corresponding to student attendance





function validateMarksDescription(){
	
	
	var ele=document.getElementById('anyid').getElementsByTagName("INPUT");
	
	 var len=ele.length;
      
         for(var i=0;i<len;i++){

//check for empty fields

             if(ele[i].name=='attendancePerFrom[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER ATTENDANCE PERCENTAGE";?>");
                ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }

             if(ele[i].name=='attendancePerTo[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER ATTENDANCE PERCENTAGE";?>");
                ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }

             if(ele[i].name=='common[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER MARKS WEIGHTAGE";?>");
                   ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
                 
                 
                if(ele[i].name=='attendancePerFrom[]' && ele[i].value>100) {
                    messageBox("<?php echo " ATTENDANCE PERCENTAGE SHOULD BE LESS THAN HUNDRED"; ?>");
                    ele[i].focus();
                    return false;
                }

                if(ele[i].name=='attendancePerTo[]' && ele[i].value>100) {
                    messageBox("<?php echo " ATTENDANCE PERCENTAGE SHOULD BE LESS THAN HUNDRED"; ?>");
                    ele[i].focus();
                    return false;
                }

                 if(ele[i].name=='common[]' && ele[i].value>100) {
                    messageBox("<?php echo " MARKS WEIGHTAGE SHOULD BE LESS THAN HUNDRED"; ?>");
                    ele[i].focus();
                    return false;
                }
                 
                
                    
	}
	addMarksDescription();
	    return false;
	
}
         function validateFeeDescription(){
	
	
	var ele=document.getElementById('anySingleid').getElementsByTagName("INPUT");
	
	 var len=ele.length;
      
         for(var i=0;i<len;i++){

//check for empty fields

             if(ele[i].name=='attendancePerFrom1[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER ATTENDANCE PERCENTAGE";?>");
                ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
             if(ele[i].name=='attendancePerTo1[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER ATTENDANCE PERCENTAGE";?>");
                ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
 
             if(ele[i].name=='common1[]' && ele[i].value==''){
                     messageBox("<?php echo "ENTER DISCOUNT AMOUNT";?>");
                   ele[i].className='inputBoxRed';
                     ele[i].focus();
                     return false;
                 }
                 
                 
                if(ele[i].name=='attendancePerFrom1[]' && ele[i].value>100) {
                    messageBox("<?php echo " ATTENDANCE PERCENTAGE SHOULD BE LESS THAN HUNDRED"; ?>");
                    ele[i].focus();
                    return false;
                }

                if(ele[i].name=='attendancePerTo1[]' && ele[i].value>100) {
                    messageBox("<?php echo " ATTENDANCE PERCENTAGE SHOULD BE LESS THAN HUNDRED"; ?>");
                    ele[i].focus();
                    return false;
                }

                
                 
                 
                    
	}
	addFeeDescription();
	    return false;
	
}
function showFinalIncentive() {
   
   var  url = '<?php echo HTTP_LIB_PATH;?>/AttendanceSet/ajaxAssignFinalIncentiveGetValues.php';
   var showDelete = ''; 
   
   document.getElementById('trAttendance').style.display='none';
   document.getElementById('trAttendance1').style.display='none';

   cleanUpTable();   
   
   new Ajax.Request(url,
   {
     method:'post',
     onCreate: function () {
       showWaitDialog(true);
     },
     onSuccess: function(transport){
           hideWaitDialog(true);
            var j = eval('('+trim(transport.responseText)+')');   
            var len=j.length;
       
            if(len>0) {
              att=0;
              fee=0; 
              for(var i=0;i<len;i++) {
                if(j[i]['weightageFormat']=='1') {  
		   document.getElementById('trAttendance').style.display='';
                   addOneRow(1);
                   var varFirst = att+1;
                   attPerFrom = "attendancePerFrom"+varFirst;
                   eval("document.getElementById(attPerFrom).value = j[i]['attendancePercentageFrom']"); 
                   attPerTo = "attendancePerTo"+varFirst;
                   eval("document.getElementById(attPerTo).value = j[i]['attendancePercentageTo']");    
                   id = "common"+varFirst;
                   eval("document.getElementById(id).value = j[i]['weigthage']");
                   att++;
                }
                else 
                if(j[i]['weightageFormat']=='2') {
		   document.getElementById('trAttendance1').style.display='';  
                   addSingleRow(1);
                   var varFirst = fee+1;
                  attPerFrom = "attendancePerFrom1"+varFirst;
                   eval("document.getElementById(attPerFrom).value = j[i]['attendancePercentageFrom']");  
                  attPerTo = "attendancePerTo1"+varFirst;
                   eval("document.getElementById(attPerTo).value = j[i]['attendancePercentageTo']");  
                   id = "common1"+varFirst;
                   eval("document.getElementById(id).value = j[i]['weigthage']");
                   fee++;
                }
              }
           } 
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

/* function to print block report*/
function printReport() {
   form = document.incentiveMarksDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/listStudentIncentiveDetailPrint.php?incentiveDetailId='+form.incentiveDetailId.value;
    window.open(path,"AssignAttendanceIncentive","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    
}
function printFeeReport() {
   form = document.incentiveFeeDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/listStudentIncentiveDetailPrint.php?incentiveDetailId='+form.incentiveDetailId.value;
    window.open(path,"AssignAttendanceIncentive","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    
}


/* function to output data to a CSV*/
function printCSV() {
   
    form = document.incentiveMarksDetailsForm;
	
	path='<?php echo UI_HTTP_PATH;?>/listStudentIncentiveDetailCSV.php?incentiveDetailId='+form.incentiveDetailId.value;
	window.location=path;
}
function printFeeCSV() {
   
    form = document.incentiveFeeDetailsForm;
	
	path='<?php echo UI_HTTP_PATH;?>/listStudentIncentiveDetailCSV.php?incentiveDetailId='+form.incentiveDetailId.value;
	window.location=path;
}


function listPage(path){

	window.location=path;
}
window.onload=function(){

   document.getElementById('trAttendance').style.display='none';
   document.getElementById('trAttendance1').style.display='none';
   showFinalIncentive();
   return false;
}
</script>
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
	showWaitDialog(true);
</SCRIPT>
	
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AttendanceSet/studentIncentiveDetailContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	hideWaitDialog(true);
</SCRIPT>
</body>
</html>
<?php

function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
 

?>
