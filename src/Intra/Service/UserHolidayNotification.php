<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-07-27
 * Time: 오후 12:16
 */

namespace Intra\Service;

use Intra\Model\UserFactory;
use Mailgun\Mailgun;

class UserHolidayNotification
{
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var \Intra\Model\HolidayRaw[]
	 */
	private $holiday_raws;
	private $action_type;

	/**
	 * @param $user
	 * @param $holiday_raws []
	 * @param $action_type
	 */
	public function __construct($user, $holiday_raws, $action_type)
	{
		$this->user = $user;
		$this->holiday_raws = $holiday_raws;
		$this->action_type = $action_type;
	}

	public function sendNotification()
	{
		$title = $this->getMailTitle();
		$ret = $this->sendMailNotification($title);
		$this->sendSlackNotification($title);

		return $ret->http_response_code == 200;
	}

	/**
	 * @return string
	 */
	private function getMailTitle()
	{
		$cost_sum = $this->getHolidaySumCost();
		$date_duration = $this->getHolidayDuration();
		$holiday_type = $this->getHolidayType();

		return "[{$this->action_type}][{$holiday_type}][{$date_duration}] {$this->user->getName()}님의 {$cost_sum}일 휴가사용신청";
	}

	/**
	 * @param $title
	 * @return \stdClass
	 * @throws \Exception
	 */
	private function sendMailNotification($title)
	{
		$emails = $this->getMailReceivers();
		$contents = $this->getMailContents();

		$mg = new Mailgun("***REMOVED***");
		$domain = "ridibooks.com";
		$ret = $mg->sendMessage(
			$domain,
			array(
				'from' => 'noreply@ridibooks.com',
				'to' => implode(', ', $emails),
				'subject' => $title,
				'text' => $contents
			)
		);
		return $ret;
	}

	/**
	 * @return array
	 */
	private function getMailReceivers()
	{
		$holiday_raw = $this->holiday_raws[0];
		$uids = array($holiday_raw->uid, $holiday_raw->manager_uid, $holiday_raw->keeper_uid);
		$uids = array_filter(array_unique($uids));
		$users = new Users;
		$user_list = $users->getUsersByUids($uids);

		$emails = array();
		foreach ($user_list as $user) {
			$emails[] = $user->getId() . '@ridi.com';
		}
		$emails[] = '***REMOVED***';
		$emails[] = '***REMOVED***';

		return array_unique(array_filter($emails));
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function getMailContents()
	{
		$holiday_raw = $this->holiday_raws[0];
		$keeper = UserFactory::getByUid($holiday_raw->keeper_uid);
		if ($keeper === null) {
			throw new \Exception('$keeper === null');
		}
		$request_date = date('Y-m-d');
		$holiday_duration = $this->getHolidayDuration();
		$holiday_sum_cost = $this->getHolidaySumCost();
		$holiday_type = $this->getHolidayType();

		$text = "요청일 : {$request_date}
요청자 : {$this->user->getName()}
종류 : {$holiday_type}
사용연차 : {$holiday_sum_cost}
사용날짜 : {$holiday_duration}
업무인수인계자 : {$keeper->getName()}
비상시연락처 : {$holiday_raw->phone_emergency}
비고 : {$holiday_raw->memo}
";
		return $text;
	}

	private function sendSlackNotification($message)
	{
		$data = "payload=" . json_encode(array("text" => $message));

		// You can get your webhook endpoint from your Slack settings
		$ch = curl_init("https://hooks.slack.com/services/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");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	/**
	 * @return mixed
	 */
	private function getHolidayType()
	{
		$holiday_type = $this->holiday_raws[0]->type;
		return $holiday_type;
	}

	/**
	 * @return string
	 */
	private function getHolidayDuration()
	{
		if (count($this->holiday_raws) == 1) {
			$date_duration = $this->holiday_raws[0]->date;
			return $date_duration;
		} else {
			$date_durations = array();
			foreach ($this->holiday_raws as $holiday_raw) {
				$date_durations[] = $holiday_raw->date;
			}
			$date_duration = implode(', ', $date_durations);
			return $date_duration;
		}
	}

	/**
	 * @return int
	 */
	private function getHolidaySumCost()
	{
		$cost_sum = 0;
		foreach ($this->holiday_raws as $holiday_raw) {
			$cost_sum += $holiday_raw->cost;
		}
		return $cost_sum;
	}
}
