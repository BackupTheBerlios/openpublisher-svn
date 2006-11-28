<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <?php if(isset($tpl['textes']) && (count($tpl['textes'])>0)): ?>
    <?php foreach($tpl['textes'] as $texte): ?>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="21" align="left" valign="top" class="itemnormal">
    <?php if($texte['status']==1): ?>
    <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/inactive.png" width="21" height="21">
    <?php elseif($texte['status']==2): ?>
    <img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/active.png" width="21" height="21">
    <?php endif; ?>
    </td>
        <td width="99%" align="left" valign="top" class="itemnormal">
          <?php if($texte['lock']==FALSE): ?>
                <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/misc/cntr/editText/id_text/<?php echo $texte['id_text']; ?>"><?php echo $texte['title']; ?></a>
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
  <?php if($tpl['showLink']==TRUE): ?><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/misc/cntr/addText">add text</a><?php else: ?>&nbsp;<?php endif; ?></td>
  </tr>
</table>
