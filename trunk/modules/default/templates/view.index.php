<?php if (!defined('JAPA_SECURE_INCLUDE')) exit; ?>
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
