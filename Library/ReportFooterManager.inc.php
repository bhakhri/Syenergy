<?php
//-------------------------------------------------------
//  This File contains Presentation logic of gazette reports
// Author :Ajinder Singh
// Created on : 08-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
class ReportFooterManager {
    
    public static $instance = null;

    public $tableHeadArray = array();                            //array for table headings
    public $tableDataArray = array();                            //array for table data
    public $reportWidth = 800;                                    //default report width
    public $reportHeading;          
    public $tableFooter;
    public $tableExFooter;
    public $autoShowFooter;                              //reportHeading
    public $reportHeadingStyle = "class = 'headingFont'";
    public $reportDataStyle = " class = 'dataFont'";
    public $reportTitleStyle = " class = 'reportTitle'";
    public $recordsPerPage = 20;
    public $dateTimeStyle = "class = 'reportDateTime'";
    public $footerStyle = "class = 'reportFooter'";
    public $reportInformationStyle = "class = 'reportInformation'";
    public $reportData;
    public $reportInformation;
    private $firstPageText = '';

    public $class1 = "class='searchhead_text'";  
    public       $class2 = "class='padding_top'"; 


   


    public function __construct() {
    }
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }


    /*    Call this function to set the width of report */

    public function setReportWidth($width = 800) {
        $this->reportWidth = $width;
    }

    /* Function used for internal working. Not to be called from outside */

    public function getReportWidth() {
        return $this->reportWidth;
    }

    /* Function called for giving the inputs for reports
        @param tableHeadArray: array for report data headers
        @param tableDataArray: array for report data
    */

     public function setReportData($tableHeadingArray = array(), $tableDataArray = array(),$tableFooter='',$autoShowFooter='Y',$tableExFooter='') {
        $this->tableHeadArray = $tableHeadingArray;
        $this->tableDataArray = $tableDataArray;
        $this->tableFooter =$tableFooter;
        $this->autoShowFooter = $autoShowFooter;
        $this->tableExFooter=$tableExFooter;
    }
    


    /* Function called for setting the report head
        @param reportHead: value for report heading
    */
    public function setReportHeading($reportHead = '') {
        $this->reportHeading = $reportHead;
    }

    /* Function used for internal working, Not to be called from outside
    */
    public function getReportHeading() {
        return $this->reportHeading;
    }

    /* Function used for setting records per page
        @param reportHead: value for report heading
    */
    public function setRecordsPerPage($recordsPerPage = 20) {
        $this->recordsPerPage = $recordsPerPage;
    }

    /* Function used for setting reportTitleStyle
        @param string: value for report title
    */
    public function setReportTitleStyle($string='') {
        $this->reportTitleStyle = $string;
    }

    /* Function used for fetching reportTitleStyle
    */
    public function getReportTitleStyle() {
        return $this->reportTitleStyle;
    }

    /* Function used for setting reportTitleStyle
        @param string: value for report title
    */
    public function setReportHeadingStyle($string='') {
        $this->reportHeadingStyle = $string;
    }

    /* Function used for fetching reportHeadingStyle
    */
    public function getReportHeadingStyle() {
        return $this->reportHeadingStyle;
    }

    /* Function used for setting reportDataStyle
        @param string: value for report title
    */
    public function setReportDataStyle($string='') {
        $this->reportDataStyle = $string;
    }

    /* Function used for fetching reportDataStyle
    */
    public function getReportDataStyle() {
        return $this->reportDataStyle;
    }

    /* Function used for setting reportDateTimeStyle
        @param string: value for report title
    */
    public function setDateTimeStyle($string='') {
        $this->reportHeadingStyle = $string;
    }

    /* Function used for fetching reportDateTimeStyle
    */
    public function getDateTimeStyle() {
        return $this->reportHeadingStyle;
    }

    /* Function used for setting footerStyle
        @param string: value for report title
    */
    public function setFooterStyle($string='') {
        $this->footerStyle = $string;
    }

    /* Function used for fetching footerStyle
    */
    public function getFooterStyle() {
        return $this->footerStyle;
    }

    public function setFirstPageText($firstPageText = '') {
        $this->firstPageText = $firstPageText;
    }

    public function getFirstPageText() {
        return $this->firstPageText;
    }


    /* Function used for making header
    */
    public function showHeader() {
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        $instituteManager =  InstituteManager::getInstance();
        global $sessionHandler;
        //print_r($_SESSION);
        $printReportLogo = $sessionHandler->getSessionVariable('PRINT_REPORT_LOGO');
        $logoArray = $instituteManager->checkLogoName($sessionHandler->getSessionVariable('InstituteId'));
        //echo $logoArray[0]['instituteLogo'];
        if($printReportLogo==''){

            if (isset($logoArray[0]['instituteLogo']) and !empty($logoArray[0]['instituteLogo'])) {

                return '<img name="logo" src="'.IMG_HTTP_PATH.'/Institutes/'.$logoArray[0]['instituteLogo']
                .'" border="0" width="170" height="70" title="'.$this->getInstituteName().'"/>';
            }
            else{

                return '';
            }

        }
        else {
            return '<img name="logo" src="'.IMG_HTTP_PATH.'/Institutes/'.$printReportLogo
                .'" border="0" width="97" height="70" title="'.$this->getInstituteName().'"/>';
        }
    }

    /* Function used for making footer
    */
    public function showFooter() {
        global $sessionHandler;
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        $logoArray = InstituteManager::getInstance()->checkLogoName($sessionHandler->getSessionVariable('InstituteId'));
        return "Generated By : ".$sessionHandler->getSessionVariable("UserName");;
    }

    /* Function used for fetching session institute name
    */
    public function getInstituteName() {
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        global $sessionHandler;
        $instituteNameArray = InstituteManager::getInstance()->getInstituteName($sessionHandler->getSessionVariable('InstituteId'));
        return $instituteNameArray[0]['instituteName'];
    }

    /* Function used for fetching session institute address
    */
    public function getInstituteAddress() {
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        global $sessionHandler;
        $instituteAddressArray = InstituteManager::getInstance()->getInstituteAddress($sessionHandler->getSessionVariable('InstituteId'));
        return $instituteAddressArray[0]['instituteAddress1'];
    }

    /* Function used for fetching session institute address
    */
    public function getInstituteTelephone() {
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        global $sessionHandler;
        $instituteAddressArray = InstituteManager::getInstance()->getInstituteTelephone($sessionHandler->getSessionVariable('InstituteId'));
        return $instituteAddressArray[0]['employeePhone'];
    }

    /* Function used for setting report information
        @param string: value for report title
    */
    public function setReportInformation($reportInformation) {
        $this->reportInformation = $reportInformation;
    }

    /* Function used for getting report information
    */
    public function getReportInformation() {
        return $this->reportInformation;
    }

    /* Function used for setting report information style
        @param string: value for report title
    */
    public function setReportInformationStyle($string='') {
        $this->reportInformationStyle = $string;
    }

    /* Function used for getting report information style
    */
    public function getReportInformationStyle() {
        return $this->reportInformationStyle;
    }
    /*************************************

    FUNCTION TO BE CALLED FOR REPORT PRINTING

    **************************************/

    public function showReport($noRecord = false) {
        $totalRecords = count($this->tableDataArray);
        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;
?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0 or $noRecord === true) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo count($this->tableHeadArray)?>'>
                            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        <?php echo NO_DATA_FOUND ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <?php 
                    
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
         <?php }
             if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
             }  
             else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
    }
    else {
        foreach($this->tableDataArray as $tableDataKey => $tableDataValue) {
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) { ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                    <?php
                        if ($this->getFirstPageText() != '' and $recordCounter == 0) {
                    ?>
                        <tr><td colspan="3" ><?php echo $this->getFirstPageText(); ?></td></tr>
                    <?php
                        }
                    ?>

                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {
                        ?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                            if($tableHeadValue['3']===true) {
                               echo "</tr><tr>"; 
                            }
                        }
                        ?>
                    </tr>
            <?php
            }
            ?>
                <tr>
                    <?php
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {
                       if($tableHeadValue['3']===true) {   
                       }
                       else {
                        ?><td <?php echo $tableHeadValue['2'].' '.$this->getReportDataStyle(); ?>><?php echo $this->tableDataArray[$i][$tableHeadKey]; ?></td>
                    <?php
                       }
                    }
                    ?>
                </tr>
            <?php
            $i++;
            $recordCounter++;

            if ($recordCounter % $this->recordsPerPage == 0) {
            ?>
                </table><br>
                <?php 
                  
                    
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                        <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                    </tr>
                </table>
                <?php
                    }
                    if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                    }
                    else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                ?>
                <br class='page'>
                <?php
                    $pageCounter++;
            }
            else if($recordCounter == $totalRecords) {
?>
                </table><br>
                <?php 
                    
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                        <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                    </tr>
                </table>
<?php
                    }
                     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                     }
                     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
            }
        }
    }
?>
</center>
<?php
    } //end of function

    /*************************************

    FUNCTION TO BE CALLED FOR MARKS DISTRIBUTION REPORT PRINTING

    **************************************/

    public function showTestWiseMarksReport() {
        $totalRecords = count($this->tableDataArray['resultData']);
        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;


        $totalTests = count($this->tableDataArray['testTypes']);
        $allTests = 0;
        for($i=0; $i < $totalTests; $i++) {
            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
            $allTests += $testTypeIdTests;
        }

        if ($allTests == 0) {
            $perTestSpace = '62%';
        }
        else {
            $perTestSpace = intval(62/$allTests).'%';
        }


?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                    <?php
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                        <td colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                    </tr>
                    <tr>
                        <?php
                        for($i=0; $i< $totalTests; $i++) {
                            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                            for($m=0; $m < $testTypeIdTests; $m++) {
                            ?>
                                <td><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?>&nbsp;</td>
                            <?php
                            }
                        }
                    ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo 4 + $allTests?>'>
                            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        No Detail Found
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <?php 
                  
                
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
    <?php }
      if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
      }
      else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
            }
    else {
        $resultDataLength = count($this->tableDataArray['resultData']);
        for($x = 0; $x < $resultDataLength; $x++) {
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) { ?>

            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <?php
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                        <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                    <?php
                    }
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                        for($m=0; $m < $testTypeIdTests; $m++) {
                        ?>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> align="center"><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?></td>
                        <?php
                        }
                    }
                ?>
                </tr>
                <tr>
                    <?php
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                        for($m=0; $m < $testTypeIdTests; $m++) {
                        ?>
                            <th <?php echo $this->getReportHeadingStyle();?> align="right"><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['maxMarks'];?></th>
                        <?php
                        }
                    }
                ?>
                </tr>
            <?php
            }
        ?>
                <tr>
                    <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['srNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['rollNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['universityRollNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['studentName'];?></td>
                    <?php
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                        for($m=0; $m < $testTypeIdTests; $m++) {
                            $thisTest = 'ms' . $this->tableDataArray['testDetails'][$testTypeId][$m]['testId'];
                        ?>
                            <td align="right" width="<?php echo $perTestSpace;?>" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x][$thisTest];?></td>
                        <?php
                        }
                    }
                    ?>
                </tr>
                <?php
                    $recordCounter++;

                    if ($recordCounter % $this->recordsPerPage == 0) {
                    ?>
                        </table><br>
                        <?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
                <?php
                    }
                     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                     }
                     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                    ?>                        
                        <br class='page'>
                        <?php
                            $pageCounter++;
                    }
                    else if($recordCounter == $totalRecords) {
        ?>
                        </table><br>
<?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>                                  
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php
                    }
                     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                     }
                     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                    }
                }
            }
?>
</center>
<?php
    } //end of function

    public function showSubjectWiseConsolidatedReport() {

        $totalRecords = count($this->tableDataArray['data']);
        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;

        $totalSubjects = count($this->tableDataArray['subjects']);
        $perSubjectSpace = intval(88/$totalSubjects).'%';


?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                    <?php
                    for($i=0; $i < $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                        $subjectCode = count($this->tableDataArray['subjects'][$i]['subjectCode']);
                    ?>
                        <td colspan = "<?php echo $totalSubjects; ?>"><?php echo $subjectCode; ?></td>
                    <?php
                    }
                    ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo 3 + $totalSubjects?>'>
                            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        No Detail Found
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
<?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>                          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
    <?php }
      if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
      }
      else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
            }
    else {

        $resultDataLength = count($this->tableDataArray['data']);
        for($x = 0; $x < $resultDataLength; $x++) {
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) { ?>

            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <?php
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                        <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                <?php
                for($i=0; $i < $totalSubjects; $i++) {
                    $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                    $subjectCode = $this->tableDataArray['subjects'][$i]['subjectCode'];
                ?>
                    <td align="center" <?php echo $this->getReportHeadingStyle();?>><?php echo $subjectCode; ?></td>
                <?php
                }
                ?>
                </tr>
            <?php
            }
        ?>
                <tr>
                    <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['data'][$x]['srNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['data'][$x]['rangeLabel'];?></td>
                    <?php
                    for($i=0; $i< $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                        $subjectCode = $this->tableDataArray['subjects'][$i]['subjectCode'];
                    ?>
                            <td align="right" width="<?php echo $perSubjectSpace;?>" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['data'][$x][$subjectCode];?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                    $recordCounter++;

                    if ($recordCounter % $this->recordsPerPage == 0) {
                    ?>
                        </table><br>
                        <?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
                        <?php }
                        if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                        }
                        else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                         ?>
                        <br class='page'>
                        <?php
                            $pageCounter++;
                    }
                    else if($recordCounter == $totalRecords) {
        ?>
                        </table><br>
                        <?php 
                    
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php
                    }
                    if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                    }
                    else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                    }
                }
            }
    }//end of function



    /*************************************

    FUNCTION TO BE CALLED FOR GRAPH PRINTING

    **************************************/

    public function showGraph($imageName) {
    ?>
        <center>
            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='0' cellspacing='0' rules='all' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td valign='top' colspan='<?php echo count($this->tableHeadArray)?>'>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                    <img src = "<?php echo $imageName;?>" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table><br>
            <?php 
                  
                    if($this->autoShowFooter=='Y') {
                ?>          
            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                </tr>
            </table>
        </center>
<?php
    } //end of function

     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
                
                else {
                echo "<br><br>".$this->tableFooter;    
             }                ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
    }
    /*************************************

    FUNCTION TO BE CALLED FOR CLASSWISE CONSOLIDATED REPORT

    **************************************/

    public function showClassWiseConsolidatedReport($graphImage) {
?>
    <center>
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                <td align='right' colspan="1" width="25%" class=''>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                        </tr>
                        <tr>
                            <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
        </table>
        <br>
        <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td valign='top' colspan='1' align="right" <?php echo $this->getReportHeadingStyle(); ?>></td>
                <td valign='top' width="25%" colspan='1' align="right"<?php echo $this->getReportHeadingStyle(); ?>>Total No.&nbsp;</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportHeadingStyle(); ?>>Percentage&nbsp;</td>
            </tr>
            <tr>
                <td valign='top' colspan='1' align="left" <?php echo $this->getReportHeadingStyle(); ?>>&nbsp;Total Students</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray['totalStudents'][0];?>&nbsp;</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportHeadingStyle(); ?>>&nbsp;</td>
            </tr>
            <tr>
                <td valign='top' colspan='1' align="left" <?php echo $this->getReportHeadingStyle(); ?>>&nbsp;Students Appeared</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray['presentStudents'][0];?>&nbsp;</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray['presentStudents'][1];?>&nbsp;</td>
            </tr>
            <tr>
                <td valign='top' colspan='1' align="left" <?php echo $this->getReportHeadingStyle(); ?>>&nbsp;Students Absent</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray['absentStudents'][0];?>&nbsp;</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray['absentStudents'][1];?>&nbsp;</td>
            </tr>
            <?php
                foreach($this->tableDataArray['data'] as $recordArray) {
            ?>
            <tr>
                <td valign='top' colspan='1' align="left" <?php echo $this->getReportHeadingStyle(); ?>>&nbsp;<?php echo $recordArray['rangeLabel'];?></td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $recordArray['cnt'];?>&nbsp;</td>
                <td valign='top' colspan='1' align="right"<?php echo $this->getReportDataStyle(); ?>><?php echo $recordArray['per'];?>&nbsp;</td>
            </tr>
            <?php
            }
            ?>
        </table><br>
        <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td valign='top' colspan='1' class=''>
                    <img src = "<?php echo $graphImage;?>"/>
                </td>
            </tr>
        </table><br>
        <?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td valign='' align="left" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
            </tr>
        </table>
    </center>
<?php
    } //end of function
     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     

    }

    /*************************************

    FUNCTION TO BE CALLED FOR STUDENT PERFORMANCE REPORT

    **************************************/

    public function showStudentPerformanceReport() {
    ?>
        <center>
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                <td align='right' colspan="1" width="25%" class=''>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                        </tr>
                        <tr>
                            <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
        </table>
        <br>
        <table border='0' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
        <?php

                $totalSubjectTypes = count($this->tableDataArray['subjectTypes']);
                for($i=0; $i< $totalSubjectTypes; $i++) {
                //print report column headers
                    $subjectTypeName = $this->tableDataArray['subjectTypes'][$i]['subjectTypeName'];
            ?>
                    <tr>
                        <td colspan='2'>
                            <table border='0' width='100%'>
                                <tr>
                                    <td <?php echo $this->getReportHeadingStyle(); ?>><b><?php echo $subjectTypeName;?></b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <table border='0' width='100%' class='reportBorder' rules='all'>
                                <?php
                                    $totalTestTypes = count($this->tableDataArray[$subjectTypeName.'#testTypes']);
                                ?>
                                <tr>
                                    <td rowspan='2' align='left' <?php echo $this->getReportHeadingStyle(); ?>>&nbsp;Subjects</td>
                                    <?php
                                    for($j=0; $j< $totalTestTypes; $j++) {
                                        $testTypeName = $this->tableDataArray[$subjectTypeName.'#testTypes'][$j]['testTypeName'];
                                        $evCriteria = $this->tableDataArray[$subjectTypeName.'#testTypes'][$j]['evaluationCriteriaId'];
                                        if ($evCriteria == 5 or $evCriteria == 6) {
                                            //attendance
                                        ?>
                                            <td colspan='3' align='center' <?php echo $this->getReportHeadingStyle(); ?>>Lectures</td>
                                        <?php
                                        }
                                        else {
                                            $testTypeCount = $this->tableDataArray[$testTypeName.'#testCount'];
                                            $thisCount = intval($testTypeCount) + 1;
                                        ?>
                                            <td  align='center' colspan='<?php echo $thisCount;?>' <?php echo $this->getReportHeadingStyle(); ?>><?php echo $testTypeName ;?></td>
                                        <?php
                                        }
                                    }
                                    ?>
                                    <td rowspan='2' align='right' <?php echo $this->getReportHeadingStyle(); ?>>Total&nbsp;</td>
                                </tr>
                                <tr>
                                    <?php
                                    for($j=0; $j < $totalTestTypes; $j++) {
                                        $testTypeName = $this->tableDataArray[$subjectTypeName.'#testTypes'][$j]['testTypeName'];
                                        $evCriteria = $this->tableDataArray[$subjectTypeName.'#testTypes'][$j]['evaluationCriteriaId'];
                                        if ($evCriteria == 5 or $evCriteria == 6) {
                                        ?>
                                            <td align='right' <?php echo $this->getReportHeadingStyle(); ?>>Held&nbsp;</td><td  align='right' <?php echo $this->getReportHeadingStyle(); ?>>Attended&nbsp;</td><td  align='right' <?php echo $this->getReportHeadingStyle(); ?>>Total&nbsp;</td>
                                        <?php
                                        }
                                        else {
                                            $testTypeCount = $this->tableDataArray[$testTypeName.'#testCount'];

                                            for($k=0;  $k < $testTypeCount; $k++) {
                                            ?>
                                                <td  align='right' <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->tableDataArray[$testTypeName.'#tests'][$k]['testName'];?>&nbsp;</td>
                                            <?php
                                            }
                                            ?>
                                            <td  align='right' <?php echo $this->getReportHeadingStyle(); ?>>Total&nbsp;</td>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                                $totalSubjects = count($this->tableDataArray[$subjectTypeName.'#subjects']);
                                for($k=0; $k< $totalSubjects; $k++) {
                                    $totalMarks = 0;
                                    $subjectCode = $this->tableDataArray[$subjectTypeName.'#subjects'][$k]['subjectCode'];
                                ?>
                                <tr>
                                    <td  align='left' <?php echo $this->getReportDataStyle(); ?>>&nbsp;<?php echo $subjectCode; ?></td>

                                    <?php
                                    for($j=0; $j < $totalTestTypes; $j++) {
                                        $testTypeName = $this->tableDataArray[$subjectTypeName.'#testTypes'][$j]['testTypeName'];
                                        $evCriteria = $this->tableDataArray[$subjectTypeName.'#testTypes'][$j]['evaluationCriteriaId'];
                                        if ($evCriteria == 5 or $evCriteria == 6) {
                                            //attendance
                                            ?><td  align='right' <?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeParts#'.$testTypeName][0]['lectureDelivered'];?>&nbsp;</td>
                                            <td  align='right' <?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeParts#'.$testTypeName][0]['lectureAttended'];?>&nbsp;</td>
                                            <td  align='right' <?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeMain#'.$testTypeName][0]['marksScored'];?>&nbsp;</td>
                                            <?php
                                            $totalMarks += floatval($this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeMain#'.$testTypeName][0]['marksScored']);
                                        }
                                        else {
                                            $testTypeTestCount = count($this->tableDataArray[$testTypeName.'#tests']);
                                            for($m=0; $m < $testTypeTestCount; $m++) {
                                                $testName = $this->tableDataArray[$testTypeName.'#tests'][$m]['testName'];
                                                $testMarks = $this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeParts#'.$testTypeName.'#Test#'.$testName][0]['marksScored'];
                                            ?>
                                                <td  align='right' <?php echo $this->getReportDataStyle(); ?>><?php echo $testMarks;?>&nbsp;</td>
                                            <?php
                                            }
                                            ?>
                                            <td  align='right' <?php echo $this->getReportDataStyle(); ?>><?php echo $this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeMain#'.$testTypeName][0]['marksScored'];?>&nbsp;</td>
                                            <?php
                                            $totalMarks += floatval($this->tableDataArray[$subjectTypeName.'#subjects#'.$subjectCode.'#TestTypeMain#'.$testTypeName][0]['marksScored']);
                                        }
                                    }
                                    ?>
                                    <td  align='right' <?php echo $this->getReportDataStyle(); ?>><b><?php echo $totalMarks;?></b>&nbsp;</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <?php
            }
            ?>
            </table><br>
            <?php 
                  
                    if($this->autoShowFooter=='Y') {
                ?>          
            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td valign='' align="left" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                </tr>
            </table>
    <?php
    }
     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
    }

    /*************************************

    FUNCTION TO BE CALLED FOR MARKS DISTRIBUTION REPORT PRINTING

    **************************************/

    public function showFinalInternalMarksReport() {
        global $REQUEST_DATA;
        $totalRecords = count($this->tableDataArray['resultData']);
        $grace = $this->tableDataArray['grace'];
        $showMarks = $REQUEST_DATA['showMarks'];
        $showUnivRollNo = $REQUEST_DATA['showUnivRollNo'];

?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                    <?php
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                        <td colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                    </tr>
                    <tr>
                        <?php
                        for($i=0; $i< $totalTests; $i++) {
                            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                            for($m=0; $m < $testTypeIdTests; $m++) {
                            ?>
                                <td><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?>&nbsp;</td>
                            <?php
                            }
                        }
                    ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo 4 + $allTests?>'>
                            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        No Detail Found
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <?php 
                    
                    
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
    <?php }
    
     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
            }
    else {

        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;

        $totalTests = count($this->tableDataArray['testTypes']);
        $allTests = 0;

        for($i=0; $i< $totalTests; $i++) {
            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
            $testTypeCategoryId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
            $isAttendanceCategory = $this->tableDataArray['testTypes'][$i]['isAttendanceCategory'];
            $testTypeName = $this->tableDataArray['testTypes'][$i]['testTypeName'];
            if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                $allTests += 5;//for attendance

            }
            else {
                $testTypeCategoryIdTests = count($this->tableDataArray['testTypeCategoryId']);
                $allTests += $testTypeCategoryIdTests;
                $allTests++;//for evaluated marks
            }
        }

        $perTestSpace = '20%';
        if ($allTests > 0) {
            $perTestSpace = intval(20/$allTests).'%';
        }

        $resultDataLength = count($this->tableDataArray['resultData']);
        for($x = 0; $x < $resultDataLength; $x++) {
            $studentTotalMarks = 0;
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) { ?>

            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <?php
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                        <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                    <?php
                    }
                    for($i=0;$i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeCategoryId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $isAttendanceCategory = $this->tableDataArray['testTypes'][$i]['isAttendanceCategory'];
                        $testTypeName = $this->tableDataArray['testTypes'][$i]['testTypeName'];
                        if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                        ?>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "7">Attendance</th>
                        <?php
                        }
                        else {
                            $testTypeCategoryIdTests = count($this->tableDataArray[$testTypeCategoryId]);
                            $thisColSpan = $testTypeCategoryIdTests + 1;
                        ?>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "<?php echo $thisColSpan;?>"><?php echo $testTypeName;?></th>
                        <?php
                        }
                    }
                ?>
                <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?>  align="center">Total</th>
                <?php
                    if ($grace == 'yes') {
                ?>
                    <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="3" align="center">Grace</th>
                <?php
                    }
                ?>


                <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="3" align="center">G.<br/>Total</th>
                </tr>
                <tr>
                    <?php
                    $totalTestTypeMaxMarks = 0;
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeCategoryId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $isAttendanceCategory = $this->tableDataArray['testTypes'][$i]['isAttendanceCategory'];
                        $testTypeName = $this->tableDataArray['testTypes'][$i]['testTypeName'];
                        $testTypeMaxMarks = round($this->tableDataArray['testTypes'][$i]['maxMarks']);
                        $testTypeAbbr = $this->tableDataArray['testTypes'][$i]['testTypeAbbr'];
                        $totalTestTypeMaxMarks += intval($testTypeMaxMarks);

                        if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                        ?>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">Held</th>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">Attended</th>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">DL</th>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">ML</th>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">Total</th>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">%</th>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2" align="center">M.M.<br><?php echo $testTypeMaxMarks;?></th>
                        <?php
                        }
                        else {
                            $testTypeCategoryIdTests = count($this->tableDataArray[$testTypeCategoryId]);
                            for ($m = 0; $m < $testTypeCategoryIdTests; $m++) {
                                $testCode = $testTypeAbbr.''.$this->tableDataArray[$testTypeCategoryId][$m]['testIndex'];
                                    ?>
                                    <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?>><?php echo $testCode;?></th>

                            <?php
                            }
                            ?>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> rowspan="2">M.M.<br><?php echo $testTypeMaxMarks;?></th>
                        <?php
                        }
                    }
                ?>
                <th rowspan="2" width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?>><?php echo $totalTestTypeMaxMarks;?></th>
                </tr>
                <tr>
                    <?php

                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeCategoryId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $isAttendanceCategory = $this->tableDataArray['testTypes'][$i]['isAttendanceCategory'];
                        $testTypeName = $this->tableDataArray['testTypes'][$i]['testTypeName'];
                        $testTypeMaxMarks = round($this->tableDataArray['testTypes'][$i]['maxMarks']);
                        $testTypeAbbr = $this->tableDataArray['testTypes'][$i]['testTypeAbbr'];
                        $indiTestMaxMarks = $this->tableDataArray['testTypes'][$i]['testMaxMarks'];
                        if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                            continue;
                        }
                        else {
                            $testTypeCategoryIdTests = count($this->tableDataArray[$testTypeCategoryId]);
                            for ($m = 0; $m < $testTypeCategoryIdTests; $m++) {
                                    ?>
                                        <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?>><?php echo $indiTestMaxMarks;?></th>
                                    <?php
                            }
                        }
                    }
                ?>

                </tr>
            <?php
            }
            $class = "class = 'row1'";
            if ($x % 2 == 0) {
                $class = "class = 'row0'";
            }
        ?>
                <tr <?php echo $class;?>>
                    <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['srNo'];?></td>
                    <?php
                    if ($showUnivRollNo) {
                    ?>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['universityRollNo'];?></td>
                    <?php } ?>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['rollNo'];?></td>
                    
                    
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['studentName'];?></td>
                    <?php
                    $graceMarks = $this->tableDataArray['resultData'][$x]['ms_grace'];
                    $studentFinalTotalMarks = $this->tableDataArray['resultData'][$x]['ms_total'];
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeCategoryId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $isAttendanceCategory = $this->tableDataArray['testTypes'][$i]['isAttendanceCategory'];
                        $testTypeName = $this->tableDataArray['testTypes'][$i]['testTypeName'];
                        $testTypeMaxMarks = $this->tableDataArray['testTypes'][$i]['maxMarks'];
                        $testTypeAbbr = $this->tableDataArray['testTypes'][$i]['testTypeAbbr'];
                        if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                        ?>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['lectureDelivered'];?></td>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['lectureAttended'];?></td>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['dutyLeave'];?></td>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['medicalLeave'];?></td>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['totalAttended'];?></td>
                            <?php
                            $percentAttended = 0;
                            if ($this->tableDataArray['resultData'][$x]['lectureDelivered'] != 0) {
                                $percentAttended = ceil($this->tableDataArray['resultData'][$x]['totalAttended'] * 100 / $this->tableDataArray['resultData'][$x]['lectureDelivered']);
                            }
                            ?>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $percentAttended;?></td>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><B><?php echo round($this->tableDataArray['resultData'][$x]['ms_attendance']);?></B></td>
                            <?php
                            $studentTotalMarks += intval($this->tableDataArray['resultData'][$x]['ms_attendance']);
                        }
                        else {
                            $testTypeCategoryIdTests = count($this->tableDataArray[$testTypeCategoryId]);
                            for ($m = 0; $m < $testTypeCategoryIdTests; $m++) {
                                $testMaxMarks = $this->tableDataArray[$testTypeCategoryId][$m]['maxMarks'];
                                $testIndex = $this->tableDataArray[$testTypeCategoryId][$m]['testIndex'];
                                $testName = 'ms_'.$testTypeCategoryId.'_'.$testIndex;
                                $testMarksName = 'ms_'.$testTypeCategoryId;
                                $studentMarks = $this->tableDataArray['resultData'][$x][$testName];
                                $align = "right";
                                if (is_null($studentMarks) || $studentMarks == "null") {
                                    $studentMarks = "---";
                                    $align = "center";
                                }
                                elseif ($studentMarks == "A" or $studentMarks == "N/A") {
                                    $align = "center";
                                    $studentMarks = "<u>$studentMarks</u>";
                                }
                                else {
                                    $studentMarks = round($studentMarks,1);
                                }
                                ?>
                                <td align="<?php echo $align;?>" <?php echo $this->getReportDataStyle();?>><?php echo $studentMarks;?></td>
                                <?php
                            }
                            $studentMarks = $this->tableDataArray['resultData'][$x][$testMarksName];
                            $studentTotalMarks += floatval($studentMarks);
                            ?>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><B><?php echo $studentMarks;?></B></td>
                            <?php
                        }

                    }
                    ?>
                    <td align="right" <?php echo $this->getReportDataStyle();?>><B><?php echo $studentTotalMarks;?></B></td>
                    <?php
                        //$studentTotalMarks += floatval($studentMarks);
                        if (is_null($graceMarks) || $graceMarks == "null") {
                            $graceMarks = 0;
                        }
                        ?>
                        <?php
                            if ($grace == 'yes') {
                        ?>
                            <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $graceMarks;?></td>
                        <?php
                            $grandTotal = $studentFinalTotalMarks + floatval($graceMarks);
                            }
                            else {
                                $grandTotal = $studentFinalTotalMarks;
                            }
                        ?>
                        <td align="right" <?php echo $this->getReportDataStyle();?>><B><?php echo $grandTotal;?></B></td>

                </tr>
                <?php
                    $recordCounter++;

                    if ($recordCounter % $this->recordsPerPage == 0) {
                    ?>
                        </table><br>
                        <?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
                        <?php } 
                    if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                    }
                    else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                        ?>
                        <br class='page'>
                        <?php
                            $pageCounter++;
                    }
                    else if($recordCounter == $totalRecords) {
        ?>
                        </table><br>
                        <?php 
                    
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php
                    }
                  if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                  }
                  else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
            
                    }
                }
            }
?>
</center>
<?php
    }//end of function

/*************************************

    FUNCTION TO BE CALLED FOR STUDENT PERFORMANCE REPORT

    **************************************/

    public function showStudentAcademicPerformanceReport($studentSubjectArray,$studentTotalMarksArray,$studentMaxTestArray,$studentTestIdArray,$maxTestsArray,$studentTestTypeArray,$studentMaxTestTypeArray,$studentTotalTestTransferredArray) {
    ?>
        <center>
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                <td align='right' colspan="1" width="25%" class=''>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                        </tr>
                        <tr>
                            <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
        </table>
        <br>
        <table border='0' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
            <tr>
                <td>
        <?php
                $subjectTypeName ='';
                $studentSubjectCount = count($studentSubjectArray);
                echo '<pre>';
                //print_r($studentSubjectArray);
                //print_r($studentTestTypeArray);
                //print_r($maxTestsArray);

                //print_r($studentTestIdArray);
                //print_r($studentMaxTestArray);
                //die;
                $subjectType = array();

                if ($studentSubjectCount > 0 && is_array($studentSubjectArray)) {
                for($i=0;$i<$studentSubjectCount;$i++) {
                    $subjectTypeName1 = strip_slashes($studentSubjectArray[$i]['subjectTypeName']);
                    $subjectTypeId = strip_slashes($studentSubjectArray[$i]['subjectTypeId']);
                    $subjectTypeName1 = ($subjectTypeName == $subjectTypeName1)?$subjectTypeName1 = "": $subjectTypeName1;
                    //if ($subjectType[$i] != $subjectTypeId) {
                    if($subjectTypeName1){

                    echo "<table border='1' cellspacing='0'><tr>
                            <td colspan=3>".$subjectTypeName1."</td>
                            </tr><tr>
                            <td>#</td>
                            <td>Sub.Code</td>
                            <td>Subject Name</td>
                            <td colspan=3>Attendance</td>
                            </tr>";
                        }
        ?>
                <tr>
                    <td>
                        <?php echo ($i+1);?>
                    </td>
                    <td>
                        <?php echo $studentSubjectArray[$i]['subjectCode'];?>
                    </td>
                    <td>
                        <?php echo $studentSubjectArray[$i]['subjectName'];?>
                    </td>
                    <td>
                        <?php echo $studentSubjectArray[$i]['lectureDelivered'];?>
                    </td>
                    <td>
                        <?php echo $studentSubjectArray[$i]['lectureAttended'];?>
                    </td>
                    <td>
                        <?php echo $studentTotalMarksArray[$i]['marksObtained'];?>
                    </td>

                    <td>
                        <?php
                                //$countStudentMaxTest = count($studentMaxTestArray);
                                foreach($studentMaxTestArray as $studentMaxTestRecord) {
                                $totalTests = $studentMaxTestRecord['totalTest'];
                                $testTypeCategoryId = $studentMaxTestRecord['testTypeCategoryId'];
                                if($totalTests > $maxTestsArray[$testTypeCategoryId]) {
                                    $maxTestsArray[$testTypeCategoryId] = $totalTests;
                                }
                                print_r($maxTestsArray);

                            }
                            //print_r($maxTestsArray);
                        ?>
                    </td>

                    <?php
                        if($subjectTypeName1 != "")
                        $subjectTypeName = $subjectTypeName1;
                    ?>

                </tr>

        <?php
                 }

                }
        ?>
            </td>
        </tr>

        </table>
    <?php
    }


    /*************************************

    FUNCTION TO BE CALLED FOR MARKS DISTRIBUTION REPORT PRINTING

    **************************************/

    public function showTestWiseMarksConsolidatedReport() {
        $totalRecords = count($this->tableDataArray['resultData']);
        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;

        $totalTests = count($this->tableDataArray['testTypes']);
        $allTests = 0;
        for($i=0; $i < $totalTests; $i++) {
            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
            $allTests += $testTypeIdTests;
        }

        if ($allTests == 0) {
            $perTestSpace = '57%';
        }
        else {
            $perTestSpace = intval(57/$allTests).'%';
        }


?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                    <?php
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                        <td colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                    </tr>
                    <tr>
                        <?php
                        for($i=0; $i< $totalTests; $i++) {
                            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                            for($m=0; $m < $testTypeIdTests; $m++) {
                            ?>
                                <td><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?>&nbsp;</td>
                            <?php
                            }
                        }
                    ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo 4 + $allTests?>'>
                            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        No Detail Found
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
    <?php }
     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
    
            }
    else {
        $resultDataLength = count($this->tableDataArray['resultData']);
        for($x = 0; $x < $resultDataLength; $x++) {
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) { ?>

            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <?php
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                        <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></th>
                    <?php
                    }
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                        for($m=0; $m < $testTypeIdTests; $m++) {
                        ?>
                            <th width="<?php echo $perTestSpace;?>" <?php echo $this->getReportHeadingStyle();?> align="center"><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?></td>
                        <?php
                        }
                    }
                ?>
                </tr>
            <?php
            }
        ?>
                <tr>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['srNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['rollNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['universityRegNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['studentName'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['groupName'];?></td>
                    <?php
                    for($i=0; $i< $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeCategoryId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                        for($m=0; $m < $testTypeIdTests; $m++) {
                            $thisTest = 'ms' . $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];
                            if (empty($this->tableDataArray['resultData'][$x][$thisTest])) {
                                $this->tableDataArray['resultData'][$x][$thisTest] = 'TNH&nbsp;';
                            }
                        ?>
                            <td align="right" width="<?php echo $perTestSpace;?>" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x][$thisTest];?></td>
                        <?php
                        }
                    }
                    ?>
                </tr>
                <?php
                    $recordCounter++;

                    if ($recordCounter % $this->recordsPerPage == 0) {
                    ?>
                        </table><br>
                        <?php 
                
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
                        <?php } 
              if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
              }
              else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                        ?>
                        <br class='page'>
                        <?php
                            $pageCounter++;
                    }
                    else if($recordCounter == $totalRecords) {
        ?>
                        </table><br>
                        <?php 
                
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php
                    }
                if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                }
                else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                    }
                }
            }
?>
</center>
<?php
    } //end of function


    public function showGazetteReport() {
        //$this->tableDataArray['resultData'];
        global $REQUEST_DATA;
        $totalRecords = $this->tableDataArray['totalStudents'];
      $internal = $REQUEST_DATA['internal'];
      $attendance = $REQUEST_DATA['attendance'];
      $external = $REQUEST_DATA['external'];
      $total = $REQUEST_DATA['total'];
      $gradeList = $REQUEST_DATA['gradeList'];  
      $emptyList = $REQUEST_DATA['emptyList'];  
       $isSGPA = $REQUEST_DATA['isSGPA'];     
        $isCGPA = $REQUEST_DATA['isCGPA'];   


  




   
?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                    <?php
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                        <td colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                    </tr>
                    <tr>
                        <?php
                        for($i=0; $i< $totalTests; $i++) {
                            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                            for($m=0; $m < $testTypeIdTests; $m++) {
                            ?>
                                <td><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?>&nbsp;</td>
                            <?php
                            }
                        }
                    ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo 4 + $allTests?>'>
                            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        No Detail Found
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <?php 
                
                    
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
    <?php }
     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
    
            }
    else {
        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;



        $srNo = 0;
        for($x = 0; $x < $totalRecords; $x++) {
            $studentTotalMarks = 0;
            $srNo++;
                         
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) {   ?>

            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>     
                <tr><th colspan="3" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="3" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <?php
                    
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                        <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                    <?php
                    }
                    $totalSubjects = count($this->tableDataArray['subjectArray']);
                    $colCounter = 0;
                    if ($internal == 'on') {
                        $colCounter++;
                    }
                    if ($attendance == 'on') {
                        $colCounter++;
                    }
                    if ($external == 'on') {
                        $colCounter++;
                    }
                    if ($total == 'on') {
                        $colCounter++;
                    }
                    if ($gradeList == 'on') {
                        $colCounter++;
                    }
                    if ($isSGPA == 'on') {
                      //$colCounter++;
                    }
                    if ($isCGPA == 'on') {
                      //$colCounter++;
                    }
                    for($i=0;$i < $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjectArray'][$i]['subjectId'];
                        /*
                        if (!isset($this->tableDataArray['maxMarks'][$subjectId])) {
                            continue;
                        }
                        */
                        $subjectCode = $this->tableDataArray['subjectArray'][$i]['subjectCode'];
                        ?>
                        <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "<?php echo $colCounter;?>"><?php echo $subjectCode;?></th>
                        <?php
                    }
                 if ($isSGPA == 'on') {  ?>
                    <td <?php echo $this->getReportHeadingStyle();?> align="center" rowspan="2">SGPA</td>
                <?php 
                 }
                 if ($isCGPA == 'on') {  ?>
                    <td <?php echo $this->getReportHeadingStyle();?> align="center" rowspan="2">CGPA</td>
                <?php 
                 }
                ?>
                <!--
                <td <?php echo $this->getReportHeadingStyle();?> align="center" rowspan="1">Total Marks / <br> Reappear in papers</td>
                -->
                </tr>
                <tr>
        
                <?php
                    for ($i=0; $i<$totalSubjects; $i++) {
                        $thisSubjectId = $this->tableDataArray['subjectArray'][$i]['subjectId'];
                        $mmm='';
                        if($this->tableDataArray['maxMarks'][$thisSubjectId]['I']!='') {
                           $mmm = "(".$this->tableDataArray['maxMarks'][$thisSubjectId]['I'].")";
                        }
                        
                        if ($internal == 'on') {
                            if($emptyList=='') {
                              $maxMarks = $this->tableDataArray['maxMarks'][$thisSubjectId]['I'];
                            }
                        ?>
                            <td <?php echo $this->getReportHeadingStyle();?> align="center">Internal Marks<br><?php echo $mmm; ?></td>
                        <?php
                        } 
                        if ($attendance == 'on') {
                            $mmm='';
                            if($this->tableDataArray['maxMarks'][$thisSubjectId]['A']!='') {
                               $mmm = "(".$this->tableDataArray['maxMarks'][$thisSubjectId]['A'].")";
                            }
                            else {
                               $mmm = "(".NOT_APPLICABLE_STRING.")";  
                            }
                        ?>
                            <td <?php echo $this->getReportHeadingStyle();?> align="center">Attendance</td>
                        <?php
                        }
                        if ($external == 'on') {
                            $mmm='';
                            if($this->tableDataArray['maxMarks'][$thisSubjectId]['E']!='') {
                               $mmm = "(".$this->tableDataArray['maxMarks'][$thisSubjectId]['E'].")";
                            }
                            else {
                               $mmm = "(".NOT_APPLICABLE_STRING.")"; 
                            }
                        ?>
                            <td <?php echo $this->getReportHeadingStyle();?> align="center">External Marks<br><?php echo $mmm; ?></td>
                        <?php
                        }
                        if ($total == 'on') {
                            $mmm='';
                            if($this->tableDataArray['maxMarks'][$thisSubjectId]['T']!='') {
                               $mmm = "(".$this->tableDataArray['maxMarks'][$thisSubjectId]['T'].")"; 
                            }
                        ?>
                            <td <?php echo $this->getReportHeadingStyle();?> align="center">Total Marks<br><?php echo $mmm; ?></td>
                        <?php
                        }
                        
                        if ($gradeList == 'on') {
                        ?>
                            <td <?php echo $this->getReportHeadingStyle();?> align="center">Grade</td>
                        <?php
                
                        }
               }
                        
                      
?>
                
                <!--
                <td <?php echo $this->getReportHeadingStyle();?> align="center"><?php echo round($this->tableDataArray['finalMaxMarksArray'][0]['finalMaxMarks'],1);?></td>
                -->
                </tr>
            <?php
            }
            $class = "";
            //$class = "class = 'row1'";
            if ($x % 2 == 0) {
             //   $class = "class = 'row0'";
            }
            $studentCount = count($this->tableDataArray['recordArray']);
            $totalSubjects = count($this->tableDataArray['subjectArray']);
        ?>
                <tr <?php echo $class;?>>
                    <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $srNo;?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?> nowrap>
                    <?php echo $this->tableDataArray['recordArray'][$x]['rollNo'];?>
                    </td>
                    <td align="left" <?php echo $this->getReportDataStyle();?> nowrap>
                    <?php echo $this->tableDataArray['recordArray'][$x]['studentName'];?>
                    </td>
                    <?php
                        for ($i=0; $i<$totalSubjects; $i++) {
                            $subjectIdShown = $this->tableDataArray['subjectArray'][$i]['subjectId'];
                            /*
                            if (!isset($this->tableDataArray['maxMarks'][$subjectIdShown])) {
                                continue;
                            }
                            */
                            if ($internal == 'on') {

                                 $mm ="";
                                 if($emptyList=='') {
                                   $mm = $this->tableDataArray['recordArray'][$x][$subjectIdShown]['IR']; 
                                   if($mm=='') {
                                     $mm='0';  
                                   } 
                                   if($mm=='') {
                                      $mm=''; 
                                   }
                                 }

                            ?>
                                <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td>
                            <?php
                            }
                            if ($attendance == 'on') {
                                 $mm ="";
                                 if($emptyList=='') {
                                   $mm = $this->tableDataArray['recordArray'][$x][$subjectIdShown]['A'];  
                                   if($mm=='') {
                                     $mm='A';  
                                   }
                                   if($mm=='') {
                                      $mm=''; 
                                   }
                                 }
                            ?>
                                <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td>
                            <?php
                            }
                            $ext='';
                            if ($external == 'on') {
                                 $mm ="";
                                 if($emptyList=='') {
                                   $mm = $this->tableDataArray['recordArray'][$x][$subjectIdShown]['ER'];  
                                   if($mm=='') {
                                     $mm=NOT_APPLICABLE_STRING;  
                                   } 
                                   if($mm=='D' || $mm=='A') {
                                     $ext='D';  
                                   }
                                   if($mm=='') {
                                      $mm=''; 
                                   }
                                 }
                            ?>
                                <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td>
                            <?php
                            }
                            if ($total == 'on') {
                                $mm ="";
                                 if($emptyList=='') {
                                   $mm = $this->tableDataArray['recordArray'][$x][$subjectIdShown]['T'];  
                                   if($mm=='A' || $ext!='') {
                                     $mm=($this->tableDataArray['recordArray'][$x][$subjectIdShown]['I'] + 0);    
                                   }
                                   if($mm=='') {
                                     $mm='0';  
                                   } 
                                   if($mm=='') {
                                      $mm=''; 
                                   }
                                 }
                            ?>
                                <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td>
                              
                            <?php
                            }
                            
                            if ($gradeList == 'on') {
                                $mm ="";
                                 if($emptyList=='') {
                                   $mm = $this->tableDataArray['recordArray'][$x][$subjectIdShown]['G'];  
                                   if($mm=='') {
                                     $mm='I';  
                                   } 
                                   if($mm=='') {
                                      $mm=''; 
                                   }
                                 }
                            ?>
                                <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td>
                            <?php
                            }
                            
                        }
                         if ($isSGPA == 'on') {

                            $mm = $this->tableDataArray['recordArray'][$x]['gpa'];  

                             if($mm=='') {
                               $mm=NOT_APPLICABLE_STRING;  
                                        }
                            if($emptyList=='on') {
                               $mm='';  
                                                  }
                       ?>
              
                      <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td>
                 <?php 
                      }  
                        if ($isCGPA == 'on') {
                           $mm = $this->tableDataArray['recordArray'][$x]['cgpa'];  
                            if($mm=='') {
                               $mm=NOT_APPLICABLE_STRING;  
                                        }
                           if($emptyList=='on') {
                              $mm='';  
                            }
                   ?>
                   <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $mm; ?></td> 
                  <?php
                }  


                ?>
                <!--
                <td align="center" <?php echo $this->getReportDataStyle();?>>
                <?php
                    if ($this->tableDataArray['recordArray'][$x]['totalMarks'] == '') {
                        $this->tableDataArray['recordArray'][$x]['totalMarks'] = 0;
                    }
                    //echo $this->tableDataArray['recordArray'][$x]['totalMarks'];
                ?></td>
                -->
                </tr>
                <?php
                    $recordCounter++;

                    if ($recordCounter % $this->recordsPerPage == 0) {
                    ?>
                        </table><br>
                        <?php 
                
                  
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray['recordArray'])?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray['recordArray'])?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
                        <?php } 
                    if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                    }
                    else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                        ?>
                        <br class='page'>
                        <?php
                            $pageCounter++;
                    }
                    else if($recordCounter == $totalRecords) {
        ?>
                        </table><br>
                        <?php 
                
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray['recordArray'])?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray['recordArray'])?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php
                    }
                    
                 if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                 }
                 else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                    
                    }
                }
            }
    }


    /*************************************

    FUNCTION TO BE CALLED FOR TOTAL MARKS REPORT PRINTING

    **************************************/

    public function showTotalMarksReport() {

        $totalRecords = count($this->tableDataArray['resultData']);
        $totalPages = floor($totalRecords / $this->recordsPerPage);
        $balanceRecords = round($totalRecords % $this->recordsPerPage);
        if ($balanceRecords > 0) {
            $totalPages++;
        }
        $pageCounter = 1;
        $reportData = '';
        $recordCounter = 0;
        $i = 0;

        $totalSubjects = count($this->tableDataArray['subjects']);
        $allTests = 0;
        for($i=0; $i < $totalSubjects; $i++) {
            $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
            $subjectCode = $this->tableDataArray['subjects'][$i]['subjectCode'];
            $allTests += 4;
        }
        if ($allTests == 0) {
            $perTestSpace = '75%';
        }
        else {
            $perTestSpace = intval(75/$allTests).'%';
        }


?>
        <center>
        <?php
            //checking if no records are there.
            if ($totalRecords == 0) {
            ?>

                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                        <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                        <td align='right' colspan="1" width="25%" class=''>
                            <table border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                                </tr>
                                <tr>
                                    <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                    <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
                </table>
                <br>
                <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <?php
                        foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                            <th <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr>
                    <?php
                    for($i=0; $i < $totalTests; $i++) {
                        $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                        $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                    ?>
                        <td colspan = "<?php echo $testTypeIdTests; ?>"><?php echo $this->tableDataArray['testTypes'][$i]['testTypeName'];?></td>
                    <?php
                    }
                    ?>
                    </tr>
                    <tr>
                        <?php
                        for($i=0; $i< $totalTests; $i++) {
                            $testTypeId = $this->tableDataArray['testTypes'][$i]['testTypeId'];
                            $testTypeIdTests = count($this->tableDataArray['testDetails'][$testTypeId]);
                            for($m=0; $m < $testTypeIdTests; $m++) {
                            ?>
                                <td><?php echo $this->tableDataArray['testDetails'][$testTypeId][$m]['testName'];?>&nbsp;</td>
                            <?php
                            }
                        }
                    ?>
                    </tr>
                    <tr>
                        <td valign='top' colspan='<?php echo 4 + $allTests?>'>
                            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                                <tr>
                                    <td  height='25' align='center' colspan='1' <?php echo $this->getReportDataStyle();?>>
                                        No Detail Found
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <?php 
                   
                    if($this->autoShowFooter=='Y') {
                ?>          
                <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                    </tr>
                </table>
    <?php }
     if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
     }
     else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
    
            }
    else {
        echo "<script language='javascript'>alert('Please take printout in landscape format');</script>";
        $resultDataLength = count($this->tableDataArray['resultData']);
        for($x = 0; $x < $resultDataLength; $x++) {
            if($recordCounter == 0 or $recordCounter % $this->recordsPerPage == 0) { ?>

            <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                <tr>
                    <td align='left' colspan="1" width="25%" class=''><?php echo $this->showHeader(); ?></td>
                    <th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getInstituteName(); ?></th>
                    <td align='right' colspan="1" width="25%" class=''>
                        <table border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right' width='50%'>Date :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('d-M-y');?></td>
                            </tr>
                            <tr>
                                <td valign='' colspan='1' <?php echo $this->getDateTimeStyle();?> align='right'>Time :&nbsp;</td><td valign='' colspan='1' align='left' <?php echo $this->getDateTimeStyle();?>><?php echo date('h:i:s A');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportHeadingStyle(); ?>><?php echo $this->reportHeading; ?></th></tr>
                <tr><th colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getReportInformationStyle(); ?>><?php echo $this->getReportInformation(); ?></th></tr>
            </table>
            <br>
            <table border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
                <tr height='50'>
                    <?php
                    foreach($this->tableHeadArray as $tableHeadKey => $tableHeadValue) {?>
                        <th rowspan='4'  <?php echo $tableHeadValue['1'].' '.$this->getReportHeadingStyle();?>><?php echo $tableHeadValue['0']; ?></td>
                    <?php
                    }
                    for($i=0; $i < $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                        $subjectCode = $this->tableDataArray['subjects'][$i]['subjectCode'];
                        $totalSubjectTestTypes = count($this->tableDataArray[$subjectId]);
                    ?>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "4"><?php echo $subjectCode;?></th>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    for($i=0; $i < $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                        $subjectName = $this->tableDataArray['subjects'][$i]['subjectName'];
                        $totalSubjectTestTypes = count($this->tableDataArray[$subjectId]);
                    ?>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "4"><?php echo $subjectName;?></th>
                    <?php
                    }
                    ?>
                </tr>
                <tr>
                <?php
                    for($i=0; $i < $totalSubjects; $i++) {
                    ?>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1">A</th>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1">I</th>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1">E</th>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1">G</th>
                    <?php
                    }
                ?>
                </tr>
                <tr>
                <?php
                    for($i=0; $i < $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                        $internal = $this->tableDataArray['subjects'][$i]['internal'];
                        $external = $this->tableDataArray['subjects'][$i]['externalMarks'];
                        $attendance = $this->tableDataArray['subjects'][$i]['attendance'];
                    ?>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1"><?php echo round($attendance);?></th>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1"><?php echo round($internal);?></th>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1"><?php echo round($external);?></th>
                    <th <?php echo $this->getReportHeadingStyle();?> align="center" colspan = "1">&nbsp;</th>
                    <?php
                    }
                ?>
                </tr>
            <?php
            }
        ?>
                <tr>
                    <td align="right" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['srNo'];?></td>
                    <td align="center" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['rollNo'];?></td>
                    <td align="left" <?php echo $this->getReportDataStyle();?>><?php echo $this->tableDataArray['resultData'][$x]['studentName'];?></td>
                    <?php
                    $totalMarksScored = 0;
                    for($i=0; $i< $totalSubjects; $i++) {
                        $subjectId = $this->tableDataArray['subjects'][$i]['subjectId'];
                        $alias = $subjectId.'_Marks';
                        list($marksScored, $marksScored2, $marksScored3, $marksScored4) = explode('#', $this->tableDataArray['resultData'][$x][$alias]);
                        if ($marksScored == '-1') {
                            $marksScored = 'A';
                        }
                        if ($marksScored == '-2') {
                            $marksScored = 'UMC';
                        }
                        if ($marksScored2 == '-1') {
                            $marksScored2 = 'A';
                        }
                        if ($marksScored2 == '-2') {
                            $marksScored2 = 'UMC';
                        }
                        ?>
                            <td width="<?php echo $perTestSpace;?>" <?php echo $this->getReportDataStyle();?> align="right"><?php echo $marksScored3;?></td>
                            <td width="<?php echo $perTestSpace;?>" <?php echo $this->getReportDataStyle();?> align="right"><?php echo $marksScored;?></td>
                            <td width="<?php echo $perTestSpace;?>" <?php echo $this->getReportDataStyle();?> align="center"><?php echo $marksScored2;?></td>
                            <td width="<?php echo $perTestSpace;?>" <?php echo $this->getReportDataStyle();?> align="center"><?php echo $marksScored4;?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                    $recordCounter++;

                    if ($recordCounter % $this->recordsPerPage == 0) {
                    ?>
                        </table><br>
                        <?php 
                  
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
                        <?php } 
                   if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
                   }
                   else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                        ?>
                        <br class='page'>
                        <?php
                            $pageCounter++;
                    }
                    else if($recordCounter == $totalRecords) {
        ?>
                        </table><br>
                        <?php 
                
                 
                    if($this->autoShowFooter=='Y') {
                ?>          
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
                                <td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td>
                                <td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php
                    }
               if($pageCounter==$totalPages ){
                        echo "<br><br>".$this->tableExFooter;
               }
               else {
                echo "<br><br>".$this->tableFooter;    
             }        
                        ?>          
                        <br><br>
                        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
                            <tr>
        <td valign='' align="right" <?php echo $this->getFooterStyle();?>>Sheet <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                            </tr>
                        </table>
        <?php            
                     
                    }
                }
            }
        ?>
</center>
<?php
    } //end of function
    
} //end of class

?>

