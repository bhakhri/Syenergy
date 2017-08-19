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
define('MODULE','BusRepairCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/BusRepair/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: BusRepair Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('name','Staff','width="10%"','',true),
    new Array('busNo','Bus Registration No.','width="10%"','',true) , 
    new Array('dated','Dated','width="8%"','align="center"',true), 
    new Array('serviceFor','Service','width="10%"','',true), 
    new Array('cost','Cost','width="5%"','align="right"',true),
    new Array('workshopName','WorkShop','width="10%"','align="left"',true),
    new Array('billNumber','Bill No.','width="5%"','align="left"',true),
    new Array('action','Action','width="2%"','align="right"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusRepair/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusRepair';   
editFormName   = 'EditBusRepair';
winLayerWidth  = 670; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBusRepair';
divResultName  = 'results';
page=1; //default page
sortField = 'name';
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
      new Array("busId","<?php echo SELECT_BUS_NAME; ?>"),
      new Array("stuffId","<?php echo SELECT_STUFF; ?>"),
      new Array("serviceFor","<?php echo ENTER_SERVICE_REASON; ?>"),
      new Array("cost","<?php echo ENTER_SERVICE_COST; ?>"),
      new Array("workshopName","<?php echo ENTER_WORKSHOP_NAME; ?>"),
      new Array("billNumber","<?php echo ENTER_BILL_NUMBER; ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
          if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='serviceFor' ) {
                messageBox("<?php echo SERVICE_REASON_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
          if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='workshopName' ) {
                messageBox("<?php echo WORKSHOP_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
          if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='billNumber' ) {
                messageBox("<?php echo BILL_NUMBER_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
          if(fieldsArray[i][0]=="cost" && (!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value") < 0 )  )  {
            messageBox("<?php echo ENTER_COST_NUM; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
          } 
        }
     
    }
    
    if(act=='Add') {
      var chLen=document.AddBusRepair.busRepairTypeChk.length;
      var chF=0;
      var chStr1='';
      var chStr2='';
      for(var k=0;k<chLen;k++){
        if(document.AddBusRepair.busRepairTypeChk[k].checked){
            if(document.getElementById('busRepairTypeDueDate_Add'+(k+1)).value==''){
                messageBox("<?php echo SELECT_DUE_DATE; ?>");
                document.getElementById('busRepairTypeDueDate_Add'+(k+1)).focus();
                return false;
            }
            if(!dateDifference(serverDate,document.getElementById('busRepairTypeDueDate_Add'+(k+1)).value,'-')){
                messageBox("<?php echo DUE_DATE_VALIDATION; ?>");
                document.getElementById('busRepairTypeDueDate_Add'+(k+1)).focus();
                return false;
            }
            chF=1;
            
            if(chStr1!=''){
                chStr1 +=',';
                chStr2 +=',';
            }
            chStr1 +=document.AddBusRepair.busRepairTypeChk[k].value;
            chStr2 +=document.getElementById('busRepairTypeDueDate_Add'+(k+1)).value;
        }  
      }
      if(chF==0){
          messageBox("<?php echo SELECT_ATLEASTONE_CHECKBOX; ?>");
          document.AddBusRepair.busRepairTypeChk[0].focus();
          return false;
      }
      if(!dateDifference(document.getElementById('dated1').value,serverDate,'-')){
         messageBox("<?php echo SERVICE_DATE_VALIDATION; ?>");
         document.getElementById('dated1').focus();
         return false; 
      }  
        addBusRepair(chStr1,chStr2);
        return false;
    }
    else if(act=='Edit') {
      var chLen=document.EditBusRepair.busRepairTypeChk.length;
      var chF=0;
      var chStr1='';
      var chStr2='';
      
      for(var k=0;k<chLen;k++){
        if(document.EditBusRepair.busRepairTypeChk[k].checked){
            if(document.getElementById('busRepairTypeDueDate_Edit'+(k+1)).value==''){
                messageBox("<?php echo SELECT_DUE_DATE; ?>");
                document.getElementById('busRepairTypeDueDate_Edit'+(k+1)).focus();
                return false;
            }
            if(!dateDifference(serverDate,document.getElementById('busRepairTypeDueDate_Edit'+(k+1)).value,'-')){
                messageBox("<?php echo DUE_DATE_VALIDATION; ?>");
                document.getElementById('busRepairTypeDueDate_Edit'+(k+1)).focus();
                return false;
            }
            chF=1;
            
            if(chStr1!=''){
                chStr1 +=',';
                chStr2 +=',';
            }
            chStr1 +=document.EditBusRepair.busRepairTypeChk[k].value;
            chStr2 +=document.getElementById('busRepairTypeDueDate_Edit'+(k+1)).value;
        }  
      }
      if(chF==0){
          messageBox("<?php echo SELECT_ATLEASTONE_CHECKBOX; ?>");
          document.EditBusRepair.busRepairTypeChk[0].focus();
          return false;
      }
         
      if(!dateDifference(document.getElementById('dated2').value,serverDate,'-')){
         messageBox("<?php echo SERVICE_DATE_VALIDATION; ?>");
         document.getElementById('dated2').focus();
         return false; 
      }    
        editBusRepair(chStr1,chStr2);
        return false;
    }
}


//used to enter/blank date fields based on selection of "Action" checkboxes
function dateAdj(target,mode){
    if(mode){
        document.getElementById(target.id).value=serverDate;
    }
    else{
        document.getElementById(target.id).value='';
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
function addBusRepair(actionIds,dueDates) {
         url = '<?php echo HTTP_LIB_PATH;?>/BusRepair/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 busId: (document.AddBusRepair.busId.value),
                 stuffId: (document.AddBusRepair.stuffId.value),
                 serviceFor: trim(document.AddBusRepair.serviceFor.value),
                 dated: (document.AddBusRepair.dated1.value),
                 cost: trim(document.AddBusRepair.cost.value),
                 workShop: trim(document.AddBusRepair.workshopName.value),
                 billNumber: trim(document.AddBusRepair.billNumber.value),
                 comments: trim(document.AddBusRepair.comments.value),
                 actionIds :actionIds,
                 dueDates  : dueDates
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
                             hiddenFloatingDiv('AddBusRepair');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo BUS_REPAIR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_REPAIR_ALREADY_EXIST ;?>"); 
                       document.AddBusRepair.busId.focus();
                     }
                     else if("<?php echo BUS_REPAIR_BILL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_REPAIR_BILL_ALREADY_EXIST ;?>"); 
                       document.AddBusRepair.billNumber.focus();
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
function deleteBusRepair(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/BusRepair/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {repairId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addBusRepair" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddBusRepair.reset();  
   document.AddBusRepair.busId.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editBusRepair(actionIds,dueDates) {
         url = '<?php echo HTTP_LIB_PATH;?>/BusRepair/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              repairId:    (document.EditBusRepair.repairId.value),
              busId:       (document.EditBusRepair.busId.value),
              stuffId:     (document.EditBusRepair.stuffId.value),
              serviceFor:  trim(document.EditBusRepair.serviceFor.value),
              dated:       (document.EditBusRepair.dated2.value),
              cost:        trim(document.EditBusRepair.cost.value),
              workShop:    trim(document.EditBusRepair.workshopName.value),
              billNumber:  trim(document.EditBusRepair.billNumber.value),
              comments:    trim(document.EditBusRepair.comments.value),
              actionIds :actionIds,
              dueDates  : dueDates
              
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBusRepair');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                   else if("<?php echo BUS_REPAIR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_REPAIR_ALREADY_EXIST ;?>"); 
                       document.EditBusRepair.busId.focus();
                   }  
                   else if("<?php echo BUS_REPAIR_BILL_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BUS_REPAIR_BILL_ALREADY_EXIST ;?>"); 
                       document.EditBusRepair.billNumber.focus();
                   }
                   else {
                        messageBox(trim(transport.responseText));                         
                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusRepair" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/BusRepair/ajaxGetValues.php';
         document.EditBusRepair.reset();
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {repairId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBusRepair');
                        messageBox("<?php echo BUS_REPAIR_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var ret=trim(transport.responseText).split('~!~');
                   j = eval('('+ret[0]+')');
                   
                   document.EditBusRepair.busId.value = j.busId;
                   document.EditBusRepair.stuffId.value = j.stuffId;
                   document.EditBusRepair.serviceFor.value = j.serviceFor;
                   document.EditBusRepair.cost.value = j.cost;
                   document.EditBusRepair.workshopName.value = j.workshopName;
                   document.EditBusRepair.billNumber.value = j.billNumber;
                   document.EditBusRepair.comments.value = j.comments;
                   document.EditBusRepair.dated2.value = j.dated;
                   document.EditBusRepair.repairId.value=j.repairId;
                   
                   if(ret.length>1){
                      var k = eval('('+ret[1]+')');
                      var chLen=document.EditBusRepair.busRepairTypeChk.length;
                      for(var m=0;m<k.length;m++){
                          for(var n=0;n<chLen;n++){
                             if(document.EditBusRepair.busRepairTypeChk[n].value==k[m].actionId){
                                document.EditBusRepair.busRepairTypeChk[n].checked=true;
                                document.getElementById('busRepairTypeDueDate_Edit'+(n+1)).value=k[m].dueDate;
                             }            
                          }
                      }
                   }    
                   
                   
                   document.EditBusRepair.busId.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/BusRepair/listBusRepairContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: listBusRepair.php $ 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Interface
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 16/05/09   Time: 11:16
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//Bug ids : 1018 to 1024
//
//*****************  Version 4  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Interface
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Interface
//Updated fleet mgmt file in Leap 
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/04/09    Time: 15:15
//Updated in $/SnS/Interface
//Enhanced bus repair module by adding action (Engine Oil Change,Gear Box
//Oil Change etc) and their due dates
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/04/09    Time: 11:25
//Updated in $/SnS/Interface
//Enhanced bus repair module
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:24
//Updated in $/SnS/Interface
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:54
//Created in $/SnS/Interface
//Created Bus Repair Module
?>