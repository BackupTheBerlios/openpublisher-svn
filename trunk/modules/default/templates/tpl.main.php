<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>
<p>Welcome to the Smart admin management interface.</p>
  <p>Please use the links to add content or switch directly to a module on the top right page</p>
  <!-- nested includes of whatWouldYouDo views from other modules if provided -->
  <?php $viewLoader->broadcast( 'whatWouldYouDo' ) ?>
  <p>&nbsp;</p>
    <p align="right"><a href="<?php echo SMART_CONTROLLER; ?>?mod=default&view=systemInfo">Show System Info</a>&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
