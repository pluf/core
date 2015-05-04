# Pluf ORM (Active Record like)

At the core of Plume is the Active Record ORM. This ORM enables rapid development of extensions without the need to deal with the SQL needed to store the data into the database. It is built to support different database engines.

## Presentation

The ORM is based on the Active Record pattern. To create your own model you need to extend the Pluf_Model class and overwrite the init() method with the details of your model. You can of course add custom methods. See the Pluf_User model for example. The Pluf_Model is very close to the Django model.

A given class model is associated to a table in the database. Each item is saved as one row in the table.

Here is a short example using the models of the test application. In this example we create a todo list and add 2 todo items in the list.

	// Create a list
	$list = new Todo_List();
	$list->name = 'My todo list';
	$list->create();
	// Create a todo item
	$item = new Todo_Item();
	$item->item = 'Improve the documentation';
	$item->completed = true;
	$item->list = $list;
	$item->create();
	// Create another todo item
	$item = new Todo_Item();
	$item->item = 'Improve the website';
	$item->completed = false;
	$item->list = $list;
	$item->create();
	// Get the list of all the items in the list
	$items = $list->get_todo_item_list();   

As you can see, you do not have to think about SQL, all the work is done for you by the ORM.

## Basic definition of a model

Here is a presentation of how you should define a model extending Pluf_Model.

	class MyApp_MyModel extends Pluf_Model
	{
	    public $_model = __CLASS__;
	    function init()
	    { 
	        // The table in the database where your
	        // model is stored.
	        $this->_a['table'] = 'myapp_mymodels';
	        // The name of your model
	        $this->_a['model'] = 'MyApp_MyModel';
	
	        // Here the core with the definition of the
	        // fields.
	        $this->_a['cols'] = array(
	           // The id field of type sequence is mandatory
	           // for each model
	           'id' =>
	           array(
	             'type' => 'Pluf_DB_Field_Sequence',
	             'blank' => true,//It is automatically added.
	             ),
	           // Here we have string for the title
	           // it must not be empty
	           'title' => 
	           array(
	             'type' => 'Pluf_DB_Field_Varchar',
	             'blank' => false,
	             'size' => 100,
	             ),
	           // But the description can be empty.
	           'description' => 
	           array(
	              'type' => 'Pluf_DB_Field_Text',
	              'blank' => true,
	              ),
	        );
	    }
	} 

The definition of the fields is an associative array with the key being the name of the field and the value being another associative array of parameters. The most important parameters are:

    type : The type of field. If it is a string, integer etc. It is the only needed parameter.
    blank : A boolean value to know if the field can be empty or not.
    size : For the Varchar fields, this is the maximum allowed size.
    verbose : The long name of the field. Used by the Pluf_Paginator class and others.
    help_text : A text to explain the field. It is displayed in the automatically generated forms.

Each type of field has different extra parameters.

Now you can use this model the following way:

	$model = new MyApp_MyModel();
	$model->title = 'Hello world!';
	$model->description = 'What first program says.';
	$model->create();
	$model->description = 'What your first program says.';
	$model->update();

Very simple, right?

