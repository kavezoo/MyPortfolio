<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class ChangePagesMetaDescriptionToText extends BaseMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        $this->table('pages')
            ->changeColumn('meta_description', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->update();
    }
}
