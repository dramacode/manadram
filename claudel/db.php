<?php
		$pdo = new PDO('sqlite:../../corpus/claudel/claudel.sqlite');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$stmt = $pdo->prepare("SELECT id FROM play WHERE code = ?");
		$stmt->execute(array("claudel_annonce_1948"));
		$id = $stmt->fetchAll()[0]['id'];
		$stmt = $pdo->query("select id from configuration WHERE play = '".$id."'");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";print_r($result);
// if($result){echo"b";}
// $bab = "";
	?>