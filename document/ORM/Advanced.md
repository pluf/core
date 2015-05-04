# Advanced Use of the Pluf ORM

Before going further with the advanced usage of the ORM, you need first to have a good understanding of the ORM as described in the basic usage.

# Creating Dynamic Views

The Pluf ORM is really simple to keep it fast, this means it will not perform table join for you. You can create join through views. They are a bit like table views at the RDBMS level but are implemented at the ORM level.

We are going to work with a small todo list with todo items belonging to a lists to illustrate the system. Each todo item belong to one list, so we are going to create a view to join the todo item with the list.

Here are the two base models:

	class Todo_Item extends Pluf_Model
	{
	    public $_model = __CLASS__;
	
	    function init()
	    {
	        $this->_a['table'] = 'todo_items';
	        $this->_a['model'] = __CLASS__;
	        $this->_a['cols'] = array(
	                            'id' =>
	                            array(
	                                  'type' => 'Pluf_DB_Field_Sequence',
	                                  'blank' => true, 
	                                  ),
	                            'item' => 
	                            array(
	                                  'type' => 'Pluf_DB_Field_Varchar',
	                                  'blank' => false,
	                                  'size' => 250,
	                                   ),
	                            'completed' => 
	                            array(
	                                  'type' => 'Pluf_DB_Field_Boolean',
	                                  'default' => false,
	                                  'index' => true,
	                                  ),
	                            'list' => 
	                            array(
	                                  'type' => 'Pluf_DB_Field_Foreignkey',
	                                  'blank' => false,
	                                  'model' => 'Todo_List',
	                                  ),
	                            );
	        $this->_a['idx'] = array();
	        $this->_a['views'] = array(
	            // The name of the view is 'with_list'
	            'with_list' => array(
	            // We are doing a left join to join the list 
	            'join' => 'LEFT JOIN '.$this->con->pfx.'todo_lists ON list='.$this->con->pfx.'todo_lists.id',
	            // We want to select the name of the list from the list table
	            // the get select will select all the fields of the current
	            // model and we add the name
	            'select' => $this->getSelect().', name',
	            // we are going to add the name of the list as property of
	            // the model when listing with this view. These will be 
	            // read only properties. It will be available as list_name
	            // property.
	            'props' => array('name' => 'list_name'),
	                         ));
	
	    }
	}

	class Todo_List extends Pluf_Model
	{
	    public $_model = __CLASS__;
	    function init()
	    {
	        $this->_a['table'] = 'todo_lists';
	        $this->_a['model'] = 'Todo_List';
	        $this->_a['cols'] = array(
	                            'id' =>
	                            array(
	                                  'type' => 'Pluf_DB_Field_Sequence',
	                                  'blank' => true, 
	                                  ),
	                            'name' => 
	                            array(
	                                  'type' => 'Pluf_DB_Field_Varchar',
	                                  'blank' => false,
	                                  'size' => 100,
	                                   ),
	                            );
	        $this->_a['idx'] = array();
	        $this->_a['views'] = array();
	    }
	}

To use this view, you can simply select all the items.

	$items = Pluf::factory('Todo_Item')->getList(array('view' => 'with_list'));
	foreach ($items as $item) {
	    print $item->item.' in '.$item->list_name."\n";
	}

In that case you will have only one SQL query.