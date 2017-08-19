<?php 
//-------------------------------------------------------
//  This File outputs the Reappear Student Report
// Author :Parveen Sharma
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassGradesheetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Class Gradesheet Master</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo',      '#',    'width="2%"','',false), 
			                   new Array('className', 'Class','width="20%"','',true) , 
			                   new Array('titleName', 'Title','width="20%"','align="left"',false),
                               new Array('showOrder', 'Display Order','width="18%"','align="left"',false),
                               new Array('internalMarks', 'Internal Pass Marks <br/>(%age)','width="20%"','align="left"',false),
                               new Array('externalMarks', 'External Pass Marks <br/>(%age)','width="20%"','align="left"',false)
                              );

recordsPerPage = 10000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ClassGradesheet/ajaxClassSessionGetValues.php';
searchFormName = 'frmReappearMapping'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 270; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = '';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';
qstr ='';
var dtArray=new Array();   

function getBatch() {
  
    var url = '<?php echo HTTP_LIB_PATH;?>/ClassGradesheet/ajaxGetBatch.php';
    
    var frm = document.frmReappearMapping;
    
    document.frmReappearMapping.degreeId.length = null;   
    document.frmReappearMapping.branchId.length = null;   
    addOption(frm.degreeId, "", "Select");
    addOption(frm.branchId, "", "Select");

    
    var pars = '';  
    
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
           var j = eval('(' + transport.responseText + ')');
           len = j.length;
           frm.batchId.length = null;
	   addOption(frm.batchId, "", "Select");
           for(var i=0;i<len;i++) { 
             addOption(frm.batchId, j[i].batchId, j[i].batchName);
           }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

	

function getDegree() {
   
    var frm = document.frmReappearMapping;
    document.frmReappearMapping.branchId.length = null;   
    addOption(frm.branchId, "", "Select");

  	batchId=document.frmReappearMapping.batchId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/ClassGradesheet/ajaxGetDegree.php';
  		//	document.frmReappearMapping.batchId.length = null;   
  		var frm = document.frmReappearMapping;
		var pars = 'batchId='+batchId;  
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
           	var j1 = eval('(' + transport.responseText + ')');
          	 len = j1.length;
		
           	frm.degreeId.length = null;
		addOption(frm.degreeId, "", "Select");
          	 for(var i=0;i<len;i++) { 
           	  addOption(frm.degreeId, j1[i].degreeId, j1[i].degreeName);
          	 }
       	 },
     	   onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}



function getBranch() {
  	
   	 var url = '<?php echo HTTP_LIB_PATH;?>/ClassGradesheet/ajaxGetBranch.php';
   
   	 var frm = document.frmReappearMapping;
    
          	batchId=document.frmReappearMapping.batchId.value;
		degreeId=document.frmReappearMapping.degreeId.value;
	
	var pars = 'batchId='+batchId+'&degreeId='+degreeId;  
    
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
           var j = eval('(' + transport.responseText + ')');
           len = j.length;
           frm.branchId.length = null;
  	   addOption(frm.branchId, "", "Select");
           for(var i=0;i<len;i++) { 
             addOption(frm.branchId, j[i].branchId, j[i].branchName);
           }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function validateAddForm() {
    
    hideResults();
    qstr ='';
	if(document.frmReappearMapping.batchId.value=='') {
  	  messageBox ("Select Batch Value");
	  document.frmReappearMapping.batchId.focus();
	  return false;
    } 
	
	if(document.frmReappearMapping.degreeId.value=='') {
  	  messageBox ("Select Degree Value");
	  document.frmReappearMapping.degreeId.focus();
	  return false;
    } 
	if(document.frmReappearMapping.branchId.value=='') {
  	  messageBox ("Select Branch Value");
	  document.frmReappearMapping.branchId.focus();
	  return false;
    } 
                                                              
    qstr = generateQueryString('frmReappearMapping');    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}


function checkDuplicate(value) {
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

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function insertValue() {

   dtArray.splice(0,dtArray.length); //empty the array  
   
   var url = '<?php echo HTTP_LIB_PATH;?>/ClassGradesheet/ajaxUpdateClassesAdd.php';
   
   var formx = document.frmReappearMapping;
   var obj=formx.getElementsByTagName('INPUT');
   var total=obj.length;
   for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('chb1[]')>-1) {
        // blank value check 
        id =obj[i].value;
        if(trim(eval("document.getElementById('chbOrder_"+id+"').value"))!='') {
           // Integer Value Checks updated
           eval("document.getElementById('chbOrder_"+id+"').className='inputbox'"); 
           var nn = trim(eval("document.getElementById('chbOrder_"+id+"').value"));
           if(!isNumericCustom(nn)) {                          
             messageBox ("Enter numeric value");
             eval("document.getElementById('chbOrder_"+id+"').className='inputboxRed'"); 
             eval("document.getElementById('chbOrder_"+id+"').focus()");  
             return false;
           }
           if(checkDuplicate(nn)==0){
             messageBox ("This column value already assign");  
             eval("document.getElementById('chbOrder_"+id+"').className='inputboxRed'"); 
             eval("document.getElementById('chbOrder_"+id+"').focus()");   
             return false;
           }
        }
      }
   }
   
   					
   new Ajax.Request(url,
   {
	method:'post',
	parameters: $('frmReappearMapping').serialize(true),
	onCreate: function(){
	showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true); 
	if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) ) { 
          messageBox(trim(transport.responseText)); 
          sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
          return false;
        }
        else {
           messageBox(trim(transport.responseText)); 
	   return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function printReport() {
	qstr +="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/listClassGradeMasterPrint.php?'+qstr;
    window.open(path,"DisplayReAppearLabelReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
	//var labelId=  document.frmReappearMapping.labelId.value;
    qstr +="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listClassGradeMasterCSV.php?'+qstr;
	window.location = path;
}

window.onload=function(){     
   getBatch(); 
}

</script>

</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/ClassGradesheet/listClassSessionTitleContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
