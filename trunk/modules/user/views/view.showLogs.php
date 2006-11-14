
<style type="text/css">
<!--
dl {
  padding-top: 5px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}
dl .logdate {
  font-size: 1em;
}
dl .loguser {
  font-size: 1.2em;
  padding-bottom: 20px;
}
-->
</style>
<dl>
  <?php foreach($tpl['logs'] as $log):  ?>
    <dd class="logdate"><?php echo $log['logdate']; ?></dd> 
    <dd class="loguser"><a href="mailto:<?php echo $log['email']; ?>"><?php echo $log['name']; ?> <?php echo $log['lastname']; ?></a></dd>
  <?php endforeach; ?>  
</dl>
