<?php

//The file contains data base functions work on dashboard
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once($FE . "/Library/common.inc.php"); //for studentId
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentAllAlerts');
	define('ACCESS','view');
	UtilityManager::ifStudentNotLoggedIn();
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

    //if ($REQUEST_DATA['startDate']=="" && $REQUEST_DATA['toDate']==""){


	//$timeTableMessages = $scStudentInformationManager->getScStudentTimeTable1($classId);
	$classId = $REQUEST_DATA['class1'];

	$totalFeeStatus = $studentInformationManager->getFeeStatus1();
	$feeArray = $studentInformationManager->showFeeAlert();
	$attendanceShortArray = $studentInformationManager->getShortAttendance($classId,'');


?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
			 <tr class="rowheading">
				<td width="100%" class="searchhead_text"><b></b></td>

			 </tr>
<?php
				$bg;
								$totalAlerts = 0;
								//$j=0;
								
								if ($feeArray[0]['cnt'] >0 && is_array($feeArray)) {
												
									$totalAlerts++;
									if ($totalAlerts>=0 && $totalAlerts<=5) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">';
										echo '<td align="left" valign="top" colspan="2"> <span > 
    <ul id="nav" class="floatleft">
    <li><a class="dmenu" href="#"><b>&bull;</b> &nbsp;&nbsp;Fee Due for :&nbsp;'.strip_slashes($feeArray[0]['cycleName']).' Print Fee Receipt <span class="arrowdown">▼</span></a>  
									    <ul>  
									    <li><a href="javascript:void(0)" onclick=printFeeReceipt("all"); title="Print All">Print All Fee</a></li>  
									     <li><a href="javascript:void(0)" onclick=printFeeReceipt("academic"); title="Print Academic Fee">Print Academic Fee</a></li>
									    <li><a href="javascript:void(0)" onclick=printFeeReceipt("hostel"); title="Print Hostel Fee">Print Hostel Fee</a></li>  
									    <li><a href="javascript:void(0)" onclick=printFeeReceipt("transport"); title="Print Transport Fee">Print Transport Fee</a></li>  
									    </ul>  
									    </li>  
									    </ul>    
										<br class="clear">  
									    </span> </td></tr>';
									}
								}
											
								$recordCount=count($totalFeeStatus);
								if ($recordCount >0 && is_array($totalFeeStatus)) {
									for ($i=0; $i<$recordCount;$i++) {
										$totalAlerts++;

										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">';
										echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;Fee Due for :'.strip_slashes($totalFeeStatus[$i]['periodName']).' Rs. '.strip_slashes(number_format($totalFeeStatus[$i]['pending']),2,'.','').'</td>';
									}
								}

								$cnt=$timeTableMessages[0]['cnt'];
								if ($cnt>0 ) {
											$totalAlerts++;
											$bg = $bg =='row0' ? 'row1' : 'row0';
											echo '<tr class="'.$bg.'">';
											echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;<a href="listTimeTable.php">The time table has been changed</a></td>';
								}

								$recordCount=count($attendanceShortArray);
								if ($recordCount >0 && is_array($attendanceShortArray)) {
									for ($i=0; $i<$recordCount;$i++) {
										$totalAlerts++;
										$subCode = $attendanceShortArray[$i]['subjectCode'];
										$per = $attendanceShortArray[$i]['per'];
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">';
										echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;Attendance Short in '.$subCode.' ('.$per.'%)</td>';
									}
								}
								if($sessionHandler->getSessionVariable('MARKS') == 1){
									$testMartsArray = $studentInformationManager->getStudentMarks2($classId);
									$recordCount=count($testMartsArray);
									if ($recordCount >0 && is_array($testMartsArray)) {
										for ($i=0; $i<$recordCount;$i++) {
											$totalAlerts++;
											$subCode = $testMartsArray[$i]['subject'];
											$marksScored = $testMartsArray[$i]['obtained'];
											$totalMarks = $testMartsArray[$i]['totalMarks'];
											$testTopic = $testMartsArray[$i]['testTopic'];
											$bg = $bg =='row0' ? 'row1' : 'row0';
											echo '<tr class="'.$bg.'">';
											$studentDataPost="listStudentMarks.php?classId=".$classId."";
											$studentData='"'.$studentDataPost.'"';
											echo "<td align='left' valign='top' colspan='2'>&bull;&nbsp;&nbsp;<a href='#' onclick='hideUrlData(".$studentData.",false);' title='For more detail click on the link'>".$subCode." - ".$testTopic." -  Marks Scored:  ".$marksScored."/".$totalMarks."</a></td>";
										}
									}
								}

								if($totalAlerts == 0) {
									echo '<tr><td colspan="2" align="center" style="padding-top:50px;" class="redColor">No Alerts</td></tr>';
								}






    /*for($i=0;$i<$cnt;$i++) {
		//$studentInformationArray[$i]['fromDate']=strip_slashes($studentInformationArray[$i]['fromDate']);
		//$studentInformationArray[$i]['toDate']=strip_slashes($studentInformationArray[$i]['toDate']);
		$studentInformationArray[$i]['Percentage']=ROUND((($studentInformationArray[$i]['attended'] /  $studentInformationArray[$i]['delivered'])*100),2);
		// add subjectId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentInformationArray[$i]);

         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    //print_r($valueArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';
	*/


?>

<?php

//$History: ajaxInitAllAlerts.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/04/08   Time: 10:35a
//Updated in $/Leap/Source/Library/ScStudent
//show subject name also
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/03/08   Time: 4:28p
//Created in $/Leap/Source/Library/ScStudent
//file is used to get alerts semester wise detail
//

?>
