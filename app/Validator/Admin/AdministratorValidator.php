<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use App\Abstract\AbstractValidator;

/**
 * @AdministratorValidator
 * @\App\Validator\Admin\AdministratorValidator
 */
final class AdministratorValidator extends AbstractValidator
{
	public function __construct()
	{
	}

	public function save(): array
	{
		return $this->validatorFactory->make(
			$this->request->post(),
			[
				'id'       => 'sometimes|integer:strict',
				'username' => 'required|string',
				'password' => 'required|string|between:6,16|regex:/^[a-zA-Z0-9_]+$/',
				'status'   => 'required|boolean:strict',
				'nickname' => 'sometimes|string|max:20|regex:/^[\p{Han}a-zA-Z0-9_]+$/u',
			],
			[
				'username.required' => '用户名不能为空',
				'username.string'   => '用户名必须是字符串',
				'username.between'  => '用户名长度在:min-:max个字符之间',
				'username.regex'    => '用户名必须是由大小写字母、0-9以及_组成',
				'password.required' => '密码不能为空',
				'password.string'   => '密码必须是字符串',
				'password.max'      => '用户名长度在:min-:max个字符之间',
				'password.regex'    => '用户名必须是由大小写字母、0-9以及_组成',
			],
		)->validate();
	}

	public function changeStatus(): array
	{
		return $this->validatorFactory->make($this->request->post(), [
			'id'     => 'required|integer:strict',
			'status' => 'required|boolean:strict',
		])->validate();
	}
}