<?php if (!defined('JAPA_SECURE_INCLUDE')) exit; ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <?php if(isset($tpl['textes']) && (count($tpl['textes'])>0)): ?>
    <?php foreach($tpl['textes'] as $texte): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="21" align="left" valign="top" class="itemnormal">
    <?php if($texte['status']==1): ?>
    <img src="./modules/common/media/pics/inactive.png" width="21" height="21">
    <?php elseif($texte['status']==2): ?>
    <img src="./modules/common/media/pics/active.png" width="21" height="21">
    <?php endif; ?>
    </td>
        <td width="99%" align="left" valign="top" class="itemnormal">
          <?php if($texte['lock']==FALSE): ?>
                <?php echo '<a href="'.JAPA_CONTROLLER.'?mod=misc&view=editText&id_text='.$texte['id_text'].'">'.$texte['title'].'</a>'; ?>
                 <?php if(!empty($texte['description'])): ?>
            <div class="font10"><?php echo $texte['description'] ?></div>
         <?php endif; ?>
        <?php elseif($texte['lock']==TRUE): ?>
          <?php echo $texte['title']; ?> <strong>-lock-</strong>
        <?php endif; ?>
        </td>
      </tr>
    </table>
    <?php endforeach; ?>
    <?php else: ?> 
  <br><br>
  <div class="font12bold">There is currently no text available here. You may add some one here.</div>
  <br><br>  
  <?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="font12">
  <?php if($tpl['showLink']==TRUE): ?><a href="<?php echo JAPA_CONTROLLER; ?>?mod=misc&view=addText">add text</a><?php else: ?>&nbsp;<?php endif; ?></td>
  </tr>
</table>
