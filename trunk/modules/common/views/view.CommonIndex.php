<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>" />
<link href="<?php echo $view['url_base']; ?>/modules/common/media/main.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {
    color: #CCCCFF;
    font-weight: bold;
}
.style7 {
  font-size: 12px;
  color: #FFCC00;
  font-weight: bold;
}
.style6 {
  font-size: 16px;
  color: #FF6633;
  font-weight: bold;
}
.style8 {color: #0033CC}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
function go(x){
    if(x != ""){
    window.location.href = x;
    }
}
function subok(s){
    s.value = "... wait";
}
</script>
<title>Admin</title>
<style type="text/css">
<!--
.topselect {
  font-size: 12px;
  color: #660000;
  background-color: #FFFFFF;
}
-->
</style>
</head>

<body>
<?php if($view['showHeaderFooter'] == TRUE): ?>
<?php if($view['isUserLogged'] == TRUE): ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" bgcolor="#EBEBEB">      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="26%" align="left" valign="top"><table width="100%"  border="0" align="left" cellpadding="2" cellspacing="2">
            <tr>
              <td width="100%" height="50" align="left" valign="top" class="style6">Open Publisher  <span class="style7">Ver.: 1.1a</span></td>
              </tr>
          </table></td>
          <td width="24%" align="right" valign="top" class="font10"> <br />
      <?php if($view['disableMainMenu']!=TRUE): ?>
          <a href="<?php echo $view['url_base']; ?>/">Switch to the  public page</a>
      <?php endif; ?>
      </td>
          <td width="34%" align="right" valign="top" class="font10">
       <br />
            <?php if(!isset($view['notLogged'])): ?> 
            <form action="" method="post">
                GoTo &gt;
                <select name="mod" class="topselect" onChange="go('<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/'+this.form.mod.options[this.form.mod.options.selectedIndex].value)"<?php if($view['disableMainMenu']==TRUE) echo ' disabled="disabled"'; ?>>
                 <option value=''></option>
                 <?php foreach($view['moduleList'] as $key => $val): ?>
                    <?php if(($val['visibility'] == TRUE)&&($view['userRole'] <= $val['perm'])): ?>
                    <option value='<?php echo $key; ?>'<?php if($view['requestedModule'] == $key) echo " selected='selected'"; ?>><?php echo $val['alias']; ?></option>
                    <?php endif; ?>
                 <?php endforeach; ?>
                </select>
            </form>
            <?php endif; ?>
          </td>
          <td width="16%" align="right" valign="top">
           <br />
              <a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/user/cntr/adminLogout" class="font12">Logout</a>
          </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="20%" align="left" valign="top">
  <?php endif; ?>
<?php endif; ?>
        <?php /* ### include the requested module controller view result ### */ ?>
        <?php echo $view['module_controller']; ?>
        
<?php if($view['showHeaderFooter'] == TRUE): ?>    
  <?php if($view['isUserLogged'] == TRUE): ?>  
    </td>
  </tr>
  <tr>
      <td align="left" valign="top" bgcolor="#EBEBEB"><table width="100%"  border="0" cellspacing="2" cellpadding="2">
          <tr>
              <td><span class="font9 style8">&copy; Armand Turpel <a href="mailto:cms@open-publisher.net">cms@open-publisher.net</a>. Project site -&gt; <a href="http://www.open-publisher.net" target="_blank">Open Publisher</a>. License: GNU Lesser General Public License (LGPL)</span></td>
          </tr>
    </table></td>
  </tr>
</table>
<?php endif; ?> 
<?php endif; ?>
</body>
</html>
