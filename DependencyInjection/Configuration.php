<?php
namespace Onekit\SpeakerRecognitionBundle\DependencyInjection;

use \Symfony\Component\Config\Definition\ConfigurationInterface,
    \Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('imports');

        $rootNode
            ->prototype('array')
            ->attribute('resource','../../vendor/onekit/speaker-recognition-bundle/Resources/config/services.yml')
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }

}