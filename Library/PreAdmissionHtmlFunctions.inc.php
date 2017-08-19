<?php
//-------------------------------------------------------
// Purpose: To generate dropdown's like city, state, country, degree etc
// functionality
//
// Created on : (15.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(MODEL_PATH."/PreAdmissionManager.inc.php");

class PreAdmissionHtmlFunctions {

	private static $instance = NULL;

	private function __construct() {
	}

	public static function getInstance() {
		if (PreAdmissionHtmlFunctions::$instance === NULL) {
			$class = __CLASS__;
			PreAdmissionHtmlFunctions::$instance = new $class;
		}
		return PreAdmissionHtmlFunctions::$instance;
	}

    
    public function getPreAdmissionData($tableName='',$selected='',$condition='',$orderBy='',$check='') {
        
        if($tableName=='Camp') {
          $results = PreAdmissionManager::getInstance()->getCampList($condition,$orderBy);
        }
        else if($tableName=='School') {
          $results = PreAdmissionManager::getInstance()->getSchoolList($condition,$orderBy);
        }
        else if($tableName=='Course') {
          $results = PreAdmissionManager::getInstance()->getCourseList($condition,$orderBy);
        }
        else if($tableName=='Religion') {
          $results = PreAdmissionManager::getInstance()->getPreadmissionReligion($condition,$orderBy);
        }
        else if($tableName=='Category') {
          $results = PreAdmissionManager::getInstance()->getPreadmissionCategory($condition,$orderBy);
        }
        else if($tableName=='Domicile') {
          $results = PreAdmissionManager::getInstance()->getPreadmissionDomicile($condition,$orderBy);
        }
        else if($tableName=='ExamTest') {
          $results = PreAdmissionManager::getInstance()->getPreadmissionExamTest($condition,$orderBy);
        }
        
        
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $ids= $results[$i]['id'];
                $name= $results[$i]['name'];
                $abbr= $results[$i]['abbr'];
                if($tableName=='Camp') {
                  $str =$ids."~".$abbr;  
                  $ids=$str;
                }
                if($check=='1') {
                   if($ids==$selected) {
                     $returnValues .='<input type="checkbox" name="examType[]" value="'.$ids.'" id="examType'.($ids).'" checked="checked">
                     <span type="contenttab_internal_rows">'.$idstrip_slashes($name).'</span>&nbsp;&nbsp;';
                   }
                   else {
                     $returnValues .='<input type="checkbox" name="examType[]"  value="'.$ids.'" id="examType'.($ids).'">
                     <span type="contenttab_internal_rows">'.strip_slashes($name).'</span>&nbsp;&nbsp;'; 
                   } 
                }
                else {
                   if($ids==$selected) {
                     $returnValues .='<option value="'.$ids.'" SELECTED="SELECTED">'.strip_slashes($name).'</option>';
                   }
                   else {
                     $returnValues .='<option value="'.$ids.'">'.strip_slashes($name).'</option>';
                   }
                }
            }

        }
        return $returnValues;
   }
}
?>
