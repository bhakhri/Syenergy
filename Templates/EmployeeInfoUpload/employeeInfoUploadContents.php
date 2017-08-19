<?php 
//-------------------------------------------------------
//  This File contains html code for Employee Info Upload
//
//
// Author :Gurkeerat Sidhu
// Created on : 10-Nov-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
                <td class="content" height="20" colspan="3">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
                <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr >
                        <td class="content_title" >Employee Info: </td>
                    </tr>
                </table>
                </td>
             </tr>
               
            <tr>
                <td class="contenttab_row" valign="top" >
                <div id="div_Outer">
				 <!-- form table starts -->
				  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <tr>
                                <td valign="top" class="contenttab_row1" align='center'>

                                <form method="POST" name="editForm"  action="<?php echo HTTP_LIB_PATH;?>/EmployeeInfoUpload/exportEmployeeInfoCSV.php" id="editForm" method="post" enctype="multipart/form-data" style="display:inline" onsubmit="return false;">
                                        <table width="40%" border="0" cellpadding="0" align='center'>
                                            <tr>
                                               <td  align="left" width="35%" >
                                                   <nobr><strong>Export Employee Info :</strong></nobr>
                                                </td>
                                                <td align="left" valign="top">
                                                    <input type="image" name="studentRollNoSubmit" value="studentRollNoSubmit" onclick="exportEmployee();" src="<?php echo IMG_HTTP_PATH;?>/excel.gif"/>
                                                    </td>
                                                   
                                            </tr>
                                        </table>
                                    </form>
                                   </td>
                            </tr>
                            <tr>
                            <td valign="top" class="contenttab_row1">
                                    <form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/EmployeeInfoUpload/fileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
                                        <table align="center" border="0" width="40%" cellpadding="0" >
                                           <tr>
                                                <td valign='top' align="left" colspan='3' class=''>
                                                 <B>Note: <br>Kindly follow the instructions strictly before uploading any file.<br>Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadEmployeeUploadInstructions.php'>here</a> to download instructions.</B>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap align="left" valign="top" width="12%">
                                                    <strong>Select File :</strong>
                                                </td>
                                                <td nowrap align="left" valign="top" width="20%" >
                                                    <input type="file" id="employeeInfoUploadFile" name="employeeInfoUploadFile" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
                                                </td>
                                                <td align="left" valign="top">
                                                    <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload.gif"  />
                                                </td>
                                            </tr>
                                        </table>
				 </form>
                                </td>
                            </tr>
                        </table>

<table align="center" border="0" cellpadding="0" width="100%">
<tr id='showSubjectEmployeeList' > 
                                          <td class="contenttab_internal_rows" align="left" colspan="20">

                                              <table width="100%" border="0px" cellpadding="0" cellspacing="0">
                                                <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" >
                                                    <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                                       <Label id='idSubjects'>Expand Sample Format for .xls file and instructions</label></b></a>
                                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                  </td>
                                                 </tr> 
                                                 <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
                                                    <nobr><br><span id='subjectTeacherInfo'>
<div id="mdiv" style="overflow:auto; width:960px;">
                       <div id="messageDiv">

<table border="1" cellpadding="0" cellspacing="0" width="100%">
                         <tr>
                           <td class="contenttab_internal_rows"><b>Sr.No.</b></td>
                           <td class="contenttab_internal_rows"><b>Employee Id&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>User Name&nbsp;<?php echo REQUIRED_FIELD;?><?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Title&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Last Name</b></td>
                           <td class="contenttab_internal_rows"><b>First Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>Middle Name</b></td>
			<td class="contenttab_internal_rows"><b>Employee Code&nbsp;<?php echo REQUIRED_FIELD;?><?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>Employee Abbr.&nbsp;<?php echo REQUIRED_FIELD;?><?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Teaching Employee (Yes/No)&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Designation&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Gender (M/F)&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>Department</b></td>
                           <td class="contenttab_internal_rows"><b>Pan No.&nbsp;<?php echo REQUIRED_FIELD;?><?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>Religion</b></td>
			<td class="contenttab_internal_rows"><b>Caste</b></td>
			  <td class="contenttab_internal_rows"><b>PF No.</b></td>
                           <td class="contenttab_internal_rows"><b>Bank Name</b></td>
                           <td class="contenttab_internal_rows"><b>Bank Account No.</b></td>
                           <td class="contenttab_internal_rows"><b>Bank Branch Name</b></td>
                           <td class="contenttab_internal_rows"><b>ESI Number</b></td>
			<td class="contenttab_internal_rows"><b>Branch&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b>Role Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			  <td class="contenttab_internal_rows"><b>Marital Status (Yes/No)</b></td>
                           <td class="contenttab_internal_rows"><b>Spouse Name</b></td>
                           <td class="contenttab_internal_rows"><b>Father Name</b></td>
                           <td class="contenttab_internal_rows"><b>Mother Name</b></td>
                           <td class="contenttab_internal_rows"><b>Contact Number</b></td>
			<td class="contenttab_internal_rows"><b>Email</b></td>
			<td class="contenttab_internal_rows"><b>Mobile Number</b></td>
			<td class="contenttab_internal_rows"><b>Address1</b></td>
                           <td class="contenttab_internal_rows"><b>Address2</b></td>
                           <td class="contenttab_internal_rows"><b>City</b></td>
                           <td class="contenttab_internal_rows"><b>State</b></td>
                           <td class="contenttab_internal_rows"><b>Country</b></td>
                           <td class="contenttab_internal_rows"><b>Pincode</b></td>
			<td class="contenttab_internal_rows"><b>Date of Birth(yyyy.mm.dd)</b></td>
			<td class="contenttab_internal_rows"><b>Date of Marriage(yyyy.mm.dd)</b></td>
			<td class="contenttab_internal_rows"><b>Date of Joining(yyyy.mm.dd)</b></td>
                           <td class="contenttab_internal_rows"><b>Date of Leaving(yyyy.mm.dd)</b></td>
                           <td class="contenttab_internal_rows"><b>Blood Group</b></td>
                           <td class="contenttab_internal_rows"><b>Status (Yes/No)</b></td>
                           
                         </tr>
                         <tr>
                                           
                           <td class="contenttab_internal_rows">1</td>
                           <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">RAM</td>
                           <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">MOHAN</td>
                           <td class="contenttab_internal_rows">RAM </td>
			<td class="contenttab_internal_rows">NARAYAN</td>
			<td class="contenttab_internal_rows">E1707122</td>
			<td class="contenttab_internal_rows">rm</td>
                           <td class="contenttab_internal_rows">YES</td>
                           <td class="contenttab_internal_rows">Professor</td>
                           <td class="contenttab_internal_rows">M</td>
                           <td class="contenttab_internal_rows">Mechnical</td>
                           <td class="contenttab_internal_rows"></td>
			<td class="contenttab_internal_rows">HINDU</td>
			<td class="contenttab_internal_rows"></td>
			  <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">PNB</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">KALLUGHANDA</td>
                           <td class="contenttab_internal_rows"></td>
			<td class="contenttab_internal_rows">KALLUGHANDA</td>
			<td class="contenttab_internal_rows">TEACHER</td>
			  <td class="contenttab_internal_rows">YES</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">SHAM MOHAN</td>
                           <td class="contenttab_internal_rows">SEEMA RANI</td>
                           <td class="contenttab_internal_rows">95834645665</td>
			<td class="contenttab_internal_rows">ram_mohan@gmail.com</td>
			<td class="contenttab_internal_rows">85774565</td>
			<td class="contenttab_internal_rows">sec-10,chandigarh</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">CHANDIGARH</td>
                           <td class="contenttab_internal_rows">PUNJAB</td>
                           <td class="contenttab_internal_rows">INDIA</td>
                           <td class="contenttab_internal_rows">17463</td>
			<td class="contenttab_internal_rows">1886.08.09</td>
			<td class="contenttab_internal_rows">2008.02.13</td>
			<td class="contenttab_internal_rows">2008.07.03</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">O+</b></td>
                           <td class="contenttab_internal_rows">YES</td>
                           
                         </tr>
                
                                      <tr>
                                           
                           <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">SEEMA</td>
                           <td class="contenttab_internal_rows">3</td>
                           <td class="contenttab_internal_rows">SHARMA</td>
                           <td class="contenttab_internal_rows">SEEMA </td>
			<td class="contenttab_internal_rows">SHARMA</td>
			<td class="contenttab_internal_rows">E6457</td>
			<td class="contenttab_internal_rows">SM</td>
                           <td class="contenttab_internal_rows">YES</td>
                           <td class="contenttab_internal_rows">Professor</td>
                           <td class="contenttab_internal_rows">M</td>
                           <td class="contenttab_internal_rows">ELECTRICAL</td>
                           <td class="contenttab_internal_rows"></td>
			<td class="contenttab_internal_rows">HINDU</td>
			<td class="contenttab_internal_rows"></td>
			  <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">UCO</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">KALLUGHANDA</td>
                           <td class="contenttab_internal_rows"></td>
			<td class="contenttab_internal_rows">KALLUGHANDA</td>
			<td class="contenttab_internal_rows">TEACHER</td>
			  <td class="contenttab_internal_rows">YES</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">HAMESH SHARMA</td>
                           <td class="contenttab_internal_rows">SPNA SHARMA</td>
                           <td class="contenttab_internal_rows">895834534</td>
			<td class="contenttab_internal_rows">s_sharma@gmail.com</td>
			<td class="contenttab_internal_rows">7564545</td>
			<td class="contenttab_internal_rows">sec-18,chandigarh</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">CHANDIGARH</td>
                           <td class="contenttab_internal_rows">PUNJAB</td>
                           <td class="contenttab_internal_rows">INDIA</td>
                           <td class="contenttab_internal_rows">1678</td>
			<td class="contenttab_internal_rows">1884.11.08</td>
			<td class="contenttab_internal_rows">2006.03.06</td>
			<td class="contenttab_internal_rows">2008.07.03</td>
                           <td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">B+</b></td>
                           <td class="contenttab_internal_rows">YES</td>
                           
                         </tr>
               </table>

         
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
			 <b><font color="red">1. Columns marks with ** cannot be duplicate</font></b><br/>
                    	 <b><font color="red">2. Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red">3. Not even a single column should be removed or added</font></b><br/>
</div>
</div>			
		</span></nobr>
                                                  </td>
                                                 </tr> 
                                              </table>
                                          </td>
                                     </tr>
</table>
	
						      <!-- form table ends -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                <td valign="top"  align="left" >
                <table width="960" border="0" align="center">
                <tr>
            <td height="163" scope="col" valign="top" align="center">
                    <?php
                         //*************Used For Creating*********
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->tableBlueHeader('Teaching/Non-Teaching Breakup','width=320' ,'height=150','align=center');
                        echo UtilityManager::includeJS("swfobject.js");
                        $flashPath = IMG_HTTP_PATH."/ampie.swf";
                    ?>           
                    <table width="100%" height="150" border="0">
                    <tr>
                        <td valign="top" align="center">
                        <table width="100%" border="0">
                        <tr>
                            <td valign="top">
                            <div id="flashcontent1">
                                <strong>You need to upgrade your Flash Player</strong>
                            </div>
                            <script type="text/javascript">
                            x = Math.random() * Math.random();
                             var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
                             so.addVariable("path", "ampie/");  
                             so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart    
                              so.addParam("wmode", "transparent");
                              so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                              so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                              so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeTeachingData.xml?t="+x));
                              so.addVariable("preloader_color", "#999999");
                              so.write("flashcontent1");
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
                     <td height="163" scope="col" valign="top" align="center">
                    <?php
                         //*************Used For Creating*********
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->tableBlueHeader('City Wise Breakup','width=320' ,'height=150','align=center');
                        echo UtilityManager::includeJS("swfobject.js");
                        $flashPath = IMG_HTTP_PATH."/ampie.swf";
                    ?>           
                    <table width="100%" height="150" border="0">
                    <tr>
                        <td valign="top" align="center">
                        <table width="100%" border="0">
                        <tr>
                            <td valign="top">
                            <div id="flashcontent2">
                                <strong>You need to upgrade your Flash Player</strong>
                            </div>
                            <script type="text/javascript">
                              x = Math.random() * Math.random();
                              var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
                              so.addVariable("path", "ampie/");  
                              so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart    
                              so.addParam("wmode", "transparent");
                              so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                              so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                              so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeCityData.xml?t="+x));
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
                     <td height="163" scope="col" valign="top" align="center">
                    <?php
                         //*************Used For Creating*********
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->tableBlueHeader('Designation Wise Breakup','width=320' ,'height=150','align=center');
                        echo UtilityManager::includeJS("swfobject.js");
                        $flashPath = IMG_HTTP_PATH."/ampie.swf";
                    ?>           
                    <table width="100%" height="150" border="0">
                    <tr>
                        <td valign="top" align="center">
                        <table width="100%" border="0">
                        <tr>
                            <td valign="top">
                            <div id="flashcontent3">
                                <strong>You need to upgrade your Flash Player</strong>
                            </div>
                            <script type="text/javascript">
                              x = Math.random() * Math.random();
                              var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
                              so.addVariable("path", "ampie/");  
                              so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart    
                              so.addParam("wmode", "transparent");
                              so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                              so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                              so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeDesignationData.xml?t="+x));
                               so.addVariable("preloader_color", "#999999");
                                so.write("flashcontent3");
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
                     <tr>
                    
                    <td height="163" scope="col" valign="top" align="center">
                    <?php
                         //*************Used For Creating*********
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->tableBlueHeader('Branch Wise Breakup','width=320' ,'height=150','align=center');
                        echo UtilityManager::includeJS("swfobject.js");
                        $flashPath = IMG_HTTP_PATH."/ampie.swf";
                    ?>           
                    <table width="100%" height="222" border="0">
                    <tr>
                        <td valign="top" align="center">
                        <table width="100%" border="0" align="center">
                        <tr>
                            <td valign="top">
                            <div id="flashcontent5">
                                <strong>You need to upgrade your Flash Player</strong>
                            </div>
                            <script type="text/javascript">
                              x = Math.random() * Math.random();
                             var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
                              so.addVariable("path", "ampie/");  
                              so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart    
                               so.addParam("wmode", "transparent");
                               so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                               so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                               so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeBranchData.xml?t="+x));
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
                   <td height="163" scope="col" valign="top" align="center">
                    <?php
                         //*************Used For Creating*********
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->tableBlueHeader('Gender Wise Breakup','width=320' ,'height=150','align=center');
                        echo UtilityManager::includeJS("swfobject.js");
                        $flashPath = IMG_HTTP_PATH."/ampie.swf";
                    ?>           
                    <table width="100%" height="222" border="0">
                    <tr>
                        <td valign="top" align="center">
                        <table width="100%" border="0" align="center">
                        <tr>
                            <td valign="top">
                            <div id="flashcontent6">
                                <strong>You need to upgrade your Flash Player</strong>
                            </div>
                            <script type="text/javascript">
                              x = Math.random() * Math.random();
                             var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
                             so.addVariable("path", "ampie/");  
                             so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
                             so.addParam("wmode", "transparent");
                              so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
                              so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
                               so.addVariable("data_file", encodeURIComponent("../Templates/Xml/employeeGenderData.xml?t="+x));
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
                  <td height="163" scope="col" valign="top" align="center">
                    <?php
                         //*************Used For Creating*********
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                        echo HtmlFunctions::getInstance()->tableBlueHeader('Custom Details','width=320' ,'height=150','align=center');
                        echo UtilityManager::includeJS("swfobject.js");
                        $flashPath = IMG_HTTP_PATH."/ampie.swf";
                    ?>           
                    <table width="100%" height="222" border="0">
                    <tr>
                        <td valign="top" align="center">
                        <table width="100%" border="0" align="center" height="205">
                        <tr>    
                            <td class="padding" valign="top"><form action="" method="POST" name="searchForm" id="searchForm">
                            <select size="1" name="searchStudent" id="searchStudent"class="inputbox1" onChange="return getSearch();">
                                <option value="">Select</option>
                                <option value="RoleWise" <?php if($_REQUEST['searchStudent']=='RoleWise') echo "Selected";?>>Role Wise</option>
                                <option value="Marital" <?php if($_REQUEST['searchStudent']=='Marital') echo "Selected";?>>Marital Status</option>
                                <option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option>
                            </select>
                            </form>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" class="content" align="center"><div id="resultsDiv"></div></td>
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
                <tr>
                    <td valign="top" class="content" colspan="3">
                       
                      
                  
                         
                    </td>
                </tr>
            </table>

        </table>
        </table>
        </table>
<?php 

?>
