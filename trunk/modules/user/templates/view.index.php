<?php if (!defined('JAPA_SECURE_INCLUDE')) exit; ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="moduleheader">User Management</td>
  </tr>
  <?php if($tpl['show_options_link']==TRUE): ?>
  <tr>
    <td height="20" align="right" valign="top"><a href="<?php echo JAPA_CONTROLLER; ?>?mod=user&view=options"><font size="2">options</font></a> &nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td height="20" align="right" valign="top">&nbsp;</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>
	<?php endif; ?>
	<?php $viewLoader->{$tpl['moduleChildView']}(); ?>
<?php if($tpl['isUserLogged'] == TRUE): ?>	
	</td>
  </tr>
</table>
<?php endif; ?>