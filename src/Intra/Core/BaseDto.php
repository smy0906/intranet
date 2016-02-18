<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 2015-08-15
 * Time: 오전 12:20
 */

namespace Intra\Core;

use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

class BaseDto
{
	protected function __construct()
	{
	}

	/**
	 * Request class를 이용하여 클래스를 초기화한다.
	 * @param Request $request
	 */
	public function initFromRequest($request)
	{
		$reflect = new ReflectionClass(get_called_class());
		$properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
		foreach ($properties as $property) {
			$property->setValue($this, $request->get($property->getName()));
		}
	}

	/**stdClass 일 경우 클래스 초기화
	 * @param \stdClass $stdClass
	 */
	public function initFromStdClass($stdClass)
	{
		$reflect = new ReflectionClass(get_called_class());
		$properties = $reflect->getDefaultProperties();
		foreach ($properties as $key => $value) {
			$this->{$key} = $stdClass->{$key};
		}
	}

	/**
	 * 배열을 이용하여 클래스를 초기화한다
	 * @param array $array
	 */
	public function initFromArray($array)
	{
		$reflect = new ReflectionClass(get_called_class());
		$properties = $reflect->getDefaultProperties();
		foreach ($properties as $key => $value) {
			if (array_key_exists($key, $array)) {
				$this->{$key} = $array[$key];
			}
		}
	}

	/**interface의 function을 가져와 클래스를 초기화 한다.
	 * @param $reader
	 */
	public function initFromInterface($reader)
	{
		$reflect = new ReflectionClass(get_called_class());
		$default_properties = $reflect->getDefaultProperties();
		foreach ($default_properties as $key => $value) {
			if (method_exists($reader, $key)) {
				$this->{$key} = $reader->$key();
			}
		}
	}

	/**
	 * 함수를 호출한 클래스의 기본 멤버변수만큼(동적, 부모 멤버변수 제외) 리턴한다.
	 * 단, Null값을 가진 column은 제외한다.
	 * @return array
	 */
	public function exportAsArrayExceptNull()
	{
		$columns = $this->exportAsArray();

		foreach ($columns as $key => $value) {
			if ($value === null) {
				unset($columns[$key]);
			}
		}

		return $columns;
	}

	/**
	 * 함수를 호출한 클래스의 기본 멤버변수만을(동적, 부모 멤버변수 제외) 리턴한다.
	 * @return array
	 */
	public function exportAsArray()
	{
		$reflect = new ReflectionClass(get_called_class());
		$default_properties = $reflect->getDefaultProperties();

		$columns = [];
		foreach ($default_properties as $key => $value) {
			$columns = array_merge($columns, [$key => $this->{$key}]);
		}

		return $columns;
	}

	public function exportAsArrayByKeys(array $keys)
	{
		$columns = [];
		foreach ($keys as $key) {
			$columns[$key] = $this->{$key};
		}

		return $columns;
	}
}
