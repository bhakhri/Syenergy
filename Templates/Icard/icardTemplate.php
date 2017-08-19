<?php 
    global $sessionHandler;
    
    $icardInstructions = $sessionHandler->getSessionVariable('I_CARD_INSTRUCTIONS');
    $icardSignature = $sessionHandler->getSessionVariable('EMPLOYEE_I_CARD_SIGNATURE');
    $icardData='';
    $icardData .= '<table border="0" cellpadding="5px" cellspacing="15px" >';
    $icardData .= '<tr>';
    $icardData .= '<td class="bborder" valign="top" width="320px" height="190px">'; 
    $icardData .=  '<div style="height:'.ICARD_HEIGHT.'; width:'.ICARD_WIDTH.'; overflow:hidden;">';          
        $icardData .= '<table cellpadding="1px" cellspacing="0px" width="320px" height="190px" border="0">';
        $icardData .= '<tr>';
        $icardData .= '<td width="160px" valign="top" align="left">';
        //$icardData .=  "<img src=\"".IMG_HTTP_PATH."/chitkara_logo.gif\" height=\"45px\" valign=\"middle\" >";   
        $icardData .=  "<INSTLOGO>";
        $icardData .= '<td width="160px" height="50px" valign="top" align="center">
                         <span class="icardTitle" valign="top"><instituteName></span><br>
                         <span style="font-size: 9px; COLOR: #000; FONT-FAMILY:arial;z-index:-2px;line-height:10px"><icardTitle></span>  
                      </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td colspan="2" valign="top">';
        $icardData .= '<table border="0">';
        $icardData .= '<tr>
                            <td valign="top" class="icardContent" colspan="3" align="right"> Sr. No.: IC-<StudentId>&nbsp;</td>
                       </tr>';
        $icardData .= '<tr>';
        $icardData .= '<td rowspan="5" valign="top" width="100px">
                        <StudentPhoto></td>';
        $icardData .= '<td width="75px" valign="top" class="icardContent"> Name </td>';
        $icardData .= '<td  class="icardData" width="135px" valign="top"> <StudentName> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= "<td class='icardContent'> Father's Name </td>";
        $icardData .= '<td class="icardData"> <FatherName> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  class="icardContent"> <StudentRollNo1> </td>';
        $icardData .= '<td class="icardData"> <StudentRollNo> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  class="icardContent"> Session </td>';
        $icardData .= '<td class="icardData"> <StudentSession> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  class="icardContent"> Course </td>';
        $icardData .= '<td class="icardData"> <Course> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td valign="bottom" class="icardContent" align="left">Student Signatory</td>';
        $icardData .= '<td valign="bottom" colspan="3" align="right" class="icardContent">'; 
        $icardData .= " <img height='35px' width='60px' src='".IMG_HTTP_PATH."/Icard/".nl2br($icardSignature)."' valign='top'><br> 
        Authorised Signatory";
        $icardData .= '</td>';
        $icardData .= '</tr>';
        $icardData .= '</table>
                       </td>';
        $icardData .= '</tr>';
        $icardData .= '</table>';
    $icardData .= '</div>';
    $icardData .= '</td>';


    $icardData .= '<td class="bborder" width="320px" height="190px" valign="top">';
    $icardData .=  '<div style="height:'.ICARD_HEIGHT.'; width:'.ICARD_WIDTH.'; overflow:hidden;">';          
        $icardData .= '<table cellpadding="0px" cellspacing="2px" width="320px" height="190px" border="0">';   
        $icardData .= '<tr>';
        $icardData .= '<td class="icardContent" valign="top" nowrap > Address: </td>';
        $icardData .= '<td class="icardData"  valign="top"   height="20px" ><StudentAddress> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>
						  <td valign="top" align="left" nowrap class="icardContent">Contact No.:</td>	
                          <td valign="top">
                            <table cellpadding="0px" cellspacing="0px" border="0">
                              <tr>
                               <td valign="top" align="left" width="35%" class="icardData"><StudentContact></td>
                               <td valign="top" align="left" width="20%" nowrap class="icardContent">Blood Group:</td>
                               <td valign="top" align="left" width="5%"  nowrap class="icardData"><StudentBloodGroup></td>
							  </tr>
							 </table> 
							</td>
						</tr>
						<tr>
						<td valign="top" align="left" nowrap class="icardContent">DOB:</td>
						<td valign="top">
							<table cellpadding="0px" cellspacing="0px" border="0">
								<td valign="top" align="left" width="9%" nowrap class="icardData">&nbsp;<StudentDOB></td>
							</table>
						</td>
						</tr>';

        $icardData .= '<tr><td  valign="top"  colspan="2" class="icardContent"> Instructions: </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  valign="top" colspan="2" class="icardData">'.nl2br($icardInstructions).'</td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  valign="top" colspan="2" class="icardData" align="center"> 
        If found please return at <COLLEGEADDRESS> 
        </td>';
        $icardData .= '</tr>';
      /*$icardData .= '<tr>';
        $icardData .=  "<td  valign='top' colspan='2' align='center'>
                            <img src=\"".IMG_HTTP_PATH."/Icard/barcode.jpg\" valign=\"top\">
                        </td>
                        </tr>";*/
        $icardData .= '<tr>';
        $icardData .= '<td  valign="top" colspan="2" align="center" class="icardBar"> <StudentRollNo> </td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  valign="top" colspan="2" align="center" class="icardBar"> <EMAILADDRESS> </td>';
        $icardData .= '</tr>';
        $icardData .= '</table>';
    $icardData .= '</div>';           
    $icardData .= '</td>';

    $icardData .= '</tr>';
    $icardData .= '</table>';

?>