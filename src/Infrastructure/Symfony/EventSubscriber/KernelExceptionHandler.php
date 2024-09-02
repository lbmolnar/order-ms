<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelExceptionHandler implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $logger, private readonly string $environment)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ('dev' === $this->environment || 'test' === $this->environment) {
            $data = [
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
                'trace' => $throwable->getTrace()
            ];
        } else {
            $data = ['message' => 'Something went wrong'];
        }

        $this->logger->error($throwable->getMessage(), ['exception' => $throwable]);
        $event->setResponse(new JsonResponse($data));
        $event->stopPropagation();
    }
}
