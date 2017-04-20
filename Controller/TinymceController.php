<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Controller;

use Exception;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use UnexpectedValueException;

use function array_values;
use function current;
use function explode;
use function fclose;
use function file_exists;
use function fopen;
use function in_array;
use function is_array;
use function is_resource;
use function md5;
use function preg_match;
use function preg_replace;
use function strtolower;
use function time;
use function trim;
use function uniqid;

/**
 * Class TinymceController
 * @package Ekyna\Bundle\UiBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TinymceController
{
    private const LOCALES = [
        'bn' => 'bn_BD',
        'bg' => 'bg_BG',
        'cn' => 'zh_CN',
        'fr' => 'fr_FR',
        'hu' => 'hu_HU',
        'il' => 'he_IL',
        'is' => 'is_IS',
        'sl' => 'sl_SI',
        'tr' => 'tr_TR',
        'tw' => 'zh_TW',
        'uk' => 'uk_UA',
    ];

    private AuthorizationCheckerInterface $authorization;
    private Packages                      $packages;
    private Filesystem                    $filesystem;
    private array                         $tinymceConfig;

    public function __construct(
        AuthorizationCheckerInterface $authorization,
        Packages                      $packages,
        Filesystem                    $filesystem,
        array                         $tinymceConfig
    ) {
        $this->authorization = $authorization;
        $this->packages = $packages;
        $this->filesystem = $filesystem;
        $this->tinymceConfig = $tinymceConfig;
    }

    /**
     * Returns the tinymce configuration.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function config(Request $request): Response
    {
        $response = new JsonResponse($this->buildTinymceConfig($request));

        $response
            ->setPublic()
            ->setMaxAge(3600 * 24 * 30)
            ->setSharedMaxAge(3600 * 24 * 30);

        return $response;
    }

    /**
     * Tinymce upload action.
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function upload(Request $request): Response
    {
        if (!$this->authorization->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        }

        // https://www.tinymce.com/docs/advanced/handle-async-image-uploads/

        $file = current($request->files->all());

        if (!$file instanceof UploadedFile) {
            throw new UnexpectedValueException('Expected instance of ' . UploadedFile::class);
        }

        // Check file
        if (!$file->isValid()) {
            throw new Exception('Invalid file.');
        }

        // Open uploaded file
        if (false === $stream = fopen($file->getRealPath(), 'r+')) {
            throw new Exception('Failed to open file.');
        }

        // Verify extension
        $extension = strtolower($file->guessExtension());
        if (!in_array($extension, ['gif', 'jpg', 'jpeg', 'png'])) {
            throw new Exception('Invalid extension.');
        }

        $filename = $file->getClientOriginalName();
        if (!preg_match('~([a-zA-Z0-9]{32}\.(gif|png|jpe?g))~', $filename)) {
            // New file name
            $filename = strtolower(md5(time() . uniqid())) . '.' . $extension;
        }

        // Write new file
        try {
            if ($this->filesystem->fileExists($filename)) {
                $this->filesystem->delete($filename);
            }

            $this->filesystem->writeStream($filename, $stream);
        } catch (FilesystemException $e) {
            return new JsonResponse([
                'error' => 'Failed to upload ' . $filename,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return new JsonResponse([
            'location' => '/tinymce/' . $filename,
        ]);
    }

    /**
     * Builds the tinymce config.
     *
     * @param Request $request
     *
     * @return array
     */
    private function buildTinymceConfig(Request $request): array
    {
        $config = $this->tinymceConfig;

        // Get local button's image
        /*foreach ($config['tinymce_buttons'] as &$customButton) {
            if ($customButton['image']) {
                $customButton['image'] = $this->getAssetsUrl($customButton['image']);
            } else {
                unset($customButton['image']);
            }

            if ($customButton['icon']) {
                $customButton['icon'] = $this->getAssetsUrl($customButton['icon']);
            } else {
                unset($customButton['icon']);
            }
        }*/

        // Update URL to external plugins
        /*foreach ($config['external_plugins'] as &$extPlugin) {
            $extPlugin['url'] = $this->getAssetsUrl($extPlugin['url']);
        }*/

        // If the language is not set in the config...
        if (!isset($config['language']) || empty($config['language'])) {
            // get it from the request
            $config['language'] = $request->getLocale();
        }

        $config['language'] = $this->getLanguage($config['language']);

        $langDirectory = __DIR__ . '/../Resources/public/lib/tinymce/langs/';

        // A language code coming from the locale may not match an existing language file
        if (!file_exists($langDirectory . $config['language'] . '.js')) {
            unset($config['language']);
        }

        if (isset($config['language']) && $config['language']) {
            $languageUrl = $this->getAssetsUrl(
                '/bundles/ekynaui/lib/tinymce/langs/' . $config['language'] . '.js'
            );
            // TinyMCE does not allow to set different languages to each instance
            foreach ($config['theme'] as $themeName => $themeOptions) {
                $config['theme'][$themeName]['language'] = $config['language'];
                $config['theme'][$themeName]['language_url'] = $languageUrl;
            }
            $config['language_url'] = $languageUrl;
        }

        if (isset($config['theme']) && $config['theme']) {
            // Parse the content_css of each theme so we can use 'asset[path/to/asset]' in there
            foreach ($config['theme'] as $themeName => $themeOptions) {
                if (isset($themeOptions['content_css'])) {
                    // As there may be multiple CSS Files specified we need to parse each of them individually
                    $cssFiles = is_array($themeOptions['content_css'])
                        ? $themeOptions['content_css']
                        : explode(',', $themeOptions['content_css']);
                    foreach ($cssFiles as $idx => $file) {
                        // we trim to be sure we get the file without spaces.
                        $cssFiles[$idx] = $this->getAssetsUrl(trim($file));
                    }
                    $config['theme'][$themeName]['content_css'] = array_values($cssFiles);
                }
            }
        }

        return $config;
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    private function getLanguage(string $locale): string
    {
        return isset(self::LOCALES[$locale]) ? self::LOCALES[$locale] : $locale;
    }

    /**
     * Get url from config string
     *
     * @param string $inputUrl
     *
     * @return string
     */
    protected function getAssetsUrl(string $inputUrl): string
    {
        $url = preg_replace('/^asset\[(.+)]$/i', '$1', $inputUrl);

        if ($inputUrl !== $url) {
            return $this->getUrl($url);
        }

        return $inputUrl;
    }

    /**
     * Returns the asset package url.
     *
     * @param string $url
     *
     * @return string
     */
    protected function getUrl(string $url): string
    {
        return $this->packages->getUrl($url);
    }
}
