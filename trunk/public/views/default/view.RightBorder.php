<style type="text/css">@import"<?php echo $view['cssFolder']; ?>typography_right.css";</style>

<?php echo $view['borderText']['body']; ?>

<!-- --- show edit link if user is logged --- -->
<?php if(isset($view['showEditLink'])): ?>
    <div style="font-size: 1em;"><a href="Module/mod/misc/cntr/editText/id_text/2">edit right border text</a></div>
<?php endif; ?>  