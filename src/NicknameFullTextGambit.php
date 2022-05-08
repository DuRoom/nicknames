<?php


namespace DuRoom\Nicknames;

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use DuRoom\Search\GambitInterface;
use DuRoom\Search\SearchState;
use DuRoom\User\UserRepository;


class NicknameFullTextGambit implements GambitInterface
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param \DuRoom\User\UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param $searchValue
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getUserSearchSubQuery($searchValue)
    {
        return $this->users
            ->query()
            ->select('id')
            ->where('username', 'like', "{$searchValue}%")
            ->orWhere('nickname', 'like',"{$searchValue}%");
    }

    /**
     * {@inheritdoc}
     */
    public function apply(SearchState $search, $searchValue)
    {
        $search->getQuery()
            ->whereIn(
                'id',
                $this->getUserSearchSubQuery($searchValue)
            );
    }
}
