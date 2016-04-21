<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Bill',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/bill',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Bills',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.0',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Bill\Providers\BillServiceProvider',
		'Sanatorium\Bill\Providers\JobServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::group([
				'prefix'    => admin_uri().'/bill/bills',
				'namespace' => 'Sanatorium\Bill\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.bill.bills.all', 'uses' => 'BillsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.bill.bills.all', 'uses' => 'BillsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.bill.bills.grid', 'uses' => 'BillsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.bill.bills.create', 'uses' => 'BillsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.bill.bills.create', 'uses' => 'BillsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.bill.bills.edit'  , 'uses' => 'BillsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.bill.bills.edit'  , 'uses' => 'BillsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.bill.bills.delete', 'uses' => 'BillsController@delete']);
			});

		Route::group([
			'prefix'    => 'bill/bills',
			'namespace' => 'Sanatorium\Bill\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.bill.bills.index', 'uses' => 'BillsController@index']);
			Route::post('create', ['as' => 'sanatorium.bill.bills.create', 'uses' => 'BillsController@create']);
			Route::get('new', ['as' => 'sanatorium.bill.bills.new', 'uses' => 'BillsController@newBill']);
			Route::get('{id}'   , ['as' => 'sanatorium.bill.bills.edit'  , 'uses' => 'BillsController@edit']);
			Route::post('{id}'  , ['as' => 'sanatorium.bill.bills.edit'  , 'uses' => 'BillsController@update']);
		});

					Route::group([
				'prefix'    => admin_uri().'/bill/jobs',
				'namespace' => 'Sanatorium\Bill\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.bill.jobs.all', 'uses' => 'JobsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.bill.jobs.all', 'uses' => 'JobsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.bill.jobs.grid', 'uses' => 'JobsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.bill.jobs.create', 'uses' => 'JobsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.bill.jobs.create', 'uses' => 'JobsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.bill.jobs.edit'  , 'uses' => 'JobsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.bill.jobs.edit'  , 'uses' => 'JobsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.bill.jobs.delete', 'uses' => 'JobsController@delete']);
			});

		Route::group([
			'prefix'    => 'bill/jobs',
			'namespace' => 'Sanatorium\Bill\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.bill.jobs.index', 'uses' => 'JobsController@index']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('bill', function($g)
		{
			$g->name = 'Bills';

			$g->permission('bill.index', function($p)
			{
				$p->label = trans('sanatorium/bill::bills/permissions.index');

				$p->controller('Sanatorium\Bill\Controllers\Admin\BillsController', 'index, grid');
			});

			$g->permission('bill.create', function($p)
			{
				$p->label = trans('sanatorium/bill::bills/permissions.create');

				$p->controller('Sanatorium\Bill\Controllers\Admin\BillsController', 'create, store');
			});

			$g->permission('bill.edit', function($p)
			{
				$p->label = trans('sanatorium/bill::bills/permissions.edit');

				$p->controller('Sanatorium\Bill\Controllers\Admin\BillsController', 'edit, update');
			});

			$g->permission('bill.delete', function($p)
			{
				$p->label = trans('sanatorium/bill::bills/permissions.delete');

				$p->controller('Sanatorium\Bill\Controllers\Admin\BillsController', 'delete');
			});
		});

		$permissions->group('job', function($g)
		{
			$g->name = 'Jobs';

			$g->permission('job.index', function($p)
			{
				$p->label = trans('sanatorium/bill::jobs/permissions.index');

				$p->controller('Sanatorium\Bill\Controllers\Admin\JobsController', 'index, grid');
			});

			$g->permission('job.create', function($p)
			{
				$p->label = trans('sanatorium/bill::jobs/permissions.create');

				$p->controller('Sanatorium\Bill\Controllers\Admin\JobsController', 'create, store');
			});

			$g->permission('job.edit', function($p)
			{
				$p->label = trans('sanatorium/bill::jobs/permissions.edit');

				$p->controller('Sanatorium\Bill\Controllers\Admin\JobsController', 'edit, update');
			});

			$g->permission('job.delete', function($p)
			{
				$p->label = trans('sanatorium/bill::jobs/permissions.delete');

				$p->controller('Sanatorium\Bill\Controllers\Admin\JobsController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-sanatorium-bill',
				'name' => 'Bill',
				'class' => 'fa fa-circle-o',
				'uri' => 'bill',
				'regex' => '/:admin\/bill/i',
				'children' => [
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Bills',
						'uri' => 'bill/bills',
						'regex' => '/:admin\/bill\/bill/i',
						'slug' => 'admin-sanatorium-bill-bill',
					],
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Jobs',
						'uri' => 'bill/jobs',
						'regex' => '/:admin\/bill\/job/i',
						'slug' => 'admin-sanatorium-bill-job',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
