<script language="JavaScript">
function goto_node(link){
parent.opener.location.href =link; }
</script>
<style type="text/css">
<!--
.sitemap {
        font-size: 12px;
  padding-top: 10px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 30px;
}
-->
</style>
<div class="sitemap">
<?php foreach($tpl['tree'] as $node):  ?>
<?php echo str_repeat('-&nbsp;',$node['level'] * 3); ?><a href="javascript:goto_node('<?php echo JAPA_CONTROLLER; ?>?mod=<?php echo $tpl['mod']; ?>&id_node=<?php echo $node['id_node']; ?>&<?php echo $tpl['url_pager_var']; ?>');"><?php echo $node['title']; ?></a><br />
<?php endforeach; ?>  
</div> 
