<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn();
    
    require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");
    $onlineFeeManager = OnlineFeeManager::getInstance();
    
    global $sessionHandler;      
    $studentId = $sessionHandler->getSessionVariable('StudentId');

  	$contentHead =  '<table width="100%" border="0" cellpadding="5px" cellspacing="0px" align="center" class="reportData">
                      <tr>
                        <td align="center" colspan="3" height="20px">';
                  //   <CollegeLogo>
    $contentHead .=  '</td>
                      </tr>
                      <tr>
                      	 <td align="right" valign="top"> 
                        	<b>Date:</b>&nbsp;'.UtilityManager::formatDate(date('Y-m-d')).'&nbsp;&nbsp;
                   		  </td>
                      </tr>';
                      
    $contentAddress ='<tr>
			            <td class="dataFont" style="padding-top:4px"  valign="top" colspan=3> 
			               Received and Thanks For using Online Fee Payment Gateway
			             </td>
			          </tr>
						
				        <tr><td height="2px" colspan="4" width="100%"></td></tr>
				        <tr>				
				           <td width="1%" nowrap="nowrap">
				             <nobr><b>Student Name</b></nobr>				
				           </td>
				           <td width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>
				           <td  width="97%" nowrap="nowrap" colspan="2">
				              <nobr><StudentName></nobr>					
				           </td>
				        </tr>   
				        <tr >				
				          <td  width="1%" nowrap="nowrap">
				             <nobr><b>Father Name</b></nobr>				
				          </td>
				          <td  width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>
				          <td  width="97%" nowrap="nowrap" >
				              <nobr><FatherName></nobr>					
				          </td>
				        </tr>
				        <tr >				
				           <td  width="1%" nowrap="nowrap" >
				              <nobr><b>Roll No.</b></nobr>				
				           </td>
				           <td  width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td> 
				           <td  width="97%" nowrap="nowrap" >
				             <nobr><RollNo></span></nobr>					
				           </td>
				        </tr>
				        <tr  >				
				            <td width="1%" nowrap="nowrap">
				                <nobr><b>Class</b></nobr>				
				            </td>
				            <td width="2%" nowrap="nowrap"><b>&nbsp;:&nbsp;</td>  
				            <td width="97%" nowrap="nowrap">
				                <nobr><ClassName></nobr>					
				            </td>
				        </tr> 
				        <tr>
				            <td class="dataFont" style="padding-top:4px" align="left" colspan=3>
				              a sum of $rupeeIcon&nbsp;<b><u><Amount></u></b>
				             </td>
         				<tr> 
     				  </table>';
    

?>
