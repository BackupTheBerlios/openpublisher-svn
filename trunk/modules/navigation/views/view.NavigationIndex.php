<?php if($tpl['showHeaderFooter'] == TRUE): ?>
<?php if($view['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="38%" class="moduleheader">Navigation Nodes Management</td>
    <td width="20%" class="moduleheader">&nbsp;</td>
    <td width="30%" align="center" valign="middle" class="moduleheader"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation/cntr/controllers" class="font10">register node related public controllers</a></td>
    <td width="12%" align="center" class="moduleheader"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation/cntr/options" class="font10">options</a></td>
  </tr>
  <tr>
    <td colspan="4">
<?php endif; ?>
<?php endif; ?>

  <?php echo $view['module_navigation_controller']; ?>
  
<?php if($view['showHeaderFooter'] == TRUE): ?>  
  <?php if($view['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>
<?php endif; ?>