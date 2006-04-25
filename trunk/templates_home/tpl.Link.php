<!-- this line puts IE in quirk mode --> 
<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMART3 PHP5 Framework - Links</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>link.css";</style>

</head>

<body>

<!-- --- include header view --- -->
<?php $viewLoader->header();?>

<div id="main">

<div id="mainleft"> 
   <!-- --- show current navigation node branche --- -->
   <?php if(count($tpl['nodeBranch']) > 0): ?>
   <div id="branch">
    <?php  foreach($tpl['nodeBranch'] as $bnode): ?>
         <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
    <?php endforeach; ?>
    <hr class="hr" />
   </div>
   <?php endif; ?>
   <h1> <?php echo $tpl['node']['title'];  ?> </h1>
   
  <div id="linkleft">
    <?php foreach($tpl['links'] as $link): ?>
      <div id="linkmainarticle">
        <h2 class="linkitemtitle"><a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a></h2>  
        <?php echo $link['description']; ?>
      </div>
    <?php endforeach; ?>
  </div>
  
  <?php if(count($tpl['childNodes'])>0): ?>
  <div id="linkright">  
    <div class="cattitle">Link Categories</div>
    <ul>
      <?php foreach($tpl['childNodes'] as $category): ?>
        <li>
          <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $category['id_node']; ?>"><?php echo $category['title']; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
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
