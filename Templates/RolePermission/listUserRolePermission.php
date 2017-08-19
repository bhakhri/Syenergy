<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top"><?php echo $pageTitle;?></td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
				<td valign="top" class="content">
				<form action="" method="POST" name="listForm" id="listForm" onSubmit="return false;">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="contenttab_border">
						<table border='0' cellspacing='0' cellpadding='0'>
							<tr>
								<td valign='top' colspan='1' class="content_title">
									Role Permissions
								</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td class="contenttab_border2">
							<table border="0" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td class="contenttab_internal_rows" valign="top"><nobr><b>Role Name: </b></nobr></td>
								<td valign="top">&nbsp;&nbsp;<select size="1" class="inputbox1" name="roleId" id="roleId" onChange="getPermissions()">
								<option value="">Select</option>
								<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getRoles(" AND roleId != $roleId");
								?>
								</select></td>
								<td valign="top" align="right" style="padding-right:5px">&nbsp;&nbsp;<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" /></td>
							</tr>
							<tr>
								<td colspan="4" height="5px"></td>
							</tr>
							</table>
					    </td>
					</tr>
					<tr>
					<td class="contenttab_row" valign="top" ><div id="results">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
								<tr>
									<td valign='middle' colspan='1' class=''>
									<B>List of Modules	</B>
									</td>
									<td valign='middle' colspan='1' class=''>
										<B>View</B>
									</td>
									<td valign='middle' colspan='1' class=''>
										<B>Add</B>
									</td>
									<td valign='middle' colspan='1' class=''>
										<B>Edit</B>
									</td>
									<td valign='middle' colspan='1' class=''>
										<B>Delete</B>
									</td>
								</tr>
						<?php
							foreach($allMenus as $independentMenu) {
								$bg='row1';
								foreach($independentMenu as $menuItemArray) {
									$headingPrinted = false;
									$heading = '';
									if ($menuItemArray[0] == SET_MENU_HEADING) {
										$heading = "
												<tr class='showHideRow'>
													<td valign='middle' colspan='5' class='headingMenu padLeft2'>".$menuItemArray[1]."
													</td>
												</tr>";
												echo $heading;
									}
									elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
										 $moduleName = $menuItemArray[2][0];
										 $moduleLabel = $menuItemArray[2][1];
										if (!isset($_SESSION[$moduleName])  or !is_array($_SESSION[$moduleName])) {
											continue;
										}

										/*
										if ($heading != '' and $headingPrinted == false) {
											echo $heading;
											$headingPrinted = true;
										}
										*/

										$permissionArray = array();
										foreach($_SESSION[$moduleName] as $key => $value) {
											if ($value == 1) {
												if ($key == 'view') {
													$permissionArray[] = VIEW;
												}
												elseif ($key == 'add') {
													$permissionArray[] = ADD;
												}
												elseif ($key == 'edit') {
													$permissionArray[] = EDIT;
												}
												elseif ($key == 'delete') {
													$permissionArray[] = DELETE;
												}
											}
										}
										$allDisabled = true;
										$viewPermission = "disabled";
										if (in_array(VIEW, $permissionArray)) {
											$viewPermission = "";
											$allDisabled = false;
										}
										$editPermission = "disabled";
										if (in_array(EDIT, $permissionArray)) {
											$editPermission = "";
											$allDisabled = false;
										}
										$addPermission = "disabled";
										if (in_array(ADD, $permissionArray)) {
											$addPermission = "";
											$allDisabled = false;
										}
										$deletePermission = "disabled";
										if (in_array(DELETE, $permissionArray)) {
											$deletePermission = "";
											$allDisabled = false;
										}
										if ($allDisabled == true) {
											continue;
										}
										$bg = $bg=='row0' ? 'row1' : 'row0';
									?>
										<tr class="<?php echo $bg;?>" id='<?php echo $moduleName;?>'>
											<td valign='middle' colspan='1' class='padLeft20'>
												<?php echo $moduleLabel;?>
											</td>
											<td valign='middle' colspan='1' class=''>
											<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
											<td valign='middle' colspan='1' class=''>
												<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
											</td>
											<td valign='middle' colspan='1' class=''>
												<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
											</td>
											<td valign='middle' colspan='1' class=''>
												<input type='checkbox'  onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
											</td>
										</tr>
									<?php
									}
									elseif($menuItemArray[0] == MAKE_MENU) {
										$moduleHeadLabel = $menuItemArray[1];
										$bg='row1';

										foreach($menuItemArray[2] as $moduleMenuItem) {
											$moduleName = $moduleMenuItem[0];
											$moduleLabel = $moduleMenuItem[1];
											if (!isset($_SESSION[$moduleName])) {
												continue;
											}
											else {


												foreach($menuItemArray[2] as $moduleMenuItem) {
													$moduleName = $moduleMenuItem[0];
													$moduleLabel = $moduleMenuItem[1];
													if (!isset($_SESSION[$moduleName]) or !is_array($_SESSION[$moduleName])) {
														break;
													}
													$permissionArray = array();
													foreach($_SESSION[$moduleName] as $key => $value) {
														if ($value == 1) {
															if ($key == 'view') {
																$permissionArray[] = VIEW;
															}
															elseif ($key == 'add') {
																$permissionArray[] = ADD;
															}
															elseif ($key == 'edit') {
																$permissionArray[] = EDIT;
															}
															elseif ($key == 'delete') {
																$permissionArray[] = DELETE;
															}
														}
													}
													$allDisabled = true;
													$viewPermission = "disabled";
													if (in_array(VIEW, $permissionArray)) {
														$viewPermission = "";
														$allDisabled = false;
													}
													$editPermission = "disabled";
													if (in_array(EDIT, $permissionArray)) {
														$editPermission = "";
														$allDisabled = false;
													}
													$addPermission = "disabled";
													if (in_array(ADD, $permissionArray)) {
														$addPermission = "";
														$allDisabled = false;
													}
													$deletePermission = "disabled";
													if (in_array(DELETE, $permissionArray)) {
														$deletePermission = "";
														$allDisabled = false;
													}
													if ($allDisabled == true) {
														break;
													}
													else {
													?>
														<tr class='headingSubMenuRow'>
															<td valign='middle' colspan='5' class='headingSubMenu'>
																<?php echo $moduleHeadLabel;?>
															</td>
														</tr>
													<?php
														break;
													}
												}
												break;
											}
										}
										foreach($menuItemArray[2] as $moduleMenuItem) {
											$moduleName = $moduleMenuItem[0];
											$moduleLabel = $moduleMenuItem[1];
											if (!isset($_SESSION[$moduleName])  or !is_array($_SESSION[$moduleName])) {
												continue;
											}
											$permissionArray = array();
											foreach($_SESSION[$moduleName] as $key => $value) {
												if ($value == 1) {
													if ($key == 'view') {
														$permissionArray[] = VIEW;
													}
													elseif ($key == 'add') {
														$permissionArray[] = ADD;
													}
													elseif ($key == 'edit') {
														$permissionArray[] = EDIT;
													}
													elseif ($key == 'delete') {
														$permissionArray[] = DELETE;
													}
												}
											}
											$allDisabled = true;
											$viewPermission = "disabled";
											if (in_array(VIEW, $permissionArray)) {
												$viewPermission = "";
												$allDisabled = false;
											}
											$editPermission = "disabled";
											if (in_array(EDIT, $permissionArray)) {
												$editPermission = "";
												$allDisabled = false;
											}
											$addPermission = "disabled";
											if (in_array(ADD, $permissionArray)) {
												$addPermission = "";
												$allDisabled = false;
											}
											$deletePermission = "disabled";
											if (in_array(DELETE, $permissionArray)) {
												$deletePermission = "";
												$allDisabled = false;
											}
											if ($allDisabled == true) {
												continue;
											}
											$bg = $bg=='row0' ? 'row1' : 'row0';
									?>
											<tr class="<?php echo $bg;?>" id='<?php echo $moduleName;?>'>
												<td valign='middle' colspan='1' class='padLeft20'>
													<?php echo $moduleLabel;?>
												</td>
												<td valign='middle' colspan='1' class=''>
													<input type='checkbox'  onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
												</td>
												<td valign='middle' colspan='1' class=''>
													<input type='checkbox'  onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
												</td>
												<td valign='middle' colspan='1' class=''>
													<input type='checkbox'  onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
												</td>
												<td valign='middle' colspan='1' class=''>
													<input type='checkbox'  onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
												</td>
											</tr>
									<?php
										}
									}
									elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
										$moduleArray = $menuItemArray[1];
										list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
										if (!isset($_SESSION[$moduleName])) {
											continue;
										}
										$permissionArray = array();
										foreach($_SESSION[$moduleName] as $key => $value) {
											if ($value == 1) {
												if ($key == 'view') {
													$permissionArray[] = VIEW;
												}
												elseif ($key == 'add') {
													$permissionArray[] = ADD;
												}
												elseif ($key == 'edit') {
													$permissionArray[] = EDIT;
												}
												elseif ($key == 'delete') {
													$permissionArray[] = DELETE;
												}
											}
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
									<tr>
										<td valign='middle' colspan='5' class='' height='10'></td>
									</tr>
									<tr class='showHideRow' id='<?php echo $moduleName;?>'>
										<td valign='middle' colspan='1' class='headingMenu padLeft2'>
											<?php echo $menuLabel; ?>
										</td>
										<td valign='middle' colspan='1' class=''>
											<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_viewPermission' <?php echo $viewPermission;?>>
										</td>
										<td valign='middle' colspan='1' class=''>
											<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_addPermission' <?php echo $addPermission;?>>
										</td>
										<td valign='middle' colspan='1' class=''>
											<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_editPermission' <?php echo $editPermission;?>>
										</td>
										<td valign='middle' colspan='1' class=''>
											<input type='checkbox' onClick="check('<?php echo $moduleName;?>','<?php echo $bg;?>')" name='<?php echo $moduleName;?>_deletePermission' <?php echo $deletePermission;?>>
										</td>
									</tr>
									<tr>
										<td valign='middle' colspan='5' class='' height='10'></td>
									</tr>
									<?php
									}
								}
							}
						$recordCount = count($dashboardFrameArray);
						if($recordCount >0 && is_array($dashboardFrameArray) ) {
						   for($i=0; $i<$recordCount; $i++ ) {
							   $headerPrinted = false;
							   if (!isset($_SESSION[$dashboardFrameArray[$i]['frameName']])) {
								   continue;
							   }
							   elseif ($headerPrinted == false) {
										   echo "<tr class='showHideRow'>
											<td valign='middle' colspan='5' class='headingMenu padLeft2'>Home Page</td>
										</tr>";
										$headerPrinted = true;
							   }

								$bg = $bg =='row0' ? 'row1' : 'row0';
								echo '<tr class="'.$bg.'" id="chb'.$dashboardFrameArray[$i]['frameId'].'">
								<td  class="padLeft20" valign="middle" height="20">'.strip_slashes($dashboardFrameArray[$i]['frameName']).'</td>
								<td valign="middle" colspan="4"><input type="checkbox" name="chb'.$dashboardFrameArray[$i]['frameId'].'" id="chb'.$dashboardFrameArray[$i]['frameId'].'" value="'.$dashboardFrameArray[$i]['frameId'].'"></td>
								</tr>';
						   }
						}
						?>
						</table>
					</td>
				</tr>
				 <tr>
					<td height="5"></td>
				 </tr>
				 <tr>
					<td  align="right" style="padding-right:5px" colspan="9"><input type="hidden" name="recordCount" value="<?php echo $recordCount?>">
					<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />&nbsp;&nbsp;<!--a href="#" onClick="printTimeTableReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a-->&nbsp;</td>
				 </tr>
                 </table>
			</form>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
<?php
//$History: listUserRolePermission.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/01/10    Time: 5:15p
//Updated in $/LeapCC/Templates/RolePermission
//fixed issue in role permission module.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/31/09    Time: 6:38p
//Updated in $/LeapCC/Templates/RolePermission
//added code so that user can not set/unset his-her own permissions.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 4:01p
//Updated in $/LeapCC/Templates/RolePermission
//corrected text from Role Permission2 to Role Permission
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:02p
//Created in $/LeapCC/Templates/RolePermission
//file added to provide role permissions for user other than admin
//


?>
