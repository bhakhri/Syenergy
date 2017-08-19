<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaves');
define('ACCESS','view');
/*As we have made new interface for duty leaves*/
redirectBrowser(UI_HTTP_PATH . "/indexHome.php?z=1");
/*As we have made new interface for duty leaves*/

UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Duty Leaves Entry </title>
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
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

// ajax search results ---end ///

function editWindow(studentName,studentId,classId,groupId,subjectId){
    document.getElementById('studentNameDiv').innerHTML='<b>'+studentName+'</b>';
    displayWindow('dutyLeaveDiv',550,200);
    cleanUpTable();
    populateValues(studentId,classId,groupId,subjectId);
}


var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    if(document.getElementById('employeeId').value==''){
        messageBox("<?php echo SELECT_TEACHER; ?>");
        document.getElementById('employeeId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('classId').focus();
        return false;
    }
   if(document.getElementById('subject').value==''){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    } 
   if(document.getElementById('group').value==''){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
    } 
   
 getStudentList();
 return false;  
   
}


function autoPopulateEmployee(timeTableLabelId){
    clearData(1);
    
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTeachers.php';
    
    if(timeTableLabelId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: timeTableLabelId
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm.employeeId.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function autoPopulateClass(employeeId){
    clearData(2);
    
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetClass.php';
    
    if(employeeId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId: employeeId
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].className,j[c].classId);
                         document.searchForm.classId.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


function populateSubjects(classId){
    clearData(3);
    
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetSubjects.php';
    
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 classId: classId,
                 employeeId :document.getElementById('employeeId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                       if(j[c].hasAttendance==1) {
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                       }
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateGroups(classId,subjectId) {
   clearData(4); 
   
   var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGroupPopulate.php';
   
   if(classId=="" || subjectId=="" || document.getElementById('employeeId').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: subjectId,
                 classId  : classId,
                 employeeId :document.getElementById('employeeId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')'); 

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


function getStudentList(){
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxStudentDutyLeaveList.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false),
                        new Array('rollNo','Roll No.','width="20%" align="left"',true),
                        new Array('regNo','Reg. No.','width="20%" align="center"',true), 
                        new Array('studentName','Name','width="50%" align="left"',true),
                        new Array('action1','Action','width="1%" align="right"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','rollNo','ASC','results','','',true,'listObj1',tableColumns,'','','&classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value));
 sendRequest(url, listObj1, ' ',true);
 

}


function clearData(mode){
    document.getElementById('results').innerHTML='';
    if(mode==1){
        document.getElementById('employeeId').options.length=1;
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==2){
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==3){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
   else if(mode==4){
       document.getElementById('group').options.length=1;
   } 
}

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************
var resourceAddCnt=0;


//for deleting a row from the table 
function deleteRow(value){
  var rval=value.split('~');
  var tbody1 = document.getElementById('resourceDetailTableBody');
  
  var tr=document.getElementById('row'+rval[0]);
  tbody1.removeChild(tr);
  
  //reCalculate();
  
  if(isMozilla){
      if((tbody1.childNodes.length-3)==0){
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
function addOneRow(){
 resourceAddCnt++; 
 createRows(resourceAddCnt,1,0);
}


//to clean up table rows
function cleanUpTable(){
   var tbody = document.getElementById('resourceDetailTableBody');
   for(var k=0;k<=resourceAddCnt;k++){
         try{
          tbody.removeChild(document.getElementById('row'+k));
         }
         catch(e){
             //alert(k);  // to take care of deletion problem
         }
      }
  resourceAddCnt=0;    
}


var bgclass='';

var serverDate="<?php echo date('Y-m-d');?>";

//create dynamic rows 
function createRows(start,rowCnt,rowData){

    
 var tbl=document.getElementById('resourceDetailTable');
 var tbody = document.getElementById('resourceDetailTableBody');
 
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
  
  cell2.setAttribute('align','center'); 
  cell3.setAttribute('align','left'); 
  cell4.setAttribute('align','center'); 
  cell5.setAttribute('align','left'); 
  
  
  if(start==0){
  var txt0=document.createTextNode(start+i+1);
  }
  else{
      var txt0=document.createTextNode(start+i);
  }
  
  var txt2=document.createElement('select');
  txt2.className="selectfield";
  //txt2.setAttribute('style','width:120px');
  txt2.style.width="120px";
  txt2.setAttribute('id','leaveTypeId'+parseInt(start+i,10));
  txt2.setAttribute('name','leaveType');
  
  
  
  var txt3=document.createElement('a');
  txt3.setAttribute('id','rd');
  txt3.setAttribute('title','Delete');       
  txt3.innerHTML='X';
  txt3.style.cursor='pointer';
  
  if(rowData !='0'){
   txt1.setAttribute('value',rowData[i]['item']);
   txt2.setAttribute('value',rowData[i]['price'])
  }
  txt3.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
  
  cell1.appendChild(txt0);
  cell2.innerHTML='<input type="text" id="leaveDate'+parseInt(start+i,10)+'" name="leaveDate'+parseInt(start+i,10)+'" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
  cell2.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('leaveDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
  cell3.appendChild(txt2);
  cell4.appendChild(txt3);
  cell5.innerHTML='<input type="text" id="comments'+parseInt(start+i,10)+'" name="comments'+parseInt(start+i,10)+'" class="inputbox" style="width:200px;" value="" maxlength="25" />';
         
  tr.appendChild(cell1);
  tr.appendChild(cell2);
  tr.appendChild(cell3);
  tr.appendChild(cell5);
  tr.appendChild(cell4);
  
  bgclass=(bgclass=='row0'? 'row1' : 'row0');
  tr.className=bgclass;
  
  tbody.appendChild(tr); 
  
  var len=document.getElementById('inLeaveType').options.length;
  var parentEle=document.getElementById('inLeaveType');
  var leaveId='leaveTypeId'+parseInt(start+i,10);
  for(var kk=0;kk<len;kk++){
      addOption(document.getElementById(leaveId), parentEle.options[kk].value,  parentEle.options[kk].text);
  }
 } 
 
 tbl.appendChild(tbody);
 
}

function reCalculate(){
  var a=document.getElementsByTagName('td');
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo'){
    bgclass=(bgclass=='row0'? 'row1' : 'row0');
    a[i].parentNode.className=bgclass;
      //a[i].innerHTML=j;
      j++;
    }
  }
  resourceAddCnt=j-1;
}

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************

var dtArray=new Array();

function checkDuplicateDate(value){
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

function validateForm(){
    var dtStr='';
    var ldStr='';
    var comStr='';
    
    dtArray.splice(0,dtArray.length); //empty the array
    
    for(var i=0;i<resourceAddCnt;i++){
      try{  
            if(document.getElementById('leaveTypeId'+(i+1)).value==''){
                messageBox("<?php echo SELECT_ATTENDANCE_CODE; ?>");
                document.getElementById('leaveTypeId'+(i+1)).focus();
                return false;
            }
            
            if(document.getElementById('comments'+(i+1)).value==''){
                messageBox("<?php echo ENTER_YOUR_COMMENTS; ?>");
                document.getElementById('comments'+(i+1)).focus();
                return false;
            }
            
            if(dtStr!=''){
                dtStr +=',';
            }
            if(ldStr!=''){
                ldStr +=',';
            }
            if(comStr!=''){
                comStr +='~!~@@~!~';
            }
            
            dtStr  +=document.getElementById('leaveDate'+(i+1)).value;
            ldStr  +=document.getElementById('leaveTypeId'+(i+1)).value;
            comStr +=document.getElementById('comments'+(i+1)).value;
            
            if(!checkDuplicateDate(document.getElementById('leaveDate'+(i+1)).value)){
                 messageBox("<?php echo DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION; ?>");
                 document.getElementById('leaveDate'+(i+1)).focus();
                 return false;
            }
            
            if(!dateDifference(document.getElementById('leaveDate'+(i+1)).value,serverDate,'-')){
               messageBox("<?php echo DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION2; ?>");
               document.getElementById('leaveDate'+(i+1)).focus();
               return false; 
            }
      }
      catch(e){}
        
    }

   giveDutyLeaves(dtStr,ldStr,comStr);
   return false;
}

function giveDutyLeaves(dates,leaves,comments){
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/doStudentDutyLeave.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 studentId  : document.getElementById('studentId').value,
                 classId    : document.getElementById('classId').value,
                 groupId    : document.getElementById('group').value,
                 subjectId  : document.getElementById('subject').value,
                 dates      : dates,
                 leaves     : leaves,
                 comments  : comments,
                 employeeId : document.getElementById('employeeId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)=="<?php echo SUCCESS; ?>") {
                        messageBox("<?php echo DUTY_LEAVES_GIVEN; ?>");
                    }
                    else{
                        messageBox(trim(transport.responseText));
                    }
                    hiddenFloatingDiv('dutyLeaveDiv');

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });  
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "dutyLeaveDiv" DIV
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(studentId,classId,groupId,subjectId) {
         url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/getStudentDutyLeaveDetails.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 studentId : studentId,
                 classId   : classId,
                 groupId   : groupId,
                 subjectId : subjectId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==-1) {
                        hiddenFloatingDiv('dutyLeaveDiv');
                        messageBox("<?php echo DUTY_LEAVE_NOT_EXIST; ?>");
                        getStudentList();
                    }
                    
                    document.getElementById('studentId').value=studentId;
                    
                    j = eval('('+transport.responseText+')');
                    if(j.length>0){
                        resourceAddCnt=j.length; 
                        createRows(1,resourceAddCnt,0);
                        
                        for(var i=0;i<resourceAddCnt;i++){
                           document.getElementById('leaveDate'+(i+1)).value=j[i].dated; 
                           document.getElementById('leaveTypeId'+(i+1)).value=j[i].attendanceCodeId;
                           document.getElementById('comments'+(i+1)).value=j[i].comments;
                        }
                    }
                   
                   

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}  
  
window.onload=function(){
    document.getElementById('timeTableLabelId').focus();
    document.searchForm.reset();
    var roll = document.getElementById("rollNo");
    autoSuggest(roll);
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminTasks/dutyLeaveEntryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>