<?php
//This file gives template of "DashBoard"  Module
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
?>
<script>
function testFn() {

    guiders.hideAll();
    addNewBread("menuLookup");
}			


function addNewBread(moduleName) {
   url = '<?php echo HTTP_LIB_PATH;?>/ajaxGuiders.php';

   new Ajax.Request(url,
   {
     method:'post',
     parameters: { moduleName: moduleName },
     onCreate: function(){
        showWaitDialog(true);
     },
     onSuccess: function(transport){
       hideWaitDialog(true);
     },
     onFailure: function(){ }
     });
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
			  <td height="10" colspan="2"></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows">
     				<b><span style="font-size: 12px; color: #BB0000;"> 
				<?php
					  global $sessionHandler;  
					  $ttStudentId =  $sessionHandler->getSessionVariable('StudentId');
					  $ttClassId =  $sessionHandler->getSessionVariable('ClassId');
					  echo HtmlFunctions::getInstance()->getStudentSubjectDetails($ttClassId,$ttStudentId);
					?></span></b></td>
			<td align="right">
            <!-- Added for autosuggest -->
            <input type="text" name="menuLookup" class="fadeMenuText" style="width:200px" id="menuLookup"
            onkeyup="getMenuLookup();" onclick="changeDefaultTextOnClick();" onblur="changeDefaultTextOnBlur();"
            value="Menu Lookup.." autocomplete="off"/>&nbsp;
			<?php
			   $status=0;
			   require_once(MODEL_PATH . "/GuidersManager.inc.php");
			   $returnStatus=GuidersManager::getInstance()->checkGuidersEntry("menuLookup");
			   if(count($returnStatus)>0) {
			     $status=1;
			   }
			if($status==0){
			?>
			<script type="text/javascript">
				      guiders.createGuider({
				      attachTo: "#menuLookup",
				      buttons: [{name: "Close", onclick:testFn}],
				      description: "Menu lookup helps you find menu options easily and quickly. Just enter the keyword that matches your menu option and menu \
							lookup automatially guides you..",
				      id: "fourth",
				      next: "fifth",
				      position: 5,
				      title: "Find Menu Options Quickly!",
				      width: 400
				    }).show();
				</script>
			<?php }?>
            <div id="menuLookupContainer" style="position:absolute;z-index:100;padding:0px 0px 0px 0px; text-align:left;
            display:none; border:1px solid #7F9DB9; margin-right:0px;"></div>
            <!-- Auto suggest ends -->
            </td>
				</tr>
		</table></td></strong>
  </tr>
      <tr>
        <td height="405"  valign="top">


<div id="div_Outer">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
			<td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                        <td valign="middle" width="20%" class="content_title">Dashboard: </td>
                        <?php
                          if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
                        ?>
                         <td valign="middle" align="right" class="content_title" width="60%" ><a href="<?php echo UI_HTTP_PATH;?>/provideFeedbackAdv.php"  style="color:white" ><img src="<?php echo IMG_HTTP_PATH?>/blink.gif" />&nbsp;You have to complete your feedback to get full access of your account</a></td>
                         <?php
                          }
                         else{
                         ?>
                         <td valign="middle" align="right" class="content_title" width="60%">
                          <?php
                          if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==1){
                           ?>
                             <a href="<?php echo UI_HTTP_PATH;?>/provideFeedbackAdv.php"  style="color:white" ><img src="<?php echo IMG_HTTP_PATH?>/blink.gif" /> [ Complete the feedback by <?php echo $sessionHandler->getSessionVariable('LastDateOfProvidingFeedback');?> or your login will be disabled ]</a>
                           <?php
                          }
                          ?>
                          <a href="listInstituteEvents.php"  style="color:white" ><i>Event</i></a>
                         </td>
                         <?php
                         }
                         ?>
                        <td valign="middle" align="right" class="content_title" width="18%">
                            <?php
                              $condition='';
                              $condition = " WHERE userId='".$sessionHandler->getSessionVariable('UserId')."'";
                              $totalRecord = CommonQueryManager::getInstance()->getUserLastLogin($condition);
                              if($totalRecord[0]['dateTimeIn']!='') {
                                echo "<span  class='redLink'>&nbsp; Last Login:&nbsp;".UtilityManager::formatDate($totalRecord[0]['dateTimeIn'],true)." </span>";
                              }
                            ?>
                        &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
             </tr>
            <tr>
<td valign="top" class="contenttab_row" align="center" width="100%">
	<table width="100%" border="0" cellspacing="4" cellpadding="0">
		<tr>
		  <td width="340px" valign="top" align="center" rowspan="3">
			 <table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="300px" valign="top" align="center" >
						<?php
								echo HtmlFunctions::getInstance()->tableBlueHeader('Notices','width="330"','height="362"','');
						?>
					<table border="0" width="100%" height="362">
						<tr>
							<td valign="top">
								<table width="100%" border="0">
								<?php            
									$recordCount = count($noticeRecordArray); 
									if($recordCount >0 && is_array($noticeRecordArray) ) {
										for($i=0; $i<$recordCount; $i++ ) {
                                            if($noticeRecordArray[$i]['visibleMode']=='3') {  
                                                $visibleImageName = IMG_HTTP_PATH."/urgent1.png";
                                              }
                                              else if($noticeRecordArray[$i]['visibleMode']=='2') {  
                                                $visibleImageName = IMG_HTTP_PATH."/important1.png";  
                                              }
                                              else {
                                                $visibleImageName = IMG_HTTP_PATH."/new.gif";  
                                              }

									//$downloadCount = ' ('.$noticeRecordArray[$i]['downloadCount'].')';
											//$bg = $bg =='row0' ? 'row1' : 'row0';
											$title="From : ".strip_slashes($noticeRecordArray[$i]['visibleFromDate'])." To : ".strip_slashes($noticeRecordArray[$i]['visibleToDate'])."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1);
											echo '<tr class="'.$bg.'">

											<td valign="top" class="padding_top" >&bull;&nbsp;&nbsp;
											<style type="text/css">
											a:link {font-weight:bold;} 
					 						
											</style>

											<a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'ViewNotices\',650,350);return false;" title="'.$title.'" >
											<span style="color:#'.$noticeRecordArray[$i]['colorCode'].'">'.strip_tags(trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),20)).' - <I>'.$noticeRecordArray[$i]['abbr'].'</I></span>';


                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               global $sessionHandler;
                                               $tdays = $sessionHandler->getSessionVariable('FLASHING_NEW_ICON_NOTICES');
                                                 if($tdays=='') {
                                                   $tdays=0;
                                                 }
                                                 if(is_numeric($tdays) === true) {
                                                   $tdays = intval($tdays);
                                                   if($tdays <= 0 ) {
                                                     $tdays = 0;
                                                   }
                                                 }
                                                 else {
                                                   $tdays = 0;
                                                 }
                                               $dt  = $noticeRecordArray[$i]['visibleFromDate'];
                                               $dtArr = explode('-',$dt);
                                               $dtArr = explode('-',$dt);
                                               $dt1 = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]+$tdays), date($dtArr[0])));
                                               $currentDate = date('Y-m-d');
                                               if($currentDate<=$dt1 && $tdays!=0) {
                                                 echo '&nbsp;<img src="'.$visibleImageName.'">';
                                               }
                                            echo '</a></td>';
											$fileName = IMG_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
											if(file_exists($fileName) && ($noticeRecordArray[$i]['noticeAttachment']!="")) {
												$fileName1 = IMG_HTTP_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
												echo '<td valign="top"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
											}
											else {
												echo '<td align="center">'.NOT_APPLICABLE_STRING.'</td>';
											}
											echo '</tr>';
										}
										echo '<tr><td colspan="2" align="right" style="padding-right:10px" valign="bottom"><a href="listInstituteNotices.php"><u>Show all Notices</u></a></td></tr>';
									}
									else {
										echo '<tr><td colspan="2" style="padding-top:200px;" align="center" class="redColor">There are no new Notices to show</td></tr>';
									}
									?>
								</table>
							</td>
						</tr>
					</table>
					<?php
					//  floatingDiv_Close();
					echo HtmlFunctions::getInstance()->tableBlueFooter();
					//*************End of Div*********
					?>
					</td>
					  </tr>
					  <tr>
						<td scope="col" valign="top" align="right">
									<?php
									echo HtmlFunctions::getInstance()->tableBlueHeader('Tasks','width="330"','height="132"','');
									?>
										<table width="100%" border="0" height="132">
											<tr>
												<td valign="top" >
													<table border="0" width="100%">
														<?php
														$recordTasksCount = count($showTask);
														if($recordTasksCount >0 && is_array($showTask) ) {

															for($i=0; $i<$recordTasksCount; $i++ ) {
																//if ($showTask[$i]['Result'] > 0) {
																	//$j=0;
																$bg = $bg =='row0' ? 'row1' : 'row0';
																$title="Description : ".trim_output2(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($showTask[$i]['shortDesc']))),250,2);
																//echo ($showTask[$i]['status']);
																if ($showTask[$i]['status'] == 0 ) {
																	echo '<tr class="'.$bg.'" style="color:red" id="tasksTdId_'.$i.'">
																	<td valign="top" align="center" width="4%" >'.($records+$i+1).'</td>
																	<td  valign="top" align="left" width="60%" id="tasksTdId__'.$i.'" ><a href="" id="statusLink'.$i.'" name="bubble" style="color:red" onclick="showTaskDetails('.$i.','.$showTask[$i]['taskId'].',\'ViewTasks\',350,250);return false;" title="'.$title.'">'
																	.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($showTask[$i]['title']))),25).
																	'</a></td>';


																	$showTask[$i]['dueDate'] = UtilityManager::formatDate($showTask[$i]['dueDate']);
																	echo '<td valign="top" width="40%" id="tasksTdId___'.$i.'">'.$showTask[$i]['dueDate'].'</td>';


																	echo '<td id="tasksTdId____'.$i.'" valign="middle"><img src='.IMG_HTTP_PATH.'/deactive.gif border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:pointer"  onclick="changeStatus('.$i.','.$showTask[$i]['taskId'].','.$showTask[$i]['status'].')"></td></tr>';
																}
																else {
																	echo '<tr class="'.$bg.'" id="tasksTdId_'.$i.'" >
																	<td valign="top" align="center" width="4%" >'.($records+$i+1).'</td>
																	<td  valign="top" align="left" width="60%" id="tasksTdId__'.$i.'" ><a href="" id="statusLink'.$i.'" name="bubble" onclick="showTaskDetails('.$i.','.$showTask[$i]['taskId'].',\'ViewTasks\',350,250);return false;" title="'.$title.'">'
																	.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($showTask[$i]['title']))),25).
																	'</a></td>';
																	$showTask[$i]['dueDate'] = UtilityManager::formatDate($showTask[$i]['dueDate']);
																	echo '<td valign="top" width="40%" id="tasksTdId___'.$i.'">'.$showTask[$i]['dueDate'].'</td>';

																	echo '<td id="tasksTdId____'.$i.'" valign="middle"><img src='.IMG_HTTP_PATH.'/active.gif border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:pointer"  onclick="changeStatus('.$i.','.$showTask[$i]['taskId'].','.$showTask[$i]['status'].')"></td></tr>';
																}
															  //}
															}
															echo '<tr><td colspan="4" align="right" style="padding-right:10px" valign="bottom"><a href="listTask.php"><u>Show all Tasks</u></a></td></tr>';
														}

														else{
															echo '<tr><td style="height:120px" align="center" valign="middle" colspan="3" class="redColor">There are no new Tasks to show</td></tr>';
														}
														?>
													</table>
												</td>
											</tr>
										</table>
										<?php
										//floatingDiv_Close();
										echo HtmlFunctions::getInstance()->tableBlueFooter();
										?>
									</td>
							</tr>
						</table>
					</td>
					<td valign="top" align="right">
							<?php
							//*************Used For Creating*********
							// floatingDiv_Create('div_Alerts','Alerts');
							echo HtmlFunctions::getInstance()->tableBlueHeader('Alerts','width="320"','height="200"','');
							?>
							<table width="100%" border="0" height="200">
								<tr>
									<td valign="top" >
										<table width="100%" border="0">
											<?php
											$bg;
											$totalAlerts = 0;
											
											if (count($feeArray) >0 && is_array($feeArray)) {	
												$totalAlerts++;
												if ($totalAlerts>=0 && $totalAlerts<=5) {
													for($i=0;$i< count($feeArray);$i++){
														$bg = $bg =='row0' ? 'row1' : 'row0';
														echo '<tr class="'.$bg.'">';
														echo '<td align="left" valign="top" colspan="2"> <span > 
	    <ul id="nav" class="floatleft">
	      <li><a class="dmenu" href="#"><b>&bull;</b> Fee Due for :&nbsp;'.strip_slashes($feeArray[$i]['cycleName']).' Print Fee Receipt</a>  
	        <ul>  
	           <li><a href="javascript:void(0)" onclick=printFeeReceipt("academic",'.$feeArray[$i]['feeClassId'].');>Print Academic Fee</a></li>
	           <li><a href="javascript:void(0)" onclick=printFeeReceipt("hostel",'.$feeArray[$i]['feeClassId'].');>Print Hostel Fee</a></li>  
	           <li><a href="javascript:void(0)" onclick=printFeeReceipt("transport",'.$feeArray[$i]['feeClassId'].');>Print Transport Fee</a></li>  
               <li><a href="javascript:void(0)" onclick=printFeeReceipt("all","'.$feeArray[$i]['feeClassId'].'");>Print All Fee</a></li>  
	        </ul>  
	      </li>  
	    </ul>    
		<br class="clear">  
	    </span> </td></tr>';
	    											}
												}
											}
										
											$recordCount=count($totalFeeStatus);
											if ($recordCount >0 && is_array($totalFeeStatus)) {
												for ($i=0; $i<$recordCount;$i++) {
													$totalAlerts++;
													if ($totalAlerts>=0 && $totalAlerts<=5) {
														$bg = $bg =='row0' ? 'row1' : 'row0';
														echo '<tr class="'.$bg.'">';
														echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;Fee Due for :'.strip_slashes($totalFeeStatus[$i]['periodName']).' Rs. '.strip_slashes(number_format($totalFeeStatus[$i]['pending']),2,'.','').'</td></tr>';
													}
												}
											}
											$cnt=$timeTableMessages[0]['cnt'];
											if ($cnt>0 ) {
												$totalAlerts++;
												if ($totalAlerts>=0 && $totalAlerts<=5) {
													$bg = $bg =='row0' ? 'row1' : 'row0';
													echo '<tr class="'.$bg.'">';
													echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;<a href="listTimeTable.php"><b>The time table has been changed</b></a></td></tr>';
												}
											}
											$recordCount=count($attendanceShortArray);
											if ($recordCount >0 && is_array($attendanceShortArray)) {
												for ($i=0; $i<$recordCount;$i++) {
													$totalAlerts++;
													if ($totalAlerts>=0 && $totalAlerts<=5) {
														$subCode = $attendanceShortArray[$i]['subjectCode'];
														$per = $attendanceShortArray[$i]['per'];
														$bg = $bg =='row0' ? 'row1' : 'row0';
														echo '<tr class="'.$bg.'">';
														echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;Attendance Short in '.$subCode .' ('.$per.'%)</td></tr>';
													}
												}
											}
											if($sessionHandler->getSessionVariable('MARKS') == 1){
												$recordCount=count($testMartsArray);
												if ($recordCount >0 && is_array($testMartsArray)) {
													for ($i=0; $i<$recordCount;$i++) {


														$totalAlerts++;
														if ($totalAlerts>=0 && $totalAlerts<=5) {
															$subCode = $testMartsArray[$i]['subjectCode'];
															$subjectName = $testMartsArray[$i]['subject'];
															$marksScored = $testMartsArray[$i]['obtained'];
															$totalMarks = $testMartsArray[$i]['totalMarks'];
															$testAbbr = $testMartsArray[$i]['testAbbr'];
															$bg = $bg =='row0' ? 'row1' : 'row0';
															echo '<tr class="'.$bg.'">';
															echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;<a href="listStudentMarks.php" title="'.$subjectName.'">'.$subCode.'-'.$testAbbr.'-Scored:  '.$marksScored.'/'.$totalMarks.'</a></td></tr>';
														}
													}
												}
											}
											if($totalAlerts > 0) {
												echo '<tr><td colspan="2" align="right" style="padding-right:10px" valign="bottom"><a href="listAllAlerts.php"><u>Show all Alerts</u></a></td></tr>';
											}
											if($totalAlerts == 0) {
												echo '<tr><td colspan="2" align="center" style="padding-top:90px;" class="redColor">There are no new  Alerts to show</td></tr>';
											}
											?>
										</table>
									</td>
								</tr>
							 </table>

							<?php
							//floatingDiv_Close();
							echo HtmlFunctions::getInstance()->tableBlueFooter();

							//*************End of Div*********
							?>
						</td>
						<td width="300px"  scope="col" valign="top" align="left">

							<?php
							//*************Used For Creating*********
							// floatingDiv_Create('div_Events','Events');
							echo HtmlFunctions::getInstance()->tableBlueHeader('Latest Uploaded Resources','width="320"','height="200"','');

							?>
							<table border="0" width="100%" height="200" >
								<tr>
									<td valign="top">
										<table width="100%" border="0">
										<?php
										$recordCount = count($resourceRecordArray);
										if($recordCount >0 && is_array($resourceRecordArray) ) {

										for($i=0; $i<$recordCount; $i++ ) {
											$bg = $bg =='row0' ? 'row1' : 'row0';
											$title="Uploaded Date : ".strip_slashes($resourceRecordArray[$i]['postedDate'])."   ".trim_output2(strip_slashes(strip_tags($resourceRecordArray[$i]['description'])),150,2);
											echo '<tr class="'.$bg.'">
											<td valign="top" class="padding_top" >&bull;&nbsp;&nbsp;<a href="listStudentInformation.php?tabIndex=4" name="bubble"  title="'.$title.'" >'
											.trim_output(strip_slashes(strip_tags($resourceRecordArray[$i]['subjectCode'])),25)
											.'--'
											.trim_output(strip_slashes(strip_tags($resourceRecordArray[$i]['employeeName'])),25)
											.'</a></td>';
											$fileName = IMG_PATH."/CourseResource/".$resourceRecordArray[$i]['attachmentFile'];
												if(file_exists($fileName) && ($resourceRecordArray[$i]['attachmentFile']!="")) {
													//$fileName1 = IMG_HTTP_PATH."/CourseResource/".$resourceRecordArray[$i]['attachmentFile'];

													$fileName1 = $resourceRecordArray[$i]['attachmentFile'];

													//echo '<td valign="top"><a href="Javascript:void(0);" title="Download File"><img name="'.$fileName1.'"  onclick="download(this.name)" src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';

													echo '<td valign="top"><a href="Javascript:void(0);" title="Download File"><img name="'.$fileName1.'"  onclick="download('.$resourceRecordArray[$i]['courseResourceId'].');return false;" src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
												}
												else {
													echo '<td align="center">'.NOT_APPLICABLE_STRING.'</td>';
												}
												echo '</tr>';
                                        }
										echo '<tr><td colspan="2" align="right" style="padding-right:10px" valign="bottom"><a href="listStudentInformation.php?tabIndex=4"><u>Resource Detail</u></a></td></tr>';
										}
										else {
											echo '<tr><td style="padding-top:90px;" align="center" class="redColor">There are no new Resources Uploaded</td></tr>';
										}
										?>
										</table>
									</td>
								</tr>
							</table>
								<?php
								//  floatingDiv_Close();
								echo HtmlFunctions::getInstance()->tableBlueFooter();
								?>
					 </td>
				</tr>
				<tr>
								<td scope="col" valign="top" align="right">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="300px" valign="top" align="center" >

									<?php
									//*************Used For Creating*********
									echo HtmlFunctions::getInstance()->tableBlueHeader('Admin Messages','width="320"','height="157"','');
									?>
									<table width="100%" border="0" height="105">
										<!--<tr class="rowheading2">
										<th align="center">#</th>
										<th width="80%" align="center">Messages</th>
										</tr>-->
										<tr>
											<td valign="top" colspan="2">
												<table border="0" width="100%" height="105">
													<?php
													$recordCountEvents = count($adminMessages);
													// $totalHeightCovered = (15 * $recordCountEvents) + 20;
													// $pendingHeight = round(157- $totalHeightCovered,0);

													if($recordCountEvents >0 && is_array($adminMessages) ) {
													  for($i=0; $i<$recordCountEvents; $i++ ) {
													    $bg = $bg =='row0' ? 'row1' : 'row0';
													    $title = strip_slashes(strip_tags($adminMessages[$i]['message']),150,2);
													    ?>
													    <tr class='<?php echo $bg;?>'>
														    <td valign='top' align="center" width="5%"><?php echo ($records+$i+1);?></td>
														    <td valign='top' align='left' style="padding-left:2px" width="80%">
                                                                <a href="" name="bubble" onclick="showAdminDetails('<?php echo $adminMessages[$i]['messageId']?>','ViewAdmin',650,350);return false;" title="<?php echo strip_tags(HtmlFunctions::getInstance()->removePHPJS($title)) ?>">
                                                                    <?php echo trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($adminMessages[$i]['message']))),25) ?>
                                                            </td>
                                                        <?php
                                                            $fileName = IMG_PATH."/AdminMessage/".$adminMessages[$i]['messageFile'];
                                                            if(file_exists($fileName) && ($adminMessages[$i]['messageFile']!="")) {
                                                              $fileName1 = IMG_HTTP_PATH."/AdminMessage/".$adminMessages[$i]['messageFile'];
                                                              echo '<td valign="top" align="center" width="15%"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
                                                            }
                                                            else {
                                                              echo '<td align="center" align="center" width="15%">'.NOT_APPLICABLE_STRING.'</td>';
                                                            }
													    echo "</tr>";
													  }
  												      echo '<tr><td colspan="3" align="right" style="padding-right:10px" valign="bottom"><a href="listAdminMessages.php"><u>Show all Messages</u></a></td></tr>';
													}
													else{
												   	   echo '<tr><td align="center" style="height:100px" valign="middle" colspan="6" class="redColor">There are no new Messages to show</td></tr>';
													}
													?>
												</table>
											</td>
										</tr>
									</table>
								<?php
								//floatingDiv_Close();
								echo HtmlFunctions::getInstance()->tableBlueFooter();

								//*************End of Div*********
								?>
								</td>
									</tr>
									<tr>
										<td scope="col" valign="top" align="right">
											<?php
											echo HtmlFunctions::getInstance()->tableBlueHeader('Teacher Comments','width="320"','height="130"','');
											?>
												<table width="100%" border="0" height="125">
													<!--<tr class="rowheading2" >
														<th align="center" width="5%">#</th>
														<th width="30%" align="center">From</th>
														<th width="40%" align="center">Comments</t h>
														<th width="20%" align="center">Attachment</th>
													</tr>-->
													<tr>
														<td valign="top" colspan="4">
															<table border="0" width="100%" height="125">
																<?php
																$recordCountEvents = count($totalMessages);
																if($recordCountEvents >0 && is_array($totalMessages) ) {
																	for($i=0; $i<$recordCountEvents; $i++ ) {
																		$bg = $bg =='row0' ? 'row1' : 'row0';
																		$title="From : ".strip_slashes($totalMessages[$i]['visibleFromDate'])." To : ".strip_slashes($totalMessages[$i]['visibleToDate'])."
																		".trim_output2(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($totalMessages[$i]['comments']))),250,2);
																		echo '<tr class="'.$bg.'">
																		<td valign="top" align="center" width="4%" >'.($records+$i+1).'</td>
																		<td  valign="top" align="left" width="32%" >'.strip_slashes($totalMessages[$i]['employeeName']).'</td>
																		<td  valign="top" align="left" width="44%" ><a href="" name="bubble" onclick="showTeacherDetails('.$totalMessages[$i]['commentId'].',\'ViewTeacher\',650,350);return false;" title="'.$title.'">'
																		.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($totalMessages[$i]['comments']))),25).
																		'</td>';
																		//echo '<td align="center" width="20%">';
																		/*
																		$fileTeacherMessage = IMG_PATH."/Teacher/".$totalMessages[$i]['commentAttachment'];
																		if(file_exists($fileTeacherMessage) && ($totalMessages[$i]['commentAttachment']!="")){
																		$fileTeacherMessage1 = IMG_HTTP_PATH."/Teacher/".$totalMessages[$i]['commentAttachment'];
																		echo '<td valign="top" align="center"><a href="'.$fileTeacherMessage1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
																		}
																		else {
																		echo '<td align="center">'.NOT_APPLICABLE_STRING.'</td>';
																			}*/
																		echo '</tr>';
																	}
																	echo '<tr><td colspan="4" align="right" style="padding-right:10px" valign="bottom"><a href="listTeacherComments.php"><u>Show all Teacher Comments</u></a></td></tr>';
																}
																else{
																	echo '<tr><td style="height:120px" align="center" valign="middle" colspan="4" class="redColor">There are no new Teacher Comments</td></tr>';
																}
																?>
															</table>
														</td>
													</tr>
												</table>
												<?php
												//floatingDiv_Close();
												echo HtmlFunctions::getInstance()->tableBlueFooter();
												?>
											</td>
									</tr>
								</table>
								</td>

								<td scope="col" valign="top" align="right">
								<?php
								//*************Used For Creating*********
								//floatingDiv_Create('div_Alerts','Attendance Last Taken On');
								require_once(BL_PATH.'/HtmlFunctions.inc.php');
								echo HtmlFunctions::getInstance()->tableBlueHeader('Attendance Activities','width=320' ,'height=280','align=center');
								echo UtilityManager::includeJS("swfobject.js");
                                $flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
								?>
								<table width="100%" height="215" border="0">
									<tr>
										<td valign="top">
										<div id="flashcontent">
										<strong>You need to upgrade your Flash Player</strong>
										</div>
										<script type="text/javascript">
										//document.getElementById("resultRow").style.display='';
                                        //form = document.marksNotEnteredForm;
                                         var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "290", "282", "8", "#FFFFFF");
                                         so.addVariable("path", "./");
                                          so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
                                          x = Math.random() * Math.random();
                                          so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>140</y><rotate>true</rotate><text>Attendance Threshold ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size></category></values><plot_area><margins><bottom>60</bottom></margins></plot_area></settings>");
                                          so.addParam("wmode", "transparent");
                                          so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
                                          so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentAvgAttendanceBarData.xml?tt="+x));
                                          so.addVariable("preloader_color", "#999999");
                                        so.write("flashcontent");
                                       </script>
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
		</td>
	</tr>
</table>
</td>
</tr>
</table>
<?php
floatingDiv_Close();
//*************End of Div*********
?>
</div>
</td>
</tr>
</table>

<?php floatingDiv_Start('ViewEvents','Event Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewEvents" action="" method="post">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Event: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerTitle" style="overflow:auto; width:580px; height:20px" ></div>
	</td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
    <td valign="middle" colspan="2" style="padding-left:3px"><B>Visible From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="visibleToDate"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerShortDescription" style="overflow:auto; width:580px; height:20px" ></div>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Long Description: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="longDescription" style="overflow:auto; width:580px; height:200px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewNotices','Notice Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewNotices" action="" method="post">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Subject: </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerNotice" style="overflow:auto; width:580px; height:20px" ></div>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Department:</b></td>
</tr>

<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerDepartment" style="overflow:auto; width:580px; height:20px" ></div>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
	<td valign="middle" colspan="2" style="padding-left:3px"><B>Visible From</B>: <span id="visibleFromDate11" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="visibleToDate11"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Description: </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerText" style="overflow:auto; width:580px;height:200px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewAdmin','Admin Messages Description','',$wrapType=''); ?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<form name="viewAdmin" action="" method="post">
		<tr>
			<td height="5px"></td></tr>
		<tr>
		   <td width="100%"  align="left" class="rowheading">&nbsp;<b>Subject </b></td>
		</tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:1px">
			<div id="innerSubject" style="overflow:auto; width:580px;" ></div>
			</td>
		</tr>
		<tr>
			<td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
		</tr>
		<tr>
			<td valign="middle" colspan="2" style="padding-left:3px"><B>From</B>: <span id="visibleMessageFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleMessageToDate"></span></td>
		</tr>
		<tr>
		   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Message Detail </b></td>
		</tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:1px">
			<div id="innerAdmin" style="overflow:auto; width:580px; height:200px" ></div>
			</td>
		</tr>

		<tr>
			<td height="5px"></td>
		</tr>

		   </form>
		</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewTeacher','Teacher Comments Description','',$wrapType=''); ?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<form name="viewTeacher" action="" method="post">
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
		   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Teacher Comments Detail </b></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:10px">
			<br />
			<div id="innerTeacherNotice" style="overflow:auto; width:580px; height:200px" ></div>
			</td>
		</tr>

		<tr>
			<td height="5px"></td>
		</tr>

		</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewTasks','Task Description'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<form name="TaskDetail" action="" method="post">
	<input type="hidden" name="taskId" id="taskId" value="" />
	<tr>
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Title </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:&nbsp;
      <input type="text" id="title" name="title" style="width:170px" class="inputbox" />
     </td>
	</tr>
	<tr>
	  <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Short Description</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
	  <td align="top"><b>&nbsp;:&nbsp;&nbsp;</b><textarea name="shortDesc" id="shortDesc" cols="22" rows="3" style="vertical-align:top;"></textarea></td>
	 </tr>
	 <tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Task Date<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding" colspan="2">:&nbsp;&nbsp;<?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dueDate',date('Y-m-d'));
              ?>
      </td>
	</tr>
	<tr>
		<td width="35%" class="contenttab_internal_rows">&nbsp;<b>Reminder Option</b><?php echo REQUIRED_FIELD; ?></td>
		<td width="65%" ><b>&nbsp;:</b>&nbsp;
		  <input type="checkbox" id="dashboard" name="dashboard" checked="checked" />Dashboard
		  <input type="checkbox" id="sms" name="sms" />SMS
		</td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Prior Days<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding" colspan="2">:&nbsp;&nbsp;<input type="text" id="daysPrior" name="daysPrior" style="width:100px" class="inputbox" value="0" maxlength="2" />
      </td>
	</tr>

	<tr>
      <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Status</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td class="padding">:&nbsp;
      <select id="status" name="status" class="selectfield1" >
		<option value="0" selected="selected">Pending</option>
		<option value="1">Completed</option>
	  </select>
     </td>
	</tr>

  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ViewTasks');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</form>
</table>

<?php floatingDiv_End(); ?>

<?php
//$History : $
?>
