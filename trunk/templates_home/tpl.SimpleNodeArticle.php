<!-- this line puts IE in quirk mode --> 
<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMART3 PHP5 Framework</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>typography.css";</style>

<script language="JavaScript" type="text/JavaScript">
    function showimage(theURL,widthx,heightx){
        w = widthx+20;
        h = heightx+100;
        newwin= window.open(theURL,'image','width='+w+',height='+h+',dependent=no,directories=no,scrollbars=no,toolbar=no,menubar=no,location=no,resizable=yes,left=0,top=0,screenX=0,screenY=0'); 
} 
</script>

</head>

<body>

<!-- --- include header view --- -->
<?php $viewLoader->header();?>

<div id="main">

<div id="mainleft"> 
   
   <!-- print title and body of a navigation node -->
   <?php if(!empty($tpl['article']['overtitle'])): ?>
      <div class="overtitle"><?php echo $tpl['article']['overtitle'];  ?></div>
   <?php endif; ?>
   <h3><?php echo $tpl['article']['title'];  ?></h3>
   <?php if(!empty($tpl['article']['subtitle'])): ?>
      <div class="subtitle"><?php echo $tpl['article']['subtitle'];  ?></div>
   <?php endif; ?>
   <?php if(!empty($tpl['article']['header'])): ?>
      <div class="header"><?php echo $tpl['article']['header'];  ?></div>
   <?php endif; ?>
   <div class="body"><?php echo $tpl['article']['body'];  ?></div>
   <?php if(!empty($tpl['article']['ps'])): ?>
      <div class="ps"><?php echo $tpl['article']['ps'];  ?></div>
   <?php endif; ?>
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
