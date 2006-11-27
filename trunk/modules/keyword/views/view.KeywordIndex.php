<?php if($view['showHeaderFooter'] == TRUE): ?>
<?php if($view['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" class="moduleheader">Keyword Management</td>
  </tr>
  <tr>
    <td colspan="4">
<?php endif; ?>
<?php endif; ?>
  <?php echo $view['module_keyword_controller']; ?>
<?php if($view['showHeaderFooter'] == TRUE): ?>  
  <?php if($view['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>
<?php endif; ?>