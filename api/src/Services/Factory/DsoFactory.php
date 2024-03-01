<?php

namespace App\Services\Factory;

use App\Dto\ConstellationRepresentation;
use App\Dto\DsoRepresentation;
use App\Dto\DTOInterface;
use App\Model\Constellation;
use App\Model\Dso;
use AstrobinWs\Response\DTO\AstrobinResponse;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\Session;

class DsoFactory extends AbstractFactory implements FactoryInterface
{
    protected function getEsModel(): string
    {
        return Dso::class;
    }

    protected function getDto(): string
    {
        return DsoRepresentation::class;
    }

    /**
     * @throws \JsonException
     * @throws InvalidArgumentException
     */
    public function buildDto(array $document): \Generator
    {
        $locale = (new Session())->get('_locale') ?? 'en';
        $idMd5 = md5(sprintf('%s_%s', $document['id'], $locale));
        $dso = $this->getDtoFromCache($idMd5);

        if (is_null($dso)) {
            $dso = $this->buildDtoFromDocument($document);
            $dso->setTypeLabel($this->translator->trans(sprintf('type.%s', $dso->getType())));
            // Astrobin
            try {
                if (!is_null($dso->getAstrobinId())) {
                    $astrobinImg = $this->astrobin->getAstrobinImage((string)$dso->getAstrobinId());
                    if ($astrobinImg instanceof AstrobinResponse) {
                        $dso->setAstrobin($astrobinImg);
                        //$astrobinUser = $this->astrobin->getAstrobinUser($astrobinImg->user);
                    }
                }
            } catch (\Exception $e) { }

            // Add constellation
            if (array_key_exists('constellation', $document) && isset($document['constellation'])) {
                $dso = $this->addConstellation($dso, $document['constellation']);
            }
            try {
                $this->saveDtoInCache($idMd5, $dso);
            } catch (InvalidArgumentException $e) {}
        }

        yield $dso;
    }

    private function addConstellation(
        DTOInterface $dto,
        array $constellationDocument
    ): DTOInterface
    {
        $nestedDto = $this->buildDtoFromDocument($constellationDocument, Constellation::class, ConstellationRepresentation::class);
        if ($nestedDto instanceof ConstellationRepresentation) {
            $dto->setConstellation($nestedDto);
        }
        return $dto;
    }
}
