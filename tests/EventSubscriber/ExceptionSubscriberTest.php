<?php
/**
 * Created by PhpStorm.
 * User: issam
 */

namespace App\Tests\EventSubscriber;


use App\EventSubscriber\ExceptionSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ExceptionSubscriberTest
 * @package App\Tests\EventSubscriber
 */
class ExceptionSubscriberTest extends TestCase
{

    /**
     *
     */
    public function testEventSubscription(){

        $this->assertArrayHasKey(ExceptionEvent::class, ExceptionSubscriber::getSubscribedEvents());
    }

    /**
     *
     */
    public function testOnExceptionSendEmail(){

        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send');
        $this->setingSubscriber($mailer);

    }

    /**
     *
     */
    public function testGoodReceptionEmail(){


        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function(\Swift_Message $message){
                return
                    array_key_exists("kharkhachissam@gmail.com", $message->getFrom()) &&
                    array_key_exists("issam.kharkhach@gmail.com",$message->getTo());
        }));

        $this->setingSubscriber($mailer);

    }

    /**
     *
     */
    public function testExceptionEmailWithTrace(){


        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function(\Swift_Message $message){
                return
                    strpos($message->getBody(), "ExceptionSubscriberTest") &&
                    strpos($message->getBody(), "une exception");
             }));


        $this->setingSubscriber($mailer);


    }

    /**
     * @param $mailer
     */
    private function setingSubscriber($mailer)
    {
        $subscriber = new ExceptionSubscriber($mailer,"kharkhachissam@gmail.com","issam.kharkhach@gmail.com");
        $kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $event = new ExceptionEvent($kernel, new \Symfony\Component\HttpFoundation\Request(), 1, new \Exception("une exception"));

        //$subscriber->onException($event);
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event);
    }


}