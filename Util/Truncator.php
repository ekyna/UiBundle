<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Util;

use DOMDocument;
use DOMNode;

use function html_entity_decode;
use function mb_convert_encoding;
use function mb_strlen;
use function mb_strrpos;

use const LIBXML_HTML_NOIMPLIED;

/**
 * Class Truncator
 * @package Ekyna\Bundle\UiBundle\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Truncator
{
    private DOMDocument $tempDiv;
    private DOMDocument $newDiv;
    private int         $charCount;
    private string      $encoding;

    public static function create(string $html, string $encoding = 'UTF-8'): Truncator
    {
        return new self($html, $encoding);
    }

    public function __construct(string $html, string $encoding = 'UTF-8')
    {
        $this->charCount = 0;
        $this->encoding = $encoding;

        $html = '<div>' . mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') . '</div>';

        $this->tempDiv = new DOMDocument('1.0', $encoding);
        $this->tempDiv->loadHTML($html, LIBXML_HTML_NOIMPLIED);
    }

    public function truncate(int $limit, string $endChar = '&hellip;'): string
    {
        $this->newDiv = new DOMDocument();

        $this->searchEnd($this->tempDiv->documentElement, $this->newDiv, $limit, $endChar);

        return $this->newDiv->saveHTML();
    }

    private function searchEnd(DOMNode $parseDiv, DOMNode $newParent, int $limit, string $endChar): bool
    {
        foreach ($parseDiv->childNodes as $ele) {
            if ($ele->nodeType != 3) {
                $newEle = $this->newDiv->importNode($ele, true);
                if (count($ele->childNodes) === 0) {
                    $newParent->appendChild($newEle);

                    continue;
                }

                $this->deleteChildren($newEle);

                $newParent->appendChild($newEle);

                if ($this->searchEnd($ele, $newEle, $limit, $endChar)) {
                    return true;
                }

                continue;
            }

            $length = mb_strlen($ele->nodeValue, $this->encoding);
            if ($length + $this->charCount >= $limit) {
                $newEle = $this->newDiv->importNode($ele);
                $offset = $limit - $this->charCount - $length;
                if (false === $pos = mb_strrpos($newEle->nodeValue, ' ', $offset)) {
                    // TODO This results in empty tags...
                    return true;
                }

                $newEle->nodeValue = mb_substr($newEle->nodeValue, 0, $pos) . html_entity_decode($endChar);

                $newParent->appendChild($newEle);

                return true;
            }

            $newEle = $this->newDiv->importNode($ele);
            $newParent->appendChild($newEle);
            $this->charCount += mb_strlen($newEle->nodeValue, $this->encoding);

            if ($this->charCount === $limit) {
                return true;
            }
        }

        return false;
    }

    private function deleteChildren(DOMNode $node): void
    {
        while (isset($node->firstChild)) {
            $this->deleteChildren($node->firstChild);
            $node->removeChild($node->firstChild);
        }
    }
}
