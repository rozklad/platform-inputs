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
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{

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

}
