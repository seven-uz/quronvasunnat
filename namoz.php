<?
require 'blocks/brain.php';
require 'functions.php';

$page = "namoz";
$pageTitle = word('Namoz');
$headerImg = "namaz.webp";
$headerColor = "linear-gradient(to bottom, #732200, #7f2a02, #8c3203, #983a04, #a54204, #a14006, #9e3e07, #9a3c09, #85300b, #70250a, #5c1b07, #481100);";

$duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

require 'blocks/head.php';
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