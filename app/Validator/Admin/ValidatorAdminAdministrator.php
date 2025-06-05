<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use App\Abstract\AbstractValidator;
use App\Exception\CustomMessageException;
use Hyperf\Contract\SessionInterface;

/**
 * @ValidatorAdminAdministrator
 * @\App\Validator\Admin\ValidatorAdminAdministrator
 */
final class ValidatorAdminAdministrator extends AbstractValidator
{
	public function __construct(private readonly SessionInterface $session)
	{
	}

	public function login(): array
	{
		$inputs = $this->validatorFactory->make($this->request->all(), [
			'username' => 'required|string|between:5,8',
			'password' => 'required|string|between:6,12',
			'captcha'  => 'required|string|size:5',
		])->validate();

		$captcha = $inputs['captcha'];

		$code = $this->session->get('captcha');

		if (!hash_equals($code, $captcha)) {
			throw new CustomMessageException('验证码不正确，请刷新后重试');
		}

		return $inputs;
	}
}