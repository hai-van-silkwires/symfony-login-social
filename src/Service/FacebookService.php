<?php


namespace App\Service;

use App\Entity\LoginSocial;
use App\Entity\User;
use App\Repository\LoginSocialRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FacebookService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    private $entityManger;
    /**
     * @var LoginSocialRepository
     */
    private $loginSocialRepository;

    public function __construct(
        UserRepository $userRepository,
        LoginSocialRepository $loginSocialRepository,
        ContainerInterface $container
    ) {
        $this->userRepository        = $userRepository;
        $this->container             = $container;
        $this->entityManger          = $this->container->get('doctrine.orm.entity_manager');
        $this->loginSocialRepository = $loginSocialRepository;
    }

    /**
     * @param $socialData
     * @param $accessToken
     * @return bool
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function process($socialData, $accessToken)
    {
        $connection = $this->entityManger->getConnection();

        try {
            $connection->beginTransaction();

            $userObj = $this->_createUser($socialData);

            if ($userObj) {
                $data = array(
                    'socialData'   => $socialData,
                    'userData'     => $userObj,
                    'access_token' => $accessToken,
                    'type'         => 'facebook'
                );

                $result = $this->_createSocialAccount($data);
                if ($result) {
                    $connection->commit();
                    return true;
                }
            }

            $connection->rollBack();
            return false;
        } catch (\Exception $e) {
            $connection->rollBack();
            return false;
        }
    }

    /**
     * @param $data
     * @return LoginSocial|bool|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function _createSocialAccount($data)
    {
        // Get social data by social id, user id, type
        $criteria = Criteria::create();

        $criteria->andWhere(
            new Comparison(
                'social_id',
                Comparison::EQ,
                $data['socialData']->getId()
            )
        );
        $criteria->andWhere(
            new Comparison(
                'user_id',
                Comparison::EQ,
                $data['userData']->getId()
            )
        );
        $criteria->andWhere(
            new Comparison(
                'type',
                Comparison::EQ,
                $data['type']
            )
        );

        $socialObj = $this->loginSocialRepository->getSocialAccount($criteria);

        if (is_null($socialObj)) {
            $socialObj = new LoginSocial();
        }

        switch ($data['type']) {
            case 'facebook':
                $socialObj->setUserId($data['userData']->getId());
                $socialObj->setSocialId($data['socialData']->getId());
                $socialObj->setAccessToken($data['access_token']->getToken());
                $socialObj->setType($data['type']);
                $socialObj->onPrePersist();
                break;
            default:
                return false;
                break;
        }

        $this->entityManger->persist($socialObj);
        $this->entityManger->flush($socialObj);
        return $socialObj;
    }

    /**
     * @param $data
     * @return User|mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function _createUser($data)
    {
        $userObj = $this->userRepository->getByEmail($data->getEmail());

        // If user is not exist, create a new user and set data for new user
        if (is_null($userObj)) {
            $userObj = new User();
            $userObj->setEmail($data->getEmail());
            $userObj->setFullName($data->getName());
            $userObj->onPrePersist();
            $this->entityManger->persist($userObj);
            $this->entityManger->flush($userObj);
        }

        return $userObj;
    }
}