<?php
declare(strict_types=1);

namespace App\Utils;

/**
 * @ArrayFunction
 * @\App\Utils\ArrayFunction
 */
final class ArrayFunction
{
	/**
	 * This function converts the provided data into a child-parent cascade table data.
	 *
	 * @param array    $list
	 * @param string   $primaryKey
	 * @param string   $parentKey
	 * @param int|null $key
	 *
	 * @return array
	 */
	public static function listTree(array &$list, string $primaryKey, string $parentKey, ?int $key = null): array
	{
		$data = [];

		foreach ($list as $item) {
			if ($item [$parentKey] !== $key) continue;

			$children = self::listTree($list, $primaryKey, $parentKey, $item[$primaryKey]);
			$data []  = empty($children) ? $item : $item + compact('children');

			unset($item);
		}

		return $data;
	}
}