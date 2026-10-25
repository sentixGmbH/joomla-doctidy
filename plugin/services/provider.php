<?php

/**
 * @package     DocTidy
 * @subpackage  plg_system_doctidy
 * @copyright   (C) 2026 sentix GmbH
 * @license     GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use DocTidy\Plugin\System\DocTidy\Extension\DocTidy;
use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Version;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $config = (array) PluginHelper::getPlugin('system', 'doctidy');

                // Joomla 4/5 expect the dispatcher as first argument, Joomla 6 only the config
                $plugin = Version::MAJOR_VERSION >= 6
                    ? new DocTidy($config)
                    : new DocTidy($container->get(DispatcherInterface::class), $config);

                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};
