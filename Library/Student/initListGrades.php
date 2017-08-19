<?php
//This file sends the data, creates the image on runtime
//
// Author :Ajinder Singh
// Created on : 21-Oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");

require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyGrade');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
//$gradesArray = $studentManager->getGrades();
$gradesArray = $studentManager->getActiveSetGrades();
   
$gradesLastArray = $studentManager->getLastGradingScales();  

$max=0;
for($i=0;$i<count($gradesArray);$i++) {
   if($gradesArray[$i]['gradePoints'] > $max) {
     $max =  $gradesArray[$i]['gradePoints']; 
   } 
}


$allSlidersArray = array();
$divTable = '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="contenttab_border"><tr><td width="50%" valign="top" style="border-collapse:collapse;">';
$divTable = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
$divTable .= '<tr><td width="10%">Grade</td><td width="7%">From</td><td width="40%"></td><td width="60%">To</td></tr>';
$textIdTo = '';
$spanIdTo = '';



$fromValue = 0;
$toValue = 0;
foreach($gradesArray as $gradeRecord) {
    $gradeId = $gradeRecord['gradeId'];
    $gradeLabel = $gradeRecord['gradeLabel'];
    $sliderId = 'sliderDiv'.$gradeId;
    $sliderTipId = 'sliderTip'.$gradeId;
    $textIdFrom = 'textFrom'.$gradeId;
    $textIdTo = 'textTo'.$gradeId;
    $spanIdTo = 'spanTo'.$gradeId;
    $spanIdFrom = 'spanFrom'.$gradeId;
    $allSlidersArray[] = Array($sliderId,$sliderTipId,$textIdTo,$textIdFrom,$spanIdTo, $spanIdFrom);
    $toValue = $toValue+10;  
    $divTable .= '<tr><td>'.$gradeLabel.'</td><td><input type = "text" name="'.$textIdFrom.'" id="'.$textIdFrom.'" value="0" readonly style="display:none;" /><span id="'.$spanIdFrom.'">0</span></td><td><nobr><input type = "text" name="'.$textIdTo.'" id="'.$textIdTo.'" value="0" style="display:none;"/><span id="'.$sliderId.'"></span></td><td><span id="'.$spanIdTo.'">0</span></nobr></td></tr>'; 
    //$divTable .= '<tr><td>'.$gradeLabel.'</td><td><input type = "text" name="'.$textIdFrom.'" id="'.$textIdFrom.'" value="'.$fromValue.'" readonly style="display:none;" /><span id="'.$spanIdFrom.'">'.$fromValue.'</span></td><td><nobr><input type = "text" name="'.$textIdTo.'" id="'.$textIdTo.'" value="'.$toValue.'" style="display:none;"/><div id="'.$sliderId.'"></div></td><td><span id="'.$spanIdTo.'">'.$toValue.'</span></nobr></td></tr>'; 
    $fromValue = $toValue+1;
}
$divTable .= '<tr><td colspan="4"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="'. IMG_HTTP_PATH.'/calculate_mgpa.gif" onClick="calculateMGPA();" /></td></tr>';
$divTable .= '</table></td><td width="50%" valign="top" class="contenttab_row"><span id = "resultsDiv" style="vertical-align:top;"></span></td></tr></table>';

$totalSliders = count($allSlidersArray);

$myString ='';
$myString2 ='';
if($textIdTo!='' && $spanIdTo != '') {
    $myString = 'document.getElementById("'.$textIdTo.'").value = 100;document.getElementById("'.$spanIdTo.'").innerHTML = 100; Ext.onReady(function(){';
    $myString2 = '';
    
    for ($i=0; $i < $totalSliders-1 ; $i++) {
        $value=$gradesLastArray[$i]['gradingRangeTo'];
        if($value==0) {
          $value=0;  
        }
        $myString .= 'new Ext.Slider({renderTo: \''.$allSlidersArray[$i][0].'\',
                                      width: 200,
                                      minValue: 0,
                                      maxValue: 100,
                                      value: '.$value.',
                                      plugins: new Ext.ux.'.$allSlidersArray[$i][1].'()});';
        $myString2 .= 'document.getElementById("'.$allSlidersArray[$i][2].'").value = 0;
                       Ext.ux.'.$allSlidersArray[$i][1].' = Ext.extend(Ext.Tip, {minWidth: 10,offsets : [0, -10],
                       init : function(slider){document.getElementById("'.$allSlidersArray[$i][2].'").value = 0;
                            slider.on("dragstart", this.onSlide, this);
                            slider.on("drag", this.onSlide, this);
                            slider.on("dragend", this.hide, this);     
                            slider.on("destroy", this.destroy, this);},   
                       onSlide : function(slider){
                                this.show();  
                                this.body.update(this.getText(slider));
                                //this.doAutoWidth();
                                this.el.alignTo(slider.thumb,"b-t?", this.offsets);},
                       getText : function(slider){
                                    document.getElementById("'.$allSlidersArray[$i][2].'").value = slider.getValue();
                                    document.getElementById("'.$allSlidersArray[$i][4].'").innerHTML = slider.getValue();
                                    document.getElementById("'.$allSlidersArray[$i+1][3].'").value = slider.getValue()+1;
                                    document.getElementById("'.$allSlidersArray[$i+1][5].'").innerHTML = slider.getValue()+1;
                                    document.getElementById("resultsDiv").innerHTML=""; return slider.getValue();}});'; 
    } 
   $myString .= '});'; 
}



// New manual DIV.............................
/////////////////.............................
//$manualDivTable='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="contenttab_border"><tr><td width="50%" valign="top" style="border-collapse:collapse;">';
  $manualDivTable='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="contenttab_border2"><tr><td class="contenttab_row" colspan="1" width=50%>';
  $manualDivTable.= '<table border="0" cellpadding="5" cellspacing="1" width="50%" class="contenttab_border2" >';

  $manualDivTable .= '<tr><td width="10%">Grade</td><td width="10%">From</td><td width="10%">To</td></tr>';
  $textIdTo1 = '';
  $spanIdTo1 = '';

  $textIdTo1 = '';
  $spanIdTo1 = '';
  foreach($gradesArray as $gradeRecord1) {
	$gradeId1 = $gradeRecord1['gradeId'];
	$gradeLabel1 = $gradeRecord1['gradeLabel'];
	$textIdFrom1 = 'textFrom1'.$gradeId1;
	$textIdTo1= 'textTo1'.$gradeId1;
	$spanIdTo1 = 'spanTo1'.$gradeId1;
	$spanIdFrom1 = 'spanFrom2'.$gradeId1;
	$allSlidersArray1[] = Array($textIdTo1,$textIdFrom1,$spanIdTo1, $spanIdFrom1);
        
        $manualDivTable .= '<tr><td>'.$gradeLabel1.'</td>
                                <td><span id="'.$spanIdFrom1.'"></span></td>
                                <td><span id="'.$gradeId1.'"></span></td>
                                </tr>';
 }

        $manualDivTable .= '</tr></td><td colspan="4"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="'. IMG_HTTP_PATH.'/calculate_mgpa.gif" onClick="calculateManualMGPA();" /></td></tr>';
        $manualDivTable .= '</table></td><td width="50%" valign="top" class="contenttab_row"><div id = "resultsDiv1" style="vertical-align:top;"></div></td></tr></table>';







