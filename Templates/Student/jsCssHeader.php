<?php
    //default css
    echo UtilityManager::includeCSS("css.css");
    
    //menu js & css
    echo UtilityManager::includeJS("menu.js");     
    echo UtilityManager::includeCSS("menu.css");
    
    //js library
    echo UtilityManager::includeJS("winjs/prototype.js"); 

    //alert dialog box 
    echo UtilityManager::includeCSS('winjs/default.css');
    echo UtilityManager::includeCSS('winjs/alphacube.css');    
    echo UtilityManager::includeJS("winjs/window.js"); 
    
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
    echo UtilityManager::includeJS("tab.css");

    
    //tabber
    echo UtilityManager::includeJS("sortable.js");
    echo UtilityManager::includeJS("sortable.css"); 
    
    //bubble tool tip
    echo UtilityManager::includeJS("BubbleTooltips.js");
    echo UtilityManager::includeCSS("bt.css");   
	 

?>
