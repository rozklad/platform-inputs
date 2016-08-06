<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc01d2ac0268d4efa8d09a78118709455
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sanatorium\\Inputs\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sanatorium\\Inputs\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'CreateAttributeRelationsTable' => __DIR__ . '/../..' . '/database/migrations/2016_08_06_224510_create_attribute_relations_table.php',
        'CreateAttributesInputgroupsTable' => __DIR__ . '/../..' . '/database/migrations/2016_08_06_203039_create_attributes_inputgroups_table.php',
        'CreateGroupsTable' => __DIR__ . '/../..' . '/database/migrations/2016_08_06_191343_create_groups_table.php',
        'CreateMediaAssignTable' => __DIR__ . '/../..' . '/database/migrations/2015_09_22_201025_create_media_assign_table.php',
        'Sanatorium\\Inputs\\Controllers\\Admin\\GroupsController' => __DIR__ . '/../..' . '/src/Controllers/Admin/GroupsController.php',
        'Sanatorium\\Inputs\\Controllers\\Admin\\MediaController' => __DIR__ . '/../..' . '/src/Controllers/Admin/MediaController.php',
        'Sanatorium\\Inputs\\Controllers\\Frontend\\DropzoneController' => __DIR__ . '/../..' . '/src/Controllers/Frontend/DropzoneController.php',
        'Sanatorium\\Inputs\\Controllers\\Frontend\\GroupsController' => __DIR__ . '/../..' . '/src/Controllers/Frontend/GroupsController.php',
        'Sanatorium\\Inputs\\Controllers\\Frontend\\MediaController' => __DIR__ . '/../..' . '/src/Controllers/Frontend/MediaController.php',
        'Sanatorium\\Inputs\\Handlers\\Group\\GroupDataHandler' => __DIR__ . '/../..' . '/src/Handlers/Group/GroupDataHandler.php',
        'Sanatorium\\Inputs\\Handlers\\Group\\GroupDataHandlerInterface' => __DIR__ . '/../..' . '/src/Handlers/Group/GroupDataHandlerInterface.php',
        'Sanatorium\\Inputs\\Handlers\\Group\\GroupEventHandler' => __DIR__ . '/../..' . '/src/Handlers/Group/GroupEventHandler.php',
        'Sanatorium\\Inputs\\Handlers\\Group\\GroupEventHandlerInterface' => __DIR__ . '/../..' . '/src/Handlers/Group/GroupEventHandlerInterface.php',
        'Sanatorium\\Inputs\\Models\\Group' => __DIR__ . '/../..' . '/src/Models/Group.php',
        'Sanatorium\\Inputs\\Models\\Media' => __DIR__ . '/../..' . '/src/Models/Media.php',
        'Sanatorium\\Inputs\\Models\\Mediable' => __DIR__ . '/../..' . '/src/Models/Mediable.php',
        'Sanatorium\\Inputs\\Providers\\GroupServiceProvider' => __DIR__ . '/../..' . '/src/Providers/GroupServiceProvider.php',
        'Sanatorium\\Inputs\\Providers\\InputServiceProvider' => __DIR__ . '/../..' . '/src/Providers/InputServiceProvider.php',
        'Sanatorium\\Inputs\\Repositories\\Group\\GroupRepository' => __DIR__ . '/../..' . '/src/Repositories/Group/GroupRepository.php',
        'Sanatorium\\Inputs\\Repositories\\Group\\GroupRepositoryInterface' => __DIR__ . '/../..' . '/src/Repositories/Group/GroupRepositoryInterface.php',
        'Sanatorium\\Inputs\\Repositories\\RelationsRepository' => __DIR__ . '/../..' . '/src/Repositories/RelationsRepository.php',
        'Sanatorium\\Inputs\\Repositories\\RelationsRepositoryInterface' => __DIR__ . '/../..' . '/src/Repositories/RelationsRepositoryInterface.php',
        'Sanatorium\\Inputs\\Traits\\MediableTrait' => __DIR__ . '/../..' . '/src/Traits/MediableTrait.php',
        'Sanatorium\\Inputs\\Traits\\ThumbableTrait' => __DIR__ . '/../..' . '/src/Traits/ThumbableTrait.php',
        'Sanatorium\\Inputs\\Types\\BaseType' => __DIR__ . '/../..' . '/src/Types/BaseType.php',
        'Sanatorium\\Inputs\\Types\\CategoryType' => __DIR__ . '/../..' . '/src/Types/CategoryType.php',
        'Sanatorium\\Inputs\\Types\\DateType' => __DIR__ . '/../..' . '/src/Types/DateType.php',
        'Sanatorium\\Inputs\\Types\\DropzoneType' => __DIR__ . '/../..' . '/src/Types/DropzoneType.php',
        'Sanatorium\\Inputs\\Types\\EmailType' => __DIR__ . '/../..' . '/src/Types/EmailType.php',
        'Sanatorium\\Inputs\\Types\\FileType' => __DIR__ . '/../..' . '/src/Types/FileType.php',
        'Sanatorium\\Inputs\\Types\\GalleryType' => __DIR__ . '/../..' . '/src/Types/GalleryType.php',
        'Sanatorium\\Inputs\\Types\\ImageType' => __DIR__ . '/../..' . '/src/Types/ImageType.php',
        'Sanatorium\\Inputs\\Types\\MediaType' => __DIR__ . '/../..' . '/src/Types/MediaType.php',
        'Sanatorium\\Inputs\\Types\\PhoneType' => __DIR__ . '/../..' . '/src/Types/PhoneType.php',
        'Sanatorium\\Inputs\\Types\\RelationType' => __DIR__ . '/../..' . '/src/Types/RelationType.php',
        'Sanatorium\\Inputs\\Types\\RepeaterType' => __DIR__ . '/../..' . '/src/Types/RepeaterType.php',
        'Sanatorium\\Inputs\\Types\\ScaleType' => __DIR__ . '/../..' . '/src/Types/ScaleType.php',
        'Sanatorium\\Inputs\\Types\\SwitcheryType' => __DIR__ . '/../..' . '/src/Types/SwitcheryType.php',
        'Sanatorium\\Inputs\\Types\\TruefalseType' => __DIR__ . '/../..' . '/src/Types/TruefalseType.php',
        'Sanatorium\\Inputs\\Types\\UrlType' => __DIR__ . '/../..' . '/src/Types/UrlType.php',
        'Sanatorium\\Inputs\\Types\\VideoType' => __DIR__ . '/../..' . '/src/Types/VideoType.php',
        'Sanatorium\\Inputs\\Types\\WysiwygType' => __DIR__ . '/../..' . '/src/Types/WysiwygType.php',
        'Sanatorium\\Inputs\\Validator\\Group\\GroupValidator' => __DIR__ . '/../..' . '/src/Validator/Group/GroupValidator.php',
        'Sanatorium\\Inputs\\Validator\\Group\\GroupValidatorInterface' => __DIR__ . '/../..' . '/src/Validator/Group/GroupValidatorInterface.php',
        'Sanatorium\\Inputs\\Widgets\\Display' => __DIR__ . '/../..' . '/src/Widgets/Display.php',
        'Sanatorium\\Inputs\\Widgets\\Entity' => __DIR__ . '/../..' . '/src/Widgets/Entity.php',
        'Sanatorium\\Inputs\\Widgets\\Group' => __DIR__ . '/../..' . '/src/Widgets/Group.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc01d2ac0268d4efa8d09a78118709455::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc01d2ac0268d4efa8d09a78118709455::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc01d2ac0268d4efa8d09a78118709455::$classMap;

        }, null, ClassLoader::class);
    }
}
