<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\PhotoFileService;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Move legacy /img/{name}.ext photo files into img/uploads/YEAR/MONTH/{id}.ext
 * and update photos.filename accordingly.
 */
class MigratePhotoUploadsCommand extends Command
{
    /**
     * @param \Cake\Console\ConsoleOptionParser $parser Parser.
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->setDescription('Copy legacy photo files into uploads/YEAR/MONTH/{id}.ext');
    }

    /**
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io IO.
     * @return int
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $files = new PhotoFileService();
        $photos = $this->fetchTable('Photos')->find()->all();
        $moved = 0;
        $skipped = 0;

        foreach ($photos as $photo) {
            $filename = str_replace('\\', '/', (string)$photo->filename);
            if ($filename === '' || $filename === 'pending' || str_starts_with($filename, 'uploads/')) {
                $skipped++;
                continue;
            }

            $source = WWW_ROOT . 'img' . DS . basename($filename);
            if (!is_file($source)) {
                $io->warning("Missing file for photo #{$photo->id}: {$source}");
                $skipped++;
                continue;
            }

            $relative = $files->importExistingFile((int)$photo->id, $source);
            $photo->filename = $relative;
            $this->fetchTable('Photos')->saveOrFail($photo);
            $io->out("Photo #{$photo->id} → {$relative}");
            $moved++;
        }

        $io->success("Moved {$moved} file(s), skipped {$skipped}.");

        return static::CODE_SUCCESS;
    }
}
