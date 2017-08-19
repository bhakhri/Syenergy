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
define('MODULE','LeaveSetMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Leave Set Mapping </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('leaveSetName','Leave Set','width="20%"','',true) ,
    new Array('leaveTypeName','Leave Type','width="20%"','',true), 
    new Array('leaveValue','Leave Value','width="10%"','align="right"',true), 
    new Array('action','Action','width="5%"','align="center"',false)
  );
  

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveSetMapping/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddLeaveSetMapping';   
editFormName   = 'EditLeaveSetMapping';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteLeaveSetMapping';
divResultName  = 'results';
page=1; //default page
sortField = 'leaveSetName';
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
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    populateValues(id,dv,w,h);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    if(act=='Add') {
        addLeaveSetMapping();
        return false;
    }
    else if(act=='Edit') {
        editLeaveSetMapping();
        return false;
    }
}

//for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
      reCalculate();
      
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
    
var resourceAddCnt=0;
    // check browser
     var isMozilla = (document.all) ? 0 : 1;
//to add one row at the end of the list
    function addOneRow(cnt) {
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        document.getElementById('deleteFlag').value=true;
              
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
        
        createRows(resourceAddCnt,cnt);
    }

 
 var bgclass='';
    
//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt){
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
      cell1.name='srNo';
      cell2.setAttribute('align','left'); 
      cell3.setAttribute('align','left');
      cell4.setAttribute('align','center');
      
      
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
      
      var txt1=document.createElement('select');
      txt1.className="selectfield";
      //txt1.style.width="120px";
      txt1.setAttribute('id','leaveTypeId'+parseInt(start+i,10));
      txt1.setAttribute('name','leaveType');

      var txt2=document.createElement('input');
      var txt3=document.createElement('a');
      
      txt2.setAttribute('id','leaveTypeValue'+parseInt(start+i,10));
      txt2.setAttribute('name','leaveTypeValue[]');
      txt2.setAttribute('type','text');
      txt2.className='inputbox';
      txt2.setAttribute('style','width:80px;');
      txt2.setAttribute('maxlength','5');
      
      txt3.setAttribute('id','rd');
      txt3.className='htmlElement';  
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
      var len= document.getElementById('leaveTypeHidden').options.length;
      var t=document.getElementById('leaveTypeHidden');
      if(len>0) {
        var tt='leaveTypeId'+parseInt(start+i,10) ;
        eval('document.AddLeaveSetMapping.'+tt+'.length = null');
        for(k=0;k<len;k++) { 
            addOption(eval('document.AddLeaveSetMapping.'+tt), t.options[k].value,  t.options[k].text);
        }
     }
  } 
  tbl.appendChild(tbody); 
  reCalculate();  
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
  var a =document.getElementById('tableDiv').getElementsByTagName("td");
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

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//var divisor=0.5;
function addLeaveSetMapping() {
         var url = '<?php echo HTTP_LIB_PATH;?>/LeaveSetMapping/ajaxInitAdd.php';
         typeArray.splice(0,typeArray.length);
         var leaveTypeString='';
         var leaveTypeValueString='';
         var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
         var len=ele.length;
         if(len == 0){
           messageBox("<?php echo NO_DATA_SUBMIT;?>");
           return false;     
         }
         
         if(document.AddLeaveSetMapping.leaveSet.value==''){
             messageBox("<?php echo SELECT_LEAVE_SET;?>");
             document.AddLeaveSetMapping.leaveSet.focus();
             return false;
         }
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='SELECT-ONE' ){
                 if(trim(ele[i].value)==''){
                     messageBox("<?php echo SELECT_LEAVE_TYPE;?>");
                     ele[i].className='inputboxRed';
                     ele[i].focus();
                     return false;
                 }
                 if(!checkDuplicateValue(trim(ele[i].value))){
                    messageBox("<?php echo DUPLICATE_LEAVE_SET_TYPE;?>");
                    ele[i].className='inputboxRed';
                    ele[i].focus();
                    return false;
                 }
                 if(leaveTypeString!=''){
                     leaveTypeString +=',';
                 }
                 leaveTypeString += trim(ele[i].value); 
             }
         }

         var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");
         var len=ele.length;
         
         for(var i=0;i<len;i++){
             if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='leaveTypeValue[]'){
                 if(ele[i].value==''){
                     messageBox("<?php echo ENTER_LEAVE_TYPE_VALUE;?>");
                     ele[i].className='inputboxRed';  
                     ele[i].focus();
                     return false;
                 }
                if(!isDecimal(trim(ele[i].value))) {
                    messageBox("Please enter numeric values");
                    ele[i].className='inputboxRed';
                    ele[i].focus();
                    return false;
                }
				if(ele[i].value != (parseInt(ele[i].value))){
					messageBox("Leave value should be a whole number");
                    ele[i].className='inputboxRed';
					ele[i].focus();
                    return false;
				}
               /* if((trim(ele[i].value) % divisor)!=0) {
                    messageBox("Leave value must be multiple of "+divisor);
                    ele[i].focus();
                    return false;
                } */
                if(ele[i].value<0) {
                    messageBox("<?php echo LEAVE_TYPE_VALUE_GREATER_ZERO; ?>");
                    ele[i].className='inputboxRed';
                    ele[i].focus();
                    return false;
                }
                if(ele[i].value>366) {
                    messageBox("Leave value can not be greater than 366 days");
                    ele[i].className='inputboxRed';
                    ele[i].focus();
                    return false;
                }
                if(leaveTypeValueString!=''){
                     leaveTypeValueString +=',';
                 }
                 leaveTypeValueString +=ele[i].value; 
             }
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 leaveSessionId: document.getElementById('leaveSessionId').value,
                 leaveTypeString      :  leaveTypeString, 
                 leaveTypeValueString :  leaveTypeValueString,
                 leaveSet             :  document.AddLeaveSetMapping.leaveSet.value
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
                            hiddenFloatingDiv('AddLeaveSetMapping');
                            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
                            return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A optionLabel
//  id=degreeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteLeaveSetMapping(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         var url = '<?php echo HTTP_LIB_PATH;?>/LeaveSetMapping/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveSetMappingId: id},
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



//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddLeaveSetMapping" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function blankValues() {
   document.AddLeaveSetMapping.leaveSet.value = '';
   cleanUpTable();
   //addOneRow(1);
   document.AddLeaveSetMapping.leaveSet.focus();
   //alert(document.getElementById('AddLeaveSetMapping').style.width);
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editLeaveSetMapping() {
    if(document.EditLeaveSetMapping.leaveType.value==''){
         messageBox("Select leave type");
         document.EditLeaveSetMapping.leaveType.focus();
         return false;
     }
    if(trim(document.EditLeaveSetMapping.leaveTypeValue.value)==''){
         messageBox("<?php echo ENTER_LEAVE_TYPE_VALUE;?>");
         document.EditLeaveSetMapping.leaveTypeValue.focus();
         return false;
     }
    if(!isDecimal(document.EditLeaveSetMapping.leaveTypeValue.value)) {
        messageBox("Please enter decimal values");
        document.EditLeaveSetMapping.leaveTypeValue.focus();
        return false;
    }
	if(document.EditLeaveSetMapping.leaveTypeValue.value > (Math.floor(document.EditLeaveSetMapping.leaveTypeValue.value))){
		messageBox("Leave value should be a whole no");
		 document.EditLeaveSetMapping.leaveTypeValue.focus();
        return false;
	}
 /*   if((trim(document.EditLeaveSetMapping.leaveTypeValue.value) % divisor)!=0) {
        messageBox("Leave value must be multiple of "+divisor);
        document.EditLeaveSetMapping.leaveTypeValue.focus();
        return false;
    } */
    if(trim(document.EditLeaveSetMapping.leaveTypeValue.value)<0) {
        messageBox("<?php echo LEAVE_TYPE_VALUE_GREATER_ZERO; ?>");
        document.EditLeaveSetMapping.leaveTypeValue.focus();
        return false;
    }
    if(trim(document.EditLeaveSetMapping.leaveTypeValue.value)>366) {
        messageBox("Leave value can not be greater than 366 days");
        document.EditLeaveSetMapping.leaveTypeValue.focus();
        return false;
    }
    
         var url = '<?php echo HTTP_LIB_PATH;?>/LeaveSetMapping/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                 leaveSetMappingId : document.EditLeaveSetMapping.leaveSetMappingId.value,
                 leaveSet          : document.EditLeaveSetMapping.leaveSet.value, 
                 leaveType         : document.EditLeaveSetMapping.leaveType.value,
                 leaveTypeValue    : trim(document.EditLeaveSetMapping.leaveTypeValue.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditLeaveSetMapping');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
                         return false;
                     }
                    else if("<?php echo DUPLICATE_LEAVE_SET_TYPE;?>" == trim(transport.responseText)){
                         messageBox("<?php echo DUPLICATE_LEAVE_SET_TYPE ;?>"); 
                         document.EditLeaveSetMapping.leaveType.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText)); 
                        //document.EditLeaveSetMapping.leaveSet.focus();
                        hiddenFloatingDiv('EditLeaveSetMapping');
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveSetMapping" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         var url = '<?php echo HTTP_LIB_PATH;?>/LeaveSetMapping/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {leaveSetMappingId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        messageBox("<?php echo LEAVE_SET_MAPPING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                     }
                     else if(trim(transport.responseText)=="<?php echo LEAVE_SET_MAPPING_CAN_NOT_MOD_DEL; ?>"){
                       messageBox("<?php echo LEAVE_SET_MAPPING_CAN_NOT_MOD_DEL; ?>");
                     }
                    else{
                         displayWindow(dv,w,h); 
                         var j = eval('('+trim(transport.responseText)+')');
                         document.EditLeaveSetMapping.leaveSet.value          = j.leaveSetId;
                         document.EditLeaveSetMapping.leaveType.value         = j.leaveTypeId;
                         document.EditLeaveSetMapping.leaveTypeValue.value    = j.leaveValue;
                         document.EditLeaveSetMapping.leaveSetMappingId.value = j.leaveSetMappingId;
                         document.EditLeaveSetMapping.leaveSet.focus();
                    }     
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print FeedBack Grades report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/leaveSetMappingReportPrint.php?'+qstr;
    window.open(path,"LeaveSetMappingReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='leaveSetMappingReportCSV.php?'+qstr;
}


function showLeaveSetMapping() {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/LeaveSetMapping/ajaxGetLeaveSetMappingValues.php';  
   
   if(document.getElementById('leaveSet').value==""){
      return false;
   } 
   
   if(document.getElementById('leaveSessionId').value==""){
      messageBox("Please select one session can be active"); 
      return false;
   } 
   
   cleanUpTable(); 
 
   new Ajax.Request(url,
   {
         method:'post',
         asynchronous:false,  
         parameters: {leaveSessionId: document.getElementById('leaveSessionId').value,
                      leaveSet: document.getElementById('leaveSet').value
                     },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            j = eval('('+trim(transport.responseText)+')');   
            len=j.length;
            if(len>0) {
              for(i=0;i<len;i++) {
                addOneRow(1);
                varFirst=i+1;
                id = "leaveTypeId"+varFirst;
                eval("document.getElementById(id).value = j[i]['leaveTypeId']");    
                id = "leaveTypeValue"+varFirst;
                eval("document.getElementById(id).value = j[i]['leaveValue']");
              }
              reCalculate();     
           } 
       },
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/LeaveSetMapping/listLeaveSetMappingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    
    <SCRIPT LANGUAGE="JavaScript">
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    </SCRIPT>
</body>
</html>

<?php 
// $History: listLeaveSetMappingAdv.php $ 
?>