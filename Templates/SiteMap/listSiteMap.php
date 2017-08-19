<?php
//-------------------------------------------------------
//  This File is used for site map
//
//
// Author :Ajinder Singh
// Created on : 21-Jan-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

$roleId = $sessionHandler->getSessionVariable('RoleId');



?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Site Map</td>
                <td valign="top" align="right">

                  </td>
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
                        <td class="content_title">Site Map : </td>
                        <td class="content_title" align='right'>
						<span style='color:#bb0000;'>Link</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:black'>Text</span>
						</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
               <td class="contenttab_row" valign="top" >
			   <div id="results">
					<table border='0' cellspacing='1' cellpadding='1' width='100%'>
						<tr class="rowheading">
							<td  colspan='1' class='searchhead_text' width='20%'>Heading</td>
							<td  colspan='1' class='searchhead_text' width='22%'>Menu</td>
							<td  colspan='1' class='searchhead_text' width='27%'>Sub Menu</td>
							<td  colspan='1' class='searchhead_text'>Description</td>
						</tr>
						<?php
				//		require_once(BL_PATH . '/menuItemsDescription.php');
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
						/*
						echo '<pre>';
						print_r($allMenus);
						echo '</pre>';
						*/

						foreach($allMenus as $independentMenu) {
							foreach($independentMenu as $menuItemArray) {
								if ($menuItemArray[0] == SET_MENU_HEADING) {

									$moduleLabel = $menuItemArray[1];
									$subMenuCounter = 0;

									$includeHeading = false;

									$mainMenuCounter++;
									$headingText = "<tr $bg><td valign='top' colspan='1' class='siteMapMainHeading'>$mainMenuCounter . $moduleLabel</td><td></td><td></td><td></td></tr>";

								}
								elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
									 $moduleName = $menuItemArray[2][0];
									 $moduleLabel = $menuItemArray[2][1];
                                     $description = $menuItemArray[2][4];

									 $sessionModule = $sessionHandler->getSessionVariable($moduleName);
									 if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
										 continue;
									 }
									 else {
										 if ($includeHeading == false) {

											 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
											 echo $headingText;//"<tr $bg><td valign='top' colspan='1' class='siteMapMainHeading'>$mainMenuCounter . $moduleLabel</td><td></td><td></td><td></td></tr>";
											 $includeHeading = true;
										 }
									 }
									 $moduleLink = $menuItemArray[2][2];
                                     $description = $menuItemArray[2][4];
									 $subMenuCounter++;
									 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
								?>
								<tr <?php echo $bg;?> ><td></td><td valign='top' colspan='1' class='siteMapMainHeading2'><?php echo $mainMenuCounter . '.' .$subMenuCounter . '. ';?><a class='redLink' href='<?php echo $moduleLink;?>'><?php echo $moduleLabel;?></a></td><td></td>

								<td class='contenttab_internal_rows'>
								<?php
									echo $description;

								?>
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
										 $sessionModule = $sessionHandler->getSessionVariable($moduleName);
										 if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
											 continue;
										 }
										 else {
											 if ($includeHeading == false) {
												 //$mainMenuCounter++;
												 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
												 echo $headingText;//"<tr $bg><td valign='top' colspan='1' class='siteMapMainHeading'>$mainMenuCounter . $moduleLabel</td><td></td><td></td><td></td></tr>";
												 $includeHeading = true;
											 }
											 if ($includeSubHeading == false) {
												 $subMenuCounter++;
												 $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
												 echo "<tr $bg><td></td><td valign='top' colspan='1' class='siteMapMainHeading2'>$mainMenuCounter .  $subMenuCounter .  $moduleHeadLabel</td><td></td><td></td></tr>";
												 $includeSubHeading = true;
											 }

										 }
										$moduleLabel = strip_tags($moduleMenuItem[1]);
										$moduleLink = $moduleMenuItem[2];
                                        $description = $moduleMenuItem[4];

										$subInnerMenuCounter++;
										$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';

										?>
										<tr <?php echo $bg;?>><td></td><td></td><td valign='top' colspan='1' class='contenttab_internal_rows'><?php echo $mainMenuCounter . '.' .$subMenuCounter . '.' .$subInnerMenuCounter . '. ';?><a class='redLinkSimple' href='<?php echo $moduleLink;?>'><?php echo $moduleLabel;?></a></td>
										<td class='contenttab_internal_rows'><?php echo $description;?></td>
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
								?>
								<tr <?php echo $bg;?>><td valign='top' colspan='1' class='siteMapMainHeading'><?php echo $mainMenuCounter . '. '?><a class='siteMapMainHeading' href='<?php echo $menuLink;?>'><?php echo $menuLabel;?></a></td><td></td>
									<td></td>
									<td class='siteMapMainHeading'><?php echo $description ?></td>
								</tr>
								<?php
								}
							}
						}
						?>
					</table>
			   </div>

        </td>
    </tr>
</table>
</td>
</tr>
</table>
<?php
// $History: listSiteMap.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/04/10    Time: 15:33
//Updated in $/LeapCC/Templates/SiteMap
//Corrected breadcrumb
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/15/10    Time: 3:20p
//Updated in $/LeapCC/Templates/SiteMap
//done customization changes
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/22/10    Time: 12:01p
//Updated in $/LeapCC/Templates/SiteMap
//done changes for site map. FCNS No. 1113
//


?>