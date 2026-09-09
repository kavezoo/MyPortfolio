<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddOriginalNameToPhotos extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->table('photos')
            ->addColumn('original_name', 'string', [
                'after' => 'code',
                'default' => null,
                'limit' => 100,
                'null' => true,
                'comment' => 'Original upload basename (often a serial number), shown in the photo viewer',
            ])
            ->addIndex(['original_name'])
            ->update();

        $rows = $this->fetchAll('SELECT id, code, filename FROM photos');
        foreach ($rows as $row) {
            $name = trim((string)($row['code'] ?? ''));
            if ($name === '') {
                $filename = str_replace('\\', '/', (string)($row['filename'] ?? ''));
                $base = pathinfo($filename, PATHINFO_FILENAME);
                // Prefer legacy seed names over numeric storage id when possible.
                if ($base !== '' && !preg_match('/^\d+$/', $base)) {
                    $name = $base;
                } elseif ($base !== '') {
                    $name = $base;
                } else {
                    $name = (string)$row['id'];
                }
            }

            $this->execute(sprintf(
                "UPDATE photos SET original_name = '%s' WHERE id = %d",
                addslashes($name),
                (int)$row['id']
            ));
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->table('photos')
            ->removeIndex(['original_name'])
            ->removeColumn('original_name')
            ->update();
    }
}
