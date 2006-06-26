<!-- this line puts IE in quirk mode -->
<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- --- AJAX --- -->
<script type='text/javascript' src='<?php echo SMART_RELATIVE_PATH; ?>ajaxserver.php?client=all&amp;stub=all&amp;view=articleAjax'></script>
<script type='text/javascript' src='<?php echo SMART_RELATIVE_PATH; ?>templates_home/ArticleAjax.js'></script>

<script language="JavaScript" type="text/JavaScript">
    function showimage(theURL,widthx,heightx){
        w = widthx+20;
        h = heightx+100;
        newwin= window.open(theURL,'image','width='+w+',height='+h+',dependent=no,directories=no,scrollbars=no,toolbar=no,menubar=no,location=no,resizable=yes,left=0,top=0,screenX=0,screenY=0'); 
} 
</script>

<title>SMART3 PHP5 Framework - Ajax</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="index, follow" />

<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>typography.css";</style>
<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>search.css";</style>

</head>

<body>

<!-- --- include header view --- -->
<?php $viewLoader->header();?>

<div id="main">

<div id="mainleft"> 
   <!-- --- show current navigation node branche --- -->
   <div id="branch">
    <?php  foreach($tpl['nodeBranch'] as $bnode): ?>
      <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
    <?php endforeach; ?>
    <a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $tpl['node']['id_node']; ?>"><?php echo $tpl['node']['title']; ?></a>
    <hr class="hr" />
   </div>
   
   <!-- print title and body of a navigation node -->
   <?php if(!empty($tpl['article']['overtitle'])): ?>
      <div class="overtitle"><?php echo $tpl['article']['overtitle'];  ?></div>
   <?php endif; ?>
   <h3><?php echo $tpl['article']['title'];  ?></h3>
  
   <!-- --- show edit link if user is logged --- -->
   <?php if(isset($tpl['showEditLink'])): ?>
     <div style="text-align: right;font-size: 1.2em;"><a href="admin.php?mod=article&view=editArticle&id_node=<?php echo $tpl['article']['id_node'];  ?>&id_article=<?php echo $tpl['article']['id_article'];  ?>&disableMainMenu=1">edit this article</a></div>
   <?php endif; ?>  
   
   <?php if(!empty($tpl['article']['subtitle'])): ?>
      <div class="subtitle"><?php echo $tpl['article']['subtitle'];  ?></div>
   <?php endif; ?>
   <?php if(!empty($tpl['article']['header'])): ?>
      <div class="header"><?php echo $tpl['article']['header'];  ?></div>
   <?php endif; ?>
   <div class="body"><?php echo $tpl['article']['body'];  ?></div>
   
             <!-- ########### AJAX Examples HTML Code ############ -->
             
             <!-- AJAX  simple text -->
             <input type="button" name="simpletext" value="show text" onclick="remoteTest.simpleText(); return false;">
             <div id="simpletext"></div>             
             <p>&nbsp;</p>
             
             <!-- AJAX  show alert box -->
             <input type="button" name="showalertbox" value="show alert box" onclick="remoteTest.showAlertBox(); return false;">
             <p>&nbsp;</p>
             
             <!-- AJAX  calculator -->
             <h4>Calculator</h4>
             <input name="number1" id="number1" type="text" size="8" maxlength="8">
             +
             <input name="number2" id="number2" type="text" size="8" maxlength="8">
             <input type="button" name="calculate" value="=" onclick="doCalculation(); return false;">
             <span id="result"></span>    
             <p></p>
             
             <!-- AJAX  article search -->
             <h4>Article search</h4>
             <input name="articlesearch" id="articlesearch" type="text" size="35" maxlength="35">
             <input type="button" name="dosearch"  id="dosearch" value="search" onclick="doSearch(); return false;">
             <div id="search"></div>                    
             <!-- ########### END AJAX Examples HTML Code ############ -->  
             
   <?php if(!empty($tpl['article']['ps'])): ?>
      <div class="ps"><?php echo $tpl['article']['ps'];  ?></div>
   <?php endif; ?>
   
   <!-- Show Comments -->
   
   <?php if($tpl['article']['allow_comment'] == 1): ?>
     <a name="comments"></a>
     <h4>Comments: </h4>
     <?php foreach($tpl['articleComments'] as $comment): ?>
       <dl class="comment">
         <dd class="commentheader">Posted by <strong><?php echo $comment['author']; ?></strong>
           at <?php echo $comment['pubdate']; ?>
           <?php if(!empty($comment['url'])): ?>
             / <a href="<?php echo $comment['url']; ?>">url</a>
           <?php  endif; ?>
         </dd>
         <dd class="commentbody">
           <?php echo $comment['body']; ?>
         </dd>
       </dl>
      <?php endforeach; ?>
    <?php endif; ?>
     
     <!-- Show Comment Form -->
     
     <?php if(isset($tpl['showCommentForm'])): ?>
       <a name="commentform"></a>
       <h3>Add comment: </h3>
     
       <?php if(!empty($tpl['cmessage'])): ?>
         <div id="commentmessage"><?php echo $tpl['commentMessage']; ?></div>
       <?php endif; ?>                      
                        
       <form name="comment" accept-charset="<?php echo $tpl['charset']; ?>" method="post" action="<?php echo SMART_CONTROLLER; ?>?id_article=<?php echo $tpl['article']['id_article']; ?>#commentform">
       <dl id="commentformelements">
         <dd class="commentFormCol1">
           Author: 
         </dd>
         <dd class="commentFormCol2">
           <input name="cauthor" type="text" value="<?php echo $tpl['cauthor']; ?>" size="50" maxlength="255" />
         </dd>
         <dd class="commentFormCol1">
           Email: 
         </dd>
         <dd class="commentFormCol2">
           <input name="cemail" type="text" value="<?php echo $tpl['cemail']; ?>" size="50" maxlength="255" />
         </dd>
         <dd class="commentFormCol1">
           Url:  
         </dd>
         <dd class="commentFormCol2">
           <input name="curl" type="text" value="<?php echo $tpl['curl']; ?>" size="50" maxlength="255" />
         </dd>
         <dd class="commentFormCol1">
           Comment:   
         </dd>
         <dd class="commentFormCol2">
           <textarea name="cbody" cols="60" rows="15" id="cbody"><?php echo $tpl['cbody']; ?></textarea>
           <br /><strong>You can format comment text with phpBB code:</strong><br>&nbsp;&nbsp;[url=http://www.yahoo.com]Yahoo[/url]<br>&nbsp;&nbsp;[email=mailto:test@yahoo.com]Email[/email]<br>
           <a href="http://www.phpbb.com/support/guide/#section4_2_5" target="_blank">See full phpBB code doc</a>
         </dd>
         <dd class="commentFormCol1">
           Turing Key:   
         </dd>
         <dd class="commentFormCol2">
           <input type="text" name="captcha_turing_key" value="" maxlength="5" size="10" />
           <input type="hidden" name="captcha_public_key" value="<?php echo $tpl['public_key']; ?>" maxlength="5" size="40" />
           <img src="<?php echo $tpl['captcha_pic']; ?>" border="1" />
         </dd>
         <dd class="commentFormCol2">
           <?php if(isset($tpl['showCommentPreview'])): ?>
             <input name="addComment" type="submit" id="addComment" value="add comment">  &nbsp;&nbsp;&nbsp;&nbsp; <input name="previewComment" type="submit" id="previewComment" value="preview comment" />
           <?php else: ?>
             <input name="addComment" type="submit" id="addComment" value="add comment">  &nbsp;&nbsp;&nbsp;&nbsp; <input name="previewComment" type="submit" id="previewComment" value="preview comment" />
           <?php endif; ?>
         </dd>
       </dl>
     <?php endif; ?>   
                      
     <?php if(isset($tpl['showCommentPreview'])): ?>
       <a name="preview"></a>
       <h2>Article comment preview: </h2>
       <dl class="comment">
         <dd class="commentheader">
           Posted by <?php echo $comment['author']; ?> 
           <?php if(!empty($tpl['commentPreview']['url'])): ?>
             / <a href="<?php echo $tpl['commentPreview']['url']; ?>">site</a>
           <?php  endif; ?>
         </dd>
         <dd class="commentbody">
           <?php echo $tpl['commentPreview']['body']; ?>
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

<!-- --- include header view --- -->
<?php $viewLoader->footer();?>

</body>
</html>

