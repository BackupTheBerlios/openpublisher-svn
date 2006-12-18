<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />

<meta name="robots" content="index, follow" />
<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />

<style type="text/css">@import"<?php echo $view['cssFolder']; ?>base.css";</style>
<style type="text/css">@import"<?php echo $view['cssFolder']; ?>typography.css";</style>

<script language="JavaScript" type="text/JavaScript">
    function showimage(theURL,widthx,heightx){
        w = widthx+20;
        h = heightx+100;
        newwin= window.open(theURL,'image','width='+w+',height='+h+',dependent=no,directories=no,scrollbars=no,toolbar=no,menubar=no,location=no,resizable=yes,left=0,top=0,screenX=0,screenY=0'); 
} 
</script>

<title>Open Publisher PHP5 CMS - <?php echo $view['article']['title'];  ?></title>

</head>

<body>

<!-- --- include header view --- -->
<?php echo $view['header'];?>

<!-- --- Middle + right column --- -->
<div id="main">

<!-- --- Left column of main --- -->
<div id="mainleft"> 
   <!-- --- show current navigation node branche --- -->
   <div id="branch">
    <?php  foreach($view['nodeBranch'] as $bnode): ?>
      <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
    <?php endforeach; ?>
    <?php if(isset($view['node']['rewrite_name'])): ?>
      <a href="<?php echo $view['urlBase']; ?>/<?php echo $view['node']['rewrite_name']; ?>"><?php echo $view['node']['title']; ?></a>
    <?php else: ?>
    <a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $view['node']['id_node']; ?>"><?php echo $view['node']['title']; ?></a>
    <?php endif; ?>
    <hr class="hr" />
   </div>
   
   <!-- print article overtitle -->
   <?php if(!empty($view['article']['overtitle'])): ?>
      <div class="overtitle"><?php echo $view['article']['overtitle'];  ?></div>
   <?php endif; ?>
   
   <!-- print article title -->
   <h3><?php echo $view['article']['title'];  ?></h3>
   
   <!-- print article subtitle -->
   <?php if(!empty($view['article']['subtitle'])): ?>
      <div class="subtitle"><?php echo $view['article']['subtitle'];  ?></div>
   <?php endif; ?>
   
   <!-- print article pubdate + modifydate -->
   <div class="date">Publish date: <?php echo $view['article']['pubdate']; ?></div>
   <div class="date">Modify date:  <?php echo $view['article']['modifydate']; ?></div>

   <!-- --- show edit article link if user is logged --- -->
   <?php if(isset($view['showEditLink'])): ?>
     <div style="text-align: right;font-size: 1.2em;"><a href="<?php echo $view['urlBase']; ?>/Module/mod/article/cntr/editArticle/id_node/<?php echo $view['article']['id_node'];  ?>/id_article/<?php echo $view['article']['id_article'];  ?>/disableMainMenu/1">edit this article</a></div>
   <?php endif; ?>  
   
   <!-- print article header -->
   <?php if(!empty($view['article']['header'])): ?>
      <div class="header"><?php echo $view['article']['header'];  ?></div>
   <?php endif; ?>
   
   <!-- print article body -->
   <div class="body"><?php echo $view['article']['body'];  ?></div>
   
   <!-- print article ps -->
   <?php if(!empty($view['article']['ps'])): ?>
      <div class="ps"><?php echo $view['article']['ps'];  ?></div>
   <?php endif; ?>
   
   <!-- Show Comments -->
   
   <?php if($view['article']['allow_comment'] == 1): ?>
     <a name="comments"></a>
     <h4>Comments: </h4>
     <?php foreach($view['articleComments'] as $comment): ?>
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
     
     <?php if(isset($view['showCommentForm'])): ?>
       <a name="commentform"></a>
       <h3>Add comment: </h3>
     
       <?php if(!empty($view['cmessage'])): ?>
         <div id="commentmessage"><?php echo $view['commentMessage']; ?></div>
       <?php endif; ?>                      
                        
       <form name="comment" accept-charset="<?php echo $view['charset']; ?>" method="post" action="<?php echo $view['urlBase']; ?>/id_article/<?php echo $view['article']['id_article']; ?>/cache/0#commentform">
       <dl id="commentformelements">
         <dd class="commentFormCol1">
           Author: 
         </dd>
         <dd class="commentFormCol2">
           <input name="cauthor" type="text" value="<?php echo $view['cauthor']; ?>" size="50" maxlength="255" />
         </dd>
         <dd class="commentFormCol1">
           Email: 
         </dd>
         <dd class="commentFormCol2">
           <input name="cemail" type="text" value="<?php echo $view['cemail']; ?>" size="50" maxlength="255" />
         </dd>
         <dd class="commentFormCol1">
           Url:  
         </dd>
         <dd class="commentFormCol2">
           <input name="curl" type="text" value="<?php echo $view['curl']; ?>" size="50" maxlength="255" />
         </dd>
         <dd class="commentFormCol1">
           Comment:   
         </dd>
         <dd class="commentFormCol2">
           <textarea name="cbody" cols="60" rows="15" id="cbody"><?php echo $view['cbody']; ?></textarea>
           <br /><strong>You can format comment text with phpBB code:</strong><br>&nbsp;&nbsp;[url=http://www.example.com]My site[/url]<br>&nbsp;&nbsp;[email]test@example.com[/email]<br>
           <a href="http://www.phpbb.com/support/guide/#section4_2_5" target="_blank">See full phpBB code doc</a>
         </dd>
         <dd class="commentFormCol1">
           Turing Key:   
         </dd>
         <dd class="commentFormCol2">
           <input type="text" name="captcha_turing_key" value="" maxlength="5" size="10" />
           <input type="hidden" name="captcha_public_key" value="<?php echo $view['public_key']; ?>" maxlength="5" size="40" />
           <img src="<?php echo $view['captcha_pic']; ?>" border="1" />
         </dd>
         <dd class="commentFormCol2">
           <?php if(isset($view['showCommentPreview'])): ?>
             <input name="addComment" type="submit" id="addComment" value="add comment">  &nbsp;&nbsp;&nbsp;&nbsp; <input name="previewComment" type="submit" id="previewComment" value="preview comment" />
           <?php else: ?>
             <input name="addComment" type="submit" id="addComment" value="add comment">  &nbsp;&nbsp;&nbsp;&nbsp; <input name="previewComment" type="submit" id="previewComment" value="preview comment" />
           <?php endif; ?>
         </dd>
       </dl>
     <?php endif; ?>   
     
     <!-- show comment preview -->                 
     <?php if(isset($view['showCommentPreview'])): ?>
       <a name="preview"></a>
       <h2>Article comment preview: </h2>
       <dl class="comment">
         <dd class="commentheader">
           Posted by <?php echo $view['commentPreview']['author']; ?> 
           <?php if(!empty($view['commentPreview']['url'])): ?>
             / <a href="<?php echo $view['commentPreview']['url']; ?>">site</a>
           <?php  endif; ?>
         </dd>
         <dd class="commentbody">
           <?php echo $view['commentPreview']['body']; ?>
         </dd>
       </dl>
     <?php endif; ?>   
   
</div>

<!-- --- Right column of main --- -->
<div id="mainright">

    <!-- --- include right border view --- -->
    <?php echo $view['rightBorder']; ?>

    <!-- --- show keywords related articles if any --- -->
    <?php if(count($view['keywordArticle'])): ?>   
      <div class="keybloc">
        <h5>Key related articles</h5>
        <ul>
          <?php foreach($view['keywordArticle'] as $karticle): ?>
            <li><a href="<?php echo $view['urlBase']; ?>/id_article/<?php echo $karticle['id_article']; ?>"><?php echo $karticle['title'] ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- --- show keywords related links if any --- -->
    <?php if(count($view['keywordLink'])): ?>       
      <div class="keybloc">
        <h5>Key related links</h5>
        <ul>
          <?php foreach($view['keywordLink'] as $klink): ?>
            <li><a href="<?php echo $klink['url']; ?>"><?php echo $klink['title'] ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    
</div>

 <br style="clear:both;" />
 
</div>

<!-- --- include footer view --- -->
<?php echo $view['footer'];?>

</body>
</html>
