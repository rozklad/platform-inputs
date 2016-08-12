<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc01d2ac0268d4efa8d09a78118709455
{
    public static $files = array (
        '721e0494efc3fe7485b0bddad955c9c5' => __DIR__ . '/../..' . '/src/helpers.php',
    );

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