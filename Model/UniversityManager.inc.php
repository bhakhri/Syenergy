<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "university" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class UniversityManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "UniversityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "UniversityManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// THIS FUNCTION IS USED FOR ADDING AN UNIVERSITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Modified on: 7.7.2008
// Modified By: Dipanjan Bhattacharjee
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addUniversity() {
        global $REQUEST_DATA;
        //contactPerson & logo is not is not inserted
        $query = 'INSERT INTO university (`universityCode`,`universityName`,`universityAbbr`,`universityAddress1`,`universityAddress2`,`cityId`,
        `stateId`,`countryId`,`pin`,`designationId`,`contactPerson`,`contactNumber`,`universityEmail`,`universityWebsite`) VALUES("'.add_slashes(trim(strtoupper($REQUEST_DATA['universityCode']))).'","'.add_slashes(trim($REQUEST_DATA['universityName'])).'","'.add_slashes(trim($REQUEST_DATA['universityAbbr'])).'","'.add_slashes(trim($REQUEST_DATA['universityAddress1'])).'","'.add_slashes(trim($REQUEST_DATA['universityAddress2'])).'",'.$REQUEST_DATA['city'].', '.$REQUEST_DATA['states'].','.$REQUEST_DATA['country'].',"'.add_slashes(trim($REQUEST_DATA['pin'])).'",'.( (trim($REQUEST_DATA['designation'])=='SELECT' || trim($REQUEST_DATA['designation'])=='' ) ? 'NULL' : $REQUEST_DATA['designation']).',"'.add_slashes($REQUEST_DATA['contactPerson']).'","'.add_slashes(trim($REQUEST_DATA['contactNumber'])).'","'.add_slashes(trim($REQUEST_DATA['universityEmail'])).'","'.add_slashes(trim($REQUEST_DATA['universityWebsite'])).'"); ';
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        
        /*return SystemDatabaseManager::getInstance()->runAutoInsert('university', 
        array('universityCode','universityName','universityAbbr','universityAddress1','universityAddress2','cityId',
        'stateId','countryId','pin','designationId','contactNumber','universityEmail','universityWebsite'), 
        array(strtoupper($REQUEST_DATA['universityCode']),$REQUEST_DATA['universityName'],$REQUEST_DATA['universityAbbr'],
        $REQUEST_DATA['universityAddress1'],$REQUEST_DATA['universityAddress2'],$REQUEST_DATA['city'],
        $REQUEST_DATA['states'],$REQUEST_DATA['country'],$REQUEST_DATA['pin'],
        (trim($REQUEST_DATA['designation'])=='' ? 'NULL' : $REQUEST_DATA['designation']==''),$REQUEST_DATA['contactNumber'],
        $REQUEST_DATA['universityEmail'],$REQUEST_DATA['universityWebsite']) ); */
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A UNIVERSITY 
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editUniversity($id) {
        global $REQUEST_DATA;

        $query = 'UPDATE university SET `universityCode`="'.add_slashes(trim(strtoupper($REQUEST_DATA['universityCode']))).'",`universityName`="'.add_slashes(trim($REQUEST_DATA['universityName'])).'",`universityAbbr`="'.add_slashes(trim($REQUEST_DATA['universityAbbr'])).'",`universityAddress1`="'.add_slashes(trim($REQUEST_DATA['universityAddress1'])).'",`universityAddress2`="'.add_slashes(trim($REQUEST_DATA['universityAddress2'])).'",`cityId`='.$REQUEST_DATA['city'].', `stateId`='.$REQUEST_DATA['states'].',`countryId`='.$REQUEST_DATA['country'].',`pin`="'.add_slashes(trim($REQUEST_DATA['pin'])).'",`designationId`='.( (trim($REQUEST_DATA['designation'])=='SELECT' || trim($REQUEST_DATA['designation'])=='' ) ? 'NULL' : $REQUEST_DATA['designation']).',`contactPerson`="'.add_slashes(trim($REQUEST_DATA['contactPerson'])).'",`contactNumber`="'.add_slashes(trim($REQUEST_DATA['contactNumber'])).'",`universityEmail`="'.add_slashes(trim($REQUEST_DATA['universityEmail'])).'",`universityWebsite`="'.add_slashes(trim($REQUEST_DATA['universityWebsite'])).'", contactPerson="'.( (trim($REQUEST_DATA['contactPerson'])=='SELECT' || trim($REQUEST_DATA['contactPerson'])=='' ) ? 'NULL' : $REQUEST_DATA['contactPerson']).'" WHERE universityId='.$id.'; ';
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");


        //contactPerson & logo is not is not edited
      /*  return SystemDatabaseManager::getInstance()->runAutoUpdate('university', 
        array('universityCode','universityName','universityAbbr','universityAddress1','universityAddress2','cityId',
        'stateId','countryId','pin','designationId','contactNumber','universityEmail','universityWebsite'), 
        array(strtoupper($REQUEST_DATA['universityCode']),$REQUEST_DATA['universityName'],$REQUEST_DATA['universityAbbr'],
        $REQUEST_DATA['universityAddress1'],$REQUEST_DATA['universityAddress2'],$REQUEST_DATA['city'],
        $REQUEST_DATA['states'],$REQUEST_DATA['country'],$REQUEST_DATA['pin'],
        $REQUEST_DATA['designation'],$REQUEST_DATA['contactNumber'],
        $REQUEST_DATA['universityEmail'],$REQUEST_DATA['universityWebsite']), "universityId=$id" );
        */
    }
        
    /*
    @@ purpose: To update filename(for logo image) in 'university' table
    @@ author: Dipanjan Bhattacharjee
    @@ Params: Id (University ID), filename (name of the file)
    @@ created On: 23.06.2008
    @@ returns: boolean value
    */
    public function updateLogoFilenameInUniversity($id, $fileName) {
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('university', 
        array('universityLogo'), 
        array($fileName), "universityId=$id" );
    }    
    
      
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING INSIITUTE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getUniversity($conditions='') {
        $query = "SELECT universityId,universityCode,universityName,universityAbbr,universityLogo,universityAddress1,universityAddress2,cityId,
        stateId,countryId,pin,designationId,contactPerson,contactNumber,universityEmail,universityWebsite 
        FROM university
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS UNIVERSITYID EXISTS IN CLASS TABLE OR NOT(DELETE CHECK)
//
//$universityId :universityId of the University
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInClass($universityId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM class 
        WHERE universityId=$universityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

 //-------------------------------------------------------------------------------------------------------
// Purpose: to check logo file name if it exists in db
// params: universityId of the University
// return: array 
// Author :Dipanjan Bhattacharjee
// Created on : (7.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkLogoName($universityId) {
     
        $query = "SELECT universityLogo  
        FROM university 
        WHERE universityId=$universityId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING AN UNIVERSITY
//
//$universityid :universityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteUniversity($universityid) {
     
        $query = "DELETE 
        FROM university
        WHERE universityId=$universityid";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING UNIVERSITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getUniversityList($conditions='', $limit = '', $orderBy=' un.universityName') {
     
        //no joining is done with employee and designation table
        
        /*$query = "SELECT un.universityId,un.universityCode,un.universityName,un.universityAbbr,un.universityLogo,
        un.universityAddress1,un.universityAddress2,ct.cityName,
        st.stateName,un.pin,cn.countryName,dg.designationName,un.contactPerson,un.contactNumber,
        un.universityEmail,un.universityWebsite
        FROM university ins, states st,city ct,designation dg ,countries cn
        WHERE un.stateId=st.stateId AND un.cityId=ct.cityId AND un.designationId=dg.designationId AND un.countryId=cn.countryId
        $conditions 
        ORDER BY $orderBy $limit"; */
        
        $query = "SELECT un.universityId,un.universityCode,un.universityName,un.universityAbbr,un.universityLogo,
        un.universityAddress1,un.universityAddress2,ct.cityName,
        st.stateName,un.pin,cn.countryName,un.contactPerson,un.contactNumber,
        un.universityEmail,un.universityWebsite
        FROM university un, states st,city ct,countries cn
        WHERE un.stateId=st.stateId AND un.cityId=ct.cityId  AND un.countryId=cn.countryId
        $conditions 
        ORDER BY $orderBy $limit" ;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF UNIVERSITYS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalUniversity($conditions='') {
        //no joining is done with employee and designation table
                  
        /*$query = "SELECT COUNT(*) AS totalRecords 
        FROM university un, states st,city ct,designation dg ,countries cn
        WHERE un.stateId=st.stateId AND un.cityId=ct.cityId AND un.designationId=dg.designationId AND un.countryId=cn.countryId
        $conditions "; */
        
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM university un, states st,city ct,countries cn
        WHERE un.stateId=st.stateId AND un.cityId=ct.cityId  AND un.countryId=cn.countryId
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
// $History: UniversityManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/10/09   Time: 13:31
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//00001787,00001788,00001789
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/17/08    Time: 10:57a
//Updated in $/Leap/Source/Model
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:47p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Model
//Added Image upload functionality
//
//*****************  Version 6  *****************
//User: Dipanjan Bhattacharjee   Date: 7/07/08    Time: 8:20p
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
//User: Dipanjan Bhattacharjee   Date: 6/23/08    Time: 5:21p
//Updated in $/Leap/Source/Model
//added the function updateLogoFilenameInUniversity
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
