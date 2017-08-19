<?php
//-------------------------------------------------------
// Purpose: to design admin dashboard.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php 
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Welcome <?php echo ucwords($sessionHandler->getSessionVariable('UserName')); ?>,</td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
			 <?php
			 if(isset($REQUEST_DATA['z'])) {
			 ?>
			 <table width='100%' class='accessDenied'><tr><td><?php echo ACCESS_DENIED;?></td></tr></table><br>
			 <?php } ?>
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                        <td valign="middle" width="50%" class="content_title">Dashboard: </td>
                        <td valign="middle" align="right" class="content_title">
                            <?php
                              $condition='';
                              $condition = " WHERE userId='".$sessionHandler->getSessionVariable('UserId')."'";
                              $totalRecord = CommonQueryManager::getInstance()->getUserLastLogin($condition);
                              if($totalRecord[0]['dateTimeIn']!='') {
                                echo "Last Login:&nbsp;".UtilityManager::formatDate($totalRecord[0]['dateTimeIn'],true);
                              }
                            ?>
                        &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
				<div id="div_Outer">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="padding_top" align="center"><b><?php echo $greetingMsg;  ?></b> </td></tr> 
				<tr>
				<td valign="top"  align="left" >
				<table width="861" border="0" align="center">
                <tr>
					<td height="163" scope="col" valign="top" align="center">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Institute Data','width=290' ,'height=150','align=center');
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top">
						<table width="100%" border="0">
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="listManagementNotice.php"><u>Total Notices</u></a></td> 
							<td valign="top" class="padding_top" align="right">
							<a href="listManagementNotice.php"><u><?php echo count($getTotalAllNoticeArr);?></u></a></td>
						</tr>
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="listManagementEvents.php"><u>Total Events</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="listManagementEvents.php"><u><?php echo $getTotalAllEventArr[0]['totalRecords']?></u></a></td>
						</tr>
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="scAllDetailsReport.php"><u>Total Students</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="scAllDetailsReport.php"><u><?php echo $getTotalStudentArr[0]['totalRecords']?></u></a></td>
						</tr>
						
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="scAllEmployeeDetailsReport.php"><u>Total Teaching Employee</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="scAllEmployeeDetailsReport.php"><u><?php echo $getTotalEmployeeArr[0]['totalRecords']?></u></a></td>
						</tr>
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="scAllEmployeeDetailsReport.php"><u>Total Non-Teaching Employee</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="scAllEmployeeDetailsReport.php"><u><?php echo $getTotalNonEmployeeArr[0]['totalRecords']?></u></a></td>
						</tr>
						 
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="listManagementDegree.php"><u>Total Degrees</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="listManagementDegree.php"><u><?php echo $getTotalDegreeArr[0]['totalRecords']?></u></a></td>
						</tr>
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="listManagementBranches.php"><u>Total Branches</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="listManagementBranches.php"><u><?php echo $getTotalBranchArr[0]['totalRecords']?></u></a></td>
						</tr>
						<tr>
							<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
							<td valign="top" class="padding_top"><a href="listCollectedFees.php"><u>Total Fees Collected</u></a></td> 
							<td valign="top" class="padding_top" align="right"><a href="listCollectedFees.php"><u><?php echo $getTotalFeesArr[0]['totalAmount']?></u></a></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
					 <td valign="top">
					 <?php
						 //*************Used For Creating*********
						 //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
						 require_once(BL_PATH.'/HtmlFunctions.inc.php');
						 echo HtmlFunctions::getInstance()->tableBlueHeader('Notices','width=290' ,'height=170','align=center');
					 ?>                     
					 <table width="100%" height="168" border="0">
					 <tr>
						<td valign="top">
						<table width="100%" border="0">
						<?php
							$recordCount = count($noticeRecordArray);
							if($recordCount >0 && is_array($noticeRecordArray) ) { 
						 
								for($i=0; $i<$recordCount; $i++ ) {
									$title="From : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleFromDate']))." To : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes($noticeRecordArray[$i]['noticeText']),550,2); 
									echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" align="left" height="10">&bull;&nbsp;&nbsp;<a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);return false;" title="'.$title.'" >'.trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),25).'</a></td>';
									$fileName = IMG_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
									if(file_exists($fileName) && ($noticeRecordArray[$i]['noticeAttachment']!='')){

										$fileName1 = IMG_HTTP_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
										echo '<td valign="top" align="right"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
									}
									echo '</tr>';
								}
								//if($noticeRecordCount>5){
									echo '<tr><td colspan="2" align="right"><a href="listManagementNotice.php"><u>More</u></a>&raquo;</td></tr>'; 
								//}
							}
							else {
								echo '<tr><td colspan="2" align="center" valign="middle" height="135">No Notice</td></tr>';
							}
						?>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					</td>
					<td valign="top">
					<?php
						//*************Used For Creating*********
						//floatingDiv_Create('div_Alerts','Attendance Last Taken On');
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Events','width=290' ,'height=170','align=center');
					?>                  
					<table width="100%" height="168" border="0">
					<tr>
						<td valign="top">
						<table width="100%" border="0">
						<?php
							$recordCount = count($eventRecordArray);
							if($recordCount >0 && is_array($eventRecordArray) ) { 
                     
								for($i=0; $i<$recordCount; $i++ ) {
									
									//$bg = $bg =='row0' ? 'row1' : 'row0';
									$title="From : ".strip_slashes(UtilityManager::formatDate($eventRecordArray[$i]['startDate']))." To : ".strip_slashes(UtilityManager::formatDate($eventRecordArray[$i]['endDate']))."     ".trim_output(strip_slashes($eventRecordArray[$i]['shortDescription']),100,2);   
									echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" align="left" height="10">&bull;&nbsp;&nbsp;<a href="" name="bubble" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].',\'divEvent\',650,350);return false;" title="'.$title.'" >'.trim_output(strip_slashes($eventRecordArray[$i]['eventTitle']),25).'</a></td>
									</tr>';
								}
								 
								
									echo '<tr><td colspan="2" align="right" valign="bottom"><a href="listManagementEvents.php"><u>More</u></a>&raquo;</td></tr>'; 
								 
							}
							else {
								echo '<tr><td colspan="2" align="center" valign="middle" height="135">No Events</td></tr>';
							}
						?>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					</td>
				</tr>
				<tr>
					<td height="163" scope="col" valign="top" align="center">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Student Branch Wise Detail','width=290' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							 
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "260", "200", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>2</x><y>120</y><rotate>true</rotate><text>Employees ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>170</y><text>Branches ---></text><text_size>10</text_size></label></labels></settings>");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBranchBarData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							</td>
							 
						</tr>
						 
						</table>
						</td>

					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
					  
					 <td height="163" scope="col" valign="top" align="center">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Employee Designation Wise Detail','width=290' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent1">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							 
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting1.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeDesignationData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent1");
							</script>
							</td>
							 
						</tr>
						 
						</table>
						</td>

					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
					 <td height="163" scope="col" valign="top" align="center">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Student Degree Wise Details','width=290' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent2">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting1.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentDegreeData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent2");
							</script>
							</td>
							 
						</tr>
						 
						</table>
						</td>

					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
					</tr>  
				<!--tr>
					<td width="675" scope="col" valign="top" align="center" colspan="3">
					<?php
         //*************Used For Creating*********
         // floatingDiv_Create('div_Messages','Messages');
         require_once(BL_PATH.'/HtmlFunctions.inc.php');
         echo HtmlFunctions::getInstance()->tableBlueHeader('Messages','width=950','height=150','align=center');
        ?>                    
         <table width="100%" height="100%" border="0" >
          <tr>
           <td height="114" valign="top" >
            <form name="searchForm" action="" method="post">
            <table border="0" cellpadding="0" cellspacing="0">
            
            <tr><td height="3px"></td></tr>
            <tr>
            <td align="left" width="100%">
             <div id="results" style="width:900px">  
               <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
               <tr class="rowheading">
                    <td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="100" class="searchhead_text"><b>Sender </b></td>
                    <td width="200" class="searchhead_text"><b>Subject</b></td>
                    <td width="400" class="searchhead_text"><b>Synopsis</b></td>
                    <td width="100" class="searchhead_text"><b>Date</b></td>
                 </tr>
                <?php
                $recordCount = count($msgRecordArray);
                if($recordCount >0 && is_array($msgRecordArray) ) { 
                  for($i=0; $i<$recordCount; $i++ ) {
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                     echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top"><a href="#" onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].',\'divMessage\',650,250);return false;">'.($records+$i+1).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].',\'divMessage\',650,250);return false;">'.strip_slashes($msgRecordArray[$i]['userName']).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].',\'divMessage\',650,250);return false;">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['subject'])),200).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].',\'divMessage\',650,250);return false;">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['message'])),275).'</a></td>
                        <td class="padding_top" valign="top"><a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].',\'divMessage\',650,250);return false;">'.UtilityManager::formatDate(strip_slashes($msgRecordArray[$i]['dated'])).'</a></td>
                        </tr>';
                    }
              
                   echo '<tr><td colspan="5" align="right" style="padding-right:10px"></td></tr>';                   
                   echo '<tr><td colspan="5" align="right" style="padding-right:10px"><a href="scListAdminMessages.php"><u>More</u></a>&raquo;</td></tr>';                   
                }
                
                else {
                    echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                }
                ?>                 
                 </table>
              </div>
              </td>
              </tr>
             </table> 
             </form>                  
           </td>
          </tr>
         </table>
            <?php 
            echo HtmlFunctions::getInstance()->tableBlueFooter();
              //floatingDiv_Close(); 
             //*************End of Div*********
            ?>  
						 </td>
					</tr>
					</table>
					<?php 
						floatingDiv_Close(); 
						//*************End of Div*********
					?>
					</td>
					</tr-->
				</table>          
				</div>    
			</td>
			</tr>
        </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

	<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice '); ?>
<form name="NoticeForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="noticeSubject" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Notice: &nbsp;</b></nobr></td>
        <td><div id="noticeText" style="overflow:auto; width:550px; height:200px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td valign="top" align="right"><nobr><b>From: &nbsp;</b></nobr></td>
        <td><div id="visibleFromDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>

    <tr>    
        <td valign="top" align="right"><nobr><b>To: &nbsp;</b></nobr></td>
        <td><div id="visibleToDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event '); ?>
<form name="EventForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="20%" valign="top" align="right"><nobr><b>Event: &nbsp;</b></nobr></td>
        <td width="80%"><div id="eventTitle" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
    <td colspan="2" valign="top" align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
        <td width="20%" align="right" valign="top"><nobr><b>From: &nbsp;</b></nobr></td>
        <td width="15%" valign="top" align="left" nowrap><div id="startDate" style="width:30px; height:20px"></div></td>
        <td width="5%" valign="top"><nobr><b>To: &nbsp;</b></nobr></td>
        <td valign="top" align="left" nowrap><div id="endDate" style="width:30px; height:20px"></div></td>
      </tr>
     </table>
    </td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(S): &nbsp;</b></nobr></td>
        <td valign="top"><div id="shortDescription" style="overflow:auto; width:225px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(L): &nbsp;</b></nobr></td>
        <td  valign="top"><div id="longDescription" style="overflow:auto; width:300px; height:200px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Message  Div-->
<?php floatingDiv_Start('divMessage','Message '); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="subject" style="overflow:auto; width:550px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Message: &nbsp;</b></nobr></td>
        <td><div id="message" name="message" style="height:200px;width:550px;overflow:auto"></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Dated: &nbsp;</b></nobr></td>
        <td width="79%"><div id="dated" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
<?php 
// $History: scIndex.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/28/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Management
//Display Last Login in dashBoard
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:39p
//Updated in $/Leap/Source/Templates/Management
//updated XML path
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 11/05/08   Time: 4:51p
//Updated in $/Leap/Source/Templates/Management
//added new reports on management dashboard
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 10/30/08   Time: 2:37p
//Updated in $/Leap/Source/Templates/Management
//updated management reports
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10/22/08   Time: 11:53a
//Updated in $/Leap/Source/Templates/Management
//updated with validations for mangement role
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10/20/08   Time: 6:39p
//Updated in $/Leap/Source/Templates/Management
//updated character length for admin messages
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:40p
//Updated in $/Leap/Source/Templates/Management
//updated with new pie charts
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:44p
//Updated in $/Leap/Source/Templates/Management
//updated notice count function
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:22p
//Updated in $/Leap/Source/Templates/Management
//updated with employee graphs
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/18/08   Time: 11:28a
//Updated in $/Leap/Source/Templates/Management
//updated div popup format
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/17/08   Time: 1:50p
//Updated in $/Leap/Source/Templates/Management
//added new functions for pie chart for management role
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:29p
//Updated in $/Leap/Source/Templates/Management
//added new files as per management role
?>