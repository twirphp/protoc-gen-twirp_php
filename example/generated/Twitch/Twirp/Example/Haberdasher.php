<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: service.proto

namespace Twitch\Twirp\Example;

use Twirp\Error;

/**
 * A Haberdasher makes hats for clients.
 *
 * Generated from protobuf service <code>twitch.twirp.example.Haberdasher</code>
 */
interface Haberdasher
{
    /**
     * MakeHat produces a hat of mysterious, randomly-selected color!
     *
     * Generated from protobuf method <code>twitch.twirp.example.Haberdasher/MakeHat</code>
     *
     * @param array $ctx
     * @param \Twitch\Twirp\Example\Size $req
     *
     * @return \Twitch\Twirp\Example\Hat
     *
     * @throws Error
     */
    public function MakeHat(array $ctx, \Twitch\Twirp\Example\Size $req);
}
