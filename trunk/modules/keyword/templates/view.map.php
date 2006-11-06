<script language="JavaScript">
function goto_key(link){
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
<?php foreach($tpl['tree'] as $key):  ?>
<?php echo str_repeat('-&nbsp;',$key['level'] * 3); ?><a href="javascript:goto_key('<?php echo JAPA_CONTROLLER; ?>?mod=<?php echo $tpl['mod']; ?>&id_key=<?php echo $key['id_key']; ?><?php echo $tpl['opener_url_vars']; ?>#key');"><?php echo $key['title']; ?></a><br />
<?php endforeach; ?>  
</div> 
