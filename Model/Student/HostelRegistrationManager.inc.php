<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class HostelRegistrationManager {
	private static $instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}


public function getStudentFullDetails($studentId='') {
    
        $query = "SELECT 
				 		  st.studentId,st.classId,c.className,st.rollNo,CONCAT(IFNULL(st.firstName,''),' ',IFNULL(st.lastName,'')) AS studentName
                                    
                   FROM 
                      	class c, student st 
                        
                   WHERE                   	
						 st.classId = c.classId			  	
                 		   AND st.studentId = '$studentId' ";
	            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getStudentHostelDetailsCheck($studentId='',$classId='') {
    
        $query = "SELECT 
                       h.hostelCode, h.hostelName, c.className,hs.classId AS hostelClassId,               
                        CONCAT(h.hostelName,'-',r.roomName,' (',hrt.roomType,')') AS hostelDetails,r.roomName as roomName,
                        hs.dateOfCheckIn,hs.dateOfCheckOut,hs.studentId,h.hostelName,hrt.roomType,
                         If(hs.dateOfCheckIn='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(hs.dateOfCheckIn,'%d-%b-%y')) AS checkInDate,
                        If(hs.dateOfCheckOut='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(hs.dateOfCheckOut,'%d-%b-%y')) AS checkOutDate        
               		FROM 
                          class c, hostel_room_type hrt,hostel h,hostel_room r,
                           hostel_students hs 
                        
                   WHERE                       
                          hrt.hostelRoomTypeId = r.hostelRoomTypeId  
                          AND hs.hostelRoomId=r.hostelRoomId
                           AND h.hostelId=r.hostelId                  
                            AND hs.studentId = '$studentId'
                            AND hs.classId = '$classId' 
                      GROUP BY
                      hs.studentId,hs.classId ";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getStudentHostelRegistration($studentId='',$classId='',$roomTypeId='') {
    
        $query = "INSERT INTO 
       		`hostel_registration`(studentId,classId,dateOfEntry,registrationStatus,roomTypeId) 
        				values('$studentId','$classId',now(),'0','$roomTypeId')  ";
	
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
    
    public function getPreviousHostelRegistration($studentId='',$classId='') {
    
        $query = "SELECT 
        				studentId,classId,dateOfEntry,registrationStatus,roomTypeId,
        				wardenComments, wardenCommentDate 
        			 FROM 
        				`hostel_registration` 
        		WHERE 
        			studentId ='$studentId' AND classId ='$classId'";
        			
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function deletePreviousHostelRegistration($studentId='',$classId='') {
    
        $query = "UPDATE  
        				`hostel_registration`
        				SET isConfrim ='1'				        			 
        		WHERE 
        			studentId ='$studentId' AND classId = '$classId' ";
					
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
 public function getRoomTypeData($conditions='') {
     
        $query = "SELECT 
                         DISTINCT hrt.hostelRoomTypeId, hrt.roomType
                  FROM 
                         hostel_room_type hrt, hostel_room_type_detail hrtd, hostel h
                  WHERE 
                        h.hostelId = hrtd.hostelId
                        AND hrtd.hostelRoomTypeId = hrt.hostelRoomTypeId
                        $conditions
                        ORDER BY  hrt.roomType
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
 
}


//

?>
