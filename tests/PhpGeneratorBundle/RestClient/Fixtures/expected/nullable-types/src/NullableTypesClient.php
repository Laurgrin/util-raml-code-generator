<?php

namespace Paysera\Test\NullableTypesClient;

use Paysera\Test\NullableTypesClient\Entity as Entities;
use Fig\Http\Message\RequestMethodInterface;
use Paysera\Component\RestClientCommon\Entity\Entity;
use Paysera\Component\RestClientCommon\Client\ApiClient;

class NullableTypesClient
{
    private $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function withOptions(array $options)
    {
        return new NullableTypesClient($this->apiClient->withOptions($options));
    }

    /**
     * Get an item
     * GET /items/{id}
     *
     * @param string $id
     * @return Entities\Item
     */
    public function getItem($id)
    {
        $request = $this->apiClient->createRequest(
            RequestMethodInterface::METHOD_GET,
            sprintf('items/%s', rawurlencode($id)),
            null
        );
        $data = $this->apiClient->makeRequest($request);

        return new Entities\Item($data);
    }

    /**
     * Update an item
     * PUT /items/{id}
     *
     * @param string $id
     * @param Entities\Item $item
     * @return Entities\Item
     */
    public function updateItem($id, Entities\Item $item)
    {
        $request = $this->apiClient->createRequest(
            RequestMethodInterface::METHOD_PUT,
            sprintf('items/%s', rawurlencode($id)),
            $item
        );
        $data = $this->apiClient->makeRequest($request);

        return new Entities\Item($data);
    }
}
