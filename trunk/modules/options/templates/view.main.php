<script language="JavaScript" type="text/JavaScript">
</script>
<form name="options" method="post" action="<?php echo JAPA_CONTROLLER; ?>?mod=options">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">global options</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      
     <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="left" valign="top" class="font10bold">Site Url</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><input name="site_url" type="text" id="site_url" value="<?php echo $tpl['siteUrl']; ?>" size="55" maxlength="255"></td>
      </tr>
      
      <tr>
        <td align="left" valign="top" class="font10bold">Public template folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <?php foreach($tpl['allPublicTplFolders'] as $_tpl): ?>
    <?php if(empty($_tpl)): ?>
       <input name="templates_folder" type="radio" value=""<?php if($tpl['publicTplFolder']==$_tpl) echo " checked"; ?>> /<br /><br />    
    <?php else: ?>
       <input name="templates_folder" type="radio" value="<?php echo $_tpl; ?>"<?php if($tpl['publicTplFolder']==$_tpl) echo " checked"; ?>> 
       <?php echo $_tpl; ?><br /><br />
    <?php endif; ?>
    <?php endforeach; ?>
    </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Public css folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <?php foreach($tpl['allPublicCssFolders'] as $_tpl): ?>
    <?php if(empty($_tpl)): ?>
       <input name="css_folder" type="radio" value=""<?php if($tpl['publicCssFolder']==$_tpl) echo " checked"; ?>> /<br /><br />    
    <?php else: ?>
       <input name="css_folder" type="radio" value="<?php echo $_tpl; ?>"<?php if($tpl['publicCssFolder']==$_tpl) echo " checked"; ?>> 
       <?php echo $_tpl; ?><br /><br />
    <?php endif; ?>
    <?php endforeach; ?>
    </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Public view folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <?php foreach($tpl['allPublicViewFolders'] as $_view): ?>
    <input name="views_folder" type="radio" value="<?php echo $_view; ?>"<?php if($tpl['publicViewFolder']==$_view) echo " checked"; ?>> 
    <?php echo $_view; ?><br /><br />
    <?php endforeach; ?>    
    </td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="font10bold">Delete whole public cache</td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="font10bold"><input type="submit" name="deletePublicCache" value="delete public cache"></td>
      </tr>
      <tr>
        <td align="left" valign="top"  class="font10bold">Disable public cache</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10"><input type="checkbox" name="disable_cache" value="1"<?php if($tpl['disableCache']==1) echo " checked "; ?>> 
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Disallowed file uploads</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="rejected_files" cols="80" rows="3"><?php echo $tpl['rejectedFiles']; ?></textarea></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Optimize Database Tables</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input type="submit" name="optimize" value="optimize"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Session Life Time in Seconds</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><input name="session_maxlifetime" type="text" id="session_maxlifetime" value="<?php echo $tpl['sessionMaxlifetime']; ?>" size="11" maxlength="11"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Max Lock Time in Seconds</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="max_lock_time" type="text" id="max_lock_time" value="<?php echo $tpl['maxLockTime']; ?>" size="11" maxlength="11"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Unlock all</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input type="submit" name="unlockall" value="unlock all"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Rows of Textareas</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="textarea_rows" type="text" id="textarea_rows" value="<?php echo $tpl['textareaRows']; ?>" size="2" maxlength="3"></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Server time zone relative to Greenwich (auto GMT = <?php echo $tpl['serverTimezone']; ?>)</td>
      </tr>
      <tr>
        <td align="left" valign="top">
        <select name="server_gmt" size="1" id="server_gmt" class="treeselectbox">
        <?php for($gmt=12; $gmt>=-12; $gmt--): ?>
          <option value="<?php echo $gmt; ?>" <?php if($tpl['serverGMT'] == $gmt) echo 'selected="selected"'; ?>><?php echo $gmt; ?></option>
        <?php endfor; ?>
        </select>
        </td>
      </tr> 
      <tr>
        <td align="left" valign="top" class="font10bold">Default time zone relative to Greenwich (GMT)</td>
      </tr>
      <tr>
        <td align="left" valign="top">
        <select name="default_gmt" size="1" id="server_gmt" class="treeselectbox">
        <?php for($gmt=12; $gmt>=-12; $gmt--): ?>
          <option value="<?php echo $gmt; ?>" <?php if($tpl['defaultGMT'] == $gmt) echo 'selected="selected"'; ?>><?php echo $gmt; ?></option>
        <?php endfor; ?>
        </select>
        </td>
      </tr> 
    </table></td>
    <td width="26%" align="left" valign="top" class="font10bold"><input type="submit" name="updateOptions" value="update"></td>
  </tr>
</table>
</form>