<?php namespace Sanatorium\Inputs\Widgets;

use Platform\Attributes\Widgets\Entity as BaseEntity;

/**
 * Part of the Platform Attributes extension.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Platform Attributes extension
 * @version    3.2.1
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Repositories\ManagerRepositoryInterface;
use Platform\Attributes\Repositories\AttributeRepositoryInterface;

class Entity extends BaseEntity
{

    /**
     * Show the entity form.
     *
     * @param  \Cartalyst\Attributes\EntityInterface  $entity
     * @param  string|array  $attribute
     * @param  string  $view
     * @return \Illuminate\View\View|null
     */
    public function show(EntityInterface $entity, $attributes = null, $view = null)
    {
        $namespace = $entity->getEntityNamespace();

        $query = $entity->newAttributeModel()->whereNamespace($namespace);

        if ($attributes) {
            if (is_array($attributes) && ! empty($attributes)) {
                $attributes = $query->whereNotIn('slug', $attributes)->get();

                return $this->renderForm($entity, $namespace, $attributes, $view);
            }

            $attribute = $query->whereSlug($attributes)->first();

            if (!  $attribute) {
                return null;
            }

            if ($view) {
                return view($view, compact('entity', 'attribute'));
            }

            return $this->manager->getEntityFormHtml($attribute, $entity);
        }

        return $this->renderForm(
            $entity, $namespace, $query->get(), $view
        );
    }

}
