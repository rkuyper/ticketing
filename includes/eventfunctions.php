<?php
function load_fields($amfields)
{
	for($i=0;$i<$amfields;$i++)
		$value[$i] = $_POST["field$i"];
	$value = str_replace('|',']',$value);
	$values = implode('|',$value);
	unset($value);
	
	return $values;
}

function load_event($eventid,$sql)
{
	$stmt=$sql->prepare("SELECT name,contents,maxpart,fields,maxreserve FROM events WHERE id=? AND NOT(category = 'Specificeer')");
	$stmt->bind_param('i',$eventid);
	$stmt->execute();
	$stmt->bind_result($event['name'],$event['pagecontents'],$event['maxpart'],$fieldsdb,$event['maxreserve']);
	$stmt->fetch();
	$stmt->close();

	$fieldsdb=explode('|',$fieldsdb);
	foreach($fieldsdb as $key => $value)
	{
		if ($key % 2 == 0) $event['fields'][$key / 2]['name'] = $value;
		else $event['fields'][$key / 2]['type'] = $value;
	}
	unset($fieldsdb);
	$event['amfields'] = count($event['fields']);
	$event['id'] = $eventid;
	return $event;
}
?>