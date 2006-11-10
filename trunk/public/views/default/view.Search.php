<!-- this line puts IE in quirk mode --> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMART3 PHP5 Framework - Search Results</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="noindex, nofollow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo $view['urlCss']; ?>base.css";</style>
<style type="text/css">@import"<?php echo $view['urlCss']; ?>search.css";</style>

</head>

<body>

<!-- --- include header view --- -->
<?php echo $view['header'];?>

<div id="main">

<div id="mainleft"> 

   <h1>search result</h1>

   <!-- show pager links to other result pages -->
   <?php if(!empty($view['pager'])): ?>
     <div id="pager"><?php echo $view['pager']; ?></div>
   <?php endif; ?>
  
   <dl id="search">
   <?php foreach($view['articles'] as $article): ?>
     <dd class="articlenodebranch"> 
       <?php  foreach($article['nodeBranch'] as $bnode): ?>
         <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
       <?php endforeach; ?>
       <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $article['node']['id_node']; ?>"><?php echo $article['node']['title']; ?></a>
     </dd>
     <dd class="articletitle">
       <a href="<?php echo $view['urlBase']; ?>/id_article/<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a>
     </dd>
     <?php if(!empty($article['description'])): ?>
     <dd class="articledescription">
       <?php echo $article['description']; ?>
     </dd>
     <?php endif; ?>
   <?php endforeach; ?>
   </dl>

   <!-- show pager links to other result pages -->
   <?php if(!empty($view['pager'])): ?>
     <div id="pager"><?php echo $view['pager']; ?></div>
   <?php endif; ?>
       
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