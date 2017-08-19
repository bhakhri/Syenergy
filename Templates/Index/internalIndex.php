<?php
  require_once(BL_PATH . '/HtmlFunctions.inc.php');
  $htmlManager = HtmlFunctions::getInstance();
  $serverDate = date('Y-m-d');
  if (SUBSCRIPTION_STATUS=="PENDING")
    $loginFileName = "logoSubscription.png";
  elseif (SUBSCRIPTION_STATUS=="OK") 
    $loginFileName = "logo.png";
  else
    $loginFileName = "logo.png";
  if($serverDate < '2013-04-15') { 
    $loginFileName = "body_loginv.jpg";
  }
?>
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-login">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-6 text-center">
              <img class="img-responsive center-block" src="<?php echo IMG_HTTP_PATH . DIRECTORY_SEPARATOR . 'SyEnergy.png'; ?>" />
            </div>
            <div class="col-lg-6 text-center">
              <img class="img-responsive center-block" src="<?php echo IMG_HTTP_PATH . DIRECTORY_SEPARATOR . $loginFileName; ?>" />
            </div>
            <div class="col-lg-12 form-group">
              <?php
                //checking for CLIENT_INSTITUTES count              
                if($databaseTamperedFlag == 1) {
                  echo '<div class="alert alert-danger" role="alert">' . DATABASE_TAMPERED . '</div>';
                } else {
                  if(UtilityManager::notEmpty($errorMessage)) {
                  logError('hello'.$errorMessage, WARNING_SEVERITY);
                    echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
                  }
                  if(UtilityManager::notEmpty($errorMessageBlockStudent)) {
                    $messageNew=$htmlManager->removePHPJS(trim($errorMessageBlockStudent),'','1');       
                    echo '<script type="text/javascript">alert(\'$messageNew\'); </script>';
                  }
              ?>
                <form id="form1" name="form1" action="" method="POST" role="form" style="display: block;">
                  <h2>LOGIN</h2>
                  <div class="form-group clearfix">
                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" onblur="populateValues(); return false;">
                  </div>
                  <div class="form-group clearfix">
                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                  </div>
                  <div class="form-group clearfix">
                    <div class="col-lg-6">
                      <label for="instituteId">Institute</label>
                      <select name="instituteId" id="instituteId" class="form-control">
                        <option value="" selected="selected">Select</option>
                        <?php echo HtmlFunctions::getInstance()->getInstituteData(); ?>
                      </select>
                    </div>
                    <div class="col-lg-6">
                      <label for="instituteId">Session</label>
                      <input type="hidden" readonly value="<?php echo $activeSessionDetail[0]['sessionId']; ?>" name="sessionIds" id="sessionIds" >
                      <select name="sessionId" id="sessionId" class="form-control">
                        <option value="" selected="selected">Select</option>
                        <?php
                          // echo HtmlFunctions::getInstance()->getSessionData();
                          // $activeSessionDetail : This variable comes from init.php and it fetches active session's details
                          $return = HtmlFunctions::getInstance()->getSessionData($activeSessionDetail[0]['sessionId'],'sessionName',2);
                          if (empty($return)) {
                            echo HtmlFunctions::getInstance()->getActiveSession();
                          } else {
                            echo $return;
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group clearfix">
                    <div class="col-xs-6">
                      <input type="hidden" name="imgSubmit.x" value="1" />
                      <input type="hidden" name="imgSubmit.y" value="1" />
                      <input type="submit" tabindex="4" class="form-control btn btn-login" value="Login" onclick="return validateLoginForm();">
                    </div>
                    <div class="col-xs-6">     
                      <input type="button" name="imgReset" tabindex="5" class="form-control btn btn-login" value="Reset" onclick="this.form.reset(); return false;">
                    </div>
                  </div>
                  <div class="text-center">
                    <a href="forgot.php" title="Forgot Password" class="redLink">Forgot Password</a>
                  </div>
                </form>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<footer>
  <div class="container">
    <div class="col-md-10 col-md-offset-1 text-center">
      <h6>Powered By syenergy Technologies Pvt Ltd</h6>
    </div>
  </div>
</footer>
