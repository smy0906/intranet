<?php
namespace Intra\Service\Press;

use Intra\Core\JsonDto;
use Intra\Model\Press as PressModel;
use Intra\Service\User\UserDto;
use Intra\Service\User\UserSession;

class Press
{
    private $user;

    /**
     * @param $user UserDto
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $press = $this->getAll();

        return $this->makeJsonRespone($press);
    }

    public function add($date, $media, $title, $link_url, $note)
    {
        try {
            $press = new PressModel();

            $press->date = $date;
            $press->media = $media;
            $press->title = $title;
            $press->link_url = $link_url;
            $press->note = $note;

            $press->save();
        } catch (\Exception $e) {
            return '자료를 추가할 수 없습니다. 다시 확인해 주세요';
        }

        return true;
    }

    public function del($press_id)
    {
        try {
            PressModel::destroy($press_id);
        } catch (\Exception $e) {
            return '삭제가 실패했습니다!';
        }

        return true;
    }

    public function edit($press_id, $key, $value)
    {
        try {
            PressModel::where('id', $press_id)->update([$key => $value]);
            return $value;
        } catch (\Exception $e) {
            return '수정을 실패했습니다!';
        }
    }

    public function getAll() {
        try {
            return PressModel::orderBy('date', 'desc')->get();
        } catch (\Exception $e) {
            return '데이터 불러오기를 실패했습니다!';
        }
    }

    public function getAllPress()
    {
        $press = $this->getAll();

        return $this->makeJsonRespone($press);
    }

    public function getPressByPage($page, $ITEMS_PER_PAGE)
    {
        $press = PressModel::orderBy('date', 'desc')->skip(($page - 1) * $ITEMS_PER_PAGE)->take($ITEMS_PER_PAGE)->get();
        $count = $this->getPressCount();

        return $this->makeJsonRespone($press, $count);
    }

    private function getPressCount()
    {
        return PressModel::count();
    }

    private function makeJsonRespone($press, $count = null) {
        $json_dto = new JsonDto();

        $json_dto->data = [
            'user' => $this->user,
            'press' => $press,
            'manager' => UserSession::isPressManager()
        ];

        if (!is_null($count)) {
            $json_dto->data['count'] = $count;
        }

        return json_encode(
            (array)$json_dto
        );
    }
}
