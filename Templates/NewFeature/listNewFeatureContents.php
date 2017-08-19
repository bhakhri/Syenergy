<?php
//-------------------------------------------------------
//  This File is used for for new feature
//
// 
//
//--------------------------------------------------------
//require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="title">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						<td valign="top">New Feature</td>
						<td valign="top" align="right"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top" class="content">
							 <table width="100%" border="0" cellspacing="0" cellpadding="0">
								 <tr height="25">
									<td class="contenttab_border" style="font-size:14px;"><strong>New Feature</strong> </td>
								 </tr>
								
								 <tr>
								   <td>
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" style = "font-size:20px;">
											
							
											<?php
												
												if(isset($_GET['id']) &&  $_GET['task']=='downloadAttachment') {
													
													$id = $_GET['id'];
												
													$retval= selectAttachment($id);
													while($row = mysql_fetch_array($retval)) {
														$attachment = $row['attachment'];
														if($attachment!= '') {
															
															$livePath = $fileDownloadPath.$attachment;
															$localPath = STORAGE_PATH . "/Broadcast/$attachment";
															$ch = curl_init($livePath);
															if(!file_exists($localPath)) {
																$fp = fopen($localPath, "w");
																curl_setopt($ch, CURLOPT_FILE, $fp);
																curl_setopt($ch, CURLOPT_HEADER, 0);
																curl_exec($ch);
																fclose($fp);
															}
															curl_close($ch);
															header("Location:../Library/download.php?t=dbf&fn=$attachment");
															die;
														}
													}
												}
												 $roleId = $sessionHandler->getSessionVariable('RoleId');
													
												 if ($roleId == 2 or $roleId == 3 or $roleId == 4 or $roleId == 5) {
													 
														$retval = selectBroadcastRole($roleId);
												 }
												 else {
													 
													  $retval = selectBroadcast();
												 }
											?>
											<tr>
											<td  class="contenttab_row" colspan="2" valign="top">
											<table border="0" width="1000px" style = "font-size:13px;">
													<tr bgcolor="#DCDCDC" class="rowheading" >
														<th class="searchhead_text" width = "5%" style="text-align:left;">&nbsp;#</th>
														<th class="searchhead_text" width = "22%" style="text-align:left;">Feature Title</th>
														<th class="searchhead_text" width = "32%" style="text-align:left;">Feature Description</th>
														<th class="searchhead_text" width = "30%" style="text-align:left;">Menu Path</th>
														<th class="searchhead_text" width = "18%" style="text-align:left;">Attachment</th>
													</tr>
															
											<?php
												$srNo=0;
												
												while($row = mysql_fetch_array($retval)) {
													$featureId = $row['featureId'];
													$featureTitle = $row['featureTitle'];
													$featureDescription = $row['featureDescription'];
													$menuPath = $row['menuPath'];
													$attachment = $row['attachment'];
													$str = $featureDescription;
													$strDesc='';
													
													if(strlen($str)>=100) {
														$strDesc = substr($str,0,100)."<div align='right'><a href ='../Interface/listNewFeatureDetails.php?id=".$featureId." ' style='color:#8d0a42;'><span style='font-size:11px;'>read more...</a></div>";
													 }
													 else {
														$strDesc = $featureDescription;
													 }
													
													$srNo++;

													if($srNo%2 != 0)
														echo "<tr bgcolor='#FFFFFF'>";
													else
														echo "<tr bgcolor='#f0f0f0'>";
													echo"<td align ='left'>".$srNo. "</td>
														<td align ='left'><a href ='../Interface/listNewFeatureDetails.php?id=".$featureId." ' style='color:#8d0a42;'>".$featureTitle."</a></td>
														<td align ='left'>".$strDesc."</td>
														<td align ='left'>".$menuPath."</td>";
													
														if ($attachment!='') {
															
															echo "<td align ='center'> 
																  <a href='../Interface/listNewFeature.php?id=".$featureId."&task=downloadAttachment'><img src='../Storage/images/download.gif' border ='0' height='18'/></a>
																  
															</td>";
														}
														else {
															 echo "<td align ='center'> ---    
																</td>";
														}
												?>		
													</tr>
														
											<?php
											}  
											?>
													
									</table>
								 </table>						
								</td>
								</tr>
					</table>
			   </td>
		</tr>
    </table>
	
	
