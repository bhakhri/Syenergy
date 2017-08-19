<?php
 $moduleHeadArray = array();
 $moduleCheckArray = array();
$roleId = $sessionHandler->getSessionVariable('RoleId');
if ($roleId != 1) {
	require_once(TEMPLATES_PATH . "/RolePermissions/listUserRolePermissions.php");
}
else {
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
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
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title"> Role Permission : </td>
						<td class="content_title" align='right'>
						<span style='color:#FD7E00;background-color:#f3f18b;'>Click To Select / Deselect</span>&nbsp;&nbsp;&nbsp;&nbsp;
						<span style='color:#bb0000;background-color:#f3f18b;'>Click To Select / Deselect</span>&nbsp;&nbsp;&nbsp;&nbsp;
						<span style='color:black'>Text</span>
						</td>
                    </tr>
                    </table>
			     </td>
             </tr>
			 <form action="" method="POST" name="listForm" id="listForm" onSubmit="return false;">
			
             <tr>
               <td class="contenttab_row" valign="top" >
			   <div id="results">

				<table border="0" cellspacing="5px" cellpadding="0" align ="center"valign="top" class="contenttab_internal_rows" nowrap>
				<tr><td height="10px"</tr>
				<tr>
	
				<td valign="top"  class="contenttab_internal_rows" nowrap><nobr><b>Role Name &nbsp; &nbsp;: &nbsp;</b></nobr></td>
				<td class="padding" valign="top" class="contenttab_internal_rows" nowrap>
				<select size="1" valign="top" name="roleId" id="roleId" style="width:230px;" class="selectfield" onChange="getPermissions()">
				<option value="">Select</option>
				<?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->getRoles();
				?>
				</select>
				</td>
				<td  align="right" style="padding-left:35px" class="padding">
				<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />
				</td>
	
				</tr>
				</table> 
					<table border='0' cellspacing='1' cellpadding='1' width='100%'>
							<tr class="rowheading">
							<td  colspan='1' class='searchhead_text' width='16%'>Heading</td>
							<td  colspan='1' class='searchhead_text' width='22%'>Menu</td>
							<td  colspan='1' class='searchhead_text' width='32%'>Sub Menu</td>
							<td valign='middle' colspan='1' class='searchhead_text' width='7%'>
									<B>View</B>
							</td>
							<td valign='middle' colspan='1'class='searchhead_text' width='7%'>
									<B>Add</B>
							</td>
							<td valign='middle' colspan='1' class='searchhead_text' width='7%'>
									<B>Edit</B>
							</td>
							<td valign='middle' colspan='1' class='searchhead_text' width='7%'>
									<B>Delete</B>
							</td>
							<td valign='middle' colspan='1' class='searchhead_text' width='7%'>
									<B>All Institutes</B>
							</td>
						</tr>
						
					<?php
				       // require_once(BL_PATH . '/menuItemsDescription.php');
						$mainMenuCounter = 0;
						$subInnerMenuCounter = 0;
						if ($roleId == 2) {
							require_once(BL_PATH . "/teacherMenuItems.php");
							$allMenus = $allTeacherMenus;
						}
						else if ($roleId == 3) {
							require_once(BL_PATH . "/parentMenuItems.php");
							$allMenus = $allParentMenus;
						}
						else if ($roleId == 4) {
							require_once(BL_PATH . "/studentMenuItems.php");
							$allMenus = $allStudentMenus;
						}
						else if($roleId == 5) {
							require_once(BL_PATH . "/managementMenuItems.php");
							$allMenus = $allManagementMenus;
						}
						foreach($allMenus as $independentMenu) {
							foreach($independentMenu as $menuItemArray) {
								if ($menuItemArray[0] == SET_MENU_HEADING) {

									$moduleLabel = $menuItemArray[1];
									$subMenuCounter = 0;

									$includeHeading = false;

									$mainMenuCounter++;
									 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
									$headingText = "<tr $bg><td valign='top' colspan='1' class='siteMapMainHeading'>$mainMenuCounter . $moduleLabel</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

								}
								elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
									 $moduleName = $menuItemArray[2][0];
									 $moduleLabel = $menuItemArray[2][1];
                                     //$description = $menuItemArray[2][4];
						
									 $sessionModule = $sessionHandler->getSessionVariable($moduleName);
									 if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
										 continue;
									 }
									 else {
										 if ($includeHeading == false) {

											 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
											 echo $headingText;
											 $includeHeading = true;
										 }
									 }
									 $moduleLink = $menuItemArray[2][2];
                                     $subMenuCounter++;
									 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';

									if (!is_array($menuItemArray[2][3])) {
											$permissionArray = array(ADD,EDIT,VIEW,DELETE);
										}
										else {
											$permissionArray = $menuItemArray[2][3];
										}
										$viewPermission = "disabled";
										if (in_array(VIEW, $permissionArray)) {
											$viewPermission = "";
										}
										$editPermission = "disabled";
										if (in_array(EDIT, $permissionArray)) {
											$editPermission = "";
										}
										$addPermission = "disabled";
										if (in_array(ADD, $permissionArray)) {
											$addPermission = "";
										}
										$deletePermission = "disabled";
										if (in_array(DELETE, $permissionArray)) {
											$deletePermission = "";
										}
					?>
								<tr <?php echo $bg;?> ><td></td><td valign='top' colspan='1' class='siteMapMainHeading2'><?php echo $mainMenuCounter . '.' .$subMenuCounter . '. ';?>
								
									<a class='redLink' href="javascript:void(0);" onClick="doAll('<?php echo $moduleName;?>');"><?php echo $moduleLabel;?></a></td>
									<td></td>
									<td valign='middle' colspan='1' class=''>
									<span id = "<?php echo $moduleName;?>_viewPermissionSpan">
										<input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
									</span>
									</td>
									<td valign='middle' colspan='1' class=''>
									<span id = "<?php echo $moduleName;?>_addPermissionSpan">
										<input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
									</span>
									</td>
									<td valign='middle' colspan='1' class=''>
									<span id = "<?php echo $moduleName;?>_editPermissionSpan">
										<input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
									</span>
									</td>
									<td valign='middle' colspan='1' class=''>
									<span id = "<?php echo $moduleName;?>_deletePermissionSpan">
										<input type='checkbox'  onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
									</span>
									</td>
									<td valign='middle' colspan='1' class=''>
									   <span id = "<?php echo $moduleName;?>_allInstitutePermissionSpan">
										<input type='checkbox' onClick="getCheck(this.name)"   name='<?php echo $moduleName;?>_allInstitutePermission'<?php echo $allInstitutePermission;?>>
									  </span>
									</td>  
								</tr>
								<?php
									
								}
								elseif($menuItemArray[0] == MAKE_MENU) {
									$moduleHeadLabel = $menuItemArray[1];
									$includeSubHeading = false;

									$subInnerMenuCounter = 0;
									foreach($menuItemArray[2] as $moduleMenuItem) {
										$moduleName = $moduleMenuItem[0];
										$moduleHeadArray[$moduleHeadLabel][] = $moduleName;
										$moduleCheckArray[$moduleHeadLabel][$moduleName] = false;
										
										?>
																		
										<?php
																				
										 $sessionModule = $sessionHandler->getSessionVariable($moduleName);
										 if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
											 continue;
										 }
										 else {
											 if ($includeHeading == false) {
												 //$mainMenuCounter++;
												 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
												 echo $headingText;
												 $includeHeading = true;
											 }
											 if ($includeSubHeading == false) {
												 $subMenuCounter++;
												 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
												 echo "<tr $bg><td></td><td valign='center' height='23' colspan='1' class='siteMapMainHeading2'>$mainMenuCounter .  $subMenuCounter . ";
												 ?>
												 <a href="javascript:void(0);" onClick="selectChildren('<?php echo $moduleHeadLabel;?>');" class="newLinkSimple"><?php echo $moduleHeadLabel;?></a></td><td></td><td></td><td></td><td></td><td></td></tr>
												 <?php
												 $includeSubHeading = true;
											 }

										 }
										$moduleLabel = strip_tags($moduleMenuItem[1]);
										$moduleLink = $moduleMenuItem[2];
                                        //$description = $moduleMenuItem[4];

										$subInnerMenuCounter++;
										$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';

												if (!is_array($moduleMenuItem[3])) {
												$permissionArray = array(ADD,EDIT,VIEW,DELETE);
											}
											else {
												$permissionArray = $moduleMenuItem[3];
											}
											$viewPermission = "disabled";
											if (in_array(VIEW, $permissionArray)) {
												$viewPermission = "";
											}
											$editPermission = "disabled";
											if (in_array(EDIT, $permissionArray)) {
												$editPermission = "";
											}
											$addPermission = "disabled";
											if (in_array(ADD, $permissionArray)) {
												$addPermission = "";
											}
											$deletePermission = "disabled";
											if (in_array(DELETE, $permissionArray)) {
												$deletePermission = "";
											}
								?>
										<tr <?php echo $bg;?>><td></td><td></td><td valign='top' colspan='1' class='contenttab_internal_rows'><?php echo $mainMenuCounter . '.' .$subMenuCounter . '.' .$subInnerMenuCounter . '. ';?>
										  <!-- <a class='redLinkSimple'  href='<?php echo $moduleLink;?>'><?php echo $moduleLabel;?></a> -->
										  <a class='redLinkSimple' href="javascript:void(0);" onClick="doAll('<?php echo $moduleName;?>');"><?php echo $moduleLabel;?></a>
										</td>
												<td valign='middle' colspan='1' class=''>
													<span id = "<?php echo $moduleName;?>_viewPermissionSpan">
													<input type='checkbox'  onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
													</span>
												</td>
												<td valign='middle' colspan='1' class=''>
												<span id = "<?php echo $moduleName;?>_addPermissionSpan">
													<input type='checkbox'  onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
												</span>
												</td>
												<td valign='middle' colspan='1' class=''>
													<span id = "<?php echo $moduleName;?>_editPermissionSpan">
													<input type='checkbox'  onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
													</span>
												</td>
												<td valign='middle' colspan='1' class=''>
													<span id = "<?php echo $moduleName;?>_deletePermissionSpan">
													<input type='checkbox'  onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
													</span>
												</td>
												<td valign='middle' colspan='1' class=''>
													<span id = "<?php echo $moduleName;?>_allInstitutePermissionSpan">
													<input type='checkbox' onClick="getCheck(this.name)"   name='<?php echo $moduleName;?>_allInstitutePermission'>
													</span>
												</td>
											
										</tr>
										<?php
									}
								}
								elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
									$moduleArray = $menuItemArray[1];
									$subMenuCounter = 0;
									list($moduleName, $menuLabel,$menuLink,$description) = explode(',',$moduleArray);
									 $sessionModule = $sessionHandler->getSessionVariable($moduleName);
									 if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
										 continue;
									 }
									$mainMenuCounter++;
									$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
									if (!is_array($menuItemArray[2])) {
											$permissionArray = array(ADD,EDIT,VIEW,DELETE);
										}
										else {
											$permissionArray = $menuItemArray[2];
										}
										$viewPermission = "disabled";
										if (in_array(VIEW, $permissionArray)) {
											$viewPermission = "";
										}
										$editPermission = "disabled";
										if (in_array(EDIT, $permissionArray)) {
											$editPermission = "";
										}
										$addPermission = "disabled";
										if (in_array(ADD, $permissionArray)) {
											$addPermission = "";
										}
										$deletePermission = "disabled";
										if (in_array(DELETE, $permissionArray)) {
											$deletePermission = "";
										}
								?>
								<tr <?php echo $bg;?>><td valign='top' colspan='1' class='siteMapMainHeading'><?php echo $mainMenuCounter . '. '?>
								<!-- 
									<a class='siteMapMainHeading' href='<?php echo $menuLink;?>'><?php echo $menuLabel;?></a>
									-->

									 <a class='redLinkSimple' href="javascript:void(0);" onClick="doAll('<?php echo $moduleName;?>');"><?php echo $menuLabel;?></a>
									</td><td></td><td></td>
										<td valign='middle' colspan='1' class=''>
											<span id = "<?php echo $moduleName;?>_viewPermissionSpan">
											<input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
											</span>
										</td>
										<td valign='middle' colspan='1' class=''>
											<span id = "<?php echo $moduleName;?>_addPermissionSpan">
											<input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
											</span>
										</td>
										<td valign='middle' colspan='1' class=''>
										<span id = "<?php echo $moduleName;?>_editPermissionSpan">
											<input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
										</span>
										</td>
										<td valign='middle' colspan='1' class=''>
				 						   <span id = "<?php echo $moduleName;?>_deletePermissionSpan">
									          <input type='checkbox' onClick="getCheck(this.name)" name='<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
									       </span>
									    </td> 
									    <td valign='middle' colspan='1' class=''>
										<span id = "<?php echo $moduleName;?>_allInstitutePermissionSpan">
									      <input type='checkbox' onClick="getCheck(this.name)"  name='<?php echo $moduleName;?>_allInstitutePermission'  id='<?php echo $moduleName;?>_allInstitutePermission' >
									    </span>
										</td>
									    									 		  					
								</tr>
								<?php
								}

							}
						}//checking tab name 
	
						//print_r($dashboardFrameArray);
						$recordCount = count($dashboardFrameArray);
						if($recordCount >0 && is_array($dashboardFrameArray) ) {
						  $find=0;
							for($i=0; $i<$recordCount; $i++ ) {
						     if($titleName!=$dashboardFrameArray[$i]['titleName']) {
						       $find=0; 
						     }	

					  $titleName=$dashboardFrameArray[$i]['titleName'];
			
						     if($titleName==$dashboardFrameArray[$i]['titleName'] && $find==0) {
							echo "<tr>
					<td valign='middle'><b>".$titleName."</b></td></tr>";
								
							$find=1;	
						     }
							 
							 $viewPermission = "disabled";
							 if ($dashboardFrameArray[$i]['viewPermission']==1) {
						 		$viewPermission = "";
							 }
							 $editPermission = "disabled";
							 if ($dashboardFrameArray[$i]['editPermission']==1) {
								$editPermission = "";
							 }
							 $addPermission = "disabled";
							 if ($dashboardFrameArray[$i]['addPermission']==1) {
								$addPermission = "";
							 }
							 $deletePermission = "disabled";
							 if ($dashboardFrameArray[$i]['deletePermission']==1) {
								$deletePermission = "";
							 }



								$bg = $bg =='row0' ? 'row1' : 'row0';
								$moduleName = strip_slashes($dashboardFrameArray[$i]['frameName']);
								//echo $moduleName;
							?>
								<tr class="<?php echo $bg;?>" >
									</td><td>
							  	   <td valign='top' colspan='1' class="redLink">
										<?php echo trim($moduleName); ?></td>
										<td>
  								    </td>
  								<!--			    
									<td valign='middle' colspan='4' class=''>
										<span id = "chb<?php echo $moduleName;?>Span">
										
										<input type='checkbox' onClick="getCheck(this.name)"     id='chb<?php echo $moduleName;?>' name='chb<?php echo $moduleName;?>' <?php echo $viewPermission;?>>
										</span>
									</td>
								-->
									<td valign='middle' colspan='1' class=''>
										<span id = "chb<?php echo $moduleName;?>_viewPermissionSpan">
										   <input type='checkbox' onClick="getCheck(this.name)" name='chb<?php echo $moduleName;?>_viewPermission'  id='chb<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
										</span>
									</td>
									<td valign='middle' colspan='1' class=''>
										<span id = "chb<?php echo $moduleName;?>_addPermissionSpan">
										  <input type='checkbox' onClick="getCheck(this.name)" name='chb<?php echo $moduleName;?>_addPermission'  id='chb<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
										</span>
									</td>
									<td valign='middle' colspan='1' class=''>
										<span id = "chb<?php echo $moduleName;?>_editPermissionSpan">
  									  	  <input type='checkbox' onClick="getCheck(this.name)" name='chb<?php echo $moduleName;?>_editPermission'  id='chb<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
										</span>
									</td>
									<td valign='middle' colspan='1' class=''>
										<span id = "chb<?php echo $moduleName;?>_deletePermissionSpan">
									      <input type='checkbox' onClick="getCheck(this.name)" name='chb<?php echo $moduleName;?>_deletePermission'  id='chb<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
									    </span>
									</td>  
									
									<td valign='middle' colspan='1' class=''>
										<span id = "chb<?php echo $moduleName;?>_allInstitutePermissionSpan">
									      <input type='checkbox' onClick="getCheck(this.name)"  name='chb<?php echo $moduleName;?>_allInstitutePermission'  id='chb<?php echo $moduleName;?>_allInstitutePermission'>
									    </span>
									</td>  
											  					
							<?php
							
							                               			 
		

						  }
						}

						$moduleHeadJSON = json_encode($moduleHeadArray);
						$moduleCheckJSON = json_encode($moduleCheckArray);
						
						?>



					<tr>
					<td  align="right" style="padding-right:5px" colspan="9"><input type="hidden" name="recordCount" value="<?php echo $recordCount+100?>">
					<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />&nbsp;&nbsp;<!--a href="#" onClick="printTimeTableReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a-->&nbsp;</td>
				 </tr>
					</table>
			   </div>
			   </form>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
 <?php
							
							
																
}
?>
