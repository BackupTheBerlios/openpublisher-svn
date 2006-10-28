<script language="JavaScript" type="text/JavaScript">
</script>
<form accept-charset="<?php echo $tpl['charset']; ?>" name="addnode" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=addNode&id_node=<?php echo $tpl['id_node']; ?>">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Add Navigation Node </td>
    </tr>
  <tr>
    <td width="57%" align="left" valign="top">    <table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if($tpl['error'] != FALSE): ?>
      <tr>
        <td width="312" align="left" valign="top" class="itemerror"><?php echo $tpl['error']; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td height="29" align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $tpl['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="addnode" type="submit" id="addnode" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&id_node=<?php echo $tpl['id_node']; ?>">back</a></td>
  </tr>
</table>
</form>