<?php
namespace colibri\base;

/**
 * Application is the base class for all colibri application classes.
 *
 * @property \colibri\components\Settings $settings The settings application component. This property is read only.
 *          
 *          
 * @author Sylvain Philip <contact@sphilip.com>
 * @since 2.0
 */
class Application extends \yii\web\Application
{

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'default';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'colibri\\base\\controllers';

    /**
     * @inheritdoc
     */
    protected function bootstrap()
    {

        if (!file_exists($this->basePath . '/.env')) {
            if ($this->getRequest()->url != $this->getUrlManager()->createUrl(['installer'])) {
                $this->getResponse()->redirect(['installer']);
            }
        }

        foreach ($this->modules as $id => $module) {
            if (!in_array($id, $this->bootstrap)) {

                if (is_array($module) && isset($module['class'])) {
                    $className = $module['class'];
                } elseif (is_string($module)) {
                    $className = $module;
                }

                if (isset($className) && method_exists($className, 'bootstrap')) {
                    $this->bootstrap[] = $id;
                }
            }
        }

        parent::bootstrap();

    }

    /**
     * @inheritdoc
     */
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'user' => ['class' => 'colibri\base\User'],
            'settings' => ['class' => 'colibri\base\Settings']
        ]);
    }
}
