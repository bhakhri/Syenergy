<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "institute" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class InstituteManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "InstituteManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "InstituteManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING AN INSTITUTE
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Modified on: 7.7.2008
// Modified By: Pushpender
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addInstitute() {
        global $REQUEST_DATA;
        //employeeId & logo is not is not inserted
        $query = 'INSERT INTO institute (`instituteCode`,`instituteName`,`instituteAbbr`,`instituteAddress1`,`instituteAddress2`,`cityId`,
        `stateId`,`countryId`,`pin`,`designationId`,`employeePhone`,`instituteEmail`,`instituteWebsite`) VALUES("'.add_slashes(strtoupper($REQUEST_DATA['instituteCode'])).'","'.add_slashes($REQUEST_DATA['instituteName']).'","'.add_slashes($REQUEST_DATA['instituteAbbr']).'","'.add_slashes($REQUEST_DATA['instituteAddress1']).'","'.add_slashes($REQUEST_DATA['instituteAddress2']).'",'.$REQUEST_DATA['city'].', '.$REQUEST_DATA['states'].','.$REQUEST_DATA['country'].',"'.add_slashes($REQUEST_DATA['pin']).'",'.( (trim($REQUEST_DATA['designation'])=='SELECT' || trim($REQUEST_DATA['designation'])=='' ) ? 'NULL' : $REQUEST_DATA['designation']).',"'.add_slashes($REQUEST_DATA['employeePhone']).'","'.add_slashes($REQUEST_DATA['instituteEmail']).'","'.add_slashes($REQUEST_DATA['instituteWebsite']).'"); ';
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        
        /*return SystemDatabaseManager::getInstance()->runAutoInsert('institute', 
        array('instituteCode','instituteName','instituteAbbr','instituteAddress1','instituteAddress2','cityId',
        'stateId','countryId','pin','designationId','employeePhone','instituteEmail','instituteWebsite'), 
        array(strtoupper($REQUEST_DATA['instituteCode']),$REQUEST_DATA['instituteName'],$REQUEST_DATA['instituteAbbr'],
        $REQUEST_DATA['instituteAddress1'],$REQUEST_DATA['instituteAddress2'],$REQUEST_DATA['city'],
        $REQUEST_DATA['states'],$REQUEST_DATA['country'],$REQUEST_DATA['pin'],
        (trim($REQUEST_DATA['designation'])=='' ? 'NULL' : $REQUEST_DATA['designation']==''),$REQUEST_DATA['employeePhone'],
        $REQUEST_DATA['instituteEmail'],$REQUEST_DATA['instituteWebsite']) ); */
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A INSTITUTE 
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editInstitute($id) {
        global $REQUEST_DATA;

        $query = 'UPDATE institute SET `instituteCode`="'.add_slashes(strtoupper($REQUEST_DATA['instituteCode'])).'",`instituteName`="'.add_slashes($REQUEST_DATA['instituteName']).'",`instituteAbbr`="'.add_slashes($REQUEST_DATA['instituteAbbr']).'",`instituteAddress1`="'.add_slashes($REQUEST_DATA['instituteAddress1']).'",`instituteAddress2`="'.add_slashes($REQUEST_DATA['instituteAddress2']).'",`cityId`='.$REQUEST_DATA['city'].', `stateId`='.$REQUEST_DATA['states'].',`countryId`='.$REQUEST_DATA['country'].',`pin`="'.add_slashes($REQUEST_DATA['pin']).'",`designationId`='.( (trim($REQUEST_DATA['designation'])=='SELECT' || trim($REQUEST_DATA['designation'])=='' ) ? 'NULL' : $REQUEST_DATA['designation']).',`employeePhone`="'.add_slashes($REQUEST_DATA['employeePhone']).'",`instituteEmail`="'.add_slashes($REQUEST_DATA['instituteEmail']).'",`instituteWebsite`="'.add_slashes($REQUEST_DATA['instituteWebsite']).'", employeeId='.( (trim($REQUEST_DATA['employeeId'])=='SELECT' || trim($REQUEST_DATA['employeeId'])=='' ) ? 'NULL' : $REQUEST_DATA['employeeId']).' WHERE instituteId='.$id.'; ';
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");


        //employeeId & logo is not is not edited
      /*  return SystemDatabaseManager::getInstance()->runAutoUpdate('institute', 
        array('instituteCode','instituteName','instituteAbbr','instituteAddress1','instituteAddress2','cityId',
        'stateId','countryId','pin','designationId','employeePhone','instituteEmail','instituteWebsite'), 
        array(strtoupper($REQUEST_DATA['instituteCode']),$REQUEST_DATA['instituteName'],$REQUEST_DATA['instituteAbbr'],
        $REQUEST_DATA['instituteAddress1'],$REQUEST_DATA['instituteAddress2'],$REQUEST_DATA['city'],
        $REQUEST_DATA['states'],$REQUEST_DATA['country'],$REQUEST_DATA['pin'],
        $REQUEST_DATA['designation'],$REQUEST_DATA['employeePhone'],
        $REQUEST_DATA['instituteEmail'],$REQUEST_DATA['instituteWebsite']), "instituteId=$id" );
        */
    }
        
    /*
    @@ purpose: To update filename(for logo image) in 'institute' table
    @@ author: Pushpender Kumar Chauhan
    @@ Params: Id (Institute ID), filename (name of the file)
    @@ created On: 23.06.2008
    @@ returns: boolean value
    */
    public function updateLogoFilenameInInstitute($id, $fileName) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('institute', 
        array('instituteLogo'), 
        array($fileName), "instituteId=$id" );
    }    
    
      
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSIITUTE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getInstitute($conditions='') {
        $query = "SELECT instituteId,instituteCode,instituteName,instituteAbbr,instituteLogo,instituteAddress1,instituteAddress2,cityId,
        stateId,countryId,pin,designationId,
        IF(employeeId IS NULL,-1,employeeId) AS employeeId,employeePhone,instituteEmail,instituteWebsite 
        FROM institute
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS INSTITUTEID EXISTS IN CLASS TABLE OR NOT(DELETE CHECK)
//
//$instituteId :instituteId of the Institute
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInClass($instituteId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM class 
        WHERE instituteId=$instituteId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS INSTITUTEID EXISTS IN BATCH TABLE OR NOT(DELETE CHECK)
//
//$instituteId :instituteId of the Institute
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInBatch($instituteId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM batch
        WHERE instituteId=$instituteId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS INSTITUTEID EXISTS IN EMPLOYEE TABLE OR NOT(DELETE CHECK)
//
//$instituteId :instituteId of the Institute
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInEmployee($instituteId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM employee 
        WHERE instituteId=$instituteId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }        
 //-------------------------------------------------------------------------------------------------------
// Purpose: to check logo file name if it exists in db
// params: instituteId of the Institute
// return: array 
// Author :Pushpender Kumar Chauhan
// Created on : (7.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkLogoName($instituteId = '') {
     
		if(SUBSCRIPTION_STATUS=='PENDING'){
			$query = "SELECT IF(instituteLogo IS NULL,'subscription.png','subscription.png') instituteLogo,instituteName
			FROM institute 
			WHERE instituteId='$instituteId'";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
		else{
			$query = "SELECT instituteLogo,instituteName  
			FROM institute 
			WHERE instituteId='$instituteId'";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
	}
	

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING AN INSTITUTE
//
//$universityid :universityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteInstitute($instituteid) {
     
        $query = "DELETE 
        FROM institute
        WHERE instituteId=$instituteid";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSTITUTE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getInstituteList($conditions='', $limit = '', $orderBy=' ins.instituteName') {
     
        //no joining is done with employee and designation table
        
        /*$query = "SELECT ins.instituteId,ins.instituteCode,ins.instituteName,ins.instituteAbbr,ins.instituteLogo,
        ins.instituteAddress1,ins.instituteAddress2,ct.cityName,
        st.stateName,ins.pin,cn.countryName,dg.designationName,ins.employeeId,ins.employeePhone,
        ins.instituteEmail,ins.instituteWebsite
        FROM institute ins, states st,city ct,designation dg ,countries cn
        WHERE ins.stateId=st.stateId AND ins.cityId=ct.cityId AND ins.designationId=dg.designationId AND ins.countryId=cn.countryId
        $conditions 
        ORDER BY $orderBy $limit"; */
        
        $query = "SELECT ins.instituteId,ins.instituteCode,ins.instituteName,ins.instituteAbbr,ins.instituteLogo,
        ins.instituteAddress1,ins.instituteAddress2,ct.cityName,
        st.stateName,ins.pin,cn.countryName,ins.employeeId,ins.employeePhone,
        ins.instituteEmail,ins.instituteWebsite
        FROM institute ins, states st,city ct,countries cn
        WHERE ins.stateId=st.stateId AND ins.cityId=ct.cityId  AND ins.countryId=cn.countryId
        $conditions 
        ORDER BY $orderBy $limit" ;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF INSTITUTES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalInstitute($conditions='') {
        //no joining is done with employee and designation table
                  
        /*$query = "SELECT COUNT(*) AS totalRecords 
        FROM institute ins, states st,city ct,designation dg ,countries cn
        WHERE ins.stateId=st.stateId AND ins.cityId=ct.cityId AND ins.designationId=dg.designationId AND ins.countryId=cn.countryId
        $conditions "; */
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM institute ins, states st,city ct,countries cn
        WHERE ins.stateId=st.stateId AND ins.cityId=ct.cityId  AND ins.countryId=cn.countryId
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	public function getInstituteName($instituteId) {
        $query = "SELECT instituteName  
        FROM institute 
        WHERE instituteId = $instituteId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getInstituteAddress($instituteId) {
        $query = "SELECT instituteAddress1  
        FROM institute 
        WHERE instituteId = $instituteId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getInstituteTelephone($instituteId) {
        $query = "SELECT employeePhone  
        FROM institute 
        WHERE instituteId = $instituteId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS INSTITUTEID EXISTS IN BATCH TABLE OR NOT(DELETE CHECK)
//
//$instituteId :instituteId of the Institute
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function getEmployee($conditions1='',$conditions2='') {
     
            $query = "SELECT
                           e.employeeId,e.employeeName 
                      FROM 
                           employee e 
                           $conditions1
                  UNION
                      SELECT
                           e.employeeId,e.employeeName 
                      FROM 
                           employee e ,employee_can_teach_in ect
                      WHERE
                           e.employeeId=ect.employeeId
                           $conditions2
                   ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   
  
}
// $History: InstituteManager.inc.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 2/15/10    Time: 6:45p
//Updated in $/LeapCC/Model
//fixed bug. 2881
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-02   Time: 2:17p
//Updated in $/LeapCC/Model
//in this logo will be displayed for the institute we logged in
//
//*****************  Version 4  *****************
//User: Administrator Date: 24/07/09   Time: 14:57
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
//
//*****************  Version 3  *****************
//User: Administrator Date: 28/05/09   Time: 12:40
//Updated in $/LeapCC/Model
//Corrected institute module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Model
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/02/08    Time: 3:48p
//Updated in $/Leap/Source/Model
//added functions to fetch institute address for fees receipt
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/07/08    Time: 3:00p
//Updated in $/Leap/Source/Model
//added function getInstituteName()
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:34p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 7/07/08    Time: 8:20p
//Updated in $/Leap/Source/Model
//Replaced code to add and edit
//
//Changed RunAutoInsert to executeUpdate
//and RunAutoUpdate to executeUpdate
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Model
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/23/08    Time: 5:21p
//Updated in $/Leap/Source/Model
//added the function updateLogoFilenameInInstitute
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/17/08    Time: 10:52a
//Updated in $/Leap/Source/Model
//Modifying html Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:20p
//Updated in $/Leap/Source/Model
//Modifying Done
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 12:23p
//Created in $/Leap/Source/Model
//Initial CheckIn
?>
