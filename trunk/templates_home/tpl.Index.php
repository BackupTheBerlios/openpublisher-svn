<!-- this line puts IE in quirk mode --> 
<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>typography.css";</style>

<title>SMART3 PHP5 Framework</title>

</head>

<body>

<!-- --- include header view --- -->
<?php $viewLoader->header();?>

<div id="main">

  <div id="mainleft"> 
    <?php echo $tpl['text']['body']; ?>  
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
