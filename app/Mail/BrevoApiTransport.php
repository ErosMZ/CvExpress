<?php

namespace App\Mail;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

/**
 * Transporte de correo que envía a través de la API HTTP de Brevo
 * (https://api.brevo.com/v3/smtp/email) en lugar de SMTP.
 *
 * Motivo: Render bloquea el tráfico SMTP saliente (puertos 25/465/587)
 * en el plan gratuito, así que SMTP nunca puede funcionar allí.
 * La API usa HTTPS (puerto 443), que no está bloqueado.
 *
 * No requiere ninguna dependencia nueva: usa file_get_contents con
 * contexto HTTP, disponible en cualquier PHP.
 */
class BrevoApiTransport implements TransportInterface
{
    private const API_URL = 'https://api.brevo.com/v3/smtp/email';

    public function __construct(private readonly string $apiKey)
    {
    }

    public function __toString(): string
    {
        return 'brevo+api';
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        if (!$message instanceof Email) {
            throw new TransportException('BrevoApiTransport solo admite mensajes Symfony Email.');
        }

        $from = $message->getFrom();
        if (empty($from)) {
            throw new TransportException('BrevoApiTransport necesita una dirección From.');
        }

        $to = $message->getTo();
        if (empty($to)) {
            throw new TransportException('BrevoApiTransport necesita al menos un destinatario.');
        }

        $payload = [
            'sender' => $this->addressToArray($from[0]),
            'to' => array_map([$this, 'addressToArray'], $to),
            'subject' => $message->getSubject() ?? '',
        ];

        if ($html = $message->getHtmlBody()) {
            $payload['htmlContent'] = $html;
        } elseif ($text = $message->getTextBody()) {
            $payload['textContent'] = $text;
        }

        if ($replyTo = $message->getReplyTo()) {
            $payload['replyTo'] = $this->addressToArray($replyTo[0]);
        }

        // Conserva la cabecera List-Unsubscribe si la trae el mensaje.
        $headers = $message->getHeaders();
        if ($headers->has('List-Unsubscribe')) {
            $payload['headers'] = [
                'List-Unsubscribe' => $headers->get('List-Unsubscribe')->getBodyAsString(),
            ];
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'api-key: '.$this->apiKey,
                ],
                'content' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'timeout' => 20,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $response = @file_get_contents(self::API_URL, false, $context);

        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/HTTP\/\S+\s+(\d+)/', $http_response_header[0], $matches)) {
            $status = (int) $matches[1];
        }

        if ($response === false || $status < 200 || $status >= 300) {
            $body = is_string($response) ? substr($response, 0, 500) : 'sin respuesta del servidor';
            throw new TransportException("Error enviando email con la API de Brevo (HTTP {$status}): {$body}");
        }

        return new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    private function addressToArray(Address $address): array
    {
        $data = ['email' => $address->getAddress()];
        if ($address->getName() !== '') {
            $data['name'] = $address->getName();
        }

        return $data;
    }
}
