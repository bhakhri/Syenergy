<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Upload Student Details : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="uploadForm"  id="uploadForm" action="<?php echo HTTP_LIB_PATH;?>/Placement/Student/uploadStudentDetailFile.php?marksIds" method="post" enctype="multipart/form-data" style="display:inline" onsubmit="return false;" >
                   <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <td class="contenttab_internal_rows" width="5%" nowrap="nowrap"><b>Placement Drive</b></td>
                    <td class="padding" width="1%">:</td>
                    <td class="padding" width="10%">
					
                      <select name="placementDriveId" id="placementDriveId" class="inputbox"   onChange="getData();" style="width:350px;">
                       <option value="">Select</option>
                       <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         $conditions =' AND 
                                            (
                                              placementDriveId NOT IN ( SELECT DISTINCT placementDriveId FROM placement_eligibility_list)
                                              OR 
                                              placementDriveId NOT IN ( SELECT DISTINCT placementDriveId FROM placement_results)
                                             )
                                      ';
                         echo HtmlFunctions::getInstance()->getPlacementDrives(' ',$conditions);
                       ?>
                      </select>
                    </td>
                     <td class="contenttab_internal_rows" width="5%" nowrap="nowrap" ><b>Choose File</b> (.xls extention)</td>
                     <td class="padding" width="1%">:</td>
                     <td class="padding" width="5%">
                      <input type="file" name="studentFile" id="studentFile" class="inputbox" />
                     </td>
                     <td class="padding" style="padding-left:20px;">
                       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="return initAdd();" />
                     </td>
                    </tr> 
					<tr	style="width:100%;">
					
                      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
						<tr>	
							<td colspan="10" align="left" class="contenttab_internal_rows"><b>To check the file format with .xls extension.Please select Placement Drive:</b></td>
						</tr>
						<tr>
							<td colspan="10" align="left" width="98%">
							<div id="summeryDiv" style="width:100%;display:none;">
					
								<table border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%" align="center">
									<tr>
									   <td class="contenttab_internal_rows"><b>Sr.No</b></td>
									   <td class="contenttab_internal_rows"><b>Title</b></td>
									   <td class="contenttab_internal_rows"><b>Candidate Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>Father's Name&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>DOB&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>Corr. Addr.</b></td>
									   <td class="contenttab_internal_rows"><b>Perm. Addr.</b></td>
									   <td class="contenttab_internal_rows"><b>Home Town</b></td>
									   <td class="contenttab_internal_rows"><b>Landline No. with STD Code</b></td>
									   <td class="contenttab_internal_rows"><b>Mobile No.</b></td>
									   <td class="contenttab_internal_rows"><b>Email</b></td>
									   <td class="contenttab_internal_rows"><b>Gender&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>Course&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>Discipline&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>Marks in 10th (%)&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>Marks in 12th (%)&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><span id ="td1"><b>% in graduation</b></span><?php echo REQUIRED_FIELD;?>
									   <input type="hidden" name="marksIds" id="marksIds"></td>
									   <td class="contenttab_internal_rows"><b>College&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									   <td class="contenttab_internal_rows"><b>University&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
									</tr>
									<tr>
										   <td class="contenttab_internal_rows1" align="right">1</td>
										   <td class="contenttab_internal_rows">Ms</td>
										   <td class="contenttab_internal_rows">Gauri</td>
										   <td class="contenttab_internal_rows">Mr. Mittal</td>
										   <td class="contenttab_internal_rows">20.03.1992</td>
										   <td class="contenttab_internal_rows">Punjab</td>
										   <td class="contenttab_internal_rows">Punjab</td>
										   <td class="contenttab_internal_rows">Patiala</td>
										   <td class="contenttab_internal_rows"></td>
										   <td class="contenttab_internal_rows">99998888</td>
										   <td class="contenttab_internal_rows">g@gmail.com</td>
										   <td class="contenttab_internal_rows">Female</td>
										   <td class="contenttab_internal_rows">BTECH</td>
										   <td class="contenttab_internal_rows">CSE</td>
										   <td class="contenttab_internal_rows">60</td>
										   <td class="contenttab_internal_rows">65</td>
										   <td class="contenttab_internal_rows">75</td>
										<!--   <td class="contenttab_internal_rows"></td> -->
										   <td class="contenttab_internal_rows">College1</td>
											<td class="contenttab_internal_rows">Univ1</td>
									</tr>
                         
									<tr>
										   <td class="contenttab_internal_rows1" align="right">2</td>
										   <td class="contenttab_internal_rows">Ms</td>
										   <td class="contenttab_internal_rows">Sonakshi</td>
										   <td class="contenttab_internal_rows">Mr. Sharma</td>
										   <td class="contenttab_internal_rows">10.05.1993</td>
										   <td class="contenttab_internal_rows">Punjab</td>
										   <td class="contenttab_internal_rows">Punjab</td>
										   <td class="contenttab_internal_rows">Patiala</td>
										   <td class="contenttab_internal_rows"></td>
										   <td class="contenttab_internal_rows">90809090</td>
										   <td class="contenttab_internal_rows">s@gmail.com</td>
										   <td class="contenttab_internal_rows">Female</td>
										   <td class="contenttab_internal_rows">BTECH</td>
										   <td class="contenttab_internal_rows">CSE</td>
										   <td class="contenttab_internal_rows">75</td>
										   <td class="contenttab_internal_rows">85</td>
										   <td class="contenttab_internal_rows">75</td>
										 <!--  <td class="contenttab_internal_rows">65</td> -->
										   <td class="contenttab_internal_rows">College2</td>
										   <td class="contenttab_internal_rows">Univ2</td>
									</tr>
                         
									 <tr>
										   <td class="contenttab_internal_rows1" align="right">3</td>
										   <td class="contenttab_internal_rows">Ms</td>
										   <td class="contenttab_internal_rows">Sherya</td>
										   <td class="contenttab_internal_rows">Mr. Aggarwal</td>
										   <td class="contenttab_internal_rows">10.05.1995</td>
										   <td class="contenttab_internal_rows">Punjab</td>
										   <td class="contenttab_internal_rows">Punjab</td>
										   <td class="contenttab_internal_rows">Zirakpur</td>
										   <td class="contenttab_internal_rows"></td>
										   <td class="contenttab_internal_rows">80908080</td>
										   <td class="contenttab_internal_rows">s@gmail.com</td>
										   <td class="contenttab_internal_rows">Female</td>
										   <td class="contenttab_internal_rows">BTECH</td>
										   <td class="contenttab_internal_rows">ECE</td>
										   <td class="contenttab_internal_rows">75</td>
										   <td class="contenttab_internal_rows">85</td>
										   <td class="contenttab_internal_rows">80</td>
										<!--   <td class="contenttab_internal_rows"></td>  -->
										   <td class="contenttab_internal_rows">College3</td>
										   <td class="contenttab_internal_rows">Univ3</td>
								   </tr>
								
									 <tr>
					
									<td class="contenttab_internal_rows" align="left" colspan="20"><b><u>***Please Note***</u></b>
									</td>
								</tr> 
								 <tr>
									<td colspan="20" align="left" class="contenttab_internal_rows">
									<b><font color="red">1.&nbsp;Date must be in DD.MM.YYYY format(use . as seperator).</font></b><br/>
									<b><font color="red">2.&nbsp;Columns marks with * are compulsory.</font></b><br/>
									<b><font color="red">3.&nbsp;Any one of landline,mobile and email is mandatory.</font></b><br/>
									<b><font color="red">4.&nbsp;Combination of candidate name , father's name , dob , landline , mobile and email must be unique.</font></b><br/>
									<b><font color="red">5.&nbsp;Enter %age marks in <u>10th,12th and&nbsp;<span id='showMarks'>Graduation</span></u>&nbsp;field.</font></b><br/>
									</td>
								 </tr>
								</table>
						</div>
                    </td>
					
		
                    </tr>
					</table></tr>
                    
			
                   </table>
                   <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:00px;height:00px;border:0px solid #fff;"></iframe>
                 </form>  
                </td>
             </tr>
			
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>

<?php
// $History: listEventContents.php $
?>