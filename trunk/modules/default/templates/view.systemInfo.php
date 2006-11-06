<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2" align="left" valign="top" class="moduleheader2">System Info</td>
    </tr>
  <tr>
    <td colspan="2" align="right" valign="top" class="font10"><a href="<?php echo JAPA_CONTROLLER; ?>?mod=default">back to main module</a></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="font12"><strong>PHP Version:</strong> <?php echo $tpl['phpVersion']; ?></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="font12"><strong>MySql Version</strong>: <?php echo $tpl['mysqlInfo']['version']; ?></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="font12"><strong>MySql Cache Status:</strong>	  <table width="623" border="0" cellspacing="2" cellpadding="2">
      	 <?php foreach($tpl['mysqlInfo']['status'] as $key => $val): ?>
	    <tr>
	      <td width="47" align="left" valign="top">&nbsp;</td>
	      <td width="179" align="left" valign="top" class="font12"><?php echo $key; ?></td>
	      <td width="377" align="left" valign="top" class="font12"><?php echo $val; ?></td>
        </tr>	
	   <?php endforeach; ?>
	</table>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php if (!defined('JAPA_SECURE_INCLUDE')) exit; ?>
