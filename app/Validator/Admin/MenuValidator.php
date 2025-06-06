<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use App\Abstract\AbstractValidator;

/**
 * @MenuValidator
 * @\App\Validator\Admin\MenuValidator
 */
final class MenuValidator extends AbstractValidator
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
				'type'     => 'required|integer:strict|digits:1',
				'parentId' => 'sometimes|integer:strict',
				'title'    => 'required|string|between:1,16',
				'name'     => 'required|string|between:1,32',
				'icon'     => 'sometimes|string|between:1,100',
				'order'    => 'sometimes|integer:strict',
			],
			[
				'type.required'  => '类型不能为空',
				'title.required' => '标题不能为空',
				'name.required'  => '权限标识不能为空',
				'title.between'  => '标题长度必须在:min-:max之间',
				'name.between'   => '权限标识长度必须在:min-:max之间',
				'icon.between'   => '图标长度必须在:min-:max之间',
			],
		)->validate();
	}
}