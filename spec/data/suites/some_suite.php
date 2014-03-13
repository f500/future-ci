<?php

return array(
    'parameters' => array(
        'foo' => array('bar', 'baz')
    ),
    'suite'      => array(
        'name' => 'Some Suite',
        'path' => '%root_dir%/some/path',
        'foo'  => '%foo%'
    )
);
