<?php declare(strict_types=1);

namespace XBase\Tests;

use XBase\Enum\TableType;
use XBase\Record\DBaseRecord;
use XBase\Table;

class DbfTest extends AbstractTestCase
{
    public function test2ByteHeaderTerminator(): void
    {
        $table = new Table(__DIR__.'/Resources/dbf/cbrf_122019N1.dbf', null, 'cp866');

        self::assertSame(TableType::DBASE_III_PLUS_NOMEMO, $table->getVersion());
        self::assertSame(442, $table->getRecordCount());

        self::assertSame([1, 'АО ЮниКредит Банк', 1, 1], array_values($table->nextRecord()->getData()));
        self::assertSame([1000, 'Банк ВТБ (ПАО)', 1, 1], array_values($table->nextRecord()->getData()));
        self::assertSame([990, 'ООО КБ "Дружба"', 1, 1], array_values($table->pickRecord(441)->getData()));
    }

    public function testIssue88(): void
    {
        $table = new Table(__DIR__.'/Resources/dbf/cbrf_122019N1.dbf', null, 'cp866');
        /** @var DBaseRecord $record */
        $record = $table->nextRecord();
        self::assertSame(
            [
                'regn'   => 1,
                'name_b' => 'АО ЮниКредит Банк',
                'priz'   => 1,
                'priz_p' => 1,
            ],
            $record->getData()
        );
    }
}
