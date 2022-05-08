<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Nicknames\Access;

use DuRoom\Settings\SettingsRepositoryInterface;
use DuRoom\User\Access\AbstractPolicy;
use DuRoom\User\User;

class UserPolicy extends AbstractPolicy
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param User $actor
     * @param User $user
     * @return bool|null
     */
    public function editNickname(User $actor, User $user)
    {
        if ($actor->isGuest() && !$user->exists && $this->settings->get('duroom-nicknames.set_on_registration')) {
            return $this->allow();
        } else if ($actor->id === $user->id && $actor->hasPermission('user.editOwnNickname')) {
            return $this->allow();
        } else if ($actor->can('edit', $user)) {
            return $this->allow();
        }
    }
}
