<?
require 'blocks/brain.php';
require 'functions.php';

$page = "haj";
$pageTitle = word('Haj');
$headerImg = "haj.webp";
$headerColor = "linear-gradient(to right bottom, #1b171b, #2b1f25, #3d272b, #4e312e, #5b3d2e, #624c35, #675b3f, #6b6b4c, #727e66, #7e9082, #90a19b, #a8b2b1);";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

require 'blocks/head.php';
require 'blocks/nav.php';
require 'blocks/header.php';


?>
<main>
	<section>
		<h3>Content</h3>
	</section>
</main>

<?include 'blocks/footer.php'?>
</body>

</html>