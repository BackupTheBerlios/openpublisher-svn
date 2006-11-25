<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<!-- --- show picture title --- -->
<title>Picture - <?php echo $view['pic']['title']; ?></title>

<!-- --- charset setting --- -->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $view['charset']; ?>">
<style type="text/css">
<!--
body {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 1.4em;
  color: #0000CC;
  background-color: #FFFFFF;
  margin: 0px;
  padding: 0px;
}
-->
</style>
</head>

<body>

<!-- --- show table with picture and description --- -->
<table width="<?php echo $view['pic']['width']; ?>" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td align="left" valign="top"><h3><?php echo $view['pic']['title']; ?></h3></td>
  </tr>
  <tr>
    <td align="left" valign="top"><img name="<?php echo $view['pic']['title']; ?>" src="data/<?php echo $view['module']; ?>/<?php echo $view['pic']['media_folder']; ?>/<?php echo $view['pic']['file']; ?>" alt="<?php echo $view['pic']['description'] ?>" width="<?php echo $view['pic']['width']; ?>" height="<?php echo $view['pic']['height']; ?>" title="<?php echo $view['pic']['title']; ?>"></td>
  </tr>
  <tr>
    <td align="left" valign="top"><p><?php echo $view['pic']['description']; ?></p></td>
  </tr>
</table>
</body>
</html>
