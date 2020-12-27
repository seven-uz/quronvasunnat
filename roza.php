<?
require 'blocks/brain.php';
require 'functions.php';

$page = "roza";
$pageTitle = word('Roza');
$headerImg = "ramadan.webp";
$headerColor = "linear-gradient(to right, #05053c, #083465, #28618b, #548fad, #89bfce, #85c4d6, #80cade, #7acfe6, #24a8da, #007fcd, #0054b6, #282191);";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

require 'blocks/head.php';
require 'blocks/nav.php';
require 'blocks/header.php';

?>
<main>
	<section>
		<h3>Roza</h3>
	</section>
</main>

<?include 'blocks/footer.php'?>
</body>

</html>