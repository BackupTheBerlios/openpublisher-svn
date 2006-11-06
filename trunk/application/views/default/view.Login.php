<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="noindex, nofollow" />

<style type="text/css">@import"<?php echo JAPA_PUBLIC_DIR; ?><?php echo $view['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo JAPA_PUBLIC_DIR; ?><?php echo $view['cssFolder']; ?>login.css";</style>

<title>SMART3 PHP5 Framework - Login</title>

<script language="JavaScript">
   function subok(s){
      s.value = "wait ...";
   }
</script>

</head>

<body>

<!-- --- include header view --- -->
<?php echo $view['header'];?>

<div id="main">

  <div id="mainleft">
  
    <form name="loginform" method="post" action="<?php echo SMART_CONTROLLER; ?>?view=login">
      
      <dl id="login">
        <dd class="loginitem">
          Login<br />
          <input type="text" name="login" maxlength="100" size="25" value="<?php echo $view['login']; ?>" />
        </dd>
        <dd class="loginitem">
           Password<br />
           <input type="password" name="password" size="25" maxlength="100" />
        </dd>
        <dd class="loginitem">
           Turing Key<br />
           <input type="text" name="captcha_turing_key" value="" maxlength="5" size="7" />
           <input type="hidden" name="captcha_public_key" value="<?php echo $view['public_key']; ?>" maxlength="5" size="40" />
           <img src="<?php echo $view['captcha_pic']; ?>" border="1" />
        </dd>
        <dd class="loginitem">
           <input type="submit" name="dologin" value="login" onclick="subok(this.form.dologin);" class="loginbutton" />
        </dd>
      </dl>
      
    </form>
    
  </div>

  <div id="mainright">
    <!-- --- include right border view --- -->
    <?php echo $view['rightBorder'];?>
  </div>

  <br style="clear:both;" />
  
</div>

<!-- --- include header view --- -->
<?php echo $view['footer'];?>

</body>
</html>
