<?php
require_once('./includes/ChibiEngine.php');

if (!check_admin('website')) die;

$sql = new chibisql;
$smarty = new chibismarty;

$id = $_REQUEST['id'];
$mode = $_REQUEST['mode'];

if ($mode == 'submit')
{
	$stmt = $sql->prepare('UPDATE events SET contents=?,name=?,category=?,maxpart=?,fields=?,maxreserve=? WHERE id=?');
	$stmt->bind_param('sssisii',$_POST['contents'],$_POST['name'],$_POST['category'],$_POST['maxpart'],$_POST['fields'],$_POST['maxreserve'],$_POST['id']);
	$stmt->execute();
	$stmt->close();
	
	$mode='edit';
}
		
if ($mode == 'new')
{
	$stmt = $sql->prepare("INSERT INTO events VALUES('','Nieuwe pagina','Specificeer','Nieuwe Inhoud','Specificeer',0,0)");
	$stmt->execute();
	$stmt->close();
	
	$id=$sql->insert_id;
	$mode = 'edit';
}

if ($mode == 'delete')
{
	$stmt = $sql->prepare('DELETE FROM events WHERE id=?');
	$stmt->bind_param('i',$id);
	$stmt->execute();
	$stmt->close();
	
	$mode='';
}

if ($mode == 'edit')
{
	$stmt = $sql->prepare('SELECT name,contents,category,fields,maxpart,maxreserve FROM events WHERE id=?');
	$stmt->bind_param('i',$id);
	$stmt->execute();
	$stmt->bind_result($event['name'],$event['contents'],$event['category'],$event['fields'],$event['maxpart'],$event['maxreserve']);
	$stmt->fetch();
	$stmt->close();

	$event['id']=$id;
	$smarty->assign('event',$event);
	$smarty->assign('mode','edit');
}

else
{
	$stmt = $sql->prepare('SELECT id,name,category FROM events ORDER BY category,name');
	$stmt->execute();
	$stmt->bind_result($id,$name,$category);
	
	while ($stmt->fetch())
		$pages[] = array('id' => $id,
			'name' => $name,
			'category' => $category);
		
	$stmt->close();
	
	$smarty->assign('pages',$pages);
	$smarty->assign('mode','geen');
}


$sql->close();
$smarty->display('eventadmin.tpl');
?>