<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Countries;

use function count;
use function dirname;
use function file_put_contents;
use function imagecopy;
use function imagecreatetruecolor;
use function imagedestroy;
use function imagepng;

/**
 * Class CountryFlagsCommand
 * @package Ekyna\Bundle\UiBundle\Command
 * @author  Etienne Dauvergne <contact@ekyna.com>
 *
 * @see     https://github.com/madebybowtie/FlagKit
 */
class CountryFlagsCommand extends Command
{
    protected static $defaultName = 'ekyna:ui:country-flags';

    private const DIST_FLAG = 'https://github.com/madebybowtie/FlagKit/raw/master/Assets/PNG/%s.png?raw=true';


    protected function configure(): void
    {
        $this
            ->setDescription('Downloads the country flags')
            ->addOption('less', null, InputOption::VALUE_NONE, 'Whether to generate less file only');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lessOnly = $input->getOption('less');

        $lessPath = dirname(__DIR__) . '/Resources/private/less/flags.less';
        $pngPath = dirname(__DIR__) . '/Resources/private/img/flags.png';

        $width = 21;
        $height = 15;
        $columns = $rows = 16;

        $png = imagecreatetruecolor($width * $columns, $height * $rows);

        $less = ".country-flag {\n";
        $less .= "    background: url('../img/flags.png') no-repeat 0 0 scroll;\n";
        $less .= "    display: inline-block;\n";
        $less .= "    height: 15px;\n";
        $less .= "    width: 21px;\n";


        $countries = Countries::getNames();
        $failures = [];

        $progressBar = new ProgressBar($output, count($countries));
        $progressBar->setMessage('Start');
        $progressBar->start();

        if (!$lessOnly) {
            $flag = $this->openFlag('none');
            imagecopy($png, $flag, 0, 0, 0, 0, $width, $height);
            imagedestroy($flag);
        }

        $c = 1;
        $r = 0;
        foreach ($countries as $code => $country) {
            $progressBar->setMessage($country);

            $x = $c * $width;
            $y = $r * $height;

            if (!$lessOnly) {
                if ($flag = $this->openFlag($code)) {
                    imagecopy($png, $flag, $x, $y, 0, 0, $width, $height);
                    imagedestroy($flag);
                } else {
                    $failures[$code] = $country;
                }
            }

            $less .= sprintf(
                "    &.%s { background-position: %s %s; }\n",
                strtolower($code),
                $x > 0 ? "-{$x}px" : '0',
                $y > 0 ? "-{$y}px" : '0'
            );

            $c++;
            if ($c > $columns - 1) {
                $c = 0;
                $r++;
            }

            $progressBar->advance();
        }

        $less .= "}\n";

        $progressBar->finish();
        $output->writeln('');

        // LESS
        $output->write('Writing LESS file ... ');
        if (file_put_contents($lessPath, $less)) {
            $output->writeln('<info>done</info>');
        } else {
            $output->writeln('<error>failure</error>');
        }
        $output->writeln('');

        // PNG
        if (!$lessOnly) {
            $output->write('Writing PNG file ... ');
            if (imagepng($png, $pngPath, 0)) {
                $output->writeln('<info>done</info>');
            } else {
                $output->writeln('<error>failure</error>');
            }
            $output->writeln('');

            imagedestroy($png);
        }


        if (empty($failures)) {
            return Command::SUCCESS;
        }

        $output->writeln('Failures:');
        foreach ($failures as $code => $country) {
            $output->writeln(sprintf('- [%s] %s', $code, $country));
        }

        return Command::FAILURE;
    }

    /**
     * Opens the flag image.
     *
     * @param string $code
     *
     * @return false|resource
     */
    private function openFlag(string $code)
    {
        if ($flag = imagecreatefrompng(sprintf(self::DIST_FLAG, $code))) {
            return $flag;
        }

        if ($flag = imagecreatefrompng(dirname(__DIR__) . "/Resources/private/flags/$code.png")) {
            return $flag;
        }

        return false;
    }
}
