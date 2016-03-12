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

        // Register the Blade @media_select widget
		$this->registerBladeMediaSelectWidget();
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
            'file'       => new Types\FileType,
            'multifile'  => new Types\MultiFileType,
            'avatar'     => new Types\AvatarType,
            'switchery'  => new Types\SwitcheryType,
            'dropzone'   => new Types\DropzoneType,
            'true_false' => new Types\TruefalseType,
            'repeater'   => new Types\RepeaterType,
            'wysiwyg'    => new Types\WysiwygType,
            'video'		 => new Types\VideoType,
        ];

        $manager = $this->app['platform.attributes.manager'];

        foreach ($types as $type) {
            $manager->registerType($type);
        }
	}

	/**
     * Register the Blade @media_select widget.
     *
     * @return void
     */
	public function registerBladeMediaSelectWidget()
	{
        $this->app['blade.compiler']->directive('media_select', function ($value) {
            return "<?php echo Widget::make('sanatorium/inputs::media.select', array$value); ?>";
        });
	}

}
