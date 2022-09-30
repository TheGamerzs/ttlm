<?php

function dataFromJson(string $file)
{
    return json_decode(
        file_get_contents(
            base_path('app/JsonData/') . $file
        )
    );
}
