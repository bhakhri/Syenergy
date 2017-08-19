<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices  and show it in dashboard
//
// Author : Dipanjan Bbhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    require_once(MODEL_PATH . "/LoginManager.inc.php");
    $loginManager = LoginManager::getInstance();
    
    //set session variable related to time table type
    $timeTableRecord = $loginManager->getTimeTableLabelType($sessionHandler->getSessionVariable('EmployeeId'));
    $sessionHandler->setSessionVariable('TeacherTimeTableLabelType',$timeTableRecord[0]['timeTableType']);
    
    //********For BirthDay wishes**************
    $birthDayTakenRecordArray = $teacherManager->checkBirthDay();
    $greetingMsg="";
    if($birthDayTakenRecordArray[0]['birthDay'] >0){
        $greetingMsg="Happy BirthDay.............";
    }
    //********For For BirthDay wishes(ends)**************
    
    
    //********For MarriageDay wishes**************
    $marriageDayTakenRecordArray = $teacherManager->checkMarriageDay();
    if($marriageDayTakenRecordArray[0]['marriageDay'] >0){
       if($greetingMsg==""){ 
        $greetingMsg ="Happy Marriage Anniversary.............";
       } 
       else{
           $greetingMsg .=" and Happy Marriage Anniversary.............";
       } 
    }
    if($greetingMsg != ""){
        $greetingMsg .=$sessionHandler->getSessionVariable('EmployeeName');
    }
    
    //********For For MarriageDay wishes(ends)**************
    
    
    /* Check for whether time has been changed on this day for this teacher*/
    
    $timeTableAlert="";
    if(!isset($_COOKIE['TimeTableAlertCookie'])){ //if time table alert cookie is not set
        $timeTableAlertRecordArray= $teacherManager->getTeacherTimeTableAlert(); 
        if($timeTableAlertRecordArray[0]['talert'] >0){
          $timeTableAlert='<a href="#" onclick="checkTimeTableAlert()" title="please re-look at your TimeTable"><img src="'.IMG_HTTP_PATH.'/blink.gif" border="0" title="please re-look at your TimeTable">&nbsp;<u>TimeTable has been changed , please re-look at your TimeTable</u></a>';  
        }
    }
    /* Check for whether time has been changed on this day for this teacher*/
    
    $limit      = ' LIMIT 0,5';  //showing first five records
   //********For Attendance Not Taken**************
    
    $filter="";
     ////////////   
    //$attendanceNotTakenRecordArray = $teacherManager->checkAttendanceNotTaken($filter,$limit);
    //********For Attendance Not Taken(ends)**************
    
    //*************For Message**************
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    $mlimit      = ' LIMIT 0,'.RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE ;
        
     //////////// 
    $filter="";

    /*       
    $totalArray = $teacherManager->getTotalAdminMessage($filter);
    $msgRecordArray = $teacherManager->getAdminMessageList($filter,$mlimit,' adm.dated DESC');
    */
    
	/*
	-----------------------------------------------
		FOR ATTENDANCE BELOW THRESHOLD
	-----------------------------------------------
	*/

	$activeTimeTableLabelArray = $teacherManager->getActiveTimeTable();
	$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
    
/**************************CODE FOR DRAG-DROP FEATURE*************************************/

//Get dashboard layout

echo '<script language="javascript">inettus_data=-1</script>';
$dashboardLayOutArray=$teacherManager->getDashboardLayout();
$dashboardLayOut=$dashboardLayOutArray[0]['dashboardLayout'];
if($dashboardLayOut!=''){
    $dashboardLayOutArr=explode('!~!',$dashboardLayOut);
    $inettus_layout=$dashboardLayOutArr[0];
    $inettus_data=$dashboardLayOutArr[1];
    echo '<script language="javascript">inettus_data="'.$inettus_data.'"</script>';
}


$widget_1 = widget('white', 'Notices', 'widget1');
$widget_2 = widget('white', 'Events', 'widget2');
$widget_3 = widget('white', 'Resource Download Count', 'widget3');
$widget_4 = widget('white', 'Analysis', 'widget4'); 
$widget_5 = widget('white', 'Messages', 'widget5'); 

$noticeWidget=-1;
$eventWidget=-1;
$resourceWidget=-1;
$analysisWidget=-1;
$messageWidget=-1;

if(trim($inettus_layout)!=''){
    if(stripos($inettus_layout,'widget1')===false){
        $noticeWidget=1;
    }
    if(stripos($inettus_layout,'widget2')===false){
        $eventWidget=1;
    }
    if(stripos($inettus_layout,'widget3')===false){
        $resourceWidget=1;
    }
    if(stripos($inettus_layout,'widget4')===false){
        $analysisWidget=1;
    }
	  if(stripos($inettus_layout,'widget5')===false){
        $messageWidget=1;
    }

}
/*
$noticeDisabled1=$noticeDisabled2="disabled='disabled'";
$eventDisabled1=$eventDisabled2="disabled='disabled'";
$resourceDisabled1=$resourceDisabled2="disabled='disabled'";
$analysisDisabled1=$analysisDisabled2="disabled='disabled'";

if($noticeWidget==-1){
    $noticeDisabled1="disabled='disabled'";
    $noticeDisabled2="";
}
else{
   $noticeDisabled1="";
   $noticeDisabled2="disabled='disabled'"; 
}
if($eventWidget==-1){
    $eventDisabled1="disabled='disabled'";
    $eventDisabled2="";
}
else{
   $eventDisabled1="";
   $eventDisabled2="disabled='disabled'"; 
}
if($resourceWidget==-1){
    $resourceDisabled1="disabled='disabled'";
    $resourceDisabled2="";
}
else{
   $resourceDisabled1="";
   $resourceDisabled2="disabled='disabled'"; 
}
if($analysisWidget==-1){
    $analysisDisabled1="disabled='disabled'";
    $analysisDisabled2="";
}
else{
   $analysisDisabled1="";
   $analysisDisabled2="disabled='disabled'"; 
}
*/

$noticeDisabled1="checked='checked'";
$eventDisabled1="checked='checked'";
$resourceDisabled1="checked='checked'";
$analysisDisabled1="checked='checked'";
$messagesDisabled1="checked='checked'";
if($noticeWidget==-1){
    $noticeDisabled1="checked='checked'";
}
else{
   $noticeDisabled1="";
}
if($eventWidget==-1){
    $eventDisabled1="checked='checked'";
}
else{
   $eventDisabled1="";
}
if($resourceWidget==-1){
    $resourceDisabled1="checked='checked'";
}
else{
   $resourceDisabled1="";
}
if($analysisWidget==-1){
    $analysisDisabled1="checked='checked'";
}
else{
   $analysisDisabled1="";
}
if($messageWidget==-1){
    $messagesDisabled1="checked='checked'";
}
else{
   $messagesDisabled1="checked='checked'";
}

$widgetOptionsString='<tr class="row0">
                 <td class="padding_top">1</td>
                 <td class="padding_top" style="padding-right:2px;">Notices</td>
                 <td class="padding_top" align="center">
                  <input type="checkbox" id="widget1_1" value="column1_Notices" onclick="toggleWidgets(this.id,this.value,this.checked);" '.$noticeDisabled1.'/>
                 </td>
                 <td class="padding_top">
                  This widget shows notices uploaded by Adminstrators.
                 </td>
                </tr>
                <tr class="row1">
                 <td class="padding_top">2</td>
                 <td class="padding_top" style="padding-right:2px;">Events</td>
                 <td class="padding_top" align="center">
                  <input type="checkbox" id="widget2_1" value="column2_Events" onclick="toggleWidgets(this.id,this.value,this.checked);" '.$eventDisabled1.' />
                 </td>
                 <td class="padding_top">
                  This widget shows upcoming or ongoing events<br/> uploaded by Adminstrators.
                 </td>
                </tr>
                <tr class="row0">
                 <td class="padding_top">3</td>
                 <td class="padding_top" style="padding-right:2px;">Resource Download Count</td>
                 <td class="padding_top" align="center">
                  <input type="checkbox" id="widget3_1" value="column2_Resource Download Count" onclick="toggleWidgets(this.id,this.value,this.checked);" '. $resourceDisabled1.' />
                 </td>
                 <td class="padding_top">
                  This widget shows resources download information.
                 </td>
                </tr>
                <tr class="row1">
                 <td class="padding_top">4</td>
                 <td class="padding_top" style="padding-right:2px;">Analysis</td>
                 <td class="padding_top" align="center">
                  <input type="checkbox" id="widget4_1" value="column3_Analysis" onclick="toggleWidgets(this.id,this.value,this.checked);" '.$analysisDisabled1.' />
                 </td>
                 <td class="padding_top">
                  This widget shows different statistics about attendance<br> and marks of students.
                 </td>
                </tr>
				<tr class="row1">
                 <td class="padding_top">5</td>
                 <td class="padding_top" style="padding-right:2px;">Messages</td>
                 <td class="padding_top" align="center">
                  <input type="checkbox" id="widget5_1" value="column1_Messages" onclick="toggleWidgets(this.id,this.value,this.checked);" '.$messagesDisabled1.'/>
                 </td>
                 <td class="padding_top">
                  This widget shows messages send by Adminstrators.
                 </td>
                </tr>';
                

/*
$widgetOptionsString='<tr class="row0">
                 <td class="padding_top">1</td>
                 <td class="padding_top">Notices</td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget1_1" value="column1_Notices" onclick="toggleWidgets(this.id,this.value);" '.$noticeDisabled1.'/>
                 </td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget1_2" value="" onclick="toggleWidgets(this.id,this.value);" '.$noticeDisabled2.'/>
                 </td>
                </tr>
                <tr class="row1">
                 <td class="padding_top">2</td>
                 <td class="padding_top">Events</td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget2_1" value="column2_Events" onclick="toggleWidgets(this.id,this.value);" '.$eventDisabled1.' />
                 </td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget2_2" value="" onclick="toggleWidgets(this.id,this.value);" '.$eventDisabled2.' />
                 </td>
                </tr>
                <tr class="row0">
                 <td class="padding_top">3</td>
                 <td class="padding_top">Resource Download Count</td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget3_1" value="column2_Resource Download Count" onclick="toggleWidgets(this.id,this.value);" '. $resourceDisabled1.' />
                 </td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget3_2" value="" onclick="toggleWidgets(this.id,this.value);" '.$resourceDisabled2.' />
                 </td>
                </tr>
                <tr class="row0">
                 <td class="padding_top">4</td>
                 <td class="padding_top">Analysis</td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget4_1" value="column3_Analysis" onclick="toggleWidgets(this.id,this.value);" '.$analysisDisabled1.' />
                 </td>
                 <td class="padding_top">
                  <input type="checkbox" id="widget4_2" value="" onclick="toggleWidgets(this.id,this.value);" '.$analysisDisabled2.' />
                 </td>
                </tr>';
*/                

$widgets = array(
        'widget1' => $widget_1,
        'widget2' => $widget_2,
        'widget3' => $widget_3,
        'widget4' => $widget_4,
        'widget5' => $widget_5
      /*  'widget6' => $widget_6
        */
    ); 

function clumns( $id ){
    global $widgets;
    global $inettus_layout;
    
    if( !isset($inettus_layout) ){
        $position = 'widget1,widget5,|widget3,widget2,|widget4,';
    }
    else{
        $position = $inettus_layout;
    }

    $explode_columns = explode('|', $position);
    //print_r($explode_columns);

    $column_1 = $explode_columns[0];
    $column_2 = $explode_columns[1];
    $column_3 = $explode_columns[2];

    //explode column 1 containt
    if( $id == 1){
        $explode_column_1 = explode(',',$column_1);
        $count_widget = ( count($explode_column_1) - 1 );
        if($count_widget > 0){
            for ($i=0; $i<=$count_widget; ++$i){
                $widget_id = $explode_column_1[$i];
                $widgets[$widget_id];
                print( $widgets[$widget_id] );
            }
        }
    }//end column 1
    //explode column 2 containt
    else if( $id == 2){
        $explode_column_2 = explode(',',$column_2);
        $count_widget = ( count($explode_column_2) - 1 );
        if($count_widget > 0){
            for ($i=0; $i<=$count_widget; ++$i){
                $widget_id = $explode_column_2[$i];
                print( $widgets[$widget_id] );
            }
        }
    }//end column 2
    //explode column 3 containt
    else if( $id == 3){
        $explode_column_3 = explode(',',$column_3);
        $count_widget = ( count($explode_column_3) - 1 );
        if($count_widget > 0){
            for ($i=0; $i<=$count_widget; ++$i){
                $widget_id = $explode_column_3[$i];
                print( $widgets[$widget_id] );
            }
        }
    }//end column 3
}



function widget($color, $title, $id){
    global $teacherManager,$activeTimeTableLabelId;
    $widget = '<li class="widget color-'.$color.'" id="'.$id.'">
                        <div class="widget-head">
                            <h3 class="contenttab_internal_rows1"><font color="#FFFFFF">'.$title.'</font></h3>
                        </div>
                        <div class="widget-content">';
                        if($id=='widget1'){//notice
                           //********For Notices**************
                           // $limit      = ' LIMIT 0,3';  //showing first three records
                            $curDate=date('Y')."-".date('m')."-".date('d');
                            $filter=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate)";  
                             ////////////   
                            //$totalArray = $teacherManager->getTotalNotice($filter);
                            $noticeRecordArray = $teacherManager->getNoticeList($filter,' LIMIT 0,6','visibleFromDate DESC, visibleMode DESC, noticeId DESC');
                            //********For Notice(ends)**************
                            
                            $widget .='<table width="100%"  style="height:242px" border="0" id="tNotice">
                            <tr>
                             <td colspan="2" align="left" nowrap valign="top">';
                             $recordCount = count($noticeRecordArray);         
                             if($recordCount >0 && is_array($noticeRecordArray) ) { 
                              $widget .='<table width="100%"  border="0" cellpadding="0" cellspacing="0">';
                              for($i=0; $i<$recordCount; $i++ ) {
                                  if($noticeRecordArray[$i]['visibleMode']=='3') {  
                                    $visibleImageName = IMG_HTTP_PATH."/urgent1.png";
                                  }
                                  else if($noticeRecordArray[$i]['visibleMode']=='2') {  
                                    $visibleImageName = IMG_HTTP_PATH."/important1.png";  
                                  }
                                  else {
                                    $visibleImageName = IMG_HTTP_PATH."/new.gif";  
                                  }
                                  
                              $attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
                              $pic=split('-',strip_slashes($noticeRecordArray[$i]['noticeAttachment'])); 
                              $title="From : ".UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate']))." To : ".UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1); 
                              $widget .='<tr class="'.$bg.'">';
                              $widget .='<td valign="top" class="contenttab_internal_rows1" nowrap="nowrap" align="left"><nobr>
                                  <a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].');return false;" title="'.$title.'" >
                                   <ul class="myUl"><li class="contenttab_internal_rows1">';
                                   if(isset($pic[1])) {     
                                    $widget .= trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject'])),25);                                   }
                                   else {
                                    $widget .= trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject'])),25);
                                   }
                             
                              require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                              global $sessionHandler;
                              $tdays = $sessionHandler->getSessionVariable('FLASHING_NEW_ICON_NOTICES');
                              if($tdays=='') {
                               $tdays=0;  
                              } 
                              if(is_numeric($tdays) === true) {
                               $tdays = intval($tdays);  
                               if($tdays <= 0 ) {
                                $tdays = 0;   
                               }  
                              }
                              else {
                               $tdays = 0;  
                              } 
                              $dt  = $noticeRecordArray[$i]['visibleFromDate'];
                              $dtArr = explode('-',$dt);
                              $dtArr = explode('-',$dt);
                              $dt1 = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]+$tdays), date($dtArr[0])));
                              $currentDate = date('Y-m-d');
                              if($currentDate <= $dt1 && $tdays!=0) {
                                $widget .= '&nbsp;<img style="margin-top:-10px;" src="'.$visibleImageName.'">';
                              } 
                              $widget .= '</li></ul>'; 
                              $widget .= '</a></td><td valign="top" align="right" >';
                              if(isset($pic[1])) {
                                $widget .='<img style="margin-bottom:-5px;" src="'.IMG_HTTP_PATH.'/download.gif" title="'.$pic[1].'" onclick=download("'.$attactment.'"); />'; 
                              }
                              $widget .='</nobr></td></tr>';
                             }
                             $widget .= '<tr><td colspan="3" class="contenttab_internal_rows1" align="right" style="padding-right:5px"><a href="listInstituteNotice.php"><u>Show all Notices</u></a></td></tr>';  
                             $widget .='</table>';
                           }
                           else {
                             $widget .='<tr><td colspan="3" class="contenttab_internal_rows1" align="center">There are no new Notices to show</td></tr>';
                           }
                           $widget .='</table>'; 
                        }
						if($id=='widget5') {
							   //********For Admin messages**************
                            $limit      = ' LIMIT 0,7';  //showing first three records
							$orderBy = 'adm.messageId DESC';
                            $messagesRecordArray = $teacherManager->getAdminMessageList('',$limit,$orderBy);
                            //********For Messages(ends)**************
							//$lim = 
                          $widget .='<table width="100%" style="height:200px"  border="0" id="tMessages">
                                <tr>
                                <td colspan="2" align="left" style="padding-left:10px" valign="top">';
                                $recordCount = count($messagesRecordArray);
                                if($recordCount >0 && is_array($messagesRecordArray) ) { 
                                $widget .='<table width="100%"  border="0" cellspacing="5">';
                                $widget .= "<ul class='myUL'>";
                                for($i=0; $i<$recordCount; $i++ ) {
                                    $title="From : ".UtilityManager::formatDate(strip_slashes($messagesRecordArray[$i]['visibleFromDate']))." To : ".UtilityManager::formatDate(strip_slashes($messagesRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($messagesRecordArray[$i]['message'])),100,2);   
                                    $widget .='<li class="contenttab_internal_rows1"><a href="" name="bubble" onclick="showMessageDetails('.$messagesRecordArray[$i]['messageId'].');return false;" title="'.$title.'" >'
                                               .trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($messagesRecordArray[$i]['subject'])),25)
                                               .'</a></li>';
                                }
                                $widget .= "</ul>";
                                $widget .= '<tr><td align="right" style="padding-right:10px" class="contenttab_internal_rows1"><a href="listAdminMessages.php"><u>Show all admin messages</u></a></td></tr>';
                                $widget .='</table>';
                               }
                               else {
                                $widget .='<tr><td colspan="2" class="contenttab_internal_rows1" align="center" >There are no new Message to show</td></tr>';
                                }
                                $widget .='</table>';
                        }
   



                        if($id=='widget2'){//events
                          //********For Events**************
                            //$limit      = ' LIMIT 0,3';  //showing first three records
                            $curDate=date('Y')."-".date('m')."-".date('d');
                            //$filter=" AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate)";  
                            $filter =" AND DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY) <=CURDATE() AND e.endDate>=CURDATE() ";
                             ////////////   
                            //$totalArray = $teacherManager->getTotalEvent($filter);
                            $eventRecordArray = $teacherManager->getEventList($filter,$limit,'e.startDate DESC');
                            //********For Events(ends)**************
                          $widget .='<table width="100%" style="height:200px"  border="0" id="tEvent">
                                <tr>
                                <td colspan="2" align="left" style="padding-left:10px" valign="top">';
                                $recordCount = count($eventRecordArray);
                                if($recordCount >0 && is_array($eventRecordArray) ) { 
                                $widget .='<table width="100%"  border="0" cellspacing="5">';
                                $widget .= "<ul class='myUL'>";
                                for($i=0; $i<$recordCount; $i++ ) {
                                    $title="From : ".UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate']))." To : ".UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['shortDescription'])),100,2);   
                                    $widget .='<li class="contenttab_internal_rows1"><a href="" name="bubble" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].');return false;" title="'.$title.'" >'
                                               .trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['eventTitle'])),25)
                                               .'</a></li>';
                                }
                                $widget .= "</ul>";
                                $widget .= '<tr><td align="right" style="padding-right:10px" class="contenttab_internal_rows1"><a href="listInstituteEvent.php"><u>Show all Events</u></a></td></tr>';
                                $widget .='</table>';
                               }
                               else {
                                $widget .='<tr><td colspan="2" class="contenttab_internal_rows1" align="center" >There are no new Events to show</td></tr>';
                                }
                                $widget .='</table>';
                        }
                        if($id=='widget3'){//resource download count
                            //fetching course resource information
                            $courseResourceRecordArray = $teacherManager->getResourceList(' ',' ',' downloadCount desc');
                            $widget .='<table width="100%" height="100%" border="0" >
                                      <tr>
                                        <td height="236" valign="top" >
                                         <form name="searchForm" action="" method="post">
                                         <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                          <tr>
                                          <td align="left" width="100%">
                                           <div id="results" style="width:100%"> 
                                           <table width="100%" border="0" cellspacing="1" cellpadding="0"  id="anyid">
                                            <tr class="contenttab_internal_rows1">
                                             <td width="3%" class="searchhead_text" bgcolor="#DCDCDC" align="left">&nbsp;&nbsp;<b>#</b></td>
                                             <td width="200" class="searchhead_text" bgcolor="#DCDCDC"><b>&nbsp;Subject</b></td>
                                             <td width="1200" class="searchhead_text" bgcolor="#DCDCDC"><b>&nbsp;Description</b></td>
                                             <td width="130" class="searchhead_text" bgcolor="#DCDCDC"><b>&nbsp;Count</b></td>
                                            </tr>';
                                             $recordCount = count($courseResourceRecordArray);
                                             if($recordCount >0 && is_array($courseResourceRecordArray) ) { 
                                             for($i=0; $i<$recordCount; $i++ ) {
                                             if($i>=8){
                                               continue;
                                             } 
                                             $bg = $bg =='row0' ? 'row1' : 'row0';
                                             $recourceSubject=$courseResourceRecordArray[$i]['subject'];
                                             $recourceSubjectTemp=$recourceSubject;
                                             if(strlen($recourceSubject)>10){
                                              $recourceSubject=substr($recourceSubject,0,7).'...';
                                             }
                                             $recourceDescription=$courseResourceRecordArray[$i]['description'];
                                             $recourceDescriptionTemp=$recourceDescription;
                                             if(strlen($recourceDescription)>23){
                                              $recourceDescription=substr($recourceDescription,0,20).'...';
                                             }
                                             $downloadCounter=$courseResourceRecordArray[$i]['downloadCount'];
                                             $widget .='<tr class="'.$bg.'">
                                              <td valign="top" class="padding_top contenttab_internal_rows1" align="right">'.($records+$i+1).'</td>
                                              <td class="padding_top contenttab_internal_rows1" valign="top" title="'.$recourceSubjectTemp.'"><nobr>&nbsp;'.$recourceSubject.'</nobr></td>
                                              <td class="padding_top contenttab_internal_rows1" valign="top" title="'.$recourceDescriptionTemp.'">&nbsp;'.$recourceDescription.'</td>
                                              <td class="padding_top contenttab_internal_rows1" valign="top" align="right">'.$downloadCounter.'&nbsp;</td>
                                              </tr>';
                                             }
                                             $widget .='<tr><td colspan="5" align="right" style="padding-right:10px"></td></tr>';                   
                                             $widget .='<tr><td colspan="5" align="right" style="padding-right:10px"><a href="listCourseResource.php"><u>Show all Resources</u></a></td></tr>';                    
                                            }
                                           else {
                                             $widget .='<tr><td colspan="5" align="center">'.NO_DATA_FOUND.'</td></tr>';
                                             }
                                            $widget .='</table>
                                                </div>
                                                </td>
                                                </tr>
                                                </table> 
                                                </form>                  
                                                </td>
                                                </tr>
                                                </table>';
                        }
                        
                        //if($id=='widget4'){//analysis
                        if($id=='widget411'){//analysis
                          //$attendanceThresholdlimit      = ' LIMIT 0,'.RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE;
                            $teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
                            $concatStr = '';
                            foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                                $subjectId = $teacherSubjectRecord['subjectId'];
                                $classId = $teacherSubjectRecord['classId'];
                                if ($concatStr != '') {
                                    $concatStr .= ',';
                                }
                                $concatStr .= "'$subjectId#$classId'";
                            }
                            if (empty($concatStr)) {
                                $concatStr = "'0#0'";
                            }
                            
                            $attCountArray = $teacherManager->countAttendanceRecords($activeTimeTableLabelId, $concatStr);
                            $attendanceRecordCount = $attCountArray[0]['cnt'];
                            if ($attendanceRecordCount == 0) {
                                $totalStudentCountBelowThreshold = -1;
                            }
                            else {
                                $attCountArray = $teacherManager->countAttendanceThresholdRecords($activeTimeTableLabelId, $concatStr);
                                $totalStudentCountBelowThreshold = $attCountArray[0]['cnt'];
                                $strAttendanceThreshold = '';
                                if ($totalStudentCountBelowThreshold > 0) {
                                    $teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
                                    foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                                        $subjectId = $teacherSubjectRecord['subjectId'];
                                        $subjectCode = $teacherSubjectRecord['subjectCode'];
                                        $classId = $teacherSubjectRecord['classId'];
                                        $className = $teacherSubjectRecord['className'];
                                        $concatStrSub = "'$subjectId#$classId'";
                                        $concatStrSub2 = "$subjectId#$classId";
                                        $concatStrSub3 = "$subjectCode#$className";
                                        $classSubjectRecordsArray = $teacherManager->countAttendanceThresholdRecords($activeTimeTableLabelId, $concatStrSub);
                                        $classSubjectCount = $classSubjectRecordsArray[0]['cnt'];
                                        if ($classSubjectCount > 0) {
                                            $strAttendanceThreshold .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"attendanceThreshold\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                                        }
                                    }
                                }
                            }
                            
                            
                            /*
                            -----------------------------------------------
                                FOR ABSENTEES
                            -----------------------------------------------
                            */
                            
                            /*
                            -----------------------------------------------
                                FOR TOPPERS
                            -----------------------------------------------
                            */

                            $strToppers = '';
                            //$teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
                            foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                                $subjectId = $teacherSubjectRecord['subjectId'];
                                $subjectCode = $teacherSubjectRecord['subjectCode'];
                                $classId = $teacherSubjectRecord['classId'];
                                $className = $teacherSubjectRecord['className'];
                                $concatStrSub = "'$subjectId#$classId'";
                                $concatStrSub2 = "$subjectId#$classId";
                                $concatStrSub3 = "$subjectCode#$className";
                                $toppersRecordArray = $teacherManager->countTopperRecords($concatStrSub);
                                $classSubjectCount = $toppersRecordArray[0]['cnt'];
                                if ($classSubjectCount > 0) {
                                    $strToppers .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"toppers\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                                }
                            }

                            /*
                            -----------------------------------------------
                                FOR BELOW AVERAGE
                            -----------------------------------------------
                            */

                            $strBelowAvg = '';
                            foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                                $subjectId = $teacherSubjectRecord['subjectId'];
                                $subjectCode = $teacherSubjectRecord['subjectCode'];
                                $classId = $teacherSubjectRecord['classId'];
                                $className = $teacherSubjectRecord['className'];
                                $concatStrSub = "'$subjectId#$classId'";
                                $concatStrSub2 = "$subjectId#$classId";
                                $concatStrSub3 = "$subjectCode#$className";
                                $toppersRecordArray = $teacherManager->countBelowAvgRecords($concatStrSub);
                                $classSubjectCount = $toppersRecordArray[0]['cnt'];
                                if ($classSubjectCount > 0) {
                                    $strBelowAvg .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"belowAvg\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                                }
                            }
                            

                            /*
                            -----------------------------------------------
                                FOR ABOVE AVERAGE
                            -----------------------------------------------
                            */

                            $strAboveAvg = '';
                            foreach($teacherSubjectsArray as $teacherSubjectRecord) {
                                $subjectId = $teacherSubjectRecord['subjectId'];
                                $subjectCode = $teacherSubjectRecord['subjectCode'];
                                $classId = $teacherSubjectRecord['classId'];
                                $className = $teacherSubjectRecord['className'];
                                $concatStrSub = "'$subjectId#$classId'";
                                $concatStrSub2 = "$subjectId#$classId";
                                $concatStrSub3 = "$subjectCode#$className";
                                $toppersRecordArray = $teacherManager->countAboveAvgRecords($concatStrSub);
                                $classSubjectCount = $toppersRecordArray[0]['cnt'];
                                if ($classSubjectCount > 0) {
                                    $strAboveAvg .= "<tr height='25'><td valign='top' colspan='1' class='contenttab_internal_rows1'><a href='javascript:showMessageSending(\"aboveAvg\",\"$concatStrSub2\",\"$concatStrSub3\")'>$className [$subjectCode] ($classSubjectCount)</a></td></tr>";
                                }
                            }
                           $widget .='<div style="overflow:auto; height:484px;">
                                      <table border="0">
                                      <tr>
                                       <td width="100%" align="left" style="padding-left:0px;padding-top:10px;" border="0" valign="top" height="100%">
                                       <table width="100%"  border="0" cellspacing="0" height="100%" align="left">
                                       <tr>
                                       <td valign="top" colspan="1" align="left" class="contenttab_internal_rows1">
                                       <ul class="myUL"><li><u><b>Attendance Below Threshold (';
                                       if ($attendanceRecordCount == 0) {
                                       $widget .=0; 
                                       } 
                                       else{
                                           $widget .=$totalStudentCountBelowThreshold;
                                       }
                                       $widget .=' Students)</b></u></li></ul>
                                       </td>
                                       </tr>';
                                       if ($attendanceRecordCount == 0) {
                                        $widget .='<tr height="25">
                                         <td  colspan="1" class="contenttab_internal_rows1">Attendance has not been taken yet.</td></tr>';
                                       }
                                       else if($totalStudentCountBelowThreshold == 0) {
                                        $widget .='<tr height="25">
                                                   <td  colspan="1" class="contenttab_internal_rows1">No Student found below Threshold.</td></tr>';
                                       }
                                       else if($totalStudentCountBelowThreshold > 0) {
                                         $widget .= $strAttendanceThreshold;
                                       }
                                       $widget .='<tr height="25">
                                                   <td  colspan="1" class="contenttab_internal_rows1">
                                                    <ul class="myUL"><li><u><b>Exams</b></u></li></ul></td></tr>';
                                       if ($strToppers != '' or $strBelowAvg != '' or $strAboveAvg != '') {
                                       $widget .='<tr height="25">
                                                 <td  colspan="1" class="contenttab_internal_rows1"><a href="javascript:showExamStatistics();">Exam Statistics</a></td>                        </tr>
                                                 <tr>
                                                 <td  colspan="1" class="contenttab_internal_rows1">
                                                  <ul class="myUL"><li><u><b>Performance </b></u>';
                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                                  $widget .=HtmlFunctions::getInstance()->getHelpLink('Subject',HELP_TEACHER_DASHBOARD_PERFROMANCE);
                                                  $widget .='</li></ul></td></tr>';
                                       }
                                       else {
                                         $widget .='<tr height="25">
                                                      <td  colspan="1" class="contenttab_internal_rows1">No Test has been taken yet.</td>
                                                     </tr>';
                                         }
                                       if ($strToppers != '') {
                                       $widget .='<tr height="25">
                                                <td  colspan="1" class="contenttab_internal_rows1"><b>Toppers</b></td>
                                                </tr>';
                                                $widget .=$strToppers;
                                       }
                                       if ($strBelowAvg != '') {
                                       $widget .='<tr height="25">
                                                   <td  colspan="1" class="contenttab_internal_rows1"><b>Below Average</b></td>
                                                  </tr>';
                                                  $widget .=$strBelowAvg;
                                       }
                                       if ($strAboveAvg != '') {
                                       $widget .='<tr height="25">
                                                  <td  colspan="1" class="contenttab_internal_rows1"><b>Above Average</b></td>
                                                  </tr>';
                                                  $widget .=$strAboveAvg;
                                       }
                                       $widget .='</table>';
                                       $widget .='</table>';
                                       $widget .='</div>';
                        }
                                      
      $widget .=        '</div>
                    </li>';
    
    return $widget;
}

/**************************CODE FOR DRAG-DROP FEATURE*************************************/    

    
    
// $History: dashBoardList.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 2/12/10    Time: 12:25p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//done changes FCNS No. 1280
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 1/12/10    Time: 4:12p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added code to apply check if current teacher is taking any course or
//not.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 1/08/10    Time: 3:06p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//file modified to make changes on Teacher Dashboard
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/09/09    Time: 15:12
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//corrected alert code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/09   Time: 12:33
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified teacher dashboard's design.Make "Notice" box longer to
//accomodate more notices.
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/21/08   Time: 12:05p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Added alert for time table changes in dashboard
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/29/08    Time: 5:48p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/24/08    Time: 1:36p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Corrected date range in event showing criteria
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/18/08    Time: 4:11p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/16/08    Time: 4:10p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:54p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>