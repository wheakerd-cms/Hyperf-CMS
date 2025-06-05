<?php
declare(strict_types=1);

namespace App\Library\JsonWebToken;

use Jose\Component\Core\{
	AlgorithmManager,
	AlgorithmManagerFactory,
};
use Jose\Component\Encryption\Algorithm\{
	ContentEncryption\A128CBCHS256,
	KeyEncryption\A256KW,
	KeyEncryption\PBES2HS512A256KW,
};
use Jose\Component\Signature\Algorithm\{
	HS256,
	PS256,
};

/**
 * @JWA
 * @\App\Library\JWT\JWA
 */
final class JWA
{
	private static ?self $instance = null;

	/**
	 * @param AlgorithmManagerFactory $algorithmManagerFactory 算法管理器工厂
	 *
	 * @noinspection SpellCheckingInspection
	 */
	public function __construct(private readonly AlgorithmManagerFactory $algorithmManagerFactory)
	{
		$this->algorithmManagerFactory->add('A128CBC-HS256', new A128CBCHS256());
		$this->algorithmManagerFactory->add('A256KW', new A256KW());
		$this->algorithmManagerFactory->add('HS256', new HS256());
		$this->algorithmManagerFactory->add('PS256', new PS256());
		$this->algorithmManagerFactory->add('PBES2-HS512+A256KW', new PBES2HS512A256KW());
		$this->algorithmManagerFactory->add(
			'PBES2-HS512+A256KW with custom configuration',
			new PBES2HS512A256KW(128, 8192),
		);
	}

	/**
	 * 创造者
	 *
	 * @param string[] $algorithms
	 *
	 * @return AlgorithmManager
	 */
	public static function create(array $algorithms): AlgorithmManager
	{
		return self::getInstance()->algorithmManagerFactory->create($algorithms);
	}

	private static function getInstance(): JWA
	{
		return self::$instance ??= new JWA(new AlgorithmManagerFactory);
	}
}