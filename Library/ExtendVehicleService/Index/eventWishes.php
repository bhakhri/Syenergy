<script language="javascript">
    // Birthday Greeting .................
    function birthdayPopUp() {    
      displayWindowNew('bdayDiv',400,200);
    } 

    function anniversaryPopUp() {    
      displayWindowNew('anvDiv',400,200);
    } 

    function eventPopUp(photoName,eventComments) {  
      <?php $x=rand(0,1000); ?>
      var img = "<img src='<?php echo IMG_HTTP_PATH;?>/Event/"+trim(photoName)+"?gg=<?php echo $x; ?>' />"; 
      document.getElementById('eventPhoto').innerHTML = img;  
      document.getElementById('eventComments').innerHTML = eventComments;
      displayWindowNew('eventDiv',400,200);
      //displayFloatingDiv('eventDiv','', w, h, screen.width/4.8, screen.height/10);
    } 
</script>      

<?php floatingDiv_Start('eventDiv',"<div id='eventComments'></div>"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  <tr>
    <td height="5px"><div id='eventPhoto'></div></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/thanks.png"  onclick="javascript:hiddenFloatingDiv('eventDiv');return false;" />
      </td>
</tr>
</table>
<?php floatingDiv_End(); ?> 

<?php floatingDiv_Start('anvDiv','Happy Anniversary'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  <tr>
    <td height="5px"><img src="<?php echo IMG_HTTP_PATH;?>/anniversary.gif" /></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/thanks.png" onclick="javascript:hiddenFloatingDiv('anvDiv');return false;" />
    </td>
</tr>
</table>
<?php floatingDiv_End();?>

<?php floatingDiv_Start('bdayDiv','Happy Birthday'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  <tr>
    <td height="5px"><img src="<?php echo IMG_HTTP_PATH;?>/birthday.gif" /></td>
  </tr>
  <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/thanks.png" onclick="javascript:hiddenFloatingDiv('bdayDiv');return false;" />
     </td>
</tr>
</table>
<?php floatingDiv_End(); ?>



<?php  
    global $FE;
    require_once(MODEL_PATH . "/BirthdayGreetingManager.inc.php");
    $BirthdayGreetingManager = BirthdayGreetingManager::getInstance();
    $comments='';
    global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='2') { 
      UtilityManager::ifTeacherNotLoggedIn(true);    
    }
    else if($roleId=='3') { 
      UtilityManager::ifParentNotLoggedIn(true);  
    }
    else if($roleId=='4') { 
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else if($roleId=='5') { 
      UtilityManager::ifManagementNotLoggedIn(true);
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();
    
   
    $userId = $sessionHandler->getSessionVariable('UserId');
    $birthGreeting = $sessionHandler->getSessionVariable('BIRTHDAY_GREETING_MESSAGE');
    
    
    $currentDate = date('Y-m-d');
    $currentDateArray = explode('-',$currentDate);

    if($birthGreeting=='1') {
      if($roleId==4) {
         $dateOfBirth = $sessionHandler->getSessionVariable('StudentDOB'); 
         $dateOfAdmission = $sessionHandler->getSessionVariable('StudentDOA');
         $userType='S';
      }
      else {
         $dateOfBirth = $sessionHandler->getSessionVariable('EmployeeDOB'); 
         $dateOfMarriage = $sessionHandler->getSessionVariable('EmployeeDOM');  
         $userType='E';
      }
      
      if($dateOfBirth!='') {
        $dobArray = explode('-',$dateOfBirth); 
        $wishResult = getWish($userType,'B',$dobArray,$currentDateArray);
        if($wishResult===true) {
          echo "<script> birthdayPopUp();</script>";    
        }
      }
      
      if($dateOfMarriage!='' && $userType=='E') {
         $domArray = explode('-',$dateOfMarriage); 
         $wishResult = getWish($userType,'A',$domArray,$currentDateArray);  
         if($wishResult===true) {
           echo "<script> anniversaryPopUp();</script>";    
         }   
      } 
    }
    
   
    $eventResult = getEvent($currentDateArray);  
    $eventArray=explode('~',$eventResult);
    $eventPhoto=$eventArray[0];
    $eventComments=$eventArray[1];
    if($eventResult!='-1') {
       if(trim($eventResult)!='') {
          echo "<script> eventPopUp('$eventPhoto','$eventComments');</script>";  
       }
    }
    

    function getEvent($currentDateArray) {
      
       global $BirthdayGreetingManager;   
       $cDate = date('Y-m-d');
       $i;
       $result = "-1";
       
       $recordArray=$BirthdayGreetingManager->eventDetailNew();
       $cnt=count($recordArray);
       
       if(is_array($recordArray) && count($recordArray)>0 ) {
         
           $photo   = $recordArray[0]['eventPhoto']; 
           $comments = $recordArray[0]['comments'];  
           $wishFormat = 'E-'.$recordArray[0]['userWishEventId'];  
         
           $recordArray1=$BirthdayGreetingManager->checkBirthday($wishFormat);    
           $lastWishDate=''; 
           $userWishid='';
           $showWish='';
           if(is_array($recordArray1) && count($recordArray1)>0 ) {  
              $lastWishDate=$recordArray1[0]['lastWishDate'];   
              $userWishid = $recordArray1[0]['userWishId']; 
           }
           
           if(($lastWishDate < $cDate) || $lastWishDate=='') {
               if(SystemDatabaseManager::getInstance()->startTransaction()) {  
                   if($userWishid=='') {  
                      $returnStatus= $BirthdayGreetingManager->postBirthdayDetail($wishFormat);     
                   } 
                   else {
                      $returnStatus= $BirthdayGreetingManager->updateBirthdayDetail($userWishid);    
                   }
                   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                      $showWish='1';
                   }
               }
           }
           
           if($showWish=='1') {
             $result = $photo."~".$comments; 
           }
           else {
             $result = "-1";
           } 
       }
       return $result;
    } 


      $showWish='';
     function getWish($userType='',$wishFormat='B',$dobArray='',$currentDateArray='') {
    
       global $BirthdayGreetingManager;
       
       if($dobArray=='') {
         return false;
       }    
       
       $cDate = date('Y-m-d');
       if($userType=='S') {
          if($wishFormat=='B' && $currentDateArray[1]==$dobArray[1] && $currentDateArray[2]==$dobArray[2]  && $dobArray !='') { 
            $recordArray=$BirthdayGreetingManager->checkBirthday($wishFormat); 
            $lastWishDate=''; 
            $userWishid='';
            $showWish='';
            if(is_array($recordArray) && count($recordArray)>0 ) {  
            $lastWishDate=$recordArray[0]['lastWishDate'];
            $userWishid = $recordArray[0]['userWishId']; 
            }

            if(($lastWishDate < $cDate) || $lastWishDate=='') {
                if(SystemDatabaseManager::getInstance()->startTransaction()) { 
                   if($userWishid=='') {  
                      $returnStatus= $BirthdayGreetingManager->postBirthdayDetail($wishFormat);     
                   } 
                   else {
                      $returnStatus= $BirthdayGreetingManager->updateBirthdayDetail($userWishid);    
                   }
                   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                      $showWish=1;
                   }
                }
            } 
          }
       } 
       
       if($userType=='E') { 
          if($wishFormat=='B' && $currentDateArray[1]==$dobArray[1] && $currentDateArray[2]==$dobArray[2] && $dobArray !=''  )  {
             $recordArray=$BirthdayGreetingManager->checkBirthday($wishFormat);
             $lastWishDate=''; 
             $userWishid='';
             $showWish='';
             if(is_array($recordArray) && count($recordArray)>0 ) {  
              $lastWishDate=$recordArray[0]['lastWishDate'];
              $userWishid = $recordArray[0]['userWishId']; 
            }
       
            if(($lastWishDate < $cDate) || $lastWishDate=='') {
               if(SystemDatabaseManager::getInstance()->startTransaction()) { 
                   if($userWishid=='') {  
                      $returnStatus= $BirthdayGreetingManager->postBirthdayDetail($wishFormat);     
                   } 
                   else {
                      $returnStatus= $BirthdayGreetingManager->updateBirthdayDetail($userWishid);    
                   }
                   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                      $showWish=1;
                   }
               }
           } 
        }
          
        if($wishFormat=='A' && $currentDateArray[1]==$dobArray[1] && $currentDateArray[2]==$dobArray[2] && $dobArray !='' )  { 
             $recordArray=$BirthdayGreetingManager->checkBirthday($wishFormat); 
             $lastWishDate=''; 
             $userWishid='';
                
             if(is_array($recordArray) && count($recordArray)>0 ) {  
               $lastWishDate=$recordArray[0]['lastWishDate'];
               $userWishid = $recordArray[0]['userWishId']; 
             }
       
             if(($lastWishDate < $cDate) || $lastWishDate=='') {
                if(SystemDatabaseManager::getInstance()->startTransaction()) { 
                       if($userWishid=='') {  
                          $returnStatus= $BirthdayGreetingManager->postBirthdayDetail($wishFormat);     
                       } 
                       else {
                          $returnStatus= $BirthdayGreetingManager->updateBirthdayDetail($userWishid);    
                       }
                       if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                          $showWish=1;
                       }
                }
             }
          }
       } 
       if($showWish==1) {
         return true;  
       }
       else {
         return false;  
       } 
    }
?>