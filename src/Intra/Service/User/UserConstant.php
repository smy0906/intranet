<?php

namespace Intra\Service\User;

class UserConstant
{
	const TEAM_CCPQ = '단행본 / 운영지원센터 / CC/PQ팀';
	const TEAM_PLATFORM = '단행본 / 개발센터 / 플랫폼팀';
	const TEAM_STORE = '단행본 / 개발센터 / 스토어팀';
	const TEAM_VIERER = '단행본 / 개발센터 / 뷰어팀';
	const TEAM_DATA = '단행본 / 개발센터 / 데이터팀';
	const TEAM_BI = '단행본 / 운영지원센터 / 사업분석팀';
	const TEAM_STORE_OP = '단행본 / 운영지원센터 / 운영지원팀';
	const TEAM_GROWTH = '단행본 / Growth팀';
	const TEAM_DEVICE = '단행본 / 디바이스팀 / 디바이스팀';
	const TEAM_DESIGN = '단행본 / 디자인팀 / 디자인팀';
	const TEAM_CM_1 = '단행본 / 콘텐츠운영1팀';
	const TEAM_CM_2 = '단행본 / 콘텐츠운영2팀';
	const TEAM_STUDIOD = '스튜디오D';
	const TEAM_DRAGON = '연재';
	const TEAM_CEO = 'CEO 직속';

	const TEAM_DETAIL_HUMAN_MANAGE = '인사팀';

	public static $jeditable_key_list = [
		'team' => [
			self::TEAM_DATA,
			self::TEAM_VIERER,
			self::TEAM_STORE,
			self::TEAM_PLATFORM,
			self::TEAM_CCPQ,
			self::TEAM_BI,
			self::TEAM_STORE_OP,
			self::TEAM_GROWTH,
			self::TEAM_DEVICE,
			self::TEAM_DESIGN,
			self::TEAM_CM_1,
			self::TEAM_CM_2,
			self::TEAM_STUDIOD,
			self::TEAM_DRAGON,
			self::TEAM_CEO,
		],
	];
}
