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
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>    
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
					<table width='100%' class='accessDenied'>
						<tr>
							<td><?php echo ACCESS_DENIED;?></td>
						</tr>
					</table><br>
					<?php } 
					
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
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
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="400">
										<tr>
											<td class="padding_top" align="center"><b><?php echo $greetingMsg;  ?></b></td>
										</tr>
										<tr>
											<td valign="top"  align="left" >
											<table width="960" border="0" align="center">
											<tr>
												<td valign="top">
												<table cellspacing="0" cellpadding="3" border="0">
												<tr>
													<td valign="top">
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->tableBlueHeader('Summary','width=315' ,'height=234','align=center');
															echo UtilityManager::includeJS("swfobject.js");
															$flashPath = IMG_HTTP_PATH."/ampie.swf";
														?>
														<table width="100%"  height="200" border="0">
														<tr>
															<td valign="top">
															<table width="100%" height="150" border="0">
															<tr>
																<td valign="top">
																<table width="100%" border="0">
																<tr>
																	<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
																	<td valign="top" class="padding_top">Total Student Enquiry</td> 
																	<td valign="top" class="padding_top" align="right">
																	<?php echo $studentTotalArray[0]['totalCount'];?></a></td>
																</tr>
																<tr>
																	<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
																	<td valign="top" class="padding_top">Today's Total Student Enquiry</td> 
																	<td valign="top" class="padding_top" align="right"><?php echo $studentTotalTodayArray[0]['totalCount'];?></td>
																</tr>
																<tr>
																	<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
																	<td valign="top" class="padding_top">Total Counselor</td> 
																	<td valign="top" class="padding_top" align="right"><?php echo $studentTotalConsularArray[0]['totalCount']?></td>
																</tr>
																<tr>
																	<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
																	<td valign="top" class="padding_top">Total Male Student</td> 
																	<td valign="top" class="padding_top" align="right"><?php echo count($studentTotalMaleArray)?></td>
																</tr>
																
																<tr>
																	<td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
																	<td valign="top" class="padding_top">Total Female Student</td> 
																	<td valign="top" class="padding_top" align="right"><?php echo count($studentTotalFeMaleArray)?></td>
																</tr>
																 
																</table>
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
												</table>
												</td>
												<td valign="top">
												<table cellspacing="0" cellpadding="3" border="0">
												<tr>
													<td valign="top">
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->tableBlueHeader('City Wise','width=315' ,'height=234','align=center');
															echo UtilityManager::includeJS("swfobject.js");
															$flashPath = IMG_HTTP_PATH."/ampie.swf";
														?>
														<table width="100%"  height="200" border="0">
														<tr>
															<td valign="top">
															<table width="100%" border="0">
															<tr>
																<td valign="top">
																<div id="flashcontent4">
																	<strong>You need to upgrade your Flash Player</strong>
																</div>
																<script type="text/javascript">
																  x = Math.random() * Math.random();
																  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
																  so.addVariable("path", "ampie/");  
																  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
																  so.addParam("wmode", "transparent");
																  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
																  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
																  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryCityData.xml?t="+x));
																  so.addVariable("preloader_color", "#999999");
																  so.write("flashcontent4");
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
												</table>
												</td>
												<td valign="top">
												<table cellspacing="0" cellpadding="3" border="0">
												<tr>
													<td valign="top">
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->tableBlueHeader('State Wise','width=315' ,'height=234','align=center');
															echo UtilityManager::includeJS("swfobject.js");
															$flashPath = IMG_HTTP_PATH."/ampie.swf";
														?>
														<table width="100%"  height="200" border="0">
														<tr>
															<td valign="top">
															<table width="100%" border="0">
															<tr>
																<td valign="top">
																<div id="flashcontent5">
																	<strong>You need to upgrade your Flash Player</strong>
																</div>
																<script type="text/javascript">
																  x = Math.random() * Math.random();
																  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
																  so.addVariable("path", "ampie/");  
																  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
																  so.addParam("wmode", "transparent");
																  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
																  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
																  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryStateData.xml?t="+x));
																  so.addVariable("preloader_color", "#999999");
																  so.write("flashcontent5");
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
												</table>
												</td>
											</tr>
											<tr>
												<td valign="top">
												<table cellspacing="0" cellpadding="3" border="0">
												<tr>
													<td valign="top">
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->tableBlueHeader('Counselor  Wise','width=315' ,'height=234','align=center');
															echo UtilityManager::includeJS("swfobject.js");
															$flashPath = IMG_HTTP_PATH."/ampie.swf";
														?>
														<table width="100%"  height="200" border="0">
														<tr>
															<td valign="top">
															<table width="100%" border="0">
															<tr>
																<td valign="top">
																<div id="flashcontent1">
																	<strong>You need to upgrade your Flash Player</strong>
																</div>
																<script type="text/javascript">
																  x = Math.random() * Math.random();
																  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
																  so.addVariable("path", "ampie/");  
																  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
																  so.addParam("wmode", "transparent");
																  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
																  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
																  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryConsolerData.xml?t="+x));
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
												</tr>
												</table>
												</td>
												<td valign="top">
												<table cellspacing="0" cellpadding="3" border="0">
												<tr>
													<td valign="top">
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->tableBlueHeader('Gender Wise','width=315' ,'height=234','align=center');
															echo UtilityManager::includeJS("swfobject.js");
															$flashPath = IMG_HTTP_PATH."/ampie.swf";
														?>
														<table width="100%"  height="200" border="0">
														<tr>
															<td valign="top">
															<table width="100%" border="0">
															<tr>
																<td valign="top">
																<div id="flashcontent2">
																	<strong>You need to upgrade your Flash Player</strong>
																</div>
																<script type="text/javascript">
																  x = Math.random() * Math.random();
																  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
																  so.addVariable("path", "ampie/");  
																  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
																  so.addParam("wmode", "transparent");
																  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
																  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
																  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryGenderData.xml?t="+x));
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
												</table>
												</td>
												<td valign="top">
												<table cellspacing="0" cellpadding="3" border="0">
												<tr>
													<td valign="top">
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
															echo HtmlFunctions::getInstance()->tableBlueHeader('Programme Wise','width=315' ,'height=234','align=center');
															echo UtilityManager::includeJS("swfobject.js");
															$flashPath = IMG_HTTP_PATH."/ampie.swf";
														?>
														<table width="100%"  height="200" border="0">
														<tr>
															<td valign="top">
															<table width="100%" border="0">
															<tr>
																<td valign="top">
																<div id="flashcontent6">
																	<strong>You need to upgrade your Flash Player</strong>
																</div>
																<script type="text/javascript">
																  x = Math.random() * Math.random();
																  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
																  so.addVariable("path", "ampie/");  
																  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
																  so.addParam("wmode", "transparent");
																  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
																  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
																  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryDegreeData.xml?t="+x));
																  so.addVariable("preloader_color", "#999999");
																  so.write("flashcontent6");
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
												</table>
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
// $History: enquiryDashBoardContent.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 23/12/09   Time: 15:42
//Updated in $/LeapCC/Templates/Index
//Fixed bug:0002306
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 6/03/09    Time: 6:58p
//Updated in $/LeapCC/Templates/Index
//Changed statistics to summary label
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/03/09    Time: 12:28p
//Updated in $/LeapCC/Templates/Index
//Updated with "counselor" spelling
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/03/09    Time: 12:12p
//Created in $/LeapCC/Templates/Index
//Intial checkin for student enquiry demographics for admin
?>