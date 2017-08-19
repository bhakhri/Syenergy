<?php
//-------------------------------------------------------
// Purpose: to design admin dashboard.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <!--tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Welcome <?php echo ucwords($sessionHandler->getSessionVariable('UserName')); ?>,</td>
            </tr>
            </table>
        </td>
    </tr-->
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <?php
             if(isset($REQUEST_DATA['z'])) {
             ?>
             <table width='100%' class='accessDenied'><tr><td><?php echo ACCESS_DENIED;?></td></tr></table><br>
             <?php } ?>
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                        <td valign="middle" width="50%" class="content_title">Dashboard: </td>
                        <td valign="middle" align="right" class="content_title">
                            <?php
                              $condition='';
                              $condition = " WHERE userId='".$sessionHandler->getSessionVariable('UserId')."'";
                              $totalRecord = CommonQueryManager::getInstance()->getUserLastLogin($condition);
                              if($totalRecord[0]['dateTimeIn']!='') {
                                echo "Last Login:&nbsp;".UtilityManager::formatDate($totalRecord[0]['dateTimeIn'],true);
                              }
                            ?>
                        &nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="div_Outer">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:2px">
                <tr>
                    <td class="padding_top" align="center"><b><?php echo $greetingMsg;  ?></b> </td></tr>
                <tr>
                <td valign="top"  align="left" style="padding-left:2px">
                   <!-- Start Table -->
                   <table width="100%" border="0" cellpadding="0" cellspacing="2px" >
                      <tr>
                        <td height="163" scope="col" valign="top" align="center" style="padding-left:2px">
                            <?php
                               //*************Used For Creating*********
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->tableBlueHeader('Institute Data','width=330' ,'height=220','align=center');
                            ?>
                            <table width="100%" height="220" border="0">
                            <tr>
                                <td valign="top">
                                <table width="100%" border="0">
                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="listManagementNotice.php"><u>Total Notices</u></a></td>
                                    <td valign="top" class="padding_top" align="right">
                                    <a href="listManagementNotice.php"><u><?php echo count($getTotalAllNoticeArr);?></u></a></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="listManagementEvents.php"><u>Total Events</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="listManagementEvents.php"><u><?php echo $getTotalAllEventArr[0]['totalRecords']?></u></a></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="allDetailsReport.php"><u>Total Students</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="allDetailsReport.php"><u><?php echo $getTotalStudentArr[0]['totalRecords']?></u></a></td>
                                </tr>

                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="allEmployeeDetailsReport.php"><u>Total Teaching Employee</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="allEmployeeDetailsReport.php"><u><?php echo $getTotalEmployeeArr[0]['totalRecords']?></u></a></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="allEmployeeDetailsReport.php"><u>Total Non-Teaching Employee</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="allEmployeeDetailsReport.php"><u><?php echo $getTotalNonEmployeeArr[0]['totalRecords']?></u></a></td>
                                </tr>

                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="listManagementDegree.php"><u>Total Degrees</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="listManagementDegree.php"><u><?php echo $getTotalDegreeArr[0]['totalRecords']?></u></a></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="listManagementBranches.php"><u>Total Branches</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="listManagementBranches.php"><u><?php echo $getTotalBranchArr[0]['totalRecords']?></u></a></td>
                                </tr>
                                <tr>
                                    <td valign="top" class="padding_top" width="5px">&bull;&nbsp;&nbsp;</td>
                                    <td valign="top" class="padding_top"><a href="listCollectedFees.php"><u>Total Fees Collected</u></a></td>
                                    <td valign="top" class="padding_top" align="right"><a href="listCollectedFees.php"><u><?php echo number_format($getTotalFeesArr[0]['totalAmount'], 2, '.', '');?></u></a></td>
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
                        <td height="163" scope="col" valign="top" align="center" style="padding-left:2px">
                            <?php
                                //*************Used For Creating*********
                                //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->tableBlueHeader('Notices','width=330' ,'height=220','align=center');
                            ?>
                            <table width="100%" height="220" border="0">
                            <tr>
                            <td valign="top" >
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

                                            $title="From : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleFromDate']))." To : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1);
                                            echo '<tr class="'.$bg.'">
                                                <td valign="top" class="padding_top" align="left" height="10">&bull;&nbsp;&nbsp;<a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);return false;" title="'.$title.'" >'.strip_tags(trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),35)).' - <I>'.$noticeRecordArray[$i]['abbr'].'</I>';
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
                                             echo '</a></td>';
                                            $fileName = IMG_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
                                            if(file_exists($fileName) && ($noticeRecordArray[$i]['noticeAttachment']!='')){

                                                $fileName1 = IMG_HTTP_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
                                                echo '<td valign="top" align="right"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
                                            }
                                            echo '</tr>';
                                        }
                                        //if($noticeRecordCount>5){
                                            echo '<tr><td colspan="2" align="right"><a href="listManagementNotice.php"><u>More</u></a>&raquo;</td></tr>';
                                        //}
                                    }
                                    else {
                                        echo '<tr><td colspan="2" align="center" valign="middle" height="135">No Notice</td></tr>';
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
                        <td scope="col" valign="top" align="center" style="padding-left:2px" rowspan="2" >
                             <?php
                                 $limit=5; //numver of rows to be displayed
                                 //*************Used For Creating*********
                                 //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                 echo HtmlFunctions::getInstance()->tableBlueHeader('Analysis','width=305' ,'height=450','align=center');
                                ?>
                                <div id="scroll2" style="overflow:auto; height:484px; vertical-align:top;">
                                    <div id="finalResultDiv" style="width:98%; vertical-align:top;">
                                        <!-- <div style='overflow:auto; height:484px;'> -->
                                        <table width="100%" style="height:484px;" border="0">
                                        <tr>
                                         <td width="100%" align="left" style="padding-left:0px;padding-top:2px;" border="0" valign="top" height="100%">
                                            <table width="100%"  border="0" cellspacing="0" cellpadding="0" height="100%" align="left">
                                                <tr>
                                                    <td valign='top' align='left'>
                                                       <table width="100%" border="0" cellspacing="0px" cellpadding="2px">
                                                        <tr>
                                                            <td valign="top" align="left" style="padding-right:8px;" class="padding_top" >
                                                               <nobr>
                                                                <!-- &bull;&nbsp;&nbsp; -->
                                                                <img id="question1" src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="getShowDetail(1); return false;" />
                                                               </nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top"><a href="listAttendanceThreshold.php?mode=a&val=0"><u><nobr>
                                                            Attendance Below (<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'); ?>%) </u></a></nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top" align="right"><nobr>
                                                            <a href="listAttendanceThreshold.php?mode=a&val=0"><u><?php echo $totalStudentCountBelowThreshold;?> Students</u></a><br>
                                                            </nobr>
                                                            </td>
                                                        </tr>
                                                         <tr id='answer1' style='display:none;'>
                                                            <td valign="top" class="padding_top" colspan="3">
                                                                <?php
                                                                       if($totalStudentCountBelowThreshold > 0) {
                                                                         echo $strAttendanceThreshold;
                                                                       }
                                                                    ?>
                                                             </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="left" style="padding-right:8px;" class="padding_top" >
                                                                <nobr>&nbsp;&bull;&nbsp;&nbsp;</nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top"><a href="listManagementExamStatistics.php"><u><nobr>
                                                            Exam Statistics </u></a></nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top" align="right"><nobr>
                                                            <a href="listManagementExamStatistics.php"><u><?php echo $examStatistics;?> Tests</u></a>
                                                            </nobr>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="left" style="padding-right:8px;" class="padding_top" >
                                                                <nobr>
                                                                <!-- &bull;&nbsp;&nbsp; -->
                                                                <img id="question2" src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="getShowDetail(2); return false;" />
                                                               </nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top" colspan="2"><a href="listAttendanceThreshold.php?mode=t&val=0"><u><nobr>
                                                            Toppers</u></a></nobr>
                                                            </td>
                                                        </tr>
                                                         <tr id='answer2' style='display:none;'>
                                                            <td valign="top" class="padding_top" colspan="3">
                                                                <?php
                                                                       if($strClassTopper != '') {
                                                                         echo $strClassTopper;
                                                                       }
                                                                    ?>
                                                             </td>
                                                        </tr>
                                                         <tr>
                                                            <td valign="top" align="left" style="padding-right:8px;" class="padding_top" >
                                                                <nobr>
                                                                <!-- &bull;&nbsp;&nbsp; -->
                                                                <img id="question3" src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="getShowDetail(3); return false;" />
                                                               </nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top"><a href="listAttendanceThreshold.php?mode=b&val=0"><u><nobr>
                                                            Below Average (<?php echo $sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE'); ?>%)</u></a></nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top" align="right"><nobr>
                                                            <a href="listAttendanceThreshold.php?mode=b&val=0"><u><?php echo $strBelowAvg;?> Students</u></a>
                                                            </nobr>
                                                            </td>
                                                        </tr>
                                                         <tr id='answer3' style='display:none;'>
                                                            <td valign="top" class="padding_top" colspan="3">
                                                                <?php
                                                                       if($strBelowAvg > 0) {
                                                                         echo $strClassBelowAvg;
                                                                       }
                                                                    ?>
                                                             </td>
                                                        </tr>
                                                         <tr>
                                                            <td valign="top" align="left" style="padding-right:8px;" class="padding_top" >
                                                                <nobr>
                                                                <!-- &bull;&nbsp;&nbsp; -->
                                                                <img id="question4" src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="getShowDetail(4); return false;" />
                                                               </nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top"><a href="listAttendanceThreshold.php?mode=av&val=0"><u><nobr>
                                                            Above Average (<?php echo $sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE'); ?>%)</u></a></nobr>
                                                            </td>
                                                            <td valign="top" class="padding_top" align="right"><nobr>
                                                            <a href="listAttendanceThreshold.php?mode=av&val=0"><u><?php echo $strAboveAvg;?> Students</u></a>
                                                            </nobr>
                                                            </td>
                                                        </tr>
                                                         <tr id='answer4' style='display:none;'>
                                                            <td valign="top" class="padding_top" colspan="3">
                                                                <?php
                                                                       if($strAboveAvg > 0) {
                                                                         echo $strClassAboveAvg;
                                                                       }
                                                                    ?>
                                                             </td>
                                                        </tr>
                                                       </table>
                                                       <!--
                                                       <ul class='myUL'><li><u><b>Attendance Below Threshold (<?php // echo $totalStudentCountBelowThreshold;?> Students)</b></u></li></ul>
                                                       -->
                                                    </td>
                                                </tr>
                                                <?php
                                                /*
                                                     //if ($totalStudentCountBelowThreshold > 0) {
                                                     //   echo $strAttendanceThreshold;
                                                     //}
                                                    <tr>
                                                        <td  colspan='1' class=''><ul class='myUL'><li><u><b>Exams</b></u></li></ul></td>
                                                    </tr>
                                                    <tr>
                                                       <td  colspan='1' class=''><a href='javascript:showExamStatistics();'>Exam Statistics</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td  colspan='1' class=''><ul class='myUL'><li><u><b>Performance </b></u><?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_TEACHER_DASHBOARD_PERFROMANCE);
                                                     ?></li></ul></td>
                                                    </tr>
                                                    <tr>
                                                        <td  colspan='1' class=''><b>Toppers</b></td>
                                                    </tr>
                                                    <?php
                                                     if ($strToppers != '') {
                                                        echo $strToppers;
                                                     }
                                                    ?>
                                                    <tr>
                                                        <td  colspan='1' class=''><b>Below Average</b></td>
                                                    </tr>
                                                    <?php
                                                     if ($strBelowAvg != '') {
                                                        echo $strBelowAvg;
                                                     }
                                                    ?>
                                                    <tr>
                                                        <td  colspan='1' class=''><b>Above Average</b></td>
                                                    </tr>

                                                    <?php
                                                     if ($strAboveAvg != '') {
                                                        echo $strAboveAvg;
                                                     }
                                                    ?>
                                                 */
                                                ?>
                                            </table>
                                          </table>
                                     <div>
                                 </div>
                                <?php
                                 echo HtmlFunctions::getInstance()->tableBlueFooter();
                                // floatingDiv_Close();
                                  //floatingDiv_Close();
                                 //*************End of Div*********
                                ?>
                        </td>
                      </tr>
                      <tr>
                        <td height="163" scope="col" valign="bottom" align="center" style="padding-left:2px">
                            <?php
                                 //*************Used For Creating*********
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->tableBlueHeader('Student Branch Wise Detail','width=330' ,'height=220','align=center');
                                echo UtilityManager::includeJS("swfobject.js");
                                $flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
                            ?>
                            <table width="100%" height="220" border="0">
                            <tr>
                            <td valign="top" >
                                        <table width="100%" border="0">
                                            <tr>
                                                <td valign="top">
                                                    <div id="flashcontent">
                                                        <strong>You need to upgrade your Flash Player</strong>
                                                    </div>
                                                    <script type="text/javascript">
                                                      x = Math.random() * Math.random();
                                                      //var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "260", "200", "8", "#FFFFFF");
                                                      var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "290", "192", "8", "#FFFFFF");
                                                      so.addVariable("path", "./");
                                                      so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
                                                      so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>2</x><y>120</y><rotate>true</rotate><text>Student ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>170</y><text>Branches ---></text><text_size>10</text_size></label></labels></settings>");
                                                      so.addParam("wmode", "transparent");
                                                      so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
                                                      so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBranchBarData.xml?t="+x));
                                                      so.addVariable("preloader_color", "#999999");
                                                      so.write("flashcontent");
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
                        <td height="163" scope="col" valign="bottom" align="center" style="padding-left:2px">
                            <?php
                                //*************Used For Creating*********
                                //floatingDiv_Create('div_Alerts','Attendance Last Taken On');
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->tableBlueHeader('Login Activities','width=330' ,'height=220','align=center');
                                echo UtilityManager::includeJS("swfobject.js");
                                $flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
                            ?>
                            <table width="100%" height="220" border="0">
                                <tr>
                                    <td valign="top">
                                        <table width="100%" border="0">
                                            <tr>
                                                <td valign="top">
                                                    <div id="flashcontent2">
                                                        <strong>You need to upgrade your Flash Player</strong>
                                                    </div>
                                                    <script type="text/javascript">
                                                        x = Math.random() * Math.random();
                                                        var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "290", "192", "8", "#FFFFFF");
                                                        so.addVariable("path", "./");
                                                        so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
                                                        //so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>120</y><rotate>true</rotate><text>Number Of Users ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>165</y><text>Date---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>8</frequency></category></values></settings>");
                                                        so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>120</y><rotate>true</rotate><text>Number Of Users ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>165</y><text>Date---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>8</frequency></category></values></settings>");
                                                        so.addParam("wmode", "transparent");
                                                        so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
                                                        so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeActivityBarData.xml?t1="+x));
                                                        so.addVariable("preloader_color", "#999999");
                                                        so.write("flashcontent2");
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
                   <!-- End Table -->
                </div>
            </td>
            </tr>
        </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

    <!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice ','',' '); ?>
<form name="NoticeForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="noticeSubject" style="width:600px;"></div></td>
    </tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Department: &nbsp;</b></nobr></td>
        <td width="89%"><div id="noticeDepartment" style="width:300px; height:20px"></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Notice: &nbsp;</b></nobr></td>
        <td><div id="noticeText" style="overflow:auto; width:550px; height:200px" ></div></td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td valign="top" align="right"><nobr><b>From: &nbsp;</b></nobr></td>
        <td><div id="visibleFromDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>

    <tr>
        <td valign="top" align="right"><nobr><b>To: &nbsp;</b></nobr></td>
        <td><div id="visibleToDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
        <td height="5px"></td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event ','',' '); ?>
<form name="EventForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="20%" valign="top" align="right"><nobr><b>Event: &nbsp;</b></nobr></td>
        <td width="80%"><div id="eventTitle" style="overflow:auto; width:300px;" ></div></td>
    </tr>
    <tr>
    <td colspan="2" valign="top" align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
        <td width="20%" align="right" valign="top"><nobr>&nbsp;&nbsp;&nbsp;<b>From: &nbsp;</b></nobr></td>
        <td width="15%" valign="top" align="left" nowrap><div id="startDate" style="width:30px; height:20px"></div></td>
        <td width="5%" valign="top"><nobr>&nbsp;&nbsp;<b>To: &nbsp;</b></nobr></td>
        <td valign="top" align="left" nowrap><div id="endDate" style="width:30px; height:20px"></div></td>
      </tr>
     </table>
    </td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(S): &nbsp;</b></nobr></td>
        <td valign="top"><div id="shortDescription" style="overflow:auto; width:300px;"></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(L): &nbsp;</b></nobr></td>
        <td  valign="top"><div id="longDescription" style="overflow:auto; width:300px; height:200px" ></div></td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Message  Div-->
<?php floatingDiv_Start('divMessage','Message '); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="subject" style="overflow:auto; width:550px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Message: &nbsp;</b></nobr></td>
        <td><div id="message" name="message" style="height:200px;width:550px;overflow:auto"></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Dated: &nbsp;</b></nobr></td>
        <td width="79%"><div id="dated" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<!--Start Attendance  Div-->
<?php floatingDiv_Start('divAttendance','Attendance '); ?>
<form name="AttendanceForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="subject" name="subject" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Class: </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="tclass" name="tclass" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Date : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="date" name="date" class="inputbox" style="border:0px" readonly="true" /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<!--<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divAttendance');return false;" />
        </td>
</tr>-->
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('examStatisticsDiv','Exam Statistics'); ?>
    <div id='examStatisticsTableOuter' style='height:400px;width:900px;overflow:auto;'>
    <div id='examStatisticsTable' style='height:400px;width:895px !important;width:99%;'></div>
    </div>
<?php floatingDiv_End(); ?>

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





<?php
// $History: index.php $
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10-02-17   Time: 12:32p
//Updated in $/LeapCC/Templates/Management
//removed notice editor issue of bubble script
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/11/10    Time: 2:30p
//Updated in $/LeapCC/Templates/Management
//query & validation format updated (topper, below, average, i.e. added)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/10/10    Time: 4:50p
//Updated in $/LeapCC/Templates/Management
//format & validation updated (topper, average,etc.)
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-04   Time: 4:11p
//Updated in $/LeapCC/Templates/Management
//Updated Popup div formatting which was due to implementation of new
//theme
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-09-04   Time: 10:20a
//Updated in $/LeapCC/Templates/Management
//Changed the formatting of Notice and event "Div" popups
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/28/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Management
//Display Last Login in dashBoard
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/02/09    Time: 4:42p
//Updated in $/LeapCC/Templates/Management
//Updated with random number parameter in flash reports
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:39p
//Updated in $/Leap/Source/Templates/Management
//updated XML path
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 11/05/08   Time: 4:51p
//Updated in $/Leap/Source/Templates/Management
//added new reports on management dashboard
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 10/30/08   Time: 2:37p
//Updated in $/Leap/Source/Templates/Management
//updated management reports
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10/22/08   Time: 11:53a
//Updated in $/Leap/Source/Templates/Management
//updated with validations for mangement role
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10/20/08   Time: 6:39p
//Updated in $/Leap/Source/Templates/Management
//updated character length for admin messages
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:40p
//Updated in $/Leap/Source/Templates/Management
//updated with new pie charts
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:44p
//Updated in $/Leap/Source/Templates/Management
//updated notice count function
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:22p
//Updated in $/Leap/Source/Templates/Management
//updated with employee graphs
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/18/08   Time: 11:28a
//Updated in $/Leap/Source/Templates/Management
//updated div popup format
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/17/08   Time: 1:50p
//Updated in $/Leap/Source/Templates/Management
//added new functions for pie chart for management role
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:29p
//Updated in $/Leap/Source/Templates/Management
//added new files as per management role
?>
