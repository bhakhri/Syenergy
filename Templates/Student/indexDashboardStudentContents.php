<?php
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
?>
<script>
function testFn() {
    guiders.hideAll();
    addNewBread("menuLookup");
}			

function addNewBread(moduleName) {
   url = '<?php echo HTTP_LIB_PATH;?>/ajaxGuiders.php';

   new Ajax.Request(url,
   {
     method:'post',
     parameters: { moduleName: moduleName },
     onCreate: function(){
        showWaitDialog(true);
     },
     onSuccess: function(transport){
       hideWaitDialog(true);
     },
     onFailure: function(){ }
     });
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
			  <td height="10" colspan="2"></td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows">
     				<b><span style="font-size: 12px; color: #BB0000;"> 
				<?php
					  global $sessionHandler;  
					  $ttStudentId =  $sessionHandler->getSessionVariable('StudentId');
					  $ttClassId =  $sessionHandler->getSessionVariable('ClassId');
					  echo HtmlFunctions::getInstance()->getStudentSubjectDetails($ttClassId,$ttStudentId);
					?></span></b></td>
					
			<td align="right">
            <!-- Added for autosuggest -->
            <input type="text" name="menuLookup" class="fadeMenuText" style="width:200px" id="menuLookup"
            onkeyup="getMenuLookup();" onclick="changeDefaultTextOnClick();" onblur="changeDefaultTextOnBlur();"
            value="Menu Lookup.." autocomplete="off"/>&nbsp;
			<?php
			   $status=0;
			   require_once(MODEL_PATH . "/GuidersManager.inc.php");
			   $returnStatus=GuidersManager::getInstance()->checkGuidersEntry("menuLookup");
			   if(count($returnStatus)>0) {
			     $status=1;
			   }
			if($status==0){
			?>
			<script type="text/javascript">
				      guiders.createGuider({
				      attachTo: "#menuLookup",
				      buttons: [{name: "Close", onclick:testFn}],
				      description: "Menu lookup helps you find menu options easily and quickly. Just enter the keyword that matches your menu option and menu \
							lookup automatially guides you..",
				      id: "fourth",
				      next: "fifth",
				      position: 5,
				      title: "Find Menu Options Quickly!",
				      width: 400
				    }).show();
				</script>
			<?php }?>
            <div id="menuLookupContainer" style="position:absolute;z-index:100;padding:0px 0px 0px 0px; text-align:left;
            display:none; border:1px solid #7F9DB9; margin-right:0px;"></div>
            <!-- Auto suggest ends -->
            </td>
				</tr>
		</table></td></strong>
  </tr>
      <tr>
        <td height="405"  valign="top">

<?php 
$isHostel ='0';   
$isOnlinePayment ='0';
?>
<div id="div_Outer">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
			<td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                        <td valign="middle" width="20%" class="content_title">Dashboard: </td>
                         <?php
                          global $sessionHandler; 
                          $isHostel = '0';
                          $isHostel=$sessionHandler->getSessionVariable('HOSTEL_REGISTRATION_NEW_RENEW');
                          if($isHostel=='1'){
                            ?>   
                             <td valign="middle" align="left" width="60%" class="content_title">
                                  <a href="#"  style="color:white" onclick="showHostelDetails('DivInstructions',400,550);return false;">
                                    <img src="<?php echo IMG_HTTP_PATH?>/blink.gif" />&nbsp;&nbsp;Apply for New or Re-New Hostel Facilities
                                  </a>  
                             </td>
                              <?php
                          }
                          
                          $isOnlinePayment='0';
                          $isOnlinePayment=$sessionHandler->getSessionVariable('ONLINE_FEE_PAYMENT');    
                          if($isOnlinePayment=='1'){
                        ?>
                             <td valign="middle" align="left" class="content_title" width="60%" >
                          	      <a href="#"  style="color:white" onclick="displayWindow('OnlineFeeInstruction',850,750);return false;">
                          		    <img src="<?php echo IMG_HTTP_PATH?>/blink.gif" />&nbsp;&nbsp;Online Fee Payment
                                  </a>
                              </td>                          	
                          <?php
                          }
                        ?>     
                        <?php
                          if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
                        ?>
                         <td valign="middle" align="right" class="content_title" width="60%" >
                         	<a href="<?php echo UI_HTTP_PATH;?>/provideFeedbackAdv.php"  style="color:white" ><img src="<?php echo IMG_HTTP_PATH?>/blink.gif" />&nbsp;You have to complete your feedback to get full access of your account</a></td>
                         <?php
                          }
                         else{
                         ?>
                         <td valign="middle" align="right" class="content_title" width="60%" nowrap="nowrap">
                          <?php
                          if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==1){
                           ?>
                             <a href="<?php echo UI_HTTP_PATH;?>/provideFeedbackAdv.php"  style="color:white" ><img src="<?php echo IMG_HTTP_PATH?>/blink.gif" /> [ Complete the feedback by <?php echo $sessionHandler->getSessionVariable('LastDateOfProvidingFeedback');?> or your login will be disabled ]</a>
                           <?php
                          }
                          ?>
                          
                         
                          <a href="listInstituteEvents.php"  style="color:white" ><i>Event</i></a>
                         </td>
                         <?php
                         }
                         ?>
                        <td valign="middle" align="right" class="content_title" width="18%" nowrap="nowrap">
                            <?php
                              $condition='';
                              $condition = " WHERE userId='".$sessionHandler->getSessionVariable('UserId')."'";
                              $totalRecord = CommonQueryManager::getInstance()->getUserLastLogin($condition);
                              if($totalRecord[0]['dateTimeIn']!='') {
                                echo "<span  class='redLink'>&nbsp; Last Login:&nbsp;".UtilityManager::formatDate($totalRecord[0]['dateTimeIn'],true)." </span>";
                              }
                            ?>
                        &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
             </tr>
            <tr>

<td valign="top" class="contenttab_row" align="center" width="100%">
	<table width="100%" border="0" cellspacing="4" cellpadding="0">
		<tr>
		  <td width="340px" valign="top" align="center" rowspan="3">
			 <table cellspacing="4" cellpadding="0" border="0">
				<tr>
					<td width="330px" valign="top" align="center" >
		<?php if($roleId==4){ 	?>				
<table width="330" height="513" border="0" cellspacing="0" cellpadding="0" style="vertical-align:top;">
          <tr>
            <td align="left" width="11" class="box_left"></td>
            <td align="left" style="background-repeat:repeat-n;" class="box_middle" height="31"><b class="fontTitleM">Notices/Latest Resources</b></td>
            <td align="right" width="11" class="box_right"></td>
          </tr>
          <tr>
            <td align="left" class="">&nbsp;</td>
            <td align="left" style="vertical-align:top;">
						
					
            <table border="0" width="100%" style="vertical-align:top;">             
              <tr>
                <td>
                	<div id="dhtmlgoodies_tabView1"  style="vertical-align:top;top-margin:-100;" >
                        <div class="dhtmlgoodies_aTab" style="overflow:auto" >
			    <div id="notices" style="vertical-align:top;">
				<table border="0" width="100%" style="vertical-align:top;" >
						<tr>
							<td valign="top">
								<table width="100%" border="0">
								<?php            
									$recordCount = count($noticeRecordArray); 
									if($recordCount >0 && is_array($noticeRecordArray) ) {
										for($i=0; $i<$recordCount; $i++ ) {
                                            if($noticeRecordArray[$i]['visibleMode']=='3') {  
                                                $visibleImageName = IMG_HTTP_PATH."/urgent1.png";
                                              }
                                              else if($noticeRecordArray[$i]['visibleMode']=='2') {  
                                                $visibleImageName = IMG_HTTP_PATH."/important1.png";  
                                              }
                                              else {
                                                $visibleImageName = IMG_HTTP_PATH."/new.gif";  
                                              }

									//$downloadCount = ' ('.$noticeRecordArray[$i]['downloadCount'].')';
											//$bg = $bg =='row0' ? 'row1' : 'row0';
											$title="From : ".strip_slashes($noticeRecordArray[$i]['visibleFromDate'])." To : ".strip_slashes($noticeRecordArray[$i]['visibleToDate'])."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1);
											echo '<tr class="'.$bg.'">


											<td valign="top" class="padding_top" >&bull;&nbsp;&nbsp;
											<style type="text/css">
											a:link {font-weight:bold;} 
					 						

											</style>


											<a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'ViewNotices\',650,350);return false;" title="'.$title.'" >
											<span style="color:#'.$noticeRecordArray[$i]['colorCode'].'">'.strip_tags(trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),20)).' - <I>'.$noticeRecordArray[$i]['abbr'].'</I></span>';


                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               global $sessionHandler;
                                               $tdays = $sessionHandler->getSessionVariable('FLASHING_NEW_ICON_NOTICES');
                                                 if($tdays=='') {
                                                   $tdays=0;
                                                 }
                                                 if(is_numeric($tdays) === true) {
                                                   $tdays = intval($tdays);
                                                   if($tdays <= 0 ) {
                                                     $tdays = 0;
                                                   }
                                                 }
                                                 else {
                                                   $tdays = 0;
                                                 }
                                               $dt  = $noticeRecordArray[$i]['visibleFromDate'];
                                               $dtArr = explode('-',$dt);
                                               $dtArr = explode('-',$dt);
                                               $dt1 = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]+$tdays), date($dtArr[0])));
                                               $currentDate = date('Y-m-d');
                                               if($currentDate<=$dt1 && $tdays!=0) {
                                                 echo '&nbsp;<img src="'.$visibleImageName.'">';
                                               }
                                            echo '</a></td>';
											$fileName = IMG_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
											if(file_exists($fileName) && ($noticeRecordArray[$i]['noticeAttachment']!="")) {
												$fileName1 = IMG_HTTP_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
												echo '<td valign="top"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
											}
											else {
												echo '<td align="center">'.NOT_APPLICABLE_STRING.'</td>';
											}
											echo '</tr>';
										}
										echo '<tr><td colspan="2" align="right" style="padding-right:10px" valign="bottom"><a href="listInstituteNotices.php"><u>Show all Notices</u></a></td></tr>';
									}
									else {
										echo '<tr><td colspan="2" style="padding-top:200px;" align="center" class="redColor">There are no new Notices to show</td></tr>';
									}
									?>
								</table>
							</td>
						</tr>
					</table>

			    </div>
			</div>
			
			<div class="dhtmlgoodies_aTab">
			    <div id="resources" style="overflow:auto; vertical-align:top;">
				      <table border='0' width='100%' >
                        <tr>
                            <td valign='top'>
                                <table width='100%' border='0'>
                                  <tr>
                                    <td style='padding-top:90px;' align='center' class='redColor'>
                                      There are no new Resources Uploaded
                                    </td>
                                  </tr>
                                </table>
                              </td>
                        </tr>      
                      </table>
			    </div>
			</div>
		</div>
				<script type="text/javascript">
		  		 initTabs('dhtmlgoodies_tabView1',
			     	 Array('Notices','Latest Uploaded Resources'),10,330,443,
			    	 Array(false,false));
		  		</script> 
			
					 </td>   
				     </tr>
				   </table>         
					 </td>
			    <td class="">&nbsp;</td>
			  </tr>
			  <tr>
			    <td height="0" valign="top" colspan="3" class=""><img src="'.IMG_HTTP_PATH.'/spacer.gif" height="0"></td>
			  </tr>
			</table>
	
			<?php }
			      else{
			?>
					<?php
						 echo HtmlFunctions::getInstance()->tableBlueHeader('Notices','width="330"','height="513"','','style="vertical-align:top;"');?>				
					
					<table border="0" width="100%" height="362">
						<tr><td height="3px"> </td> <tr>
						<tr>
							<td valign="top">
								<table width="100%" border="0">
                                                                <?php
																	$recordCount = count($noticeRecordArray);
																	if($recordCount >0 && is_array($noticeRecordArray) ) {
																		for($i=0; $i<$recordCount; $i++ ) {

if($noticeRecordArray[$i]['visibleMode']=='3') {  
                                                $visibleImageName = IMG_HTTP_PATH."/urgent1.png";
                                              }
                                              else if($noticeRecordArray[$i]['visibleMode']=='2') {  
                                                $visibleImageName = IMG_HTTP_PATH."/important1.png";  
                                              }
                                              else {
                                                $visibleImageName = IMG_HTTP_PATH."/new.gif";  
                                              }
																			$bg = $bg =='row0' ? 'row1' : 'row0';
																			$title="From : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleFromDate']))." To : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleToDate']))." ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1);
																			echo '<tr class="'.$bg.'">
																			<td valign="top" class="padding_top" >&bull;&nbsp;&nbsp;<a href="" name="bubble" onClick="editWindow('.$noticeRecordArray[$i]['noticeId'].',\'ViewNotices\',520,400); return false;" title="'.$title.'" ><span style="color:#'.$noticeRecordArray[$i]['colorCode'].'">'.trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),35).' - <I>'.$noticeRecordArray[$i]['abbr'].'</I></span>';
																			require_once(BL_PATH.'/HtmlFunctions.inc.php');
																			global $sessionHandler;
																			$tdays = $sessionHandler->getSessionVariable('FLASHING_NEW_ICON_NOTICES');
																			if($tdays=='') {
																				$tdays=0;
																			}
																			if(is_numeric($tdays) === true) {
																				$tdays = intval($tdays);
																				if($tdays <= 0 ) {
																				$tdays = 0;
																				}
																			}
																			else {
																				$tdays = 0;
																			}
																			$dt  = $noticeRecordArray[$i]['visibleFromDate'];
																			$dtArr = explode('-',$dt);
																			$dtArr = explode('-',$dt);
																			$dt1 = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]+$tdays), date($dtArr[0])));
																			$currentDate = date('Y-m-d');
																			if($currentDate<=$dt1 && $tdays!=0) {
																				                                                 echo '&nbsp;<img src="'.$visibleImageName.'">';
																			}
																			echo '</a></td>';
																			if($noticeRecordArray[$i]['noticeAttachment']=='') {
																				$noticeRecordArray[$i]['noticeAttachment']=NOT_APPLICABLE_STRING;
																			}
																			else {
																				$noticeRecordArray[$i]['noticeAttachment']='<a href="'.IMG_HTTP_PATH.'/Notice/'.$noticeRecordArray[$i]['noticeAttachment'].'" target="_blank"><img title="Download" src="'.IMG_HTTP_PATH.'/download.gif"></a>';
																			}
																			echo '<td align="center">'.$noticeRecordArray[$i]['noticeAttachment'].'</td>';
																			echo '</tr>';
																		}
																		echo '<tr><td colspan="2" align="right" style="padding-right:10px" valign="bottom"><a href="displayNotices.php"><u>All Notices</u></a></td></tr>';
																	}
																	else {
																		echo '<tr><td colspan="2" style="padding-top:60px;" align="center" class="redColor">No Notices</td></tr>';
																	}
                                                                    ?>
                                                                </table>
							</td>
						</tr>
					</table>

					<?php
					//  floatingDiv_Close();
					echo HtmlFunctions::getInstance()->tableBlueFooter();
					//*************End of Div*********
					?>
		<?php } ?>	 
				
					</td>
					<td valign="top" >
					 <table cellspacing="0" cellpadding="0" border="0">
					    <tr>
						<td width="300px" valign="top" >

						<?php
						//*************Used For Creating*********
						echo HtmlFunctions::getInstance()->tableBlueHeader('Admin Messages','width="300"','height="171"','');
						?>
		
						<table width="100%" border="0" height="105">
				
				<tr>
					<td valign="top" colspan="2">
						<table border="0" width="100%" height="105">
							<?php
							$recordCountEvents = count($adminMessages);
							// $totalHeightCovered = (15 * $recordCountEvents) + 20;
							// $pendingHeight = round(157- $totalHeightCovered,0);

							if($recordCountEvents >0 && is_array($adminMessages) ) {
							  for($i=0; $i<$recordCountEvents; $i++ ) {
							    $bg = $bg =='row0' ? 'row1' : 'row0';
							    $title = strip_slashes(strip_tags($adminMessages[$i]['message']),150,2);
							    ?>
							    <tr class='<?php echo $bg;?>'>
								    <td valign='top' align="center" width="5%"><?php echo ($records+$i+1);?></td>
								    <td valign='top' align='left' style="padding-left:2px" width="80%">
			<a href="" name="bubble" onclick="showAdminDetails('<?php echo $adminMessages[$i]['messageId']?>','ViewAdmin',650,350);return false;" title="<?php echo strip_tags(HtmlFunctions::getInstance()->removePHPJS($title)) ?>">
			<?php echo trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($adminMessages[$i]['message']))),25) ?>
			</td>
			<?php
			$fileName = IMG_PATH."/AdminMessage/".$adminMessages[$i]['messageFile'];
			if(file_exists($fileName) && ($adminMessages[$i]['messageFile']!="")) {
			$fileName1 = IMG_HTTP_PATH."/AdminMessage/".$adminMessages[$i]['messageFile'];
			echo '<td valign="top" align="center" width="15%"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
			}
			else {
			echo '<td align="center" align="center" width="15%">'.NOT_APPLICABLE_STRING.'</td>';
			}
							    echo "</tr>";
							  }
						      echo '<tr><td colspan="3" align="right" style="padding-right:10px" valign="bottom"><a href="listAdminMessages.php"><u>Show all Messages</u></a></td></tr>';
							}
							else{
						   	   echo '<tr><td align="center" style="height:100px" valign="middle" colspan="6" class="redColor">There are no new Messages to show</td></tr>';
							}
							?>
						</table>
					</td>
				</tr>
			</table>						
		
						<?php
						//  floatingDiv_Close();
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						?>
						</td>
					   </tr>
					   <tr>
						<td width="300px" valign="top" align="right">

						<?php
						echo HtmlFunctions::getInstance()->tableBlueHeader('Teacher Comments','width="300"','height="171"','');
						?>
	
						<table width="100%" border="0" height="125">
			<!--<tr class="rowheading2" >
				<th align="center" width="5%">#</th>
				<th width="30%" align="center">From</th>
				<th width="40%" align="center">Comments</t h>
				<th width="20%" align="center">Attachment</th>
			</tr>-->
			<tr>
				<td valign="top" colspan="4">
					<table border="0" width="100%" height="125">
						<?php
						$recordCountEvents = count($totalMessages);
						if($recordCountEvents >0 && is_array($totalMessages) ) {
							for($i=0; $i<$recordCountEvents; $i++ ) {
								$bg = $bg =='row0' ? 'row1' : 'row0';
								$title="From : ".strip_slashes($totalMessages[$i]['visibleFromDate'])." To : ".strip_slashes($totalMessages[$i]['visibleToDate'])."
								".trim_output2(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($totalMessages[$i]['comments']))),250,2);
								echo '<tr class="'.$bg.'">
								<td valign="top" align="center" width="4%" >'.($records+$i+1).'</td>
								<td  valign="top" align="left" width="32%" >'.strip_slashes($totalMessages[$i]['employeeName']).'</td>

								<td  valign="top" align="left" width="44%" ><a href="" name="bubble" onclick="showTeacherDetails('.$totalMessages[$i]['commentId'].',\'ViewTeacher\',650,350);return false;" title="'.$title.'">'
								.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($totalMessages[$i]['comments']))),25).
								'</td>';
								//echo '<td align="center" width="20%">';
								/*
								$fileTeacherMessage = IMG_PATH."/Teacher/".$totalMessages[$i]['commentAttachment'];
								if(file_exists($fileTeacherMessage) && ($totalMessages[$i]['commentAttachment']!="")){
								$fileTeacherMessage1 = IMG_HTTP_PATH."/Teacher/".$totalMessages[$i]['commentAttachment'];
								echo '<td valign="top" align="center"><a href="'.$fileTeacherMessage1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
								}
								else {
								echo '<td align="center">'.NOT_APPLICABLE_STRING.'</td>';
									}*/
								echo '</tr>';
							}
							echo '<tr><td colspan="4" align="right" style="padding-right:10px" valign="bottom"><a href="listTeacherComments.php"><u>Show all Teacher Comments</u></a></td></tr>';
						}
						else{
							echo '<tr><td style="height:120px" align="center" valign="middle" colspan="4" class="redColor">There are no new Teacher Comments</td></tr>';
						}
						?>
					</table>
				</td>
			</tr>
			</table>						
	
						<?php
						//  floatingDiv_Close();
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						?>
						</td>
					   </tr>

					    <tr>
						<td width="300px" valign="top" align="right">

						<?php
						echo HtmlFunctions::getInstance()->tableBlueHeader('Tasks','width="300"','height="171"','');
						?>
						
							
				<table width="100%" border="0" height="132">
				<tr>
				<td valign="top" >
				<table border="0" width="100%">
					<?php
					$recordTasksCount = count($showTask);
					if($recordTasksCount >0 && is_array($showTask) ) {

						for($i=0; $i<$recordTasksCount; $i++ ) {
							//if ($showTask[$i]['Result'] > 0) {
								//$j=0;
							$bg = $bg =='row0' ? 'row1' : 'row0';
							$title="Description : ".trim_output2(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($showTask[$i]['shortDesc']))),250,2);
							//echo ($showTask[$i]['status']);
							if ($showTask[$i]['status'] == 0 ) {
								echo '<tr class="'.$bg.'" style="color:red" id="tasksTdId_'.$i.'">
								<td valign="top" align="center" width="4%" >'.($records+$i+1).'</td>
								<td  valign="top" align="left" width="60%" id="tasksTdId__'.$i.'" ><a href="" id="statusLink'.$i.'" name="bubble" style="color:red" onclick="showTaskDetails('.$i.','.$showTask[$i]['taskId'].',\'ViewTasks\',350,250);return false;" title="'.$title.'">'
								.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($showTask[$i]['title']))),25).
								'</a></td>';


								$showTask[$i]['dueDate'] = UtilityManager::formatDate($showTask[$i]['dueDate']);
								echo '<td valign="top" width="40%" id="tasksTdId___'.$i.'">'.$showTask[$i]['dueDate'].'</td>';


								echo '<td id="tasksTdId____'.$i.'" valign="middle"><img src='.IMG_HTTP_PATH.'/deactive.gif border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:pointer"  onclick="changeStatus('.$i.','.$showTask[$i]['taskId'].','.$showTask[$i]['status'].')"></td></tr>';
							}
							else {
								echo '<tr class="'.$bg.'" id="tasksTdId_'.$i.'" >
								<td valign="top" align="center" width="4%" >'.($records+$i+1).'</td>
								<td  valign="top" align="left" width="60%" id="tasksTdId__'.$i.'" ><a href="" id="statusLink'.$i.'" name="bubble" onclick="showTaskDetails('.$i.','.$showTask[$i]['taskId'].',\'ViewTasks\',350,250);return false;" title="'.$title.'">'
								.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($showTask[$i]['title']))),25).
								'</a></td>';
								$showTask[$i]['dueDate'] = UtilityManager::formatDate($showTask[$i]['dueDate']);
								echo '<td valign="top" width="40%" id="tasksTdId___'.$i.'">'.$showTask[$i]['dueDate'].'</td>';

								echo '<td id="tasksTdId____'.$i.'" valign="middle"><img src='.IMG_HTTP_PATH.'/active.gif border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:pointer"  onclick="changeStatus('.$i.','.$showTask[$i]['taskId'].','.$showTask[$i]['status'].')"></td></tr>';
							}
						  //}
						}
						echo '<tr><td colspan="4" align="right" style="padding-right:10px" valign="bottom"><a href="listTask.php"><u>Show all Tasks</u></a></td></tr>';
					}

					else{
						echo '<tr><td style="height:120px" align="center" valign="middle" colspan="3" class="redColor">There are no new Tasks to show</td></tr>';
					}
					?>
				</table>
				</td>
				</tr>
				</table>
	
						<?php
						//  floatingDiv_Close();
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						?>
						</td>
					   </tr>		
						
					 </table>
					</td>
					<td align="right" style="vertical-align:top;">
                           	 <?php
					        //*************Used For Creating*********
					        // floatingDiv_Create('div_Alerts','Alerts');
					        echo HtmlFunctions::getInstance()->tableBlueHeader('Alerts','width="330"','height="513"','','style="vertical-align:top;"');
					        ?>

                         	<table width="100%" border="0" style="vertical-align:top;">
                                <tr>
                                   <td colspan="10" height="5px"></td>
                                </tr>  
						         <?php
                                $bg='row0';
                                $totalAlerts = 0;
                                if (count($feeArray) >0 && is_array($feeArray)) {    
                                    $totalAlerts++;
                                    if ($totalAlerts>=0 && $totalAlerts<=11) {
                                        for($i=0;$i< count($feeArray);$i++){
                                            $bg = $bg =='row0' ? 'row1' : 'row0';
                                            echo '<tr  style="vertical-align:top;"  class="'.$bg.'">';
                                            echo '<td align="left" valign="top" colspan="2"> <span > 
                                                        <ul id="nav" class="floatleft">
                        <li><a class="dmenu" href="#"><b>&bull;</b> Fee Due for :&nbsp;'.strip_slashes($feeArray[$i]['cycleName']).' Print Fee Receipt</a>  
                                                        <ul>  
        <li><a href="javascript:void(0)" onclick="alertFeeInstructions(\'academic\','.$feeArray[$i]['feeClassId'].');">Print Academic Fee</a></li>
        <li><a href="javascript:void(0)" onclick="alertFeeInstructions(\'hostel\','.$feeArray[$i]['feeClassId'].');">Print Hostel Fee</a></li>  
        <li><a href="javascript:void(0)" onclick="alertFeeInstructions(\'transport\','.$feeArray[$i]['feeClassId'].');">Print Transport Fee</a></li>  
        <li><a href="javascript:void(0)" onclick="alertFeeInstructions(\'all\','.$feeArray[$i]['feeClassId'].');">Print All Fee</a></li>  
                                                        </ul>  
                                                        </li>  
                                                        </ul>    
                                                        <br class="clear">  
                                                        </span> </td></tr>';
                                            }
                                        }
                                  }
                                  $recordCount=count($totalFeeStatus);
                                  if ($recordCount >0 && is_array($totalFeeStatus)) {
                                        for ($i=0; $i<$recordCount;$i++) {
                                            $totalAlerts++;
                                            if ($totalAlerts>=0 && $totalAlerts<=11) {
                                                $bg = $bg =='row0' ? 'row1' : 'row0';
                                                echo '<tr style="vertical-align:top;" class="'.$bg.'">';
                                                echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;Fee Due for :'.strip_slashes($totalFeeStatus[$i]['periodName']).' Rs. '.strip_slashes(number_format($totalFeeStatus[$i]['pending']),2,'.','').'</td></tr>';
                                            }
                                        }
                                  }
                                  $cnt=$timeTableMessages[0]['cnt'];
                                  if ($cnt>0 ) {
                                    $totalAlerts++;
                                    if ($totalAlerts>=0 && $totalAlerts<=11) {
                                        $bg = $bg =='row0' ? 'row1' : 'row0';
                                        echo '<tr  style="vertical-align:top;"  class="'.$bg.'">';
                                        echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;<a href="listTimeTable.php"><b>The time table has been changed</b></a></td></tr>';
                                    }
                                  }
                                  $recordCount=count($attendanceShortArray);
                                  if ($recordCount >0 && is_array($attendanceShortArray)) {
                                    for ($i=0; $i<$recordCount;$i++) {
                                        $totalAlerts++;
                                        if ($totalAlerts>=0 && $totalAlerts<=11) {
                                            $subCode = $attendanceShortArray[$i]['subjectCode'];
                                            $per = $attendanceShortArray[$i]['per'];
                                            $bg = $bg =='row0' ? 'row1' : 'row0';
                                            echo '<tr  style="vertical-align:top;"  class="'.$bg.'">';
                                            echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;Attendance Short in '.$subCode .' ('.$per.'%)</td></tr>';
                                        }
                                    }
                                  }
                                  if($sessionHandler->getSessionVariable('MARKS') == 1){
                                    $recordCount=count($testMartsArray);
                                    if ($recordCount >0 && is_array($testMartsArray)) {
                                        for ($i=0; $i<$recordCount;$i++) {
                                          $totalAlerts++;
                                          if ($totalAlerts>=0 && $totalAlerts<=11) {
                                            $subCode = $testMartsArray[$i]['subjectCode'];
                                            $subjectName = $testMartsArray[$i]['subject'];
                                            $marksScored = $testMartsArray[$i]['obtained'];
                                            $totalMarks = $testMartsArray[$i]['totalMarks'];
                                            $testAbbr = $testMartsArray[$i]['testAbbr'];
                                            $bg = $bg =='row0' ? 'row1' : 'row0';
                                            echo '<tr  style="vertical-align:top;"  class="'.$bg.'">';
                                            echo '<td align="left" valign="top" colspan="2">&bull;&nbsp;&nbsp;<a href="listStudentMarks.php" title="'.$subjectName.'">'.$subCode.'-'.$testAbbr.'-Scored:  '.$marksScored.'/'.$totalMarks.'</a></td></tr>';
                                          }
                                        }
                                    }
                                  }
                                  if($totalAlerts > 0) {
                                    echo '<tr  style="vertical-align:top;" ><td colspan="2" align="right" style="padding-right:10px" valign="bottom"><a href="listAllAlerts.php"><u>Show all Alerts</u></a></td></tr>';
                                  }
                                  if($totalAlerts == 0) {
                                    echo '<tr  style="vertical-align:top;" ><td colspan="2" align="center" style="padding-top:90px;" class="redColor">There are no new  Alerts to show</td></tr>';
                                  }
                            ?>
					        </table> 
			
					<?php
		            	//  floatingDiv_Close();
					    echo HtmlFunctions::getInstance()->tableBlueFooter();
					?>  
				   </td>
				</tr>
			 </table>



        </td>
    </tr>
</table>
<?php
floatingDiv_Close();
//*************End of Div*********
?>
</div>
</td>
</tr>
</table>

<?php floatingDiv_Start('ViewEvents','Event Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewEvents" action="" method="post">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Event: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerTitle" style="overflow:auto; width:580px; height:20px" ></div>
	</td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
    <td valign="middle" colspan="2" style="padding-left:3px"><B>Visible From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="visibleToDate"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerShortDescription" style="overflow:auto; width:580px; height:20px" ></div>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Long Description: </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="longDescription" style="overflow:auto; width:580px; height:200px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewNotices','Notice Description','',$wrapType=''); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewNotices" action="" method="post">
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Subject: </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerNotice" style="overflow:auto; width:580px; height:20px" ></div>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Department:</b></td>
</tr>

<tr>
    <td width="100%"  align="left" style="padding-left:3px">
    <div id="innerDepartment" style="overflow:auto; width:580px; height:20px" ></div>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
</tr>

<tr>
	<td valign="middle" colspan="2" style="padding-left:3px"><B>Visible From</B>: <span id="visibleFromDate11" style="height:20px"></span>&nbsp;&nbsp;<B>Visible To</B>: <span id="visibleToDate11"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Description: </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:3px">
	<div id="innerText" style="overflow:auto; width:580px;height:200px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewAdmin','Admin Messages Description','',$wrapType=''); ?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<form name="viewAdmin" action="" method="post">
		<tr>
			<td height="5px"></td></tr>
		<tr>
		   <td width="100%"  align="left" class="rowheading">&nbsp;<b>Subject </b></td>
		</tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:1px">
			<div id="innerSubject" style="overflow:auto; width:580px;" ></div>
			</td>
		</tr>
		<tr>
			<td width="100%"  align="Left" class="rowheading">&nbsp;<b>Date: </b></td>
		</tr>
		<tr>
			<td valign="middle" colspan="2" style="padding-left:3px"><B>From</B>: <span id="visibleMessageFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleMessageToDate"></span></td>
		</tr>
		<tr>
		   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Message Detail </b></td>
		</tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:1px">
			<div id="innerAdmin" style="overflow:auto; width:580px; height:200px" ></div>
			</td>
		</tr>

		<tr>
			<td height="5px"></td>
		</tr>

		   </form>
		</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewTeacher','Teacher Comments Description','',$wrapType=''); ?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<form name="viewTeacher" action="" method="post">
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
		   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Teacher Comments Detail </b></td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		<tr>
		<tr>
			<td width="100%"  align="left" style="padding-left:10px">
			<br />
			<div id="innerTeacherNotice" style="overflow:auto; width:580px; height:200px" ></div>
			</td>
		</tr>

		<tr>
			<td height="5px"></td>
		</tr>

		</table>

<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('ViewTasks','Task Description'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<form name="TaskDetail" action="" method="post">
	<input type="hidden" name="taskId" id="taskId" value="" />
	<tr>
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Title </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:&nbsp;
      <input type="text" id="title" name="title" style="width:170px" class="inputbox" />
     </td>
	</tr>
	<tr>
	  <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<strong>Short Description</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
	  <td align="top"><b>&nbsp;:&nbsp;&nbsp;</b><textarea name="shortDesc" id="shortDesc" cols="22" rows="3" style="vertical-align:top;"></textarea></td>
	 </tr>
	 <tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Task Date<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding" colspan="2">:&nbsp;&nbsp;<?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->datePicker('dueDate',date('Y-m-d'));
              ?>
      </td>
	</tr>
	<tr>
		<td width="35%" class="contenttab_internal_rows">&nbsp;<b>Reminder Option</b><?php echo REQUIRED_FIELD; ?></td>
		<td width="65%" ><b>&nbsp;:</b>&nbsp;
		  <input type="checkbox" id="dashboard" name="dashboard" checked="checked" />Dashboard
		  <input type="checkbox" id="sms" name="sms" />SMS
		</td>
	</tr>

	<tr>
        <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Prior Days<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding" colspan="2">:&nbsp;&nbsp;<input type="text" id="daysPrior" name="daysPrior" style="width:100px" class="inputbox" value="0" maxlength="2" />
      </td>
	</tr>

	<tr>
      <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Status</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td class="padding">:&nbsp;
      <select id="status" name="status" class="selectfield1" >
		<option value="0" selected="selected">Pending</option>
		<option value="1">Completed</option>
	  </select>
     </td>
	</tr>

  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ViewTasks');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</form>
</table>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('DivInstructions','Instrunctions'); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr><td height="5px"></td></tr>
	    <tr align="center">
		    <td class="contenttab_internal_rows" align="center" style=" font-size:15px;">
                <nobr>&nbsp;<strong>Instructions:</strong></nobr>
		    </td>
	     </tr>
	     <tr>
		    <td class="contenttab_internal_rows">
		        <ul style=" font-size:12px;"><br>
		            <li><b>1.</b> Read these instructions carefully before get register for New/Renew Hostel Facilities.</li><br>
		            <li><b>2.</b> Student can avail for hostel facilities and existing students can re-new the respective facilities till date.</li><br>
		            <li><b>3.</b> Student can Cancel the Hostel facilities allocation within 7 days of the filled form confirmation.</li><br>
		            <li><b>4.</b> If student Cancel the Hostel facility allocation after 7 days of form filled then, Student have to pay Rs.100 per day as a fine. 
		            </li><br>
		        </ul>
		    </td>
	     </tr>
	     <tr><td height="5px"></td></tr>
	     <tr>
		    <td class="contenttab_internal_rows" align="center" style="padding-left:235px;" colspan="4">
	            <input type="image" src="<?php echo IMG_HTTP_PATH;?>/next.png" onClick="getNextHostel();" />&nbsp;&nbsp;
	            <input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('DivInstructions');return false;" />
		    </td>
	     </tr>
     </table>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('HostelRegistration','Apply for New or Re-New Hostel Facilities'); ?>
<form name="hostelRegForm" id="hostelRegForm" method="post" onsubmit="return false;">
<?php
    global $sessionHandler; 
    $hostelStudentName = $sessionHandler->getSessionVariable('StudentName');
    $hostelRollNo = $sessionHandler->getSessionVariable('RollNo');
    $hostelFatherName = $sessionHandler->getSessionVariable('FatherName');
    $hostelClassName = $sessionHandler->getSessionVariable('ClassName');
?>	
	
<table width="100%" border="0" cellspacing="3px" cellpadding="2px" class="border">
<tr><td height="4px"></td></tr>
<tr>                
	<td class="contenttab_internal_rows"  ><nobr><b>Student Name </b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="hostelStudentName"><?php echo $hostelStudentName; ?></span><br/>
	</nobr> 
	               
	</td>

<tr>                
	<td class="contenttab_internal_rows" ><nobr><b>Father's Name </b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="hostelFatherName"><?php echo $hostelFatherName; ?></span><br/>
	</nobr>                    
	</td>

</tr>

<tr >                
	<td class="contenttab_internal_rows"  ><nobr><b>Roll No </b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="hostelRollNo"><?php echo $hostelRollNo; ?></span><br/>
	</nobr>                    
	</td>

</tr>
<tr>                
	<td class="contenttab_internal_rows" ><nobr><b>Class</b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="hostelClassName"><?php echo $hostelClassName; ?></span><br/>
	</nobr>                    
	</td>
</tr>
<tr>
  <td class="contenttab_internal_rows" height="5px;" colspan="3"></td>	
</tr>  
<tr id="hostelD1" style="display:none;">
  <td class="contenttab_internal_rows" style="color:red;font-size:12px;" colspan="3"><nobr><b>Hostel Details</b></nobr></td>	
</tr>
<tr id="hostelD2" style="display:none;">
  <td class="contenttab_internal_rows" height="2px;" colspan="3"></td>	
</tr>
<tr id="hostelD3" style="display:none;">                
	<td class="contenttab_internal_rows" ><nobr><b>Hostel(Room)</b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="hostelDetails"></span><br/>
	</nobr>                    
	</td>
</tr>  
<tr id="hostelD4" style="display:none;">                
	<td class="contenttab_internal_rows" ><nobr><b> Check Out Date  </b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="checkOutDate"></span><br/>
	</nobr>                    
	</td>
</tr> 
<tr>
  <td class="contenttab_internal_rows" height="3px;" colspan="3"></td>	
</tr>
<tr>
  <td class="contenttab_internal_rows" style="color:red;font-size:12px;" colspan="3"><nobr><b>Select Required Fields</b></nobr></td>	
</tr>
<tr>                
	<td class="contenttab_internal_rows" ><nobr><b>Hostel</b><?php echo REQUIRED_FIELD; ?></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<select id="registHostelId" name="registHostelId" class="selectfield"  style="width:200px;">
			<option value="">Select</option>
			 <?php
			 require_once(BL_PATH.'/HtmlFunctions.inc.php');
			 echo HtmlFunctions::getInstance()->getHostelName();
			?>
		</select> <br/>
	</nobr>                    
	</td>
</tr>  
<tr>                
	<td class="contenttab_internal_rows" ><nobr><b>Room Type</b><?php echo REQUIRED_FIELD; ?></nobr>               
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<select id="registRoomTypeId" name="registRoomTypeId" class="selectfield"  style="width:200px;">
			<option value="">Select</option>
			 <?php
			 require_once(BL_PATH.'/HtmlFunctions.inc.php');
			 echo HtmlFunctions::getInstance()->getHostelNameRoomType();
			?>
		</select> <br/>
	</nobr>                    
	</td>
</tr>  
<tr>                
	<td class="contenttab_internal_rows" ><nobr><b>Apply Date</b></nobr>                
	</td>
	<td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;&nbsp;</b>
		<span id="applyDate"><?php echo date("d-m-Y", mktime(0, 0, 0, date('m'), date('d')+30, date('Y')));?></span><br/>
	</nobr>                    
	</td>
</tr> 
<tr><td height="7px"></td></tr>
<tr>    
    <td class="contenttab_internal_rows" align="center" colspan="4"><nobr><b>
    <font color="red" font-size="20px;">Apply For New Or Re-new Hostel Facility</font></b>
    </nobr><br/><br/>
	</td>        
</tr>
<tr >
	<td height="7px" colspan="3" ></td> 
</tr>
<tr>
	<td align="center" style="padding-right:10px" colspan="3">
	 <input type="image"  src="<?php echo IMG_HTTP_PATH;?>/apply.png" onClick="return registerHostelDetails('0');return false;" />    
	&nbsp;&nbsp;
	<input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="return registerHostelDetails('1');return false;" />
	</td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>



<?php  floatingDiv_Start('AlertFeesInstruction','Read following instructions carefully before go for Fee Generate Print Slip','12','','','1'); ?>
<div id="divAlertFeesInstruction" style="overflow:auto; width:450px; vertical-align:top;"> 
    <input type="hidden" name="alertPayFee" id="alertPayFee"/>
    <input type="hidden" name="alertFeeClassId" id="alertFeeClassId"/>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" valign='top' nowrap><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
               If a student pay fee after Last Date then late fee fine will automatically generated as per Chitkara University rules.
           </td>
        </tr>    
        <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" nowrap valign='top'><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
             If you have paid your fee but fee entry is not submitted in chalkpad, and you still generate your slip then also automatic fine will be applied to your fee. So, please generate it carefully.
           </td>
        </tr>      
        <tr><td height="15px" ></td></tr>
        <tr>
            <td class="contenttab_internal_rows"  nowrap="nowrap" colspan='2'>
            <center>
            <input type="image" src="<?php echo IMG_HTTP_PATH;?>/generate.gif" onClick="printFeeReceipt(document.getElementById('alertPayFee').value,document.getElementById('alertFeeClassId').value);" />&nbsp;
                <input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AlertFeesInstruction');return false;" />
                </center>
            </td>
         </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 

<?php  floatingDiv_Start('OnlineFeeInstruction','Read these instructions carefully before go for ONLINE FEE PAYMENT','15','','','1'); ?>
<div id="divOnlineFeeInstruction" style="overflow:auto; width:500px; vertical-align:top;">  
	<form name="onlineFeeInstruction" id="onlineFeeInstruction" method="post" onsubmit="return false;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" valign='top' nowrap><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
            Students must ensure details in the FORM are all correctly filled up in all respect. 
            Incomplete or ambiguous information shall not be accepted by the bank.
           </td>
        </tr>    
        <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" nowrap valign='top'><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
            Follow the instructions to input the credit/debit card information. 
            </td>
        </tr>  
        <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" nowrap valign='top'><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
             Enter the information EXACTLY as it appears on your credit card (i.e ACCOUNT HOLDER NAME AND CONTACT NUMBER)                      
            </td>
        </tr>  
        <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" nowrap valign='top'><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
            Check the information for accuracy, and then click PAYMENT.
            </td>
        </tr>   
          <tr><td style="height:10px;"></td></tr>
        <tr>
           <td class="instrunctions" align="justify" width="1%" nowrap valign='top'><b>&bull;</b></td>
           <td class="instrunctions" align="justify" width="99%" valign='top' class='row1'>
            Please, make sure that the reference number and the amount are exactly right.
             The fees cannot be altered, because your fee will be identified by the exact amount.               
            </td>
        </tr>      
        <tr><td height="15px" ></td></tr>
        <tr>
            <td class="contenttab_internal_rows"  nowrap="nowrap" colspan='2'>
            <center>
            <input type="image" src="<?php echo IMG_HTTP_PATH;?>/next.png" onClick="return nextStudentDetails();" />&nbsp;
                <input type="image" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('OnlineFeeInstruction');return false;" />
                </center>
            </td>
         </tr>
    </table>
   </form>
</div>       
<?php floatingDiv_End(); ?> 
                                   

<?php  floatingDiv_Start('OnlineFeePayment','Online Fee Payment','20','','','1'); ?>
<form name="frmOnlineFeeForm" id="frmOnlineFeeForm" method="post" onsubmit="return false;">
<?php
    global $sessionHandler; 
    $StudentNameFee = $sessionHandler->getSessionVariable('StudentName');
    $RollNoFee = $sessionHandler->getSessionVariable('RollNo');
    $FatherNameFee = $sessionHandler->getSessionVariable('FatherName');
    $ClassNameFee = $sessionHandler->getSessionVariable('ClassName');
?>    
 <div id="scroll2" style="overflow:auto; width:400px; height:450px; vertical-align:top;">
    <table width="100%" border="0"  cellspacing="5px" cellpadding="5px" class="border">
        <tr><td height="2px" colspan="4" width="100%"></td></tr>
        <tr>                
           <td class="contenttab_internal_rows" width="1%" nowrap="nowrap">
             <nobr><b>Student Name</b></nobr>                
           </td>
           <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>
           <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" colspan="2">
              <nobr><span id="studentName"><?php echo $StudentNameFee; ?></span></nobr>                    
           </td>
        </tr>   
        <tr >                
          <td class="contenttab_internal_rows" width="1%" nowrap="nowrap">
             <nobr><b>Father's Name</b></nobr>                
          </td>
          <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>
          <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" >
              <nobr><span id="fatherName"><?php echo $FatherNameFee; ?></span></nobr>                    
          </td>
        </tr>
        <tr >                
           <td class="contenttab_internal_rows" width="1%" nowrap="nowrap" >
              <nobr><b>Roll No.</b></nobr>                
           </td>
           <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td> 
           <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" >
             <nobr><span id="rollNo"><?php echo $RollNoFee; ?></span></nobr>                    
           </td>
        </tr>
        <tr  >                
            <td class="contenttab_internal_rows" width="1%" nowrap="nowrap">
                <nobr><b>Class</b></nobr>                
            </td>
            <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
            <td class="contenttab_internal_rows" width="97%" nowrap="nowrap">
                <nobr><span id="className"><?php echo $ClassNameFee; ?></span></nobr>                    
            </td>
        </tr>  
        <tr id='spanPayment1' style='display:none'>                
           <td class="contenttab_internal_rows" width="100%" nowrap="nowrap" colspan="4">
             <nobr><b><font color="red">Fee Details</font></b></nobr>                
           </td>
        </tr>        
        <tr>
            <td class="contenttab_internal_rows" colspan="4" nowrap="nowrap">
               <div id="divFeeAmountResult"></div>	                                     
            </td>
        </tr> 
      </table>
      <table width="100%" border="0"  cellspacing="5px" cellpadding="5px" class="border" id='spanPayment2' style='display:none'>   
        <tr>                
           <td class="contenttab_internal_rows" width="100%" nowrap="nowrap" colspan="4">
             <nobr><b><font color="red">Holder Details</font></b></nobr>                
           </td>
        </tr>
        <tr>                
           <td class="contenttab_internal_rows" width="1%" nowrap="nowrap">
            <nobr><b>Total Payable Fee</b></nobr>                
           </td>
           <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
           <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" style="font-family:Verdana, Arial, Helvetica, sans-serif; width:250px; background:#BFBFBF; font-weight:bold; font-size:14px; color:#000000">
              <nobr><img src="<?php echo IMG_HTTP_PATH ?>/rupee-icon.gif" style="height:12px">&nbsp;
              <label id="onlineTotalFee" ></label>
              </nobr>                    
           </td>
        </tr> 
        <tr>                
           <td class="contenttab_internal_rows" width="1%" nowrap="nowrap">
            <nobr><b>Account Holder Name</b></nobr>                
           </td>
           <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
           <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" >
              <nobr><input type="text" id="onlineHolderName" class="inputbox" name="onlineHolderName"></nobr>                    
            </td>
        </tr>
        <tr>                
           <td class="contenttab_internal_rows"  width="1%" nowrap="nowrap">
              <nobr><b>Contact No.</b>    </nobr>                
           </td>
           <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
           <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" >
              <nobr><input type="text" id="onlineContactNo" name="onlineContactNo" class="inputbox" >
              </nobr>                    
           </td>
        </tr> 
        <tr>                
           <td class="contenttab_internal_rows"  width="1%" nowrap="nowrap">
              <nobr><b>Email Id</b>    </nobr>                
           </td>
           <td class="contenttab_internal_rows" width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
           <td class="contenttab_internal_rows" width="97%" nowrap="nowrap" >
              <nobr><input type="text" id="onlineEmailId" name="onlineEmailId" class="inputbox" >
              </nobr>                    
           </td>
        </tr>   
         <tr style="display:none">                
           <td class="contenttab_internal_rows" valign="top" width="1%" nowrap="nowrap">
              <nobr><b>Captcha</b></nobr>                
           </td>
           <td class="contenttab_internal_rows" valign="top"  width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
           <td class="contenttab_internal_rows"  valign="top" width="97%" nowrap="nowrap" >
              <nobr>
              <img src="<?php echo HTTP_PATH;?>/Library/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
              <input id="6_letters_code" class="inputbox" name="6_letters_code" type="text"><br>
              <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
              </nobr>                    
           </td>
        </tr>    
        <tr>
          <td height="10px" colspan="3" ></td> 
        </tr>
      </table>
      <table width="100%" border="0"  cellspacing="5px" cellpadding="5px" class="border" id='spanPayment5' style='display:none'> 
        <tr>
           <td class="contenttab_internal_rows" valign="top">
            <center><span id='spanPayment' style='display:none'>
            <input type="image" name="confirm" src="<?php echo IMG_HTTP_PATH;?>/payment.png"  onClick="addOnlineFeeStudentDetails();return false;" />&nbsp;</span>
            <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('OnlineFeePayment');return false;" />
            </center>
          </td>
        </tr>                
     </table>     
 </div>   
</form>

<?php floatingDiv_End(); ?>