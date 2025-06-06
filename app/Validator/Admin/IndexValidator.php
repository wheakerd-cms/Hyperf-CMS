<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use App\Abstract\AbstractValidator;
use App\Exception\CustomMessageException;
use Hyperf\Contract\SessionInterface;
use Hyperf\Stringable\Str;

/**
 * @IndexValidator
 * @\App\Validator\Admin\IndexValidator
 */
final class IndexValidator extends AbstractValidator
{
	public function __construct(private readonly SessionInterface $session)
	{
	}

	public function login(): array
	{
		$inputs = $this->validatorFactory->make($this->request->all(), [
			'username' => 'required|string|max:20',
			'password' => 'required|string',
			'captcha'  => 'required|string|size:5',
		])->validate();

		$captcha = $inputs['captcha'];
		unset($inputs['captcha']);

		$code = $this->session->get('captcha') ?? '';
		$code = Str::lower($code);

		if (!hash_equals($code, $captcha)) {
			throw new CustomMessageException('验证码不正确，请刷新后重试', 400);
		}

		return $inputs;
	}
}