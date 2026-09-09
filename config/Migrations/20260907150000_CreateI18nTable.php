<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateI18nTable extends BaseMigration
{
    /**
     * Change Method.
     *
     * @return void
     */
    public function change(): void
    {
        $this->table('i18n')
            ->addColumn('locale', 'string', [
                'default' => null,
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('field', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(
                ['locale', 'model', 'foreign_key', 'field'],
                ['unique' => true, 'name' => 'I18N_LOCALE_FIELD']
            )
            ->addIndex(
                ['model', 'foreign_key', 'field'],
                ['name' => 'I18N_FIELD']
            )
            ->create();
    }
}
