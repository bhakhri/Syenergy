<?php
//-------------------------------------------------------
//  This File creates html form student role
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
            <tr>
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
						<td class="contenttab_border" height="20">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">Student Role Permission: </td>
							</tr>
							</table>
						</td>
					 </tr>
					<tr>
					<td class="contenttab_row" valign="top" ><div id="results">  
						<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
								<tr>
									<td height='5'></td>
								</tr>
								<tr>
									<td valign='bottom' colspan='1' class='headingMenu'><B>Module List</B></td>
									<td valign='bottom' colspan='1' class=''><B>View</B></td>
									<td valign='bottom' colspan='1' class=''><B>Add</B></td>
									<td valign='bottom' colspan='1' class=''><B>Edit</B></td>
									<td valign='bottom' colspan='1' class=''><B>Delete</B></td>
								</tr>
						<?php
							 
							foreach($allStudentMenus as $independentMenu) {
								$bg='row1';
								 
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
//$History: listStudentRolePermission.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/RolePermission
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:33p
//Updated in $/LeapCC/Templates/RolePermission
//change the breadcrumb
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:04a
//Created in $/LeapCC/Templates/RolePermission
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
 ?>