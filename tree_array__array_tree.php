<?php
/** 
 * tree <-> array classes
 * 
 * @author Jad Haddouch <jad.haddouch@gmail.com>
 * @docauthor Jad Haddouch <jad.haddouch@gmail.com>
 * @copyright Copyright 2018 JH - Dev
 */



/** 
 * tree_array__array_tree class
 */
class tree_array__array_tree {
	/**
	 * the original data in an array
	 */
	var $a = array();
	
	/**
	 * the original data in a tree
	 */
	var $t = array();
	
	/**
	 * the name of the parent field
	 */
	var $parent_field = 'parent';
	
	/**
	 * the root parent id
	 */
	var $parent_root_id = 0;
	
	/**
	 * the name of the id field
	 */
	var $id_field = 'id';
	
	/**
	 * the name of the children field
	 */
	var $children_field = 'children';
	
	
	
	/**
	 * Constructor
	 *
	 * @param $origin the original data
	 * @param $format the original data format
	 * @param $cfg [optional] this element configuration
	 */
	function __construct ($origin, $format, $cfg = array()) {
		if ($format === 'array' || $format === 'tree') { 
			$this->t = $origin;
			
			$array = $format === 'array' ? $origin : array();
			$tree = $format === 'tree' ? $origin : array();
		} 
		else { $this->error('__construct($origin, $format) : unrecognized $format = (' . $format . ')'); }
		
		$this->a = $array;
		$this->t = $tree;
	
		if (isset($cfg['parent_field'])) { $this->parent_field = $cfg['parent_field']; }
		if (isset($cfg['parent_root_id'])) { $this->parent_root_id = $cfg['parent_root_id']; }
		if (isset($cfg['id_field'])) { $this->id_field = $cfg['id_field']; }
		if (isset($cfg['children_field'])) { $this->parent_field = $cfg['children_field']; }
	}
	
	
	
	/**
	 * getTree
	 *
	 * @param $sort [optional] the field used to make sorting
	 * @param $direction [optional] the sorting direction
	 */
	function getTree ($sort = false, $direction = SORT_ASC) { 
		if ($sort !== false) { $this->sortTree($this->t, $sort, $direction); }
		return $this->t; 
	}
	
	
	
	
	
	/**
	 * searchInArrayGetInTree
	 *
	 * @param $ids An array of values to find
	 * @param $ids_id_field [optional] the name of the field used in the research values array ($ids)
	 * @param $id_field the name of the field used in data ($this->a)
	 * @param $sort [optional] the field used to make sorting
	 * @param $direction [optional] the sorting direction
	 */
	function searchInArrayGetInTree ($ids, $ids_id_field = 'id', $id_field = 'id', $sort = false, $direction = SORT_ASC) {
		$t = $this->searchInArray($ids, $ids_id_field, $id_field);
		
		$t = $this->treeFromArray(false, $t);
		
		if ($sort !== false) { $this->sortTree($t, $sort, $direction); }
		
		return $t;
	}
	
	
	
	
	
	/**
	 * searchInArray
	 * search in $this->a the $ids values 
	 *
	 * @param $ids An array of values to find
	 * @param $ids_id_field [optional] the name of the field used in the research values array ($ids)
	 * @param $id_field the name of the field used in data ($this->a)
	 */
	function searchInArray ($ids, $ids_id_field = 'id', $id_field = 'id') {
		$t = array();
		
		$this->sortArray($this->a, $this->parent_field, SORT_DESC);
		
		foreach ($ids as $id) {
			foreach ($this->a as $data) {
				if (isset($data[$id_field], $id[$ids_id_field]) && $data[$id_field] == $id[$ids_id_field]) { $t[] = $data; }
			}
		}
		
		$this->getParents($t);
		
		return $t;
	}
	
	
	
	
	
	/**
	 * getParents
	 * add in $array the parents of the already present values
	 *
	 * @param $array An array of values to add their parents
	 * @param $ids_id_field [optional] the name of the field used in the research values array ($ids)
	 * @param $id_field the name of the field used in data ($this->a)
	 */
	function getParents (&$array, $ids_id_field = 'id', $id_field = 'id') {
		foreach ($array as $data) {
			if ($data[$this->parent_field] > $this->parent_root_id) { 
				$array = array_merge($array, $this->searchInArray(array(array($ids_id_field => $data[$this->parent_field]),), $ids_id_field, $id_field)); 
			}
		}
	}
	
	
	
	/**
	 * getArray
	 *
	 * @param $sort [optional] the field used to make sorting
	 * @param $direction [optional] the sorting direction
	 */
	function getArray ($sort = false, $direction = SORT_ASC) { 
		if ($sort !== false) { $this->sortArray($this->a, $sort, $direction); }
		return $this->a; 
	}
	
	
	
	/**
	 * sortTree
	 *
	 * @param $tree the tree to sort
	 * @param $field the field used to make sorting
	 * @param $direction [optional] the sorting direction
	 */
	function sortTree (&$tree, $field, $direction = SORT_ASC) {
		$this->sortArray ($tree, $field, $direction);
		foreach ($tree as &$data) { if (isset($data[$this->children_field])) { $this->sortTree($data[$this->children_field], $field, $direction); }}
	}
	
	
	
	/**
	 * sortArray
	 *
	 * @param $array the array to sort
	 * @param $field the field used to make sorting
	 * @param $direction [optional] the sorting direction
	 */
	function sortArray (&$array, $field, $direction = SORT_ASC) { array_multisort(array_column($array, $field), $direction, $array); }
	
	
	
	/**
	 * treeFromArray
	 * Make a tree from an array
	 *
	 * @param $set_this_t [optional] true to set $this->t
	 * @param $array [optional] the source array 
	 */
	function treeFromArray ($set_this_t = true, $array = false) {
		$this_t = array();
		
		$array = $array === false ? $this->a : $array;
		
		$this->sortArray($array, $this->parent_field);
		
		foreach ($array as $v) {
			$parent_id = isset($v[$this->parent_field]) ? $v[$this->parent_field] : $this->parent_root_id;
			if ($parent_id > $this->parent_root_id) { $this->findIdInTreeAndAddChild($this_t, $parent_id, $v); } 
			else { $this_t[] = $v; }
		}
		
		if ($set_this_t === true) { $this->t = $this_t; }
		
		return $this_t;
	}
	
	

	/**
	 * findIdInTreeAndAddChild
	 * Add an array record in his parent
	 *
	 * @param $tree the tree
	 * @param $id the parent id
	 * @param $child the child data
	 */
	function findIdInTreeAndAddChild (&$tree, $id, $child) {
		foreach ($tree as &$data) {
			if ($data[$this->id_field] == $id) {
				if (!isset($data[$this->children_field])) { $data[$this->children_field] = array(); }
				$data[$this->children_field][] = $child;
				return true;
			} 
			else if (isset($data[$this->children_field]) && $this->findIdInTreeAndAddChild($data[$this->children_field], $id, $child)) { return true; } 
		}
		return false;
	}
	
	
	
	/**
	 * arrayFromTree
	 * Make an array from a tree
	 *
	 * @param $this_a the array to set
	 * @param $tree source tree
	 * @param $set_this_a[optional] true to set $this->a
	 */
	function arrayFromTree (&$this_a, $tree, $set_this_a = true) {
		foreach ($tree as $v) {
			if (isset($v[$this->children_field])) { $this->arrayFromTree($this_a, $v[$this->children_field]); }
			$a = $v;
			unset($a[$this->children_field]);
			$this_a[] = $a;
		}
		
		if ($set_this_a === true) { $this->a = $this_a; }
		
		return $this_a;
	}
	
	
	
	/**
	 * error
	 *
	 * @param $msg the error message
	 */
	function error ($msg) { die('ERROR tree_array__array_tree { msg: ' . $msg . ' }'); }
	
}

/** 
 * tree_from_array class
 */
class tree_from_array extends tree_array__array_tree {
	/**
	 * Constructor
	 *
	 * @param $array the original data
	 * @param $cfg [optional] this element configuration
	 */
	function __construct ($array, $cfg = array()) { 
		parent::__construct($array, 'array', $cfg);
		$this->treeFromArray();
	}
}

/** 
 * array_from_tree class
 */
class array_from_tree extends tree_array__array_tree {
	/**
	 * Constructor
	 *
	 * @param $tree the original data
	 * @param $cfg [optional] this element configuration
	 */
	function __construct ($tree, $cfg = array()) {
		parent::__construct($tree, 'tree', $cfg);
		$this->arrayFromTree($this->a, $this->t);
	}
}

?>