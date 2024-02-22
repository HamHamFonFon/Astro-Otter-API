<?php

namespace App\Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Item\Image;
use AstrobinWs\Services\GetImage;
use AstrobinWs\Services\GetUser;
use JsonException;

final class Astrobin
{
    public const IMG_DEFAULT = '/build/images/default.png';
    public const IMG_LARGE_DEFAULT = '/build/images/default_large.jpg';

    private GetImage $imageWs;

    private GetUser $userWs;

    public function __construct(
        private readonly string $astrobinApiKey,
        private readonly string $astrobinApiSecret
    )
    {
        $this->imageWs = new GetImage($this->astrobinApiKey, $this->astrobinApiSecret);
        $this->userWs = new GetUser($this->astrobinApiKey, $this->astrobinApiSecret);
    }

    public function getAstrobinImage(?string $astrobinId): Image|AstrobinResponse
    {
        $defautImage = new Image();
        $defautImage->url_hd = self::IMG_LARGE_DEFAULT;
        $defautImage->url_regular = self::IMG_LARGE_DEFAULT;
        $defautImage->user = null;
        $defautImage->title = null;

        try {
            $astrobinImage = (!is_null($astrobinId)) ? $this->imageWs->getById($astrobinId) : basename(self::IMG_LARGE_DEFAULT);
            if ($astrobinImage instanceof AstrobinError) {
                return $defautImage;
            }
            if ($astrobinImage instanceof AstrobinResponse) {
                return $astrobinImage;
            }

            return $defautImage;
        } catch (WsResponseException | \Exception $e) {
            return $defautImage;
        }
    }

    /**
     * @param string $username
     * @return AstrobinResponse|null
     */
    public function getAstrobinUser(string $username): ?AstrobinResponse
    {
        try {
            return $this->userWs->getByUsername($username, 1);
        } catch (WsResponseException|JsonException|WsException|\ReflectionException $e) {
            return null;
        }
    }
}
