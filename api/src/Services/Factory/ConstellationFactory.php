<?php

namespace App\Services\Factory;

use App\Dto\DTOInterface;
use App\Services\Factory\FactoryInterface;

class ConstellationFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * @return string
     */
    protected function getEsModel(): string
    {
        // TODO: Implement getEsModel() method.
    }

    /**
     * @return string
     */
    protected function getDto(): string
    {
        // TODO: Implement getDto() method.
    }

    /**
     * @param array $document
     * @return mixed
     */
    public function buildDto(array $document)
    {
        // TODO: Implement buildDto() method.
    }

    /**
     * @param array $listDocumentsId
     * @return mixed
     */
    public function buildListDto(array $listDocumentsId)
    {
        // TODO: Implement buildListDto() method.
    }


}
