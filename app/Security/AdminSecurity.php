<?php
declare(strict_types=1);

namespace App\Security;

use App\Abstract\AbstractSecurity;
use App\Cache\Admin\AdministratorCache;
use App\Library\JsonWebToken\JWA;
use App\Library\JsonWebToken\JWK;
use Jose\Component\Core\JWK as JoseJWK;

/**
 * @AdminSecurity
 * @\App\Security\AdminSecurity
 */
final readonly class AdminSecurity extends AbstractSecurity
{
	public function __construct(private AdministratorCache $cache)
	{
		$jwk = new JoseJWK(
			[
				'kty' => 'oct',
				'k'   => $this->getKey(),
			],
		);

		$algorithmManager = JWA::create(['HS256']);

		parent::__construct($algorithmManager, $jwk);
	}

	private function getKey()
	{
		$key = $this->cache->getKey();

		//  TODO: Rewrite if the key doesnt exist.
		if (!$key) {
			$key = JWK::createOctKey()->get('k');

			$this->cache->setKey($key);
		}

		return $key;
	}
}