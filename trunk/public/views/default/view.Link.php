<!-- this line puts IE in quirk mode --> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Open Publisher - Links</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo $view['urlCss']; ?>base.css";</style>
<style type="text/css">@import"<?php echo $view['urlCss']; ?>link.css";</style>

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
   <h1> <?php echo $view['node']['title'];  ?> </h1>
   
  <div id="linkleft">
    <?php foreach($view['links'] as $link): ?>
      <div id="linkmainarticle">
        <h2 class="linkitemtitle"><a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a></h2>  
        <?php echo $link['description']; ?>
        <!-- --- show edit link if user is logged --- -->
        <?php if(isset($view['showEditLink'])): ?>
          <div style="text-align: right;font-size: 1.2em;"><a href="<?php echo $view['urlBase']; ?>/Module/mod/link/cntr/editLink/id_node/<?php echo $link['id_node'];  ?>/id_link/<?php echo $link['id_link'];  ?>/disableMainMenu/1">edit this link</a></div>
        <?php endif; ?>  
      </div>
    <?php endforeach; ?>
  </div>
  
  <?php if(count($view['childNodes'])>0): ?>
  <div id="linkright">  
    <div class="cattitle">Link Categories</div>
    <ul>
      <?php foreach($view['childNodes'] as $category): ?>
        <li>
          <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $category['id_node']; ?>"><?php echo $category['title']; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
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
