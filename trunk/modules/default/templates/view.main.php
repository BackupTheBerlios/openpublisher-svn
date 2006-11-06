<?php if (!defined('JAPA_SECURE_INCLUDE')) exit; ?>
<p>Welcome to Open Publisher's admin management interface.</p>
  <p>Please use the links to add content or switch directly to a module on the top right page</p>
  <!-- nested includes of whatWouldYouDo views from other modules if provided -->
  <?php $viewLoader->broadcast( 'whatWouldYouDo' ) ?>
  <p>&nbsp;</p>
    <p align="right"><a href="<?php echo JAPA_CONTROLLER; ?>?mod=default&view=systemInfo">Show System Info</a>&nbsp;&nbsp;&nbsp;</p>
    <p>&nbsp;</p>
