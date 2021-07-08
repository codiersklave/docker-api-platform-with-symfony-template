<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\DatabaseEntityInterface;
use DateTime;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Event listener for Doctrine entity lifecycle events.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
class EntityLifecycleListener
{
    /**
     * @var UserPasswordEncoderInterface $passwordEncoder
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @var UserInterface|string $user
     */
    private $user;

    /**
     * @var ValidatorInterface $validator
     */
    private ValidatorInterface $validator;

    /**
     * @param TokenStorageInterface $tokenStorage Used to retrieve the current user.
     * @param UserPasswordEncoderInterface $passwordEncoder Used to encode the user's password.
     * @param ValidatorInterface $validator
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;

        $token = $tokenStorage->getToken();

        if (!empty($token)) {
            $this->user = $token->getUser();
        }
    }

    /**
     * @param DatabaseEntityInterface $entity
     * @throws Exception
     */
    private function validateEntity(DatabaseEntityInterface $entity)
    {
        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {
            $errorString = (string) $errors;
            throw new Exception('Validation errors: ' . $errorString);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (
            method_exists($entity, 'getCreatedAt') &&
            method_exists($entity, 'setCreatedAt') &&
            $entity->getCreatedAt() === null
        ) {
            $entity->setCreatedAt(new DateTime());
        }

        if (
            method_exists($entity, 'getUpdatedAt') &&
            method_exists($entity, 'setUpdatedAt') &&
            $entity->getUpdatedAt() === null
        ) {
            $entity->setUpdatedAt(new DateTime());
        }

        if (
            $this->user !== null &&
            $this->user instanceof UserInterface &&
            method_exists($entity, 'getCreatedBy') &&
            method_exists($entity, 'setCreatedBy') &&
            $entity->getCreatedBy() === null
        ) {
            $entity->setCreatedBy($this->user);
        }

        if (
            $this->user !== null &&
            $this->user instanceof UserInterface &&
            method_exists($entity, 'getUpdatedBy') &&
            method_exists($entity, 'setUpdatedBy') &&
            $entity->getUpdatedBy() === null
        ) {
            $entity->setUpdatedBy($this->user);
        }

        if ($entity instanceof DatabaseEntityInterface) {
            $this->validateEntity($entity);
        }

        if ($entity instanceof UserInterface) {
            $this->encodePassword($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws Exception
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new DateTime());
        }

        if (
            $this->user !== null &&
            $this->user instanceof UserInterface &&
            method_exists($entity, 'setUpdatedBy')
        ) {
            $entity->setUpdatedBy($this->user);
        }

        if ($entity instanceof DatabaseEntityInterface) {
            $this->validateEntity($entity);
        }
    }

    /**
     * @param UserInterface $user
     */
    private function encodePassword(UserInterface $user)
    {
        if (method_exists($user, 'getPlainPassword') && method_exists($user, 'setPassword')) {
            $plainPassword = $user->getPlainPassword();

            if (!empty($plainPassword)) {
                $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
                $user->setPassword($encoded);
            }
        }
    }
}
