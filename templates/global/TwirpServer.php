<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!

namespace {{ .Namespace }};

use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twirp\ErrorCode;
use Twirp\TwirpError;

/**
 * Common server implementation.
 */
abstract class TwirpServer
{
    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var StreamFactory
     */
    protected $streamFactory;

    /**
     * @param MessageFactory|null $messageFactory
     * @param StreamFactory|null  $streamFactory
     */
    public function __construct(
        MessageFactory $messageFactory = null,
        StreamFactory $streamFactory = null
    ) {
        if ($messageFactory === null) {
            $messageFactory = MessageFactoryDiscovery::find();
        }

        if ($streamFactory === null) {
            $streamFactory = StreamFactoryDiscovery::find();
        }

        $this->messageFactory = $messageFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Used when there is no route for a request.
     *
     * @param ServerRequestInterface $req
     *
     * @return TwirpError
     */
    final protected function noRouteError(ServerRequestInterface $req)
    {
        $msg = sprintf('no handler for path "%s', $req->getUri()->getPath());

        return $this->badRouteError($msg, $req->getMethod(), $req->getUri()->getPath());
    }

    /**
     * Used when the twirp server cannot route a request.
     *
     * @param string $msg
     * @param string $method
     * @param string $url
     *
     * @return TwirpError
     */
    final protected function badRouteError($msg, $method, $url)
    {
        $e = TwirpError::newError(ErrorCode::BadRoute, $msg);
        $e = $e->withMeta('twirp_invalid_route', $method . ' ' . $url);

        return $e;
    }

    /**
     * Writes Twirp errors in the response and triggers hooks.
     *
     * @param array        $ctx
     * @param \Twirp\Error $e
     *
     * @return ResponseInterface
     */
    protected function writeError(array $ctx, \Twirp\Error $e)
    {
        $statusCode = ErrorCode::serverHTTPStatusFromErrorCode($e->code());

        $body = $this->streamFactory->createStream(json_encode([
            'code' => $e->code(),
            'msg' => $e->msg(),
            'meta' => $e->metaMap(),
        ]));

        return $this->messageFactory
            ->createResponse($statusCode)
            ->withHeader('Content-Type', 'application/json') // Error responses are always JSON (instead of protobuf)
            ->withBody($body);
    }
}
