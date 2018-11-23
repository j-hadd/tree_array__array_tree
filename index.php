<?php

include_once('tree_array__array_tree.php');

/**
 * the default configuration for the tree_array__array_tree object
 * $cfg = array('parent_field' => 'parent', 'parent_root_id' => 0, 'id_field' => 'id', 'children_field' => 'children',);
 */

$db_data = array(
	array('id' => 1,	'name' => '1', 			'parent' => 0, 'toto' => 'toto'),
	array('id' => 4, 	'name' => '1.1', 		'parent' => 1,),
	array('id' => 10, 	'name' => '1.1.1', 		'parent' => 4,),
	array('id' => 5, 	'name' => '1.2', 		'parent' => 1,),
	array('id' => 18, 	'name' => '1.2.1', 		'parent' => 5,),
	array('id' => 17, 	'name' => '1.2.1.1',	'parent' => 18,),
	array('id' => 16, 	'name' => '1.2.2', 		'parent' => 5,),
	array('id' => 11, 	'name' => '1.2.3', 		'parent' => 5,),
	array('id' => 19, 	'name' => '1.2.3.1', 	'parent' => 11,),
	array('id' => 6, 	'name' => '1.3', 		'parent' => 1, 'titi' => 'titi'),
	array('id' => 2, 	'name' => '2', 			'parent' => 0,),
	array('id' => 7, 	'name' => '2.1', 		'parent' => 2,),
	array('id' => 8, 	'name' => '2.2', 		'parent' => 2,),
	array('id' => 12, 	'name' => '2.2.1', 		'parent' => 8,),
	array('id' => 3, 	'name' => '3', 			'parent' => 0,),
	array('id' => 9, 	'name' => '3.1', 		'parent' => 3,),
	array('id' => 15, 	'name' => '3.2', 		'parent' => 3,),
	array('id' => 13, 	'name' => '3.2.1', 		'parent' => 15,),
	array('id' => 14, 	'name' => '3.3', 		'parent' => 3,),
	//array('id' => 20, 'name' => '', 'parent' => 0,),
	
);

shuffle($db_data);

echo '<pre>$db_data = '; print_r($db_data); echo '</pre>';

$tree = new tree_from_array($db_data);
$tree = $tree->getTree('name');

echo '<pre>$tree = '; print_r($tree); echo '</pre>';

$array = new array_from_tree($tree);
$array = $array->getArray('name', SORT_DESC);

echo '<pre>$array = '; print_r($array); echo '</pre>';

?>