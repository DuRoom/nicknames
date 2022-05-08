<?php

namespace DuRoom\Nicknames;

use DuRoom\User\DisplayName\DriverInterface;
use DuRoom\User\User;

class NicknameDriver implements DriverInterface {

    public function displayName(User $user): string
    {
        return $user->nickname ? $user->nickname : $user->username;
    }
}
