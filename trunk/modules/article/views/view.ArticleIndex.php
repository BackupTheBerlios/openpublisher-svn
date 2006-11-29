<?php if($view['showHeaderFooter'] == TRUE): ?>
<?php if($view['isUserLogged'] == TRUE): ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="23%" class="moduleheader">Articles Management</td>
    <td width="21%" class="moduleheader">&nbsp;</td>
    <td width="19%" align="center" valign="middle" class="moduleheader">
  <?php if($view['disableMainMenu']!=TRUE): ?>
     <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/article/cntr/controllers" class="font10">article public controllers</a>
  <?php endif; ?> 
  </td>    
    <td width="17%" align="center" valign="middle" class="moduleheader">
  <?php if($view['disableMainMenu']!=TRUE): ?>
     <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/article/cntr/summary" class="font10">articles summary</a>
  <?php endif; ?> 
  </td>
    <td width="20%" align="center" class="moduleheader">
  <?php if(($view['disableMainMenu']!=TRUE)&&($view['show_admin_link']==TRUE)): ?>
     <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/article/cntr/options" class="font10">article module options</a>
  <?php endif; ?>
  </td>
  </tr>
  <tr>
    <td colspan="4">
<?php endif; ?>
<?php endif; ?>

  <?php echo $view['module_article_controller']; ?>
  
<?php if($view['showHeaderFooter'] == TRUE): ?>  
  <?php if($view['isUserLogged'] == TRUE): ?>  
  </td>
  </tr>
</table>
<?php endif; ?>
<?php endif; ?>