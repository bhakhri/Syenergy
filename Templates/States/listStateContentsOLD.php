	<table cellpadding="3" cellspacing="0" style="border:1px solid #B6C7EB;width:450px;" align="center">
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    if(isset($REQUEST_DATA['status'])) {
                                                    
                        echo '<tr><td colspan="3">'.HtmlFunctions::getInstance()->statusMessage($REQUEST_DATA['status']).'</td></tr>';
                    }
                ?>
            <tr>
				<th bgcolor="#ECF1FB" colspan="2" align="left">States Master</th>
                <th bgcolor="#ECF1FB" align="right"><a href="addState.php" title="add/edit">Add</a></th>
			</tr>
            <tr> 
              <td width="20%"><b>State Code</b></td>
              <td width="40%"><b>State Name</b></td>
              <td width="30%"><b>Country</b></td>
            </tr>
            <?php
            $recordCount = count($stateRecordArray);
            if($recordCount >0 && is_array($stateRecordArray) ) { 
                
                for($i=0; $i<$recordCount; $i++ ) {
                    echo  '
                        <tr> 
                          <td><a href="addState.php?stateId='.$stateRecordArray[$i]['stateId'].'" title="add/edit">'.$stateRecordArray[$i]['stateCode'].'</a></td>
                          <td>'.$stateRecordArray[$i]['stateName'].'</td>
                          <td>'.$stateRecordArray[$i]['countryName'].'</td>
                       </tr>';                  
                }
                
                if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                      require_once(BL_PATH . "/Paging.php");
                      $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                      echo '<tr><td colspan="3">&nbsp;</td></tr>
                      <tr><td colspan="3" align="right">'.$paging->printLinks('fontText').'</td></tr>';                    }
            }
            ?>            
	</table>
