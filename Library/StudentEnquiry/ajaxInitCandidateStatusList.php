<?php
//-------------------------------------------------------
// Initialise Second phase of listing of Candidate Status     
//
//
// Author : Vimal Sharma
// Created on : (11.02.2009 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CandidateStatus');
    define('ACCESS','view');
    UtilityManager::ifAdmissionNotLoggedIn(true);
    UtilityManager::headerNoCache();
 
    require_once(MODEL_PATH . "/Admission/CandidateStatusManager.inc.php");
    require_once(MODEL_PATH . "/Admission/CandidateManager.inc.php"); 
    require_once(BL_PATH.'/HtmlFunctions.inc.php');          
    $candidateStatusManager = CandidateStatusManager::getInstance();
    
    /////////////////////////

    $filter = '';
    // to limit records per page    
    $page           = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records        = ($page-1)* RECORDS_PER_PAGE;
    $limit          = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter      = ' WHERE (aaf.candidateName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR aaf.formNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" 
             OR aaf.AIEEERollNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR aaf.AIEEERank LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy    = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField      = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : ' acs.displayOrder, aaf.AIEEERank';
    
     $orderBy       = " $sortField $sortOrderBy";         
    
    //Process for Extra Selection Criteria
    $selectedProgram        = (!UtilityManager::IsNumeric($REQUEST_DATA['allPrograms']) || UtilityManager::isEmpty($REQUEST_DATA['allPrograms']) ) ? 0 : $REQUEST_DATA['allPrograms']; 
    if ($selectedProgram != 0 ) {
        if (empty($filter)) {
            $filter = " WHERE acs.programId = $selectedProgram "; 
        } else {
            $filter .= " AND acs.programId = $selectedProgram ";    
        }   
    }
    $selectedStatusType     = (UtilityManager::isEmpty($REQUEST_DATA['statusType'])) ? 'AL' : $REQUEST_DATA['statusType']; 
    if ($selectedStatusType == 'SL'){
        if (empty($filter)) {
            $filter = " WHERE (acs.candidateStatus  = 'A' OR acs.candidateStatus = 'O') ";  
        } else {
            $filter .= " AND (acs.candidateStatus  = 'A' OR acs.candidateStatus = 'O') ";
        }      
    } elseif($selectedStatusType != 'AL'){
        if (empty($filter)) {
            $filter = " WHERE (acs.candidateStatus  = '$selectedStatusType') ";  
        } else {
            $filter .= " AND (acs.candidateStatus  = '$selectedStatusType') ";
        }      
    }
    
    $programType            = 'G';
    $graduateProgramArray   = CandidateManager::getInstance()->getProgramPreference($programType);
    $graduatePrefTotal      = count($graduateProgramArray); 
    $graduatePrograms       = '';
    $graduateProgramsEdit   = '';
    
    for($i=1; $i <= $graduatePrefTotal; $i++) {
        $graduatePrograms .= '<tr>
                            <td class="contenttab_internal_rows"><nobr><b>' . $i . '</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="G' . $i . '" id="G' . $i . '" disabled>
                            <option value="0">Select</option>' ;
        $graduatePrograms .= HtmlFunctions::getInstance()->getProgramPreferenceList($programType);
        $graduatePrograms .= '</select></td></tr>';
    }
    $graduatePrograms .= '</table></div>'; 
    
   // $graduateProgramsEdit = '<div id="gpPreferenceEdit" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . $graduatePrograms ;   
    $graduatePrograms     = '<div id="gpPreference" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . $graduatePrograms;
    
    $postGraduateProgramArray   = CandidateManager::getInstance()->getProgramPreference('P');
    $postGraduatePrefTotal      = count($postGraduateProgramArray); 
    $postGraduatePrograms       = '';
    $postGraduateProgramsEdit   = '';
    for($i=1; $i <= $postGraduatePrefTotal; $i++) {
        $postGraduatePrograms .= '<tr>
                            <td class="contenttab_internal_rows"><nobr><b>' . $i . '</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="PG' . $i . '" id="PG' . $i . '" disabled>
                            <option value="0">Select</option>' ;
        $postGraduatePrograms .= HtmlFunctions::getInstance()->getProgramPreferenceList('P');

    }   
    $postGraduatePrograms .= '</table></div>'; 
  //  $postGraduateProgramsEdit = '<div id="pgpPreferenceEdit" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . $postGraduatePrograms;         
    $postGraduatePrograms     = '<div id="pgpPreference" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . $postGraduatePrograms;    


                                           
    ////////////
    $resultTotal   = $candidateStatusManager->getTotalCandidateStatus($filter); 
    $candidateRecordArray   = $candidateStatusManager->getCandidateStatusList($filter,$limit,$orderBy);
    if (isset($candidateRecordArray) && count($candidateRecordArray) > 0 ) { 

        $totalRecords       = count($candidateRecordArray);

        for ($i=0; $i < $totalRecords; $i++) {
            $candidateRecordArray[$i]['candidateStatus']  = $candidateStatusArr[$candidateRecordArray[$i]['candidateStatus']];
        $showlink = "<a href='#' onClick='editWindow(".$candidateRecordArray[$i]['candidateId'] .",\"editForm\",715,250)' title='Edit'><img src='".IMG_HTTP_PATH."/edit.gif' border='0' /></a>";
        $valueArray = array_merge(array('actionStatus' => $showlink , 'srNo' => ($records+$i+1) ),$candidateRecordArray[$i]);
            
            //$valueArray = array_merge(array('srNo' => ($records+$i+1) ),$candidateRecordArray[$i]);
            if(trim($json_val)=='') {
                $json_val = json_encode($valueArray);
            } else {
                $json_val .= ','.json_encode($valueArray);           
            }
        }
        $totalRecords = $resultTotal[0]['totalRecords'];
        echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"' . $totalRecords . '","page":"'.$page.'","info" : ['.$json_val.']}'; 
    } else {
        echo 0;    
    }
    
?>