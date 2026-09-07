<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Behavior\Translate\EavStrategy;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;

/**
 * Base table with Timestamp and the shared visible/pos finder.
 */
class AppTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
    }

    /**
     * Attach CakePHP Translate behavior using the classic i18n EAV table.
     *
     * @param array<string> $fields Fields stored in the i18n table for non-default locales.
     * @return void
     */
    protected function addTranslate(array $fields): void
    {
        $this->addBehavior('Translate', [
            'strategyClass' => EavStrategy::class,
            'fields' => $fields,
            'defaultLocale' => (string)Configure::read('App.defaultLocale', 'hu_HU'),
            'allowEmptyTranslations' => false,
        ]);
    }

    /**
     * Finder for published records ordered by pos.
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findVisible(SelectQuery $query): SelectQuery
    {
        $alias = $this->getAlias();

        return $query
            ->where(["{$alias}.visible" => true])
            ->orderBy(["{$alias}.pos" => 'ASC', "{$alias}.id" => 'ASC']);
    }
}
