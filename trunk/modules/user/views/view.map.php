<script language="JavaScript">
function goto_user(link){
parent.opener.location.href =link; }
</script>
<style type="text/css">
<!--
.sitemap {
        font-size: 12px;
  padding-top: 10px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 30px;
}
-->
</style>
<div class="sitemap">
    <?php foreach($tpl['users'] as $usr): ?>
    <table width="100%"  border="0" cellspacing="6" cellpadding="6">
      <tr>
          <td width="60%" align="left" valign="top" class="itemnormal">
            <a href="javascript:goto_user('<?php echo JAPA_CONTROLLER; ?>?mod=<?php echo $tpl['mod']; ?>&id_user=<?php echo $usr['id_user']; ?><?php echo $tpl['opener_url_vars']; ?>#user');"><?php echo $usr['login']; ?></a> <?php echo $usr['name']; ?> <?php echo $usr['lastname']; ?>
       </td>
       <td width="38%" align="left" valign="top" class="itemsmall">
              <?php echo $usr['role_t']; ?>
           </td>
      </tr>
    </table>
    <?php endforeach; ?>
</div> 
