<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Smart Frame Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--
body,td,th {
    font-family: Verdana, Arial, Helvetica, sans-serif;
}
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
.title {
  color: #CCFF66;
  font-weight: bold;
  font-size: 14px;
}
.subtitle {
  font-size: 12px;
  color: #990000;
  font-weight: bold;
}
.normal {font-size: 12px}
.subtitle {
    font-size: 12px;
    color: #990000;
    font-weight: bold;
}
.style1 {color: #990000; font-size: 12px;}
.error {color: #990000; font-size: 10px; font-weight: bold;}
-->
</style>
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
</head>
<body>
<?php $show_button=TRUE; ?>
<?php if(count($tpl['folder_error'])>0): ?>
   <?php foreach($tpl['folder_error'] as $err): ?>
    <div class="error"><?php echo $err; ?></div>
   <?php endforeach; ?>
<?php $show_button=FALSE; endif; ?>
<?php if($show_button==FALSE): ?>
   <br><div class="error"><br>Please change the folder rights and reload this page!</div><br><br>
<?php endif; ?>
<?php if(count($tpl['error'])>0): ?>
    <?php foreach($tpl['error'] as $err): ?>
    <div class="error"><?php echo $err; ?><br><br></div>
  <?php endforeach; ?>
<?php endif; ?>
<form name="setup" id="setup" method="post" action="<?php echo JAPA_CONTROLLER; ?>">
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#3366CC"><span class="title">Open Publisher Project Setup</span></td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Set Charset of your site</span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Charset:</span></td>
        <td width="81%" align="left" valign="top"><select name="charset">
          <option value="iso-8859-1" selected="selected">Western (iso-8859-1)</option>
          <option value="iso-8859-2">Central European (iso-8859-2)</option>
          <option value="windows-1257">Baltic (windows-1257)</option>
          <option value="iso-8859-13">Baltic (iso-8859-13)</option>
          <option value="windows-1250">Central European (windows-1250)</option>
          <option value="windows-1251">Cyrillic (windows-1251)</option>
          <option value="windows-1256">Arabic (windows-1256)</option>
          <option value="iso-8859-7">Greek (iso-8859-7)</option>
          <option value="iso-8859-8">Hebrew (iso-8859-8)</option>
          <option value="iso-8859-9">Turkish (iso-8859-9)</option>
          <option value="GB2312">Chinese (GB2312)</option>
          <option value="Big5">Chinese (Big5)</option>
          <option value="EUC-KR">Korean (EUC-KR)</option>
          <option value="TIS-620">Thai (TIS-620)</option>
          <option value="EUC-JP">Japanese (EUC-JP)</option>
          <option value="KOI8-U">Ukrainian (KOI8-U)</option>
          <option value="KOI8-R">Relcom Russian (KOI8-R)</option>
          <option value="utf-8">Unicode (utf-8)</option>
        </select>
        </td>
      </tr>
    </table>   
    <table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><p><span class="subtitle">&nbsp;Database connect data</span></p>
        </td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top"><span class="normal">Host:</span></td>
        <td width="81%" align="left" valign="top">
          <input name="dbhost" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_dbhost'])) echo $tpl['form_dbhost']; ?>"/> 
          <span class="normal">Port:</span>
          <input name="dbport" type="text" size="6" maxlength="6" value="<?php if(isset($tpl['form_dbport'])) echo $tpl['form_dbport']; ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">User:</span></td>
        <td align="left" valign="top">
          <input name="dbuser" type="text" size="50" maxlength="255"  value="<?php if(isset($tpl['form_dbuser'])) echo $tpl['form_dbuser']; ?>"/>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="normal">Password:</td>
        <td align="left" valign="top"><input name="dbpasswd" type="password" id="login" size="50" maxlength="255"  value="<?php if(isset($tpl['form_dbpasswd'])) echo $tpl['form_dbpasswd'] ?>"/></td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">DB Name :</span></td>
        <td align="left" valign="top">
          <input name="dbname" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_dbname'])) echo $tpl['form_dbname'] ?>"/> 
          </td>      
      <tr>
        <td align="left" valign="top"><span class="normal">Tables Prefix :</span></td>
        <td align="left" valign="top">
          <input name="dbtablesprefix" type="text" size="50" maxlength="255" value="<?php if(empty($tpl['form_dbtableprefix'])) echo 'op_';else echo $tpl['form_dbtableprefix']; ?>"/>
        </td>
      </tr>
    </table>
    <table width="90%"  border="0" cellspacing="4" cellpadding="2">
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Create a superuser login password</span></td>
      </tr>
      <tr>
        <td width="19%" align="left" valign="top" class="normal">Login:</td>
        <td width="81%" align="left" valign="top"><input name="syslogin" type="text" id="login" size="50" maxlength="255" value="superuser" disabled/></td>
      </tr>
      <tr>
        <td align="left" valign="top"><span class="normal">Password:</span></td>
        <td align="left" valign="top">
          <input name="syspassword" type="text" size="50" maxlength="255" value="<?php if(isset($tpl['form_syspassword'])) echo $tpl['form_syspassword'] ?>"/>
        </td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td colspan="2" align="left" valign="top"><span class="subtitle">&nbsp;Insert sample content?</span></td>
        </tr>
      <tr>
        <td align="left" valign="top"><input type="checkbox" name="insert_sample_content" value="yes"> 
          <span class="normal">yes</span></td>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top"><p>&nbsp;
      </p>
      <p>
        <?php if($show_button==TRUE): ?><input type="submit" name="do_setup" value="Submit" /><?php endif; ?>
      </p></td>
  </tr>
</table>
</form>
</body>
</html>