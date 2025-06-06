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
		$inputs = $this->validatorFactory->make($this->request->post(), [
			'id'       => 'sometimes|integer:strict',
			'username' => 'required|string',
			'status'   => 'required|boolean:strict',
			'nickname' => 'required|string',
			'password' => 'required|string',
		])->validate();

		//  TODO: check password

		return $inputs;
	}

	public function changeStatus(): array
	{
		return $this->validatorFactory->make($this->request->all(), [
			'id'     => 'required|integer:strict',
			'status' => 'required|boolean:strict',
		])->validate();
	}
}