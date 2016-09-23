<?php namespace Sanatorium\Inputs\Providers;

use Cartalyst\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Sanatorium\Inputs\Types;

class InputServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register all the project-user types
        $this->registerTypes();

		// Register the Blade @display widget
		$this->registerDisplayWidget();

        // Register the blade @attributesnot widget
        $this->registerAttributesnotWidget();

        // Register relations manager
        $this->bindIf('sanatorium.inputs.relations', 'Sanatorium\Inputs\Repositories\RelationsRepository');

        // Register default relations
        $this->registerRelations();

        // Register the Blade @live widget
        $this->registerBladeLiveWidget();

        // Config and other resources
        $this->prepareResources();

	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
        // Override the original attributes the data handler
        $this->app->bind('platform.attributes.handler.data', 'Sanatorium\Inputs\Handlers\Attributes\AttributesDataHandler');

        // Override the original attributes validator
        $this->app->bind('platform.attributes.validator', 'Sanatorium\Inputs\Validator\Attributes\AttributeValidator');
	}

	public function registerTypes()
	{
		$types = [
            'file'       		=> new Types\FileType,
            'switchery'  		=> new Types\SwitcheryType,
            'dropzone'   		=> new Types\DropzoneType,
            'true_false' 		=> new Types\TruefalseType,
            'repeater'   		=> new Types\RepeaterType,
            'wysiwyg'    		=> new Types\WysiwygType,
            'video'		 		=> new Types\VideoType,
			'media'				=> new Types\MediaType,
			'image'		 		=> new Types\ImageType,
			'gallery'		 	=> new Types\GalleryType,
			'category'			=> new Types\CategoryType,
            'date'			    => new Types\DateType,
            'scale'             => new Types\ScaleType,
            'phone'             => new Types\PhoneType,
            'url'               => new Types\UrlType,
            'email'             => new Types\EmailType,
            'relation'          => new Types\RelationType,
            'country'           => new Types\CountryType,
            'avatar'            => new Types\AvatarType,
            'tags'              => new Types\TagsType,
        ];

        $manager = $this->app['platform.attributes.manager'];

        foreach ($types as $type) {
            $manager->registerType($type);
        }
	}

	/**
     * Register the Blade @display widget.
     *
     * @return void
     */
	public function registerDisplayWidget()
	{
        $this->app['blade.compiler']->directive('display', function ($value) {
            return "<?php echo Widget::make('sanatorium/inputs::display.show', array$value); ?>";
        });
	}

	public function registerAttributesnotWidget()
    {
        $this->app['blade.compiler']->directive('attributesnot', function ($value) {
            return "<?php echo Widget::make('sanatorium/inputs::entity.show', array$value); ?>";
        });
    }

    public function registerRelations()
    {
        try
        {
            // Register the relations
            $this->app['sanatorium.inputs.relations']->registerRelation(
                'page', 'Platform\Pages\Models\Page'
            );

        } catch (\ReflectionException $e)
        {
            // sanatorium/inputs is not installed or does not support relations
        }
    }

    /**
     * Register the Blade @live widget.
     *
     * @return void
     */
    public function registerBladeLiveWidget()
    {
        $this->app['blade.compiler']->directive('live', function ($value) {
            return "<?php echo Widget::make('sanatorium/inputs::live.make', array$value); ?>";
        });

        $this->app['blade.compiler']->directive('live_custom', function ($value) {
            return "<?php echo Widget::make('sanatorium/inputs::live.custom', array$value); ?>";
        });
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-inputs');

        $this->publishes([
            $config => config_path('sanatorium-inputs.php'),
        ], 'config');
    }

}
