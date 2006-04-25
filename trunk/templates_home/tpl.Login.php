<!-- this line puts IE in quirk mode -->
<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="noindex, nofollow" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>login.css";</style>

<title>SMART3 PHP5 Framework - Login</title>

</head>

<body>

<!-- --- include header view --- -->
<?php $viewLoader->header();?>

<div id="main">

  <div id="mainleft"> <div id="login">
        <form name="form1" method="post" action="<?php echo SMART_CONTROLLER; ?>?view=login">
              <table width="350" border="0" cellspacing="0" cellpadding="2" align="center">
                <tr align="left" valign="top">
                  <td colspan="2">
                    <div align="center" class="loginerror"> </div>
                  </td>
                </tr>
                <tr>
                  <td width="64%" valign="top" align="left" class="loginitem"> Login<br>
                      <input type="text" name="login" maxlength="1000" size="25" value="<?php echo $tpl['login']; ?>">
                  </td>
                  <td width="36%" valign="top" align="center"> </td>
                </tr>
                <tr>
                  <td width="64%" valign="top" align="left" class="loginitem"> Passwd<br>
                      <input type="password" name="password" size="25" maxlength="100">
                  </td>
                  <td width="36%" valign="middle" align="center" class="logintext"> </td>
                </tr>
                <tr>
                  <td valign="top" align="left" class="loginitem">Turing Key<br>
                      <input type="text" name="captcha_turing_key" value="" maxlength="5" size="25">
                      <input type="hidden" name="captcha_public_key" value="<?php echo $tpl['public_key']; ?>" maxlength="5" size="40">
                  </td>
                  <td align="right" valign="baseline" ><img src="<?php echo $tpl['captcha_pic']; ?>" border="1"></td>
                </tr>
                <tr align="center">
                  <td colspan="2" valign="middle"><br>
                      <input type="submit" name="dologin" value="login" onclick="subok(this.form.dologin);" class="loginbutton">
                  </td>
                </tr>
              </table>
            </form> </div>
  </div>

  <div id="mainright">
    <!-- --- include right border view --- -->
    <?php $viewLoader->rightBorder();?>
  </div>

  <br style="clear:both;" />
  
</div>

<!-- --- include header view --- -->
<?php $viewLoader->footer();?>

</body>
</html>
