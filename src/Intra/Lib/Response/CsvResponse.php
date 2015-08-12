<?php
/**
 * Created by PhpStorm.
 * User: ridi
 * Date: 14. 4. 1
 * Time: 오후 2:13
 */

namespace Intra\Lib\Response;

use Symfony\Component\HttpFoundation\Response;

class CsvResponse extends Response
{
	protected $data;
	protected $callback;

	/**
	 * Constructor.
	 *
	 * @param mixed $data The response data
	 * @param string $filename
	 * @param integer $status The response status code
	 * @param array $headers An array of response headers
	 */
	public function __construct($data = null, $filename = null, $status = 200, $headers = array())
	{
		parent::__construct('', $status, $headers);

		if (null === $data) {
			$data = new \ArrayObject();
		}
		if (null === $filename) {
			$this->filename = "data_" . date('Ymd');
		} else {
			$this->filename = $filename;
		}

		$this->setData($data);
	}

	/**
	 * Sets the data to be sent as json.
	 *
	 * @param mixed $data
	 *
	 * @return CsvResponse
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setData($data = array())
	{
		foreach ($data as $k => $v) {
			if (is_object($v)) {
				$v = get_object_vars($v);
			} elseif (is_scalar($v)) {
				$v = array($v);
			}
			foreach ($v as $k2 => $v2) {
				$v[$k2] = iconv('utf-8', 'cp949', $v2);
			}
			$data[$k] = $v;
		}

		$this->data = $this->encodeCsv($data);

		return $this->update();
	}

	public function encodeCsv($input)
	{
		$ret = '';
		foreach ($input as $row) {
			foreach ($row as $dat) {
				$ret .= '"' . str_replace('"', '""', $dat) . '",';
			}
			$ret .= "\n";
		}
		return $ret;
	}

	/**
	 * Updates the content and headers according to the json data and callback.
	 *
	 * @return CsvResponse
	 */
	protected function update()
	{
		$this->headers->set('Content-Type', 'application/vnd.ms-excel; charset=cp949');
		$this->headers->set('Content-Disposition', "attachment; filename=" . $this->filename . ".csv");

		return $this->setContent($this->data);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function create($data = null, $filename = null, $status = 200, $headers = array())
	{
		return new static($data, $filename, $status, $headers);
	}
}
