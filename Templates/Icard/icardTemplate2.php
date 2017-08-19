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
        $icardData .= '<td width="100px" valign="top" align="left" style="padding-left:5px">';
        //$icardData .=  "<img src=\"".IMG_HTTP_PATH."/chitkara_logo.gif\" height=\"45px\" valign=\"middle\" >";   
        //$icardData .=  "<INSTLOGO>";
        $icardData .=  "<StudentPhoto>";
        $icardData .= '<td width="220px" height="50px" valign="top" align="center">
                        <table cellpadding="0px" cellspacing="0px" border="0">
                          <tr>
                            <td align="center"> 
                                <span style="font-size: 14px; COLOR: #000; FONT-FAMILY:arial;z-index:-2px;line-height:15px">
                                <span class="icardTitle" valign="top"><instituteName></span><br>
                                <b><icardTitle></b></span>
                            </td>
                          </tr>   
                        </table>   
                        <span class="icardContent" style="vertical-align:bottom;padding-left:120px;line-height:25px">
                        Sr. No.&nbsp;:&nbsp;IC-<StudentId>&nbsp;
                        </span> 
                      </td>';
                      
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td colspan="2" valign="top">';
        $icardData .= '<table cellpadding="0px" cellspacing="0px" border="0">';
        $icardData .= '<tr>';
        $icardData .= '<td rowspan="6" valign="bottom" width="100px"><INSTLOGO> </td>';
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
        $icardData .= '<td>&nbsp;</td>';
        $icardData .= '<td  align="center" class="icardContent">'; 
        $icardData .= "<img height='35px' width='60px' src='".IMG_HTTP_PATH."/Icard/".nl2br($icardSignature)."' valign='top'><br> 
        Authorised Signatory";
        $icardData .= '</td>';
        $icardData .= '</tr>';
        $icardData .= '</table>';
         $icardData .= '</td>';
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
        $icardData .= '<td valign="top" colspan="2" class="icardData">'.nl2br($icardInstructions).'</td>';
        $icardData .= '</tr>';
        $icardData .= '<tr>';
        $icardData .= '<td  valign="top" colspan="2" class="icardData" align="center"> 
        If found please return at <COLLEGEADDRESS> 
        </td>';
        $icardData .= '</tr>';
        /*$icardData .= '<tr>';
        $icardData .=  "<td  valign='top' colspan='2' align='center'>
                        <img src=\"".IMG_HTTP_PATH."/Icard/barcode.jpg\" valign=\"top\"></td>";  
                        </tr>';*/
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