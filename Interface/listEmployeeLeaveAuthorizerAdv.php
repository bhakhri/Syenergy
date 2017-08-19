<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeaveAuthorizerAdv');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Leave Authorizer(Advanced) </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

//listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitListAdv.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddEmployeeLeaveAuthorizer';   
editFormName   = 'EditEmployeeLeaveAuthorizer';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteEmployeeLeaveAuthorizer';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';


var elArray=new Array();
function checkDuplicateValue(value){
    var i= elArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(elArray[k]==value){
          fl=0;
          break;
      }  
    }
   if(fl==1){
       elArray.push(value);
   } 
   return fl;
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function employeeLeaveAuthorizer() {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitAddAdv.php';
         
         elArray.splice(0,elArray.length); //empty the array
         
         //check for no data
         var tbody1 = document.getElementById('resourceDetailTableBody');
         if(isMozilla){
              if((tbody1.childNodes.length-3)==0){
                  messageBox("<?php echo NO_DATA_SUBMIT;?>");
                  return false;
              }
          }
         else{
              if((tbody1.childNodes.length-1)==0){
                  messageBox("<?php echo NO_DATA_SUBMIT;?>");
                  return false;
              }
         }
         
         var inputString='';
         for(var i=0;i<resourceAddCnt;i++){
           try{  
            if(document.getElementById('employeeId'+(i+1)).value==''){
                messageBox("Select employee");
                document.getElementById('employeeId'+(i+1)).focus();
                return false;
            }
            
            if(document.getElementById('firstEmployeeId'+(i+1)).value==''){
                messageBox("<?php echo SELECT_FIRST_AUTHORIZER; ?>");
                document.getElementById('firstEmployeeId'+(i+1)).focus();
                return false;
            }
            
            if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                if(document.getElementById('secondEmployeeId'+(i+1)).value==''){
                    messageBox("<?php echo SELECT_SECOND_AUTHORIZER; ?>");
                    document.getElementById('secondEmployeeId'+(i+1)).focus();
                    return false;
                }
              
              /*if(document.getElementById('firstEmployeeId'+(i+1)).value==document.getElementById('secondEmployeeId'+(i+1)).value){
                    messageBox("<?php echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION; ?>");
                    document.getElementById('secondEmployeeId'+(i+1)).focus();
                    return false;
                }
              */  
            }
            
            if(document.getElementById('leaveTypeId'+(i+1)).value==''){
                messageBox("<?php echo SELECT_LEAVE_TYPE2; ?>");
                document.getElementById('leaveTypeId'+(i+1)).focus();
                return false;
            }
            
            if(!checkDuplicateValue(document.getElementById('employeeId'+(i+1)).value+'~'+document.getElementById('leaveTypeId'+(i+1)).value)){
                 messageBox("<?php echo DUPLICATE_AUTHORIZATION_RESTRICTION; ?>");
                 document.getElementById('leaveTypeId'+(i+1)).focus();
                 return false;
            }
            
            if(inputString!=''){
                inputString +=',';
            }
            
            if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
              inputString +=document.getElementById('employeeId'+(i+1)).value+'~'+document.getElementById('firstEmployeeId'+(i+1)).value+'~'+document.getElementById('secondEmployeeId'+(i+1)).value+'~'+document.getElementById('leaveTypeId'+(i+1)).value+'~'+document.getElementById('apprId'+(i+1)).value;
            }
            else {
              inputString +=document.getElementById('employeeId'+(i+1)).value+'~'+document.getElementById('firstEmployeeId'+(i+1)).value+'~0~'+document.getElementById('leaveTypeId'+(i+1)).value+'~'+document.getElementById('apprId'+(i+1)).value;   
            }
            
      }
      catch(e){}
        
    }
         
       new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 inputValue   : inputString
                 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         messageBox(trim(transport.responseText));
                         prePopulateValue(); //refresh list
                     }
                     else if("<?php echo ENTER_VALID_EMPLOYEE_INFO;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo ENTER_VALID_EMPLOYEE_INFO?>");
                     }
                     else if("<?php echo SELECT_FIRST_AUTHORIZER;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_FIRST_AUTHORIZER?>");
                     }
                     else if("<?php echo SELECT_SECOND_AUTHORIZER;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_SECOND_AUTHORIZER?>");
                     }
                     else if("<?php echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION?>");
                     }
                     else if("<?php echo SELECT_LEAVE_TYPE;?>" == trim(transport.responseText)){
                         messageBox("<?php  echo SELECT_LEAVE_TYPE?>");
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//function used for deleting data
function deleteEmployeeLeaveAuthorizer(employeeId,leaveTypeId) {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitDeleteAdv.php';

         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {
                 employeeId   : employeeId,
                 leaveTypeId  : leaveTypeId
                 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo DELETE;?>" == trim(transport.responseText)) {
                   delFlag=1;
                   return;
                 }
                 else {
                   messageBox(trim(transport.responseText));    
                 }
                 delFlag=0;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************
var resourceAddCnt=0;
//for deleting a row from the table 
var delFlag=0;
function deleteRow(value,empId,firstAuth,secondAuth,leaveType){
      delFlag=0;  
      var rval=value.split('~');
      if(empId!=-1 && firstAuth!=-1 && secondAuth!=-1 && leaveType!=-1){
         if(!confirm("<?php echo DELETE_CONFIRM?>")){
             return ;
         }
         //delete record
         deleteEmployeeLeaveAuthorizer(empId,leaveType);
         if(delFlag==0){
             return;
         }
         
      }
      var tbody1 = document.getElementById('resourceDetailTableBody');
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
      reCalculate();
      if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceAddCnt=0;
              document.getElementById('saveTrId').style.display='none';
          }
      }
      else{
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
              document.getElementById('saveTrId').style.display='none';
          }
      }
      
    formx = document.allDetailsForm;     
    //start Employee
   try{ 
    totalEmployeeIds = formx.elements['employeeId'].length;
    for(i=0;i<totalEmployeeIds;i++) {
       formx.elements['employeeId'][i].id = "employeeId"+(i+1); 
       formx.elements['firstEmployeeId'][i].id = "firstEmployeeId"+(i+1); 
       if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
         formx.elements['secondEmployeeId'][i].id = "secondEmployeeId"+(i+1); 
       }
       formx.elements['leaveTypeId'][i].id = "leaveTypeId"+(i+1); 
    }
   }
   catch(e){}    
} 


//to add one row at the end of the list
function addOneRow(){
 resourceAddCnt++; 
 createRows(resourceAddCnt,1,-1,-1,-1,-1,-1,-1);
 document.getElementById('saveTrId').style.display='';
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
function createRows(start,rowCnt,empId,firstAuth,secondAuth,leaveType,isMapped,isApproved){
  
 var tbl=document.getElementById('resourceDetailTable');
 var tbody = document.getElementById('resourceDetailTableBody');
 
 for(var i=0;i<rowCnt;i++){
  var tr=document.createElement('tr');
  tr.setAttribute('id','row'+parseInt(start+i,10));
  
  var cell1=document.createElement('td');
  cell1.setAttribute('align','left');
  cell1.name='srNo';
  
  var cell2=document.createElement('td'); 
  var cell3=document.createElement('td'); 
  if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
    var cell4=document.createElement('td');
  }
  var cell5=document.createElement('td');
  var cell6=document.createElement('td');
  
  cell2.setAttribute('align','left'); 
  cell2.style.paddingLeft='2px';; 
  cell3.setAttribute('align','left'); 
  cell3.style.paddingLeft='2px';; 
  if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
    cell4.setAttribute('align','left'); 
    cell4.style.paddingLeft='2px';; 
  }
  cell5.setAttribute('align','left'); 
  cell5.style.paddingLeft='2px';; 
  cell6.setAttribute('align','center');
  
  if(start==0){
    var txt0=document.createTextNode(start+i+1);
  }
  else{
    var txt0=document.createTextNode(start+i);
  }
  
  var txt2=document.createElement('select');
  txt2.className="selectfield";
  //txt2.style.width="120px";
  txt2.setAttribute('id','employeeId'+parseInt(start+i,10));
  txt2.setAttribute('name','employeeId');
  txt2.onchange = new Function('getAuthorizerAndLeaveTypes(this.value,this.id)');
  
  var txt3=document.createElement('select');
  txt3.className="selectfield";
  //txt3.style.width="120px";
  txt3.setAttribute('id','firstEmployeeId'+parseInt(start+i,10));
  txt3.setAttribute('name','firstEmployeeId');
  
  if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
      var txt4=document.createElement('select');
      txt4.className="selectfield";
      //txt4.style.width="120px";
      txt4.setAttribute('id','secondEmployeeId'+parseInt(start+i,10));
      txt4.setAttribute('name','secondEmployeeId');
  }
  
  var txt5=document.createElement('select');
  txt5.className="selectfield";
  //txt5.style.width="120px";
  txt5.setAttribute('id','leaveTypeId'+parseInt(start+i,10));
  txt5.setAttribute('name','leaveTypeId');
  
  var txt6=document.createElement('a');
  txt6.setAttribute('id','rd');
  if(isMapped==-1){
   txt6.setAttribute('title','Delete');       
   txt6.innerHTML='X';
   txt6.style.cursor='pointer';
  }
  else{
    txt6.innerHTML="<?php echo NOT_APPLICABLE_STRING?>";
  }
  
  txt6.setAttribute('href','javascript:void(0)');  //for ie and ff    
  txt6.onclick = new Function('deleteRow("'+parseInt(start+i,10)+'~0",'+empId+','+firstAuth+','+secondAuth+','+leaveType+')');
  
  cell1.appendChild(txt0);
  cell2.appendChild(txt2);
  cell3.appendChild(txt3);
  if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
    cell4.appendChild(txt4);
  }
  cell5.appendChild(txt5);
  cell6.appendChild(txt6);
  
  cell3.innerHTML +='<input type="hidden" id="apprId'+parseInt(start+i,10)+'" value="'+isApproved+'" />';

  tr.appendChild(cell1);
  tr.appendChild(cell2);
  tr.appendChild(cell3);
  if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
    tr.appendChild(cell4);
  }
  tr.appendChild(cell5);
  tr.appendChild(cell6);
  
  bgclass=(bgclass=='row0'? 'row1' : 'row0');
  tr.className=bgclass;
  
  tbody.appendChild(tr); 
      
    /*For Emp DDS*/
      var len=document.getElementById('hiddenAuthorizedEmployeeId').options.length;
      var parentEle=document.getElementById('hiddenAuthorizedEmployeeId');
      var employeeId='employeeId'+parseInt(start+i,10);
      for(var kk=0;kk<len;kk++){
         addOption(document.getElementById(employeeId), parentEle.options[kk].value,  parentEle.options[kk].text);
      }
      if(empId!=-1){
          document.getElementById(employeeId).value=empId;
      }
    /*For Emp DDS*/
    
    /*FOR AUTHORIZER DDS*/
     var len=document.getElementById('hiddenEmpId').options.length;
     var parentEle=document.getElementById('hiddenEmpId');
     var firstEemployeeId='firstEmployeeId'+parseInt(start+i,10);
     addOption(document.getElementById(firstEemployeeId), '',  'Select');
      
     if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
       var secondEemployeeId='secondEmployeeId'+parseInt(start+i,10);
       addOption(document.getElementById(secondEemployeeId), '',  'Select');
     }
          
     if(empId!=-1){
          for(var kk=0;kk<len;kk++){
              if(parentEle.options[kk].value==empId){
                  continue
              }
              addOption(document.getElementById(firstEemployeeId), parentEle.options[kk].value,  parentEle.options[kk].text);
              if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                addOption(document.getElementById(secondEemployeeId), parentEle.options[kk].value,  parentEle.options[kk].text);
              }
          }
          document.getElementById(firstEemployeeId).value=firstAuth;
          if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
           document.getElementById(secondEemployeeId).value=secondAuth;
          }
     }
    /*FOR AUTHORIZER DDS*/
    
    /*FOR LEAVE TYPE DDS*/
     var len=document.getElementById('hiddenLeaveTypeId').options.length;
     var parentEle=document.getElementById('hiddenLeaveTypeId');
     var leaveTypeId='leaveTypeId'+parseInt(start+i,10);
     addOption(document.getElementById(leaveTypeId), '',  'Select');     
     
     if(empId!=-1){
       for(var kk=0;kk<len;kk++) {
         var val=parentEle.options[kk].value.split('~');
            if(val[0]!=empId){
              continue
            }
         addOption(document.getElementById(leaveTypeId), val[1],  parentEle.options[kk].text);
       }
       document.getElementById(leaveTypeId).value=leaveType;
     }
    /*FOR AUTHORIZER DDS*/
    
    if(isMapped!=-1){
       document.getElementById(employeeId).disabled=true; 
       //document.getElementById(firstEemployeeId).disabled=true; 
       if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
         //document.getElementById(secondEemployeeId).disabled=true; 
       }
       document.getElementById(leaveTypeId).disabled=true; 
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
      a[i].innerHTML=j;
      j++;
    }
  }
  resourceAddCnt=j-1;
}

//****************FUNCTION NEEDED FOR DYNAMICALLY ADDING/EDITING/DELETTING ROWS**************

function getAuthorizerAndLeaveTypes(val,target){
   getEmployeeAuthorizer(val,target); 
   getEmployeeLeaveTypes(val,target)
}

function getEmployeeAuthorizer(val,target) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxGetEmployeeAuthorizer.php';
         var t=target.split('employeeId');
         var targetId=t[1];
         var firstEmp=document.getElementById('firstEmployeeId'+targetId);
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
           var secondEmp=document.getElementById('secondEmployeeId'+targetId);
         }
         
         firstEmp.options.length=0;
         if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
           secondEmp.options.length=0;
         }
         
         if(trim(val)==''){
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {val: val},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        var len=j.length;
                        addOption(firstEmp,'','Select');
                        if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                          addOption(secondEmp,'','Select');  
                        }
                        for(var i=0;i<len;i++){
                           addOption(firstEmp,j[i].employeeId,j[i].employeeName);
                           if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {  
                             addOption(secondEmp,j[i].employeeId,j[i].employeeName);
                           }
                        }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getEmployeeLeaveTypes(val,target) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/ApplyLeave/ajaxGetEmployeeLeaveTypes.php';
         var t=target.split('employeeId');
         var targetId=t[1];
         var leaveType=document.getElementById('leaveTypeId'+targetId);
         leaveType.options.length=0;
         
         
         
         if(trim(val)==''){
             return false;
         }
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous : false,
             parameters: {val: val},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        
                     }
                    else{
                        var j = eval('('+trim(transport.responseText)+')');
                        var len=j.length;
                        addOption(leaveType,'','Select');
                        for(var i=0;i<len;i++){
                           addOption(leaveType,j[i].leaveTypeId,j[i].leaveTypeName);
                        }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function prePopulateValue() {
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveAuthorizer/ajaxInitListAdv.php';
         cleanUpTable();
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {1: 1},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                     }
                    else{
                         var j = eval('('+transport.responseText+')');
                         var len=j.length;
                         resourceAddCnt=0;
                         if(len>0){
                          for(var i=0;i<len;i++){
                             resourceAddCnt++;
                             createRows(resourceAddCnt,1,j[i]['employeeId'],j[i]['firstApprovingEmployeeId'],j[i]['secondApprovingEmployeeId'],j[i]['leaveTypeId'],j[i]['isMapped'],j[i]['approvingId']);
                          }
                          document.getElementById('saveTrId').style.display='';
                        }
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

window.onload=function(){
    prePopulateValue();
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeLeaveAuthorizer/listEmployeeLeaveAuthorizerAdvContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listEmployeeLeaveAuthorizerAdv.php $ 
?>