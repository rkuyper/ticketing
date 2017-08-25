<?php
require_once('./includes/ChibiEngine.php');

$pageid=$_GET['id'];

$sql = new chibisql;
$stmt=$sql->prepare("SELECT name,contents FROM static WHERE id=?");
$stmt->bind_param('i',$pageid);
$stmt->execute();
$stmt->bind_result($pagename,$pagecontents);
$stmt->fetch();
$stmt->close();
$sql->close();

$smarty = new chibismarty;
$smarty->assign('pagename',$pagename);
$smarty->assign('pagecontents',$pagecontents);
$smarty->assign('pageid',$pageid);
$smarty->display('info.tpl');
?>