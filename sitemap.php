<?php
$xmlmode = true;
require_once('./includes/ChibiEngine.php');
echo "<?xml version='1.0' encoding='UTF-8'?>";
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
   <loc>http://www.chibicon.nl/</loc>
   <priority>0.8</priority>
  </url>
  <url>
   <loc>http://www.chibicon.nl/registratie.php</loc>
  </url>
  <url>
   <loc>http://www.chibicon.nl/animereviews.php</loc>
  </url>
  <url>
   <loc>http://www.chibicon.nl/gamereviews.php</loc>
  </url>
  <url>
   <loc>http://www.chibicon.nl/japansecultuur.php</loc>
  </url>
  <url>
   <loc>http://www.chibicon.nl/contact.php</loc>
  </url>
  <url>
   <loc>http://www.chibicon.nl/english.php</loc>
   <priority>0.8</priority>
  </url>
<?php
require_once('./includes/ChibiEngine.php');
$sql = new chibisql;
	
$stmt = $sql->prepare("SELECT id,'s' FROM static WHERE NOT(category = 'Specificeer') UNION SELECT id,'e' FROM events WHERE NOT(category = 'Specificeer') ORDER BY id");
$stmt->execute();
$stmt->bind_result($id,$type);
$i=0;
while($stmt->fetch())
{
	if ($type == 's')
		echo "<url>\n<loc>http://www.chibicon.nl/info.php?id=$id</loc>\n</url>\n";
	else
		echo "<url>\n<loc>http://www.chibicon.nl/event.php?id=$id</loc>\n</url>\n";
}
$stmt->close();
$sql->close();
?>
</urlset>