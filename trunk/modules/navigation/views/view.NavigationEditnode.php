<?php if($view['format']==2): ?>
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="<?php echo $view['url_base']; ?>/modules/common/media/tiny_mce/tiny_mce_gzip.php"></script>
<script language="javascript" type="text/javascript">
  // Notice: The simple theme does not use all options some of them are limited to the advanced theme
  tinyMCE.init({
    directionality : "ltr",
    remove_script_host : false,
    relative_urls : true,
    mode : "exact",
    content_css : "<?php echo $view['url_base']; ?>/modules/common/media/content.css",
    theme_advanced_containers_default_align : "left",
    theme_advanced_styles : "Font Size 8=f8;Font Size 10=f10;Font Size 12=f12;Font Size 14=f14;Font Size 16=f16;Font Size 18=f18;Font Size 20=f20;Forecolor=forecolor;Backcolor=backcolor;Quote=quote;",
    elements : "body",
    convert_fonts_to_spans : true,
    inline_styles : true,    
    valid_elements : "*[*]",
    theme : "advanced",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",   
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,styleselect,charmap,preview,fullscreen,separator,styleprops",   
    theme_advanced_buttons2 : "bullist, numlist,outdent,indent,separator,undo,redo,separator,insertdate,inserttime,link,unlink,anchor,cleanup,code,separator,table,hr,removeformat,sub,sup,search,replace,separator,pastetext,pasteword,selectall",  
    theme_advanced_buttons3 : "",   
    plugins : "style,fullscreen,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,searchreplace,paste" 
  });
 function insertFile(folder,title,file,id_file)
{
    tinyMCE.execCommand('mceInsertContent',0,'<a href="<?php echo $view['url_base']; ?>/data/navigation/'+folder+'/'+file+'" title="'+title+'">'+title+'</a>');
}
function insertFileDesc(desc)
{
    tinyMCE.execCommand('mceInsertContent',0,desc);
}
function insertImage(controller,path,file,title,id_pic,id_node,width,height,jsc)
{
  if(jsc==1){
    tinyMCE.execCommand('mceInsertContent',0,'<a href="javascript:showimage(\''+controller+'/cntr/picture/id_node/'+id_node+'/id_pic/'+id_pic+'\','+width+','+height+');" title="'+title+'"><img src="'+path+file+'" title="'+title+'" border="0" class="smart3thumbimage" /></a>');
    }
  else {
    tinyMCE.execCommand('mceInsertContent',0,'<img src="'+path+file+'" title="'+title+'" border="0" width="'+width+'" height="'+height+'" class="smart3image" />'); 
  }
}
function insertImgDesc(desc)
{
    tinyMCE.execCommand('mceInsertContent',0,desc);
} 
</script>
<!-- /tinyMCE -->
<?php elseif($view['format']==1): ?>
<!-- PEAR text_wikki -->
<script language="javascript" type="text/javascript" src="<?php echo $view['url_base']; ?>/modules/common/media/textarea.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $view['url_base']; ?>/modules/navigation/media/text_wikki_func.js"></script>
<!-- /PEAR text_wikki -->
<?php endif; ?>
<script language="JavaScript" type="text/JavaScript">
function keywordmap(){
mm='scrollbars=1,toolbar=0,menubar=0,resizable=no,width=400,height=450';
newwindow= window.open('<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/nodecoration/1/mod/keyword/cntr/map/openerModule/navigation/opener_url_vars/<?php echo $view['opener_url_vars']; ?>','',mm); }

function deletenode(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delete_node.value="1";
        with(f){
        submit();
        }
        }
}
function dellogo(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.deletelogo.value="1";
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
function uploadlogofile(f)
{
        f.uploadlogo.value="1";
        with(f){
        submit();
        }
}
function uploadpicfile(f)
{
        f.uploadpicture.value="true";
        with(f){
        submit();
        }
}
function deletepic(f, id_pic)
{
      check = confirm('Delete this picture');
        if(check == true)
        {
        f.imageID2del.value=id_pic;
        with(f){
        submit();
        }
    }
}
function moveup(f, id_pic)
{
        f.imageIDmoveUp.value=id_pic;
        with(f){
        submit();
        }
}
function movedown(f, id_pic)
{
        f.imageIDmoveDown.value=id_pic;
        with(f){
        submit();
        }
}
function movefileup(f, id_file)
{
        f.fileIDmoveUp.value=id_file;
        with(f){
        submit();
        }
}
function movefiledown(f, id_file)
{
        f.fileIDmoveDown.value=id_file;
        with(f){
        submit();
        }
}
function uploadufile(f)
{
        f.uploadfile.value="true";
        with(f){
        submit();
        }
}
function deletefile(f, id_file)
{
      check = confirm('Delete this file');
        if(check == true)
        {
        f.fileID2del.value=id_file;
        with(f){
        submit();
        }
    }
}
function switch_format(f)
{
  f.switchformat.value=1;
    with(f){
        submit();
    }
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
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
<form accept-charset="<?php echo $view['charset']; ?>" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/navigation/cntr/editNode" method="post" enctype="multipart/form-data" name="editnode" id="editnode">
<input name="id_node" type="hidden" value="<?php echo $view['node']['id_node']; ?>">
<input name="gotonode" type="hidden" value="">
<input name="modifynodedata" type="hidden" value="true">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="id_parent" type="hidden" value="<?php echo $view['node']['id_parent']; ?>">
<input name="old_status" type="hidden" value="<?php echo $view['node']['status']; ?>">
<input name="delete_node" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" class="moduleheader2">Edit Node ID: <?php echo $view['node']['id_node']; ?></td>
    </tr>
  <tr>
    <td width="80%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($view['error'])>0): ?>
      <tr>
        <td height="25" align="left" valign="top" class="itemerror">
    <?php foreach($view['error'] as $err): ?>
        <?php echo $err; ?><br />
      <?php endforeach; ?> 
    </td>
      </tr>
      <?php endif; ?>   
      <tr>
        <td align="left" valign="top" >
         <div class="font12 indent5">
            <a href="javascript:gotonode(document.forms['editnode'],0);">Top</a>
            <?php foreach($view['branch'] as $node): ?>
             / <a href="javascript:gotonode(document.forms['editnode'],<?php echo $node['id_node']; ?>);"><?php echo $node['title']; ?></a>
            <?php endforeach; ?></div>    
    </td>
        </tr>
      <tr>
        <td align="right" valign="top" ><table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="39%" align="left" valign="top"><table width="99%" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td width="10%" align="left" valign="top" class="font10bold">Status </td>
                </tr>
              <tr>
                <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                  <option value="3" <?php if($view['node']['status'] == 3) echo 'selected="selected"'; ?>>protect</option>
                  <option value="2" <?php if($view['node']['status'] == 2) echo 'selected="selected"'; ?>>active</option>
                  <option value="1" <?php if($view['node']['status'] == 1) echo 'selected="selected"'; ?>>inactive</option>
                </select>
                </td>
              </tr>
            </table></td>
            <td width="61%" height="28" align="right" valign="top">
              <input name="finishupdate" type="submit" value="Submit" class="button">
              <input type="submit" name="refresh" value="refresh" class="button">
              <input type="button" name="cancel" value="cancel" onClick="cancel_edit(this.form);" class="button">
            </td>
          </tr>
        </table></td>
      </tr>      
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="87%" align="left" valign="top" class="font10bold">Parent Node</td>
              </tr>
            <tr>
              <td align="left" valign="top"><select name="node_id_parent" size="1" id="node_id_parent" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($view['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $view['node']['id_parent'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
            </tr>
          </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $view['node']['title']; ?>"></td>
      </tr>
    <?php if($view['use_shorttext']==1): ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Short Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="short_text" cols="90" rows="3" id="short_text"><?php echo $view['node']['short_text']; ?></textarea></td>
      </tr>
    <?php endif; ?>
    <?php if($view['use_body']==1): ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Body</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="body" cols="90" rows="<?php echo $view['textarea_rows']; ?>" id="body"><?php echo $view['node']['body']; ?></textarea></td>
      </tr>
    <?php endif; ?>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top">
              <input name="finishupdate" type="submit" value="Submit" class="button">
        &nbsp;
      
        <input type="submit" name="refresh" value="refresh" class="button">
        &nbsp;
      
        <input type="button" name="cancel" value="cancel" onClick="cancel_edit(this.form);" class="button">
            </td>
          </tr>
        </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font9"><hr>
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this node" onclick="deletenode(this.form, 'Delete this node?');">
          </div>
          <hr></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font12bold"><a name="views"></a>Node associated view</td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="10%" align="left" valign="top"><select name="id_controller" size="1" id="id_controller" class="treeselectbox">
              <option value="0">No controller</option>
              <?php foreach($view['publicControllers'] as $controller):  ?>
                 <option value="<?php echo $controller['id_controller']; ?>" <?php if($controller['id_controller'] == $view['node']['id_controller'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo $controller['name']; ?></option>
              <?php endforeach; ?>
            </select></td>
            <td width="30%" align="left" valign="top" class="font10bold"><input type="checkbox" name="controllerssubnodes" value="1"> update controller of subnodes</td>
           <td width="60%" align="left" valign="top" class="font10bold"><input type="submit" name="refresh" value="Update node controller" class="button"></td>
          </tr>
        </table></td>
      </tr> 
      <tr>
        <td align="left" valign="top"> 
          <!-- nested includes of NodeRelatedPublicController from other modules if provided -->
          <?php foreach( $view['nodeRelatedPublicController'] as $publicController): ?>
              <?php echo $publicController; ?>
          <?php endforeach; ?>
        </td>
      </tr>
      <?php if($view['use_keywords']==1): ?>
        <tr>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
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
    <?php if($view['use_files']==1): ?>
      <tr>
        <td align="left" valign="top"><hr>          
          <table width="200" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Files</td>
            </tr>
            <tr>
              <td align="center" valign="top">
                <input type="file" name="ufile" id="ufile" size="10" class="fileform">
                <input name="uploadfile" type="hidden" value="">
                <input name="updatef" type="button" id="updatef" value="Submit" onclick="uploadufile(this.form);">
              </td>
            </tr>
            <tr>
              <td height="28" align="left" valign="top">
                <input name="fileID2del" type="hidden" value="">
                <input name="fileIDmoveUp" type="hidden" value="">
                <input name="fileIDmoveDown" type="hidden" value="">
                <?php foreach($view['file'] as $file): ?>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td colspan="2" class="font12bold"><?php echo $file['file']; ?></td>
                        </tr>
                        <tr>
                          <td align="right" valign="top"><a href="javascript:insertFile('<?php echo $view['node']['media_folder']; ?>','<?php if(!empty($file['title'])) echo $file['title']; else echo $file['file']; ?>','<?php echo $file['file']; ?>','<?php echo $file['id_file']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertf<?php echo $file['id_file']; ?>','','modules/common/media/pics/rewindover.png',0)"><img name="Insertf<?php echo $file['id_file']; ?>" src="modules/common/media/pics/rewind.png" title="Insert <?php echo $file['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletefile(document.forms['editnode'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('delFile<?php echo $file['id_file']; ?>','','modules/common/media/pics/deleteover.png',0)"> <img name="delFile<?php echo $file['id_file']; ?>" src="modules/common/media/pics/delete.png" title="Delete <?php echo $file['file']; ?>" alt="Delete <?php echo $file['file']; ?>" width="30" height="29" border="0"></a> </td>
                          <td align="left" valign="top"> <a href="javascript:movefileup(document.forms['editnode'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('fup<?php echo $file['id_file']; ?>','','modules/common/media/pics/upover.png',0)"><img src="./modules/common/media/pics/up.png" title="Move <?php echo $file['file']; ?> up" alt="Move <?php echo $file['file']; ?> up" name="fup<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a> <a href="javascript:movefiledown(document.forms['editnode'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('fdown<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/downover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/down.png" title="Move <?php echo $file['file']; ?> down" alt="Move <?php echo $file['file']; ?> down" name="fdown<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <input name="fileid[]" type="hidden" value="<?php echo $file['id_file']; ?>">
                    <td align="center" valign="top"> <a href="javascript:insertFileDesc('<?php echo $file['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_file','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindsover.png',0)"> </a>
                      <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                          <td width="1%" align="left" valign="top" class="font10">Tit</td>
                          <td width="99%" align="left" valign="top"><input name="filetitle[]" type="text" class="font12" id="filetitle" value="<?php echo $file['title']; ?>" size="25" maxlength="255"></td>
                        </tr>
                        <tr>
                          <td align="left" valign="top" class="font10">
              Desc<br><a href="javascript:insertFileDesc('<?php echo $file['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertfdesc<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindsover.png',0)">
              <img name="Insertfdesc<?php echo $file['id_file']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/rewinds.png" title="Insert <?php echo $file['file']; ?> description in cursor text position" alt="Insert <?php echo $file['file']; ?> description in cursor text position" width="21" height="21" border="0">
              </a></td>
                          <td align="left" valign="top"><textarea name="filedesc[]" cols="20" rows="3" class="font12" title="Picture <?php echo $file['file']; ?> description"><?php echo stripslashes($file['description']); ?></textarea></td>
                        </tr>
                      </table>
                      </td>
                  </tr>
                </table>
                <hr>
                <?php endforeach; ?>
              </td>
            </tr>
          </table></td>
      </tr>
    <?php endif; ?>
    </table>
      <hr></td>
    <td width="20%" align="left" valign="top" class="font10bold">
  <?php if($view['use_logo']==1): ?> <table width="200" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Logo Picture</td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?php if(empty($view['node']['logo'])): ?>
          <input name="uploadlogo" type="hidden" value="">    
          <input type="file" name="logo" size="10">
          <input name="update" type="button" id="update" value="Submit" onclick="uploadlogofile(this.form);">
          <?php else: ?>
          <img name="nodelogo" src="data/navigation/<?php echo $view['node']['media_folder']; ?>/<?php echo $view['node']['logo']; ?>" alt="Node Logo" width="150" > <br>
          <input name="deletelogo" type="hidden" value="">
          <input type="button" name="eraselogo" value="delete" onclick="dellogo(this.form, 'Delete node logo Picture?');">
          <?php endif; ?>
        </td>
      </tr>
    </table>
      <?php endif; ?>
    <?php if($view['use_images']==1): ?>
      <table width="200" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Pictures</td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <input name="uploadpicture" type="hidden" value="">
      <input type="file" name="picture" size="10">
            <input name="updatep" type="button" id="updatep" value="Submit" onclick="uploadpicfile(this.form);">
          </td>
        </tr>
        <tr>
          <td height="28" align="left" valign="top">
            <input name="imageID2del" type="hidden" value="">
            <input name="imageIDmoveUp" type="hidden" value="">
            <input name="imageIDmoveDown" type="hidden" value="">
            <?php foreach($view['thumb'] as $thumb): ?>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="right" valign="top">
                      <a href="javascript:insertImage('<?php echo $view['url_base']; ?>','data/navigation/<?php echo $view['node']['media_folder']; ?>/','<?php echo $thumb['file']; ?>','<?php echo $thumb['title']; ?>','<?php echo $thumb['id_pic']; ?>','','<?php echo $thumb['width']; ?>','<?php echo $thumb['height']; ?>', 0);"><img src="<?php echo $view['url_base']; ?>/data/navigation/<?php echo $view['node']['media_folder']; ?>/thumb/<?php echo $thumb['file']; ?>" alt="<?php echo $thumb['description']; ?>" name="<?php echo $thumb['file']; ?>" width="120" border="0" title="<?php echo $thumb['file']; ?>"></a></td>
                      <td align="left" valign="top"> <a href="javascript:moveup(document.forms['editnode'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Up<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/upover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/up.png" title="Move <?php echo $thumb['file']; ?> up" alt="Move <?php echo $thumb['file']; ?> up" name="Up<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a><br/>
                          <br/>
                        <a href="javascript:movedown(document.forms['editnode'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Down<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/downover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/down.png" title="Move <?php echo $thumb['file']; ?> down" alt="Move <?php echo $thumb['file']; ?> down" name="Down<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a></td>
                    </tr>
                    <tr>
                      <td align="right" valign="top">
            <a href="javascript:insertImage('<?php echo $view['url_base']; ?>','data/navigation/<?php echo $view['node']['media_folder']; ?>/thumb/','<?php echo $thumb['file']; ?>','<?php echo addslashes($thumb['title']); ?>','<?php echo $thumb['id_pic']; ?>','<?php echo $view['node']['id_node']; ?>','<?php echo $thumb['width']; ?>','<?php echo $thumb['height']; ?>', 1);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insert<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindover.png',0)"><img name="Insert<?php echo $thumb['id_pic']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/rewind.png" title="Insert <?php echo $thumb['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletepic(document.forms['editnode'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','<?php echo $view['url_base']; ?>/modules/common/media/pics/deleteover.png',0)"></a>
            <a href="javascript:deletepic(document.forms['editnode'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Del<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/deleteover.png',0)">
              <img name="Del<?php echo $thumb['id_pic']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/delete.png" title="Delete <?php echo $thumb['file']; ?>" alt="Delete <?php echo $thumb['file']; ?>" width="30" height="29" border="0">
            </a></td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <input name="picid[]" type="hidden" value="<?php echo $thumb['id_pic']; ?>">
                <td align="center" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td width="1%" align="left" valign="top" class="font10">Tit</td>
                    <td width="99%" align="left" valign="top"><input name="pictitle[]" type="text" class="font12" id="pictitle" value="<?php echo $thumb['title']; ?>" size="25" maxlength="255"></td>
                  </tr>
                  <tr>
                    <td align="left" valign="top" class="font10">
          desc<br><a href="javascript:insertImgDesc('<?php echo $thumb['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertpdesc<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindsover.png',0)"><img name="Insertpdesc<?php echo $thumb['id_pic']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/rewinds.png" title="Insert <?php echo $thumb['file']; ?> description in cursor text position" alt="Insert <?php echo $thumb['file']; ?> description in cursor text position" width="21" height="21" border="0"></a></td>
                    <td align="left" valign="top"><textarea name="picdesc[]" cols="18" rows="3" class="font12" title="Picture <?php echo $thumb['file']; ?> description"><?php echo $thumb['description']; ?></textarea></td>
                  </tr>
                </table>                  
                </td>
              </tr>
            </table>
            <hr>
            <?php endforeach; ?>
          </td>
        </tr>
      </table>
    <?php endif; ?>
    </td>
  </tr>
</table>
</form>