<?php	
	if(!defined('CONFIG')) exit(setup());
	$fansuberName	= (!empty($_GET['fansuber'])) ? mysql_real_escape_string($_GET['fansuber']) : '';

?><h1>Latest <?php echo $config['accro']; ?> Releases<br /><br />
	<?php if($fansuberName != '') echo ' from ' , htmlentities($fansuberName); ?>
</h1>
<?php
	echo '<p>Total releases :';

	$donnees = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS nb_entry FROM releases"));
	echo $donnees['nb_entry'];
	$totalCracks = $donnees['nb_entry'];

	if($fansuberName != '')
	{
		$r = mysql_query("SELECT COUNT(*) AS nb_entry FROM releases WHERE fansuber='" . $fansuberName . "'");
		$donnees = mysql_fetch_array($r);
		echo '<br />Total releases of <b>' , htmlentities($fansuberName) , '</b>: ' , $donnees['nb_entry'];
	}
	
	echo '</p>';
	
	$page = (isset($_GET['spg'])) ? (int)$_GET['spg'] : 1;
	$page = $page < 0 ? 0 : $page;
	//si on ne pr�cise pas la page on va � la premi�re page
	
	if($page != 0) $premierMessageAafficher = ($page - 1) * $config['cracksparpage'];
	
	$nombreDePages  = ceil($totalCracks / $config['cracksparpage']);
	
	echo '<span id="pagenums">Page : ';
	for ($i = 1 ; $i <= $nombreDePages ; $i++)
	{
		if($i != $page) echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?crk=releases&spg=' . $i . '">' . $i . '</a>';
		else echo $i . ' ';
	}
	echo '</span>';

?>
	<hr />
	<table border="0" cellpadding="3" cellspacing="0">
		<tr><td><center>Release name</center></td><td>Date (m/d/Y)</td><td>Fansuber</td></tr>
<?php
	if($fansuberName != '')
		$r = mysql_query("SELECT * FROM releases WHERE fansuber='" . $fansuberName . "' ORDER BY date DESC");
		
		
	elseif($page == 0)
		$r = mysql_query("SELECT * FROM releases");
	else
		$r = mysql_query("SELECT * FROM releases ORDER BY date DESC LIMIT $premierMessageAafficher, {$config['cracksparpage']}");

	while($donnees = mysql_fetch_array($r) )
	{
		echo '<tr>';
			echo '<td><a href="' . htmlentities($donnees['url']) , '">' , htmlentities($donnees['name']) , '</a></td>';
			echo '<td>' , date('m/d/Y', $donnees['date']) , '</td>';
			echo '<td><b><a href="index.php?crk=releases&fansuber=' , htmlentities($donnees['fansuber']) , '">' , htmlentities($donnees['fansuber']) , '</a></b></td>';
		echo '</tr>';
	}

?></table>

<hr />
<div id="footerlinks">
	<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?crk=releases&spg=0"><font face="fixedsys" size="1">[All releases]</font></a>
</div>
