<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF institute notices for teacher
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AdminMessageList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/TeacherActivity/initAdminMessagesList.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Admin Messages </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','valign="top"',false), 
new Array('userName','Sender','width="10%"','valign="top"',true) , 
new Array('subject','Subject','width="20%"','valign="top"',true), 
new Array('message','Synopsis','width="40%"','valign="top"',true) , 
new Array('dated','Date','width="12"','align="center" valign="top"',true),
new Array('messageFile','Attachment','width="7%"','align="center" valign="top"',false) , 
new Array('details','Detail','width="5%"','align="right" valign="top"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE; ?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAdminMessageList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'userName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whe
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Message Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showMessageDetails(id) {
    displayWindow('divMessage',300,200);
    populateMessageValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateMessageValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetMessageDetails.php';
         document.getElementById('downloadDiv').innerHTML='';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {messageId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divMessage');
                        messageBox("This Message Does Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   
                   //document.MessageForm.subject.value = trim(j.subject);
                   document.getElementById('subject').innerHTML = trim(j.subject);
                   //document.MessageForm.message.value = trim(j.message);
                   
                   document.getElementById('message').innerHTML = trim(j.message);
                   document.getElementById('dated').innerHTML=j.dated;
                   if(j.messageFile!="<?php echo NOT_APPLICABLE_STRING;?>"){
                     document.getElementById('downloadDiv').innerHTML=j.messageFile;
                   }
                   else{
                     document.getElementById('downloadDiv').innerHTML='';  
                   }
                   /*
                   var dt=j.dated.split(' ');
                   if(dt.length >1 ){
                    //document.MessageForm.dated.value = customParseDate(dt[0],"-")+" "+dt[1];
                     document.getElementById('dated').innerHTML=customParseDate(dt[0],"-")+" "+dt[1];
                   }
                   else{
                       //document.MessageForm.dated.value = customParseDate(dt[0],"-");
                       document.getElementById('dated').innerHTML=customParseDate(dt[0],"-");
                   }
                   */

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function download(str){
    var address="<?php echo IMG_HTTP_PATH;?>/AdminMessage/"+trim(str);
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}


</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listAdminMessagesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
//---------------------------------------------------------------------------------------------------------------  
//purpose: to trim a string and output str.. etc
//Author:Dipanjan Bhattcharjee
//Date:2.09.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}

// $History: listAdminMessages.php $ 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Interface/Teacher
//Corrected look and feel of teacher module logins
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:14
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001201,00001207,00001208,00001216
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/29/08    Time: 6:21p
//Created in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Interface/Teacher
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:50p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:57p
//Updated in $/Leap/Source/Interface/Teacher
//Changed header.php and footer.php paths to the original paths
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:56p
//Created in $/Leap/Source/Interface/Teacher
//Initial Checkin
?>