<?php

use Helpers\StringsHelper;

class NameStringTest extends TestCase
{

    /** @test */
    public function includes_title()
    {
        $name = 'Mrs. Jo';

        $this->assertEquals('Jo', StringsHelper::firstName($name));
        $this->assertEquals('Mrs', StringsHelper::title($name));
    }

    /** @test */
    public function middle_name()
    {
        $name = 'Rev Jo Grace Cavill';

        $this->assertEquals('Rev', StringsHelper::title($name));
        $this->assertEquals('Jo', StringsHelper::firstName($name));
        $this->assertEquals('Cavill', StringsHelper::lastName($name));
    }

    /** @test */
    public function single_word()
    {
        $name = 'Jo';

        $this->assertEquals('Jo', StringsHelper::firstName($name));
        $this->assertEquals('', StringsHelper::lastName($name));
        $this->assertEquals('', StringsHelper::title($name));
    }

    /** @test */
    public function double_word()
    {
        $name = 'Jo Cavill';

        $this->assertEquals('Jo', StringsHelper::firstName($name));
        $this->assertEquals('Cavill', StringsHelper::lastName($name));
        $this->assertEquals('', StringsHelper::title($name));
    }

    /** @test */
    public function double_barrelled()
    {
        $name = 'Jo Charger-Cavill';

        $this->assertEquals('Jo', StringsHelper::firstName($name));
        $this->assertEquals('Charger-Cavill', StringsHelper::lastName($name));
        $this->assertEquals('', StringsHelper::title($name));
    }
}

?>
