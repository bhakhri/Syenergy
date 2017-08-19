<?php 
//It contains the marks of student 
//
// Author :Jaineesh
// Created on : 05.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Student Activities&nbsp;&raquo;&nbsp;Marks Obtained</td>
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
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Marks Obtained :</td>
                        <td class="content_title" title="Print" align="right" style="padding-right:20px">
                            <input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;
                            <!-- <a id='generateCSV' href='#' onClick='javascript:printCSV();'><img src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></a> -->
                        </td> 
                    </tr>
                    </table>
                </td>
             </tr>
			 <tr>
							<td valign="top">
								<form name="attendance" id="attendance">
									<table border="0" cellspacing="1" cellpadding="1" width="100%" align="right">
										<tr>

											<td width="80%" class="contenttab_internal_rows"><nobr><b>Study Period : </b></nobr></td>
											
											<td class="padding"><select size="1" class="selectfield" name="semesterDetail" id="semesterDetail" onChange="getStudentMarks(this.value)">
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
                <td class="contenttab_row" valign="top" ><div id="results"></div>
   
							</td>
						</tr>
						 
						</table>
						<tr>
							<td align="right" colspan="7" style="padding-right:18px">
								<input type="image" name="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();"/>&nbsp;
                                <!-- <a id='generateCSV1' href='#' onClick='javascript:printCSV();'><img src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></a> -->
							</td>
						</tr>
				</td>
			</tr>
    </table>


				
<?php 
//$History : $

?>
