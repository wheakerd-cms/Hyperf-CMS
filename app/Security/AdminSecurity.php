<?php
declare(strict_types=1);

namespace App\Security;

use App\Abstract\AbstractSecurity;
use App\Cache\SystemDefaultCache;
use App\Library\JsonWebToken\JWA;
use Jose\Component\Core\JWK;

/**
 * @AdminSecurity
 * @\App\Security\AdminSecurity
 */
final readonly class AdminSecurity extends AbstractSecurity
{
	public function __construct(SystemDefaultCache $systemDefaultCache, JWA $jsonWebAlgorithms)
	{
		$key              = $systemDefaultCache->getAdminKey();
		$algorithmManager = $jsonWebAlgorithms->create(['HS256']);
		$jwk              = new JWK(
			[
				'kty' => 'oct',
				'k'   => $key,
			],
		);

		parent::__construct($algorithmManager, $jwk);
	}
}