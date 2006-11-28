<script language="JavaScript" type="text/JavaScript">
<!--
function keywordmap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/nodecoration/1/mod/keyword/cntr/map/openerModule/link/opener_url_vars/<?php echo $view['opener_url_vars']; ?>','',mm); }

function deletelink(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delete_link.value="1";
        with(f){
        submit();
        }
        }
}
// unlock a node and forward to the node with id x. use this for links
function gotonode(f,x){
        f.gotonode.value=x;
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
<form accept-charset="<?php echo $view['charset']; ?>" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/link/cntr/editLink" method="post" enctype="multipart/form-data" name="editlink" id="editlink">
<input name="gotonode" type="hidden" value="">
<input name="modifylinkdata" type="hidden" value="true">
<input name="disableMainMenu" type="hidden" value="<?php echo $view['disableMainMenu']; ?>">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="id_node" type="hidden" value="<?php echo $view['id_node']; ?>">
<input name="id_link" type="hidden" value="<?php echo $view['link']['id_link']; ?>">
<input name="delete_link" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td class="moduleheader2">Edit Link ID: <?php echo $view['link']['id_link']; ?></td>
    </tr>
  <tr>
    <td width="80%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($view['error'])>0): ?>
    <?php foreach($view['error'] as $error): ?>
      <tr>
        <td width="312" align="left" valign="top" class="itemerror"><?php echo $error; ?></td>
      </tr>
    <?php endforeach; ?>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" >
         <div class="font12 indent5">
            <a href="javascript:gotonode(document.forms['editlink'],0);">Top</a>
            <?php foreach($view['branch'] as $node): ?>
             / <a href="javascript:gotonode(document.forms['editlink'],<?php echo $node['id_node']; ?>);"><?php echo $node['title']; ?></a>            
             <?php endforeach; ?> 
             / <a href="javascript:gotonode(document.forms['editlink'],<?php echo $view['node']['id_node']; ?>);"><?php echo $view['node']['title']; ?></a></div>    
    </td>
        </tr>      
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="10%" align="left" valign="top" class="font10bold">Status </td>
              <td width="53%" align="left" valign="top" class="font10bold">Navigation Node</td>
              <td width="37%" align="right" valign="top" class="font10bold">
          <input name="finishupdate" type="submit" id="finishupdate" value="Submit" class="button">&nbsp;
          <input name="refresh" type="submit" id="refresh" value="Refresh" class="button">&nbsp;
        <input type="button" name="cancel" value="cancel" onclick="cancel_edit(this.form);" class="button"></td>
            </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                <option value="2" <?php if($view['link']['status'] == 2) echo 'selected="selected"'; ?>>active</option>
                <option value="1" <?php if($view['link']['status'] == 1) echo 'selected="selected"'; ?>>inactive</option>
              </select></td>
              <td align="left" valign="top"><select name="link_id_node" size="1" id="node_id_node" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($view['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $view['id_node'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
              <td align="right" valign="top">&nbsp;</td>
            </tr>
          </table></td>
      </tr>  
      <tr>
        <td align="left" valign="top" class="font10bold">Url</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="url" type="text" id="url" size="90" maxlength="1024" value="<?php echo $view['link']['url']; ?>"></td>
      </tr>    
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $view['link']['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="description" cols="90" rows="5" id="description"><?php echo $view['link']['description']; ?></textarea></td>
      </tr>
      <?php if($view['userRole']<60): ?>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this link" onclick="deletelink(this.form, 'Delete this link?');" class="button">
          </div></td>
      </tr>
      <?php endif; ?>
  <?php if($view['use_keywords']==1): ?>
        <tr>
        <td align="left" valign="top" class="font12bold"><a name="key"></a>Keywords</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="font12bold"><a href="javascript:keywordmap();">open keyword map</a></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font12"> 
          <?php foreach($view['keys'] as $keybranch): ?>
      <input name="id_key[]" type="checkbox" value="<?php echo $keybranch['id_key']; ?>"> <?php echo $keybranch['branch']; ?><br />
      <?php endforeach; ?>
      <?php if(is_array($view['keys']) && (count($view['keys'])>0)): ?>
      <div><br />To remove keywords check the keywords and hit refresh or submit</div>
      <?php endif; ?>
      </td>
      </tr>
    <?php endif; ?>   
      <tr>
        <td align="left" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit" class="button">
          &nbsp;

          <input name="refresh" type="submit" id="refresh" value="Refresh" class="button">
          &nbsp;

          <input type="button" name="cancel" value="cancel" onclick="cancel_edit(this.form);" class="button"></td>
      </tr>
    </table>
    </td>
    </tr>
</table>
</form>