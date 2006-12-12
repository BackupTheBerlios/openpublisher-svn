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
  <form name="format" method="post" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/user/cntr/options">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="62%" align="left" valign="top" class="font10bold">&nbsp;Thumbnails width in pixels</td>
        <td width="38%" align="left" valign="top" class="font10"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/user">back</a></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;<input name="thumb_width" type="text" value="<?php echo $view['option']['thumb_width']; ?>" size="4" maxlength="3"></td>
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
        <td align="left" valign="top" class="font10">&nbsp;<input name="file_size_max" type="text" size="8" maxlength="8" value="<?php echo $view['option']['file_size_max']; ?>" ></td>
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
        <td align="left" valign="top" class="font10">&nbsp;<input name="img_size_max" type="text" size="8" maxlength="8" value="<?php echo $view['option']['img_size_max']; ?>"></td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">&nbsp;Admin user logging</td>
        <td align="left" valign="top" class="font10">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;
            disabled: <input name="use_log" type="radio" value="0"<?php if($view['option']['use_log']=='0') echo ' checked="checked"'; ?> /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            enabled: <input name="use_log" type="radio" value="1"<?php if($view['option']['use_log']=='1') echo ' checked="checked"'; ?> /> 
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
