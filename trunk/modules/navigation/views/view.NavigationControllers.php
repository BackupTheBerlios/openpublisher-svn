<script language="JavaScript" type="text/JavaScript">
</script>
<form name="addnode" method="post" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation/cntr/controllers">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Register node related controllers</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td width="393" align="left" valign="top" class="font10bold">All available public controllers</td>
            </tr>
      <?php if(count($tpl['availableControllers'])>0): ?>
            <tr>
              <td height="29" align="left" valign="top" class="font10bold">
                <?php foreach($tpl['availableControllers'] as $controller): ?>
                  <input type="checkbox" name="availablecontroller[]" value="<?php echo $controller['name'] ?>">
                  <?php echo $controller['name'] ?><br>
                <?php endforeach; ?>
              </td>
            </tr>
            <tr>
              <td align="left" valign="top"><input name="register" type="submit" id="register" value="register">
              </td>
            </tr>
      <?php endif; ?>
          </table></td>
          <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td width="393" align="left" valign="top" class="font10bold">Registered public controllers</td>
            </tr>
       <?php if(count($tpl['registeredControllers'])>0): ?>
            <tr>
              <td height="29" align="left" valign="top" class="font10bold">
                <?php foreach($tpl['registeredControllers'] as $controller): ?>
                <input type="checkbox" name="registeredcontroller[]" value="<?php echo $controller['id_view'] ?>">
                <?php echo $controller['name'] ?>
                <br>
                <?php endforeach; ?>
              </td>
            </tr>
            <tr>
              <td align="left" valign="top"><input name="unregister" type="submit" id="unregister" value="unregister">
              </td>
            </tr>
      <?php endif; ?>
          </table></td>
        </tr>
      </table></td>
    <td width="26%" align="left" valign="top" class="font10bold"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation">back to main navigation module</a></td>
  </tr>
</table>
</form>