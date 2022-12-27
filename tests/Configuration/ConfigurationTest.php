<?php

namespace Waxwink\Orbis\Tests\Configuration;

use Waxwink\Orbis\Configuration\Configuration;
use Waxwink\Orbis\Configuration\ConfigurationException;

class ConfigurationTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function testCanResolveConfigKeys(): void
    {
        $configArray = [
            "a" => ["b" => ["c" => "d"]],
            "e" => ["f" => ["g" => "h"], "i" => ["j" => "k"]],
        ];
        $configuration = new Configuration($configArray);

        $this->assertEquals("k", $configuration->get("e.i.j"));
        $this->assertEquals("d", $configuration->get("a.b.c"));
        $this->assertEquals(["c" => "d"], $configuration->get("a.b"));
    }

    public function testCanResolveConfigKeysFromConfigFile(): void
    {
        $configArray = [
            "a" => ["b" => ["c" => "d"]],
            "e" => ["f" => ["g" => "h"], "i" => ["j" => "k"]],
            "configPath" => __DIR__
        ];
        $configuration = new Configuration($configArray);

        $this->assertEquals("k", $configuration->get("e.i.j"));
        $this->assertEquals("d", $configuration->get("a.b.c"));
        $this->assertEquals(["c" => "d"], $configuration->get("a.b"));
        $this->assertEquals("cc", $configuration->get("test.aa.bb"));
    }

    public function testIfAConfigFileIsNotIncludingAnArrayThenAnExceptionWouldBeThrown()
    {
        $configArray = [
            "a" => ["b" => ["c" => "d"]],
            "configPath" => __DIR__
        ];
        $configuration = new Configuration($configArray);

        $this->expectException(ConfigurationException::class);

        $configuration->get("invalidConfig.aa.bb");
    }
}
