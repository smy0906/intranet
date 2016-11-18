<?php
/** @var $this Intra\Core\Control */

use Intra\Service\IntraDb;
use Intra\Service\User\UserSession;

$db = IntraDb::getGnfDb();

$request = $this->getRequest();
$type = $request->get('type');

if (!strlen($type)) {
	$type = 'default';
}

$where = [
	'is_visible' => 1,
	'type' => $type,
];

$rooms = $db->sqlDicts('select * from rooms  where ?', sqlWhere($where));
$name = UserSession::getSelfDto()->name;

$descriptions = [
	'default' => '#회의실 예약 방법
1. 인트라넷에 로그인 후, [회의실 예약] 메뉴를 눌러주세요.
2. 예약할 회의실을 선택한 후, 사용하실 시간을 드래그하여 지정해주세요.
3. 예약자와 예약내용을 기재하시고, ‘Enter’키를 눌러 저장해주세요.
4. 삭제 시 내가 저장한 시간을 클릭하고 왼쪽에 ‘휴지통’ 버튼을 눌러주세요. (수정 시에는 ‘펜’ 버튼을 눌러주세요.)

*주의사항
- 회의실 예약 후, 미팅이 취소된 경우 다른 사람을 위해 예약 내역을 꼭 삭제해주세요.

- 모든 외부 손님의 미팅은 10층에서 진행해주세요. (예. 출판사 미팅, 면접 등)
- 11층의 중요한 외부 손님(예. VIP, 중요한 기자 등) 방문의 경우, 방문자 및 방문내용에 대해 대표님 서면 승인이 있어야 출입이 가능하오니 유념하여 주세요.
* 관련 문의는 인사팀에 해주시기 바랍니다.',
];

$notices = [
	'default' => '정기 미팅, 장기 미팅은 인사팀 철민님 통해 예약가능합니다.',
	'focus' => ' - FOCUS ROOM은 업무 집중 및 개인 휴식 공간입니다',
];

return [
	'rooms' => $rooms,
	'name' => $name,
	'description' => $descriptions[$type],
	'notice' => $notices[$type],
];
