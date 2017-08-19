<?php
//-------------------------------------------------------
// Purpose: To generate student attendance list 
// functionality 
//
// Author : Dipanjan Bhattacharjee
// Created on : (31.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
if(isset($REQUEST_DATA['id']) and $REQUEST_DATA['id'] > 0){
 require_once(BL_PATH . "/Teacher/StudentActivity/initList.php"); 
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Student Attendance</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("ajax.js");  
echo UtilityManager::includeJS("tab-view.js"); 


//pareses input and returns 0 if the input is blank
//Author: Dipanjan Bhatacharjee
//Date:14.7.2008
function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

//pareses input and returns "-" if the input is blank
//Author: Dipanjan Bhatacharjee
//Date:14.7.2008
function parseOutput($data){
    
     return ( (trim($data)!="" ? $data : "---" ) );  
    
}
?> 

<script language="javascript">

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET STUDENT ATTENDANCE
//
//Author : Dipanjan Bhattacharjee
// Created on : (15.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getAttendance(classId,studentId,startDate,endDate) {

    if(trim(startDate)=="" || trim(endDate)==""){
        alert("Date Fields Can Not Be Empty");
        return false;
    }
   
   if(!dateDifference(startDate,endDate,"-")) 
   {
      alert("To Date Can Not Be Smaller Than From Date");   
      return false;
   }
    
    url = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxInitAttendance.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {startDate2: (startDate), 
             endDate2: (endDate),
             classId: (classId),
             studentId: (studentId)},
             onCreate:function(transport){ showWaitDialog();},
             onSuccess: function(transport){
               
             hideWaitDialog();
             //alert(transport.responseText);
             j= trim(transport.responseText).evalJSON();
             var tbHeadArray = new Array(new Array('srNo','#','width="5%"',''), new Array('subjectName','Subject','width="20%"','') , new Array('delivered','Lecure Delivered','width="8%"',''), new Array('attended','Lecture Attended','width="8%"',''), new Array('percentageAtt','Percentage','width="8%"',''));
            //alert(transport.responseText);
               printResultsNoSorting('results', j.info, tbHeadArray);
             //alert(trim(transport.responseText));
             
             if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                 return false;
                 //location.reload();
                     
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
    require_once(TEMPLATES_PATH . "/Teacher/StudentActivity/studentDetailAttendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: studentDetailAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:26p
//Created in $/Leap/Source/Interface/Teacher
?>
