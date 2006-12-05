<style type="text/css">@import"<?php echo $view['cssFolder']; ?>header.css";</style>

<div id="header">
<div class="pagetitle"><span class="smart3">Open Publisher</span> -&nbsp;PHP5 Content Management System</div>
<div class="pagecontact">
  <!-- show admin links if user is logged -->
  <?php if( isset($view['loggedUserRole']) ): ?>
    
    <!-- show link to the administration interface -->
    <?php if($view['loggedUserRole'] < 100): ?>
      <a href="<?php echo $view['urlBase']; ?>/<?php echo $view['adminWebController']; ?>">Admin</a> &nbsp;&nbsp;
    <?php endif; ?>  
  <?php endif; ?>
  
  <!-- show logout links if user is logged -->
  <?php if($view['isUserLogged'] == TRUE): ?>
    <a href="<?php echo $view['urlBase']; ?>/cntr/logout">Logout</a>
  <?php else: ?>
    <a href="<?php echo $view['urlBase']; ?>/cntr/login">Login</a>
  <?php endif; ?> &nbsp;&nbsp;   
  <a href="<?php echo $view['urlBase']; ?>/cntr/sitemap" class="topnavlink">Sitemap</a> &nbsp;&nbsp;
   <form accept-charset="<?php echo $view['charset']; ?>" name="form2" method="post" action="<?php echo $view['urlBase']; ?>/cntr/search" class="form">
    <input name="search" type="text" value="<?php if(isset($view['formsearch'])) echo $view['formsearch']; else echo "search"; ?>" size="30" maxlength="255" class="searchform"> &nbsp;
  </form>   
</div>
</div>

<div id="topmenu">
<ul id="toplinks">
  <!-- link to the entry page -->
  <li><a href="<?php echo $view['urlBase']; ?>">Home</a></li>
  <!-- output all root navigation nodes -->
  <?php foreach($view['rootNodes'] as $node): ?>    
  <li><a href="<?php echo $view['urlBase']; ?>/id_node/<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a></li>
  <?php endforeach; ?> 
</ul>
</div>