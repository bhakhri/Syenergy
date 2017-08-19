<?php
/*
  This File initialises salary sheet

 Author :Abhiraj Malhotra
 Created on : 04-May-2010
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(MODEL_PATH . "/PayrollManager.inc.php");
 /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
   // $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
   // $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    // $orderBy = " $sortField $sortOrderBy";         
        $headsArray=$REQUEST_DATA['headsArray'];   
        if(count($headsArray)==0)
        {
          echo 1;  
        }
        else
        {
        
        if(trim($REQUEST_DATA['month'])!='' && trim($REQUEST_DATA['year'])!="") 
        {   
            //logError("Month for sheet is: ".$REQUEST_DATA['month']);
            $sessionHandler->setSessionVariable('selectedHeads',$headsArray);
            $foundArray = PayrollManager::getInstance()->getSalariedEmployee("where MONTHNAME(withEffectFrom) LIKE '".trim($REQUEST_DATA['month'])."' and YEAR(withEffectFrom)=".trim($REQUEST_DATA['year']));
            $totalArray[0]['totalRecords'] = count($foundArray);
            $month=trim($REQUEST_DATA['month']);
            $year=trim($REQUEST_DATA['year']);   
			$cnt=count($foundArray);
            $count=0;
            $amount=0;
            $totalEarning=0;
            $totalDeduction=0;
            $htmlstr='<table width=100% border="0" cellspacing="0" cellpadding="0">
            <tr class="rowheading">
            <td class="searchhead_text" style="border-right: 1px solid #fff" align="left">
            <b>#</b>
            </td>
            <td class="searchhead_text" style="border-right: 1px solid #fff" align="left">
            <b>Name</b>
            </td>
            <td class="searchhead_text" style="border-right: 1px solid #fff" align="left">
            <b>Code</b>
            </td>
            <td class="searchhead_text" style="border-right: 1px solid #fff" align="left">
            <b>Department</b>
            </td>
            <td class="searchhead_text" style="border-right: 1px solid #fff" align="left">
            <b>Designation</b>
            </td>
            ';
            for($i=0;$i<count($headsArray);$i++)
            {
                
                $headAbbr=PayrollManager::getInstance()->getHeadAbbr("where headName like'".$headsArray[$i]."'");
                $headType=PayrollManager::getInstance()->getHeadType("where headName like'".$headsArray[$i]."'");
                if($headType[0]['headType']==0)
                {
                    $htmlstr .= '
                    <td class="contenttab_row1" style="border-right: 1px solid #fff;" align="right">
                    <b>'.$headAbbr[0]['headAbbr'].'</b>
                    </td>';
                }
                else
                {
                    $htmlstr .= '
                    <td class="contenttab_row1" style="border-right: 1px solid #fff;  color:red" align="right">
                    <b>'.$headAbbr[0]['headAbbr'].'</b>
                    </td>';  
                }
                  
            }
            $htmlstr .= '<td class="searchhead_text" style="border-right: 1px solid #fff" align="right">
            <b>Net</b>
            </td>';
            $htmlstr .= '</tr>';
            for($i=0;$i<$cnt;$i++)
            {
               $totalEarning=0;
               $totalDeduction=0;
               $net=0;
               $employeeId=$foundArray[$i]['employeeId'];
               logError("Month for sheet is: ".$month); 
               $conditions="where employeeId=".$employeeId." and MONTHNAME(withEffectFrom) like '".$month."' and YEAR(withEffectFrom) like '".$year."'";
               $foundAssignedArray = PayrollManager::getInstance()->getAssignedHeads($conditions);
               $cnt1=count($foundAssignedArray);
               if($cnt1>0)
               {
                   $count=$count+1;
                   $department = PayrollManager::getInstance()->getSingleField('department','departmentName','where departmentId='.$foundArray[$i]['departmentId']);
                   $designation = PayrollManager::getInstance()->getSingleField('designation','designationName','where designationId='.$foundArray[$i]['designationId']);
                   $dataArray[$i] = array('srNo'=>($records+$i+1),'empName'=>$foundArray[$i]['employeeName'], 'empCode'=>$foundArray[$i]['employeeCode']
                   , 'empDepartment'=>$department[0]['departmentName'],'empDesignation'=>$designation[0]['designationName']);
                    
                    
                    for($k=0;$k<count($headsArray)+1;$k++)
                    {
                       $value[$k]=0; 
                    }
                   for($j=0;$j<$cnt1;$j++)
                   {
                       $foundHeadArray=PayrollManager::getInstance()->getHead('where headId='.$foundAssignedArray[$j]['headId']);
                      
                          //logError('1111111111111111111111'.strlen(array_search(trim($foundHeadArray[0]['headName']),$headsArray)));
                           if(strlen(array_search(trim($foundHeadArray[0]['headName']),$headsArray))>0)
                           {  
 
                              $key=array_search($foundHeadArray[0]['headName'],$headsArray);
                               $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails($foundArray[$i]['employeeId'],
                               substr($month,0,3),$year);
                               //done for checking salary on hold or not
                               if(count($holdArray)>0 && $holdArray[0]['status']==1)
                               {
                                   $value['key']=0;
                               }
                               else 
                               {
                                 
                                 $value[$key]=$foundAssignedArray[$j]['headValue']; 
                           
                               }
                       
                               if($foundHeadArray[0]['headType']==0)
                               {
                                   $totalEarning=$totalEarning+$value[$key];
                               }
                               elseif($foundHeadArray[0]['headType']==1)
                               { 
                                   $totalDeduction=$totalDeduction+$value[$key];
                               }
                       
                           }
                           else
                           {
                             $totalEarning=$totalEarning+0;
                             $totalDeduction=$totalDeduction+0;  
                           }  
                       //$valueArray[$i]=array_merge($valueArray[$i],array($foundHeadArray[0]['headName']=>$foundAssignedArray[$j]['headValue']));
                   }
                
                   //logError("xxxxxxxx".$totalEarning);
                   //logError("yyyyyyyy".$totalDeduction);
                   $net=$totalEarning-$totalDeduction;
                   $value[count($headsArray)]=$net;
                   $dataArray[$i]=array_merge($dataArray[$i],$value); 
                   
               }
             
            }
            //$amount=$totalEarning-$totalDeduction;
            //$valueArray=array('amount'=>$amount, 'count'=>$count, 'empCount'=>$cnt);
        $sessionHandler->setSessionVariable('dataArray',$dataArray);    
        for($i=0;$i<count($dataArray);$i++)
        {
            if($i%2==0)
            {
                $class="row0";
            }
            else
            {
                $class="row1";
            }
            $empId=PayrollManager::getInstance()->getSingleField('employee','employeeId','where 
            employeeCode='."'".$dataArray[$i]['empCode']."'");
            $empId=$empId[0]['employeeId'];
            $holdArray = PayrollManager::getInstance()->getSalaryHoldDetails($empId,substr($month,0,3),$year);
            //done for checking salary on hold or not
            if(count($holdArray)>0 && $holdArray[0]['status']==1)
            {
                logError("hello abiraaaj");
                $style=';text-decoration:line-through"';
            }
            else
            {
                $style=''; 
            } 
            $htmlstr .='<tr class="'.$class.'">';
              $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff'.$style.'" align="left">'.$dataArray[$i]['srNo'].'</td>';
              $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff'.$style.'" align="left">'.$dataArray[$i]['empName'].'</td>';
              $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff'.$style.'" align="left">'.$dataArray[$i]['empCode'].'</td>';
              $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff'.$style.'" align="left">'.$dataArray[$i]['empDepartment'].'</td>';
              $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff'.$style.'" align="left">'.$dataArray[$i]['empDesignation'].'</td>';
             for($j=0;$j<count($headsArray)+1;$j++)  
             {
                 $headType=PayrollManager::getInstance()->getHeadType("where headName like'".$headsArray[$j]."'");
                if($headType[0]['headType']==0)
                {
                    $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff'.$style.'" align="right">'.$dataArray[$i][$j].'</td>';
                }
                else
                {
                    $htmlstr .='<td class="contenttab_row1" style="border-right: 1px solid #fff; color:red'.$style.'" align="right">'.$dataArray[$i][$j].'</td>';  
                }
                 
             }
            //$htmlstr .='<td class="contenttab_row1" >'. $totalEarning-$totalDeduction.'</td>';           
            $htmlstr .='</tr>';
            
        }
         $htmlstr .='<tr class=row0>
         <td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666"></td>
         <td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666"></td>
         <td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666"></td>
         <td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666"></td>
         <td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666"></td>
         ';
        for($i=0;$i<count($headsArray)+1;$i++)
        {               
            for($j=0;$j<count($dataArray);$j++)
            {
               $total[$i]= $total[$i]+$dataArray[$j][$i]; 
            }
        }
        $sessionHandler->setSessionVariable('total',$total);
         for($i=0;$i<count($headsArray)+1;$i++)
         {
             $headType=PayrollManager::getInstance()->getHeadType("where headName like'".$headsArray[$i]."'");
                if($headType[0]['headType']==0)
                {
                    $htmlstr .= '<td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666" align="right">'.$total[$i].'</td>';
                }
                else
                {
                    $htmlstr .= '<td class="contenttab_row1" style="border-right: 1px solid #fff; border-top:1px dashed #666; border-bottom:1px dashed #666; color:red" align="right">'.$total[$i].'</td>';  
                } 
             
         }
         echo $htmlstr;
        }
        else 
        {
            echo 0;
        }
        //print_r($dataArray);
        
        }
?>

