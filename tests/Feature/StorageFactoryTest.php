<?php

it('returns a pretty name for a storage id', function () {

    $return = \App\TT\StorageFactory::getPrettyName('biz_granny');
    expect($return)->toBe('Grandma\'s House');

});

it('returns a pretty name for faction storages', function () {

    $return = \App\TT\StorageFactory::getPrettyName('faq_1999');
    expect($return)->toBe('Faction - 1999');

});
