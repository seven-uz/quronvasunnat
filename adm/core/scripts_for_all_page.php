<?php

$menuQ = mysqli_query($db, "SELECT m.*, mc.id as mc_id, mc.title as mc_title, mp.id as mp_id, mp.title as mp_title FROM menu m LEFT JOIN menu_categories mc ON m.cat_id = mc.id LEFT JOIN menu_parents mp ON m.parent_id = mp.id ORDER BY mc.sort ASC, m.sort ASC;");
while($row = $menuQ->fetch_assoc()){
	$menu[] = $row;
}
echo '<pre>';
var_dump($menu);exit;
if ($menu === null) $menu = [];