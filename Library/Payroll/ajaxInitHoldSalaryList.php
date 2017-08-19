<?php
//-------------------------------------------------------
// Purpose: To display employees after salary is generated 
//
// Author : Abhiraj Malhotra
// Created on : 10-May-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Payroll');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    global $sessionHandler;
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 :                                $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    $filter='';
    $filterFlag='';
    /// Search filter /////  
      if(UtilityManager::notEmpty($REQUEST_DATA['searchfield'])) {
          $startPos=strpos(trim($REQUEST_DATA['searchfield']),"(")+1;
          $endPos=strripos(trim($REQUEST_DATA['searchfield']),"(")-1;
          $len=($endPos-$startPos)+1;
          $employeeCode=substr(trim($REQUEST_DATA['searchfield']),$startPos,$len);
          $employeeCode=trim($employeeCode);
          $filter = "and employeeCode like '".$employeeCode."' and instituteId=".$sessionHandler->getSessionVariable("InstituteId");
          $filterFlag="notEmpty";
          $name=substr(trim($REQUEST_DATA['searchfield']),0,strpos(trim($REQUEST_DATA['searchfield']),"("));
          $valid = CommonQueryManager::getInstance()->getEmployee('employeeName',' and employeeName='."'".$name."'".' and employeeCode='."'".$employeeCode."'".' and instituteId='.$sessionHandler->getSessionVariable("InstituteId"));
          if(count($valid)==0)
          {
              $err="true";
          }
      }
    else
    {
        
        $filter="and instituteId=".$sessionHandler->getSessionVariable("InstituteId");
        $filterFlag="empty";
         
    }
    if($err!="true")
    {
    /// order by /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = "$sortField $sortOrderBy";         
     $condition="$orderBy $limit";
    $dataArray = CommonQueryManager::getInstance()->getEmployee($condition,$filter);
    if($filterFlag=="empty")
    {
    $totalDataArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$filter);
    //echo "Records number: ".count($totalDataArray);
    $totalArray[0]['totalRecords']=count($totalDataArray);
    }
    else
    {
       $totalArray[0]['totalRecords']=1; 
    }
    //$headRecordArray = $payrollManager->getHeadList($filter,$limit,$orderBy);
    $cnt = count($dataArray);
    $imgPath=IMG_HTTP_PATH;
    $value=$sessionHandler->getSessionVariable("UserThemeId");
     if ($value == 1) {
                 $imgPath=IMG_HTTP_PATH;
            }
            elseif ($value == 2) {
                 $imgPath=IMG_HTTP_PATH.'/Themes/Brown';
            }
            elseif ($value == 3) {
                $imgPath=IMG_HTTP_PATH.'/Themes/Green';
               
            }
            elseif ($value == 4) {
               $imgPath=IMG_HTTP_PATH.'/Themes/Orange'; 
               
            }
            elseif ($value == 5) {
                 $imgPath=IMG_HTTP_PATH.'/Themes/Green_light'; 
                
            }
            elseif ($value == 6) {
                $imgPath=IMG_HTTP_PATH.'/Themes/Violet';   
              
            }
            elseif ($value == 7) {
                $imgPath=IMG_HTTP_PATH.'/Themes/Blue_light';
             
            }
            else {
                
                $imgPath=IMG_HTTP_PATH; 
            }
           
    for($i=0;$i<$cnt;$i++) { 
        $holdArray= $payrollManager->getSalaryHoldDetails($dataArray[$i]['employeeId'],$REQUEST_DATA['month'],$REQUEST_DATA['year']);
        $holdHistoryArray= $payrollManager->getSalaryHoldDetails($dataArray[$i]['employeeId']);
        $holdCount=count($holdArray);
        $holdHistoryCount=count($holdHistoryCount);
        if($holdCount==0)
        {
           $action="<input type=image src=".$imgPath."/hold.gif    
           align='center' onClick=showHoldSalary(".$dataArray[$i]['employeeId'].",'".$REQUEST_DATA['month']."','".$REQUEST_DATA['year']."');              return false; title='Hold Salary' />&nbsp";
        }
        elseif($holdCount>0 && $holdArray[0]['status']==0)
        {
            
           $action="<input type=image src=".$imgPath."/hold.gif 
           align='center' onClick=showHoldSalary(".$dataArray[$i]['employeeId'].",'".$REQUEST_DATA['month']."','".$REQUEST_DATA['year']."');             return false;' title='Hold Salary' />&nbsp";
        }
        else
        {
             $action="<input type=image src=".$imgPath."/unhold.gif 
             align='center' onClick=showUnholdSalary(".$dataArray[$i]['employeeId'].",'".$REQUEST_DATA['month']."','".$REQUEST_DATA['year']."');              return false;' title='Unhold Salary' />&nbsp";
        }
        if($holdHistoryCount>0)
        {
            $viewHistory="<img src=".IMG_HTTP_PATH."/eye.png    
            align='center' onClick='viewHistory(".$dataArray[$i]['employeeId']."); return false;' title='View History' />&nbsp";
        }
        else
        {
           $viewHistory="<img src=".IMG_HTTP_PATH."/eye.png    
           align='center' title='No History Available' onClick=noHistory(); />&nbsp"; 
        }
        $action=$action.'&nbsp;'.$viewHistory;
        if($dataArray[$i]['departmentId']!="")
        {
         $departmentName = PayrollManager::getInstance()->getSingleField('department','departmentName','where departmentId='.$dataArray[$i]         ['departmentId']);
        }
        else
        {
          $departmentName[0]['departmentName']="--";  
        }
        if($dataArray[$i]['designationId']!="")
        {
        $designation = PayrollManager::getInstance()->getSingleField('designation','designationName','where designationId='.$dataArray[$i][         'designationId']);
        }
        else
        {
           $designation[0]['designationName']="--"; 
        }
        $valueArray = array('employeeName' =>  $dataArray[$i]['employeeName'], 'employeeCode' => $dataArray[$i]['employeeCode'], 
        'takeAction'=>  $action, 'department' =>  $departmentName[0]['departmentName'] , 'designation' =>
        $designation[0]['designationName'],'srNo' => ($records+$i+1));

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    }
    else
    {
        $emptyArray=array();
        $emptyArray=json_encode($emptyArray);
        echo '{"totalRecords":"'.count($emptyArray).'","info" :[]}';
    }
  

?>
