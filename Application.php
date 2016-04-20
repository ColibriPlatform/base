<?php
namespace colibri;

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
    public $controllerNamespace = 'colibri\\controllers';

    /**
     * @inheritdoc
     */
    protected function bootstrap()
    {
        parent::bootstrap();

        if (! $this->getDb()->dsn) {
            if ($this->getRequest()->url != $this->getUrlManager()->createUrl(['install'])) {
                $this->getResponse()->redirect(['install']);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), [
            'user' => ['class' => 'colibri\User'],
            'settings' => ['class' => 'colibri\Settings']
        ]);
    }
}
