<?php
//-------------------------------------------------------
// Purpose: To add items
//
// Author : DB
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','ItemsSupplierMapping');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if(!isset($REQUEST_DATA['itemId']) || trim($REQUEST_DATA['itemId']) == '') {
        $errorMessage .= INVALID_ITEM_RECORD. "\n";
    }
    
    $itemId=add_slashes(trim($REQUEST_DATA['itemId']));
    
    if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/ItemsManager.inc.php");
        $foundArray=ItemsManager::getInstance()->getItem(' WHERE itemId ="'.$itemId.'"');
        //existense Check
        if($foundArray[0]['itemId']==''){
            echo INVALID_ITEM_RECORD;
            die;
        }
       
        //supplier and price check
        if(trim($REQUEST_DATA['supplierIds'])!='' or trim($REQUEST_DATA['priceStr'])!=''){
            $supplierArray1=array_unique(explode(',',trim($REQUEST_DATA['supplierIds'])));
            $priceArray=explode(',',trim($REQUEST_DATA['priceStr']));
            $supplierNameArray=array_unique(explode(',',trim($REQUEST_DATA['supplierNameStr'])));
            if(count($supplierArray1)!=count($priceArray)){
                echo 'Mismatched supplier and price list';
                die;
            }
            
            $supplierArray2=ItemsManager::getInstance()->getSupplierList(' WHERE supplierId IN ('.trim($REQUEST_DATA['supplierIds']).')');
            $supplierArray2=explode(',',UtilityManager::makeCSList($supplierArray2,'supplierId'));
            $diff=array_diff($supplierArray1,$supplierArray2);
            if(count($diff)>0){
                $id=$diff[0];
                $c=count($supplierNameArray);
                $sName='';
                for($i=0;$i<$c;$i++){
                    $ids=explode('~!~',$supplierNameArray[$i]);
                    if($id==$ids[0]){
                        $sName=$ids[1];
                        break;
                    }
                }
              echo 'Supplier Code : '.$sName.' does not exist';
              die;
            }
           //checking 
           $count=count($supplierArray1);
           for($i=0;$i<$count;$i++){
               if(trim($supplierArray1[$i])==''){
                   echo ENTER_ITEM_PRICE;
                   die;
               }
               if(!is_numeric(trim($supplierArray1[$i]))){
                   echo ENTER_ITEM_PRICE_IN_NUMERIC;
                   die;
               }
               if(trim($supplierArray1[$i])<=0){
                   echo ENTER_ITEM_PRICE_GREATER_THAN_ZERO;
                   die;
               }
           }
        }
    
       if(SystemDatabaseManager::getInstance()->startTransaction()) { 

            //add item-supplier mapping
            $rStatus=ItemsManager::getInstance()->addItemSupplierMapping($itemId,$supplierArray1,$priceArray);
            if($rStatus===false){
                echo FAILURE;
                die;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()){
                echo SUCCESS;
                die;
            }
            else{
                echo FAILURE;
                die;
            } 
       }
       else{
           echo FAILURE;
           die;
       }
 	 }
    else {
        echo $errorMessage;
    }
// $History: ajaxDoItemSupplierMapping.php $    
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/09/09   Time: 11:09
//Updated in $/Leap/Source/Library/INVENTORY/ItemsMaster
//corrected access permission
?>