# tree <-> array classes

use <code>$tree = new tree_from_array($array);</code> to transform an array to a tree<br>
use <code>$array = new array_from_tree($tree);</code> to transform a tree to an array

# functions 
**treeFromArray 
Translate an array into a tree

**arrayFromTree
Translate a tree into an array

**getTree
Return the instance tree

**getArray
Return the instance array

**searchInArrayGetInTree
Retrieve some data in instance's array and return it with their parents as a tree

**searchInArray
Retrieve some data in instance's array and return it with their parents

**getParents
Retrieve some data's parents in instance's array

**alreadyInArray
True if the given array contain the passed data

**sortTree
Sort the given tree

**sortArray
Sort the given array

**findIdInTreeAndAddChild
Find some data in given tree and add to it the passed data as a child

**error
Display an error message and kill the execution

see index.php for more info