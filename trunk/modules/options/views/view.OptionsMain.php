<form name="options" method="post" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/options">
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
        <td align="left" valign="top" class="font10bold"><input name="site_url" type="text" id="site_url" value="<?php echo $view['option']['site_url']; ?>" size="55" maxlength="255"></td>
      </tr>
      
      <tr>
        <td align="left" valign="top" class="font10bold">Public view folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <?php foreach($view['allPublicViewFolders'] as $_tpl): ?>
    <?php if(empty($_tpl)): ?>
       <input name="view_folder" type="radio" value=""<?php if($view['option']['views_folder']==$_tpl) echo " checked"; ?>> /<br /><br />    
    <?php else: ?>
       <input name="view_folder" type="radio" value="<?php echo $_tpl; ?>"<?php if($view['option']['views_folder']==$_tpl) echo " checked"; ?>> 
       <?php echo $_tpl; ?><br /><br />
    <?php endif; ?>
    <?php endforeach; ?>
    </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Public style folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <?php foreach($view['allPublicStyleFolders'] as $_tpl): ?>
    <?php if(empty($_tpl)): ?>
       <input name="style_folder" type="radio" value=""<?php if($view['option']['styles_folder']==$_tpl) echo " checked"; ?>> /<br /><br />    
    <?php else: ?>
       <input name="style_folder" type="radio" value="<?php echo $_tpl; ?>"<?php if($view['option']['styles_folder']==$_tpl) echo " checked"; ?>> 
       <?php echo $_tpl; ?><br /><br />
    <?php endif; ?>
    <?php endforeach; ?>
    </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Controller folders</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <?php foreach($view['allPublicControllerFolders'] as $_view): ?>
    <input name="controller_folder" type="radio" value="<?php echo $_view; ?>"<?php if($view['option']['controllers_folder']==$_view) echo " checked"; ?>> 
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
        <td align="left" valign="top" class="font10"><input type="checkbox" name="disable_cache" value="1"<?php if($view['option']['disable_cache']==1) echo " checked "; ?>> 
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Disallowed file uploads</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="rejected_files" cols="80" rows="3"><?php echo $view['option']['rejected_files']; ?></textarea></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Optimize Database Tables</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input type="submit" name="optimize" value="optimize"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Time in Seconds to empty the recycler</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="recycler_time" type="text" id="recycler_time" value="<?php echo $view['option']['recycler_time']; ?>" size="11" maxlength="11"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Session Life Time in Seconds</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><input name="session_maxlifetime" type="text" id="session_maxlifetime" value="<?php echo $view['option']['session_maxlifetime']; ?>" size="11" maxlength="11"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Max Lock Time in Seconds</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="max_lock_time" type="text" id="max_lock_time" value="<?php echo $view['option']['max_lock_time']; ?>" size="11" maxlength="11"></td>
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
        <td align="left" valign="top"><input name="textarea_rows" type="text" id="textarea_rows" value="<?php echo $view['option']['textarea_rows']; ?>" size="2" maxlength="3"></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Server time zone relative to Greenwich (auto GMT = <?php echo $view['serverTimezone']; ?>)</td>
      </tr>
      <tr>
        <td align="left" valign="top">
        <select name="server_gmt" size="1" id="server_gmt" class="treeselectbox">
        <?php for($gmt=12; $gmt>=-12; $gmt--): ?>
          <option value="<?php echo $gmt; ?>" <?php if($view['option']['server_gmt'] == $gmt) echo 'selected="selected"'; ?>><?php echo $gmt; ?></option>
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
          <option value="<?php echo $gmt; ?>" <?php if($view['option']['default_gmt'] == $gmt) echo 'selected="selected"'; ?>><?php echo $gmt; ?></option>
        <?php endfor; ?>
        </select>
        </td>
      </tr> 
    </table></td>
    <td width="26%" align="left" valign="top" class="font10bold"><input type="submit" name="updateOptions" value="update"></td>
  </tr>
</table>
</form>