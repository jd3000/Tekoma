<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HCaptcha
{
    private const HCAPTCHA_ENDPOINT = 'https://hcaptcha.com/siteverify';
    private HttpClientInterface $httpClient;
    private RequestStack $requestStack;
    private string $hCaptchaSecretKey;

    public function __construct(
        httpClientInterface $httpClient,
        RequestStack $requestStack,
        string $hCaptchaSecretKey
    ) {
        $this->httpClient = $httpClient;
        $this->requestStack = $requestStack;
        $this->hCaptchaSecretKey = $hCaptchaSecretKey;
    }

    public function isHCaptchaValid()
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return false;
        }

        $options = [
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/x-www-form-urlencoded'
            ],
            'body' => [
                'secret'        => $this->hCaptchaSecretKey,
                'response'      => $request->request->get('h-captcha-response')
            ]
        ];

        $response = $this->httpClient->request('POST', self::HCAPTCHA_ENDPOINT, $options);
        $data = $response->toArray();
        return $data;
        // dd($data);
        // return $data['succes'];

    }
}
