<?php

namespace Waxwink\Orbis\CommonCommands;

use Waxwink\Orbis\Console\Command;
use Waxwink\Orbis\Console\HasClimate;
use Waxwink\Orbis\Contracts\ContainerInterface;

class ContainerList extends Command
{
    use HasClimate;

    public const NAME = "container:list";

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function __invoke()
    {
        $registered = $this->container->all();

        $list = [];
        foreach ($registered as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            if (!is_string($value)) {
                $value = get_class($value);
            }
            if ($key === $value) {
                continue;
            }

            $list[] =["key" => $key, "implementation" => $value];
        }

        $this->table($list);

        //
//        $climate = new CLImate;
//
//        $climate->bold()->red("USAGE:");
//        $climate->tab()->out(static::name() . "  <ARGUMENTS> <OPTIONS>");
//        $climate->bold()->red("ARGUMENTS:");
//        $climate->tab()->lightGreen()->inline("name");
//        $climate->tab()->tab()->out("name of the person");
//
//        $climate->bold()->red("OPTIONS:");
//        $climate->tab()->lightGreen()->inline("-v");
//        $climate->tab()->tab()->out("version");
//        $climate->tab()->lightGreen()->inline("--help");
//        $climate->tab()->tab()->out("for help");
//        $climate->tab()->lightGreen()->inline("--env");
//        $climate->tab()->tab()->out("for environment");
    }
}
