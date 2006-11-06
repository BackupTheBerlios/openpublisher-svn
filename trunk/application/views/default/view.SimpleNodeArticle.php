<!-- this line puts IE in quirk mode --> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMART3 PHP5 Framework</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo $view['urlCss']; ?>base.css";</style>
<style type="text/css">@import"<?php echo $view['urlCss']; ?>typography.css";</style>

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
   
   <!-- print title and body of a navigation node -->
   <?php if(!empty($view['article']['overtitle'])): ?>
      <div class="overtitle"><?php echo $view['article']['overtitle'];  ?></div>
   <?php endif; ?>
   <h3><?php echo $view['article']['title'];  ?></h3>
   
   <!-- --- show edit link if user is logged --- -->
   <?php if(isset($view['showEditLink'])): ?>
     <div style="text-align: right;font-size: 1.2em;"><a href="admin.php?mod=article&view=editArticle&id_node=<?php echo $view['article']['id_node'];  ?>&id_article=<?php echo $view['article']['id_article'];  ?>&disableMainMenu=1">edit this article</a></div>
   <?php endif; ?>  
   
   <?php if(!empty($view['article']['subtitle'])): ?>
      <div class="subtitle"><?php echo $view['article']['subtitle'];  ?></div>
   <?php endif; ?>
   <?php if(!empty($view['article']['header'])): ?>
      <div class="header"><?php echo $view['article']['header'];  ?></div>
   <?php endif; ?>
   <div class="body"><?php echo $view['article']['body'];  ?></div>
   <?php if(!empty($view['article']['ps'])): ?>
      <div class="ps"><?php echo $view['article']['ps'];  ?></div>
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
