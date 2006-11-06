<!-- this line puts IE in quirk mode --> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />


<link rel="stylesheet" href="<?php echo $view['urlCss']; ?>base.css" type="text/css" media="projection, screen, tv" />
<link rel="stylesheet" href="<?php echo $view['urlCss']; ?>typography.css" type="text/css" media="projection, screen, tv" />
<title>SMART3 PHP5 Framework</title>

</head>

<body>

<!-- --- include header view --- -->
<?php echo $view['header']; ?>

<div id="main">

  <div id="mainleft"> 

    <!-- --- show edit link if user is logged --- -->
    <?php if(isset($view['showEditLink'])): ?>
        <div style="text-align: right; font-size: 1.2em;">
          <a href="admin.php?mod=misc&view=editText&id_text=1">edit content</a>
        </div>
    <?php endif; ?>  
  
    <?php echo $view['text']['body']; ?>
    
  </div>

  <div id="mainright">
    <!-- --- include right border view --- -->
    <?php echo $view['rightBorder']; ?>
  </div>

  <br style="clear:both;" />
  
</div>

<!-- --- include header view --- -->
<?php echo $view['footer']; ?>

</body>
</html>
