<?php

/*
 * This file is part of DuRoom/nickname.
 *
 * Copyright (c) 2020 DuRoom.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace DuRoom\Nicknames;

use DuRoom\Api\Serializer\UserSerializer;
use DuRoom\Extend;
use DuRoom\Nicknames\Access\UserPolicy;
use DuRoom\User\Event\Saving;
use DuRoom\User\Search\UserSearcher;
use DuRoom\User\User;
use DuRoom\User\UserValidator;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\User())
        ->displayNameDriver('nickname', NicknameDriver::class),

    (new Extend\Event())
        ->listen(Saving::class, SaveNicknameToDatabase::class),

    (new Extend\ApiSerializer(UserSerializer::class))
        ->attribute('canEditOwnNickname', function ($serializer, $user) {
            $actor = $serializer->getActor();
            return $actor->id === $user->id && $serializer->getActor()->can('editOwnNickname', $user);
        }),

    (new Extend\Settings())
        ->default('duroom-nicknames.set_on_registration', true)
        ->default('duroom-nicknames.min', 1)
        ->default('duroom-nicknames.max', 150)
        ->serializeToForum('displayNameDriver', 'display_name_driver', null, 'username')
        ->serializeToForum('setNicknameOnRegistration', 'duroom-nicknames.set_on_registration', 'boolval')
        ->serializeToForum('randomizeUsernameOnRegistration', 'duroom-nicknames.random_username', 'boolval'),

    (new Extend\Validator(UserValidator::class))
        ->configure(AddNicknameValidation::class),

    (new Extend\SimpleDuRoomSearch(UserSearcher::class))
        ->setFullTextGambit(NicknameFullTextGambit::class),

    (new Extend\Policy())
        ->modelPolicy(User::class, UserPolicy::class),
];
