<?php
ob_start();
if(isset($_GET['id']) && $_GET['task']=='downloadAttachment' ) {	
	 $id= $_GET['id'];
	 $retval= selectAttachment($id);
		while($row = mysqli_fetch_array($retval)) {
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
								 <tr height="30" class="contenttab_border">
									<td  style="font-size:13px;"><strong>New Feature</strong></td>
									<td	 nowrap="nowrap" align="right" style="border-left:0px;margin-right:5px;"><a href ='../Interface/listNewFeature.php' style='color:#8d0a42;font-size:13px;padding-right:10px;'> <b>Back To New Feature</b></td>
								 </tr>
											<?php
		
									if(isset($_GET['id']) ) {
										//$flag = 0;
										$loginRoleId =  $sessionHandler->getSessionVariable('RoleId');
										$id = $_GET['id'];
										$descArray = selectDescription($id);
										$recordsFound = mysqli_num_rows($descArray);
										if ($recordsFound == 0) {
											$f=1;
										}
										else {
											while($row = mysqli_fetch_Array($descArray)) {
											  
											  $roleId = $row['roleId'];
											  $featureTitle = $row['featureTitle'];
											  $featureDescription = $row['featureDescription'];
											}
										}
											if($f==1) { ?>
														<tr>
														<td  class="contenttab_row" colspan="2" valign="top">
														<table border="0" align ="center" width="800px" style = "font-size:15px;">
															<tr>
																<td  align="center" ><b>Record Not Found</b></td>
															</tr>
														 </table>
														 </td>
														 </tr>
										<?php	}
											else if( $roleId == $loginRoleId or  $loginRoleId == 1 && $f!=1) {
														?>	<tr>
															<td  class="contenttab_row" colspan="2" valign="top">
															<table border="0" align ="center" width="700px" style = "font-size:14px;">
															<tr>
																<td width="25%" align="left" ><b>Feature Title</b></td>
																<td><b>:</b></td>
																<td align="left" ><b><?php  echo $featureTitle;?></b></td>
															</tr>	
															<tr>
																<td valign="top" ><b>Feature Description</b></td>
																<td valign="top"><b>:</b></td>
																 <td align="justify" bgcolor="#f5f5f5" ><?php echo $featureDescription; ?></td>
															</tr>
															<tr><?php
																echo "<td valign='top'><b>Download Attachment</b></td>
																<td valign='top'><b>:</b></td>
																 <td align='left' >
																 <a href='../Interface/listNewFeatureDetails.php?id=".$id."&task=downloadAttachment'><img src='../Storage/images/download.gif' border ='0' height='18'/></a></td>
															</tr>
														</table> </td></tr>";?>
												<?php }
													
												else  {   ?>
													<tr>
													<td  class="contenttab_row" colspan="2" valign="top">
															<table border="0" align ="center" width="800px" style = "font-size:15px;">
															<tr>
																<td  align="center" ><b>Record Not Found</b></td>
															</tr>
														  </table>
													</td>
													</tr>
											<?php   }
								
										
											 ?>
											
									   </td>
								</tr>
							</table>
						
											
							</table>						
							</td>
							</tr>
							</table>

	<?php } 
	 
		else {?>
		<tr>
		<td  class="contenttab_row" colspan="2" valign="top" >
		<table border="0" align ="center" width="800px" style = "font-size:15px;">
			<tr>
				<td  align="center" ><b>Record Not Found</b></td>
			</tr>
		 </table>
		 </td>
		 </tr>
		
		
    <?php }  ob_flush(); ?>