<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<?php if($tpl['showHeaderFooter'] == TRUE): ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="38%" class="moduleheader">Navigation Nodes Management</td>
    <td width="20%" class="moduleheader">&nbsp;</td>
    <td width="30%" align="center" valign="middle" class="moduleheader"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=views" class="font10">register node related views</a></td>
    <td width="12%" align="center" class="moduleheader"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=options" class="font10">options</a></td>
  </tr>
  <tr>
    <td colspan="4">
<?php endif; ?>
<?php endif; ?>
  <?php $viewLoader->{$tpl['moduleChildView']}(); ?>
<?php if($tpl['showHeaderFooter'] == TRUE): ?>  
  <?php if($tpl['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>
<?php endif; ?>