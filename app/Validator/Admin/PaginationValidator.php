<?php
declare(strict_types=1);

namespace App\Validator\Admin;

use Hyperf\Validation\Request\FormRequest;

/**
 * 表格分页的通用验证器
 *
 * @PaginationValidator
 * @\App\Validator\Admin\PaginationValidator
 */
final class PaginationValidator extends FormRequest
{
	public function rules(): array
	{
		return [
			'search'      => 'sometimes|json',
			'sorts'       => 'sometimes|json',
			'currentPage' => 'sometimes|integer',
			'perPage'     => 'sometimes|integer',
		];
	}

	public function validated(): array
	{
		$inputs = $this->getValidatorInstance()->validate();

		$search      = isset($inputs['search']) ? json_decode($inputs['search'], true) : [];
		$sorts       = isset($inputs['sorts']) ? json_decode($inputs['sorts'], true) : [];
		$currentPage = max(intval($inputs['currentPage'] ?? 1), 1);
		$perPage     = min(max(intval($inputs['perPage'] ?? 20), 20), 200);

		return compact('search', 'sorts', 'currentPage', 'perPage');
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