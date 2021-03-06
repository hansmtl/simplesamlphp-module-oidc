<?php

/*
 * This file is part of the simplesamlphp-module-oidc.
 *
 * Copyright (C) 2018 by the Spanish Research and Academic Network.
 *
 * This code was developed by Universidad de Córdoba (UCO https://www.uco.es)
 * for the RedIRIS SIR service (SIR: http://www.rediris.es/sir)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\SimpleSAML\Modules\OpenIDConnect\Controller;

use PhpSpec\ObjectBehavior;
use SimpleSAML\Configuration;
use SimpleSAML\Modules\OpenIDConnect\Controller\OpenIdConnectDiscoverConfigurationController;
use SimpleSAML\Modules\OpenIDConnect\Services\ConfigurationService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class OpenIdConnectDiscoverConfigurationControllerSpec extends ObjectBehavior
{
    /**
     * @return void
     */
    public function let(
        ConfigurationService $configurationService
    ) {
        $this->beConstructedWith($configurationService);
    }

    /**
     * @return void
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType(OpenIdConnectDiscoverConfigurationController::class);
    }

    /**
     * @return void
     */
    public function it_returns_openid_connect_configuration(
        ServerRequest $request,
        ConfigurationService $configurationService,
        Configuration $oidcConfiguration
    ) {
        $configurationService->getOpenIDScopes()->shouldBeCalled()
            ->willReturn(['openid' => 'openid']);

        $configurationService->getSimpleSAMLSelfURLHost()->shouldBeCalled()
            ->willReturn('http://localhost');
        $configurationService->getOpenIdConnectModuleURL('authorize.php')
            ->willReturn('http://localhost/authorize.php');
        $configurationService->getOpenIdConnectModuleURL('access_token.php')
            ->willReturn('http://localhost/access_token.php');
        $configurationService->getOpenIdConnectModuleURL('userinfo.php')
            ->willReturn('http://localhost/userinfo.php');
        $configurationService->getOpenIdConnectModuleURL('jwks.php')
            ->willReturn('http://localhost/jwks.php');

        $this->__invoke($request)->shouldHavePayload([
            'issuer' => 'http://localhost',
            'authorization_endpoint' => 'http://localhost/authorize.php',
            'token_endpoint' => 'http://localhost/access_token.php',
            'userinfo_endpoint' => 'http://localhost/userinfo.php',
            'jwks_uri' => 'http://localhost/jwks.php',
            'scopes_supported' => ['openid'],
            'response_types_supported' => ['code', 'token'],
            'subject_types_supported' => ['public'],
            'id_token_signing_alg_values_supported' => ['RS256'],
            'code_challenge_methods_supported' => ['plain', 'S256'],
        ]);
    }

    public function getMatchers(): array
    {
        return [
            'havePayload' => function (JsonResponse $subject, $payload) {
                return $payload === $subject->getPayload();
            },
        ];
    }
}
