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
    theme : "advanced",
    convert_fonts_to_spans : true,
    valid_elements : "*[*]",
    inline_styles : true,
    remove_linebreaks : false,
    theme_advanced_resizing : true,
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",   
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,styleselect,charmap,preview,fullscreen,separator,styleprops",   
    theme_advanced_buttons2 : "bullist, numlist,outdent,indent,separator,undo,redo,separator,insertdate,inserttime,link,unlink,anchor,cleanup,code,separator,table,hr,removeformat,sub,sup,search,replace,separator,pastetext,pasteword,selectall",  
    theme_advanced_buttons3 : "",   
    plugins : "style,fullscreen,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,searchreplace,paste" 
  });
 function insertFile(folder,title,file,id_file)
{
    tinyMCE.execCommand('mceInsertContent',0,'<a href="data/article/'+folder+'/'+file+'" title="'+title+'">'+title+'</a>');
}
function insertFileDesc(desc)
{
    tinyMCE.execCommand('mceInsertContent',0,desc);
}
function insertImage(controller,path,file,title,id_pic,id_article,width,height,jsc)
{
  if(jsc==1){
    tinyMCE.execCommand('mceInsertContent',0,'<a href="javascript:showimage(\'cntr/picture/id_article/'+id_article+'/id_pic/'+id_pic+'\','+width+','+height+');" title="'+title+'"><img src="'+path+file+'" title="'+title+'" border="0" class="smart3thumbimage" /></a>');
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

<script language="JavaScript" type="text/JavaScript">
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
<form accept-charset="<?php echo $tpl['charset']; ?>" action="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/article/cntr/modArticle/disableMainMenu/1" method="post" enctype="multipart/form-data" name="editarticle" id="editarticle">
<input name="id_article" type="hidden" value="<?php echo $view['id_article']; ?>">
<input name="id_node" type="hidden" value="<?php echo $view['id_node']; ?>">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="modifyarticledata" type="hidden" value="true">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" class="moduleheader2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="66%" align="left" valign="middle" class="moduleheader2">Modify Article Content </td>
          <td width="34%" align="right" valign="middle"><input name="back" type="submit" id="back" value="Back" class="button">
            <input name="refresh" type="submit" id="refresh" value="Refresh" class="button">
            <input name="finishupdate" type="submit" id="finishupdate" value="Submit" class="button"></td>
        </tr>
      </table></td>
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
    <?php if($view['use_overtitle']==1): ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Overtitle</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><input name="overtitle" type="text" id="overtitle" size="90" maxlength="1024" value="<?php echo $view['article']['overtitle']; ?>"></td>
      </tr>
    <?php endif; ?> 
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $view['article']['title']; ?>"></td>
      </tr>
    <?php if($view['use_subtitle']==1): ?>
      <tr>
        <td align="left" valign="top" class="font10bold">Subtitle</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><input name="subtitle" type="text" id="subtitle" size="90" maxlength="1024" value="<?php echo $view['article']['subtitle']; ?>"></td>
      </tr>
    <?php endif; ?>   
    <?php if($view['use_description']==1): ?>  
      <tr>
        <td align="left" valign="top" class="font10bold">Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="description" cols="90" rows="3" id="description"><?php echo $view['article']['description']; ?></textarea></td>
      </tr>
    <?php endif; ?> 
    <?php if($view['use_header']==1): ?>  
      <tr>
        <td align="left" valign="top" class="font10bold">Header</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="header" cols="90" rows="3" id="header"><?php echo $view['article']['header']; ?></textarea></td>
      </tr>
    <?php endif; ?>     
      <tr>
        <td align="left" valign="top" class="font10bold">Body</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="body" cols="90" rows="<?php echo $view['textarea_rows']; ?>" id="body"><?php echo $view['article']['body']; ?></textarea></td>
      </tr>
    <?php if($view['use_ps']==1): ?>  
      <tr>
        <td align="left" valign="top" class="font10bold">Post Script</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="ps" cols="90" rows="3" id="ps"><?php echo $view['article']['ps']; ?></textarea></td>
      </tr>
    <?php endif; ?>       
      <tr>
        <td align="left" valign="top"><input name="back" type="submit" id="back" value="Back" class="button">
          <input name="refresh" type="submit" id="refresh" value="Refresh" class="button">
          <input name="finishupdate" type="submit" id="finishupdate" value="Submit" class="button"></td>
      </tr> 
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
                          <td align="right" valign="top"><a href="javascript:insertFile('<?php echo $view['article']['media_folder']; ?>','<?php if(!empty($file['title'])) echo addslashes($file['title']); else echo $file['file']; ?>','<?php echo $file['file']; ?>','<?php echo $file['id_file']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertf<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindover.png',0)"><img name="Insertf<?php echo $file['id_file']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/rewind.png" title="Insert <?php echo $file['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletefile(document.forms['editarticle'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('delFile<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/deleteover.png',0)"> <img name="delFile<?php echo $file['id_file']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/delete.png" title="Delete <?php echo $file['file']; ?>" alt="Delete <?php echo $file['file']; ?>" width="30" height="29" border="0"></a> </td>
                          <td align="left" valign="top"> <a href="javascript:movefileup(document.forms['editarticle'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('fup<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/upover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/up.png" title="Move <?php echo $file['file']; ?> up" alt="Move <?php echo $file['file']; ?> up" name="fup<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a> <a href="javascript:movefiledown(document.forms['editarticle'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('fdown<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/downover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/down.png" title="Move <?php echo $file['file']; ?> down" alt="Move <?php echo $file['file']; ?> down" name="fdown<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a></td>
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
              Desc<br>              <a href="javascript:insertFileDesc('<?php echo $file['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insertfdesc<?php echo $file['id_file']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindsover.png',0)">
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
    </td>
    <td width="20%" align="left" valign="top" class="font10bold">
  <?php if($view['use_logo']==1): ?> <table width="200" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Logo Picture</td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?php if(empty($view['article']['logo'])): ?>
      <input name="uploadlogo" type="hidden" value="">
          <input type="file" name="logo" size="10">
          <input name="update" type="button" id="update" value="Submit" onclick="uploadlogofile(this.form);">
          <?php else: ?>
          <img name="articlelogo" src="<?php echo $view['url_base']; ?>'/data/article/'.$view['article']['media_folder'].'/'.$view['article']['logo']; ?>" alt="Article Logo" width="150"> <br>
          <input name="deletelogo" type="hidden" value="">
          <input type="button" name="eraselogo" value="delete" onclick="dellogo(this.form, 'Delete article logo Picture?');">
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
            <a href="javascript:insertImage('<?php echo $view['url_base']; ?>','data/article/<?php echo $view['article']['media_folder']; ?>/','<?php echo $thumb['file']; ?>','<?php echo $thumb['title']; ?>','<?php echo $thumb['id_pic']; ?>','','<?php echo $thumb['width']; ?>','<?php echo $thumb['height']; ?>', 0);"><img src="data/article/<?php echo $view['article']['media_folder']; ?>/thumb/<?php echo $thumb['file']; ?>" alt="<?php echo addslashes($thumb['description']); ?>" name="<?php echo $thumb['file']; ?>" width="120" border="0" title="Insert original image <?php echo $thumb['file']; ?> in text cursor position"></a></td>
                     
            <td align="left" valign="top"> <a href="javascript:moveup(document.forms['editarticle'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Up<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/upover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/up.png" title="Move <?php echo $thumb['file']; ?> up" alt="Move <?php echo $thumb['file']; ?> up" name="Up<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a><br/>
                          <br/>
                        <a href="javascript:movedown(document.forms['editarticle'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Down<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/downover.png',0)"><img src="<?php echo $view['url_base']; ?>/modules/common/media/pics/down.png" title="Move <?php echo $thumb['file']; ?> down" alt="Move <?php echo $thumb['file']; ?> down" name="Down<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a></td>
                    </tr>
                    <tr>
                      <td align="right" valign="top">
            <a href="javascript:insertImage('<?php echo $view['url_base']; ?>','data/article/<?php echo $view['article']['media_folder']; ?>/thumb/','<?php echo $thumb['file']; ?>','<?php echo addslashes($thumb['title']); ?>','<?php echo $thumb['id_pic']; ?>','<?php echo $view['article']['id_article']; ?>','<?php echo $thumb['width']; ?>','<?php echo $thumb['height']; ?>',1);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Insert<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/rewindover.png',0)">
            <img name="Insert<?php echo $thumb['id_pic']; ?>" src="<?php echo $view['url_base']; ?>/modules/common/media/pics/rewind.png" title="Insert thumbnail <?php echo $thumb['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletepic(document.forms['editarticle'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','<?php echo $view['url_base']; ?>/modules/common/media/pics/deleteover.png',0)"></a>
            <a href="javascript:deletepic(document.forms['editarticle'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Del<?php echo $thumb['id_pic']; ?>','','<?php echo $view['url_base']; ?>/modules/common/media/pics/deleteover.png',0)">
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