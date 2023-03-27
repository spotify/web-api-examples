<?php

declare(strict_types=1);

namespace SpotifyWebAPI;

class SpotifyWebAPIException extends \Exception
{
    public const TOKEN_EXPIRED = 'The access token expired';
    public const RATE_LIMIT_STATUS = 429;

    /**
     * The reason string from a player request's error object.
     *
     * @var string
     */
    private $reason;

    /**
     * Returns the reason string from a player request's error object.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Returns whether the exception was thrown because of an expired access token.
     *
     * @return bool
     */
    public function hasExpiredToken()
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }

    /**
     * Returns whether the exception was thrown because of rate limiting.
     *
     * @return bool
     */
    public function isRateLimited()
    {
        return $this->getCode() === self::RATE_LIMIT_STATUS;
    }

    /**
     * Set the reason string.
     *
     * @param string $reason
     *
     * @return void
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }
}
