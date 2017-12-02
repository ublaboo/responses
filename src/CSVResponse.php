<?php

/**
 * @copyright   Copyright (c) 2015 ublaboo <ublaboo@paveljanda.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Ublaboo
 */

namespace Ublaboo\Responses;

use Nette;


/**
 * CSV file download response
 */
class CSVResponse implements Nette\Application\IResponse
{

	use Nette\SmartObject;

	/**
	 * @var string
	 */
	protected $contentType = 'application/octet-stream';

	/**
	 * @var string
	 */
	protected $delimiter;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $output_encoding;

	/**
	 * @var boolean
	 */
	protected $include_bom;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $headers = [
		'Expires' => '0',
		'Cache-Control' => 'no-cache',
		'Pragma' => 'Public'
	];


	/**
	 * @param array           $data
	 * @param string          $name
	 * @param string          $output_encoding
	 * @param string          $delimiter
	 */
	public function __construct(
		$data,
		$name = 'export.csv',
		$output_encoding = 'utf-8', # may be often windows-1250 on windows machines
		$delimiter = ';',
		$include_bom = FALSE
	) {
		if (strpos($name, '.csv') === FALSE) {
			$name = "$name.csv";
		}

		$this->name = $name;
		$this->delimiter = $delimiter;
		$this->data = $data;
		$this->output_encoding = $output_encoding;
		$this->include_bom = $include_bom;
	}


	/**
	 * Sends response to output.
	 * @param  Nette\Http\IRequest  $httpRequest
	 * @param  Nette\Http\IResponse $httpResponse
	 * @return void
	 */
	public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
	{
		/**
		 * Disable tracy bar
		 */
		if (class_exists('\Tracy\Debugger') && property_exists('\Tracy\Debugger', 'productionMode')) {
			\Tracy\Debugger::$productionMode = TRUE;
		}

		/**
		 * Set Content-Type header
		 */
		$httpResponse->setContentType($this->contentType, $this->output_encoding);

		/**
		 * Set Content-Disposition header
		 */
		$httpResponse->setHeader('Content-Disposition', 'attachment'
			. '; filename="' . $this->name . '"');
		/*. '; filename*=' . $this->output_encoding . '\'\'' . rawurlencode($this->name));*/

		/**
		 * Set other headers
		 */
		foreach ($this->headers as $key => $value) {
			$httpResponse->setHeader($key, $value);
		}

		if (function_exists('ob_start')) {
			ob_start();
		}

		/**
		 * Output data
		 */
		if($this->include_bom && strtolower($this->output_encoding) == 'utf-8'){
			echo  b"\xEF\xBB\xBF";
		}
		$delimiter = '"' . $this->delimiter . '"';

		foreach ($this->data as $row) {
			if (strtolower($this->output_encoding) == 'utf-8') {
				echo('"' . implode($delimiter, (array) $row) . '"');
			} else {
				echo(iconv('UTF-8', $this->output_encoding, '"' . implode($delimiter, (array) $row) . '"'));
			}

			echo "\r\n";
		}

		if (function_exists('ob_end_flush')) {
			ob_end_flush();
		}
	}

}
