<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Open Publisher PHP5 CMS - Search Results</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />

<meta name="robots" content="noindex, nofollow" />
<meta name="author" content="Armand Turpel"/>
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo $view['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo $view['cssFolder']; ?>search.css";</style>

</head>

<body>

<!-- --- include header view --- -->
<?php echo $view['header'];?>

<div id="main">

<div id="mainleft"> 

   <h1><a name="result">search result</a></h1>

   <!-- show pager links to other result pages -->
   <?php if(!empty($view['pager'])): ?>
     <div class="pager"><?php echo $view['pager']; ?></div>
   <?php endif; ?>
  
   <dl id="search">
   <!-- print result articles  -->
   <?php foreach($view['articles'] as $article): ?>
   
   <!-- print node branch of each article  -->
     <dd class="articlenodebranch"> 
     
       <?php  foreach($article['nodeBranch'] as $bnode): ?>
         <?php if(!empty($bnode['rewrite_name'])): ?>
           <a href="<?php echo $view['urlBase']; ?>/<?php echo $bnode['rewrite_name']; ?>"><?php echo $bnode['title']; ?></a> /
         <?php else: ?>
           <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
        <?php endif; ?>
    <?php endforeach; ?>
    <?php if(!empty($article['node']['rewrite_name'])): ?>
      <a href="<?php echo $view['urlBase']; ?>/<?php echo $article['node']['rewrite_name']; ?>"><?php echo $article['node']['title']; ?></a>
    <?php else: ?>
    <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $article['node']['id_node']; ?>"><?php echo $article['node']['title']; ?></a>
    <?php endif; ?>
    
     </dd>
     <!-- print article title  -->
     <dd class="articletitle">
       <?php if(!empty($article['rewrite_name'])): ?>
         <a href="<?php echo $view['urlBase']; ?>/<?php echo $article['rewrite_name']; ?>"><?php echo $article['title']; ?></a>
       <?php else: ?>
         <a href="<?php echo $view['urlBase']; ?>/id_article/<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a>
       <?php endif; ?>
     </dd>
     
     <!-- print article description if available  -->
     <?php if(!empty($article['description'])): ?>
     <dd class="articledescription">
       <?php echo $article['description']; ?>
     </dd>
     <?php endif; ?>
   <?php endforeach; ?>
   </dl>

   <!-- show pager links to other result pages -->
   <?php if(!empty($view['pager'])): ?>
     <div class="pager"><?php echo $view['pager']; ?></div>
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