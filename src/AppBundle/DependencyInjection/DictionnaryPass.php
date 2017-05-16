<?php
/**
 * Created by PhpStorm.
 * User: Luk
 * Date: 16/05/2017
 * Time: 10:08
 */

namespace AppBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DictionnaryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->hasDefinition('app.wordlist')) {
            return;
        }
        /** @var Definition $definition */
        $definition = $container->findDefinition('app.wordlist');

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('word_list.loader');
        $methodCalls = $definition->getMethodCalls();
        $newMethodCalls = array();


        foreach ($taggedServices as $id => $tags) {
            $type = $tags[0]['type'];
            // add the transport service to the ChainTransport service
            $newMethodCalls[] = array('addLoader', array($type, new Reference($id)));
        }

        $newMethodCalls = array_merge($newMethodCalls, $methodCalls);
        $definition->setMethodCalls($newMethodCalls);
        //$newMethodCalls[]
    }
}