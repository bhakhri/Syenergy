<!---**************************For Teacher Role*********************************-->
<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
 <tr>
    <td valign="top" class="menu_middle" height="20">
    <table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
    <tr>
        <td valign="top" class="menu_left" style="width:10px"></td>
        <td width="98%" height="36" valign="middle">
        <!-- QuickMenu Structure [Menu 0] -->
        <ul id="qm0" class="qmmc">

    <li>
        <a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Teacher/employeeInfo.php"><font size="2">Employee Info</font></a>
    </li>
    
    <li><span class="qmdivider qmdividery" ></span></li>
    <li>
        <a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Teacher/searchStudent.php"><font size="2">Student Info</font></a>
    </li>

    <li><span class="qmdivider qmdividery" ></span></li>
    
    <li>
    <a class="qmparent" href="javascript:void(0)"><font size="2">Marks & Attendance</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listDailyAttendance.php">Daily Attendance</a></li>
            <?php
             if($sessionHandler->getSessionVariable('BULK_ATTENDANCE_ALLOWED')==1){
            ?>     
             <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listBulkAttendance.php">Bulk Attendance</a></li>
            <?php
             }
             ?>
            <?php
             if($sessionHandler->getSessionVariable('DUTY_LEAVES_ALLOWED')==1){
            ?>       
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/dutyLeaveEntryAdvanced.php">Student Duty Leaves Entry</a></li>
             <!--<a href="<?php echo UI_HTTP_PATH;?>/Teacher/dutyLeaveEntry.php">Duty Leave</a></li>-->
            <?php
             }
             ?>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listEnterAssignmentMarks.php">Test Marks</a></li>
            <?php
             if($sessionHandler->getSessionVariable('GRACE_MARKS_ALLOWED')==1){
            ?>       
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/graceMarks.php">Grace Marks</a></li>
            <?php
             }
             ?>
            <!--<li><a href="listEnterExternalMarks.php">External Marks</a></li>-->
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listClasswiseAttendance.php">Display Attendance</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listClasswiseGrade.php">Display Marks</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/finalInternalReport.php">Display Final Internal Report</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/teacherInternalFinalMarksReport.php">Display Consolidated Internal Marks Report</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/teacherTopicCoveredReport.php">Display Subject Wise Topic Taught Report</a></li>
            <?php
             if($sessionHandler->getSessionVariable('SUBJECT_TOPIC_RESTRICTION')==1){
            ?>    
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/bulkListSubjectTopic.php">Bulk Subject Topic Master</a></li>
             <?php
             }
             ?>
        </ul>
    </li>

    <li><span class="qmdivider qmdividery" ></span></li>
    
    <li>
    <a class="qmparent" href="javascript:void(0);"><font size="2">Messaging</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listStudentMessage.php">Send Message to Students</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listParentMessage.php">Send Message to Parents</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listEmployeeMessage.php">Send Message to Colleagues</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listAdminMessages.php">Display Admin Messages</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listCourseResource.php">Upload Resource</a></li>
        </ul>
    </li>
    
    <li><span class="qmdivider qmdividery" ></span></li>
    
    <li>
    <a class="qmparent" href="javascript:void(0);"><font size="2">Notices</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listInstituteNotice.php">Display Institute Notices</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listInstituteEvent.php">Display Institute Events</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listTeacherComment.php">Display Teacher Comments</a></li>
            
        </ul>
    </li>
    
    <li><span class="qmdivider qmdividery" ></span></li>
    
    <li>
        <a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Teacher/listTimeTable.php"><font size="2">My Time Table</font></a>
    </li>    
    <li><span class="qmdivider qmdividery" ></span></li>
    <li>
        <a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Teacher/listFineStudent.php"><font size="2">Student Fine</font></a>
    </li>
    <li><span class="qmdivider qmdividery" ></span></li>
    <li>
    <a class="qmparent" href="javascript:void(0);"><font size="2">Reports</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listSubjectPerformance.php">Test wise performance report</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listSubjectPerformanceComparison.php">Test wise performance comparison report</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listGroupWisePerformance.php">Group wise performance report</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listSubjectWisePerformance.php">Subject wise performance report</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/attendanceRegister.php">Attendance Register</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listFeedbackTeacherDetailedGpaReport.php">Feedback Teacher Detailed GPA Report (Advanced)</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Teacher/listFeedbackTeacherFinalReport.php">Feedback Teacher Final Report (Advanced)</a></li>
        </ul>
    </li>
   <!-- <li><span class="qmdivider qmdividery" ></span></li>
    <li>
    <a class="qmparent" href="javascript:void(0);"><font size="2">Help Videos</font></a>
        <ul>
            <li><a href="javascript:newPopup('<?php echo HELP_VIDEO_PATH;?>/Teacher/BulkAttendance/bulkattendance-f.html');">Bulk Attendance</a></li>
            
        </ul>
    </li> --->
    <li><span class="qmdivider qmdividery" ></span></li>   
    
     <li><a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/provideFeedbackAdv.php"><font size="2">Provide Feed Back</font></a></li>
     <li><span class="qmdivider qmdividery" ></span></li>   
    
     <li><a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Teacher/changePassword.php"><font size="2">Change Password</font></a></li>
                                 
  <li class="qmclear">&nbsp;</li></ul>

<!-- Ending Page Content [menu nests within] -->
 </td>
 <td valign="top" class="menu_right" style="width:10px;height:20px"></td>  
 </tr>
</table>
    <script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
 </table>
<!---**************************For Teacher Role(End)*********************************--> 
 
 
<!---**************************For Student Role*********************************--> 
 <table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
 <tr>
    <td valign="top" class="menu_middle" height="20">
    <table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
    <tr>
        <td valign="top" class="menu_left" style="width:10px"></td>
        <td width="98%" height="36" valign="middle">
        <!-- QuickMenu Structure [Menu 0] -->
        <ul id="qm0" class="qmmc">
        <li>
        <a class="qmparent" href="javascript:void(0)"><font size="2">Student Info</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listStudentInformation.php">Complete Info</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listAttendance.php">Display Attendance</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listStudentMarks.php">Display Marks</a></li>
            <!-- <li><a href="listTimeTable.php">Display Time Table</a></li> -->
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listTeacherComments.php">Display Teacher Comments</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listInstituteNotices.php">Display Institute Notices</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listInstituteEvents.php">Display Institute Events</a></li>
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listAdminMessages.php">Display Admin Messages</a></li>
            <!--<li><a href="studentFeedBack.php">Feed Back</a></li> -->
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/listTask.php">Task Manager</a></li>
        </ul>
    </li>
    <li><span class="qmdivider qmdividery" ></span></li>
     <li>
     <a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Student/studentInternalReappearForm.php"><font size="2">Internal Re-appear Form</font></a>
    </li>    
     <li><span class="qmdivider qmdividery" ></span></li> 
    <li>
    <a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Student/listTimeTable.php"><font size="2">Show Time Table</font></a>
        <!--<ul>
            <li><a href="listTimeTable.php">Display Student Time Table</a></li>
        </ul>-->
    </li>    

    <li><span class="qmdivider qmdividery" ></span></li>

    <li><a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Student/listStudentFee.php"><font size="2">Show Fee Payment History</font></a>
    
    <li><span class="qmdivider qmdividery" ></span></li>
    
    <li>
        <a class="qmparent" href="javascript:void(0)"><font size="2">Feedback</font></a>
        <ul>
            <!--<li><a href="<?php echo UI_HTTP_PATH;?>/Student/studentFeedBack.php">Teacher Feed Back</a></li> 
            <li><a href="<?php echo UI_HTTP_PATH;?>/Student/generalFeedBack.php">General Feed Back</a></li>-->
            <li><a href="<?php echo UI_HTTP_PATH;?>/provideFeedbackAdv.php">Advanced Feed Back</a></li>
        </ul>
    </li>
    </li>
    <li><span class="qmdivider qmdividery" ></span></li>
    <li><a class="qmparent" href="<?php echo UI_HTTP_PATH;?>/Student/changeStudentPassword.php"><font size="2">Change Password</font></a>
    </li>

    

<!-- Ending Page Content [menu nests within] -->
 </td>
 <td valign="top" class="menu_right" style="width:10px;height:20px"></td>  
 </tr>
</table>
    <script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
 </table>
<!---**************************For Student Role(End)*********************************-->


<!---**************************For Parent Role*********************************-->
<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
 <tr>
    <td valign="top" class="menu_middle" height="20">
    <table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
    <tr>
        <td valign="top" class="menu_left" style="width:10px"></td>
        <td width="98%" height="36" valign="middle">
        <!-- QuickMenu Structure [Menu 0] -->
        <ul id="qm0" class="qmmc">
        <li>
        <a class="qmparent" href="javascript:void(0);"><font size="2">Parent Activities</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/studentInfo.php">Student Info</a></li>
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayAttendance.php">Display Attendance</a></li>
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayMarks.php">Display Marks</a></li>
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayTimeTable.php">Display Time Table</a></li>
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/listAdminMessages.php">Display Admin Messages</a></li>  
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayTeacherComments.php">Display Teacher Comments</a></li>  
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/listAllAlerts.php">Display Alerts</a></li>  
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/listTask.php">Task Manager</a></li>
            
        </ul>
    </li>
    
    <li><span class="qmdivider qmdividery" ></span></li>
    
    <li><a class="qmparent" href="javascript:void(0);"><font size="2">Institute Notices</font></a>
        <ul>
        <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayNotices.php">Display Institute Notices</a></li>
        <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayEvents.php">Display Events</a></li>
        </ul>
    </li>            

    <li><span class="qmdivider qmdividery" ></span></li>
    
      <li>
    <a class="qmparent" href="javascript:void(0)"><font size="2">Reports</font></a>
        <ul>
            <li><a href="<?php echo UI_HTTP_PARENT_PATH;?>/displayFees.php">Display Fee Details</a></li>
           
         </ul>
    <li><span class="qmdivider qmdividery" ></span></li>
     <li>
    <a class="qmparent" href="<?php echo UI_HTTP_PARENT_PATH;?>/changePassword.php" ><font size="2">Change Password</font></a>
 
                                 
  <li class="qmclear">&nbsp;</li></ul>

<!-- Ending Page Content [menu nests within] -->
 </td>
 <td valign="top" class="menu_right" style="width:10px;height:20px"></td>  
 </tr>
</table>
    <script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
 </table>
 <!---**************************For Parent Role(End)*********************************-->
 

<!---**************************For Management Role*********************************--> 
<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
 <tr>
    <td valign="top" class="menu_middle" height="20">
    <table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
    <tr>
        <td valign="top" class="menu_left" style="width:10px"></td>
        <td width="98%" height="36" valign="middle">
        <!-- QuickMenu Structure [Menu 0] -->
        <ul id="qm0" class="qmmc">
            <li><a class="qmparent" href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/allDetailsReport.php"><font size="2">Student Info</font></a></li>
            <li><span class="qmdivider qmdividery" ></span></li>
            <li><a class="qmparent" href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/allEmployeeDetailsReport.php"><font size="2">Employee Info</font></a></li>
            <li><span class="qmdivider qmdividery" ></span></li>
            <li><a class="qmparent" href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/allFeesDetailsReport.php"><font size="2">Fees Info</font></a></li>
            <li><span class="qmdivider qmdividery" ></span></li>
            <li><a class="qmparent" href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/allNoticeDetailsReport.php"><font size="2">Notice Info</font></a></li>
            <li><span class="qmdivider qmdividery" ></span></li>
            <li><a class="qmparent" href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/allEventDetailsReport.php"><font size="2">Event Info</font></a></li>
            <li><span class="qmdivider qmdividery" ></span></li>
            <li><a class="qmparent" href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/admissionDetailsReport.php"><font size="2">Admission Info</font></a></li>
            <li class="qmclear">&nbsp;</li>
            <li><span class="qmdivider qmdividery" ></span></li>
             
            <li><a class="qmparent" href="javascript:void(0);"><font size="2">Institute Notices</font></a>
                <ul>
                    <li><a href="<?php echo UI_HTTP_PATH?>/listCalendar.php">Manage Events</a></li>
                    <li><a href="<?php echo UI_HTTP_PATH?>/listNotice.php">Manage Notices</a></li>
                </ul>
            </li>            

            <li><span class="qmdivider qmdividery" ></span></li>

            <li><a class="qmparent" href="javascript:void(0);"><font size="2">Messaging</font></a>
                <ul>
                    <li><a href="<?php echo UI_HTTP_PATH?>/listAdminStudentMessage.php">Send Message to Students</a></li>
                    <li><a href="<?php echo UI_HTTP_PATH?>/listAdminParentMessage.php">Send Message to Parents</a></li>
                    <li><a href="<?php echo UI_HTTP_PATH?>/listAdminEmployeeMessage.php">Send Message to Employees</a></li>
                </ul>
            </li>            

            <!--li><span class="qmdivider qmdividery" ></span></li>
                    <li><a class="qmparent" href="javascript:void(0);"><font size="2">Reports</font></a>
                        <ul>
                            <li><a href="<?php echo UI_HTTP_PATH?>/allDetailsReport.php">Advanced student report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/studentAttendanceReport.php">Student attendance report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/studentPercentageWiseReport.php">Percentage wise attendance report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/testWiseMarksReport.php">Testwise marks report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/marksDistributionReport.php">Marks Distribution</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/transferredMarksReport.php">Transferred marks report</a></li>
                            <li><a href="<?php echo UI_HTTP_MANAGEMENT_PATH?>/allSubjectDetailsReport.php">Study period wise subject report</a></li>
                        </ul>
                    </li -->
                 <li><span class="qmdivider qmdividery" ></span></li>
                    <li><a class="qmparent" href="javascript:void(0);"><font size="2">Reports</font></a>
                        <ul>
                            <li><a class="qmparent" href="javascript:void(0);">Messages</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/smsDetailReport.php">Messages List</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/smsFullDetailReport.php">Messages Count List</a></li>
                                </ul>
                            </li>
                            <li><a class="qmparent" href="javascript:void(0);">Hostel</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/hostelDetailReport.php">Hostel Detail Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listCleaningHistory.php">Cleaning History Report</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/listStudentLists.php">Student List</a></li> 
                            <li><a href="<?php echo UI_HTTP_PATH?>/listEmployeeLists.php">Employee List</a></li>
                            <li><a class="qmparent" href="javascript:void(0);">Role</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/roleWiseUserReport.php">Role Wise User Report</a></li> 
                                </ul>
                            </li>
                            <li><a class="qmparent" href="javascript:void(0);">Attendance</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/studentAttendanceReport.php">Student Attendance Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/studentPercentageWiseReport.php">Percentage Wise Attendance Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/attendanceStatusReport.php">Last Attendance Taken Report</a></li>
                                </ul>
                            </li>
                            <li><a class="qmparent" href="javascript:void(0);">Examination Reports</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/testWiseMarksReport.php">Test wise Marks Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/finalInternalReport.php">Final Internal Marks Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/datewiseTestReport.php">Date Wise Test Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/studentRankWiseReport.php">Student Exam Rankwise Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/marksStatusReport.php">Marks Status Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/studentExternalMarksReport.php">Student External Marks Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/internalMarksFoxproReport.php">Student Internal Marks Foxpro Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/tranferredMarksReport.php">Transferred Marks Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/academicPerformanceReport.php">Student Academic Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/studentAcademicPerformanceReport.php">Student Academic Performance Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/testTypeConsolidatedReport.php">Test Type Distribution Consolidated Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/testWiseMarksConsolidatedReport.php">Test Type Category wise Detailed Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/Teacher/listSubjectWisePerformance.php">Subject Wise Performance Report (After Transfer)</a></li>
                                </ul>
                            </li>
                            <li><a class="qmparent" href="javascript:void(0);">Fee collection reports</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/installmentDetail.php">Installment Detail of Students</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/feeCollection.php">Fee Collection</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/paymentHistory.php">Display Fee payment history</a></li>
                                </ul>
                            </li>
                            <li><a class="qmparent" href="javascript:void(0);">Fine</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listFineCollectionReport.php">Category Wise Fine Collection Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listStudentWiseFineCollectionReport.php">Student Wise Fine Collection Summary Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listStudentDetailFineCollectionReport.php">Student Detail Fine Collection Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/fineHistory.php">Fine Payment History Report</a></li>
                                </ul>
                            </li>
                            <li><a class="qmparent" href="javascript:void(0);">Feedback Reports</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/teacherFeedbackReport.php">Teacher Survey Feedback</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/generalFeedbackReport.php">General Survey Feedback</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/scCourseWiseResourceReport.php">Coursewise Resources Report</a></li> 
                            <li><a href="<?php echo UI_HTTP_PATH?>/studentConsolidatedReport.php">Student Consolidated Report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/teacherWiseConsolidatedReport.php">Teacher Consolidated Report</a></li> 
                            <li><a href="<?php echo UI_HTTP_PATH?>/teacherTopicCoveredReport.php">Teacher Topic Taught Report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/listOffenseReport.php">Offense Report</a></li>
                            <li><a class="qmparent" href="javascript:void(0);">Bus Reports</a>
                                <ul>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listInsuranceDueReport.php">Insurance Due Report</a></li> 
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listFuelReport.php">Fuel Usage Report</a></li>
                                    <li><a href="<?php echo UI_HTTP_PATH?>/listRepairCostReport.php">Bus Repair Cost Report</a></li>
                                </ul>
                            </li> 
                            <li><a href="<?php echo UI_HTTP_PATH?>/listConsolidatedDataReport.php">Consolidated Report</a></li> 
                            <li><a href="<?php echo UI_HTTP_PATH?>/displayStudentInternalReappearReport.php">Display Student Re-appear Report</a></li>
                            <li><a href="<?php echo UI_HTTP_PATH?>/displayBusPassReport.php">Display Bus Pass Report</a></li> 
                        </ul>
                    </li> 
                    <li><span class="qmdivider qmdividery" ></span></li>  
                    <li><a class="qmparent" href="<?php echo UI_HTTP_PATH?>/changePassword.php"><font size="2">Change Password</font></a></li>            
                </ul>
                
<!-- Ending Page Content [menu nests within] -->
 </td>
 <td valign="top" class="menu_right" style="width:10px;height:20px"></td>  
 </tr>
</table>
    <script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
</table>
<!---**************************For Management Role(End)*********************************--> 