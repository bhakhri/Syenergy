<?php 
    $admitLogo = $sessionHandler->getSessionVariable('ADMIT_INSTITUTE_LOGO');
    $admitPhoto  = $sessionHandler->getSessionVariable('ADMIT_PHOTO');
    $admitShowNo  = $sessionHandler->getSessionVariable('ADMIT_SHOW_NO');
    $showName='';  
    $tdColSpan="colspan='4'";
    if($admitShowNo==1) { 
      $showName = "Reg. No.";
      $showValue = "<StudentRegNo>";
      $tdColSpan="";
    }
    else if($admitShowNo==2) {
      $showName = "Univ. RNo."; 
      $showValue = "<UnivRollNo>";
      $tdColSpan="";
    }
    
    $icardData='';   
    $icardData="<table width='370px' height='190px' border='0px' cellpadding='0px' cellspacing='0px'>
                <tr>
                  <td align='left' valign='top' width='100%'>
                    <table width='100%' height='190px' border='0px' cellpadding='0px' cellspacing='0px'>
                      <tr>
                        <td valign='top' height='190px'>
                         <table width='100%' border='0px' height='190px' cellspacing='0px' cellpading='0px'>
                              <tr>";
                                  $colspan='7';  
                                  if($admitLogo==1) { 
                                    $icardData .="<td  align='center' class='tdBorder' colspan='2'> 
                                        <INSTLOGO>
                                    </td>";    
                                    $colspan='5';
                                  }  
                                  $icardData .="<td  align='center' class='tdBorder' colspan='$colspan'>
                                    <b><font size='2'><instituteName></font><br>
                                    <font size='2'><HEADING1><bR><HEADING2></font></b>
                                </td>
                              </tr>";
                              $colspan = $colspan-1;
                              $icardData .="<tr>
                                <td width ='23%' align='left' class='icardHeading' nowrap>Name of Student</td>
                                <td width ='1%'  align='left' class='icardHeading'><b>:</b></td>
                                <td width ='69%' align='left' class='tdBorder' colspan='$colspan'><StudentName></td>";
                                
                                if($admitPhoto==1) {
                                   $icardData .="<td width ='69%' align='right' valign='top' rowspan='4'>
                                                   <StudentPhoto>
                                                 </td>";     
                                }
                                else {
                                   if($tdColSpan!='') {
                                     $tdColSpan = "colspan='6'";  
                                   }   
                                }
                                $icardData .="
                              </tr>
                              <tr>
                                <td align='left' class='icardHeading' >Father's Name</td> 
                                <td align='left' class='icardHeading' ><b>:</b></td>
                                <td align='left' class='tdBorder' colspan='$colspan'><FatherName></td>
                              </tr>
                              <tr>
                                  <td style='align='left' class='icardHeading' width='20%'>College RNo.</td>
                                  <td width ='1%' align='left' class='icardHeading'><b>:</b></td>       
                                  <td align='left' class='tdBorder' align='left' width='29%' $tdColSpan><StudentRollNo></td>";
                              if($showName!='') { 
                                  $icardData .="<td align='left' class='icardHeading' width='20%' nowrap><div align='right'>$showName</div></td>
                                  <td width ='1%' align='left' class='icardHeading'><b>:</b></td>       
                                  <td align='left' class='tdBorder' align='right' width='29%'>$showValue&nbsp;</td>";
                              }    
                              $icardData .="</tr>
                              <tr>
                                  <td class='icardHeading' width='20%'>Branch</td>
                                  <td width ='1%' align='left' class='icardHeading'><b>:</b></td>       
                                  <td class='tdBorder' align='left' width='79%' colspan='$colspan'><Course></td>
                             </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td  height='5px' colspan='6'>&nbsp;</td>
                              </tr>
                              <tr>
                                <td align='left' style='padding-left:25px' colspan='3' class='icardHeading'>Seal</td>
                                <td align='right' style='padding-right:25px' class='icardHeading' colspan='4'>Controller of Examination</td>
                              </tr>
                            </table>
                        </td>
                      </tr>   
                      <tr>
                            <td valign='Bottom' colspan='4' height='6px' class='tdBorder' colspan='4'>&nbsp;</td>
                      </tr>                   
                    </table>
                  </td>
                </tr> 
              </table>";
?>