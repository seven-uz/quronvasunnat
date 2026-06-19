<?php

session_start();

include 'blocks/db.php';

if(isset($_REQUEST["term"]) and strlen($_REQUEST['term']) > 3){
	$word = $_REQUEST["term"];
	$like = '%'.$word.'%';
	$safeWord = htmlspecialchars($word, ENT_QUOTES, 'UTF-8');

	$sql = "SELECT *,'sura' as tablename FROM `suralar` WHERE (mano LIKE ? OR text LIKE ? OR textar LIKE ?)";

	if($stmt = mysqli_prepare($db, $sql)){
		mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);

		if(mysqli_stmt_execute($stmt)){
			$result = mysqli_stmt_get_result($stmt);
			echo '<div class="result_content">';
				if(mysqli_num_rows($result) > 0){
					echo '<span><strong>'.$safeWord.'</strong> sorovi boyicha saytda <strong>'.mysqli_num_rows($result).'</strong> ta natija topildi</span>';
					$pattern = "/\b([a-z]*".preg_quote($word, '/')."[a-z]*)\b/i";
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
						$url = 'quron?'.$row['tablename'].'='.rawurlencode($row['ns']).'#'.rawurlencode($row['no']);
						echo '<a href="'.$url.'">'.preg_replace($pattern, "<mark>$1</mark>", $row['mano']).'<br>
						[<i>'.$row['title'].' surasi '.$row['no'].'-oyat</i>]</a>';
					}
				}else{
					echo '<span><strong>'.$safeWord.'</strong> sorovi boyicha saytda hech qanday malumot topilmadi!</span>';
				}
			echo '</div>';
		} else{
			echo "Qidiruvda xatolik yuz berdi.";
		}
	}
}

mysqli_close($db);
