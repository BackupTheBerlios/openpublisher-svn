<?php if($tpl['format']==2): ?>
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="<?php echo SMART_RELATIVE_PATH; ?>modules/common/media/tiny_mce/tiny_mce_gzip.php"></script>
<script language="javascript" type="text/javascript">
  // Notice: The simple theme does not use all options some of them are limited to the advanced theme
  tinyMCE.init({
    directionality : "ltr",
    remove_script_host : false,
    relative_urls : true,
    mode : "exact",
    content_css : "<?php echo SMART_RELATIVE_PATH; ?>modules/common/media/content.css",
    theme_advanced_containers_default_align : "left",
    theme_advanced_styles : "Font Size 8=f8;Font Size 10=f10;Font Size 12=f12;Font Size 14=f14;Font Size 16=f16;Font Size 18=f18;Font Size 20=f20;Forecolor=forecolor;Backcolor=backcolor;Quote=quote;",
    elements : "description",
    convert_fonts_to_spans : true,
    inline_styles : true,      
    theme : "advanced",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",   
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,styleselect,charmap,preview,fullscreen",   
    theme_advanced_buttons2 : "bullist, numlist,outdent,indent,separator,undo,redo,separator,insertdate,inserttime,link,unlink,cleanup,code,separator,table,hr,removeformat,sub,sup,search,replace,save,separator,pastetext,pasteword,selectall",  
    theme_advanced_buttons3 : "", 
    plugins : "fullscreen,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste"
    
  });
 function insertFile(folder,title,file,id_file)
{
    tinyMCE.execCommand('mceInsertContent',0,'<a href="data/user/'+folder+'/'+file+'" title="'+title+'">'+title+'</a>');
}
function insertFileDesc(desc)
{
    tinyMCE.execCommand('mceInsertContent',0,desc);
}
function insertImage(controller,path,file,title,id_pic,id_user,width,height,jsc)
{
  if(jsc==1){
    tinyMCE.execCommand('mceInsertContent',0,'<a href="javascript:showimage(\''+controller+'?view=picture&id_user='+id_user+'&id_pic='+id_pic+'\','+width+','+height+');" title="'+title+'"><img src="'+path+file+'" title="'+title+'" border="0" class="smart3thumbimage" /></a>');
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
<?php elseif($tpl['format']==1): ?>
<!-- PEAR text_wikki -->
<script language="javascript" type="text/javascript" src="./modules/common/media/textarea.js"></script>
<script language="javascript" type="text/javascript" src="./modules/navigation/media/text_wikki_func.js"></script>
<!-- /PEAR text_wikki -->
<?php endif; ?>
<script language="JavaScript" type="text/JavaScript">
function deluser(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.deleteuser.value="1";
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
function cancel_edit(f)
{
        f.canceledit.value="1";
        with(f){
        submit();
        }
}
function uploadlogofile(f)
{
        f.uploadlogo.value="true";
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



<form accept-charset="<?php echo $tpl['charset']; ?>" action="<?php echo SMART_CONTROLLER; ?>?mod=user&view=editUser" method="post" enctype="multipart/form-data" id="edituser" name="edituser">
<input name="id_user" type="hidden" id="id_user" value="<?php echo $tpl['id_user']; ?>">
<input name="deleteuser" type="hidden" id="deleteuser" value="">
<input name="canceledit" type="hidden" id="canceledit" value="">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold"> Edit User </td>
    </tr>
  <tr>
    <td width="79%" align="left" valign="top">    <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($tpl['error'])>0): ?>
      <tr>
        <td align="left" valign="top" class="itemerror" colspan="2">
    <?php foreach($tpl['error'] as $err): ?>
       <?php echo $err; ?><br />
    <?php endforeach; ?>
    </td>
      </tr>
      <?php endif; ?>
    <?php if($tpl['showButton']==TRUE): ?>
      <tr>
        <td width="45%" align="left" valign="top" class="font10bold">Status:</td>
        <td width="55%" align="left" valign="top" class="font10bold">Role</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><select name="status">
      <option value="2"<?php if($tpl['user']['status']==2) echo ' selected="selected"'; ?>>Active</option>
          <option value="1"<?php if($tpl['user']['status']==1) echo ' selected="selected"'; ?>>Inactive</option>
        </select></td>
        <td align="left" valign="top" class="font10bold"><select name="role">
          <?php foreach($tpl['form_roles'] as $key => $val): ?>
          <option value="<?php echo $key; ?>"<?php if($tpl['user']['role']==$key) echo ' selected="selected"'; ?>><?php echo $val; ?></option>
          <?php endforeach; ?>
        </select></td>
      </tr>
    <?php endif; ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Login</td>
        <td align="left" valign="top" class="font10bold">Password</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="login" type="text" id="login" size="20" maxlength="255" value="<?php echo $tpl['user']['login']; ?>"> 
        </td>
        <td align="left" valign="top"><input name="passwd" type="text" id="passwd" size="20" maxlength="255" value=""></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Name</td>
        <td align="left" valign="top" class="font10bold">Lastname</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="name" type="text" id="name" size="25" maxlength="255" value="<?php echo $tpl['user']['name']; ?>">
* </td>
        <td align="left" valign="top"><input name="lastname" type="text" id="lastname" size="25" maxlength="255" value="<?php echo $tpl['user']['lastname']; ?>">
*</td>
      </tr>
   
      <tr>
        <td height="28" align="left" valign="top" class="font10bold">Email</td>
        <td align="left" valign="top" class="font10bold">Default time zone relative to Greenwich (GMT)</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="email" type="text" id="passwd" size="30" maxlength="255" value="<?php echo $tpl['user']['email']; ?>">
* </td>
        <td align="left" valign="top">
        <select name="user_gmt" size="1" id="user_gmt" class="treeselectbox">
        <?php for($gmt=12; $gmt>=-12; $gmt--): ?>
          <option value="<?php echo $gmt; ?>" <?php if($tpl['user']['user_gmt'] == $gmt) echo 'selected="selected"'; ?>><?php echo $gmt; ?></option>
        <?php endfor; ?>
        </select>
		</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">&nbsp;</td>
        <td rowspan="2" align="right" valign="bottom"><input name="updatethisuser" type="hidden" value="1">
          <input name="updateuser" type="submit" value="Submit">
          <?php if($tpl['showButton']==TRUE): ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="Submit" value="delete" onClick="deluser(this.form, 'Delete this user?');">
<?php endif; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="Submit" value="cancel" onClick="cancel_edit(this.form);"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
        </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><textarea name="description" rows="15" cols="80" style="width: 100%" wrap="VIRTUAL" id="description"><?php echo $tpl['user']['description']; ?></textarea> 
        </td>
        </tr>  
    <!--  format feature for a next release (tiny mice or text_wiki)
    <?php if($tpl['show_format_switch']==TRUE):  ?>     
      <tr>
        <td colspan="2" align="left" valign="top" class="font10bold">Use text format: 
          <input type="hidden" name="switchformat" value="0">
      <input type="radio" name="format" value="2" <?php if($tpl['format']==2) echo "checked"; ?> onclick="switch_format(document.forms['edituser'])">
          Wysiwyg &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" name="format" value="1" <?php if($tpl['format']==1) echo "checked"; ?> onclick="switch_format(document.forms['edituser'])"> 
          Wikki
          </td>
      </tr>
    <?php else: ?>
        <input type="hidden" name="format" value="<?php echo $tpl['format']; ?>">
    <?php endif;  ?>
    -->
      <tr>
        <td colspan="2" align="left" valign="top"><hr>          <table width="200" border="0" cellspacing="0" cellpadding="4">
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
              <?php foreach($tpl['user']['file'] as $file): ?>
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="2" class="font12bold"><?php echo $file['file']; ?></td>
                        </tr>
                      <tr>
                        <td align="right" valign="top"><a href="javascript:insertFile('<?php echo $tpl['user']['media_folder']; ?>','<?php if(!empty($file['title'])) echo addslashes($file['title']); else echo $file['file']; ?>','<?php echo $file['file']; ?>','<?php echo $file['id_file']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insert<?php echo $file['id_file']; ?>','','modules/common/media/pics/rewindover.png',0)"><img name="Insert<?php echo $file['id_file']; ?>" src="modules/common/media/pics/rewind.png" title="Insert <?php echo $file['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <a href="javascript:deletefile(document.forms['edituser'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('File<?php echo $file['id_file']; ?>','','modules/common/media/pics/deleteover.png',0)">
            <img name="File<?php echo $file['id_file']; ?>" src="modules/common/media/pics/delete.png" title="Delete <?php echo $file['file']; ?>" alt="Delete <?php echo $file['file']; ?>" width="30" height="29" border="0"></a>
            </td>
                        <td align="left" valign="top"> <a href="javascript:movefileup(document.forms['edituser'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('up<?php echo $file['id_file']; ?>','','modules/common/media/pics/upover.png',0)"><img src="./modules/common/media/pics/up.png" title="Move <?php echo $file['file']; ?> up" alt="Move <?php echo $file['file']; ?> up" name="up<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a>
                          <a href="javascript:movefiledown(document.forms['edituser'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('down<?php echo $file['id_file']; ?>','','modules/common/media/pics/downover.png',0)"><img src="./modules/common/media/pics/down.png" title="Move <?php echo $file['file']; ?> down" alt="Move <?php echo $file['file']; ?> down" name="down<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <input name="fid[]" type="hidden" value="<?php echo $file['id_file']; ?>">
                  <td align="center" valign="top"> 
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td width="1%" align="left" valign="top" class="font10">Tit</td>
                        <td width="99%" align="left" valign="top"><input name="filetitle[]" type="text" class="font12" id="filetitle" value="<?php echo $file['title']; ?>" size="25" maxlength="255">
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="top" class="font10"> Desc<br>
                          <a href="javascript:insertFileDesc('<?php echo $file['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertfdesc<?php echo $file['id_file']; ?>','','modules/common/media/pics/rewindsover.png',0)"> <img name="Insertfdesc<?php echo $file['id_file']; ?>" src="modules/common/media/pics/rewinds.png" title="Insert <?php echo $file['file']; ?> description in cursor text position" alt="Insert <?php echo $file['file']; ?> description in cursor text position" width="21" height="21" border="0"> </a></td>
                        <td align="left" valign="top"><textarea name="filedesc[]" cols="20" rows="3" class="font12" title="Picture <?php echo $file['file']; ?> description"><?php echo stripslashes($file['description']); ?></textarea>
                        </td>
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
          </td>
        </tr>
    </table>
    </td>
    <td width="21%" align="center" valign="top" class="font10bold"><table width="200" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Logo Picture</td>
      </tr>
      <tr>
        <td align="center" valign="top">
    <?php if(empty($tpl['user']['logo'])): ?>
      <input type="file" name="logo" id="logo" size="10" class="fileform">
        <input name="uploadlogo" type="hidden" value="">
        <input name="update" type="button" id="update" value="Submit" onclick="uploadlogofile(this.form);">
        <?php else: ?>
      <img name="userlogo" src="<?php echo SMART_RELATIVE_PATH.'data/user/'.$tpl['user']['media_folder'].'/'.$tpl['user']['logo']; ?>" alt="User Logo" width="150">
        <br>
        <input name="deletelogo" type="hidden" value="">
        <input type="button" name="eraselogo" value="delete" onclick="dellogo(this.form, 'Delete user logo Picture?');">
    <?php endif; ?>
</td>
      </tr>
    </table>      
      <table width="200" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Pictures</td>
        </tr>
        <tr>
          <td align="center" valign="top">
         <input type="file" name="picture" id="picture" size="10" class="fileform">
       <input name="uploadpicture" type="hidden" value="">
         <input name="updatep" type="button" id="updatep" value="Submit" onclick="uploadpicfile(this.form);">
          </td>
        </tr>
        <tr>
          <td height="28" align="left" valign="top">
        <input name="imageID2del" type="hidden" value="">
      <input name="imageIDmoveUp" type="hidden" value="">
      <input name="imageIDmoveDown" type="hidden" value="">
      <?php foreach($tpl['user']['thumb'] as $thumb): ?>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="right" valign="top">
          <a href="javascript:insertImage('<?php echo $tpl['publicWebController']; ?>','data/user/<?php echo $tpl['user']['media_folder']; ?>/','<?php echo $thumb['file']; ?>','<?php echo $thumb['title']; ?>','<?php echo $thumb['id_pic']; ?>','<?php echo $tpl['id_user']; ?>','<?php echo $thumb['width']; ?>','<?php echo $thumb['height']; ?>', 0);">
          <img src="./data/user/<?php echo $tpl['user']['media_folder']; ?>/thumb/<?php echo $thumb['file']; ?>" alt="<?php echo $thumb['description']; ?>" name="<?php echo $thumb['file']; ?>" width="120" border="0" title="<?php echo $thumb['file']; ?>">
                    </a>
                </td>
                    <td align="left" valign="top">
             <a href="javascript:moveup(document.forms['edituser'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('up<?php echo $thumb['id_pic']; ?>','','modules/common/media/pics/upover.png',0)"><img src="./modules/common/media/pics/up.png" title="Move <?php echo $thumb['file']; ?> up" alt="Move <?php echo $thumb['file']; ?> up" name="up<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a><br/>
             <br/>
             <a href="javascript:movedown(document.forms['edituser'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('down<?php echo $thumb['id_pic']; ?>','','modules/common/media/pics/downover.png',0)"><img src="./modules/common/media/pics/down.png" title="Move <?php echo $thumb['file']; ?> down" alt="Move <?php echo $thumb['file']; ?> down" name="down<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a></td>
                  </tr>
                  <tr>
                    <td align="right" valign="top">
          <a href="javascript:insertImage('<?php echo $tpl['publicWebController']; ?>','data/user/<?php echo $tpl['user']['media_folder']; ?>/thumb/','<?php echo $thumb['file']; ?>','<?php echo addslashes($thumb['title']); ?>','<?php echo $thumb['id_pic']; ?>','<?php echo $tpl['id_user']; ?>','<?php echo $thumb['width']; ?>','<?php echo $thumb['height']; ?>', 1);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insert<?php echo $thumb['id_pic']; ?>','','modules/common/media/pics/rewindover.png',0)"><img name="Insert<?php echo $thumb['id_pic']; ?>" src="modules/common/media/pics/rewind.png" title="Insert <?php echo $thumb['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletepic(document.forms['edituser'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<?php echo $thumb['id_pic']; ?>','','modules/common/media/pics/deleteover.png',0)"><img id="Image6" name="Image<?php echo $thumb['id_pic']; ?>" src="modules/common/media/pics/delete.png" title="Delete <?php echo $thumb['file']; ?>" alt="Delete <?php echo $thumb['file']; ?>" width="30" height="29" border="0"></a></td>
                    <td align="left" valign="top">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
          <input name="pid[]" type="hidden" value="<?php echo $thumb['id_pic']; ?>">
                <td align="center" valign="top">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                  <tr>
                    <td width="1%" align="left" valign="top" class="font10">Tit</td>
                    <td width="99%" align="left" valign="top"><input name="pictitle[]" type="text" class="font12" id="pictitle" value="<?php echo $thumb['title']; ?>" size="25" maxlength="255">
                    </td>
                  </tr>
                  <tr>
                    <td align="left" valign="top" class="font10"> desc<br>
                      <a href="javascript:insertImgDesc('<?php echo $thumb['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertpdesc<?php echo $thumb['id_pic']; ?>','','modules/common/media/pics/rewindsover.png',0)"><img name="Insertpdesc<?php echo $thumb['id_pic']; ?>" src="modules/common/media/pics/rewinds.png" title="Insert <?php echo $thumb['file']; ?> description in cursor text position" alt="Insert <?php echo $thumb['file']; ?> description in cursor text position" width="21" height="21" border="0"></a>                      </td>
                    <td align="left" valign="top"><textarea name="picdesc[]" cols="18" rows="3" class="font12" title="Picture <?php echo $thumb['file']; ?> description"><?php echo $thumb['description']; ?></textarea>
                    </td>
                  </tr>
                </table>
             </td>
              </tr>
            </table>
      <hr>
      <?php endforeach; ?>
      </td>
        </tr>
      </table>      <p>&nbsp;</p>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>   </p></td>
  </tr>
</table>
</form>