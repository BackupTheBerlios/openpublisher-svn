<script language="JavaScript" type="text/JavaScript">
function keymap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=keyword&view=map','',mm); }
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
  <a href="<?php echo SMART_CONTROLLER; ?>?mod=keyword">Top</a>
  <?php foreach($tpl['branch'] as $key): ?>
   / <a href="<?php echo SMART_CONTROLLER; ?>?mod=keyword&id_key=<?php echo $key['id_key']; ?>"><?php echo $key['title']; ?></a>
  <?php endforeach; ?>
  <?php if($tpl['id_key']!=0): ?>
     <span class="font12bold"> / <?php echo $tpl['key']['title']; ?></span>
  <?php endif; ?>
  </div>
  <?php if(isset($tpl['keys']) && (count($tpl['keys'])>0)): ?>
    <?php foreach($tpl['keys'] as $key): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="21" align="left" valign="top" class="itemnormal">
    <?php if($key['status']==1): ?>
    <img src="./modules/common/media/pics/inactive.png" width="21" height="21">
    <?php elseif($key['status']==2): ?>
    <img src="./modules/common/media/pics/active.png" width="21" height="21">	
    <?php endif; ?>
    </td>
        <td width="1%" align="left" valign="top" class="font9"><?php if($key['lock']==FALSE): ?><a href="<?php echo SMART_CONTROLLER; ?>?mod=keyword&view=editKeyword&id_key=<?php echo $key['id_key']; ?>">edit</a><?php endif; ?>&nbsp;</td>
          <td width="99%" align="left" valign="top" class="itemnormal">
          <?php if($key['lock']==FALSE): ?>
                <?php echo '<a href="'.SMART_CONTROLLER.'?mod=keyword&id_key='.$key['id_key'].'">'.$key['title'].'</a>'; ?>
              <?php elseif($key['lock']==TRUE): ?>
          <?php echo $key['title']; ?> <strong>-lock-</strong>
        <?php endif; ?>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
    <?php else: ?> 
  <br><br>
  <div class="font12bold">There is currently no keyword available here. You may add some one here.</div>
  <br><br>  
  <?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="font12">
       <a href="<?php echo SMART_CONTROLLER; ?>?mod=keyword&view=addKeyword&id_key=<?php echo $tpl['id_key']; ?>">add keyword</a>
    <p><a href="javascript:keymap();">KeyMap</a></p></td>
  </tr>
</table>
