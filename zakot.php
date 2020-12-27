<?
require 'blocks/brain.php';
require 'functions.php';

$page = "zakot";
$pageTitle = word('Zakot');
$headerImg = "zakat.webp";
$headerColor = "linear-gradient(to right top, #a69c96, #b19d8d, #b89f84, #bda379, #bea76f, #bdaa65, #bbae5b, #b6b252, #b0b646, #a7ba39, #9cbe2d, #8fc31f);";

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