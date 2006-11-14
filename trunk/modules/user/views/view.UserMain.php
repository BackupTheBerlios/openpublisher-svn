<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="89%" align="left" valign="top">
  <?php if(isset($view['users']) && (count($view['users'])>0)): ?>
    <?php foreach($view['users'] as $usr): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
          <td width="60%" align="left" valign="top" class="itemnormal">
          <?php if($usr['lock']==FALSE): ?>
                <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/user/cntr/editUser/id_user/<?php echo $usr['id_user']; ?>"><?php echo $usr['login']; ?></a> (<?php echo $usr['name']; ?> <?php echo $usr['lastname']; ?>)
              <?php elseif($usr['lock']==TRUE): ?>
          <?php echo $usr['login'].' ('.$usr['name'].' '.$usr['lastname'].')'; ?> <strong>-lock-</strong>
        <?php endif; ?>
       </td>
       <td width="38%" align="left" valign="top" class="itemsmall">
              <?php echo $usr['role_t']; ?>
           </td>
      </tr>
    </table>
    <?php endforeach; ?>
  <?php endif; ?>
</td>
    <td width="11%" align="center" valign="top" class="itemnormal"><?php if($view['showAddUserLink']==TRUE): ?><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/user/cntr/addUser">add user </a><?php endif; ?></td>
  </tr>
</table>
