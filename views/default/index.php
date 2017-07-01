<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */
?>

<h1>Home</h1>

<p>This is the default homepage. You can override it if you enable theming in your application configuration.</p>

<pre>
return [
    'components' => [
        'view' => [
            'basePath' => '@app/themes/yourtheme',
            'baseUrl' => '@web/themes/yourtheme',
            'pathMap' => [
                '@colibri/base/views' => '@app/themes/yourtheme',
             ],
        ],
    ],
];
</pre>

<p>
See <a href="http://www.yiiframework.com/doc-2.0/guide-output-theming.html">Theming guide</a>
to have more information about theming.
</p>