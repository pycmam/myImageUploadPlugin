<?php


/**
 * Template
 */
class Doctrine_Template_SkipColumns extends Doctrine_Template
{
    public function setTableDefinition()
    {
        $this->addListener(new Doctrine_Template_SkipColumns_Listener($this->_options));
    }
}


/**
 * Listener
 */
class Doctrine_Template_SkipColumns_Listener extends Doctrine_Record_Listener
{
    /**
     * Construct
     *
     * @param  array $options
     * @return void
     */
    public function __construct(array $options)
    {
        $this->_options = $options;
    }


    /**
     * Pre SELECT
     */
    public function preDqlSelect(Doctrine_Event $event)
    {
        $query = $event->getQuery();

        $params = $event->getParams();
        $alias = $params['alias'];

        $limit = $query->getDqlPart('limit');
        $limit = count($limit) ? (int) $limit[0] : 0;

        // Если не указан LIMIT 1
        // Если не указан SELECT
        // Если это не подзапрос
        if (1 != $limit && !$query->getDqlPart('select') && !$query->isSubquery()) {

            $skip = $this->getOption('skip');
            $columns = $event->getInvoker()->getTable()->getColumnNames();
            $columns = array_diff($columns, $skip);

            $select = $alias.'.'.join(', '.$alias.'.', $columns);
            $query->select($select);
        }
    }

}
