<?php

namespace colibri\base;

use Yii;
use yii\base\Component;

class Settings extends Component
{
    /**
     * @var string The settings directory
     */
    protected $_settingsPath;

    protected $_settings = [];

    /**
     * Returns the settings directory.
     * @return string the settings directory.
     */
    public function getSettingsPath()
    {
        if ($this->_settingsPath === null) {
            $this->_settingsPath = Yii::getAlias('@app/settings');
        }
        return $this->_settingsPath;
    }

    /**
     * Sets the settings directory.
     * @param string $path the settings directory. This can be either a directory name or a path alias.
     * @throws InvalidParamException if the directory does not exist.
     */
    public function setConfigPath($path)
    {
        $path = Yii::getAlias($path);
        $p = realpath($path);
        if ($p !== false && is_dir($p)) {
            $this->_settingsPath = $p;
        } else {
            throw new InvalidParamException("The directory does not exist: $path");
        }
    }

    public function saveSection($data, $section='base')
    {
        $buffer = "<?php\nreturn [\n";

        foreach ($data as $k => $v) {
            $cleanValue = addslashes(trim($v));
            $buffer .= "    '{$k}' => '{$cleanValue}',\n";
        }
        
        $buffer .= "];";

        return file_put_contents($this->getSettingsPath() . '/' . $section . '.php' , $buffer);
    }

    public function getSection($section='default')
    {
        if (!isset($this->_settings[$section])) {
            $path = $this->getSettingsPath() . '/' . 'settings_' . $section . '.php';
            if (file_exists($path)) {
                $this->_settings[$section] = require $path;
            } else {
                $this->_settings[$section] = [];
            }
        }
        return $this->_settings[$section];
    }

    public function get($key, $default='', $section='default')
    {
        $data = $this->getSection($section);

        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    public function set($key, $value, $section='default')
    {
        $this->_settings[$section][$key] = $value;
    }
}