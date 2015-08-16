<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 20
 * Time: 오전 11:49
 */

namespace Intra\Model;

use Intra\Core\MsgException;
use Intra\Service\IntraDb;

class UserModel extends DomainCacheModel
{
	static private $table = 'users';
	/**
	 * @var int
	 */
	public $uid;
	public $public_information;
	/**
	 * @var
	 */
	public $user;
	/**
	 * @var \Gnf\db\base
	 */
	private $db;

	public function __construct($uid, $other_db = null)
	{
		$this->db = IntraDb::getGnfDb();
		$this->uid = $uid;
		$this->initUser();
	}

	/**
	 * @internal param $uid
	 */
	private function initUser()
	{
		$user = $this->getDbDto();
		$this->user = $user;
		$user['id_for_css'] = str_replace(".", "_", $user['id']);
		$this->public_information = $user;
	}

	public function getDbDto()
	{
		return self::setCache(
			$this->uid,
			function () {
				$user = $this->db->sqlDict('select * from ? where uid = ?', sqlTable(self::$table), $this->uid);
				if ($user) {
					$obj = json_decode($user['extra']);
					if (is_object($obj)) {
						$user['extra'] = @get_object_vars($obj);
					} else {
						$user['extra'] = array();
					}
				}
				return $user;
			}
		);
	}

	/**
	 * @param $userJoinDto
	 * @return bool
	 * @throws MsgException
	 */
	public static function addUser($userJoinDto)
	{
		$array = get_object_vars($userJoinDto);

		$uid = IntraDb::getGnfDb()->sqlInsert(sqlTable(self::$table), $array);
		if (!$uid) {
			throw new MsgException('계정 추가가 실패하였습니다');
		}
		return true;
	}

	public function isValid()
	{
		$where = array();
		$where['uid'] = $this->uid;
		$where['on_date'] = sqlLesserEqual(sqlNow());
		$where['off_date'] = sqlGreaterEqual(sqlNow());
		return $this->db->sqlCount(self::$table, $where);
	}

	public function updateExtra($key, $val)
	{
		self::invalidateCache($this->uid);

		$user = $this->user;
		$user['extra'][$key] = $val;

		$value = array('extra' => json_encode($user['extra']));
		$where = array('uid' => $this->uid);
		$this->db->sqlUpdate(self::$table, $value, $where);

		return 1;
	}

	public function getName()
	{
		return $this->user['name'];
	}
}
