<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventSubscriber;

use App\Infrastructure\JSONSchemaValidator\Validator;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestEventSubscriber implements EventSubscriberInterface
{
    private const string ACCEPTED_HEADER = 'application/json';

    public function __construct(
        private readonly Validator $validator,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return array<class-string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (
            self::ACCEPTED_HEADER === $request->headers->get('accept')
            && self::ACCEPTED_HEADER === $request->headers->get('content-type')
        ) {
            try {

                $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
                $errors = $this->validator->validate($request->getPathInfo(), $request->getContent());

                if (count($errors) > 0) {
                    $event->setResponse(new JsonResponse($errors, Response::HTTP_BAD_REQUEST));
                    $event->stopPropagation();
                } else {
                    $event->getRequest()->request->replace($content);
                }
            } catch (\Throwable) {
                $event->setResponse(new Response(null, Response::HTTP_UNPROCESSABLE_ENTITY));
                $event->stopPropagation();

                $this->logger->warning('Received invalid JSON content');
            }
        } else {
            $event->setResponse(new Response(null, Response::HTTP_UNSUPPORTED_MEDIA_TYPE));
            $event->stopPropagation();
        }
    }
}
