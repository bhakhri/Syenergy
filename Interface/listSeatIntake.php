<?php
//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Parveen Sharma
// Created on : 27-01-09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','QuotaSeatIntake');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Seat Intake</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script type="text/javascript" language="javascript">

var resourceAddCnt=0;

// check browser
var isMozilla = (document.all) ? 0 : 1;

// Check integerValue
function valInteger(en) {
    en.value = trim(en.value);
    if (false === isInteger(en.value)) {
        messageBox ("Enter interger value ");
        en.value="";
        en.focus();
        return false;
    }
    if (isInteger(en.value)<0) {
        messageBox ("Enter the value between 0 to 100");
        en.value="";
        en.focus();
        return false;
    }
}

function valDecimal(en) {

   en.value = trim(en.value);     
   if(isEmpty(en.value)) { 
      en.focus();
      return false;  
   }             
   if (!isDecimal(en.value)) {
       messageBox ("Enter decmial value ");
       //en.value="";
       en.focus();
       return false;
    }
    if (isDecimal(en.value)<0 || isDecimal(en.value)>100) {
        messageBox ("Marks Scored value between 0 to 100. ");
        en.value="";
        en.focus();
        return false;
    }
    return true;
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
      resourceAddCnt--;
      
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
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
                         
     for(var i=0;i<rowCnt;i++){
      var tr=document.createElement('tr');
      tr.setAttribute('id','row'+parseInt(start+i,10));
      
      var cell1=document.createElement('td');  
      var cell2=document.createElement('td');
      var cell3=document.createElement('td'); 
      var cell4=document.createElement('td'); 
     
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
      
      var txt1=document.createElement('select');
      var txt2=document.createElement('input');
      var txt3=document.createElement('a');
      
      
      txt1.setAttribute('id','quotaId'+parseInt(start+i,10));
      txt1.setAttribute('name','quotaId[]'); 
      txt1.className='htmlElement';
      
      txt2.setAttribute('id','totalSeat'+parseInt(start+i,10));
      txt2.setAttribute('name','totalSeat[]');
      //txt2.setAttribute('onBlur','valInteger(this)');
      txt2.setAttribute('maxlength','"3"');      
      txt2.className='inputbox1';
      txt2.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt2.setAttribute('type','text');
       
      txt3.setAttribute('id','rd');
      txt3.className='inputbox1';  
      txt3.setAttribute('title','Delete');       
      txt3.innerHTML='X';
      txt3.style.cursor='pointer';
      txt3.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
      
      
      cell1.appendChild(txt0);      
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
              
      tr.appendChild(cell1);                
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
 
      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;

      tbody.appendChild(tr); 
      
          // add option Quota Data
          var len= document.getElementById('quota').options.length;
          var t=document.getElementById('quota');
          // add option Select initially
          if(len>0) {
            var tt='quotaId'+parseInt(start+i,10) ; 
            //alert(eval("document.getElementById(tt).length"));
            for(k=0;k<len;k++) { 
              addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
             }
          }
          
      
     } 
     tbl.appendChild(tbody);   
}

var percentFromArray=new Array();
var percentToArray=new Array();
var posArray=new Array();

function validateAddForm(frm) {

/*  
    if(trim(document.getElementById('timeTableLabelId').value)==""){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    } 
    
    if(trim(document.getElementById('degreeId').value)==""){
        messageBox("<?php echo SELECT_DEGREE; ?>");
        document.getElementById('degreeId').focus();
        return false;
    } 
    
    if(trim(document.getElementById('subjectTypeId').value)==""){
        messageBox("<?php echo SELECT_SUBJECT_TYPE; ?>");
        document.getElementById('subjectTypeId').focus();
        return false;
    }       
*/

    if(trim(document.getElementById('attendanceSetId').value)==""){
      messageBox("<?php echo SELECT_ATTENDANCE_SET; ?>");
      document.getElementById('attendanceSetId').focus();
      return false;
    } 
    
    percentFromArray=new Array();
    percentToArray=new Array();
    posArray=new Array();
    
    //alert(resourceAddCnt);
    if(resourceAddCnt==0) {
        //msg = confirm('All values for this subject type will be deleted. Are you sure?')
        msg = confirm('All values for this attendance set will be deleted. Are you sure?')
        if(msg == false) {
            return false;
        }
    }
    else {
            formx = document.attendancePercentFrm;
            var obj=formx.getElementsByTagName('INPUT');
            var total=obj.length;
            var x=0;
            var y=0;
            for(var i=0;i<total;i++) {
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('percentFrom[]')>-1) {
                   // blank value check 
                   if(trim(obj[i].value) == "") {
                     messageBox ("Percent From field cannot be left blank");
                     obj[i].focus();
                     return false;             
                   }
                   
                   // Integer Value Checks updated
                   if(!isInteger(trim(obj[i].value))) {
                     messageBox ("Enter interger value for percentage from (0 to 100)");
                     obj[i].focus();
                     return false;
                   }
                   
                   // Ranges Checks 
                   if(parseInt(obj[i].value,10) < 0 || parseInt(obj[i].value,10) >100 ) {
                     messageBox ("Enter interger value for percentage from (0 to 100)");
                     obj[i].focus();
                     return false;
                   }
                   percentFromArray[x]=parseInt(obj[i].value,10);
                   posArray[x]=i;
                   x++;
                }
                
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('percentTo[]')>-1) {
                   // blank value check 
                   if(trim(obj[i].value) == "") {
                     messageBox ("Percent To field cannot be left blank");
                     obj[i].focus();
                     return false;             
                   }
                   
                   // Integer Value Checks updated
                   if(!isInteger(trim(obj[i].value))) {
                     messageBox ("Enter interger value for percentage to (0 to 100)");
                     obj[i].focus();
                     return false;
                   }
                   
                   // Ranges Checks 
                   if(parseInt(obj[i].value,10) < 0 || parseInt(obj[i].value,10) >100 ) {
                     messageBox ("Enter interger value for percentage to (0 to 100)");
                     obj[i].focus();
                     return false;
                   }
                   percentToArray[y]=parseInt(obj[i].value,10);
                   y++;
                }
                
                if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('marksScored[]')>-1) {
                   // blank value check 
                   if(trim(obj[i].value) == "") {
                     messageBox ("Marks Scored field cannot be left blank");  
                     obj[i].focus();
                     return false;             
                   }
                   
                   // Decimal Value Checks updated
                   if(!isDecimal(trim(obj[i].value))) {
                     messageBox ("Enter decimal value for marks scored (0 to 100)"); 
                     obj[i].focus();
                     return false;
                   }
                   
                   // Ranges Checks 
                   if(parseFloat(obj[i].value) < 0 || parseFloat(obj[i].value) >100 ) {
                     messageBox ("Enter decimal value for marks scored (0 to 100)"); 
                     obj[i].focus();
                     return false;
                   }
                }
            }
            
            // Wrong Data Validation Checks
            for(var i=0;i<x;i++) { 
              ifrom = percentFromArray[i];
              ito   = percentToArray[i];
              if(ifrom > ito) {
                 messageBox("Percent From cannot be greater than Percent To");   
                 for(var z=0;z<total;z++) {
                    if(obj[z].type.toUpperCase()=='TEXT' && obj[z].name.indexOf('percentFrom[]')>-1) {
                        if(z==posArray[i]) {
                          obj[z].focus();  
                          return false;
                        }
                    }
                 }
                 return false;
              }
              for(var k= i+1; k <x; k++) {  
                jfrom = percentFromArray[k];
                jto   = percentToArray[k];
                for(var j=ifrom; j<=ito; j++) {  
                   if(j == jfrom || j == jto) {
                      messageBox("Wrong data input");   
                      for(var z=0;z<total;z++) {
                        if(obj[z].type.toUpperCase()=='TEXT' && obj[z].name.indexOf('percentFrom[]')>-1) {
                            if(z==posArray[k]) {
                              obj[z].focus();  
                              return false;
                            }
                        }
                      }
                      return false;
                   }
                } 
              }
            }
            
            /*
            for(i=0;i<total;i++) {
                // Empty Box Check
                if(trim(formx.elements['percentFrom[]'][i].value) == "") {
                   messageBox ("Percent From field cannot be left blank");
                   formx.elements['percentFrom[]'][i].focus();
                   return false;             
                }
                if(trim(formx.elements['percentTo[]'][i].value) == "") {
                   messageBox ("Percent To field cannot be left blank");
                   formx.elements['percentTo[]'][i].focus();
                   return false;             
                }
                if(trim(formx.elements['marksScored[]'][i].value) == "") {
                   messageBox ("Marks Scored field cannot be left blank");
                   formx.elements['marksScored[]'][i].focus();
                   return false;             
                }
                
                // Integer & Decimal Value Checks updated
                if(!isInteger(trim(formx.elements['percentFrom[]'][i].value))) {
                    messageBox ("Enter interger value for percentage from (0 to 100)");
                    formx.elements['percentFrom[]'][i].focus();
                    return false;
                }
                if(!isInteger(trim(formx.elements['percentTo[]'][i].value))) {
                    messageBox ("Enter interger value for percentage to (0 to 100)");
                    formx.elements['percentTo[]'][i].focus();
                    return false;
                }
                if(!isDecimal(trim(formx.elements['marksScored[]'][i].value))) {   
                    messageBox ("Enter decimal value for marks scored (0 to 100)");
                    formx.elements['marksScored[]'][i].focus();
                    return false;             
                }
                
                // Ranges Checks 
                if(parseInt(formx.elements['percentFrom[]'][i].value) < 0 || parseInt(formx.elements['percentFrom[]'][i].value) >100 ) {
                    messageBox ("Enter interger value for percentage from (0 to 100)");
                    formx.elements['percentFrom[]'][i].focus();
                    return false;
                }
                if(parseInt(formx.elements['percentTo[]'][i].value) < 0 || parseInt(formx.elements['percentTo[]'][i].value) >100 ) {
                    messageBox ("Enter interger value for percentage to (0 to 100)");
                    formx.elements['percentTo[]'][i].focus();
                    return false;
                }
                if(parseFloat(formx.elements['marksScored[]'][i].value) < 0 || parseFloat(formx.elements['marksScored[]'][i].value) >100 ) { 
                    messageBox ("Enter decimal value for marks scored (0 to 100)");   
                    formx.elements['marksScored[]'][i].focus();
                    return false;             
                }
                
                if(parseInt(formx.elements['percentFrom[]'][i].value) > parseInt(formx.elements['percentTo[]'][i].value)) {
                    messageBox("Percent From cannot be greater than Percent To");   
                    formx.elements['percentFrom[]'][i].focus();
                    return false;
                }
            }
            */
    }
    //alert('1');
    //return;
    addAttendanceMarksPercent();
    return false;
}


function resetValues() {
    document.getElementById('attendancePercentFrm').reset();
}

function hideValue() {
   
    document.getElementById('trAttendance').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    cleanUpTable();   
}

function showSeatIntake(){
   
   //var  url = '<?php echo HTTP_LIB_PATH;?>/AttendancePercent/ajaxAttendancePercentGetValues.php';
   
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
 
   //if(document.attendancePercentFrm.subjectTypeId.value!='') {
   if(document.attendancePercentFrm.degreeId.value!='' && document.attendancePercentFrm.branchId.value!='') {
      document.getElementById('results').style.display='';    
      document.getElementById('results11').style.display='';    
      
         /*    
               new Ajax.Request(url,
              {
                 method:'post',
                 parameters: {   //timeTableLabelId: document.getElementById('timeTableLabelId').value,
                                 // degreeId: document.getElementById('degreeId').value,
                                 // subjectTypeId: document.getElementById('subjectTypeId').value
                                 attendanceSetId: document.getElementById('attendanceSetId').value
                 },
                 onCreate: function () {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                        hideWaitDialog(true);
                        j = eval('('+trim(transport.responseText)+')');   
                        len=j.attendancePercentArr.length;
                        
                        if(len>0) {
                            addOneRow(len);
                            resourceAddCnt=len;
                            document.getElementById('trAttendance').style.display='';                    
                            for(i=0;i<len;i++) {
                                varFirst = i+1;
                                perFrom = 'percentFrom'+varFirst;
                                perTo = 'percentTo'+varFirst;
                                marksScored = 'marksScored'+varFirst;
                                eval("document.getElementById(perFrom).value = j['attendancePercentArr'][i]['percentFrom']");
                                eval("document.getElementById(perTo).value = j['attendancePercentArr'][i]['percentTo']");
                                eval("document.getElementById(marksScored).value = j['attendancePercentArr'][i]['marksScored']");
                           } 
                       }
                 },
                 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
               });
         */    
   }
}

function addAttendanceMarksPercent() {
   url = '<?php echo HTTP_LIB_PATH;?>/AttendancePercent/ajaxInitAttendancePercent.php';
   
   if(trim(document.getElementById('attendanceSetId').value)==""){
        messageBox("<?php echo SELECT_ATTENDANCE_SET; ?>");
        document.getElementById('attendanceSetId').focus();
        return false;
   }
/*  
   if(trim(document.getElementById('timeTableLabelId').value)==""){
      messageBox("<?php echo SELECT_TIME_TABLE; ?>");
      document.getElementById('timeTableLabelId').focus();
      return false;
   } 
    
   if(trim(document.getElementById('degreeId').value)==""){
      messageBox("<?php echo SELECT_DEGREE; ?>");
      document.getElementById('degreeId').focus();
      return false;
   } 
   
   if(trim(document.getElementById('subjectTypeId').value)==""){
        messageBox("<?php echo SELECT_SUBJECT_TYPE; ?>");
        document.getElementById('subjectTypeId').focus();
        return false;
    }  
  
   document.getElementById('trAttendance').style.display='none';
   if (document.attendancePercentFrm.subjectTypeId.value != '') {
       document.getElementById('trAttendance').style.display='';
   }
*/ 

   document.getElementById('trAttendance').style.display='none';
   if (document.attendancePercentFrm.attendanceSetId.value != '') {
       document.getElementById('trAttendance').style.display='';
   }
   
   
   
   params = generateQueryString('attendancePercentFrm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        messageBox(trim(transport.responseText)); 
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo SLAB_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText)) {                                                                                                              
            cleanUpTable();
            resetValues();
           /* 
            document.getElementById('timeTableLabelId').selectedIndex=0;  
            document.getElementById("degreeId").selectedIndex=0;
            document.getElementById("subjectTypeId").selectedIndex=0;
            document.getElementById('timeTableLabelId').focus();
           */
            document.getElementById('attendanceSetId').selectedIndex=0;  
            document.getElementById('attendanceSetId').focus();
            
            document.getElementById('trAttendance').style.display='none';
            document.getElementById('results').style.display='none';
            document.getElementById('results11').style.display='none';
            return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function getClassDegree(){
  
   url = '<?php echo HTTP_LIB_PATH;?>/AttendancePercent/ajaxAttendanceDegreeValues.php';

   document.getElementById('trAttendance').style.display='none';    
   cleanUpTable();  
   
   form = document.attendancePercentFrm;
   form.degreeId.length = null;
   addOption(form.degreeId, '', 'Select');
   
   document.getElementById("degreeId").selectedIndex=0;
   document.getElementById("subjectTypeId").selectedIndex=0;
    
   if(document.attendancePercentFrm.timeTableLabelId.value!='' ) {
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {
             timeTableLabelId: document.getElementById('timeTableLabelId').value
         },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                //cleanUpTable();                              
               // alert(transport.responseText);
                var j = eval('('+trim(transport.responseText)+')');
                len=j.length;
                                 
                if(len>0) {
                    for(i=0;i<len;i++) {
                        addOption(document.attendancePercentFrm.degreeId, j[i].degreeId, j[i].degreeCode);
                   }
               }
               
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
   }
}

window.onload=function() {
   //document.getElementById('timeTableLabelId').focus();
   //document.getElementById('attendanceSetId').focus();
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
<?php
// $History: listSeatIntake.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/04/10    Time: 1:14p
//Created in $/LeapCC/Interface
//initial checkin
//

?>