<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Bus Pass Form
//
// Author :Parveen Sharma
// Created on : 12-June-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentBusPass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Bus Pass </title>
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

var studentIds="";                 

/*
var tableHeadArray = new Array( 
                    new Array('srNo','#','width="3%"','',false), 
                    new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                    new Array('regNo','Reg. No.','width="12%"','',true), 
                    new Array('studentName','Name','width="14%"','',true), 
                    new Array('fatherName','Father`s Name','width="15%"','',true), 
                    new Array('className','Class','width="14%"','',true),
                    new Array('studentMobileNo','Contact No.','width=12%','align="right"',true),
                    new Array('routeCode','Route','width="11%"','',true),
                    new Array('stopName','Stoppage','width="11%"','',true),
                    new Array('action1','Action','width="9%"','align="right"',false)
                    );
*/                    

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','align="center"',false), 
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                               new Array('rollNo','Roll No.','width="10%"','',true), 
                               new Array('studentName','Name','width="20%"','',true), 
                               new Array('busName','Bus Route','width="18%"','',false),
                               new Array('busNo','Bus No.','width="18%"','',false),
                               new Array('stoppage','Bus Stoppage','width="18%"','',false),
                               new Array('receiptNo','Receipt No.','width="11%"','',false),
                               new Array('validUpto','Valid Upto<br><span style="color:#FF0000; font-size:8px; font-weight:bold; font-family:Verdana">(DD-MM-YY)</span>','width="11%"','align="center"',false),
                               new Array('action1','Action','width="8%"','align="center"',false)  );

//This function Validates Form 
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL='<?php echo HTTP_LIB_PATH;?>/Icard/initStudentBusPass.php';    
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusPass';   
editFormName   = 'AddBusPass';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBusPass';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'rollNo';
sortOrderBy = 'ASC';

var validRegNo ='';
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window
// ajax search results ---end ///
//This function Displays Div Window

var dtArrayRoute=new Array(); 
var dtArrayBus=new Array(); 

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function validateAddForm1(frm,act) {
   
   dtArrayRoute.splice(0,dtArrayRoute.length); //empty the array   
   dtArrayBus.splice(0,dtArrayBus.length); //empty the array         
   
   if(document.getElementById('degree').value=='') {
      messageBox("<?php echo SELECT_DEGREE; ?>");  
      document.getElementById('degree').focus();
      return false;
   }
   
   if(!isAlphaCharCustom(document.getElementById('sRollNo').value,"0123456789-/.") && document.getElementById('sRollNo').value!='' ) {
      messageBox("<?php echo ACCEPT_ROLLNO; ?>");  
      document.getElementById('sRollNo').focus();
      return false;
   }
   
   if(!isAlphaCharCustom(document.getElementById('sName').value," .") && document.getElementById('sName').value!='' ) {
      messageBox("<?php echo ACCEPT_NAME; ?>");  
      document.getElementById('sName').focus();
      return false;
   }

   /*
   if(trim(document.getElementById('sRollNo').value) =='' && trim(document.getElementById('sName').value)=='' ) {
      messageBox("<?php echo ENTER_ROLLNO_NAME; ?>");  
      document.getElementById('sRollNo').focus();
      return false;
   }
   */

   if(act=='busPassPrint' || act=='busPassSave') {
      addBusPass(act);
      return false;
   }
   
  /* else {
      if(trim(document.getElementById('busPassId').value)!='') {
        if(trim(document.getElementById('busPassStatus').value)=='') {
          messageBox("<?php echo BUSPASS_STATUS; ?>");      
          document.getElementById('busPassStatus').focus();
          return false;
        }  
        editBusPass(act);
        return false;    
      }
   } */ 
   
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   document.getElementById('resultsDiv').innerHTML='';
            
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
   doAll();
}


function checkDuplicate(value,type) {
  
    if(type=='Bus') {
      var i= dtArrayBus.length;
      var fl=1;
      for(var k=0;k<i;k++){
         if(dtArrayBus[k]==value){
           dtArrayBus[k]=value;
           break;
         }  
      }
      if(fl==1){
        dtArrayBus.push(value);
      } 
   }
   else {
      var i= dtArrayRoute.length;
      var fl=1;
      for(var k=0;k<i;k++){
         if(dtArrayRoute[k]==value){
           dtArrayRoute[k]=value;
           break;
         }  
      }
      if(fl==1){
        dtArrayRoute.push(value);
      }  
   }
   
   return fl;
}
       
function validateAddForm(frm,act) {
   
   if(act=='busPassPrint' || act=='busPassSave') {
      addBusPass(act);
      return false;
   }
   else {
      if(trim(document.getElementById('busPassId').value)!='') {
        if(trim(document.getElementById('busPassStatus').value)=='') {
          messageBox("<?php echo BUSPASS_STATUS; ?>");      
          document.getElementById('busPassStatus').focus();
          return false;
        }  
        editBusPass(act);
        return false;    
      }
   } 
   
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   document.getElementById('resultsDiv').innerHTML='';
   
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
   doAll();
}

function checkDisable(id) {
   
   if(eval("document.getElementById('sStudentId"+(id)+"').value=='Y'")) { 
       if(eval("document.getElementById('chb"+(id)+"').checked==true")) {
          eval("document.getElementById('busRoute"+(id)+"').disabled = false");
          eval("document.getElementById('busStop"+(id)+"').disabled = false");
          eval("document.getElementById('receiptNo"+(id)+"').disabled = false");
          eval("document.getElementById('validUpto"+(id)+"').disabled = false");
          eval("document.getElementById('busNo"+(id)+"').disabled = false");
       }
       else {
          eval("document.getElementById('busRoute"+(id)+"').disabled= true");
          eval("document.getElementById('busStop"+(id)+"').disabled= true");
          eval("document.getElementById('receiptNo"+(id)+"').disabled= true");
          eval("document.getElementById('validUpto"+(id)+"').disabled= true");
          eval("document.getElementById('busNo"+(id)+"').disabled= true");
          
          eval("document.getElementById('busNo"+(id)+"').selectedIndex=0 ");
          eval("document.getElementById('busRoute"+(id)+"').selectedIndex=0 ");
          eval("document.getElementById('busStop"+(id)+"').selectedIndex=0 ");
          eval("document.getElementById('receiptNo"+(id)+"').value='' ");
          eval("document.getElementById('validUpto"+(id)+"').value='' ");
       }   
   }
}

//THIS FUNCTION IS USED TO POPULATE "bus route" select box depending upon which bus stoppage is selected
function getBusCount(val,no) {       
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxGetBusTotal.php'; 
 
    //document.searchForm.studentStop.options.length=0;
    document.getElementById('divBusTotal'+no).innerHTML='';  
    
    if(val=='') {
      getUpdateRoute('Bus'); 
      return false;
    }
   
    var totalBus=0;
    pars = "busId="+val;
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            j = eval('('+ transport.responseText+')');

            totalBus = j[0].cnt;
            seatingCapacity = j[0].seatingCapacity;
            var str = val+"~"+totalBus+"~"+seatingCapacity;
            checkDuplicate(str,'Bus');  
            getUpdateRoute('Bus');
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "bus route" select box depending upon which bus stoppage is selected

function autoPopulate(val,no) {       
   
    var url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxGetRouteWiseBusNo.php'; 
   //document.searchForm.studentStop.options.length=0;
    document.getElementById('busStop'+no).options.length=0;
    var objOption = new Option("Select","");
    document.getElementById('busStop'+no).options.add(objOption); 
    
    document.getElementById('busNo'+no).options.length=0;
    var objOption = new Option("Select","");
    document.getElementById('busNo'+no).options.add(objOption); 
   
    document.getElementById('divRouteTotal'+no).innerHTML='';
    document.getElementById('divBusTotal'+no).innerHTML='';  
    
    if(val=='') {
      getUpdateRoute('Route'); 
      return false;
    }
   
    var totalRoute=0;
    pars = "busRouteId="+val;
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var ret=trim(transport.responseText).split('!~!~!');  
            if(ret.length > 0 ) {
                var j = eval('(' + ret[0] + ')');
                for(var c=0;c<j.length;c++){
                   objOption = new Option(j[c].busNo,j[c].busId);
                   document.getElementById('busNo'+no).options.add(objOption); 
                }
                
                var j = eval('(' + ret[1] + ')');
                for(var c=0;c<j.length;c++){
                   objOption = new Option(j[c].stopName,j[c].busStopId);
                   document.getElementById('busStop'+no).options.add(objOption); 
                }
                
                var j = eval('(' + ret[2] + ')'); 
                totalRoute = j;
                var str = val+"~"+totalRoute;
                checkDuplicate(str,'Route');  
                getUpdateRoute('Route');
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}
         
         
                                 
function getUpdateRoute(chk) {

    if(chk=='Route') {
        var len= dtArrayRoute.length;
        var fl=1;
        for(var k=0;k<len;k++){
          var ret=dtArrayRoute[k].split('~');
          var totalRoute=ret[1];
          var val = ret[0];
          
          var formx = document.searchForm;
          for(var i=1;i<formx.length;i++){
             if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && formx.elements[i].checked==true) {  
                id=formx.elements[i].value;
                route= "busRoute"+formx.elements[i].value;
                routeValue = document.getElementById(route).value;
                if(val==routeValue) {
                  totalRoute++;
                  document.getElementById('divRouteTotal'+id).innerHTML="Total Users Allotted:&nbsp;"+totalRoute;  
                }
             }  
          }
        }
    }
    else {
        var len= dtArrayBus.length;
        var fl=1;
        for(var k=0;k<len;k++){
          var ret=dtArrayBus[k].split('~');
          var totalRoute=ret[1];
          var val = ret[0];
          var seatingCapacity = ret[2];
          var formx = document.searchForm;
          for(var i=1;i<formx.length;i++){
             if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && formx.elements[i].checked==true) {  
                id=formx.elements[i].value;
                route= "busNo"+formx.elements[i].value;
                routeValue = document.getElementById(route).value;
                if(val==routeValue) {
                  totalRoute++;
                  document.getElementById('divBusTotal'+id).innerHTML="Alloted:&nbsp;"+totalRoute+", Capacity:&nbsp;"+seatingCapacity;  
                }
             }  
          }
        }
    }
    return false;
}

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    document.getElementById('divHeaderId').innerHTML= '&nbsp;Edit Student Bus Pass Details'; 
    populateValues(id); 
}


function addBusPass(act) {
        
    formx = document.searchForm;
     
    var selected=0;
    studentCheck='';
    
    var formx = document.searchForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }             
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
    }
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
      
      
     // Check Validations         
     for(var i=1;i<formx.length;i++) {
         if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && (formx.elements[i].checked) ){
              id=formx.elements[i].value;
              route = "sStudentId"+id;
              if(document.getElementById(route).value=='Y' ) {   
                    route= "busRoute"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                      messageBox("<?php echo BUSPASS_ROUTE?>");
                      document.getElementById(route).focus(); 
                      return false;
                    }
                   
                    route= "busStop"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                      messageBox("<?php echo BUSPASS_STOPPAGE?>");
                      document.getElementById(route).focus(); 
                      return false;
                    }
                   
                    route= "receiptNo"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                      messageBox("<?php echo ENTER_BUSPASS_RECEIPT; ?>");      
                      document.getElementById(route).focus();
                      return false;
                    } 
                    
                    if(!isAlphaCharCustom(document.getElementById(route).value,"0123456789-/.&")) {
                         messageBox("<?php echo ACCEPT_BUSPASS_RECEIPT; ?>");      
                         document.getElementById(route).focus();  
                         return false;
                    } 
                   
                    route= "validUpto"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                        messageBox("<?php echo BUSPASS_VALID_DATE; ?>");      
                        document.getElementById(route).focus();
                        return false;
                    }
                    
                    if(!isNumericCustom(document.getElementById(route).value,"-")) {
                         messageBox("<?php echo DATE_FORMAT; ?>");      
                         document.getElementById(route).focus();  
                         return false;
                    } 
                   
                    if(document.getElementById(route).value.length < 8 ) {
                         messageBox("<?php echo DATE_FORMAT; ?>");      
                         document.getElementById(route).focus();  
                         return false;
                    }
                    
                    var dateStr = document.getElementById(route).value
                    var dt=dateStr.split('-');
                    dateStr = dt[1]+"-"+dt[0]+"-20"+dt[2];
                       
                    if(!isDate2(dateStr)) {
                         document.getElementById(route).focus();  
                         return false;
                    }

                    dateStr = "20"+dt[2]+"-"+dt[1]+"-"+dt[0];
                    //alert(dateStr);
                    if(!dateDifference(document.getElementById('currentDate').value,dateStr,'-') ) {
                       messageBox("<?php echo BUSPASS_DATE_CHECK; ?>");      
                       document.getElementById(route).focus();
                       return false;
                    } 
               }
           }
       }
       
       // Duplicate Receipt No. Validation Checks
       for(i=1;i<formx.length;i++) {
         if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && (formx.elements[i].checked) ){
              id=formx.elements[i].value;
              route = "sStudentId"+id;
              if(document.getElementById(route).value=='Y' ) {   
                route= "receiptNo"+formx.elements[i].value;
                routeValue = document.getElementById(route).value;
                for(var j=(i+1);j<formx.length;j++) {
                  if(formx.elements[j].type=="checkbox" && formx.elements[j].name=="chb[]" && (formx.elements[j].checked) ){
                      id=formx.elements[j].value;
                      route = "sStudentId"+id;
                      if(document.getElementById(route).value=='Y' ) {   
                         route1= "receiptNo"+formx.elements[j].value;
                         routeValue1 = document.getElementById(route1).value; 
                         if(routeValue1==routeValue) {
                             messageBox("<?php echo RECEIPT_BUSPASS_ALREADY; ?>");      
                             document.getElementById(route).focus();
                             return false;
                         }     
                      }
                  }
                } // End for j lodp 
            }
         }
       }
       
       
       url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitAdd.php';
       var pars = generateQueryString('searchForm');
       new Ajax.Request(url,
       {
         method:'post',
         parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                     flag = true;
                     if(act=='busPassPrint') {
                         printReport();
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
                         doAll();
                         return false;
                     }   
                     else {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
                         doAll();
                         //location.reload();
                         return false;
                     }
                     return false;
                 } 
                 else
                 if("<?php echo FAILURE;?>" == trim(transport.responseText)) {                     
                    messageBox(trim(transport.responseText));  
                    return false;
                 }
                 else {
                     //messageBox(trim(transport.responseText));  
                    displayWindow('divDuplicateReceiptInformation',420,370);
                    document.getElementById('receiptInfo').innerHTML= trim(transport.responseText);  
                    //alert(trim(transport.responseText)); 
                    return false;  
                 }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function editBusPass(studentId,busPassId,classId) {
         
         url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitEdit.php';
         
         if(false===confirm("<?php echo CANCEL_BUSPASS;?>")) {
            return false;
         }
         
         new Ajax.Request(url,
             {
                  method:'post',
                  parameters:{ busPassId : busPassId,
                               busPassStatus : '2', 
                               studentId : studentId,
                               classId : classId
                             },
                  onSuccess: function(transport){
                  if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                      showWaitDialog(true);
                  }
                  else {
                         //hideWaitDialog(true);
                        // messageBox(trim(transport.responseText));
                         if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                            // hiddenFloatingDiv('AddBusPass');
                             //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
                             doAll();
                             if(act=='Print') {
                                printReport();
                             }
                             //blankValues();
                             return false;
                             //location.reload();
                         }
                          else {
                             messageBox(trim(transport.responseText));
                             //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
                             //doAll();
                             //blankValues();
                             return false;
                         }
                   }
                 },
                 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
               });
} 

/*function editBusPass(act) {
         
         url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitEdit.php';
         
         //studentIdS = trim(document.addBusPass.studentId.value);
         new Ajax.Request(url,
         {
              method:'post',
              parameters:{ busStopId  : trim(document.addBusPass.studentStop.value),
                           busRouteId : trim(document.addBusPass.studentRoute.value),
                           receiptNo : trim(document.addBusPass.receiptNo.value),
                           validUpto : trim(document.addBusPass.validUpto.value),
                           busPassStatus : trim(document.addBusPass.busPassStatus.value),
                           studentId : trim(document.addBusPass.studentId.value),
                           classId :  trim(document.addBusPass.classId.value),
                           busPassId: trim(document.addBusPass.busPassId.value)
                         },   
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
                 else {
                     hideWaitDialog(true);
                    // messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AddBusPass');
                         //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
                         doAll();
                         if(act=='Print') {
                            printReport();
                         }
                         //blankValues();
                         return false;
                         //location.reload();
                     }
                      else {
                         messageBox(trim(transport.responseText));
                         //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false); 
                         //doAll();
                         //blankValues();
                         return false;
                     }
               
               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
   validRegNo = "";           
}       
*/

// Blank Values
function blankValues() {
    document.getElementById("studentDetail").style.display='none';
    document.getElementById("editStudentDetail").style.display='';    
    document.getElementById("studentName").innerHTML  = '';
    document.getElementById("className").innerHTML  = '';
    document.getElementById('divHeaderId').innerHTML= '&nbsp;Add Student Bus Pass'; 
    document.getElementById('busRouteDiv1').style.display= 'none';
    document.getElementById('busStopDiv1').style.display= 'none';
    document.getElementById('validDiv1').style.display= 'none';
    document.getElementById('receiptDiv1').style.display= 'none'; 
    document.getElementById('busPassStatusDiv').style.display= 'none'; 
    document.getElementById('busRouteDiv').style.display= '';
    document.getElementById('busStopDiv').style.display= '';
    document.getElementById('validDiv').style.display= '';
    document.getElementById('receiptDiv').style.display= '';    
    document.getElementById('studentId').value= ''; 
    document.getElementById('classId').value= ''; 
    document.getElementById('busPassId').value= ''; 
    
    document.getElementById('studentRoute').selectedIndex=0; 
    document.addBusPass.studentStop.options.length=0;
    var objOption = new Option("Select","");
    document.addBusPass.studentStop.options.add(objOption); 
    
    document.getElementById('regNo').value= '';    
    document.getElementById('receiptNo').value= '';    
    document.getElementById('validUpto').value= ''; 
    
    document.getElementById('busPassStatus').selectedIndex=1; 
    //document.getElementById('busPassStatus').disabled=true; 
    
    
    document.addBusPass.regNo.disabled = false;        
    document.addBusPass.receiptNo.disabled = false;  
    document.addBusPass.validUpto.disabled = false;        
    document.addBusPass.studentRoute.disabled = false;  
    document.addBusPass.studentStop.disabled = false;  
    
    document.addBusPass.regNo.focus();
    
    validRegNo = "";
}




// function to display Student Details
function getStudentDetails() {
   //blankValues();
   if(document.addBusPass.regNo.value=='') {
       document.getElementById("studentDetail").style.display='none';   
       return false;
   }
   url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxGetStudentDetails.php';     
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {regNo: document.addBusPass.regNo.value},
         onCreate: function() {
             showWaitDialog(true);
    },
    onSuccess: function(transport){
            hideWaitDialog(true);
            if(trim(transport.responseText)==0) {
              document.getElementById("studentDetail").style.display='none';
              //messageBox("Invalid Reg. Number");
              //document.getElementById('regNo').focus();
              validRegNo = "";
              return false;
           }
           
           j = eval('('+trim(transport.responseText)+')');
           document.getElementById("studentDetail").style.display='';
           document.getElementById('studentName').innerHTML= j.studentName;  
           document.getElementById('className').innerHTML= j.className; 
           
           document.getElementById('studentId').value= j.studentId; 
           document.getElementById('classId').value= j.classId;    
           
           validRegNo="1";
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}



//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Student Details"

function populateValues(id) {
   
         url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxGetValues.php';
         
           //blankValues();
           new Ajax.Request(url,
           {
             method:'post',
             asynchoronus:false,
             parameters: {busPassId: id},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                   hideWaitDialog(true);
                   var j = eval('('+transport.responseText+')');
                   
                   document.getElementById('busPassStatus').selectedIndex=1; 
                   
                   document.getElementById("studentDetail").style.display='';
                   document.getElementById("editStudentDetail").style.display='none'; 
                   
                   document.getElementById('studentName').innerHTML= j.studentName;    
                   document.getElementById('className').innerHTML= j.className; 
                 
                   document.getElementById('busRouteDiv').style.display= 'none';
                   document.getElementById('busStopDiv').style.display= 'none';
                   document.getElementById('validDiv').style.display= 'none';  
                   document.getElementById('receiptDiv').style.display= 'none';   
    
                   document.getElementById('busRouteDiv1').style.display= '';
                   document.getElementById('busStopDiv1').style.display= '';
                   document.getElementById('validDiv1').style.display= ''; 
                   document.getElementById('receiptDiv1').style.display= '';  
                   document.getElementById('busPassStatusDiv').style.display= ''; 
    
                   document.getElementById('busRouteDiv1').innerHTML= j.routeCode;                
                   document.getElementById('busStopDiv1').innerHTML= j.stopName;
                   document.getElementById('receiptDiv1').innerHTML= j.receiptNo;
                   document.getElementById('validDiv1').innerHTML= customParseDate(j.validUpto,"-");
                   document.getElementById('studentRollNo').innerHTML= j.rollNo;
                   
                   
                   
                   document.getElementById('busPassId').value= j.busPassId; 
                   document.getElementById('classId').value= j.classId; 
                   document.getElementById('studentId').value= j.studentId; 
                  
                   
                   document.addBusPass.regNo.value = j.regNo;
                   document.addBusPass.studentRoute.value = j.busRouteId;
                   autoPopulate(j.busRouteId,'busRoute','addBusPass');
                   //alert(j.busStopId);
                   document.addBusPass.studentStop.value = j.busStopId;
                   
                   //document.addBusPass.receiptNo.value = j.receiptNo;
                   //document.addBusPass.validUpto.value = j.validUpto;
                   //document.addBusPass.busPassStatus.value = j.busPassStatus;
                   
                   document.addBusPass.studentRoute = j.selectedIndex=1;                   
                   //document.getElementById('busPassStatus').disabled=false; 
                  
                   document.addBusPass.regNo.disabled = true;        
                   document.addBusPass.receiptNo.disabled = true;  
                   document.addBusPass.validUpto.disabled = true;        
                   document.addBusPass.studentRoute.disabled = true;  
                   document.addBusPass.studentStop.disabled = true;  
                   
                   document.addBusPass.busPassStatus.focus();
                   
                   validRegNo = "1";
               }
           },
           onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
         });
}


function doAll(){
   
   try {
       var formx = document.searchForm;
       if(formx.checkbox2.checked){
            for(var i=1;i<formx.length;i++){
                if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {  
                  formx.elements[i].checked=true;
                  id=formx.elements[i].value;
                  route = "sStudentId"+id;
                  if(document.getElementById(route).value=='Y' ) {   
                     route = "busRoute"+id;
                     document.getElementById(route).disabled=false;
                     document.getElementById(route).selectedIndex=0;
                     
                     route = "busStop"+id;
                     document.getElementById(route).disabled=false;
                     document.getElementById(route).selectedIndex=0;
                     
                     route = "busNo"+id;
                     document.getElementById(route).disabled=false;
                     document.getElementById(route).selectedIndex=0;
                     
                     route = "receiptNo"+id;
                     document.getElementById(route).disabled=false;
                     document.getElementById(route).value="";
                     
                     route = "validUpto"+id;
                     document.getElementById(route).disabled=false;
                     document.getElementById(route).value="";
                 }
               }
            }  // end for loop
        }
        else{
            for(var i=1;i<formx.length;i++){
                if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {  
                  formx.elements[i].checked=false;
                  id=formx.elements[i].value;
                  route = "sStudentId"+id;
                  if(document.getElementById(route).value=='Y' ) {   
                     route = "busRoute"+id;
                     document.getElementById(route).disabled=true;
                     document.getElementById(route).selectedIndex=0;
                     
                     route = "busStop"+id;
                     document.getElementById(route).disabled=true;
                     document.getElementById(route).selectedIndex=0;
                     
                     route = "busNo"+id;
                     document.getElementById(route).disabled=true;
                     document.getElementById(route).selectedIndex=0;
                     
                     route = "receiptNo"+id;
                     document.getElementById(route).disabled=true;
                     document.getElementById(route).value="";
                     
                     route = "validUpto"+id;
                     document.getElementById(route).disabled=true;
                     document.getElementById(route).value="";
                 }
               }
            }  // end for loop
        } 
  } catch(e){ } 
}

// function to print Bus Pass report
function printReport() {                    
    
    var selected=0;
    studentCheck='';
    
    
    classId=document.getElementById('degree').value;
    var formx = document.searchForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }             
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
    }
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    form = document.searchForm;
    path='<?php echo UI_HTTP_PATH;?>/busPrint.php?student='+studentCheck+'&cardView=1&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+classId;
    window.open(path,"BusPassPrint","status=1,menubar=1,scrollbars=1, width=900");
}


/* 
//this function validates form
function validateAddForm(frm, act) {
   
   if(trim(document.getElementById('busPassId').value)=='') {
       
       if(trim(document.getElementById('regNo').value)=='') {
          document.getElementById("studentDetail").style.display='none';
          document.getElementById('studentName').innerHTML= j.studentName;  
          document.getElementById('className').innerHTML= j.programme;    
          messageBox("<?php echo ENTER_BUSPASS_REG; ?>");   
          document.getElementById('regNo').focus();
          return false;
       }
       
       if(!isAlphaCharCustom(document.getElementById('regNo').value,"0123456789-/.")) {
          document.getElementById("studentDetail").style.display='none';
          document.getElementById('studentName').innerHTML= j.studentName;  
          document.getElementById('className').innerHTML= j.programme; 
          messageBox("<?php echo INVALID_BUSPASS_REG; ?>");  
          document.getElementById('regNo').focus();
          return false;
       }

       
       if(!isAlphaCharCustom(document.getElementById('receiptNo').value,"0123456789-/.&")) {
          messageBox("<?php echo ACCEPT_BUSPASS_RECEIPT; ?>");      
          document.getElementById('receiptNo').focus();
          return false;
       } 
       
       if(trim(document.getElementById('studentRoute').value)=='') {
          messageBox("<?php echo BUSPASS_ROUTE; ?>");      
          document.getElementById('studentRoute').focus();
          return false;
       }
       
       if(trim(document.getElementById('studentStop').value)=='') {
          messageBox("<?php echo BUSPASS_STOPPAGE; ?>");      
          document.getElementById('studentStop').focus();
          return false;
       }
       
       if(trim(document.getElementById('receiptNo').value)=='') {
          messageBox("<?php echo ENTER_BUSPASS_RECEIPT; ?>");      
          document.getElementById('receiptNo').focus();
          return false;
       } 
       
       if(trim(document.getElementById('validUpto').value)=='') {
          messageBox("<?php echo BUSPASS_VALID_DATE; ?>");      
          document.getElementById('validUpto').focus();
          return false;
       }
       
       if(!dateDifference(document.getElementById('currentDate').value,document.getElementById('validUpto').value,'-') ) {
          messageBox("<?php echo BUSPASS_DATE_CHECK; ?>");      
          document.getElementById('validUpto').focus();
          return false;
       }
       
        if(validRegNo=='') {
          if(trim(document.getElementById('regNo').value)=='') {
             messageBox("<?php echo ENTER_BUSPASS_REG; ?>");      
             document.getElementById('regNo').focus();
             return false;
          }  
          else {
             document.getElementById("studentDetail").style.display='none';
             //document.getElementById('studentName').innerHTML= j.studentName;  
            // document.getElementById('className').innerHTML= j.programme;    
             messageBox("<?php echo INVALID_BUSPASS_REG; ?>");      
             document.getElementById('regNo').focus();
             return false;
          }
       } 
 
   }
  
   if(trim(document.getElementById('busPassStatus').value)=='') {
      messageBox("<?php echo BUSPASS_STATUS; ?>");      
      document.getElementById('busPassStatus').focus();
      return false;
   }  
                                                                                                    
   if(trim(document.getElementById('busPassId').value)=='') {
      addBusPass(act);
      return false;
   }
   else {
      editBusPass(act);
      return false;
   }

   studentIds = trim(document.addBusPass.studentId.value);
   
}

function addBusPass(act) {
         url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitAdd.php';
         
         studentIdS = trim(document.addBusPass.studentId.value);
         
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {busStopId  : trim(document.addBusPass.studentStop.value),
                          busRouteId : trim(document.addBusPass.studentRoute.value),
                          receiptNo : trim(document.addBusPass.receiptNo.value),
                          validUpto : trim(document.addBusPass.validUpto.value),
                          busPassStatus : trim(document.addBusPass.busPassStatus.value),
                          studentId : trim(document.addBusPass.studentId.value),
                          classId : trim(document.addBusPass.classId.value)
                         },
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(act=='Print') {
                             hiddenFloatingDiv('AddBusPass');
                             path='<?php echo UI_HTTP_PATH;?>/busPrint.php?student='+studentIdS+'&cardView=1&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
                             window.open(path,"BusPassPrint","status=1,menubar=1,scrollbars=1, width=900"); 
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                             return false;
                         }   
                         else if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                              validRegNo = "";
                              blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddBusPass');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function autoPopulate(val,type,frm)
{       
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php'; 
   if(frm=="Add"){
       if(type=="busRoute"){
          document.addBusPass.studentStop.options.length=0;
          var objOption = new Option("Select","");
          document.addBusPass.studentStop.options.add(objOption); 
       }   
   }
   else{
        if(type=="busRoute"){
          document.addBusPass.studentStop.options.length=0;
          var objOption = new Option("Select","");
          document.addBusPass.studentStop.options.add(objOption); 
       }
   }  
      
 new Ajax.Request(url,
           {
             method:'post',
             asynchoronus:false,
             parameters: {type: type,id: val},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                    hideWaitDialog(true);
                    var j = eval('('+transport.responseText+')'); 
                    
                     for(var c=0;c<j.length;c++){
                         if(frm=="Add"){
                            
                             if(type=="busRoute"){
                                 objOption = new Option(j[c].stopName,j[c].busStopId);
                                 document.addBusPass.studentStop.options.add(objOption); 
                             }
                           
                          }
                      else{
                            if(type=="busRoute"){
                                objOption = new Option(j[c].stopName,j[c].busStopId);
                                document.addBusPass.studentStop.options.add(objOption); 
                             }
                          }
                     }
                     
                  }
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}
window.onload=function(){
    document.getElementById('busPassStatus').selectedIndex=1; 
}
*/

function getLabelClass(){
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetExternalTimeTableClass.php';
    
    document.searchForm.degree.length = null;    
    addOption(document.searchForm.degree, '', 'Select');   
    
    form = document.searchForm;
    var timeTable = form.labelId.value;
    
    if(timeTable=='') {
      return false;  
    }
    var pars = 'timeTableLabelId='+timeTable;
    
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.searchForm.degree.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(document.searchForm.degree, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.searchForm.degree, '', 'Select'); 
                }
                // now select the value                                     
                document.searchForm.degree.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

window.onload=function(){
  // getLabelClass();
   var roll = document.getElementById("sRollNo");
   autoSuggest(roll);
}

</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Icard/createBusPassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: createBusPass.php $
//
//*****************  Version 30  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 29  *****************
//User: Parveen      Date: 6/23/09    Time: 7:10p
//Updated in $/LeapCC/Interface
//function isDate2 added
//
//*****************  Version 28  *****************
//User: Parveen      Date: 6/23/09    Time: 7:02p
//Updated in $/LeapCC/Interface
//isDate function updated
//
//*****************  Version 27  *****************
//User: Parveen      Date: 6/23/09    Time: 6:42p
//Updated in $/LeapCC/Interface
//formatting & deactive code update
//
//*****************  Version 26  *****************
//User: Parveen      Date: 6/23/09    Time: 5:10p
//Updated in $/LeapCC/Interface
//code update edit functions
//
//*****************  Version 25  *****************
//User: Parveen      Date: 6/23/09    Time: 3:43p
//Updated in $/LeapCC/Interface
//issue fix code update (editBusPass function)
//
//*****************  Version 24  *****************
//User: Parveen      Date: 6/23/09    Time: 3:16p
//Updated in $/LeapCC/Interface
//code updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 6/23/09    Time: 10:38a
//Updated in $/LeapCC/Interface
//alert message comments
//
//*****************  Version 22  *****************
//User: Parveen      Date: 6/23/09    Time: 10:29a
//Updated in $/LeapCC/Interface
//date formatting settings
//
//*****************  Version 21  *****************
//User: Parveen      Date: 6/22/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//validation format, condition updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 6/22/09    Time: 4:13p
//Updated in $/LeapCC/Interface
//issue fix Format, validation added
//
//*****************  Version 19  *****************
//User: Parveen      Date: 6/22/09    Time: 2:38p
//Updated in $/LeapCC/Interface
//display table records spelling correct 
//
//*****************  Version 18  *****************
//User: Parveen      Date: 6/22/09    Time: 2:29p
//Updated in $/LeapCC/Interface
//formating, validations, messages, conditions  changes 
//
//*****************  Version 17  *****************
//User: Parveen      Date: 6/18/09    Time: 10:36a
//Updated in $/LeapCC/Interface
//messages updated 
//
//*****************  Version 16  *****************
//User: Parveen      Date: 6/16/09    Time: 3:10p
//Updated in $/LeapCC/Interface
//date format setting valid Upto
//
//*****************  Version 15  *****************
//User: Parveen      Date: 6/16/09    Time: 12:40p
//Updated in $/LeapCC/Interface
//busPass Status add disabled / edit enabled
//
//*****************  Version 14  *****************
//User: Parveen      Date: 6/16/09    Time: 11:27a
//Updated in $/LeapCC/Interface
//validation message update
//
//*****************  Version 13  *****************
//User: Parveen      Date: 6/16/09    Time: 11:18a
//Updated in $/LeapCC/Interface
//conditions, validation & formatting updated (bug fix)
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/15/09    Time: 6:23p
//Updated in $/LeapCC/Interface
//display message updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/15/09    Time: 5:35p
//Updated in $/LeapCC/Interface
//validation message update
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/15/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//display messages update
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/15/09    Time: 4:36p
//Updated in $/LeapCC/Interface
//issue fix (timetableLabelId remove)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/15/09    Time: 4:05p
//Updated in $/LeapCC/Interface
//addBusPass Head update (Add Bus Pass)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/15/09    Time: 3:41p
//Updated in $/LeapCC/Interface
//function, validations update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/15/09    Time: 3:23p
//Updated in $/LeapCC/Interface
//blankValues function update (busPassId blank)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/15/09    Time: 3:12p
//Updated in $/LeapCC/Interface
//save function update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/15/09    Time: 2:32p
//Updated in $/LeapCC/Interface
//addbuspass error fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/15/09    Time: 12:34p
//Updated in $/LeapCC/Interface
//validation, conditions & formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/13/09    Time: 3:17p
//Updated in $/LeapCC/Interface
//only edit functionality added (action1)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/13/09    Time: 2:53p
//Created in $/LeapCC/Interface
//initial checkin
//

?>
