<?php
//-------------------------------------------------------
// Purpose: To generate dropdown's like city, state, country, degree etc
// functionality
//
// Created on : (15.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   require_once(MODEL_PATH."/CommonQueryManager.inc.php");
   require_once(MODEL_PATH."/AdminTasksManager.inc.php");
   require_once(MODEL_PATH."/EmployeeReportsManager.inc.php");

   if(CURRENT_PROCESS_FOR=="cc"){
    require_once(MODEL_PATH."/Teacher/TeacherManager.inc.php");
   }
   elseif(CURRENT_PROCESS_FOR=="sc"){
    require_once(MODEL_PATH."/Teacher/ScTeacherManager.inc.php");
   }

class HtmlFunctions {

	private static $instance = NULL;

	private function __construct() {
	}

	public static function getInstance() {
		if (HtmlFunctions::$instance === NULL) {
			$class = __CLASS__;
			HtmlFunctions::$instance = new $class;
		}
		return HtmlFunctions::$instance;
	}

	




public function getRoleNameReceived($condition='')
{ $results = CommonQueryManager::getInstance()->getRoleNames($condition);
$returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                    $returnValues .='<option value="'.$results[$i]['roleId'].'">'.strip_slashes($results[$i]['roleName']).'</option>';
            }

        }
        return $returnValues;
	}


    public function tableBlueHeader($title='',$width='width="400"',$height='',$align='center',$valign='') {
        return '<table '.$width.' '.$height.' border="0" cellspacing="0" cellpadding="0" align="'.$align.'">
          <tr>
            <td align="left" width="11" class="box_left"></td>
            <td align="left" style="background-repeat:repeat-n;" class="box_middle" height="31"><b class="fontTitleM">'.$title.'</b></td>
            <td align="right" width="11" class="box_right"></td>
          </tr>
          <tr>
            <td align="left" class="leftBorder">&nbsp;</td>
            <td align="left" '.$valign.'>';
    }
   public function tableBlueFooter() {
        return '</td>
            <td class="rightBorder">&nbsp;</td>
          </tr>
          <tr>
            <td height="1" valign="top" colspan="3" class="bottomBorder"><img src="'.IMG_HTTP_PATH.'/spacer.gif" height="3px"></td>
          </tr>
        </table>';
    }

	//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF RANGE OF MARKS
//
// Created on : (4/11/2011)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getMarksRange() {
        $rangeArray = CommonQueryManager::getInstance()->getRanges();
        $returnValues = '';
      	foreach($rangeArray as $rangeRecord) {
			$lowMarksValue = $rangeRecord['lowMarksValue'];
			$highMarksValue = $rangeRecord['highMarksValue'];
			$returnValues .= $lowMarksValue.' - '.$highMarksValue;
			$returnValues .=", ";
		}
		$returnValues = rtrim($returnValues,', ');
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Collect fee Class
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getCollectFeeClassData($selected='',$condition='',$orderBy=' className') {
        $results = CommonQueryManager::getInstance()->getCollectFeeClass($condition,$orderBy);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Countries  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getCountriesData($selected='') {
		$results = CommonQueryManager::getInstance()->getCountries('countryName');
		$returnValues = '';
		if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++){

					if (in_array($results[$i]['countryId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['countryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['countryName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['countryId'].'">'.strip_slashes($results[$i]['countryName']).'</option>';
					}
				}
			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['countryId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['countryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['countryName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['countryId'].'">'.strip_slashes($results[$i]['countryName']).'</option>';
					}
				}
			}
		}
		return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF nationality  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getNationalityData($selected='') {
		$results = CommonQueryManager::getInstance()->getCountries('countryName');
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				if($results[$i]['countryId']==$selected) {
					$returnValues .='<option value="'.$results[$i]['countryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['nationalityName']).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['countryId'].'">'.strip_slashes($results[$i]['nationalityName']).'</option>';
				}
			}

		}
		return $returnValues;
   }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATES  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getStatesData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getStates('stateName',$condition='');
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++){

					if (in_array($results[$i]['stateId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['stateId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['stateName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['stateId'].'">'.strip_slashes($results[$i]['stateName']).'</option>';
					}
				}
			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['stateId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['stateId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['stateName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['stateId'].'">'.strip_slashes($results[$i]['stateName']).'</option>';
					}
				}
            }
        }
        return $returnValues;
   }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Institutes
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//Modified By

// Author :Arvind Singh Rawat
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

 public function getInstituteData($selected='') {
        $results = CommonQueryManager::getInstance()->getInstitute('instituteCode');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['instituteId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }


   public function getEmployeeInstitutes($selected='') {
		global $sessionHandler;
		$selected = $sessionHandler->getSessionVariable('InstituteId');
        $results = CommonQueryManager::getInstance()->getEmployeeInstitutes('instituteCode');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['instituteId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }
//-------------------------------------------------------
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

 public function getEmployeeInstituteData($instituteId) {
        $results = CommonQueryManager::getInstance()->getEmployeeInstitute($instituteId);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['instituteId']==$instituteId) {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }


  //-------------------------------------------------------
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

 public function getEmployeeDefaultInstitute($instituteId) {
        $results = CommonQueryManager::getInstance()->getEmployeeInstitute($instituteId);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
				if($instituteId == $results[$i]['instituteId']) {
					$returnValues .='<tr><td scope="col" align="left"><input type="checkbox" name="teachingininstitutes[]" id="teachingininstitutes'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'" checked="true"/>'.$results[$i]['instituteCode'].'</td><td scope="col" align="center"><input type="radio" name="defaultInstitute" id="defaultInstitute'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'" checked="true"/></td></tr>';
				}
				else {
					$returnValues .='<tr><td scope="col" align="left"><input type="checkbox" name="teachingininstitutes[]" id="teachingininstitutes'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'"/>'.$results[$i]['instituteCode'].'</td><td scope="col" align="center"><input type="radio" name="defaultInstitute" id="defaultInstitute'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'"/></td></tr>';
				}
            }
        }
        return $returnValues;
   }


//--------------------------------------------------------
// Author :Dipanjan Bhattacharjee
// Created on : (13.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 public function getEmployeeInstituteForCommonResourcesData($conditions='',$selected='') {
        $results = CommonQueryManager::getInstance()->getEmployeeInstituteForCommonResources($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['instituteId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'">'.strip_slashes($results[$i]['instituteCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Cities
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getCityData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getCity('cityName',$condition='');
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++){

					if (in_array($results[$i]['cityId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['cityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['cityName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['cityId'].'">'.strip_slashes($results[$i]['cityName']).'</option>';
					}
				}
			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['cityId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['cityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['cityName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['cityId'].'">'.strip_slashes($results[$i]['cityName']).'</option>';
					}
				}
            }
        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF DESIGNATIONS
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (13.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getDesignationData($selected='') {
        $results = CommonQueryManager::getInstance()->getDesignation('designationName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['designationId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['designationId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['designationName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['designationId'].'">'.strip_slashes($results[$i]['designationName']).'</option>';
                }
            }

        }
        return $returnValues;
   }



  //-------------------------------------------------------
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

 public function getEditEmployeeDefaultInstitute($instituteId) {
        $results = CommonQueryManager::getInstance()->getEmployeeInstitute($instituteId);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
				if($instituteId == $results[$i]['instituteId']) {
					$returnValues .='<tr><td scope="col" align="left"><input type="checkbox" name="teachingininstitutes1[]" id="teachingininstitutes1_'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'" checked="true"/>'.$results[$i]['instituteCode'].'</td><td scope="col" align="center"><input type="radio" name="defaultInstitute" id="defaultInstitute'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'" checked="true"/></td></tr>';
				}
				else {
					$returnValues .='<tr><td scope="col" align="left"><input type="checkbox" name="teachingininstitutes1[]" id="teachingininstitutes1_'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'"/>'.$results[$i]['instituteCode'].'</td><td scope="col" align="center"><input type="radio" name="defaultInstitute" id="defaultInstitute'.$results[$i]['instituteId'].'" value="'.$results[$i]['instituteId'].'"/></td></tr>';
				}
            }
        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF UNIVERSITIES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


  //Modified By

// Author :Arvind Singh Rawat
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getUniversityData($selected='') {
        $results = CommonQueryManager::getInstance()->getUniversity('universityName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['universityId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['universityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['universityName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['universityId'].'">'.strip_slashes($results[$i]['universityName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
// this function is used to get a list of bank branch abbr
// Author :Rajeev Aggarwal
// Created on : (22.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getBankBranchData($selected='') {
        $results = CommonQueryManager::getInstance()->getBankBranch('bankBranchId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['bankBranchId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['bankBranchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['branchAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['bankBranchId'].'">'.strip_slashes($results[$i]['branchAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

// this function is used to get a list of teaching employee
// Author :Rajeev Aggarwal
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getTeacher($selected='',$mode='') {
        $results = CommonQueryManager::getInstance()->getTeacherData($mode);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   // this function is used to get a list of university Abbreviation
// Author :Rajeev Aggarwal
// Created on : (01.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getUniversityAbbr($selected='') {
        $results = CommonQueryManager::getInstance()->getUniversity('universityAbbr');

        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

					$count = count($results);
					for($i=0;$i<$count;$i++) {
						if (in_array($results[$i]['universityId'], $selected))  {
							$returnValues .='<option value="'.$results[$i]['universityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['universityAbbr']).'</option>';
						}
						else {
							$returnValues .='<option value="'.$results[$i]['universityId'].'">'.strip_slashes($results[$i]['universityAbbr']).'</option>';
						}
					}
				}
				else{

					$count = count($results);
					for($i=0;$i<$count;$i++) {
						if($results[$i]['universityId']==$selected) {
							$returnValues .='<option value="'.$results[$i]['universityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['universityAbbr']).'</option>';
						}
						else {
							$returnValues .='<option value="'.$results[$i]['universityId'].'">'.strip_slashes($results[$i]['universityAbbr']).'</option>';
						}
					}
				}

        }
        return $returnValues;
   }
   // this function is used to get a list of student category
// Author :Rajeev Aggarwal
// Created on : (01.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getCategoryClassData($selected='') {
        $results = CommonQueryManager::getInstance()->getQuota('quotaId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {







                if($results[$i]['quotaId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['quotaId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['quotaName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['quotaId'].'">'.strip_slashes($results[$i]['quotaName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   public function getSubjectTypeData($selected='') {

        $results = CommonQueryManager::getInstance()->getSubjectType('subjectTypeId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'" >'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   public function getSubjectTypeData2($selected='') {

        $results = CommonQueryManager::getInstance()->getSubjectType2('subjectTypeName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'" >'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   /*
    public function getInstituteData($selected='') {
        $results = CommonQueryManager::getInstance()->getInstitute('instituteId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['instituteId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['instituteName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['instituteId'].'">'.strip_slashes($results[$i]['instituteName']).'</option>';
                }
            }

        }
        return $returnValues;
   } */

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF SUBJECT TYPES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


//Modified By

// Author :Arvind Singh Rawat
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

/*   public function getSubjectTypeData($selected='') {
        $results = CommonQueryManager::getInstance()->getSubjectTypes('subjectTypeName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'">'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
          */


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF EMPLOYEES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getEmployeeData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A EMPLOYEE FULL NAME CONCTED WITH EMPLOYEE CODE
//
//orderBy: on which column to sort
// Created on : 4/19/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getEmployeeFullName($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getEmployeeFullName('employeeName',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeFullName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeFullName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Degree
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getDegreeData($selected='', $fieldName = 'degreeCode') {
        $results = CommonQueryManager::getInstance()->getDegree('degreeCode');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['degreeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i][$fieldName]).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i][$fieldName]).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Degree
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getDegreeAbbr($selected='') {

        $results = CommonQueryManager::getInstance()->getDegree('degreeAbbr');

        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
              //  echo "<option>Select</option>";
				for($i=0;$i<$count;$i++) {
					if (in_array($results[$i]['degreeId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
				}

			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['degreeId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
				}
			}

        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Degree
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getInstituteDegreeAbbr($selected='') {

        $results = CommonQueryManager::getInstance()->getInstituteDegree('degreeAbbr');

        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if (in_array($results[$i]['degreeId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
				}

			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['degreeId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
					}
				}
			}

        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batch
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBatchData($selected='') {
        $results = CommonQueryManager::getInstance()->getBatch('batchName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['batchId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['batchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['batchName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['batchId'].'">'.strip_slashes($results[$i]['batchName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Period
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getStudyPeriodData($selected='') {
        $results = CommonQueryManager::getInstance()->getStudyPeriod('periodName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if (in_array($results[$i]['studyPeriodId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['studyPeriodId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['periodName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['studyPeriodId'].'">'.strip_slashes($results[$i]['periodName']).'</option>';
					}
				}

			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['studyPeriodId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['studyPeriodId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['periodName']).'</option>';
					}
					else {
					$returnValues .='<option value="'.$results[$i]['studyPeriodId'].'">'.strip_slashes($results[$i]['periodName']).'</option>';
					}
				}
            }
        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Period
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getPeriodicityData($selected='') {
        $results = CommonQueryManager::getInstance()->getPeriodicity('periodicityName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['periodicityId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['periodicityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['periodicityCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['periodicityId'].'">'.strip_slashes($results[$i]['periodicityCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF subject
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getSubjectData($selected='') {
        $results = CommonQueryManager::getInstance()->getSubject('subjectCode');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getClassData($selected='') {
        $results = CommonQueryManager::getInstance()->geClass('cls.degreeId,cls.branchId,cls.studyPeriodId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class which are active and future
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getAdmitClassData($selected='') {
        $results = CommonQueryManager::getInstance()->geAdmitClass('cls.className');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getEvaluationCritieriaData($selected='') {
        $results = CommonQueryManager::getInstance()->geEvaluationCritieria('evaluationCriteriaName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['evaluationCriteriaId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['evaluationCriteriaId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['evaluationCriteriaName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['evaluationCriteriaId'].'">'.strip_slashes($results[$i]['evaluationCriteriaName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

     //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BRANCH
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBranchData($selected='') {
        $results = CommonQueryManager::getInstance()->getBranch('branchCode');
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if (in_array($results[$i]['branchId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['branchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['branchCode']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['branchId'].'">'.strip_slashes($results[$i]['branchCode']).'</option>';
					}
				}

			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['branchId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['branchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['branchCode']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['branchId'].'">'.strip_slashes($results[$i]['branchCode']).'</option>';
					}
				}
			}

        }
        return $returnValues;
   }


//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Hostel Name
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

  public function getHostelName($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getHostelName('hostelName',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++){

					if (in_array($results[$i]['hostelId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['hostelId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['hostelName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['hostelId'].'">'.strip_slashes($results[$i]['hostelName']).'</option>';
					}
				}
			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['hostelId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['hostelId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['hostelName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['hostelId'].'">'.strip_slashes($results[$i]['hostelName']).'</option>';
					}
				}
            }
        }
        return $returnValues;
   }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Hostel ROOM
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (11.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getHostelRoomData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getHostelRoom('hostelRoomId',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['hostelRoomId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['hostelRoomId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['roomName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['hostelRoomId'].'">'.strip_slashes($results[$i]['roomName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Institute ROOM
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getInstituteRoomData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getInstituteRoom('roomId',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['roomId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['roomId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['roomAbbreviation']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['roomId'].'">'.strip_slashes($results[$i]['roomAbbreviation']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Institute ROOM
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getInstituteRoomData2($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getInstituteRoom2('roomId',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['roomId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['roomId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['roomAbbreviation']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['roomId'].'">'.strip_slashes($results[$i]['roomAbbreviation']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Bus Route Name
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBusRouteName($selected='') {
        $results = CommonQueryManager::getInstance()->getBusRoute('routeCode');
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++){

					if (in_array($results[$i]['busRouteId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['busRouteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['routeCode']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['busRouteId'].'">'.strip_slashes($results[$i]['routeCode']).'</option>';
					}
				}
			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['busRouteId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['busRouteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['routeCode']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['busRouteId'].'">'.strip_slashes($results[$i]['routeCode']).'</option>';
					}
				}
			}
        }
        return $returnValues;
   }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Bus Route Name
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (26.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBusStopName($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getBusStop('stopName',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++){

					if (in_array($results[$i]['busStopId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['busStopId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['stopName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['busStopId'].'">'.strip_slashes($results[$i]['stopName']).'</option>';
					}
				}
			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['busStopId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['busStopId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['stopName']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['busStopId'].'">'.strip_slashes($results[$i]['stopName']).'</option>';
					}
				}
            }
        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ROLES  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (1.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getRoleData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getRole('roleName',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['roleId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['roleId'].'" SELECTED>'.UtilityManager::getTitleCase(strip_slashes($results[$i]['roleName'])).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['roleId'].'" >'.UtilityManager::getTitleCase(strip_slashes($results[$i]['roleName'])).'</option>';
                }
            }

        }
        return $returnValues;
   }

/*public function getRoleData1($selected='',$conditions='') {
       $results = CommonQueryManager::getInstance()->getRole1('roleName',$conditions);
        $returnValues = '';
      foreach($results as $key=>$value)
      {
         if($key==$selected)
           $returnValues .='<option value="'. $key.'" SELECTED>'.$value.'</option>';
         else
           $returnValues .='<option value="'. $key.'">'. $value.'</option>';
      }
      return $returnValues;
   }*/
  //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Checkboxes  of roles
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh rawat
// Created on : (07.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getRoleCheckboxData($condition) {
        $results = CommonQueryManager::getInstance()->getRole('roleName',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            $noOfRows=$count/4;

            $j=0;
            for($i=0;$i<$count;$i++) {
                    // $returnValues.='<tr>';
                    $noOfColumns=0;

                   // while($noOfColumns < 3)
                    //{
                    // if($count==$j){ break;}
                    // if($results[$j]['roleId']!='1')
                     //{
                         $returnValues .='<tr><td scope="col" align="left"><input type="checkbox" name="roleId[]" id="roleId'.$results[$i]['roleId'].'" value="'.$results[$i]['roleId'].'" />'.$results[$i]['roleName'].'</td><td scope="col" align="left"><input type="radio" name="defaultRole" id="defaultRole'.$results[$i]['roleId'].'"  value="'.$results[$i]['roleId'].'" /></td></tr>';
                     //$noOfColumns++;

                   //  }
                      // $j++;
                    //}
                   // $returnValues.='</tr>';

                }
				//$returnValues .='<input type="text" name="totalRole" id="totalRole" value="'.$count.'" />';



        }
        return  $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//

// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeCycleClassData($selected='',$condition='',$orderBy=' className') {
        $results = CommonQueryManager::getInstance()->getFeeCycleClasses($condition,$orderBy);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeCycleListData($selected='',$condition='',$orderBy=' cycleName') {
        $results = CommonQueryManager::getInstance()->getFeeCycleClasses($condition,$orderBy,$opition='1');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeCycleId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeCycleId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['cycleName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeCycleId'].'">'.strip_slashes($results[$i]['cycleName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeCycleData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getFeeCycle('cycleName',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeCycleId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeCycleId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['cycleName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeCycleId'].'">'.strip_slashes($results[$i]['cycleName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Nishu Bindal
// Created on : (7.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeCycleDataNew($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getFeeCycleNew('cycleName',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $cycleName = strip_slashes($results[$i]['cycleName'])." (Inactive)";
                if($results[$i]['status']=='1') {
                  $cycleName = strip_slashes($results[$i]['cycleName'])." (Active)";  
                }
                if($results[$i]['feeCycleId']==$selected) {
                  $returnValues .='<option value="'.$results[$i]['feeCycleId'].'" SELECTED="SELECTED">'.$cycleName.'</option>';
                }
                else {
                  $returnValues .='<option value="'.$results[$i]['feeCycleId'].'">'.$cycleName.'</option>';
                }
            }

        }
        return $returnValues;
   }



//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeHeadData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getFeeHead('headName',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeHeadId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['headName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'">'.strip_slashes($results[$i]['headName']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getConcessionCategory($selected='',$condition='',$orderBy=' categoryName') {
        $results = CommonQueryManager::getInstance()->getFeeConcession($orderBy,$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['categoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['categoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['categoryId'].'">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------

 public function getFeeHeadValues($selected='',$condition='',$orderBy=' headName') {
       $results = CommonQueryManager::getInstance()->getFeeHeadList($condition,'',$orderBy);
       $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeHeadId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['headName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'">'.strip_slashes($results[$i]['headName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF FEE HEAD NAME  IN THE "SELECT" ELEMENT
//
// selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (16.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeHeadNameData($selected='') {
        $results = CommonQueryManager::getInstance()->getAllocatedFeeHead('headName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeHeadId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'" SELECTED="SELECTED">'.ucwords(strip_slashes($results[$i]['headName'])).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'">'.ucwords(strip_slashes($results[$i]['headName'])).'</option>';
                }
            }

        }
        return $returnValues;
   }

   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CONCATENATED BATCH,UNIVERSITY AND BRANCH FROM CLASS TABLE
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getConcatenateClassData($selected='') {
        $results = CommonQueryManager::getInstance()->getConcatenateClass('classId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['universityId'].'-'.$results[$i]['degreeId'].'-'.$results[$i]['branchId']==$selected) {
                     $returnValues .='<option value="'.$results[$i]['universityId'].'-'.$results[$i]['degreeId'].'-'.$results[$i]['branchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['universityId'].'-'.$results[$i]['degreeId'].'-'.$results[$i]['branchId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }

    //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ENTRANCE EXAM
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getEntranceExamData($selected='') {

		global $results;
		asort($results);
        $returnValues = '';
         if(isset($results) && is_array($results)) {
            $count = count($results);
             foreach($results as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Title
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getTitleData($selected='') {
        global $titleResults;

		echo($selected);

        $returnValues = '';
         if(isset($titleResults) && is_array($titleResults)) {
            $count = count($titleResults);
             foreach($titleResults as $key=>$value) {
                if($key==$selected) {
					echo($selected);
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }
//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Blood group Array
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (25.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getBloodGroupData($selected='') {

        global $bloodResults;

        $returnValues = '';
         if(isset($bloodResults) && is_array($bloodResults)) {
            $count = count($bloodResults);
             foreach($bloodResults as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }


   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF GROUP TYPE NAME IN THE COMBOBOX
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getGroupTypeData($selected='') {
        $results = CommonQueryManager::getInstance()->getGroupTypeName('groupTypeId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['groupTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupTypeId'].'">'.strip_slashes($results[$i]['groupTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
/*public function getParentGroup($selected='') {
        $results = CommonQueryManager::getInstance()->getGroupParent('groupTypeId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['GroupTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupTypeId'].'">'.strip_slashes($results[$i]['groupTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   */
   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Period
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getStudyPeriod($selected='') {
        $results = CommonQueryManager::getInstance()->getStudyPeriodName('studyPeriodId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['studyPeriodId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['studyPeriodId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['periodName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['studyPeriodId'].'">'.strip_slashes($results[$i]['periodName']).'</option>';
                }
            }

        }
        return $returnValues;

   }

  //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF years
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBirthYear($selected='') {
       $yearArr = array();
		for($i=date('Y')-60;$i<=date('Y')-15;$i++) {
		   if($i==$selected)
	           $returnValues .='<option value="'.$i.'" SELECTED>'. $i.'</option>';
		   else
   	           $returnValues .='<option value="'.$i.'">'. $i.'</option>';

        }
        return $returnValues;
   }
 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF years
//
//orderBy: on which column to sort
//
// Author :Ajinder Singh
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getAdmissionYear($selected='') {
       $yearArr = array();
		for($i=date('Y')-8;$i<=date('Y');$i++) {
		   if($i==$selected)
	           $returnValues .='<option value="'.$i.'" SELECTED>'. $i.'</option>';
		   else
   	           $returnValues .='<option value="'.$i.'">'. $i.'</option>';
        }
        return $returnValues;
   }

 //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF JOINING YEARS
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (12.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getEmployeeJLYear($selected='') {
       $yearArr = array();
		for($i=date('Y')-50;$i<=date('Y')+2;$i++) {
		   if($i==$selected)
	           $returnValues .='<option value="'.$i.'" SELECTED>'. $i.'</option>';
		   else
   	           $returnValues .='<option value="'.$i.'">'. $i.'</option>';

        }
        return $returnValues;
   }

   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee years
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (16.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getEmployeeBirthYear($selected='') {
       $yearArr = array();
        for($i=date('Y')-70;$i<=date('Y')+2;$i++) {
           if($i==$selected)
               $returnValues .='<option value="'.$i.'" SELECTED>'. $i.'</option>';
           else
                  $returnValues .='<option value="'.$i.'">'. $i.'</option>';

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF month
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBirthMonth($selected='') {
      global $monArr;
		foreach($monArr as $key=>$value)
	   {
		   if($key==$selected)
			   $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
		   else
			   $returnValues .='<option value="'. $key.'">'. $value.'</option>';
	   }
        return $returnValues;
   }

   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF date
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (02.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBirthDate($selected='') {

		for($i=1;$i<=31;$i++) {
           if($i==intval($selected))
	           $returnValues .='<option value="'.($i<10?"0".$i:$i).'" SELECTED>'. $i.'</option>';
		   else
   	           $returnValues .='<option value="'.($i<10?"0".$i:$i).'">'. $i.'</option>';

        }
        return $returnValues;
   }
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class status
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (11.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getClassStatus($selected='') {
      global $classStatusArr;
		foreach($classStatusArr as $key=>$value)
	   {
		   if($key==$selected)
			   $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
		   else
			   $returnValues .='<option value="'. $key.'">'. $value.'</option>';
	   }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class result
//
//orderBy: on which column to sort
//
// Author :Parveen Sharma
// Created on : (11.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getClassResult($selected='') {

      global $classResults;

      foreach($classResults as $key=>$value)  {
        if($key==$selected)
           $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
        else
           $returnValues .='<option value="'. $key.'">'. $value.'</option>';
      }

      return $returnValues;
   }



//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Fine type
//
//orderBy: on which column to sort
//
// Author :Arvind singh Rawat
// Created on : (28.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getFineType($selected='') {
      global $fineTypeArr;
		foreach($fineTypeArr as $key=>$value)
	   {
		   if($key==$selected)
			   $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
		   else
			   $returnValues .='<option value="'. $key.'">'. $value.'</option>';
	   }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF fee receipt payment status
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getFeeReceiptPaymentStatus($selected='') {
      global $receiptPaymentArr;
      foreach($receiptPaymentArr as $key=>$value) {
        if($key==$selected)
		  $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
		else
		  $returnValues .='<option value="'. $key.'">'. $value.'</option>';
	  }
      return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF fee receipt status
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getFeeReceiptStatus($selected='',$notInclude='') {
      global $receiptArr;
	  foreach($receiptArr as $key=>$value) {
          if($key!=$notInclude) {
		    if($key==$selected)
			 $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
		    else
			 $returnValues .='<option value="'. $key.'">'. $value.'</option>';
          }
	   }
       return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF fee receipt status
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.

//

//--------------------------------------------------------

   public function getFeePaymentMode($selected='',$notInclude='') {
      global $modeArr;
	  foreach($modeArr as $key=>$value)
	  {
         if($notInclude!=$key) {
		   if($key==$selected)
		     $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
		   else
		     $returnValues .='<option value="'. $key.'">'. $value.'</option>';
         }
	  }
      return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF bank
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getBankData($selected='') {
        $results = CommonQueryManager::getInstance()->getBank('bankId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['bankId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['bankId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['bankAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['bankId'].'">'.strip_slashes($results[$i]['bankAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF payment mode
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getPaymentMode($selected='') {
      global $modeArr;
		foreach($modeArr as $key=>$value)
	   {
		   if($key == $selected)
				$returnValues .='<input type="radio" name="paymentMode" id="paymentMode" value="'. $key.'" checked onClick="formdisable(this.value)" tabindex="12">'.$value."&nbsp;";
		   else
	   		    $returnValues .='<input type="radio" name="paymentMode" id="paymentMode" value="'. $key.'" onClick="formdisable(this.value)" >'.$value."&nbsp;";

	   }
        return $returnValues;
   }

 //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Group Name  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getGroupData($orderBy='',$selected='',$condition='') {
	   	if($orderBy == '' ){
			$orderBy='groupId';
		}
        $results = CommonQueryManager::getInstance()->getGroupParent($orderBy,$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['groupName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'">'.strip_slashes($results[$i]['groupName']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Leave Session  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Parveen Sharma
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getLeaveSessionData($selected='',$condition='',$orderBy='') {
        $results = CommonQueryManager::getInstance()->getLeaveSessionList($condition,$orderBy);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['leaveSessionId']==$selected || ($results[$i]['active']=='1' && $selected=='')) {
                   $returnValues .='<option value="'.$results[$i]['leaveSessionId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['sessionName']).'</option>';
                }
                else {
                   $returnValues .='<option value="'.$results[$i]['leaveSessionId'].'">'.strip_slashes($results[$i]['sessionName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

    public function getSessionData($selected='',$field='',$mode='1') {
        $results = CommonQueryManager::getInstance()->getSessionDetail($mode);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['sessionId']==$selected) {
                   if($field==''){
                    $returnValues .='<option value="'.$results[$i]['sessionId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['sessionName']).'</option>';
                   }
                  else{
                      $returnValues .='<option value="'.$results[$i]['sessionId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i][$field]).'</option>';
                  }
                }
                else {
                   if($field==''){
                    $returnValues .='<option value="'.$results[$i]['sessionId'].'">'.strip_slashes($results[$i]['sessionName']).'</option>';
                   }
                  else{
                    $returnValues .='<option value="'.$results[$i]['sessionId'].'">'.strip_slashes($results[$i][$field]).'</option>';
                  }
                }
            }

        }
        return $returnValues;
   }


   public function getActiveSession() {
	   $results = CommonQueryManager::getInstance()->getActiveSession();
	   $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
				$returnValues .='<option value="'.$results[$i]['sessionId'].'">'.strip_slashes($results[$i]['sessionName']).'</option>';
			}
		}
		return $returnValues;
   }


//--------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A calender for using in date selection
//$txtId:name/id of the textbox,$value:initial value of the textbox
// Author :Dipanjan Bhattacharjee
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------
   public function datePicker($fieldName,$value='',$id='',$showFunctionName=''){
       echo "<input type=\"text\" id=\"$fieldName\" name=\"$fieldName\" class=\"inputBox\" readonly=\"true\" value=\"$value\" size=\"8\" /><input type=\"image\" id=\"calImg$id\" name=\"calImg$id\" title=\"Select Date\" src=\"".IMG_HTTP_PATH."/calendar.gif\"  $showFunctionName onClick=\"return showCalendar('$fieldName', '%Y-%m-%d', '24', true);  \">";
   }

   public function datePicker2($fieldName,$value=''){
      return "<input type=\"text\" id=\"$fieldName\" name=\"$fieldName\" class=\"inputBox\" readonly=\"true\" value=\"$value\" size=\"8\" /><input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\"".IMG_HTTP_PATH."/calendar.gif\"  onClick=\"return showCalendar('$fieldName', '%Y-%m-%d', '24', true);\">";
   }

    public function datePicker1($fieldName,$value=''){
        echo "<input type=\"text\" id=\"$fieldName\" name=\"$fieldName\" class=\"inputBox\" readonly=\"true\" value=\"$value\" size=\"8\" /><input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\"".IMG_HTTP_PATH."/calendar.gif\"  onClick=\"return showCalendar('$fieldName', '%Y-%m-%d', '24', true);\"  tabindex=\"16\" >";
   }

    public function datePicker3($fieldName,$value=''){
        echo "<input type=\"text\" id=\"$fieldName\" name=\"$fieldName\" class=\"inputBox\" readonly=\"true\" value=\"$value\" size=\"8\" /><input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\"".IMG_HTTP_PATH."/calendar.gif\" onBlur=\"hideAddRow();cleanUpTable();\"  onClick=\"return showCalendar('$fieldName', '%Y-%m-%d', '24', true);\"  tabindex=\"16\" >";
   }

   public static function dateDropdown($fld_name,$date) {
           if(!empty($date) && $date!='0000-00-00') {
             return "<script>DateInput('$fld_name', false, 'YYYY-MM-DD','$date')</script>";
             }
           else {
             $date = date('Y-m-d');
             return "<script>DateInput('$fld_name','$date')</script>";
             }
        }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF building  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getBuildingData($selected='') {
        $results = CommonQueryManager::getInstance()->getBuilding('buildingName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['buildingId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['buildingId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['buildingName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['buildingId'].'">'.strip_slashes($results[$i]['buildingName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF block Name IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getBlockData($selected='') {
        $results = CommonQueryManager::getInstance()->getBlock('blockName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['blockId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['blockId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['blockName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['blockId'].'">'.strip_slashes($results[$i]['blockName']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class corresponding to a teacher
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getTeacherClassData($selected='') {
       //$results = TeacherManager::getInstance()->getTeacherClass();
       $date=date('Y-m-d');
       global $sessionHandler;
       $timeTableLabelTypeConditions='';
       if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
          if(MODULE=='DailyAttendance'){
            $timeTableLabelTypeConditions=' AND t.fromDate ="'.$date.'"';
          }
          else if(MODULE=='ClassWiseAttendanceList'){
            $timeTableLabelTypeConditions=' AND t.fromDate BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
          }
          else{
            $timeTableLabelTypeConditions=' AND t.fromDate <="'.$date.'"';
          }
       }

       $results = TeacherManager::getInstance()->getTeacherAdjustedClass($date,$date,'','',$timeTableLabelTypeConditions);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//------------------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF class corresponding to all teachers
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (03.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getAllTeacherClassData($selected='') {
       $results = AdminTasksManager::getInstance()->getAllTeacherClass();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF subjects corresponding to a teacher
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getTeacherSubjectData($selected='',$conditions='') {

       if(CURRENT_PROCESS_FOR=="cc"){
        $results = TeacherManager::getInstance()->getTeacherSubject($conditions);
       }
       elseif(CURRENT_PROCESS_FOR=="sc"){
           $results = ScTeacherManager::getInstance()->getTeacherSubject($conditions);
       }

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
            }

        }
        return $returnValues;

   }
//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF subjects corresponding to a teacher
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getGroup($selected='',$conditions='') {
        $results = TeacherManager::getInstance()->getGroup($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['groupShort']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'">'.strip_slashes($results[$i]['groupShort']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//------------------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF subjects corresponding to all teacher
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (03.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getAllTeacherSubjectData($selected='') {


        $results = AdminTasksManager::getInstance()->getAllTeacherSubject();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }




//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Section(SC) corresponding to a teacher
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (10.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getTeacherSectionData($selected='') {

        $results = ScTeacherManager::getInstance()->getTeacherSection();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['sectionId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['sectionId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['sectionName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['sectionId'].'">'.strip_slashes($results[$i]['sectionName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Section(SC)
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (17.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getSectionData($selected='') {

        $results = CommonQueryManager::getInstance()->getSectionList();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['sectionId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['sectionId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['sectionName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['sectionId'].'">'.strip_slashes($results[$i]['sectionName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   //------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeeFundAllocation lists
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (18.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getFeeFundAllocationData($selected='') {
         $results = CommonQueryManager::getInstance()->getFeeFundAllocation('allocationEntity');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeFundAllocationId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeFundAllocationId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['allocationEntity']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeFundAllocationId'].'">'.strip_slashes($results[$i]['allocationEntity']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF groups corresponding to a teacher
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getTeacherGroupData($selected='') {
       $results = TeacherManager::getInstance()->getTeacherGroup();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['groupName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'">'.strip_slashes($results[$i]['groupName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF groups corresponding to a teacher
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getTeacherPeriodData($conditions='') {
       $results = TeacherManager::getInstance()->getTeacherPeriod($conditions);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $returnValues .='<option value="'.$results[$i]['periodId'].'">'.strip_slashes($results[$i]['periodNumber']).'</option>';
            }

        }
        return $returnValues;
   }


//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LIST OF Session Years starting from 6 years back to 6 years ahead
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------

   public function getSessionsList($selected='') {

        $returnValues = '';

		for($i=date('2002'); $i<=date('Y')+6; $i++) {
			if($i == $selected) {
				$returnValues .='<option value="'.$i.'" SELECTED="SELECTED">'.$i.'</option>';
			}
			else {
				$returnValues .='<option value="'.$i.'">'.$i.'</option>';
			}
		}
        return $returnValues;
   }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CLASSES WITH STUDY PERIOD
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
   public function getClassWithStudyPeriod($selected='') {
		$results = CommonQueryManager::getInstance()->getClassWithStudyPeriod();
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$classNameArray = explode('-', $results[$i]['className']);
				//array_shift($classNameArray);
				//array_shift($classNameArray);
				$className = implode("-",$classNameArray);
				if($results[$i]['classId']==$selected) {
					$returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($className).'</option>';
				}
			}
		}
		return $returnValues;
	}
//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CLASSES WITH STUDY PERIOD
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
   public function getPromotedClassWithStudyPeriod($selected='') {
		$results = CommonQueryManager::getInstance()->getPromotedClassWithStudyPeriod();
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$classNameArray = explode('-', $results[$i]['className']);
				array_shift($classNameArray);
				array_shift($classNameArray);
				$className = implode("-",$classNameArray);
				if($results[$i]['classId']==$selected) {
					$returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($className).'</option>';
				}
			}
		}
		return $returnValues;
	}

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CURRENT SESSION CLASSES
//
//selected: which element in the select element to be selected
//
// Author : Ajinder Singh
// Created on : (24.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getCurrentSessionClasses($selected='') {
		$results = CommonQueryManager::getInstance()->getCurrentSessionClasses();
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$classId = $results[$i]['classId'];
				$className = $results[$i]['className'];
				if($results[$i]['classId']==$selected) {
					$returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($className).'</option>';
				}
			}
		}
		return $returnValues;
	}//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF CURRENT SESSION CLASSES
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (09.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getSessionClasses($selected='') {
		$results = CommonQueryManager::getInstance()->getSessionClasses();
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$classId = $results[$i]['classId'];
				$className = $results[$i]['className'];
				if($results[$i]['classId']==$selected) {
					$returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($className).'</option>';
				}
			}
		}
		return $returnValues;
	}

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF 1 TO 10, FOR ROLL NO. LENGTH
//
//selected: which element in the select element to be selected
//
// Author : Ajinder Singh
// Created on : (24.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getRollNoLength($selected='') {
        $returnValues = '';

		for($i=1; $i<=30; $i++) {
			if($i == $selected) {
				$returnValues .='<option value="'.$i.'" SELECTED="SELECTED">'.$i.'</option>';
			}
			else {
				$returnValues .='<option value="'.$i.'">'.$i.'</option>';
			}
		}
        return $returnValues;

	}


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF conducting authority
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (23.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
   public function getConductingAuthorityData($selected='') {
       /*********************************************************************
       * WHENEVER SOMEONE CHANGES(ADD/EDIT/DELETE) CONDUNCTING AUTHORITY    *
       * DATA, PLEASE UPDATE getTestConductingAuthorityData() FUNCTION      *
       * ALSO AS WE HAVE HARD-CODED CONDUNCTING AUTHORITY                   *
       **********************************************************************/

        $returnValues = '';
        $returnValues .='<option value="1" >INTERNAL</option>';
        $returnValues .='<option value="2" >EXTERNAL</option>';
        $returnValues .='<option value="3" >ATTENDANCE</option>';
        return $returnValues;
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BOTTOM LEVEL GROUPS
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (25.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getLastLevelGroups($selected='',$condition) {
		$results = CommonQueryManager::getInstance()->getLastLevelGroups(' groupName',$condition);

		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$groupId = $results[$i]['groupId'];
				$groupName = $results[$i]['groupName'];
				if($results[$i]['groupId']==$selected) {
					$returnValues .='<option value="'.$results[$i]['groupId'].'" SELECTED="SELECTED">'.strip_slashes($groupName).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['groupId'].'">'.strip_slashes($groupName).'</option>';
				}
			}
		}

		return $returnValues;

	}

//--------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL LEVEL GROUPS
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (09.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------
public function getCurrentGroups($selected='',$condition) {
        
        $results = CommonQueryManager::getInstance()->getAllCurrentGroups(' cls.isActive',$condition);
        $returnValues='';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $groupId = $results[$i]['groupId'];
                $groupName = $results[$i]['groupName'];
                if($results[$i]['groupId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'" SELECTED="SELECTED">'.strip_slashes($groupName).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'">'.strip_slashes($groupName).'</option>';
                }
            }
        }
        return $returnValues;       
}


//--------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL LEVEL Categories(Quotas)
//selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (15.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------
public function getCurrentCategories($selected='',$condition='',$showParentCat='') {
        $results = CommonQueryManager::getInstance()->getAllCurrentCategories(' quotaName',$condition,$showParentCat);
        $returnValues = '';

        if(isset($results) && is_array($results)) {
            //if(is_array($selected)){
            if($selected!=''){
                $count = count($results);
                foreach($results as $key=>$val) {
                    //if (in_array($key, $selected))  {
                    if($key==$selected) {
                        $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$val.'</option>';
                    }
                    else{
                        $returnValues .='<option value="'.$key.'">'.$val.'</option>';
                    }
                }
            }
            else{
                if(isset($results) && is_array($results)) {
                    $count = count($results);
                    foreach($results as $key=>$val) {
                        $returnValues .='<option value="'.$key.'">'.$val.'</option>';
                    }
                }
            }
        return $returnValues;
    }
}

//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF attendance codes
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (4.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function getAttendanceCodeData($conditions='', $selectedCode = '') {
       $results = CommonQueryManager::getInstance()->getAttendanceCode($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
				$selected = '';
				if ($selectedCode == $results[$i]['attendanceCode']) {
					$selected = " selected = 'selected' ";
				}
                $returnValues .='<option '.$selected.' value="'.$results[$i]['attendanceCodeId'].'">'.strip_slashes($results[$i]['attendanceCode']).'</option>';
            }

        }
        return $returnValues;
   }

//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LIST OF Grades Drop-down box
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (04.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------
   public function makeGradeSelect($gradeSetId, $selected='') {
        $returnValues = '';
		$results = CommonQueryManager::getInstance()->getGradeLabels($gradeSetId);

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['gradeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['gradeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['gradeLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['gradeId'].'">'.strip_slashes($results[$i]['gradeLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }



//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LIST OF Marks Drop-down box
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (04.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------

   public function makeMarksSelect($selected='') {

        $returnValues = '';
		$marksSelectArray = array('A'=>'A', 'UMC'=>'UMC','I'=>'I','MU'=>'MU','Marks'=>'Marks');


		foreach($marksSelectArray as $key => $value) {
			if($i == $selected) {
				$returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
			}
			else {
				$returnValues .='<option value="'.$key.'">'.$value.'</option>';
			}
		}
        return $returnValues;
   }
//------------------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET LIST OF Marks Drop-down box
//
//selected: which element in the select element to be selected
//
// Author :Ajinder Singh
// Created on : (04.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------

   public function makeDutyLeaveSelect($selected='') {

      $returnValues = '';
		global $globalDutyLeaveStatusArray;

		foreach($globalDutyLeaveStatusArray as $key => $value) {
			if($i == $selected) {
				$returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
			}
			else {
				$returnValues .='<option value="'.$key.'">'.$value.'</option>';
			}
		}
        return $returnValues;
   }

//-----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF classes(in a formatted way)
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (11.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------

   public function getFormattedClassData($selected='') {
        $results = CommonQueryManager::getInstance()->getFormattedClass('classId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
            }
        }
        return $returnValues;
   }


//-----------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Sections(in a formatted way)
//
//orderBy: on which column to sort
//
// Author :Arvind Singh Rawat
// Created on : (02.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------

   public function getSectionTypeData($selected='') {
        $results = array('L' => 'Lecture' , 'T' => 'Tutorials' , 'P' => 'Practical' );
        $returnValues = '';
        foreach($results AS $index => $value){
                if($index == $selected) {
                        $returnValues .='<option value="'.$index.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else{
                   $returnValues .='<option value="'.$index.'">'.$value.'</option>';
                  }
            }
        return $returnValues;
   }

   public function getCourseData($selected='') {
        $results = CommonQueryManager::getInstance()->getCourses('','subjectCode');
        $returnValues = '';
        if(isset($results) && is_array($results)) {

			if(is_array($selected)){

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if (in_array($results[$i]['subjectId'], $selected))  {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
					}
				}

			}
			else{

				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['subjectId']==$selected) {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
					}
					else {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
					}
				}
            }
        }
        return $returnValues;
   }


//----------------------------------------------------------------------------
//Purpose: Remove any javascript,PHP code from a string with a given value
//Author:Dipanjan Bhattacharje
//Date:08.09.2008
//$input: Input String
//$rep: Replacer String
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function removePHPJS($input,$rep='',$other=''){

     /* return (str_ireplace(array('<?php','<?','?>','<script','</script>'),$rep,html_entity_decode($input))); */
     $str = (str_ireplace(array('<?php','<?','?>','<script','</script>'),$rep,html_entity_decode($input)));
     $str = strip_tags($str);
     $str = html_entity_decode($str);

	 //it removes all the html tags
	 if($other!='') {
       $str = preg_replace("/[[:punct:]]/", "", $str);
       $str = preg_replace("/[[:space:]]/", " ", $str);
	 }

     return $str;
 }

//----------------------------------------------------------------------------
//Purpose: make student filter with default values
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function makeStudentDefaultSearch($param='') {

	 global $REQUEST_DATA;

     if($param=='') {
       $param="Roll No.&nbsp; ";
     }
     else {
        $param="Reg. No./ Uni. No./ Roll No.";
     }

	 $maleSelected="";
	 $femaleSelected="";
	 if($REQUEST_DATA['gender']=='M')
		$maleSelected="SELECTED";
	 if($REQUEST_DATA['gender']=='F')
		$femaleSelected="SELECTED";
	 $studentDefaultSearch =  "
			<tr>
				<td valign='middle' colspan='1' class='' style='text-align:left' nowrap><b>$param</b></td>
				<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
				<td valign='middle' colspan='1' class='' align='left'>
                <input type='text' class='selectfield' autocomplete='off' name='rollNo' id='rollNo' style='width:197px'  value='".$REQUEST_DATA['rollNo']."'/></td>
				<td valign='middle' colspan='1' class='' style='text-align:left'><b>&nbsp;Student Name&nbsp; </b></td>
				<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
				<td valign='middle' colspan='4' class='' style='text-align:left'><input type='text' class='selectfield' name='studentName' id='studentName' style='width:99%' value='".$REQUEST_DATA['studentName']."'/></td>
			</tr>
			<tr>
				<td valign='middle' colspan='1' style='width:40px;text-align:left' class=''><b>Gender&nbsp;</b></td>
				<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
				<td valign='middle' colspan='1' class='' align='left'>
					<select name='gender' class='htmlElement' style='width:200px'>
						<option value='' selected>Select</option>
						<option value='M' ".$maleSelected.">Male</option>
						<option value='F' ".$femaleSelected.">Female</option>
					</select>
				</td>
				<td valign='middle' colspan='1' class=''  style='width:110px;text-align:left;padding-left:2px'>



					<B>&nbsp;Birth Date&nbsp;From&nbsp;</B>
				</td>
				<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
				<td valign='top' colspan='1' class=''  style='width:260px;text-align:left'>
				   <select size='1' name='birthYearF' id='birthYearF' class='htmlElement' style='width:67px;'>
					<option value=''>Year</option>".$this->getBirthYear($REQUEST_DATA['birthYearF'])."</select>
					<select size='1' name='birthMonthF' id='birthMonthF' class='htmlElement' style='width:67px;'>
					<option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['birthMonthF'])."</select>
					<select size='1' name='birthDateF' id='birthDateF' class='htmlElement' style='width:60px;'>
					<option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['birthDateF'])."</select>
				</td>
				<td valign='middle' colspan='1' class=''  align='left' style='width:40px'>
					<B>To</B>&nbsp;
				</td>
				<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
				<td valign='top'  class='' style='width:260px;text-align:left'>
				   <select size='1' name='birthYearT' id='birthYearT' class='htmlElement' style='width:67px;'>
					<option value=''>Year</option>".$this->getBirthYear($REQUEST_DATA['birthYearT'])."</select>
					<select size='1' name='birthMonthT' id='birthMonthT' class='htmlElement' style='width:67px;'>
					<option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['birthMonthT'])."</select>
					<select size='1' name='birthDateT' id='birthDateT' class='htmlElement' style='width:60px;'>
					<option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['birthDateT'])."</select>
				</td>
			</tr>

			";
			return $studentDefaultSearch;
	}

//----------------------------------------------------------------------------
//Purpose: make student filter for academic search
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
	public function makeStudentAcademicSearch($showSections = false) {

		global $REQUEST_DATA;
		$degreeArr = explode(",", $REQUEST_DATA['degreeId']);
		$branchArr = explode(",", $REQUEST_DATA['branchId']);
		$periodicityArr = explode(",", $REQUEST_DATA['periodicityId']);
		$subjectArr = explode(",", $REQUEST_DATA['courseId']);
		$universityArr = explode(",", $REQUEST_DATA['universityId']);
		$groupArr = explode(",", $REQUEST_DATA['groupId']);

        //detecting IE6 browser
        $isIE6=$this->isIE6Browser();
        if($isIE6==1 or $isIE6==0){
		$studentAcademicSearch = "
					<tr class='showHideRow'>
						<td valign='top' colspan='1' class='' style='text-align:left' nowrap ><B>Academic criteria&nbsp;</B></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td colspan='7' align='left'>
							<a class='allStudentLink' href='javascript:showHide(\"academic\");'><span id='academic'>Expand</span></a>
						</td>
					</tr>
					<tr height='5'></tr>
					<tr id='academic3' style='display:none;'>
						<td valign='middle' colspan='1' class='' style='text-align:left' nowrap><b>Fee Receipt&nbsp; </b></td>
						<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='middle' colspan='1' class='' align='left'><input type='text' class='selectfield' name='feeReceiptNo' id='feeReceiptNo' style='width:196px'  value='".$REQUEST_DATA['feeReceiptNo']."'/></td>
						<td valign='middle' colspan='1' class='' style='text-align:left'>
                        <nobr><b>&nbsp;Institute Reg. No.&nbsp; </b></nobr></td>
						<td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='middle' colspan='1' class='' style='text-align:left'><input type='text' class='selectfield' name='regNo' id='regNo' style='width:196px' value='".$REQUEST_DATA['regNo']."'/></td>
						<td valign='middle' colspan='1' class=''  align='right' nowrap><b>Attendance From &nbsp; </b></td><td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td><td valign='middle' colspan='1' class='' align='left'><input type='text' class='selectfield' name='attendanceFrom' id='attendanceFrom' style='width:50px' value='".$REQUEST_DATA['attendanceFrom']."'/>%<b>&nbsp;&nbsp;To :&nbsp; </b><input type='text' class='selectfield' name='attendanceTo' id='attendanceTo' style='width:50px' value='".$REQUEST_DATA['attendanceTo']."'/>%</td>
					</tr>
					<tr id='academic1' style='display:none;'>
						<td valign='top' colspan='1' class='' style='text-align:left'><b>Degree&nbsp; </b></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='top' colspan='1' class='' style='text-align:left' >
							<select multiple name='degreeId[]' id='degreeId' size='5' class='htmlElement2' style='width:200px'>".$this->getDegreeAbbr($degreeArr)."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"degreeId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"degreeId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='1' class='' style='text-align:left'><b>&nbsp;Branch&nbsp; </b></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='top' colspan='1' class='' style='text-align:left' >
							<select multiple='multiple' name='branchId[]' id='branchId' size='5' class='htmlElement2' style='width:200px;'>".$this->getBranchData($branchArr)."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"branchId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"branchId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='1' class='' style='text-align:left'><b>Periodicity&nbsp; </b></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='top' colspan='1' class='' style='text-align:left' >
							<select multiple name='periodicityId[]' id='periodicityId' size='5' class='htmlElement2' style='width:200px'>".$this->getStudyPeriodData($periodicityArr)."</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"periodicityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"periodicityId[]\",\"None\");'>None</a>
						</td>
					</tr>
					<tr id='academic2' style='display:none;'>
					";
					if ($showSections === true) {
						$studentAcademicSearch .= "
						<td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Course :&nbsp; </b></td>
						<td valign='top' colspan='1' class=''>
							<select multiple name='courseId[]' id='courseId' size='5' class='htmlElement2' style='width:200px'>".$this->getCourseData($subjectArr,'')."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='1' class=''  align='right'><b>Section :&nbsp; </b></td>
						<td valign='top' colspan='1' class=''>
							<select multiple name='sectionId[]' id='sectionId' size='5' class='htmlElement2' style='width:200px'>".$this->getSectionAbbr()."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='1' class=''  align='right'><b>Univ :&nbsp; </b></td>
						<td valign='top' colspan='1' class=''>
							<select multiple name='universityId[]' id='universityId' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr()."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='2' class=''></td>";
					}
					else {
						$studentAcademicSearch .= "
						<td valign='top' colspan='1' class='' style='text-align:left'><b>Subject&nbsp; </b></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='top' colspan='1' class='' style='text-align:left' >
							<select multiple name='courseId[]' id='courseId' size='5' class='htmlElement2' style='width:200px'>".$this->getCourseData($subjectArr,'')."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='1' class='' style='text-align:left'><b>&nbsp;Group&nbsp; </b></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='top' colspan='1' class='' style='text-align:left' >
							<select multiple name='groupId[]' id='groupId' size='5' class='htmlElement2' style='width:200px'>".$this->getCurrentGroups($groupArr,'')."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"groupId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"groupId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='1' class='' style='text-align:left'><b>University&nbsp; </b></td>
						<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
						<td valign='top' colspan='1' class='' style='text-align:left' >
							<select multiple name='universityId[]' id='universityId' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr($universityArr)."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\");'>None</a>
						</td>
						<td valign='top' colspan='3' class=''></td>";
					}
					$studentAcademicSearch .= " </tr>";
        }
        else{

                    /*New Multiple Selected DDs are implemented*/
                    $studentAcademicSearch = "
                    <tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' style='text-align:left' nowrap ><B>Academic criteria&nbsp;</B></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td colspan='7' align='left'>
                            <a class='allStudentLink' href='javascript:showHide(\"academic\",1);'><span id='academic'>Expand</span></a>
                        </td>
                    </tr>
                    <tr height='5'></tr>
                    <tr id='academic3' style='display:none;'>
                        <td valign='middle' colspan='1' class='' style='text-align:left' nowrap><b>Fee Receipt&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class='' align='left'><input type='text' class='selectfield' name='feeReceiptNo' id='feeReceiptNo' style='width:196px'  value='".$REQUEST_DATA['feeReceiptNo']."'/></td>
                        <td valign='middle' colspan='1' class='' style='text-align:left'>
                        <nobr><b>Institute Reg. No.&nbsp; </b></nobr></td>
                        <td valign='middle' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class='' style='text-align:left'><input type='text' class='selectfield' name='regNo' id='regNo' style='width:196px' value='".$REQUEST_DATA['regNo']."'/></td>
                    </tr>
                    <tr id='academic1' style='display:none;'>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Degree&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                        <div id='degreeContainerDiv'>
                            <select multiple name='degreeId[]' id='degreeId' size='5' style='width:193px'>".$this->getDegreeAbbr($degreeArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='d1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='d2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                            <td id='d3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                            <td width='5%'>
                             <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"degreeId\",\"d1\",\"degreeContainerDiv\",\"d3\");' />
                            </td>
                            </tr>
                        </table>
                        </div>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Branch&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left;padding-left:1px;' >
                        <div id='branchContainerDiv'>
                            <select multiple='multiple' name='branchId[]' id='branchId' size='5' style='width:194px;'>".$this->getBranchData($branchArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='d11'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='d22' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='d33' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"branchId\",\"d11\",\"branchContainerDiv\",\"d33\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Periodicity&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                        <div id='periodicityContainerDiv'>
                            <select multiple name='periodicityId[]' id='periodicityId' size='5' style='width:193px'>".$this->getStudyPeriodData($periodicityArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='d111'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='d222' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='d333' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"periodicityId\",\"d111\",\"periodicityContainerDiv\",\"d333\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                    </tr>
                    <tr><td colspan='6' height='2px' id='academicDummyRow' style='display:none;'></td></tr>
                    <tr id='academic2' style='display:none;'>
                    ";
                    if ($showSections === true) {
                        $studentAcademicSearch .= "
                        <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Course :&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                            <select multiple name='courseId[]' id='courseId' size='5' class='htmlElement2' style='width:200px'>".$this->getCourseData($subjectArr,'')."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"None\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class=''  align='right'><b>Section :&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                            <select multiple name='sectionId[]' id='sectionId' size='5' class='htmlElement2' style='width:200px'>".$this->getSectionAbbr()."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"None\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class=''  align='right'><b>Univ :&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                            <select multiple name='universityId[]' id='universityId' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr()."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\");'>None</a>
                        </td>
                        <td valign='top' colspan='2' class=''></td>";
                    }
                    else {
                        $studentAcademicSearch .= "
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Subject&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                        <div id='courseContainerDiv'>
                            <select multiple name='courseId[]' id='courseId' size='5' style='width:193px'>".$this->getCourseData($subjectArr,'')."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='d1111'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='d2222' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='d3333' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"courseId\",\"d1111\",\"courseContainerDiv\",\"d3333\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Group&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left;padding-left:1px;' >
                        <div id='groupContainerDiv'>
                            <select multiple name='groupId[]' id='groupId' size='5' style='width:194px'>".$this->getCurrentGroups($groupArr,'')."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='d11111'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='d22222' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='d33333' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"groupId\",\"d11111\",\"groupContainerDiv\",\"d33333\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>University&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                        <div id='univContainerDiv'>
                            <select multiple name='universityId[]' id='universityId' size='5' style='width:193px'>".$this->getUniversityAbbr($universityArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='d111111'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='d222222' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='d333333' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"universityId\",\"d111111\",\"univContainerDiv\",\"d333333\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                        <td valign='top' colspan='3' class=''></td>";
                    }
                    $studentAcademicSearch .= " </tr>";
            }


					return $studentAcademicSearch;

					/* INSTITUTE REMOVED
						<td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Institute :&nbsp; </b></td>
						<td valign='middle' colspan='1' class=''>
							<select multiple name='instituteId[]' size='5' class='selectfield' style='width:200px'>".$this->getInstituteData()."
							</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"instituteId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"instituteId[]\",\"None\");'>None</a>
						</td>
					*/

		}



//----------------------------------------------------------------------------
//Purpose: make parent filter for academic search
//Author:Ajinder Singh   + Dipanjan Bhattacharjee
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
    public function makeParentAcademicSearch($showSections = false,$frm) {

        global $REQUEST_DATA;
        $degreeArr = explode(",", $REQUEST_DATA['degreeId']);
        $branchArr = explode(",", $REQUEST_DATA['branchId']);
        $periodicityArr = explode(",", $REQUEST_DATA['periodicityId']);
        $subjectArr = explode(",", $REQUEST_DATA['subjectId']);
        $sectionArr = explode(",", $REQUEST_DATA['sectionId']);
        $universityArr = explode(",", $REQUEST_DATA['universityId']);

        //detecting IE6 browser
        $isIE6=$this->isIE6Browser();

       if($isIE6==1){
        $studentAcademicSearch = "
                    <tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' style='text-align:left' nowrap><B>Academic criteria&nbsp;</B>
                        </td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td colspan='9' align='left'>
                            <a class='allReportLink' href='javascript:showHideParent(\"parent_academic\");'><span id='parent_academic'>Expand</span></a>
                        </td>
                    </tr>
                    <tr height='5'></tr>
                    <tr id='parent_academic1' style='display:none;'>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Degree&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='degreeId[]' id='degreeId' size='5' class='htmlElement2' style='width:200px'>".$this->getDegreeAbbr($degreeArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"degreeId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"degreeId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Branch&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple='multiple' name='branchId[]' id='branchId' size='5' class='htmlElement2' style='width:200px;'>".$this->getBranchData($branchArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"branchId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"branchId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class=''  style='text-align:left'>&nbsp;<b>Periodicity&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='periodicityId[]' id='periodicityId' size='5' class='htmlElement2' style='width:200px'>".$this->getStudyPeriodData($periodicityArr)."</select> <br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"periodicityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"periodicityId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                    </tr>
                    <tr id='parent_academic2' style='display:none;'>
                    ";
                    if ($showSections === true) {
                        $studentAcademicSearch .= "
                        <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Course&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='courseId[]' id='courseId' size='5' class='htmlElement2' style='width:200px'>".$this->getCourseData($subjectArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Section&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='sectionId[]' id='sectionId' size='5' class='htmlElement2' style='width:200px'>".$this->getSectionAbbr($sectionArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>University&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='universityId[]' id='universityId' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr($universityArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='2' class=''></td>";
                    }
                    else {

                        $studentAcademicSearch .= "
                        <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Subject&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class='' style='text-align:left' >
                            <select multiple name='courseId[]' id='courseId' size='5' class='htmlElement2' style='width:200px'>".$this->getCourseData($subjectArr,'')."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Group&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class='' style='text-align:left' >
                            <select multiple name='groupId[]' id='groupId' size='5' class='htmlElement2' style='width:200px'>".$this->getCurrentGroups($groupArr,'')."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"groupId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"groupId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class=''  align='right'><b>Univ </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='universityId[]' id='universityId' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr($universityArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='3' class=''></td>";
                    }
                    $studentAcademicSearch .= " </tr><tr id='parent_academic3' style='display:none;'></tr>";

                 }
                else{
                    $studentAcademicSearch = "
                    <tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' style='text-align:left' nowrap><B>Academic criteria&nbsp;</B>
                        </td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td colspan='9' align='left'>
                            <a class='allReportLink' href='javascript:showHideParent(\"parent_academic\",1);'><span id='parent_academic'>Expand</span></a>
                        </td>
                    </tr>
                    <tr height='5'></tr>
                    <tr id='parent_academic1' style='display:none;'>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Degree&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                        <div id='degree_parentContainerDiv'>
                            <select multiple name='degreeId[]' id='degree_parentId' size='5' class='htmlElement2' style='width:200px'>".$this->getDegreeAbbr($degreeArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='degree_parentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='degree_parentD2' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                             <td id='degree_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                             <td width='5%'>
                              <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"degree_parentId\",\"degree_parentD1\",\"degree_parentContainerDiv\",\"degree_parentD3\");' />
                             </td>
                             </tr>
                            </table>
                         </div>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Branch&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                        <div id='branch_parentContainerDiv'>
                            <select multiple='multiple' name='branchId[]' id='branch_parentId' size='5' style='width:196px;'>".$this->getBranchData($branchArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='branch_parentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='branch_parentD2' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                             <td id='branch_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                             <td width='5%'>
                              <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"branch_parentId\",\"branch_parentD1\",\"branch_parentContainerDiv\",\"branch_parentD3\");' />
                             </td>
                             </tr>
                            </table>
                         </div>
                        </td>
                        <td valign='top' colspan='1' class=''  style='text-align:left'>&nbsp;<b>Periodicity&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                        <div id='periodicity_parentContainerDiv'>
                            <select multiple name='periodicityId[]' id='periodicity_parentId' size='5' style='width:196px'>".$this->getStudyPeriodData($periodicityArr)."
                            </select>
                            </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='periodicity_parentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='periodicity_parentD2' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                             <td id='periodicity_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                             <td width='5%'>
                              <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"periodicity_parentId\",\"periodicity_parentD1\",\"periodicity_parentContainerDiv\",\"periodicity_parentD3\");' />

                             </td>
                             </tr>
                            </table>
                         </div>
                        </td>
                    </tr>
                    <tr id='parent_academic2' style='display:none;'>
                    ";
                    if ($showSections === true) {
                        $studentAcademicSearch .= "
                        <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Course&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='courseId[]' id='courseId' size='5' class='htmlElement2' style='width:200px'>".$this->getCourseData($subjectArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"courseId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>Section&nbsp;</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='sectionId[]' id='sectionId' size='5' class='htmlElement2' style='width:200px'>".$this->getSectionAbbr($sectionArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"sectionId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left'><b>University&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='universityId[]' id='universityId' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr($universityArr)."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='2' class=''></td>";
                    }
                    else {

                        $studentAcademicSearch .= "
                        <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Subject&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left' >
                        <div id='course_parentContainerDiv'>
                            <select multiple name='courseId[]' id='course_parentId' size='5' style='width:196px'>".$this->getCourseData($subjectArr,'')."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='course_parentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='course_parentD2' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                             <td id='course_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                             <td width='5%'>
                              <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"course_parentId\",\"course_parentD1\",\"course_parentContainerDiv\",\"course_parentD3\");' />
                             </td>
                             </tr>
                            </table>
                         </div>
                        </td>
                        <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Group&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class='' style='text-align:left' >
                        <div id='group_parentContainerDiv'>
                            <select multiple name='groupId[]' id='group_parentId' size='5' style='width:196px'>".$this->getCurrentGroups($groupArr,'')."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='group_parentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='group_parentD2' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                             <td id='group_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                             <td width='5%'>
                              <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"group_parentId\",\"group_parentD1\",\"group_parentContainerDiv\",\"group_parentD3\");' />
                             </td>
                             </tr>
                            </table>
                         </div>
                        </td>
                        <td valign='top' colspan='1' class=''  align='right'><b>Univ</b></td>
                        <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                        <div id='univ_parentContainerDiv'>
                            <select multiple name='universityId[]' id='university_parentId' size='5' style='width:196px'>".$this->getUniversityAbbr($universityArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='univ_parentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='univ_parentD2' >
                            <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                            <tr>
                             <td id='univ_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                             <td width='5%'>
                              <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"university_parentId\",\"univ_parentD1\",\"univ_parentContainerDiv\",\"univ_parentD3\");' />
                             </td>
                             </tr>
                            </table>
                         </div>
                        </td>
                        <td valign='top' colspan='3' class=''></td>";
                    }

                    $studentAcademicSearch .= " </tr><tr id='parent_academic3' style='display:none;'></tr>";
                }

                    return $studentAcademicSearch;
        }


//----------------------------------------------------------------------------
//Purpose: make student filter for address search
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function makeStudentAddressSearch() {

			global $REQUEST_DATA;
			$cityArr = explode(",", $REQUEST_DATA['cityId']);
			$stateArr = explode(",", $REQUEST_DATA['stateId']);
			$countryArr = explode(",", $REQUEST_DATA['countryId']);

            //detecting IE6 browser
            $isIE6=$this->isIE6Browser();
            if($isIE6==1 or $isIE6==0){

			$studentAddressSearch = "
				<tr class='showHideRow'>
					<td valign='top' colspan='1' class='' style='text-align:left' >
						<B>Address criteria&nbsp;</B>
					</td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='top' colspan='7'  align='left'>
						<a class='allStudentLink' href='javascript:showHide(\"address\");'><span id='address'>Expand</span></a>
					</td>
				</tr>
				<tr height='5'></tr>
				<tr id='address1' style='display:none;'>
					<td valign='top' colspan='1' class='' style='text-align:left' ><b>City&nbsp; </b></td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select multiple name='cityId[]' id='cityId' size='5' class='htmlElement2' style='width:200px'>".$this->getCityData($cityArr)."
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"None\");'>None</a>
					</td>
					<td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>State&nbsp; </b></td>

					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select multiple name='stateId[]' id='stateId' size='5' class='htmlElement2' style='width:200px'>".$this->getStatesData($stateArr)."
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"None\");'>None</a>
					</td>
					<td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Country&nbsp; </b></td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select multiple name='countryId[]' id='countryId' size='5' class='htmlElement2' style='width:200px' >".$this->getCountriesData($countryArr)."
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"None\");'>None</a>
					</td>
				</tr>
			";
           }
           else{
            $studentAddressSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                        <B>Address criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='7'  align='left'>
                        <a class='allStudentLink' href='javascript:showHide(\"address\",1);'><span id='address'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='address1' style='display:none;'>
                    <td valign='top' colspan='1' class='' style='text-align:left' ><b>City&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                    <div id='cityContainerDiv'>
                        <select multiple name='cityId[]' id='cityId' size='5' style='width:196px'>".$this->getCityData($cityArr)."
                        </select>
                     </div>
                     <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='cityD1'></div>
                     <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='cityD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='cityD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"cityId\",\"cityD1\",\"cityContainerDiv\",\"cityD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>State&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                    <div id='stateContainerDiv'>
                        <select multiple name='stateId[]' id='stateId' size='5' style='width:196px'>".$this->getStatesData($stateArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='stateD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='stateD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='stateD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"stateId\",\"stateD1\",\"stateContainerDiv\",\"stateD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Country&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                    <div id='countryContainerDiv'>
                        <select multiple name='countryId[]' id='countryId' size='5' style='width:196px' >".$this->getCountriesData($countryArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='countryD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='countryD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='countryD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"countryId\",\"countryD1\",\"countryContainerDiv\",\"countryD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                </tr>
            ";
           }
			return $studentAddressSearch;
		}


//----------------------------------------------------------------------------
//Purpose: make parent filter for address search
//Author:Ajinder Singh + Dipanjan Bhattacharjee
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeParentAddressSearch($frm) {

            global $REQUEST_DATA;
            $cityArr = explode(",", $REQUEST_DATA['cityId']);
            $stateArr = explode(",", $REQUEST_DATA['stateId']);
            $countryArr = explode(",", $REQUEST_DATA['countryId']);

            //detecting IE6 browser
            $isIE6=$this->isIE6Browser();

            if($isIE6==1){
            $studentAddressSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>Address criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='7'  align='left'>
                        <a class='allReportLink' href='javascript:showHideParent(\"parent_address\");'><span  align='left' id='parent_address'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='parent_address1' style='display:none;'>
                    <td valign='top' colspan='1' class='' style='text-align:left'><b>City&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select multiple name='cityId[]' id='cityId' size='5' class='htmlElement2' style='width:200px'>".$this->getCityData($cityArr)."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>State&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select multiple name='stateId[]' id='stateId' size='5' class='htmlElement2' style='width:200px'>".$this->getStatesData($stateArr)."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Country&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select multiple name='countryId[]' id='countryId' size='5' class='htmlElement2' style='width:200px' >".$this->getCountriesData($countryArr)."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                </tr>
            ";
            }
         else{
           $studentAddressSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>Address criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='7'  align='left'>
                        <a class='allReportLink' href='javascript:showHideParent(\"parent_address\",1);'><span  align='left' id='parent_address'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='parent_address1' style='display:none;'>
                    <td valign='top' colspan='1' class='' style='text-align:left'><b>City&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left'>
                    <div id='city_parentContainerDiv'>
                        <select multiple name='cityId[]' id='city_parentId' size='5' style='width:196px'>".$this->getCityData($cityArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='city_parentD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='city_parentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='city_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"city_parentId\",\"city_parentD1\",\"city_parentContainerDiv\",\"city_parentD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>State&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left'>
                    <div id='state_parentContainerDiv'>
                        <select multiple name='stateId[]' id='state_parentId' size='5' style='width:196px'>".$this->getStatesData($stateArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='state_parentD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='state_parentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='state_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"state_parentId\",\"state_parentD1\",\"state_parentContainerDiv\",\"state_parentD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Country&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left'>
                    <div id='country_parentContainerDiv'>
                        <select multiple name='countryId[]' id='country_parentId' size='5' style='width:196px' >".$this->getCountriesData($countryArr)."
                        </select>

                     </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='country_parentD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='country_parentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='country_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"country_parentId\",\"country_parentD1\",\"country_parentContainerDiv\",\"country_parentD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                </tr>
            ";
         }

            return $studentAddressSearch;
        }

//----------------------------------------------------------------------------

//Purpose: make student filter for misc search
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function makeStudentMiscSearch() {

			global $REQUEST_DATA;
			$mgmtSelected="";
			$notSelected="";
			if($REQUEST_DATA['categoryId']=='1' and $REQUEST_DATA['categoryId']!=''){

				$mgmtSelected="SELECTED";
			}
			elseif($REQUEST_DATA['categoryId']=='0' and $REQUEST_DATA['categoryId']!='' ){

				$notSelected="SELECTED";
			}
			$hostelArr = explode(",", $REQUEST_DATA['hostelId']);
			$busStopArr = explode(",", $REQUEST_DATA['busStopId']);
			$busRouteArr = explode(",", $REQUEST_DATA['busRouteId']);

            //detecting IE6 browser
            $isIE6=$this->isIE6Browser();

            if($isIE6==1 or $isIE6==0){
            $studentMiscSearch = "
				<tr class='showHideRow'>
					<td valign='top' colspan='1' class='' style='text-align:left' >
						<B>Misc. criteria&nbsp;</B>
					</td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='top' colspan='7'  align='left'>
						<a class='allStudentLink' href='javascript:showHide(\"misc\");'><span id='misc'>Expand</span></a>
					</td>
				</tr>
				<tr height='5'></tr>
				<tr id='misc1' style='display:none;'>
					<td valign='middle' colspan='1' style='width:50px' class='' style='text-align:left' >
					<nobr><b>Mgmt. Cat.&nbsp; </b></nobr></td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' align='left' style='text-align:left' >
						<select name='categoryId' class='selectfield' style='width:200px' >
							<option value='' selected>Select</option>
							<option value='1' ".$mgmtSelected.">Yes</option>
							<option value='0' ".$notSelected.">No</option>
						</select>
					</td>
					<td valign='top' colspan='1' class='' style='text-align:left' >
						<nobr><B>&nbsp;Admn Date&nbsp;From&nbsp;</B></nobr>
					</td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='top' colspan='1' class='' style='text-align:left' >
					   <select size='1' name='admissionYearF' id='admissionYearF' class='htmlElement' style='width:67px;'>
						<option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearF'])."</select>
						<select size='1' name='admissionMonthF' id='admissionMonthF' class='htmlElement' style='width:67px;'>
						<option value=''>Month</option>".$this->getBirthMonth(abs($REQUEST_DATA['admissionMonthF']))."</select>
						<select size='1' name='admissionDateF' id='admissionDateF' class='htmlElement' style='width:60px;'>
						<option value=''>Day</option>".$this->getBirthDate(abs($REQUEST_DATA['admissionDateF']))."</select>
					</td>
					<td valign='top' colspan='1' class='' style='text-align:left' >
						&nbsp;<B>To</B>&nbsp;
					</td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='top' colspan='1' class='' style='text-align:left' >
					   <select size='1' name='admissionYearT' id='admissionYearT' class='htmlElement' style='width:67px;'>
						<option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearT'])."</select>
						<select size='1' name='admissionMonthT' id='admissionMonthT' class='htmlElement' style='width:67px;'>
						<option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['admissionMonthT'])."</select>
						<select size='1' name='admissionDateT' id='admissionDateT' class='htmlElement' style='width:60px;'>
						<option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['admissionDateT'])."</select>
					</td>
				</tr>
				<tr id='misc2' style='display:none;'>
					<td valign='top' colspan='1' class='' style='text-align:left' ><b>Hostel&nbsp; </b></td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select multiple name='hostelId[]' id='hostelId' size='5' class='htmlElement2' style='width:200px'>".$this->getHostelName($hostelArr)."
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"hostelId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"hostelId[]\",\"None\");'>None</a>
					</td>
					<td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Bus Stop&nbsp; </b></td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select multiple name='busStopId[]' id='busStopId' size='5' class='htmlElement2' style='width:200px' >".$this->getBusStopName($busStopArr)."
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"busStopId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"busStopId[]\",\"None\");'>None</a>
					</td>
					<td valign='top' colspan='1' class='' style='text-align:left' nowrap>&nbsp;<b>Bus Route&nbsp; </b></td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select multiple name='busRouteId[]' id='busRouteId' size='5' class='htmlElement2' style='width:200px' >".$this->getBusRouteName($busRouteArr)."
						</select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"busRouteId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"busRouteId[]\",\"None\");'>None</a>
					</td>
				</tr>
				<tr id='misc3' style='display:none;'>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						&nbsp;<b>Quota&nbsp; </b>
					</td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select name='quotaId' id='quotaId' class='selectfield' style='width:200px' >
						<option value='' selected>Select</option>".$this->getCurrentCategories($REQUEST_DATA['quotaId'],' WHERE parentQuotaId=0 ',$showParentCat='1')."</select>
					</td>

					<td valign='middle' colspan='1' class='' style='text-align:left' >
						&nbsp;<b>Blood Group&nbsp; </b>
					</td>
					<td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
					<td valign='middle' colspan='1' class='' style='text-align:left' >
						<select name='bloodGroup' id='bloodGroup' class='selectfield' style='width:200px' >
						<option value='' selected>Select</option>".$this->getBloodGroupData($REQUEST_DATA['bloodGroup'])."</select>
					</td>

				</tr>
			";
          }
          else{
           $studentMiscSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                        <B>Misc. criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='7'  align='left'>
                        <a class='allStudentLink' href='javascript:showHide(\"misc\",1);'><span id='misc'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='misc1' style='display:none;'>
                    <td valign='middle' colspan='1' style='width:50px' class='' style='text-align:left' >
                    <nobr><b>Mgmt. Cat.&nbsp; </b></nobr></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left' style='text-align:left' >
                        <select name='categoryId' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>
                            <option value='1' ".$mgmtSelected.">Yes</option>
                            <option value='0' ".$notSelected.">No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                        <nobr><B>&nbsp;Admn Date&nbsp;From&nbsp;</B></nobr>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                       <select size='1' name='admissionYearF' id='admissionYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearF'])."</select>
                        <select size='1' name='admissionMonthF' id='admissionMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth(abs($REQUEST_DATA['admissionMonthF']))."</select>
                        <select size='1' name='admissionDateF' id='admissionDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate(abs($REQUEST_DATA['admissionDateF']))."</select>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                        &nbsp;<B>To</B>&nbsp;
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                       <select size='1' name='admissionYearT' id='admissionYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearT'])."</select>
                        <select size='1' name='admissionMonthT' id='admissionMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['admissionMonthT'])."</select>
                        <select size='1' name='admissionDateT' id='admissionDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['admissionDateT'])."</select>
                    </td>
                </tr>
                <tr id='misc2' style='display:none;'>
                    <td valign='top' colspan='1' class='' style='text-align:left' ><b>Hostel&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                    <div id='hostelContainerDiv'>
                        <select multiple name='hostelId[]' id='hostelId' size='5' style='width:196px'>".$this->getHostelName($hostelArr)."
                        </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='hostelD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='hostelD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='hostelD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"hostelId\",\"hostelD1\",\"hostelContainerDiv\",\"hostelD3\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Bus Stop&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                    <div id='busStopContainerDiv'>
                        <select multiple name='busStopId[]' id='busStopId' size='5' style='width:196px' >".$this->getBusStopName($busStopArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='busStopD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='busStopD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='busStopD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"busStopId\",\"busStopD1\",\"busStopContainerDiv\",\"busStopD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' nowrap>&nbsp;<b>Bus Route&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                    <div id='busRouteContainerDiv'>
                        <select multiple name='busRouteId[]' id='busRouteId' size='5' class='htmlElement2' style='width:197px' >".$this->getBusRouteName($busRouteArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='busRouteD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='busRouteD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='busRouteD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"busRouteId\",\"busRouteD1\",\"busRouteContainerDiv\",\"busRouteD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                </tr>
                <tr id='misc3' style='display:none;'>
                    <td valign='middle' colspan='1' class='' style='text-align:left' >
                        &nbsp;<b>Quota&nbsp; </b>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' style='text-align:left' >
                        <select name='quotaId' id='quotaId' class='selectfield' style='width:200px' >
                        <option value='' selected>Select</option>".$this->getCategoryClassData($REQUEST_DATA['quotaId'])."</select>
                    </td>

                    <td valign='middle' colspan='1' class='' style='text-align:left' >
                        &nbsp;<b>Blood Group&nbsp; </b>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' style='text-align:left' >
                        <select name='bloodGroup' id='bloodGroup' class='selectfield' style='width:200px' >
                        <option value='' selected>Select</option>".$this->getBloodGroupData($REQUEST_DATA['bloodGroup'])."</select>
                    </td>

                </tr>
            ";
          }

			return $studentMiscSearch;
		}

//----------------------------------------------------------------------------
//Purpose: make student filter for Hostel wise search
//Author:Dipanjan Bhattacharjee
//Date:14.01.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeStudentHostelSearch() {

            global $REQUEST_DATA;
            $mgmtSelected="";
            $notSelected="";
            $hostelArr = explode(",", $REQUEST_DATA['studentHostelId']);
            $roomTypeArr = explode(",", $REQUEST_DATA['studentHostelRoomTypeId']);
            $roomArr = explode(",", $REQUEST_DATA['studentHostelRoomId']);
            $studentMiscSearch = "
                <tr >
                    <td valign='top' colspan='1' class='' style='text-align:left' ><b>Hostel&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                    <div id='studentHostelContainerDiv'>
                        <select multiple name='hostelId[]' id='studentHostelId' size='5' style='width:196px'>".$this->getHostelName($hostelArr)."
                        </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='studentHostelD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='studentHostelD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='studentHostelD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"studentHostelId\",\"studentHostelD1\",\"studentHostelContainerDiv\",\"studentHostelD3\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Room Type&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                    <div id='studentHostelRoomTypeDiv'>
                        <select multiple name='studentHostelRoomTypeId[]' id='studentHostelRoomTypeId' size='5' style='width:196px' >".$this->getHostelRoomTypeData($roomTypeArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='studentHostelRoomTypeD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='studentHostelRoomTypeD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='studentHostelRoomTypeD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"studentHostelRoomTypeId\",\"studentHostelRoomTypeD1\",\"studentHostelRoomTypeContainerDiv\",\"studentHostelRoomTypeD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' nowrap>&nbsp;<b>Room&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                    <div id='studentHostelRoomContainerDiv'>
                        <select multiple name='studentHostelRoomId[]' id='studentHostelRoomId' size='5' class='htmlElement2' style='width:197px' >".$this->getHostelRoomData($roomArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='studentHostelRoomD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='studentHostelRoomD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='studentHostelRoomD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"studentHostelRoomId\",\"studentHostelRoomD1\",\"studentHostelRoomContainerDiv\",\"studentHostelRoomD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                </tr>
            ";

            return $studentMiscSearch;
        }


//----------------------------------------------------------------------------
//Purpose: make student filter for Transport wise search
//Author:Dipanjan Bhattacharjee
//Date:14.01.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeStudentTransportSearch() {

            global $REQUEST_DATA;
            $busStopArr = explode(",", $REQUEST_DATA['studentBusStopId']);
            $busRouteArr = explode(",", $REQUEST_DATA['studentBusRouteId']);

            $studentMiscSearch = "
                <tr>
                    <td valign='top' colspan='1' class='' style='text-align:left' >&nbsp;<b>Bus Stop&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left;padding-left:2px;' >
                    <div id='studentBusStopContainerDiv'>
                        <select multiple name='studentBusStopId[]' id='studentBusStopId' size='5' style='width:196px' >".$this->getBusStopName($busStopArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF' id='studentBusStopD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='studentBusStopD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='studentBusStopD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"studentBusStopId\",\"studentBusStopD1\",\"studentBusStopContainerDiv\",\"studentBusStopD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left' nowrap>&nbsp;<b>Bus Route&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' style='text-align:left' >
                    <div id='studentBusRouteContainerDiv'>
                        <select multiple name='studentBusRouteId[]' id='studentBusRouteId' size='5' class='htmlElement2' style='width:197px' >".$this->getBusRouteName($busRouteArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='studentBusRouteD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='studentBusRouteD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='studentBusRouteD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"studentBusRouteId\",\"studentBusRouteD1\",\"studentBusRouteContainerDiv\",\"studentBusRouteD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                </tr>
            ";

            return $studentMiscSearch;
        }


//----------------------------------------------------------------------------
//Purpose: make parent filter for misc search
//Author:Ajinder Singh + Dipanjan Bhattacharjee
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeParentMiscSearch($frm) {

            global $REQUEST_DATA;
            //echo "<pre>";
            //print_r($REQUEST_DATA);
            $mgmtSelected="";
            $notSelected="";
            if($REQUEST_DATA['categoryId']=='1' and $REQUEST_DATA['categoryId']!='')
                $mgmtSelected="SELECTED";
            elseif($REQUEST_DATA['categoryId']=='0' and $REQUEST_DATA['categoryId']!='' )
                $notSelected="SELECTED";
                $notSelected="SELECTED";

            $hostelArr = explode(",", $REQUEST_DATA['hostelId']);
            $busStopArr = explode(",", $REQUEST_DATA['busStopId']);
            $busRouteArr = explode(",", $REQUEST_DATA['busRouteId']);

            //detecting IE6 browser
            $isIE6=$this->isIE6Browser();

            if($isIE6==1){
            $studentMiscSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>Misc. criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='7'  align='left'>
                        <a class='allReportLink' href='javascript:showHideParent(\"parent_misc\");'><span id='parent_misc'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='parent_misc1' style='display:none;'>
                    <td valign='middle' colspan='1' style='width:40px' class='' style='text-align:left'>
                    <nobr><b>Mgmt. Cat.&nbsp; </b></nobr></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select name='categoryId' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>
                            <option value='1' ".$mgmtSelected.">Yes</option>
                            <option value='0' ".$notSelected.">No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>&nbsp;Admn Date: From&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left'>
                       <select size='1' name='admissionYearF' id='admissionYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearF'])."</select>
                        <select size='1' name='admissionMonthF' id='admissionMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['admissionMonthF'])."</select>
                        <select size='1' name='admissionDateF' id='admissionDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['admissionDateF'])."</select>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>To</B>&nbsp;
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                       <select size='1' name='admissionYearT' id='admissionYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearT'])."</select>
                        <select size='1' name='admissionMonthT' id='admissionMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['admissionMonthT'])."</select>
                        <select size='1' name='admissionDateT' id='admissionDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['admissionDateT'])."</select>
                    </td>
                </tr>
                <tr id='parent_misc2' style='display:none;'>
                    <td valign='top' colspan='1' class='' style='text-align:left'><b>Hostel&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class=''>
                        <select multiple name='hostelId[]' id='hostelId' size='5' class='htmlElement2' style='width:200px'>".$this->getHostelName($hostelArr)."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"hostelId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"hostelId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Bus Stop&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class=''>
                        <select multiple name='busStopId[]' id='busStopId' size='5' class='htmlElement2' style='width:200px' >".$this->getBusStopName($busStopArr)."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"busStopId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"busStopId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Bus Route&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class=''>
                        <select multiple name='busRouteId[]' id='busRouteId' size='5' class='htmlElement2' style='width:200px' >".$this->getBusRouteName($busRouteArr)."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"busRouteId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"busRouteId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                </tr>
                <tr id='parent_misc3' style='display:none;'>
                    <td valign='middle' colspan='1' class='' style='text-align:left'>
                        &nbsp;<b>Quota&nbsp; </b>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class=''>
                        <select name='quotaId' id='quotaId' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>".$this->getCategoryClassData($REQUEST_DATA['quotaId'])."</select>
                    </td>
                    <td valign='top' colspan='4' class=''>

                    </td>
                </tr>
            ";
           }
           else{
            $studentMiscSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>Misc. criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='7'  align='left'>
                        <a class='allReportLink' href='javascript:showHideParent(\"parent_misc\",1);'><span id='parent_misc'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='parent_misc1' style='display:none;'>
                    <td valign='middle' colspan='1' style='width:40px' class='' style='text-align:left'>
                    <nobr><b>Mgmt. Cat.&nbsp; </b></nobr></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select name='categoryId' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>
                            <option value='1' ".$mgmtSelected.">Yes</option>
                            <option value='0' ".$notSelected.">No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>&nbsp;Admn Date: From&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class='' align='left'>
                       <select size='1' name='admissionYearF' id='admissionYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearF'])."</select>
                        <select size='1' name='admissionMonthF' id='admissionMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['admissionMonthF'])."</select>
                        <select size='1' name='admissionDateF' id='admissionDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['admissionDateF'])."</select>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>
                        <B>To</B>&nbsp;
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                       <select size='1' name='admissionYearT' id='admissionYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear($REQUEST_DATA['admissionYearT'])."</select>
                        <select size='1' name='admissionMonthT' id='admissionMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['admissionMonthT'])."</select>
                        <select size='1' name='admissionDateT' id='admissionDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['admissionDateT'])."</select>
                    </td>
                </tr>
                <tr id='parent_misc2' style='display:none;'>
                    <td valign='top' colspan='1' class='' style='text-align:left'><b>Hostel&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                    <div id='hostel_parentContainerDiv'>
                        <select multiple name='hostelId[]' id='hostel_parentId' size='5' style='width:196px'>".$this->getHostelName($hostelArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='hostel_parentD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='hostel_parentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='hostel_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"hostel_parentId\",\"hostel_parentD1\",\"hostel_parentContainerDiv\",\"hostel_parentD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Bus Stop&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                     <div id='busStop_parentContainerDiv'>
                        <select multiple name='busStopId[]' id='busStop_parentId' size='5' style='width:196px' >".$this->getBusStopName($busStopArr)."
                        </select>
                     </div>
                     <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='busStop_parentD1'></div>
                     <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='busStop_parentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='busStop_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"busStop_parentId\",\"busStop_parentD1\",\"busStop_parentContainerDiv\",\"busStop_parentD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' style='text-align:left'>&nbsp;<b>Bus Route&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                    <div id='busRoute_parentContainerDiv'>
                        <select multiple name='busRouteId[]' id='busRoute_parentId' size='5' style='width:196px' >".$this->getBusRouteName($busRouteArr)."
                        </select>
                     </div>
                     <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='busRoute_parentD1'></div>
                     <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='busRoute_parentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='busRoute_parentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"busRoute_parentId\",\"busRoute_parentD1\",\"busRoute_parentContainerDiv\",\"busRoute_parentD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                </tr>
                <tr id='parent_misc3' style='display:none;'>
                    <td valign='middle' colspan='1' class='' style='text-align:left'>
                        &nbsp;<b>Quota&nbsp; </b>
                    </td>
                    <td valign='top' colspan='1' class=''><b>:&nbsp; </b></td>
                    <td valign='middle' colspan='1' class=''>
                        <select name='quotaId' id='quotaId' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>".$this->getCategoryClassData($REQUEST_DATA['quotaId'])."</select>
                    </td>
                    <td valign='top' colspan='4' class=''>

                    </td>
                </tr>
            ";
           }
            return $studentMiscSearch;
        }

//----------------------------------------------------------------------------
//Purpose: fetch list of subjects with subject code
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getSubjectsWithCode() {
			$results = CommonQueryManager::getInstance()->getSubjectsWithCode();

			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectName']).'</option>';
				}
			}
			return $returnValues;
		}

//----------------------------------------------------------------------------
//Purpose: fetch list of subjects with subject code for which tests have been taken
//Author:Ajinder Singh
//Date:13.10.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getTestSubjectsWithCode() {
			$results = CommonQueryManager::getInstance()->getTestSubjectsWithCode();

			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectName']).'</option>';
				}
			}
			return $returnValues;
		}

//----------------------------------------------------------------------------
//Purpose: fetch list of sections
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getSectionList() {
			$results = CommonQueryManager::getInstance()->getSectionList();
			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
						$returnValues .='<option value="'.$results[$i]['sectionId'].'">'.strip_slashes($results[$i]['sectionName'].' '.$results[$i]['sectionType']).'</option>';
				}
			}
			return $returnValues;
		}

//----------------------------------------------------------------------------
//Purpose: fetch list of sections
//Author:Ajinder Singh
//Date:10.09.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getSectionAbbr() {
			$results = CommonQueryManager::getInstance()->getSectionAbbr();
			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
						$returnValues .='<option value="'.$results[$i]['sectionId'].'">'.strip_slashes($results[$i]['sectionName']).'</option>';
				}
			}
			return $returnValues;
		}

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
//
// selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
	public function getTimeTableLabelData($selected='',$condition='') {
	$results = CommonQueryManager::getInstance()->getTimeTableLabel($condition);
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);

			for($i=0;$i<$count;$i++) {
				$timeTableLabelId = $results[$i]['timeTableLabelId'];
				$labelName = $results[$i]['labelName'];
				if( ($results[$i]['timeTableLabelId']==$selected) || ($results[$i]['isActive']==1 && $selected=='')) {
					$returnValues .='<option value="'.$results[$i]['timeTableLabelId'].'" SELECTED="SELECTED">'.strip_slashes($labelName).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['timeTableLabelId'].'">'.strip_slashes($labelName).'</option>';
				}
			}

		}
		return $returnValues;
	}























//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
// selected: which element in the select element to be selected
// Author :Rajeev Aggarwal
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------------------------------------------
	public function getPublicationData($selected='',$condition='') {
	$results = EmployeeManager::getInstance()->getPublication($condition);
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);

			for($i=0;$i<$count;$i++) {
				$publicationId = $results[$i]['publicationId'];
				$publicationName = $results[$i]['publicationName'];
				if( ($results[$i]['publicationId']==$selected)  && $selected=='') {
					$returnValues .='<option value="'.$results[$i]['publicationId'].'" SELECTED="SELECTED">'.strip_slashes($publicationName).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['publicationId'].'">'.strip_slashes($publicationName).'</option>';
				}
			}
		}
		return $returnValues;
	}

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
//
// selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
	public function getAllTimeTableLabelData($selected='',$condition='') {
		$results = CommonQueryManager::getInstance()->getTimeTableLabel($condition);
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$timeTableLabelId = $results[$i]['timeTableLabelId'];
				$labelName = $results[$i]['labelName'];
				if( ($results[$i]['timeTableLabelId']==$selected) || ($results[$i]['isActive']==1 && $selected=='')) {
					$returnValues .='<option value="'.$results[$i]['timeTableLabelId'].'">'.strip_slashes($labelName).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['timeTableLabelId'].'">'.strip_slashes($labelName).'</option>';
				}
			}
		}
		return $returnValues;
	}


//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
//
// selected: which element in the select element to be selected
//
// Author :Parveen Sharma
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getTimeTableLabelDate($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getTimeTableLabel($condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $timeTableLabelId = $results[$i]['timeTableLabelId']."~".$results[$i]['startDate']."~".$results[$i]['endDate']."~".$results[$i]['timeTableType'];
                $labelName = $results[$i]['labelName'];
                if( ($results[$i]['timeTableLabelId']==$selected) || ($results[$i]['isActive']==1 && $selected=='') ) {
                    $returnValues .='<option value="'.$timeTableLabelId.'" SELECTED="SELECTED">'.strip_slashes($labelName).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$timeTableLabelId.'">'.strip_slashes($labelName).'</option>';
                }
            }
        }
        return $returnValues;
    }

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels for teachers as they can see only active and past
// classes time table
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (31.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------------------------------------------
    public function getTimeTableLabelDataForTeachers($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getTimeTableLabelForTeachers($condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $timeTableLabelId = $results[$i]['timeTableLabelId'];
                $labelName = $results[$i]['labelName'];
                if( ($results[$i]['timeTableLabelId']==$selected) || ($results[$i]['isActive']==1 && $selected=='')) {
                    $returnValues .='<option value="'.$results[$i]['timeTableLabelId'].'" SELECTED="SELECTED">'.strip_slashes($labelName).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['timeTableLabelId'].'">'.strip_slashes($labelName).'</option>';
                }
            }
        }
        return $returnValues;
    }


    //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FEE CYCLE NAME  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Arvind Singh Rawat
// Created on : (2.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
 public function getTestTypeData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getTestType('testTypeName',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['testTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['testTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['testTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['testTypeId'].'">'.strip_slashes($results[$i]['testTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------
//Purpose: fetch list of subjects with subject code for which marks have been transferred
//Author:Ajinder Singh
//Date:20-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getMarksTransferredSubjectsWithCode() {
			$results = CommonQueryManager::getInstance()->getMarksTransferredSubjectsWithCode();

			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
						$returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectName']).'</option>';
				}
			}
			return $returnValues;
		}

//----------------------------------------------------------------------------
//Purpose: fetch list of histogram label
//Author:Jaineesh
//Date:22-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getHistogramLabels() {
			$results = CommonQueryManager::getInstance()->getHistogramLabel('histogramLabel');

			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);

				for($i=0;$i<$count;$i++) {

						$returnValues .='<option value="'.$results[$i]['histogramId'].'">'.strip_slashes($results[$i]['histogramLabel']).'</option>';
				}
			}
			return $returnValues;
		}

//----------------------------------------------------------------------------
//Purpose: fetch list of subjects with subject code for which marks have been transferred
//Author:Ajinder Singh
//Date:20-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getGradingLabels() {
			$results = CommonQueryManager::getInstance()->getGradingLabels();

			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
						$returnValues .='<option value="'.$results[$i]['gradingLabelId'].'">'.strip_slashes($results[$i]['gradingLabel']).'</option>';
				}
			}
			return $returnValues;
		}

// -----------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF grades

 public function getGradeData($selected='') {
        $results = CommonQueryManager::getInstance()->getGrade('gradeLabel');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['gradeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['gradeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['gradeLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['gradeId'].'">'.strip_slashes($results[$i]['gradeLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }


// -----------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF grades

 public function getGradingLabelData($selected='') {

        $results = CommonQueryManager::getInstance()->getGradingLabel('gradingLabel');



        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['gradingLabel']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['gradingLabelId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['gradingLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['gradingLabelId'].'">'.strip_slashes($results[$i]['gradingLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------
//Purpose: make employee filter with default values
//Author:Parveen Sharma
//Date:31.10.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function makeEmployeeDefaultSearch() {
     global $REQUEST_DATA;
     $maleSelected="";
     $femaleSelected="";
     if($REQUEST_DATA['genderRadio']=='M')
        $maleSelected="SELECTED";
     if($REQUEST_DATA['genderRadio']=='F')
        $femaleSelected="SELECTED";
     $employeeDefaultSearch =  "
            <tr>
                <td valign='middle' nowrap colspan='1' class='' style='width:70px' align='left'><nobr><b>Employee Code &nbsp;</b></nobr></td>
                <td valign='middle' nowrap colspan='1' class='' align='left'>
                <nobr><b><span style='vertical-align:top;'>:</span></b>
                <input type='text' class='selectfield' name='employeeCode' value='".trim($REQUEST_DATA['employeeCode'])."' id='employeeCode' style='width:200px' />
                </nobr>
                </td>
                <td valign='middle' nowrap colspan='1' class='' align='left' style='padding-left:40px'><b>Employee Name&nbsp;</b></td>
                <td valign='top' nowrap colspan='3' class='' align='left'>
                <nobr><b><span style='vertical-align:top;'>:</span></b>
                <input type='text' class='selectfield' name='employeeName' value='".trim($REQUEST_DATA['employeeName'])."' id='employeeName' style='width:500px' />
                </nobr>
                </td>
            </tr>
            <tr>
                <td valign='middle' colspan='1' style='width:70px' class='' align='left'>
                <b>Gender&nbsp;</b></td>
                <td valign='middle' colspan='1' class='' align='left'>
                    <nobr><b><span style='vertical-align:top;'>:</span></b>
                    <select name='genderRadio' id='genderRadio' class='htmlElement' style='width:100px'>
                        <option value='' selected>Select</option>
                        <option value='M' $maleSelected>Male</option>
                        <option value='F' $femaleSelected>Female</option>
                    </select>
                    </nobr>
                </td>
                <td valign='top' colspan='1' class='' align='left' style='width:160px;padding-left:40px'><nobr>
                    <B>Birth Date &nbsp;From&nbsp;</B></nobr>
                </td>
                <td valign='top' colspan='1' class='' align='left'>
                   <nobr><b><span style='vertical-align:top;'>:</span></b>
                   <select size='1' name='birthYearF' id='birthYearF' class='htmlElement' style='width:67px;'>
                    <option value=''>Year</option>".$this->getBirthYear($REQUEST_DATA['birthYearF'])."</select>
                    <select size='1' name='birthMonthF' id='birthMonthF' class='htmlElement' style='width:67px;'>
                    <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['birthMonthF'])."</select>
                    <select size='1' name='birthDateF' id='birthDateF' class='htmlElement' style='width:60px;'>
                    <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['birthDateF'])."</select>
                    </nobr>
                </td>
                <td valign='top' colspan='1' class=''  align='left' style='padding-left:40px'>
                    <nobr><B>To</B></nobr>
                </td>
                <td valign='top' colspan='1' class='' align='left'>
                   <nobr><b><span style='vertical-align:top;' align:'left'>:</span></b>
                   <select size='1' name='birthYearT' id='birthYearT' class='htmlElement' style='width:67px;'>
                    <option value=''>Year</option>".$this->getBirthYear($REQUEST_DATA['birthYearT'])."</select>
                    <select size='1' name='birthMonthT' id='birthMonthT' class='htmlElement' style='width:67px;'>
                    <option value=''>Month</option>".$this->getBirthMonth($REQUEST_DATA['birthMonthT'])."</select>
                    <select size='1' name='birthDateT' id='birthDateT' class='htmlElement' style='width:60px;'>
                    <option value=''>Day</option>".$this->getBirthDate($REQUEST_DATA['birthDateT'])."</select>
                    </nobr>
                </td>
            </tr>";
            return $employeeDefaultSearch;
    }

//----------------------------------------------------------------------------
//Purpose: make employee filter for academic search
//Author:Parveen Sharma
//Date:31.10.2008


// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
    public function makeEmployeeAcademicSearch($showSections = false,$preFix='',$frm='') {

        global $REQUEST_DATA;
        $departmentArr = explode(",", $REQUEST_DATA['departmentId']);
        $designationArr = explode(",", $REQUEST_DATA['designationId']);

        //detecting IE6 browser
        $isIE6=$this->isIE6Browser();
        /*
          IE :
          <td valign='top' colspan='1' class=''  align='left'><b>Institute Name</b></td>
                        <td valign='top' colspan='1' align='left' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                            <select multiple id='instituteId' name='instituteId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getInstituteData()."
                            </select><br>
                            &nbsp;&nbsp;Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"instituteId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"instituteId[]\",\"None\",\"$frm\");'>None</a></nobr>
                        </td>

          FF :
           <td valign='top' colspan='1' class=''  align='left'><b>Institute Name</b></td>
                        <td valign='top' colspan='1' align='left' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>&nbsp;
                        <div id='instituteContainerDiv' style='display:inline;'>
                            <select multiple id='instituteId' name='instituteId[]' size='5' style='width:200px'>".$this->getInstituteData()."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='instituteD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='instituteD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='instituteD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"instituteId\",\"instituteD1\",\"instituteContainerDiv\",\"instituteD3\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
        */
      if($isIE6==1){
        $employeeAcademicSearch = "
                    <tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' align='left' nowrap><B>Academic criteria&nbsp;</B>
                        </td>
                        <td colspan='5' align='left'>
                           <b>:&nbsp;</b><a class='allReportLink' href='javascript:showHide(\"academic\");'><span id='academic'>Expand</span></a>
                        </td>
                    </tr>
                    <tr height='5'></tr>
                    <tr id='academic1' style='display:none;'>

                        <td valign='top' colspan='1' class=''  align='left'><nobr><b>Department</b></nobr></td>
                        <td valign='middle' colspan='1'  align='left' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                            <select multiple id='departmentId' name='departmentId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getDepartmentData($departmentArr)."
                            </select><br>Select&nbsp;&nbsp;Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"departmentId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"departmentId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>Designation</b></nobr></td>
                        <td valign='middle' colspan='1' align='left' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                            <select multiple id='designationId' name='designationId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getDesignationData($designationArr)."
                            </select><br>&nbsp;&nbsp;Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"designationId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"designationId[]\",\"None\",\"$frm\");'>None</a></nobr>
                        </td>
                    </tr>
                    <tr id='academic2' style='display:none;'>
						<td valign='top' colspan='1' class='' align='left'><b>Qualification&nbsp;</b></td>
						<td valign='top' colspan='1' class=''>
							<nobr><b>:</b> <input type='text' class='selectfield' name='qualification' id='qualification' value='".trim($REQUEST_DATA['qualification'])."' style='width:200px' /></nobr>
						</td>
						<td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>Role Name</b></nobr></td>
						<td valign='middle' colspan='1' align='left' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
							<select id='roleName' name='roleName' class='htmlElement2' style='width:200px'><option value=''>Select</option>".$this->getRoleData($REQUEST_DATA['roleName'],'WHERE roleId NOT IN (1,3,4)')."</select>
						</td>
                    </tr>";
      }
                    /*   <td valign='top' colspan='1' class=''  align='right'><b>Univ :&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='universityId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr()."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\");'>None</a>
                        </td>
                        <td valign='top' colspan='3' class=''></td>
                     </tr>*/
      else{
            $employeeAcademicSearch = "
                    <tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' align='left' nowrap><B>Academic criteria&nbsp;</B>
                        </td>
                        <td colspan='5' align='left'>
                           <b>:&nbsp;</b><a class='allReportLink' href='javascript:showHide(\"academic\",2);'><span id='academic'>Expand</span></a>
                        </td>
                    </tr>
                    <tr height='5'></tr>
                    <tr id='academic1' style='display:none;'>

                        <td valign='top' colspan='1' class=''  align='left'><nobr><b>Department</b></nobr></td>
                        <td valign='top' colspan='1'  align='left' class=''><nobr><b><span style='vertical-align:top;padding-right:2px;'>:</span></b>
                        <div id='departmentContainerDiv' style='display:inline;'>
                            <select multiple id='departmentId' name='departmentId[]' size='5' style='height:20px;width:222px;'>".$this->getDepartmentData($departmentArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='departmentD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='departmentD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='departmentD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"departmentId\",\"departmentD1\",\"departmentContainerDiv\",\"departmentD3\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                        <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>Designation</b></nobr></td>
                        <td valign='top' colspan='1' align='left' class=''><nobr><b><span style='vertical-align:top;padding-right:2px;'>:</span></b>
                        <div id='designationContainerDiv' style='display:inline;'>
                            <select multiple id='designationId' name='designationId[]' size='5' style='height:20px;width:289px;'>".$this->getDesignationData($designationArr)."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='designationD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='designationD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='designationD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"designationId\",\"designationD1\",\"designationContainerDiv\",\"designationD3\");' />
                         </td>
                         </tr>
                        </table>
                        </div>
                        </td>
                    </tr>
                    <tr id='academic2' style='display:none;'>
                     <td valign='top' colspan='1' class='' align='left'><b>Qualification&nbsp;</b></td>
                    <td valign='top' colspan='1' class=''>
                    <nobr><b>:</b>&nbsp;<input type='text' class='selectfield' name='qualification' id='qualification' value='".trim($REQUEST_DATA['qualification'])."' style='width:225px' /></nobr>
                     </td>
					 <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>Role Name</b></nobr></td>
                     <td valign='middle' colspan='1' align='left' class='' style='padding-left:2px'><nobr><b><span style='vertical-align:top;'>:</span></b>
                        <select id='roleName' name='roleName' class='htmlElement2' style='width:292px'><option value=''>Select</option>".$this->getRoleData($REQUEST_DATA['roleName'],'WHERE roleId NOT IN (1,3,4)')."</select>
                     </td>
                    </tr>";

      }

            return $employeeAcademicSearch;
        }



//----------------------------------------------------------------------------
//Purpose: make employee filter for address search
//Author:Parveen Sharma
//Date:31.10.2008


// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeEmployeeAddressSearch($preFix='',$frm='') {
          global $REQUEST_DATA;

          $cityArr = explode(",", $REQUEST_DATA['cityId']);
          $stateArr = explode(",", $REQUEST_DATA['stateId']);
          $countryArr = explode(",", $REQUEST_DATA['countryId']);
          //detecting IE6 browser
          $isIE6=$this->isIE6Browser();

          if($isIE6==1){
            $employeeAddressSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' align='left'>
                        <B>Address criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='5'  align='left'>
                        <b>:&nbsp;</b><a class='allReportLink' href='javascript:showHide(\"address\");'><span id='address'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='address1' style='display:none;'>
                   <td valign='top' colspan='1' class='' align='left'><b>City &nbsp; </b></td>
                    <td valign='top' align='left' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                        <select multiple id='cityId' name='cityId[]' size='5' class='htmlElement2' style='height:20px'>".$this->getCityData($cityArr)."
                        </select><br>&nbsp;&nbsp;Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"None\",\"$frm\");'>None</a></nobr>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>State </b></nobr></td>
                    <td valign='top' align='left' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                        <select multiple id='stateId' name='stateId[]' size='5' class='htmlElement2' style='height:20px'>".$this->getStatesData($stateArr)."
                        </select><br>&nbsp;&nbsp;Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"None\",\"$frm\");'>None</a></nobr>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>Country</b></nobr></td>
                    <td valign='top' align='left' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                        <select multiple id='countryId' name='countryId[]' size='5' class='htmlElement2' style='height:20px' >".$this->getCountriesData($countryArr)."
                        </select><br>&nbsp;&nbsp;Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"None\",\"$frm\");'>None</a></nobr>
                    </td>
                </tr>
            ";
          }
         else{
           $employeeAddressSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' align='left'>
                        <B>Address criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='5'  align='left'>
                        <b>:&nbsp;</b><a class='allReportLink' href='javascript:showHide(\"address\",2);'><span id='address'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='address1' style='display:none;'>
                   <td valign='top' colspan='1' class='' align='left'><b>City &nbsp; </b></td>
                    <td valign='top' align='left' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                    <div id='cityContainerDiv' style='display:inline;'>
                        <select multiple id='cityId' name='cityId[]' size='5'  style='height:20px;width:222px'>".$this->getCityData($cityArr)."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='cityD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='cityD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='cityD3' width='95%' valign='top' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"cityId\",\"cityD1\",\"cityContainerDiv\",\"cityD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>State</b></nobr></td>
                    <td valign='top' align='left' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                     <div id='stateContainerDiv' style='display:inline;'>
                        <select multiple id='stateId' name='stateId[]' size='5' style='height:20px;width:292px;'>".$this->getStatesData($stateArr)."
                        </select>
                     </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='stateD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='stateD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                        <tr>
                         <td id='stateD3' width='95%' valign='top' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>
                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"stateId\",\"stateD1\",\"stateContainerDiv\",\"stateD3\");' />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>





                   <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>Country</b></nobr></td>
                    <td valign='top' align='left' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                     <div id='countryContainerDiv' style='display:inline;'>

                        <select multiple id='countryId' name='countryId[]' size='5' style='height:20px;width:192px;'>".$this->getCountriesData($countryArr)."
                        </select>
                     </div>

                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='countryD1'></div>
                    <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='countryD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >

                        <tr>
                         <td id='countryD3' width='95%' valign='top' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                         <td width='5%'>

                          <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"countryId\",\"countryD1\",\"countryContainerDiv\",\"countryD3\");' />
                         </td>
                         </tr>
                        </table>

                     </div>
                    </td>
                </tr>
            ";
         }

            return $employeeAddressSearch;
        }




//----------------------------------------------------------------------------
//Purpose: make student filter for misc search
//Author:Parveen Sharma
//Date:31.10.2008


// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeEmployeeMiscSearch($preFix='') {
            global $REQUEST_DATA;
            $marriedSelected1="";
            $marriedSelected2="";
            if($REQUEST_DATA['isMarried']=='1')
             $marriedSelected1="SELECTED";
             if($REQUEST_DATA['isMarried']=='0')
              $marriedSelected2="SELECTED";

            $teachingSelected1="";
            $teachingSelected2="";
            if($REQUEST_DATA['teachEmployee']=='1')
             $teachingSelected1="SELECTED";
             if($REQUEST_DATA['teachEmployee']=='0')
              $teachingSelected2="SELECTED";

            $employeeMiscSearch = "
                <tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' align='left'>
                        <B>Misc. criteria&nbsp;</B>
                    </td>
                    <td valign='top' colspan='5'  align='left'>
                       <b>:&nbsp;</b><a class='allReportLink' href='javascript:showHide(\"miscEmployee\");'><span id='miscEmployee'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='misc1' style='display:none;'>
                    <td valign='middle' colspan='1' style='width:50px' class='' align='left'>
                    <nobr><b>Married</b></nobr></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                       <b>:&nbsp;</b><select name='isMarried' id='isMarried' class='selectfield' style='width:100px' >
                            <option value='' selected>Select</option>
                            <option value='1' $marriedSelected1>Yes</option>
                            <option value='0' $marriedSelected2>No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>
                        Joining &nbsp;From &nbsp;</b></nobr>
                    </td>
                    <td valign='top' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                       <select size='1' name='joiningYearF' id='joiningYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear(trim($REQUEST_DATA['joiningYearF']))."</select>
                        <select size='1' name='joiningMonthF' id='joiningMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth(trim($REQUEST_DATA['joiningYearF']))."</select>
                        <select size='1' name='joiningDateF' id='joiningDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate(trim($REQUEST_DATA['joiningDateF']))."</select></nobr>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'>
                        <nobr><b>To </B></nobr>
                    </td>
                    <td valign='top' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                       <select size='1' name='joiningYearT' id='joiningYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear(trim($REQUEST_DATA['joiningYearT']))."</select>
                        <select size='1' name='joiningMonthT' id='joiningMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth(trim($REQUEST_DATA['joiningMonthT']))."</select>
                        <select size='1' name='joiningDateT' id='joiningDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate(trim($REQUEST_DATA['joiningDateT']))."</select>
                        </nobr>
                    </td>
                </tr>
                 <tr id='misc2' style='display:none;'>
                  <td valign='middle' colspan='1' style='width:50px' class='' align='left'>
                    <nobr><b>Teaching Emp.</b></nobr></td>
                    <td valign='middle' colspan='1' class='' align='left'>

                        <b>:&nbsp;</b><select name='teachEmployee' id='teachEmployee' class='selectfield' style='width:100px' >
                            <option value='' selected>Select</option>
                            <option value='1' $teachingSelected1>Yes</option>
                            <option value='0' $teachingSelected2>No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'><nobr><b>
                        Leaving &nbsp;From &nbsp;</b></nobr>
                    </td>
                    <td valign='top' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                       <select size='1' name='leavingYearF' id='leavingYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear(trim($REQUEST_DATA['leavingYearF']))."</select>
                        <select size='1' name='leavingMonthF' id='leavingMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth(trim($REQUEST_DATA['leavingMonthF']))."</select>
                        <select size='1' name='leavingDateF' id='leavingDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate(trim($REQUEST_DATA['leavingDateF']))."</select>
                        </nobr>
                    </td>
                    <td valign='top' colspan='1' class='' align='left' style='padding-left:40px'>
                        <nobr><b>To </b>
                    </td>
                    <td valign='top' colspan='1' class=''><nobr><b><span style='vertical-align:top;'>:</span></b>
                       <select size='1' name='leavingYearT' id='leavingYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear(trim($REQUEST_DATA['leavingYearT']))."</select>
                        <select size='1' name='leavingMonthT' id='leavingMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth(trim($REQUEST_DATA['leavingMonthT']))."</select>
                        <select size='1' name='leavingDateT' id='leavingDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate(trim($REQUEST_DATA['leavingDateT']))."</select>
                        </nobr>
                    </td>
                </tr>
                <tr id='misc3' style='display:none;'>
                </tr>
            ";
            return $employeeMiscSearch;
        }



//----------------------------------------------------------------------------
//Purpose: make employee filter for academic search
//Author:Parveen Sharma
//Date:31.10.2008
//Modified By:Dipanjan Bhattacharjee
//Date:30.12.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
    public function makeEmployeeAcademicSearch_feedback($showSections = false,$preFix='',$frm='') {
        $rowStr='';
        //detecting IE6 browser
        $isIE6=$this->isIE6Browser();
        if($isIE6){
        if(trim($preFix)!=''){
            $rowStr='<tr class="showHideRow">
                        <td valign="top" colspan="1" class="" align="right"><B>Academic :&nbsp;</B>
                        </td>
                        <td colspan="5" align="left">
                            <a class="allReportLink" href=javascript:showHideAdvanced("'.$preFix.'","academic","address","miscEmployee");>
                            <span id="'.$preFix.'academic">Expand</span></a>
                        </td>
                    </tr>
                    <tr height="5"></tr>
                   <tr id="'.$preFix.'academic1" style="display:none;">';
        }
        else{
            $rowStr="<tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' align='right'><B>Academic :&nbsp;</B>
                        </td>
                        <td colspan='5' align='left'>
                            <a class='allReportLink' href='javascript:showHide(\"academic\");'><span id='academic'>Expand</span></a>
                        </td>
                    </tr>
                    <tr height='5'></tr>
                    <tr id='academic1' style='display:none;'>";
        }
        $employeeAcademicSearch = "
                            $rowStr
                           <td valign='top' colspan='1' class=''  align='right'><b>Designation:&nbsp; </b></td>
                        <td valign='middle' colspan='1' class='' align='left'>
                            <select multiple name='designationId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getDesignationData()."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"designationId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"designationId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                        <td valign='top' colspan='1' class='' align='right'><b>Qualification:&nbsp; </b></td>
                            <td valign='top' colspan='1' class=''><input type='text' class='selectfield' name='qualification' id='qualification' style='width:200px' />
                        </td>
                        <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Institute :&nbsp; </b></td>
                        <td valign='middle' colspan='1' class='' align='left'>
                            <select multiple name='instituteId[]' size='5' class='inputbox1' style='width:200px;'>".$this->getInstituteData()."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"instituteId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"instituteId[]\",\"None\",\"$frm\");'>None</a>
                        </td>
                    </tr>
                    <tr id='academic2' style='display:none;'>
                    </tr>";

                    /*   <td valign='top' colspan='1' class=''  align='right'><b>Univ :&nbsp; </b></td>
                        <td valign='middle' colspan='1' class=''>
                            <select multiple name='universityId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getUniversityAbbr()."
                            </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"All\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"universityId[]\",\"None\");'>None</a>
                        </td>
                        <td valign='top' colspan='3' class=''></td>
                     </tr>*/
        }
       else{
          if(trim($preFix)!=''){
            $rowStr='<tr class="showHideRow">
                        <td valign="top" colspan="1" class="" align="right"><B>Academic :&nbsp;</B>
                        </td>
                        <td colspan="5" align="left">
                            <a class="allReportLink" href=javascript:showHideAdvanced("'.$preFix.'","academic","address","miscEmployee");>
                            <span id="'.$preFix.'academic">Expand</span></a>
                        </td>
                    </tr>
                    <tr height="5"></tr>
                   <tr id="'.$preFix.'academic1" style="display:none;">';
        }
        else{
            $rowStr="<tr class='showHideRow'>
                        <td valign='top' colspan='1' class='' align='right'><B>Academic :&nbsp;</B>
                        </td>
                        <td colspan='5' align='left'>
                            <a class='allReportLink' href='javascript:showHide(\"academic\",3);'><span id='academic'>Expand</span></a>
                        </td>
                    </tr>

                    <tr height='5'></tr>
                    <tr id='academic1' style='display:none;'>";
        }
        $employeeAcademicSearch = "
                            $rowStr
                        <td valign='top' colspan='1' class=''  align='right'><b>Designation:&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                        <div id='designation_empContainerDiv'>
                            <select multiple name='designationId[]' id='designation_empId' size='5' style='width:196px'>".$this->getDesignationData()."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='designation_empD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='designation_empD2' >
                         <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                          <tr>
                           <td id='designation_empD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                           <td width='5%'>
                            <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"designation_empId\",\"designation_empD1\",\"designation_empContainerDiv\",\"designation_empD3\");' />
                           </td>
                          </tr>
                         </table>
                        </div>
                        </td>
                        <td valign='top' colspan='1' class='' align='right'><b>Qualification:&nbsp; </b></td>
                            <td valign='top' colspan='1' class=''><input type='text' class='selectfield' name='qualification' id='qualification' style='width:200px' />
                        </td>
                        <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Institute :&nbsp; </b></td>
                        <td valign='top' colspan='1' class=''>
                        <div id='institute_empContainerDiv'>
                            <select multiple name='instituteId[]' id='institute_empId' size='5' style='width:196px;'>".$this->getInstituteData()."
                            </select>
                        </div>
                        <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'    id='institute_empD1'></div>
                        <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='institute_empD2' >
                         <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                          <tr>
                           <td id='institute_empD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                           <td width='5%'>
                            <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"institute_empId\",\"institute_empD1\",\"institute_empContainerDiv\",\"institute_empD3\");' />
                           </td>
                          </tr>
                         </table>
                        </div>
                        </td>
                    </tr>";
                    //<tr id='academic2' style='display:none;'></tr>";
            }

                    return $employeeAcademicSearch;
        }



//----------------------------------------------------------------------------
//Purpose: make employee filter for address search
//Author:Parveen Sharma
//Date:31.10.2008
//Modified By:Dipanjan Bhattacharjee
//Date:30.12.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeEmployeeAddressSearch_feedback($preFix='',$frm='') {
        $rowStr='';
        //detecting IE6 browser
        $isIE6=$this->isIE6Browser();

        if($isIE6==1){
        if(trim($preFix)!=''){
            $rowStr='<tr class="showHideRow">
                        <td valign="top" colspan="1" class="" align="right"><B>Address :&nbsp;</B>
                        </td>
                        <td colspan="5" align="left">
                            <a class="allReportLink" href=javascript:showHideAdvanced("'.$preFix.'","address","academic","miscEmployee");>
                            <span id="'.$preFix.'address">Expand</span></a>
                        </td>
                    </tr>
                    <tr height="5"></tr>
                   <tr id="'.$preFix.'address1" style="display:none;">';
        }
        else{
            $rowStr="<tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' align='right'>
                        <B>Address :&nbsp;</B>
                    </td>
                    <td valign='top' colspan='5'  align='left'>
                        <a class='allReportLink' href='javascript:showHide(\"address\");'><span id='address'>Expand</span></a>
                    </td>
                </tr>
               <tr height='5'></tr>
                <tr id='address1' style='display:none;'>";
        }
            $employeeAddressSearch = "
                $rowStr
                    <td valign='top' colspan='1' class=''  align='right'><b>City :&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select multiple name='cityId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getCityData()."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"cityId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                    <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>State :&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select multiple name='stateId[]' size='5' class='htmlElement2' style='width:200px'>".$this->getStatesData()."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"stateId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                    <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Country :&nbsp; </b></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select multiple name='countryId[]' size='5' class='htmlElement2' style='width:200px' >".$this->getCountriesData()."
                        </select><br>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"All\",\"$frm\");'>All</a> / <a class='allReportLink' href='javascript:makeSelection(\"countryId[]\",\"None\",\"$frm\");'>None</a>
                    </td>
                </tr>
            ";
        }
        else{
            if(trim($preFix)!=''){
            $rowStr='<tr class="showHideRow">
                        <td valign="top" colspan="1" class="" align="right"><B>Address :&nbsp;</B>
                        </td>
                        <td colspan="5" align="left">
                            <a class="allReportLink" href=javascript:showHideAdvanced("'.$preFix.'","address","academic","miscEmployee");>
                            <span id="'.$preFix.'address">Expand</span></a>
                        </td>
                    </tr>
                    <tr height="5"></tr>
                   <tr id="'.$preFix.'address1" style="display:none;">';
            }
           else{
            $rowStr="<tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' align='right'>
                        <B>Address :&nbsp;</B>
                    </td>
                    <td valign='top' colspan='5'  align='left'>
                        <a class='allReportLink' href='javascript:showHide(\"address\",3);'><span id='address'>Expand</span></a>
                    </td>
                </tr>
               <tr height='5'></tr>
                <tr id='address1' style='display:none;'>";
            }
            $employeeAddressSearch = "
                $rowStr
                    <td valign='top' colspan='1' class=''  align='right'><b>City :&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                    <div id='city_empContainerDiv'>
                        <select multiple name='cityId[]' id='city_empId' size='5' style='width:196px'>".$this->getCityData()."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'     id='city_empD1'></div>
                     <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='city_empD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                         <tr>
                          <td id='city_empD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                          <td width='5%'>
                            <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"city_empId\",\"city_empD1\",\"city_empContainerDiv\",\"city_empD3\");' />
                          </td>
                         </tr>
                        </table>
                      </div>
                    </td>
                    <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>State :&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                    <div id='state_empContainerDiv'>
                        <select multiple name='stateId[]' id='state_empId' size='5' style='width:196px'>".$this->getStatesData()."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'     id='state_empD1'></div>
                     <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='state_empD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                         <tr>
                          <td id='state_empD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                          <td width='5%'>
                            <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"state_empId\",\"state_empD1\",\"state_empContainerDiv\",\"state_empD3\");' />
                          </td>
                         </tr>
                        </table>
                      </div>
                    </td>
                    </td>
                    <td valign='top' colspan='1' class=''  align='right'>&nbsp;<b>Country :&nbsp; </b></td>
                    <td valign='top' colspan='1' class=''>
                    <div id='country_empContainerDiv'>
                        <select multiple name='countryId[]' id='country_empId' size='5' style='width:196px' >".$this->getCountriesData()."
                        </select>
                    </div>
                    <div style='display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF'     id='country_empD1'></div>
                     <div style='display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF' class='inputbox' id='country_empD2' >
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%' >
                         <tr>
                          <td id='country_empD3' width='95%' valign='middle' style='padding-left:3px;' class='contenttab_internal_rows'></td>
                          <td width='5%'>
                            <img id='downArrawId' src='".IMG_HTTP_PATH."/down_arrow.gif' style='margin-bottom:0px;' onClick='popupMultiSelectDiv(\"country_empId\",\"country_empD1\",\"country_empContainerDiv\",\"country_empD3\");' />
                          </td>
                         </tr>
                        </table>
                      </div>
                    </td>
                </tr>
            ";
        }

            return $employeeAddressSearch;
        }




//----------------------------------------------------------------------------
//Purpose: make student filter for misc search
//Author:Parveen Sharma
//Date:31.10.2008
//Modified By:Dipanjan Bhattacharjee
//Date:30.12.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function makeEmployeeMiscSearch_feedback($preFix='') {
        $rowStr='';

        if(trim($preFix)!=''){
            $rowStr='<tr class="showHideRow">
                        <td valign="top" colspan="1" class="" align="right"><B>Misc. :&nbsp;</B>
                        </td>
                        <td colspan="5" align="left">
                            <a class="allReportLink" href=javascript:showHideAdvanced("'.$preFix.'","miscEmployee","academic","address");>
                            <span id="'.$preFix.'miscEmployee">Expand</span></a>
                        </td>
                    </tr>
                    <tr height="5"></tr>
                   <tr id="'.$preFix.'miscEmployee1" style="display:none;">';
                   $rowStr2='<tr id="'.$preFix.'miscEmployee2" style="display:none;">';
        }
        else{
            $rowStr="<tr class='showHideRow'>
                    <td valign='top' colspan='1' class='' align='right'>
                        <B>Misc. :&nbsp;</B>
                    </td>
                    <td valign='top' colspan='5'  align='left'>
                        <a class='allReportLink' href='javascript:showHide(\"miscEmployee\");'><span id='miscEmployee'>Expand</span></a>
                    </td>
                </tr>
                <tr height='5'></tr>
                <tr id='misc1' style='display:none;'>";

           $rowStr2="<tr id='misc2' style='display:none;'>";
        }
            $employeeMiscSearch = "
                 $rowStr
                    <td valign='middle' colspan='1'  class='' align='right'>
                    <nobr><b>Married:&nbsp; </b></nobr></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select name='isMarried' id='isMarried' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>
                            <option value='1'>Yes</option>
                            <option value='0'>No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' align='right'>
                        <B>&nbsp;Joining From :&nbsp;</B>
                    </td>
                    <td valign='top' colspan='1' class=''>
                       <select size='1' name='joiningYearF' id='joiningYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear()."</select>
                        <select size='1' name='joiningMonthF' id='joiningMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth()."</select>
                        <select size='1' name='joiningDateF' id='joiningDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate()."</select>
                    </td>
                    <td valign='top' colspan='1' class='' align='right'>
                        <B>To :</B>&nbsp;
                    </td>
                    <td valign='top' colspan='1' class=''>
                       <select size='1' name='joiningYearT' id='joiningYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear()."</select>
                        <select size='1' name='joiningMonthT' id='joiningMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth()."</select>
                        <select size='1' name='joiningDateT' id='joiningDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate()."</select>
                    </td>
                </tr>
                 $rowStr2
                  <td valign='middle' colspan='1' style='width:50px' class='' align='right'>
                    <nobr><b>Teaching Emp.:</b></nobr></td>
                    <td valign='middle' colspan='1' class='' align='left'>
                        <select name='teachEmployee' id='teachEmployee' class='selectfield' style='width:200px' >
                            <option value='' selected>Select</option>
                            <option value='1'>Yes</option>
                            <option value='0'>No</option>
                        </select>
                    </td>
                    <td valign='top' colspan='1' class='' align='right'>
                        <nobr><B>Leaving From :&nbsp;</B></nobr>
                    </td>
                    <td valign='top' colspan='1' class=''>
                       <select size='1' name='leavingYearF' id='leavingYearF' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear()."</select>
                        <select size='1' name='leavingMonthF' id='leavingMonthF' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth()."</select>
                        <select size='1' name='leavingDateF' id='leavingDateF' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate()."</select>
                    </td>
                    <td valign='top' colspan='1' class='' align='right'>
                        <B>To :</B>&nbsp;
                    </td>
                    <td valign='top' colspan='1' class=''>
                       <select size='1' name='leavingYearT' id='leavingYearT' class='htmlElement' style='width:67px;'>
                        <option value=''>Year</option>".$this->getAdmissionYear()."</select>
                        <select size='1' name='leavingMonthT' id='leavingMonthT' class='htmlElement' style='width:67px;'>
                        <option value=''>Month</option>".$this->getBirthMonth()."</select>
                        <select size='1' name='leavingDateT' id='leavingDateT' class='htmlElement' style='width:60px;'>
                        <option value=''>Day</option>".$this->getBirthDate()."</select>
                    </td>
                </tr>
                <tr id='misc3' style='display:none;'>
                </tr>
            ";
            return $employeeMiscSearch;
        }

//----------------------------------------------------------------------------
//Purpose: fetch list of study period name
//Author:Jaineesh
//Date:31-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getStudyPeriodName($studentId,$selected='') {
            if(trim($studentId)==''){
                return '';
            }
			$results = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['periodName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['periodName']).'</option>';
                }
			}
			return $returnValues;
		}
	}

//----------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF RESOURCE CATEGORIES
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------

   public function getResourceCategoryData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getResourceCategory($condition='');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['resourceTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['resourceTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['resourceName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['resourceTypeId'].'">'.strip_slashes($results[$i]['resourceName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

	public function getRoles($condition='') {
        $results = CommonQueryManager::getInstance()->getRoles($condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                    $returnValues .='<option value="'.$results[$i]['roleId'].'">'.strip_slashes($results[$i]['roleName']).'</option>';
            }

        }
        return $returnValues;
	}

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Labels  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFeedBackLabelData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getFeedBackLabel('isActive DESC',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackSurveyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }




    //----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Advanced AnswerSet  IN THE "SELECT" ELEMENT
//

//selected: which element in the select element to be selected
//
// Author :Gurkeerat Sidhu
// Created on : (12.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFeedbackAdvOptionsData($selected='',$condition='') {
         $results = CommonQueryManager::getInstance()->getFeedbackAdvAnswerSet('answerSetName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['answerSetId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['answerSetId'].'"

SELECTED="SELECTED">'.strip_slashes($results[$i]['answerSetName']).'</option>';
                }
                else {
                    $returnValues .='<option

value="'.$results[$i]['answerSetId'].'">'.strip_slashes($results[$i]['answerSetName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Labels  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (17.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFeedBackGradeData($selected='') {
        $results = CommonQueryManager::getInstance()->getFeedBackGrade('  feedbackGradeValue DESC');
        $returnValues = '';
        $returnValues.='<table border="0" cellpadding="0" cellspacing="0"><tr>';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
               $returnValues.='<td>';
                if($results[$i]['feedbackGradeId']==$selected) {
                    $returnValues .='<input type="radio" name="radioGrade" id="'.$results[$i]['feedbackGradeId'].'" value="'.$results[$i]['feedbackGradeId'].'" CHECKED" />'.trim(strip_slashes($results[$i]['feedbackGradeLabel']));
                }
                else {
                    $returnValues .='<input type="radio" name="radioGrade" id="'.$results[$i]['feedbackGradeId'].'" value="'.$results[$i]['feedbackGradeId'].'" " />'.trim(strip_slashes($results[$i]['feedbackGradeLabel']));
                }
                $returnValues.='</td>';
            }

        }
       $returnValues.='</tr></table>';
        return $returnValues;
   }


//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF FeedBack Category  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFeedBackCategoryData($selected='') {
        $results = CommonQueryManager::getInstance()->getFeedBackCategory('feedbackCategoryName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackCategoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackCategoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackCategoryName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackCategoryId'].'">'.strip_slashes($results[$i]['feedbackCategoryName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Financial Years  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFinancialYearData($selected='') {
        $results = CommonQueryManager::getInstance()->getFinancialYear('financialYear DESC');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['financialYearId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['financialYearId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['financialYearLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['financialYearId'].'">'.strip_slashes($results[$i]['financialYearLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Leave Types  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (20.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getLeaveTypeData($selected='') {
        $results = CommonQueryManager::getInstance()->getLeaveType('leaveTitle');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['leaveTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['leaveTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['leaveTitle']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['leaveTypeId'].'">'.strip_slashes($results[$i]['leaveTitle']).'</option>';
                }
            }

        }
        return $returnValues;
   }



//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Departments  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getDepartmentData($selected='') {
        $results = CommonQueryManager::getInstance()->getDepartment('abbr');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['departmentId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['departmentId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['abbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['departmentId'].'">'.strip_slashes($results[$i]['abbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

 //----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee Name
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (17.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getEmployeeNameData($selected='') {
        $results = CommonQueryManager::getInstance()->getEmployeeName('employeeName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }


// this function is used to get a list of feedback survey
// Author :Parveen Sharma
// Created on : (01.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getFeedbackSurvey($selected='') {
        $results = CommonQueryManager::getInstance()->getFeedbackSurveyData();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackSurveyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------
//Purpose: this function is fetch degree of a attendance mark
//Author:Parveen Sharma
//Date:05.04.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
        public function getDegreeAttendanceWithClasses() {
            $results = CommonQueryManager::getInstance()->getDegreeWithCode();

            $returnValues = '';
            if(isset($results) && is_array($results)) {
                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }
            return $returnValues;
        }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL Employee Names
//
// Author :Parveen Sharma
// Created on : (08.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getEmployeeActive($selected='') {
        $results = CommonQueryManager::getInstance()->getEmployee('employeeName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   public function getGroupTypes() {
        $results = CommonQueryManager::getInstance()->getGroupTypes();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['groupTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupTypeId'].'">'.strip_slashes($results[$i]['groupTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
//---------------------------------------------------------------------------
// Purpose: To get the list of period slots
// Params: periodSlotId
// Author :Pushpender Kumar Chauhan
// Created on : (16.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
   public function getPeriodSlot($selected='') {
        $results = CommonQueryManager::getInstance()->getPeriodSlot();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['periodSlotId']==$selected || ($results[$i]['isActive']==1 && $selected=='') ) {
                    $returnValues .='<option value="'.$results[$i]['periodSlotId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['slotName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['periodSlotId'].'">'.strip_slashes($results[$i]['slotName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
// Purpose: To get the list of period slots
// Params: periodSlotId
// Author :Pushpender Kumar Chauhan
// Created on : (16.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
   public function getActivePeriodSlot($selected='') {
        $results = CommonQueryManager::getInstance()->getPeriodSlot(" where isActive = 1");
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['periodSlotId']==$selected || ($results[$i]['isActive']==1 && $selected=='') ) {
                    $returnValues .='<option value="'.$results[$i]['periodSlotId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['slotName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['periodSlotId'].'">'.strip_slashes($results[$i]['slotName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   //---------------------------------------------------------------------------
// Purpose: To get the list of period slots
// Params: periodSlotId
// Author :Pushpender Kumar Chauhan
// Created on : (16.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
   public function getWithoutPeriodSlot($selected='') {
        $results = CommonQueryManager::getInstance()->getPeriodSlot();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['periodSlotId']==$selected || ($results[$i]['isActive']==1 && $selected=='') ) {
                    $returnValues .='<option value="'.$results[$i]['periodSlotId'].'">'.strip_slashes($results[$i]['slotAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['periodSlotId'].'">'.strip_slashes($results[$i]['slotAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF OFFENCES
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattcharjee
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getOffenceData($selected='') {
        $results = CommonQueryManager::getInstance()->getOffence('offenseAbbr');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['offenseId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['offenseId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['offenseAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['offenseId'].'">'.strip_slashes($results[$i]['offenseAbbr']).'</option>';
                }
            }


        }
        return $returnValues;
   }

//----------------------------------------------------------------------------
//Purpose: show employee leave type
//Author:Jaineesh
//Date:25.11.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
	public function getTestTypeCategory($condition='',$selected='') {
        $results = CommonQueryManager::getInstance()->getTestTypeCategoryData($condition,'testTypeName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['leaveId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['testTypeCategoryId'].'">'.strip_slashes($results[$i]['testTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['testTypeCategoryId'].'">'.strip_slashes($results[$i]['testTypeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF subject ACCORDING TO TIME TABLE
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (24.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getSubjectTimeTableData($selected='') {
        $results = CommonQueryManager::getInstance()->getSubjectTimeTable('subjectId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   //-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF hostel room type
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (22.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getHostelRoomTypeData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getHostelRoomType('hostelRoomTypeId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['hostelRoomTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['hostelRoomTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['roomType']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['hostelRoomTypeId'].'">'.strip_slashes($results[$i]['roomType']).'</option>';
                }
            }

        }
        return $returnValues;
   }



//----------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF BUS NAMES  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------
   public function getBusData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getBus(' busNo',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['busId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['busId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['busNo']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['busId'].'">'.strip_slashes($results[$i]['busNo']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUFF NAMES  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------
   public function getTransportStuffData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getTransportStuff(' name',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['stuffId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['stuffId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['name']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['stuffId'].'">'.strip_slashes($results[$i]['name']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Transport Stuff Types
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------

   public function getTransportStuffTypeData($selected='') {
      global $transportStuffTypeArr;
      foreach($transportStuffTypeArr as $key=>$value)
      {
         if($key==$selected)
           $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
         else
           $returnValues .='<option value="'. $key.'">'. $value.'</option>';
      }
      return $returnValues;
   }


   //-------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Bus Repair Type
// Author :Dipanjan Bhattacharjee
// Created on : (09.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------

   public function getBusRepairType($act,$dayVal='') {
      global $busRepairTypeArr;
      echo '<table border="0" cellpadding="0" cellspacing="5" width="300px">';
      echo '<tr><th class="">&nbsp;Action</th><th class="">Due Date</th></tr>';
      foreach($busRepairTypeArr as $key=>$value){
         echo '<tr>';
         echo '<td class="">
                 <input type="checkbox" onclick="dateAdj(this.form.busRepairTypeDueDate_'.$act.$key.',this.checked);" name="busRepairTypeChk" id="busRepairTypeChk'.$key.'" value="'. $key.'"><b>'. $value.'</b>
               </td>';
         echo '<td >'.$this->datePicker2('busRepairTypeDueDate_'.$act.$key,$dayVal).'</td>';
         echo '</tr>';
      }
      echo '</table>';
      return $returnValues;
   }

    public function timeTableDateArray() {
        $dayOfWeek = date('w');

        $y = date("Y");
        $m = date("m");
        $d1  = (date("d")-$dayOfWeek);
        for($i=1; $i<=7; $i++) {
           //date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')+365, date('Y')))
          $dateArr[$i]  = date("Y-m-d", mktime(0, 0, 0, $m, ($d1+$i), $y));
        }
        return $dateArr;
    }

//-----------------------------------------------------------------
//  THIS FUNCTION IS USED TO Show Time table periods columnswise
//
// Author :Parveen Sharma
// Created on : (09.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------
    public function showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,$infoTime='0',$timeTableType=1,$teacherRecordDateArray='') {
	
         global $sessionHandler;
         global $daysArr;

	
         $serverDate = explode('-',date('Y-m-d'));
         $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);

         // $Str variable used for TimeTable Data in Td format.
         // tdHrLine morethan teacher's etc. hr line added
         $dt = $this->timeTableDateArray();
         if($timeTableType==1) {
            $headValue="Days/Periods";
            $start = 1;
            $end=7;
         }
         else
         if($timeTableType==2) {
            $headValue="Date";
            $start = 0;
            $end=count($teacherRecordDateArray)-1;
         }
         $returnStr='<table width="100%" border="0"  class="reportTableBorder">
                     <tr>
                     <td align="left">
                      <table width="99%" border="1" align="left" cellspacing="0" cellpadding="0" class="reportTableBorder">
                      <tr class="rowheading">
                            <td width="5%" valign="middle" align="center" style="font-size:12px"><b>'.$headValue.'</b></td>';
         for($m=0;$m<count($periodArray);$m++)  {
             $pArray1 = explode(':',$periodArray[$m]['psTime']);
             $pArray2 = explode(':',$periodArray[$m]['peTime']);

             $pStart = $pArray1[0].':'.$pArray1[1].' '.substr($pArray1[2],strlen($pArray1[2])-2,2);
             $pEnd = $pArray2[0].':'.$pArray2[1].' '.substr($pArray2[2],strlen($pArray2[2])-2,2);

             $periodArr = strip_slashes($periodArray[$m]['periodNumber'])."<br>".$pStart."<br>";
             $periodArr .= $pEnd."<br>";
             $returnStr .='<td valign="middle" align="center" width="10%" style="font-size:12px"><b>'.$periodArr.'</b></td>';
         }
         $returnStr .='</tr>';

         $k=0;
         $recordArray = count($teacherRecordArray);
         for($i=$start;$i<=$end;$i++) {
           $bg = $bg =='trow0' ? 'trow1' : 'trow0';
           $returnStr .= '<tr class='.$bg.'>';
           if($timeTableType==1) {
              $returnStr .= '<td valign="middle" align="center" class="dataFont">'.$daysArr[$i].'</td>';
           }
           else
           if($timeTableType==2) {
             $tfromDate = $teacherRecordDateArray[$i]['fromDate'];
             $returnStr .= '<td valign="middle" align="center" class="dataFont"><nobr>'.UtilityManager::formatDate($tfromDate).'</nobr></td>';
           }
           $alignArr = array();
           for($j=0;$j<count($periodArray);$j++) {
           	 $hrCount =0;
                 $periodArr = strip_slashes($periodArray[$j]['periodNumber']);
                 $str='';
                 $hrLineAdd='';
                 while($k < $recordArray) {
                        if($timeTableType==1) { // Weekly Check
                           $tRecordArray = $teacherRecordArray[$k]['daysOfWeek'];
                           $comp =$i;
                           $ttfromDate = $dt[$i];
                        }
                        else
                        if($timeTableType==2) { // Daily Check
                           $ttfromDate = $teacherRecordArray[$k]['fromDate'];
                           $tRecordArray = $teacherRecordArray[$k]['fromDate'];
                           $comp=$tfromDate;
                        }
                        if($tRecordArray==$comp && $teacherRecordArray[$k]['periodNumber']==$periodArr) {
                        
                            if($teacherRecordArray[$k]['adjustmentType']=='3') {
                               $str .= "<span style='background-color:#FF0000; color:#FFFFFF; background-repeat:repeat-x'>";
                            }
                            else {
                               $str .= "<span style='color:#000000'>";
                            }
                            if($hrLineAdd=='0') {
                              $str .= "<hr>";
                              $hrCount++;
                            }
                            
                            foreach($alignArr as $key =>$value){
                            	if($teacherRecordArray[$k]['subjectCode']."-".$teacherRecordArray[$k]['employeeName'] == $value){
                            		$temp = $hrCount;
                            		for($h=0;$h<($key -$temp);$h++){
                            			$str .="<p style='height:76px;'><hr>";
                            			$hrCount++;
                            		}
                            	}
                            }
                          
                            $str .= '<span title="'.strip_slashes($teacherRecordArray[$k]['subjectName']).'('.strip_slashes($teacherRecordArray[$k]['subjectCode']).')">'.strip_slashes($teacherRecordArray[$k]['subjectCode']).'</span><br>';
                            $quiz='';
                            if ($teacherRecordArray[$k]['activity'] == QUIZ) {
                               $quiz = " <b><font color='red'>Quiz</font></b>";
                            }
                            $str .= strip_slashes($teacherRecordArray[$k]['groupShort']).$quiz.'<br>';
                            $str .= strip_slashes($teacherRecordArray[$k]['roomAbbreviation']).'<br>';
                            $str .= strip_slashes($teacherRecordArray[$k]['employeeName']);
                            if($infoTime=='1') {
                               $query1='?fromDate='.$ttfromDate.'&periodId='.$teacherRecordArray[$k]['periodId'].'&classId='.$teacherRecordArray[$k]['classId'].'&subjectId='.$teacherRecordArray[$k]['subjectId'].'&groupId='.$teacherRecordArray[$k]['groupId'];
                               $query2='?class='.$teacherRecordArray[$k]['classId'].'&subject='.$teacherRecordArray[$k]['subjectId'].'&group='.$teacherRecordArray[$k]['groupId'];
                               if($teacherRecordArray[$k]['hasAttendance']==1) {
                                  if($timeTableType==1) {
                                    $str .= "<br><a class='allReportLink' title='Daily Attendance Information' href='listDailyAttendance.php".$query1."'><font color=blue>Daily Attendance</a>
                                             <br><a class='allReportLink' title='Student Information' href='searchStudent.php".$query2."'><font color=blue>Student Info</a>";
                                  }
                                  else {
                                     $sDate = explode('-',$ttfromDate);
                                     $start_date=gregoriantojd($sDate[1], $sDate[2], $sDate[0]);
                                     $diff = $start_date - $end_date;
                                     if($timeTableType==2 && $diff <= 0 ) {
                                        $str .= "<br><a class='allReportLink' title='Daily Attendance Information' href='listDailyAttendance.php".$query1."'><font color=blue>Daily Attendance</a>
                                                  <br><a class='allReportLink' title='Student Information' href='searchStudent.php".$query2."'><font color=blue>Student Info</a>";
                                     }
                                  }
                               }
                            }
                            if($teacherRecordArray[$k]['adjustEmpName']!='') {
                               $str .= " (Adjustment)".$teacherRecordArray[$k]['adjustEmpName'];
                            }
                            $str .= "<br>".strip_slashes($teacherRecordArray[$k]['className']);
                            $str .= "</span>";
                            $hrLineAdd="0";
                            $alignArr[$hrCount] =  $teacherRecordArray[$k]['subjectCode']."-".$teacherRecordArray[$k]['employeeName'];
                            $k++;
                       }
                       if($timeTableType==1) { // Weekly Check
                         if($teacherRecordArray[$k]['daysOfWeek']!=$i || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                            break;
                       }
                       else
                       if($timeTableType==2) { // Daily Check
                         if($teacherRecordArray[$k]['fromDate']!=$tfromDate || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                           break;
                      }
              } 
            
              $returnStr .=  '<td valign="top" valign="middle" align="center" class="dataFont">';
              if($str == '') {
                 $returnStr .=  "&nbsp;" ;
              }
              else {
                 $returnStr .=  $str;
              }
              $returnStr .=  '</td>';
          }
          $returnStr .=  '</tr>';
       }
       $returnStr .=  '</table></td></tr></table>';
       return trim($returnStr);
    }


//-----------------------------------------------------------------
//  THIS FUNCTION IS USED TO Show Time table periods Rowswise
//
// Author :Parveen Sharma
// Created on : (09.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------
    public function showTimeTablePeriodsRows($teacherRecordArray,$periodArray,$infoTime='0',$timeTableType=1,$teacherRecordDateArray='') {

         global $sessionHandler;
         global $daysArr;

         $serverDate = explode('-',date('Y-m-d'));
         $end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);

         // $Str variable used for TimeTable Data in Td format.
         // tdHrLine morethan teacher's etc. hr line added
         $dt = $this->timeTableDateArray();
         if($timeTableType==1) {
            $headValue="Days";
            $start = 1;
            $end=7;
         }
         else
         if($timeTableType==2) {
            $headValue="Date";
            $start = 0;
            $end=count($teacherRecordDateArray)-1;
         }

         $returnStr='<table width="100%" border="0"  class="reportTableBorder">
                     <tr>
                     <td align="left">
                      <table width="99%" border="1" align="left" cellspacing="0" cellpadding="0" class="reportTableBorder">
                      <tr class="rowheading">
                            <td width="5%" valign="middle" align="center" style="font-size:12px"><b>Periods</b></td>';
         for($m=$start;$m<=$end;$m++)  {
            if($timeTableType==1) {
              $returnStr .='<td valign="middle" align="center" width="10%" style="font-size:12px"><b>'.$daysArr[$m].'</b></td>';
            }
            else
            if($timeTableType==2)  {
              $tfromDate = $teacherRecordDateArray[$m]['fromDate'];
              $returnStr .= '<td valign="middle" align="center" class="dataFont"><nobr>'.UtilityManager::formatDate($tfromDate).'</nobr></td>';
            }
         }
         $returnStr .='</tr>';

         $k=0;
         $recordArray = count($teacherRecordArray);
         for($i=0;$i<count($periodArray);$i++) {
           $bg = $bg =='trow0' ? 'trow1' : 'trow0';

           $pArray1 = explode(':',$periodArray[$i]['psTime']);
           $pArray2 = explode(':',$periodArray[$i]['peTime']);

           $pStart = $pArray1[0].':'.$pArray1[1].' '.substr($pArray1[2],strlen($pArray1[2])-2,2);
           $pEnd = $pArray2[0].':'.$pArray2[1].' '.substr($pArray2[2],strlen($pArray2[2])-2,2);

           $periodArr = strip_slashes($periodArray[$i]['periodNumber']);
           $returnStr .= '<tr class='.$bg.'>';
           $returnStr .= '<td valign="middle" align="center" class="dataFont">'.$periodArr."<br>".$pStart."<br>".$pEnd.'</td>';
           for($j=$start;$j<=$end;$j++) {
                 $str='';
                 $hrLineAdd='';
                 while($k < $recordArray) {
                    if($timeTableType==1) { // Weekly Check
                       $tRecordArray = $teacherRecordArray[$k]['daysOfWeek'];
                       $comp =$j;
                       $ttFromDate = $dt[$j];
                    }
                    else
                    if($timeTableType==2) { // Daily Check
                       $ttFromDate = $teacherRecordArray[$k]['fromDate'];
                       $tRecordArray = $teacherRecordArray[$k]['fromDate'];
                       $comp = $teacherRecordDateArray[$j]['fromDate'];
                    }
                    if($tRecordArray==$comp && $teacherRecordArray[$k]['periodNumber']==$periodArr) {
                        if($teacherRecordArray[$k]['adjustmentType']=='3') {
                           $str .= "<span style='background-color:#FF0000'; color:'white'>";
                        }
                        else {
                           $str .= "<span style='color:'black'>";
                        }
                        if($hrLineAdd=='0') {
                          $str .= "<hr>";
                        }
                        $str .=  strip_slashes($teacherRecordArray[$k]['subjectCode']).'<br>';
                        $quiz='';
                        if ($teacherRecordArray[$k]['activity'] == QUIZ) {
                           $quiz = " <b><font color='red'>Quiz</font></b>";
                        }
                        $str .= strip_slashes($teacherRecordArray[$k]['groupShort']).$quiz.'<br>';
                        $str .= strip_slashes($teacherRecordArray[$k]['roomAbbreviation']).'<br>';
                        $str .= strip_slashes($teacherRecordArray[$k]['employeeName']);
                        if($infoTime=='1') {
                           $query1='?fromDate='.$ttFromDate.'&periodId='.$teacherRecordArray[$k]['periodId'].'&classId='.$teacherRecordArray[$k]['classId'].'&subjectId='.$teacherRecordArray[$k]['subjectId'].'&groupId='.$teacherRecordArray[$k]['groupId'];
                           $query2='?class='.$teacherRecordArray[$k]['classId'].'&subject='.$teacherRecordArray[$k]['subjectId'].'&group='.$teacherRecordArray[$k]['groupId'];
                           if($teacherRecordArray[$k]['hasAttendance']==1) {
                              if($timeTableType==1) { // Weekly Check
                                 $str .= "<br><a class='allReportLink' title='Daily Attendance Information' href='listDailyAttendance.php".$query1."'><font color=blue>Daily Attendance</font></a>
                                         <br><a class='allReportLink' title='Student Information' href='searchStudent.php".$query2."'><font color=blue>Student Info</a>";
                              }
                              else {
                                 $sDate = explode('-',$ttfromDate);
                                 $start_date=gregoriantojd($sDate[1], $sDate[2], $sDate[0]);
                                 $diff = $start_date - $end_date;
                                 if($timeTableType==2 && $diff <= 0 ) {
                                    $str .= "<br><a class='allReportLink' title='Daily Attendance Information' href='listDailyAttendance.php".$query1."'><font color=blue>Daily Attendance</a>
                                              <br><a class='allReportLink' title='Student Information' href='searchStudent.php".$query2."'><font color=blue>Student Info</a>";
                                 }
                              }
                           }

                        }
                        $str .= "<br>".strip_slashes($teacherRecordArray[$k]['className']);
                        $str .= "</span>";
                        $hrLineAdd="0";
                        $k++;
                   }
                   if($timeTableType==1) {  // Weekly Check
                     if($teacherRecordArray[$k]['daysOfWeek']!=$j || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                       break;
                   }
                   else
                   if($timeTableType==2) {  // Daily Check
                     if($teacherRecordArray[$k]['fromDate']!=$comp || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                        break;
                   }
              }
              $returnStr .=  '<td valign="top" valign="middle" align="center" class="dataFont">';
              if($str == '') {
                 $returnStr .=  "&nbsp;" ;
              }
              else {
                 $returnStr .=  $str;
              }
              $returnStr .=  '</td>';
          }
          $returnStr .=  '</tr>';
       }
       $returnStr .=  '</table></td></tr></table>';
       return trim($returnStr);
    }

//---------------------------------------------------------------------------

//  THIS FUNCTION IS USED TO GET A LIST OF Time Table View
//
//selected: which element in the select element to be selected
//
// Author :Parveen Sharma
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getTimeTableView($selected='') {

       global $timeTableFormatArr;

        $returnValues = '';
         if(isset($timeTableFormatArr) && is_array($timeTableFormatArr)) {
            $count = count($timeTableFormatArr);
             foreach($timeTableFormatArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF SUGGESTION
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getSuggestion($selected='') {
    global $suggestionArr;
    /*
		foreach($suggestionArr as $key=>$value){
		   if($key==$selected){
			   $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
           }
		   else{
			   $returnValues .='<option value="'. $key.'">'. $value.'</option>';
           }
	    }
     */
     $checked="checked='checked'";
     foreach($suggestionArr as $key=>$value){
        $returnValues .= '<input type="radio" name="suggestionSubject" '.$checked.' value="'.$key.'" >'.$value;
        $checked='';
     }
    return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student I-Card Format
//
// Author :Parveen Sharma
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getICardData($selected='') {

       // I-Card  Array
        $icardArr = array("2"=>"I-Card","3"=>"Admit Card","4"=>"Bus Pass");
        $returnValues = '';
         if(isset($icardArr) && is_array($icardArr)) {
            $count = count($icardArr);
             foreach($icardArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Complaint Category
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (28.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getComplaintCategoryData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getComplaintCategory('complaintCategoryId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['complaintCategoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['complaintCategoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['complaintCategoryId'].'">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

 //-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Hostel Student Data
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (28.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getStudentHostelData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getStudentHostel('studentId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['studentId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['studentId'].'" >'.strip_slashes($results[$i]['studentName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['studentId'].'">'.strip_slashes($results[$i]['studentName']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF EMPLOYEE NAME FROM TEMP_EMPLOYEE
//
//orderBy: on which column to sort
//
// Author :Jaineesh
// Created on : (28.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getTempEmployeeName($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getTempEmployee('tempEmployeeId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['tempEmployeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['tempEmployeeId'].'" >'.strip_slashes($results[$i]['tempEmployeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['tempEmployeeId'].'">'.strip_slashes($results[$i]['tempEmployeeName']).'</option>';
                }
            }
        }
        return $returnValues;
   }


 //-------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF temporary employee designation

//
//orderBy: on which column to sort
//
// Author :Gurkeerat Sidhu
// Created on : (29.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getEmpDesignation($selected='') {
        $results = CommonQueryManager::getInstance()->getStatus('tempDesignationId');
        $returnValues = '';
        if(isset($results) && is_array($results)) {

            if(is_array($selected)){

                $count = count($results);
                for($i=0;$i<$count;$i++){

                    if (in_array($results[$i]['tempDesignationId'], $selected))  {
                        $returnValues .='<option value="'.$results[$i]['tempDesignationId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['designationName']).'</option>';
                    }
                    else {
                        $returnValues .='<option value="'.$results[$i]['tempDesignationId'].'">'.strip_slashes($results[$i]['designationName']).'</option>';
                    }
                }
            }
            else{

                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    if($results[$i]['tempDesignationId']==$selected) {
                        $returnValues .='<option value="'.$results[$i]['tempDesignationId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['designationName']).'</option>';
                    }
                    else {
                        $returnValues .='<option value="'.$results[$i]['tempDesignationId'].'">'.strip_slashes($results[$i]['designationName']).'</option>';
                    }
                }
            }
        }
        return $returnValues;
   }




     //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF HOSTEL TYPE
//
//selected: which element in the select element to be selected
//
// Author :Gurkeerat Sidhu
// Created on : (17.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getHostelType($selected='') {
        global $hostelTypeArr;

        $returnValues = '';
         if(isset($hostelTypeArr) && is_array($hostelTypeArr)) {
            $count = count($hostelTypeArr);
             foreach($hostelTypeArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
         }

  //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF TEMPORARY EMPLOYEE STATUS
//
//selected: which element in the select element to be selected
//
// Author :Gurkeerat Sidhu
// Created on : (29.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getTempEmployeeStatus($selected='') {
        global $statusArr;

        $returnValues = '';
         if(isset($statusArr) && is_array($statusArr)) {
            $count = count($statusArr);
             foreach($statusArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee Name
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (17.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getPreviousEmployeeData($selected='',$classId) {
        $results = CommonQueryManager::getInstance()->getPreviousEmployeeName('employeeName',$classId);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

  //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF HOSTEL VISITOR RELATION
//
//selected: which element in the select element to be selected
//
// Author :Gurkeerat Sidhu
// Created on : (18.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getHostelVisitorRel($selected='') {
        global $hostelVisitorRelArr;

        $returnValues = '';
         if(isset($hostelVisitorRelArr) && is_array($hostelVisitorRelArr)) {
            $count = count($hostelVisitorRelArr);
             foreach($hostelVisitorRelArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

 //----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF General FeedBack Labels  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (30.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFeedBackGeneralLabelData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getGeneralFeedBackLabel('isActive DESC',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackSurveyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
            }

        }
        return $returnValues;
   }

      //-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF time table labels
//
// selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (30.09.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
	public function getCurrentDegree($selected='',$condition='') {
		$results = CommonQueryManager::getInstance()->getDegreeLabel($condition);
		$returnValues = '';

		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$classId = $results[$i]['classId'];
				$className = $results[$i]['className'];
				if( ($results[$i]['classId']==$selected) || ($results[$i]['isActive']==1 && $selected=='')) {
					$returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
				}
				else {
					$returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($className).'</option>';
				}
			}
		}
		return $returnValues;
	}


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF UserNames
//orderBy: on which column to sort
// Author :Dipanjan Bhattcharjee
// Created on : (01.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

   public function getUserData($selected='',$conditions='',$field='userName') {
        $results = CommonQueryManager::getInstance()->getUserNameDetailed($field,$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $str = strip_slashes($results[$i][$field]);
                if($results[$i]['userId']==$selected) {
                   $returnValues .='<option value="'.$results[$i]['userId'].'" SELECTED="SELECTED">'.$str.'</option>';
                }
                else {
                   $returnValues .='<option value="'.$results[$i]['userId'].'">'.$str.'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Offense Category
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (14.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getOffenseCategory($selected='') {
        $results = CommonQueryManager::getInstance()->getOffenseCategoryData('offenseName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['offenseId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['offenseId'].'">'.strip_slashes($results[$i]['offenseName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['offenseId'].'">'.strip_slashes($results[$i]['offenseName']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//----------------------------------------------------------------------------------------
// function to check valid email id
//
// Author :Parveen Sharma
// Created on : 05-06-09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function isEmail($str=''){
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $str)){
            return 0;
        } else{
            return 1;
        }
    }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Bus Pass Status
//
// Author :Parveen Sharma
// Created on : (12.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getBusPassStatus($selected='') {
       global $buspassArray;
       foreach($buspassArray as $key=>$value)
       {
           if($key==$selected)
               $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
           else
               $returnValues .='<option value="'. $key.'">'. $value.'</option>';
       }
       return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STATUS
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (03.07.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getStatus($selected='',$mode='') {
        global $statusCategoryArr;
        global $statusCategoryArr2;
        if ($mode==1){
            $useArray=$statusCategoryArr2 ;
        }
        else{
           $useArray=$statusCategoryArr;
        }
        $returnValues = '';
         if(isset($useArray) && is_array($useArray)) {
            $count = count($statusCategoryArr);
             foreach($useArray as $key=>$value) {
                if($key==2) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED" >'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }
        }
        return $returnValues;
       }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Fine Category
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (03.07.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getFineCategory($selected='') {
        $results = CommonQueryManager::getInstance()->getFineCategoryData('fineCategoryAbbr');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['offenseId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['fineCategoryId'].'">'.strip_slashes($results[$i]['fineCategoryAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['fineCategoryId'].'">'.strip_slashes($results[$i]['fineCategoryAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Fine Category for particular role
//  selected: which element in the select element to be selected
//  Author :Rajeev Aggarwal
//  Created on : (06.07.09)
//  Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
   public function getRoleFineCategory($selected='') {   	
        $results = CommonQueryManager::getInstance()->getRoleFineCategoryData('fineCategoryAbbr');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['offenseId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['fineCategoryId'].'">'.strip_slashes($results[$i]['fineCategoryAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['fineCategoryId'].'">'.strip_slashes($results[$i]['fineCategoryAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }
//------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Parent Subject Category Information
// Author :Parveen Sharma
// Created on : (06.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.

//--------------------------------------------------------
 public function getParentSubjectCategoryData($selected='') {
        $results = CommonQueryManager::getInstance()->getParentSubjectCategory('categoryName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectCategoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectCategoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectCategoryId'].'">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

//--------------------------------------------------------
//  THIS FUNCTION IS USED TO Make a select box based upon the array passed to it.
// Author :Parveen Sharma
// Created on : (06.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function makeSelectBox($array, $optionKeyField, $optionValueField, $selectedKey = '') {
	   $returnValues = '';
	   foreach($array as $record) {
		   if ($record[$optionKeyField] == $selectedKey) {
			   $returnValues .='<option value="'.$record[$optionKeyField].'" SELECTED="SELECTED">'.strip_slashes($record[$optionValueField]).'</option>';
		   }
		   else {
			   $returnValues .='<option value="'.$record[$optionKeyField].'">'.strip_slashes($record[$optionValueField]).'</option>';
		   }
	   }
	   return $returnValues;
   }

   public function makeVoucherTypeSelectBox($selected = '') {
	   global $voucherTypeArray;
	   $returnValues = '';
	   foreach($voucherTypeArray as $key => $value) {
		   if ($selected == $key) {
			   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.strip_slashes($value).'</option>';
		   }
		   else {
			   $returnValues .='<option value="'.$key.'">'.strip_slashes($value).'</option>';
		   }
	   }
	   return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee Publishing Scope Value
//
// Author :Parveen Sharma
// Created on : (11.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getEmployeePublisherScopeData($selected='') {
       global $publisherScopeArr;

       foreach($publisherScopeArr as $key=>$value)
       {
           if($key==$selected)
               $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
           else
               $returnValues .='<option value="'. $key.'">'. $value.'</option>';
       }
       return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee Seminar Participation Value
//
// Author :Parveen Sharma
// Created on : (11.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getEmployeeSeminarParticipationData($selected='') {
       global $seminarParticipationArr;

       foreach($seminarParticipationArr as $key=>$value)
       {
           if($key==$selected)
               $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
           else
               $returnValues .='<option value="'. $key.'">'. $value.'</option>';
       }
       return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Batches for current institute
//
// Author :Ajinder Singh
// Created on : (29.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getBatches($selected = '') {
        $results = CommonQueryManager::getInstance()->getBatches('batchName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['batchId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['batchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['batchName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['batchId'].'">'.strip_slashes($results[$i]['batchName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

	public function makeGroupLedgerSelectBox($selected = '') {
		$groupLedgerArray = Array('group'=>'GroupWise', 'ledger'=>'LedgerWise');
		$returnValues = '';
		foreach($groupLedgerArray as $key => $value) {
			if ($selected == $key) {
				$returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.strip_slashes($value).'</option>';
			}
			else {
				$returnValues .='<option value="'.$key.'">'.strip_slashes($value).'</option>';
			}
		}
		return $returnValues;
	}


   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO Parse CSV Values
//
// Author :Parveen Sharma
// Created on : (29.07.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function parseCSVComments($comments) {
      $comments = str_replace('"', '""', $comments);
      $comments = str_ireplace('<br/>', "\n", $comments);
      $comments = str_ireplace('<br>', " ", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
        return '"'.$comments.'"';
      }
      else {
        return $comments.chr(160);
      }
    }

//-----------------------------------------------------------------
//  THIS FUNCTION IS USED TO Show Time table periods columnswise  CSV generate
//
// Author :Parveen Sharma
// Created on : (09.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------

   public function showTimeTablePeriodsColumnsCSV($teacherRecordArray,$periodArray,$infoTime='0',$timeTableType=1,$teacherRecordDateArray='') {

         global $sessionHandler;
         global $daysArr;

         // $Str variable used for TimeTable Data in Td format.
         // tdHrLine morethan teacher's etc. hr line added

         $dt = $this->timeTableDateArray();
         if($timeTableType==1) {
            $headValue="Days";
            $start = 1;
            $end=7;
         }
         else
         if($timeTableType==2) {
            $headValue="Date";
            $start = 0;
            $end=count($teacherRecordDateArray)-1;
         }

         $csv = '';
         for($m=0;$m<count($periodArray);$m++)  {
            if($m==0) {
              $csv = $headValue;
            }

            $pArray1 = explode(':',$periodArray[$m]['psTime']);
            $pArray2 = explode(':',$periodArray[$m]['peTime']);

            $pStart = $pArray1[0].':'.$pArray1[1].' '.substr($pArray1[2],strlen($pArray1[2])-2,2);
            $pEnd = $pArray2[0].':'.$pArray2[1].' '.substr($pArray2[2],strlen($pArray2[2])-2,2);

            $periodArrTime  = "\012".$pStart." ".$pEnd;
            $periodArr = strip_slashes($periodArray[$m]['periodNumber']);
            $csv .= ",".$this->parseCSVComments($periodArr.$periodArrTime);
         }
         if($csv!='') {
            $csv .= "\n";
         }

         $k=0;
         $recordArray = count($teacherRecordArray);
         for($i=$start;$i<=$end;$i++) {
           if($timeTableType==1) {
             $csv .= $this->parseCSVComments($daysArr[$i]);
           }
           else
           if($timeTableType==2) {
              $tfromDate = $teacherRecordDateArray[$i]['fromDate'];
              $csv .=  $this->parseCSVComments(UtilityManager::formatDate($tfromDate));
           }
           for($j=0;$j<count($periodArray);$j++) {
                 $periodArr = strip_slashes($periodArray[$j]['periodNumber']);
                 $str="";
                 $hrLineAdd='';
                 while($k < $recordArray) {
                    if($timeTableType==1) { // Weekly Check
                      $tRecordArray = $teacherRecordArray[$k]['daysOfWeek'];
                      $comp =$i;
                    }
                    else
                    if($timeTableType==2) { // Daily Check
                       $tRecordArray = $teacherRecordArray[$k]['fromDate'];
                       $comp=$tfromDate;
                    }
                    if($tRecordArray==$comp && $teacherRecordArray[$k]['periodNumber']==$periodArr) {
                        if($hrLineAdd=='0') {
                          $str .= "\012";
                        }
                        $str .= strip_slashes($teacherRecordArray[$k]['subjectCode'])."\012";
                        $quiz='';
                        if ($teacherRecordArray[$k]['activity'] == QUIZ) {
                           $quiz = " <b><font color='red'>Quiz</font></b>";
                        }
                        $str .= strip_slashes($teacherRecordArray[$k]['groupShort']).$quiz."\012";
                        $str .= strip_slashes($teacherRecordArray[$k]['roomAbbreviation'])."\012";
                        $str .= strip_slashes($teacherRecordArray[$k]['employeeName']);
                        if($infoTime=='1') {
                          $query1='?fromDate='.$dt[$i].'&periodId='.$teacherRecordArray[$k]['periodId'].'&classId='.$teacherRecordArray[$k]['classId'].'&subjectId='.$teacherRecordArray[$k]['subjectId'].'&groupId='.$teacherRecordArray[$k]['groupId'];
                          $query2='?class='.$teacherRecordArray[$k]['classId'].'&subject='.$teacherRecordArray[$k]['subjectId'].'&group='.$teacherRecordArray[$k]['groupId'];
                          if($teacherRecordArray[$k]['hasAttendance']==1) {
                             $str .= "\012<a class='allReportLink' title='Daily Attendance Information' href='listDailyAttendance.php".$query1."'><font color=blue>Daily Attendance</a>
                                       \012<a class='allReportLink' title='Student Information' href='searchStudent.php".$query2."'><font color=blue>Student Info</a>";
                           }
                        }
                        $str .= "\012".strip_slashes($teacherRecordArray[$k]['className']);
                        $hrLineAdd="0";
                        $k++;
                        $str .= "\012";
                   }
                   if($timeTableType==1) { // Weekly Check
                      if($teacherRecordArray[$k]['daysOfWeek']!=$i || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                         break;
                   }
                   else
                   if($timeTableType==2) { // Daily Check
                      if($teacherRecordArray[$k]['fromDate']!=$tfromDate || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                         break;
                  }
              }
              $returnStr .= '<td valign="top" valign="middle" align="center" class="timtd">';
              if($str == '') {
                 $csv .= ",";
              }
              else {
                 $csv .= ",".$this->parseCSVComments($str);
              }
              $returnStr .= '</td>';
          }
          $csv .= "\n";
       }
       return $this->removePHPJS(trim($csv));
    }


//-----------------------------------------------------------------
//  THIS FUNCTION IS USED TO Show Time table periods Rowswise  Generate in CSV
//
// Author :Parveen Sharma
// Created on : (09.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------
    public function showTimeTablePeriodsRowsCSV($teacherRecordArray,$periodArray,$infoTime='0',$timeTableType=1,$teacherRecordDateArray='') {

         global $sessionHandler;
         global $daysArr;

         // $Str variable used for TimeTable Data in Td format.
         // tdHrLine morethan teacher's etc. hr line added
         $dt = $this->timeTableDateArray();
         if($timeTableType==1) {
            $headValue="Days";
            $start = 1;
            $end=7;
         }
         else
         if($timeTableType==2) {
            $headValue="Date";
            $start = 0;
            $end=count($teacherRecordDateArray)-1;
         }
         $returnStr='<table width="100%" border="1" cellspacing="0" cellpadding="0" class="reportTableBorder" >
                      <tr class="rowheading">
                            <td width="5%" valign="middle" align="center" style="font-size:12px"><b>Periods</b></td>';

         for($m=$start;$m<=$end;$m++)  {
            if($m==1) {
              $csv = ' Periods';
            }
            if($timeTableType==1) {
               $periodArr = strip_slashes($daysArr[$m]);
            }
            else
            if($timeTableType==2)  {
              $tfromDate = $teacherRecordDateArray[$m]['fromDate'];
              $periodArr = strip_slashes(UtilityManager::formatDate($tfromDate));
            }
            $csv .= ', '.$this->parseCSVComments($periodArr);
         }
         if($csv!='') {
           $csv .= "\n";
         }

         $k=0;
         $recordArray = count($teacherRecordArray);
         for($i=0;$i<count($periodArray);$i++) {
           $pArray1 = explode(':',$periodArray[$i]['psTime']);
           $pArray2 = explode(':',$periodArray[$i]['peTime']);

           $pStart = $pArray1[0].':'.$pArray1[1].' '.substr($pArray1[2],strlen($pArray1[2])-2,2);
           $pEnd = $pArray2[0].':'.$pArray2[1].' '.substr($pArray2[2],strlen($pArray2[2])-2,2);

           $periodArrTime = "\012".$pStart." ".$pEnd;
           $periodArr = strip_slashes($periodArray[$i]['periodNumber']);
           $csv .= $this->parseCSVComments($periodArr.$periodArrTime);
           for($j=$start;$j<=$end;$j++) {
                 $str='';
                 $hrLineAdd='';
                 while($k < $recordArray) {
                    if($timeTableType==1) { // Weekly Check
                       $tRecordArray = $teacherRecordArray[$k]['daysOfWeek'];
                       $comp =$j;
                    }
                    else
                    if($timeTableType==2) { // Daily Check
                       $tRecordArray = $teacherRecordArray[$k]['fromDate'];
                       $comp = $teacherRecordDateArray[$j]['fromDate'];
                    }
                    if($tRecordArray==$comp && $teacherRecordArray[$k]['periodNumber']==$periodArr) {
                        if($hrLineAdd=='0') {
                          $str .= "\012";
                        }
                        $str .=  strip_slashes($teacherRecordArray[$k]['subjectCode'])."\012";
                        $quiz='';
                        if ($teacherRecordArray[$k]['activity'] == QUIZ) {
                           $quiz = " <b><font color='red'>Quiz</font></b>";
                        }
                        $str .= strip_slashes($teacherRecordArray[$k]['groupShort']).$quiz."\012";
                        $str .= strip_slashes($teacherRecordArray[$k]['roomAbbreviation'])."\012";
                        $str .= strip_slashes($teacherRecordArray[$k]['employeeName']);
                        if($infoTime=='1') {
                          $query1='?fromDate='.$dt[$j].'&periodId='.$teacherRecordArray[$k]['periodId'].'&classId='.$teacherRecordArray[$k]['classId'].'&subjectId='.$teacherRecordArray[$k]['subjectId'].'&groupId='.$teacherRecordArray[$k]['groupId'];
                          $query2='?class='.$teacherRecordArray[$k]['classId'].'&subject='.$teacherRecordArray[$k]['subjectId'].'&group='.$teacherRecordArray[$k]['groupId'];
                          if($teacherRecordArray[$k]['hasAttendance']==1) {
                              $str .= "\012<a class='allReportLink' title='Daily Attendance Information' href='listDailyAttendance.php".$query1."'><font color=blue>Daily Attendance</font></a>
                                       \012<a class='allReportLink' title='Student Information' href='searchStudent.php".$query2."'><font color=blue>Student Info</a>";
                          }
                        }
                        $str .= "\012".strip_slashes($teacherRecordArray[$k]['className']);
                        $hrLineAdd="0";
                        $k++;
                        $str .= "\012";
                   }
                   if($timeTableType==1) {  // Weekly Check
                     if($teacherRecordArray[$k]['daysOfWeek']!=$j || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                       break;
                   }
                   else
                   if($timeTableType==2) {  // Daily Check
                     if($teacherRecordArray[$k]['fromDate']!=$comp || $teacherRecordArray[$k]['periodNumber']!=$periodArr)
                        break;
                   }
              }
              if($str == '') {
                 $csv .= ",";
              }
              else {
                 $csv .= ",".$this->parseCSVComments($str);

              }
          }
          $csv .= "\n";
       }
       return $this->removePHPJS(trim($csv));
    }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Days
//
// Author :Parveen Sharma
// Created on : (17.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getDaysList($selected='') {
        global $daysArr;
        $returnValues = '';
         if(isset($daysArr) && is_array($daysArr)) {
             foreach($daysArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }
        }
        return $returnValues;
   }

   //----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee Name
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (17.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getTeacherData($selected='') {
        $results = CommonQueryManager::getInstance()->getEmployeeName('employeeName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }







//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF conducting authority for which there is entry in test_type table
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (24.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
   public function getTestConductingAuthorityData($orderBy=' conductingAuthority', $selected='') {

        $condunctingAuthorityArray=array('1'=>'INTERNAL','2'=>'EXTERNAL','3'=>'ATTENDANCE');

        $results = CommonQueryManager::getInstance()->getTestConductingAuthority($orderBy,'');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['conductingAuthority']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['conductingAuthority'].'" SELECTED="SELECTED">'.$condunctingAuthorityArray[$results[$i]['conductingAuthority']].'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['conductingAuthority'].'">'.$condunctingAuthorityArray[$results[$i]['conductingAuthority']].'</option>';
                }
            }

        }
        return $returnValues;
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A Help
// Author :Parveen Sharma
// Created on : (26.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------------------------------------
 public function getHelpLink($title='',$msg='',$helpImg='',$showImg='',$videoHelp='') {

        global $sessionHandler;

        if($videoHelp!=''){
          $returnValues = "&nbsp;<input style='display:none;' type='image' id='help' name='helpOnOFF' src='".IMG_HTTP_PATH."/help_on.gif' onClick='openVideoHelpDiv(); return false;' />";
          return $returnValues;
        }

		if($showImg!='') {
           $returnValues = "&nbsp;<a class='blueLinkSimple' href='#' onClick='showHelpDetails(\"$title\",\"$msg\"); return false;'); >$showImg</a>";
        }
        else {
		   if($helpImg == '') {
			 $returnValues = "&nbsp;<input style='display:none; vertical-align:bottom;margin-bottom:-2px;' type='image' id='help' name='helpOnOFF' src='".IMG_HTTP_PATH."/help_on.gif' onClick='showHelpDetails(\"$title\",\"$msg\"); return false;' />";
		   }
		   else if($helpImg != '') {
			 $returnValues = "<input style='display:none' type='image' id='help' name='helpOnOFF' src='".IMG_HTTP_PATH."/help_on1.gif' onClick='showHelpDetails(\"$title\",\"$msg\"); return false;' />&nbsp;";
		   }
		}
        return $returnValues;
   }
   
   
   public function showPeriodSlotList($periodSlotId) {
        
        global $sessionHandler; 
       
        if($periodSlotId=='') {
          $periodSlotId=0;  
        }
        
        $periodsArray = CommonQueryManager::getInstance()->getShowSlotPeriods($periodSlotId);   
        
        $tableData ='';
        if(is_array($periodsArray) && count($periodsArray)>0) {
           $tableHead='';
           foreach($periodsArray as $periodRecord) {
             $tableHead .= "<td class='contenttab_internal_rows' nowrap>".date('h:i',strtotime($periodRecord['startTime'])).' '.$periodRecord['startAmPm'].' - '.date('h:i',strtotime($periodRecord['endTime'])).' '.$periodRecord['endAmPm']."</td>";
           }
                                    
           $periodData='';
           foreach($periodsArray as $periodRecord) {
             $periodData .= "<td class='contenttab_internal_rows'>".$periodRecord['periodNumber']."</td>";
           }
           $tableData .= "<table border='1' cellspacing='0' cellpadding='0' rules='all' style='border-collapse:collapse;' align='center' bgcolor='#FFFF99' width='100%'>
                            <tr>
                                <td valign='top' colspan='1' >
                                    <b>Timings</b>
                                </td>
                                $tableHead
                            </tr>
                            <tr>
                              <td valign='top' colspan='1' class='contenttab_internal_rows'>
                                 <b>Periods</b>
                              </td>
                              $periodData 
                              </tr></table>";
        }
        else { 
          $tableData .= "<table border='1' cellspacing='0' cellpadding='0' rules='all' style='border-collapse:collapse;' align='center' bgcolor='#FFFF99' width='100%'>
                            <tr>
                                <td valign='top' colspan='1'style='color:red;'>
                                    <b>&nbsp Note: Periods are not defined for the selected period slot</b>
                                </td>
                             </tr>
                          </table>";   
        }
        
         return $tableData;       
   }

 /*  public function getHelpLink($title='',$msg='',$helpImg='') {

        global $sessionHandler;

        if($helpImg == '') {
          $returnValues = "&nbsp;<input style='display:none; vertical-align:bottom;margin-bottom:-2px;' type='image' id='help' name='helpOnOFF' src='".IMG_HTTP_PATH."/help_on.gif' onClick='showHelpDetails(\"$title\",\"$msg\"); return false;' />";
        }
        else if($helpImg != '') {
          $returnValues = "<input style='display:none' type='image' id='help' name='helpOnOFF' src='".IMG_HTTP_PATH."/help_on1.gif' onClick='showHelpDetails(\"$title\",\"$msg\"); return false;' />&nbsp;";
        }
        else {
           $returnValues = "";
        }
        return $returnValues;
   }*/


   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Time Table Adjustment Type
//
//
// Author :Jaineesh
// Created on : (29.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getTimeTableAdjustmentType($selected='',$arr) {
		global $adjustmentTypeArr;
		asort($adjustmentTypeArr);
		$returnValues = '';
		if ($arr != '') {
		$arr1 = explode(",",$arr);
         if(isset($adjustmentTypeArr) && is_array($adjustmentTypeArr)) {
            //$count = count($adjustmentTypeArr);
			$i=0;
             foreach($adjustmentTypeArr as $key=>$value) {
			  if($value == $arr1[$i]) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
				$i++;
              }
			 }
          }
		}
		else {
			if(isset($adjustmentTypeArr) && is_array($adjustmentTypeArr)) {
            //$count = count($adjustmentTypeArr);
			$i=0;
             foreach($adjustmentTypeArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
              }
			 }
          }

        return $returnValues;
   }

// this function is used to get a list of teaching employee
// Author :Jaineesh
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getAllTeacher($selected='') {
        $results = CommonQueryManager::getInstance()->getAllTeacherData();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

    public function getTeachersHighlighted($selected='') {
        $results = CommonQueryManager::getInstance()->getAllTeachers();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
					$isActive = $results[$i]['isActive'];
					$style = 'style="color:black;"';
					if ($isActive == '0' or $isActive == 0) {
						$style = 'style="color:red;"';
					}
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option '.$style.' value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option '.$style.' value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   
   
    public function getEmployeeHighlighted($selected='') {
        $results = CommonQueryManager::getInstance()->getAllEmployees();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                    $isActive = $results[$i]['isActive'];
                    $style = 'style="color:black;"';
                    if ($isActive == '0' or $isActive == 0) {
                      $style = 'style="color:red;"';
                    }
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option '.$style.' value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName1']).'</option>';
                }
                else {
                    $returnValues .='<option '.$style.' value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName1']).'</option>';
                }
            }

        }
        return $returnValues;
   }

 public function getEmployeeHighlightedNew($selected='',$showId='') {
      $results = CommonQueryManager::getInstance()->getAllEmployeesNew();
      $returnValues = '';
      
      if(isset($results) && is_array($results)) {
         $count = count($results);
         for($i=0;$i<$count;$i++) {
            $isActive = $results[$i]['isActive'];
            $isTeaching = $results[$i]['isTeaching'];  
            $style = 'style="color:black;"';
            
            $employeeName = strip_slashes($results[$i]['employeeName1']);
            $instituteCode = strip_slashes($results[$i]['instituteCode']);
            if($isTeaching=='1') {
              $employeeName .= " (Teaching - $instituteCode)";  
            }
            else {
              $employeeName .= " (Non Teaching - $instituteCode)";  
            }
            
            if($showId=='') {
              $id = strip_slashes($results[$i]['employeeId']);
            }
            else {
              $id = strip_slashes($results[$i]['userId']);  
            }
            if ($isActive == '0' or $isActive == 0) {
              $style = 'style="color:red;"';
            }
            if($results[$i]['employeeId']==$selected) {
              $returnValues .='<option '.$style.' value="'.$id.'" SELECTED="SELECTED">'.$employeeName.'</option>';
            }
            else {
              $returnValues .='<option '.$style.' value="'.$id.'">'.$employeeName.'</option>';
            }
         }
      }
      return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF class
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getAllClassData($selected='') {
        $results = CommonQueryManager::getInstance()->getAllClass('ttc.timeTableLabelId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';

                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


   public function getAllClassDataNew($selected='') {
        $results = CommonQueryManager::getInstance()->getAllClass('ttc.timeTableLabelId');
	
	if(trim($selected)!='')	{ 
	  $selectedArr = explode(',',$selected);	
	}

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
		$selected='';	
	        for($j=0;$j<count($selectedArr);$j++) {
     		  if($selectedArr[$j]==$results[$i]['classId']) {
		     $selected=$results[$i]['classId'];
		     break;
		  }	
		}
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Fee Type
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (19.11.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFeeTypeData($selected='') {
        global $feeTypeArr;

        $returnValues = '';
         if(isset($feeTypeArr) && is_array($feeTypeArr)) {
            $count = count($titleResults);
             foreach($feeTypeArr as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }


// this function is used to get a list of transferred classes
// Author :Dipanjan Bhattacharjee
// Created on : (03.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getTransferredClasses($timeTableLabelId,$employeeId='') {
        if($employeeId!=''){
          $results = TeacherManager::getInstance()->getLabelMarksTransferredClassTeacher($timeTableLabelId,$employeeId);
        }
        else{
          $results = TeacherManager::getInstance()->getLabelMarksTransferredClass($timeTableLabelId);
        }

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }



// this function is used to get a list of teaching employee
// Author :Jaineesh
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getActiveClassesWithNoGroups($selected='') {
        $results = CommonQueryManager::getInstance()->getActiveClassesWithNoGroupsData();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A AttendanceSetId
//
//selected: which element in the select element to be selected
//
// Author :Parveen Sharma
// Created on : (17.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getAttendanceSetData($selected='',$condition='',$orderBy='attendanceSetName') {
        $results = CommonQueryManager::getInstance()->getAttendanceSet($condition,$orderBy);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['attendanceSetId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['attendanceSetId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['attendanceSetName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['attendanceSetId'].'">'.strip_slashes($results[$i]['attendanceSetName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Past class
//
// Author :Parveen Sharma
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getPastClassData($selected='',$condition='') {

       $results = CommonQueryManager::getInstance()->getPastClasses();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF ADV. FEEDBACK RELATIONSHIP
// selected: which element in the select element to be selected
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
   public function getAdvFeedBackRelationshipData($selected='') {

        global $advFeedBackRelationship;
        $returnValues = '';
         if(isset($advFeedBackRelationship) && is_array($advFeedBackRelationship)) {
            $count = count($advFeedBackRelationship);
             foreach($advFeedBackRelationship as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }



//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Subject Types related to logged in institute
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------

   public function getSubjectTypesData($selected='',$condition='') {

        $results = CommonQueryManager::getInstance()->getSubjectTypes($condition);
        $returnValues = '';

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectTypeId'].'">'.strip_slashes($results[$i]['subjectTypeName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Label
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------

   public function getAdvFeedBackLabelData($selected='',$condition='') {

        $results = CommonQueryManager::getInstance()->getAdvFeedBackLabel($condition);
        $returnValues = '';

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackSurveyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Label
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------

   public function getAdvFeedBackCategoryData($selected='',$condition='') {

        $results = CommonQueryManager::getInstance()->getAdvFeedBackCategory($condition);
        $returnValues = '';

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackCategoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackCategoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackCategoryName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackCategoryId'].'">'.strip_slashes($results[$i]['feedbackCategoryName']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Question Set Names
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------

   public function getAdvFeedBackQuestionSetData($selected='',$condition='') {

        $results = CommonQueryManager::getInstance()->getAdvFeedBackQuestionSet($condition);
        $returnValues = '';

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackQuestionSetId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackQuestionSetId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackQuestionSetName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackQuestionSetId'].'">'.strip_slashes($results[$i]['feedbackQuestionSetName']).'</option>';
                }
            }
        }
        return $returnValues;
   }



//---------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Adv. Feedback Lable Mapped Labels corresponding to
// an user of a particular role
// Author : Dipanjan Bhattacharjee
// Created on : (19.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------

   public function fetchMappedFeedbackLabelAdvForUsersData($selected='',$roleId=-1,$userId=-1) {

        $results = CommonQueryManager::getInstance()->fetchMappedFeedbackLabelAdvForUsers($roleId,$userId);
        $returnValues = '';

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['feedbackSurveyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feedbackSurveyId'].'">'.strip_slashes($results[$i]['feedbackSurveyLabel']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student Interneal Re-appear class
//
// Author :Parveen Sharma
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getReappearClassData($selected='',$condition='') {

       $results = CommonQueryManager::getInstance()->getReappearClasses();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';

                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student Interneal Re-appear subjects
//
// Author :Parveen Sharma
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getReappearSubjectData($selected='',$condition='') {

       $results = CommonQueryManager::getInstance()->getReappearSubject();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student Interneral Re-appear Status Array
//
//selected: which element in the select element to be selected
//
// Author :Rajeev Aggarwal
// Created on : (25.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getReappearStatusData($selected='') {

        global $reppearStatusArr;

        $returnValues = '';
         if(isset($reppearStatusArr) && is_array($reppearStatusArr)) {
            $count = count($reppearStatusArr);
             foreach($reppearStatusArr as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

//--------------------------------------------------------------------------------------------------------------------------------------------
//   THIS FUNCTION IS USED TO GET A LIST OF ALL Institue Past and Present CLASS
// Author :Parveen Sharma
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
   public function getAllInstituteClass($selected='') {
        $results = CommonQueryManager::getInstance()->getInstitueClass();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }
        }
        return $returnValues;
    }


//-------------------------------------------------------------------
// THIS FUNCTION IS USED TO Detect User's Browser Type(IE or not)
// Author :Dipanjan Bhattacharjee
// Created on : (15.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------
public function isIE6Browser(){
  $isIE6=0;

  if(strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko')){
       if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')){
         //$browser = 'Netscape (Gecko/Netscape)';
       }
       else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')){
         //$browser = 'Mozilla Firefox (Gecko/Firefox)';
       }
       else{
         //$browser = 'Mozilla (Gecko/Mozilla)';
       }
    $isIE6=0;
  }
  else if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
       if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')){
         //$browser = 'Opera (MSIE/Opera/Compatible)';
       }
       else{
         //$browser = 'Internet Explorer (MSIE/Compatible)';
       }
     $isIE6=0;
     $browser["agent"] = $_SERVER["HTTP_USER_AGENT"];
     $browser["version"] = @array_combine( array("name", "number"), explode(" ", array_shift( explode( ";", strstr( $browser["agent"], "MSIE" ) ) ) ) );
     $browser["version"]["int"] = @array_shift( explode( ".", $browser["version"]["number"]));
     if($browser["version"]["number"]=='6.0'){//if browser is IE6
         $isIE6=1;
     }
     else{
         $isIE6=0;
     }
  }
 else{
     $browser = 'Others browsers';
     $isIE6=-1;
 }

//echo $browser;
return $isIE6;

}


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Visitor Source Details
//
// Author :Parveen Sharma
// Created on : (11.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getVisitorSourceData($selected='') {

       global $visitorSource;

       $i=0;
       $returnValues = '<tr>';
       foreach($visitorSource as $key=>$value) {
           $check ='';
           if($key==$selected) {
             $check = "checked=checked";
           }
           $returnValues .='<td class="contenttab_internal_rows"><nobr>
                                <input class="inputbox1" '.$check.' type="checkbox" id="visitSource'.$key.'" name="visitSource" value="'.$key.'">
                            </nobr></td>
                            <td class="contenttab_internal_rows"><nobr>'.$value.'</nobr></td>
                            <td class="contenttab_internal_rows" width="50"></td>';

           $i=$i+1;
           if($i%6==0) {
             $returnValues .='</tr>';
             $i=0;
           }
       }
       if($i%6==1) {
         $returnValues .='</tr>';
       }
       return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student Enquiry Form Status
//
// Author :Parveen Sharma
// Created on : (25.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getEnquiryFormStatus($selected='',$condition='') {

        global $enquiryStatusArr;

        $returnValues = '';
         if(isset($enquiryStatusArr) && is_array($enquiryStatusArr)) {
            $count = count($enquiryStatusArr);
             foreach($enquiryStatusArr as $key=>$value) {
               if($condition=='') {
                  if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                  }
                  else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                  }
               }
               else {
                   if($key!=2 && $key!=5 ) {
                      if($key==$selected) {
                        $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                      }
                      else {
                        $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                      }
                   }
               }
            }
        }
        return $returnValues;
   }


   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Title
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getExperienceData($selected='') {
        global $experienceResults;

        $returnValues = '';
         if(isset($experienceResults) && is_array($experienceResults)) {
            $count = count($experienceResults);
             foreach($experienceResults as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Title
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getExperienceCertificateData($selected='') {
        global $experienceAvailableResults;

        $returnValues = '';
         if(isset($experienceAvailableResults) && is_array($experienceAvailableResults)) {
            $count = count($experienceAvailableResults);
             foreach($experienceAvailableResults as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }


//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF subject
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getActiveClassSubjectData($selected='',$condition='') {

        $results = EmployeeReportsManager::getInstance()->getActiveTimeTableSubject($condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ACtive Class Time Table
// Author :PArveen Sharma
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getActiveTimeTableClasses($selected='') {
        $results = CommonQueryManager::getInstance()->getTimeTableClasses();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }
        }
        return $returnValues;
   }


   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ACtive Class Time Table
// Author :PArveen Sharma
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getOptionalSubjects($subjectId,$groupId) {
        $results = CommonQueryManager::getInstance()->getOptionalSubjects($subjectId,$groupId);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['groupId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['groupName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['groupId'].'">'.strip_slashes($results[$i]['groupName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF DEDUCTION ACCOUNTS FOR PAYROLL HEADS MASTER
//
//
// Author :Abhiraj Malhotra
// Created on : (12.04.2010)
// Copyright 2009-2010 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getDeductionAccounts() {
		$results = CommonQueryManager::getInstance()->getDedAccountList();
		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$returnValues .='<option value="'.$results[$i]['dedAccountId'].'">'.strip_slashes($results[$i]['accountName']).'('.strip_slashes($results[$i]['accountNumber']).')'.'</option>';
			}

		}
		return $returnValues;
   }

    //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Room Type
// Author :Jaineeh
// Created on : (26.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getRoomType($condition='') {
        $results = CommonQueryManager::getInstance()->getRoomTypeList('roomType',$condition);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['roomTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['roomTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['abbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['roomTypeId'].'">'.strip_slashes($results[$i]['abbr']).'</option>';
                }
            }
        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF ALL EMPLOYEES
//
//orderBy: on which column to sort
//
// Author :Abhiraj Malhotra
// Created on : (19.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getAllEmployeeData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
        return $results;
   }

   //----------------------------------------------------------------------------
   //Purpose: make time table date filter
   //Author:Parveen Sharma
   //Date:10.09.2008
   // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
   //----------------------------------------------------------------------------
   public function makeTimeTableSearch() {

        $timeTableDefaultSearch =  "
                    <table width='10%' border='0' cellspacing='0px' cellpadding='0px' align='left'>
                    <tr>
                        <td class='contenttab_internal_rows' ><nobr><strong>&nbsp;From Date</strong></nobr></td>
                        <td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                        <td class='contenttab_internal_rows' align='left'><nobr>".$this->datePicker2('fromDate',date('Y-m-d'))."</nobr></td>
                        <td class='contenttab_internal_rows' align='left'><nobr><strong>&nbsp;&nbsp;To Date</strong></nobr></td>
                        <td class='contenttab_internal_rows' align='left' ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                        <td class='contenttab_internal_rows' align='left'><nobr>".$this->datePicker2('toDate',date('Y-m-d'))."</nobr></td>
                     </tr></table>";

        return $timeTableDefaultSearch;
   }


 //----------------------------------------------------------------------------
//Purpose: fetch list of study period name
//Author:Jaineesh
//Date:31-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
		public function getStudentAllocatedSubjectData($studentId,$classId,$selected='') {
            if(trim($studentId)=='' or trim($classId)==''){
                return '';
            }
			$results = CommonQueryManager::getInstance()->getStudentAllocatedSubject($studentId,$classId);
			$returnValues = '';
			if(isset($results) && is_array($results)) {
				$count = count($results);
				for($i=0;$i<$count;$i++) {
					if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['subjectId'].'">'.strip_slashes($results[$i]['subjectCode']).'</option>';
                }
			}
			return $returnValues;
		}
	}




    //----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Employee Name Allocated to particular Class
//
//selected: which element in the select element to be selected
//
// Author :	Prashant
// Created on : (11.5.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getTeacherDataAllocatedToClass($classId,$selected='') {
        $results = CommonQueryManager::getInstance()->getEmployeeNameAllocatedToClass($classId,'');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//----------------------------------------------------------------------------
// Purpose: To show "Budget Heads" in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 17.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getBudgetHeadsData($conditions='',$selected='') {
            $results = CommonQueryManager::getInstance()->getBudgetHeadsData($conditions);
            $returnValues = '';
            if(isset($results) && is_array($results)) {
                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    if($results[$i]['budgetHeadId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['budgetHeadId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['headName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['budgetHeadId'].'">'.strip_slashes($results[$i]['headName']).'</option>';
                }
            }
            return $returnValues;
        }
}

//----------------------------------------------------------------------------
// Purpose: To show "Leave Set" in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 17.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getLeaveSessionSetAdvData($conditions='',$selected='') {
    $results = CommonQueryManager::getInstance()->getLeaveSessionSetAdvData($conditions);
    $returnValues = '';
    if(isset($results) && is_array($results)) {
       $count = count($results);
       for($i=0;$i<$count;$i++) {
          $val = $results[$i]['leaveSetId'];
          if($results[$i]['leaveSetId']==$selected) {
            $returnValues .='<option value="'.$val.'" SELECTED="SELECTED">'.strip_slashes($results[$i]['leaveSetName']).'</option>';
          }
          else {
            $returnValues .='<option value="'.$val.'">'.strip_slashes($results[$i]['leaveSetName']).'</option>';
          }
       }
       return $returnValues;
    }
}

//----------------------------------------------------------------------------
// Purpose: To show "Leave Set" in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 17.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getLeaveSetAdvData($conditions='',$selected='') {
            $results = CommonQueryManager::getInstance()->getLeaveSetAdvData($conditions);
            $returnValues = '';
            if(isset($results) && is_array($results)) {
                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    if($results[$i]['leaveSetId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['leaveSetId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['leaveSetName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['leaveSetId'].'">'.strip_slashes($results[$i]['leaveSetName']).'</option>';
                }
            }
            return $returnValues;
        }
}


    //----------------------------------------------------------------------------
    // Purpose: To show "Leave Set" in "Select" element
    // Author: Dipanjan Bhattacharjee
    // Date: 17.05.2010
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //----------------------------------------------------------------------------
    public function getLeaveTypeAdvData($conditions='',$selected='') {
                $results = CommonQueryManager::getInstance()->getLeaveTypeAdvData($conditions);
                $returnValues = '';
                if(isset($results) && is_array($results)) {
                    $count = count($results);
                    for($i=0;$i<$count;$i++) {
                        if($results[$i]['leaveSetId']==$selected) {
                        $returnValues .='<option value="'.$results[$i]['leaveTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['leaveTypeName']).'</option>';
                    }
                    else {
                        $returnValues .='<option value="'.$results[$i]['leaveTypeId'].'">'.strip_slashes($results[$i]['leaveTypeName']).'</option>';
                    }
                }
                return $returnValues;
            }
    }


    //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF subject
    //
    //orderBy: on which column to sort
    //
    // Author :Parveen Sharma
    // Created on : (25.07.2010)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getCourseList($selected='',$condition='',$orderBy='') {
        $results = CommonQueryManager::getInstance()->getCourseData($condition,$orderBy);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $val=$results[$i]['subjectId'];
                if($results[$i]['subjectId']==$selected) {
                    $returnValues .='<option value="'.$val.'" SELECTED="SELECTED">'.strip_slashes($results[$i]['subjectName1']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$val.'">'.strip_slashes($results[$i]['subjectName1']).'</option>';
                }
            }

        }
        return $returnValues;
    }




//----------------------------------------------------------------------------
// Purpose: To show "Leave Status" for First Authorize Person in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 25.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getFirstAuthorizationLeaveStatusData($selected='') {
        global $leaveStatusArray;
        $returnValues = '';

        global $sessionHandler;
        $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');

         if(isset($leaveStatusArray) && is_array($leaveStatusArray)) {
             foreach($leaveStatusArray as $key=>$value) {
                if($key!=1 and $key!=3){
                   continue;
                }
                if($key==$selected) {
                   if($leaveAuthorizersId==1 && $value=='First Approval') {
                      $value = "Approve";
                   }
                   else if($leaveAuthorizersId==1 && $value=='Rejected') {
                      $value = "Reject";
                   }
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                   if($leaveAuthorizersId==1 && $value=='First Approval') {
                      $value = "Approve";
                   }
                   else if($leaveAuthorizersId==1 && $value=='Rejected') {
                      $value = "Reject";
                   }


                   $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
     return $returnValues;
}


//-------------------------------------------------------------------------------
// Purpose: To show "Leave Status" for First Authorize Person in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 25.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getSecondAuthorizationLeaveStatusData($selected='') {
        global $leaveStatusArray;
        $returnValues = '';
         if(isset($leaveStatusArray) && is_array($leaveStatusArray)) {
             foreach($leaveStatusArray as $key=>$value) {
                if($key!=2 and $key!=3){
                   continue;
                }
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
     return $returnValues;
}



//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Education
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (24.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getEducationData($selected='') {
        global $educationResults;

        $returnValues = '';
         if(isset($educationResults) && is_array($educationResults)) {
            $count = count($educationResults);
             foreach($educationResults as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }


   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Education
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (24.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFamilyAilment($selected='') {
        global $familyAilmentsResults;
		 $selectedArray = explode(',',$selected);

		 //print_r($selectedArray);

		 $cnt = count($selectedArray);

		 //print_r($familyAilmentsResults);

		 $count = count($familyAilmentsResults);
        $returnValues = '';
		foreach ($familyAilmentsResults as $key => $value) {
			$selected = "";
			//$key = intval($key);
			if (in_array($key, $selectedArray)) {
				$selected = " selected ";
			}
			$returnValues .= "<option value = '$key' $selected>$value</option>";
		}


        return $returnValues;
   }

    //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Ailment
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (24.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getFamilyAilmentData($selected='') {
        global $familyAilmentsResults;

        $returnValues = '';
         if(isset($familyAilmentsResults) && is_array($familyAilmentsResults)) {
            $count = count($familyAilmentsResults);
             foreach($familyAilmentsResults as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Coaching Centers
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (02.06.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getCoachingCentersData($selected='') {
        global $coachingCenterArr;

        $returnValues = '';
         if(isset($coachingCenterArr) && is_array($coachingCenterArr)) {
            $count = count($coachingCenterArr);
             foreach($coachingCenterArr as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }




//----------------------------------------------------------------------------
// Purpose: To show "Session Years" in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 26.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getSessionYearData($conditions='',$selected='') {
            $results = CommonQueryManager::getInstance()->getSessionDetail(1);
            $returnValues = '';
            if(isset($results) && is_array($results)) {
                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    if($results[$i]['sessionId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['sessionYear'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['sessionYear']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['sessionYear'].'">'.strip_slashes($results[$i]['sessionYear']).'</option>';
                }
            }
            return $returnValues;
        }
}

//----------------------------------------------------------------------------
// Purpose: To show "Pending Leave Status" in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 25.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getPendingLeaveStatusData($selected='') {

    global $leaveStatusArray;

    global $sessionHandler;
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');
    $returnValues = '';

    if(isset($leaveStatusArray) && is_array($leaveStatusArray)) {
         foreach($leaveStatusArray as $key=>$value) {
            if($key!=0 and $key!=1){
               continue;
            }
            if($key==$selected) {
               if($leaveAuthorizersId==1 && $value=='First Approval') {
                 $value = "Approved";
               }
               $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
            }
            else {
              if($leaveAuthorizersId==1 && $value=='First Approval') {
                 $value = "Approved";
              }
              $returnValues .='<option value="'.$key.'">'.$value.'</option>';
            }
        }
    }


    return $returnValues;
}


    //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Degree
    //
    //orderBy: on which column to sort
    //
    // Author :Parveen Sharma

    // Created on : (25.07.2010)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getDegreeList($selected='',$condition='',$orderBy='') {
        $results = CommonQueryManager::getInstance()->getRegistrationDegreeList($condition,$orderBy);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $val=$results[$i]['classId'];
                $className= "Term-".UtilityManager::romanNumerals($i+4);
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$val.'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$val.'">'.strip_slashes($className).'</option>';
                }
            }

        }
        return $returnValues;
    }


    //-------------------------------------------------------
    //  THIS FUNCTION IS USED TO GET A LIST OF Degree
    //
    //orderBy: on which column to sort
    //
    // Author :Parveen Sharma
    // Created on : (25.07.2010)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getRegistrationClassList($selected='',$condition='',$orderBy='') {
        $results = CommonQueryManager::getInstance()->getRegistrationClass($condition,$orderBy);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $val=$results[$i]['classId'];
                $className= $results[$i]['className'];
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$val.'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$val.'">'.strip_slashes($className).'</option>';
                }
            }

        }
        return $returnValues;
    }



//---------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Budget Head Types
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (10.06.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
   public function getBudgetHeadTypeData($selected='') {
       global $globalBudgetHeadTypeArray;

       $returnValues = '';
       if(isset($globalBudgetHeadTypeArray) && is_array($globalBudgetHeadTypeArray)) {
         foreach($globalBudgetHeadTypeArray as $key=>$value) {
            if($key==$selected) {
                $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
            }
            else {
                $returnValues .='<option value="'.$key.'">'.$value.'</option>';
            }
         }
       }
       return $returnValues;
   }


public function getDutyLeaveEventData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getDutyLeaveEventData($condition);
        $returnValues = '';
		echo"<pre>";

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['eventId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['eventId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['eventTitle']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['eventId'].'">'.strip_slashes($results[$i]['eventTitle']).'</option>';
                }
            }

        }
        return $returnValues;
}


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Counselling Rounds
//
//selected: which element in the select element to be selected
//
// Author :Parveen Sharma
// Created on : (25.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getCounsellingRoundsData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getCounsellingRounds(' roundId',$condition);

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $roundName = $results[$i]['roundName'];
                if($results[$i]['roundId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['roundId'].'" SELECTED="SELECTED">'.strip_slashes($roundName).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['roundId'].'">'.strip_slashes($roundName).'</option>';
                }
            }
        }

        return $returnValues;

    }


//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Counselling Class
//
//selected: which element in the select element to be selected
//
// Author :Parveen Sharma
// Created on : (25.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function getCounsellingClassData($selected='',$condition='') {
        $results = CommonQueryManager::getInstance()->getCounsellingClass(' className',$condition);

        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $className = $results[$i]['className'];
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($className).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($className).'</option>';
                }
            }
        }

        return $returnValues;

    }

//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Title
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (05.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getRepairTypeData($selected='') {
        global $repairTypeResults;

        $returnValues = '';
         if(isset($repairTypeResults) && is_array($repairTypeResults)) {
            $count = count($repairTypeResults);
             foreach($repairTypeResults as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Grade Set values
//
// Author :Jaineesh
// Created on : (22.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getExtraTyres($selected = '',$condition='') {
        $results = CommonQueryManager::getInstance()->getExtraTyres($condition);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['tyreId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['tyreId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['tyreNumber']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['tyreId'].'">'.strip_slashes($results[$i]['tyreNumber']).'</option>';
                }
            }
        }
        return $returnValues;
   }

   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Vehicle Service Array
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (16.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getVehicleServiceData($selected='') {



        global $serviceArr;

        $returnValues = '';
         if(isset($serviceArr) && is_array($serviceArr)) {
            $count = count($serviceArr);
             foreach($serviceArr as $key=>$value) {
                if($key==$selected) {
                    $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
   }

   //--------------------------------------------------------
// this function is used to get a list of vehicle types
// Author :Ajinder Singh
// Created on : 01-Dec-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	public function getVehicleTypes($selected='') {
		$results = CommonQueryManager::getInstance()->getVehicleTypes();

		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$returnValues .='<option value="'.$results[$i]['vehicleTypeId'].'">'.strip_slashes($results[$i]['vehicleType']).'</option>';
			}

		}
		return $returnValues;
	}

	public function getInsuringCompany() {
		$results = CommonQueryManager::getInstance()->getInsuringCompany();

		$returnValues = '';
		if(isset($results) && is_array($results)) {
			$count = count($results);
			for($i=0;$i<$count;$i++) {
				$returnValues .='<option value="'.$results[$i]['insuringCompanyId'].'">'.strip_slashes($results[$i]['insuringCompanyName']).'</option>';
			}

		}
		return $returnValues;
	}

	//----------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUFF NAMES  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Dipanjan Bhattacharjee
// Created on : (21.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------
   public function getTransportStaffData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getTransportStaff(' name',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['staffId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['staffId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['name']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['staffId'].'">'.strip_slashes($results[$i]['name']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Transport Stuff Types
//
//orderBy: on which column to sort
//
// Author :Dipanjan Bhattacharjee
// Created on : (18.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------

   public function getTransportStaffTypeData($selected='') {
      global $transportStaffTypeArr;
      foreach($transportStaffTypeArr as $key=>$value)
      {
         if($key==$selected)
           $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
         else
           $returnValues .='<option value="'. $key.'">'. $value.'</option>';
      }
      return $returnValues;
   }

   public function datePickerWithTime($fieldName,$value=''){

       echo "<input type=\"text\" id=\"$fieldName\" name=\"$fieldName\" class=\"inputbox1\" readonly=\"true\" value=\"$value\" size=\"18\" /><input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\"".IMG_HTTP_PATH."/calendar.gif\"  onClick=\"return showCalendar('$fieldName', '%Y-%m-%d %H:%M', '24', true);\">";
   }


//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Inventory Deptt. Type
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (17.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getInventoryDepartmentType($selected='') {
        global $inventoryDepartmentArr;

        $returnValues = '';
         if(isset($inventoryDepartmentArr) && is_array($inventoryDepartmentArr)) {
            $count = count($inventoryDepartmentArr);
             foreach($inventoryDepartmentArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
     }


//----------------------------------------------------------------------------
//Purpose: show user names
//Author:Jaineesh
//Date:25.11.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
	public function getAllUserData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getUserData('userName',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['userId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['userId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['userName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['userId'].'">'.strip_slashes($results[$i]['userName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Tax Head  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (09 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getIndentData($selected='') {

        $results = CommonQueryManager::getInstance()->getIndent();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['partyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['indentId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['indentNo']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['indentId'].'">'.strip_slashes($results[$i]['indentNo']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Tax Head  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (09 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getPOData($selected='') {
        $results = CommonQueryManager::getInstance()->getPO();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['partyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['poId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['poNo']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['poId'].'">'.strip_slashes($results[$i]['poNo']).'</option>';
                }
            }
        }
        return $returnValues;
   }



 //----------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Tax Head  IN THE "SELECT" ELEMENT
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (09 Aug 10)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
   public function getPartyData($selected='') {
        $results = CommonQueryManager::getInstance()->getParty();
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['partyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['partyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['partyCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['partyId'].'">'.strip_slashes($results[$i]['partyCode']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//----------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Item categories  IN THE "SELECT" ELEMENT
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (11.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------
   public function getItemCategoryData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->
getItemCategory(' categoryCode',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['itemCategoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['itemCategoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['itemCategoryId'].'">'.strip_slashes($results[$i]['categoryName']).'</option>';
                }
            }
        }
        return $returnValues;
   }


//----------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Item categories  IN THE "SELECT" ELEMENT
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (11.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------
   public function getItemCategoryData1($selected='',$conditions='') {

        $results = CommonQueryManager::getInstance()->getItemCategory(' categoryCode',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
               $str = $results[$i]['categoryName']." (".$results[$i]['categoryCode']."-";
	       if($results[$i]['categoryType']==1) {
		  $str .= 'Consumable';
	       }
	       else {
		  $str .='Non-consumable';
	       }
               $str .=")";
	       if($results[$i]['itemCategoryId']==$selected) {
		 $returnValues .='<option id="'.$results[$i]['itemCategoryId'].'" value="'.$results[$i]['itemCategoryId'].'" SELECTED="SELECTED">'.$str.'</option>';
	       }
	       else {
		 $returnValues .='<option id="'.$results[$i]['itemCategoryId'].'" value="'.$results[$i]['itemCategoryId'].'">'.$str.'</option>';
	       }
            }
        }
        return $returnValues;
   }



//---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Inventory Department Incharge
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (24.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getInvDepartmentIncharge($selected='') {
        $results = CommonQueryManager::getInstance()->getDepartmentIncharge($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Inventory Department Incharge
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (24.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getInvDepttData($selected='') {
        $results = CommonQueryManager::getInstance()->getInvDepttData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['invDepttId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['invDepttId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['invDepttAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['invDepttId'].'">'.strip_slashes($results[$i]['invDepttAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

   //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Inventory Deptt. Type
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (17.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getItemTypeData($selected='') {
        global $itemTypeArr;

        $returnValues = '';
         if(isset($itemTypeArr) && is_array($itemTypeArr)) {
            $count = count($itemTypeArr);
             foreach($itemTypeArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
     }

	 //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Issued items Status
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (17.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getIssuedItemStatusData($selected='') {
        global $issuedStatusArr;

        $returnValues = '';
         if(isset($issuedStatusArr) && is_array($issuedStatusArr)) {
            $count = count($issuedStatusArr);
             foreach($issuedStatusArr as $key=>$value) {
                if($key==$selected) {
                   $returnValues .='<option value="'.$key.'" SELECTED="SELECTED">'.$value.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$key.'">'.$value.'</option>';
                }
            }

        }
        return $returnValues;
     }

	 //---------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Inventory Department Incharge
//
//selected: which element in the select element to be selected
//
// Author :Jaineesh
// Created on : (24.02.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
   public function getInvNonIssueDepttData($conditions='') {
        $results = CommonQueryManager::getInstance()->getInvNonIssueDepttData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['invDepttId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['invDepttId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['invDepttAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['invDepttId'].'">'.strip_slashes($results[$i]['invDepttAbbr']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Item categories  IN THE "SELECT" ELEMENT
// selected: which element in the select element to be selected
// Author :Dipanjan Bhattacharjee
// Created on : (11.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------
   public function getItemConsumableCategoryData($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getItemConsumableCategory(' abbr',$conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['itemCategoryId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['itemCategoryId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['abbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['itemCategoryId'].'">'.strip_slashes($results[$i]['abbr']).'</option>';
                }
            }
        }
        return $returnValues;
   }

   //-------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Unit of measurements
// Author :Dipanjan Bhattacharjee
// Created on : (29.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------

   public function getUnitOfMeasurementData($selected='') {
      global $UnitOfMeasurementArray;

      foreach($UnitOfMeasurementArray as $key=>$value)
      {
         if($key==$selected)
           $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
         else
           $returnValues .='<option value="'. $key.'">'. $value.'</option>';
      }
      return $returnValues;
   }

//-------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF packaging type
// Author :Dipanjan Bhattacharjee
// Created on : (29.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------

   public function getPackagingData($selected='') {
      global $packagingArray;
      foreach($packagingArray as $key=>$value)
      {
         if($key==$selected)
           $returnValues .='<option value="'. $key.'" SELECTED>'. $value.'</option>';
         else
           $returnValues .='<option value="'. $key.'">'. $value.'</option>';
      }
      return $returnValues;
   }




//----------------------------------------------------------------------------
// Purpose: To show "Budget Heads" in "Select" element
// Author: Dipanjan Bhattacharjee
// Date: 17.05.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getSelectedTimeTableClassesForBranchChange($conditions='',$selected='') {
            require_once(MODEL_PATH.'/ChangeStudentBranchManager.inc.php');
            $results = ChangeStudentBranchManager::getInstance()->getSelectedTimeTableClassesForBranchChange($conditions);
            $returnValues = '';
            if(isset($results) && is_array($results)) {
                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }
            return $returnValues;
        }
}


public function getSelectedTimeTableClasses($conditions='',$selected='') {
            require_once(MODEL_PATH.'/DutyLeaveManager.inc.php');
            $results = DutyLeaveManager::getInstance()->getSelectedTimeTableClasses($conditions);
            $returnValues = '';
            if(isset($results) && is_array($results)) {
                $count = count($results);
                for($i=0;$i<$count;$i++) {
                    if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }
            return $returnValues;
        }
}

	//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Grade Set values
//
// Author :Parveen Sharma
// Created on : (22.10.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getGradeSetData($selected = '',$condition='') {
        $results = CommonQueryManager::getInstance()->getGradeSet($condition);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['gradeSetId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['gradeSetId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['gradeSetName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['gradeSetId'].'">'.strip_slashes($results[$i]['gradeSetName']).'</option>';
                }
            }
        }
        return $returnValues;
   }

   //-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student's Teacher
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (28.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getStudentTeacherData($selected='') {
        $results = CommonQueryManager::getInstance()->getStudentTeacher('employeeName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['userId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['userId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['userId'].'">'.strip_slashes($results[$i]['employeeName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Student's Teacher
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (28.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function getTeacherStudentData($selected='') {
        $results = CommonQueryManager::getInstance()->getTeacherStudent('firstName');
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['userId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['userId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['firstName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['userId'].'">'.strip_slashes($results[$i]['firstName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------
// This function is used to get a list of appraisal tabs
// Author :Dipanjan Bhattacharjee
// Created on : (15.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------
    public function getAppraisalTab($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getAppraisalTabData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['appraisalTabId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['appraisalTabId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['appraisalTabName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['appraisalTabId'].'">'.strip_slashes($results[$i]['appraisalTabName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------
// This function is used to get a list of appraisal titles
// Author :Dipanjan Bhattacharjee
// Created on : (15.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------
    public function getAppraisalTitle($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getAppraisalTitleData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['appraisalTitleId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['appraisalTitleId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['appraisalTitle']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['appraisalTitleId'].'">'.strip_slashes($results[$i]['appraisalTitle']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//---------------------------------------------------------------
// This function is used to get a list of appraisal proofs
// Author :Dipanjan Bhattacharjee
// Created on : (15.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------
    public function getAppraisalProof($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getAppraisalProofData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['appraisalProofId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['appraisalProofId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['appraisalProofName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['appraisalProofId'].'">'.strip_slashes($results[$i]['appraisalProofName']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Placement Companies
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (29.07.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------

   public function getPlacementCompanies($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getPlacementCompaniesData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['companyId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['companyId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['companyCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['companyId'].'">'.strip_slashes($results[$i]['companyCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }

//----------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET A LIST OF Placement Drives
// orderBy: on which column to sort
// Author :Dipanjan Bhattacharjee
// Created on : (03.08.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
   public function getPlacementDrives($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getPlacementDrivesData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['placementDriveId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['placementDriveId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['placementDriveCode']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['placementDriveId'].'">'.strip_slashes($results[$i]['placementDriveCode']).'</option>';
                }
            }

        }
        return $returnValues;
   }


//----------------------------------------------------------------------------
// Purpose: fetch list of classes from "admappl_student_information" table
// Author: Dipanjan Bhattacharjee
// Date:27.09.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function getAdmApplClasssData($conditions='',$selected='') {
        $results = CommonQueryManager::getInstance()->getAdmApplClassData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
            }
            else {
                $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
            }
        }
        return $returnValues;
    }
 }


 //----------------------------------------------------------------------------
// Purpose: fetch list of classes from "student_program_fee" table
// Author: Dipanjan Bhattacharjee
// Date:27.09.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function getStudentProgramFeeData($conditions='',$selected='') {
        $results = CommonQueryManager::getInstance()->getStudentProgramFeeData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['programFeeId']==$selected) {
                $returnValues .='<option value="'.$results[$i]['programFeeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['programFeeName']).'</option>';
            }
            else {
                $returnValues .='<option value="'.$results[$i]['programFeeId'].'">'.strip_slashes($results[$i]['programFeeName']).'</option>';
            }
        }
        return $returnValues;
    }
 }


//----------------------------------------------------------------------------
// Purpose: fetch list of users who has requested allocation for guest house
// Author: Dipanjan Bhattacharjee
// Date:22.11.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function getGuestHouseRequester($selected='',$conditions='') {
        $results = CommonQueryManager::getInstance()->getGuestHouseRequesterData($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['programFeeId']==$selected) {
                $returnValues .='<option value="'.$results[$i]['userId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['userName']).'</option>';
            }
            else {
                $returnValues .='<option value="'.$results[$i]['userId'].'">'.strip_slashes($results[$i]['userName']).'</option>';
            }
        }
        return $returnValues;
    }
 }

//----------------------------------------------------------------------------
// Purpose: fetch list of Fee Clases
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
  public function getAllFeeClass($condition='',$orderBy='cls.degreeId,cls.branchId,cls.studyPeriodId') {
        $results = CommonQueryManager::getInstance()->getAllFeeClassData($condition,$orderBy);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                	$returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                	 $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }
        }
        return $returnValues;
   }
  
   public function getStudentFeeClasses($condition='') {
        $results = CommonQueryManager::getInstance()->getStudentAllFeeClasses($studentId='',$classId='',$condition='');

        $returnValues = '<table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="border">';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
              $returnValues .= '<tr class="rowheading" >
              						<td class="padding_top" align="center" colspan="2">';  
                $returnValues .= '<b>'.$results[$i]['className'].'</b>';
              $returnValues .= '</td></tr>';  
			  
              $returnValues .= '<tr>';
              $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;">
                                  <nobr><b>&bull;&nbsp;</b> 
                                  <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',1); return false;">Academic Fee</a>
                                  </nobr><br></td>';
             $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;"> 
                                <nobr><b>&bull;&nbsp;</b>
                                <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',3); return false;">Hostel Fee</a>
                              	</nobr><br>
                              </td>';
               $returnValues .= '</tr>'; 
			    $returnValues .= '<tr>';                    
             $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;"> 
                                <nobr><b>&bull;&nbsp;</b>
                                <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',2); return false;">Transport Fee</a>
                                </nobr><br>
                              </td>';
             $returnValues .= '<td class="padding_top" align="left" style="padding-left:15px;"> 
                                <nobr><b>&bull;&nbsp;</b>
                                <a href="#" onClick="populateStudentFeeDetails('.$results[$i]['classId'].',4); return false;">All Fee</a>
                             	 </nobr><br>
                              </td>';
                                  
              $returnValues .= '</tr>';
              $returnValues .= '<tr class="'.$bg.'"><td style="height:14px;"></td></tr>';
            }
        }
        $returnValues .= '</table>';
        return $returnValues;
   }
/////////------------------------------------------------------------
//THIS FUNCTION IS USED TO VIEW IN DASHBOARD
//CLASS NAME STUDENT NAME COURSES.........
//USER : Aarti   DATE: 20/01/12***********
//******************************************
/////////////////////////////////////////////
public function getStudentSubjectDetails($classId='',$studentId='',$orderBy='subjectCode') {
      
     global $sessionHandler;  
     $roleId = $sessionHandler->getSessionVariable('RoleId');
    	
     $studentSubjectArray = CommonQueryManager::getInstance()->getStudentSubjectDetails($classId, $studentId,$orderBy);       
     if($roleId == 3 || $roleId == 4) {
        $studentName = $sessionHandler->getSessionVariable('StudentName');
        $className = $sessionHandler->getSessionVariable('ClassName');
     }
     else {
        // Fetch 
     }
         
     $studentSubject = "";
     $totalSubject=0;
     $result='';
     if(isset($studentSubjectArray) && is_array($studentSubjectArray)) {
       for($i=0;$i<count($studentSubjectArray);$i++) {
         if($studentSubject != "") {
           $studentSubject .= ", ";   
	 }  
       	 $studentSubject .= $studentSubjectArray[$i]['subjectName'];
	 $totalSubject = $totalSubject +1;
       }
    }

       $result = "Welcome, ".$studentName = ucwords(strtolower($studentName))."  (You are currently enrolled in ".$className = ucwords($className)."  and are Studying  "  .$totalSubject ."   Subjects)";
            if($totalSubject==0)//Welcome, Nitish Kumar( You are currently enrolled in 2009-BTECH-PTU-CSE-5SEM and are studying 5 subjects )
                 $result =  $result."  ! Please Allocate Subjects";  // if no subject is defined
                       return $result;///$str = strtolower($str)
}
//----------------------------------------------------------------------------
// Purpose: fetch list of All Degrees
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getAllDegree($selected='',$condition ='',$orderBy ='degreeAbbr'){
	$results = CommonQueryManager::getInstance()->fetchAllDegree($condition,$orderBy);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['degreeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
                }
            }
        }
        return $returnValues;

}

public function getAllClassDegree($selected='',$condition ='',$orderBy ='degreeAbbr') {
    
        $results = CommonQueryManager::getInstance()->fetchAllClassDegree($condition,$orderBy);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['degreeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['degreeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['degreeId'].'">'.strip_slashes($results[$i]['degreeAbbr']).'</option>';
                }
            }
        }
        return $returnValues;

}
//----------------------------------------------------------------------------
// Purpose: fetch list of FEE HEADS
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function getFeeHeadDataNew($orderBy,$condition){
	$results = CommonQueryManager::getInstance()->getFeeHeadNew($orderBy,$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);

            for($i=0;$i<$count;$i++) {
                if($results[$i]['feeHeadId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['headName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['feeHeadId'].'">'.strip_slashes($results[$i]['headName']).'</option>';
                }
            }

        }
        return $returnValues;
}
//----------------------------------------------------------------------------
// Purpose: fetch list of HOSTELS
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function fetchHostels($selected,$condition){
	$results = CommonQueryManager::getInstance()->getHostelNames('hostelName',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);

            for($i=0;$i<$count;$i++) {
                if($results[$i]['hostelId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['hostelId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['hostelName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['hostelId'].'">'.strip_slashes($results[$i]['hostelName']).'</option>';
                }
            }

        }
        return $returnValues;	
}
//----------------------------------------------------------------------------
// Purpose: fetch list of ROOM TYPES
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function fetchRoomTypes($selected,$condition){
	$results = CommonQueryManager::getInstance()->getRoomTypes('roomType',$condition);
        $returnValues = '';
        if(isset($results) && is_array($results)){
            $count = count($results);

            for($i=0;$i<$count;$i++) {
                if($results[$i]['hostelRoomTypeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['hostelRoomTypeId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['roomType']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['hostelRoomTypeId'].'">'.strip_slashes($results[$i]['roomType']).'</option>';
                }
            }
        }
        return $returnValues;	
}
//----------------------------------------------------------------------------
// Purpose: fetch list of BUS ROUTES
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
public function fetchBusRoutes($selected='',$condition=''){
	$results = CommonQueryManager::getInstance()->getBusRoutes($condition,'route');
        $returnValues = '';
        if(isset($results) && is_array($results)){
            $count = count($results);

            for($i=0;$i<$count;$i++){
                if($results[$i]['busRouteId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['busRouteId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['route']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['busRouteId'].'">'.strip_slashes($results[$i]['route']).'</option>';
                }
            }
        }
        return $returnValues;	
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF Batch
//
//orderBy: on which column to sort
//
// Author :Rajeev Aggarwal
// Created on : (14.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

   public function fetchActiveBatch($selected='') {
        $results = CommonQueryManager::getInstance()->getActiveBatch('batchName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['batchId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['batchId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['batchName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['batchId'].'">'.strip_slashes($results[$i]['batchName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   //----------------------------------------------------------------------------
// Purpose: fetch list of BUS STOP CITY
// Author: Nishu Bindal
// Date:22.Feb.2012
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
   public function fetchBusStopCity($selected =''){
   	 $results = CommonQueryManager::getInstance()->getbusStopCityName('cityName');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['busStopCityId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['busStopCityId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['cityName']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['busStopCityId'].'">'.strip_slashes($results[$i]['cityName']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   
   //----------------------------------------------------------------------------
// Purpose: fetch list of classes from "student_program_fee" table
// Author: Dipanjan Bhattacharjee
// Date:27.09.2010
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
 public function getAllPeriods($conditions='',$selected='') {
     
    $results = CommonQueryManager::getInstance()->getAllPeriods($conditions);
    $returnValues = '';
    if(isset($results) && is_array($results)) {
        $count = count($results);
        for($i=0;$i<$count;$i++) {
          $periodTime = strip_slashes($results[$i]['periodTime']);  
          if($results[$i]['periodId']==$selected) {
           $returnValues .='<option value="'.$results[$i]['periodId'].'" SELECTED="SELECTED">'.$periodTime.'</option>';
         }
         else {
           $returnValues .='<option value="'.$results[$i]['periodId'].'">'.$periodTime.'</option>';
         }
      }
      return $returnValues;
   }
} 


public function getSmsTemplate($conditions='',$selected='') {
     
    $results = CommonQueryManager::getInstance()->getSmsTemplate($conditions);
    $returnValues = '';
    if(isset($results) && is_array($results)) {
        $count = count($results);
        for($i=0;$i<$count;$i++) {
          $templateName = strip_slashes($results[$i]['templateName']);  
	      $ids = $results[$i]['id']."!~~!".$results[$i]['noCols']."!~~!".$results[$i]['templateText'];
          if($results[$i]['id']==$selected) {
           $returnValues .='<option value="'.$ids.'" SELECTED="SELECTED">'.$templateName.'</option>';
         }
         else {
           $returnValues .='<option value="'.$ids.'">'.$templateName.'</option>';
         }
      }
      return $returnValues;
   }
} 

 public function getTeacherList($selected='') {
     
        $results = CommonQueryManager::getInstance()->getTeacherDataList();

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $employeeName = ucwords(strip_slashes($results[$i]['employeeNameCode']));
                if($results[$i]['employeeId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'" SELECTED="SELECTED">'.$employeeName.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['employeeId'].'">'.$employeeName.'</option>';
                }
            }

        }
        return $returnValues;
   }
   
   public function getClassDataForAlumni($selected='',$condition='',$orderBy='') {
       
        $results = CommonQueryManager::getInstance()->getClassDataForAlumniList($condition,$orderBy);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $className = strip_slashes($results[$i]['className']);
                if($results[$i]['classId']==$selected) {
                  $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.$className.'</option>';
                }
                else {
                  $returnValues .='<option value="'.$results[$i]['classId'].'">'.$className.'</option>';
                }
            }

        }
        return $returnValues;
   }
   
   public function getFineClass($selected='',$condition='') {
     
        $results = CommonQueryManager::getInstance()->getFineClassList($condition);

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                $instituteClassName = $results[$i]['instituteClassName'];
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.$instituteClassName.'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.$instituteClassName.'</option>';
                }
            }

        }
        return $returnValues;
   }
   
   public function getLoginClass($conditions='',$selected='') {
     
        $results = CommonQueryManager::getInstance()->getLoginClassList($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
              $combineName = strip_slashes($results[$i]['combineName']);  
              if($results[$i]['combineId']==$selected) {
                $returnValues .='<option value="'.$results[$i]['combineId'].'" SELECTED="SELECTED">'.$combineName.'</option>';
              }
              else {
                $returnValues .='<option value="'.$results[$i]['combineId'].'">'.$combineName.'</option>';
              }
          }
          return $returnValues;
       }
   } 

    public function getLoginInstitute($conditions='',$selected='') {
     
        $results = CommonQueryManager::getInstance()->getLoginInstituteList($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
              $combineName = strip_slashes($results[$i]['combineName']);  
              if($results[$i]['combineId']==$selected) {
                $returnValues .='<option value="'.$results[$i]['combineId'].'" SELECTED="SELECTED">'.$combineName.'</option>';
              }
              else {
                $returnValues .='<option value="'.$results[$i]['combineId'].'">'.$combineName.'</option>';
              }
          }
          return $returnValues;
       }
   } 
   
   public function getHostelNameRoomType($conditions='',$selected='') {
     
        $results = CommonQueryManager::getInstance()->fetchHostelRoomTypes($conditions);
        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
              $combineName = strip_slashes($results[$i]['hostelRoomType']);  
              if($results[$i]['hostelRoomTypeId']==$selected) {
                $returnValues .='<option value="'.$results[$i]['hostelRoomTypeId'].'" SELECTED="SELECTED">'.$combineName.'</option>';
              }
              else {
                $returnValues .='<option value="'.$results[$i]['hostelRoomTypeId'].'">'.$combineName.'</option>';
              }
          }
          return $returnValues;
       }
   } 
  public function getRouteStopRegistration($selected='',$condition='') {
        
      $results = CommonQueryManager::getInstance()->getRouteStopRegistration(' br.routeName, bs.stopName',$condition);
      $returnValues = '';
      if(isset($results) && is_array($results)) {
        $count = count($results);
        for($i=0;$i<$count;$i++) {
            $busRouteStopMappingId = trim($results[$i]['busRouteStopMappingId']);
            $routeName = trim($results[$i]['routeName']);
            $stopName = trim($results[$i]['stopName']);
            $val = $busRouteStopMappingId."!~~!!~~!".$routeName."!~~!!~~!".$stopName; 
            if($routeName==$selected) {
              $returnValues .='<option value="'.$val.'" SELECTED="SELECTED">'.$routeName.'</option>';
            }
            else {
              $returnValues .='<option value="'.$val.'">'.$routeName.'</option>';
            }
        }
      }
      return $returnValues;
   }
  
   public function getHostelRegistrationData($selected='',$condition='') {
      $results = CommonQueryManager::getInstance()->getHostelRegistration('hostelName',$condition);
      $returnValues = '';
      if(isset($results) && is_array($results)) {
        $count = count($results);
        for($i=0;$i<$count;$i++) {
            $hostelId = trim($results[$i]['hostelId']);
            $hostelName = trim($results[$i]['hostelName']);
            $wardenName = trim($results[$i]['wardenName']);
            $wardenContactNo = trim($results[$i]['wardenContactNo']);
            $val = $hostelId."!~~!!~~!".$hostelName."!~~!!~~!".$wardenName."!~~!!~~!".$wardenContactNo; 
            if($hostelName==$selected) {
              $returnValues .='<option value="'.$val.'" SELECTED="SELECTED">'.$hostelName.'</option>';
            }
            else {
              $returnValues .='<option value="'.$val.'">'.$hostelName.'</option>';
            }
        }
      }
      return $returnValues;
   }
   
   
   
   
   
   public function getResultClassData($selected='') {
        $results = CommonQueryManager::getInstance()->getResultClass('cls.degreeId,cls.branchId,cls.studyPeriodId');

        $returnValues = '';
        if(isset($results) && is_array($results)) {
            $count = count($results);
            for($i=0;$i<$count;$i++) {
                if($results[$i]['classId']==$selected) {
                    $returnValues .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
                }
                else {
                    $returnValues .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
                }
            }

        }
        return $returnValues;
   }
   
   
    
   
}
?>
