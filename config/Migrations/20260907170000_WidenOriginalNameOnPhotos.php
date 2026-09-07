<?php
declare(strict_types=1);

use Migrations\BaseMigration;

/**
 * Store/show full original filename (with extension) and widen the column.
 */
class WidenOriginalNameOnPhotos extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->table('photos')
            ->changeColumn('original_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'comment' => 'Original upload filename (e.g. IMG_4821.jpg), shown in the photo viewer',
            ])
            ->update();

        $rows = $this->fetchAll('SELECT id, original_name, filename FROM photos');
        foreach ($rows as $row) {
            $name = trim((string)($row['original_name'] ?? ''));
            $stored = str_replace('\\', '/', (string)($row['filename'] ?? ''));
            $ext = strtolower(pathinfo($stored, PATHINFO_EXTENSION));

            if ($name === '') {
                $base = pathinfo($stored, PATHINFO_FILENAME);
                $name = $base !== '' ? $base : (string)$row['id'];
            }

            // Append extension when missing so the viewer shows a real file name.
            if ($ext !== '' && !str_contains($name, '.')) {
                $name .= '.' . $ext;
            }

            $this->execute(sprintf(
                "UPDATE photos SET original_name = '%s' WHERE id = %d",
                addslashes(mb_substr($name, 0, 255)),
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
            ->changeColumn('original_name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->update();
    }
}
