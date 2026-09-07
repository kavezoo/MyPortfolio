<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

/**
 * Seed / refresh i18n rows for page menu labels and hero texts.
 */
class SeedPageTranslationsCommand extends Command
{
    /**
     * @param \Cake\Console\ConsoleOptionParser $parser Parser.
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->setDescription('Insert page content translations into the i18n table');
    }

    /**
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io IO.
     * @return int
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $translations = require CONFIG . 'Seeds' . DS . 'data' . DS . 'page_translations.php';
        $pages = $this->fetchTable('Pages')->find()->all()->combine('slug', 'id')->toArray();
        $connection = ConnectionManager::get('default');
        $count = 0;

        foreach ($translations as $locale => $bySlug) {
            foreach ($bySlug as $slug => $fields) {
                $pageId = $pages[$slug] ?? null;
                if (!$pageId) {
                    $io->warning("Missing page slug: {$slug}");
                    continue;
                }

                foreach ($fields as $field => $content) {
                    $connection->execute(
                        'DELETE FROM i18n WHERE locale = :locale AND model = :model AND foreign_key = :fk AND field = :field',
                        [
                            'locale' => $locale,
                            'model' => 'Pages',
                            'fk' => $pageId,
                            'field' => $field,
                        ]
                    );
                    $connection->insert('i18n', [
                        'locale' => $locale,
                        'model' => 'Pages',
                        'foreign_key' => $pageId,
                        'field' => $field,
                        'content' => $content,
                    ]);
                    $count++;
                }
            }
        }

        $io->success("Wrote {$count} i18n row(s) for Pages.");

        return static::CODE_SUCCESS;
    }
}
