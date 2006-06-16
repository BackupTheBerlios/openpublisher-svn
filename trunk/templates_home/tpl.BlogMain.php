<!-- this line puts IE in quirk mode --> 
<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMART3 PHP5 Framework - Blog</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>typography_blog.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>blogmain.css";</style>

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
   
  <div id="blogleft">
  <?php foreach($tpl['allArticles'] as $article): ?>
  <div id="blogmainarticle">
    <h2 class="blogitemtitle"><a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a></h2>
    
    <!-- --- show edit link if user is logged --- -->
    <?php if(isset($tpl['showEditLink'])): ?>
      <div style="font-size: 1em;"><a href="admin.php?mod=article&view=editArticle&id_node=<?php echo $article['id_node'];  ?>&id_article=<?php echo $article['id_article'];  ?>&disableMainMenu=1">edit this blog posting</a></div>
    <?php endif; ?>  
    
    <?php echo $article['body']; ?>
    <div id="blogmaimarticlefooter">
      Category: <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $article['id_node']; ?>"><?php echo $article['node']['title']; ?></a><br>
      Date: <?php echo $article['pubdate']; ?> / 
      <?php if($article['num_comments'] > 0): ?>
        <a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>#comments">comments: <?php echo $article['num_comments']; ?></a>
      <?php else: ?>
        comments: <?php echo $article['num_comments']; ?>
      <?php endif; ?>
    </div>
  </div>
  
  <?php endforeach; ?>
  </div>
  <?php if(count($tpl['childNodes'])>0): ?>
  <div id="blogright">  
        <div class="cattitle">Blog Categories</div>
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
