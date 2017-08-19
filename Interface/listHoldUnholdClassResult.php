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
define('MODULE','HoldUnholdClassResult');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hold/Unhold Class Result</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo',      '#',    'width="4%"','',false), 
                               new Array('className', 'Class','width="24%"','',true) , 
                               new Array('attendance', 'Attendance<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll(2);\">','width="14%"','align="center"',false),
                               new Array('marks', 'Test Marks<input type=\"checkbox\" id=\"checkbox3\" name=\"checkbox3\" onclick=\"doAll(3);\">','width="14%"','align="center"',false),
                               new Array('finalResult', 'Final Result<input type=\"checkbox\" id=\"checkbox4\" name=\"checkbox4\" onclick=\"doAll(4);\">','width="14%"','align="center"',false),
                               new Array('grades', 'Grades<input type=\"checkbox\" id=\"checkbox5\" name=\"checkbox5\" onclick=\"doAll(5);\">','width="14%"','align="center"',false),
                              new Array('individualStudent', 'Individual Student','width="16%"','align="center"',false)
                              );

recordsPerPage = 10000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxClassSessionGetValues.php';
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
 

function listIndividualStudentWindow(hiddenClassId, id,className) {
     //return false;
     dv = 'divIndividualStudent';
     w=810;
     h=450; 
     
     document.getElementById('holdUnholdClassName').innerHTML = className;
     document.getElementById('holdUnholdClassId').value = hiddenClassId;
     
     url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxInitIndividualStudentList.php';  
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {classId: id,
                      hiddenClassId: hiddenClassId
                     },
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         classId = id;
         j = trim(transport.responseText);
         document.getElementById('resultInfo').innerHTML= j;    
         displayWindow(dv,w,h);
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}


function getBatch() {
  
    var url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxGetBatch.php';
    
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

function getClasses() {
  
    var url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxGetClass.php';
    
    var frm = document.frmReappearMapping;
   	var frm1 = document.frmHoldStudent;
    frm1.classId.length = null;   
    addOption(frm1.classId, "", "Select");

  	batchId=document.frmReappearMapping.batchId.value;
	degreeId=document.frmReappearMapping.degreeId.value;
	branchId=document.frmReappearMapping.branchId.value;
    
   var pars = 'batchId='+batchId+'&degreeId='+degreeId+'&branchId='+branchId;  
    
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
           frm1.classId.length = null;
	       addOption(frm1.classId, "", "Select");
           for(var i=0;i<len;i++) { 
             addOption(frm1.classId, j[i].classId, j[i].className);
           }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

	

function getDegree() {

    var url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxGetDegree.php';
    var frm = document.frmReappearMapping;
   
    document.frmReappearMapping.branchId.length = null;   
    addOption(frm.branchId, "", "Select");

  	batchId=document.frmReappearMapping.batchId.value;
    
    if(batchId=='') {
      batchId='0';  
    }
     
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
           	  addOption(frm.degreeId, j1[i].degreeId, j1[i].degreeAbbr);
          	 }
       	 },
     	   onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}



function getBranch() {
  	
   	 var url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxGetBranch.php';
   
   	 var frm = document.frmReappearMapping;
     batchId=document.frmReappearMapping.batchId.value;
     degreeId=document.frmReappearMapping.degreeId.value;
     
     if(batchId=='') {
       batchId='0';  
     }
     
     if(degreeId=='') {
       degreeId='0';  
     }
	
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
             addOption(frm.branchId, j[i].branchId, j[i].branchCode);
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


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function insertValue() {
   
   formx = document.frmReappearMapping;   
    
   var url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxUpdateClassesAdd.php';
   
   unHoldId='';
   holdId='';
   var formx = document.frmReappearMapping;
   var obj=formx.getElementsByTagName('INPUT');
   var total=obj.length;
   for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('chbclassId[]')>-1) {
         // blank value check 
         id =obj[i].value;
         chk1='0';
         chk2='0';
         chk3='0';
         chk4='0';
         if(eval("formx.chbattendance_"+id+".checked"))  {   
           chk1='1';  
         }
         if(eval("formx.chbmarks_"+id+".checked"))  {   
           chk2='1';  
         }
         if(eval("formx.chbfinalResult_"+id+".checked"))  {   
           chk3='1';  
         }
         if(eval("formx.chbgrades_"+id+".checked"))  {   
           chk4='1';  
         }
         if(chk1!='0' || chk2!='0' || chk3!='0' || chk4!='0') {
           if(holdId!='') {
             holdId += ","; 
           } 
           holdId +=id+"~"+chk1+"~"+chk2+"~"+chk3+"~"+chk4;
         } 
         else {
           if(unHoldId!='') {
             unHoldId += ","; 
           }  
           unHoldId +=id;
         }
      }
   }
     
   new Ajax.Request(url,
   {
	method:'post',
    asynchronous:false,  
	parameters: { batchId :  (document.frmReappearMapping.batchId.value), 
                  degreeId: (document.frmReappearMapping.degreeId.value), 
                  branchId: (document.frmReappearMapping.branchId.value),
                  holdId : holdId,
                  unHoldId : unHoldId
                },
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
	path='<?php echo UI_HTTP_PATH;?>/listHoldUnholdClassPrint.php?'+qstr;
    window.open(path,"DisplayReAppearLabelReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
	//var labelId=  document.frmReappearMapping.labelId.value;
    qstr +="&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listHoldUnholdClassCSV.php?'+qstr;
	window.location = path;
}

window.onload=function(){     
   getBatch(); 
}

function doAll(val){

    formx = document.frmReappearMapping;
    
    if(val==2) {
      id='chbattendance[]';  
      id1='checkbox2';  
      id11='spanAtt';
    }
    else if(val==3) {
      id='chbmarks[]';  
      id1='checkbox3';  
      id11='spanMarks';
    }
    else if(val==4) {
      id='chbfinalResult[]';  
      id1='checkbox4';  
      id11='spanFinal';
    }
    else if(val==5) {
      id='chbgrades[]';  
      id1='checkbox5';  
      id11='spanGrade';
    }
    
    if(eval("formx."+id1+".checked")){
      for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name==id){
            formx.elements[i].checked=true;
            str = id11+formx.elements[i].value; 
            eval("document.getElementById('"+str+"').innerHTML='Held'");
        }
      }
    }
    else{
        for(var i=1;i<formx.length;i++){
          if(formx.elements[i].type=="checkbox" && formx.elements[i].name==id){
            formx.elements[i].checked=false;
            str = id11+formx.elements[i].value; 
            eval("document.getElementById('"+str+"').innerHTML='Unheld'");
          }
        }
    }

}

function addStudents() {
   
   formx = document.frmHoldStudent;
    
   var url = '<?php echo HTTP_LIB_PATH;?>/HoldUnholdClass/ajaxStudentAdd.php';
   
   holdId='';
   var formx = document.frmHoldStudent;
   var obj=formx.getElementsByTagName('INPUT');
   var total=obj.length;
   for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('chbstudentId[]')>-1) {
         // blank value check 
         id =obj[i].value;
         chk1='0';
         chk2='0';
         chk3='0';
         chk4='0';
	     isClass='0';
         find='';
         if(eval("formx.chbattendance1_"+id+".checked"))  {   
           chk1='1';  
           find='1';
         }
         if(eval("formx.chbmarks1_"+id+".checked"))  {   
           chk2='1';
           find='1';  
         }
         if(eval("formx.chbfinalResult1_"+id+".checked"))  {   
           chk3='1';
           find='1';  
         }
         if(eval("formx.chbgrades1_"+id+".checked"))  {   
           chk4='1';
           find='1';  
         }
	   
         if(find=='1') {
           if(holdId!='') {
             holdId += ","; 
           } 
           holdId +=id+"~"+chk1+"~"+chk2+"~"+chk3+"~"+chk4;
         } 
      }
   }
     
   new Ajax.Request(url,
   {
	method:'post',
    asynchronous:false,  
	parameters: { classId: (document.frmHoldStudent.holdUnholdClassId.value), 
                  holdId : holdId,
                },
	onCreate: function(){
	showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true); 
	    if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) ) { 
          messageBox(trim(transport.responseText)); 
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


function doAllStudentCheck(val){

    formy = document.frmHoldStudent;
    if(val==2) {
      id='chbattendance1[]';  
      id1='checkbox2';  
      id11='spanAtt1';
    }
    else if(val==3) {
      id='chbmarks1[]';  
      id1='checkbox3';  
      id11='spanMarks1';
    }
    else if(val==4) {
      id='chbfinalResult1[]';  
      id1='checkbox4';  
      id11='spanFinal1';
    }
    else if(val==5) {
      id='chbgrades1[]';  
      id1='checkbox5';  
      id11='spanGrade1';
    }
    else if(val==6) {
      id='chbAllClass1[]';  
      id1='checkbox6';  
      id11='spanAllClass';
    }
    
    	
	if(eval("formy."+id1+".checked")){
      for(var i=1;i<formy.length;i++){
        if(formy.elements[i].type=="checkbox" && formy.elements[i].name==id){
            formy.elements[i].checked=true;
            str = id11+formy.elements[i].value; 
            eval("document.getElementById('"+str+"').innerHTML='Held'");
        }
      }
    }
    else{
        for(var i=1;i<formy.length;i++){
          if(formy.elements[i].type=="checkbox" && formy.elements[i].name==id){
            formy.elements[i].checked=false;
            str = id11+formy.elements[i].value; 
            eval("document.getElementById('"+str+"').innerHTML='Unheld'");
          }
        }
    }
}


function getHold(classId,mod) {
    
   if(mod=='A') {
     str = "spanAtt"+classId;    
     if(eval("document.getElementById('chbattendance_"+classId+"').checked")) { 
       eval("document.getElementById('"+str+"').innerHTML='Held'"); 
     }
     else {  
       eval("document.getElementById('"+str+"').innerHTML='Unheld'"); 
     }
   }

  
   if(mod=='M') {
     str = "spanMarks"+classId;    
     if(eval("document.getElementById('chbmarks_"+classId+"').checked")) { 
       eval("document.getElementById('"+str+"').innerHTML='Held'"); 
     }
     else {  
       eval("document.getElementById('"+str+"').innerHTML='Unheld'"); 
     }
   }

  
   if(mod=='F') {
     str = "spanFinal"+classId;    
     if(eval("document.getElementById('chbfinalResult_"+classId+"').checked")) { 
       eval("document.getElementById('"+str+"').innerHTML='Held'"); 
     }
     else {  
       eval("document.getElementById('"+str+"').innerHTML='Unheld'"); 
     }
   }

	  
   if(mod=='G') {
     str = "spanGrade"+classId;    
     if(eval("document.getElementById('chbgrades_"+classId+"').checked")) { 
       eval("document.getElementById('"+str+"').innerHTML='Held'"); 
     }
     else {  
       eval("document.getElementById('"+str+"').innerHTML='Unheld'"); 
     }
   }
   
   if(mod=='G') {
     str = "spanGrade"+classId;    
     if(eval("document.getElementById('chbgrades_"+classId+"').checked")) { 
       eval("document.getElementById('"+str+"').innerHTML='Held'"); 
     }
     else {  
       eval("document.getElementById('"+str+"').innerHTML='Unheld'"); 
     }
   }
   
   
}

function getHoldStudents(studentId,mod) {
   
     var rval=mod.split('~');
     str = rval[1]+studentId;
     str1 = rval[0]+'_'+studentId;   
     
     if(eval("document.getElementById('"+str1+"').checked")) { 
       eval("document.getElementById('"+str+"').innerHTML='Held'"); 
     }
     else {  
       eval("document.getElementById('"+str+"').innerHTML='Unheld'"); 
     }
}

</script>

</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/HoldUnholdClass/listHoldUnholdClassContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
