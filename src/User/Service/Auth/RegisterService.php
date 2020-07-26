<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Auth;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Service\TokenService;
use Ares\User\Entity\User;
use Ares\User\Exception\RegisterException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use PHLAK\Config\Config;
use ReallySimpleJWT\Exception\ValidateException;

/**
 * Class RegisterService
 *
 * @package Ares\User\Service\Auth
 */
class RegisterService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * LoginService constructor.
     *
     * @param UserRepository $userRepository
     * @param TokenService   $tokenService
     * @param Config         $config
     */
    public function __construct(
        UserRepository $userRepository,
        TokenService $tokenService,
        Config $config
    ) {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
        $this->config = $config;
    }

    /**
     * Register a user.
     *
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ORMException
     * @throws RegisterException
     * @throws ValidateException
     */
    public function register(array $data): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->getByUsername($data['username']);

        if (!is_null($user)) {
            throw new RegisterException(__('register.user.exists'), 422);
        }

        $user = $this->userRepository->save($this->getNewUser($data));

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        return response()->setData([
            'token' => $token
        ]);
    }

    /**
     * Returns new user.
     *
     * @param array $data
     * @return User
     */
    private function getNewUser(array $data): User
    {
        $user = new User();

        return $user
            ->setUsername($data['username'])
            ->setPassword(password_hash(
                $data['password'],
                PASSWORD_ARGON2ID))
            ->setMail($data['mail'])
            ->setLook($this->config->get('hotel_settings.start_look'))
            ->setCredits($this->config->get('hotel_settings.start_credits'))
            ->setPoints($this->config->get('hotel_settings.start_points'))
            ->setPixels($this->config->get('hotel_settings.start_pixels'))
            ->setMotto($this->config->get('hotel_settings.start_motto'))
            ->setIPRegister($data['ip_register'])
            ->setCurrentIP($data['ip_current'])
            ->setAccountCreated(time())
            ->setTicket('lol');
    }
}
