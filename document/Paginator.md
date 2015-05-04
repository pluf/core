# صفحه بندی

در بسیاری از موارد با فهرست بزرگی از داده‌ها روبرو هستیم که برای کار با آنها نیز به صفحه بندی و دسته بندی آنها داریم. در این سکو امکانات مناسبی برای صفحه بندی داده‌ها در نظر گرفته شده است. تمام این امکانات در کلاس Pluf_Paginator ایجاد شده است.


## یک نمونه ساده

یک نمونه بسیار ابتدایی از این کلاس به صورت زیر تعریف می‌شود:

	$garticle = new YourApp_Article();
	$pag = new Pluf_Paginator($garticle);
	$pag->action = array('YourApp_Views::listArticles');
	$pag->summary = __('This table shows a list of the articles.');
	$list_display = array(
	       array('id', 'Pluf_Paginator_ToString', __('title')),
	       array('modif_dtime', 'Pluf_Paginator_DateYMD', __('modified')),
	       array('status', 'Pluf_Paginator_DisplayVal', __('status')), 
	                     );
	$pag->items_per_page = 50;
	$pag->no_results_text = __('No articles were found.');
	$pag->configure($list_display, 
	                array('content'), 
	                array('status', 'modif_dtime')
	               );
	$pag->setFromRequest($request);

### تشریح نمونه 

This means that you will list 50 articles per page:

	$pag->items_per_page = 50;

each page will have 3 columns with the title, last modification day and the status of the article.

	$list_display = array(
	       array('id', 'Pluf_Paginator_ToString', __('title')),
	       array('modif_dtime', 'Pluf_Paginator_DateYMD', __('modified')),
	       array('status', 'Pluf_Paginator_DisplayVal', __('status')), 
	                     );

For example, 'id' means that the field id of your model is passed to the function Pluf_Paginator_ToString together with the item listed. This function do not used the field but directly call the method __toString of the item and returns it.

For the second column, the 'modif_dtime' field is passed to the a function that is simply returning the timestamp formatted in YYYY-MM-DD format.

The third column is displaying the 'status' of the article, the call back function here is getting the corresponding value from the available choices in the model column definition.

You will be able to sort the records by status and modification time and you can search in the content of the article.

	$pag->configure($list_display, 
	                array('content'), 
	                array('status', 'modif_dtime')
	               );

## Paginator configuration

### items = null

An ArrayObject of items to list. Only used if not using directly a model.

### item_extra_props = array()

Extra property/value for the items.

This can be practical if you want some values for the edit action which are not available in the model data.

### forced_where = null

The forced where clause on top of the search.

### model_view = null

View of the model to be used.

### items_per_page = 50

Maximum number of items per page.

### no_results_text = 'No items found'

Text to display when no results are found.

### sort_fields = array()

Which fields of the model can be used to sort the dataset. To be useable these fields must be in the $list_display so that the sort links can be shown for the user to click on them and sort the list.

### sort_order = array()

Current sort order. An array with first value the field and second the order of the sort.

### edit_action = ''

Edit action, if you set it, the first column data will be linked to to view you give here.

You can give a simple view like : YourApp_Views::editItem, the id of the item will be given as argument to the view. You can also decide what arguments you pass to the view, for example: array('YourApp_Views::edit-normal', 'id') will the view YourApp_Views::edit-normal with the id as first argument.

### action = ''

Action for search/next/previous. The action is either the simple Model::views like YourApp_Views::listItems or you can give a fully defined view like array('YourApp_Views::listItems', array('value1', 'value2'))

### id = ''

Id of the generated table.

### extra = null

Extra parameters for the modification function call. These parameters are given as third argument to the call back functions when displaying the data.

### summary = ''

Summary for the table.

### nb_items = 0

Total number of items. Available only after the rendering of the paginator.

## In your template

To disply the result in your template and if you have associated $pag to the template variable articles:

	{$articles.render}

Yes, not more complicated than that.