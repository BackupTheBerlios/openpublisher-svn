<?php if($view['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="moduleheader">User Management</td>
  </tr>
  <?php if($view['show_options_link']==TRUE): ?>
  <tr>
    <td height="20" align="right" valign="top"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/user/cntr/options"><font size="2">options</font></a> &nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td height="20" align="right" valign="top">&nbsp;</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>
	<?php endif; ?>
	
	<?php echo $view['module_user_controller']; ?>
	
<?php if($view['isUserLogged'] == TRUE): ?>	
	</td>
  </tr>
</table>
<?php endif; ?>