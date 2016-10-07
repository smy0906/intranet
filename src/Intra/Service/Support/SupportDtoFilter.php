<?php

namespace Intra\Service\Support;

class SupportDtoFilter
{

	/**
	 * @param SupportDto $support_dto
	 *
	 * @return SupportDto
	 */
	public static function filterAddingDto($support_dto)
	{
		$columns = SupportPolicy::getColumns($support_dto->target);
		if ($support_dto->target == 'family_event') {
			$category = $support_dto->dict[$columns['분류']->key];

			if ($category == '결혼') {
				$flower_type_column = '화환';
			} elseif (in_array($category, ['자녀출생', '졸업', '장기근속(3년)'])) {
				$flower_type_column = '과일바구니';
			} elseif (in_array($category, ['사망-부모 (배우자 부모 포함)', '사망-조부모 (배우자 조부모 포함)'])) {
				$flower_type_column = '조화';
			} else {
				$flower_type_column = '기타';
			}

			if ($support_dto->dict[$columns['화환 종류']->key] == '자동선택') {
				$support_dto->dict[$columns['화환 종류']->key] = $flower_type_column;
			}

			if (in_array($category, [
				'결혼',
				'자녀출생',
				'사망-부모 (배우자 부모 포함)',
			])) {
				$cash = '1000000';
				$support_dto->dict[$columns['경조금']->key] = $cash;
			}
		}
		return $support_dto;
	}
}
