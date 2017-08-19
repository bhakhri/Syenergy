<?php
//it contain the template of All Alerts  
//
// Author :Jaineesh
// Created on : 23-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
				
				<tr>
					  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> </tr>
					<td valign="top" align="right">   
				   <form action="" method="" name="searchForm">
		  
					  </form>
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
							<td width="40%" class="content_title" align="right"><nobr><b>Study Period : </b></nobr></td>
								<td class="padding">
								<select size="1" class="selectfield" name="semesterDetail" id="semesterDetail" onChange="getAllAlerts(this.value)">
								<option value="0" selected="selected">All</option>
									<?php
										
										$studentId = $sessionHandler->getSessionVariable('StudentId');
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo HtmlFunctions::getInstance()->getStudyPeriodName($studentId,$classId);
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
         <!--<?php  
                
				$bg;
								$totalAlerts = 0;
								//$j=0;
								$recordCount=count($totalFeeStatus);
								if ($recordCount >0 && is_array($totalFeeStatus)) {
									for ($i=0; $i<$recordCount;$i++) {
										$totalAlerts++;
										
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">';
										echo '<td valign="top" class="padding_top" style="padding-left:15px">'.$j++.'</td>';
										echo '<td align="left" valign="top" colspan="2">Fee Due for :'.strip_slashes($totalFeeStatus[$i]['periodName']).' Rs. '.strip_slashes(number_format($totalFeeStatus[$i]['pending']),2,'.','').'</td>';
									}
								}
								else {
									$j=1;
								}
								$cnt=$timeTableMessages[0]['cnt'];
								if ($cnt>0 ) {
											$totalAlerts++;
											$bg = $bg =='row0' ? 'row1' : 'row0';
											echo '<tr class="'.$bg.'">';
											echo '<td valign="top" class="padding_top" style="padding-left:15px">'.$j++.'</td>';
											echo '<td align="left" valign="top" colspan="2"><a href="scTimeTable.php">The time table has been changed</a></td>';	
								}
								else {
									$j=1;
								}
								$recordCount=count($attendanceShortArray);
								if ($recordCount >0 && is_array($attendanceShortArray)) {
									for ($i=0; $i<$recordCount;$i++) {
										$totalAlerts++;
										$subCode = $attendanceShortArray[$i]['subjectCode'];
										$per = $attendanceShortArray[$i]['per'];
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">';
										echo '<td valign="top" class="padding_top" style="padding-left:15px">'.$j++.'</td>';
										echo '<td align="left" valign="top" colspan="2">Attendance Short in '.$subCode.' ('.$per.'%)</td>';
									}
								}
								else {
									$j=1;								
								}
								$recordCount=count($testMartsArray);
								if ($recordCount >0 && is_array($testMartsArray)) {
									for ($i=0; $i<$recordCount;$i++) {
										$totalAlerts++;
										$subCode = $testMartsArray[$i]['subjectCode'];
										$marksScored = $testMartsArray[$i]['obtained'];
										$totalMarks = $testMartsArray[$i]['totalMarks'];
										$testTopic = $testMartsArray[$i]['testTopic'];
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">';
										echo '<td valign="top" class="padding_top" style="padding-left:15px">'.$j++.'</td>';
										echo '<td align="left" valign="top" colspan="2"><a href="scMarks.php" title="For more detail click on the link">'.$subCode.' - '.$testTopic.' -  Marks Scored:  '.$marksScored.'/'.$totalMarks.'</a></td>';
									}
								}
								else {
									$j=1;
								}
								
								if($totalAlerts == 0) {
									echo '<tr><td colspan="2" align="center" style="padding-top:50px;" class="redColor">No Alerts</td></tr>';
								}
                ?>      -->           
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
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:43p
//Created in $/LeapCC/Templates/Student
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
