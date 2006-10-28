<!-- prevent direct call -->
<?php if (!defined('SMART_SECURE_INCLUDE')) exit; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl['charset']; ?>" />
<meta name="robots" content="noindex, nofollow" />

<style type="text/css">@import"<?php echo SMART_RELATIVE_PATH; ?><?php echo $tpl['adminCssFolder']; ?>base.css";</style>

<title>Open Publisher - Admin</title>

</head>

<body>

<div id="globalheader">
  <div id="title">Open Publisher</div>
  <div id="version"><?php echo $tpl['opVersion'] ?></div>
</div>

<?php /* ### include the module view (template) ### */ ?>
<?php $viewLoader->{$tpl['moduleRootView']}(); ?>

<div id="globalfooter">

</div>

</body>
</html>
