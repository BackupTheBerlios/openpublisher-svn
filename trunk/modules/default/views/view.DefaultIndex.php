<p>Welcome to Open Publisher's admin management interface.</p>
<p>Please use the links to add content or switch directly to a module on the top right page</p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="moduleheader">Main Page</td>
  </tr>
  <tr>
    <td align="left" valign="top">
        <?php foreach($view['what_would_you_do'] as $modul_view): ?>
            <?php echo $modul_view; ?>
        <?php endforeach; ?>
    </td>
  </tr>
</table>
  <p>&nbsp;</p>
    <p align="right"><a href="<?php echo JAPA_CONTROLLER; ?>?mod=default&view=systemInfo">Show System Info</a>&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
