<?php
//-----------------------------------------------------------------------------
//  To generate Employee ICard functionality
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeBusPass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=utf-8" />
<title><?php echo SITE_NAME;?>: Create Employee Bus Pass </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

/*
var tableHeadArray = new Array( new Array('srNo','#','width="2%"','',false),
                                new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false),
                                new Array('employeeName','Name','width=15% align="left"','align="left"',true),
                                new Array('employeeCode','Emp. Code','width="10%" align="left"','align="left"',true),
                                new Array('designationName','Designation','width="15%" align="left"','align="left"',true),
                                new Array('contactNumber','Contact No.','width="12%"','align=left',true),
                                new Array('permAddress','Address','width="20%" align="left"','align="left"',false));
*/

var tableHeadArray = new Array(new Array('srNo','#','width="1%" ','align="left"',false),
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%" valign=\"middle\"','valign=\"middle\" align=\"left\"',false),
                               new Array('employeeName','Name','width=12% align="left"','align="left"',true),
                               new Array('employeeCode','Emp. Code','width="12%" align="left"','align="left"',true),
                               new Array('designationName','Designation','width="12%" align="left"','align="left"',true),
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false),
                               new Array('busName','Bus Route','width="18%"','',false),
                               new Array('busNo','Bus No.','width="18%"','',false),
                               new Array('stoppage','Bus Stoppage','width="18%"','',false),
                               //new Array('receiptNo','Receipt No.','width="12%"','',false),
                               new Array('validUpto','Valid Upto<br><span style="color:#FF0000; font-size:8px; font-weight:bold; font-family:Verdana">(DD-MM-YY)</span>','width="12%"','align="center"',false),
                               new Array('action1','Action','width="8%"','align="center"',false)  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitEmployeeBusPassList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 750; //  add/edit form width
winLayerHeight = 595; // add/edit form height
deleteFunction = 'return deleteEmployee';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'employeeName';
sortOrderBy    = 'ASC';

queryString ='';
myQueryString='';
employeeCheck='';

var dtArrayRoute=new Array(); 
var dtArrayBus=new Array(); 

function validateAddForm() {
    //code checks whether atleast one checkbox is selected or not
    queryString = '';
    form = document.allDetailsForm;
    
    dtArrayRoute.splice(0,dtArrayRoute.length); //empty the array   
    dtArrayBus.splice(0,dtArrayBus.length); //empty the array   

    if(!isEmpty(document.getElementById('birthYearF').value) || !isEmpty(document.getElementById('birthMonthF').value) || !isEmpty(document.getElementById('birthDateF').value)){

        if(isEmpty(document.getElementById('birthYearF').value)){

           messageBox("Please select date of birth year");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthF').value)){

           messageBox("Please select date of birth month");
           document.allDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateF').value)){

           messageBox("Please select date of birth date");
           document.allDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearT').value) || !isEmpty(document.getElementById('birthMonthT').value) || !isEmpty(document.getElementById('birthDateT').value)){

        if(isEmpty(document.getElementById('birthYearT').value)){

           messageBox("Please select date of birth year");
           document.allDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthT').value)){

           messageBox("Please select date of birth month");
           document.allDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateT').value)){

           messageBox("Please select date of birth date");
           document.allDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){

        dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

        dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){

        dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

        dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

/*  //from date of birth
    birthDateFF = form.birthYearF.value+'-'+form.birthMonthF.value+'-'+form.birthDateF.value;
    queryString += '&birthDateF='+birthDateFF;


    //to date of birth
    $birthDateTT = form.birthYearT.value+'-'+form.birthMonthT.value+'-'+form.birthDateT.value;
    queryString += '&birthDateT='+$birthDateTT;
*/

    // Joining Date
    if(!isEmpty(document.getElementById('joiningYearF').value) || !isEmpty(document.getElementById('joiningMonthF').value) || !isEmpty(document.getElementById('joiningDateF').value)){

        if(isEmpty(document.getElementById('joiningYearF').value)){

           messageBox("Please select date of joining year");
           document.allDetailsForm.joiningYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningMonthF').value)){

           messageBox("Please select date of joining month");
           document.allDetailsForm.joiningMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningDateF').value)){

           messageBox("Please select date of joining date");
           document.allDetailsForm.joiningDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('joiningYearT').value) || !isEmpty(document.getElementById('joiningMonthT').value) || !isEmpty(document.getElementById('joiningDateT').value)){

        if(isEmpty(document.getElementById('joiningYearT').value)){

           messageBox("Please select date of joining year");
           document.allDetailsForm.joiningYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningMonthT').value)){

           messageBox("Please select date of joining month");
           document.allDetailsForm.joiningMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningDateT').value)){

           messageBox("Please select date of joining date");
           document.allDetailsForm.joiningDateT.focus();
           return false;
        }
    }


    if(!isEmpty(document.getElementById('joiningYearF').value) && !isEmpty(document.getElementById('joiningMonthF').value) && !isEmpty(document.getElementById('joiningDateF').value) && !isEmpty(document.getElementById('joiningYearT').value) && !isEmpty(document.getElementById('joiningMonthT').value) && !isEmpty(document.getElementById('joiningDateT').value)){

        dobFValue = document.getElementById('joiningYearF').value+"-"+document.getElementById('joiningMonthF').value+"-"+document.getElementById('joiningDateF').value

        dobTValue = document.getElementById('joiningYearT').value+"-"+document.getElementById('joiningMonthT').value+"-"+document.getElementById('joiningDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsForm.joiningYearF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('joiningYearF').value) && !isEmpty(document.getElementById('joiningMonthF').value) && !isEmpty(document.getElementById('joiningDateF').value) && !isEmpty(document.getElementById('joiningYearT').value) && !isEmpty(document.getElementById('joiningMonthT').value) && !isEmpty(document.getElementById('joiningDateT').value)){

        dobFValue = document.getElementById('joiningYearF').value+"-"+document.getElementById('joiningMonthF').value+"-"+document.getElementById('joiningDateF').value

        dobTValue = document.getElementById('joiningYearT').value+"-"+document.getElementById('joiningMonthT').value+"-"+document.getElementById('joiningDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsForm.joiningYearF.focus();
           return false;
        }
    }

/*  //from date of joining
    joiningDateFF = form.joiningYearF.value+'-'+form.joiningDateF.value+'-'+form.joiningMonthF.value;
    queryString += '&joiningDateF='+joiningDateFF;

    //to date of joining
    joiningDateTT = form.joiningYearT.value+'-'+form.joiningDateT.value+'-'+form.joiningMonthT.value;
    queryString += '&joiningDateT='+joiningDateTT;
*/


    // Leaving Date

    if(!isEmpty(document.getElementById('leavingYearF').value) || !isEmpty(document.getElementById('leavingMonthF').value) || !isEmpty(document.getElementById('leavingDateF').value)){

        if(isEmpty(document.getElementById('leavingYearF').value)){
           messageBox("Please select date of leaving year");
           document.allDetailsForm.leavingYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingMonthF').value)){

           messageBox("Please select date of leaving month");
           document.allDetailsForm.leavingMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingDateF').value)){

           messageBox("Please select date of leaving date");
           document.allDetailsForm.leavingDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('leavingYearT').value) || !isEmpty(document.getElementById('leavingMonthT').value) || !isEmpty(document.getElementById('leavingDateT').value)){

        if(isEmpty(document.getElementById('leavingYearT').value)){

           messageBox("Please select date of leaving year");
           document.allDetailsForm.leavingYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingMonthT').value)){

           messageBox("Please select date of leaving month");
           document.allDetailsForm.leavingMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingDateT').value)){

           messageBox("Please select date of leaving date");
           document.allDetailsForm.leavingDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('leavingYearF').value) && !isEmpty(document.getElementById('leavingMonthF').value) && !isEmpty(document.getElementById('leavingDateF').value) && !isEmpty(document.getElementById('leavingYearT').value) && !isEmpty(document.getElementById('leavingMonthT').value) && !isEmpty(document.getElementById('leavingDateT').value)){

        dobFValue = document.getElementById('leavingYearF').value+"-"+document.getElementById('leavingMonthF').value+"-"+document.getElementById('leavingDateF').value

        dobTValue = document.getElementById('leavingYearT').value+"-"+document.getElementById('leavingMonthT').value+"-"+document.getElementById('leavingDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsForm.leavingYearF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('leavingYearF').value) && !isEmpty(document.getElementById('leavingMonthF').value) && !isEmpty(document.getElementById('leavingDateF').value) && !isEmpty(document.getElementById('leavingYearT').value) && !isEmpty(document.getElementById('leavingMonthT').value) && !isEmpty(document.getElementById('leavingDateT').value)){

        dobFValue = document.getElementById('leavingYearF').value+"-"+document.getElementById('leavingMonthF').value+"-"+document.getElementById('leavingDateF').value

        dobTValue = document.getElementById('leavingYearT').value+"-"+document.getElementById('leavingMonthT').value+"-"+document.getElementById('leavingDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsForm.leavingYearF.focus();
           return false;
        }
    }

    sendReq(listURL,divResultName,searchFormName, queryString,false);

    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    doAll();

    return false;
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

function getUpdateRoute(chk) {

    if(chk=='Route') {
        var len= dtArrayRoute.length;
        var fl=1;
        for(var k=0;k<len;k++){
          var ret=dtArrayRoute[k].split('~');
          var totalRoute=ret[1];
          var val = ret[0];
          
          var formx = document.allDetailsForm;
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
          var formx = document.allDetailsForm;
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


function doAll(){

   var formx = document.allDetailsForm;
   if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
              formx.elements[i].checked=true;
              id=formx.elements[i].value;
              route = "eEmployeeId"+id;
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
                 
                 /*
                 route = "receiptNo"+id;
                 document.getElementById(route).disabled=false;
                 document.getElementById(route).value="";
                 */

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
              route = "eEmployeeId"+id;
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

                 /*
                 route = "receiptNo"+id;
                 document.getElementById(route).disabled=true;
                 document.getElementById(route).value="";

                 */

                 route = "validUpto"+id;
                 document.getElementById(route).disabled=true;
                 document.getElementById(route).value="";
             }
           }
        }  // end for loop
    }
}

function validateAddForm1(frm,act) {

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

   //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false);
   sendReq(listURL,divResultName,searchFormName, queryString,false);
   doAll();
}



function addBusPass(act) {

    var selected=0;
    employeeCheck='';
    var formx = document.allDetailsForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(employeeCheck=='') {
                   employeeCheck=formx.elements[i].value;
                }
                else {
                    employeeCheck = employeeCheck + ',' +formx.elements[i].value;
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
              route = "eEmployeeId"+id;
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

                   /*
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
                  */

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

    /*
       // Duplicate Receipt No. Validation Checks
       for(i=1;i<formx.length;i++) {
         if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && (formx.elements[i].checked) ){
              id=formx.elements[i].value;
              route = "eEmployeeId"+id;
              if(document.getElementById(route).value=='Y' ) {
                route= "receiptNo"+formx.elements[i].value;
                routeValue = document.getElementById(route).value;
                for(var j=(i+1);j<formx.length;j++) {
                  if(formx.elements[j].type=="checkbox" && formx.elements[j].name=="chb[]" && (formx.elements[j].checked) ){
                      id=formx.elements[j].value;
                      route = "eEmployeeId"+id;
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
    */

       url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitEmployeeBusAdd.php';
       var pars = generateQueryString('allDetailsForm');
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
                         sendReq(listURL,divResultName,searchFormName, queryString,false);
                         //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false);
                         doAll();
                         return false;
                     }
                     else {
                         sendReq(listURL,divResultName,searchFormName, queryString,false);
                         //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+document.getElementById('degree').value+'&sRollNo='+trim(document.getElementById('sRollNo').value)+'&sName='+trim(document.getElementById('sName').value),false);
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


function editBusPass(employeeId,busPassId) {

         url = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitEmployeeBusEdit.php';

         if(false===confirm("<?php echo CANCEL_BUSPASS;?>")) {
            return false;
         }

         new Ajax.Request(url,
             {
                  method:'post',
                  parameters:{ busPassId : busPassId,
                               employeeId : employeeId
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
                             sendReq(listURL,divResultName,searchFormName, queryString,false);  
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


// function to print Bus Pass report
function printReport() {

    var selected=0;
    employeeCheck='';

    var formx = document.allDetailsForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(employeeCheck=='') {
                   employeeCheck=formx.elements[i].value;
                }
                else {
                    employeeCheck = employeeCheck + ',' +formx.elements[i].value;
                }
                selected++;
            }
        }
    }
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    form = document.allDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/employeeBusPrint.php?employee='+employeeCheck+'&cardView=1&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"BusPassPrint","status=1,menubar=1,scrollbars=1, width=900");
}


function checkDisable(id) {
   if(eval("document.getElementById('eEmployeeId"+(id)+"').value=='Y'")) {
       if(eval("document.getElementById('chb"+(id)+"').checked==true")) {
          eval("document.getElementById('busRoute"+(id)+"').disabled = false");
          eval("document.getElementById('busStop"+(id)+"').disabled = false");
          //eval("document.getElementById('receiptNo"+(id)+"').disabled = false");
          eval("document.getElementById('validUpto"+(id)+"').disabled = false");
          eval("document.getElementById('busNo"+(id)+"').disabled = false"); 
       }
       else {
          eval("document.getElementById('busRoute"+(id)+"').disabled= true");
          eval("document.getElementById('busStop"+(id)+"').disabled= true");
          //eval("document.getElementById('receiptNo"+(id)+"').disabled= true");
          eval("document.getElementById('validUpto"+(id)+"').disabled= true");
          eval("document.getElementById('busNo"+(id)+"').disabled = true"); 

          eval("document.getElementById('busNo"+(id)+"').selectedIndex=0 ");
          eval("document.getElementById('busRoute"+(id)+"').selectedIndex=0 ");
          eval("document.getElementById('busStop"+(id)+"').selectedIndex=0 ");
          //eval("document.getElementById('receiptNo"+(id)+"').value='' ");
          eval("document.getElementById('validUpto"+(id)+"').value='' ");
       }
   }
}

</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Icard/listEmployeeBusPassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//$History: employeeIcard.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/22/09   Time: 6:20p
//Updated in $/LeapCC/Interface
//date format & alignement updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/15/09   Time: 1:05p
//Updated in $/LeapCC/Interface
//escape function use
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/14/09   Time: 3:38p
//Updated in $/LeapCC/Interface
//validate format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/01/09   Time: 3:26p
//Updated in $/LeapCC/Interface
//icard title added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:08p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Interface
//issue fix format & conditions & alignment updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/11/09    Time: 5:24p
//Updated in $/LeapCC/Interface
//validations functions Formatting updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/23/09    Time: 2:48p
//Updated in $/LeapCC/Interface
//formatting update (admitCard, Buspass, Icard)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/12/09    Time: 4:40p
//Updated in $/LeapCC/Interface
//inital checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/12/09    Time: 3:45p
//Created in $/LeapCC/Interface
//icard file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Interface
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 3:43p
//Created in $/Leap/Source/Interface
//initial checkin
//

?>
