<?
require 'blocks/brain.php';
include 'functions.php';

$page = "asmaulhusna";
$pageTitle = "Asma ul-Hunsa";
$headerImg = "asmaulhusna.webp";
$headerColor = "linear-gradient(74deg, rgba(24,24,24,1) 0%, rgba(121,121,121,1) 50%, rgba(27,27,27,1) 100%);";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

include 'blocks/head.php';
include 'blocks/nav.php';
include 'blocks/header.php';
?>
<main>
	<section>
		<div class="container">
			<h3 class="h2">
				<? echo word('Asma ul-Husna. Allohning 99 ta ismi va ta’rifi') ?>
			</h3>
			<div class="col-fluid">
				<?$asmaulhusna = mysqli_query($db, "SELECT * FROM asmaulhusna");
					$n = 1;
					$n2 = 1;
					while($row = $asmaulhusna->fetch_assoc()){
						echo '
						<div class="content-item">
							<div class="head-item d-flex justify-content-between"><h4>'.$n++.'. '.word($row['title']).'</h4><h4 class="arabicFont">'.arabicNumbers($n2++).'. '.$row['titlear'].'</h4></div>
							<div class="body-item">
							<p>'.word($row['text']).'</p>
							<p>'.word($row['title'].' ismi qur’ondagi');
							if($row['id'] == 1){
								echo '<a href="quron?sura=55"><strong> '.word('bir, butun sura "Ar-Rohman" nomi bilan nomlangan.').'</strong></a>';
							}elseif($row['id'] == 2){
								echo '<strong> '.word('Voqea surasidan boshqa barcha suralarning boshlanishida qo‘llaniladi.').'</strong>';
							}else{
								$manbaarray = explode(',', $row['manbainkoran']);
								$myresult = '';
								foreach ($manbaarray as $key => $value) {
									$thisval = strstr($value, ':', true);
									$thisvalafter = strstr($value, ':');
									$thisvalafter = substr($thisvalafter, 1);
									$thisquery = mysqli_query($db, "SELECT * FROM suranames WHERE id='$thisval'");
									$myrow = $thisquery->fetch_assoc();
									$myresult .= '<a href="quron?sura='.$myrow['id'].'#'.$thisvalafter.'">'.word($myrow['title'].'['.$thisvalafter.']').'</a>, ';
								}
								echo '<strong> ';
								echo substr($myresult, 0, -2);
								echo '</strong> ';
								if(count($manbaarray) > 1){
									echo word('suralarida kelgan.').'</p>';
								}else{
									echo word('surasida kelgan.').'</p>';
								}
							}
							echo '</div>
							<i class="fas fa-copy"></i>
						</div>';
					}
				?>
			</div>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
</body>

</html>