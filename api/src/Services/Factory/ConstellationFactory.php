<?php

namespace App\Services\Factory;

use App\Dto\ConstellationRepresentation;
use App\Model\Constellation;
use Generator;
use Symfony\Component\HttpFoundation\Session\Session;

class ConstellationFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * @return string
     */
    protected function getEsModel(): string
    {
        return Constellation::class;
    }

    /**
     * @return string
     */
    protected function getDto(): string
    {
        return ConstellationRepresentation::class;
    }

    /**
     * @param array $document
     * @return Generator
     */
    public function buildDto(array $document): Generator
    {
        $locale = (new Session())->get('_locale') ?? 'en';
        $idMd5 = md5(sprintf('%s_%s', $document['id'], $locale));
        $constellation = $this->getDtoFromCache($idMd5);
        if (is_null($constellation)) {
            $constellation = $this->buildDtoFromDocument($document);
            $this->saveDtoInCache($constellation);
        }

        yield $constellation;
    }

    /**
     * @param array $listDocuments
     * @return Generator
     */
    public function buildListDto(array $listDocuments): Generator
    {
        foreach ($listDocuments as $document) {
            yield from $this->buildDto($document);
        }
    }
}
