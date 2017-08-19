<?php

//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

 <form name="addConfig"  id="addConfig"  action="<?php echo HTTP_LIB_PATH;?>/Configs/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" colspan="2" class="">
				<tr>
					<td valign="top" colspan="1" class="">
						<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
						</table>
					</td>
				</tr>
			</td>
		</tr>
		<tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="500">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Config Settings</span> </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top"><div id="dhtmlgoodies_tabView1">
				   <div class="dhtmlgoodies_aTab" style="overflow:auto">

							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="80%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'ADDRESS'");
								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';

										if($configsRecordArray[$i]['param']=='DEFAULT_COUNTRY'){
										?>
											<select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="fatherCountry" onChange="autoPopulate(this.value,'states','Add','fatherStates','fatherCity');">
											<option value="" selected="checked">Select</option>
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getCountriesData($configsRecordArray[$i]['value']);
											?>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DEFAULT_STATE'){
										?>
											<select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="fatherStates" onChange="autoPopulate(this.value,'city','Add','fatherStates','fatherCity');">
											<option value="">Select</option>
											<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getStatesData($configsRecordArray[$i]['value']);
											?>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DEFAULT_CITY'){
										?>
											<select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="fatherCity">
											<option value="">Select</option>
											<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getCityData($configsRecordArray[$i]['value']);
											?>

											</select>
										<?php
										}
                                        
                                        elseif($configsRecordArray[$i]['param']=='LOGOUT_HOME_LINK'){
                                           echo '<input maxlength="100" class="htmlElement" type="text" id="logoutHomeLink" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';                                        
                                        }
										else{
                                           echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
										}
										echo '</td>
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
				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
					   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="85%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'ATTENDANCE'");
								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';

                                      if($configsRecordArray[$i]['param']=='BULK_ATTENDANCE_ALLOWED'){
										?>
										<!--
										<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
                                        
                                        elseif($configsRecordArray[$i]['param']=='STUDENT_DETAINED_MESSAGE'){
                                        ?>
                                            <textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
                                        <?php
                                        }
                                        
					  					 elseif($configsRecordArray[$i]['param']=='SMS_CLASSES'){
															?>
									<select multiple name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>" size='5' class='inputbox1' style='width:260px'>
								    <?php
								      require_once(BL_PATH.'/HtmlFunctions.inc.php');
								      echo HtmlFunctions::getInstance()->getAllClassDataNew($configsRecordArray[$i]['value']);
								    ?>
								    </select><br>
								    <div align="left">
								    Select &nbsp;
								    <a class="allReportLink" href="javascript:makeSelection('config_<?php echo $configsRecordArray[$i]['configId']?>','All','addConfig');">All</a> / 
								    <a class="allReportLink" href="javascript:makeSelection('config_<?php echo $configsRecordArray[$i]['configId']?>','None','addConfig');">None</a>
								    </div></nobr>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DUTY_LEAVES_ALLOWED'){
										?>
											<!--
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"      
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='GRACE_MARKS_ALLOWED'){
										?>
											<!--
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='ATTENDANCE_DELETEABLE'){
										?>
											<!--
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='LOOSELY_COUPLED_ATTENDANCE'){
										?>
											<!--
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='TEST_GROUP_RESTRICTION'){
										?>
										<!--
										<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='SUBJECT_TOPIC_RESTRICTION'){
										?>
											<!--
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										else{
                                            if(strlen(strip_slashes($configsRecordArray[$i]['value']))>=10) {
                                               echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
                                            else {
                                               echo '<input size="10" maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
										}
										echo '</td>
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

				    <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="90%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'ADMIT_STUDENT'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top" >';
										if($configsRecordArray[$i]['param']=='TIMETABLE_FORMAT'){
										?>
											<select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>"  id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getTimeTableView($configsRecordArray[$i]['value']);
											?>
											</select>
										<?php
										}
                                      
										elseif($configsRecordArray[$i]['param']=='DEFAULT_EXAM'){
										?>
											<select style="width:270px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getEntranceExamData($configsRecordArray[$i]['value']);
											?>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DEFAULT_CATEGORY'){
										?>
											<select style="width:270px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getCurrentCategories($configsRecordArray[$i]['value'],' WHERE parentQuotaId=0 ');
											?>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DEFAULT_NATIONALITY'){
										?>
											<select style="width:270px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getNationalityData($configsRecordArray[$i]['value']);
											?>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DEFAULT_DOMICILE'){
										?>
											<select  style="width:270px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getStatesData($configsRecordArray[$i]['value']);
											?>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='AUTO_GENERATED_REG_NO'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select> -->


											<input  type="radio" value="1"
											name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No






										<?php
										}
										else{
                                            if(strlen(strip_slashes($configsRecordArray[$i]['value']))>=10) {
                                               echo '<input maxlength="500" class="htmlElement" type="text"  name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
                                            else {
                                               echo '<input size="10" maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
										}
										echo '</td>
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
				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="85%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'MESSAGE'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';

										if($configsRecordArray[$i]['param']=='TEACHER_SMS_STUDENTS'){

										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No



										<?php
										}
	                                                                   elseif($configsRecordArray[$i]['param']=='NONTEACHER_SMS_PARENTS'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"

											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}
                                                                             elseif($configsRecordArray[$i]['param']=='NONTEACHER_NONSMS_PARENTS'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}








										elseif($configsRecordArray[$i]['param']=='TEACHER_SMS_PARENTS'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}

										elseif($configsRecordArray[$i]['param']=='TEACHER_SMS_COLLEAGUES'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}
										elseif($configsRecordArray[$i]['param']=='NONTEACHER_SMS_PARENTS'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}
										elseif($configsRecordArray[$i]['param']=='NONTEACHER_NONSMS_PARENTS'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}
										else{
                                            if(strlen(strip_slashes($configsRecordArray[$i]['value']))>=10) {
                                               echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
                                            else {
                                               echo '<input size="10" maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }

										}
										echo '</td>
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
				    <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="70%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'CARD'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top" >';

                                        if($configsRecordArray[$i]['param']=='I_CARD_TEMPLATE'){
                                        ?>
                                            <select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Template-1</option>
                                            <option value="2" <?php if($configsRecordArray[$i]['value']=='2') echo "selected";?>>Template-2</option>
                                            </select>
                                        <?php
                                        }
                                        elseif($configsRecordArray[$i]['param']=='ADMIT_INSTITUTE_LOGO'){
                                        ?>
                                           <!--
										   <select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }
                                        elseif($configsRecordArray[$i]['param']=='ADMIT_PHOTO'){
                                        ?>
                                         <!--
										 <select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }
                                        elseif($configsRecordArray[$i]['param']=='ADMIT_SHOW_NO'){
                                        ?>
                                          <!--
										  <select style="width:150px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Registration No.</option>
                                            <option value="2" <?php if($configsRecordArray[$i]['value']=='2') echo "selected";?>>University No.</option>
                                            </select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }
										elseif($configsRecordArray[$i]['param']=='ADVERTISEMENT_ENABLE'){
										?>
										<!--
										<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='I_CARD_INSTRUCTIONS'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='BUS_PASS_INSTRUCTIONS'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='BUS_PASS_FOUND_ADDRESS'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='BUS_PASS_INSTITUTE_EMAIL'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EMPLOYEE_I_CARD_INSTRUCTIONS'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EMPLOYEE_I_CARD_FOUND'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='BUS_PASS_LOGO'){

											//echo "---".$configsRecordArray[$i]['value'];
											if($configsRecordArray[$i]['value']){

												$imgSrc3= IMG_HTTP_PATH."/BusPass/".$configsRecordArray[$i]['value'];
											}
											else{
												$imgSrc3= IMG_HTTP_PATH."/file.gif";

											}
										?>
											<input type="file" name="busPassLogo" id="busPassLogo">
											<img src='<?php echo $imgSrc3?>' width='20' height='20' id='bussPassImageId'/>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='I_CARD_LOGO'){

											if($configsRecordArray[$i]['value']){

												$imgSrc4= IMG_HTTP_PATH."/Icard/".$configsRecordArray[$i]['value'];
											}
											else{
												$imgSrc4= IMG_HTTP_PATH."/file.gif";

											}
										?>
											<input type="file" name="iCardLogo" id="iCardLogo">
											<img src='<?php echo $imgSrc4?>' width='20' height='20' id='icardImageId'/>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EMPLOYEE_I_CARD_LOGO'){

											//echo "---".$configsRecordArray[$i]['value'];
											if($configsRecordArray[$i]['value']){

												$imgSrc5= IMG_HTTP_PATH."/Icard/".$configsRecordArray[$i]['value'];
											}
											else{
												$imgSrc5= IMG_HTTP_PATH."/file.gif";

											}
										?>
											<input type="file" name="employeeiCardLogo" id="employeeiCardLogo">
											<img src='<?php echo $imgSrc5?>' width='20' height='20' id='employeeiCardImageId'/>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EMPLOYEE_I_CARD_SIGNATURE'){

											if($configsRecordArray[$i]['value']){

												$imgSrc6= IMG_HTTP_PATH."/Icard/".$configsRecordArray[$i]['value'];
											}
											else{
												$imgSrc6= IMG_HTTP_PATH."/file.gif";

											}
										?>
											<input type="file" name="employeeiCardSignature" id="employeeiCardSignature">
											<img src='<?php echo $imgSrc6?>' width='20' height='20' id='employeeiCardSignatureImageId'/>

										<?php
										}
										else{
                                            if(strlen(strip_slashes($configsRecordArray[$i]['value']))>=10) {
                                               echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
                                            else {
                                               echo '<input size="10" maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }

										}
										echo '</td>
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

				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <tr class="rowheading">
                                <td width="3%" class="unsortable"><b>#</b></td>
                                <td width="70%" height="20"  class="searchhead_text">
                                    <strong>
                                        Label
                                    </strong>
                                </td>
                                <td width="50%" height="20"  class="searchhead_text">
                                    <strong>
                                        Value
                                    </strong>
                                </td>
                            </tr>
                            <?php
                                require_once(MODEL_PATH . "/ConfigsManager.inc.php");
                                $configsManager = ConfigsManager::getInstance();
                                $configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'BUS'");

                                $recordCount = count($configsRecordArray);
                                if($recordCount >0 && is_array($configsRecordArray) ) {
                                    for($i=0; $i<$recordCount; $i++ ) {
                                        $bg = $bg =='row0' ? 'row1' : 'row0';
                                        echo '<tr class="'.$bg.'">
                                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                                        <td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
                                        <td class="padding_top" valign="top">';

                                        	if($configsRecordArray[$i]['param']=='BUS_PASS_INSTRUCTIONS'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										else if($configsRecordArray[$i]['param']=='BUS_PASS_FOUND_ADDRESS'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										else if($configsRecordArray[$i]['param']=='BUS_PASS_INSTITUTE_EMAIL'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='0'><?php echo $configsRecordArray[$i]['value']?></textarea>

										<?php
										}
										else if($configsRecordArray[$i]['param']=='BUS_PASS_LOGO'){

											//echo "---".$configsRecordArray[$i]['value'];
											if($configsRecordArray[$i]['value']){

												$imgSrc3= IMG_HTTP_PATH."/BusPass/".$configsRecordArray[$i]['value'];
											}
											else{
												$imgSrc3= IMG_HTTP_PATH."/file.gif";

											}
										?>
											<input type="file" name="busPassLogo" id="busPassLogo">
											<img src='<?php echo $imgSrc3?>' width='20' height='20' id='bussPassImageId'/>
										<?php
										}
										else{
                                            if(strlen(strip_slashes($configsRecordArray[$i]['value']))>=10) {
                                               echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }
                                            else {
                                               echo '<input size="10" maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
                                            }

										}
                                        echo '</td>
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

				   <!--div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsReminderArray = $configsManager->getReminderConfigList(" AND tabGroup = 'REMINDER'");
								//print_r($configsReminderArray);
								$recordCount = count($configsReminderArray);
								if($recordCount >0 && is_array($configsReminderArray) ) {

									for($i=0; $i<$recordCount; $i++ ) {

										$bg = $bg =='row0' ? 'row1' : 'row0';

										if($configsReminderArray[$i]['param']=='STUDENT_DAILY_REMINDER_DASHBOARD'){

											echo '<tr class="'.$bg.'">
											<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
											<td class="padding_top" valign="top">'.strip_slashes($configsReminderArray[$i]['labelName']).'</td>
											<td class="padding_top" valign="top">';
											?>
												<select style="width:82px"  size="1" class="selectfield"  name="reminderConfig_1" id="reminderConfig_1">
												<option value="">Select</option>
												<option value="1" <?php if($configsReminderArray[$i]['value']=='1') echo "selected";?>>Yes</option>
												<option value="0" <?php if($configsReminderArray[$i]['value']=='0') echo "selected";?>>No</option>
												</select>
											<?php

											echo '</td></tr>';
										 }
										 elseif($configsReminderArray[$i]['param']=='SEND_BIRTHDAY_GREETING_STUDENT'){

											//echo $configsReminderArray[$i]['value'];
											//die();
											echo '<tr class="'.$bg.'">
											<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
											<td class="padding_top" valign="top">'.strip_slashes($configsReminderArray[$i]['labelName']).'</td>
											<td class="padding_top" valign="top">';
											?>
												<select style="width:82px"  size="1" class="selectfield" name="reminderConfig_2" id="reminderConfig_2" onChange="showOtherOption(this.value,'student')">
												<option value="">Select</option>
												<option value="1" <?php if($configsReminderArray[$i]['value']=='1') echo "selected";?>>Yes</option>
												<option value="0" <?php if($configsReminderArray[$i]['value']=='0') echo "selected";?>>No</option>
												</select>
											<?php
											if($configsReminderArray[2]['value']==1){

												$showSMSStudent='';
											}
											else{

												$showSMSStudent='display:none';
											}

											if($configsReminderArray[2]['value']==1){

												$showEmailStudent='';
											}
											else{

												$showEmailStudent='display:none';
											}
											if($configsReminderArray[$i]['value']==1){

												$showTypeStudent='';
												$showTypeStudent1='';
												$showSMSStudent='';
												$showEmailStudent='';
											}
											else{

												$showTypeStudent='display:none';
												$showTypeStudent1='display:none';
												$showSMSStudent='display:none';
												$showEmailStudent='display:none';

											}


											if($configsReminderArray[6]['value']){

												$imgSrc= IMG_HTTP_PATH."/Reminder/".$configsReminderArray[6]['value'];
											}
											else{
												$imgSrc= IMG_HTTP_PATH."/notfound.jpg";

											}


											//echo $configsReminderArray[5]['value'];
											//die();
											echo '</td></tr>';
											echo '<tr class="'.$bg.'" id="showTypeStudent" style="'.$showTypeStudent.'">
											<td valign="top" class="padding_top"></td>
											<td class="padding_top" valign="top"></td>
											<td class="padding_top" valign="top"><input type="checkbox" id="reminderConfig_3" name="reminderConfig_3" value="1" onclick="smsDivShow();"';  if($configsReminderArray[2]['value']=='1') echo "checked";

											echo '>SMS &nbsp;<input type="checkbox" id="reminderConfig_4" name="reminderConfig_4" value="1" onclick="emailDivShow();"';
											if($configsReminderArray[3]['value']=='1') echo "checked";

											echo '>E-Mail &nbsp;<input type="checkbox" id="reminderConfig_5" name="reminderConfig_5" value="1"';
											if($configsReminderArray[4]['value']=='1') echo "checked";

											echo '>DashBoard &nbsp;</td></tr>';

											echo '<tr class="'.$bg.'" id="showTypeStudent1" style="'.$showTypeStudent1.'">
											<td class="padding_top" valign="top"></td>
											<td valign="top" class="padding_top">';
											echo "<img src='".$imgSrc."' width='170' height='190' id='studentImageId'/>";
											echo '</td>

											<td class="padding_top" valign="top"><textarea id="reminderConfig_6" name="reminderConfig_6" rows="10" cols="50" style="width:50%" class="mceEditor" >'.$configsReminderArray[5]['value'].'</textarea></td></tr>';

											echo '<tr class="'.$bg.'" id="smsDivStudent" style="'.$showSMSStudent.'">
											<td valign="top" class="padding_top"></td>
											<td class="padding_top" valign="top"></td>
											<td class="padding_top" valign="top">SMS Length :<input type="text" id="sms_char" name="sms_char" class="small_txt" value="0" disabled="true" />&nbsp;&nbsp;&nbsp;SMS(s) :<input type="text" id="sms_no" name="sms_no" class="small_txt" value="1" disabled="true" /></td></tr>';

											echo '<tr class="'.$bg.'" id="emailDivStudent" style="'.$showEmailStudent.'">
											<td valign="top" class="padding_top"></td>
											<td class="padding_top" valign="top"></td>
											<td class="padding_top" valign="top"><input type="file" name="emailStudentFile" id="emailStudentFile">';

											echo '</td></tr>';

										 }
										 elseif($configsReminderArray[$i]['param']=='EMPLOYEE_DAILY_REMINDER_DASHBOARD'){

											echo '<tr class="'.$bg.'">
											<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
											<td class="padding_top" valign="top">'.strip_slashes($configsReminderArray[$i]['labelName']).'</td>
											<td class="padding_top" valign="top">';
											?>
												<select style="width:82px"  size="1" class="selectfield" name="reminderConfig_8" id="reminderConfig_8">
												<option value="">Select</option>
												<option value="1" <?php if($configsReminderArray[$i]['value']=='1') echo "selected";?>>Yes</option>
												<option value="0" <?php if($configsReminderArray[$i]['value']=='0') echo "selected";?>>No</option>
												</select>
											<?php

											echo '</td></tr>';
										 }
										 elseif($configsReminderArray[$i]['param']=='SEND_BIRTHDAY_GREETING_EMPLOYEE'){

											echo '<tr class="'.$bg.'">
											<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
											<td class="padding_top" valign="top">'.strip_slashes($configsReminderArray[$i]['labelName']).'</td>
											<td class="padding_top" valign="top">';
											?>
												<select style="width:82px"  size="1" class="selectfield" name="reminderConfig_9" id="reminderConfig_9" onChange="showOtherOption(this.value,'employee')">
												<option value="">Select</option>
												<option value="1" <?php if($configsReminderArray[$i]['value']=='1') echo "selected";?>>Yes</option>
												<option value="0" <?php if($configsReminderArray[$i]['value']=='0') echo "selected";?>>No</option>
												</select>
											<?php
											if($configsReminderArray[9]['value']==1){

												$showSMSEmployee='';
											}
											else{

												$showSMSEmployee='display:none';
											}

											if($configsReminderArray[9]['value']==1){

												$showEmailEmployee='';
											}
											else{

												$showEmailEmployee='display:none';
											}
											if($configsReminderArray[$i]['value']==1){

												$showTypeEmployee='';
												$showTypeEmployee1='';
												$showSMSEmployee='';
												$showEmailEmployee='';
											}
											else{

												$showTypeEmployee='display:none';
												$showTypeEmployee1='display:none';
												$showSMSEmployee='display:none';
												$showEmailEmployee='display:none';
											}

											if($configsReminderArray[13]['value']){

												$imgSrc1= IMG_HTTP_PATH."/Reminder/".$configsReminderArray[13]['value'];
											}
											else{
												$imgSrc1= IMG_HTTP_PATH."/notfound.jpg";
											}



											echo '</td></tr>';
											echo '<tr class="'.$bg.'" id="showTypeEmployee" style="'.$showTypeEmployee.'">
											<td valign="top" class="padding_top"></td>
											<td class="padding_top" valign="top"></td>
											<td class="padding_top" valign="top"><input type="checkbox" id="reminderConfig_10" name="reminderConfig_10" value="1" onclick="smsDivShow1();"';
											if($configsReminderArray[9]['value']=='1') echo "checked";

											echo '>SMS &nbsp;<input type="checkbox" id="reminderConfig_11" name="reminderConfig_11" value="1" onclick="emailDivShow1();"';
											if($configsReminderArray[10]['value']=='1') echo "checked";

											echo '>E-Mail &nbsp;<input type="checkbox" id="reminderConfig_12" name="reminderConfig_12" value="1"';
											if($configsReminderArray[11]['value']=='1') echo "checked";

											echo '>DashBoard &nbsp;</td></tr>';

											echo '<tr class="'.$bg.'" id="showTypeEmployee1" style="'.$showTypeEmployee1.'">
											<td valign="top" class="padding_top"></td>
											<td valign="top" class="padding_top">';
											echo "<img src='".$imgSrc1."' width='170' height='190' id='employeeImageId'/>";
											echo '</td>
											<td class="padding_top" valign="top"><textarea id="reminderConfig_13" name="reminderConfig_13" rows="10" cols="50" style="width:50%" class="mceEditor1">'.$configsReminderArray[12]['value'].'</textarea></td></tr>';

											echo '<tr class="'.$bg.'" id="smsDivEmployee" style="'.$showSMSEmployee.'">
											<td valign="top" class="padding_top"></td>
											<td class="padding_top" valign="top"></td>
											<td class="padding_top" valign="top">SMS Length :<input type="text" id="sms_char1" name="sms_char1" class="small_txt" value="0" disabled="true" />&nbsp;&nbsp;&nbsp;SMS(s) :<input type="text" id="sms_no1" name="sms_no1" class="small_txt" value="1" disabled="true" /></td></tr>';

											echo '<tr class="'.$bg.'" id="emailDivEmployee" style="'.$showEmailEmployee.'">
											<td valign="top" class="padding_top"></td>
											<td class="padding_top" valign="top"></td>
											<td class="padding_top" valign="top"><input type="file" name="emailEmployeeFile" id="emailEmployeeFile">';

											echo '</td></tr>';

										 }
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
				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'ALERT'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';

										if($configsRecordArray[$i]['param']=='DAILY_ATTENDANCE_UPDATE_SMS'){

										?>
											<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DAILY_ATTENDANCE_UPDATE_EMAIL'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DAILY_ATTENDANCE_UPDATE_DASHBOARD'){
										?>
											<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='MONTHLY_ATTENDANCE_UPDATE_SMS'){
										?>
											<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='MONTHLY_ATTENDANCE_UPDATE_EMAIL'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='MONTHLY_ATTENDANCE_UPDATE_DASHBOARD'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DISCIPLINARY_UPDATE_SMS'){
										?>
											<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DISCIPLINARY_UPDATE_EMAIL'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='DISCIPLINARY_UPDATE_DASHBOARD'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EXAM_UPDATE_SMS'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EXAM_UPDATE_EMAIL'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='EXAM_UPDATE_DASHBOARD'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='HOLIDAY_UPDATE_SMS'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='HOLIDAY_UPDATE_EMAIL'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='HOLIDAY_UPDATE_DASHBOARD'){
										?>
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
										<?php
										}
										else{

											echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'">';
										}
										echo '</td>
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

				   </div-->
					 <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="85%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'TIME_TABLE'");

								$disabledTimeTable ="";
								global $sessionHandler;
								$sessionHandler->getSessionVariable('TT_DISABLE_CONFLICTS');
								if(!$sessionHandler->getSessionVariable('TT_DISABLE_CONFLICTS')){

									$disabledTimeTable ="DISABLED=DISABLED";
								}

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';

										if($configsRecordArray[$i]['param']=='TT_DISABLE_CONFLICTS'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_tt1" onChange="disableEnable()">

											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='TT_ALLOW_DIFFERENT_SUBJECT'){
										?>
											<!--<select style="width:82px"  size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_tt2" <?php echo $disabledTimeTable?>>

											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->



											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='TT_ALLOW_DIFFERENT_TEACHER'){
										?>
											<!--<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_tt3"  <?php echo $disabledTimeTable?>>

											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->



											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No


										<?php
										}
										else{

											echo '<input maxlength="500" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="60">';
										}
										echo '</td>
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
							<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
							</td>
						</tr>
					</table>

				   </div>
				   
					<div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="80%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							 <?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'FEE'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';

										if($configsRecordArray[$i]['param']=='CONCESSION_FORMAT'){
                                        ?>
                                            <select style="width:100px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Max</option>
                                            <option value="2" <?php if($configsRecordArray[$i]['value']=='2') echo "selected";?>>Min</option>
                                            <option value="3" <?php if($configsRecordArray[$i]['value']=='3') echo "selected";?>>Reducing</option>
                                            </select>
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='HOSTEL_REGISTRATION_NEW_RENEW'){
                                        ?>
                                            <select style="width:100px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='ONLINE_FEE_PAYMENT'){
                                        ?>
                                            <select style="width:100px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
                                        <?php    
                                        }
                                        
                                        elseif($configsRecordArray[$i]['param']=='FEE_ALERT_IN_STUDENT_LOGIN'){
                                        ?>
                                            <select style="width:100px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
                                        <?php    
                                        }
                                         elseif($configsRecordArray[$i]['param']=='INSTITUTE_BANK_NAME'){
                                        ?>
                                            <select style="width:100px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                           <?php
						  require_once(BL_PATH.'/HtmlFunctions.inc.php');
						  echo HtmlFunctions::getInstance()->getBankData($configsRecordArray[$i]['value']);
					    ?>
                                            </select>
                                        <?php    
                                        }
										else {
											echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="50">';
										}
										echo '</td>
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
				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="100%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="60%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							 <?php
								require_once(BL_PATH.'/helpMessage.inc.php');
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'PERFORMANCE'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										require_once(BL_PATH.'/HtmlFunctions.inc.php');
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" align="top">'.strip_slashes($configsRecordArray[$i]['labelName']);
										if($configsRecordArray[$i]['param']=='ABOVE_AVERAGE_PERCENTAGE'){
											echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_ABOVE_AVERAGE);
										}
										else if($configsRecordArray[$i]['param']=='BELOW_AVERAGE_PERCENTAGE') {
											echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_BELOW_AVERAGE);
										}

										echo '</td>
										<td class="padding_top" valign="top">';

										if($configsRecordArray[$i]['param']=='TIMETABLE_FORMAT'){
										?>
											<select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>"  id="config_<?php echo $configsRecordArray[$i]['configId']?>">

											</select>

										<?php
										}
										else{

											echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="25" >';
										}
										echo '</td>
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
					<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
					<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
								<tr>
									<td height="5px"></td></tr>
								<tr>
								<tr>
									<td width="89%">
										<div id="helpInfo" style="vertical-align:top;" ></div>
									</td>
								</tr>
							</table>
					</div>
<?php floatingDiv_End(); ?>

				   </div>
				    <div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							 <?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'STUDENT_LOGIN'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top">';
					  					 if($configsRecordArray[$i]['param']=='ENABLE_REGISTRATION'){
															?>
									<select multiple name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>" size='5' class='inputbox1' style='width:260px'>
								    <?php
								      require_once(BL_PATH.'/HtmlFunctions.inc.php');
								      echo HtmlFunctions::getInstance()->getAllClassDataNew($configsRecordArray[$i]['value']);
								    ?>
								    </select><br>
								    <div align="left">
								    Select &nbsp;
								    <a class="allReportLink" href="javascript:makeSelection('config_<?php echo $configsRecordArray[$i]['configId']?>','All','addConfig');">All</a> / 
								    <a class="allReportLink" href="javascript:makeSelection('config_<?php echo $configsRecordArray[$i]['configId']?>','None','addConfig');">None</a>
								    </div></nobr>
										<?php
										}

										elseif($configsRecordArray[$i]['param']=='STUDENT_CAN_CHANGE_IMAGE'){
										?>
											<!--
											<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No

										<?php
										}
										else{

											echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="50">';
										}
										echo '</td>
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

				   <div class="dhtmlgoodies_aTab" style="overflow:auto">
					   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="85%" height="20"  class="searchhead_text">
									<strong>
										Label
									</strong>
								</td>
								<td width="50%" height="20"  class="searchhead_text">
									<strong>
										Value
									</strong>
								</td>
							</tr>
							<?php
								require_once(MODEL_PATH . "/ConfigsManager.inc.php");
								$configsManager = ConfigsManager::getInstance();
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'SMS_ALERTS'");
								$disableMessage ="";
								$disableButton ="";
								global $sessionHandler; 
								$sessionHandler->getSessionVariable('ATTENDANCE_ALERT_TO_PARENTS');
								$sessionHandler->getSessionVariable('STUDENT_ABSENT_MESSAGE');
								if(!$sessionHandler->getSessionVariable('ATTENDANCE_ALERT_TO_PARENTS')){
									$disableButton ="DISABLED=DISABLED";
								}
								if($sessionHandler->getSessionVariable('STUDENT_ABSENT_MESSAGE')){
									$disableMessage ="DISABLED=DISABLED";
								}
								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']);
										if($configsRecordArray[$i]['param']=='CUSTOMISED_MESSAGE'){
											echo "<font color='red'>"." [Please do not edit (studentName),(subjectCode) and (date) text from the message]"."</font>";
										}
										echo '</td><td class="padding_top" valign="top">';
										if($configsRecordArray[$i]['param']=='MESSAGE_TO_STUDENT_FOR_NEW_MARKS' OR $configsRecordArray[$i]['param']=='MESSAGE_TO_PARENTS_FOR_NEW_MARKS' OR $configsRecordArray[$i]['param']== 'MESSAGE_TO_STUDENT_FOR_ATTENDANCE' OR $configsRecordArray[$i]['param']=='MESSAGE_TO_TEACHERS_FOR_CHANGED_TIME_TABLE' OR $configsRecordArray[$i]['param']=='MESSAGE_TO_STUDENTS_FOR_CHANGED_TIME_TABLE'){
											echo '<input maxlength="500" class="htmlElement" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="50">';
										}
										elseif($configsRecordArray[$i]['param']=='SMS_ALERT_FOR_NOTICE_UPLOAD'){
										?>
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='SMS_ALERT_TO_STUDENTS_FOR_CHANGE_IN_TIME_TABLE'){
										?>
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='SMS_ALERT_TO_TEACHERS_FOR_CHNAGE_IN_TIME_TABLE'){
										?>
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='SMS_ALERT_TO_STUDENTS_WHEN_ATTENDANCE_IS_UPDATED'){
										?>
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
											elseif($configsRecordArray[$i]['param']=='SMS_ALERT_TO_STUDENTS_WHEN_MARKS_UPDATED'){
										?>
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='SMS_ALERT_TO_PARENTS_FOR_NEW_MARKS'){
										?>
											<input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='ATTENDANCE_ALERT_TO_PARENTS'){
										?>											
											<input  type="radio"  value='1' name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="msg1" <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> onclick="enableDisable(this.value)" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

											<input  type="radio" value='0' name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="msg2" <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> onclick="enableDisable(this.value)" />No
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='STUDENT_ABSENT_MESSAGE'){
										?>											
											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="msg3" <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> onclick="customMessage(this.value)" <?php echo $disableButton?>/>Default&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="msg4" <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> onclick="customMessage(this.value)" <?php echo $disableButton?>/>Customise
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='CUSTOMISED_MESSAGE'){
										?>					
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="msg5" cols='60' rows='5' onkeyup="return ismaxlength(this)" maxlength='140' <?php echo $disableMessage; ?> ><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
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


<div class="dhtmlgoodies_aTab" style="overflow:auto">
						   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr class="rowheading">
								<td width="3%" class="unsortable"><b>#</b></td>
								<td width="70%" height="20"  class="searchhead_text">
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
								$configsRecordArray = $configsManager->getConfigList(" AND tabGroup = 'OTHER'");

								$recordCount = count($configsRecordArray);
								if($recordCount >0 && is_array($configsRecordArray) ) {
									for($i=0; $i<$recordCount; $i++ ) {
										$bg = $bg =='row0' ? 'row1' : 'row0';
										echo '<tr class="'.$bg.'">
										<td valign="top" class="padding_top" >'.($records+$i+1).'</td>
										<td class="padding_top" valign="top">'.strip_slashes($configsRecordArray[$i]['labelName']).'</td>
										<td class="padding_top" valign="top" align="right" >';

									    if($configsRecordArray[$i]['param']=='TIMETABLE_FORMAT'){
                                        ?>
                                            <select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>"  id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <?php
                                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                              echo HtmlFunctions::getInstance()->getTimeTableView($configsRecordArray[$i]['value']);
                                            ?>
                                            </select>
                                        <?php
                                        }
									elseif($configsRecordArray[$i]['param']=='MENTOR_COMMENTS_EMAIL'){
										?>
											<input type="text" class="inputbox" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" value="<?php echo $configsRecordArray[$i]['value']?>">
										<?php
										}
										elseif($configsRecordArray[$i]['param']=='STUDENT_REGISTRATION_EMAIL'){
										?>
											<input type="text" class="inputbox" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" value="<?php echo $configsRecordArray[$i]['value']?>">
										<?php
										}
										
                                        elseif($configsRecordArray[$i]['param']=='REGISTRATION_ONWER'){
                                        ?>
                                           <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='ALLOWED_ATTENDANCE_REGISTRATION'){
                                        ?>
                                           <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='FEEDBACK_PERMISSION'){
                                        ?>
                                           <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='FEE_PRINT_DETAIL_PDF'){
                                        ?>
                                           <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='FINE_INFO'){
                                        ?>
                                          <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php    
                                        }
                                        elseif($configsRecordArray[$i]['param']=='POLL_INFO'){
                                        ?>
                                           <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php    
                                        }
                                        else if($configsRecordArray[$i]['param']=='GRADE_I_DECLARE_RESULT'){
                                        ?>
                                            <!--<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
                                            -->


                                            <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }  
                                         else if($configsRecordArray[$i]['param']=='ATTENDANCE_REGISTER_INFO'){
                                        ?>
                                            <!--<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
                                            -->


                                            <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }  
                                         
                                        else if($configsRecordArray[$i]['param']=='GRADE_INFO'){
                                        ?>
                                            <!--<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
                                            -->


                                            <input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

                                            <input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            id="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                            <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }  
										else if($configsRecordArray[$i]['param']=='ADVERTISEMENT_ENABLE'){
										?>
											<!--<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}    
                                        
 elseif($configsRecordArray[$i]['param']=='GRADE_I_FORMAT_ALLOW'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
 
elseif($configsRecordArray[$i]['param']=='GRADE_F_FORMAT_ALLOW'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
} 
                                        
                                        elseif($configsRecordArray[$i]['param']=='CGPA_PRECISION_DECIMAL_ROUNDUP'){
                                                            ?>
                                            <select name="config_<?php echo $configsRecordArray[$i]['configId']?>"
                                                    id="config_<?php echo $configsRecordArray[$i]['configId']?>" class='inputbox1' style='width:188px'>
                                                <option value="">Select</option>
                                                <option <?php if($configsRecordArray[$i]['value']=='ceil') echo "selected";?> value="ceil">Round Up</option>
                                                <option <?php if($configsRecordArray[$i]['value']=='floor') echo "selected";?> value="floor">Round Down</option>
                                                <option <?php if($configsRecordArray[$i]['value']=='round') echo "selected";?> value="round">Normal Rounding</option>
                                                <option <?php if($configsRecordArray[$i]['value']=='noround') echo "selected";?> value="noround">No Rounding</option>
                                            </select>
                                            </nobr>
                                        <?php
                                        }

                                        elseif($configsRecordArray[$i]['param']=='BIRTHDAY_GREETING_MESSAGE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
                                        
                                        
elseif($configsRecordArray[$i]['param']=='INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='PARENTS_INFO'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='SIBLING'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='COURSE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='ADMINISTRATIVE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='SCHEDULE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='SHOW_HOD_APPRAISAL'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='MARKS'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='ATTENDANCE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}

elseif($configsRecordArray[$i]['param']=='FEES'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}

elseif($configsRecordArray[$i]['param']=='RESOURCE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}

elseif($configsRecordArray[$i]['param']=='FINAL_RESULT'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='OFFENSE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}

elseif($configsRecordArray[$i]['param']=='MISC_INFO'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='MESSAGE_CORRESPONDENCE'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='MOODLE_URL'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='ADMIT_STUDENT_REQUIRED_FIELD'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}

elseif($configsRecordArray[$i]['param']=='FLASHING_NEW_ICON_NOTICES'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
elseif($configsRecordArray[$i]['param']=='PERSONAL_INFO'){
?>
       <input  type="radio" value="1" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

       <input type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
               id="config_<?php echo $configsRecordArray[$i]['configId']?>"
       <?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
<?php
}
										elseif($configsRecordArray[$i]['param']=='TUTORIAL_GROUP_EXISTS'){
										?>
											<!--<select style="width:82px" size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="" selected="selected">Select</option>
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
											<option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
											</select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
										<?php
										}
										
										elseif($configsRecordArray[$i]['param']=='PAGINATION_POSITION'){
										?>
											<select size="1" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
											<option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>bottom</option>
											<option value="2" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>top</option>
											<option value="3" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>both top and bottom</option>
											</select>
										<?php
										}
                                       
                                        elseif($configsRecordArray[$i]['param']=='SHOW_MOODLE_FRAME_NEW'){
                                        ?>
                                           <!-- <select style="width:82px" size="1" style="width:82px" class="selectfield" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>">
                                            <option value="" selected="selected">Select</option>
                                            <option value="1" <?php if($configsRecordArray[$i]['value']=='1') echo "selected";?>>Yes</option>
                                            <option value="0" <?php if($configsRecordArray[$i]['value']=='0') echo "selected";?>>No</option>
                                            </select>
											-->


											<input  type="radio" value="1"  name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='1') echo "checked";?> />Yes&nbsp;&nbsp;

											<input  type="radio" value="0" name="config_<?php echo $configsRecordArray[$i]['configId']?>"
											id="config_<?php echo $configsRecordArray[$i]['configId']?>"
											<?php if($configsRecordArray[$i]['value']=='0') echo "checked";?> />No
                                        <?php
                                        }
										elseif($configsRecordArray[$i]['param']=='RECORDS_PER_PAGE'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="recordPerPage" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='LINKS_PER_PAGE'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="linkPerPage" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='RECORDS_PER_PAGE_TEACHER'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="recordPerPageTeacher" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='RECORDS_PER_PAGE_ADMIN_MESSAGE'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="recordPerPageAdmin" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="recordPerPageMessage" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='DAY_LIMIT_FOR_NOTIFICATION'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="recordPerPageMessage" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='RECORD_LIMIT_FOR_NOTIFICATION'){

											echo '<input maxlength="5" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" id="recordPerPageMessage" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
										}
										elseif($configsRecordArray[$i]['param']=='PRINT_REPORT_LOGO'){

											if($configsRecordArray[$i]['value']){

												$imgSrc7= IMG_HTTP_PATH."/Institutes/".$configsRecordArray[$i]['value'];
											}
											else{
												$imgSrc7= IMG_HTTP_PATH."/file.gif";

											}
										?>
											<input type="file" name="printReportLogo" id="printReportLogo">
											<img src='<?php echo $imgSrc7?>' width='20' height='20' id='printReportLogoImageId'/>
										<?php
										}
											elseif($configsRecordArray[$i]['param']=='DISCLAIMER_TEXT'){
										?>
											<textarea class="inputbox1" name="config_<?php echo $configsRecordArray[$i]['configId']?>" id="config_<?php echo $configsRecordArray[$i]['configId']?>" cols='60' rows='5'><?php echo $configsRecordArray[$i]['value']?></textarea>
										<?php
										}
										else{
                                           if(strlen(strip_slashes($configsRecordArray[$i]['value']))>=10) {
											  echo '<input maxlength="500" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
                                           }
                                           else {
                                              echo '<input maxlength="500" class="inputbox1" type="text" name="config_'.$configsRecordArray[$i]['configId'].'" value="'.strip_slashes($configsRecordArray[$i]['value']).'" size="31">';
                                           }
										}
										echo '</td>
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
							<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
							</td>
						</tr>
					</table>

				   </div>



				 </div>

				   <script type="text/javascript">
					initTabs('dhtmlgoodies_tabView1',Array('Address','Attendance','Admit Student','Messaging','I Card','Bus Pass','Time Table','Fees','Performance','Student Login','SMS Alerts','Other'),0,985,435,
                                                     Array(false,false,false,false,false,false,false,false,false,false,false,false));
					//'Reminders','Parent Alerts','
				   </script>
				   </td>
				   </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

 </form>
<?php
// $History: listConfigContents.php $
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 10-02-23   Time: 3:46p
//Updated in $/LeapCC/Templates/Configs
//updated admit student with config setting for registration number
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 10-02-11   Time: 6:00p
//Updated in $/LeapCC/Templates/Configs
//fixed bug no 0002823
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 10-01-12   Time: 12:04p
//Updated in $/LeapCC/Templates/Configs
//added "Performance" Tab in config setting
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 09-12-24   Time: 3:27p
//Updated in $/LeapCC/Templates/Configs
//config we can change the print report image if print report image is
//not available.
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 09-11-06   Time: 12:09p
//Updated in $/LeapCC/Templates/Configs
//Updated with Paging Parameter in config management
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 09-09-03   Time: 4:18p
//Updated in $/LeapCC/Templates/Configs
//Added Time table conflict parameters
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 09-09-01   Time: 3:23p
//Updated in $/LeapCC/Templates/Configs
//commented "Parent alerts" and reminders till the functionality is built
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 09-08-28   Time: 6:40p
//Updated in $/LeapCC/Templates/Configs
//added "Fees" tab in config management
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 8/10/09    Time: 5:40p
//Updated in $/LeapCC/Templates/Configs
//Added InstituteId in Config Table and Updated code
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 7/30/09    Time: 1:25p
//Updated in $/LeapCC/Templates/Configs
//1) 0000758: Admit (Admin) > Focus should be move back to appropriate
//field text box after validation message.
//2) 0000757: Admit (Admin) > Focus should be move back to appropriate
//field text box after validation message.
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 7/14/09    Time: 10:31a
//Updated in $/LeapCC/Templates/Configs
//Updated with bus pass and i card parameters
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 7/13/09    Time: 6:49p
//Updated in $/LeapCC/Templates/Configs
//added reminder and other Bus Pass config parameter
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/18/09    Time: 11:34a
//Updated in $/LeapCC/Templates/Configs
//fixed: 0000062: Config Master �Admin > Breadcrumb is not correct.
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/12/09    Time: 6:09p
//Updated in $/LeapCC/Templates/Configs
//added delete attendance and college registration number parameter
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/01/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Configs
//added "I-card" and "Bus Pass" Instruction parameter
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 6/01/09    Time: 10:55a
//Updated in $/LeapCC/Templates/Configs
//Updated with Admit student and messaging tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/30/09    Time: 10:17a
//Updated in $/LeapCC/Templates/Configs
//Added Messaging tab in config setting to give permission to send sms to
//teachers
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/26/09    Time: 11:55a
//Updated in $/LeapCC/Templates/Configs
//removed fees Tab for now as there are no rules for this tab
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/04/09    Time: 3:48p
//Updated in $/LeapCC/Templates/Configs
//Implemented "Other" tab in config settings for time table format
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 3/18/09    Time: 12:16p
//Created in $/LeapCC/Templates/Configs
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 3/18/09    Time: 11:33a
//Created in $/Leap/Source/Templates/Configs
//Intial checkin
//
//*****************  Version 36  *****************
//User: Rajeev       Date: 2/24/09    Time: 6:15p
//Updated in $/Leap/Source/Templates/ScStudent
//Fixed issues and added address verification
//
//*****************  Version 35  *****************
//User: Rajeev       Date: 1/24/09    Time: 4:05p
//Updated in $/Leap/Source/Templates/ScStudent
//made valign top in resource tab
//
//*****************  Version 34  *****************
//User: Rajeev       Date: 1/22/09    Time: 5:05p
//Updated in $/Leap/Source/Templates/ScStudent
//changed table height
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 1/22/09    Time: 2:33p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with left align
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 1/16/09    Time: 2:38p
//Updated in $/Leap/Source/Templates/ScStudent
//updated formatting
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Templates/ScStudent
//use student, dashboard, sms, email icons
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 1/13/09    Time: 12:24p
//Updated in $/Leap/Source/Templates/ScStudent
//Updated with centralized message, required field, left align all fields
//
//*****************  Version 29  *****************
//User: Rajeev       Date: 12/22/08   Time: 6:25p
//Updated in $/Leap/Source/Templates/ScStudent
//Added Offense tab in student detail
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 12/15/08   Time: 5:20p
//Updated in $/Leap/Source/Templates/ScStudent
//added CGPA parameter
//
//*****************  Version 27  *****************
//User: Rajeev       Date: 12/08/08   Time: 5:53p
//Updated in $/Leap/Source/Templates/ScStudent
//added scroll auto and fees ajax based
//
//*****************  Version 26  *****************
//User: Rajeev       Date: 11/21/08   Time: 3:09p
//Updated in $/Leap/Source/Templates/ScStudent
//added Ajax functionality on hostel and bus route
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 11/21/08   Time: 1:02p
//Updated in $/Leap/Source/Templates/ScStudent
//added functionality to fetch final result
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 11/13/08   Time: 4:14p
//Updated in $/Leap/Source/Templates/ScStudent
//updated resource tab
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 11/13/08   Time: 11:46a
//Updated in $/Leap/Source/Templates/ScStudent
//Updated Student tab with complete Ajax functionality
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 11/12/08   Time: 1:46p
//Updated in $/Leap/Source/Templates/ScStudent
//made complete student module based on all classes
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 11/05/08   Time: 6:00p
//Updated in $/Leap/Source/Templates/ScStudent
//added "Resource " in student tab
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 10/20/08   Time: 6:19p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with new parameters for display
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 10/14/08   Time: 12:56p
//Updated in $/Leap/Source/Templates/ScStudent
//updated validations


//
//*****************  Version 18  *****************
//User: Rajeev       Date: 10/06/08   Time: 5:02p
//Updated in $/Leap/Source/Templates/ScStudent
//updated time table for student formatting
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 10/06/08   Time: 2:59p
//Updated in $/Leap/Source/Templates/ScStudent
//updated student time table format
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 9/23/08    Time: 5:00p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with new student filter
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 9/22/08    Time: 5:42p
//Updated in $/Leap/Source/Templates/ScStudent
//added file upload action
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 9/22/08    Time: 4:32p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with subject code in section display
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/20/08    Time: 8:48p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with username validations
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 9/19/08    Time: 4:07p
//Updated in $/Leap/Source/Templates/ScStudent
//updated files according to subject centric
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/18/08    Time: 7:31p
//Updated in $/Leap/Source/Templates/ScStudent
//updated student photo upload path
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/17/08    Time: 8:05p
//Updated in $/Leap/Source/Templates/ScStudent
//added functionality of student time table according to section
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/17/08    Time: 2:47p
//Updated in $/Leap/Source/Templates/ScStudent
//updated with asterix on mandatory fields
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Templates/ScStudent
//updated back button with class
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Templates/ScStudent
//updated as respect to subject centric
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/16/08    Time: 6:53p
//Updated in $/Leap/Source/Templates/ScStudent
//updated cellsapcing in attendance
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/16/08    Time: 5:40p
//Updated in $/Leap/Source/Templates/ScStudent
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:55p
//Updated in $/Leap/Source/Templates/ScStudent
//updated files according to subject centric
?>
