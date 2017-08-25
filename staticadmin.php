<?php
require_once('./includes/ChibiEngine.php');

if (!check_admin('website')) die;

$sql = new chibisql;
$smarty = new chibismarty;

$id = $_REQUEST['id'];
$mode = $_REQUEST['mode'];

if ($mode == 'submit')
{
	$stmt = $sql->prepare('UPDATE static SET contents=?,name=?,category=? WHERE id=?');
	$stmt->bind_param('sssi',$_POST['contents'],$_POST['name'],$_POST['category'],$_POST['id']);
	$stmt->execute();
	$stmt->close();
	
	$mode='edit';
}
		
if ($mode == 'new')
{
	$stmt = $sql->prepare("INSERT INTO static VALUES('','Nieuwe pagina','Specificeer','Nieuwe Inhoud')");
	$stmt->execute();
	$stmt->close();
	
	$id=$sql->insert_id;
	$mode = 'edit';
}

if ($mode == 'delete')
{
	$stmt = $sql->prepare('DELETE FROM static WHERE id=?');
	$stmt->bind_param('i',$id);
	$stmt->execute();
	$stmt->close();
	
	$mode='';
}

if ($mode == 'edit')
{
	$stmt = $sql->prepare('SELECT name,contents,category FROM static WHERE id=?');
	$stmt->bind_param('i',$id);
	$stmt->execute();
	$stmt->bind_result($static['name'],$static['contents'],$static['category']);
	$stmt->fetch();
	$stmt->close();
	
	$static['id']=$id;
	
	$smarty->assign('static',$static);
	$smarty->assign('mode','edit');
}

else
{
	$stmt = $sql->prepare('SELECT id,name,category FROM static ORDER BY category,name');
	$stmt->execute();
	$stmt->bind_result($id,$name,$category);
	
	while ($stmt->fetch())
	{
		$pages[] = array('id' => $id,
			'name' => $name,
			'category' => $category);
	}
		
	$stmt->close();
	
	$smarty->assign('pages',$pages);
	$smarty->assign('mode','geen');
}


$sql->close();
$smarty->display('staticadmin.tpl');
?>