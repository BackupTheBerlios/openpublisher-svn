<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['cssFolder']; ?>header.css";</style>

<div id="header">
<div class="pagetitle"><span class="smart3">Open Publisher</span> -&nbsp;PHP5 Content Management System</div>
<div class="pagecontact">
  <!-- show admin links if user is logged -->
  <?php if( isset($tpl['loggedUserRole']) ): ?>
    
    <!-- show link to the administration interface -->
    <?php if($tpl['loggedUserRole'] < 100): ?>
      <a href="<?php echo $tpl['adminWebController']; ?>">Admin</a> &nbsp;&nbsp;
    <?php endif; ?>  
  <?php endif; ?>
  
  <!-- show logout links if user is logged -->
  <?php if($tpl['isUserLogged'] == TRUE): ?>
    <a href="<?php echo SMART_CONTROLLER; ?>?view=logout">Logout</a>
  <?php else: ?>
    <a href="<?php echo SMART_CONTROLLER; ?>?view=login">Login</a>
  <?php endif; ?> &nbsp;&nbsp;   
  <a href="index.php?view=sitemap" class="topnavlink">Sitemap</a> &nbsp;&nbsp;
   <form accept-charset="<?php echo $tpl['charset']; ?>" name="form2" method="post" action="<?php echo SMART_CONTROLLER; ?>?view=search" class="form">
    <input name="search" type="text" value="<?php if(isset($tpl['formsearch'])) echo $tpl['formsearch']; else echo "search"; ?>" size="30" maxlength="255" class="searchform"> &nbsp;
  </form>   
</div>
</div>

<div id="topmenu">
<ul id="toplinks">
  <!-- link to the entry page -->
  <li><a href="<?php echo SMART_CONTROLLER; ?>">Home</a></li>
  <!-- output all root navigation nodes -->
  <?php foreach($tpl['rootNodes'] as $node): ?>    
  <li><a href="<?php echo SMART_CONTROLLER; ?>?id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a></li>
  <?php endforeach; ?> 
</ul>
</div>