<?php

/**
 * @package     DocTidy
 * @subpackage  plg_system_doctidy
 * @copyright   (C) 2026 sentix GmbH
 * @license     GNU General Public License version 2 or later
 */

namespace DocTidy\Plugin\System\DocTidy\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;
use Joomla\Event\SubscriberInterface;

\defined('_JEXEC') or die;

/**
 * DocTidy – small, configurable output fixes for Joomla sites (with a focus on EDocman):
 *  1. Font Awesome from a public CDN is replaced by Joomla's local copy (no visitor IP to third parties).
 *  2. EDocman document pages: the second <h1> (mobile header) becomes a <div> – one H1 per page.
 *  3. EDocman lists: <h1> headlines inside short descriptions become styled paragraphs.
 *  4. Optional: EDocman page titles are rewritten (prefix removed, suffix added).
 * Every replacement is narrow: if the markup changes, it simply stops matching and the page stays as it is.
 */
final class DocTidy extends CMSPlugin implements SubscriberInterface
{
    /** CDN stylesheets of Font Awesome that are replaced by the local copy */
    private const FA_CDN_PATTERN = '~<link\b[^>]*href="https?://(?:cdnjs\.cloudflare\.com/ajax/libs/font-awesome|cdn\.jsdelivr\.net/npm/@fortawesome|use\.fontawesome\.com|stackpath\.bootstrapcdn\.com/font-awesome|maxcdn\.bootstrapcdn\.com/font-awesome)/[^"]*"[^>]*>\s*~i';

    public static function getSubscribedEvents(): array
    {
        return ['onAfterRender' => 'onAfterRender'];
    }

    public function onAfterRender(): void
    {
        $app = $this->getApplication();

        if (!$app->isClient('site') || $app->getDocument()->getType() !== 'html') {
            return;
        }

        $input   = method_exists($app, 'getInput') ? $app->getInput() : $app->input;
        $edocman = $input->getCmd('option') === 'com_edocman';
        $body    = $app->getBody();

        if ($this->params->get('local_fontawesome', 1)
            && ($this->params->get('local_fontawesome_scope', 'all') === 'all' || $edocman)) {
            $body = $this->localFontAwesome($body);
        }

        if ($this->params->get('single_h1_document', 1)) {
            $body = preg_replace(
                '~(<div class="mobile-title-container">.*?)<h1 class="document-title">(.*?)</h1>~s',
                '$1<div class="document-title">$2</div>',
                $body,
                1
            );
        }

        if ($this->params->get('list_headlines', 1)) {
            $class = htmlspecialchars(trim((string) $this->params->get('list_headline_class', 'doctidy-headline')) ?: 'doctidy-headline');
            $body  = preg_replace_callback(
                '~(<div class="edocman-description-details[^"]*">)(.*?)(<div class="edocman-taskbar)~s',
                function ($m) use ($class) {
                    return $m[1] . preg_replace('~<h1\b[^>]*>(.*?)</h1>~s', '<p class="' . $class . '">$1</p>', $m[2]) . $m[3];
                },
                $body
            );
        }

        if ($edocman && $this->params->get('title_rewrite', 0)) {
            $body = $this->rewriteTitle($body, $input->getCmd('view'));
        }

        $app->setBody($body);
    }

    /** Replace the first Font Awesome CDN link with the local copy and drop further ones. */
    private function localFontAwesome(string $body): string
    {
        $local = $this->localFontAwesomeUrl();

        if ($local === null) {
            return $body; // no local copy on this site – leave the page untouched
        }

        $count = 0;

        return preg_replace_callback(
            self::FA_CDN_PATTERN,
            function () use ($local, &$count) {
                return $count++ === 0 ? '<link href="' . $local . '" rel="stylesheet">' . "\n" : '';
            },
            $body
        );
    }

    private function localFontAwesomeUrl(): ?string
    {
        foreach (['/media/vendor/fontawesome-free/css/fontawesome.min.css', '/media/vendor/fontawesome-free/css/all.min.css'] as $path) {
            if (is_file(JPATH_ROOT . $path)) {
                return Uri::root(true) . $path;
            }
        }

        return null;
    }

    private function rewriteTitle(string $body, string $view): string
    {
        $prefix = (string) $this->params->get('title_prefix', '');
        $suffix = $view === 'document'
            ? (string) $this->params->get('title_suffix_document', '')
            : (string) $this->params->get('title_suffix_category', '');

        return preg_replace_callback(
            '~<title>(.*?)</title>~s',
            function ($m) use ($prefix, $suffix) {
                $title = $m[1];

                // With a prefix configured, only titles starting with it are rewritten
                // (pages with their own title, e.g. from the menu item, stay untouched).
                if ($prefix !== '') {
                    $matched = false;

                    foreach ([htmlspecialchars($prefix, ENT_COMPAT, 'UTF-8'), $prefix] as $p) {
                        if (substr($title, 0, strlen($p)) === $p) {
                            $title   = substr($title, strlen($p));
                            $matched = true;
                            break;
                        }
                    }

                    if (!$matched) {
                        return $m[0];
                    }
                }

                return '<title>' . trim($title) . htmlspecialchars($suffix, ENT_COMPAT, 'UTF-8') . '</title>';
            },
            $body,
            1
        );
    }
}
