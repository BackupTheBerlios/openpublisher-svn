<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
.jj {
  font-family: "Courier New", Courier, mono;
  padding-top: 0px;
  padding-right: 0px;
  padding-bottom: 5px;
  padding-left: 0px;
  font-size: 100%;
}
-->
</style>
<form accept-charset="<?php echo $tpl['charset']; ?>" name="addnode" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=link&view=addLink&id_node=<?php echo $tpl['id_node']; ?>">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">Add Link</td>
    </tr>
  <tr>
    <td width="57%" align="left" valign="top"><table width="400" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($tpl['error'])>0): ?>
	  <?php foreach($tpl['error'] as $error): ?>
      <tr>
        <td width="312" align="left" valign="top" class="itemerror"><?php echo $error; ?></td>
      </tr>
	  <?php endforeach; ?>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Naviagtaion Node:</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">
		     <select name="link_id_node" size="1" id="link_id_node" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $tpl['id_node'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
      </tr>  	  
      <tr>
        <td align="left" valign="top" class="font10bold">Status:</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><select name="status">
          <option value="2"<?php if($tpl['status']==2) echo ' selected="selected"'; ?>>Active</option>
          <option value="1"<?php if($tpl['status']==1) echo ' selected="selected"'; ?>>Inactive</option>
        </select></td>
      </tr>  	  
      <tr>
        <td align="left" valign="top" class="font10bold">Url</td>
      </tr>
      <tr>
        <td height="29" align="left" valign="top"><input name="url" type="text" id="url" size="90" maxlength="1024" value="<?php echo $tpl['url']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td height="29" align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $tpl['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td height="29" align="left" valign="top"><textarea name="description" cols="80" rows="5" id="description"><?php echo $tpl['description']; ?></textarea></td>
      </tr>	  
      <tr>
        <td align="left" valign="top"><input name="addlink" type="submit" id="addlink" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="43%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=link&id_node=<?php echo $tpl['id_node']; ?>">back</a></td>
  </tr>
</table>
</form>