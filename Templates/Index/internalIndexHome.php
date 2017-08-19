<?php
//-------------------------------------------------------
// Purpose: to design admin dashboard.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php 
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
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
		     parameters: { moduleName: moduleName
				   
				 },
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
					    <td height="5"></td>
				    </tr>
				    <tr>
					    <td valign="middle">Welcome <?php echo $sessionHandler->getSessionVariable('UserName'); ?>, </td>
                        <td valign="right" width="35%">
                         <?php
                              echo $expectedDateString;
                         ?>
                        </td>
                        <td align=right>
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
				          description: "Menu lookup helps you find menu options easily and quickly. Just enter the keyword that matches your menu option and menu lookup automatially guides you..",
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
			    </table>
		</td>
	</tr>
   </table>
 <table border="0" cellspacing="0" cellpadding="0" width="100%">    
<tr>
    <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
                <td valign="top" class="content">
                    <?php
                    if(isset($REQUEST_DATA['z'])) {
                    ?>
                    <table width='100%' class='accessDenied'>
                        <tr>
                            <td><?php echo ACCESS_DENIED;?></td>
                        </tr>
                    </table><br>
                    <?php } 
                    
                    ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td class="contenttab_border" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                                    <tr>
                                    <td valign="middle" width="50%" class="content_title">Dashboard: </td>
                                    <td valign="middle" align="right" class="content_title">
                                    <?php 
                                     if ($unApprovedRecordArray[0]['totalRecords']){ //for showing unapproved fine request
                                    ?>     
                                    <a href='fineReport.php?status=2'><img src="<?php echo IMG_HTTP_PATH ?>/blink.gif" border="0" title="UnApproved Fine">&nbsp;</a>
                                    <a href='fineReport.php?status=2' style="color:#FFFFFF" title="UnApproved Fine">You have <?php echo $unApprovedRecordArray[0]['totalRecords']?> unapproved fine requests</a>
                                    <?php
                                      }
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
                        <?php
                        global $sessionHandler;
                        if($sessionHandler->getSessionVariable('RoleType')=='11'){
                        ?>
                        <tr>
                            <td class="contenttab_row" valign="top" >
                                <div id="div_Outer">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="400">
                                        <tr>
                                            <td class="padding_top" align="center"><b><?php echo $greetingMsg;  ?></b></td>
                                        </tr>
                                        <tr>
                                            <td valign="top"  align="left" >
                                            <table width="960" border="0" align="center">
                                            <tr>
                                                <td valign="top">
                                                <table cellspacing="0" cellpadding="3" border="0">
                                                <tr>
                                                    <td valign="top">
                                                        <?php
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->tableBlueHeader('City Wise','width=315' ,'height=234','align=center');
                                                            echo UtilityManager::includeJS("swfobject.js");
                                                            $flashPath = IMG_HTTP_PATH."/ampie.swf";
                                                        ?>
                                                        <table width="100%"  height="200" border="0">
                                                        <tr>
                                                            <td valign="top">
                                                            <table width="100%" border="0">
                                                            <tr>
                                                                <td valign="top">
                                                                <div id="flashcontent4">
                                                                    <strong>You need to upgrade your Flash Player</strong>
                                                                </div>
                                                                <script type="text/javascript">
                                                                  x = Math.random() * Math.random();
                                                                  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
                                                                  so.addVariable("path", "ampie/");  
                                                                  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
                                                                  so.addParam("wmode", "transparent");
                                                                  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                                                                  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                                                                  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryCityData.xml?t="+x));
                                                                  so.addVariable("preloader_color", "#999999");
                                                                  so.write("flashcontent4");
                                                                </script>
                                                                </td>
                                                            </tr>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                        </table>
                                                        <?php 
                                                            echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                            //floatingDiv_Close(); 
                                                            //*************End of Div*********
                                                        ?>
                                                    </td>
                                                </tr>
                                                </table>
                                                </td>
                                                <td valign="top">
                                                <table cellspacing="0" cellpadding="3" border="0">
                                                <tr>
                                                    <td valign="top">
                                                        <?php
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->tableBlueHeader('State Wise','width=315' ,'height=234','align=center');
                                                            echo UtilityManager::includeJS("swfobject.js");
                                                            $flashPath = IMG_HTTP_PATH."/ampie.swf";
                                                        ?>
                                                        <table width="100%"  height="200" border="0">
                                                        <tr>
                                                            <td valign="top">
                                                            <table width="100%" border="0">
                                                            <tr>
                                                                <td valign="top">
                                                                <div id="flashcontent5">
                                                                    <strong>You need to upgrade your Flash Player</strong>
                                                                </div>
                                                                <script type="text/javascript">
                                                                  x = Math.random() * Math.random();
                                                                  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
                                                                  so.addVariable("path", "ampie/");  
                                                                  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
                                                                  so.addParam("wmode", "transparent");
                                                                  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                                                                  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                                                                  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryStateData.xml?t="+x));
                                                                  so.addVariable("preloader_color", "#999999");
                                                                  so.write("flashcontent5");
                                                                </script>
                                                                </td>
                                                            </tr>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                        </table>
                                                        <?php 
                                                            echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                            //floatingDiv_Close(); 
                                                            //*************End of Div*********
                                                        ?>
                                                    </td>
                                                </tr>
                                                </table>
                                                </td>
                                                <td valign="top">
                                                <table cellspacing="0" cellpadding="3" border="0">
                                                <tr>
                                                    <td valign="top">
                                                        <?php
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->tableBlueHeader('Programme Wise','width=315' ,'height=234','align=center');
                                                            echo UtilityManager::includeJS("swfobject.js");
                                                            $flashPath = IMG_HTTP_PATH."/ampie.swf";
                                                        ?>
                                                        <table width="100%"  height="200" border="0">
                                                        <tr>
                                                            <td valign="top">
                                                            <table width="100%" border="0">
                                                            <tr>
                                                                <td valign="top">
                                                                <div id="flashcontent6">
                                                                    <strong>You need to upgrade your Flash Player</strong>
                                                                </div>
                                                                <script type="text/javascript">
                                                                  x = Math.random() * Math.random();
                                                                  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "190", "8", "#FFFFFF");
                                                                  so.addVariable("path", "ampie/");  
                                                                  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
                                                                  so.addParam("wmode", "transparent");
                                                                  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                                                                  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                                                                  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentEnquiryDegreeData.xml?t="+x));
                                                                  so.addVariable("preloader_color", "#999999");
                                                                  so.write("flashcontent6");
                                                                </script>
                                                                </td>
                                                            </tr>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                        </table>
                                                        <?php 
                                                            echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                            //floatingDiv_Close(); 
                                                            //*************End of Div*********
                                                        ?>
                                                    </td>
                                                </tr>
                                                </table>
                                                </td>
                                            </tr>

                                            </table>
                                        </td>
                                    </tr>
                         
                        <?php
                        }
                        else{  
                        ?>
                        <tr>
                            <td class="contenttab_row" valign="top" >
                                <div id="div_Outer">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="400" style="padding-top:2px">
                                    <?php if($greetingMsg!='')
                                      {
                                      ?>
                                      <tr><td class="padding_top" align="center"><b><?php echo $greetingMsg; ?></b> </td></tr>
                                      <?php 
                                      }
                                    ?> 
                                        <tr>
                                        <?php
                                        //echo $dashboardFrameArray[0]['frameName'];
                                        if(($dashboardFrameArray[0]['frameName']=='Notice')  && $sessionHandler->getSessionVariable('Notice')!=''){ 
                                        ?>

                                            <td valign="top"  align="left" >
                                                <table width="960" border="0" align="center" cellspacing="0" cellpadding="0">
                                                <tr>
                                                <td valign="top" style="padding-left:2px;"> 
                                                <table cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                    <td valign="top" >
                                                            <?php
                                                                //*************Used For Creating*********
                                                                //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                echo HtmlFunctions::getInstance()->tableBlueHeader('Notices','width=326' ,'height=460','align=center');
                                                            ?>                     
                                                            <table width="100%" border="0" height="432">
                                                                <tr>
                                                                    <td valign="top">
                                                                        <div id="divNoticeCountList">                                                                      
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
                                                                                        

$downloadCount = ' ('.$noticeRecordArray[$i]['downloadCount'].')';
                                                                                        $title="From : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleFromDate']))." To : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1); 
                                                                                        echo '<tr class="'.$bg.'">
                                                                                            <td valign="top" class="padding_top" align="left" height="10">&bull;&nbsp;&nbsp;

											    <a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);return false;" title="'.$title.'" ><span style="color:#'.$noticeRecordArray[$i]['colorCode'].'">'.strip_tags(trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),35)).$downloadCount.'- <I>'.$noticeRecordArray[$i]['abbr'].'</I></span>';

										
                                                                                         /*   <a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);return false;" title="'.$title.'" ><span style="color:#'.$noticeRecordArray[$i]['colorCode'].'">'.strip_tags(trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),35)).'.
											
											    <a href="" name="bubble" onclick="showNoticeCount('.$noticeRecordArray[$i]['noticeId'].',\'countNotice\',650,350);return false;" title="'.$title.'" ><span style="color:#'.$noticeRecordArray[$i]['colorCode'].'"> '.$downloadCount.'- <I>'.$noticeRecordArray[$i]['abbr'].'</I></span>';*/
        
                                                                                    
                                                                                         //echo '&nbsp;<img src="'.IMG_HTTP_PATH.'/new1.gif" title="" alt="">'; 
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
                                                                                         if($currentDate <= $dt1 && $tdays!=0) {
                                                                                             
                                                                       echo '&nbsp;<img src="'.$visibleImageName.'">';
                                                                                         }                            
                                                                                         echo '</a>&nbsp;&nbsp;';
                                                                                         ?>
   
                                                                                         <?php
                                                                                         echo '</td>';
                                                                                        $fileName = IMG_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
                                                                                        if(file_exists($fileName) && ($noticeRecordArray[$i]['noticeAttachment']!='')){

                                                                                        $fileName1 = IMG_HTTP_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
                                                                                            echo '<td valign="top" align="right"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
                                                                                        }
                                                                                        echo '</tr>';
                                                                                    }
                                                                                    //if($noticeRecordCount>16){
                                                                                        echo '<tr><td colspan="2" align="right"><a href="displayAllNotices.php"><u>More</u></a>&raquo;</td></tr>'; 
                                                                                    //}
                                                                                }
                                                                                else {
                                                                                    echo '<tr><td colspan="2" align="center" valign="middle" height="135">No Notice</td></tr>';
                                                                                }
                                                                            ?>
                                                                        </table>
</div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <?php 
                                                                echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                                //floatingDiv_Close(); 
                                                                //*************End of Div*********
                                                            ?>
                                                             
                                                </td>
                                                </tr>
                                                </table>
                                                </td>
                                                <?php
                                                    }
                                                ?>
                                                <td valign="top">
                                                <table cellspacing="0" cellpadding="0" border="0" width='100%'>
                                                <tr>
                                                <?php
                                                global $sessionHandler;
                                                 $recordCount1 = $dashboardFrameCount[0]['totalRecords'];
                                                if($recordCount1 >0 && is_array($dashboardFrameArray) ) {

                                                    $count=0;
                                                    for($k=0; $k<$recordCount1; $k++ ) {
                                                        
                                                            
                                                                if(($dashboardFrameArray[$k]['frameName']=='Events') &&  $sessionHandler->getSessionVariable('Events')!='') {
                                                                    $count++;
                                                            ?>
                                                            <td valign="top" style="padding-left:4px;">
                                                            <?php
                                                                //*************Used For Creating*********
                                                                //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                echo HtmlFunctions::getInstance()->tableBlueHeader('Events','width=326' ,'height=234','align=center');
                                                            ?>
                                                            <table width="100%"  height="180" border="0">
                                                                <tr>
                                                                    <td valign="top">
                                                                        <table width="100%" border="0">
                                                                            <?php
                                                                                $recordCount = count($eventRecordArray);
                                                                                if($recordCount >0 && is_array($eventRecordArray) ) { 

                                                                                    for($i=0; $i<$recordCount; $i++ ) {

                                                                                        //$bg = $bg =='row0' ? 'row1' : 'row0';
                                                                                        $title="From : ".strip_slashes(UtilityManager::formatDate($eventRecordArray[$i]['startDate']))." To : ".strip_slashes(UtilityManager::formatDate($eventRecordArray[$i]['endDate']))."     ".trim_output(strip_slashes($eventRecordArray[$i]['shortDescription']),100,1);   
                                                                                        echo '<tr class="'.$bg.'">
                                                                                        <td valign="top" class="padding_top" align="left" height="10">&bull;&nbsp;&nbsp;<a href="" name="bubble" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].',\'divEvent\',650,150);return false;" title="'.$title.'" >'.strip_tags(trim_output(strip_slashes($eventRecordArray[$i]['eventTitle']),25)).'</a></td>
                                                                                        </tr>';
                                                                                    }
                                                                                    //if($eventRecordCount>5) {

                                                                                        echo '<tr><td colspan="2" align="right" valign="bottom"><a href="listCalendar.php"><u>More</u></a>&raquo;</td></tr>'; 
                                                                                    //}
                                                                                }
                                                                                else {
                                                                                    echo '<tr><td colspan="2" align="center" valign="middle" height="135">No Events</td></tr>';
                                                                                }
                                                                            ?>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <?php 
                                                                echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                                //floatingDiv_Close(); 
                                                                //*************End of Div*********
                                                            ?>
                                                            </td>
                                                            <?php
                                                            }    
                                                            if((($dashboardFrameArray[$k]['frameName']=='LoginActivities') &&  $sessionHandler->getSessionVariable('LoginActivities')!='') ){
                                                                $count++;
                                                            ?>
                                                                <td scope="col" valign="top" align="left"  style="padding-left:4px;padding-right:2px">
                                                                <?php
                                                                    //*************Used For Creating*********                        //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                    echo HtmlFunctions::getInstance()->tableBlueHeader('Login Activities','width=326' ,'height=190','align=center');
                                                                    echo UtilityManager::includeJS("swfobject.js");
                                                                    $flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
                                                                ?>                      
                                                                <table width="100%" height="180" border="0">
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <div id="flashcontent">
                                                                                <strong>You need to upgrade your Flash Player</strong>
                                                                            </div>
                                                                            <script type="text/javascript">
                                                                                var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "290", "192", "8", "#FFFFFF");
                                                                                so.addVariable("path", "./");  
                                                                                so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
                                                                                x = Math.random() * Math.random();
                                                                                so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>120</y><rotate>true</rotate><text>Number Of Users ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>165</y><text>Date---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>8</frequency></category></values></settings>");
                                                                                so.addParam("wmode", "transparent");
                                                                                so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting.xml"));
                                                                                so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeActivityBarData.xml?t="+x));
                                                                                so.addVariable("preloader_color", "#999999");
                                                                                so.write("flashcontent");
                                                                            </script>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <?php 
                                                                    echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                                    //floatingDiv_Close(); 
                                                                    //*************End of Div*********
                                                                ?>
                                                            </td>
                                                            <?php
                                                            }
                                    
                                                            
                                                            if(($dashboardFrameArray[$k]['frameName']=='AverageAttendance') &&  $sessionHandler->getSessionVariable('AverageAttendance')!=''){
                                                                $count++;
                                                            ?>
                                                            <td scope="col" valign="top" align="left"  style="padding-left:4px;">
                                                            <?php
                                                                //*************Used For Creating*********
                                                                //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                echo HtmlFunctions::getInstance()->tableBlueHeader('Attendance Threshold','width=326' ,'height=170','align=center');
                                                                echo UtilityManager::includeJS("swfobject.js");
                                                                $flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
                                                            ?>      
                                                                <form name="attendanceForm">
                                                                <table width="100%" height="194" border="0">
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <select size="1" class="inputbox1" name="classId" id="classId" onChange="showGraph()">
                                                                                <option selected="selected" value="0">Select Class</option>
                                                                                <?php
                                                                                //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                                echo $returnValues1;
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" height="167">
                                                                            <div id="resultsDiv"></div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                </form>
                                                                <?php 
                                                                    echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                                    //floatingDiv_Close(); 
                                                                    //*************End of Div*********
                                                                ?>
                                                                </td>
                                                                <?php
                                                                }
                                                                if(($dashboardFrameArray[$k]['frameName']=='TestTypeDetail') &&  $sessionHandler->getSessionVariable('TestTypeDetail')!=''){
                                                                    $count++;
                                                                ?>
                                                                <td scope="col" valign="top" align="left"  style="padding-left:4px">
                                                                <?php
                                                                //*************Used For Creating*********
                                                                //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                echo HtmlFunctions::getInstance()->tableBlueHeader('Test Type Distribution','width=326' ,'height=180','align=center');
                                                                echo UtilityManager::includeJS("swfobject.js");
                                                                $flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
                                                                ?>   
                                                                <div id="scroll" style="OVERFLOW: auto; HEIGHT:198px;width:300px; TEXT-ALIGN: justify;">
                                                                <form name="testForm">
                                                                <table width="100%" border="0">
                                                                    <tr>
                                                                        <td valign="top">
                                                                            <select size="1" class="inputbox1" name="classId1" id="classId1" onChange="showTestGraph()">
                                                                                <option  selected="selected" value="0">Select Class</option>
                                                                                <?php
                                                                                    echo $returnValues1;
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" height="167"><div id="resultsDiv1"></div></td>
                                                                    </tr>
                                                                </table>
                                                                </form>
                                                                </div>
                                                                <?php 
                                                                    echo HtmlFunctions::getInstance()->tableBlueFooter();
                                                                    //floatingDiv_Close(); 
                                                                    //*************End of Div*********
                                                                ?>  
                                                                </td> 
                                                                <?php
                                                                }
                                                                     
                                                                if(($dashboardFrameArray[0]['frameName']=='Notice')  && $sessionHandler->getSessionVariable('Notice')!=''){
                                                                    
                                                                    if(($count%2)==0)
                                                                        echo "</tr><tr>";
                                                                }else{
                                                                
                                                                    if(($count%3)==0)
                                                                        echo "</tr><tr>";
                                                                }
                                                            }
                                                        }
                                                        if($count==0) 
                                                            echo "<tr><td align='center' colspan='6'><center><b>".WELCOME_ADMIN."</b></center></td></tr>";
                                                        ?>
                                                        </table>
                                                    <?php 
                                                    floatingDiv_Close(); 
                                                    //*************End of Div*********
                                                    ?>
                                                </td>
                                                </tr>
                                                </table>
                                                </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <?php 
                        }
                    ?>
    
    </table>



<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice Description','',' '); ?>
<form name="NoticeForm" action="" method="post">  
<table width="500" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<td height="5px"></td></tr>
<tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Subject: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px" colspan="2" height='20'><div id="noticeSubject"></div></td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Department: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeDepartment" style="width:430px; height:20px"></div></td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Date: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td valign="middle" colspan="2" height='20'>&nbsp;<B>From</B>: <span id="visibleFromDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="visibleToDate" style="height:20px"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Description: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="noticeText" style="overflow:auto; width:530px; height:200px" ></div></td>
</tr> 
 
<tr>
<td height="5px"></td>
</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<!--Start Notice  Div-->
<?php floatingDiv_Start('countNotice',' Attendance History ','',' '); ?>
<form name="NoticeForm" action="" method="post">  
<table  border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<td height="5px"></td></tr>
<tr>

<tr>
	<td valign="middle"  class="rowheading" width="20px">&nbsp;<nobr><b># &nbsp;</b></nobr></td>
	<td valign="middle"  class="rowheading" width="150px">&nbsp;<nobr><b>User Name &nbsp;</b></nobr></td>
	<td valign="middle"  class="rowheading" width="200px">&nbsp;<nobr><b>Name &nbsp;</b></nobr></td>
	<td valign="middle"  class="rowheading" width="125px">&nbsp;<nobr><b>Role &nbsp;</b></nobr></td>
	<td valign="middle"  class="rowheading" width="150px">&nbsp;<nobr><b>Date &nbsp;</b></nobr></td>
</tr>

<tr>
<td height="5px"></td>
</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event Description','',' '); ?>
<form name="EventForm" action="" method="post">  
<table width="500" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
<tr>
	<td height="5px"></td></tr>
<tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Event: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:3px" colspan="2"><div id="eventTitle" ></div></td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Date: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td valign="middle" colspan="2">&nbsp;<B>From</B>: <span id="startDate" style="height:20px"></span>&nbsp;&nbsp;<B>To</B>: <span id="endDate" style="height:20px"></span></td>
</tr>
<tr>
    <td height="5px"></td>
</tr> 
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Short Description: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td valign="middle" colspan="2" style="padding-left:3px" ><div id="shortDescription"></div></td>
</tr>
<tr>
	<td valign="middle" colspan="2" class="rowheading">&nbsp;<nobr><b>Long Description: &nbsp;</b></nobr></td>
</tr>
<tr>
	<td valign="middle" colspan="2" style="padding-left:3px" >
     <div id="longDescription" style="overflow:auto; width:530px;height:100px" ></div>
    </td>
</tr> 
 
<tr>
<td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<?php 
// $History: internalIndexHome.php $
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 2/24/10    Time: 5:56p
//Updated in $/LeapCC/Templates/Index
//fixed query error
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 2/19/10    Time: 3:10p
//Updated in $/LeapCC/Templates/Index
//Show a graph showing count of students who are falling short of
//attendance in any subject. On clicking the bar, the attendance details
//of the particular student or all students are shown.
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 10-02-17   Time: 12:18p
//Updated in $/LeapCC/Templates/Index
//removed notice editor issue
//
//*****************  Version 21  *****************
//User: Parveen      Date: 1/29/10    Time: 2:54p
//Updated in $/LeapCC/Templates/Index
//integer date check added (notices tdays)
//
//*****************  Version 20  *****************
//User: Parveen      Date: 1/29/10    Time: 11:46a
//Updated in $/LeapCC/Templates/Index
//integer field check add (days)
//
//*****************  Version 19  *****************
//User: Parveen      Date: 1/28/10    Time: 12:41p
//Updated in $/LeapCC/Templates/Index
//new flash image code updated
//
//*****************  Version 17  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 3:54p
//Updated in $/LeapCC/Templates/Index
//
//*****************  Version 16  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 11:16a
//Updated in $/LeapCC/Templates/Index
//admin dashboard improvements
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 8/27/09    Time: 1:11p
//Updated in $/LeapCC/Templates/Index
//Gurkeerat: resollved issue regarding issues 1226,1227
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/20/09    Time: 5:38p
//Updated in $/LeapCC/Templates/Index
//Gurkeerat: fixed issue 947
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 8/05/09    Time: 3:15p
//Updated in $/LeapCC/Templates/Index
//fixed 0000896,0000897,0000898,0000899,0000900
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 10/07/09   Time: 12:02
//Updated in $/LeapCC/Templates/Index
//corrected image size in "unapproved fine alert" display
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/07/09    Time: 10:28
//Updated in $/LeapCC/Templates/Index


//Changed "blinking" image
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 6/07/09    Time: 18:29
//Updated in $/LeapCC/Templates/Index
//Added "UnApproved Fine Display" in admin's dashboard
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Templates/Index
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 6/02/09    Time: 6:15p
//Updated in $/LeapCC/Templates/Index
//Updated with "Pre admission" dashboard and print report
//
//*****************  Version 7  *****************
//User: Parveen      Date: 5/28/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Index
//Display Last Login in dashBoard
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/26/09    Time: 1:21p
//Updated in $/LeapCC/Templates/Index
//Updated test type distribution graph format with scroll div
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/26/09    Time: 11:47a
//Updated in $/LeapCC/Templates/Index
//table tags remove 
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/19/09    Time: 5:56p
//Updated in $/LeapCC/Templates/Index
//Updated Admin dashboard with role permission, test type and average
//attendance
//
//*****************  Version 36  *****************
//User: Rajeev       Date: 5/07/09    Time: 11:44a
//Updated in $/Leap/Source/Templates/ScIndex
//Increased event title displaying width so that title can be seen
//properly if width increases
//
//*****************  Version 35  *****************
//User: Dipanjan     Date: 5/05/09    Time: 18:19
//Updated in $/Leap/Source/Templates/ScIndex
//Updated model and template files to show notices according to issuing
//department colors for admin,student,parent and management level users
//
//*****************  Version 34  *****************
//User: Rajeev       Date: 4/17/09    Time: 7:14p
//Updated in $/Leap/Source/Templates/ScIndex
//Updated with test type report on dashboard
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 3/07/09    Time: 6:04p
//Updated in $/Leap/Source/Templates/ScIndex
//Updated admin dashboard layout
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 2/17/09    Time: 4:56p
//Updated in $/Leap/Source/Templates/ScIndex
//Made default show of graphs
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Templates/ScIndex
//use student, dashboard, sms, email icons
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 1/06/09    Time: 6:32p
//Updated in $/Leap/Source/Templates/ScIndex
//reduced  the height of dashboard frame
//
//*****************  Version 29  *****************
//User: Rajeev       Date: 12/30/08   Time: 4:39p
//Updated in $/Leap/Source/Templates/ScIndex
//added dashboard permission module
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 12/26/08   Time: 12:30p
//Updated in $/Leap/Source/Templates/ScIndex
//Added Advertisement feature on admin dashboard
//
//*****************  Version 27  *****************
//User: Ajinder      Date: 12/26/08   Time: 12:22p
//Updated in $/Leap/Source/Templates/ScIndex
//DONE THE CODE FORMATTING...RELEASED FOR RAJEEV.
//
//*****************  Version 26  *****************
//User: Rajeev       Date: 12/09/08   Time: 11:13a
//Updated in $/Leap/Source/Templates/ScIndex
//updated notice with department
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 11/19/08   Time: 6:12p
//Updated in $/Leap/Source/Templates/ScIndex
//updated trim output function
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 11/19/08   Time: 10:58a
//Updated in $/Leap/Source/Templates/ScIndex
//added test type graph
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 11/17/08   Time: 6:06p
//Updated in $/Leap/Source/Templates/ScIndex
//updated dashboard with Average attendance class wise
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 11/15/08   Time: 7:08p
//Updated in $/Leap/Source/Templates/ScIndex
//changed flash graph time limit
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 11/14/08   Time: 3:12p
//Updated in $/Leap/Source/Templates/ScIndex
//updated with login activity report
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:40p
//Updated in $/Leap/Source/Templates/ScIndex
//added average marks and average attendance graphs
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 11/11/08   Time: 1:01p
//Updated in $/Leap/Source/Templates/ScIndex
//dashboard XML path updated
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 11/07/08   Time: 4:00p
//Updated in $/Leap/Source/Templates/ScIndex
//added dashboard and other pie reports on student demographics
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 11/06/08   Time: 5:37p
//Updated in $/Leap/Source/Templates/ScIndex
//updated student dashboard with Bar graph of user activities
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 10/24/08   Time: 3:36p
//Updated in $/Leap/Source/Templates/ScIndex
//updated last attendance taken on query
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 10/17/08   Time: 5:00p
//Updated in $/Leap/Source/Templates/ScIndex
//updated div popup
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 10/08/08   Time: 5:24p
//Updated in $/Leap/Source/Templates/ScIndex
//added code for not viewable area
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 10/08/08   Time: 1:09p
//Updated in $/Leap/Source/Templates/ScIndex
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 10/08/08   Time: 12:52p
//Updated in $/Leap/Source/Templates/ScIndex
//updated the right align to notice download
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10/07/08   Time: 10:10a
//Updated in $/Leap/Source/Templates/ScIndex
//added download notice functionality
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10/06/08   Time: 4:45p
//Updated in $/Leap/Source/Templates/ScIndex
//updated with download attachment
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10/03/08   Time: 10:54a
//Updated in $/Leap/Source/Templates/ScIndex
//updated the event div with from date and to date on top
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 9/29/08    Time: 6:49p
//Updated in $/Leap/Source/Templates/ScIndex
//updated for div functionality
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/26/08    Time: 9:53a
//Updated in $/Leap/Source/Templates/ScIndex
//changed the date format for bubble
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/24/08    Time: 1:24p
//Updated in $/Leap/Source/Templates/ScIndex
//updated events list with "EVENT_DAY_PRIOR" parameter
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/23/08    Time: 10:47a
//Updated in $/Leap/Source/Templates/ScIndex
//changed the display message
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/22/08    Time: 6:26p
//Updated in $/Leap/Source/Templates/ScIndex
//updated attendance missed link
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/20/08    Time: 8:52p
//Updated in $/Leap/Source/Templates/ScIndex
//removed "group" from attendance not taken list
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/08/08    Time: 12:39p
//Created in $/Leap/Source/Templates/ScIndex
//intial checkin
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/06/08    Time: 5:04p
//Updated in $/Leap/Source/Templates/Index
//updated dashboard
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/04/08    Time: 12:57p
//Updated in $/Leap/Source/Templates/Index
//updated the formatting and made floating div for event description
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/01/08    Time: 3:31p
//Updated in $/Leap/Source/Templates/Index
//updated with fees dues on dashboard
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/28/08    Time: 11:45a
//Updated in $/Leap/Source/Templates/Index
//updated with issues sent by QA of build 24
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/25/08    Time: 6:33p
//Updated in $/Leap/Source/Templates/Index
//updated message
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/21/08    Time: 11:21a
//Updated in $/Leap/Source/Templates/Index
//updated more notice link
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:35p
//Updated in $/Leap/Source/Templates/Index
//updated formatting
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/16/08    Time: 5:41p
//Updated in $/Leap/Source/Templates/Index
//updated with new blue theme
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:44p
//Updated in $/Leap/Source/Templates/Index
//added bubble script
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Index
//updated links
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:09p
//Updated in $/Leap/Source/Templates/Index
//updated dashboard
?>
