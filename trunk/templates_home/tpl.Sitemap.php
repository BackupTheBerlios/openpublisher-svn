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
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>sitemap.css";</style>

</head>

<body>

<!-- --- include header view --- -->
<?php $viewLoader->header();?>

<div id="main">

<div id="mainleft"> 

   <h1>Site Map</h1>
   
   <!-- --- show the whole navigation node tree (sitemap) --- -->
   <ul id="sitemap">
     <?php foreach($tpl['tree'] as $node):  ?>
        <li class="nodelevel<?php echo $node['level']; ?>">-<a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a></li>
     <?php endforeach; ?>
   </ul>
        
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