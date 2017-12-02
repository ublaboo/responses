<?php

/**
 * @copyright   Copyright (c) 2015 ublaboo <ublaboo@paveljanda.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Ublaboo
 */

namespace Ublaboo\Responses;

use Nette;
use Nette\Utils\Image;

class ImageResponse implements Nette\Application\IResponse
{

	use Nette\SmartObject;

	/**
	 * @var Image|string
	 */
	private $image;

	/**
	 * @param \Nette\Image|string
	 */
	public function __construct($image)
	{
		if (!$image instanceof Image && !file_exists($image)) {
			throw new \Nette\InvalidArgumentException('Image must be Nette\Image or file path');
		}

		$this->image = $image;
	}


	/**
	 * @param \Nette\Http\IRequest
	 * @param \Nette\Http\IResponse
	 */
	public function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse)
	{
		if ($this->image instanceof Nette\Utils\Image) {
			$image = $this->image;
		} else {
			$image = Nette\Utils\Image::fromFile($this->image);
		}

		$image->send();
	}

}
