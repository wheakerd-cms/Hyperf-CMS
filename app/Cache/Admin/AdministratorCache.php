<?php
declare(strict_types=1);

namespace App\Cache\Admin;

use App\Abstract\AbstractCache;
use App\Library\JsonWebToken\JWK;
use App\Proxy\DefaultRedisProxy;

/**
 * @AdministratorCache
 * @\App\Cache\Admin\AdministratorCache
 */
final readonly class AdministratorCache extends AbstractCache
{
	public function __construct(DefaultRedisProxy $redis)
	{
		parent::__construct($redis);
	}

	public function setKey(?string $key = null): void
	{
		$this->redis->set('admin_key', $key ?? JWK::createOctKey()->get('k'));
	}

	public function getKey(): false|string
	{
		return $this->redis->get('admin_key');
	}

	/**
	 * 判断用户凭证是否由本系统签发
	 *
	 * @param string $token
	 *
	 * @return false|int
	 */
	public function hasToken(string $token): false|int
	{
		return false !== $this->redis->get($token);
	}

	/**
	 * 存储生成的用户令牌
	 *
	 * @param string $token
	 * @param int    $userid
	 * @param int    $expireTime
	 *
	 * @return void
	 */
	public function setToken(string $token, int $userid, int $expireTime = 60 * 60 * 24 * 30): void
	{
		$this->redis->setex($this->getKey($token), $expireTime, $userid);
	}

	/**
	 * 移除用户凭证
	 *
	 * @param string $token
	 *
	 * @return void
	 */
	public function delToken(string $token): void
	{
		$this->redis->del($this->getKey($token));
	}
}