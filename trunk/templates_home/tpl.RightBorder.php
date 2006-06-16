<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>typography_right.css";</style>

<?php echo $tpl['borderText']['body']; ?>

<!-- --- show edit link if user is logged --- -->
<?php if(isset($tpl['showEditLink'])): ?>
    <div style="font-size: 1em;"><a href="admin.php?mod=misc&view=editText&id_text=2">edit right border text</a></div>
<?php endif; ?>  