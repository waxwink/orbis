<?php

namespace Waxwink\Orbis\Console;

use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Spinner;

/**
 * @method \League\CLImate\CLImate black(string $str = null)
 * @method \League\CLImate\CLImate red(string $str = null)
 * @method \League\CLImate\CLImate green(string $str = null)
 * @method \League\CLImate\CLImate yellow(string $str = null)
 * @method \League\CLImate\CLImate blue(string $str = null)
 * @method \League\CLImate\CLImate magenta(string $str = null)
 * @method \League\CLImate\CLImate cyan(string $str = null)
 * @method \League\CLImate\CLImate lightGray(string $str = null)
 * @method \League\CLImate\CLImate darkGray(string $str = null)
 * @method \League\CLImate\CLImate lightRed(string $str = null)
 * @method \League\CLImate\CLImate lightGreen(string $str = null)
 * @method \League\CLImate\CLImate lightYellow(string $str = null)
 * @method \League\CLImate\CLImate lightBlue(string $str = null)
 * @method \League\CLImate\CLImate lightMagenta(string $str = null)
 * @method \League\CLImate\CLImate lightCyan(string $str = null)
 * @method \League\CLImate\CLImate white(string $str = null)
 *
 * @method \League\CLImate\CLImate backgroundBlack(string $str = null)
 * @method \League\CLImate\CLImate backgroundRed(string $str = null)
 * @method \League\CLImate\CLImate backgroundGreen(string $str = null)
 * @method \League\CLImate\CLImate backgroundYellow(string $str = null)
 * @method \League\CLImate\CLImate backgroundBlue(string $str = null)
 * @method \League\CLImate\CLImate backgroundMagenta(string $str = null)
 * @method \League\CLImate\CLImate backgroundCyan(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightGray(string $str = null)
 * @method \League\CLImate\CLImate backgroundDarkGray(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightRed(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightGreen(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightYellow(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightBlue(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightMagenta(string $str = null)
 * @method \League\CLImate\CLImate backgroundLightCyan(string $str = null)
 * @method \League\CLImate\CLImate backgroundWhite(string $str = null)
 *
 * @method \League\CLImate\CLImate bold(string $str = null)
 * @method \League\CLImate\CLImate dim(string $str = null)
 * @method \League\CLImate\CLImate underline(string $str = null)
 * @method \League\CLImate\CLImate blink(string $str = null)
 * @method \League\CLImate\CLImate invert(string $str = null)
 * @method \League\CLImate\CLImate hidden(string $str = null)
 *
 * @method \League\CLImate\CLImate info(string $str = null)
 * @method \League\CLImate\CLImate comment(string $str = null)
 * @method \League\CLImate\CLImate whisper(string $str = null)
 * @method \League\CLImate\CLImate shout(string $str = null)
 * @method \League\CLImate\CLImate error(string $str = null)
 *
 * @method mixed out(string $str)
 * @method mixed inline(string $str)
 * @method mixed table(array $data)
 * @method mixed json(mixed $var)
 * @method mixed br($count = 1)
 * @method mixed tab($count = 1)
 * @method mixed draw(string $art)
 * @method mixed border(string $char = null, integer $length = null)
 * @method mixed dump(mixed $var)
 * @method mixed flank(string $output, string $char = null, integer $length = null)
 * @method mixed progress(integer $total = null)
 * @method Spinner spinner(string $label = null, string ...$characters = null)
 * @method mixed padding(integer $length = 0, string $char = '.')
 * @method mixed input(string $prompt, \League\CLImate\Util\Reader\ReaderInterface $reader = null)
 * @method mixed confirm(string $prompt, \League\CLImate\Util\Reader\ReaderInterface $reader = null)
 * @method mixed password(string $prompt, \League\CLImate\Util\Reader\ReaderInterface $reader = null)
 * @method mixed checkboxes(string $prompt, array $options, \League\CLImate\Util\Reader\ReaderInterface $reader = null)
 * @method mixed radio(string $prompt, array $options, \League\CLImate\Util\Reader\ReaderInterface $reader = null)
 * @method mixed animation(string $art, \League\CLImate\TerminalObject\Helper\Sleeper $sleeper = null)
 * @method mixed columns(array $data, $column_count = null)
 * @method mixed clear()
 * @method \League\CLImate\CLImate clearLine()
 *
 * @method \League\CLImate\CLImate addArt(string $dir)
 */
trait HasClimate
{
    protected ?CLImate $CLImate = null;

    public function __call(string $name, array $arguments)
    {
        if (!$this->CLImate) {
            $this->CLImate = new CLImate();
        }

        $this->CLImate->{$name}(...$arguments);
    }
}
