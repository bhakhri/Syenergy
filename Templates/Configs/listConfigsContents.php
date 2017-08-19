<?php 
//
//This file creates Html Form output in "Config" Module 
//
// Author :Ajinder Singh
// Created on : 08-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        Setup&nbsp;&raquo;&nbsp;Local Masters&nbsp;&raquo;&nbsp;Config Masters
                    </td>
                    <td valign="top" align="right">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" class="content" height="405">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="contenttab_border" height="20">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
							<tr>
								<td class="content_title">
									Config Detail :
								</td>
								<td class="content_title" title="Add">
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="contenttab_row" valign="top" >
						<form name="addConfig" action="" method="post">
							<div id="results">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" >
									<tr class="rowheading">
										<td width="10%" class="unsortable">
											<b>
												#
											</b>
										</td>
										<td width="50%" height="20"  class="searchhead_text">
											<strong>
												Label
											</strong>
										</td>
										<td width="40%" height="20"  class="searchhead_text">
											<strong>
												Value
											</strong>
										</td>
									</tr>
									<?php
										require_once(MODEL_PATH . "/ConfigsManager.inc.php");
										$configsManager = ConfigsManager::getInstance();
										$configsRecordArray = $configsManager->getConfigList();

										$recordCount = count($configsRecordArray);
										if($recordCount >0 && is_array($configsRecordArray) ) { 
											for($i=0; $i<$recordCount; $i++ ) {
												$bg = $bg =='row0' ? 'row1' : 'row0';
												echo '<tr class="'.$bg.'">
												<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
												<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
												<td class="padding_top" valign="top"><input maxlength="30" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'"></td>
												</tr>';
											}
											if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
												$bg = $bg =='row0' ? 'row1' : 'row0';
												require_once(BL_PATH . "/Paging.php");
												$paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
												echo '<tr><td colspan="5" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
											}
										}
										?>
										<tr>
											<td align="center" colspan="3"><br>
										<?php
										if ($recordCount >0) {
										?>
											<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form);return false;" />
										<?php
										}
										else {
											echo 'No record found';
										}

									?>
										</td>
									</tr>
								</table>
							</div>
						</form>
					</td>
				</tr>
			</table>
<?php 
// $History: listConfigsContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Configs
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/08/08    Time: 7:24p
//Created in $/Leap/Source/Templates/Configs
//file added for configs master
//


?>
