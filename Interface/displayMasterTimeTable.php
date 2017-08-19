<?php 
//-------------------------------------------------------
//  This File print functionality of master time table
// Author :Rajeev Aggarwal
// Created on : 05-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/TimeTable/initTimeTable.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Time Table </title>
<?php 
require_once(TEMPLATES_PATH .'/Teacher/jsCssHeader.php'); 

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author : Dipanjan Bhattacharjee
// Created on : 31.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>

<script language="javascript">

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO get time table date for a class
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.082008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function validateAddForm() {
    var fieldsArray = new Array(new Array("studentClass","Select Class"),new Array("studentGroup","Select Group"));
    var len = fieldsArray.length;
	var frm = document.timeTableForm;

    for(i=0;i<len;i++) {
        if(isEmpty(document.getElementById(fieldsArray[i][0]).value) ) {
            messageBox(fieldsArray[i][1],fieldsArray[i][0]);
			eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    } 
    getTimeTableData();
	
	return false;
}
function clearText()
{
	document.getElementById('results').innerHTML="";
}
function getTimeTableData() {
 
 
 url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxMasterTimeTable.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {
         studentGroupId: (trim(document.timeTableForm.studentGroup.value))
         },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
			 if(trim(transport.responseText)){

				hideWaitDialog(true);
				document.getElementById('results').innerHTML=trim(transport.responseText);
			 }
			 else{

				document.getElementById('results').innerHTML="No Details Found";
			 }
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
}

function autoPopulate(val,type,frm)
{
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   if(frm=="Add"){
       if(type=="subject"){
            document.timeTableForm.subject.options.length=0;
            var objOption = new Option("SELECT","");
            document.timeTableForm.subject.options.add(objOption); 
       }
}
    
new Ajax.Request(url,
{
	 method:'post',
	 parameters: {type: type,id: val},
	 onSuccess: function(transport){
	   if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ){
		   
		  showWaitDialog(true);
	   }
	   else{
			hideWaitDialog(true);
			j = trim(transport.responseText).evalJSON();   
			len = j.groupArr.length;
			document.timeTableForm.studentGroup.length = null;
			addOption(document.timeTableForm.studentGroup, '', 'Select');
			for(i=0;i<len;i++) { 
			 addOption(document.timeTableForm.studentGroup, j.groupArr[i].groupId, j.groupArr[i].groupName);
			}
		  }
	 },
	 onFailure: function(){ alert('Something went wrong...') }
   }); 
}

function printReport() {

	var name = document.getElementById('studentGroup');
	var cname = document.getElementById('studentClass');
	
	path='<?php echo UI_HTTP_PATH;?>/classTimeTableReportPrint.php?groupId='+document.timeTableForm.studentGroup.value+'&groupName='+name.options[name.selectedIndex].text+'&className='+cname.options[cname.selectedIndex].text;
	//alert(path);
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=440, top=150,left=150");
}
</script> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/masterTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//History: $
?>