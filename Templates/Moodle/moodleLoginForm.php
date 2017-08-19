<?php
 $showMoodle = $sessionHandler->getSessionVariable('SHOW_MOODLE_FRAME_NEW');
 $moodleURL  = $sessionHandler->getSessionVariable('MOODLE_URL');  
 $userName = $sessionHandler->getSessionVariable('UserName');  
 $userPass = $sessionHandler->getSessionVariable('EnterUserPassWord'); 
 
 if($showMoodle == 1) {
   $targetName = "frame1";
 } else {
   $targetName = "_blank";
 }
?> 
<form name = "moodleLoginForm" action="<?php echo $moodleURL; ?>" method="post" target="<?php echo $targetName; ?>">
   <input type="hidden" name="username" id="username" size="15"  value="<?php echo $userName; ?>" />
   <input type="hidden" name="password" id="password" size="15"  value="<?php echo $userPass; ?>" />
   <input type="hidden" name="testcookies" value="0" />
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top">Moodle&nbsp;&raquo;&nbsp;Login</td>
                </tr>
            </table>
        </td>
    </tr>
     <tr>
        <td valign="top">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td style="vertical-align:top;" align="center" height="200">
                       <?php 
                            if($moodleURL=='') { ?> 
                               <img src="<?php echo IMG_HTTP_PATH; ?>/moodle_powered.png" border="0">
                            <?php  
                            }
                            if($showMoodle ==1) { ?>
                                <iframe name="frame1" id="frame1" width="900" height="600" frameborder="0" ></iframe>    
                       <?php
                            } else { ?> 
                               <img src="<?php echo IMG_HTTP_PATH; ?>/moodle_powered.png" border="0">
                       <?php
                            }
                       ?>
                    </td>         
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
</form>                        