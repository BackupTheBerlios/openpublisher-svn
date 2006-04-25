<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" colspan="3" align="left" valign="top" class="font12bold">Article associated view for this node</td>
  </tr>
  <tr>
    <td width="10%" align="left" valign="top">
      <select name="article_id_view" size="1" id="id_view" class="treeselectbox">
        <option value="0">No View</option>
          <?php foreach($tpl['articlePublicViews'] as $view):  ?>
            <option value="<?php echo $view['id_view']; ?>" <?php if($view['id_view'] == $tpl['articleAssociatedPublicView']['id_view'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo $view['name']; ?></option>
          <?php endforeach; ?>
      </select>
    </td>
    <td width="30%" align="left" valign="top" class="font10bold"><input type="checkbox" name="articleviewssubnodes" value="1"> update view of subnodes</td>
    <td width="60%" align="left" valign="top" class="font10bold"><input type="submit" name="refresh" value="Update article view" class="button"></td>
  </tr>
</table>
