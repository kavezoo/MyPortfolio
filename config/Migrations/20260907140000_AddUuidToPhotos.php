<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddUuidToPhotos extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->table('photos')
            ->addColumn('uuid', 'char', [
                'after' => 'id',
                'default' => null,
                'limit' => 36,
                'null' => true,
            ])
            ->addIndex(['uuid'], ['unique' => true])
            ->update();

        $rows = $this->fetchAll('SELECT id FROM photos');
        foreach ($rows as $row) {
            $uuid = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                random_int(0, 0xffff),
                random_int(0, 0xffff),
                random_int(0, 0xffff),
                random_int(0, 0x0fff) | 0x4000,
                random_int(0, 0x3fff) | 0x8000,
                random_int(0, 0xffff),
                random_int(0, 0xffff),
                random_int(0, 0xffff)
            );
            $this->execute(
                sprintf(
                    "UPDATE photos SET uuid = '%s' WHERE id = %d",
                    $uuid,
                    (int)$row['id']
                )
            );
        }

        $this->table('photos')
            ->changeColumn('uuid', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->update();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->table('photos')
            ->removeIndex(['uuid'])
            ->removeColumn('uuid')
            ->update();
    }
}
