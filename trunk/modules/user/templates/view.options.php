<style type="text/css">
<!--
.subview {
  font-size: 12px;
  font-weight: bold;
  color: #990000;
  background-color: #CCCCFF;
}
-->
</style>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td align="left" valign="middle" class="subview">&nbsp;Options</td>
  </tr>
  <tr>
    <td align="left" valign="top">
  <form name="format" method="post" action="<?php echo JAPA_CONTROLLER; ?>?mod=user&view=options">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <!-- Option to switch between tiny mice and text wiki. reserved for a future release
      <tr>
        <td width="46%" height="21" align="left" valign="top" class="font10bold">&nbsp;Fromat of  the users description textarea</td>
        <td width="54%" rowspan="5" align="left" valign="top" class="font10bold"><p><a href="<?php echo JAPA_CONTROLLER; ?>?mod=user">back</a></p>
          <p>
              <?php if($tpl['uptodate']==TRUE): ?>
                <font color="#FF0000">The user module options are now up to date!</font> </p>          <?php endif; ?></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
          <input type="radio" name="force_format" value="2" <?php if($tpl['option']['force_format']==2) echo "checked"; ?>>
Tiny Mice  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="force_format" value="1" <?php if($tpl['option']['force_format']==1) echo "checked"; ?>>  
Text Wikki &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="force_format" value="0" <?php if($tpl['option']['force_format']==0) echo "checked"; ?>> 
Custom
        </td>
        </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;</td>
        </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Fromat if custom</td>
        </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" name="default_format" value="2" <?php if($tpl['option']['default_format']==2) echo "checked"; ?>>
Tiny Mice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="default_format" value="1"<?php if($tpl['option']['default_format']==1) echo "checked"; ?> >
Text Wikki &nbsp;&nbsp;</td>
        </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">&nbsp;</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
    -->
      <tr>
        <td width="62%" align="left" valign="top" class="font10bold">&nbsp;Thumbnails width in pixels</td>
        <td width="38%" align="left" valign="top" class="font10"><a href="<?php echo JAPA_CONTROLLER; ?>?mod=user">back</a></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;<input name="thumb_width" type="text" value="<?php echo $tpl['option']['thumb_width']; ?>" size="4" maxlength="3"></td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">&nbsp;Max. file size in bytes</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;<input name="file_size_max" type="text" size="8" maxlength="8" value="<?php echo $tpl['option']['file_size_max']; ?>" ></td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">&nbsp;Max picture size in bytes</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;<input name="img_size_max" type="text" size="8" maxlength="8" value="<?php echo $tpl['option']['img_size_max']; ?>"></td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">&nbsp;Admin user logging</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;
            disabled: <input name="use_log" type="radio" value="0"<?php if($tpl['option']['use_log']=='0') echo ' checked="checked"'; ?> /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            enabled: <input name="use_log" type="radio" value="1"<?php if($tpl['option']['use_log']=='1') echo ' checked="checked"'; ?> /> 
        </td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" name="updateoptions" value="update"></td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
    </table>
  </form>
  </td>
  </tr>
</table>
