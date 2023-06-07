<?php


namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    const MIME_JSON = 'application/json';

    public function __invoke(ExceptionEvent $event): void
    {
        // Получаем MIME тип из заголовка Accept
        $acceptHeader = $event->getRequest()->headers->get('Accept');

        if ($acceptHeader === self::MIME_JSON) {
            // You get the exception object from the received event
            $exception = $event->getThrowable();

            // Customize your response object to display the exception details
            $response = new Response();
            $response->setContent($this->exceptionToJson($exception));

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // sends the modified response object to the event
            $event->setResponse($response);
        }
    }

    public function exceptionToJson(\Throwable $exception): string
    {
        return json_encode(
            [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }
}