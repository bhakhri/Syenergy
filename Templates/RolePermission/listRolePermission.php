<?php
//-------------------------------------------------------
//  This File creates html form
//
// Author :Ajinder Singh
// Created on : 06-Nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

$pageTitle = 'Setup&nbsp;&raquo;&nbsp;User Management&nbsp;&raquo;&nbsp;Role Permissions';

global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');

if ($roleId != 1) {
	require_once(TEMPLATES_PATH . "/RolePermission/listUserRolePermission.php");
}
else {

require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
               
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
						<td class="contenttab_border" colspan="2">
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
								<td height="10" colspan="4"></td>
							</tr>
							<tr>
								<td class="contenttab_internal_rows"><nobr><b>Role Name: </b></nobr></td>
								<td class="padding"><select size="1" class="inputbox1" name="roleId" id="roleId" onChange="getPermissions()">
								<option value="">Select</option>
								<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getRoles();
								?>
								</select></td>
								<td  align="right" style="padding-right:5px" class="padding">
									<input type="image" style="margin-bottom:-1px;" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />
								</td>
							</tr>
							<tr>
								<td colspan="4" height="5px"></td>
							</tr>
							</table>
					    </td>
					</tr>
					<tr>
					<td class="contenttab_row" valign="top" width="100%">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
								//echo '<pre>';
								//print_r($independentMenu);
								//die();
								foreach($independentMenu as $menuItemArray) {
									if ($menuItemArray[0] == SET_MENU_HEADING) {
										?>
									<tr class='showHideRow'>
										<td valign='middle' colspan='5' class='headingMenu padLeft2'>
											<?php echo $menuItemArray[1]; ?>
										</td>
									</tr>
										<?php
									}
									elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
										 $moduleName = $menuItemArray[2][0];
										 $moduleLabel = $menuItemArray[2][1];

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

									?>
											<tr class='headingSubMenuRow'>
												<td valign='middle' colspan='5' class='headingSubMenu'>
													<?php echo $moduleHeadLabel;?>
												</td>
											</tr>
									<?php
										foreach($menuItemArray[2] as $moduleMenuItem) {
											$moduleName = $moduleMenuItem[0];
											$moduleLabel = $moduleMenuItem[1];
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

                           echo "<tr class='showHideRow'>
							<td valign='middle' colspan='5' class='headingMenu padLeft2'>Home Page</td>
						</tr>";
						   for($i=0; $i<$recordCount; $i++ ) {

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
					<td  align="right" style="padding-right:5px" colspan="9"><input type="hidden" name="recordCount" value="<?php echo $recordCount+100?>">
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
}
?>
<?php
//$History: listRolePermission.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 1/02/10    Time: 19:30
//Updated in $/LeapCC/Templates/RolePermission
//Done bug fixing.
//Bug ids---
//0002703,0002702
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 2/01/10    Time: 5:15p
//Updated in $/LeapCC/Templates/RolePermission
//fixed issue in role permission module.
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/RolePermission
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Templates/RolePermission
//added role permission module for user other than admin
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Templates/RolePermission
//changed queries to add instituteId
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:01a
//Updated in $/LeapCC/Templates/RolePermission
//Added 'Parent, Student, Teacher and Management' Role permission
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:30p
//Created in $/LeapCC/Templates/RolePermission
//Intial checkin
?>