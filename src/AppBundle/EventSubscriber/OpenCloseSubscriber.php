<?php
/**
 * Created by PhpStorm.
 * User: Luk
 * Date: 16/05/2017
 * Time: 13:32
 */

namespace AppBundle\EventSubscriber;


use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class OpenCloseSubscriber implements EventSubscriberInterface
{

    private $twig;

    public function __construct(EngineInterface $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'closeWebsite'
        ];
    }

    public function closeWebsite(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if(!$this->isClosed()) {
            return;
        }

        /*$event->getRequest()->attributes
            ->set('_controller', '');*/

        $html = $this->twig->render('openclose/close.html.twig');

        $event->setResponse(new Response($html));

    }

    private function isClosed()
    {
        $now = date('H');
        return true;
        if($now < 12) {
            return true;
        }
        if($now > 14) {
            return true;
        }

        return false;
    }
}