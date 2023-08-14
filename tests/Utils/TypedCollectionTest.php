<?php
declare(strict_types=1);

namespace ApiCoreTest\Utils;

use ApiCore\Utils\TypedCollection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TypedCollectionTest extends TestCase
{
    #[Test]
    public function add(): void
    {
        $collection = new TypedCollection();

        $collection->add('test1')->add('test2')->add('test3');

        $i = 1;
        foreach ($collection as $value) {
            $this->assertEquals('test'. $i, $value);
            $i++;
        }
    }
}
