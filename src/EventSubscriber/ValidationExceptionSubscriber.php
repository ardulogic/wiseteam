<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Run before Symfony’s default exception normaliser.
        return [KernelEvents::EXCEPTION => ['onKernelException', 32]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isApiRequest($event->getRequest())) {
            return;                 // Let Symfony handle non-API requests
        }


        $violationException = $this->eventContainsValidationException($event);

        if ($violationException === null) {
            return;                         // Not our concern — let Symfony handle it
        }

        $this->setViolationResponse($event, $violationException);
    }

    private function isApiRequest(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api')
            || str_starts_with((string)$request->attributes->get('_route'), 'api_')
            || $request->getPreferredFormat() === 'json';
    }

    /**
     * Returns the ValidationFailedException carried by the event or null.
     */
    private function eventContainsValidationException(ExceptionEvent $event): ?ValidationFailedException
    {
        $e = $event->getThrowable();

        // We only care about 422 HTTP exceptions.
        if (!($e instanceof HttpExceptionInterface) ||
            $e->getStatusCode() !== Response::HTTP_UNPROCESSABLE_ENTITY) {
            return null;
        }

        // Walk the previous-exception chain until we meet a ValidationFailedException.
        while ($e && !$e instanceof ValidationFailedException) {
            $e = $e->getPrevious();
        }

        return $e ?: null;
    }

    /**
     * Converts the violation list to { field: message } JSON and sets it on the event.
     */
    private function setViolationResponse(ExceptionEvent $event, ValidationFailedException $violations): void
    {
        $errors = [];
        foreach ($violations->getViolations() as $v) {
            // First error per field is enough for vee-validate.
            $errors[$v->getPropertyPath()] ??= $v->getMessage();
        }

        $event->setResponse(new JsonResponse([
            'errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
