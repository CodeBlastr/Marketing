<?php
$items = array();
foreach($methods as $value) {
	$items[$value] = $value;
}
echo $javascript->object($items);
?>