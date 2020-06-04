<?php
use PHPUnit\Framework\TestCase;
use CardValidDetect\ValidDetect;


class ValidDetectTest extends TestCase
{
    private $ValidDetect ;

    public function setUp(): void
    {
        parent::setUp();
        $this->ValidDetect = new ValidDetect;
    }

    public function additionProvider()
    {
        return [
            ['VISA', '4916847576752405'],
            ['VISA', '4485881267490075'],
            ['VISA', '4532271541955778629'],
        ];
    }

    /**
     * @dataProvider additionProvider
     */
    public function testPushAndPop($expected, $card)
    {
        $this->assertSame($expected, $this->ValidDetect->detect($card));
    }
}