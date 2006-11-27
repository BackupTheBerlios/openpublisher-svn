<script language="JavaScript" type="text/JavaScript">
<!--



function deletekey(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delete_key.value="1";
        with(f){
        submit();
        }
        }
}
// unlock a node and forward to the node with id x. use this for links
function gotokey(f,x){
        f.gotokey.value=x;
        with(f){
        submit();
        }
}
function cancel_edit(f)
{
        f.canceledit.value="1";
        with(f){
        submit();
        }
}
//-->
</script>
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
<form accept-charset="<?php echo $tpl['charset']; ?>" action="<?php echo JAPA_CONTROLLER; ?>?mod=keyword&view=editKeyword" method="post" enctype="multipart/form-data" name="editkeyword" id="editkeyword">
<input name="id_key" type="hidden" value="<?php echo $tpl['key']['id_key']; ?>">
<input name="gotokey" type="hidden" value="">
<input name="modifykeyworddata" type="hidden" value="true">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="id_parent" type="hidden" value="<?php echo $tpl['key']['id_parent']; ?>">
<input name="old_status" type="hidden" value="<?php echo $tpl['key']['status']; ?>">
<input name="delete_key" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td class="moduleheader2">Edit Keyword ID: <?php echo $tpl['key']['id_key']; ?></td>
    </tr>
  <tr>
    <td width="80%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($tpl['error'])>0): ?>
      <tr>
        <td height="25" align="left" valign="top" class="itemerror">
    <?php foreach($tpl['error'] as $err): ?>
        <?php echo $err; ?><br />
      <?php endforeach; ?> 
    </td>
      </tr>
      <?php endif; ?>   
      <tr>
        <td align="left" valign="top" >
         <div class="font12 indent5">
            <a href="javascript:gotokey(document.forms['editkeyword'],0);">Top</a>
            <?php foreach($tpl['branch'] as $nkey): ?>
             / <a href="javascript:gotokey(document.forms['editkeyword'],<?php echo $nkey['id_key']; ?>);"><?php echo $nkey['title']; ?></a>
            <?php endforeach; ?></div>    
    </td>
        </tr>      
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="10%" align="left" valign="top" class="font10bold">Status </td>
              <td width="80%" align="left" valign="top" class="font10bold">Parent Node</td>
              <td width="10%" align="right" valign="top" class="font10bold"><input type="button" name="cancel" value="cancel" onclick="cancel_edit(this.form);"></td>
            </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                <option value="2" <?php if($tpl['key']['status'] == 2) echo 'selected="selected"'; ?>>active</option>
                <option value="1" <?php if($tpl['key']['status'] == 1) echo 'selected="selected"'; ?>>inactive</option>
              </select></td>
              <td align="left" valign="top"><select name="key_id_parent" size="1" id="key_id_parent" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_key']; ?>" <?php if($val['id_key'] == $tpl['key']['id_parent'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
              <td align="right" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
            </tr>
          </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $tpl['key']['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="description" cols="90" rows="5" id="description"><?php echo $tpl['key']['description']; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this keyword" onclick="deletekey(this.form, 'Delete this Keyword?');">
          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
      </tr>
    </table>
    </td>
    </tr>
</table>
</form>