<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Bus/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false), 
    new Array('busName','Name','width="6%"','',true) , 
    new Array('busNo','Registration No.','width="8%"','',true), 
    new Array('modelNumber','Model','width="8%"','',true), 
    new Array('purchaseDate','Purchase Date','width="8%"','align="center"',true), 
    new Array('seatingCapacity','Capacity','width="5%"','align="right"',true), 
    new Array('yearOfManufacturing','Mfd. Year','width="5%"','align="right"',true), 
    new Array('isActive','In Service','width="5%"','align="center"',true),
    new Array('action','Action','width="3%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Bus/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBus';   
editFormName   = 'EditBusDiv';
winLayerWidth  = 720; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBus';
divResultName  = 'results';
page=1; //default page
sortField = 'busName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
      new Array("busName","<?php echo ENTER_BUS_NAME ?>"),
      new Array("busNo","<?php echo ENTER_BUS_NO ?>"),
      new Array("busModel","<?php echo ENTER_BUS_MODEL_NO ?>"),
      new Array("busCapacity","<?php echo ENTER_BUS_CAPACITY ?>"),
      new Array("manYear","<?php echo SELECT_MAN_YEAR ?>"),
      new Array("busInsCompany","<?php echo ENTER_INSURANCE_COMPANY ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='busInsCompany' ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='busName' ) {
                messageBox("<?php echo BUS_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='busNo' ) {
                messageBox("<?php echo BUS_NO_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='busModel' ) {
                messageBox("<?php echo BUS_MODEL_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           if(!isNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='busCapacity') {
                messageBox("Enter numeric value");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
          if(eval("frm."+(fieldsArray[i][0])+".value")<=0 && fieldsArray[i][0]=='busCapacity') {
                messageBox("<?php echo BUS_CAPACITY_RESTRICTION; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }  
          
          if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length>0 && fieldsArray[i][0]=='busInsCompany' ) {
            if(act=='Add'){
              if(document.getElementById('insuranceDate1').value==''){
                  messageBox("<?php echo SELECT_INSURANCE_DATE;?>");
                  document.getElementById('insuranceDate1').focus();
                  return false;
                  break;
              }
             if(!dateDifference(serverDate,document.getElementById('insuranceDate1').value,'-')){
                messageBox("<?php echo INSURANCE_DATE_VALIDATION;?>");
                document.getElementById('insuranceDate1').focus();
                return false;
                break; 
             }    
            }
            else if(act=='Edit'){
              if(document.getElementById('insuranceDate2').value==''){
                  messageBox("<?php echo SELECT_INSURANCE_DATE;?>");
                  document.getElementById('insuranceDate2').focus();
                  return false;
                  break;
              }
             if(!dateDifference(serverDate,document.getElementById('insuranceDate2').value,'-')){
                messageBox("<?php echo INSURANCE_DATE_VALIDATION;?>");
                document.getElementById('insuranceDate2').focus();
                return false;
                break; 
             }   
            }
          }
          else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length==0 && fieldsArray[i][0]=='busInsCompany' ){
              if(act=='Add'){
                document.getElementById('insuranceDate1').value='';  
                if(document.AddBus.insuranceReminder.checked){
                    messageBox("<?php echo INSURANCE_REMINDER_RESTRICTION; ?>");
                    document.AddBus.insuranceReminder.focus();
                    return false;
                }  
              }
              else if(act=='Edit'){
                  document.getElementById('insuranceDate2').value='';
                  if(document.EditBus.insuranceReminder.checked){
                    messageBox("<?php echo INSURANCE_REMINDER_RESTRICTION; ?>");
                    document.EditBus.insuranceReminder.focus();
                    return false;
                } 
              }
          }   
        }
     
    }
    
    if(act=='Add') {
     if(document.getElementById('purchaseDate1').value==''){
        messageBox("<?php echo BUS_PURCHASE_DATE_EMPTY;?>");
        document.getElementById('purchaseDate1').focus();
        return false;  
     }
     if(!dateDifference(document.getElementById('purchaseDate1').value,serverDate,'-')){
        messageBox("<?php echo BUS_PURCHASE_DATE_VALIDATION;?>");
        document.getElementById('purchaseDate1').focus();
        return false;
     }
     if(trim(document.AddBus.busPhoto.value)!=""){ 
         if(!checkAllowdExtensions(trim(document.AddBus.busPhoto.value))){
             document.AddBus.busPhoto.focus();
             messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
             return false;
         } 
     }    
        initAdd();
        addBus();
        //return false;
    }
    else if(act=='Edit') {
     if(document.getElementById('purchaseDate2').value==''){
        messageBox("<?php echo BUS_PURCHASE_DATE_EMPTY;?>");
        document.getElementById('purchaseDate2').focus();
        return false;  
     }
     if(!dateDifference(document.getElementById('purchaseDate2').value,serverDate,'-')){
        messageBox("<?php echo BUS_PURCHASE_DATE_VALIDATION;?>");
        document.getElementById('purchaseDate2').focus();
        return false;
     }
     if(trim(document.EditBus.busPhoto.value)!=""){ 
         if(!checkAllowdExtensions(trim(document.EditBus.busPhoto.value))){
             document.EditBus.busPhoto.focus();
             messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
             return false;
         } 
     }   
        initEdit();
        editBus();
        //return false;
    }
}

//--------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check a files extension before it is uploaded
//
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function checkAllowdExtensions(value){
  //get the extension of the file 
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="gif,bmp,jpg,jpeg,png";

  var extArr=str.split(",");
  var fl=0;
  var ln=extArr.length;
  
  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }

  if(fl){
   return true;
  }
 else{
  return false;
 }   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A BUS
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBus() {
         url = '<?php echo HTTP_LIB_PATH;?>/Bus/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 busName:          trim(document.AddBus.busName.value), 
                 busNo:            trim(document.AddBus.busNo.value), 
                 manYear:          (document.AddBus.manYear.value), 
                 isActive :        (document.AddBus.isActive[0].checked ? 1 : 0),
                 purchaseDate:     document.AddBus.purchaseDate1.value,
                 modelNo:          trim(document.AddBus.busModel.value),
                 seatingCapacity:  trim(document.AddBus.busCapacity.value),
                 insCompany:       trim(document.AddBus.busInsCompany.value),
                 insDueDate:       (document.AddBus.insuranceDate1.value),
                 insReminder:      (document.AddBus.insuranceReminder.checked ? 1 : 0)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddBusDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo BUS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_ALREADY_EXIST ;?>"); 
                       document.AddBus.busName.focus();
                     }
                     else if("<?php echo BUS_NO_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_NO_ALREADY_EXIST ;?>"); 
                       document.AddBus.busNo.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteBus(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Bus/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addBus" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddBus.reset();  
   document.AddBus.busName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editBus() {
         url = '<?php echo HTTP_LIB_PATH;?>/Bus/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              busId:            (document.EditBus.busId.value),
              busName:          trim(document.EditBus.busName.value), 
              busNo:            trim(document.EditBus.busNo.value), 
              manYear:          (document.EditBus.manYear.value), 
              isActive :        (document.EditBus.isActive[0].checked ? 1 : 0),
              purchaseDate:     document.EditBus.purchaseDate2.value,
              modelNo:          trim(document.EditBus.busModel.value),
              seatingCapacity:  trim(document.EditBus.busCapacity.value),
              insCompany:       trim(document.EditBus.busInsCompany.value),
              insDueDate:       (document.EditBus.insuranceDate2.value),
              insReminder:      (document.EditBus.insuranceReminder.checked ? 1 : 0)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBusDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                   else if("<?php echo BUS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_ALREADY_EXIST ;?>"); 
                       document.EditBus.busName.focus();
                   }
                  else if("<?php echo BUS_NO_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_NO_ALREADY_EXIST ;?>"); 
                       document.EditBus.busNo.focus();
                  }
                  else {
                        messageBox(trim(transport.responseText));                         
                    }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBus" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Bus/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBusDiv');
                        messageBox("<?php echo BUS_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }

                   j = eval('('+transport.responseText+')');
                   
                   document.EditBus.reset();
                   
                   document.EditBus.busName.value = j.busName;
                   document.EditBus.busNo.value = j.busNo;
                   document.EditBus.manYear.value = j.yearOfManufacturing;
                   
                   if(j.purchaseDate!='' && j.purchaseDate!='0000-00-00'){
                      document.EditBus.purchaseDate2.value=j.purchaseDate;
                   }
                   else{
                       document.EditBus.purchaseDate2.value='';
                   }
                   document.EditBus.busModel.value = j.modelNumber;
                   document.EditBus.busCapacity.value = j.seatingCapacity;
                   document.EditBus.busInsCompany.value = j.insuringCompany;
                   
                   d = new Date();
                   var rndNo = d.getTime();
                   if(j.busImage!=-1){
                       document.getElementById('busImageDiv').innerHTML='<img style="border:1px solid #8EBCD7" src="'+imagePathURL+'/Bus/'+j.busImage+'?'+rndNo+'" height="65px;" width="70px">';                   }
                   else{
                       document.getElementById('busImageDiv').innerHTML='';
                   }
                   
                   if(j.insuranceDueDate!='' &&  j.insuranceDueDate!='0000-00-00'){
                      document.EditBus.insuranceDate2.value=j.insuranceDueDate;
                   }
                   else{
                       document.EditBus.insuranceDate2.value='';
                   }
                   
                   document.EditBus.insuranceReminder.checked=(j.remindDueInsurance==1? true: false);
                   
                   if(j.isActive==1){
                     document.EditBus.isActive[0].checked=true;  
                   }
                   else if(j.isActive==0){
                     document.EditBus.isActive[1].checked=true;  
                   }
                   
                   document.EditBus.busId.value=j.busId;
                   
                   document.EditBus.busName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print bus stop report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/busReportPrint.php?'+qstr;
    window.open(path,"BusReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/busReportCSV.php?'+qstr;
	window.location = path;
}

function initAdd() {
    document.getElementById('AddBus').onsubmit=function() {
        document.getElementById('AddBus').target = 'uploadTargetAdd';
    }
}
//window.onload=initAdd;
function initEdit() {
    document.getElementById('EditBus').onsubmit=function() {
        document.getElementById('EditBus').target = 'uploadTargetEdit';
    }
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddBus;
 }
 else{
     var form = document.EditBus;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Bus/listBusContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: listBus.php $ 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/07/09   Time: 10:26
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000551,0000552
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:00
//Updated in $/LeapCC/Interface
//Copied bus master enhancements from leap to leapcc
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 15/06/09   Time: 11:09
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//Bug ids---00000070 to 0000073,0000075
//
//*****************  Version 4  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 5/05/09    Time: 12:07
//Updated in $/Leap/Source/Interface
//Corrected data population bug in bus master
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/05/09    Time: 12:11
//Updated in $/SnS/Interface
//Fixed bugs---943,944,945
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/04/09    Time: 16:36
//Updated in $/SnS/Interface
//Enhanced bus master module
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/02/09    Time: 19:12
//Created in $/SnS/Interface
//Created Bus Master Module
?>