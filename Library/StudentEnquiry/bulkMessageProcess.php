    <?php
    //-------------------------------------------------------
    // THIS FILE IS USED TO send message to students by admin
    //
    //
    // Author : Parveen Sharma
    // Created on : (21.7.2008 )
    // Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------


    /*
    * ////////////////////////////////////////////////////////////////
    * $argv[1]=UserId
    * $argv[2]=InstituteId
    * $argv[3]=SessionId
    * $argv[4]=>StudentIds
    * $argv[5]=>MessageMedium 
    * $argv[6]=>Message Body
    * $argv[7]=>Message Subject
    * $argv[8]=>ADMIN_EMAIL
    * $argv[9]=>DB_HOST
    * $argv[10]=>DB_USER
    * $argv[11]=>DB_PWD
    * $argv[12]=>DB_NAME
    * $argv[13]=SMS_MAX_LENGTH
    * $argv[14]=SMS_GATEWAY_USER_VARIABLE
    * $argv[15]=SMS_GATEWAY_PASS_VARIABLE
    * $argv[16]=SMS_GATEWAY_NUMBER_VARIABLE
    * $argv[17]=SMS_GATEWAY_MESSAGE_VARIABLE
    * $argv[18]=SMS_GATEWAY_SNDR_VARIABLE
    * $argv[19]=SMS_GATEWAY_SNDR_VALUE
    * $argv[20]=SMS_GATEWAY_USERNAME
    * $argv[21]=SMS_GATEWAY_PASSWORD
    * $argv[22]=SMS_GATEWAY_URL
    * $argv[23]=NUMBER OF LOOPS BEFORE A SLEEP
    * $argv[24]= AMOUNT OF SLEEP 
    * $argv[25]= FROM DATE
    * $argv[26]= TO DATE
    * $argv[27]= ConsulerId
    * $argv[28]= imagePath
    * /////////////////////////////////////////////////////////////////
    */

   /*
    if(COUNSELING_MAIL==0) {             
        //  For Local Testing -- Start    
        $argv = explode("\~", $argStr);
        $newArray = array();
        $i=1;
        foreach($argv as $value) {
            if(trim($value) != '') {
                $newArray[$i] = strip_slashes($value);
                $i++;
            }
        }
        $argv = $newArray;
        // For Local Testing -- End
    }
   */ 
    //if($argc < 24 ){ //if number of arguments is less than 24
    //  exit;
    //}
    
    function isEmail($str=''){
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $str)){
            return 0;
        } else{
            return 1;
        }
    }    

$cInstructions = $argv[29];  ;
$cAddress = $argv[30];

$msgSubject = "Counseling Scheduling Demo Mail";  
$msgBody = "<html>
                <head>
                  <title>Counseling Report</title>
                  <style type='text/css'>
                     .innerheading{font-family:Verdana, Arial, Helvetica, sans-serif; 
                                   font-size:12px; color:#68881e; border-left:#a1c058 4px solid; 
                                   font-weight:bold; padding-left:10px; margin-left:5px; margin-bottom:10px;}
                                   
                     .reportData{FONT-WEIGHT: normal; FONT-SIZE: 12px; COLOR: #000000; FONT-FAMILY: Arial, Helvetica, sans-serif}
                  </style>                 
                </head>
                <body>
                   <table border='0' width='80%' align='left' cellpadding='5px' cellspacing='2px' class='reportData'>  
                        <tr>
                            <td height='10px' align='center'>
                                 <imageShow><br><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Dear <studentName>,<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding-left:20px'>
                                We are pleased to inform you that your date of counseling has been scheduled on <b><startDate> to <endDate></b> at <b><scheduleTime><b>.
                            </td>
                        </tr>
                        <tr>    
                            <td>
                            <br><br>
                            ".nl2br($cInstructions)."
                            </td>
                        </tr>
                        <tr>
                            <td align='center'><br><Br>
                                <div class='innerheading'><font color='#000000'>
                                  <b>VENUE</b><br>".nl2br($cAddress)." 
                                </div>    
                            </td>
                        </tr>
                   </table>   
                 </body>
            </html>";      
    

    $connection=mysql_connect($argv[9],$argv[10],$argv[11]);
    $selDb=mysql_select_db($argv[12],$connection);

    // SMS variables & max length detail 
    $userId      =$argv[1];  
    $instituteId =$argv[2]; 
    $sessionId   =$argv[3];

    //-------------------------------------------------------
    // THIS FUNCTION IS USED FOR sending SMS
    //
    //$conditions :db clauses
    // Author :Parveen Sharma 
    // Created on : (19.7.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------  
     function sendSMS($mobileNo, $message) {
        // $mobileNo = '9878425461,9855094422';
             global $argv;
            $postVars = $argv[14].'='.$argv[20].'&'.$argv[15].'='.$argv[21].'&'.$argv[16].'='.$mobileNo.'&'.$argv[18].'='.$argv[19].'&'.$argv[17].'='.$message;  
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $argv[22]); //set the url
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
            curl_setopt($ch, CURLOPT_POST, 1); //set POST method
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars); //set the POST variables
            $response = curl_exec($ch); //run the whole process and return the response
            curl_close($ch); //close the curl handle
            if(preg_match("/failure/i",$response)) {
                logError('SMS Response: '.$response);
                return false;
            }
            else {
                return true;
            }
        } 


    //-------------------------------------------------------
    // THIS FUNCTION IS USED FOR sending email 
    //
    //$conditions :db clauses
    // Author :Parveen Sharma 
    // Created on : (8.7.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------  
    function sendEmail($from,$to,$msgSubject,$msgBody,$img){           
         $headers  .= 'From: '.$from.' '. "\r\n" ;    
         $headers  .= 'MIME-Version: 1.0' . "\r\n";
         $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";          
         
         //$imgLogo = $img."/logo.gif";
         //$fp       = fopen($imgLogo, "rb");
         //$file     = fread($fp, filesize($imgLogo));
         //$fileData = chunk_split(base64_encode($file));
         
         //return UtilityManager::sendMail($to, $msgSubject, $msgBody, $headers);
         return @mail($to, $msgSubject, $msgBody, $headers);
    }
    
    
   function formatDate($date = '',$showTime=false) {
           if(trim($date)=='') {
               return '--';
           }
           else {
              //format is yyyy-mm-dd
               
               $yy = substr($date,0,4);
               $mm = abs(substr($date,5,2));
               $dd = substr($date,8,2);
               
               if($showTime){    

                   $hr = substr($date,11,2);
                   $min = substr($date,14,2);
                   //echo "<br>";
                   return date("d-M-y H:i", mktime($hr, $min, 0,$mm,$dd,$yy));

               }
               else{
               
                   return date("d-M-y",mktime(0,0,0,$mm,$dd,$yy));
               }
          }
    }  

     
    //fetch student email/+mobile nos 
    function getStudentEmailMobileNoList($conditions){
        global $connection;

        $query="SELECT 
                    studentId, CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName, 
                    IFNULL(studentEmail,'') AS studentEmail, IFNULL(studentMobileNo,'') AS  studentMobileNo
                FROM 
                    student_enquiry 
                $conditions ";
        
        $result = mysql_query($query, $connection);
        $rows = Array();
        while ($row = mysql_fetch_assoc($result)) {            
               $rows[] = $row;
        }
        return $rows;        
    } 

    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR inserting sms/email records sent sms/email to students/+employees
    //
    //$conditions :db clauses
    // Author :Parveen Sharma 
    // Created on : (19.07.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------         
     function adminMessageEmailSMSRecord($conditions='') {
         global $connection;
         $query= "UPDATE `student_enquiry` SET ".$conditions;
         $result= mysql_query($query, $connection); 
     }         
        
    //-------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR cal culating no of sms based on sms max lengthsending SMS
    //
    //$conditions :db clauses
    // Author :Parveen Sharma 
    // Created on : (21.7.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------------  
    $smsArr=array();  //will contain smss(each of sms_max_length or less)
    function smsCalculation($value,$limit){
     $temp1=$value;
     $nos=1;
     global $smsArr;
     $smsArr[0]=substr($value,0,$limit);
     while(strlen($temp1) > $limit){
         $temp1=substr($temp1,$limit);
         $smsArr[$nos]=substr($temp1,0,$limit);
         $nos=$nos+1;
     }
     return $nos;
    } 
        

    $insQuery=""; 
    //$curDate=date('Y')."-".date('m')."-".date('d');
    $curDate=date('Y-m-d h:i:s');
    $currentDate = date('Y-m-d'); 
    $yy = date('Y'); 
    $sms=0;$email=0;


    $errorMessage='';
    $sl=0;

    $studentEmailMobilesArr=getStudentEmailMobileNoList(" WHERE studentId IN(".$argv[4].")"); 
    $cnt=count($studentEmailMobilesArr);
    
    $msgMedium=split("," , $argv[5]); 
    if($msgMedium[0]==1){
    //calculate and prepare smses based on sms_max_length
    $smsNo=smsCalculation(strip_tags($msgBody),$argv[13]);   
    }
    $mCnt=count($msgMedium);
    //$errorMessage = SUCCESS;


    $sl=0;
    if($cnt > 0 and is_array($studentEmailMobilesArr)){
       for($i=0; $i < $cnt ; $i++) {
         // Sleeping time
         if($sl==$argv[23]){
           $sl=0;
           sleep($argv[24]);
         }
         else{
           $sl++;
         }
         $sms=0;$email=0;
         $studentId   = $studentEmailMobilesArr[$i]['studentId'];
         $studentName = $studentEmailMobilesArr[$i]['studentName'];   
         $email = trim($studentEmailMobilesArr[$i]['studentEmail']);
         
         if($email=='') {
           $email = NOT_APPLICABLE_STRING;   
         }
         if(isEmail(trim($email))){
           $imgLogo = "<img src='".$argv[28]."/logo.gif' />";   
           $msgs = $argv[7];
           $startDate = formatDate($argv[25]);
           $endDate = formatDate($argv[26]);
           $time = "9:30 AM to 5:00 PM";
           $img = formatDate($argv[25]); 
           $img = $argv[28];
           $msgs = str_replace("<studentName>",ucwords(trim($studentName)),$msgs);
           $msgs = str_replace("<startDate>",$startDate,$msgs);
           $msgs = str_replace("<endDate>",$endDate,$msgs);
           $msgs = str_replace("<scheduleTime>",$time,$msgs); 
           $msgs = str_replace("<imageShow>",$imgLogo,$msgs); 
           
           sendEmail($argv[8],$email,$argv[7],$msgs,$img); 
           $insQuery=" `candidateStatus`      = '5',
                       `counselingDate_start` = '".$argv[25]."',
                       `counselingDate_end`   = '".$argv[26]."',
                       `counselingId`         = '".$argv[27]."',
                       `scheduleId`           = '".$yy.$studentId."' WHERE studentId = '".$studentId."'";
           $returnStatus= adminMessageEmailSMSRecord($insQuery); //add the record in database   
           $email=1;
         }
         else{
           $email=0;
         } 
      }  //end of forloop
    }
     
    echo $errorMessage;  
    
// $History: bulkMessageProcess.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Library/StudentEnquiry
//validation and format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/24/10    Time: 4:05p
//Created in $/LeapCC/Library/StudentEnquiry
//initial checkin
//

?>