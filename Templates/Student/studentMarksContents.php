<?php 
//It contains the marks of student 
//
// Author :Jaineesh
// Created on : 05.12.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
               <!-- <td valign="top">Student Info&nbsp;&raquo;&nbsp;Display Marks</td> -->
                <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                <td valign="top" align="right">   
               
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
              
             </tr>
			 <tr>
						<td class="contenttab_border2" valign="top" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
                
						<tr class="row0">
							<td valign="top">
								<form name="studentMarks" id="studentMarks">
									<table border="0" cellspacing="1" cellpadding="1" width="100%" align="right">
										<tr>

											<td width="80%" class="contenttab_internal_rows1" align="right"><nobr><b>Study Period : </b></nobr></td>
											
											<td class="padding"><select size="1" class="selectfield" name="semesterDetail" id="semesterDetail" onChange="getStudentMarks(this.value);">
											<option value="0" selected="selected">All</option>
												<?php
													$studentId = $sessionHandler->getSessionVariable('StudentId');
													require_once(BL_PATH.'/HtmlFunctions.inc.php');
													echo HtmlFunctions::getInstance()->getStudyPeriodName($studentId,$classId);
												?>
												</select>
											</td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
						<tr>
						<td valign="top" ><div id="results"></div>
   
						</td>
						
					</td></tr></table>
				</td>
			</tr><tr height=10></tr> 
						<tr>
							<td align="right" colspan="7" style="padding-right:18px">
								<input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();"/>&nbsp;<input type="image" name="printStudentMarksSubmit" id='generateCSV' onClick='printCSV();return false' src="<?php echo IMG_HTTP_PATH;?>/excel.gif" value="printStudentMarksSubmit" />
							</td>
						</tr>
				</table>
				</td>
			</tr>
    </table>


				
<?php 
//$History : $

?>
