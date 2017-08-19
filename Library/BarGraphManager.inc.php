<?php
//-------------------------------------------------------
//  This File contains Presentation logic of bar graphs
//
//
// Author :Ajinder Singh
// Created on : 18-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


class  BarGraphManager{

	private static $instance = null;
	private $graphWidth = 800;
	private $graphHeight = 600;
	private $plotWidth = 700;
	private $plotHeight = 500;
	private $xLabelsArray = array();
	private $yLabelsArray = array();
	private $extraArray = array();
	private $xLabel = '';
	private $yLabel = '';
	private $dataArray = array();
	private $graphColor = '#CCCCCC';
	private $plotColor = '#FFFFFF';
	private $xLabelColor = '#000000';
	private $xLabelsColor = '#000000';
	private $yLabelColor = '#000000';
	private $yLabelsColor = '#000000';
	private $axisColor = '#CCCCCC';
	private $dataColorArray = array('#73AACC', '#0000FF', '#00FF00', '#CC6600', '#7634AB', '#496896', '#84A23E', '#426303', '#1C4262');
	private $alpha = 20;


	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	/* function for setting bar alpha */
	public function setAlpha($alpha = 40) {
		$this->alpha = $alpha;
	}

	/* function for getting bar alpha */
	private function getAlpha() {
		return $this->alpha;
	}


	/* function for setting axis color */
	public function setAxisColor($axisColor = '#000000') {
		$this->axisColor = $axisColor;
	}

	/* function for getting axis color */
	private function getAxisColor() {
		return $this->axisColor;
	}


	/* function for setting x-axis labels */
	public function setXLabelsArray($xLabelsArray = array()) {
		$this->xLabelsArray = $xLabelsArray;
	}

	/* function for getting x-axis labels */
	private function getXLabelsArray() {
		return $this->xLabelsArray;
	}

	/* function for setting y-axis labels */
	public function setYLabelsArray($yLabelsArray = array()) {
		$this->yLabelsArray = $yLabelsArray;
	}

	/* function for getting y-axis labels */
	private function getYLabelsArray() {
		return $this->yLabelsArray;
	}

	/* function for setting x-axis main label */
	public function setXLabel($xLabel = '') {
		$this->xLabel = $xLabel;
	}

	/* function for getting x-axis main label */
	private function getXLabel() {
		return $this->xLabel;
	}

	/* function for setting y-axis main label */
	public function setYLabel($string) {
		$this->yLabel = $string;
	}

	/* function for getting y-axis main label */
	private function getYLabel() {
		return $this->yLabel;
	}
	
	/* function for setting data array */
	public function setDataArray($dataArray = array()) {
		$this->dataArray = $dataArray;
	}

	/* function for getting data array */
	private function getDataArray() {
		return $this->dataArray;
	}

	/* function for setting data array */
	public function setExtraArray($extraArray = array()) {
		$this->extraArray = $extraArray;
	}

	/* function for getting data array */
	private function getExtraArray() {
		return $this->extraArray;
	}

	/* function for setting graph width */
	public function setGraphWidth($graphWidth = 800) {
		$this->graphWidth = $graphWidth;
	}

	/* function for getting graph width */
	private function getGraphWidth() {
		return $this->graphWidth;
	}

	/* function for setting graph height */
	public function setGraphHeight($graphHeight = 600) {
		$this->graphHeight = $graphHeight;
	}

	/* function for getting graph height */
	private function getGraphHeight() {
		return $this->graphHeight;
	}


	/* function for setting plot width */
	public function setPlotWidth($plotWidth = 700) {
		$this->plotWidth = $plotWidth;
	}

	/* function for getting plot width */
	private function getPlotWidth() {
		return $this->plotWidth;
	}

	/* function for setting plot height */
	public function setPlotHeight($plotHeight = 500) {
		$this->plotHeight = $plotHeight;
	}

	/* function for getting plot height */
	private function getPlotHeight() {
		return $this->plotHeight;
	}

	/* function for setting graph color */
	public function setGraphColor($graphColor = '#CCCCCC') {
		$this->graphColor = $graphColor;
	}

	/* function for getting graph color */
	private function getGraphColor() {
		return $this->graphColor;
	}

	/* function for setting plot color */
	public function setPlotColor($plotColor = '#FFFFFF') {
		$this->plotColor = $plotColor;
	}

	/* function for getting plot color */
	private function getPlotColor() {
		return $this->plotColor;
	}

	/* function for setting x-label color */
	public function setXLabelColor($xLabelColor = '#000000') {
		$this->xLabelColor = $xLabelColor;
	}

	/* function for getting x-label color */
	private function getXLabelColor() {
		return $this->xLabelColor;
	}

	/* function for setting y-label color */
	public function setYLabelColor($yLabelColor = '#000000') {
		$this->yLabelColor = $yLabelColor;
	}

	/* function for getting y-label color */
	private function getYLabelColor() {
		return $this->yLabelColor;
	}

	/* function for setting x-labels color */
	public function setXLabelsColor($xLabelsColor = '#000000') {
		$this->xLabelsColor = $xLabelsColor;
	}

	/* function for getting x-labels color */
	private function getXLabelsColor() {
		return $this->xLabelsColor;
	}

	/* function for setting y-labels color */
	public function setYLabelsColor($yLabelsColor = '#000000') {
		$this->yLabelsColor = $yLabelsColor;
	}

	/* function for getting y-labels color */
	private function getYLabelsColor() {
		return $this->yLabelsColor;
	}

	/* function for setting bars color */
	public function setDataColorArray($dataColorArray = array()) {
		$this->dataColorArray = $dataColorArray;
	}

	/* function for getting bars color */
	private function getDataColorArray() {
		return $this->dataColorArray;
	}

	/* function for converting color to array of hex points */
	public function getRGB($color) {
		$rColor = intval("0x".substr($color, 1,2),16);
		$gColor = intval("0x".substr($color, 3,2),16);
		$bColor = intval("0x".substr($color, 5,2),16);
		return array($rColor, $gColor, $bColor);
	}

	/** function to be called for making image 
		@param savePath: path and name of image to be stored (mandatory)
		@param logoImage: give path and name of logo image to be shown in graph (optional)
	*/
	public function makeGraph($savePath) {

		//set plot starting points
		$leftMargin = ($this->getGraphWidth() - $this->getPlotWidth())/2;
		$topMargin = ($this->getGraphHeight() - $this->getPlotHeight())/2;

		
		//create graph
		$im = @imagecreatetruecolor($this->getGraphWidth(), $this->getGraphHeight()) or die("Cannot create image");

		//set graph background color
		$alpha = $this->getAlpha();
		$grapColorArray = $this->getRGB($this->getGraphColor());
		$graphColor = imagecolorallocatealpha($im, $grapColorArray[0], $grapColorArray[1], $grapColorArray[2], 50);
		imagefill($im, 0, 0, $graphColor);


		//set plot color
		$plotColorArray = $this->getRGB($this->getPlotColor());
		$plotColor = imagecolorallocate($im, $plotColorArray[0], $plotColorArray[1], $plotColorArray[2]);
		imagefilledrectangle($im, $leftMargin, $topMargin, $leftMargin + $this->getPlotWidth(), $topMargin + $this->getPlotHeight(), $plotColor);

		//creating y label
		$yLabelColorArray = $this->getRGB($this->getYLabelColor());
		$yLabelColor = imagecolorallocate($im, $yLabelColor[0], $yLabelColor[1], $yLabelColor[2]);

		//write string y label
		imagestringup($im, 5, $leftMargin-50, $topMargin + ($this->getGraphHeight()/2), $this->getYLabel(), $yLabelColor);

		//creating x label
		$xLabelColorArray = $this->getRGB($this->getXLabelColor());
		$xLabelColor = imagecolorallocate($im, $xLabelColor[0], $xLabelColor[1], $xLabelColor[2]);

		//write string x label
		imagestring($im, 5, $this->getPlotWidth()/2, $topMargin + $this->getPlotHeight()+20, $this->getXLabel(), $xLabelColor);

		
		//making Y labels
		$totalYLabels = count($this->getYLabelsArray()) - 1;
		$avgYPerLabel = floor($this->getPlotHeight() / $totalYLabels);

		$startYLabel = 0;
		$yLabelCtr = 1;
		$yLabelPointsArray = array();
		$yLabelPointsArray[] = $startYLabel;
		while($yLabelCtr <= $totalYLabels) {
			$startYLabel += $avgYPerLabel;
			$yLabelPointsArray[] = $startYLabel;
			$yLabelCtr++;
		}

		//making ylables array
		$yLabelsColorArray = $this->getRGB($this->getYLabelsColor());
		$yLabelsColor = imagecolorallocate($im, $yLabelsColor[0], $yLabelsColor[1], $yLabelsColor[2]);

		//fetching y labels
		$yPoint = 0;
		$yLabelsArray = array_reverse($this->getYLabelsArray());

		//making axis color
		$axisColorArray = $this->getRGB($this->getAxisColor());
		$axisColor = imagecolorallocate($im, $axisColorArray[0], $axisColorArray[1], $axisColorArray[2]);

		while($yPoint < count($yLabelPointsArray)) {
			//print y labels
			imagestring($im, 2, $leftMargin-20 , $topMargin + $yLabelPointsArray[$yPoint]-5, $yLabelsArray[$yPoint], $yLabelsColor);
			if($yPoint != 0 ) {
				//print axis
				imageline($im, $leftMargin, $topMargin + $yLabelPointsArray[$yPoint]+3, $leftMargin + $this->getPlotWidth(), $topMargin + $yLabelPointsArray[$yPoint]+3, $axisColor);
			}
			$yPoint++;
		}


		//making X labels
		$totalXLabels = count($this->getXLabelsArray()) - 1;
		$avgXPerLabel = floor($this->getPlotWidth() / $totalXLabels);

		$startXLabel = 0;
		$xLabelCtr = 1;
		$xLabelPointsArray = array();
		$xLabelPointsArray[] = $startXLabel;
		while($xLabelCtr <= $totalXLabels) {
			$startXLabel += $avgXPerLabel;
			$xLabelPointsArray[] = $startXLabel;
			$xLabelCtr++;
		}

		//making x labels color
		$xLabelsColorArray = $this->getRGB($this->getXLabelsColor());
		$xLabelsColor = imagecolorallocate($im, $xLabelsColor[0], $xLabelsColor[1], $xLabelsColor[2]);

		$xPoint = 0;
		$xLabelsArray = $this->getXLabelsArray();

		//first point shown outside loop, because, first label to start from 0 position, and rest of elements to be labelled in plot area
		imagestring($im, 2, $leftMargin + $xLabelPointsArray[$xPoint] , $topMargin + $this->getPlotHeight() + 2, $xLabelsArray[$xPoint], $yLabelsColor);

		$xPositionsArray = array();
		$xPositionsArray[] = $leftMargin + $xLabelPointsArray[$xPoint];

		$xPoint++;

		while($xPoint < count($xLabelPointsArray)) {
			//printing x labels
			imagestring($im, 2, $xLabelPointsArray[$xPoint] , $topMargin + $this->getPlotHeight() + 2, $xLabelsArray[$xPoint], $yLabelsColor);
			$xPositionsArray[] = $xLabelPointsArray[$xPoint]-15;
			$xPoint++;
		}
		
		//making bars color

		$dataArrays = $this->getDataArray();

	
		$dataArrayCounter = -1;
		$startX = 0;
		$totalWidth = 65;
		$totalBars = count($dataArrays);
		$widthPerBar = $totalWidth / $totalBars;
		foreach($dataArrays as $dataArrayKey => $dataArray) {
			$dataColorArray = $this->getDataColorArray();
			$dataColorValue = $this->getRGB($dataColorArray[++$dataArrayCounter]);
			$dataColor = imagecolorallocatealpha($im, $dataColorValue[0], $dataColorValue[1], $dataColorValue[2], $alpha);
			$ctr = 0;
			foreach($dataArray as $recordKey => $recordVal) {
				//printing bars and value
				$maxValue = max($this->getYLabelsArray());
				$value = round(($this->getPlotHeight() * $recordVal) / $maxValue,0);
				$barLabel = $recordVal;
				if (is_string($dataArrayKey) and !empty($dataArrayKey)) {
					$barLabel .= " [ $dataArrayKey ]";
				}
				$recordKey;
				imagerectangle($im, $xPositionsArray[$ctr] + $startX, $this->getPlotHeight()+ $topMargin, $xPositionsArray[$ctr] + $startX+$widthPerBar, $this->getPlotHeight()+ $topMargin- $value +2, $yLabelsColor);
				imagefilledrectangle($im, $xPositionsArray[$ctr]  + $startX, $this->getPlotHeight()+ $topMargin, $xPositionsArray[$ctr] + $startX+$widthPerBar, $this->getPlotHeight()+ $topMargin- $value+2, $dataColor);
				imagestringup($im, 1, $xPositionsArray[$ctr]+$startX+1 , $this->getPlotHeight()+ $topMargin - $value - 5, $barLabel, $xLabelsColor);
				$ctr++;
			}
			$startX += $widthPerBar;
		}

		//saving image
		imagejpeg($im, $savePath);
		//cleanup
		imagedestroy($im);
	}


	/** function to be called for making image 
		@param savePath: path and name of image to be stored (mandatory)
		@param logoImage: give path and name of logo image to be shown in graph (optional)
	*/
	public function makeLineGraph($savePath) {

		//set plot starting points
		$leftMargin = ($this->getGraphWidth() - $this->getPlotWidth())/2;
		$topMargin = ($this->getGraphHeight() - $this->getPlotHeight())/2;

		
		//create graph
		$im = @imagecreatetruecolor($this->getGraphWidth(), $this->getGraphHeight()) or die("Cannot create image");

		//set graph background color
		$alpha = $this->getAlpha();
		$grapColorArray = $this->getRGB($this->getGraphColor());
		$graphColor = imagecolorallocatealpha($im, $grapColorArray[0], $grapColorArray[1], $grapColorArray[2], 50);
		imagefill($im, 0, 0, $graphColor);


		//set plot color
		$plotColorArray = $this->getRGB($this->getPlotColor());
		$plotColor = imagecolorallocate($im, $plotColorArray[0], $plotColorArray[1], $plotColorArray[2]);
		imagefilledrectangle($im, $leftMargin, $topMargin, $leftMargin + $this->getPlotWidth(), $topMargin + $this->getPlotHeight(), $plotColor);

		//creating y label
		$yLabelColorArray = $this->getRGB($this->getYLabelColor());
		$yLabelColor = imagecolorallocate($im, $yLabelColor[0], $yLabelColor[1], $yLabelColor[2]);

		//write string y label
		imagestringup($im, 5, $leftMargin-50, $topMargin + ($this->getGraphHeight()/2), $this->getYLabel(), $yLabelColor);

		//creating x label
		$xLabelColorArray = $this->getRGB($this->getXLabelColor());
		$xLabelColor = imagecolorallocate($im, $xLabelColor[0], $xLabelColor[1], $xLabelColor[2]);

		//write string x label
		imagestring($im, 5, $this->getPlotWidth()/2, $topMargin + $this->getPlotHeight()+20, $this->getXLabel(), $xLabelColor);

		
		//making Y labels
		$totalYLabels = count($this->getYLabelsArray()) - 1;
		$avgYPerLabel = floor($this->getPlotHeight() / $totalYLabels);
		$avg = max($this->getYLabelsArray()) / $totalYLabels;

		$startYLabel = 0;
		$yLabelCtr = 1;
		$yLabelPointsArray = array();
		$yLabelPointsArray[] = $startYLabel;
		while($yLabelCtr <= $totalYLabels) {
			$startYLabel += $avgYPerLabel;
			$yLabelPointsArray[] = $startYLabel;
			$yLabelCtr++;
		}

		//making ylables array
		$yLabelsColorArray = $this->getRGB($this->getYLabelsColor());
		$yLabelsColor = imagecolorallocate($im, $yLabelsColor[0], $yLabelsColor[1], $yLabelsColor[2]);

		//fetching y labels
		$yPoint = 0;
		$yLabelsArray = array_reverse($this->getYLabelsArray());

		//making axis color
		$axisColorArray = $this->getRGB($this->getAxisColor());
		$axisColor = imagecolorallocate($im, $axisColorArray[0], $axisColorArray[1], $axisColorArray[2]);

		while($yPoint < count($yLabelPointsArray)) {
			//print y labels
			imagestring($im, 2, $leftMargin-20 , $topMargin + $yLabelPointsArray[$yPoint]-5, $yLabelsArray[$yPoint], $yLabelsColor);
			if($yPoint != 0 ) {
				//print axis
				imageline($im, $leftMargin, $topMargin + $yLabelPointsArray[$yPoint]+3, $leftMargin + $this->getPlotWidth(), $topMargin + $yLabelPointsArray[$yPoint]+3, $axisColor);
			}
			$yPoint++;
		}


		//making X labels
		$totalXLabels = count($this->getXLabelsArray()) - 1;
		$avgXPerLabel = floor($this->getPlotWidth() / $totalXLabels);

		$startXLabel = 0;
		$xLabelCtr = 1;
		$xLabelPointsArray = array();
		$xLabelPointsArray[] = $startXLabel;
		while($xLabelCtr <= $totalXLabels) {
			$startXLabel += $avgXPerLabel;
			$xLabelPointsArray[] = $startXLabel;
			$xLabelCtr++;
		}

		//making x labels color
		$xLabelsColorArray = $this->getRGB($this->getXLabelsColor());
		$xLabelsColor = imagecolorallocate($im, $xLabelsColor[0], $xLabelsColor[1], $xLabelsColor[2]);

		$xPoint = 0;
		$xLabelsArray = $this->getXLabelsArray();
		$extraArray = $this->getExtraArray();
		$maxX = 50;
		$newVal = (int) $maxX/10;
		$newVal = ceil($newVal) * 10;
		$newValArray = range(0, $newVal, 10);


		//first point shown outside loop, because, first label to start from 0 position, and rest of elements to be labelled in plot area
		imagestring($im, 2, $leftMargin + $newValArray[$xPoint] , $topMargin + $this->getPlotHeight() + 2, $newValArray[$xPoint], $yLabelsColor);

		$xPositionsArray = array();
		$xPositionsArray[] = $leftMargin + $newValArray[$xPoint];

		$xPoint++;
		$totalWidth = $this->getPlotWidth();
		$balanceValues = count($newValArray);
		$perValueSpace = $totalWidth / 5;
		$totalValues = count($xLabelsArray);
		$perPointSpace = $totalWidth / 50;


		$startValue = 0;
		while($xPoint < count($newValArray)) {
			//printing x labels
			$startValue = $leftMargin + ($xPoint * $perValueSpace);
			imagestring($im, 2, $startValue , $topMargin + $this->getPlotHeight() + 2, $newValArray[$xPoint], $yLabelsColor);
			$xPositionsArray[] = $startValue;// + $newValArray[$xPoint];
			$xPoint++;
		}
		$dataArrays = $this->getDataArray();
		
		$newX = 0;
		$newY = 0;

		$dataColorArray = $this->getDataColorArray();
		$dataColorValue = $this->getRGB($dataColorArray[2]);
		$dataColor = imagecolorallocatealpha($im, $dataColorValue[0], $dataColorValue[1], $dataColorValue[2], $alpha);


		$dataColorValue1 = $this->getRGB($dataColorArray[3]);
		$dataColor1 = imagecolorallocatealpha($im, $dataColorValue1[0], $dataColorValue1[1], $dataColorValue1[2], $alpha);


		$dataColorValue2 = $this->getRGB($dataColorArray[4]);
		$dataColor2 = imagecolorallocatealpha($im, $dataColorValue2[0], $dataColorValue2[1], $dataColorValue2[2], $alpha);

		
		
		for($i=0; $i < count($xLabelsArray); $i++) {
			$xLabelVal = $xLabelsArray[$i];
			$oldX = $leftMargin + ($xLabelVal * $perPointSpace);
			$oldY = $this->getPlotHeight()+ $topMargin - ($dataArrays[$i] * ($avgYPerLabel/$avg));

			$xLocation = $oldX;
			$yLocation = $oldY;

			if ($i > 0) {
				imagefilledellipse($im, $oldX, $oldY, 5, 5, $dataColor);
				imageline($im, $oldX, $oldY, $newX, $newY, $dataColor);
			}
			imagestringup($im, 3, $xLocation , $yLocation-10, $xLabelVal, $dataColor1);
			imagestringup($im, 3, $xLocation , $yLocation-10, '   '.$dataArrays[$i], $dataColor2);
			$newX = $oldX;
			$newY = $oldY;
		}

		//saving image
		imagejpeg($im, $savePath);
		//cleanup
		imagedestroy($im);
	}


}//end of class
?>
<?php 

//$History: BarGraphManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 11/04/08   Time: 12:23p
//Updated in $/Leap/Source/Library
//changed line color 
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/03/08   Time: 3:45p
//Updated in $/Leap/Source/Library
//added function makeLineGraph() to make line graphs
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:39p
//Updated in $/Leap/Source/Library
//done minor modification:
//checked if bar record value text is empty then dont show square
//brackets
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/27/08    Time: 4:38p
//Updated in $/Leap/Source/Library
//removed logo showing code
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/27/08    Time: 12:45p
//Updated in $/Leap/Source/Library
//logo showing code commented
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/22/08    Time: 3:32p
//Updated in $/Leap/Source/Library
//changed the code to support "multiple bars" graph
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:29p
//Updated in $/Leap/Source/Library
//added code for VSS
//

?>
