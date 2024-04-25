<?
$result1 = mysqli_query($db, "SELECT count(*) FROM suralar");
$row1 = mysqli_fetch_row($result1);
$rand = mt_rand(0,$row1[0] - 1);

$result101 = mysqli_query($db, "SELECT textar FROM suralar LIMIT $rand, 1");
$row101 = mysqli_fetch_assoc($result101);

echo $row101['textar'];