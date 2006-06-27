<script language="JavaScript" type="text/JavaScript">
</script>
<form name="options" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=article&view=options">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Articles Summary</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">
  <?php if(isset($tpl['art_pubdate']) && (count($tpl['art_pubdate'])>0)): ?>
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td align="left" valign="middle" bgcolor="#CCCC99" class="font12bold">Last published Articles</td>
    </tr>
  </table>
    <?php foreach($tpl['art_pubdate'] as $art_pubdate): ?>  
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="9%" align="left" valign="top" class="font10">
         <?php if($art_pubdate['status']==0): ?>
           delete
         <?php elseif($art_pubdate['status']==1): ?>
           cancel
         <?php elseif($art_pubdate['status']==2): ?>
           propose
         <?php elseif($art_pubdate['status']==3): ?>
           edit
         <?php elseif($art_pubdate['status']==4): ?>
           publish
         <?php elseif($art_pubdate['status']==5): ?>
           protect               
         <?php endif; ?>    
    </td>
        <td width="91%" align="left" valign="top" class="itemnormal">
           <div class="font10">
      Publish Date: <?php echo $art_pubdate['pubdate']; ?><br>
      </div>
          <div class="font10">
            <?php  foreach($art_pubdate['nodeBranch'] as $bnode): ?>
                 <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
            <?php endforeach; ?>
      <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $art_pubdate['node']['id_node']; ?>"><?php echo $art_pubdate['node']['title']; ?></a></div>             
      <?php if(($art_pubdate['lock']==FALSE)&&($art_pubdate['hasAccess']==true)): ?>
        <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle&id_node=<?php echo $art_pubdate['id_node']; ?>&id_article=<?php echo $art_pubdate['id_article']; ?>"><?php echo $art_pubdate['title']; ?></a><?php else: ?><?php echo $art_pubdate['title']; ?>
      <?php endif; ?>   
    </td>
      </tr>
    </table>
  <?php endforeach; ?>
  <?php endif; ?>
  <?php if(isset($tpl['art_modifydate']) && (count($tpl['art_modifydate'])>0)): ?>
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td align="left" valign="middle" bgcolor="#CCCCCC" class="font12bold">Last modified Articles</td>
    </tr>
  </table>
    <?php foreach($tpl['art_modifydate'] as $art_modifydate): ?>  
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="9%" align="left" valign="top" class="font10">
         <?php if($art_modifydate['status']==0): ?>
           delete
         <?php elseif($art_modifydate['status']==1): ?>
           cancel
         <?php elseif($art_modifydate['status']==2): ?>
           propose
         <?php elseif($art_modifydate['status']==3): ?>
           edit
         <?php elseif($art_modifydate['status']==4): ?>
           publish
         <?php elseif($art_modifydate['status']==5): ?>
           protect               
         <?php endif; ?>    
    </td>
        <td width="91%" align="left" valign="top" class="itemnormal">
           <div class="font10">
      Last modified Date: <?php echo $art_modifydate['modifydate']; ?><br>
      </div>
          <div class="font10">
            <?php  foreach($art_modifydate['nodeBranch'] as $bnode): ?>
                 <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $bnode['id_node']; ?>"><?php echo $bnode['title']; ?></a> /
            <?php endforeach; ?>
      <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&id_node=<?php echo $art_modifydate['node']['id_node']; ?>"><?php echo $art_modifydate['node']['title']; ?></a> </div>    
      <?php if(($art_modifydate['lock']==FALSE)&&($art_modifydate['hasAccess']==true)): ?>
          <a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle&id_node=<?php echo $art_modifydate['id_node']; ?>&id_article=<?php echo $art_modifydate['id_article']; ?>"><?php echo $art_modifydate['title']; ?></a><?php else: ?><?php echo $art_modifydate['title']; ?>
      <?php endif; ?>    
    </td>
      </tr>
    </table>
  <?php endforeach; ?>
  <?php endif; ?>  
</td>
    <td width="26%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=article">back to article module</a>   </td>
  </tr>
</table>
</form>