<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SwapTeacherTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Adjust Time Table </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag


//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage=1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
page=1; //default page

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


var bgclass='';
var tableStr=globalTB;
var clickCnt=0;
var srNo=0;
//tableStr +='<tr class="rowheading"><td class="searchhead_text" width="2%" align="left">#</td><td class="searchhead_text" width="15%">Class</td><td class="searchhead_text" width="10%">Subject</td><td class="searchhead_text" width="10%">Group</td><td class="searchhead_text" width="10%">Period</td><td class="searchhead_text" width="10%">Day</td><td class="searchhead_text" width="12%">Substitute Teacher</td><td class="searchhead_text" width="12%">By Teacher</td><td class="searchhead_text" width="10%" align="center">Delete</td></tr>';
tableStr +='<tr class="rowheading"><td class="searchhead_text" width="2%" align="left">#</td><td class="searchhead_text" width="93%" align="left">Swap/Substitution Details</td><td class="searchhead_text" width="5%" align="center">Delete</td></tr>';
function generateSuggestionDiv(timeTableId,str,checked){
    //create and remove divs dynamically
    var parent=document.getElementById('resultDiv');
    var tStr=globalTB;
    if(trim(parent.innerHTML)==''){
        //parent.innerHTML='<hr/><b>Your Selection</b><br/>'+tableStr;
        parent.innerHTML='<hr/><b>Below would be the substituted time table entry if you press save and there are no conflicts</b><br/>'+tableStr;
        parent.style.display='';
        //parent.innerHTML=tableStr;
    }
    
    try{
       
       if(checked){ //if checked is true
         var employeeId=document.searchForm.replaceTeacherId.value;
         var en1=document.searchForm.replaceTeacherId.options[document.searchForm.replaceTeacherId.selectedIndex].text;
         var employeeId2=document.searchForm.replacingTeacherId.value;
         var en2=document.searchForm.replacingTeacherId.options[document.searchForm.replacingTeacherId.selectedIndex].text;
         if(employeeId==employeeId2 && (employeeId!='' && employeeId2!='')){
             messageBox("<?php echo SAME_EMPLOYEE_RESTRICTION;?>");
             return false;
         }
         
         var arg=str.split(',');
         
         bgclass=(bgclass=='row0'? 'row1' : 'row0');
         var hiddenValue='';
         srNo++; 
         if(arg[5]==1){
          //tStr +='<tr class="'+bgclass+'"><td class="padding_top" width="2%" align="left">'+(srNo)+'</td><td class="padding_top" width="15%">'+arg[0]+'</td><td class="padding_top" width="10%">'+arg[1]+'</td><td class="padding_top" width="10%">'+arg[2]+'</td><td class="padding_top" width="10%">'+arg[3]+'</td><td class="padding_top" width="10%">'+arg[4]+'</td><td class="padding_top" width="12%">'+en1+'</td><td class="padding_top" width="12%">'+en2+'</td><td class="padding_top" align="center" width="10%"><a onclick="removeSelection('+timeTableId+');" style="cursor:pointer;"><b>X</b></a></td></tr></table>';
          tStr +='<tr class="'+bgclass+'"><td class="padding_top" width="2%" align="left">'+(srNo)+'</td><td class="padding_top" width="93%"><b>'+en2+'</b> will be taking <b>Class</b> : '+arg[0]+' , <b>Subject</b> : '+arg[1]+' , <b>Group</b> : '+arg[2]+' , <b>Period</b> : '+arg[3]+' on '+arg[4]+' in lieu of <b>'+en1+'</b></td><td class="padding_top" align="center" width="5%"><a onclick="removeSelection('+timeTableId+');" style="cursor:pointer;"><b>X</b></a></td></tr></table>';
          hiddenValue=timeTableId+'~'+employeeId+'~'+employeeId2;
         }
         else{
           //tStr +='<tr class="'+bgclass+'"><td class="padding_top" width="2%" align="left">'+(srNo)+'</td><td class="padding_top" width="15%">'+arg[0]+'</td><td class="padding_top" width="10%">'+arg[1]+'</td><td class="padding_top" width="10%">'+arg[2]+'</td><td class="padding_top" width="10%">'+arg[3]+'</td><td class="padding_top" width="10%">'+arg[4]+'</td><td class="padding_top" width="12%">'+en2+'</td><td class="padding_top" width="12%">'+en1+'</td><td class="padding_top" align="center" width="10%"><a onclick="removeSelection('+timeTableId+');" style="cursor:pointer;"><b>X</b></a></td></tr></table>';
           tStr +='<tr class="'+bgclass+'"><td class="padding_top" width="2%" align="left">'+(srNo)+'</td><td class="padding_top" width="93%"><b>'+en1+'</b> will be taking <b>Class</b> : '+arg[0]+' , <b>Subject</b> : '+arg[1]+' , <b>Group</b> : '+arg[2]+' , <b>Period</b> : '+arg[3]+' on '+arg[4]+' in lieu of <b>'+en2+'</b></td><td class="padding_top" align="center" width="5%"><a onclick="removeSelection('+timeTableId+');" style="cursor:pointer;"><b>X</b></a></td></tr></table>';
           hiddenValue=timeTableId+'~'+employeeId2+'~'+employeeId;
         }
         
         var ele=document.getElementById('timeDIV_'+timeTableId);
         var hEle=document.getElementById('hEle'+timeTableId);
         if(!ele){
             var child=document.createElement('div');
             child.setAttribute('id','timeDIV_'+timeTableId);
             child.setAttribute('name','timeDIV');
             child.innerHTML=tStr;
             
             var hElement=document.createElement('input');
             hElement.setAttribute('id','hEle'+timeTableId);
             hElement.setAttribute('name','hEle[]');
             hElement.setAttribute('type','hidden');
             hElement.setAttribute('value',hiddenValue);
             
             parent.appendChild(child);
             parent.appendChild(hElement);
             clickCnt++;
            
         }
        else{
            ele.innerHTML=tStr;
            hEle.value=hiddenValue; 
        } 
       }
       else{
           var ele=document.getElementById('timeDIV_'+timeTableId);
           var hEle=document.getElementById('hEle'+timeTableId);
           if(ele){
            parent.removeChild(ele);//remove the div
            parent.removeChild(hEle);//remove the hidden element
            clickCnt--;
           }
       }
     document.getElementById('saveTr1').style.display='';//show "Save & Cancel buttons"
     if(clickCnt<=0){
       srNo=0;
       parent.innerHTML='';
       parent.style.display='none';
       document.getElementById('saveTr1').style.display='none'; //hide "Save & Cancel buttons"
     }
    }
    catch(e){
        alert(e);
    }
}

function removeSelection(id){
    var parent=document.getElementById('resultDiv');
    var ele=document.getElementById('timeDIV_'+id);
    var hEle=document.getElementById('hEle'+id);
    if(ele){
     parent.removeChild(ele);//remove the div
     parent.removeChild(hEle);//remove the hidden element
     var chkEl=document.getElementById('emps'+id);
     if(chkEl){
         chkEl.checked=false;
     }
     clickCnt--;
    }
    if(clickCnt<=0){
       srNo=0; 
       parent.innerHTML='';
       parent.style.display='none';
       document.getElementById('saveTr1').style.display='none'; //hide "Save & Cancel buttons"  
    }
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var serverDate="<?php echo date('Y-m-d');?>";
function getTimeTableData() {

   var labelId=document.searchForm.labelId.value;
   var employeeId=document.searchForm.replaceTeacherId.value;
   var en1=document.searchForm.replaceTeacherId.options[document.searchForm.replaceTeacherId.selectedIndex].text;
   var employeeId2=document.searchForm.replacingTeacherId.value;
   var en2=document.searchForm.replacingTeacherId.options[document.searchForm.replacingTeacherId.selectedIndex].text;
   if(labelId==''){
       messageBox("<?php echo SELECT_TIME_TABLE;?>");
       document.searchForm.labelId.focus();
       return false;
   }
   if(employeeId==''){
       messageBox("<?php echo SELECT_TEACHER_TO_SUBSTITUTE;?>");
       document.searchForm.replaceTeacherId.focus();
       return false;
   }
   if(employeeId2==''){
       messageBox("<?php echo SELECT_TEACHER_BY_SUBSTITUTE;?>");
       document.searchForm.replacingTeacherId.focus();
       return false;
   }
   
   if(employeeId==employeeId2 && (employeeId!='' && employeeId2!='')){
             messageBox("<?php echo SAME_EMPLOYEE_RESTRICTION;?>");
             return false;
   }
   
   var fromDate=document.getElementById('fromDate').value;
   var toDate=document.getElementById('toDate').value;
   if(!dateDifference(serverDate,fromDate,"-")){ //if fromDate is lesser than current date
       messageBox("<?php echo TIME_TABLE_FROM_DATE_VALIDATION; ?>");
       document.getElementById('fromDate').focus();
       return false;
   }
   if(!dateDifference(serverDate,toDate,"-")){ //if toDate is lesser than current date 
       messageBox("<?php echo TIME_TABLE_TO_DATE_VALIDATION; ?>");
       document.getElementById('toDate').focus();
       return false;
   }
   if(!dateDifference(fromDate,toDate,"-")){ //if fromDate is greather than to date 
       messageBox("<?php echo TIME_TABLE_DATE_VALIDATION; ?>");
       document.getElementById('toDate').focus();
       return false;
   }
   
   document.getElementById('empDiv1').innerHTML='';
   document.getElementById('empDiv2').innerHTML='';

   //clean up data when "Show List" button is clicked
   document.getElementById('resultDiv').innerHTML='';
   document.getElementById('saveTr1').style.display='none';
   clickCnt=0;
   srNo=0;
  
  var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTimeTableDetailsForTeacher.php';
 
  document.getElementById('empDiv1').innerHTML=''; 
  var tableColumns1 = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('emps','<input type=\"checkbox\" id=\"empList1\" name=\"empList1\" onclick=\"selectEmps(this.checked,1);\">','width="3%" align="center"',false),
                        new Array('className','Class','width="20%" align="left"',false),
                        new Array('subjectCode','Subject','width="14%" align="left"',false),
                        new Array('groupShort','Group','width="12%" align="left"',false),
                        new Array('periodNumber','Period','width="12%" align="left"',false),
                        new Array('daysOfWeek','Day','width="10%" align="left"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,page,'','daysOfWeek','ASC','empDiv1','','',true,'listObj',tableColumns1,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&type=1'+'&fromDate='+fromDate+'&toDate='+toDate);
 sendRequest(url, listObj, '',false);
 
 document.getElementById('empDiv1').innerHTML='<b>Time Table of '+en1+'</b>'+document.getElementById('empDiv1').innerHTML; 
 //document.getElementById('empDiv1').style.backgroundColor='#D5AE80';
 document.getElementById('empDiv1').className='showHideRow';
 
 var tableColumns2 = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        //new Array('emps','<input type=\"checkbox\" id=\"empList1\" name=\"empList1\" onclick=\"selectEmps(this.checked,2);\">','width="3%" align="center"',false),
                        new Array('className','Class','width="20%" align="left"',false),
                        new Array('subjectCode','Subject','width="14%" align="left"',false),
                        new Array('groupShort','Group','width="12%" align="left"',false),
                        new Array('periodNumber','Period','width="12%" align="left"',false),
                        new Array('daysOfWeek','Day','width="10%" align="left"',false)
                       );
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,page,'','daysOfWeek','ASC','empDiv2','','',true,'listObj2',tableColumns2,'','','&employeeId='+employeeId2+'&timeTableLabelId='+labelId+'&type=2'+'&fromDate='+fromDate+'&toDate='+toDate);
 sendRequest(url, listObj2, '',false);
 
 document.getElementById('empDiv2').innerHTML='<b>Time Table of '+en2+'</b>'+document.getElementById('empDiv2').innerHTML;
 //document.getElementById('empDiv2').style.backgroundColor='#B1D0E2';
 document.getElementById('empDiv2').className='reverseshowHideRow';
 
 
 /*code for selecting chkboxes goes here*/
  //selectCheckBoxes();
 /*code for selecting chkboxes goes here*/ 
 
 /*code for deleting duplicate records goes here*/
 //removeDuplicateValues();
 /*code for deleting duplicate records goes here*/
 
 /*
 if(listObj.totalRecords>0){
     document.getElementById('saveTr1').style.display='';
 }
 */
}


function getAdjustedTimeTableData() {
   var labelId=document.searchForm2.labelId2.value;
   var employeeId=document.searchForm2.teacherId.value;
   var en1=document.searchForm2.teacherId.options[document.searchForm2.teacherId.selectedIndex].text;
   if(labelId==''){
       messageBox("<?php echo SELECT_TIME_TABLE;?>");
       document.searchForm2.labelId2.focus();
       return false;
   }
   if(employeeId==''){
       messageBox("Select a teacher");
       document.searchForm2.teacherId.focus();
       return false;
   }
   
   var fromDate=document.getElementById('fromDate2').value;
   var toDate=document.getElementById('toDate2').value;
   
   if(!dateDifference(fromDate,toDate,"-")){ //if fromDate is greather than to date 
       messageBox("<?php echo TIME_TABLE_DATE_VALIDATION; ?>");
       document.getElementById('toDate2').focus();
       return false;
   }
   
   //if fromDate and toDate are both lesser than current date
   if(!dateDifference(serverDate,fromDate,"-") && !dateDifference(serverDate,toDate,"-")){ 
       messageBox("<?php echo TIME_TABLE_CANCEL_DATE_VALIDATION; ?>");
       document.getElementById('toDate2').focus();
       return false;
   }
   
   
   document.getElementById('cancelResultDiv').innerHTML='';
   document.getElementById('cancelOptionTd').style.display='none';
   var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetAdjustedTimeTableDetailsForTeacher.php';
 
   var tableColumns1 = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('emps','<input type=\"checkbox\" id=\"empList3\" name=\"empList3\" onclick=\"selectEmps2(this.checked);\">','width="3%" align="center"',false),
                        new Array('className','Class','width="20%" align="left"',false),
                        new Array('subjectCode','Subject','width="14%" align="left"',false),
                        new Array('groupShort','Group','width="12%" align="left"',false),
                        new Array('periodNumber','Period','width="6%" align="left"',false),
                        new Array('daysOfWeek','Day','width="10%" align="left"',false),
                        new Array('fromDate','From','width="10%" align="center"',false),
                        new Array('toDate','To','width="10%" align="center"',false),
                        new Array('attendanceTaken','Attendance Taken','width="20%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj3 = new initPage(url,recordsPerPage,linksPerPage,page,'','daysOfWeek','ASC','cancelResultDiv','','',true,'listObj3',tableColumns1,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&fromDate='+fromDate+'&toDate='+toDate);
 sendRequest(url, listObj3, '',false);
 if(listObj3.totalRecords>0){
  document.getElementById('cancelResultDiv').innerHTML='<b>Adjusted Time Table of '+en1+'</b>'+document.getElementById('cancelResultDiv').innerHTML; 
  document.getElementById('cancelOptionTd').style.display='';
 }
}


//this function is used to pre-select checkboxes based upon previous selection of users
function selectCheckBoxes(){
    var c1 = document.getElementById('resultDiv').getElementsByTagName('INPUT');
    var c2 = document.getElementById('empDiv1').getElementsByTagName('INPUT');
    var c3 = document.getElementById('empDiv2').getElementsByTagName('INPUT');
    try{
    var cnt1=c1.length;
    var cnt2=c2.length;                                                             
    var cnt3=c3.length;
        for (var i=0; i<cnt1; i++) {
            if (c1[i].type.toUpperCase()=='HIDDEN' && c1[i].name=='hEle[]'){
                var id=c1[i].id.split('hEle')[1];
                for(var j=0;j<cnt2;j++){
                   if (c2[j].type.toUpperCase()=='CHECKBOX' && c2[j].name=='emps1' && c2[j].value==id){
                       c2[j].checked=true;
                   } 
                }
                
                for(var k=0;k<cnt3;k++){
                   if (c3[k].type.toUpperCase()=='CHECKBOX' && c3[k].name=='emps2' && c3[k].value==id){
                       c3[k].checked=true;
                   } 
                }
            }
        }
    }
    catch(e){
        
    }

}

/*THIS FUNCTION IS USED TO DELETE RECORDS FROM THE RESULT DIV IF THIS RECORDS DOES NOT EXISTS IN DATABASE*/
function removeDuplicateValues(){
   var c1 = document.getElementById('resultDiv').getElementsByTagName('INPUT');
   var len1=c1.length;
   var c2 = document.getElementById('empDiv1').getElementsByTagName('INPUT');
   var len2=c2.length;
   var c3 = document.getElementById('empDiv2').getElementsByTagName('INPUT');
   var len1=c3.length;
   var e1=document.getElementById('replaceTeacherId').value;
   var e2=document.getElementById('replacingTeacherId').value;
   
   for(var j=0;j<len1;j++){
     if (c1[j].type.toUpperCase()=='HIDDEN' && c1[j].name=='hEle[]'){
         var replaceTeacher=c1[j].value.split('~')[1];
         var replaceTimeTableId=c1[j].value.split('~')[0];
         if(replaceTeacher==e1){
             var fl=0;
             for(var i=0;i<len2;i++){
              if (c2[i].type.toUpperCase()=='CHECKBOX' && c2[i].name=='emps1'){
                  var val=c2[i].value;
                  if(replaceTimeTableId==val){
                      fl=1;
                      break;
                  }
              }
           }
           if(!fl){
             removeSelection(replaceTimeTableId);//remove the already deleted record
           }
         }
      } 
   }
   
   for(var j=0;j<len1;j++){
     if (c1[j].type.toUpperCase()=='HIDDEN' && c1[j].name=='hEle[]'){
         var replaceTeacher=c1[j].value.split('~')[1];
         var replaceTimeTableId=c1[j].value.split('~')[0];
         if(replaceTeacher==e2){
             var fl=0;
             for(var i=0;i<len3;i++){
              if (c3[i].type.toUpperCase()=='CHECKBOX' && c3[i].name=='emps2'){
                  var val=c3[i].value;
                  if(replaceTimeTableId==val){
                      fl=1;
                      break;
                  }
              }
           }
           if(!fl){
             removeSelection(replaceTimeTableId);//remove the already deleted record
           }
         }
      } 
   }
   
   
} 

function selectEmps(checked,mode){
  var employeeId=document.searchForm.replaceTeacherId.value;
  var employeeId2=document.searchForm.replacingTeacherId.value;
  if(employeeId==employeeId2 && (employeeId!='' && employeeId2!='')){
     messageBox("<?php echo SAME_EMPLOYEE_RESTRICTION;?>");
     return false;
  }
  var inputs = document.getElementsByTagName('input');
  var cnt=inputs.length;
  for(var i=0; i <cnt ; i++) {
     try{   
      if(inputs[i].getAttribute('type') == 'checkbox' && inputs[i].getAttribute('name')=='emps'+mode) {
         inputs[i].checked=checked;
         //generate suggestion div
         generateSuggestionDiv(inputs[i].getAttribute('value'),inputs[i].getAttribute('alt'),checked);
      }
     }
    catch(e){
    } 
  }
}

//this function is used to check/uncheck cancellation checkboxes
function selectEmps2(checked){
  var inputs = document.getElementsByTagName('input');
  var cnt=inputs.length;
  for(var i=0; i <cnt ; i++) {
     try{   
      if(inputs[i].getAttribute('type') == 'checkbox' && inputs[i].getAttribute('name')=='cancelledEmps') {
         inputs[i].checked=checked;
      }
     }
    catch(e){
    } 
  }
}


function getTeachersForThisTimeTable(value) {
         document.searchForm.replaceTeacherId.options.length=1;
         document.searchForm.replaceTeacherId.selectedIndex=0;
         document.searchForm.replacingTeacherId.options.length=1;
         document.searchForm.replacingTeacherId.selectedIndex=0;
         document.getElementById('empDiv1').innerHTML='';
         document.getElementById('empDiv2').innerHTML='';
         //document.getElementById('empDiv1').style.border='';
         //document.getElementById('empDiv2').style.border='';
         document.getElementById('empDiv1').style.backgroundColor='';
         document.getElementById('empDiv2').style.backgroundColor='';
         document.getElementById('resultDiv').innerHTML='';
         document.getElementById('saveTr1').style.display='none';
         clickCnt=0;
         bgclass='';
         srNo=0;
         
         if(value==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getTeachersForTimeTimeLabel.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: (value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('('+transport.responseText+')');
                     var len=j.length;
                     for(var c=0;c<len;c++){
                         var objOption1 = new Option(j[c].employeeName,j[c].employeeId);
                         var objOption2 = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm.replaceTeacherId.options.add(objOption1);
                         document.searchForm.replacingTeacherId.options.add(objOption2);
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function getAdjustedTeachersForThisTimeTable(value) {
         document.searchForm2.teacherId.options.length=1;
         document.getElementById('cancelResultDiv').innerHTML='';
         document.getElementById('cancelOptionTd').style.display='none';
         if(value==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getAdjustedTeachersForTimeTimeLabel.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: (value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('('+transport.responseText+')');
                     var len=j.length;
                     for(var c=0;c<len;c++){
                         var objOption = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm2.teacherId.options.add(objOption);
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}



function doSubstitution(){
   
  var labelId=document.searchForm.labelId.value;
  var employeeId=document.searchForm.replaceTeacherId.value;
  var employeeId2=document.searchForm.replacingTeacherId.value;
   
  if(labelId==''){
       messageBox("<?php echo SELECT_TIME_TABLE; ?>");
       document.searchForm.labelId.focus();
       return false;
  }
  if(employeeId==''){
       messageBox("<?php echo SELECT_TEACHER_TO_SUBSTITUTE; ?>");
       document.searchForm.replaceTeacherId.focus();
       return false;
  }
  if(employeeId2==''){
       messageBox("<?php echo SELECT_TEACHER_BY_SUBSTITUTE; ?>");
       document.searchForm.replacingTeacherId.focus();
       return false;
  }
   
  if(employeeId==employeeId2 && (employeeId!='' && employeeId2!='')){
             messageBox("<?php echo SAME_EMPLOYEE_RESTRICTION;?>");
             return false;
  }

  if(false==confirm("Proceed with time table substitution?")){
      return false;
  }
  
  document.getElementById('conflictMessageDiv').innerHTML='';
  
  var c1=document.getElementById('resultDiv').getElementsByTagName('DIV');
  try{
    var cnt1=c1.length;
    if(cnt1==0){
      messageBox("<?php echo NO_DATA_SUBMIT?>");
      return false;  
    }
    for (var i=0; i<cnt1; i++) {
       if (c1[i].getAttribute('name')=='timeDIV'){
           c1[i].style.border='';
       }
    }
  }
  catch(e){
  }
  
   var pars = generateQueryString('searchForm');
   var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/doTimeTableSubstitution.php';
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
                         messageBox('Time table changed successfully');
                         //clearData();
                         clearDataAfterSave();
                     }
                     else{
                         var ret=trim(transport.responseText).split('~');
                         //messageBox(ret[0]);
                         document.getElementById('conflictMessageDiv').innerHTML=ret[0];
                         displayWindow('conflictsDivId',315,250);
                         if(ret.length>1){
                             var id='timeDIV_'+ret[1];
                             try{
                                var cnt1=c1.length;
                                for (var i=0; i<cnt1; i++) {
                                   if (c1[i].getAttribute('name')=='timeDIV' && c1[i].getAttribute('id')==id){
                                       c1[i].style.border='1px solid red';
                                       break;
                                   }
                                }
                              }
                              catch(e){
                              }
                         }
                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
   }); 
}


function doCancellation(){
   
  var labelId=document.searchForm2.labelId2.value;
  var employeeId=document.searchForm2.teacherId.value;
  var en1=document.searchForm2.teacherId.options[document.searchForm2.teacherId.selectedIndex].text;
  if(labelId==''){
       messageBox("<?php echo SELECT_TIME_TABLE;?>");
       document.searchForm.labelId.focus();
       return false;
  }
  if(employeeId==''){
       messageBox("Select a teacher");
       document.searchForm2.teacherId.focus();
       return false;
  }
   
  var fromDate=document.getElementById('fromDate2').value;
  var toDate=document.getElementById('toDate2').value;
   
  if(!dateDifference(fromDate,toDate,"-")){ //if fromDate is greather than to date 
       messageBox("<?php echo TIME_TABLE_DATE_VALIDATION; ?>");
       document.getElementById('toDate2').focus();
       return false;
  }
   
   //if fromDate and toDate are both lesser than current date
  if(!dateDifference(serverDate,fromDate,"-") && !dateDifference(serverDate,toDate,"-")){ 
       messageBox("<?php echo TIME_TABLE_CANCEL_DATE_VALIDATION; ?>");
       document.getElementById('toDate2').focus();
       return false;
  }
  
  var c1=document.getElementById('cancelResultDiv').getElementsByTagName('INPUT');
  var checkedStr='';
  var altStr='';
  try{
    var cnt1=c1.length;
    if(cnt1<2){
      messageBox("<?php echo NO_DATA_SUBMIT?>");
      return false;  
    }
    for (var i=0; i<cnt1; i++) {
       if (c1[i].getAttribute('name')=='cancelledEmps' && c1[i].checked==true){
           if(checkedStr!=''){
               checkedStr +=',';
           }
           checkedStr +=c1[i].getAttribute('value');
           if(c1[i].getAttribute('alt')==1){
               if(altStr!=''){
                   altStr +=',';
               }
               altStr +=c1[i].getAttribute('value');
           }
       }
    }
  }
  catch(e){
  }
  
  if(checkedStr==''){
      messageBox("Please select one checkbox");
      document.getElementById('empList3').focus();
      return false;
  }
  var override=0;
  if(altStr!=''){
      override=Number(confirm("Some of the selected values has corresponding attendance records.\nThese attendance records will also be deleted.\nAre you sure?"));
      if(!override){
          return false;
      }
  }
if(altStr==''){
  if(false==confirm("Proceed with time table adjustment deletion?")){
      return false;
  }
}
  
   var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/doTimeTableAdjustmentCancellation.php';
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                    checkedStr : (checkedStr),
                    altStr     : (altStr),
                    override   :  override
             
             },
             onCreate: function() {
                 showWaitDialog(true);                                           
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         messageBox('Time table adjustments deleted successfully');
                         cleanUpData(2);
                     }
                     else{
                         messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
   }); 
}

function clearText(mode){
  document.getElementById('empDiv'+mode).innerHTML='';
  document.getElementById('empDiv'+mode).style.border='';
}

function clearData(){
     document.searchForm.labelId.selectedIndex=0;
     document.searchForm.replaceTeacherId.options.length=1;
     document.searchForm.replaceTeacherId.selectedIndex=0;
     document.searchForm.replacingTeacherId.options.length=1;
     document.searchForm.replacingTeacherId.selectedIndex=0;
     document.getElementById('empDiv1').innerHTML='';
     document.getElementById('empDiv2').innerHTML='';
     document.getElementById('empDiv1').style.backgroundColor='';
     document.getElementById('empDiv2').style.backgroundColor='';
     document.getElementById('resultDiv').innerHTML='';
     document.getElementById('saveTr1').style.display='none';
     clickCnt=0;
     bgclass='';
     srNo=0;
}

function clearDataAfterSave(){
     document.searchForm.replaceTeacherId.selectedIndex=0;
     document.searchForm.replacingTeacherId.selectedIndex=0;
     document.getElementById('empDiv1').innerHTML='';
     document.getElementById('empDiv2').innerHTML='';
     document.getElementById('empDiv1').style.backgroundColor='';
     document.getElementById('empDiv2').style.backgroundColor='';
     document.getElementById('resultDiv').innerHTML='';
     document.getElementById('saveTr1').style.display='none';
     clickCnt=0;
     bgclass='';
     srNo=0; 
}

function cleanUpData(mode){
    if(mode==1){
       document.getElementById('cancelResultDiv').innerHTML='';
       document.getElementById('cancelOptionTd').style.display='none';
       return false;
    }
    
    document.searchForm2.labelId2.selectedIndex=0;
    document.searchForm2.teacherId.options.length=1;
    document.getElementById('cancelResultDiv').innerHTML='';
    document.getElementById('cancelOptionTd').style.display='none'; 
    return false;
}


function validateData(){
  doSubstitution();
}

function validateData2(){
  doCancellation();
}

window.onload=function(){
    if(document.searchForm.labelId.options.length>1){
        getTeachersForThisTimeTable(document.searchForm.labelId.value);//populate teachers for this time table
    }
   if(document.searchForm2.labelId2.options.length>1){
       getAdjustedTeachersForThisTimeTable(document.searchForm2.labelId2.value); //populate adjusted teachers for this time table
   } 
}


</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/TimeTable/swapTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: swapTimeTable.php $ 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/11/09   Time: 10:16
//Updated in $/LeapCC/Interface
//Corrected logic of deleting adjustment time table entries 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 3/11/09    Time: 11:20
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001931,00001930
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 2/11/09    Time: 18:10
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001919,00001920,00001921,00001923,00001924
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 22/10/09   Time: 17:57
//Updated in $/LeapCC/Interface
//Modified javascript and tab names
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/10/09   Time: 13:19
//Updated in $/LeapCC/Interface
//Added code "time table adjustment cancellation"
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 21/10/09   Time: 13:10
//Updated in $/LeapCC/Interface
//Checked in for time being
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 13/10/09   Time: 9:37
//Created in $/LeapCC/Interface
//Created "Swap time table" module
?>