
## vendor-nullable-types-client

Provides methods to manipulate `NullableTypesClient` API.
It automatically authenticates all requests and maps required data structure for you.

#### Usage

This library provides `ClientFactory` class, which you should use to get the API client itself:

```php
use Paysera\Test\NullableTypesClient\ClientFactory;

$clientFactory = new ClientFactory([
    'base_url' => 'https://example.com/nullable-types/rest/v1/', // optional, in case you need a custom one.
    'mac' => [                                          // use this, if API requires Mac authentication.
        'mac_id' => 'my-mac-id',
        'mac_secret' => 'my-mac-secret',
    ],
    'basic' => [                                        // use this, if API requires Basic authentication.
        'username' => 'username',
        'password' => 'password',
    ],
    'oauth' => [                                        // use this, if API requires OAuth v2 authentication.
        'token' => [
            'access_token' => 'my-access-token',
            'refresh_token' => 'my-refresh-token',
        ],
    ],
    // other configuration options, if needed
]);

$nullableTypesClient = $clientFactory->getNullableTypesClient();
```

Please use only one authentication mechanism, provided by `Vendor`.

Now, that you have instance of `NullableTypesClient`, you can use following methods
### Methods

    
Get an item


```php

$result = $nullableTypesClient->getItem($id);
```
---

Update an item


```php
use Paysera\Test\NullableTypesClient\Entity as Entities;

$item = new Entities\Item();

$item->setId($id);
$item->setLabel($label);
$item->setNote($note);
$item->setEnabled($enabled);
$item->setUpdatedAt($updatedAt);
$item->setOwner($owner);
    
$result = $nullableTypesClient->updateItem($id, $item);
```
---


