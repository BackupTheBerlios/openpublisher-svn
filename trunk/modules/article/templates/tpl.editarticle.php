<script language="JavaScript" type="text/JavaScript">
<?php if($tpl['use_keywords']==1): ?>
  function keywordmap(){
    mm='scrollbars=1,toolbar=0,menubar=0,resizable=yes,width=500,height=450';
    newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=keyword&view=map&openerModule=article&opener_url_vars=<?php echo $tpl['opener_url_vars']; ?>','',mm); }
<?php endif; ?>

  function usermap(){
    mm='scrollbars=1,toolbar=0,menubar=0,resizable=yes,width=500,height=450';
    newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=user&view=map&openerModule=article&opener_url_vars=<?php echo $tpl['opener_url_vars']; ?>','',mm); }

<?php if(isset($tpl['showLogLink'])): ?>
  function showLogs(){
    mm='scrollbars=1,toolbar=0,menubar=0,resizable=yes,width=500,height=450';
    newwindow= window.open('<?php echo SMART_CONTROLLER; ?>?nodecoration=1&mod=user&view=showLogs&openerView=editArticle&openerModule=article&id_item=<?php echo $tpl['id_article']; ?>','',mm); }
<?php endif; ?>

// unlock a node and forward to the node with id x. use this for links
function gotonode(f,x){
        f.gotonode.value=x;
        with(f){
        submit();
        }
}
var flag = 0;
function enablechangedate(f)
{
  if(flag==0)
  {
    flag = 1;
    f.changedate_year.disabled="";
    f.changedate_month.disabled="";
    f.changedate_day.disabled="";
    f.changedate_hour.disabled="";
    f.changedate_minute.disabled="";
    f.changedatebutton.value="disable";
    f.ch1.disabled="";
    f.ch2.disabled="";
    f.ch3.disabled="";
  f.ch4.disabled="";
  f.ch5.disabled="";
  }
  else
  {
      flag = 0;
      f.changedate_year.disabled="disabled";
    f.changedate_month.disabled="disabled";
    f.changedate_day.disabled="disabled";
    f.changedate_hour.disabled="disabled";
    f.changedate_minute.disabled="disabled";
    f.ch1.disabled="disabled";
    f.ch2.disabled="disabled";
    f.ch3.disabled="disabled";
  f.ch4.disabled="disabled";
  f.ch5.disabled="disabled";
    f.changedatebutton.value="enable";  
  }
}
function cancel_edit(f)
{
        f.canceledit.value="1";
        with(f){
        submit();
        }
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
<form accept-charset="<?php echo $tpl['charset']; ?>" action="<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle&disableMainMenu=1" method="post" enctype="multipart/form-data" name="editarticle" id="editarticle">
<input name="id_article" type="hidden" value="<?php echo $tpl['id_article']; ?>">
<input name="id_node" type="hidden" value="<?php echo $tpl['id_node']; ?>">
<input name="gotonode" type="hidden" value="">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="modifyarticledata" type="hidden" value="true">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td class="moduleheader2">Edit Article ID: <?php echo $tpl['article']['id_article']; ?></td>
    </tr>
  <tr>
    <td class="font16bold">
         <div class="font12 indent5">
            <a href="javascript:gotonode(document.forms['editarticle'],0);">Top</a>
            <?php foreach($tpl['branch'] as $node): ?>
             / <a href="javascript:gotonode(document.forms['editarticle'],<?php echo $node['id_node']; ?>);"><?php echo $node['title']; ?></a>
            <?php endforeach; ?>
            <?php if($tpl['id_node']!=0): ?>
               / <a href="javascript:gotonode(document.forms['editarticle'],<?php echo $tpl['node']['id_node']; ?>);"><?php echo $tpl['node']['title']; ?></a>
            <?php endif; ?>     
     </div>   
  </td>
  </tr>
  <tr>
    <td class="font16bold"><?php echo $tpl['article']['title']; ?><hr></td>
  </tr>
  <tr>
    <td width="94%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
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
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="34%" align="left" valign="top" class="font12bold">Status </td>
              <td width="32%" rowspan="2" align="right" valign="top" class="font12bold">
                <ul>
                  <?php if(isset($tpl['showLogLink'])): ?>
                    <li><a href="javascript:showLogs();">Who modified this article?</a></li>
                  <?php endif; ?>
                  <li><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=modArticle&disableMainMenu=1&id_node=<?php echo $tpl['id_node']; ?>&id_article=<?php echo $tpl['id_article']; ?>">Modify article content</a></li>
                  <?php if($tpl['article']['status'] >= 4): ?>
                    <li><a href="<?php echo $tpl['publicWebController']; ?>?id_article=<?php echo $tpl['id_article']; ?>">View online</a></li>
                  <?php endif; ?>
                </ul>
              </td>
              <td width="34%" align="right" valign="top" class="font10bold"><input name="back" type="button" value="Back" onClick="cancel_edit(this.form);" class="button">
                <input name="refresh" type="submit" value="Refresh" class="button">
                <input name="finishupdate" type="submit" value="Submit" class="button"></td>
            </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                <option value="5" <?php if($tpl['article']['status'] == 5) echo 'selected="selected"'; ?>>protect</option>
                <option value="4" <?php if($tpl['article']['status'] == 4) echo 'selected="selected"'; ?>>publish</option>
                <option value="3" <?php if($tpl['article']['status'] == 3) echo 'selected="selected"'; ?>>edit</option>
                <option value="2" <?php if($tpl['article']['status'] == 2) echo 'selected="selected"'; ?>>propose</option>
                <option value="1" <?php if($tpl['article']['status'] == 1) echo 'selected="selected"'; ?>>cancel</option>
                <option value="0" <?php if($tpl['article']['status'] == 0) echo 'selected="selected"'; ?>>delete</option>
              </select></td>
              <td align="right" valign="top">&nbsp;</td>
            </tr>
          </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        Navigation Node of this Article</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">
        <select name="article_id_node" size="1" id="article_id_node" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $tpl['id_node'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
      </select></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        Publish Date</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10"><table width="300" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top" class="font10">Year</td>
            <td align="left" valign="top" class="font10">Month</td>
            <td align="left" valign="top" class="font10">Day</td>
            <td align="left" valign="top" class="font10">Hour</td>
            <td align="left" valign="top" class="font10">Minute</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="pubdate_year" type="text" size="5" maxlength="4" value="<?php echo $tpl['article']['pubdate']['year']; ?>"></td>
            <td align="left" valign="top"><select name="pubdate_month">
        <?php for($month=1;$month<13;$month++): ?>
              <option value="<?php echo $month; ?>" <?php if($tpl['article']['pubdate']['month'] == $month) echo 'selected="selected"'; ?>><?php echo $month; ?></option>
              <?php endfor; ?>
            </select>
              </td>
            <td align="left" valign="top"><select name="pubdate_day">
        <?php for($day=1;$day<32;$day++): ?>
              <option value="<?php echo $day; ?>" <?php if($tpl['article']['pubdate']['day'] == $day) echo 'selected="selected"'; ?>><?php echo $day; ?></option>
              <?php endfor; ?>                
            </select>
              </td>
            <td align="left" valign="top"><select name="pubdate_hour">
        <?php for($hour=0;$hour<24;$hour++): ?>
              <option value="<?php echo $hour; ?>" <?php if($tpl['article']['pubdate']['hour'] == $hour) echo 'selected="selected"'; ?>><?php echo $hour; ?></option>
              <?php endfor; ?>
            </select>
              </td>
            <td align="left" valign="top"><select name="pubdate_minute">
        <?php for($minute=0;$minute<60;$minute++): ?>
              <option value="<?php echo $minute; ?>" <?php if($tpl['article']['pubdate']['minute'] == $minute) echo 'selected="selected"'; ?>><?php echo $minute; ?></option>
              <?php endfor; ?>
            </select>
              </td>
          </tr>
        </table></td>
      </tr>
    <?php if($tpl['use_changedate']==1): ?>
      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        Change Status Date</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><table width="372" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="36" align="left" valign="top" class="font10">Year</td>
            <td width="53" align="left" valign="top" class="font10">Month</td>
            <td width="53" align="left" valign="top" class="font10">Day</td>
            <td width="53" align="left" valign="top" class="font10">Hour</td>
            <td width="53" align="left" valign="top" class="font10">Minute</td>
            <td width="29" align="left" valign="top" class="font10">&nbsp;</td>
            <td width="51" align="left" valign="top" class="font10">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="changedate_year" type="text" size="5" maxlength="4" value="<?php echo $tpl['article']['changedate']['year']; ?>"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
            </td>
            <td align="left" valign="top"><select name="changedate_month"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($month=1;$month<13;$month++): ?>
                <option value="<?php echo $month; ?>" <?php if($tpl['article']['changedate']['month'] == $month) echo 'selected="selected"'; ?>><?php echo $month; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="changedate_day"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($day=1;$day<32;$day++): ?>
                <option value="<?php echo $day; ?>" <?php if($tpl['article']['changedate']['day'] == $day) echo 'selected="selected"'; ?>><?php echo $day; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="changedate_hour"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($hour=0;$hour<24;$hour++): ?>
                <option value="<?php echo $hour; ?>" <?php if($tpl['article']['changedate']['hour'] == $hour) echo 'selected="selected"'; ?>><?php echo $hour; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="changedate_minute"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($minute=0;$minute<61;$minute++): ?>
                <option value="<?php echo $minute; ?>" <?php if($tpl['article']['changedate']['minute'] == $minute) echo 'selected="selected"'; ?>><?php echo $minute; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">              <input type="button" name="changedatebutton" value="enable" onclick="enablechangedate(this.form);"></td>
          </tr>
        </table>
          <table width="547" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="20" align="left" valign="top" class="font10bold">&nbsp;</td>
              <td width="60" align="left" valign="top" class="font10bold">To status:</td>
              <td width="76" align="left" valign="top" class="font10">delete
                  <input type="radio" name="changestatus" id="ch1" value="0"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==0) echo ' checked="checked"'; ?>>
              </td>
              <td width="82" align="left" valign="top" class="font10">cancel
                  <input type="radio" name="changestatus" id="ch2" value="1"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==1) echo ' checked="checked"'; ?>>
              </td>
              <td width="90" align="left" valign="top" class="font10">edit
                  <input type="radio" name="changestatus" id="ch3" value="3"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==3) echo ' checked="checked"'; ?>>
              </td>
              <td width="76" align="left" valign="top" class="font10">publish
                <input type="radio" name="changestatus" id="ch4" value="4"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==4) echo ' checked="checked"'; ?>></td>
              <td width="99" align="left" valign="top" class="font10">protect
                <input type="radio" name="changestatus" id="ch5" value="5"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==5) echo ' checked="checked"'; ?>></td>
            </tr>
          </table></td>
      </tr>
    <?php endif; ?>
    <?php if($tpl['use_articledate']==1): ?>
      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        Original Date</td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="300" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top" class="font10">Year</td>
            <td align="left" valign="top" class="font10">Month</td>
            <td align="left" valign="top" class="font10">Day</td>
            <td align="left" valign="top" class="font10">Hour</td>
            <td align="left" valign="top" class="font10">Minute</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="articledate_year" type="text" size="5" maxlength="4" value="<?php echo $tpl['article']['articledate']['year']; ?>">
            </td>
            <td align="left" valign="top"><select name="articledate_month">
                <?php for($month=1;$month<13;$month++): ?>
                <option value="<?php echo $month; ?>" <?php if($tpl['article']['articledate']['month'] == $month) echo 'selected="selected"'; ?>><?php echo $month; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="articledate_day">
                <?php for($day=1;$day<32;$day++): ?>
                <option value="<?php echo $day; ?>" <?php if($tpl['article']['pubdate']['day'] == $day) echo 'selected="selected"'; ?>><?php echo $day; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="articledate_hour">
                <?php for($hour=0;$hour<13;$hour++): ?>
                <option value="<?php echo $hour; ?>" <?php if($tpl['article']['pubdate']['hour'] == $hour) echo 'selected="selected"'; ?>><?php echo $hour; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="articledate_minute">
                <?php for($minute=0;$minute<61;$minute++): ?>
                <option value="<?php echo $minute; ?>" <?php if($tpl['article']['pubdate']['minute'] == $minute) echo 'selected="selected"'; ?>><?php echo $minute; ?></option>
                <?php endfor; ?>
              </select>
            </td>
          </tr>
        </table>
          </td>
      </tr>
    <?php endif; ?>

      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        <a name="key"></a>Article Users</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="font12bold"><a href="javascript:usermap();">open user map</a></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font12"> 
          <?php foreach($tpl['articleUsers'] as $user): ?>
            <input name="id_user[]" type="checkbox" value="<?php echo $user['id_user']; ?>"> <?php echo $user['lastname']; ?> <?php echo $user['name']; ?> (<a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['login']; ?></a>)<br />
      <?php endforeach; ?>
      <?php if(is_array($tpl['articleUsers']) && (count($tpl['articleUsers'])>0)): ?>
      <div><br />To remove users check the users and hit refresh or submit</div>
      <?php endif; ?>
      </td>
      </tr>

  <?php if($tpl['use_keywords']==1): ?>
      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        <a name="key"></a>Keywords</td>
      </tr>
      <tr>
        <td align="right" valign="top" class="font12bold"><a href="javascript:keywordmap();">open keyword map</a></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font12"> 
          <?php foreach($tpl['keys'] as $keybranch): ?>
      <input name="id_key[]" type="checkbox" value="<?php echo $keybranch['id_key']; ?>"> <?php echo $keybranch['branch']; ?><br />
      <?php endforeach; ?>
      <?php if(is_array($tpl['keys']) && (count($tpl['keys'])>0)): ?>
      <div><br />To remove keywords check the keywords and hit refresh or submit</div>
      <?php endif; ?>
      </td>
      </tr>
    <?php endif; ?>
    
    <?php if($tpl['use_article_view']==1): ?>
        <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        <a name="key"></a>Article related view (optionally)</td>
      </tr> 
      <tr>
        <td align="left" valign="top" class="font12"> 
          <select name="article_view"> 
          <option value="0">none</option>          
          <?php foreach($tpl['articlePublicViews'] as $view): ?>
              <option value="<?php echo $view['id_view']; ?>" <?php if($tpl['article']['id_view'] == $view['id_view']) echo 'selected="selected"'; ?>><?php echo $view['name']; ?></option>
          <?php endforeach; ?>
          </select>
      </td>
      </tr>
  <?php endif; ?> 
  
      <tr>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
    </table>
    </td>
    </tr>
</table>


<?php if($tpl['use_comment']==1): ?>

<style type="text/css">
<!--
.commentbody {
  font-size: 12px;
  padding-left: 20px;
}
.commenthead {
  font-size: 12px;
}
-->
</style>

<table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <td align="left" valign="top" class="font12bold">
        <hr>
        <a name="key"></a>Article comments</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10">
        <input type="checkbox" name="allow_comment" value="1"<?php if($tpl['article']['allow_comment']==1) echo " checked "; ?>> allow comments &nbsp;&nbsp;
        <input type="checkbox" name="close_comment" value="1"<?php if($tpl['article']['close_comment']==1) echo " checked "; ?>> close comments
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="refresh" type="submit" value="Refresh" class="button">           
        </td>
      </tr> 
      <tr>
        <td align="left" valign="top" class="font12bold"><hr /></td>
      </tr>      
      <tr>
        <td align="left" valign="top" class="font10">
            <?php foreach($tpl['articleComments'] as $comment): ?>
            <input name="id_comment[]" type="hidden" value="<?php echo $comment['id_comment']; ?>">
            <table>
              <tr>
                <td align="left" valign="top" class="commenthead">
                  
                Date: <?php echo $comment['pubdate']; ?> &nbsp;&nbsp;
                Author: 
                <?php if(!empty($comment['email'])): ?>
                    <a href="mailto:<?php echo $comment['email']; ?>"><?php echo $comment['author']; ?></a>
                <?php else: ?>
                    <?php echo $comment['author']; ?>
                <?php endif; ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if( $comment['status'] == 1 ): ?> 
                    <input type="checkbox" name="id_comment_val[]" value="<?php echo $comment['id_comment']; ?>"> <strong>validate</strong>
                  <?php endif; ?>
                     &nbsp;&nbsp;<input type="checkbox" name="id_comment_del[]" value="<?php echo $comment['id_comment']; ?>"> delete             
                </td>
              </tr>
              <tr>
                <td align="left" valign="top" class="commentbody">
                    <?php echo $comment['body']; ?>
                </td>
              </tr>  
              <tr>
                 <td align="left" valign="top"><hr /></td>
              </tr>               
            </table>
            <?php endforeach; ?>
        </td>
      </tr>       
</table>

<?php endif; ?>
</form>