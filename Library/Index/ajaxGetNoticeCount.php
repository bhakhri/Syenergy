<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
//
// Author :Vritee 
// Created on : (20.9.2011 )
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance(); 
    global $sessionHandler;  
    $roleId=$sessionHandler->getSessionVariable('RoleId');  
    if($roleId=='4') { 
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else if($roleId=='3') { 
      UtilityManager::ifParentNotLoggedIn(true);  
    }
    else if($roleId=='5') { 
      UtilityManager::ifManagementNotLoggedIn(true);   
    }
    else if($roleId=='2') { 
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();
    
    require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
    
    
    $limit    = ' LIMIT 0,16';  //showing first three records  // DONT REINITIALIZE VARIABLE UNLESS IT IS NEEDED
    $curDate  = date('Y-m-d');
    $filter   = " AND ('$curDate' >= visibleFromDate AND '$curDate' <= visibleToDate)";  
    
    $noticeRecordArray = $dashboardManager->getNoticeList($filter,'','n.noticeId DESC,n.visibleFromDate DESC');
    $recordCount=count($noticeRecordArray);
    
    
    $tableData ='<table width="100%" border="0">';
    if($recordCount >0 && is_array($noticeRecordArray) ) { 
        for($i=0; $i<$recordCount; $i++ ) {
            $downloadCount = ' ('.$noticeRecordArray[$i]['downloadCount'].')';
                $title="From : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleFromDate']))." To : ".strip_slashes(UtilityManager::formatDate($noticeRecordArray[$i]['visibleToDate']))."     ".trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText'])),500,1); 
                $tableData .= '<tr class="'.$bg.'">
                                <td valign="top" class="padding_top" align="left" height="10">&bull;&nbsp;&nbsp;
                                    <a href="" name="bubble" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);return false;" title="'.$title.'" ><span style="color:#'.$noticeRecordArray[$i]['colorCode'].'">'.strip_tags(trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),35)).'.'.$downloadCount.'- <I>'.$noticeRecordArray[$i]['abbr'].'</I></span>';
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
                  $tableData .=  '&nbsp;<img src="'.IMG_HTTP_PATH.'/new.gif">';
                }                            
                $tableData .=  '</a>&nbsp;&nbsp;';
                $tableData .=  '</td>';
                $fileName = IMG_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
                if(file_exists($fileName) && ($noticeRecordArray[$i]['noticeAttachment']!='')){
                    $fileName1 = IMG_HTTP_PATH."/Notice/".$noticeRecordArray[$i]['noticeAttachment'];
                    $tableData .= '<td valign="top" align="right"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
                }
                $tableData .= '</tr>';
          }
          $tableData .=  '<tr><td colspan="2" align="right"><a href="listNotice.php"><u>More</u></a>&raquo;</td></tr>'; 
      }
      else {
        $tableData .=  '<tr><td colspan="2" align="center" valign="middle" height="135">No Notice</td></tr>';
      }
      
    $tableData .= "</table>";
    
    echo $tableData;
die;
    
function trim_output($str,$maxlength,$mode=1,$rep='...'){
      $ret=($mode==2?chunk_split($str,12):$str);
      if(strlen($ret) > $maxlength){
        $ret=substr($ret,0,$maxlength).$rep; 
      }
      return $ret;  
}
?>

