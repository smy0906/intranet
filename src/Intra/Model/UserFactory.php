<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-28
 * Time: 오전 11:06
 */

namespace Intra\Model;


use Intra\Core\AjaxMessage;
use Intra\Service\IntraDb;

/**
 * Class UserFactory
 * @package Intra\Model
 *
 * 복수의 User인스턴스 여러개 생성
 * 존재 확인
 */
class UserFactory
{
	static public function assertUserIdExist($id)
	{
		if (!self::isExistById($id)) {
			throw new AjaxMessage(
				'아이디가 없습니다. <a href="https://login.windows.net/common/oauth2/logout?response_type=code&client_id=***REMOVED***&resource=https://graph.windows.net&redirect_uri=http://intra.ridibooks.kr/usersession/login.azure">로그인 계정을 여러개 쓰는경우 로그인 해제</a>하고 다시 시도해주세요'
			);
		}
		if (!self::getbyId($id)->isValid()) {
			throw new AjaxMessage(
				'로그인 불가능한 계정입니다. 인프라팀에 확인해주세요. <a href="https://login.windows.net/common/oauth2/logout?response_type=code&client_id=***REMOVED***&resource=https://graph.windows.net&redirect_uri=http://intra.ridibooks.kr/usersession/login.azure">로그인 계정을 여러개 쓰는경우 로그인 해제</a>하고 다시 시도해주세요'
			);
		}
	}

	static public function isExistById($uid)
	{
		return !!self::getUidById($uid);
	}

	static public function getUidById($id)
	{
		$db = IntraDb::getGnfDb();
		return $db->sqlData('select uid from users where id = ?', $id);
	}

	private static function getbyId($id)
	{
		$uid = self::getUidById($id);
		return self::getByUid($uid);
	}

	public static function getByUid($uid)
	{
		return new UserModel($uid);
	}

	public static function isExist($uid)
	{
		$db = IntraDb::getGnfDb();
		return $db->sqlData('select count(*) from ? where uid = ?', sqlTable('users'), $uid);
	}

	/**
	 * @return UserModel[]
	 */
	public static function getAvailableUsers()
	{
		$db = IntraDb::getGnfDb();

		$where = array();
		$where['on_date'] = sqlLesserEqual(sqlNow());
		$where['off_date'] = sqlGreaterEqual(sqlNow());

		$uids = $db->sqlDatas('select uid from users where ? order by name', sqlWhere($where));
		return self::getUsersByUids($uids);
	}

	/**
	 * @param $uids
	 * @return \Intra\Model\UserModel[]
	 */
	public static function getUsersByUids($uids)
	{
		$ret = array();
		foreach ($uids as $uid) {
			$ret[] = new UserModel($uid);
		}
		return $ret;
	}

	/**
	 * @return UserModel[]
	 */
	public static function getAllUsers()
	{
		$uids = self::getAllUserUid();
		return self::getUsersByUids($uids);
	}

	/**
	 * @return array
	 */
	public static function getAllUserUid()
	{
		$db = IntraDb::getGnfDb();

		$uids = $db->sqlDatas('select uid from users order by name');
		return $uids;
	}

	public static function getManagerUsers()
	{
		$db = IntraDb::getGnfDb();

		$where = array();
		$where['on_date'] = sqlLesserEqual(sqlNow());
		$where['off_date'] = sqlGreaterEqual(sqlNow());
		$where['position'] = array('팀장', 'CTO', 'CEO', '부사장');

		$uids = $db->sqlDatas('select uid from users where ? order by name', sqlWhere($where));
		return self::getUsersByUids($uids);
	}
}
