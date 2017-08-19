<?php
//-------------------------------------------------------
//  This File contains menu items for accounts module.
//
//
// Author :Ajinder Singh
// Created on : 10-Aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once($FE . "/Library/common.inc.php"); //for sessionId 
require_once(BL_PATH . "/ReportManager.inc.php");

class AccountsReportManager {
	public static $instance = null;
	public $tableHeadArray = array();							//array for table headings
	public $tableDataArray = array();							//array for table data
	public $reportWidth = 800;									//default report width
	public $reportHeading;										//reportHeading
	public $reportHeadingStyle = "class = 'headingFont'";
	public $reportDataStyle = " class = 'dataFont'";
	public $reportTitleStyle = " class = 'reportTitle'";
	public $recordsPerPage = 20;
	public $dateTimeStyle = "class = 'reportDateTime'";
	public $footerStyle = "class = 'reportFooter'";
	public $reportInformationStyle = "class = 'reportInformation'";
	public $reportData;
	public $reportInformation;
	
	public function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	
	/*	Call this function to set the width of report */
	
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

	public function setReportData($tableHeadingArray = array(), $tableDataArray = array(), $special = '') {
		$this->tableHeadArray = $tableHeadingArray;
		$this->tableDataArray = $tableDataArray;
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

	/* Function used for making header
	*/
	public function showHeader() {
        //require_once(MODEL_PATH . "/InstituteManager.inc.php");
        //$instituteManager =  InstituteManager::getInstance();
		global $sessionHandler;
		//$logoArray = $instituteManager->checkLogoName($sessionHandler->getSessionVariable('InstituteId'));
		if (isset($logoArray[0]['instituteLogo']) and !empty($logoArray[0]['instituteLogo'])) {
			return '<img name="logo" src="'.IMG_HTTP_PATH.'/Institutes/'.$logoArray[0]['instituteLogo'].'" border="0" width="70" height="70" title="'.$this->getInstituteName().'"/>';
		}
		else {
			return '';
		}
	}

	/* Function used for making footer
	*/
	public function showFooter() {
		global $sessionHandler;
        //require_once(MODEL_PATH . "/InstituteManager.inc.php");
		//$logoArray = InstituteManager::getInstance()->checkLogoName($sessionHandler->getSessionVariable('InstituteId'));
        return "Generated By : ".$sessionHandler->getSessionVariable("UserName");;
	}

	/* Function used for fetching session institute name
	*/
	public function getCompanyName() {
        //require_once(MODEL_PATH . "/InstituteManager.inc.php");
		global $sessionHandler;
		//$instituteNameArray = InstituteManager::getInstance()->getInstituteName($sessionHandler->getSessionVariable('InstituteId'));
		return $sessionHandler->getSessionVariable('CompanyName');
	}

	/* Function used for fetching session institute address
	*/
	public function getCompanyAddress() {
        //require_once(MODEL_PATH . "/InstituteManager.inc.php");
		global $sessionHandler;
		//$instituteAddressArray = InstituteManager::getInstance()->getInstituteAddress($sessionHandler->getSessionVariable('InstituteId'));
		return $instituteAddressArray[0]['instituteAddress1'];
	}

	/* Function used for fetching session institute address
	*/
	public function getCompanyTelephone() {
        //require_once(MODEL_PATH . "/InstituteManager.inc.php");
		global $sessionHandler;
		//$instituteAddressArray = InstituteManager::getInstance()->getInstituteTelephone($sessionHandler->getSessionVariable('InstituteId'));
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

	/* Function used for making balance sheet report
		@param: printLogicArray [array]
	*/
	public function showBalanceSheet($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$liaHeadSum = 0;
		$asHeadSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 40;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		foreach($printLogicArray as $printLogicRecord) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0'  class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='40%'>Liabilities</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='10%'>Amount(Rs.)</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='40%'>Assets</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='10%'>Amount(Rs.)</td>
					</tr>
					<?php
					if ($recordCounter > 0) {
					?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($liaHeadSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($asHeadSum);?></B></td>
					</tr>
		<?php
					}
				}
				$recordCounter++;
		?>
				<tr>
				<?php
					if (isset($printLogicRecord['liabilities'])) {
						$liaType = $printLogicRecord['liabilities']['Type'];
						$liaName = $printLogicRecord['liabilities']['Name'];
						$liaAmount = $printLogicRecord['liabilities']['Amount'];
					?>
						
					<?php
						if ($liaType == 'Main') {
							$liaHeadSum += $liaAmount;
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1'><B><?php echo $liaName;?></B></td>
						<td  colspan='1' ></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><B><?php echo formatValue($liaAmount);?></B></td>
					<?php
						}
						else {
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1'>&nbsp;&nbsp;<?php echo $liaName;?></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><?php echo formatValue($liaAmount);?></td>
						<td  colspan='1' ></td>
					<?php
						}
					}
					else {
					?>
					<td  colspan='3' ></td>
					<?php
					}
					if (isset($printLogicRecord['assets'])) {
						$asType = $printLogicRecord['assets']['Type'];
						$asName = $printLogicRecord['assets']['Name'];
						$asAmount = $printLogicRecord['assets']['Amount'];
					?>
						
					<?php
						if ($asType == 'Main') {
							$asHeadSum += $asAmount;
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' ><b><?php echo $asName;?></b></td>
						<td colspan='1' ></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><B><?php echo formatValue($asAmount);?></B></td>
					<?php
						}
						else {
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' >&nbsp;&nbsp;<?php echo $asName;?></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><?php echo formatValue($asAmount);?></td>
						<td  colspan='1' ></td>
					<?php
						}
					}
					else {
					?>
					<td  colspan='3' ></td>
					<?php
					}
			?>
				</tr>
			<?php
					if ($recordCounter == $totalRecords) {
			?>
						<tr>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Total</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right'  width='10%'><B><?php echo formatValue($liaHeadSum);?></B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Total</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($asHeadSum);?></B></td>
						</tr>
						</table>
						<br>
						<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
							<tr>
								<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
							</tr>
						</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
						
				?>
						<tr>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Carried Over</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right'  width='10%'><B><?php echo formatValue($liaHeadSum);?></B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Carried Over</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($asHeadSum);?></B></td>
						</tr>
						</table>
						<br>
						<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
							<tr>
								<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
							</tr>
						</table>
						<br class='page'>
						<br>
				<?php
					$pageCounter++;
					}
				}
	}//end of function



	/* Function used for making balance sheet report
		@param: printLogicArray [array]
	*/
	public function showProfitLoss($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$expHeadSum = 0;
		$incHeadSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 40;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		foreach($printLogicArray as $printLogicRecord) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0'  class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='40%'>Expenses</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='10%'>Amount(Rs.)</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='40%'>Incomes</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='10%'>Amount(Rs.)</td>
					</tr>
					<?php
					if ($recordCounter > 0) {
					?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($expHeadSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($incHeadSum);?></B></td>
					</tr>
		<?php
					}
				}
				$recordCounter++;
		?>
				<tr>
				<?php
					if (isset($printLogicRecord['expenses'])) {
						$expType = $printLogicRecord['expenses']['Type'];
						$expName = $printLogicRecord['expenses']['Name'];
						$expAmount = $printLogicRecord['expenses']['Amount'];
					?>
						
					<?php
						if ($expType == 'Main') {
							$expHeadSum += $expAmount;
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1'><B><?php echo $expName;?></B></td>
						<td  colspan='1' ></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><B><?php echo formatValue($expAmount);?></B></td>
					<?php
						}
						else {
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1'>&nbsp;&nbsp;<?php echo $expName;?></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><?php echo formatValue($expAmount);?></td>
						<td  colspan='1' ></td>
					<?php
						}
					}
					else {
					?>
					<td  colspan='3' ></td>
					<?php
					}
					if (isset($printLogicRecord['incomes'])) {
						$incType = $printLogicRecord['incomes']['Type'];
						$incName = $printLogicRecord['incomes']['Name'];
						$incAmount = $printLogicRecord['incomes']['Amount'];
					?>
						
					<?php
						if ($incType == 'Main') {
							$incHeadSum += $incAmount;
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' ><b><?php echo $incName;?></b></td>
						<td colspan='1' ></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><B><?php echo formatValue($incAmount);?></B></td>
					<?php
						}
						else {
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' >&nbsp;&nbsp;<?php echo $incName;?></td>
						<td <?php echo $this->getReportDataStyle();?> align='right' colspan='1' ><?php echo formatValue($incAmount);?></td>
						<td  colspan='1' ></td>
					<?php
						}
					}
					else {
					?>
					<td  colspan='3' ></td>
					<?php
					}
			?>
				</tr>
			<?php
					if ($recordCounter == $totalRecords) {
			?>
						<tr>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Total</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right'  width='10%'><B><?php echo formatValue($expHeadSum);?></B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Total</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($incHeadSum);?></B></td>
						</tr>
						</table>
						<br>
						<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
							<tr>
								<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>

							</tr>
						</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
				?>
						<tr>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Carried Over</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right'  width='10%'><B><?php echo formatValue($expHeadSum);?></B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='40%'><B>Carried Over</B></td>
							<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($incHeadSum);?></B></td>
						</tr>
						</table>
						<br>
						<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
							<tr>
								<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
							</tr>
						</table>
						<br class='page'>
						<br>
				<?php
					$pageCounter++;
					}
				}
	}//end of function


	/* Function used for making groupwise trial balance
		@param: printLogicArray [array]
	*/
	public function showTrialBalanceGroupWise($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$debitSum = 0;
		$creditSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 40;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		foreach($printLogicArray as $groupName => $balanceDetailsArray) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0'  class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='60%'>Particulars</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='20%'>Debit</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='20%'>Credit</td>
					</tr>
					<?php
					if ($recordCounter > 0) {
					?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
		<?php
					}
				}
				$recordCounter++;
				$amount = $balanceDetailsArray['Amount'];
		?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><?php echo $groupName;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'>
						<?php 
								if ($balanceDetailsArray['Type'] == 'Dr') {
									$debitSum += $amount;
									echo formatValue($amount);
								}
						?>
						</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'>
						<?php 
								if ($balanceDetailsArray['Type'] == 'Cr') {
									$creditSum += $amount;
									echo formatValue($amount);
								}
						?>
						</td>
					</tr>
			<?php
				if ($recordCounter == $totalRecords) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Total</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Carried Over</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
					<br class='page'>
					<br>
				<?php
					$pageCounter++;
					}
		}
	}//end of function


	/* Function used for making groupwise trial balance
		@param: printLogicArray [array]
	*/
	public function showTrialBalanceLedgerWise($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$debitSum = 0;
		$creditSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 50;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		foreach($printLogicArray as $ledgerName => $balanceDetailsArray) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0'  class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='60%'>Particulars</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='20%'>Debit</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='20%'>Credit</td>
					</tr>
					<?php
					if ($recordCounter > 0) {
					?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
		<?php
					}
				}
				$recordCounter++;
				$amount = $balanceDetailsArray['Amount'];
		?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><?php echo $ledgerName;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'>
						<?php 
								if ($balanceDetailsArray['Type'] == 'Dr') {
									$debitSum += $amount;
									echo formatValue($amount);
								}
						?>
						</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'>
						<?php 
								if ($balanceDetailsArray['Type'] == 'Cr') {
									$creditSum += $amount;
									echo formatValue($amount);
								}
						?>
						</td>
					</tr>
			<?php
				if ($recordCounter == $totalRecords) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Total</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Carried Over</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
					<br class='page'>
					<br>
				<?php
					$pageCounter++;
					}
		}
	}//end of function


	/* Function used for making Group Summary
		@param: printLogicArray [array]
	*/
	public function showGroupSummary($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$debitSum = 0;
		$creditSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 25;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		foreach($printLogicArray as $ledgerName => $balanceDetailsArray) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0'  class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='60%'>Particulars</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='20%'>Debit</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='center' width='20%'>Credit</td>
					</tr>
					<?php
					if ($recordCounter > 0) {
					?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
		<?php
					}
				}
				$recordCounter++;
				$amount = $balanceDetailsArray['Amount'];
		?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><?php echo $ledgerName;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'>
						<?php 
								if ($balanceDetailsArray['Type'] == 'Dr') {
									$debitSum += $amount;
									echo formatValue($amount);
								}
						?>
						</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'>
						<?php 
								if ($balanceDetailsArray['Type'] == 'Cr') {
									$creditSum += $amount;
									echo formatValue($amount);
								}
						?>
						</td>
					</tr>
			<?php
				if ($recordCounter == $totalRecords) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Total</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2'  width='60%'><B>Carried Over</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='20%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
					<br class='page'>
					<br>
				<?php
					$pageCounter++;
					}
		}
	}//end of function




	/* Function used for Group Vouchers/Ledgers
		@param: printLogicArray [array]
	*/
	public function showGroupVoucherLedger($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$debitSum = 0;
		$creditSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 40;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		$drAmount = 0;
		$crAmount = 0;
		$thisVoucherDate = '';
		foreach($printLogicArray as $printLogicRecord) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0' rules='rows' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='5%'>Date</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='5%'>DrCr</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='40%'>Particulars</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='15%'>Vch Type</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='15%'>Vch No.</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='right' width='10%'>Debit</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='right' width='10%'>Credit</td>
					</tr>
					<?php
					if ($recordCounter > 0) {
					?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='5'><B>Brought Forward</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
		<?php
					}
				}
				$recordCounter++;
				$style = '';
				if (isset($printLogicRecord['StartOpeningBalance']) or $printLogicRecord['OpeningBalance'] or isset($printLogicRecord['Total']) or isset($printLogicRecord['voucher']) or isset($printLogicRecord['ClosingBalance'])) {
					if (isset($printLogicRecord['StartOpeningBalance'])) {
						$style = 'style = "font-weight:bold;"';
						$thisPrintLogicRecord = $printLogicRecord['StartOpeningBalance'];
						$debitSum += $drAmount;
						$creditSum += $crAmount;
					}
					elseif (isset($printLogicRecord['OpeningBalance'])) {
						$style = 'style = "font-weight:bold;"';
						$thisPrintLogicRecord = $printLogicRecord['OpeningBalance'];
					}
					elseif (isset($printLogicRecord['Total'])) {
						$style = 'style = "font-weight:bold;"';
						$thisPrintLogicRecord = $printLogicRecord['Total'];
					}
					elseif (isset($printLogicRecord['voucher'])) {
						$thisPrintLogicRecord = $printLogicRecord['voucher'];
					}
					elseif (isset($printLogicRecord['ClosingBalance'])) {
						$style = 'style = "font-weight:bold;"';
						$thisPrintLogicRecord = $printLogicRecord['ClosingBalance'];
					}
					$voucherDate = $thisPrintLogicRecord['voucherDate'];
					$drcr = $thisPrintLogicRecord['drcr'];
					$ledgerName = $thisPrintLogicRecord['ledgerName'];
					$voucherType = $thisPrintLogicRecord['voucherType'];
					$voucherNo = $thisPrintLogicRecord['voucherNo'];
					$drAmount = $thisPrintLogicRecord['drAmount'];
					$crAmount = $thisPrintLogicRecord['crAmount'];
					if (isset($printLogicRecord['StartOpeningBalance']) or isset($printLogicRecord['voucher'])) {
						$debitSum += $drAmount;
						$creditSum += $crAmount;
					}


				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='5%' <?php echo $style;?>>
						<?php 
							if ($voucherDate != $thisVoucherDate) {
								echo $voucherDate;
							} 
							else {
								echo '';
							}
						?>
						</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='center' width='5%' <?php echo $style;?>><?php echo $drcr;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='40%' <?php echo $style;?>><?php echo $ledgerName;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='center' width='15%' <?php echo $style;?>><?php echo $voucherType;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='15%' <?php echo $style;?>>
						<?php echo $voucherNo;?>
						</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%' <?php echo $style;?>><?php echo formatValue($drAmount);?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%' <?php echo $style;?>><?php echo formatValue($crAmount);?></td>
					</tr>
				<?php
				}
				elseif (isset($printLogicRecord['narration'])) {
					$narration = $printLogicRecord['narration'];
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='2' align='left' width='5%'>Narration:</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='5' align='left' width='5%' style='font-style:italic;'><?php echo $narration;?></td>
					</tr>
				<?php
				}
				if ($recordCounter == $totalRecords) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='5'><B>Total</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' ><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' ><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='5'><B>Closing Balance</B></td>
					<?php
					if ($debitSum > $creditSum) {
						$balance = $debitSum - $creditSum; 
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' ><B><?php echo formatValue($balance);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' ></td>
					<?php
					}
					elseif ($creditSum > $debitSum) {
						$balance = $creditSum - $debitSum; 
					?>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' ></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' ><B><?php echo formatValue($balance);?></B></td>
					<?php
					}
					?>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='5'  width='80%'><B>Carried Over</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
					<br class='page'>
					<br>
				<?php
					$pageCounter++;
					}
					$thisVoucherDate = $voucherDate;
		}
	}//end of function



	/* Function used for Group Vouchers/Ledgers
		@param: printLogicArray [array]
	*/
	public function showVoucher($printLogicArray = array()) {
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$debitSum = 0;
		$creditSum = 0;
		?>
				<table align='center' border='1' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='left' width='50%'>Voucher No. <?php echo $printLogicArray['voucherNo'];?></td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='50%'>Dated : <?php echo $printLogicArray['voucherDate'];?></td>
					</tr>
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='2' align='left' width='50%'>Particulars </td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='50%'>Amount</td>
					</tr>
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='3' align='left' width='50%'>Account:</td>
					</tr>
					<?php
						foreach($printLogicArray as $printLogicRecord) {
							if (is_array($printLogicRecord)) {
							if (isset($printLogicRecord['debit'])) {
								$ledger = $printLogicRecord['debit'][0];
								$amount = $printLogicRecord['debit'][1];
					?>
								<tr>
									<td <?php echo $this->getReportDataStyle();?> colspan='2' align='left' width='50%'>&nbsp;&nbsp;<?php echo $ledger;?></td>
									<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='50%'>&nbsp;&nbsp;<?php echo $amount;?></td>
								</tr>
					<?php
									}

							}
						}
				?>
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='3' align='left' width='50%'>Through:</td>
					</tr>

				<?php
						foreach($printLogicArray as $printLogicRecord) {
							if (is_array($printLogicRecord)) {
							if (isset($printLogicRecord['credit'])) {
								$ledger = $printLogicRecord['credit'][0];
								$amount = $printLogicRecord['credit'][1];
					?>
								<tr>
									<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='30%'>&nbsp;&nbsp;<?php echo $ledger;?></td>
									<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='20%'>&nbsp;&nbsp;<?php echo $amount;?></td>
									<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='50%'></td>
								</tr>
					<?php
									}

							}
						}
					?>
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='3' align='left' width='50%'>On Account of:</td>
					</tr>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='3' align='left' width='50%'><?php echo $printLogicArray['narration'];?></td>
					</tr>
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='3' align='left' width='50%'>Amount in words:</td>
					</tr>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='3' align='left' width='50%'><?php echo $printLogicArray['total'];?></td>
					</tr>
				</table>
				<br><br><br>
				<table align='center' border='0' cellspacing='0' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='50%'>Receiver's Signature</td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='50%'>Authorised Signatory</td>
					</tr>
				</table>

					<?php
	}//end of function


	/* Function used for Group Vouchers/Ledgers
		@param: printLogicArray [array]
	*/
	public function showVoucherTypeRegister($printLogicArray = array()) {
		global $voucherTypeArray;
	?>
		<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
			<tr>
				<td align='left' colspan="1" width="25%" ><?php echo $this->showHeader(); ?></td>
				<th align='center' colspan="1" width="50%" <?php echo $this->getReportTitleStyle();?>><?php echo $this->getCompanyName(); ?></th>
				<td align='right' colspan="1" width="25%" >
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
		<?php
		$debitSum = 0;
		$creditSum = 0;
		$recordCounter = 0;
		$totalRecords = count($printLogicArray);
		$pageRecords = 40;
		$totalPages = floor($totalRecords / $pageRecords);
		if ($totalRecords % $pageRecords > 0) {
			$totalPages++;
		}
		$pageCounter = 1;
		$drAmount = 0;
		$crAmount = 0;
		$thisVoucherDate = '';
		foreach($printLogicArray as $printLogicRecord) {
			if ($recordCounter % $pageRecords == 0) {
		?>
				<table align='center' border='1' cellspacing='0' rules='all' class="reportTableBorder" width="<?php echo $this->getReportWidth(); ?>">
					<tr>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='center' width='10%'>Date</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='40%'>Particulars</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='15%'>Vch Type</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='left' width='15%'>Vch No.</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='right' width='10%'>Debit<br>Amount</td>
						<td <?php echo $this->getReportHeadingStyle();?> colspan='1' align='right' width='10%'>Credit<br>Amount</td>
					</tr>
					<?php
				}
				$recordCounter++;
				$voucherDate = date('d-M-Y', strtotime($printLogicRecord['voucherDate']));
				$ledgerName = $printLogicRecord['ledgerName'];
				$voucherType = $printLogicRecord['voucherType'];
				$voucherTypeName = $voucherTypeArray[$printLogicRecord['voucherTypeId']];
				$voucherNo = $printLogicRecord['voucherNo'];
				$drAmount = $printLogicRecord['drAmount'];
				$crAmount = $printLogicRecord['crAmount'];
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='5%'><B><?php echo $voucherDate;?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='40%' ><?php echo $ledgerName;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='15%' ><?php echo $voucherTypeName;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='left' width='15%' ><?php echo $voucherNo;?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%' ><?php echo $drAmount == '0.00' ? '' : formatValue($drAmount);?></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%' ><?php echo $crAmount == '0.00' ? '' : formatValue($crAmount);?></td>
					</tr>
				<?php
				}
				if ($recordCounter == $totalRecords) {
				?>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
				<?php
					}
					elseif ($recordCounter % $pageRecords == 0) {
				?>
					<tr>
						<td <?php echo $this->getReportDataStyle();?> colspan='5'  width='80%'><B>Carried Over</B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($debitSum);?></B></td>
						<td <?php echo $this->getReportDataStyle();?> colspan='1' align='right' width='10%'><B><?php echo formatValue($creditSum);?></B></td>
					</tr>
					</table>
					<br>
					<table align='center' border='0' cellspacing='0' cellpadding='0' width="<?php echo $this->getReportWidth(); ?>">
						<tr>
							<td valign='' align="left" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>><?php echo $this->showFooter(); ?></td><td valign='' align="right" colspan="<?php echo count($this->tableHeadArray)?>" <?php echo $this->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
						</tr>
					</table>
					<br class='page'>
					<br>
				<?php
					$pageCounter++;
					}
	}//end of function



}//end of class


// $History: AccountsReportManager.inc.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:23p
//Updated in $/LeapCC/Library/Accounts
//corrected vss comment
//





?>