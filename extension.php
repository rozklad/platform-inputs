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

    'name' => 'Inputs',

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

    'slug' => 'sanatorium/inputs',

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

    'description' => 'Extended input types',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version should be a string that can be used with version_compare().
    | This is how the extensions versions are compared.
    |
    */

    'version' => '4.2.1',

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

    'require' => [
        'platform/attributes',
        'platform/media',
    ],

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

		'Sanatorium\Inputs\Providers\InputServiceProvider',
		'Sanatorium\Inputs\Providers\GroupServiceProvider',
		'Sanatorium\Inputs\Providers\FormServiceProvider',

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
            'prefix'    => 'inputs',
            'namespace' => 'Sanatorium\Inputs\Controllers\Frontend',
        ], function ()
        {
            Route::group([
                'prefix' => 'media',
            ], function ()
            {
                Route::get('/', ['as' => 'sanatorium.inputs.media.all', 'uses' => 'MediaController@getMedia']);
                Route::any('upload', ['as' => 'sanatorium.inputs.media.upload', 'uses' => 'MediaController@upload']);
                Route::get('{id}/{type}', ['as' => 'sanatorium.inputs.media.entity', 'uses' => 'MediaController@getMediaAssignedToEntity']);
            });

            Route::post('dropzone', ['as' => 'sanatorium.inputs.dropzone.upload', 'uses' => 'DropzoneController@upload']);
            Route::post('dropzone/single', ['as' => 'sanatorium.inputs.dropzone.upload.single', 'uses' => 'DropzoneController@single']);
            Route::post('dropzone/avatar', ['as' => 'sanatorium.inputs.dropzone.upload.avatar', 'uses' => 'DropzoneController@avatar']);
            Route::post('dropzone/cover', ['as' => 'sanatorium.inputs.dropzone.cover', 'uses' => 'DropzoneController@cover']);
            Route::delete('dropzone/delete', ['as' => 'sanatorium.inputs.dropzone.delete', 'uses' => 'DropzoneController@delete']);

            Route::get('media/options/{only_images}', ['as' => 'sanatorium.inputs.media.options', 'uses' => 'DropzoneController@options']);

            Route::post('live/edit', ['as' => 'sanatorium.inputs.live.edit', 'uses' => 'LiveController@edit']);
        });
        Route::group([
            'prefix' => admin_uri(),
            'namespace' => 'Sanatorium\Inputs\Controllers\Admin',
        ], function()
        {
            Route::get('inputs/attributes/settings/{type?}', ['as' => 'sanatorium.inputs.attributes.settings', 'uses' => 'AttributesController@settings']);

            Route::group([
                'prefix' => 'media',
            ], function ()
            {
                Route::delete('{id}', ['as' => 'sanatorium.inputs.media.delete', 'uses' => 'MediaController@delete']);
                //Route::get('files_list', ['as' => 'admin.media.files_list', 'uses' => 'MediaController@filesList']);
                // Used by Imperavi redactor image media manager, overrides platform/media
                Route::get('images_list', ['as' => 'admin.media.images_list', 'uses' => 'MediaController@imagesList']);
                //Route::post('upload', ['as' => 'admin.media.upload', 'uses' => 'MediaController@upload']);
                //Route::post('link_media', ['as' => 'admin.media.link_media', 'uses' => 'MediaController@linkMedia']);
            });
        });

		Route::group([
				'prefix'    => admin_uri().'/inputs/groups',
				'namespace' => 'Sanatorium\Inputs\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.inputs.groups.all', 'uses' => 'GroupsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.inputs.groups.all', 'uses' => 'GroupsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.inputs.groups.grid', 'uses' => 'GroupsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.inputs.groups.create', 'uses' => 'GroupsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.inputs.groups.create', 'uses' => 'GroupsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.inputs.groups.edit'  , 'uses' => 'GroupsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.inputs.groups.edit'  , 'uses' => 'GroupsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.inputs.groups.delete', 'uses' => 'GroupsController@delete']);
			});

		Route::group([
			'prefix'    => 'inputs/groups',
			'namespace' => 'Sanatorium\Inputs\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.inputs.groups.index', 'uses' => 'GroupsController@index']);
		});

        Route::get('/show/media/{path}', ['as' => 'sanatorium.inputs.show.media', 'uses' => function($path){

            // Only for local content
            $path = storage_path('files/'.$path);

            if ( !File::exists($path) )
                abort(404);

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;

        }])->where('path', '.*');

					Route::group([
				'prefix'    => admin_uri().'/inputs/forms',
				'namespace' => 'Sanatorium\Inputs\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.inputs.forms.all', 'uses' => 'FormsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.inputs.forms.all', 'uses' => 'FormsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.inputs.forms.grid', 'uses' => 'FormsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.inputs.forms.create', 'uses' => 'FormsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.inputs.forms.create', 'uses' => 'FormsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.inputs.forms.edit'  , 'uses' => 'FormsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.inputs.forms.edit'  , 'uses' => 'FormsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.inputs.forms.delete', 'uses' => 'FormsController@delete']);
			});

		Route::group([
			'prefix'    => 'inputs/forms',
			'namespace' => 'Sanatorium\Inputs\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.inputs.forms.index', 'uses' => 'FormsController@index']);
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
        $permissions->group('inputs', function($g)
        {
            $g->name = 'Inputs';

            $g->permission('inputs.tags.create', function ($p)
            {
                $p->label = trans('sanatorium/inputs::types.tags.create_value');
            });
        });

		$permissions->group('group', function($g)
		{
			$g->name = 'Groups';

			$g->permission('group.index', function($p)
			{
				$p->label = trans('sanatorium/inputs::groups/permissions.index');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\GroupsController', 'index, grid');
			});

			$g->permission('group.create', function($p)
			{
				$p->label = trans('sanatorium/inputs::groups/permissions.create');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\GroupsController', 'create, store');
			});

			$g->permission('group.edit', function($p)
			{
				$p->label = trans('sanatorium/inputs::groups/permissions.edit');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\GroupsController', 'edit, update');
			});

			$g->permission('group.delete', function($p)
			{
				$p->label = trans('sanatorium/inputs::groups/permissions.delete');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\GroupsController', 'delete');
			});
		});

		$permissions->group('form', function($g)
		{
			$g->name = 'Forms';

			$g->permission('form.index', function($p)
			{
				$p->label = trans('sanatorium/inputs::forms/permissions.index');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\FormsController', 'index, grid');
			});

			$g->permission('form.create', function($p)
			{
				$p->label = trans('sanatorium/inputs::forms/permissions.create');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\FormsController', 'create, store');
			});

			$g->permission('form.edit', function($p)
			{
				$p->label = trans('sanatorium/inputs::forms/permissions.edit');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\FormsController', 'edit, update');
			});

			$g->permission('form.delete', function($p)
			{
				$p->label = trans('sanatorium/inputs::forms/permissions.delete');

				$p->controller('Sanatorium\Inputs\Controllers\Admin\FormsController', 'delete');
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

    'widgets' => function ()
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

    'settings' => function (Settings $settings, Application $app)
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
				'class' => 'fa fa-object-group',
				'name' => 'Groups',
				'uri' => 'inputs/groups',
				'regex' => '/:admin\/inputs\/group/i',
				'slug' => 'admin-sanatorium-inputs-group',
				'children' => [
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Forms',
						'uri' => 'inputs/forms',
						'regex' => '/:admin\/inputs\/form/i',
						'slug' => 'admin-sanatorium-inputs-form',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
