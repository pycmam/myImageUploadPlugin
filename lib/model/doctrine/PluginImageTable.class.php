<?php
/**
 */
class PluginImageTable extends Doctrine_Table
{
    /**
     * Переместить основную картинку в начало списка
     *
     * @param Doctrine_Query $q
     * @param Doctrine_Record $object
     * @return Doctrine_Query
     */
    public function withPrimaryOrder(Doctrine_Query $q = null, Doctrine_Record $object)
    {
        if (is_null($q)) {
            $q = $this->createQuery();
        }

        $alias = $q->getRootAlias();

        if ($object->getImageId()) {
            $q->orderBy(sprintf('FIELD(id, %d) DESC', $object->getImageId()));
        }

        return $q;
    }
}