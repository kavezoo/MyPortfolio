<?php
declare(strict_types=1);

use Migrations\BaseMigration;

/**
 * PhotoCategories i18n rows were seeded with short language codes (en/de/…).
 * Translate behavior and admin tabs use full locales (en_GB/de_DE/…).
 */
class NormalizePhotoCategoryI18nLocales extends BaseMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $map = [
            'en' => 'en_GB',
            'de' => 'de_DE',
            'fr' => 'fr_FR',
            'it' => 'it_IT',
        ];

        foreach ($map as $from => $to) {
            $this->execute(
                sprintf(
                    "UPDATE i18n SET locale = '%s' WHERE model = 'PhotoCategories' AND locale = '%s'",
                    $to,
                    $from
                )
            );
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $map = [
            'en_GB' => 'en',
            'de_DE' => 'de',
            'fr_FR' => 'fr',
            'it_IT' => 'it',
        ];

        foreach ($map as $from => $to) {
            $this->execute(
                sprintf(
                    "UPDATE i18n SET locale = '%s' WHERE model = 'PhotoCategories' AND locale = '%s'",
                    $to,
                    $from
                )
            );
        }
    }
}
