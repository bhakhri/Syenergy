<?php 

//
//This file creates DashBoard for Teacher Module 
//
// Author :Dipanjan Bhattacharjee
// Created on : 12.07.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php 
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	require_once(BL_PATH.'/helpMessage.inc.php');
	/*
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	*/
?>
<style>
.myUL {
	/*margin-left:0px;
	margin-left:0px !important;
	*/
	margin-top:0px !important;
	margin-top:5px;
	padding-left:14px !important;
	margin-left:0px;
	margin-bottom:0px;
	
}
</style>

<table border='0' width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
               <td valign="top">  <strong>Frequently Asked Questions related to Teacher's functionality</strong></td>   
                 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
		<tr>
			<td valign="top" class="content">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
			<tr>
				<td class="contenttab_border" height="20">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
				<tr>
					<td valign="middle" width="50%" class="content_title">FAQ: </td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td class="contenttab_row" valign="top" >
	 
				<table width="1000px" cellpadding="0px" cellspacing="0px" align="center" style="margin-top:5px;">
						<tr>
						<!-- main left coloumn -->
											<td><a name="top"></a>
				<div class="headingtxt12"><strong> a.	Attendance   </strong> </div>
				<div style="margin-left:10px; text-align:justify;">           
											

				<div class="dhtmlgoodies_question" >What is the function of Schedule button in daily attendance? </div>
				<div class="dhtmlgoodies_answer">
					<div>
						<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image001.png"></div>
						<br>
				The Schedule button is most useful button for easing data entry. After entering date for which we want to mark attendance, normally we have to select values from four drop downs namely Class, Subject, Group and Period. Whereas if we click Schedule button after selecting date for which we want to  mark attendance, click Schedule button. Schedule for the day will be displayed as shown below. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image003.png"></div>
				<br>
				Now click on any row. The values for that row will be automatically filled in the drop downs. For example if I click in row having values 3rd period, 09CSx group, CS101 subject and BTech-CSE-2 Sem, these values will be automatically filled in the relevant drop downs as shown below. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image005.png"></div>
					</div>
				</div>

				<div class="dhtmlgoodies_question">How can I mark daily Attendance in three Clicks. </div>
				<div class="dhtmlgoodies_answer">
					<div>
				a. Click on Schedule.
				<br>
				b. Click on row for which you want to mark attendance.
				<br>
				c. Click on save Button.
				<br>
				<br>
				(A neat exercise. The assumptions here are 
				<br>
				1. That attendance is being marked for current day.
				<br>
				2. All students are Present) 

					</div>
				</div>
					 
				<div class="dhtmlgoodies_question">What is the purpose of blinking button Attendance History? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image007.png"></div>
						This is a very useful feature, which can help us in many ways:
						<br>
						a. To see history of dates for which attendance has been entered.
						<br>
						b. To see For which duration attendance has been entered in Bulk and for which periods attendance has been entered as daily.
						<br>
						c. To edit or delete attendance for a particular period or duration. we can use edit and delete buttons in front of the relevant row. 
						<br>
						<img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image009.png"> Edit Button 
						<br>
						 <img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image011.png"> Delete Button 
						 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image013.png"></div>
					</div>
				</div>

				<div class="dhtmlgoodies_question">How can I delete attendance already entered by me? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					•	If you want to delete attendance entered as Daily attendance, use link Marks & Attendance » Daily Attendance, however if you want to delete attendance entered as bulk attendance, use link Marks & Attendance » Bulk Attendance.
					<br>
					•	screen as shown below will open:
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image013.png"></div>
					•	Find the row containing attendance of your interest, say the row highlighted in blue. 
				<br>
				•	Click on delete attendance <img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image011.png"> button in front of the row of interest. 
				<br>
				•	the attendance for the duration selected will be deleted. 

				</div>
				</div>

				<div class="dhtmlgoodies_question">How can I edit attendance already entered by me? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				•	If you want to Edit attendance entered as Daily attendance, use link Marks & Attendance » Daily Attendance, however if you want to Edit attendance entered as bulk attendance, use link Marks & Attendance » Bulk Attendance.  
				<br>
				•	screen as shown below will open:
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image013.png"></div>
				•	Find the row containing attendance of your interest, say the row highlighted in blue. 
				<br>
				•	Click on Edit attendance <img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image009.png">   button in front of the row of interest. 
				<br>
				•	the attendance for the duration selected will be displayed. 
				<br>
				Change the attendance of the students you want to change. 

				</div>
				</div>

				<div class="dhtmlgoodies_question">When I am about to enter the attendance, I select properly Attendance Date, Class, Subject, Group and Period and click Show List button, the students don't appear and System says "No Data Found". How do I resolve the issue. </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Check if the Group selected above has been allotted to the students. The process for checking the groups is shown below: 
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image016.png"></div>
				a. Select the link Student Info » Search Student.
				<br>
				b. Select the class and subject.
				<br>
				c. Select the group for which you want to check group allocation.
				<br>
				d. Click Show List button.
				<br>
				e. If the groups have been allocated then a list of students will be displayed, otherwise system will display "No data found".
				<br>
				f. If groups have not been allocated to the students, ask the administrator to allocate the groups. 
				<br>

				</div>
				</div>

				<div class="dhtmlgoodies_question">While Entering daily attendance, I have to click twice to change attendance status of a student from Present to Absent or vice-versa. How can I do it in one click? </div>
				<div class="dhtmlgoodies_answer">
				Click on Name of the student whose attendance status you want to change. The student marked Present, will be marked Absent and vice-versa.  
				<br>

				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image018.png"></div>
					<div>
				 
				</div>
				</div>

				<div class="dhtmlgoodies_question">How can I mark daily attendance for specific students? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image020.png"></div>
				In Daily Attendance screen click on the link as shown above. The pop up as shown below opens.
				</div>
				</div>



				<div class="dhtmlgoodies_question">What is the use of field comments while entering attendance? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image022.png"></div>
				This is a special field to help teachers remember some salient points about that day's attendance. Say the Teacher tells the class that she will give them an interesting article on some topic. To remind herself she can enter comments "Give Article on how to use Moodle to the class" while entering that days attendance.    
				</div>
				</div>

				<div class="dhtmlgoodies_question">What is the significance of field Topics taught? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Generally the Topics to be taught to the students are decided at the begining of the session. These are uploaded by the administrator. This field helps us in keeping a record of the days when a particular topic was taught.
				</div>
				</div>

				<div class="dhtmlgoodies_question">Suppose we delete a student, but we want to see his attendance and marks status, how do we see this? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				You can't do this, you have to seek Administrator's help in doing this. The process for the same is as shown below.
				<br>
				<br>
				a. Restore the student using link Setup  » Student Setup  » Restore Students.
				<br>
				b. See his attendance and awards reports.
				<br>
				c. Delete this student again using link Setup  » Student Setup  » Delete Students 
				<br>

				</div>
				</div>

				<div class="dhtmlgoodies_question">How can I check attendance status of the students. </div>
				<div class="dhtmlgoodies_answer">
					<div>
				a. Use Report at the link Marks & Attendance » Display Attendance.
				<br>
				b. See the figure below along with comment added to it. 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image024.png"></div>
				</div>
				</div>

				<div class="dhtmlgoodies_question">System is displaying wrong number of lectures delivered. How can I check, for which date I have forgotten to enter attendance? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				To see day wise details of lectures delivered, use the report Reports » Attendance » Attendance Register. If you have entered daily attendance then it shows day wise attendance details as shown below. You can compare it with your records to see that the attendance is actually OK. 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image026.png"></div>
				<br>
				However if you have entered bulk attendance then it shows how bulk attendance has been entered as shown below. You can check lectures delivered and attended and correct it if necessary. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image028.png"></div>
				</div>
				</div>

				<div class="dhtmlgoodies_question">Student claims he attended more lectures than is being shown in his records. How can I show him period wise lectures attended by him? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				You can shown him his attendance using the report Reports » Attendance » Attendance Register as shown above. He can verify his attendance and it can be corrected as explained under edit attendance. 

				  </div>                         
				</div>                            

				<div class="dhtmlgoodies_question">I don't see the topic taught today in the topic list, How can I add topics to my topic list?? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				A teacher can't change the list of topics shown to them. This is uploaded by the Administrator. You can seek Administrator's help in getting the topics list changed. 


				  </div>                         
				</div>       
				<div class="dhtmlgoodies_question">How can I enter Duty leave for the students?  </div>
				<div class="dhtmlgoodies_answer">
					<div>

				Teacher can mark duty leave only if allowed by the administrator. If allowed, the process is: 
				<br>
				<br>
				•	Click on link Marks & Attendance  »  Student Duty Leaves Entry. 
				<br>
				•	You can write comments explaining reason for awarding duty leaves as shown below. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image030.png"></div>
				<br>


				  </div>                         
				</div>       

				<div class="dhtmlgoodies_question">How can I see the list of topics entered for a subject I am teaching?</div>
				<div class="dhtmlgoodies_answer">
					<div>
				Click on the link Marks & Attendance  » Bulk Subject Topic Master. 


				  </div>                         
				</div>       

				<div class="dhtmlgoodies_question">How can I check the when did I teach which topic? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				Click on link Marks & Attendance » Display Subject Wise Topic Taught Report. 

				  </div>                         
				</div>       

				</div>

				<div class="headingtxt12"><strong> b.	Marks Related Questions     </strong> </div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">If I enters wrong maximum marks for a test and marks are transferred. How can I correct this? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				Follow following steps using link Marks & Attendance  » Test Marks:
				<br>
				a. Open the test for which the max marks have been entered wrongly. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image032.png"></div>
				<br>
				b. Correct the Max Marks.
				<br>
				c. Save the data.
				<br>
				d. Ask the Administrator to repeat the Transfer and subsequent processes again. 


				  </div>                         
				</div>       

				<div class="dhtmlgoodies_question">When I am  about to enter the Marks for a test, I select properly Class, Subject, Group and click Show List button, the students don't appear and System says "No Data Found". How do I resolve the issue. </div>
				<div class="dhtmlgoodies_answer">
					<div>

				Check if the Group selected above has been allotted to the students. The process for checking the groups is shown below: 

				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image016.png"></div>
				a. Select the link Student Info » Search Student.
				<br>
				b. Select the class and subject.
				<br>
				c. Select the group for which you want to check group allocation.
				<br>
				d. Click Show List button.
				<br>
				e. If the groups have been allocated then a list of students will be displayed, otherwise system will display "No data found".
				<br>
				f. If groups have not been allocated to the students, ask the administrator to allocate the groups. 


				  </div>                         
				</div>       

				<div class="dhtmlgoodies_question">When I click on Marks & Attendance »  Final Internal Marks Report, the drop down Degree doesn't get populated. What is the reason?</div>
				<div class="dhtmlgoodies_answer">
					<div>
				This report is visible only after marks have been properly transferred. The degree will get populated only if the marks for subjects you are teaching for the degree have been transferred. Sol. If the degree is not getting populated, ask the Administrator to transfer the marks. 


				  </div>                         
				</div>       

				<div class="dhtmlgoodies_question">How can I graphically see how the students performed in various tests? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				The link Reports » Test wise performance report is a very versatile report which can be used to see graphically the class performance in any combination of one or more tests conducted for the class as shown below. 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image034.png"></div>

				  </div>                         
				</div>       

				<div class="dhtmlgoodies_question">Have we got a single report available before data transfer using which we can see how each student performed in each test? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Using link Marks & Attendance » Display Marks
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image036.png"></div>

				  </div>                         
				</div>       


				<div class="dhtmlgoodies_question">Once the marks have been transferred, how can I see the calculations for internal marks? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Use the link Marks & Attendance »  Final Internal Marks Report. 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image038.png"></div>

				  </div>                         
				</div>    

				<div class="dhtmlgoodies_question">I am teaching same subject to different groups of same class. Can I compare their average performance? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Yes you can compare group wise performance by using link Reports » Display Group wise performance. 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image040.png"></div>

				  </div>                         
				</div> 

				</div>


				<div class="headingtxt12"><strong> c.	Messaging      </strong> </div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">Can I communicate via sms with my students ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				You can send message to the students using the link Messaging  » Send message to students as shown below:
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image042.png"></div>
				<br>
				a. Select SMS as the Messasge Medium.
				<br>
				b. Type the subject and text as shown above.
				<br>
				c. Select Class, subject and Group to which the students belong to whom message is to be sent.
				<br>
				d. Click Show list button. 
				<br>
				e. List of students studying the subject will be displayed.
				<br>
				f. Select students to whom message is to be sent.
				<br>
				g. Click Send button at the bottom of the form.
				<br>
				h. Message will be sent to all the selected students.
				<br>
				<br>
				<strong>Note :</strong>
				<br>
				a. If we select SMS then only the messaqe part will be sent to student, Subject part will  not be sent.
				<br>
				b. If the Institute management decides then teachers can be barred from sending SMS to the students. 
				<br>

				  </div>                         
				</div> 

				<div class="dhtmlgoodies_question">Can I send emails/sms/messages to specific students right from within the application ? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				You can send message to the students using the link Messaging  » Send message to students as shown below: 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image042.png"></div>
				<br>

				•	To send SMS select SMS as the Messasge Medium Similarly select Email or Dashboard as per requirement. 
				<br>
				•	Type the subject and text as shown above. 
				<br>
				•	Select Class, subject and Group to which the students belong to whom message is to be sent. 
				<br>
				•	Click Show list button. 
				<br>
				•	List of students studying the subject will be displayed. 
				<br>
				•	Select students to whom message is to be sent. 
				<br>
				•	Click Send button at the bottom of the form. 
				<br>
				•	Message will be sent to all the selected students. 
				<br>
				<br>
				<strong>Notes :  </strong>
				<br>
				If you select SMS as message medium: 
				<br>
				•	Then only the messaqe part will be sent to student, Subject part will  not be sent. 
				<br>
				•	If the Institute management decides then teachers can be barred from sending SMS to the students. 
				<br>
				•	If message length is less than 160 characters, then the message will be sent as a single SMS, otherwise it will be sent as multiple SMSes each of maximum 160 characters. 
				<br>
				If you select Dashboard as message medium: 
				<br>
				•	Then you will be asked to fill in the from and to date within which the message will be displayed on student's dashboard. 
				<br>


				  </div>                         
				</div> 

				<div class="dhtmlgoodies_question">Can I send emails/sms/messages to other colleagues of mine from within the application ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				You can send message to Colleagues using link Messaging  » Send Message to Employees. The process is same as explained here. The difference is in selecting the employees. The method is explained below. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image044.png"></div>
				<br>


				  </div>                         
				</div> 
				   
				 <div class="dhtmlgoodies_question">Is there a way to communicate with the parents of students  via the application ?  </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Yes, a teacher can send message to students using link Messaging  » Send message to Parents. The remaining process is same as explained in send SMS to students. 


				  </div>                         
				</div> 

				<!--<div class="dhtmlgoodies_question">Can the parents of the students communicate with me via the application? </div>
				<div class="dhtmlgoodies_answer">
					<div>



				  </div>                         
				</div> 
				-->
				<div class="dhtmlgoodies_question">When I send a message to students/parents/colleagues, is there a way to check if the message was delivered to all recipients successfully or failed in some cases ? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				You can see the list of messages  sent by you using the link Notices  » Display Teacher Comments. This will show the screen as shown below:
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image046.png"></div>
				<br>
				on clicking link details it shows what messages, of what type have been sent to whom. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image048.png"></div>
				<br>
				  </div>                         
				</div> 

				<div class="dhtmlgoodies_question">Is there a quick way to send a message to the students who are performing below average  ? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				On teachers dashboard the see the frame Analysis as shown below. 
				  <br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image050.png"></div>
				<br>
				•	On clicking the link shown in top of the diagram, we get the screen as shown in bottom of the screen shot shown above. 
				<br>
				•	Type in the message that is to be sent, along with its subject. 
				<br>
				•	Select the medium through which the message will be delivered to the student(SMS, Dashboard or EMail). 
				<br>
				•	Select the students to whom message is to be sent. 
				<br>
				•	Click on send button. 
				<br>
				•	The message will be sent to the all the selected students. 
				<br>


				  </div>                         
				</div> 

				<div class="dhtmlgoodies_question">Is there a quick way to send a message to the students who are falling short of attendance ? </div>
				<div class="dhtmlgoodies_answer">
					<div>

				On teachers dashboard the see the frame Analysis as shown below. 
					<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image052.png"></div>
				<br>
				•	On clicking the link shown in top of the diagram, we get the screen as shown in bottom of the screen shot shown above. 
				<br>
				•	Type in the message that is to be sent, along with its subject. 
				<br>
				•	Select the medium through which the message will be delivered to the student(SMS, Dashboard or EMail). 
				<br>
				•	Select the students to whom message is to be sent. 
				<br>
				•	Click on send button. 
				<br>
				•	The message will be sent to the all the selected students. 
				<br>


				  </div>                         
				</div> 

				<div class="dhtmlgoodies_question">How can I see the data about me as entered in syenergy? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				Click on link Employee Information. The personal data about you will be displayed. 
				  </div>                         
				</div> 

				<div class="dhtmlgoodies_question">Suppose I see some data entered about me is wrong. How can I correct it</div>
				<div class="dhtmlgoodies_answer">
					<div>
				You cannot change personal data about yourself. However you can inform the Administrator about the error. Administrator can correct the data about you. 


				  </div>                         
				</div>   
					   
				</div>

				<div class="headingtxt12"><strong> d.	View Student's Data      </strong> </div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">How can I see detailed personal data about a student? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					You can see personal data of only the students to whom you teach. The details can be seen using link Student Info. 
					</div>
				</div>
				</div>
					   

				<div class="headingtxt12"><strong> e.	Course Resources </strong> </div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">How can I share some interesting teaching material with the students ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					When a teacher finds some document of interest to students, it is important to share it with the students. syenergy provides an easy way to share this material. This can be done by using the link Messaging  » Upload Course Resource. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image054.png"></div>
				<br>	
					To upload resources, click on link Add Resource. The add resource dialogue will be displayed. 
					<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image056.png"></div>
				<br>
				<br>

				•	Select the Subject for which you want to upload the course resource. 
				<br>
				•	Select the Category of resource(document, URL). 
				<br>
				•	Provide a brief description. This description will be visible to students. The description should be provided in such a manner that it explains purpose of resource being uploaded. 
				<br>
				•	If the resource being uploaded is a URL, type it in URL field. If it is a document, type its path to upload it. This file can be downloaded by students for their reference. 
				<br>
				<br>
				<strong>Note: </strong>
				<br>
				•	Teachers can only upload resources related to the subject they are teaching. 
				<br>
				•	Only students being taught by them will be able to download resources uploaded by them. 
				<br>
				•	They can upload either a valid URL, or a document of type displayed under allowed file types in screen shot above namely files with extension gif, jpg, jpeg, png, bmp, doc, pdf, xls, csv, txt, rar, zip, gz, tar, docx, xlsx, pptx, ppt. 
				<br>
				•	They can upload files of size less than a predefined value set by administrator. 
				<br>

					</div>
				</div>


				<div class="dhtmlgoodies_question">Is there a restriction on what kind/type( word, ppt, etc ) of documents I can share with students ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					You can upload only files of the type:gif, jpg, jpeg, png, bmp, doc, pdf, xls, csv, txt, rar, zip, gz, tar, docx, xlsx, pptx, ppt. 
					</div>
				</div>
				</div>


				<div class="headingtxt12"><strong> f.	Student Fines       </strong> </div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">I see a student without uniform in my class. Can I apply a fine to him through this system ? Do I have to collect this fine too ? If not, then when and how will he pay the fine ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				If Administrator has granted authority to the teachers to impose fine then you can impose them. The link to be used is Student Fine. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image058.png"></div>
				<br>
				No as a teacher you can not collect the fine. The fine process is:
				<br>
				•	Impose the fine(Only roles allowed to impose the fine can impose it) 
				<br>
				•	Approve/Unapprove/Cancel fine(Only persons authorised to collect fine can Approve it) 
				<br>
				•	Collect only approved fines(Only persons authorised to collect fine can collect it once it has been approved) 
				<br>
				So if his fine is Approved, then he has to go to the designated authorities to pay fine. 
					
					</div>
				</div>
				</div>


				<div class="headingtxt12"><strong> g.	Feedback by Students</strong> </div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">Sometime back a feedback was taken by the students for the teachers that taught them. I am told that I can see my results through the application. How ?  
				</div>
				<div class="dhtmlgoodies_answer">
					<div>
					You can see the Feedback reports using the links Reports  » Feedback Teacher Final Report (Advanced) and Reports  » Feedback Teacher Final Report (Advanced).
					<br>

					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image060.png"></div>  
					<br>
				The first report displays the Scores obtained by the student in the feedback by the students. The second report displays the what was given by how many students. The screen shots of both the reports are given above. 
					</div>
				</div>
				</div>
					 
				<div class="headingtxt12"><strong> h.	Dashboard </strong></div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">On the Dashboard , in the Analysis panel, I see links for toppers , below average and above average students. Can you tell me what is the criteria for determining these ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image062.png"></div>  	
				<br>
					The Method for calculating toppers etc is as explained below. For each student: 
				<br>
				•	Add up Maximum marks for all the tests held for the subject for which the student was member of class.(Say MM) 
				<br>
				•	Add up all the marks scored by the student in all the tests above.(Say MO) 
				<br>
				•	Find the percentage of marks scored by the student using formula Percentage= (MO X 100)/MM. 
				<br>
				For example Two assignments(A1 and A2) and two sessionals(S1 and S2): 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image064.png"></div>  	
				<br>
				The students with maximum percentage make it to the toppers list. Using a similar logic below average and above average students are found.
				<br>
				Clicking on any row gives us list of students falling under that category. We can send them message through SMS, EMail or dashboard. 

					</div>
				</div>
				</div>	 

				<div class="headingtxt12"><strong>i.	Miscellaneous  </strong></div>
				<div style="margin-left:10px;">    

				<div class="dhtmlgoodies_question">What is the function of the Shortcut bar? </div>
				<div class="dhtmlgoodies_answer">
					<div>
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image066.png"></div>  	
				<br>
				Shortcut bar is set of always visible shortcuts for most frequently performed acts by the teachers. For example to enter test marks we can use menu option Marks & Attendance  » Test Marks involving lots of mouse movements and 2 clicks. however we can do the same activities by clicking the link <img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image068.gif"> . Similarly all shortcuts are listed in the image above. 

					</div>
				</div>
				<div class="dhtmlgoodies_question">Can I see somewhere all the different links that are available through my login ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					Yes as shown below using link Site Map. 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image069.png"></div>  	
				<br>
					</div>
				</div>
				<div class="dhtmlgoodies_question">I am having multiple roles in the organization. In some departments I am a teacher while in another I am an HOD. Is there a simple way to switch between these roles without logging out of the application and then logging in again ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					At the top of syenergy you will see two drop downs containing list of institutes to which you can log on and roles assigned to you 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image071.png"></div>  	
				<br>
					You can select the role easily, if you want to  log into another institute you can change the institute. 
					</div>
				</div>
				<div class="dhtmlgoodies_question">I am teaching in multiple institutes in a mult-campus organization setup and have different time tables in them. Is there a way to easily switch between the institutes without logging out of the application and then logging in back again ? </div>
				<div class="dhtmlgoodies_answer">
					<div>
					At the top of syenergy you will see two drop downs containing list of institutes to which you can log on and roles assigned to you 
				<br>
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/image071.png"></div>  	
				<br>
					You can select the role the role easily by selecting the role assigned to you from Drop down on right above, if you want to  log into another institute you can change the institute selecting the institute where you want to work from Drop down on right above. 
					</div>
				</div>
				<div style="margin-top:5px; margin-bottom:5px;margin-right:5px;" align="right"><a href="#top"><img src="<?php echo IMG_HTTP_PATH.'/faq'?>/top.png" border="0"></a></div>  	
				
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
		</td>
	</tr>
</table>
