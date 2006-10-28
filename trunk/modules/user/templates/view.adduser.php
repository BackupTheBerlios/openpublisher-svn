<form accept-charset="<?php echo $tpl['charset']; ?>" name="adduser" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=user&view=addUser">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td width="99%" align="left" valign="top">    <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($tpl['error'])>0): ?>
      <tr>
        <td align="left" valign="top" class="itemerror">
    <?php foreach($tpl['error'] as $error): ?>
    <?php echo $error; ?><br />
    <?php endforeach; ?>
    </td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC" class="itemnormalbold">Add User </td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Status:</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><select name="status">
      <option value="2"<?php if($tpl['form_status']==2) echo ' selected="selected"'; ?>>Active</option>
          <option value="1"<?php if($tpl['form_status']==1) echo ' selected="selected"'; ?>>Inactive</option>
        </select></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Login</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="login" type="text" id="login" size="40" maxlength="255" value="<?php echo $tpl['form_login']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Password</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="passwd" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $tpl['form_passwd']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Role</td>
      </tr>
      <tr>
        <td align="left" valign="top"><select name="role">
      <?php foreach($tpl['form_roles'] as $key => $val): ?>
          <option value="<?php echo $key; ?>"<?php if($tpl['role']==$key) echo ' selected="selected"'; ?>><?php echo $val; ?></option>
      <?php endforeach; ?>
        </select> 
          *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Name</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="name" type="text" id="name" size="40" maxlength="255" value="<?php echo $tpl['form_name']; ?>"> 
        *</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Lastname</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="lastname" type="text" id="lastname" size="40" maxlength="255" value="<?php echo $tpl['form_lastname']; ?>"> 
        *</td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Email</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="email" type="text" id="passwd" size="40" maxlength="255" value="<?php echo $tpl['form_email']; ?>"> 
        *</td>
      </tr>       
      <tr>
        <td align="left" valign="top"><input name="addthisuser" type="submit" id="addthisuser" value="Submit"></td>
      </tr>
    </table>
    </td>
    <td width="1%" align="left" valign="top" class="font10bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=user">cancel</a></td>
  </tr>
</table>
</form>