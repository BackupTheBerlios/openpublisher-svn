<!-- this line puts IE in quirk mode --> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Open Publisher PHP5 CMS - <?php echo $view['node']['title'];  ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="index, follow" />
<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<link rel="stylesheet" href="<?php echo $view['urlCss']; ?>base.css" type="text/css" media="projection, screen, tv" />
<link rel="stylesheet" href="<?php echo $view['urlCss']; ?>typography.css" type="text/css" media="projection, screen, tv" />
<link rel="stylesheet" href="<?php echo $view['urlCss']; ?>node.css" type="text/css" media="projection, screen, tv" />

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
<?php echo $view['header'];?>

<div id="main">

<div id="mainleft"> 

   <!-- --- show current navigation node branche --- -->
   <?php if(count($view['nodeBranch']) > 0): ?>
   <div id="branch">
    <?php  foreach($view['nodeBranch'] as $bnode): ?>
      <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
    <?php endforeach; ?>
    <hr class="hr" />
   </div>     
   <?php endif; ?>

  <!-- --- show edit link if user is logged --- -->
  <?php if(isset($view['showEditLink'])): ?>
    <div style="float: right;font-size: 1em;"><a href="admin.php?mod=navigation&view=editNode&id_node=<?php echo $view['node']['id_node'];  ?>&disableMainMenu=1">edit this node</a></div>
  <?php endif; ?>  
   
   <!-- print title and body of a navigation node -->
   <h1> <?php echo $view['node']['title'];  ?> </h1>
   <?php echo $view['node']['body'];  ?>

   <!-- print article titles of the current navigation node -->
   <?php if(count($view['nodeArticles']) > 0): ?>
   <dl>
     <dt>Articles:</dt>
     <dd>
       <ul>
         <?php foreach($view['nodeArticles'] as $article): ?>
           <li class="li">
             <div class="date">Publish date: <?php echo $article['pubdate']; ?></div>
             <div class="date">Modify date:  <?php echo $article['modifydate']; ?></div>
             <a href="<?php echo $view['urlBase']; ?>/id_article/<?php echo $article['id_article']; ?>"><?php echo $article['title']; ?></a></li>
         <?php endforeach; ?>
       </ul>
     </dd>
   </dl>
   <?php endif; ?>

   <!-- print subnodes of the current navigation node -->
   <?php if(count($view['childNodes']) > 0): ?>
   <dl>
     <dt>Sub-Categories:</dt>
     <dd>   
       <ul>
         <?php foreach($view['childNodes'] as $cnode): ?>
           <li class="li"><a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $cnode['id_node']; ?>"><?php echo $cnode['title']; ?></a></li>
         <?php endforeach; ?>
       </ul>
     </dd>
   </dl>
   <?php endif; ?>
        
</div>

<div id="mainright">
    <!-- --- include right border view --- -->
    <?php echo $view['rightBorder'];?>
</div>

 <br style="clear:both;" />
</div>

<!-- --- include footer view --- -->
<?php echo $view['footer'];?>

</body>
</html>
