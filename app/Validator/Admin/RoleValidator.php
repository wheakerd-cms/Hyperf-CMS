<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use App\Abstract\AbstractValidator;

/**
 * @RoleValidator
 * @\App\Validator\Admin\RoleValidator
 */
final class RoleValidator extends AbstractValidator
{
	public function __construct()
	{
	}

	public function save(): array
	{
		return $this->validatorFactory->make(
			$this->request->post(),
			[
				'name'     => 'required|string',
				'menuList' => 'sometimes|list|distinct|array_int',
				'id'       => 'sometimes|integer:strict',
			],
		)->validate();
	}
}