<script language="JavaScript" type="text/JavaScript">
function nodemap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=navigation&view=nodemap&openerModule=article','',mm); }
function search_art(search){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=yes,width=700,height=450';
newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=article&view=search&openerModule=article&search='+encodeURI(search),'',mm); }
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
a.search_pager {
  font-size: 14px;
}
span.search_pager {
  font-size: 14px;
  font-weight: bold;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="86%" align="left" valign="top">
  <div class="font12 indent5">
  <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&article_page=1">Top</a>
  <?php foreach($tpl['branch'] as $node): ?>
   / <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $node['id_node']; ?>&article_page=1"><?php echo $node['title']; ?></a>
  <?php endforeach; ?>
  <?php if($tpl['id_node']!=0): ?>
     <span class="font12bold"> / <?php echo $tpl['node']['title']; ?></span>
  <?php endif; ?>
  </div>
  <?php if(isset($tpl['nodes']) && (count($tpl['nodes'])>0)): ?>
    <?php foreach($tpl['nodes'] as $node): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="1%" align="left" valign="top" class="font10">&nbsp;</td>
        <td width="1%" align="left" valign="top" class="font10">
           <?php if($node['status']==1): ?>
              <img src="./modules/common/media/pics/inactive.png" width="21" height="21">
           <?php elseif($node['status']==2): ?>
              <img src="./modules/common/media/pics/active.png" width="21" height="21">
           <?php elseif($node['status']==3): ?>
              <img src="./modules/common/media/pics/restricted.png" width="21" height="21">       
           <?php endif; ?>    
    </td>
        <td width="98%" align="left" valign="top" class="font12">
          <?php echo '<a href="'.SMART_CONTROLLER.'?mod=article&id_node='.$node['id_node'].'&article_page=1">'.$node['title'].'</a>'; ?>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
   <?php endif; ?>
  <?php if(isset($tpl['articles']) && (count($tpl['articles'])>0)): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="55%" align="left" valign="top" bgcolor="#CCCCFF" class="font12bold">&nbsp;Articles</td>
      </tr>  
    </table>
    <?php if(count($tpl['articles'])>0): ?>
     <!-- show pager links to other result pages -->   
  <?php if(!empty($tpl['pager'])): ?>
    <table width="100%" border="0" cellspacing="6" cellpadding="0">
            <tr>
               <td width="190" align="left" valign="top">
           <?php echo $tpl['pager']; ?>
               </td>
            </tr>
    </table>
    <hr>
  <?php endif; ?> 
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <?php endif; ?>
    <?php foreach($tpl['articles'] as $article): ?> 
      <tr>
        <td width="1" align="left" valign="top" class="font10">
    <?php if($article['status']==0): ?>
      delete
    <?php elseif($article['status']==1): ?>
      cancel
    <?php elseif($article['status']==2): ?>
      propose
    <?php elseif($article['status']==3): ?>
      edit
    <?php elseif($article['status']==4): ?>
      publish
    <?php elseif($article['status']==5): ?>
      protect               
    <?php endif; ?>
    </td>
     <?php if($tpl['order']=='rank'): ?>
        <td width="1" align="left" valign="top" class="itemnormal"><?php if($tpl['showArticle']==TRUE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_article_up=<?php echo $article['id_article']; ?>&id_node=<?php echo $tpl['id_node']; ?>"><img src="./modules/common/media/pics/up.png" width="21" height="21" border="0"></a><?php else: ?>&nbsp;<?php endif; ?></td>
        <td width="1" align="left" valign="top" class="itemnormal"><?php if($tpl['showArticle']==TRUE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_article_down=<?php echo $article['id_article']; ?>&id_node=<?php echo $tpl['id_node']; ?>"><img src="./modules/common/media/pics/down.png" width="21" height="21" border="0"></a><?php else: ?>&nbsp;<?php endif; ?></td>
       <?php endif; ?>
        <td width="99%" align="left" valign="top" class="itemnormal">
                    <div class="font10">
           Publish Date: <?php echo $article['pubdate']; ?><br>
           Last modified: <?php echo $article['modifydate']; ?><br>
          </div>
      <?php if(($tpl['showArticle']==true)&&
               ($article['lock']==false)&&
               ($article['hasAccess']==true)): ?>
        <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle&id_node=<?php echo $tpl['id_node']; ?>&id_article=<?php echo $article['id_article']; ?>&disableMainMenu=1"><?php echo $article['title']; ?></a>
      <?php else: ?>
        <?php echo $article['title']; ?>
      <?php endif; ?>
      <?php if(!empty($article['description'])): ?>
      <div class="font10"><?php echo $article['description']; ?></div>
      <?php endif; ?>
        </td>
      </tr>
  <?php endforeach; ?>    
  <?php if(count($tpl['articles'])>0): ?>
    </table>
     <!-- show pager links to other result pages -->   
  <?php if(!empty($tpl['pager'])): ?>
      <hr>
    <table width="100%" border="0" cellspacing="6" cellpadding="0">
            <tr>
               <td width="190" align="left" valign="top">
           <?php echo $tpl['pager']; ?>
               </td>
            </tr>
    </table>
    <hr>
  <?php endif; ?> 
    <?php endif; ?> 
  
  <?php elseif(($tpl['showArticle']==TRUE)&&($tpl['showAddArticle']==TRUE)): ?>
        <br><br><div class="font12bold">There is currently no article under this node. You may add some one here.</div><br><br>  
    <?php endif; ?>   
    </td>
    <td width="14%" align="left" valign="top" class="font12">
    <?php if(($tpl['showArticle']==TRUE)&&($tpl['showAddArticle']==TRUE)): ?>
       <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=addArticle&id_node=<?php echo $tpl['id_node']; ?>">add article</a>
    <?php endif; ?>
    <p><a href="javascript:nodemap();">NodesMap</a></p>
    <?php if(isset($tpl['articles']) && (count($tpl['articles'])>0)): ?>  
     <div class="font10">
    <form name="order" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $tpl['id_node']; ?>">
    article order: <br> 
            <select name="order" class="topselect">
            <option value="title"<?php if($tpl['order']=='title') echo ' selected="selected"'; ?>>title</option>
            <option value="pubdate"<?php if($tpl['order']=='pubdate') echo ' selected="selected"'; ?>>publish date</option>
            <option value="modifydate"<?php if($tpl['order']=='modifydate') echo ' selected="selected"'; ?>>modify date</option>
      <option value="articledate"<?php if($tpl['order']=='articledate') echo ' selected="selected"'; ?>>article date</option>
            <option value="rank"<?php if($tpl['order']=='rank') echo ' selected="selected"'; ?>>rank</option>
            </select><br>
      asc: <input name="ordertype" type="radio" value="asc"<?php if($tpl['ordertype']=='asc') echo ' checked="checked"'; ?> class="topselect"> 
      desc: <input name="ordertype" type="radio" value="desc"<?php if($tpl['ordertype']=='desc') echo ' checked="checked"'; ?> class="topselect">
      <br><input type="submit" name="reorder" value="reorder" class="topselect">
      </form></div>
    <?php endif; ?>
    <form accept-charset="<?php echo $tpl['charset']; ?>" name="searchform" method="post" action="">
      <input name="search" type="text" id="search" size="20" maxlength="255" class="topselect"><br>
     <input name="searchbutton" type="button" value="search" class="topselect" onClick="search_art(this.form.search.value);">
    </form>
  </td>
  </tr>
</table>
