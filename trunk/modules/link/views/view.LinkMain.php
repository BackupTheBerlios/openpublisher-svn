<script language="JavaScript" type="text/JavaScript">
function nodemap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/nodecoration/1/mod/navigation/cntr/nodemap/openerModule/link','',mm); }
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <div class="font12 indent5">
  <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/link">Top</a>
  <?php foreach($view['branch'] as $node): ?>
   / <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/link/id_node/<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>
  <?php endforeach; ?>
  <?php if($view['id_node']!=0): ?>
     <span class="font12bold"> / <?php echo $view['node']['title']; ?></span>
  <?php endif; ?>
  </div>
  <?php if(isset($view['nodes']) && (count($view['nodes'])>0)): ?>
    <?php foreach($view['nodes'] as $node): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td width="1%" align="left" valign="top" class="font10">&nbsp;</td>
        <td width="1%" align="left" valign="top" class="font10">
           <?php if($node['status']==1): ?>
              <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/inactive.png" width="21" height="21">
           <?php elseif($node['status']==2): ?>
              <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/active.png" width="21" height="21">
           <?php elseif($node['status']==3): ?>
              <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/restricted.png" width="21" height="21">
           <?php endif; ?>    
    </td>
        <td width="98%" align="left" valign="top" class="font12">
          <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/link/id_node/<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
   <?php endif; ?>
  <?php if(isset($view['links']) && (count($view['links'])>0)): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCFF" class="font12bold">&nbsp;Links</td>
      </tr>  
    </table>
    <?php foreach($view['links'] as $link): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="1%" align="left" valign="top" class="itemnormal">
    <?php if($link['status']==1): ?>
    <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/inactive.png" width="21" height="21">
    <?php elseif($link['status']==2): ?>
    <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/active.png" width="21" height="21">
    <?php endif; ?>
    </td>
        <td width="1%" align="left" valign="top" class="font9">
    <?php if(($view['showLink']==TRUE)&&($link['lock']==FALSE)): ?><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/link/cntr/editLink/id_node/<?php echo $view['id_node']; ?>/id_link/<?php echo $link['id_link']; ?>/disableMainMenu/1">edit</a><?php else: ?>edit<?php endif; ?>&nbsp;</td>
          <td width="98%" align="left" valign="top" class="itemnormal">
                <?php echo '<a href="'.$link['url'].'" target="_blank">'.$link['title'].'</a>'; ?>
        <?php if(!empty($link['description'])): ?>
           <div class="font10"><?php echo $link['description']; ?></div>
        <?php endif; ?>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
  <?php elseif(($view['showLink']==TRUE)&&($view['showAddLink']==TRUE)): ?>
        <br><br><div class="font12bold">There is currently no link under this node. You may add some one here.</div><br><br>  
    <?php endif; ?>   
    </td>
    <td width="11%" align="center" valign="top" class="font12">
    <?php if(($view['showLink']==TRUE)&&($view['showAddLink']==TRUE)): ?>
       <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/link/cntr/addLink/id_node/<?php echo $view['id_node']; ?>">add link</a>
    <?php endif; ?>
    <p><a href="javascript:nodemap();">NodesMap</a></p></td>
  </tr>
</table>
