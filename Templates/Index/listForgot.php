<?php
//--------------------------------------------------------  
// Purpose: This file contains HTMl Output for the Forgot Password Module
//
// Author:Parveen Sharma
// Created on : 15.12.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------      

require_once(BL_PATH . '/HtmlFunctions.inc.php');
?>

<table class="login_body" width="100%">
	<tr>
	  <td align="center">
		
		<table width="749px" cellpadding="0px" cellspacing="0px" background="<?php echo IMG_HTTP_PATH;?>/body_login.jpg" height="591px" border="0px">
			<tr>
			   <td colspan="2" align="center">
			   </td>
			</tr>
			<tr>
			   <td width="378px">
			   </td>
			   <td width="350px" align="left" valign="bottom" style="padding-bottom:105px; padding-right:0px;">
			   	<table width="300px" cellpadding="0px" cellspacing="0px" border="0px" height="80px" style="padding:5px 0px 30px 0px;">
					<tr>
						<td align="left"><img src="<?php echo IMG_HTTP_PATH;?>/logo.gif" />
						</td>
					</tr>
				</table>
     
                    		 <table width="350px" cellpadding="0px" cellspacing="0px" border="0px" height="228px" style="padding:10px 5px 10px 15px; 
					background:url(<?php echo IMG_HTTP_PATH;?>/login_login.gif) no-repeat">
        
          				<tr>
        		 			 <td valign="top">	
			   			   <div style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:16px; padding:15px 0px 10px 0px; 
							border-bottom:1px solid #FFFFFF;width:270px; margin-left:10px;">
 	                     				<strong>Forgot Password</strong>					
			    			   </div>
	                 			 </td>
					</tr>
               
                   			<tr>
                        			<td colspan="4" align="center" class="text_loginpanel">
                       				 <?php if($errorVariable=='1'){
                       						 echo "<label class='redColor'>".FORGOT_TECHNICAL_PROBLEM."</label>";
                       				 	} 
                       					if($userCheckVariable=='1'){ 
                            					echo "<label class='redColor'>".INVALID_USERNAME."</label>"; 
                      					 } 
							if($mail=='1'){
                       						echo "<label class='redColor'>Mail Sent Successfully to '".$mailAdd."' </label>";
                      					 }
							 if(UtilityManager::notEmpty($errorMessage)) {
                      						echo '<span class="error">'.$errorMessage.'<br/></span>';
                        }
                        			?>
                       				</td>
                   			</tr>
                   
					<tr>
					  <td valign="top">
										
							
					    <table width="350"  border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
						    <td align="left" style="padding-left:10px;">
						     <form  name="form1"  id="form1" method="post" action="">
						        <table width="80%" border="0" cellspacing="2" cellpadding="4">
								<tr height="25">
								  <td width="37%" align="left" class="text_loginpanel" >Username</td>
								   <td width="63%" align="left">
									<input name="username" id="username" type="text" style="width:180px;" class="inputbox"/>
								   </td>
									
								</tr>
                          					
								 <tr>
                                  				    <td class="text_loginpanel">&nbsp;</td>
                                				    <td align="left"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit_login.gif"  										onclick="return  validateLoginForm();" /> 
                                					<input type="image" name="imgReset" src="<?php echo IMG_HTTP_PATH;?>/reset_login.gif"  		  										onclick="this.form.reset();return false;" />
								   </td>
                            					</tr>
							
								<tr>
                               					   <td align="left" class="text_loginpanel">&nbsp;</td>
                                   				   <td align="left" class="text_loginpanel"><a href="index.php" class="redLink">Back to Home Page</a></td>
                           					</tr>
                            
                       					</table>
                        			      </form>
						     </td>
                  				</tr>  
					     </table>
					</td>
                      		      </tr>
              			</table>
			</td>
                     </tr>
              </table>
	      <table width="800px">
		<tr>	
	           <td align="right">
		   <span class="text_menu_login">&laquo; Powered By ChalkPad Technologies Pvt Ltd &raquo;</span>
	           </td>
	        </tr>
	     </table>
	</td>
    </tr>
</table>


<?php //$History: listForgot.php $ 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/02/09    Time: 12:14p
//Updated in $/LeapCC/Templates/Index
//message format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/28/09    Time: 7:10p
//Updated in $/LeapCC/Templates/Index
//Sent message change
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/15/08   Time: 2:33p
//Updated in $/LeapCC/Templates/Index
//verification code update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Index
//
//*****************  Version 10  *****************
//User: Arvind       Date: 10/25/08   Time: 5:37p
//Updated in $/Leap/Source/Templates/Index
//modify
//
//*****************  Version 9  *****************
//User: Arvind       Date: 10/25/08   Time: 5:19p
//Updated in $/Leap/Source/Templates/Index
//modify
//
//*****************  Version 8  *****************
//User: Arvind       Date: 10/25/08   Time: 3:39p
//Updated in $/Leap/Source/Templates/Index
//modified the sent message,added mail id of sent mail
//
//*****************  Version 7  *****************
//User: Arvind       Date: 10/23/08   Time: 2:45p
//Updated in $/Leap/Source/Templates/Index
//addded tabindex
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/16/08    Time: 4:27p
//Updated in $/Leap/Source/Templates/Index
//modified the display
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/28/08    Time: 4:15p
//Updated in $/Leap/Source/Templates/Index
//modified the error message
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/24/08    Time: 12:56p
//Updated in $/Leap/Source/Templates/Index
//added coments
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/24/08    Time: 12:53p
//Created in $/Leap/Source/Templates/Index
//initial chekin


?>
