<?php

session_start();

include 'blocks/db.php';

if(isset($_REQUEST["term"]) and strlen($_REQUEST['term']) > 3){
	$word = $_REQUEST["term"];
	$sql = "SELECT *,'sura' as tablename FROM `suralar` WHERE (mano LIKE '%$word%' or text like '%$word%' or textar like '%$word%')";

	if($stmt = mysqli_prepare($db, $sql)){
		if(mysqli_stmt_execute($stmt)){
			$result = mysqli_stmt_get_result($stmt);
			echo '<div class="result_content">';
				if(mysqli_num_rows($result) > 0){
					echo '<span><strong>'.$word.'</strong> sorovi boyicha saytda <strong>'.mysqli_num_rows($result).'</strong> ta natija topildi</span>';
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
						$url = 'quron?'.$row['tablename'].'='.$row['ns'].'#'.$row['no'];
						echo '<a href="'.$url.'">'.preg_replace("/\b([a-z]*${word}[a-z]*)\b/i","<mark>$1</mark>",$row['mano']).'<br>
						[<i>'.$row['title'].' surasi '.$row['no'].'-oyat</i>]</a>';
					}
				}else{
					echo '<span><strong>'.$word.'</strong> sorovi boyicha saytda hech qanday malumot topilmadi!</span>';
				}
			echo '</div>';
		} else{
			echo "ERROR: Kodda xatolik! $sql. " . mysqli_error($db);
		}
	}
}

mysqli_close($db);