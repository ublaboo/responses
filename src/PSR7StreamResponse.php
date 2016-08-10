<?php

/**
 * @copyright   Copyright (c) 2015 ublaboo <ublaboo@paveljanda.com>
 * @author      Martin Procházka <juniwalk@outlook.cz>
 * @package     Ublaboo
 */

namespace Ublaboo\Responses;

use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Psr\Http\Message\StreamInterface;

/**
 * File download response from PSR7 stream.
 */
final class PSR7StreamResponse implements \Nette\Application\IResponse
{
	/**
	 * Instance of the response stream.
	 * @var StreamInterface
	 */
	private $stream;

	/**
	 * Name of the downloading file.
	 * @var string
	 */
	private $name;

	/**
	 * Content-Type of the contents.
	 * @var string
	 */
	private $contentType;


	/**
	 * @param  StreamInterface  $stream  PSR7   Stream instance
	 * @param  string           $name           Imposed file name
	 * @param  string           $contentType    MIME content type
	 */
	public function __construct(StreamInterface $stream, $name, $contentType = NULL)
	{
		$this->stream = $stream;
		$this->name = $name;
		$this->contentType = $contentType ?: 'application/octet-stream';
	}


	/**
	 * Returns the stream to a downloaded file.
	 * @return StreamInterface
	 */
	public function getStream()
	{
		return $this->stream;
	}


	/**
	 * Returns the file name.
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Returns the MIME content type of a downloaded file.
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}


	/**
	 * Sends response to output.
	 * @param IRequest   $request
	 * @param IResponse  $response
	 */
	public function send(IRequest $request, IResponse $response)
	{
		// Set response headers for the file download
		$response->setHeader('Content-Length', $this->stream->getSize());
		$response->setHeader('Content-Type', $this->contentType);
		$response->setHeader('Content-Disposition', 'attachment; filename="'.$this->name.'";');

		while (!$this->stream->eof()) {
			echo $this->stream->read(4e6);
		}

		$this->stream->close();
	}
}
