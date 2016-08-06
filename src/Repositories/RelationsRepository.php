<?php namespace Sanatorium\Inputs\Repositories;


class RelationsRepository implements RelationsRepositoryInterface {

    /**
     * Array of registered namespaces.
     *
     * @var array
     */
    protected $relations = [];

    /**
     * {@inheritDoc}
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * {@inheritDoc}
     */
    public function registerRelation($key, $relation)
    {
        $this->relations[$key] = $relation;
    }

}
