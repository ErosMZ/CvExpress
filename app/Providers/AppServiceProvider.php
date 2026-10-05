<?php

namespace App\Providers;

use App\Mail\BrevoApiTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Cv\OpenAICvParser::class, function () {
            $client = \OpenAI::client(config('services.openai.api_key'));

            return new \App\Services\Cv\OpenAICvParser($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Transporte "brevo": envía por la API HTTP de Brevo en vez de SMTP,
        // porque Render bloquea el SMTP saliente en el plan gratuito.
        // Se activa con MAIL_MAILER=brevo y BREVO_API_KEY en el entorno.
        Mail::extend('brevo', function (array $config = []) {
            return new BrevoApiTransport($config['key'] ?? '');
        });
    }
}
