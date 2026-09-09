<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\TestCase;

/**
 * PagesControllerTest class
 *
 * The public site pages are loaded from the database, so the original
 * CakePHP skeleton display() tests no longer apply.
 */
class PagesControllerTest extends TestCase
{
    /**
     * Placeholder until fixtures cover the portfolio schema.
     *
     * @return void
     */
    public function testHomeRequiresDatabase(): void
    {
        $this->markTestSkipped('A nyilvános oldalak adatbázisból töltődnek; fixture-ök később készülnek.');
    }
}
