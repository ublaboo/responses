<?php

/**
 * @copyright   Copyright (c) 2015 ublaboo <ublaboo@paveljanda.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Ublaboo
 */

namespace Ublaboo\Responses;

use Nette;

/**
 * Add JSON_PRETTY_PRINT option to Nette\Application\Responses\JsonResponse
 */
class JsonPrettyResponse extends Nette\Application\Responses\JsonResponse
{

	/**
	 * {inheritDoc}
	 */
	public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
	{
		$httpResponse->setContentType($this->contentType);
		$httpResponse->setExpiration(FALSE);

		echo Nette\Utils\Json::encode($this->payload, Nette\Utils\Json::PRETTY);
	}

}
