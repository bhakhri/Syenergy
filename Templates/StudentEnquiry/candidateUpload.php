<?php 
//-------------------------------------------------------
//  This File contains html code for Candidate Upload
//
//
// Author :Vimal Sharma
// Created on : 07-Feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="360">
                <tr>
                    <td valign="top" class="content" height=50>
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form action="<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/candidateFileUpload.php" id="uploadForm" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd">
                                        <table align="center" border="0" cellpadding="0">
                                            <tr>
                                                <td colspan="1" align="right" valign="top" >
                                                    <strong>Select File :</strong> &nbsp;
                                                </td>
                                                <td valign="top" rowspan='1'>
                                                    <input type="file" id="candidateUploadFile" name="candidateUploadFile" class="inputbox1" <?php echo $disableClass?>/>
                                                    <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px;"></iframe>  
                                                </td>
                                                <td align="left" colspan="2" valign="top">
                                                  <span style="padding-right:10px" >
                                                    <input type="image" name="uploadSubmit" value="uploadSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload_candidate_list.gif"  />
                                                  </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Rank Upload -->
                <tr>
                    <td valign="top" class="content"  height=50>
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form  action="<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/candidateRankFileUpload.php" id="uploadForm1" method="post" enctype="multipart/form-data" style="display:inline" target="uploadTargetAdd1">
                                        <table align="center" border="0" cellpadding="0">
                                            <tr>
                                                <td colspan="1" align="right" valign="top" >
                                                    <strong>Select File :</strong> &nbsp;
                                                </td>
                                                <td valign="top" rowspan='1'>
                                                    <input type="file" id="candidateUploadFile" name="candidateUploadFile" class="inputbox1" <?php echo $disableClass?>/>
                                                    <iframe id="uploadTargetAdd1" name="uploadTargetAdd1" src="" style="width:0px;height:0px;border:0px;"></iframe>
                                                </td>
                                                <td align="left" colspan="2" valign="top">
                                                    <span style="padding-right:10px" >
                                                    <input type="image" name="uploadSubmit" value="uploadSubmit" src="<?php echo IMG_HTTP_PATH;?>/upload_candidate_rank.gif"  />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                 <td valign="top" class="contenttab_row1">  
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">  
                       <tr>
                            <td width='2%' class="contenttab_internal_rows"><nobr><b>Note</b></nobr></td>
                            <td width='98%'  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td> 
                       </tr>     
                       </tr>
                            <td class="contenttab_internal_rows" colspan='2'>
                                Kindly follow the instructions strictly before uploading file.<br>
                                Click <a class='redLink' href='<?php echo UI_HTTP_PATH;?>/downloadEnquiryUploadInstructions.php'>here</a> to download instructions.
                            </td>
                        </tr>
                    </table>
<BR/>
  
<table align="center" border="0" cellpadding="0" width="100%" >
<tr id='showSubjectEmployeeList'> 
                                          <td class="contenttab_internal_rows" align="center" colspan="20">

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
<B><U>UPLOAD CANDIDATE LIST</U></B><br />
<div id="Div12" style="overflow:auto; width:1000px;">
<table border="1" cellpadding="0" cellspacing="0" width="100%">
                         <tr>
                           <td class="contenttab_internal_rows"><b>SERIAL NO.</b></td>
                           <td class="contenttab_internal_rows"><b> FIRST_NAME&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>LAST_NAME&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>RELATION NAME</b></td>
                           <td class="contenttab_internal_rows"><b>RELATION [F/M/G] </b></td>
                           <td class="contenttab_internal_rows"><b> GENDER [M/F]</b></td>

			<td class="contenttab_internal_rows"><b> DOB [DD.MM.YY]  </b></td>
			<td class="contenttab_internal_rows"><b>COMPETITION ROLL NO.&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b> STUDENT MOBILE NO. &nbsp;<?php echo REQUIRED_FIELD;?></b></td>
			<td class="contenttab_internal_rows"><b> FATHER'S MOBILE NO. </b></td>
                           <td class="contenttab_internal_rows"><b> HOSTEL [Y/N] </b></td>
                           <td class="contenttab_internal_rows"><b> STUDENT EMAIL ID</b></td>
			<td class="contenttab_internal_rows"><b>COMPETITION EXAM BY</b></td>
                           <td class="contenttab_internal_rows"><b>COMPETITION EXAM RANK</b></td>
                         </tr>
                         <tr>
                                 <td class="contenttab_internal_rows">1.</td>
                           <td class="contenttab_internal_rows">MEENA</td>
                           <td class="contenttab_internal_rows">KAKKAR</td>
                           <td class="contenttab_internal_rows">SUNIL KAKKAR</td>
                           <td class="contenttab_internal_rows">F</td>
                           <td class="contenttab_internal_rows">F</td>
			<td class="contenttab_internal_rows">1990.07.09</td>
			<td class="contenttab_internal_rows">564443</td>
			<td class="contenttab_internal_rows">9973855635</td>
			<td class="contenttab_internal_rows">9728734545</td>
                           <td class="contenttab_internal_rows">N</td>
                           <td class="contenttab_internal_rows">meena@gmail.com</td>
			<td class="contenttab_internal_rows">UNIVERSITY</td>
                           <td class="contenttab_internal_rows">234</td>
                         </tr>
                          <tr>
                                 <td class="contenttab_internal_rows">2</td>
                           <td class="contenttab_internal_rows">NITIN</td>
                           <td class="contenttab_internal_rows">KUMAR</td>
                           <td class="contenttab_internal_rows">MUNISH KUMAR</td>
                           <td class="contenttab_internal_rows">F</td>
                           <td class="contenttab_internal_rows">M</td>
			<td class="contenttab_internal_rows">1990.01.01</td>
			<td class="contenttab_internal_rows">7384757</td>
			<td class="contenttab_internal_rows">936456456</td>
			<td class="contenttab_internal_rows">94566456</td>
                           <td class="contenttab_internal_rows">N</td>
                           <td class="contenttab_internal_rows">nitin@gmail.com</td>
			<td class="contenttab_internal_rows"></td>
                           <td class="contenttab_internal_rows">200</td>
                         </tr>
                        </table>
			<br/>
<B><U>UPLOAD CANDIDATE RANK</U></B><br/>
 
<table border="1" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                           <td class="contenttab_internal_rows"><b>SERIAL NO.</b></td>
                          <td class="contenttab_internal_rows"><b>COMPETITION ROLL NO.&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                           <td class="contenttab_internal_rows"><b>COMPETITION EXAM RANK&nbsp;<?php echo REQUIRED_FIELD;?></b></td>
                         </tr>
                          <tr>
                           <td class="contenttab_internal_rows">1.</td>
                          <td class="contenttab_internal_rows">564443</td>
                           <td class="contenttab_internal_rows">234</td>
                         </tr>
                        <tr>
                           <td class="contenttab_internal_rows">2.</td>
                          <td class="contenttab_internal_rows">7384757</td>
                           <td class="contenttab_internal_rows">200</td>
                         </tr>
                        </table>
			<br/>
			<b><u>***Please Note***</u><b><br/>
			
                    	 <b><font color="red">1. Columns marks with * are compulsory</font></b><br/>
                    	 <b><font color="red">2. Columns must be in the same order as in above mentioned format</b><br/>
			 <b><font color="red">3. Not even a single column should be removed or added</font></b><br/>
			
										
		</span></nobr>
                                                  </td>
                                                 </tr> 
                                              </table>
                                             
                                          </td>
                                     </tr>
</table>
</div>

                 </td>   
                </tr>
                <tr>
                    <td height="250">
                    </td>
                </tr>
            </table>

        </td>            
    </tr>
    <tr>
            <td height="10"></td>
    </tr>
</table>
               
