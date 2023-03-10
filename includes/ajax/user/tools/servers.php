<?php

	if (!isset($_SERVER['HTTP_REFERER'])){
		die;
	}
	
	ob_start();
	require_once '../../../inc/config.php';
	require_once '../../../inc/init.php';

	if (!empty($maintaince)){
		die();
	}
	
	if (!($user -> LoggedIn()) || !($user -> notBanned($odb))){
		die();
	}

	if (!($user->hasMembership($odb)) && $testboots == 0) {
		die();
	}
	
?>
	
<table class="table table-striped">
	<thead>
        <tr>
			<th class="text-center" style="font-size: 12px;">Name</th>
			<th class="text-center" style="font-size: 12px;">Status</th>
            <th class="text-center" style="font-size: 12px;">Attacks</th>
            <th class="text-center" style="font-size: 12px;">Load</th>
        </tr>
	</thead>
    <tbody>
	
<?php

	if($system == 'api'){
		$SQLGetInfo = $odb->query("SELECT * FROM `api` ORDER BY `id` DESC");
	} else {
		$SQLGetInfo = $odb->query("SELECT * FROM `servers` ORDER BY `id` DESC");
	}
	
	while ($getInfo = $SQLGetInfo->fetch(PDO::FETCH_ASSOC)) {
		$name    = $getInfo['name'];
		$attacks = $odb->query("SELECT COUNT(*) FROM `logs` WHERE `handler` LIKE '%$name%' AND `time` + `date` > UNIX_TIMESTAMP() AND `stopped` = 0")->fetchColumn(0);
		$load    = round($attacks / $getInfo['slots'] * 100, 2);
		echo '<tr class="text-center" style="font-size: 12px;">
				<td>' . $name . '</td>
				<td><b><font color="green">Online</font></b></td>
				<td>' . $attacks . '</td>
				<td>' . $load . '%</td>
			  </tr>';
	}
	
?>

    </tbody>
 </table>	
	