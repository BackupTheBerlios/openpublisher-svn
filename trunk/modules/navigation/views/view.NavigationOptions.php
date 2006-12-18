<form name="options" method="post" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation/cntr/options">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Navigation module related options</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="top" class="font10bold">Thumbnail width in pixels</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="thumb_width" type="text" value="<?php echo $view['option']['thumb_width']; ?>" size="4" maxlength="4"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Max. image file size in bytes</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="img_size_max" type="text" size="8" maxlength="8" value="<?php echo $view['option']['img_size_max']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Max. attached file size in bytes</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="file_size_max" type="text" size="8" maxlength="8" value="<?php echo $view['option']['file_size_max']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Use url rewrites</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <input type="radio" name="use_url_rewrite" value="0"<?php if($view['option']['use_url_rewrite']==0) echo " checked "; ?>> No<br>
    <input type="radio" name="use_url_rewrite" value="1"<?php if($view['option']['use_url_rewrite']==1) echo " checked "; ?>> Yes
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Use node related content</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <input type="checkbox" name="use_logo" value="1"<?php if($view['option']['use_logo']==1) echo " checked "; ?>> Logo<br>
    <input type="checkbox" name="use_images" value="1"<?php if($view['option']['use_images']==1) echo " checked "; ?>> Images<br>
    <input type="checkbox" name="use_files" value="1"<?php if($view['option']['use_files']==1) echo " checked "; ?>> Files<br>
    <input type="checkbox" name="use_short_text" value="1"<?php if($view['option']['use_short_text']==1) echo " checked "; ?>> Short Text Field<br>
    <input type="checkbox" name="use_body" value="1"<?php if($view['option']['use_body']==1) echo " checked "; ?>> Body Text Field<br>
    <input type="checkbox" name="use_keywords" value="1"<?php if($view['option']['use_keywords']==1) echo " checked "; ?>> Keywords<br>
    </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" name="updateOptions" value="update"></td>
      </tr>
    </table></td>
    <td width="26%" align="left" valign="top" class="font10bold"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation">back to main navigation module</a>
    <?php if($view['error'] != FALSE):  ?><br><br>
      <?php foreach($view['error'] as $error): ?>
       <?php echo $error; ?><br><br>
    <?php endforeach; ?>
    <?php endif; ?>
  </td>
  </tr>
</table>
</form>