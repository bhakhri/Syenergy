<?php
    //plain text header
    header("Content-Type: text/plain; charset=UTF-8");

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==1){//for admin
     UtilityManager::ifNotLoggedIn();
    }
    else if($roleId==2){//for teacher
     UtilityManager::ifTeacherNotLoggedIn();   
    }
    else{
        UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache();

    $postData = json_decode($HTTP_RAW_POST_DATA);
    $postDataArray = explode('&',$postData);
    foreach($postDataArray as $postRecord) {
        list($postKey,$postValue) = explode('=',$postRecord);
        $REQUEST_DATA[$postKey] = $postValue;
    }
    $groupText = $_REQUEST['text'];
    $entryTask = $_REQUEST['requesting'];
    if ($entryTask == 'ledgerGroups') {

        require_once(MODEL_PATH . '/Accounts/LedgerManager.inc.php');
        $ledgerManager = LedgerManager::getInstance();

        $groupsArray = $ledgerManager->getLedgerGroups($groupText);
        echo json_encode($groupsArray);
    }
    elseif ($entryTask == 'companyGroups') {

        require_once(MODEL_PATH . '/Accounts/GroupsManager.inc.php');
        $groupsManager = GroupsManager::getInstance();

        $groupsArray = $groupsManager->getGroups($groupText);
        echo json_encode($groupsArray);
    }
    
    elseif ($entryTask == 'rollNumber') {

        require_once(MODEL_PATH . '/StudentManager.inc.php');
        $studentManager = StudentManager::getInstance();

        $studentArray = $studentManager->getStudentRoll($groupText);
        echo json_encode($studentArray);
    }
    
     elseif ($entryTask == 'fineRollNumber') {

        require_once(MODEL_PATH . '/FineManager.inc.php');
        $fineManager = FineManager::getInstance();

        $studentArray = $fineManager->getStudentRoll($groupText);
        echo json_encode($studentArray);
    }

    elseif ($entryTask == 'receiptVoucherDr' or $entryTask == 'paymentVoucherCr') {

        require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
        $voucherManager = VoucherManager::getInstance();

        $ledgerArray = $voucherManager->getLedgersRDPC($groupText);

        echo json_encode($ledgerArray);
    }

    elseif ($entryTask == 'receiptVoucherCr' or $entryTask == 'paymentVoucherDr' or  $entryTask == 'journalVoucherDr'  or  $entryTask == 'journalVoucherCr') {

        require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
        $voucherManager = VoucherManager::getInstance();

        $ledgerArray = $voucherManager->getLedgersRCPDJDJC($groupText);

        echo json_encode($ledgerArray);
    }
    elseif ($entryTask == 'contraVoucherDr' or $entryTask == 'contraVoucherCr') {

        require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
        $voucherManager = VoucherManager::getInstance();

        $ledgerArray = $voucherManager->getLedgersCDCC($groupText);

        echo json_encode($ledgerArray);
    }

?>