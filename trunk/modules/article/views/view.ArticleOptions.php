<form name="options" method="post" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/article/cntr/options">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Article module options</td>
    </tr>
  <tr>
    <td width="74%" align="left" valign="top">      
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
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
    <!-- For a later release
      <tr>
        <td align="left" valign="top"  class="font10bold">Fromat of the node body textareas</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
          <input type="radio" name="force_format" value="2" <?php if($view['option']['force_format']==2) echo "checked"; ?>>
    Tiny Mice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="force_format" value="1" <?php if($view['option']['force_format']==1) echo "checked"; ?>>
    Text Wikki &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="force_format" value="0" <?php if($view['option']['force_format']==0) echo "checked"; ?>>
    Custom </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Default Fromat if custom</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="radio" name="default_format" value="2" <?php if($view['option']['default_format']==2) echo " checked"; ?>>
    Tiny Mice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="default_format" value="1"<?php if($view['option']['default_format']==1) echo " checked"; ?> >
    Text Wikki &nbsp;&nbsp;</td>
      </tr>
    -->
      <tr>
        <td align="left" valign="top" class="font10bold">Allow article related comments</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
       <input type="checkbox" name="use_comment" value="1"<?php if($view['option']['use_comment']==1) echo " checked "; ?>><br>
    </td>    
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Default article comments status</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
          inactive: <input name="default_comment_status" type="radio" value="1"<?php if($view['option']['default_comment_status']=='1') echo ' checked="checked"'; ?> class="topselect"> 
          active: <input name="default_comment_status" type="radio" value="2"<?php if($view['option']['default_comment_status']=='2') echo ' checked="checked"'; ?> class="topselect"> 
    </td>    
      </tr>
      <tr>      
        <td align="left" valign="top" class="font10bold">Use article related specific controller</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
       <input type="checkbox" name="use_article_controller" value="1"<?php if($view['option']['use_article_controller']==1) echo " checked "; ?>><br>
    </td>
      <tr>
        <td align="left" valign="top" class="font10bold">Use article related content</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
    <input type="checkbox" name="use_logo" value="1"<?php if($view['option']['use_logo']==1) echo " checked "; ?>> Logo<br>
    <input type="checkbox" name="use_images" value="1"<?php if($view['option']['use_images']==1) echo " checked "; ?>> Images<br>
    <input type="checkbox" name="use_files" value="1"<?php if($view['option']['use_files']==1) echo " checked "; ?>> Files<br>
    <input type="checkbox" name="use_overtitle" value="1"<?php if($view['option']['use_overtitle']==1) echo " checked "; ?>> Overtitle<br>
    <input type="checkbox" name="use_subtitle" value="1"<?php if($view['option']['use_subtitle']==1) echo " checked "; ?>> Subtitle<br>
    <input type="checkbox" name="use_description" value="1"<?php if($view['option']['use_description']==1) echo " checked "; ?>> Description<br>
    <input type="checkbox" name="use_header" value="1"<?php if($view['option']['use_header']==1) echo " checked "; ?>> Header<br>
    <input type="checkbox" name="use_ps" value="1"<?php if($view['option']['use_ps']==1) echo " checked "; ?>> PS<br>
    <input type="checkbox" name="use_changedate" value="1"<?php if($view['option']['use_changedate']==1) echo " checked "; ?>> Change Date<br>
    <input type="checkbox" name="use_articledate" value="1"<?php if($view['option']['use_articledate']==1) echo " checked "; ?>> Article Date<br>
    <input type="checkbox" name="use_keywords" value="1"<?php if($view['option']['use_keywords']==1) echo " checked "; ?>> Keywords<br>
    </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Default article order</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
            <select name="default_order" class="topselect">
            <option value="title"<?php if($view['option']['default_order']=='title') echo ' selected="selected"'; ?>>title</option>
            <option value="pubdate"<?php if($view['option']['default_order']=='pubdate') echo ' selected="selected"'; ?>>publish date</option>
            <option value="modifydate"<?php if($view['option']['default_order']=='modifydate') echo ' selected="selected"'; ?>>modify date</option>
      <option value="articledate"<?php if($view['option']['default_order']=='articledate') echo ' selected="selected"'; ?>>article date</option>
            <option value="rank"<?php if($view['option']['default_order']=='rank') echo ' selected="selected"'; ?>>rank</option>
            </select><br>
      asc: <input name="default_ordertype" type="radio" value="asc"<?php if($view['option']['default_ordertype']=='asc') echo ' checked="checked"'; ?> class="topselect"> 
      desc: <input name="default_ordertype" type="radio" value="desc"<?php if($view['option']['default_ordertype']=='desc') echo ' checked="checked"'; ?> class="topselect"> 
    </td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><input type="submit" name="updateOptions" value="update"></td>
      </tr>
    </table></td>
    <td width="26%" align="left" valign="top" class="font10bold"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/article">back to article module</a>
    <?php if(count($view['error'])>0):  ?><br>
    <br>
      <?php foreach($view['error'] as $error): ?>
       <?php echo $error; ?><br><br>
    <?php endforeach; ?>
    <?php endif; ?>
  </td>
  </tr>
</table>
</form>