<script language="JavaScript" type="text/JavaScript">
</script>
<form name="addnode" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=views">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Register node related views</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td width="50%" align="left" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td width="393" align="left" valign="top" class="font10bold">All available views</td>
            </tr>
      <?php if(count($tpl['availableViews'])>0): ?>
            <tr>
              <td height="29" align="left" valign="top" class="font10bold">
                <?php foreach($tpl['availableViews'] as $view): ?>
                  <input type="checkbox" name="availableview[]" value="<?php echo $view['name'] ?>">
                  <?php echo $view['name'] ?><br>
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
              <td width="393" align="left" valign="top" class="font10bold">Registered views</td>
            </tr>
       <?php if(count($tpl['registeredViews'])>0): ?>
            <tr>
              <td height="29" align="left" valign="top" class="font10bold">
                <?php foreach($tpl['registeredViews'] as $view): ?>
                <input type="checkbox" name="registeredview[]" value="<?php echo $view['id_view'] ?>">
                <?php echo $view['name'] ?>
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
    <td width="26%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation">back to main navigation module</a></td>
  </tr>
</table>
</form>