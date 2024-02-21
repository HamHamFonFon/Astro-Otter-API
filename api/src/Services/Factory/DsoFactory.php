<?php

namespace App\Services\Factory;

use App\Dto\DsoRepresentation;
use App\Dto\DTOInterface;
use App\Model\Dso;
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
     */
    public function buildDto(array $document): ?\Generator
    {
        $locale = (new Session())->get('_locale') ?? 'en';
        $idMd5 = md5(sprintf('%s_%s', $document['id'], $locale));
        if ($dso = $this->getDtoFromCache($idMd5)) {
            dump($dso);
        } else {
            $dso = $this->buildDtoFromDocument($document);
            $dso->setTypeLabel($this->translator->trans(sprintf('type.%s', $dso->getType())));

            $this->saveDtoInCache($dso);
            dump($dso); die();


        }


        yield $dso;
    }

    public function buildListDto(array $listDocumentsId)
    {
        // TODO: Implement buildListDto() method.
    }


}
