<?php 
    global $sessionHandler;
    
    $busPassLogo = IMG_HTTP_PATH."/BusPass/".$sessionHandler->getSessionVariable('BUS_PASS_LOGO'); 
    $busPassInstructions = $sessionHandler->getSessionVariable('BUS_PASS_INSTRUCTIONS');
    $busPassInstitueName = $sessionHandler->getSessionVariable('BUS_PASS_INSTITUTE_NAME');
    $busPassFoundAddress = $sessionHandler->getSessionVariable('BUS_PASS_FOUND_ADDRESS');
    $busPassEmail = $sessionHandler->getSessionVariable('BUS_PASS_INSTITUTE_EMAIL');
    

    $icardData ='
            <table cellpadding="5px" cellspacing="15px" border="0" align="center">
            <tr>';
            
    // Front Side -- Start 
    $icardData .='        
            <td class="bborder" valign="top" align="center" height="320px" width="190px">
            <div style="height:320px; width:190px; overflow:hidden;">
            <table width="190px" height="320px" border="0" cellpadding="0px" cellspacing="0px">
            <tr>
            <td valign="top" class="icardHeading" align="center" height="45px">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                  <tr>
                     <td valign="middle" align="center" class="icardHeading"><INSTLOGO></td>
                  </tr>
                  <tr>    
                     <td valign="top" align="center" class="frontname"><b>'.nl2br($busPassInstitueName).'<br>BUS PASS</b></u></td>
                  </tr>    
                </table>
            </td>
            </tr>
            <tr>
                <td class="frontname" align="center" valign="middle">
                    <reportHeading>
                </td>
            </tr>
            <tr>
                <td class="icardtd" align="center" valign="middle" height="150px">
                    <employeePhoto>
                </td>
            </tr>
            <tr>
                <td valign="top" class="frontname" align="center">
                    <employeeName><br>
                    <designationName>
                </td>
            </tr> 
            <tr>
                <td valign="top" class="icardData" align="center">
                    <BusPassDetail>   
                </td>
            </tr>        
            </table>
            </div>
            </td>';
    // Front Side -- End
            
            
    // Back Side -- Start
    $icardData .='<td class="bborder" valign="top" align="center" height="320px" width="190px">
            <div style="height:320px; width:190px; overflow:hidden;"> 
            <table width="190px" height="320px" border="0" cellpadding="0px" cellspacing="0px">
            <tr>
                <td valign="top" width="45%" class="icardContent" align="left">Employee Code</td>
                <td valign="top" width="3%" class="icardContent" align="left">:</td>
                <td valign="top" width="52%" class="icardData" align="left" ><employeeCode></td>
            </tr>
            <tr>
            <td valign="top" width="45%" class="icardContent" align="left">Department</td>
            <td valign="top" width="3%" class="icardContent" align="left">:</td>
            <td valign="top" width="52%" class="icardData" align="left" ><department></td>
            </tr>
            <tr>
              <td valign="top" width="45%" class="icardContent" align="left">Date of Joining</td>  
              <td valign="top" width="3%" class="icardContent" align="left">:</td>
              <td valign="top" width="52%" class="icardData" align="left" ><DOJ></td>
            </tr>
            <tr>
              <td valign="top" width="45%" class="icardContent" align="left">Blood Group</td>  
              <td valign="top" width="3%" class="icardContent" align="left">:</td>
              <td valign="top" width="52%" class="icardData" align="left" ><BloodGroup></td>
            </tr>
            <tr>
            <td  valign="top" class="icardContent" align="left" colspan="3">Address: <br>
            <div class="icardData"> <address> </div>
            </td>
            </tr>
            <tr>
                <td valign="top" class="icardContent" align="left">Contact No.</td>
                <td valign="top" class="icardContent" align="left">:</td>
                <td valign="top" class="icardData" align="left"><contactNo></td>
            </tr>
            <tr>
                <td  class="icardtd" valign="bottom" align="right" colspan="3" ><br>
                    <img src="'.IMG_HTTP_PATH.'/Icard/'.nl2br($icardSignature).'" valign="top">  <br> 
                    Authorized Signatory
                </td>
            </tr> 
            <tr>
                <td valign="bottom" class="icardData" align="center" colspan="3" >
                    <div>'.($busPassFoundAddress).'</div><br>
                    <div align="center">'.($busPassEmail).'</div>
                </td>
            </tr>
            <tr>
                <td valign="middle" class="icardData" align="center" colspan="3" >  
                  <img src="'.IMG_HTTP_PATH.'/Icard/barcode.jpg" valign="top"> 
                </td>  
            </tr> 
            </table></div>
            </td>';
    // Back Side -- End          
            
    $icardData .='</tr>
            </table>';
            
            
?>

<?php // $History: icardEmployeeTemplate.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/01/09   Time: 3:26p
//Updated in $/LeapCC/Templates/Icard
//icard title added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/01/09   Time: 4:59p
//Updated in $/LeapCC/Templates/Icard
//icard title input box added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/11/09    Time: 4:13p
//Updated in $/LeapCC/Templates/Icard
//i-card front image width, height setting 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/11/09    Time: 4:07p
//Updated in $/LeapCC/Templates/Icard
//EMPLOYEE_I_CARD_FOUND  session variable added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:09p
//Created in $/LeapCC/Templates/Icard
//initial checkin
//

?>