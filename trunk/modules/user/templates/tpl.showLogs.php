
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
<dl>
  <?php foreach($tpl['logs'] as $log):  ?>
    <dt><?php echo $log['logdate']; ?></dt>
    <dd><a href="mailto:<?php echo $log['email']; ?>"><?php echo $log['name']; ?> <?php echo $log['lastname']; ?></a></dd>
  <?php endforeach; ?>  
</dl>
</div> 
