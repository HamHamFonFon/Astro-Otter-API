<?php

namespace App\Services\Factory;

use App\Dto\ConstellationRepresentation;
use App\Dto\DsoRepresentation;
use App\Dto\DTOInterface;
use App\Model\Dso;
use App\Services\Astrobin;
use App\Services\Cache\Redis;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\Item\Image;
use AstrobinWs\Response\DTO\Item\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractFactory
{
    abstract protected function getEsModel(): string;
    abstract protected function getDto(): string;

    public function __construct(
        private Redis $redisAdapter,
        protected TranslatorInterface $translator,
        protected Astrobin $astrobin
    ) {}

    /**
     * Transform ES document (array) into DTO
     * @param array $document
     * @return DTOInterface
     */
    protected function buildDtoFromDocument(array $document): DTOInterface
    {
        $model = $this->getEsModel();
        $dto = $this->getDto();

        $normalizer = [new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())];
        $encoder = [new JsonEncode()];
        $serializer = new Serializer($normalizer, $encoder);
        $hydratedEntity = $serializer->denormalize($document, $model, 'json');

        $locale = (new Session())->get('_locale');
        return new $dto($hydratedEntity, $locale);
    }

    protected function getDtoFromCache(string $idMd5): ?DTOInterface
    {
        if ($this->redisAdapter->hasItem($idMd5)) {
            return null;
        }

        $serializedDto = $this->redisAdapter->getItem($idMd5);
        if (is_null($serializedDto)) {
            $this->redisAdapter->deleteItem($idMd5);
            return null;
        }

        $unserializedDto = unserialize($serializedDto, [
            'allowed_classes' => [
                DsoRepresentation::class,
                ConstellationRepresentation::class,
                Image::class,
                User::class,
                AstrobinError::class,
                Dso::class,
            ]
        ]);

        return ($unserializedDto instanceof DTOInterface) ? $unserializedDto : null;
    }

    protected function saveDtoInCache(DTOInterface $dto): void
    {
        $key = md5(sprintf('%s_%s', $dto->getId(), $dto->getLocale()));
        $this->redisAdapter->saveItem($key, serialize($dto));
    }
}
