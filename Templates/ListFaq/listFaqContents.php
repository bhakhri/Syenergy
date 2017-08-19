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
               <td valign="top">  <strong>Frequently Asked Questions related to Admin's functionality</strong></td>   
                 
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
                
				
                <div class="headingtxt12"><strong> a.	Role Management    </strong> </div>
                	<div class="dhtmlgoodies_question">How to assign a role or an Additional Role to existing user?
				</div> 
				 
				<div class="dhtmlgoodies_answer">
				<div>
				    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/1.jpg"  width="100%"></div> 
					1.	From Setup Menu go to User Management and select Manage Users. It will show list of all users with details like User Name, Role Name, Name, Display Name and Active status (Path to reach the Manage Users module)<br />
                    2.	Click on  icon on right side of the screen. It will open Edit User window to modify user details. If the name of the user is not on first page then move to other pages by clicking on page numbering links at bottom of the page or enter username in search box to search.<br/>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/2.jpg" width="100%"></div><br/> 
                    3.	Select the role name by clicking on check box to make it checked form Role Name list box. (In case of Student or Parent users Role Name will be displayed Student or Parent by default.)<br/>
                    4.	Click on radio button in Default Role to make checked role as default role of user. The default role is the role which will be assigned to the user when he/she logs in. If the user is assigned multiple roles, then his/her current role can be changed after logging in from the drop down on top right of the screen.<br />
                    5.	Enter data in Display Name to display user name at top right corner of user account.<br/>
                    6.	Click on Save button to save details.(User account will be updated as all mandatory fields are filled
                   </div> 
                </div>
                <div class="dhtmlgoodies_question">How to provide Academic Head Privileges to a role?
				</div>
				<div class="dhtmlgoodies_answer">
					<div>
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/7.png" width="100%"></div>
                    1. From Setup Menu go to User Management and select Academic Head Privileges. It will show the Academic Head Privileges screen. (Path to reach the Academic Head Privileges Module)<br />
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/8.png" width="100%"></div>
                    2. Select role name from Role Name list box. It will populate list of users having selected role in Users list box. (Value for the Role Name list box should be populated from Role Name module)<br />
                    3. Select user from Users list box. <br />
                    4. Click on Assign Privileges button.<br /> 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/9.png" width="100%"></div>
                    5. A list of classes along with details like Group Type and Groups will be displayed. (The selected Group Type like Tutorial, Practical, and Theory will populate the Group field with groups associated with them.)<br />
                    6. Select the class/classes by clicking on check box prior to Class Name. <br /> 
                    7. Select Group Type from group type list. Click on All to select all and None to cancel selection.  Select group type from Group Type list box. To select multiple group types press Ctrl key on keyboard and select group type from Group Type list box by clicking on items in list.<br />
                    8. To select all classes click on check box in first column of header row.<br /> 
                    9. Click on Save button save Academic Head Privileges data.<br />
                    10. Click on View Privileges to view already assigned privileges.

                    </div>
                    </div>
                    <div class="headingtxt12"><strong> b.	Hostel Management   </strong> </div>
                	<div class="dhtmlgoodies_question">How to add Hostel details?
				</div>
				<div class="dhtmlgoodies_answer">
					<div>
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/3.jpg" width="100%"></div><br /> 
                    1.	From Setup Menu go to Hostel Masters and select Hostel Master. It will show hostel details(Path to reach the "Hostel Master" module)<br />
                    2.	Click on  icon on right side of the screen. It will open Add Hostel window to add hostel details<br />
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/4.jpg" width="100%"></div><br /> 
                    3.	Enter name of the hostel in Hostel Name text box<br />
                    4.	Enter hostel abbreviation in Hostel Abbr. text box. Enter the abbreviation intelligently as it would be displayed in various reports in place of the complete hostel name. E.g. Hostel name is Aryabatta, so Hostel Abbr can be AryaB. <br />
                    5.	Enter number of rooms in No. of Rooms text box<br />
                    6.	Select hostel type from Hostel Type list box. E.g. you can select boys, girls, mixed or guest house for hostel type, with Ac, without AC, attached Bath, etc.<br /> 
                    7.	Enter number of floors that are there in the hostel in No. of Floors text box<br />
                    8.	Enter total capacity of hostel in Total Capacity text box<br />
                    9.	Click on Save button to save details<br />

                    </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to add Hostel Room Type?
				</div>
				<div class="dhtmlgoodies_answer">
					<div>
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/5.jpg" width="100%"></div> 
                    1.	From Setup Menu go to Hostel Master and select Hostel Room Type Master. It will show Hostel Room Type Detail screen (Path to reach the "Hostel Room Type Master" module)<br />
                    2.	Click on  icon on right side of the screen. It will open Add Hostel Room Type window to add hostel room type<br />
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/6.jpg" width="100%"></div><br /> 
                    3.	Enter type of hostel in Hostel Room Type text box. E.g. it can be single, double etc.<br /> 
                    4.	Enter hostel type abbreviation in Abbr. text box. E.g. Hostel name is Aryabatta, so Hostel Abbr can be "AryaB".<br /> 
                    5.	Click on Save button to save details<br />

                    </div>
                    </div>
                     <div class="dhtmlgoodies_question">How to add Hostel Room Type Detail for a Hostel?
				</div>
				<div class="dhtmlgoodies_answer">
					<div>
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/7.jpg" width="100%"></div><br /> 
                    1.	From Setup Menu go to Hostel Master and select Hostel Room Type Detail Master. It will show Hostel Room Type Detail screen (Path to reach the Hostel Room Type Detail Master module).<br />
                    2.	Click on  icon on right side of the screen. It will open Add Hostel Room Type Detail window to add hostel room type detail<br />
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/8.jpg" width="100%"></div><br /> 
                    3.	Select hostel name from Hostel Name list box. (This list will contain the names of all hostels which you would have created in the hostel master)<br />
                    4.	Select room type from Hostel Room Type list box.(This list will contain the names of all hostels room type which you would have created in the hostel master)<br />
                    5.	Enter capacity of room in Capacity text box <br />
                    6.	Enter number of beds in No. of Beds text box<br />
                    7.	Select Yes or No from Attached Bathroom list box to specify that the facility of attached bathroom is available or not<br />
                    8.	Select Yes or No from Air Conditioned list box to specify that the facility of air conditioner is available or not<br />
                    9.	Select Yes or No from Internet Facility list box to specify that the facility of internet is available or not<br />
                   10.	Enter number of fans in No. of Fans text box to specify number of fans available in these types of rooms for selected hostel<br />
                   11.	Enter number of lights in No. of Lights text box to specify number of lights available in these types of rooms for selected hostel<br />
                   12.	Enter fee amount in Fees text box<br />
                   13.	Click on Save button to save details<br />

                    </div>
                    </div>
                     <div class="dhtmlgoodies_question">How to add Hostel Room for a Hostel?
				</div>
				<div class="dhtmlgoodies_answer">
					<div>
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/9.jpg" width="100%"></div><br /> 
                    1.	From Setup Menu go to Hostel Master and select Hostel Room Master. It will show Hostel Room Detail screen (Path to reach the Hostel Room Master module).<br />
                    2.	Click on  icon on right side of the screen. It will open Add Hostel Room window to add hostel room<br />
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/10.jpg" width="100%"></div><br /> 
                    3.	Enter name of the room in Room Name text box<br />
                    4.	Select hostel name from Hostel Name list box. E.g. it can be Gargi, etc.<br />
                    5.	Select room type from Room Type list box. E.g. it can be single, double etc.<br />
                    6.	Enter capacity of room in Room Capacity text box<br />
                    7.	Enter fee amount in Fees text box<br />
                    8.	Click on Save button to save details<br />

                    </div>
                    </div>
                     <div class="dhtmlgoodies_question">How to allocate room to Students?
                 </div>
				<div class="dhtmlgoodies_answer">
					<div>
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/11.jpg" width="100%"></div><br /> 
                    1.	From Admin Func. go to Hostel Management and select Room Allocation Master. It will show Room Allocation screen (Path to reach the Room Allocation Master module).<br />
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/12.jpg" width="100%"></div><br /> 
                    2.	Click on  icon on right side of the screen. It will open Add Room Allocation window to allocate room to student<br />
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/13.jpg" width="100%"></div><br /> 
                    3.	Enter roll no in Roll/Reg No text box. It will show student details like "Name and Class"<br />
                    4.	Select hostel name from Hostel list box in which allocating the room to student<br />
                    5.	Select type of room from Room Type list box. It will show room facilities.<br /> 
                    6.	Select the room to allot to student from Room list box<br />
                    7.	Select check in date from Check In Date date calendar<br />
                    8.	Select expected checkout date from Expected Checkout Date calendar when the room will be vacant<br />
                    9.	Click on Save button to save details


                    </div>
                    </div>
                    <div class="headingtxt12"><strong> c.	Student Information    </strong> </div>
                	<div class="dhtmlgoodies_question">How to get list of students in a class?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/14.jpg" width="100%"></div><br /> 
                    1.	Click on Find Student Menu. It will show selection criteria from where select different criteria to show list of students as per requirement<br />
                    2.	Click on Expand link in Academic criteria bar. It will show different list boxes from where you can select either of combination of Degree, Branch, Periodicity, Subject, Group and University<br />
                    3.	Click on Show List button. It will show list of students as per criteria selected<br />
                    4.	Click on Export to Excel to export data in excel format<br />

                    </div>
                    </div>
                    <div class="headingtxt12"><strong> d.	Student Fine Management  </strong> </div>
                	<div class="dhtmlgoodies_question">How to add Fine Category?	
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/15.jpg" width="100%"></div><br /> 
                    1.	Click on Find Student Menu. It will show selection criteria from where select different criteria to show list of students as per requirement<br />
                    2.	Click on Expand link in Academic criteria bar. It will show different list boxes from where you can select either of combination of Degree, Branch, Periodicity, Subject, Group and University<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/16.jpg" width="100%"></div><br /> 
                    3.	Click on Show List button. It will show list of students as per criteria selected<br />
                    4.	Click on Export to Excel to export data in excel format<br />

                    </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Assign Role to Fines Mapping Master?	
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/17.jpg" width="100%"></div><br /> 
                    1.	From Fine Menu select Assign Role to Fines Mapping Master. It will show Assign Role to Fines Mapping Detail box. This is used to allow specific users to be able to approve fines for specific offences (Path to reach the Assign Role to Fines Mapping Master module).<br />
                    2.	Click on  icon on right side of the screen. It will open Add Role to Fines Mapping window to map different fine categories to user who will approve the fine(Could not open add icon ).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/124.jpg" width="100%"></div><br /> 
                    3.	Select role name from Role list box.<br />
                    4.	Select fine category from Fines to be Taken list box. To select multiple fine categories press Ctrl key and select fine category from Fines to be Taken list box<br />
                    5.	In Approver text box enter username who will approve different types of fine categories selected in Fines to be Taken list box. To enter multiple usernames, enter usernames separated with comma eg. 01001,01002<br />
                    6.	Click on Save button to save details<br />

                    </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to add Fine details of Student?	
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/18.jpg" width="100%"></div><br /> 
                    1.	From Fine Menu select Student Fine Master. It will show Student Fine Detail box with list of students with fine imposed on them and fine status (Path to reach the Student Fine Detail module).<br />
                    2.	Click on  icon on right side of the screen. It will open Add Student Fine window to enter fine details of selected student<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/19.jpg" width="100%"></div><br /> 
                    3.	Enter roll no in Roll No text box. It will show  student details like Name and Class<br />
                    4.	Select fine category from Fine Category list box. E.g. It can be CSE_A_CRD, CSE_Bunk, etc.<br /> 
                    5.	Select fine impose date from Date Calendar<br />
                    6.	Enter fine amount in Amount text box<br />
                    7.	Enter reason of fine in Reason text box<br />
                    8.	Select Yes or No from Add to No Dues list box<br />
                    9.	Click on Save button to save details<br />


                    </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Approve Student's Fine?	
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/20.jpg" width="100%"></div><br /> 
                    1.	From Fine Menu select Student Fine Approval. It will show Student Fine Approval Detail box with list of students with a search criteria and fine imposed on them and fine status. These criteria are used to select the specific list of students whose fine you may want to approve. (Path to reach the Student Fine Approval Detail module).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/21.jpg" width="100%"></div><br /> 
                    2.	Select time table from Time Table list box. E.g. It can be JUNDEC2010, etc.<br />
                    3.	Select class from Class list box. E.g. it can be BCA-I, MCA-II etc.<br />
                    4.	Enter roll no in Roll No search box to show details for entered roll no<br />
                    5.	Select fine category from Fine Category list box<br />
                    6.	Enter date range in Date Between:   and   to show list within this date range<br />
                    7.	Select fine status from Fine Status list box. E.g. it can approved, unapproved, etc.<br />
                    8.	Click on Show List button. It will show list of students as per selected criteria<br />
                    9.	Select students to approve by clicking on check box prior to Roll No column<br />
                   10.	Select status from Status list box (It is not showing the list of students). E.g. it can approved, unapproved, etc.<br />
                   11.	Click on Save Button to save details<br />
                   12.	Click on Print button to take print our of selected students with details<br />

                    </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Collect Fine?	
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/22.jpg" width="100%"></div><br /> 
                    1.	From Fine Menu select Collect Fine. It will show Collect Fine screen. This criteria is used to select the specific list of students whose fine you want to collect. (Path to reach the Collect Fine module).<br />
                    2.	Select date of receipt in Receipt Date date calendar<br />
                    3.	Enter roll no in Student Roll No text box. It will show student details in Student Detail box and list of all fines in Fine Detail box.(It is not showing fine details of student)<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/23.jpg" width="100%"></div><br /> 
                    4.	Select fine from Fine List  by clicking on check box prior to Fine Type column<br />
                    5.	Enter remarks if any in Remarks text box<br />
                    6.	Enter name of the person from whom collecting the fine in Received From text box<br /> 
                    7.	Click on Save button or click on Save / Print button to take print out<br />

                    </div>
                    </div>
                    <div class="headingtxt12"><strong> e. Student promotions, grading and testing     </strong> </div>
                	<div class="dhtmlgoodies_question">How to promote Students to next class? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/19.png" width="100%"></div><br />
                    From Activities Menu go to Exam Activities and select Promote Students. It will show Promote Students screen. (Path to reach the Promote Students Module)
                   </div>
                    </div>
                    <div class="headingtxt12"><strong> f. Time Table Management    </strong> </div>
                	<div class="dhtmlgoodies_question">How to create Time Table Label?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/1.png" width="100%"></div><br /> 
                    1. From Setup Menu go to Time Table Masters and select Time Table Label Master. (Path to reach the Time Table Label Master Module)<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/2.png" width="100%"></div><br /> 
                    2. Time Table Label details will be displayed in the gird along with all the time tables that are added till present date.<br /> 
                    3. Click on  icon on right side of the screen. (This is used to add new Time table academic cycle)<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/3.png" width="100%"></div><br /> 
                    4. Add Time Table Label window will be displayed to add new time table label to start a new academic cycle.<br /> 
                    5. Enter time table label in Name text box. This name should be meaningful so that every user is able to understand the meaning while selecting the time table label. (Label names like JUN-DEC2010 or JAN-MAY2010 specify that these names are for particular time period. E.g. June to Dec 2010 academic cycle is for JUN-DEC2010 time table.)<br />
                    6. Click on    to select date for From Date field. (Time Table Label start date will be specified in From Date. E.g. for JUN-DEC2010: Starting Date should be between 1st and 30th June.)<br />
                    7. Click on    to select date for To Date field. (Time Table Label End date will be specified in To Date. E.g. for JUNDEC2010: Ending Date should be between 1st and 31st December.)<br />
                    8. Select Yes or No to make it active or inactive time table label respectively. (By default Yes radio button is selected. If the user wants to make it inactive, select the No radio button.)<br />
                    9. Select Weekly or Daily as per type of time table. Select weekly time table schedule type by clicking on Weekly radio button to create time table schedule on the basis of week which will remain same for all weeks within an academic cycle. Select daily time table schedule type by clicking on Daily radio button to create time table schedule on the basis of date.<br />  
                    10. Click on Save button to save time table label details. (New Time Table Label will be created as all the mandatory fields are selected)

                    </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Associate Time Table to Class?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/18.png" width="100%"></div><br /> 
                    1. From Setup Menu go to Time Table Masters and select Associate Time Table to Class. (Path to reach the Associate Time Table to Class Module)<br />
                    2. Associate Time Table to Class screen will be displayed.<br />  
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/5.png" width="100%"></div><br /> 
                    3. Select time table from Time Table list box. (Value has been populating from Time Table Label Module)<br />
                    4. Click on Show List button. It will show list of classes with check box prior to Class Name column. The check box is either checked or unchecked.<br /> Checked means that class is already associated with selected time table label and unchecked means that class is not associated with selected time table label<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/6.png" width="100%"></div><br /> 
                    5. Select class to associate with time table by clicking on check box prior to Class Name label.<br /> 
                    6. To select all classes click on checkbox in first column of header.<br /> 
                    7. Click on Save button to save data. (All the changes will be updated)

                    </div>
                    </div>
                      <div class="headingtxt12"><strong> g. Academic Programmes Setup    </strong> </div>
                	<div class="dhtmlgoodies_question">How to add Subjects? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/10.png" width="100%"></div><br /> 
					1. From Setup Menu go to Academic Masters and select Subject Master. (Path to reach the Academics Masters Module)<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/11.png" width="100%"></div><br /> 
                    2. A list of subjects along with subject details will be displayed. (E.g. Subject Name, Subject Code, Abbr., etc)<br />
                    3. Click on label to sort any column in header row. (Data should be displayed in ascending or descending order by click on sorting link)<br />
                    4. Click on  icon on right side of the screen. <br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/12.png" width="100%"></div><br /> 
					5. Add Subject window to add details of subject will be displayed. (This is used to add new Subject)<br />
                    6. Enter name of the subject in Subject Name text box. (E.g. Accommodation Management, ESL, etc)<br /> 
                    7. Enter code of the subject in Subject Code text box. (E.g. Elective 1, Elective 2, etc)<br />
                    8. Enter abbreviation of subject in Abbr. text box. (E.g.  Accn. Mgt.  For Accounts Management)<br />
                    9. Select type of the subject from Subject Type list box. (List has been populated from Subject Type Master module)<br />
                   10. Select category of subject from Subject Category list box. (List has been populated from Subject Category Master module)<br />
                   11. Select Yes or No from Attendance list box to specify whether attendance is required for the subject.<br />
                   12. Select Yes or No from Marks list box to specify whether marks entry is required for the subject.<br />
                   13. Click on Save button to save subject details. (New Subject will be created as all the mandatory fields are selected)

					</div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Assign Subjects to Class? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
                   <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/13.png" width="100%"></div><br /> 
                    1. From Setup Menu go to Class Masters and select Assign Subjects to Class. It will show Assign Subjects to Class screen. (Path to reach the Academics Masters Module)<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/14.png" width="100%"></div><br /> 
                    2. Select time table from Time Table list box. It will populate classes associated to selected time table in Class list box. (Value for Time Table has been populated from Time Table Label Master Module)<br />
                    3. Select class from Class list box.<br />
                    4. Click on All Subjects check box to set the criteria to show list of subjects. If this box is "checked" then it will show all subjects and unchecked check box will show only subjects assigned to selected class.<br />
                    5. Click on Show List button.<br /> 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/15.png" width="100%"></div><br /> 
                    6. A list of subjects with details like Code, Subject Name, Type, Optional, Major, Minor, Offered, Credits, Internal Marks and External Marks will be displayed.<br />
                    7. Row marked with red color signifies that the subject is already assigned to selected class.<br /> 
                    8. Select subject to assign/associate to class by clicking on check box prior to Code.<br />
                    9. Click on check box in Optional column to mark it Checked to specify that the selected subject is optional subject.<br />
                   10. Click on check box in Major/Minor column to mark it Checked to specify that the selected subject is major/minor subject. (This field will be enabled when clicked on Yes Checkbox in the Optional Field)<br />
                   11. Click on check box in Offered column to mark it Checked to specify that the selected subject is compulsory subject.<br />
                   12. Enter credits for selected subject in Credits column.<br /> 
                   13. Enter internal marks weightage for subject in Internal Marks column.<br />
                   14. Enter external marks weightage for subject in External Marks column.<br />
                   15. Click on Save button to save data. (All the changes done will be saved)
                    </div>
                    </div>
                    <div class="headingtxt12"><strong> h. Attendance management     </strong> </div>
                	<div class="dhtmlgoodies_question">How to delete Attendance?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/123.jpg" width="100%"></div><br /> 
                  1. From Activities Menu go to Marks and Attendance and select Delete Attendance. It will show Delete Attendance box. (Path to reach the Delete Attendance Module)<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/16.png" width="100%"></div><br /> 
                  2. Select time table label from Time Table list box. It will populate all the classes associated with the time table in Class list box. (E.g. JUNDEC2010, etc)<br />
                  3. Select class from Class list box. It will populate all the subjects associated with the class in Subject list box. (E.g. BTECH-I, MCA-I, etc)<br />
                  4. Select subject from Subject list box. (E.g. Account Management, etc)<br />
                  5. Click on Show List button. It will show detailed list of Attendance taken for the selected subject.<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/17.png" width="100%"></div><br /> 
                  6. Select from list by clicking on check box to delete attendance. (E.g. click on the check box for selecting a specific teacher)<br /> 
                  7. Click on Delete button to delete attendance.<br />
                  8. If you want to delete all attendance list then click on check box in label header row.<br /> 
                  9. Click on Delete button to delete attendance for the selected subject.<br /> 

                    </div>
                    </div>
                    <div class="headingtxt12"><strong> i Student groups and sections management (Course/Program Management)    </strong> </div>
                	<div class="dhtmlgoodies_question">How to create group? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/24.jpg" width="100%"></div><br /> 
                  1. From Setup Menu go to Class Masters and select Group Master. It will show list of groups with details. (Path to reach the Group Master))<br />
                  2. Click on   icon button on right side of the screen. It will show Add Group window to add group details)<br />
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/25.jpg" width="100%"></div><br /> 
                  3. Select the class from Class list box for which you want to create group. (All active classes should be displayed in Class list box and value is populating from Create Class module))<br />
                  4. Enter the name of the group in Name text box.)<br /> 
                  5. Enter short name of the group in Short Name text box. (User have to give the abbreviation to group name))<br />
                  6. To create optional group click Optional check box to mark it checked.)<br /> 
                  7. If optional is checked then select optional subject from Subject list box.)<br /> 
                  8. Select parent group from Parent Group list box. (All created groups should be displayed in Parent Group list box.))<br />
                  9. Select type of the group from Group Type Name as per group type. (Five types (Tutorial, Practical, Theory, Training Workshop and universal) of group name should get displayed and select from there.))<br />
                 10. Click on Save button to save group details. (Group will be created on click "Save" button as have all mandatory fields are filled.)<br />

                   </div>
                    </div>

				<div class="dhtmlgoodies_question">How Can I check the detailed Teaching Load of Each Teacher? 
                   </div>
				<div class="dhtmlgoodies_answer">
				  <div>
					  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/T1.jpg" width="100%"></div><br /> 
                  1. To see the teaching load of each teacher, we can use the link Time Table >> Display Teacher Load.<br />
     	          2. Click on  icon button on right side of the screen. It will show Add Group window to add group details<br />
                   </div>
                  </div>
					

				<div class="dhtmlgoodies_question">How can I see the details of courses being taught by teachers?
                   </div>
				<div class="dhtmlgoodies_answer">
				  <div>
					  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/T2.jpg" width="100%"></div><br /> 
                  The details of the courses can be seen using two reports.<br /><br />
				  1.By using report Time Table >> Display Teacher Load<br />
     	          2.By using the report Reports >> Subject Taught By Teacher Report<br />
                   </div>
                  </div>


	             <div class="dhtmlgoodies_question">How to Allocate Students to Groups?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/26.jpg" width="100%"></div><br /> 
                   1.	From Setup Menu go to Student Setup and select Assign Group to Students (Advance). It will show complete assign group screen. (Path to reach the Group Master))<br />
                   2.	Select degree or class from Degree list box. (All active classes should be displayed in Degree list box and value is populating from Assign Group to Students module))<br />
                   3.	Select sorting order from Sort By list box as per your criteria. (All active classes should be displayed in ascending or descending order))<br />
                   4.	Click on Show List button. It will show list of students as per the selection of sorting criteria along with groups created for selected class or degree program. Note : If it is showing No Record Found message then it means that Groups has not been created for selected class or degree program)<br />
                   5.	Assign the groups to students by clicking on check box in group name column for theory, practical and tutorial groups)<br />
                   6.	Click on Save button to assign groups to students (Group will be created on click Save button as have all mandatory fields are filled.))<br />

                   </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Create Time Table?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/27.jpg" width="100%"></div><br /> 
              1.	From Time Table Menu go to Manage Time Table and select Class Wise. It will show Table Detail box. (Path to reach the Group Master))<br />
			        <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/125.jpg" width="100%"></div><br /> 
              2.	Select period slot from Period Slot list box. (All active classes should be displayed in Period list box and value is populating from period slot module)<br />
              3.	Select time table from Time Table list box. (All active classes should be displayed in table list box and value is populating from Time table list module)<br />
              4.	Select class from Class list box. (All classes which are associated with Time Table will be displayed in Class list box)<br />
              5.	Click on Show Time Table button. It will show details of time table, if time table is already created for selected criteria<br />
              6.	Click on   label to add additional entry in time table (Various field should be displayed mandatory field has to be filled compulsory.)<br />
              7.	Enter time table details by selecting Subject, Group, Teacher, Room  list boxes and enter periods  in Periods text box<br />
              8.	Click on Show Conflicts button to check the conflicts in time table(This will display the which mandatory field doesn''t filled and conflicts)<br />
              9.	Click on   icon to delete any time table entry<br />
             10.	Click on Save button to time table details (Group will be created on click Save button as have all mandatory fields are filled.)<br />



                   </div>
                    </div>
                    <div class="dhtmlgoodies_question">How to Edit Test Marks? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/29.jpg" width="100%"></div><br />
					 1.	From Activities Menu go to Marks and Attendance and select Test Marks. It will show Test Marks box with selection criteria. (Path to reach the Group Master)<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/30.jpg" width="100%"></div><br />
                     2.	Select time table from Time Table list box(All active classes should be displayed in table list box and value is populating from Time table list module)<br />
                     3.	Select teacher from Teacher list box(All active classes should be displayed in teacher list box and value is populating from Test Marks module)<br />
                     4.	Select class from Class list box. (All classes which are associated with Time Table will be displayed in Class list box)<br />
                     5.	Select subject from Subject list box(All subjects which are associated with Class will be displayed in Subject list box)<br />
                     6.	Select group from Group list box(All groups which are associated with Subject will be displayed in Group list box)<br />
					 7.	Select type of test from Test Type list box. (All active classes should be displayed in Test Type list box and value is populating from Test Marks module)<br />
					 8.	Select test from Test list box to edit test. It will show the details of test created.<br />
                     9.	Click Show List button.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/32.jpg" width="100%"></div><br />
                    10.	Enter maximum marks in Max Marks<br /> 
                    11.	Enter marks for each student in Marks column<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/31.jpg" width="100%"></div><br />
                    12. Click on Save button to save test marks (Group will be created on click Save button as have all mandatory fields are filled.

                   </div>
                    </div>
					 <div class="dhtmlgoodies_question">How to add Qualification of Employee? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/52.jpg" width="100%"></div><br />
					1.	From Setup Menu go to Administrative Masters and select Employee Master. (Path to reach the Group Master)<br />
                    2.	List of all employees will be displayed in grid.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/53.jpg" width="100%"></div><br />
                    3.	Click on Employee Detail  icon in Action column. It will show details of selected employee<br />
                    4.	Click on Qualification tab. It will show qualification details of selected employee<br />
                    5.	Click on label. It will add a new row. (Various Row should be displayed mandatory Rows has to be filled compulsory.)<br />
                    6.	Enter qualification details of employee<br />
                    7.	Click on   icon to delete qualification information (Group will be created on click Save button as have all mandatory fields are filled.)

					</div>
					</div>
					 <div class="dhtmlgoodies_question">How to create Degree Program? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/50.jpg" width="100%"></div><br />
					1.	From Setup Menu go to Class Master and select Degree Master. It will show list of all degree programs. (Path to reach the Group Master)<br />
                    2.	Click   icon button on right side of the screen<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/51.jpg" width="100%"></div><br />
                    3.	Enter name of the degree in Degree text box(User have to write the name of Degree)<br />
                    4.	Enter code of degree in Code text box User have to write the code user want to give to Degree)<br />
                    5.	Enter abbreviation of degree in Abbreviation text box(User have to write the Abbreviation to the Degree)<br />
                    6.	Click on Save button to save degree details (Group will be created on click Save button as have all mandatory fields are filled.)

					</div>
					</div>
					 <div class="dhtmlgoodies_question">How to create Branch? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/48.jpg" width="100%"></div><br />
					1.	From Setup Menu go to Class Master and select Branch Master. It will show list of all branches. (Path to reach the Group Master)<br />
                    2.	Click   icon button on right side of the screen<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/49.jpg" width="100%"></div><br />
                    3.	Enter name of the branch in Branch Name text box<br />
                    4.	Enter branch abbreviation in Abbreviation text box<br />
                    5.	Click on Save button to save branch details. (Group will be created on click Save button as have all mandatory fields are filled.)


					</div>
					</div>
					 <div class="dhtmlgoodies_question">How to create Batch? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/46.jpg" width="100%"></div><br />
					1.	From Setup Menu go to Class Master and select Batch Master. It will show list of all batches. (Path to reach the "Group Master")<br />
                    2.	Click icon button on right side of the screen<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/47.jpg" width="100%"></div><br />
                    3.	Enter name of the batch in Batch Name<br />
                    4.	Enter Start Date of batch<br />
                    5.	Enter End Date of batch<br />
                    6.	Click on Save button to save batch details (Group will be created on click Save button as have all mandatory fields are filled.)


					</div>
					</div>
					<div class="dhtmlgoodies_question">How to create Session? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/44.jpg" width="100%"></div><br />
					1.	From Setup Menu go to Class Master and select Session Master. It will show list of all 
Session. (Path to reach the Group Master)<br />
                    2.	Click   icon button on right side of the screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/45.jpg" width="100%"></div><br />
                    3.	Enter name of the session in Session Name<br />
                    4.	Enter Start Date of session(Select the Start Date from the calendar by clicking on calendar icon)<br />
                    5.	Enter End Date of session(Select the End Date from the calendar by clicking on calendar icon)<br />
                    6.	Select Yes or No from Active list box to make it active session<br />
                    7.  Click on Save button to save session details. (Group will be created on click Save button as have all mandatory fields are filled.)

					</div>
					</div>
					<div class="dhtmlgoodies_question">How to create Class? 
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/41.jpg" width="100%"></div><br />
					1.	From Setup Menu go to Class Master and select Create Class. It will show list of all classes.
(Path to reach the Group Master)<br />
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/42.jpg" width="100%"></div><br />
                    2.	To sort the listing as per column header, click in header column<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/43.jpg" width="100%"></div><br />
                    3.	Click   icon button on right side of the screen<br /> 
                    4.	Select session from Session list box<br />
                    5.	Select batch from Batch list box<br />
                    6.	Select university from University list box<br />
                    7.	Select degree from Degree list box<br />
                    8.	Select branch from Branch list box<br />
                    9.	Select duration of degree from Degree duration list box<br />
                   10.	Select periodicity from Periodicity list box<br />
                   11.	Click on Get Study Periods button. It will show list of all semesters as per degree duration.<br /> 
Select Active to make the semester active; select Past for preceding semesters and Future for subsequent semesters<br />
                   12.	Enter description of class in Description text box<br />
                   13. lick on Save button save class details. (Group will be created on click Save button as have all mandatory fields are filled.)

					</div>
					</div>
					<div class="headingtxt12"><strong> j. Communication between different stakeholders, i.e. students, parents, faculty and administration    </strong> </div>
                	<div class="dhtmlgoodies_question">How to upload Notices?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/39.jpg" width="100%"></div><br />
					1.	From Communication Menu select Manage Notices. It will show list of all notices
(Path to reach the Group Master)<br />
                    2.	Click icon button on right side of the screen<br /> 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/40.jpg" width="100%"></div><br />
                    3.	Enter subject of the notice in Subject text box<br />
                    4.	Enter notice details in Notice Details text area<br />
                    5.	Select file as an attachment if any by click on Browse button and selecting a file from list(For E.g. Png, jpg, jpeg)<br />
                    6.	Select date range from Visible From and Visible To date calendar<br />
                    7.	Select the name of the department who is uploading the notice from Department list box<br /> 
                    8.	Select the different criteria from Notice Visible To area<br />
                    9.	Click on Save button to upload notice details. (Group will be created on click Save button as have   all mandatory fields are filled)


					</div>
					</div>
						<div class="dhtmlgoodies_question">How to Send Messages to Students?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/36.jpg" width="100%"></div><br />
					1.	From Communication Menu select Send Message to Students. It will show Send Message to Students screen. (Path to reach the Send Message to Students)<br />
                    2.	Enter subject of the message in Subject text box. (Regarding what the notice is)<br />
                    3.	Select Message Medium : SMS, Email, Dashboard (You can select one of these as per your requirement or select all)<br />
                    4.	Enter message<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/37.jpg" width="100%"></div><br />
                    5.	Click on Send button to send message. (If message medium is selected then only message will be sent)<br />
                    6.	Select the criteria to show the list of students and click on show list<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/38.jpg" width="100%"></div><br />
                    7.	Select the students by clicking on check box or click send to all.

					</div>
					</div>
						<div class="dhtmlgoodies_question">How to Send Messages to Parents?
                   </div>
				<div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/34.jpg" width="100%"></div><br />
					1.	From Communication Menu select Send Message to Parents. It will show you Messaging screen. (Path to reach the Send Message to Parents)<br />
                    2.	Enter subject of the message in Subject text box<br />
                    3.	Select Message Medium : SMS, Email, Dashboard (You can select one of these as per your requirement or select all)<br />
                    4.	Enter message.<br />
                    5.	Select the Criteria to show the list of students and click on show list.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/35.jpg" width="100%"></div><br />
                    6.	Select the students by clicking on check box under Father, Mother and Guardian Labels or click check box prior to label to select all<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
                    7.	Click on Send button to send message. (If message medium is selected then only message will be sent)

					</div>
					</div>
					<div class="headingtxt12"><strong> k. Session start activities </strong> </div>
                	<div class="dhtmlgoodies_question">What are the typical set of session start activities that need to be performed?
                   </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/graph.jpg" width="100%"></div><br />
					</div>
					</div>
					<div class="headingtxt12"><strong> l. Pre-Admission of Student </strong> </div>
                	<div class="dhtmlgoodies_question">How to Upload Candidate Details? 
                   </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					Path to reach Upload Candidate Details module: - From Pre Admission tab select Upload Candidate Details.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					1.	Click on here link present under the Note caption. This will open a text file in which instructions are given.
                    2.	Follow the instructions strictly and make the excel file and save it with .xls extension. E.g. abc.xls
                    3.	For uploading the saved excel file click on Browse button or write the path for that file in the textbox. E.g. C:\Documents and Settings\CET\My Documents\abc.xls
                    4.	Click on Upload Candidate List button to upload the data present in the saved excel file.
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					5.	If all the steps are followed properly, a popup message will be opened displaying message Data Transferred Successfully.
                    6.	For uploading candidate's rank, follow step 2 and 3. Then click on Upload Candidate Rank present below "Upload Candidate List" button.
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to View Candidate Details? 
                   </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					Path to reach View Candidate Details module: - From Pre Admission tab select View Candidate Details. 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					1.	Click on Add  icon or Add Enquiry link on right side of the screen. It will show Add Enquiry Details window to add details of Candidate.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					2.	Select class in which candidate wants admission from the Seeking Admission in mandatory field. Data is populated from Class Masters module.
                    3.	Select Counselor from Counselor mandatory field. Data is populated from Manage Users module.
                    4.	Enter valid data for Application Form No. field textbox. It will be unique.
                    5.	Select the Enquiry date in the Date field.
                    6.	Select the admission status from Admission Status field. It will be Waiting, Offered and Rejected.
                    7.	Under Student Personal Details caption, enter the candidate's name in the mandatory First Name field textbox and optional Last Name field textbox.
					8.	Select date of birth of the Candidate in the Date of Birth field. 
                    9.	Select the gender of the candidate by clicking on the Male or Female radio button.
                   10.	Enter valid email id in the textbox for Email mandatory field textbox. E.g. studentenquiry@yahoo.com
                    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
			       11.	Enter the contact details of the candidate in the Contact No. mandatory field textbox and Mobile No. field textbox. E.g. 988128898.
                   12.	Select Nationality of the student in the drop down list. E.g. Indian.
                   13.	Select the Domicile of the candidate from the drop down list. Data is populated from the State Master module. E.g. Punjab, H.P, etc.
                   14.	Select category of the student for Category field from the drop down list. E.g. General, OBC, etc.
                   15.	Select the Entrance exam given by the candidate from the Comp. Exam. By field. It will be AIEEE, CET, etc.
                   16.	Enter the roll no. of the candidate in the Exam Roll No. field textbox for the competition exam selected in the "Comp. Exam. By" field.
                   17.	Enter the rank of the candidate in the Rank field textbox. This means the candidate''s position in the entrance exam given.
                   18.	Enter the father's name and mother's name of the student in the Father's Name field textbox and Mother's Name field textbox.
                   19.	Under Contact Details caption, enter the address of the candidate in the Address1 field textbox and Address2 field textbox.
                   20.	Enter valid pin code for Pincode field textbox. 
                   21.	Select data for Country, State, City and Other (City) field from the drop down list. The list is populated from Country Master, State Master and City Master module respectively.
				   <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
				   22.	Under Visitor Details caption, enter the purpose of visit by the candidate in the Purpose of Visit textbox. E.g. the purpose can be the enquiry for the admission or just for survey.
                   23.	Enter the name of the person with whom the visitor wants to meet in Name of the person whom visitor intends to meet field textbox. E.g. any faculty member or any employee.
                   24.	Click on the appropriate checkbox(s) for the Source From field where the candidate has got the information. It can be single or multiple selections i.e. Print Media, Thru College Student, Thru Website and Electronic Media.
                   25.	Enter the name of the paper in the Paper Name field textbox. It is to be entered if Print Media checkbox is selected in the Source from field.
                   26.	Click on Save button. This will save the enquiry details of the candidate.
                   27.	Click on Cancel button. This will not save the data and will close the Add Enquiry Details form and will resume to View Candidate Details module.
				    <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
				   28.	Select the class from Seeking Admission in drop down list. E.g. MCA, BTECH, etc.
                   29.	Enter name of the candidate in the Student Name field textbox.
                   30.	Enter name of Student''s father in the Father''s Name field textbox. 
                   31.	Select name of the city in the City, State and Country fields. It can be single or multiple selections as checkboxes are present with names. Click on the checkbox(s) to select multiple names.
                   32.	Select the data for Counselor field from the drop down list. 
                   33.	Select Candidate Status from the drop down list. It will be Waiting, Admission, Offered, Rejected and Counselor.
                   34.	Click on Show List button. This will show the list of candidates according to the details entered in the Search fields.
                   35.	Click on Edit   icon under Action in the grid to edit the enquiry details of the candidate.
                   36.	Click on Delete   icon in the grid to delete the record of the candidate.
				   37.	Click on Print   icon in the grid to print the details the candidate's details.
                   38.	Click on the paging links to browse the list of candidates.
                   39.	Click on Print button to print the list of candidates. A window will open in which the entire list is displayed.
                   40.	Click on Export to Excel button to get the list of candidates in excel file.
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to conduct Candidates Counseling? 
                  </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					Path to reach Candidate Counseling module: - From Pre Admission tab select Candidate Counseling. 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					1.	Enter the range of serial numbers in Students Counseling From Sr. No. field textbox and Counseling To Sr. No field textbox for these mandatory fields. By default, the value is 1 and 500 respectively.
                    2.	Select the field according to which sorted records will be displayed from the "Sort Field" drop down list. It will be Rankwise, Namewise. This is mandatory field. By default, the value is "Rankwise".
                    3.	Select "Ascending (A-Z)" or "Descending (Z-A)" from "Order By" mandatory field. By default, the value is "Descending (Z-A)".
                    4.	Select the exam from drop down list for the "Filter by Exam" field. E.g. AIEEE, CET, etc.
                    5.	Enter the rank in the "Rank From" field textbox and "Rank To" field textbox. This is the minimum and maximum range of ranks entered. 
                    6.	Select the date for "Set Counseling Period From Date" field and "Set Counseling Period To Date" field. By default, the selected date is present date.
                    7.	Click on "Show List" button. This will display the "Student Enquiry List".
                     <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					8.	To select all candidates click on checkbox in first column of header.
                    9.	Select candidates for counseling by clicking on check box prior to "Name" label.
                   10.	Click on "Send" button to send the message to the candidates selected for the counseling.
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to view Admission Fee Details?
                  </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					Path to reach select "Admission Fee" module: - From "Pre Admission" tab select "Admission Fee". 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					1.	Enter the correct roll no. of the competition exam in "Competition Exam Roll No." field textbox.
                    2.	Enter the correct form no. of the application in the "Application Form No." field textbox. Enter the value for either for "Competition Exam Roll No."  Or "Application Form No." field.
                    3.	Click on the "Search" button. This will display "Fee Receipt Details" and "Candidate Details" shown in the next screenshot.
                    4.	Click on the "Print" button. It will display the "Admission letter" for printing with all the details entered in the above mentioned field.
					5.	Under "Fee Receipt Details" caption, select the class in the drop down list for "Degree" mandatory field. E.g. BTECH, MCA
                    6.	Enter the amount in the "Cash Amount" mandatory field textbox.
                    7.	Enter the Demand Draft Amount in the "DD Amount" mandatory field textbox.
                    8.	Enter the valid Demand Draft Number in the "DD No." mandatory field textbox.
                    9.	Select the date for "DD dated" mandatory field.
                   10.	Enter the name of the bank in the "Bank Name" mandatory field textbox.
                   11.	Click on "Save" button to save the "Fee Receipt Details" of the candidate.
                   12.	Click on "Save/Print" button. It will save the "Fee Receipt Details" and will display the "Admission letter" for printing with all the details entered in the above mentioned field.
                   <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
				   13.	Click on "Cancel Receipt" button to cancel the receipt of the candidate. It will open a popup with confirmation message "Are you sure cancel this receipt". Click on "OK" to cancel the receipt. A "Cancel Receipt" window will open.
                   <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
				   14.	Select the mode for refunding fees from the drop down list in the "Refund Mode" mandatory field. It will be "Percentage" and "Fixed".
                   15.	Enter the percentage in the "%" field textbox. By default it is 20%. Its value will be accepted between 0 and 100 only.
                   16.	Enter the remarks in the "Remarks" field textbox.
                   17.	Click on "Save" button to save the "Cancel Receipt Details".
                   18.	Click on "Cancel" button to cancel the process and return to the "Admission Fee" module page.
				   <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
				   19.	Under the "Candidate Details" Caption the details of the candidate are displayed.
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to view enquiry details of the candidate in graphical view?
                  </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Student Enquiry Demographics" module: - From "Pre Admission" tab select "Student Enquiry Demographics". 
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/33.jpg" width="100%"></div><br />
					1.	Click on the graph displayed under "City Wise" caption. A window opens which shows the no. of Candidates according to the city. 
                    2.	Click on the graph displayed under "State Wise" caption. It will show the no. of candidates according to the state. 
                    3.	Click on the graph displayed under "Counselor Wise" caption. It will show the no. of candidates according to the counselor. 
                    4.	Click on the graph displayed under "Gender Wise" caption. It will show the no. of candidates according to the gender.
                    5.	Click on the graph displayed under "Programme Wise" caption. It will show the no. of candidates according to the programme. 
					

					</div>
					</div>
				<div style="margin-top:5px; margin-bottom:5px;margin-right:5px;" align="right"><a href="#top"><img src="<?php echo IMG_HTTP_PATH.'file:///faq'?>/top.png" border="0"></a></div>  	
				
				</div>	 
				
				<div class="headingtxt12"><strong>m.Inventory Management </strong> </div>
				<div class="dhtmlgoodies_question">How to Add Category to Item Category Master Module?
                  </div>
				   <div class="dhtmlgoodies_answer">
					<div>
					Path to reach "item Category" master module: - From "Inventory" module go to "Item category" Master.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/100.png" width="100%"></div><br />
					1.	Click on "Add" icon on right side of the screen. (It will open "Add Item Category" window to create new category of item)
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/101.png" width="100%"></div><br />
					2.	Enter item name in "Category Name" field text box (It should be Stationary, Pencil and Pens)<br />
					3.	Enter item code in "category code" field text box (Enter the code intelligently as it would be displayed in various reports in place 
						of the complete Item Category Name).<br />
					4.	Click on "Save" button (User account will be updated as all mandatory fields are filled).<br />
					5.	Click on "Cancel" button ("Add Item Category" window get closed).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/102.png" width="100%"></div><br />
					6.	Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displaying in grid).<br />
					7.	Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					8.  Click on "Delete" Icon underneath "Action" delete the already exists records in grid.<br />
					9.	User can view all records by click on paging links on the page.<br />
					10. Click on "Print" button to take the print of the record (This will only print data displayed in grid).<br />
					11. Click on "Export to Excel" button to take this grid data to excel sheet.<br />


					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Assign Category to Item Master Module?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Item Master" module: - From Inventory module go to "Item Master".
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/103.png" width="100%"></div><br />
					1.  Click on "Add" icon on right side of the screen (It will open "Add Item" window to create new item).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/104.png" width="100%"></div><br />
					2.  Select "Category code" from Category code drop down box (For E.g. Pencils, Pens and Stationary).<br />
					3.  Data gets populated in "Category Name" in field textbox automatically as clicked on "Category code" field textbox. <br />
					4.  Enter the name of item in "Item name" field textbox (For e.g. Books, plastic pencil box).<br />
					5.  Enter the code for Item in "Item code" field textbox (Enter the code intelligently as it would be displayed in various reports in place	    of the complete Item name)<br />
					6.  Enter value in "Re-Order Level" field Textbox as in which order user want to display the items. <br />
					7.	Select value from "Unit" drop down box (For e.g. Kilogram, liter and number).<br />
					8.	Click on "Cancel" button to close the "Add Item" window.<br />
					9.	Click on "Save" button to save details (Add item will be updated as all mandatory fields are filled).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/105.png" width="100%"></div><br />
					10. Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					11. Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					12. Click on "Delete" icon underneath "Action" to delete the already exists records in grid.<br />
					13. User can view all records by click on paging links on the page.<br />
					14. Click on "Print" button to take the print of the record (This will only print data displayed in grid).<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Mapping in requisition Mapping?
					</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Requisition Mapping" module: - From "Inventory" module go to "Requisition Mapping".
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/106.png" width="100%"></div><br />
					1. Select "Role Name" from Role Name field drop down box.<br />
					2. Click on Show list button (Requisition Mapping detail window opens up).<br />
					3. Select the "Role" from Role field drop down box.<br />
					4. Select the "User" from drop down box in front of that.<br />
					5. Click on "Save" button to save details (Requisition Mapping will be updated as all mandatory fields are filled).<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Request in Requisition Master?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Requisition Master" module (From Inventory module go to "Requisition Master").
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/107.png" width="100%"></div><br />
					1.	Click on "Add" icon on right side of the screen.(It will open "Add Item Master" window to create new item)<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/108.png" width="100%"></div><br />
					2.	A unique code display as "Requisition No" in Requisition no field textbox, populating automatically in grid.<br />
					3.	Select "Item category" from Item Category field drop down box.<br />
					4.	Select "Item Code" from Item Code field drop down box. (Enter the code intelligently as it would be displayed in various reports in		place of the complete Item Code)<br />
					5.	Enter the "Quantity required" in Quantity required field text box.<br />
					6.	Click on "Action" button X (To delete row from Add Requisition Table).<br />
					7.	Click +  on this link to Add Row (To add row in Add Requisition Table).<br />
					8.	Click on "Save" button to save details (Requisition Master will be updated as all mandatory fields are filled).<br />
					9.	Click on "Cancel" button to close the "Add Requisition" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/109.png" width="100%"></div><br />
					10. Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					11. Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					12. Click on "Cancel" icon underneath "Action" to Cancel the already exists records in grid.<br />
					
					13. Click on "Print" button to take the print of the record (This will only print data displayed in grid).<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How To Approved request in Approved Requisition?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Approved Requisition" module (From Inventory module go to "Approved Requisition").
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/110.png" width="100%"></div><br />
					1. Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					2. Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/111.png" width="100%" ></div><br />
					3. Click on "Approved" button to approve item Request from "Requisition master".<br />
					4. Click on "Reject" button to reject item Request from "Requisition Master".<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Maintain Open Stock?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Opening Stock Master" Module: - From Inventory module go to "Opening Stock Master".
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/112.png" width="100%" ></div><br />
					1.  Select "Category Code" from category code field drop down box (Enter the code intelligently as it would be displayed in various			reports	in place of the complete Item Code).<br />
					2.	Value for Country Name should be populated as selected in Category Code drop down box.<br />
					3.  Select the "Date" for "As on Date" from date picker.<br />
					4.  Click on "Show List" button to show data in that grid.<br />
					5.	Enter the data of opening Balance for both 5 & 6 step.<br />
					7.	Click on "Save" button to save details ("Opening Stock Master" Detail will be updated as all mandatory fields are filled).<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to issue items in "Issue Master" module?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Issue Master" module: - From Inventory module go to "Issue Master" module.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/113.png" width="100%" ></div><br />
					1.	Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					2.	When request is approved by "Approved Requisition" data comes directly to "Issue Master".<br />
					3.	Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/114.png" width="100%"></div><br />
					4. Click on "Approve" button to approve request after reading all mandatory details.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to work in "Party Master"?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "party Master" module: - From Inventory module go to "Party Master" module.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/115.png" width="100%"></div><br />
					1.	Click on "Add" icon on right side of the screen (It will open "Party Master" window to create new category of party).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/117.png" width="100%"></div><br />
					2.	Enter "Party Name" in Party name field text box (It should be anything).<br />
					3.	Enter "Party code" in Party code field text box (Enter the code intelligently as it would be displayed in various reports in place of		the complete Party Name).<br />
					4.	Enter the "Party Address" in party address field text box.<br />
					5.	Enter the "Phone(s)" in phone field text box (Only accept single phone number).<br />
					6.	Enter the "Fax" number in fax field textbox.<br />
					7.	Click on "Save" button to save details (Party Master will be updated as all mandatory fields are filled).<br />
					8.	Click on "Cancel" button to close the Party Master window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/116.png" width="100%"></div><br />
					9.  Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					10. Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					11. Click on "Delete" icon underneath "Action" to delete the already exists records in grid.<br />
					12. Click on "Print" button to take the print of the record. (This will only print data displayed in grid).<br />
					13. Click on "Export to Excel" button to take these current records to excel sheet.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to indent data in "Indent Master"?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Indent Master" module: - From Inventory module go to "Indent Master" module.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/118.png" width="100%"></div><br />
 
					1.	Click on "Add" Icon on right side of the screen (It will open "Indent Master" window to add new Indent).<br />
						<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/119.png" width="100%"></div><br />
					2.	Click on this link +  to Add Row (To add row in "Add Indent" Table).<br />
					3.  Select the "Item Category" name from Item category drop down box (For e.g. Pencils, Pens, and Stationary).<br />
					4.  Select the "Item Category" code from Item code drop down box (Enter the code intelligently as it would be displayed in various reports		in place of the complete Item Category Name).<br />
					5.	Enter the "Quantity required" of items in quantity required textbox.<br />
					6.	Click on "Cross" button Add Indent window gets closed.<br />
					7.	Click on this "X" link to "Cancel" row record from "Add Indent" table.<br />
					8.  Click on "Save" button (User account will be updated as all mandatory fields are filled).<br />
					9.	Click on "Cancel" button ("Add Indent" window get closed).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/120.png" width="100%"></div><br />
					10. Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					11. Click on "Edit" icon underneath "Action" to update or modify the already exists records in grid.<br />
					12. Click on "Cancel" icon underneath "Action" to Cancel the already exists records in grid.<br />
					13. Click on "Print" button to take the print of the record (This will only print data displayed in grid).<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Generate the PO?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Generate PO" module: - From Inventory module go to "Generate PO" module.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/121.png" width="100%"></div><br />

					1. Click on Add icon on right side of the screen (It will open "Generate PO" Category window to create new Generate PO).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/123.png" width="100%"></div><br />
					2. Enter party code in "party code" text box (Enter the code intelligently as it would be displayed in various reports in place of the	     complete Party Name).<br />
					3. Enter Party name in "Party name" field text box (It should be Ads, Eddo anything).<br />
					4. Click +  on this link to Add Row (To add row in "Add PO" Table).<br />
					5. Select "Indent No" from Indent No drop down box (For e.g. 01_2010_03).<br />
					6. Select "Item Category" from Item Category drop down box (For e.g. pens, pencils and Stationary).<br />
					7. Select "Item Code" from Item Code drop down box (Enter the code intelligently as it would be displayed in various reports in place of	   the complete Party Name).<br />
                    8. Enter the "Quantity required" of items in quantity required textbox.<br />
					9. Enter the "Rate" of single item in quantity required textbox.<br />
					10. Enter the "Amount" of item in amount textbox.<br />
					11. Click on this "X" link to "Cancel" row record from "Add PO" table.<br />
					12. Click on "Save" button (User account will be updated as all mandatory fields are filled).<br />
					13. Click on "Cancel" button ("Add Indent" window get closed).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/124.png" width="100%"></div><br />
 
					14. Enter the data to be search in Search text box and click on Search button (Search will be work for all fields displayed in grid).<br />
					15. Click on "Cancel" icon underneath "Action" to Cancel the already exists records in grid.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Receive the GRN ?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "GRN" module: - From Inventory module go to "GRN" module.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/125.png" width="100%"></div><br />
					1.	Click on "Add" icon on right side of the screen (It will open "GRN" Category window to create new GRN).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/126.png" width="100%"></div><br />
					2.	Enter party code in "party code" field text box (Enter the code intelligently as it would be displayed in various reports in place of		the complete Party Name).<br />
					3.	Enter "Bill No" in "Bill no" field text box.<br />
					4.	Select "Date" from Date Picker for "Bill Date" field.<br />
					5.	Click on + link to Add Row (To add row in "Add GRN" Table).<br />
					6.	Select "GRN No" from Indent No drop down box. (For e.g. 01_2010_03).<br />
					7.	Select "Item Category" from Item Category drop down box (For e.g. pens, pencils and Stationary).<br />
					8.	Select "Item Code" from Item Code drop down box (Enter the code intelligently as it would be displayed in various reports in place of		the complete Party Name).<br />
					9.	Enter "P.O.Rate" in P.O.Rate field textbox (P.O. stand for purchasing order, it should contain rate of purchasing).<br />
					10. Enter "P.O.quantity" in P.O.quantity field textbox (It should contain purchasing item quantity for e.g. this should be numeric			only).<br />
					11. Enter "Qty. Received" in Qty. Received field textbox (It should contain the quantity of purchased items).<br />
					12. Enter "Amount" in amount field text box (It contain amount of purchasing Item). <br /> 
					13. Click on this "X" link to "Cancel" row record from "GRN" table.<br />
					14. Click on "Save" button (User account will be updated as all mandatory fields are filled).<br />
					15.	Click on "Cancel" button ("GRN" window get closed).<br />
					</div>
					</div>
					<div class="headingtxt12"><strong>n.Placement Management </strong> </div>
					<div class="dhtmlgoodies_question">How to View and Add Follow ups details?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Follow Ups Master" module: - From "Admin Func. Menu" go to "Placement Management" and select "Follow Ups Master". It will show "Follow Ups Master" screen.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/128.jpg" width="100%"></div><br />
					1.	Click on Add icon  on right side of the screen. It will open "Add Follow Ups" window to add company details.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/129.png" width="100%"></div><br />
					2.	Enter name of the company in "Name" text box field. E.g. it can be "Tata Consultancy Services". <br />
					3.	Enter company code in "Code" text box field. E.g. it can be "TCS".<br />
					4.	Enter address of the company in "Address" text box field.<br />
					5.	Enter the person name to whom user want to contact, in "Contact Person" text box field.<br />
					6.	Enter the designation of the company person in "Designation" text box field.<br />
					7.	Enter the contact number of the company in "Landline" and "Mobile" text box field.<br />
					8.	Enter the email of the company or person in "Email Id" text box field.<br />
					9.	Select "Established" or "Startup" radio button in "Industry Type" field. E.g. if industry is new, then user can select "Startup" radio		button.<br />
					10.	Select "Yes" or "No" radio button in "Active" field. E.g. yes for active company and no for non-active company.<br />
					11.	Enter the remarks for the company in "Remarks" text box field.<br />
					12.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/130.png" width="100%"></div><br />
						 

					13.	Enter the valid data and click on "Search" icon   to search that data in the grid. Search will work for all fields displaying in grid.		E.g. enter name in search text box field to search that name in grid.<br />
					14.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					15.	Click on "Edit" icon underneath "Action" in grid to modify or update the existing data.<br />
					16.	Click on "Delete" icon underneath "Action" in grid to delete existing data in the grid.<br />
					17.	Click on "Paging" link to browse all records in grid.<br />
					18.	Click on "Print" button to print the list of companies. Print window will open in which entire list will be displayed.<br />
					19.	Click on "Export to Excel" button to get the list of companies in excel file.<br />
					

					</div>
					</div>
					<div class="dhtmlgoodies_question">How to View and Add Follow ups details?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Follow Ups Master" module: - From "Admin Func. Menu" go to "Placement Management" and select "Follow Ups Master". It will show "Follow Ups Master" screen.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/131.png" width="100%"></div><br />
 

					1.	Click on Add icon on right side of the screen. It will open "Add Follow Ups" window to add company details.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/132.png" width="80%"></div><br />
					 

					2.	Select the "Company" from drop down list box in "Company" field, which is populating from "Company Master" module.<br />
					3.	Select "Date" by clicking on date picker   on which user had contacted with company.<br />
					4.	Select "New call" radio button to make new call and "Follow up" radio button to follow existing call in "Type of call" field.<br />
					5.	Select the radio button in "Contacted Via" field by which user had contacted with company. E.g. Select "email" radio button if user		had contacted with company by email.<br />
					6.	Enter the contacted company person in "Contacted Person" field text box to whom user had already contacted. <br />
					7.	Enter the designation of the company person in "Designation" text box field.<br />
					8.	Enter the comments related to company in "Comments" text box, for that company which user had selected in "Company" field.<br />
					9.	Select "Yes" radio button to follow up existing call and "No" radio button to don''t follow up in "Follow Up" field.   <br />
					10.	Select "Date" by clicking on date picker in "Follow Up date" field, if user had clicked on "Yes" radio button in "Follow up" field and		selected date can not be less than current date.<br />
					11.	Select the radio button in "Follow up by" field by which user wants to contact with company. E.g. Select "email" radio button if user     wants to contact with company by email and enter email address in "Email Id" text box field.<br />
					12.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/133.png" width="100%"></div><br />
					 


					13.	Enter the valid data and click on "Search" icon   to search that data in grid. Search will work for all fields displaying in grid.<br />		E.g. enter company in search text box field to search that company in grid. <br />
					14.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					15.	Click on "Edit" icon underneath "Action" in grid to modify or update the existing data.<br />
					16.	Click on "Delete" icon underneath "Action" in grid to delete existing data in grid.<br />
					17.	Click on "Print" button to print the list of companies. Print window will open in which entire list will be displayed.<br />
					18.	Click on "Export to Excel" button to get the list of companies in excel file.<br />
					</div>
					</div>

					<div class="dhtmlgoodies_question">How to View and Add Placement Drive Master details?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Placement Drive Master" module: - From "Admin Func. Menu" go to "Placement Management" and select "Placement Drive Master". It will show "Placement Drive Master" screen.
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/134.png" width="100%"></div><br />
 

					1.	Click on Add icon on right side of the screen. It will open "Placement Drive Master" window to add company details.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/135.png" width="100%"></div><br /> 

					2.	Enter placement code in "Placement Drive Code" text box field. E.g. it can be "Colg-2010".<br />
					3.	Select the "Company" from drop down list box in "Company" field, which is populating from company master.<br />
					4.	Select starting, ending "Date" and "Time" from "From Date" field and "To Date" field. Starting date should not be less than ending		date.<br />
					5.	Enter the name of the persons in "Visiting persons" text box field who are visiting for the placement test.<br />
					6.	Enter the venue of the placement test in "Venue" text box field. E.g. venue can be in any college.<br />
					7.	Select "Yes" or "No" radio button from "Eligibility Criteria" field. E.g. if user had select "Yes" radio button then he/she had 
						to enter cutoff marks of 10th,12th and last semester in "Cutoff marks in" text box field and if user had selected "No" radio 
						button then	"Cutoff marks in" field will be disabled. <br />
					8.	Select "Yes" or "No" radio button from "Test" field. E.g. if user had selected "Yes" radio button then he/she will enter the number of		"Test subjects" and "Duration" in "Enter test subjects and duration " field.<br />
					9.	Select "Yes" or "No" radio button from "Group Discussion" field. E.g. if user had selected "Yes" radio button then there will be group		discussion session and user can enter discussion duration in "Discussion Duration" text box field.<br />
					10.	Select "Yes" or "No" radio button from "Technical interview" field.<br />
					11.	Select "Yes" or "No" radio button from "HR interview" field.<br />
					12.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/136.png" width="100%"></div><br />

					13.	Enter the valid data and click on "Search" icon   to search that data in grid. Search will work for all fields displaying in grid.		E.g. enter company in search text box field to search that company in grid.<br />
					14.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					15.	Click on "Edit" icon underneath "Action" in the grid to modify or update the existing data.<br />
					16.	Click on "Delete" icon underneath "Action" in grid to delete existing data in grid.<br />
					17.	Click on "Print" button to print the list of companies. Print window will open in which entire list will be displayed.<br />
					18.	Click on "Export to Excel" button to get the list of companies in excel file.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Upload Student Details?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					 Path to reach the "Upload Student Details" module: - From "Admin Func. Menu" go to  "Placement Management" and select "Upload Student Details". It will show "Upload Student Details" screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/137.png" width="100%"></div><br />
					1.	Select "Placement Drive" from drop down list box in "Placement Drive" field, which is populating from Placement Drive Master.<br />
					2.	Click on "Browser" button to select ".xls extension" file from "Choose File" field. To make ".xls extension" file please refer to the		"Note" written in red font underneath "format for file" grid. <br />
					3.	Click on "Upload button" to upload selected ".xls extension" file.<br />
					</div>
					</div>

					<div class="dhtmlgoodies_question">How to Generate Student List?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Generate Student List" module: - From "Admin Func. Menu" go to "Placement Management" and select "Generate Student List". It will show "Generate Student List" screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/138.png" width="100%"></div><br />
					1.	Select "Placement Drive" from drop down list box in "Placement Drive" field, which is populating from Placement Drive Master.<br />
					2.	"Placement Drive Details" will be displayed underneath "Placement Drive Details" caption when user will select "Placement Drive" from		drop down list box in "Placement Drive" field. E.g. In this eligibility criteria, test, interview, cutoff marks and group discussion		details will be displayed. <br /> 
					3.	Grace Marks will be entered by user in "Grace Marks" text box field. E.g. if grace marks 5% is entered then it will deduct 5% from 		10th, 12th and graduation.<br />
					4.	Click on "Show List" button to see details of students with cutoff marks details.<br />
					5.	User can "Sort" the data in ascending or descending order by click on sorting link next to field s name in grid.<br />
					6.	Click on "Check box" underneath student field in the grid, to select a specific students or user can select all students just by<br />		clicking on first "Check box" in grid.<br />
					7.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Generate Student Result List?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Generate Student Result List" module: - From "Admin Func. Menu" go to "Placement Management" and select "Generate Student Result List". It will show "Generate Student Result List" screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/139.png" width="100%"></div><br />

 
					1.	Select "Placement Drive" from drop down list box in "Placement Drive" field, which is populating from Placement Drive Master.<br />
					2.	"Placement Drive Details" will be displayed underneath "Placement Drive Details" caption when user will select "Placement Drive"<br />
						from drop down list box in "Placement Drive" field. E.g. In this eligibility criteria, test, interview, cutoff marks and group  discussion details will be displayed.  <br />
					3.	Click on "Show List" button to see details of students with cutoff marks details.<br />
					4.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					5.	Click on "Check box" underneath selected field in the grid, to select a specific students who are selected or user can select all		students just by clicking on first "Check box" in grid.<br />
					6.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
					7.	Click on "Print" button to print the list of students. Print window will open in which entire list will be displayed.<br />
					8.	Click on "Export to Excel" button to get the list of students in excel file.<br />
					</div>
					</div>
					<div class="headingtxt12"><strong>o.Exam Masters</strong> </div>
					<div class="dhtmlgoodies_question">How to assign Test Type Category?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Add Test Type Category" module: - From "Setup" modules go to "Exam Masters" now go to" Add Test Type Category".<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/140.png" width="100%"></div><br />

					1.	Click on "Add"   icon on right side of the screen (It will open "Add Test Type Category" window to create new Test type).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/141.png" width="100%"></div><br />
					 
					2.	Enter the "Test type Category name" in "Test type Category name" field textbox (It should be assignment, testing).<br />
					3.	Enter the "Test type Category Abbr" in "Test type Category Abbr" field textbox (It should be assign, test).<br />
					4.	Enter the "Exam type" in "Exam type" field textbox (It should be Internal, External).<br />
					5.	Enter the "Exam type" in "Exam type" field textbox (It should be Internal, External).<br />
					6.	Enter the "Show Category in Test" in "Show Category in Test" field textbox (It should be Yes or No).<br />
					7.	Enter the "Whether Attendance Category" in "Show Category in Test" field textbox (It should be Yes or No).<br />
					8.	Select the color according to "Test Type Category Master" from color check box.<br />
					9.	Click on "Save" button to save details ("Test Type Category Master" will be updated as all mandatory fields are filled).<br />
					10. Click on "Cancel" button to close the "Test Type Category Master" window.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/143.bmp" width="100%"></div><br />
					11. Click on "Edit"   icon underneath "Action" to update or modify the already exists records in grid.<br />
					12. Click on "Delete" icon underneath "Action" to delete the already exists records in grid.<br />
					13. User can view all records by click on paging links on the page.<br />
					14. Click on "Print" button to take the print of the record (This will only print data displayed in grid).<br />
					15. Click on "Export to Excel" button to take these current records to excel sheet.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to assign Evaluation Criteria?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach "Evaluation Criteria" : - From "Setup" go to module go to "Exam Masters" now go to "Evaluation Criteria".<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/142.png" width="100%"></div><br />
					1. Evaluation Criteria Detail should be displayed on page.<br />
					2. Click on "Print" button to take the print of the record. <br />
					3. Click on "Export to Excel" button to take these current records to excel sheet.<br />
					</div>
					</div>
					<div class="headingtxt12"><strong>p.Leave Management</strong> </div>
					<div class="dhtmlgoodies_question">	How to Add Session for Leaves? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Leave Session Master" module : - From "Leaves" go to "Leave Session Master". It will show "Leave Session Master" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/194.png" width="100%"></div><br />
					

					1.	Click on "Add"  icon on right side of the screen. It will open "Add Session" window to generate Session for leaves.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/193.png" width="100%"></div><br />
					 

					2.	Enter name of session in "Name" field text box. E.g. it can be "JUNDEC10" means session is from "June" to "December" in session			"2010". <br />
					3.	Select start date of session from date picker ( ).<br />
					4.	Select end date of session from date picker ( ).<br />
					5.	Select "Yes/No" radio button for Active field. Session should be active as "Yes" radio button is selected.<br /> 
					6.	Click on "Save" button to save the detail. Details will be saved if all mandatory fields are filled.<br />
					7.	Click on "Cancel" button to close the "Add Session" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/211.png" width="100%"></div><br />
					 
					8.	Click on "Edit" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/191.png" width="1%">) icon underneath Action in grid, it will open "Edit Session" window and user can update the existing record.<br />
					9.	Click on "Delete" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/190.png" width="1%">) icon underneath Action to delete the existing record in grid.<br />
					10.	Click on "Print" button to print the grid records. It will print all fieldname records which are in grid.<br />
					11.	Click on "Export to Excel" button to export the records in excel sheet.<br />
					12.	Enter the data to be search in Search text box and click on Search button. Search will be work for all fields displaying in grid.<br />
					13.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.  <br />
					14.	User can move to next page to see further records by click on "Paging" icon.<br />
					</div>
					</div>
					
					<div class="dhtmlgoodies_question">How to Add Types of Leaves? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Leave Type Master" module :- From "Leaves" go to "Leave Type Master". It will show "Leave Type Master" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/197.png" width="100%"></div><br />
					1.	Click on "Add"  icon on right side of the screen. It will open "Add Leave Type" window to generate Type of leaves.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/198.png" width="100%"></div><br />
					2.	Enter name of leave type in "Leave Type Name" field textbox. E.g. "Accident", "Medical".<br />
					3.	Select radio button (Yes/No) for Carry Forward field (E.g. if employee can take 10 leaves for "Accident" leave type in one session and		employee takes 6 leaves in a session, and if Carry forward field is selected- "Yes", then for next session that employee can take 14		leaves for "Accident" leave type).<br />
					4.	Select Radio button (Yes/No) for "Reimbursement" field (E.g. if employee can take 10 leaves in a session and employee takes 6 leaves,		then for remaining 4 leaves, that employee gets "bonus" at end of session).<br />  
					5.	Select "Yes/No" radio button for Active field. Leave type should be active as "Yes" radio button is selected.<br />
					6.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					7.	Click on "Cancel" button to close the "Add Leave Type" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/199.png" width="100%"></div><br /> 
					8.	Click on "Edit" ( ) icon underneath Action in grid, it will open "Edit Leave Type" window and user can update the existing record.<br />
					9.	Click on "Delete" ( ) icon underneath Action to delete existing record in grid.<br />
					10.	Click on "Print" button to print the grid records. It will print all fieldname records which are in grid.<br />
					11.	Click on "Export to Excel" button to export the records in excel sheet.<br />
					12.	Enter the data to be search in Search text box and click on Search button. Search will be work for all fields displaying in grid.<br />
					13.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.<br />
					14.	User can move to next page to see further record by click on "Paging" icon.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Make Leave Set? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Leave Set Master" module : - From "Leaves" go to "Leave Set Master". It will show "Leave Set Master" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/200.png" width="100%"></div><br />
					1.	Click on "Add"  icon on right side of the screen. It will open "Add Leave Set" window to generate Set of leaves.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/203.png" width="100%"></div><br /> 

					2.	Enter name of leave set in "Leave Set Name" field textbox. E.g. "Employee", "CEO" etc.<br />
					3.	Select "Yes/No" radio button for Active field. Leave Set should be active as "Yes" radio button is selected.<br />
					4.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					5.	Click on "Cancel" button to close the "Add Leave Set" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/213.png" width="100%"></div><br /> 
					6.	Click on "Edit" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/191.png" width="1%">) icon underneath Action in grid, it will open "Edit Leave Set" window and user can update the existing record.<br />
					7.	Click on "Delete" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/190.png" width="1%">) icon underneath Action to delete existing record in grid.<br />
					8.	Click on "Print" button to print the grid records. It will print all fieldname records which are in grid.<br />
					9.	Click on "Export to Excel" button to export the records in excel sheet.<br />
					10.	User can search the records by enter "search value" in search field and then click on search icon.<br />
					11.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.<br />
					12.	User can move to next page to see further record by click on "Paging" icon.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Make Leave Set Mapping?</div> 
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Leave Set Mapping" module :- From "Leaves" go to "Leave Set Mapping". It will show "Leave Set Mapping" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/205.png" width="100%"></div><br /> 
					1.	Click on "Add"  icon on right side of the screen. It will open "Add Leave Set Mapping" window to make mapping of leave set with leave		type.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/206.png" width="100%"></div><br />
					2.	Select set of leaves from "Leave set" list box. Values populated from "Leave Set Master" module. <br />
					3.	Select types of leaves from "Leave Type" list box. Values populated from "Leave Type Master" module. <br />
					4.	Enter numeric value in "Value" field (E.g. "Value = 2" for "Leave Type  Accident" and "Leave Set  Employee" means employee can		take maximum 2 leaves for accident).<br />
					5.	User can delete the record by click on "Delete" icon (Note: user can able to delete the record if the record is not linked in other		modules).<br />
					6.	User can add multiple rows by click on "Add More" link.
					7.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					8.	Click on "Cancel" button to close the "Add Leave Set Mapping" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/207.png" width="100%"></div><br /> 
					9.	Click on "Edit" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/191.png" width="1%">) icon underneath Action in grid, it will open "Edit Leave Set Mapping" window and user can update the existing record.<br />
					10.	Click on "Delete" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/190.png" width="1%"> ) icon underneath Action to delete existing record in grid.<br />
					11.	Click on "Print" button to print the grid records. It will print all fieldname records which are in grid.<br />
					12.	Click on "Export to Excel" button to export the records in excel sheet.<br />
					13.	User can search the record which is under grid fieldname; by enter "search value" in search field textbox and then click on search		icon.<br />
					14.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.<br />
					15.	User can move to next page to see further record by click on "Paging" icon.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Make Leave Set Mapping with Employee? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Employee Leave Set Mapping" module :- From "Leaves" go to "Employee Leave Set Mapping". It will show "Employee Leave Set Mapping" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/214.png" width="100%"></div><br /> 

					1.	Click on "Add"  icon on right side of the screen. It will open "Add Employee Leave Set Mapping" window to make mapping of leave set		with Employee of the company.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/216.png" width="100%"></div><br />
					 
					2.	Enter code of the employee in "Employee code" field textbox.<br />
					3.	Employee name populated in "Employee Name" field if the "Employee Code" is correct / exists.<br />
					4.	Select set of leaves from "Leave Set" list box. That values populated in "Leave Set" list box which are mapped with at least one leave		type in "Leave Set Mapping" module. <br />
					5.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					6.	Click on "Cancel" button to close the "Add Employee Leave Set Mapping" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/217.png" width="80%"></div><br /> 
					7.	If user doesnt know the "Employee Code", then user can also search their code by their employee name. Enter employee name in "Enter		Name" field textbox.<br />
					8.	Click on "Search" button to search the employee code. After click on Search button. Records populated in grid.<br />
					9.	User can select record by click on employee name or employee code. As user click on name / code "Employee Code" and "Employee Name"		fields filled for that employee.<br />
					10.	User can move to next page to see further record by click on "Paging" icon.<br />
					11.	Click on "Save" button, after select leave set from "Leave Set" list box to save the details. Details will be saved if all mandatory		fields are filled.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/218.png" width="100%"></div><br /> 
					12.	Click on "Edit" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/191.png" width="1%">) icon underneath Action in grid, it will open "Edit Employee Leave Set Mapping" window and user can update the existing record.<br />
					13.	Click on "Delete" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/190.png" width="1%"> ) icon underneath Action to delete existing record in grid.<br />
					14.	Click on "Print" button to print the grid records. It will print all fieldname records which are in grid.<br />
					15.	Click on "Export to Excel" button to export the records in excel sheet.<br />
					16.	Enter the data to be search in Search text box and click on Search button. Search will be work for all fields displaying in grid.<br />
					17.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.<br />
					18.	User can move to next page to see further record by click on "Paging" icon.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Make Leave Set Mapping with Employee using Advanced way? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Employee Leave Set Mapping (Advanced)" module. (From "Leaves" go to "Employee Leave Set Mapping (Advanced)". It will show "Employee Leave Set Mapping (Advanced)" module screen).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/219.png" width="100%"></div><br /> 

					1.	User can find the employee by enter employee code in "Employee Code" textbox.<br />
					2.	User can find the employee by enter employee name in "Employee name" textbox.<br />
					3.	User can find the employee by select gender from "Gender" list box.<br />
					4.	User can find the employee by select birth date from "Birth Date" list boxes, those employees whose birth dates in between "From" to		"To" dates that employees populated in grid. <br />
					5.	User can find employee by their "Department", "Designation", "Qualification", and "Role Name" under "Academic criteria.<br />
					6.	User can find employee by their "City", "State" and "Country" under "Address criteria.<br />
					7.	User can find employee by their Married status, Joining date, Teaching Emp. status, and Leaving date under "Misc criteria".<br />
					8.	Click on "Show List" button, after select any one or multiple criteria. As user click on "Show List" button, data populated in grid.<br />
					9.	Now user select leave set for "Leave Set" list box. That values populated in "Leave Set" list box which are mapped with at least one		leave type in "Leave Set Mapping" module.<br />
					10.	Click on "Save" button to save the details, details saved if all mandatory fields filled.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Make Authorizer of Employee Leave? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Employee Leave Authorizer" module :- From "Leaves" go to "Employee Leave Authorizer". It will show "Employee Leave Authorizer" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/220.png" width="100%"></div><br /> 	
					 
					1.	Click on "Add"  icon on right side of the screen. It will open "Add Employee Leave Authorizer" window to make authorizer of employee leave.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/221.png" width="100%"></div><br /> 
					 
					2.	Enter code of the employee in "Employee code" field textbox.<br />
					3.	Employee name populated in "Employee Name" field if the "Employee Code" is correct / exists.<br />
					4.	User select first authorizer from "First Authorizer" list box, first authorizer means if any employee apply leave than that leave		first approved by first authorizer.<br />
					5.	User select second authorizer from "Second Authorizer" list box, second authorizer means if any employees apply leave and leave			approved by first authorizer than after first approval that leave approved by second authorizer, if second authorizer approve the		leave than employee leave approved and if leave is not approved by first authorizer than leave is not comes to second authorizer for		approval.<br />
					6.	Select types of leaves from "Leave Type" list box. Values populated from "Leave Type Master" module. Only that leaves type values		populated in "Leave Type" list box, if employee name mapped with leave set in "Employee Leave Set Mapping" module and that leave set		mapped with leave type in "Leave Set Mapping" module.<br />
					7.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					8.	Click on "Cancel" button to close the "Add Employee Leave Authorizer" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/222.png" width="100%"></div><br /> 
					 
					9.	Click on "Edit" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/191.png" width="1%">) icon underneath Action in grid, it will open "Edit Employee Leave Authorizer" window and user can update the		existing record.<br />
					10.	Click on "Delete" (<img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/190.png" width="1%">) icon underneath Action to delete existing record in grid.<br />
					11.	Enter the data to be search in Search text box and click on Search button. Search will be work for all fields displaying in grid.<br />
					12.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.<br />
					13.	User can move to next page to see further record by click on "Paging" icon.<br />
					</div>
					</div>
					
					<div class="dhtmlgoodies_question">How to Make Authorizer of Employee Leave using Advance way? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Employee Leave Authorizer (Advanced)" module :- From "Leaves" go to "Employee Leave Authorizer (Advanced)". It will show "Employee Leave Authorizer (Advanced)" module screen.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/223.png" width="100%"></div><br /> 
					 
					1.	User can add rows by click on "Add One Row" link.<br />
					2.	Select Employee name under "Employee" list box, only those employees name populated in "Employee" list box, which are map with "leave		set" in "Employee Leave Set Mapping" module.<br />
					3.	User select first authorizer from "First Authorizer" list box, first authorizer means if any employee apply leave than that leave		first approved by first authorizer.<br />
					4.	User select second authorizer from "Second Authorizer" list box, second authorizer means if any employees apply leave and leave			approved by first authorizer than after first approval that leave approved by second authorizer, if second authorizer approve the		leave than employee leave approved and if leave is not approved by first authorizer than leave is not comes to second authorizer for		approval.<br />
					5.	Select types of leaves from "Leave Type" list box. Values populated in "Leave Type" list box from "Leave Type Master" module. Only		that leaves type values populated in "Leave Type" list box, if employee name mapped with leave set in "Employee Leave Set Mapping"		module and that leave set mapped with leave type in "Leave Set Mapping" module.<br />
					6.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					7.	User can delete the record by click on "Delete" icon. (Note: user can able to delete the record if the record is not linked in other		modules).<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Apply Leaves? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the"Apply Leaves" module :- From"Leaves" go to"Apply Leaves". It will show"Apply Leaves" module screen.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/224.png" width="100%"></div><br /> 

					1.	Click on"Add"  icon on right side of the screen. It will open"Add Employee Leave Authorizer" window to make authorizer of employee		leave.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/225.png" width="100%"></div><br /> 
					 
					2.	Enter code of the employee in "Employee code" field textbox.<br />
					3.	Employee name populated in "Employee Name" field if the"Employee Code" is correct / exists.<br />
					4.	Select types of leaves from "Leave Type" list box. Values populated in"Leave Type " list box from"Leave Type Master" module. Only that		leaves type values populated in "Leave Type" list box, if employee name mapped with leave set in"Employee Leave Set Mapping" module		and	that leave set mapped with leave type in "Leave Set Mapping" module.<br />
					5.	Select first date of leave from date picker ( ) in "Leave From" field.<br />
					6.	Select last date of leave from date picker ( ) in "Leave To" field.<br />
					7.	Enter reason of leave in "Reason" field textbox.<br />
					8.	Select date of apply leave from date picker ( ) in "Application Date" field.<br />
					9.	Click on"Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					10.	Click on"Cancel" button to close the "Add Employee Leave Authorizer" window.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/226.png" width="100%"></div><br />  
					11.	Click on "Edit" ( ) icon underneath Action in grid, it will open "Edit Apply Leave" window and user can update the existing record.<br />
					12.	Click on "Delete" ( ) icon underneath Action to delete existing record in grid.<br />
					13.	Enter the data to be search in Search text box and click on Search button. Search will be work for all fields displaying in grid.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/229.png" width="100%"></div><br /> 
					14.	User can sort the data in ascending or descending order by click on sorting link next to field name in grid.<br />
					15.	User can move to next page to see further record by click on "Paging" icon.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Authorize a Leave?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Authorize Employee Leaves" module :- From "Leaves" go to "Authorize Employee Leaves". It will show "Authorize Employee Leaves" module screen.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/227.png" width="100%"></div><br /> 
						Note: Login Form "First Authorizer" section.<br />
					1.	Click on Edit ( ) icon underneath Action in grid, it will open "Authorize Applied Leave" window.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/228.png" width="100%"></div><br /> 
					 

					2.	Code populated of that employee who apply for leave in "Employee Code" field.<br />
					3.	Name populated of that employee who apply for leave in "Employee Name" field.<br />
					4.	Leave type populated in "Leave Type" field for the applied leave.<br />
					5.	Leave records populated in "Leave Records" field, leave records show how much leaves can take an employee and how much he/she taken		till now.<br />
					6.	Start and end of apply leave populated in "Leave From" and"Leave To" fields respectively.<br />
					7.	Leaves applied for how much days populated in "Leave Applied For" field.<br />
					8.	Reason of leave populated in "Reason" field.<br />
					9.	Status of leave populated in "Leave Status" field,(E.g. :  after first approval status is "First approval", after second approval		status is  "Second approval" and if rejected then status is " Rejected").<br />
					10.	First authorizer select status from "Status" list box.(E.g."First Approval" means approved by him and"Rejected" means rejected by		him).<br />
					11.	First authorizer gives reason for approval or reject of leave in "Reason" field textbox.<br />
					12.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					13.	Click on"Cancel" button to close the "Authorize Applied Leave" window.<br />
					
						Note: Now login Form "Second Authorizer" section:-<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/229.png" width="100%"></div><br /> 
					14.	Status of first authorizer displayed in "Status" field under"First approval".<br />
					15.	Reason of first authorizer displayed in "Reason" field under"First approval".<br />
					16.	Second authorizer select status from "Status" list box.(E.g."Second Approval" means approved by him and"Rejected" means rejected by		him).<br />
					17.	Second authorizer gives reason for approval or reject of leave in "Reason" field textbox.<br />
					18.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					19.	Click on "Cancel" button to close the "Authorize Applied Leave" window.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Carry Forward the Employee Leaves?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Employee Leave Carry Forward" module :- From "Leaves" go to "Employee Leave Carry Forward". It will show "Employee Leave Carry Forward" module screen.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/230.png" width="100%"></div><br /> 

					1.	User can find the employee by enter employee code in "Employee Code" textbox.<br />
					2.	User can find the employee by enter employee name in "Employee name" textbox.<br />
					3.	User can find the employee by select gender from "Gender" list box.<br />
					4.	User can find the employee by select birth date from "Birth Date" list boxes, those employees whose birth dates in between "From" to		"To" dates that employees populated in grid. <br />
					5.	User can find employee by their "Department", "Designation", "Qualification", and "Role Name" under "Academic criteria.<br />
					6.	User can find employee by their "City", "State" and "Country" under "Address criteria.<br />
					7.	User can find employee by their Married status, Joining date, Teaching Emp. status, and Leaving date under "Misc criteria".<br />
					8.	Click on "Show List" button, after select any one or multiple criteria. As user click on "Show List" button, data populated in grid.<br />		After click on "Show List" button, records populated in grid.<br />
					9.	Data under "Allowed" columns means how much maximum leaves can take by an employee in a session.<br />
					10.	Data under "Taken" columns means how much leaves taken by an employee in a session.<br />
					11.	Data under "Balance" columns means how much leaves left of an employee in a session.<br />
					12.	User can select "Check box" under "Carry Forward" column. Balance of employee leave(s) of current session will be carry forward to		next session).<br />
					13.	Click on "Save" button to save the details. Details will be saved if all mandatory fields are filled.<br />
					14.	Click on "Print" button to print the grid records. It will print all fieldname records which are in grid.<br />
					15.	Click on "Export to Excel" button to export the records in excel sheet.<br />
					</div>
					</div>
					 <div class="headingtxt12"><strong> q. Fee Management</strong> </div>
					<div class="dhtmlgoodies_question">How to Add Bank? </div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Bank Master" module:" :- From "Setup Menu" go to "Fee Masters" and select "Bank Master" module.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/145.png" width="100%"></div><br />		   
					1. Click on Add   icon on right side of the screen. It will open "Add Bank" window to add bank details.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/146.png" width="100%"></div><br />
						   
					2. Enter bank name in "Bank Name" field text box. E.g. State Bank of India.
					3. Enter abbreviation in "Abbr." field text box. E.g. Bank Name is State Bank of India, so "Bank Abbr." can be "SBI".<br />
					4.  Click on "Save" button to save details. (User Details will be updated as all mandatory fields are filled).<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/147.png" width="100%"></div><br />
					 
					5.  Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/148.png" width="100%"></div><br />
					 
				    6. Click on Edit   icon underneath Action in grid. It will open "Edit Bank" window. User can change or modify details to existing records.<br />
				    7. Click on Delete   icon underneath Action in grid to delete existing records.<br />
					8. Click on "Print" button to print the Bank records.<br />
					9.  Click on "Export to Excel" button to export the records in Excel Sheet.<br />
					10. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					11.  User can view the records on next page by click on "Page Linking".<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/186.png" width="100%"></div><br />
				    12. Click on Add Branch   icon underneath "Add Branch" field in grid. It will display "Add Bank Branch" window to add bank branch.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/187.png" width="100%"></div><br />
					13. User cannot change this field. Value is coming from "Bank Master" Module.<br />
					14. Enter branch name in the "Branch Name" field text box. E.g. State Bank of India.<br />
					15. Enter abbreviation in the "Abbr." field text box. E.g. SBI<br />
					16. Enter account type in the "Account Type" field text box. E.g. Saving Account<br />
					17. Enter account number in the "Account Number" field text box.<br />
					18. Enter operator in "Operator" field text box.<br />
				    19. Click on "Save" button to save the details of Bank Branch. (Details will be updated as all mandatory   fields are filled).<br />
					20. Click on "Cancel" button if u doesnt want to save details.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Add Fund allocation?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Fund Allocation Master" module :- "From "Setup Menu" goes to "Fee Masters" and select "Fund Allocation Master" module.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/150.png" width="100%"></div><br />	
					1.  Click on Add   icon on the right side of the screen. It will display "Add Fee Fund Allocation" window to add fund details.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/151.png" width="100%"></div><br />	   
					2. Enter allocation entity in "Allocation Entity" field text box. E.g. Punjab Technical University.<br />
					3. Enter abbreviation in "Abbr." field text box. E.g. PTU.<br />
				    4. Click on "Save" button to save details. (User details will be updated as all mandatory fields are filled).<br />
					5. Click on "Cancel" Button if u doesnt want to save details.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/152.png" width="100%"></div><br />
						
					6.  Click on Edit   icon underneath Action in grid. It will display "Edit Fee Fund Allocation" window. User can change or modify details		to existing records.<br />
					7.  Click on Delete   icon underneath Action in grid to delete the existing records.<br />
					8.  Click on "Print" button to print the Records.<br />
					9.  Click on "Export to Excel" button to export the records in Excel Sheet.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/153.png" width="100%"></div><br />
						 
					10. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
						 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/154.png" width="100%"></div><br />
						 
					11. User can sort the data in ascending or descending order by click on sorting link next to field s name in grid.<br />
					12. User can view the records on next page by click on "Page Linking".<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Create Fee Cycle Classes?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Fee Cycle Classes" module :- "From "Setup Menu" goes to "Fee Masters" and select "Fee Cycle Classes" module.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/173.png" width="100%"></div><br />			
					1. Select fee cycle from "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
					2. Click on "Show List" button to show Classes related to selected "Fee Cycle".<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/174.png" width="100%"></div><br />

					3. Select the Class by clicking on the checkbox which user wants to assign to fee cycle. User cannot select the classes which are already		assigned to a fee cycle.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/175.png" width="100%"></div><br />	   

					4. Click on "Print" button to print the records.<br />
					5. Click on "Export to Excel" button to get the list of fee cycle classes in Excel sheet.<br />
					6. Click on "Save" button to save the selected records.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Add Fee Head?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					 Path to reach the "Fee Head" module :- "From "Setup Menu" goes to "Fee Masters" and select "Fee Head" module.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/167.png" width="100%"></div><br />	  			   
					1.  Click on Add   icon on the right side of the screen. It will display "Add Fee Head" window to add fee head details.<br />
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/168.png" width="100%"></div><br />	  
						
					2.  Enter fee head name in "Name" field text box. E.g. Fee Head1<br />
					3.  Enter abbreviation for fee head in "Abbr." field text box. E.g. FH1<br />
					4.  Select refundable Security using radio buttons.<br />
					5.  If yes is selected for 'Additional' field then Fee Head value will be applied as set in "Student Misc Charges" module.<br />
					6.  Enter display Order Value in "Display Order" field text box. E.g. 10<br />
					7.  Click on "Save" button to save the fee head details. (User details will be updated as all mandatory fields are filled).<br />
					8.  Click on "Cancel" button to close add fee head window.
					 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/169.png" width="100%"></div><br />	  	
					9.  Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.) <br />     
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/170.png" width="100%"></div><br />	  
						 
					10. Click on Edit   icon underneath Action in grid. It will display "Edit Fee Head" window. User can change or modify the existing			records.<br />
					11. Click on Delete   icon underneath Action in grid to delete the existing records. <br />
					12. Click on "Print" button to print the details of the records.<br />
					13. Click on "Export to Excel" button to export the records in Excel Sheet.<br />
					14. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					15. User can view the records on next page by click on "Page Linking".<br />
					</div>
					</div>
					
					<div class="dhtmlgoodies_question">How to Create Fee Head Values?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					Path to reach the "Fee Head Values" module: "From "Setup Menu" goes to "Fee Masters" and select "Fee   Head Values" module.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/171.png" width="100%"></div><br />			 
					1.	Select fee cycle from "Fee Cycle" drop down box. Fee Cycle value is coming from "Fee Cycle Master" module. <br />
					2.	Select Class from "Class" drop down box. Value is coming from "Create Class" module. Selected classes in "To" field are associated		with "Fee Cycle" field.<br />
					3.	Select Class in "To" field drop down box to which user wants to Copy Fee Head Values.<br />
					4.	Click on "Copy Fee Head values" button. It will copy Fee Head Values. <br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/172.png" width="100%"></div><br />	
						
					5.	Select fee cycle from "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
					6.	Select class from "Class" drop down box. Value is coming from "Create Class" module.<br />
					7.	Click on "Show List" button to enter the details of the "Fee Head Values".<br />
					8.	Select fee head name from "Fee Head" drop down box. Value is coming from "Fee Head" module.<br />
					9.	Select quota from "Quota" drop down box. Value is coming from "Quota Master" module.<br />
					10. Select value for applying quota from "Applicable To" drop down box.<br />
					11. Enter amount in the "Amount" field text box.<br />
					12. Click on "Delete" icon underneath Action in grid to delete the existing fee head values details.<br />
					13. Click on "Save" button to save the details as all mandatory fields are filled.<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Add Fee Cycle?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					 Path to reach the "Fee Cycle Master" module: "From "Setup Menu" goes to "Fee Masters" and select "Fee Cycle Master" module.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/155.png" width="100%"></div><br />					
					1.  Click on Add   icon on the right side of the screen. It will display "Add Fee Cycle" window to add fee cycle details.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/156.png" width="100%"></div><br />			 
					2.  Enter name in "Name" field text box. E.g. Aug09Dec10.<br />
					3.  Enter abbreviation in "Abbr." field text box. E.g. Aug09Dec10.<br />
					4.  Select Starting Date from Date Picker for "From" field.<br />
					5.  Select Ending Date from Date Picker for "To" field.<br />
					6.  Click on "Save" button to save the details. (User details will be updated as all mandatory fields are filled).<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/157.png" width="100%"></div><br />				 
					7.  Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.) <br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/158.png" width="100%"></div><br />	
						  
					8.  Click on Edit   icon underneath Action in grid. User can change or modify details to existing records.<br />
					9.  Click on "Print" button to print the existing records.<br />
					10. Click on Delete   icon underneath Action in grid to delete the existing records.<br />
					11. Click on "Export to Excel" button to export the records in Excel Sheet.<br />
					12. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
					13. User can view the records on next page by click on "Page Linking".<br />
					</div>
					</div>
					<div class="dhtmlgoodies_question">How to Add Fee Cycle Fine?</div>
					<div class="dhtmlgoodies_answer">
					<div>
					 Path to reach the "Fee Cycle Fine Master" module :- "From "Setup Menu" goes to "Fee Masters" and select "Fee Cycle Fine Master" module.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/159.png" width="100%"></div><br />
							
					1.	Click on Add   icon on the right side of the screen. It will display "Add Fee Cycle Fine" window to add fine details.<br />
						<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/160.png" width="100%"></div><br />   
					2.	Select fee cycle from "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
					3.	Select Starting date from Date Picker for "From" field.<br />
					4.	Select End date from Date Picker for "To" field.<br />
					5.	Enter fine amount in "Fine Amount" field text box. Fine Amount cannot be negative.<br />
					6.	Select fine type from "Fine Type" drop down box. E.g. Fixed<br />
					7.	Click on "Save" button to save the details. (User details will be updated as all mandatory fields are filled).<br />
					8.	Click on "Cancel" button to close the "Add Fine Amount" window.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/161.png" width="100%"></div><br />
						 
					9.	Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />      
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/162.png" width="100%"></div><br />
					 
				   10.	Click on Edit   icon underneath Action in grid. It will display "Edit Fee Cycle Fine" window. User can change or modify the existing			records.<br />
				   11.	Click on Delete   icon underneath Action in grid to delete the existing records. <br />
				   12.	Click on "Print" button to print the details of the records.<br />
				   13.	Click on "Export to Excel" button to export the records in Excel sheet.<br />
				   14.	User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				   15.	User can view the records on next page by click on "Page Linking".<br />
				   </div>
				   </div>
				<div class="dhtmlgoodies_question">How to Set Student Concession?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach the "Student Concession" module :- "From "Setup Menu" goes to "Fee Masters" and select "Student Concession" module.<br />
					<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/163.png" width="100%"></div><br />	   
				1. Enter roll no. in the "Roll No." field text box. E.g. E071093<br />
				2. Enter student name in the "Student Name" field text box.<br />
				3. Select gender from "Gender" drop down box.E.g. Female/Male.<br />
				4. Select birth date from drop down box for "Birth Date From" field. <br />
				5. Select birth date from drop down box for "To" field.<br />
				6. Select fee cycle from "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
				7. Click on "Academic Criteria: Expand" link to select value for Fee Receipt, Institute Reg. No., Attendance ,Degree, Branch, periodicity,		subject, group, university from the list menu. (It contains various information about Academic Criteria).<br />
				8. Click on "Address Criteria: Expand" link to select value for city, state and Country from the list menu. (It contains various information		about Address Criteria)<br />
				9. Click on "Misc Criteria: Expand" link to select value for Mgmt.Cat,Admn Date From and To, Hostel, Bus Stop, Bus route, Quota, Blood Group		from the list menu. (It contains various information about Misc Criteria). By clicking on "Show List" button it will display records such		as Name, Roll No, Univ.RNo, Head Value,   Concession, Type, Value, Balance, and Reason.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/330.bmp" width="100%"></div><br />
								   

				10. Select type from "Type" drop down box.<br />
				11. Enter concession value in "Value" field text box.<br />
				12. It will show value automatically for "Balance" field text box.<br />
				13. Enter reason for concession in "Reason" field text box.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Create Student Misc Charges?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				 Path to reach the "Student Misc Charges" module :- "From "Setup Menu" goes to "Fee Masters" and select "Student Misc Charges" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/176.png" width="100%"></div><br />		   
				1. Enter roll no. of the student in the "Roll No." field text box.<br />
				2. Enter name of student in the "Student Name" field text box.E.g Rajinder Kaur<br />
				3. Select gender of the student from "Gender" field drop down box.<br />
				4. Select date of birth from drop down box for "Birth Date From" field.<br />
				5. Select birth date from drop down box for "To" field.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/177.png" width="100%"></div><br />			 
				6. Click on "Academic Criteria: Expand" link to select values for Fee Receipt, Institute Reg.No., Attendance ,Degree, Branch, periodicity,		subject, group, university from the list menu. (It contains various information about Academic Criteria).  <br />
				7. Click on "Address Criteria: Expand" link to select values for City, State and Country from the list menu. (It contains various information		about Address Criteria).<br />
				8. Click on "Misc Criteria: Expand" link to select values for Mgmt.Cat, Admn Date From, Hostel, Bus Stop, Bus Route, Quota, Blood Group from		the list menu. (It contains various information about Misc Criteria).<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/181.png" width="100%"></div><br />				

								  
				9.  Select cycle of fee from "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
				10. Select quota from "Quota" drop down box. Value is coming from "Quota Master" module.<br />
				11. Select head of fee from "Fee Head" drop down box. Value is coming from "Fee Head" module.<br />
				12. Select value for "Applicable To" drop down box. (It contains Leet, NonLeet and Both).<br />
				13. Click on "Show List" button to show the records. It will display the student details such as Name, Roll No, Univ.No, reg.No, Class and Amount.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/326.bmp" width="100%"></div><br />	 



				14. Enter amount in "Amount" field text box.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to collect fees?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach the "Collect Fees" module: "From "Setup Menu" goes to "Fee" module and select "Collect Fees" module.<br />
									  
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/184.png" width="100%"></div><br />		  
						
			    1. Select fee receipt date from date picker for "Receipt Date" field.<br />
				2. Enter receipt number in "Receipt No." field text box. E.g. CIET/07/08/45<br />
				3. Enter roll number of student in "Reg/Roll No." field text box. E.g. E071093<br />
				4. User can directly search student by click on "Search Student" link.<br />
				5. Select fee type from "Fee Type" drop down box.<br />
				6. Select fee cycle from "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
				7. Select class from "Fee Class" drop down box. Value is coming from "Fee Classes" module.<br />
				8. User can enter remarks in "Print Remarks" field text box.<br />
				9. User can enter general remarks in "General Remarks" field text box.<br />
				10. Enter name of person from whom user is receiving fees in "Received From" field text box.<br />
				11. Enter fee amount underneath "Fees Details" in "Amount" field text box.<br />
				12. Enter applicable amount in "Appl.Amnt" field text box underneath Fee Details.<br />
				13. Enter amount to be paid in "Fee Amount Paid" field text box underneath "Amount Paid Details".<br />
				14. Enter amount of transport to be paid in "Transport Amt Paid" field text box underneath "Amount Paid    Details".<br />
				15. Enter amount of hostel to be paid in "Hostel Amt Paid" field text box underneath "Amount Paid Details".<br />
				16. Select bank from "Payable Fav Branch" drop down box. Value is coming from "Bank Master" module.<br />
				17. Enter cash amount in "Cash Amount" field text box underneath "Cash Payment Details".<br />
				18. Select type from "Type" field text box underneath "Cash Payment Details".<br />
				19. Enter fee receipt number in "Number" field text box underneath "Cash Payment Details".<br />
				20. Enter amount in "Amount" field text box underneath "Cash Payment Details".<br />
				21. Select bank name from "Bank" drop down box underneath "Cash Payment Details". Value is coming from "Bank Master" module.<br />
				22. Select date from date picker for "Date" field underneath "Cash Payment Details".<br />
				23. Click on Delete icon underneath "Cash Payment Details" to delete the records.<br />
				24. Click on "Save" button to save the records as all mandatory fields are filled.<br />
				25. Click on "Save/Print" button to save and print the records.<br />
				26. Click on "Print" button to print the records.
				<br /></div>
				</div>
				<div class="dhtmlgoodies_question">How to Import Fee?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach "Import Fee" module : - From "Fee" tab select "Import Fee" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/327.bmp" width="100%"></div><br />
					
				1)	Click on "here" link to download the Fee Uploading Format under the "Notes" caption. It will open an excel sheet with all fields that are		needed to upload the details of the fee.<br />
				2)	Click on "here" link to download the instructions and follow all mentioned steps strictly to create the fee format. <br />
				3)	Select the fee cycle from the "Fee Cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
				4)	Click on "Browse" button to attach the file for uploading.<br />
				5)	Click on "Upload" button to upload the excel sheet containing the fee details.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Create Fee Receipt Status?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach the "Fee Receipt Status" module: "From "Setup Menu" goes to "Fee" module and select "Fee Receipt Status" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/334.bmp" width="100%"></div><br />

				 
				 1. Select the degree from "Degree" drop down box. Value is coming from "Degree Master" module.<br />
				 2. Enter the student name in "Student name" field text box.<br />
				 3. Select starting date from date picker for "From Date" field.<br />
				 4. Select ending date from date picker for "To Date" field.<br />
				 5. Enter starting amount in "Amount Paid From" field text box.<br />
				 6. Enter ending amount in "Amount Paid To" field text box.<br />
				 7. Select the batch from "Batch" drop down box. Value is coming from "Batch Master" module.<br />
				 8. Enter the roll no in "Student Roll no" field text box.<br />
				 9. Select the status from "Instrumental status" drop down box.<br />
				10. Select the period of study from "Study period" drop down box. Value is coming from "Periodicity Master" module.<br />
				11. Select fee cycle from "Fee cycle" drop down box. Value is coming from "Fee Cycle Master" module.<br />
				12. Select the receipt status from "Receipt status" drop down box. Value is coming from "Collect Fees" module.<br />
				13. Click on "Show List" button it will display all fee receipt records.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/335.bmp" width="100%"></div><br />					 
				14. Select instrument status from "Instrument" drop down box.<br />
				15. Select receipt status from "Receipt" drop down box. Value is coming from "Collect Fees" module.<br />
				16. Click on "Save" button to save the details.<br />
				17. Click on "Print" button to print the records.<br />
				18. Click on "Export to Excel" button to export the records in Excel Sheet.<br />
				</div>
				</div>
				<div class="headingtxt12"><strong>r.Fleet Management </strong> </div>
				
				<div class="dhtmlgoodies_question">How to Add Vehicle Type Details?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach the "Vehicle Type Master" module :- From "Setup Menu" go to "Fleet Management" module and then select "Vehicle Type Master" master. It will show "Vehicle Type Master" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/349.png" width="100%"></div><br />
				 

				1.	Click on Add icon   on right side of the screen. It will open "Add Vehicle Type" window to add Vehicle Type.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/350.png" width="100%"></div><br />
				2.	Enter the vehicle type in "Vehicle Type" field text box. For e.g. "Vehicle Type" field value can be "Bus", "Car" etc.<br />
				3.	Enter the Main Tyres in "Main Tyres" field text box. For e.g. "Main Tyres" field value can be "8" or "6" etc.<br />
				4.	Enter the Spare Tyres in "Spare Tyres" field text box. For e.g. "Spare Tyres" field value can be "2" or "4" etc.<br />
				5.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				6.	Click on "Cancel" button to close the "Add Vehicle Type" window without saving the Data.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/351.png" width="100%"></div><br />
				7.	Enter the valid data in the "Search" field and click on "Search" icon   to search that data in grid. Search will work for all fields displayed in grid. E.g. enter Vehicle Type in search text box field to search that Vehicle Type in grid. <br />
				8.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				9.	Click on "Edit" icon   underneath "Action" in grid to modify or update the existing data.<br />
				10.	Click on "Delete" icon   underneath "Action" in grid to delete existing data in grid.<br />
				11.	Click on "Print" button to print the list of Vehicle Type. Print window will open in which entire list will be displayed.<br />
				12.	Click on "Export to Excel" button to get the list of Vehicle Types in excel file.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Insurance Company Details?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach the "Insurance Company Master" module :- From "Setup Menu" goes to "Fleet Management" module and then select "Insurance Company Master" module. It will show "Insurance Company Master" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/352.png" width="100%"></div><br /> 
				1.	Click on Add icon   on right side of the screen. It will open "Add Insurance" window to add Insurance Company details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/353.png" width="100%"></div><br />
				2.	Enter the Company Name in "Company Name" field text box. For e.g. "Company Name" field value can be "Bajaj" etc.<br />
				3.	Enter the details of the company in "Detail" field text box for the company entered in the "Company Name" field.<br />
				4.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				5.	Click on "Cancel" button to close the "Add Insurance" window without saving the Data.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/340.png" width="100%"></div><br /> 

				6.	Enter the valid data in the "Search" field and click on "Search" icon   to search that data in grid. Search will work for all fields displayed in grid. E.g. enter Company Name in search text box field to search that Company Name in grid. <br />
				7.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				8.	Click on "Edit" icon   underneath "Action" in grid to modify or update the existing data.<br />
				9.	Click on "Delete" icon   underneath "Action" in grid to delete existing data in grid.<br />
				10.	Click on "Print" button to print the list of Company names. Print window will open in which entire list will be displayed.<br />
				11.	Click on "Export to Excel" button to get the list of Company names in excel file.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Details?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				Path to reach the "Vehicle Master" module :- From "Setup Menu" go to "Fleet Management" module and the select "Vehicle Master" module. It will show "Vehicle Master" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/341.png" width="100%"></div><br />
				1.	Click on Add icon   on right side of the screen. It will open "Add Vehicle Details" window to add Vehicle details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/342.png" width="100%"></div><br />
				2.	Select the "Vehicle Type" from drop down list box in "Select Vehicle Type" field, which is populating from "Vehicle Type Master" module.<br />
				3.	"Vehicle Info." tab is selected by default. It contains the information of the vehicle.<br />
				4.	Enter the Vehicle Name in "Vehicle Name" field text box. For e.g. "Vehicle Name" field value can be "Alto" etc.<br />
				5.	Enter the Registration No. in "Registration No." field text box. For e.g. "Registration No." field value can be "pb rt rt rwet"			etc."Registration No." cannot have duplicate values.<br />	
				6.	Enter the Model No. in "Model No." field text box. For e.g. "Model No." field value can be "2010RX" etc.<br />
				7.	Select "Date" by clicking on date picker   in "Purchase Date" field on which the Vehicle was purchased.<br />	
				8.	Enter the Seating capacity in "Seating Capacity" field text box. . For e.g. "Seating Capacity" field value can be "6" etc. "Seating			Capacity" field value can only be numeric.<br />	
				9.	Enter the Fuel capacity in "Fuel Capacity" field text box. . For e.g. "Fuel Capacity" field value can be "100" etc. "Fuel Capacity" field		value can only be numeric.<br />	
				10.	Select the Vehicle Manufacturing Year from drop down list box in "Manufacturing Year" field.<br />	
				11.	Enter the place where the vehicle was registered in "Registered At" field text box.<br />	
				12.	Select "Date" by clicking on date picker   in "Passenger Tax Valid Till" field until when the passenger tax is valid.<br />	
				13.	Select "Date" by clicking on date picker   in "Passing Valid Till" field until when the passing of the vehicle is valid.<br />	
				14.	Click on the "Browse" button of the "Vehicle Photo" field to upload a photo of the vehicle.<br />	
				15.	Enter the Engine Number of the vehicle in "Engine No." field text box. For e.g. "Engine No." field value can be "475856" etc."Engine No."		cannot have duplicate values.<br />	
				16.	Enter the Chassis Number of the vehicle chassis in "Chassis No." field text box. For e.g. "Chassis No." field value can be "476"			etc."Chassis No." cannot have duplicate values.<br />	
				17.	Enter the name of the vehicle Body maker in the "Body Maker" field text box.<br />	
				18.	Enter the cost of the vehicle chassis in "Chassis Cost" field text box. For e.g. "Chassis Cost" field value can be "4746" etc."Chassis		Cost" can have only numeric values.<br />	
				19.	Select "Date" by clicking on date picker   in "Body Purchase Date" field on which the Vehicle body was purchased.<br />	
				20.	Enter the Body cost of the vehicle in "Body Cost" field text box. For e.g. "Body Cost" field value can be "46758" etc. "Body Cost" field		value can only be numeric.<br />	
				21.	Select "Date" by clicking on date picker in "Put on Road" field on which the Vehicle was put on road.<br />	
				22.	Select "Date" by clicking on date picker in "Regn. No. Valid Till" field until when the vehicle registration number is valid.<br />	
				23.	Select "Date" by clicking on date picker in "Road Tax Valid Till" field until when the vehicle road tax is valid.<br />	
				24.	Select "Date" by clicking on date picker in "Pollution Tax Valid Till" field until when the vehicle pollution tax is valid.<br />	
				25.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />	
				26.	Click on "Cancel" button to close the "Add Vehicle Details" window without saving the Data.<br />	
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/343.png" width="100%"></div><br />
				 

				27.	Select "Insurance Info." Tab. It contains the information of the vehicle insurance.<br />	
				28.	Select "Date" by clicking on date picker   in "Ins. Date" field on which the Vehicle was insured.<br />	
				29.	Select the Insurance Company from drop down list box in "Ins. Company" field, which is populating from "Insurance Company Master" module.<br />	
				30.	Enter the amount of value for which the vehicle is insured in "Value Insured" field text box.<br />	
				31.	Enter No Claim Bonus value in "NCB" field text box if no claim has been taken in the insurance period.<br />	
				32.	Enter the Branch name of the insurance company in "Branch Name" field text box.<br />	
				33.	Enter the Payment description in the "Payment Desc." field text box.<br />	
				34.	Select "Date" by clicking on date picker   in "Ins. Due Date" field upto which the Vehicle is been insured.<br />	
				35.	Enter the Policy number in "Policy No." field text box, this value must be unique.<br />	
				36.	Enter the value for insurance premium in "Ins. Premium" field text box.<br />	
				37.	Select the payment mode for the insurance from drop down list box in "Payment Mode". <br />	
				38.	Enter the name of the agent for the insurance in "Agent Name" field text box.<br />	
				39.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />	
				40.	Click on "Cancel" button to close the "Add Vehicle Details" window without saving the Data.<br />	
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/344.png" width="100%"></div><br />

				 
				41.	Select "Tyres'' Info." Tab. It contains the information of the vehicle Tyres.<br />	
				42.	Enter the Tyre number under the "Details of Tyres". Every Tyre Number is unique.<br />	
				43.	Enter the Model number of the tyre under "Tyres Model No." field text box.<br />	
				44.	Enter the manufacturing company of the tyre being used for the vehicle under the "Tyres Mfg. Company" field text box.<br />	
				45.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />	
				46.	Click on "Cancel" button to close the "Add Vehicle Details" window without saving the Data.<br />	
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/347.png" width="100%"></div><br />
				 

				47.	Select "Battery Info." Tab. It contains the information of the batteries used in vehicle.<br />	
				48.	Enter the Battery No. of the battery being used in the vehicle in "Battery No." field text box. Every Battery number is unique.<br />	
				49.	Enter the name of the Battery maker in"Battery Make" field text box.<br />	
				50.	Select "Date" by clicking on date picker   in "Warranty Till" field upto which the warranty of the battery exist.<br />	
				51.	Enter the optional Battery No. of the battery being used in the vehicle in "Optional Battery No." field text box. Every Battery number is unique.<br />	
				52.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />	
				53.	Click on "Cancel" button to close the "Add Vehicle Details" window without saving the Data.<br />	
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/348.png" width="100%"></div><br />
				 

				54.	Select "Service Info." Tab. It contains the information of the free services of the vehicle.<br />	
				55.	Enter a numeric value for the number of services in the "No. of free Services" field text box. For e.g. "No. of free Services" filed value		can be "1", "2" etc.<br />	
				56.	Click on "Show List" button to list down the details of the free services for the vehicle.<br />	
				57.	Select "Date" by clicking on date picker   in "Due Date" field when the servicing of the vehicle is to be done.<br />	
				58.	Enter a numeric value for the KM Run of the vehicle in the "KM Run" field text box. For e.g. "KM Run" field value can be "134", "2434"		etc.<br />	
				59.	Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />	
				60.	Click on "Cancel" button to close the "Add Vehicle Details" window without saving the Data.<br />	
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/346.png" width="100%"></div><br />
				 
				61.	Enter the valid data in the "Search" field and click on "Search" icon   to search that data in grid. Search will work for all fields		displayed in grid. E.g. enter Registration No. in search text box field to search that Registration Number.<br />	
				62.	User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />	
				63.	Click on "Edit" icon   underneath "Action" in grid to modify or update the existing data.<br />	
				64.	Click on "Delete" icon   underneath "Action" in grid to delete existing data in grid.<br />	
				65.	Click on "Print" button to print the list of Vehicle Type. Print window will open in which entire list will be displayed.<br />	
				66.	Click on "Export to Excel" button to get the list of Vehicle Types in excel file.<br />	
				67.	Click on the "Paging Link" to view the vehicle details on the next page.<br />	
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Fuel Details?</div>
				<div class="dhtmlgoodies_answer">
				<div>
				
				Path to reach "Fuel Master" module :- From "Setup Menu" go to "Fleet Management" and then select "Fuel Master" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/354.png" width="100%"></div><br />
				 
				1)	Click on Add  icon on right side of the screen. It will show "Add Fuel Uses" window to add fuel usage by the vehicle.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/356.png" width="100%"></div><br />
				 
				2)	Select type of vehicle from "Vehicle Type" field list box. This is a mandatory field and the data is populated from the "Vehicle Type		Master" module. E.g. Bus, Cab, etc.<br />
				3)	Select registration number of vehicle from "Registration No." field list box. This is a mandatory field and the data is populated from the		"Vehicle Master" module.<br />
				4)	Select name of the staff from "Staff Name" field list box. This is a mandatory field and the data is populated from the "Transport Staff		Master" module.<br />
				5)	Select the date from the "Date" mandatory field. By default, present date is selected.<br />
				6)	The last mileage of the vehicle will be displayed in the "Last Mileage" field textbox. It will display zero if the vehicle is new.<br />
				7)	Enter the current mileage of the vehicle in the "Current Mileage" mandatory field textbox. The current mileage is to be entered according		to the fuel filled last time and kilometers run by the vehicle on that fuel.<br />
				8)	Enter the amount of fuel filled in "Litres" mandatory field textbox. E.g. 10,11,12,etc<br />
				9)	Enter the rate of fuel in the "Rate" field textbox. E.g. 33.54 per litre.<br />
			   10)	The amount will be displayed in the "Amount" field textbox automatically. The result from the product of amount entered in the "Litres"			field and the amount entered in the "Rate" field will be displayed. E.g. 335.4(Rs.) calculated from 10 * 33.54.<br />
			   11)	Click on "Save" button to save the fuel uses details of the vehicle.<br />
			   12)	Click on "Cancel" button to close the "Add Fuel Uses" window without saving the fuel uses detail.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/355.png" width="100%"></div><br />
				 
			   13)	Enter the valid data in the "Search" field textbox and click on "Search" button to search that data in the grid. Search will work for all			fields displaying in grid. E.g. enter name in search text box field to search that name in grid.<br />
			   14)	Click on "Edit"   icon in the "Action" field. An "Edit Fuel Uses" window will be opened to change the fuel uses details.<br />
			   15)	Click on "Delete"   icon underneath "Action" field. It will delete existing data in the grid. Note that, only last saved entry for the			fuel used in the vehicle can be deleted.<br />
			   16)	Click on the paging links to browse the list of "Fuel Uses details" in the grid.<br />
			   17)	Click on the "Print" button to print the fuel uses details.<br />
			   18)	Click on the "Export to Excel" button to get the list of "Fuel Uses details" in the excel file.<br />
			   </div>
			   </div>
			   <div class="dhtmlgoodies_question">How to Purchase/Replace Vehicle Tyre?</div>
			   <div class="dhtmlgoodies_answer">
			   <div>
				Path to reach the "Purchase/Replace Tyre" module :- From "Setup Menu" goes to "Fleet Management" and select "Purchase/Replace Vehicle Tyre" module. It will show "Purchase/Replace Tyre" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/357.png" width="100%"></div><br />
					 
				1. Select vehicle from "Vehicle Type" field list box, which is populating from "Vehicle Master" module.<br />
				2. Select registration number from "Registration No." field list box, which is populating from "Vehicle Master" module.<br />
				3. Click on "Show List" button to see detail of tyres of selected vehicle.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/358.png" width="100%"></div><br />	 
				4. Click on "Edit" icon   to edit tyre detail of vehicle.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/359.png" width="100%"></div><br />
				5. User can edit vehicle tyre details under "Edit Vehicle Tyre" caption. E.g. tyre number, manufacturer, model number etc.<br />
				6. Registration number can not be edit in "Edit Vehicle Tyre" window.<br />
				7. Select "Main" or "Spare" radio button from "Used as" field. E.g. If user is editing for main tyre then user can select "Spare" radio button    to swap tyre from main to spare tyre and select the vehicle from list box field below "Main" radio button.<br />
				8. Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/360.png" width="100%"></div><br />	 

				9. Click on "Purchase" icon   to purchase new tyre for selected tyre.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/361.png" width="100%"></div><br />	 

				10. Enter the details of new tyre in text box fields under "Purchase Vehicle Tyre" caption. E.g. tyre number, manufacturer, model number etc.<br />
				11. Registration number can not be changed in "Edit Vehicle Tyre" window.<br />
				12. Select "Damage" or "To Store " radio button from "Used as" field. Select "Damage" radio button to damage old tyre or select "To Store"		radio button to store tyre in stock.<br />
				13. Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/362.png" width="100%"></div><br />	 
				14. Click on "Replace" icon   to replace selected tyre from stock.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/363.png" width="100%"></div><br />	 
				15. Select tyre from "Stock Tyre" field list box. E.g. user can select tyre from "Stock Tyre"field to replace tyre with selected tyre from the		grid.<br />
				16. Enter vehicle reading in "Reading"field text box.<br />
				17. Select replacement date of tyre from "Replacement Date" field.<br />
				18. Enter replacement reason of tyre in "Replacement Reason" field text box.<br />
				19. Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/364.png" width="100%"></div><br />	 
				20. User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				21. Click on "Paging" link to browse all records in grid.<br />
				22. Click on "Print" button to print the list of "Purchase/Replace Tyre Report". Print window will open in which entire list will be displayed.<br />
				23. Click on "Export to Excel" button to get the list of "Purchase/Replace Tyre Report" in excel file.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Tax?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach the "Tax Master" module :- From "Setup Menu" go to "Fleet Management" and select "Tax Master" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/421.png" width="100%"></div><br />		   
				1.  Click on Add icon on right side of the screen. It will open "Add Tax" window to add tax details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/422.png" width="100%"></div><br />
					   
				2.  Enter tax name in "Tax Name" field text box. E.g. Road Tax, Pollution Tax etc.<br />
				3.  Click on "Save" button to save details. (User Details will be updated as all mandatory fields are filled).<br />
				4.  Click on "Cancel" button to close add tax window.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/423.png" width="100%"></div><br />
				 
				5.  Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/424.png" width="100%"></div><br />
				 
				6.  Click on Edit   icon underneath Action in grid. It will open "Edit Tax" window. User can change or modify details to existing records.<br />
				7.  Click on Delete   icon underneath Action in grid to delete existing records.<br />
				8.  User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				9.  User can view the records on next page by click on "Page Linking".<br />
			    10. Click on "Print" button to print the tax records.<br />
			    11. Click on "Export to Excel" button to export the records in Excel Sheet.
<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question"> How to Add States Tax?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>

				Path to reach the "States Tax" module :- From "Setup Menu" go to "Fleet Management" and select "States Tax" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/417.png" width="100%"></div><br />		  
			    1. Click on Add   icon on right side of the screen. It will open "Add States Tax" window to add states tax details.<br />
	`			<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/418.png" width="100%"></div><br />
				2. Select vehicle type from "Select Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module. E.g Bus,Truck,Cab etc.<br />
				3. Select registration number from "Registration No." drop down box. Value is also coming from "Vehicle Type Master" module according to         "Vehicle type".<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/414.png" width="100%"></div><br />
				4.  Click on "Add Rows" to add details of "States Tax".<br />
				5.  Select state from "State" drop own box.<br />
				6.  Select tax type from "Tax" drop down box. Value is coming from "Tax Master" module.<br />
				7.  Enter amount in the "Amount" field text box.<br />
				8.  Select payment date from date picker for"Date of Payment" field.<br />
				9.  Select due date of tax from date picker for "Due Date" field.<br />
				10. Click on "Delete" icon underneath Action to delete the record.<br />
				11. Click on "Save" button to save the tax details as all manadatory fields are filled.<br />
				12. Click on "cancel" button to close the "Add States Tax" window.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/415.png" width="100%"></div><br />
						   
				13.  Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/416.png" width="100%"></div><br />
			    14.  Click on Edit icon underneath Action in grid. It will open "Edit States Tax" window. User can change or modify details to existing		 records.<br />
				15.  Click on Delete   icon underneath Action in grid to delete existing records.<br />
				16.  User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				17.  User can view the records on next page by click on "Page Linking".<br />
				18.  Click on "Print" button to print the States Tax records.<br />
				19.  Click on "Export to Excel" button to export the records in Excel Sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Purchased Store Tyre Detail?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach the "Purchased Store Tyre Detail" module. (From "Setup Menu" go to "Fleet Management" and select "Purchased Store Tyre Detail" module. It will show "Purchased Store Tyre Detail" screen).<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/365.png" width="100%"></div><br />
				 
				1. Click on Add icon on right side of the screen. It will open "Add Store Tyre" window to add store tyre details.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/366.png" width="100%"></div><br />
				2. Enter the details of store tyre in text box fields under "Add Store Tyre" caption. E.g. tyre number, manufacturer, model number etc.<br />
				3. Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/367.png" width="100%"></div><br />

				 
				4. Enter the valid data and click on "Search" icon   to search that data in the grid. Search will work for all fields displaying in grid. E.g.		enter tyre number in search text box field to search that tyre number in grid.<br />
				5. User can "Sort" the data in ascending or descending order by click on sorting link next to field s name in grid.<br />
				6. Click on "Edit" icon   to edit tyre detail of vehicle.<br />
				7. Click on "Delete" icon underneath "Action" in grid to delete existing data in the grid.<br />
				8. Click on "Print" button to print the list of "Purchased Store Tyre Report". Print window will open in which entire list will be displayed.<br />
				9. Click on "Export to Excel" button to get the list of "Purchased Store Tyre" in excel file.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Retreading the Tyre?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Tyre Retreading" module. :- From "Setup Menu" go to "Fleet Management" and select "Tyre Retreading" module. It will show "Tyre Retreading" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/368.png" width="100%"></div><br />
				 
				1. Click on Add icon on right side of the screen. It will open "Tyre Retreading" window to add tyre retreading details.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/369.png" width="100%"></div><br />
				2. Select vehicle type from "Select Vehicle Type" field list box. E.g. user can select bus, small vehicles, medium vehicle etc.<br />
				3. Select registration number from "Registration No." field list box for the vehicle which was selected in "Select Vehicle Type" field. E.g.		if user had selected bus then user will see registration number of bus only.<br />
				4. Select tyre number from "Tyre No." field list box. E.g. user can select tyre number of that selected registration number vehicle which was		selected in "Registration No." field.<br />
				5. Enter workshop name in "Workshop Name" field text box. <br />
				6. Enter retreading amount of tyre in "Retreading Amount" field text box. <br />
				7. Enter reading of the vehicle in "Reading" field text box.<br />
				8. Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/370.png" width="100%"></div><br />
				 
				9. Enter the valid data and click on "Search" icon   to search that data in the grid. Search will work for all fields displaying in grid. E.g.		enter tyre number in search text box field to search that tyre number in grid.<br />
				10. User can "Sort" the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				11. Click on "Edit" icon   to edit tyre retreading details of vehicle.<br />
				12. Click on "Delete" icon underneath "Action" in grid to delete existing data in the grid.<br />
				13. Click on "Paging" link to browse all records in the grid.<br />
				14. Click on "Print" button to print the list of  tyre retreading details. Print window will open in which entire list will be displayed.<br />
				15. Click on "Export to Excel" button to get the list of "tyre retreading details" in excel file.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Insurance? </div>
			    <div class="dhtmlgoodies_answer">
			    <div>
 
				Path to reach the "Vehicle Insurance" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Insurance" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/371.png" width="100%"></div><br />		   
				1.	Click on Add   icon on right side of the screen. It will open "Add Vehicle Insurance" window to add vehicle insurance details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/374.png" width="100%"></div><br />
			    2.	Select vehicle type in "Select Vehicle Type" drop down. Value is coming from "Vehicle Master" module.<br />
				3.	Select registration number in "Registration No." drop down box. Value is coming from "Vehicle Master" module. <br />
				4.	Select date from date picker for "Insurance Date" field.<br />
				5.	Select date from date picker for "Insurance Due Date" field.<br />
				6.	Select insurance company from "Insurance Company" drop down box. Value is coming from "Insurance Company Master" module.<br />
				7.	Enter policy number in the "Policy No." field text box. Policy number should be unique.<br />
				8.	Enter insured value in "Value Insured" field text box. E.g. 50000.<br />
				9.	Enter insurance premium in "Insurance Premium" field text box.<br />
			    10. Enter No Claim Bonus in "NCB" field text box.<br />
				11. Select payment mode from "Payment Mode" drop down box. E.g. Cash, Cheque etc.<br />
				12. Enter branch name in "Branch Name" field text box.<br />
				13. Enter name of agent in "Agent Name" field text box.<br />
				14. Enter description of payment in "Payment Description" field text box.<br />
				15. Click on "Save" button to save the details as all mandatory fields are filled.<br />
				16. Click on "Cancel" button to close the "Add Vehicle Insurance" window.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/373.png" width="100%"></div><br /> 
				17. Enter the data to be search in Search text box and click on Search button. (Search will be work for all    fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/372.png" width="100%"></div><br />
				 
				 
				18. Click on Delete icon underneath Action in grid to delete existing records.<br />
				19. Click on Edit icon underneath Action in grid. It will open "Edit Vehicle Insurance" window. User can change or modify details to			existing records.<br />
				20. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				21. User can view the records on next page by click on "Page Linking".<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">	How to Add Vehicle Insurance Claim?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
			 
				Path to reach the "Vehicle Insurance Claim" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Insurance Claim" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/375.png" width="100%"></div><br />		   
				1. Click on Add   icon on right side of the screen. It will open "Add Vehicle Insurance Claim" window to add vehicle insurance claim details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/378.png" width="100%"></div><br />
					   

				2. Select vehicle type from "Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module.<br />
				3. Select registration number from "Registration No." drop down box. Value is coming from "Vehicle Master" module. <br />
				4. Select date of claim from date picker for "Claim Date" field.<br />
				5. Enter Amount in "Claim Lodged for Amount" field text box e.g. 500000<br />
				6. Enter amount received in "Claim Amount Received" field text box. E.g.400000 <br />
				7. Enter expenses in "Total Expenses" field text box.<br />
				8. Enter self expenses in "Self expenses borne by us" field text box.<br />
				9. Enter NCB date of claim in "NCB as on Date of Claim" field text box. Value is coming from"  Vehicle Master" module.<br />
				10.Select date from date picker for "Date of Settlement" field.    <br />  
				11.Click on "Save" button to save the details as all mandatory fields are filled.<br />
				12.Click on "Cancel" button to close the "Add Vehicle Insurance Claim" window.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/377.png" width="100%"></div><br />				   

				13. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/376.png" width="100%"></div><br />
				 
				 
				14.Click on Delete icon underneath Action in grid to delete existing records.<br />
				15.Click on Edit icon underneath Action in grid. It will open "Edit Vehicle Insurance Claim" window. User can change or modify details to existing records.<br />
				16.User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				17.User can view the records on next page by click on "Page Linking".<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Accident? </div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach the "Vehicle Accident" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Accident" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/379.png" width="100%"></div><br />		   
				1. Click on Add   icon on right side of the screen. It will open "Add Vehicle Accident" window to add vehicle accident details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/380.png" width="100%"></div><br />
			    2. Select vehicle type in "Select Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module.<br />
				3. Select registration number in "Registration No." drop down box. Value is coming from "Vehicle Master" module. <br />
				4. Select transport staff from "Transport Staff" drop down box. Value is coming from "Transport Staff Master" module.<br />
				5. Select bus route from "Bus Route" drop down box. Value is coming from "Vehicle Route Master"   module.<br />
				6. Select date from date picker for "Date" field.<br />
				7. Enter remarks in "Remarks" field text box.<br />
				8. Click on "Save" button to save the details as all mandatory fields are filled.<br />
				9. Click on "Cancel" button to close the "Add Vehicle Accident" window.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/381.png" width="100%"></div><br />   
				10. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/382.png" width="100%"></div><br />
				11. Click on Delete icon underneath Action in grid to delete existing records.<br />
				12. Click on Edit icon underneath Action in grid. It will open "Edit Vehicle Accident" window. User can change or modify details to existing records.<br />
				13. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				14. User can view the records on next page by click on "Page Linking".<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Battery?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 
				Path to reach the "Vehicle Battery" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Battery" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/383.png" width="100%"></div><br />		   
				1. Click on Add   icon on right side of the screen. It will open "Add Vehicle Battery" window to add vehicle battery details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/384.png" width="100%"></div><br />
					   

				2.	Select vehicle type in "Select Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module.<br />
				3.	Select registration number in "Registration No." drop down box. Value is coming from "Vehicle Master" module. <br />
				4.	Select replaced battery number from "Replaced Battery No." drop down box. Value is coming from "Vehicle Master" module.<br />
				5.	Enter new battery number in "New Battery No." field text box. Value is coming from "Vehicle Master" module.<br />
				6.	Enter new battery make in "New Battery Make" field text box.<br />
				7.	Select date of warranty from date picker for "Warranty Till" field.<br />
				8.	Enter reading of meter in "Meter Reading" field text box.<br />
				9.	Enter cost of battery in "Cost" field text box.<br />
				10. Select date of installation from date picker for "Installation Date" field.<br />
				11. Click on "Save" button to save the details as all mandatory fields are filled.<br />
				12. Click on "Cancel" button to close the "Add Vehicle Battery" window.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/385.png" width="100%"></div><br />   
				13. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/386.png" width="100%"></div><br />
				14. Click on Delete icon underneath Action in grid to delete existing records.<br />
				15. Click on Edit icon underneath Action in grid. It will open "Edit Vehicle Battery" window. User can change or modify details to existing records.<br />
				16. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				17. User can view the records on next page by click on "Page Linking".<br />
				18. Click on "Print" button to print the existing records.<br />
				19. Click on "Export to Excel" button to export the records on Excel Sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Tax?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach the "Vehicle Tax" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Tax" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/387.png" width="100%"></div><br />		   
				1. Click on Add   icon on right side of the screen. It will open "Add Vehicle Tax" window to add vehicle tax details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/388.png" width="100%"></div><br />
				2. Select vehicle type from "Select Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module.<br />
				3. Select registration number from "Vehicle Registration No." drop down box. Value is coming from "Vehicle Master" module. <br />
				4. Select date from date picker for "Regn.No. Valid Till" field.<br />
				5. Select date from date picker for "Passenger Tax Valid till" field.<br />
				6. Select date from ate picker for "Road Tax Valid Till" field.<br />
				7. Select date from date picker for "Pollution Check Valid Till" field.<br />
				8. Select date from date picker for "Passing Valid Till" field.<br />
				9. Click on "Save" button to save the details as all mandatory fields are filled.<br />
				10. Click on "Cancel" button to close the "Add Vehicle Tax" window.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/389.png" width="100%"></div><br />  

				11. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/390.png" width="100%"></div><br />

				12. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				13.  User can view the records on next page by click on "Page Linking".<br />
				14. Click on "Print" button to print the existing records.<br />
				15. Click on "Export to Excel" button to export the records on Excel sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Route details? </div>
			    <div class="dhtmlgoodies_answer">
			    <div>
	
				Path to reach the "Vehicle Route Master" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Route Master" module".
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/391.png" width="100%"></div><br />		   
				1. Click on Add icon on right side of the screen. It will open "Add Vehicle Route " window to add Vehicle Route details.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/392.png" width="100%"></div><br />
					   

				2. Enter vehicle route name in "Vehicle Route Name" field text box. E.g. Rajpura.<br />
				3. Enter vehicle route code in "Vehicle Route Code" field text box. E.g. 10A<br />
				4. Enter vehicle route charges in "Vehicle Route Charges" field text box.<br />
				5. Click on "Save" button to save the details as all mandatory fields are filled.<br />
				6. Click on "Cancel" button to close the "Add Vehicle Route" window.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/393.png" width="100%"></div><br />   

				7. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/394.png" width="100%"></div><br />
				8. Click on Delete icon underneath Action in grid to delete existing records.<br />
				9. Click on Edit icon underneath Action in grid. It will open "Edit Vehicle Route" window. User can change or modify details to existing records.<br />

				10. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				11. User can view the records on next page by click on "Page Linking".<br />
				12. Click on "Print" button to print the existing records.<br />
				13. Click on "Export to Excel" button to export the records on Excel sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Vehicle Stop details?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 
				Path to reach the "Vehicle Stop Master" module :- From "Setup Menu" go to "Fleet Management" and select "Vehicle Stop Master" module".<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/395.png" width="100%"></div><br />		   
				1. Click on Add icon on right side of the screen. It will open "Add Bus Stop " window to add bus stop details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/398.png" width="100%"></div><br />
					   

				2. Enter vehicle stop name in "Vehicle Stop Name" field text box. E.g. Rajpura.<br />
				3. Enter vehicle stop abbreviation in "Vehicle Stop Abbr." field text box. <br />
				4. Select vehicle route from "Vehicle Route" drop down box. Value is coming from "Vehicle Route Master" module.<br />
				5. Enter time for bus stop in "Schedule Time" field text box.<br />
				6. Enter transport charges in "Transport Charges" field text box.<br />
				7. Click on "Save" button to save the details as all mandatory fields are filled.<br />
				8. Click on "Cancel" button to close the "Add Bus Stop" window.<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/397.png" width="100%"></div><br /> 

				9. Enter the data to be search in Search text box and click on Search button. (Search will be work for all fields displaying in grid.)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/396.png" width="100%"></div><br />				 

				10. Click on Delete icon underneath Action in grid to delete existing records.<br />
				11. Click on Edit icon underneath Action in grid. It will open "Edit Bus Stop" window. User can change or modify details to existing records.<br />
				12. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				13. User can view the records on next page by click on "Page Linking".<br />
				14. Click on "Print" button to print the existing records.<br />
				15. Click on "Export to Excel" button to export the records on Excel sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Transport Staff Master?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 
				Path to reach "Transport Staff Master" module : - From "Setup Menu" go to "Fleet Management" and then select "Transport Staff Master" module".<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/399.png" width="100%"></div><br />	
				1.	Click on Add  icon on right side of the screen. It will show "Add Transport Staff" window to add new transport staff.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/400.png" width="100%"></div><br />	 

				2.	Enter name of the staff member in the "Name" field textbox. It is mandatory field.<br />
				3.	Enter the address in the "Address" field textbox.<br />
				4.	Select "Date of Birth" for the staff member.<br />
				5.	Enter the code in the "Code" field textbox. It is mandatory and unique.<br />
				6.	Select the "Joining Date" of the staff member.<br />
				7.	Select "Blood Group" from the list box. It will be O+, O-, AB+, etc.<br />
				8.	Select the "Staff Type" from the list box. The types will be Driver, Conductor, Other.<br />
				9.	Select the "Leaving Date" of the staff member.<br />
				10.	Select the "Yes" or "No" radio button for the "Verification Done" field. "Yes" will be selected if verification is done and "No" will be selected if verification is not done.<br />
				11.	Enter the "License No. in the textbox given. It is mandatory.<br />
				12.	Select the "Issue Date" of the license for the staff member.<br />
				13.	Enter the "Licensing Authority" of the license.<br />
				14.	Select the "Expiry Date" of the license.<br />
				15.	Select the "Medical Examination Date" for the staff member.<br />
				16.	Click on "Browse" button for uploading image of the staff in the "Staff Image" field.<br />
				17.	Click on "Browse" button for uploading image of the driver''s license in the "Driver License Image" field.<br />
				18.	Click on "Save" button to save the details of the new staff member.<br />
				19.	Click on "Cancel" button to return to the module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/401.png" width="100%"></div><br />	 
				20.	Enter the valid data in the "Search" field textbox and click on "Search" button to search that data in the grid. Search will work for all fields displaying in grid. E.g. enter name in search text box field to search that name in grid.<br />
				21.	Click on "Edit" icon underneath "Action" field. An "Edit Transport Staff" window will be opened to change the staff details.<br />
				22.	Click on "Delete" icon underneath "Action" field. It will delete existing data in the grid. <br />
				23.	Click on the paging links to browse the list of "Transport Staff Detail" list in the grid.<br />
				24.	Click on the "Print" button to print the Transport Staff Details.<br />
				25.	Click on the "Export to Excel" button to get the list of "Transport Staff Details" in the excel file.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to View Vehicle Detail Report?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 
				Path to reach the "Vehicle Details Report" module :- From "Reports Menu" go to "Fleet Management Report" and select "Vehicle Details Report" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/402.png" width="100%"></div><br />		   

				1. Select vehicle type in "Select Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module.<br />
				2. Select registration number from "Vehicle Registration No." drop down box. Value is coming from "Vehicle Master" module. <br />
				3. Click on "Show List" button to display the details. It will display all details regarding vehicle such as Vehicle Info, Insurance Info, Fuel Info, Accident Info, Service Info and Tyre History.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/403.png" width="100%"></div><br />
				4. Click on "Vehicle Info" Tab. It will display all details regarding vehicle such as Model No., purchase date, Manufacturing year, Seating Capacity, Fuel capacity, Engine No., Chassis No., Body Maker, Chassis cost, Chassis Purchase date, Body Cost and Put on road. Vehicle Detail is coming from "Vehicle Master" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/404.png" width="100%"></div><br />
				 
				5.	Click on "Insurance Info" Tab. It will display all details regarding vehicle insurance such as Ins.Date, Ins.Due Date, and Ins.Comp, policy No., Value Insured, Ins.Premium, NCB, Branch Name and Agent Name. Insurance Detail is coming from "Vehicle Insurance" module.
				6.	Click on "Print" button to print the insurance details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/405.png" width="100%"></div><br />
				   
				7.	Click on "Fuel Info" Tab. It will display all details regarding vehicle fuel such as Staff Name, from Date and To Date. Fuel Detail is coming from "Fuel Master" module.<br />
				8.	Select name of staff from "Staff Name" drop down box. Value is coming from "Transport Staff Master" module.<br />
				9.	Select date from date picker for "From" field.<br />
				10. Select date from date picker for "To" field.<br />
				11. Click on "Show List" button to display all the details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/406.png" width="100%"></div><br />	   
				12. Click on "Accident Info" Tab. It will display all details regarding vehicle accident such as Staff Name, "From" Date and To Date. Detail is coming from "Vehicle Accident" module.<br />
				13. Select name of staff from "Staff Name" drop down box. Value is coming from "Fuel Master" module.<br />
				14. Select date from date picker for "From" field.<br />
				15. Select date from date picker for "To" field.<br />
				16. Click on "Show List" button to display all the details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/407.png" width="100%"></div><br />
				17. Click on "Service Info" Tab. It will display all details regarding vehicle service info such as service type,from and to date. Details is coming from "Vehicle Master" module.<br />
				18. Select service type from "Service type" drop down box. Value is coming from "Vehicle Master" module.<br />
				19. Select date from date picker for "From" field.<br />
				20. Select date from date picker for "To" field.<br />
				21. Click on "Show List" button to display all the details.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/408.png" width="100%"></div><br />
				22. Click on "Tyre History" Tab. It will display all the details regarding Tyre history such as Model no, Manufacturer, Main Tyre no. Tyre History detail is coming from "Vehicle Master" module.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to View Insurance Due Reports Details?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 
				Path to reach the "Insurance Due Reports" module :- From "Reports Menu" go to "Fleet Management Report" and then select "Insurance Due Reports" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/425.png" width="100%"></div><br />		   
				1. Select Date from date picker for "From" field.<br />
				2. Select Date from date picker for "To" field.<br />
				3. Select bus from "Bus" drop down box. Value is coming from "Vehicle Master" module.<br />
				4. Click on "Show List" button to display the details. It will display all details regarding Insurance Due Date such as Name, Registration No, In Service, Insuring Company, Policy No and Due Date. Value is coming from "Vehicle Master" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/426.png" width="100%"></div><br />  
				5. After clicking on "Show list" button. It will display "Due Date" of Vehicle Insurance. Value is coming from "Vehicle Insurance" module.<br />
				6. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				7. User can view the records on next page by click on "Page Linking".<br />
				8. Click on "Print" button to print the Insurance details.<br />
				9. Click on "Export to Excel" button to export the reports in excel sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to View Tyre Retreading Report?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Tyre Retreading Report" module :- From "Reports Menu" go to "Fleet Management report" and select "Tyre Retreading Report" module. It will show "Tyre Retreading Report" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/409.png" width="100%"></div><br />
				 
				1. Enter tyre number in "Tyre No." field text box. Tyre number should be of existing vehicle.<br />
				2. Click on "Show List" button to see details of tyre retreading report. E.g. registration number, KM reading, reason etc<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/410.png" width="100%"></div><br />
				 
				3. User can "Sort" the data in ascending or descending order by click on sorting link next to field s name in grid.<br />
				4. Click on detail icon under "Detail" caption to see reason for tyre retreading. <br />
				5. Click on "Print" button to print the list of  tyre retreading report details. Print window will open in which entire list will be displayed.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to View Vehicle Insurance Report Details?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 
				Path to reach the "Vehicle Insurance Reports" module :- "From "Reports Menu" go to "Fleet Management Report" and select "Vehicle Insurance Reports" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/411.png" width="100%"></div><br /		   
				1. Select Vehicle type from "Select Vehicle Type" drop down box. Value is coming from "Vehicle Type Master" module.<br />
				2. Select registration number from "Registration No." drop down box. Value is coming from "Vehicle Master" module.<br />
				3. Click on "Show List" button to display the details regarding selected data. It will display all details regarding Vehicle Insurance such as Insurance Company, in service, Policy No, Insurance From, Insurance To, Sum Insured, Premium, and NCB. Value is coming from "Vehicle Insurance" module.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/412.png" width="100%"></div><br /
				4. User can sort the data in ascending or descending order by click on sorting link next to field''s name in grid.<br />
				5. Click on "Print" button to print the Insurance details.<br />
				6. Click on "Export to Excel" button to export the report in excel sheet.<br />
				</div>
				</div>
				<div class="headingtxt12"><strong>s.Student Setup</strong> </div>
				<div class="dhtmlgoodies_question">How to Admit Student?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				Path to reach the "Admit Student" module :- From "Setup. Menu" go to "Student Setup" and select "Admit Student". It will show "Admit Student" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/301.png" width="100%"></div><br />
					 

				1. Select "Upper case", "Lower case" or "Mixed case" radio button from "Select class" field. E.g. if user had selected "Lower case" radio		button then all text in the text box field will be in lower case.<br />
				2. Select institute, from "Institute" field list box. E.g. select "CIET" institute if student wants to take admission in "CIET" institute. <br /> 
				3. Select class, from "Class" field list box. E.g. select "2010-PTU-MCA-CA-1SEM" class to take admission in MCA 1semester.<br />
				4. Select branch, from "Branch" field list box. E.g. select "ADMIN" for administration.<br />
				5. Enter college registration number in "College Reg No" field text box. College registration number should be unique number from other			student''s registration number.<br />
				6. Enter personal details of student in "Personal details" grid. E.g. in this user can enter date of admission, Exam roll number, Class roll		number, Date of birth, rank, first name, last name, email address etc.<br />
				7. Click on "Browser" button to upload the student photo from "Student photo" field.<br />
				8. Select exam, from "Exam" field list box. E.g. select "CET" if student had given CET exam.<br />
				9. Select category of student, from "Category" field list box. E.g. if student is from Punjab state and his category is general then he/she		can select Punjab State General from "Category" field list box.<br />
			    10.Select domicile of student, from "Domicile" field list box. E.g. if student domicile is Punjab then he/she will select Punjab in "domicile" field list box. <br />
				11.Click on "Mgmt Category" field check box, if student had taken management seat for admission. <br />
				12.Select "Yes" radio button if user wants to avail hostel facility or "No" radio button if user don''t want hostel facility in" Hostel facility availed?" field.<br />
				13.Select "Yes" radio button if user wants to avail transportation facility or "No" radio button if user don''t want transportation facility in" Transportation facility availed?" field.<br />
				14.Select "Is LEET" field check box as student is from LEET else leave it unchecked.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/302.png" width="100%"></div><br />	 
				15.Select "Yes" radio button from "Have you ever stayed in hostel?" field, if in past student had stayed in hostel and enter numbers of year he/she stayed in hostel in "If yes, how many years?" field text box. Select "No" radio button if in past student had not stayed in hostel. <br />
				16.Select "Self-financed" radio button from "Education" field, if student had not taken any loan from bank or select "Educational Loan" radio button if student had taken loan from bank and student should enter name and address of the bank in "Bank Name and Address" field and amount of loan in "Loan Amount" field.<br />
				17.Enter number of languages known by student in "Languages Known" field. E.g. if student can read English and Hindi languages then he should enter English, Hindi in "To Read" field text box.  <br />
				18.Select "Yes" radio button from" Have you completed graduation" field if student had completed his/her graduation and select "No" radio button if not completed his/her graduation.<br />
				19.Select "Yes" or "No" radio button from" If no, have you written the final exam" field. If student had selected "Yes" radio button then he/she should mention the result due date in" If yes, when is the result due" field.<br />
				20.Enter the past Academic record of 10th, 10+2, Graduation, PG(if any) and Any Diploma underneath "Previous Academic Record" caption.  <br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/303.png" width="100%"></div><br />	 
				21.Enter father's detail underneath "Father Details" caption. E.g. details include name, occupation, email, mobile, address etc.<br />
				22.Enter mother's detail underneath "Mother Details" caption. If user selects the check box in front of "Mother details" caption then all the information of "Father Details" fields will be included in the "Mother Details" fields. E.g. details include name, occupation, email, mobile, address etc.<br />
				23.Enter guardian s detail underneath "Guardian Details" caption. If user selects the first check box in front of "Guardian Details" caption then it will include information of father's detail and by selecting second check box it will include mother's detail. E.g. details include name, occupation, email, mobile, address etc.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/304.png" width="100%"></div><br />	 
				24.Enter correspondence address of student underneath "Correspondence Address" caption. E.g. it includes address1, address2, country, contact no, state, city and pin code.<br />
				25.Enter permanent address of student underneath "Permanent Address" caption. If user selects the check box in front of "Permanent Address" caption then all information of "Correspondence Address" will be included in "Permanent Address" fields. E.g. it includes address1, address2, country, contact no, state, city and pin code.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/305.png" width="100%"></div><br />	 
				26.Select "Yes" or "No" radio button from" Do you suffer from any ailment that requires medical attention on a regular basis" field. If student had selected "Yes" radio button then student should mention nature of ailment and do any of student family members suffer from any ailment in different fields under "Ailment" caption.<br />
				27.Select coaching from "Do you have taken Coaching" field list box, if student had taken any coaching then enter coaching class, branch manager name, address and phone number in text box field.<br />
				28.Select "Yes" or "No" radio button from "Do you have work experience" field. If student had selected "Yes" radio button then student should mention department, organization and place in different fields under "Miscellaneous" caption.<br />
				29.Enter remarks of the student in "Remarks" field text box.<br />
				30 Enter student reference number/ reference name in "Reference number/ Reference name" field text box.<br />
				31.Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				32.Click on "Reset" button to clear the current data done by the user on "Admit student" page.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Generate Student Login?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Assign Group to Students (Advanced)" module : - From "Setup" tab go to "Student Setup" and then select "Generate Student Login". <br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/307.png" width="100%"></div><br />
				1)	Select degree of the student from the "Degree" field list box. The degrees are populated from "Class Masters" module. E.g. BTECH, MCA, etc.<br />
				2)	Select group of the student from the "Group" field list box. By default, it will be "All". Groups associated with the class will be populated from "Group Master" module.<br />
				3)	Enter Roll No. of the student in the "Roll No." field textbox.<br />
				4)	Enter name of the student in the "Student Name" field textbox.<br />
				5)	Select user name from "Username" field list box. It will be Reg. No., Univ. Roll No., Roll No., Email and Student Name + Batch. E.g. if Univ. Roll No. is selected then username generated will be e101010. <br />
				6)	Click on the checkbox present on the left of "Do not change the username for existing users" field. If user deselects the checkbox then new logins will be regenerated for students that already have existing usernames. By default, the checkbox is already selected.<br />
				7)	Under "Password" caption, select the radio button according to which user wants to generate the password. It will be "Make Password as first name followed by birth year", "Enter common password" and "Generate random password". By default, "Make Password as first name followed by birth year" radio button is selected. If the user selects "Enter common password", then the password is to be entered in the textbox.<br />
				8)	Click on "Show List" button. It will display the list of students according to the data entered or selected in the "Degree", "Group", "Roll No." and "Student Name" search criteria fields.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/308.png" width="100%"></div><br />
				9)	To select all students click on checkbox in first column of header. <br />
				10)	Select students for generating logins by clicking on check box prior to "Reg. No." label.<br />
				11)	Click on "Generate Logins and Export" button to generate logins and save them in an excel file.
<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Assign Roll number to Students?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>

				Path to reach the "Assign Roll Numbers" module :- From "Setup Menu" go to "Student Setup" and select "Assign Roll Numbers". It will show "Assign Roll Numbers" screen.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/306.png" width="100%"></div><br />
				 
				1. Select degree from "Degree" field list box. E.g. If user wants to assign roll number to "2009-PTU-MCA-CA-3SEM" batch, then user can select from "Degree" field list box.  <br />
				2. Select roll number length from "Roll No. Length" field list box. E.g. if user had selected 10, then roll no. length will be of 10 digits.<br />
				3. Select alphabetic or registration text from "Sorting" field list box. E.g. if user had selected alphabetic text then it will sort data in alphabetic order under "Student Details" caption.<br />
				4. Select "Include Leet" check box if user wants to include student from Leet.<br />
				5. Enter number in prefix or suffix in "Prefix" or "Suffix" field text box respectively. Roll no. will start from that number, which was entered in "Prefix" field and roll no. will end from that number, which was entered in "Suffix"field.<br />
				6. Select "Include already assigned" check box if user wants to include student to whom roll numbers are already assigned.<br />
				7. Enter number in "Start Series From" field text box. It will start roll number in increasing order from number that was entered.<br />
				8. Click on "Show List" button to see details of students with old and new roll numbers, under "Student Details" caption.<br />
				9. Click on "Check box" underneath "Select" field in the grid, to select a specific students to whom user wants to assign new roll no. or user can select all students just by clicking on first "Check box" in grid.<br />
				10.Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Upload Student Group?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Upload Student Group" module: - From "Setup" tab go to "Student Setup" and then select "Upload Student Group". <br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/309.png" width="100%"></div><br />
				 
				1)	Click on "here" link to download the Student Group Uploading Format under the "Notes" caption. It opens a sample excel file with all the		fields that are needed to upload the different group(s) that are to be assigned to the students. The fields will be Student Name, Roll		No., Group Short, Next Group Short, etc.<br />
				2)	Click on "here" link to download the instructions to be strictly followed for the excel file under "Notes" caption.<br />
				3)	Select the class in the "Select Class" field list box. The classes displayed are populated from "Class Master" module.<br />
				4)	Click on "Browse" button or enter the path where the excel file is saved on the disk. E.g. C:\Documents and Settings\My Documents\			StudentDetailUploadFormat.xls<br />
				5)	Click on "Upload" button to upload the excel file containing the student group detail(s).<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Upload / download Roll no. or Univ. no?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				Path to reach "Upload/Download Student Roll No. / Univ. Roll No". :- (From "Setup" go to "Student Setup" and click on "Upload / download roll no. or univ. no").<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/310.png" width="100%"></div><br /> 
				1. User should read "Notes" before by clicking on here link. (This will tell user Save the export file in excel format, Browse the excel file and upload it, Roll No. & University Roll No. will be changed for that student whose Upload Roll No. status will be yes.)<br />
				2. Click on "Student List" radio button. (This will display all student lists in grid.)<br />
				3. Click on "Student List without Roll no. or University Roll No." (This will not display "Roll No." and "Univ. Roll No." in grid.)<br />
				4. Select the "Select Class" from "Select Class" drop down box. (This will display the list in drop down box.)<br />
				5. Click on "Export to Excel" button this will take the all record related to class to Excel sheet.<br />
				6. Select from "Browse" in "Select file" where the file is take it from there. (The files get selected from there.)<br />
				7. Click on "Upload" button to upload updated data. (This will update all records)<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Upload Student Detail?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Upload Student Detail" module : - From "Setup" tab go to "Student Setup" and then select "Upload Student Detail". <br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/311.png" width="100%"></div><br />
				1)	Click on "here" link to download the Student Detail Format under the "Notes" caption. It opens an excel file with all the fields that are		needed to upload the detail of the student present in it.<br />
				2)	Click on "here" link to download the instructions to be strictly followed for the excel file under "Notes" caption.<br />
				3)	Select the class in the "Select Class" field list box.<br />
				4)	Click on "Browse" button or enter the path where the excel file is saved on the disk. E.g. C:\Documents and Settings\My Documents\			StudentDetailUploadFormat.xls.<br />
				5)	Click on "Upload" button to upload the excel file containing the student details.<br />
				6)	Click on "Update" button to update any already existing record. Any changes or modifications in the records are saved.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Delete a Student?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				Path to reach "Delete student" module :- From "Setup" go to "Student Setup" and click on "Delete Students". <br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/312.png" width="100%"></div><br />
				1.	Enter Student Roll no in "Roll No." field text box.(User can search the Record from roll no. also.)<br />
				2.	Enter Student Name in "Student Name" field text box.(It should contain full name in student name text box.)<br />
				3.	Select "Gender" from gender drop down box. (It should contain male and female options in it. From where you can select as per user requirement.)<br />
				4.	Select "Birth Date From" and "Birth Date to" in yyyy-mm-dd drop down box.<br />
				5.	Click on "Academic Criteria: Expand" link to search the student by Degree, Branch, periodicity, subject, group, university drop down box. (It contains various information about Academic Criteria.)<br />
				6.	Click on "Address Criteria: Expand" link to search the student by City, State; Country. (It contains various information about Address Criteria)<br />
				7.	Click on "Misc Criteria: Expand" link to search the student by quota, blood group. (It contains various information about Misc Criteria.)<br />
				8.	Click on "Show List" button. (To show record according to selected information.)<br />
				9.	Mark check box to select any record from grid. (User can mark all records and selected also accordingly.)<br />
				10. Click on sorting icon   to arrange any field according to sorting order. (It can be ascending or descending.)<br />
				11. User can view all records by click on paging links on the page.<br />
				12. Click on "Delete" button to delete details. (It should delete the record of any student record.)<br />
				13. Click on "Cancel" button to close the record grid.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/313.png" width="100%"></div><br />
				14. Click on "Print" button to take the print of the record. (This will only print data displayed in grid.)<br />
				15. Click on "Export to Excel" button to take these current records to excel sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Restore a Student?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Restore student" module :- From "Setup" go to "Student Setup" and click on "Restore student".<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/314.png" width="100%"></div><br />
				1.	Enter Student Roll no in "Roll No." field text box. (User can easily search the Record from roll no. also.)<br />
				2.	Enter Student Name in "Student Name" field text box. (It should contain roll no, full name and clicking on checkbox will also select student.)<br />
				3.	Gender" from gender drop down box. (It should contain male and female options from it.)<br />
				4.	Select "Birth Date From" and "Birth Date to" in yyyy-mm-dd drop down box.	<br />
				Click on "Academic Criteria: Expand" link to search the student by Degree, Branch, periodicity, subject, group, university drop down box. (It contains various information about Academic Criteria)<br />
				6.	Click on "Address Criteria: Expand" link to search the student by City, State; Country. (It contains various information about Address Criteria)<br />
				7.	Click on "Misc Criteria: Expand" link to search the student by quota, blood group. (It contains various information about Misc Criteria).<br />
				8.	Click on "Show List" button. (To show record according to selected information)<br />
				9.	Mark check box to select any record from grid. (User can mark all records and selected also accordingly.)<br />
				10. Click on sorting icon   to arrange any field according to sorting order. (It can be ascending or descending.)<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/315.png" width="100%"></div><br />
				11. User can view all records by click on paging links on the page.<br />
				12. Click on "Restore" button to restore details. (It should restore the record of any student record.)<br />
				13. Click on "Cancel" button to close the record grid.<br />
				14. Click on "Print" button to take the print of the record. (This will only print data displayed in grid).<br />
				15. Click on "Export to Excel" button to take these current records to excel sheet.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to "Update roll no." of a Student?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				Path to reach "Update Roll No." :- From "Setup" goes to "Student Setup" and click on "Update Student Class/Roll No". <br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/316.png" width="100%"></div><br />
				1.	Click on "Active Classes" radio button. (This will display all the classes in grid.)<br />
				2.	Click on "Active classes with matching subjects" radio button. (This will only display the classes which are having same subject as present class)<br />
				3.	Select the "Select Criteria" from select criteria drop down box. (This will contain Roll no. , University Roll no. and Registration also.)
				4.	Enter the Value according to "Select Criteria" to update that in grid.<br />
				5.	Click on "Show List" button. (This will display records in grids according to previous filled records.)<br />
				6.	Enter the "Current Class" in current class field textbox.<br />
				7.	Enter the "New Class" in new class field textbox. (The updated class gets filled in "New Class" field textbox.)<br />
				8.	Enter the "New Roll No." in new roll no. field textbox. (The updated roll no. gets filled in "Roll No." field textbox.)<br />
				9.	Click on checkbox to Make new roll no. as username. (If student wants to make his Roll no. as his USER NAME, then he/she has to click on that check box.)<br />
				10. Enter the reason of updating roll no. in "Reason" textbox.<br />
				11. Click on "Save" button to save details. (User account will be updated as all mandatory fields are filled.)<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Generate Parent Logins?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Generate Parent Logins" :- From "Setup" go to "Student Setup" and click on "Generate Parent Logins".<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/320.png" width="100%"></div><br /> 
				1.	Enter Student Roll no in "Roll No." field text box. (User can easily search the Record from roll no. also.)<br />
				2.	Enter Student Name in "Student Name" field text box. (It should contain full name in student name text box.)<br />
				3.	Select "Gender" from gender drop down box. (User will select male and female options from "Gender" drop down box.)<br />
				4.	Select "Birth Date From" and "Birth Date to" in yyyy-mm-dd drop down box.<br />
				5.	Click on "Academic Criteria: Expand" link to search the student by Degree, Branch, periodicity, subject, group, university drop down box.		(It contains various information about Academic Criteria.)<br />
				6.	Click on "Address Criteria: Expand" link to search the student by City, State; Country. (It contains various information about Address		Criteria.)<br />
				7.	Click on "Misc Criteria: Expand" link to search the student by quota, blood group. (It contains various information about Misc Criteria)
				8.	Click on "Show List" button. (To show record according to selected information.)<br />
				9.	User should read "Note" before "Generate parent login" of any student. (The username would be a prefix f<Roll Number> for father, prefix		m<Roll Number> for mother and g<Roll Number> for guardian.)<br />
				10. User should fill "Password" for more information regarding generate parent login. (Create pass word for already created user.)<br />
				11. User should fill Authorized person for generate password and also enter Designation of that person in Authorized person field textbox and		designation field textbox.<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/321.png" width="100%"></div><br />
				12. Mark check box to select any record from grid. (User can mark all records and selected also accordingly.)<br />
				13. Click on sorting icon   to arrange any field according to sorting order. (It can be ascending or descending.)<br />
				14. Click on "Father's name" checkbox to select father login. (This means father get selected as "Parent login".)<br />
				15. Click on "Generate login &print" button to generate login of father and also take the print of that login detail.<br />
				16. Click on "Generate login & Export" button to generate login of father and also take the record of that login detail on excel sheet.<br />
				17. Click on "Mother's name" checkbox to select mother login. (This means mother get selected as "Parent login".)<br />
				18. Click on "guardian's name" checkbox to select guardian login.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Assign Groups to Students (Advanced)?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				Path to reach "Assign Group to Students (Advanced)" module : - From "Setup" tab go to "Student Setup" and then select "Assign Group to Students (Advanced)". <br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/322.png" width="100%"></div><br />
				1)	Select degree in the "Degree" field list box. E.g. BTECH, MCA, etc.<br />
				2)	Select the field in the "Sort By" field list box to sort the students according to that field in the grid. Fields will be Roll No,			U.RollNo and Alphabetic. E.g. if Roll No. is selected then the list will be displayed according to the Roll No.<br />
				3)	Click on "Show List" button. To get the student and groups detail.<br />
				  <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/323.png" width="100%"></div><br />
				4)	The summary of the groups created in the "Group Master" module and number of students associated with those groups will be displayed under		the "Group Wise Student Counter" caption. E.g. 68 students assigned to "08Csx" group and 69 to the "08Csy" group under "Theory" group		type. All the students associated with the class selected in the "Degree" field will be displayed in the grid under "Assign Groups"			caption.<br />
				5)	Click on the checkbox(s) for selecting students for assigning group under "Theory" field in the grid. The groups are populated from "Group		Type Master" module. It will be Theory, Tutorial, Training Workshop and Practical. Note that, any student should not be selected in two		same groups present under different group types. E.g. one student cannot be assigned "08CSx" and "08CSy" groups at the same time under		"Theory" group type. Similarly click on the checkbox(s) for selecting students for assigning groups under "Training Workshop", "Tutorial"		and "Practical" group types in the grid.<br />
				6)	Click on the "Print" button to take a printout of the list of students assigned to different groups.<br />
				7)	Click on the "Export to Excel" button to have the list of students in an excel file.<br />
				8)	Click in the "Save" button.  All the data changes will be saved.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Assign Optional Subjects to Students?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				 Path to reach the "Assign Optional Subjects to Students" module. (From "Setup" go to "Student Setup" and select "Assign Optional Subjects to Students". It will show "Assign Optional Subjects to Students" module screen)<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/328.bmp" width="100%"></div><br />
				1)	Select Class from "Degree" list box.<br />
				2)	Select Subject from "Subject" list box. Those subject code populated in "Subject" list box for which three check boxes (‘Optional’, 'Major/Minor' and ‘Offered’) are selected in "Assign Subject to Class" module.<br />
				3)	Select optional Subject from "Choose" list box. Those optional Subjects are populated in "Choose" list box which are associative with selected subject in "Subject" list box and this association done in "Map Major/Minor Subjects to Class" module.<br />
				4)	User can Sort the student by select any one radio button ("Roll No.", "Univ. Roll No." and "Name").<br />
				5)	Click on "Show list" button. After click on show list button students data populated in grid which is not associate with any group.<br />
				6)	To assign the student to the particular group the user must select the particular radio button.<br />
				7)	User must select the "None" radio button, if no student is to be assign to group.<br />
				8)	Click on "Assign Groups" button to save the details. <br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Update Student Groups?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				Path to reach "Update Student Groups" module : - From "Setup" tab go to "Student Setup" and then select "Update Student Groups". 
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/329.bmp" width="100%"></div><br />				 

				1)	Enter valid Roll No. of the student.<br />
				2)	Click on "Show List" button. The different groups associated with the class will be displayed under "Group Allocation" caption. By default, selected checkbox(s) denote that these groups are already assigned to the student.<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/331.bmp" width="100%"></div><br /> 
				3)	Click on the checkbox(s) for changing (selecting/deselecting) or assigning new group to students displayed under different group types displayed in the grid. E.g. changing groups like "09MCA" and "GROUP1" displayed under "Theory Groups", "Tutorial Groups" and ‘Practical Groups" header. Note that, any student should not be selected in two different group types simultaneously. E.g. "09MCA" and "GROUP1" cannot be assigned to one student at the same time under one group type.<br />
				4)	Click on "Update Student Details" button. An "Update compulsory groups" window opens up with the details of the attendance and tests held in different groups of the class.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/332.bmp" width="100%"></div><br />
				5)	Click on the subject tabs present on the top of "Update compulsory groups" window. The group names with details of attendance of the student will be displayed when "Attendance" tab is selected. <br />
				6)	Enter the number of lectures attended by the student in the textbox present under "Lectures Attended" field. The total lecturers delivered in its previous group will be displayed from the "Daily Attendance" module. The lectures attended for the new group assigned should be entered accordingly as the student has attended lectures in its previous group also.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/333.bmp" width="100%"></div><br />
				7)	Similarly, select Test tab, the details of the tests held till present date along with test marks of the student will be displayed.<br />
				8)	Enter the marks scored by the student under the "Marks Scored" field textbox. The total marks scored in its previous group tests will also be displayed. The marks for the new group assigned should be entered accordingly as the student has scored in its previous group tests.
				9)  Click on the "Save" button. This will save the changes made to the group details of the student like number of lectures and test marks entered.<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Change Student Branch?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				
				Path to reach "Change Student Branch" (From "Setup" goes to "Student Setup" and click on "Change Student Branch".)<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/324.png" width="100%"></div><br />
				1. Select the "Class" from Class drop down box. (This will select student’s current class.)<br />
				2. Click on "Show List" button. (This will display all students in selected class.)<br />
				3. Click on sorting icon   to arrange any field according to sorting order. (It can be ascending or descending.)<br />
				4. Select the "New Class" from New Class / Branch drop down box. (This will select student’s New class.)<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/325.png" width="100%"></div><br />
				5. Click on "Save" button to save group details. (Group will be created on click "Save" button as have all mandatory fields are filled.)<br />
				</div>
				</div>
				<div class="dhtmlgoodies_question">How to Add Grace Marks?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>

				Path to reach the "Grace Marks" module :- From "Setup. Menu" go to "Student Setup" and select "Grace Marks". It will show "Grace Marks" screen.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/337.png" width="100%"></div><br />
				1.	Select class from "Class" field list box. Only that classes will populate for which internal marks are transferred. <br />
				2.	Select subject from "Subject" field list box. Subjects will be displayed according to class which user selects from "Class" field.<br />
				3.	Select group from "Group" field list box. Groups will be displayed according to subject which user selects from "Subject" field.<br />
				4.	Enter roll number in "Roll No." field text box. If user wants to give grace marks to specific student and it is optional.<br />
				5.	Click on "Show List" button to see details of students.<br />
				6.	Enter grace marks in "Grace Marks" field text box, if user wants to give grace marks to all students which are displaying under "Student List" caption.<br />
				7.	Click on "Sort" icon next to name field in the grid, to sort data in ascending or descending order.<br />
				8.	Marks, excluding grace marks of students will be displayed under "Marks Scored" caption in grid.<br />
				9.	Enter grace marks in "Grace Marks" text box under "Grace Marks" caption in grid. User can give grace marks to individual student separately. <br />
				10. Marks, including grace marks of student will be displayed under "Marks with Grace" caption.<br />
				11. Maximum marks of students will be displayed under "Max. Marks" caption.<br />
				12. Click on "Save" button to save details. Data will get saved in database as user will click on "Save" button.<br />
				13. Click on "Cancel" button to close "Student List" grid.<br />
				</div>
				</div>
				
				<div class="headingtxt12"><strong>t.Teacher Setup</strong> </div>
				<div class="dhtmlgoodies_question">How to find teachers for substituting a teacher going on leave?</div>
			    <div class="dhtmlgoodies_answer">
			    <div>
				<h3>Scenario 1</h3>Suppose Ms Meenal Khanna is busy in Second period on Tuesday. Now we wish to find all teachers who are free on Tuesday, in 2nd period and who can teach subject CS-202.<br />
				 <div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/t3.png" width="100%"></div><br />
				 The process is as follows:<br />
					1.	We select Time Table Label (Jan-Jun2011) <br />
					2.	We select teacher who will be busy (Meenal Khanna)<br />
					3.	All days on which Ms Meenal Khanna is having at least one class will be populated in drop down Days. We select the day (Tuesday) when Ms Meenal will		be busy.<br />
					4.	All periods in which she will be teaching are populated in box Periods. We select Period (2nd). We will be shown the subject she will be teaching in		2nd Period.<br />
					5.	Select Subject Operating System(CS-202).<br />
					6.	Now click ,"Show List".<br />
					7.	A list of all teachers (along with their telephone numbers) who are free in 2nd period and can teach CS-202 is displayed as shown above.<br />
				<br /><br />
				<h3>Scenario 2</h3>Now we want to see list of all teachers who are free in 2nd period, whether or not they can teach subject Operating System(CS-202).<br />
				<div style="margin-top:5px; margin-bottom:5px;"><img src="<?php echo IMG_HTTP_PATH.'/ListFaq'?>/t4.png" width="100%"></div><br />
				 The process is as follows:<br />
					1.	We select Time Table Label (Jan-Jun2011) <br />
					2.	We select teacher who will be busy (Meenal Khanna)<br />
					3.	All days on which Ms Meenal Khanna is having at least one class will be populated in drop down Days. We select the day (Tuesday) when Ms Meenal will		be busy.<br />
					4.	All periods in which she will be teaching are populated in box Periods. We select Period (2nd). We will be shown the subject she will be teaching in		2nd Period.<br />
					5.	Don’s select anything in box Subjects.<br />
					6.	Now click "Show list".<br />
					7.	A list of all teachers(along with their telephone numbers) who are free in 2nd period is displayed as shown above.<br />
				</div>
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
