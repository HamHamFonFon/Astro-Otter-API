<?php

namespace App\Services\Factory;

use App\Dto\DsoRepresentation;
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
        $dso = $this->getDtoFromCache($idMd5);

        if (is_null($dso)) {
            $dso = $this->buildDtoFromDocument($document);
            $dso
                ->setTypeLabel($this->translator->trans(sprintf('type.%s', $dso->getType())));

            try {
                $astrobinImg = $this->astrobin->getAstrobinImage($dso->getAstrobinId());
                $astrobinUser = $this->astrobin->getAstrobinUser($astrobinImg->user);

                $dso
                    ->setAstrobin($astrobinImg)
                    ->setAstrobinUser($astrobinUser);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }

            try {
                $constellation = null;
                $dso
                    ->setConstellation($constellation);
            } catch (\Exception $e) { }
//            $this->saveDtoInCache($dso);
        }

        yield $dso;
    }

    public function buildListDto(array $listDocuments)
    {
        // TODO: Implement buildListDto() method.
    }


}
