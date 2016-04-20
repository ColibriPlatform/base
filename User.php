<?php
namespace colibri\base;

class User extends \yii\web\User
{

    /**
     * @var boolean to know if the user has the administrator privilege
     */
    protected $_isAdmin;

    /**
     * Can be used to know if the user has the administrator privilege
     * Note : the identity interface must contains the is_admin attribute
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        if ($this->_isAdmin === null) {
            $this->_isAdmin = false;
            if ($identity = $this->getIdentity()) {
                if (!empty($identity->is_admin)) {
                    $this->_isAdmin = true;
                }
            }
        }
        return $this->_isAdmin;
    }
}
