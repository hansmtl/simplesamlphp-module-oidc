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

namespace SimpleSAML\Modules\OpenIDConnect\Factories\Grant;

use League\OAuth2\Server\Grant\RefreshTokenGrant;
use SimpleSAML\Modules\OpenIDConnect\Repositories\RefreshTokenRepository;

class RefreshTokenGrantFactory
{
    /**
     * @var \SimpleSAML\Modules\OpenIDConnect\Repositories\RefreshTokenRepository
     */
    private $refreshTokenRepository;

    /**
     * @var \DateInterval
     */
    private $refreshTokenDuration;

    public function __construct(
        RefreshTokenRepository $refreshTokenRepository,
        \DateInterval $refreshTokenDuration
    ) {
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->refreshTokenDuration = $refreshTokenDuration;
    }

    public function build(): RefreshTokenGrant
    {
        $refreshTokenGrant = new RefreshTokenGrant($this->refreshTokenRepository);
        $refreshTokenGrant->setRefreshTokenTTL($this->refreshTokenDuration);

        return $refreshTokenGrant;
    }
}
