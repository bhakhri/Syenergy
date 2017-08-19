<?php
//it contain the template of All Alerts  
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="title">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<!--<tr>
					<td height="10">
				</tr> -->
				<tr>
                <td>
			<!--	<td valign="top">Parent Activities &nbsp;&raquo;&nbsp;Display Alerts </td> -->
                  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> </td>
					<td valign="top" align="right">   
				     <form action="" method="" name="searchForm">
		  
					 </form>
                    </td>  
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
				 <td valign="top" class="content">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td class="contenttab_border" height="20">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
						<tr>
							<td class="content_title" width="60%">Alerts </td>
							<td width="40%" align="right" style="padding-right:5px">
								 <span class="content_title">Study Period:</span>
                                <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="getAllAlerts(this.value);"> 
                                <option value="0">All</option>
                                  <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getStudyPeriodName($studentDataArr[0]['studentId'],$studentDataArr[0]['classId']);
                                  ?>
                                  </select>
								</td>
						</tr>
						</table>
					</td>
				 </tr>
				 <tr>
					<td class="contenttab_row" valign="top" ><div id="results">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                     <tr><td><table><div id="result"></table> </div></td></tr>
                  </table>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
<?php floatingDiv_Start('ViewNotices','Notice Description'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewNotices" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Subject </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerNotice" style="overflow:auto; width:380px;" ></div><br>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Detail </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerText" style="overflow:auto; width:380px; height:200px" ></div>
	</td>
</tr>

<tr>
    <td align="center" style="padding-right:10px">
       
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif" title="Close Window" width="15" height="15" onclick="javascript:hiddenFloatingDiv('ViewNotices');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>
	
<?php
//$History: allAlertsContents.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/04/09    Time: 5:37p
//Updated in $/LeapCC/Templates/Parent
//showTimeTablePeriodsColumnsCSV, showTimeTablePeriodsRowsCSV function
//base code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 4:41p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/14/09    Time: 6:40p
//Updated in $/Leap/Source/Templates/ScParent
//issue fix 1070, 1003, 346, 344, 1076, 1075, 1073,
//1072, 1071, 1069, 1068, 1067, 1064, 
//1063, 1061, 1060, 438 1001, 1004 
//alignment & formating, validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/07/09    Time: 6:19p
//Created in $/Leap/Source/Templates/ScParent
//initial checkin
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/03/08   Time: 12:13p
//Updated in $/Leap/Source/Templates/ScStudent
//modified alerts for study period
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/22/08   Time: 12:36p
//Updated in $/Leap/Source/Templates/ScStudent
//remove the spaces
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/18/08   Time: 11:39a
//Updated in $/Leap/Source/Templates/ScStudent
//modified
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:23p
//Updated in $/Leap/Source/Templates/ScStudent
//give link for time table 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/17/08   Time: 3:03p
//Created in $/Leap/Source/Templates/ScStudent
//contain the template of alerts
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/12/08    Time: 6:51p
//Updated in $/Leap/Source/Templates/Student
//bug fixed
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:34p
//Updated in $/Leap/Source/Templates/Student
//modify for paging
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/10/08    Time: 7:53p
//Updated in $/Leap/Source/Templates/Student
//put paging
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/Student
//fixation bugs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/08    Time: 4:19p
//Updated in $/Leap/Source/Templates/Student
//modification in dimming div size
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/03/08    Time: 4:06p
//Updated in $/Leap/Source/Templates/Student
//modification for view detail
//
//*****************  Version 3  *****************
//User: Administrator Date: 9/01/08    Time: 1:27p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/02/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Student
//modification in template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:46p
//Created in $/Leap/Source/Templates/Student
//contain the template of institute notice for student
//
//


?>