<?php if($view['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="38%" class="moduleheader">Global Options Management</td>
    <td width="20%" class="moduleheader">&nbsp;</td>
    <td width="30%" align="center" valign="middle" class="moduleheader">&nbsp;</td>
    <td width="12%" align="center" class="moduleheader">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">
  <?php endif; ?>
  
  <?php echo $view['module_option_controller']; ?>
  
  <?php if($view['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>