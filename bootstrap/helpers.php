<?php

function dataFromJson(string $file)
{
    return json_decode(
        file_get_contents(
            base_path('app/JsonData/') . $file
        )
    );
}

function buildFakeStorageApiResponse(array $items, string $storageName = 'biz_yellowjack'): string
{
    $response = [
        'storages' => null,
        'code' => 200,
        'user_id' => 645753
    ];

    $response['storages'][] = [
        'inventory' => null,
        'name' => $storageName
    ];

    foreach($items as $name => $amount) {
        $response['storages'][0]['inventory'][$name] = ['amount' => $amount];
    }

    return json_encode($response);
}
