<?php

namespace App\Services\Factory;

use App\Dto\ConstellationRepresentation;
use App\Dto\DsoRepresentation;
use App\Model\Constellation;
use App\Model\Dso;
use AstrobinWs\Response\DTO\AstrobinResponse;
use Nette\Utils\Image;
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
    public function buildDto(array $document)
    {
        $locale = (new Session())->get('_locale') ?? 'en';

        $idMd5 = md5(sprintf('%s_%s', $document['id'], $locale));
        $dso = $this->getDtoFromCache($idMd5);

        if (is_null($dso)) {
            $dso = $this->buildDtoFromDocument($document);
            $dso
                ->setConstellation(null)
                ->setTypeLabel($this->translator->trans(sprintf('type.%s', $dso->getType())))
            ;
            try {
                if (!is_null($dso->getAstrobinId())) {
                    $astrobinImg = $this->astrobin->getAstrobinImage((string)$dso->getAstrobinId());
                    if ($astrobinImg instanceof AstrobinResponse) {
                        $dso->setAstrobin($astrobinImg);
                        //$astrobinUser = $this->astrobin->getAstrobinUser($astrobinImg->user);
                    }
                }
            } catch (\Exception $e) {
                dump($e->getMessage());
            }

            $this->saveDtoInCache($idMd5, $dso);
        }

        yield $dso;
    }

    /**
     * @throws \JsonException
     */
    public function buildListDto(array $listDocuments): \Generator
    {
        foreach ($listDocuments as $document) {
//            $dso = $this->buildDto($document);
            yield from $this->buildDto($document);
        }

    }
}
