<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Open Publisher PHP5 CMS - <?php echo $tpl['node']['title'];  ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="index, follow" />
<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>typography.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>node.css";</style>

<!-- open new window with full size image -->
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
   
   <!-- print title and body of a navigation node -->
   <h1> <?php echo $tpl['node']['title'];  ?> </h1>
   <?php echo $tpl['node']['body'];  ?>

   <!-- print article titles of the current navigation node -->
   <?php if(count($tpl['nodeArticles']) > 0): ?>
   <dl>
     <dt>Articles:</dt>
     <dd>
       <ul>
         <?php foreach($tpl['nodeArticles'] as $article): ?>
           <li class="li"><a href="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a></li>
         <?php endforeach; ?>
       </ul>
     </dd>
   </dl>
   <?php endif; ?>

   <!-- print subnodes of the current navigation node -->
   <?php if(count($tpl['childNodes']) > 0): ?>
   <dl>
     <dt>Sub-Categories:</dt>
     <dd>   
       <ul>
         <?php foreach($tpl['childNodes'] as $cnode): ?>
           <li class="li"><a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $cnode['id_node']; ?>"><?php echo $cnode['title']; ?></a></li>
         <?php endforeach; ?>
       </ul>
     </dd>
   </dl>
   <?php endif; ?>
        
</div>

<div id="mainright">
    <!-- --- include right border view --- -->
    <?php $viewLoader->rightBorder();?>
</div>

 <br style="clear:both;" />
</div>

<!-- --- include footer view --- -->
<?php $viewLoader->footer();?>

</body>
</html>
