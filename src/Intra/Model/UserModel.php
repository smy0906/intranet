<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 13. 12. 20
 * Time: 오전 11:49
 */

namespace Intra\Model;

use Intra\Core\BaseModel;
use Intra\Core\MsgException;
use Intra\Model\Base\DomainCacheModel;
use Intra\Service\IntraDb;
use Intra\Service\User\UserDto;

class UserModel extends BaseModel
{
	use DomainCacheModel;

	static private $table = 'users';

	/**
	 * @param $userJoinDto UserDto
	 * @return bool
	 * @throws MsgException
	 */
	public static function addUser($userJoinDto)
	{
		$users_row = $userJoinDto->exportDatabaseForJoin();

		$uid = IntraDb::getGnfDb()->sqlInsert(sqlTable(self::$table), $users_row);
		if (!$uid) {
			throw new MsgException('계정 추가가 실패하였습니다');
		}
		return true;
	}

	public static function isExistById($id)
	{
		return self::getDb()->sqlCount('users', ['id' => $id]);
	}

	public static function getRowWithUid($uid)
	{
		return self::cachingGetter(
			$uid,
			function () use ($uid) {
				return self::getDb()->sqlDict('select * from ? where uid = ?', sqlTable(self::$table), $uid);
			}
		);
	}

	public static function getRowWithUids(array $uids)
	{
		return self::cachingGetter(
			$uids,
			function () use ($uids) {
				return self::getDb()->sqlDicts(
					'select * from ? where ?', sqlTable(self::$table), sqlWhere(['uid' => $uids])
				);
			}
		);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function convertUidFromId($id)
	{
		return self::getDb()->sqlData('select uid from ? where id = ?', sqlTable(self::$table), $id);
	}

	public static function isExistByUid($uid)
	{
		return self::getDb()->sqlData('select count(*) from users where uid = ?', $uid);
	}

	public static function getRowsAvailable()
	{
		$where = [];
		$where['on_date'] = sqlLesserEqual(sqlNow());
		$where['off_date'] = sqlGreaterEqual(sqlNow());

		return self::getDb()->sqlDicts('select * from users where ? order by name', sqlWhere($where));
	}

	public static function getAllRows()
	{
		return self::getDb()->sqlDicts('select * from users order by name');
	}

	public static function getRowsManager()
	{
		$where = [];
		$where['on_date'] = sqlLesserEqual(sqlNow());
		$where['off_date'] = sqlGreaterEqual(sqlNow());
		$where['position'] = ['팀장', 'CTO', 'CEO', '부사장'];

		return self::getDb()->sqlDicts('select * from users where ? order by name', sqlWhere($where));
	}

	public static function updateExtra($uid, $extra_update)
	{
		self::invalidateCache($uid);

		$where = ['uid' => $uid];
		self::getDb()->sqlUpdate(self::$table, $extra_update, $where);

		return 1;
	}
}
