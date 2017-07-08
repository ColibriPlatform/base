<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base;

/**
 * Settings class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class Settings extends \pheme\settings\components\Settings
{

    /**
     * {@inheritDoc}
     * @see \pheme\settings\components\Settings::get()
     */
    public function get($key, $section = null, $default = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key, 2);
            if (count($pieces) > 1) {
                $section = $pieces[0];
                $key = $pieces[1];
            } else {
                $section = '';
            }
        }

        $data = $this->getRawConfig();

        if (isset($data[$section][$key][0])) {
            if (in_array($data[$section][$key][1], ['object', 'boolean', 'bool', 'integer', 'int', 'float', 'string', 'array'])) {
                settype($data[$section][$key][0], $data[$section][$key][1]);
            }
        } else {
            $data[$section][$key][0] = $default;
        }
        return $data[$section][$key][0];
    }
}
