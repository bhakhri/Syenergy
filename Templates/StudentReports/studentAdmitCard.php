<?php 
//-------------------------------------------------------
// Purpose: to design the layout for student admit card.
//
// Author : Jaineesh
// Created on : (08.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
			   //DISPLAYS ALL RECORDS 
               require_once($FE . "/Library/common.inc.php");
               global $sessionHandler;
                $studentCount = count($studentArray);

				$i = 0;
			if($studentCount >0 && is_array($studentArray)) {
				foreach($studentArray as $studentRecord) {
					$j=$i+1;
					if($i %2 == 0) {
						?><table border="1" cellpadding="0" cellspacing="3" rules="ALL"><tr><?php
					} ?>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="400" style="font-size:10px">
								<tr>
									<td colspan="2" align="center"><b><?php echo $sessionHandler->getSessionVariable('InstituteName') ?></b></td>
								</tr>
								<tr>
									<td colspan="2" height="5px"></td>
								</tr>
								<tr>
									<td style="padding-left:5px" width="130px" ><strong>Name of Student</strong></td>
									<td ><?php echo strip_slashes($studentArray[$i]['firstName'])?>  <?php echo strip_slashes($studentArray[$i]['lastName'])?> </td>
								</tr>
								<tr>
									<td colspan="2" height="5px"></td>
								</tr>
								<tr>
									<td style="padding-left:5px"><b>Father&acute;s Name</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['fatherName']))?></td>
								</tr>
								<tr>
									<td colspan="2" height="5px"></td>
								</tr>
								<tr>
									<td style="padding-left:5px"><b>Roll No.</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['rollNo']))?></td>
								</tr>
								<tr>
									<td colspan="2" height="5px"></td>
								</tr>
								<tr>
									<td style="padding-left:5px"><b>Univ. Roll No.</b></td><td><?php echo strip_slashes($studentArray[$i]['universityRollNo']) ?></td>
								</tr>
								<tr>
										<td colspan="2" height="5px"></td>
								</tr>
								<tr>
										<td style="padding-left:5px"><b>Degree</b></td>
										<td><?php echo strip_slashes($studentArray[$i]['degreeName'])?></td>
								</tr>
								<tr>
									<td colspan="2" height="5px"></td>
								</tr>
								<tr>
										<td style="padding-left:5px"><b>Semester</b></td>
										<td><?php echo strip_slashes($studentArray[$i]['periodName'])?>  <?php echo strip_slashes($studentArray[$i]['periodicityName']) ?></td>
								</tr>
								<tr>
									<td colspan="2" height="30px"></td>
								</tr>
								<tr>
										<td style="padding-left:5px" align="center"><b>Seal</b></td>
										<td align="center"><b>Signature of Coordinator</b></td>
								</tr>
							</table></td>
							<?php 
								$i++; 
								if($i %2 == 0) { 
									?></tr></table>
								<?php if($j%10==0) { ?>
									<BR CLASS='page'>
								<?php } ?>
							<?php
								}
							}
							
						}
						else { 
							echo '<table><tr><td colspan="5" align="center">No record found</td></tr></table>';
						}?> 
    
  


<?php 
// $History: studentAdmitCard.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/16/08    Time: 4:46p
//Updated in $/Leap/Source/Templates/StudentReports
//modified for report student information
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/07/08    Time: 8:06p
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 5  *****************
//User: Administrator Date: 8/05/08    Time: 3:51p
//Updated in $/Leap/Source/Templates/StudentReports
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/04/08    Time: 7:17p
//Updated in $/Leap/Source/Templates/StudentReports
//modification in design
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:16p
//Updated in $/Leap/Source/Templates/StudentReports
//remove class
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/10/08    Time: 11:06a
//Created in $/Leap/Source/Templates/Reports
//templates for student icard, bus pass, hostel card, admit card, photo
//gallery, library card

?>
    <!--End: Div To Edit The Table-->
    


