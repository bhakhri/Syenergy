<?php
		$sessionHandler;
		$themeId = $sessionHandler->getSessionVariable('UserThemeId');
		// echo UtilityManager::includeJS("combinedJS.php",'',1);
		
		echo UtilityManager::includeJS("menu.js",'',2);
		echo UtilityManager::includeJS("winjs/prototype.js",'',2);
		echo UtilityManager::includeJS("dimmingdiv.js",'',2);
		echo UtilityManager::includeJS("misc.js",'',2);
		echo UtilityManager::includeJS("calendar.js",'',2);
		echo UtilityManager::includeJS("calendar-en.js",'',2);
		echo UtilityManager::includeJS("setup.js",'',2);
		echo UtilityManager::includeJS("functions.js",'',2);
		echo UtilityManager::includeJS("tabber.js",'',2);
		echo UtilityManager::includeJS("BubbleTooltips.js",'',2);
		echo UtilityManager::includeJS("scroller.js",'',2);
		echo UtilityManager::includeJS("serverDetails.php",'',2);
		//require_once("analytics.js");
		echo UtilityManager::includeJS("jquery-1.6.2.min.js",'',2);
		echo UtilityManager::includeJS("jqueryNoConflict.js",'',2);
		echo UtilityManager::includeJS("guiders-1.1.0.js",'',2);
		
		
		
		echo UtilityManager::includeCSS("themeCss.php?id=$themeId",'','',1);
		echo UtilityManager::includeCSS("themeCss2.php",'','',2);
		echo UtilityManager::includeCSS("guiders-1.1.0.css",'','',2);
		echo UtilityManager::includeJS("swfobjectVideo.js",'',2);
		echo UtilityManager::includeJS("jquery-1.11.0.min.js",'',2);
		echo UtilityManager::includeJS("form_default.js",'',2);
		
		
/*
    //default css
    echo UtilityManager::includeCSS("css.css");
    
    //menu js & css
    echo UtilityManager::includeJS("menu.js");     
    echo UtilityManager::includeCSS("menu.css");
    
    //js library
    echo UtilityManager::includeJS("winjs/prototype.js"); 

    //alert dialog box 
    //echo UtilityManager::includeCSS('winjs/default.css');
    //echo UtilityManager::includeCSS('winjs/alphacube.css');    
    //echo UtilityManager::includeJS("winjs/window.js"); 
    
    //dim effects
    echo UtilityManager::includeJS("dimmingdiv.js"); 
    echo UtilityManager::includeJS("misc.js"); 
  
    //calendar
    echo UtilityManager::includeJS("calendar.js"); 
    echo UtilityManager::includeJS("calendar-en.js");
    echo UtilityManager::includeJS("setup.js");
    echo UtilityManager::includeCSS('calendar-win2k-cold-1.css');    
    
    //default utility functions
    echo UtilityManager::includeJS("functions.js"); 
    
    //tabber
    echo UtilityManager::includeJS("tabber.js");
    //echo UtilityManager::includeCSS("tab.css");
    
    //tabber
    //echo UtilityManager::includeJS("sortable.js");
    //echo UtilityManager::includeJS("sortable.css");    

  
  //bubble tool tip
    echo UtilityManager::includeJS("BubbleTooltips.js");
    echo UtilityManager::includeCSS("bt.css"); 
    
    //scroller js
    echo UtilityManager::includeJS("scroller.js");     
*/
?>