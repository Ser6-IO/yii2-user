<?php

namespace ser6io\yii2user\widgets;

use Yii;

/**
 * Generates a Dropdown menu with Actions for this module.
 * This Dropdown can be added to the Main NavBar
 * 
 * @author Ser6-IO
 */
class UserNav extends \yii\bootstrap5\Nav
{
    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();

        $this->encodeLabels = false;

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '<i class="bi bi-box-arrow-in-right"></i> Login', 'url' => ['/user/auth/link-login']];
        } else {
            $menuItems[] = [
                'label' => '<i class="bi bi-person-circle"></i>', 
                'dropdownOptions' => ['class' => 'dropdown-menu-end'],
                'items' => [
                    '<div class="dropdown-header">User</div>',
                    ['label' => '<i class="bi bi-person-gear"></i> Profile', 'url' => ['/user/profile/view']],
                    ['label' => '<i class="bi bi-person-lock"></i> Password', 'url' => ['/user/profile/password']],
                    '-',
                    ['label' => '<i class="bi bi-power"></i> Logout', 'url' => '/user/auth/logout', 'linkOptions' => ['data-tooltip' => 'true', 'title' => 'Logout', 'data-method' => 'post']]
                ]
            ];
        }
        
        $this->items = $menuItems;
    }
}
