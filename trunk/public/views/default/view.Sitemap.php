<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Open Publisher PHP5 CMS - Sitemap</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo $view['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo $view['cssFolder']; ?>sitemap.css";</style>

</head>

<body>

<!-- --- include header view --- -->
<?php echo $view['header'];?>

<div id="main">

<div id="mainleft"> 

   <h1>Site Map</h1>
   
   <!-- --- show the whole navigation node tree (sitemap) --- -->
   <ul id="sitemap">
     <?php foreach($view['tree'] as $node):  ?>
       <?php if(!empty($node['rewrite_name'])): ?>
          <li class="nodelevel<?php echo $node['level']; ?>">-<a href="<?php echo $view['urlBase']; ?>/<?php echo $node['rewrite_name']; ?>"><?php echo $node['title']; ?></a></li>
       <?php else: ?>
          <li class="nodelevel<?php echo $node['level']; ?>">-<a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a></li>
       <?php endif; ?>
     <?php endforeach; ?>
   </ul>
        
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