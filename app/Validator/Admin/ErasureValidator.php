<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use Hyperf\Validation\Request\FormRequest;

/**
 * 删除接口的通用验证器
 *
 * @ErasureValidator
 * @\App\Validator\Admin\ErasureValidator
 */
final class ErasureValidator extends FormRequest
{
	public function rules(): array
	{
		return [
			'ids' => 'required|list|distinct|array_int',
		];
	}

	public function validated(): array
	{
		return $this->getValidatorInstance()->validate()['ids'];
	}

	/**
	 * @return bool
	 *
	 * @used-by FormRequest
	 */
	public function authorize(): bool
	{
		return true;
	}
}