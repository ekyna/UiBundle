<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FaIconsCommand
 * @package Ekyna\Bundle\UiBundle\Command
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FaIconsCommand extends Command
{
    protected static $defaultName = 'ekyna:ui:fa-icons';

    private string $projectDirectory;
    private array  $exclude = ['fa', 'font-awesome'];


    public function __construct(string $projectDirectory)
    {
        parent::__construct();

        $this->projectDirectory = $projectDirectory;
    }

    protected function configure(): void
    {
        $this->setDescription('Build the UiBundle font awesome icons constants class.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $iconNames = $this->gatherIconsNames();

        $content = $this->dumpConstantFile($iconNames, $output);

        $this->writeConstantFile($content, $output);

        return Command::SUCCESS;
    }

    /**
     * Gather the icon names.
     */
    private function gatherIconsNames(): array
    {
        $path = $this->projectDirectory . '/node_modules/font-awesome/less/icons.less';

        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new \RuntimeException("Failed to open $path.");
        }

        $icons = [];

        while (false !== $line = fgets($handle)) {
            if (preg_match('~\{fa-css-prefix\}-([a-z-]+):before~', $line, $matches)) {
                $icons[] = $matches[1];
            }
        }

        return array_diff($icons, $this->exclude);
    }

    /**
     * Dumps the constants file.
     */
    private function dumpConstantFile(array $iconNames, OutputInterface $output): ?string
    {
        if (empty($iconNames)) {
            $output->writeln("Abort as no icon found.");

            return null;
        }

        $content = <<<EOT
<?php

namespace Ekyna\Bundle\UiBundle\Model;

use Ekyna\Bundle\ResourceBundle\Model\AbstractConstants;

/**
 * Class FAIcons
 * @package Ekyna\Bundle\UiBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class FAIcons extends AbstractConstants
{
    private static \$config = {$this->generateConfig($iconNames)};
    
    /**
     * @inheritDoc
     */
    public static function getConfig()
    {
        return static::\$config;
    }
}

EOT;

        return $content;
    }

    /**
     * Generates the icon constants class's config.
     *
     * @param array $iconNames
     *
     * @return string
     */
    private function generateConfig(array $iconNames): string
    {
        $output = "[\n";

        foreach ($iconNames as $name) {
            $output .= "        '$name' => ['{$this->humanize($name)}'],\n";
        }

        $output .= '    ]';

        return $output;
    }

    /**
     * Makes the icon name human readable.
     *
     * @param string $text
     *
     * @return string
     */
    private function humanize($text)
    {
        return ucfirst(trim(str_replace('-', ' ', $text)));
    }

    /**
     * Writes the icon constants class file.
     *
     * @param string          $content
     * @param OutputInterface $output
     */
    private function writeConstantFile(string $content, OutputInterface $output): void
    {
        if (empty($content)) {
            $output->writeln('Abort as empty content.');

            return;
        }

        $path = $this->projectDirectory . '/src/Ekyna/Bundle/UiBundle/Model/FAIcons.php';

        $filesystem = new Filesystem();

        if (file_exists($path)) {
            $output->writeln('Backing up the old constant file.');
            $filesystem->rename($path, $path . '.backup', true);
        }

        $output->writeln('Writing the new constant file.');
        $filesystem->dumpFile($path, $content);
    }
}

