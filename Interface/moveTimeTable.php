<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF MOVE/COPY TIME TABLE 
//
// Author : Jaineesh
// Created on : (27.10.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MoveTeacherTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Move/Copy Time Table </title>
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
tableStr +='<tr class="rowheading"><td class="searchhead_text" width="2%" align="left">#</td><td class="searchhead_text" width="93%" align="left">Move/Copy Details</td><td class="searchhead_text" width="5%" align="center">Delete</td></tr>';
function generateSuggestionDiv(timeTableId,str,checked){
	//alert(timeTableId);
    //create and remove divs dynamically
	
	var parent=document.getElementById('resultDiv');
    var tStr=globalTB;
    if(trim(parent.innerHTML)==''){
        parent.innerHTML='<hr/><b>Your Selection</b><br/>'+tableStr;
        parent.style.display='';
        //parent.innerHTML=tableStr;
    }

    try{
       
       if(checked){ //if checked is true

        // var employeeId=document.searchForm.employeeId.value;
		 //alert(employeeId);
         //var en1=document.searchForm.employeeId.options[document.searchForm.employeeId.selectedIndex].text;
		
         var arg=str.split(',');
         
         bgclass=(bgclass=='row0'? 'row1' : 'row0');
         var hiddenValue='';
         srNo++; 
           //tStr +='<tr class="'+bgclass+'"><td class="padding_top" width="2%" align="left">'+(srNo)+'</td><td class="padding_top" width="15%">'+arg[0]+'</td><td class="padding_top" width="10%">'+arg[1]+'</td><td class="padding_top" width="10%">'+arg[2]+'</td><td class="padding_top" width="10%">'+arg[3]+'</td><td class="padding_top" width="10%">'+arg[4]+'</td><td class="padding_top" width="12%">'+en2+'</td><td class="padding_top" width="12%">'+en1+'</td><td class="padding_top" align="center" width="10%"><a onclick="removeSelection('+timeTableId+');" style="cursor:pointer;"><b>X</b></a></td></tr></table>';
           tStr +='<tr class="'+bgclass+'"><td class="padding_top" width="2%" align="left">'+(srNo)+'</td><td class="padding_top" width="93%"><b>'+arg[5]+'</b> <b>Class</b> : '+arg[0]+' , <b>Subject</b> : '+arg[1]+' , <b>Group</b> : '+arg[2]+' , <b>Period</b> : '+arg[3]+ ' is '+arg[7]+ ' from ' +arg[8]+ ' (' +arg[10]+ ') to ' +arg[9]+ ' ('+arg[6]+')</td><td class="padding_top" align="center" width="5%"><a onclick="removeSelection('+timeTableId+');" style="cursor:pointer;"><b>X</b></a></td></tr></table>';
           //hiddenValue=timeTableId+'~'+employeeId;
         
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
       // alert(e);
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
   var classId = document.searchForm.classId.value;
   var subjectId = document.searchForm.subjectId.value;
   var groupId = document.searchForm.groupId.value;
   var employeeId=document.searchForm.employeeId.value;
   var adjustmentTypeId = document.searchForm.typeId.value;
   var fromDate = document.searchForm.fromDate.value;
   var toDate = document.searchForm.toDate.value;
	
   
   var en1=document.searchForm.employeeId.options[document.searchForm.employeeId.selectedIndex].text;
   
   if(labelId==''){
       messageBox("<?php echo SELECT_TIME_TABLE;?>");
       document.searchForm.labelId.focus();
       return false;
   }

   if(adjustmentTypeId==''){
       messageBox("<?php echo SELECT_ADJUSTMENT_TYPE;?>");
       document.searchForm.typeId.focus();
       return false;
   }

   if (fromDate == "") {
	messageBox("<?php echo DATE_FROM_NOT_EMPTY; ?>");
       document.getElementById('fromDate').focus();
       return false;
   }

   if (toDate == "") {
	messageBox("<?php echo DATE_TO_NOT_EMPTY; ?>");
       document.getElementById('toDate').focus();
       return false;
   }
 /*  if(!dateDifference(serverDate,fromDate,"-")){ //if fromDate is lesser than current date
       messageBox("<?php echo TIME_TABLE_FROM_DATE_VALIDATION; ?>");
       document.getElementById('fromDate').focus();
       return false;
   }
   if(!dateDifference(serverDate,toDate,"-")){ //if toDate is lesser than current date 
       messageBox("<?php echo TIME_TABLE_TO_DATE_VALIDATION; ?>");
       document.getElementById('toDate').focus();
       return false;
   }*/
   if(!dateDifference(fromDate,toDate,"-")){ //if fromDate is greather than to date 
       messageBox("<?php echo TIME_TABLE_DATE_VALIDATION; ?>");
       document.getElementById('toDate').focus();
       return false;
   }

   if(fromDate == toDate) { //if fromDate is greather than to date 
       messageBox("<?php echo TIME_TABLE_DATE_EQUAL; ?>");
       document.getElementById('fromDate').focus();
       return false;
   }
   document.getElementById('resultDiv').innerHTML = '';
   if (document.getElementById('resultDiv').innerHTML == '') {
	   document.getElementById('saveTr1').style.display='none';
   }
   //document.getElementById('saveTr1').innerHTML = '';
   document.getElementById('empDiv1').innerHTML = '';
   clickCnt=0;
        
  var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTeacherTimeTable.php';
 
  document.getElementById('empDiv1').innerHTML=''; 
  var tableColumns1 = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('emps','<input type=\"checkbox\" id=\"empList1\" name=\"empList1\" onclick=\"selectEmps(this.checked);\">','width="3%" align="center"',false),
						new Array('employeeName','Employee Name','width="20%" align="left"',false),
                        new Array('className','Class','width="20%" align="left"',false),
                        new Array('subjectCode','Subject','width="14%" align="left"',false),
                        new Array('groupShort','Group','width="12%" align="left"',false),
                        new Array('periodNumber','Period','width="12%" align="left"',false),
                        new Array('daysOfWeek','Day','width="10%" align="left"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,page,'','daysOfWeek','ASC','empDiv1','','',true,'listObj',tableColumns1,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&classId='+classId+'&subjectId='+subjectId+'&groupId='+groupId+'&fromDate='+fromDate+'&toDate='+toDate+'&adjustmentTypeId='+adjustmentTypeId);
 sendRequest(url, listObj, '',false);
 
 document.getElementById('empDiv1').innerHTML='<b>Time Table of '+en1+'</b>'+document.getElementById('empDiv1').innerHTML; 
 //document.getElementById('empDiv1').style.backgroundColor='#D5AE80';
 document.getElementById('empDiv1').className='showHideRow';
 
 /*code for selecting chkboxes goes here*/
  //selectCheckBoxes();
 /*code for selecting chkboxes goes here*/ 

}


//this function is used to pre-select checkboxes based upon previous selection of users

function selectCheckBoxes(){
	
    var c1 = document.getElementById('resultDiv').getElementsByTagName('INPUT');
    var c2 = document.getElementById('empDiv1').getElementsByTagName('INPUT');
   // var c3 = document.getElementById('empDiv2').getElementsByTagName('INPUT');
    try{
    var cnt1=c1.length;
    var cnt2=c2.length;                                                             
        for (var i=0; i<cnt1; i++) {
            if (c1[i].type.toUpperCase()=='HIDDEN' && c1[i].name=='hEle[]'){
                var id=c1[i].id.split('hEle')[1];
                for(var j=0;j<cnt2;j++){
                   if (c2[j].type.toUpperCase()=='CHECKBOX' && c2[j].name=='chb[]' && c2[j].value==id){
                       c2[j].checked=true;
                   } 
                }
            }
        }
    }
    catch(e){
        
    }
} 

function selectEmps(checked){
	
  //var employeeId=document.searchForm.employeeId.value;
//alert(checked)
  var inputs = document.getElementsByTagName('input');
  var cnt=inputs.length;

 // alert(cnt);

  for(var i=0; i <cnt ; i++) {
     try{
      if(inputs[i].getAttribute('type') == 'checkbox' && inputs[i].getAttribute('name') == 'chb[]') {
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

function doMoveCopy(){
   
  var labelId=document.searchForm.labelId.value;
  var adjustmentTypeId = document.searchForm.typeId.value;
   
  if(labelId==''){
       messageBox("<?php echo SELECT_TIME_TABLE; ?>");
       document.searchForm.labelId.focus();
       return false;
  }

  if(adjustmentTypeId==''){
       messageBox("<?php echo SELECT_ADJUSTMENT_TYPE;?>");
       document.searchForm.typeId.focus();
       return false;
   }

   var fromDate=document.getElementById('fromDate').value;
   var toDate=document.getElementById('toDate').value;

   if (fromDate == "") {
	messageBox("<?php echo DATE_NOT_EMPTY; ?>");
       document.getElementById('fromDate').focus();
       return false;
   }

   if (toDate == "") {
	messageBox("<?php echo DATE_NOT_EMPTY; ?>");
       document.getElementById('toDate').focus();
       return false;
   }
  
   var pars = generateQueryString('searchForm');
   var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/doTimeTableCopyMove.php';
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
                         cleanData();
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


function cleanData(){
	document.searchForm.labelId.value='';
	document.searchForm.typeId.value='';
	document.searchForm.classId.options.length=1;
	document.searchForm.subjectId.options.length=1;
	document.searchForm.groupId.options.length=1;
	document.searchForm.employeeId.options.length=1;
	document.getElementById('fromDate').value = '';
	document.getElementById('toDate').value = '';
	document.getElementById('empDiv1').innerHTML = '';
	document.getElementById('resultDiv').innerHTML = '';
	document.getElementById('saveTr1').style.display='none';
	var srNo = 0;
}

function clearData(mode){
     
    if(mode==1){
		document.searchForm.subjectId.value="";
		document.searchForm.subjectId.options.length=1;
		document.searchForm.groupId.options.length=1;
		document.searchForm.employeeId.options.length=1;
		document.getElementById('empDiv1').innerHTML='';
		document.getElementById('resultDiv').innerHTML='';
    }
    else if(mode==2){
		document.searchForm.groupId.value="";
		document.searchForm.groupId.options.length=1;
		document.searchForm.employeeId.options.length=1;
		document.getElementById('empDiv1').innerHTML='';
		document.getElementById('resultDiv').innerHTML='';
    }
    else if(mode==3){
		document.searchForm.employeeId.value="";
		document.searchForm.employeeId.options.length=1;
		document.getElementById('empDiv1').innerHTML='';
		document.getElementById('resultDiv').innerHTML='';
    }
 //  blankValues(1); 
}

function validateData(){
  doMoveCopy();
}

function validateData2(){
  doCancellation();
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


window.onload=function(){
		//document.searchForm2.labelId2.focus();
		
		timeTableMoveCopy();
		document.getElementById('calImg1').onblur=timeTableMoveCopy;
		getAdjustedTeachersForThisTimeTable(document.searchForm2.labelId2.value);

}
var dt1='';

function timeTableMoveCopy(){
document.searchForm.classId.options.length=1;

/*if(dt1==document.getElementById('fromDate').value){
	return false;
}
else{*/
	//dt1=document.getElementById('fromDate').value;
	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getClassTimeTable.php';
	new Ajax.Request(url,
           {
             method:'post',
             parameters: {
					timeTableLabelId: (document.getElementById('labelId').value),
					fromDate : (document.getElementById('fromDate').value)
					//timeTableLabelId: timeTableLabelId,
					//fromDate : fromDate
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('('+transport.responseText+')');
                     var len=j.length;
                     for(var c=0;c<len;c++){
                         var objOption1 = new Option(j[c].className,j[c].classId);
                         //var objOption2 = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm.classId.options.add(objOption1);
                         //document.searchForm.replacingTeacherId.options.add(objOption2);
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
  //}
//	alert(document.getElementById('fromDate').value);
}

function getSubject(classId,fromDate){
	clearData(1);
	if (classId != "") {
	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getSubjectClass.php';
	if(classId == ""){
       return false;
   }
	new Ajax.Request(url,
           {
             method:'post',
             parameters: {
					classId: classId,
					fromDate: fromDate
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('('+transport.responseText+')');
                     var len=j.length;
                     for(var c=0;c<len;c++){
                         var objOption1 = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subjectId.options.add(objOption1);
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
	}
}

function getGroups(classId,subjectId,fromDate){
	clearData(2);
	if (subjectId != "") {
	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getGroupsClass.php';
	new Ajax.Request(url,
           {
             method:'post',
             parameters: {
					classId: classId,
					subjectId: subjectId,
					fromDate: fromDate
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('('+transport.responseText+')');
                     var len=j.length;
                     for(var c=0;c<len;c++){
                         var objOption1 = new Option(j[c].groupShort,j[c].groupId);
                         document.searchForm.groupId.options.add(objOption1);
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
	}
}


function getemployee(classId,groupId,fromDate){
	clearData(3);
	if (groupId != "") {
	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getEmployeeClass.php';
	new Ajax.Request(url,
           {
             method:'post',
             parameters: {
					classId: classId,
					subjectId: document.getElementById('subjectId').value,
					groupId: groupId,
					fromDate: fromDate
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
                         document.searchForm.employeeId.options.add(objOption1);
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
	}
}

function getAdjustedTeachersForThisTimeTable(value) {
		document.searchForm2.teacherId.options.length=1;
        document.getElementById('cancelResultDiv').innerHTML='';
        document.getElementById('cancelOptionTd').style.display='none';
         if(value==''){
             return false;
         }
		 //alert (url);
         var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getAdjustedTeacherTimeTimeLabel.php';
		 
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
   var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetMoveCopyTimeTableDetails.php';
 
   var tableColumns1 = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('emps','<input type=\"checkbox\" id=\"empList3\" name=\"empList3\" onclick=\"selectEmps2(this.checked);\">','width="3%" align="center"',false),
                        new Array('className','Class','width="20%" align="left"',false),
                        new Array('subjectCode','Subject','width="14%" align="left"',false),
                        new Array('groupShort','Group','width="12%" align="left"',false),
                        new Array('periodNumber','Period','width="12%" align="left"',false),
                        new Array('daysOfWeek','Day','width="10%" align="left"',false),
                        new Array('fromDate','From','width="10%" align="center"',false),
                        new Array('toDate','To','width="10%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj3 = new initPage(url,recordsPerPage,linksPerPage,page,'','daysOfWeek','ASC','cancelResultDiv','','',true,'listObj3',tableColumns1,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&fromDate='+fromDate+'&toDate='+toDate);
 sendRequest(url, listObj3, '',false);
 if(listObj3.totalRecords>0){
  document.getElementById('cancelResultDiv').innerHTML='<b>Adjusted Time Table of '+en1+'</b>'+document.getElementById('cancelResultDiv').innerHTML; 
  document.getElementById('cancelOptionTd').style.display='';
 }
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
  
   var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/doTimeTableMoveCopyCancellation.php';
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


</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/TimeTable/moveTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: moveTimeTable.php $ 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/13/09   Time: 6:25p
//Updated in $/LeapCC/Interface
//Modification in code for move/copy timetable
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/04/09   Time: 4:28p
//Updated in $/LeapCC/Interface
//give link move/copy teacher time table and add new field adjustment
//type in time_table_adjustment table
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:30a
//Created in $/LeapCC/Interface
//new file for move/copy time table
//
//
?>