<p>Welcome to Open Publisher's admin management interface.</p>
  <p>Please use the links to add content or switch directly to a module on the top right page</p>
  <!-- nested includes of whatWouldYouDo views from other modules if provided -->
  <?php foreach($view['whatWouldYouDo'] as $item): ?>
      <?php echo $item; ?>
  <?php endforeach; ?>
  <p>&nbsp;</p>
    <p align="right"><a href="<?php echo $view['url_base']; ?>/<?php echo $view['adminWebController']; ?>/mod/default/cntr/systemInfo">Show System Info</a>&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
