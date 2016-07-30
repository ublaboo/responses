<?php

/**
 * @copyright   Copyright (c) 2015 ublaboo <ublaboo@paveljanda.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Ublaboo
 */

namespace Ublaboo\Responses;

use Nette;
use Nette\Application;

/**
 * Add JSON_PRETTY_PRINT option to Nette\Application\Responses\JsonResponse
 */
class JsonPrettyResponse extends Nette\Application\Responses\JsonResponse
{

	private $code = Nette\Http\IResponse::S200_OK;


	/**
	 * @param int $code
	 * @return void
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}


	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}


	/**
	 * {inheritDoc}
	 */
	public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
	{
		$httpResponse->setContentType($this->getContentType(), 'utf-8');
		$httpResponse->setExpiration(FALSE);
		$httpResponse->setCode($this->code);

		echo Nette\Utils\Json::encode($this->getPayload(), Nette\Utils\Json::PRETTY);
	}

}
