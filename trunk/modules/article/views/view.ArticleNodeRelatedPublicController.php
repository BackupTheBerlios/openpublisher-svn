<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" colspan="3" align="left" valign="top" class="font12bold">Article associated view for this node</td>
  </tr>
  <tr>
    <td width="10%" align="left" valign="top">
      <select name="article_id_view" size="1" id="id_view" class="treeselectbox">
        <option value="0">No View</option>
          <?php foreach($view['articlePublicControllers'] as $controller):  ?>
            <option value="<?php echo $controller['id_controller']; ?>" <?php if($controller['id_controller'] == $view['articleAssociatedPublicController']['id_controller'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo $controller['name']; ?></option>
          <?php endforeach; ?>
      </select>
    </td>
    <td width="30%" align="left" valign="top" class="font10bold"><input type="checkbox" name="articleviewssubnodes" value="1"> update view of subnodes</td>
    <td width="60%" align="left" valign="top" class="font10bold"><input type="submit" name="refresh" value="Update article view" class="button"></td>
  </tr>
</table>
