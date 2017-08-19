 <?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
 
UtilityManager::headerNoCache();
    
	$wishFormat=trim($REQUEST_DATA['wishFormat']);
	$wishBit=trim($REQUEST_DATA['wishBit']);
	$lastWishDate=trim($REQUEST_DATA['lastWishDate']);
    
	require_once(MODEL_PATH . "/BirthdayGreetingManager.inc.php");
       $lastWishBit=BirthdayGreetingManager::getInstance()->checkLastWishBit($wishFormat);

      if($lastWishBit[0]['wishBit'] ==''){
            $check= BirthdayGreetingManager::getInstance()->postBirthdayDetail($wishFormat,$wishBit,$lastWishDate);
       
       
   	}

     else{
       $bit=$lastWishBit[0]['wishBit'];
       $bit++;
       $check= BirthdayGreetingManager::getInstance()->updateBirthdayDetail($wishFormat,$bit,$lastWishDate);

    }



	
    if($check) {
        echo 'Data successfully added';
    }

else {
  echo 'Data could not be inserted';
}

