<?php

namespace ViKon\Auth\Factory;

use Illuminate\Support\Arr;
use ViKon\Auth\Model\User;
use ViKon\Support\Database\Repository\AbstractFactory;

class UserFactory extends AbstractFactory
{
    /**
     * Create new user and return it
     *
     * @param string  $username
     * @param string  $password
     * @param mixed[] $optional
     *
     * @return \ViKon\Auth\Model\User
     */
    public function build($username, $password, array $optional = [])
    {
        $user           = new User();
        $user->username = strtolower($username);
        $user->password = bcrypt($password);
        $user->fill($optional);

        return $user;
    }

    /**
     * Create new user and store in database
     *
     * @param string  $username
     * @param string  $password
     * @param mixed[] $optional
     *
     * @return \ViKon\Auth\Model\User
     */
    public function create($username, $password, array $optional = [])
    {
        return $this->connection->transaction(function () use ($username, $password, $optional) {
            $user = $this->build($username, $password, $optional);

            $user->save();

            return $user;
        });
    }

    /**
     * Update user in database
     *
     * @param \ViKon\Auth\Model\User $user
     * @param mixed[]                $optional
     *
     * @return \ViKon\Auth\Model\User
     */
    public function update(User $user, array $optional = [])
    {
        return $this->connection->transaction(function () use ($user, $optional) {
            // Set password for user if provided in optional array
            if (Arr::has($optional, User::FIELD_PASSWORD)) {
                if ($optional[User::FIELD_PASSWORD] !== null || $optional[User::FIELD_PASSWORD] !== '') {
                    $user->password = bcrypt($optional[User::FIELD_PASSWORD]);
                }
                Arr::forget($optional, User::FIELD_PASSWORD);
            }

            $user->fill($optional);

            $user->save();

            return $user;
        });
    }
}