<script language="JavaScript">
function goto_item(link){
parent.opener.location.href =link; }
</script>
<style type="text/css">
<!--
.branch {
	font-size: 10px;	
}
.article {
  font-size: 12px;
  font-weight: bold;
  color: #0000CC;
}  
a.search_pager {
	font-size: 14px;
}
span.search_pager {
	font-size: 14px;
	font-weight: bold;
}
a.smart_pager {
	font-size: 10px;
}
span.smart_pager {
	font-size: 10px;
	font-weight: bold;
}
-->
</style>
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Article Search</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td>
          <!-- --- show search string --- -->
          <div class="branch">Search: "<?php echo $tpl['search']; ?>"
              <hr class="hr" />
          </div>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top">
          <!-- show pager links to other result pages -->
          <?php if(!empty($tpl['pager'])): ?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="190" align="left" valign="top"> <?php echo $tpl['pager']; ?> </td>
            </tr>
          </table>
          <hr>
          <?php endif; ?>
          <!-- show search result -->
          <?php if(count($tpl['articles']) > 0): ?>
          <table width="90%"  border="0" cellpadding="0" cellspacing="0">
            <tr>			
              <td width="100%" height="161" align="left" valign="top">
                <ul class="subnodeul">
                  <?php foreach($tpl['articles'] as $article): ?>
                  <li class="subnodelist">
                    <div class="branch">
                      <?php  foreach($article['nodeBranch'] as $bnode): ?>
                      <a href="javascript:goto_item('<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $bnode['id_node']; ?>&article_page=1');"><?php echo $bnode['title']; ?></a> /
                      <?php endforeach; ?>
					<a href="javascript:goto_item('<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $article['node']['id_node']; ?>&article_page=1');"><?php echo $article['node']['title']; ?></a> </div>
                    <div class="branch">
					 Publish Date: <?php echo $article['pubdate']; ?><br>
					 Last modified: <?php echo $article['modifydate']; ?><br>
					</div>
                    <div class="article"><a href="javascript:goto_item('<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle&id_node=<?php echo $article['id_node']; ?>&id_article=<?php echo $article['id_article']; ?>');"><?php echo $article['title']; ?></a></div>
                      <div class="branch">status: 
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
                          restrict	  	  	  	  
                      <?php endif; ?>					
					</div>
                    <br>
                  </li>
                  <?php endforeach; ?>
                </ul>
              </td>
            </tr>
          </table>
          <!-- show pager links to other result pages -->
          <?php if(!empty($tpl['pager'])): ?>
          <hr>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="190" align="left" valign="top"> <?php echo $tpl['pager']; ?> </td>
            </tr>
          </table>
          <?php endif; ?>
          <?php else: ?>
      no article found
      <?php endif; ?>
        </td>
      </tr>
    </table>
</td>
    <td width="26%" align="left" valign="top" class="font10bold">
      <form accept-charset="<?php echo $tpl['charset']; ?>" name="searchform" method="post" action="<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=article&view=search">
	    <input name="search" type="text" id="search" size="25" maxlength="255" class="topselect">
	    <br>
		 <input name="searchbutton" type="submit" value="search" class="topselect">
	  </form>
      </td>
  </tr>
</table>