<?php
//-------------------------------------------------------
// Purpose: to design the layout for student identity.
//
// Author : Jaineesh
// Created on : (08.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
						<?php //DISPLAYS ALL RECORDS 
						if(isset($REQUEST_DATA['task'])) {
							if($REQUEST_DATA['task'] == 'IC') {
								$heading = 'IDENTITY CARD';
							}
							elseif($REQUEST_DATA['task'] == 'BP') {
								$heading = 'BUS PASS';
							}
							elseif($REQUEST_DATA['task'] == 'HC') {
								$heading = 'HOSTEL CARD';
							}
							elseif($REQUEST_DATA['task'] == 'LC') {
								$heading = 'LIBRARY CARD';
							}
						}

						$studentCount = count($studentArray);
						require_once($FE . "/Library/common.inc.php");
						global $sessionHandler;
						if($studentCount >0 && is_array($studentArray)) {
							//for($i=0; $i<$studentCount; $i++ ) {
							for($i=0; $i<$studentCount; $i++ ) {
								$j = $i+1;
							?>
							<table rules="all" border="1"><tr><td><table border="0" cellpadding="0" cellspacing="0" width="400" style="font-size:10px">
								<tr><td><?php
										if(file_exists(IMG_PATH.'/Institutes/'.$sessionHandler->getSessionVariable('InstituteLogo'))=="") {
											echo "<IMG src=\"".IMG_HTTP_PATH;"/Institutes/".$sessionHandler->getSessionVariable('InstituteLogo')."\" height=\"50px\" width=\"50px\">";										}
										else { ?><table rules="all" border="1" width="60px" height="40px"><tr><td></td></tr></table><?php } ?></td>
									<td ></td>
									<td align="right" style="padding-right:10px"><strong>S.No.&nbsp;&nbsp;</strong><?php echo strip_slashes($studentArray[$i]['studentId'])?></td></tr>
								<tr><td align="center" colspan="3"><U><b><?php echo $heading; ?></b></U></td></tr>
								<tr><td colspan="3" height="10px"></td></tr>
								<tr><td style="padding-left:5px" ><strong>Roll No.</strong></td>
										<td ><?php echo strip_slashes($studentArray[$i]['rollNo'])?></td>
										<td align="center" style="padding-right:3px" rowspan="6" valign="top">
										<?php
											if(strip_slashes($studentArray[$i]['studentPhoto']=="")) {
												echo '<table rules="all" border="1" width="80px" height="80px"><tr><td></td></tr></table>';
											} 
											else { 
												echo "<img src=\"".IMG_HTTP_PATH."/Student/".strip_slashes($studentArray[$i]['studentPhoto'])."\" height=\"80px\" width=\"80px\" valign=\"middle\" >";
											}
										?>
										</td></tr>
								<tr><td colspan="3" height="5px"></td></tr>
								<tr><td style="padding-left:5px"><b>Name</b></td>
										<td><?php echo strtoupper(strip_slashes($studentArray[$i]['firstName'])).' '.strtoupper(strip_slashes($studentArray[$i]['lastName'])) ?>
										</td></tr>
								<tr><td colspan="3" height="5px"></td></tr>
								<tr><td style="padding-left:5px"><b>Father Name</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['fatherName'])) ?></td></tr>
								<tr><td colspan="3" height="5px"></td></tr>
								<tr><td style="padding-left:5px"><b>Degree</b></td>
									<td><?php echo strip_slashes($studentArray[$i]['degreeName'])?></td></tr>
								<tr><td colspan="3" height="5px"></td></tr>
								<tr><td style="padding-left:5px"><b>Valid Till</b></td><td><?php echo strip_slashes($studentArray[$i]['dateOfLeaving']) ?></td>
										<td align="center" style="padding-right:10px"><strong>Auth. sig.</strong></td></tr>
								<tr><td colspan="3" height="10px"></td></tr>
								<tr><td align="center" valign="top" colspan="2"><strong>CHITKARA EDUCATIONAL TRUST</strong></td>
                                    <td align="center" style="padding-right:5px" rowspan="2" ><div style="width:60px;height:40px;border:1px solid black"></div></td>
								</tr>
								<tr><td colspan="2" align="center" valign="top"><b>www.chitkara.edu.in</b></td></tr>
							</table></td><td><table border="0" cellpadding="0" cellspacing="0" width="400" style="font-size:10px">
								<tr><td style="padding-left:5px" width="100px" ><b>Address</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['permAddress1']))?></td></tr>
								<tr><td colspan="2" height="5px"></td></tr>
								<tr><td style="padding-left:5px"><b>Contact No.</b></td>
									<td><?php echo strip_slashes($studentArray[$i]['studentMobileNo'])?></td></tr>
								<tr><td colspan="2" height="5px"></td></tr>
								<tr><td style="padding-left:5px"><b>Date of Birth</b></td>
									<td><?php echo strip_slashes($studentArray[$i]['dateOfBirth'])?></td></tr>
								<tr><td colspan="2" height="5px"></td></tr>
								<tr><td style="padding-left:5px" colspan="2"><b>Instructions</b></td></tr>
								<tr><td colspan="2" height="5px"></td></tr>
								<tr><td colspan="2" style="padding-left:10px">1.This Card is The Property Of The Institute</td></tr>
								<tr><td colspan="2" style="padding-left:10px">2.The Bearer is Requested to Keep The I Card With Him/Her</td></tr>
								<tr><td colspan="2" style="padding-left:10px">3.Duplicate Card Will Be Issued With A Fine Of Rs.500/- </td></tr>
								<tr><td colspan="2" height="5px"></td></tr>
								<tr><td style="padding-left:5px" colspan="2" align="center"><strong>If Found Please Return At Saraswati Kendra SCO 160-161,</strong></td></tr> 
								<tr>
									<td style="padding-left:5px" colspan="2" align="center"><strong>Sector 9-C Chandigarh,0172-2746209,2747057</strong></td>
								</tr>
								<tr><td colspan="2" height="10px"></td></tr>
								<tr><td style="padding-left:5px" colspan="2" align="center"><b><?php echo strip_slashes($studentArray[$i]['regNo'])?></b></td>
								</tr>
								</table></td></tr></table>
								<?php
								if ($j%4==0) { ?><BR CLASS="page"><?php 
								}
							}
						}

						else { 
							echo '<table><tr><td colspan="5" align="center">No record found</td></tr></table>';
						}
					?>





<?php 
// $History: studentIdentityCard.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/18/08    Time: 3:43p
//Updated in $/Leap/Source/Templates/StudentReports
//modification in template
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/16/08    Time: 4:46p
//Updated in $/Leap/Source/Templates/StudentReports
//modified for report student information
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/06/08    Time: 3:06p
//Updated in $/Leap/Source/Templates/StudentReports
//modification in report
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/06/08    Time: 10:39a
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 6  *****************
//User: Admin        Date: 8/05/08    Time: 6:34p
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/05/08    Time: 3:51p
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/18/08    Time: 2:43p
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:12p
//Updated in $/Leap/Source/Templates/StudentReports
//remove bread crum
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/10/08    Time: 5:17p
//Updated in $/Leap/Source/Templates/StudentReports
//modification for print report
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/10/08    Time: 11:06a
//Created in $/Leap/Source/Templates/Reports
//templates for student icard, bus pass, hostel card, admit card, photo
//gallery, library card

?>
<!--End: Div To Edit The Table-->



