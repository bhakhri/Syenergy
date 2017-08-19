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
define('MODULE','InstituteNoticeList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
require_once(BL_PATH . "/Teacher/TeacherActivity/initInstituteNoticeList.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Institute Notices </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','valign="top"',false), 
new Array('noticeSubject','Subject','width="20%"','valign="top"',true), 
new Array('departmentName','Department','width="20%"','valign="top"',true),
new Array('noticeText','Notice','width="30%"','valign="top"',true) , 
new Array('visibleFromDate','Visible From','width="15%"','align="center" valign="top"',true),
new Array('visibleToDate','Visible To','width="15%"','align="center" valign="top"',true),
new Array('noticeAttachment','Attachment','width="8%"','align="center" valign="top"',false), 
new Array('details','Action','width="5%"','align="center" valign="top"',false)
);


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInstituteNoticeList.php';
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
sortField = 'visibleFromDate';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Notice Div
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
function showNoticeDetails(id) {
    displayWindow('divNotice',300,200);
    populateNoticeValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateNoticeValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetNoticeDetails.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {noticeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divNotice');
                        messageBox("This Notice Record Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
		           document.getElementById('noticeSubject').innerHTML = trim(j.noticeSubject);
                   document.getElementById('noticeDepartment').innerHTML = trim(j.departmentName+' ('+j.abbr+')');
                   document.getElementById('noticeText').innerHTML = trim(j.noticeText);
				   //alert(j.noticeAttachment)
				   if(j.noticeAttachment=='' || j.noticeAttachment==null)
				   {
                     
					 //alert(1);
					 //document.getElementById('editLogoPlace').innerHTML = 'k7567';
					 document.getElementById('editLogoPlace').style.display = 'none';
					  
					 
                   }
                   else{
                      //document.getElementById('editLogoPlace').innerHTML = j.noticeAttachment;
                      //document.getElementById('downloadFileName').value = j.noticeAttachment;
					  document.getElementById('downloadFileName').value=trim(j.noticeAttachment);
								 
					  document.getElementById('editLogoPlace').style.display = '';
					                      
                   }
				   
				   
				   
				   //document.getElementById('downloadFileName').value=trim(j.noticeAttachment);
                   //document.getElementById('visibleToDateNotice').innerHTML = customParseDate(j.visibleToDate,"-");
				   document.getElementById('divHeaderId').innerHTML ='This Notice is visible on Dashboard From '+ customParseDate(j.visibleFromDate,"-") + ' to ' + customParseDate(j.visibleToDate,"-");
				   
                   //document.getElementById('visibleFromDateNotice').innerHTML = customParseDate(j.visibleFromDate,"-");

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
	}



	
function  download(str){    
var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listInstituteNoticeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
	   ?>
</body>
</html>
<script language="javascript">
 
  

 //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	
	
	
	function  download1()
	{    

   var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+document.getElementById('downloadFileName').value;
   
   //window.location = address;
     window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
	 
	}
	//alert(document.getElementById('downloadFileName').value);
	//alert(address);
	/*
	function  download(str)
	{    
    var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
*/
	
	 
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

// $History: listInstituteNotice.php $ 
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 3/09/09    Time: 11:37
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001407,00001408,00001409,
//00001419,00001420
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 1/09/09    Time: 11:21
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001351,00001353,00001354,00001355,
//00001369,00001370,00001371
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:54a
//Updated in $/LeapCC/Interface/Teacher
//Gurkeerat: resolved issue regardind issue nos. 1226,1227
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/08/09   Time: 12:00
//Updated in $/LeapCC/Interface/Teacher
//Corrected column name
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 2:06p
//Updated in $/LeapCC/Interface/Teacher
//Showing Department Name
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
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
