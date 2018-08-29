<?php

namespace nvbooster\SortingManagerBundle\ConfigStorage;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use nvbooster\SortingManager\ConfigInterface;
use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SessionStorage implements ConfigStorageInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::retrieve()
     */
    public function retrieve(ConfigInterface $config)
    {
        if ($this->session->has($config->getName())) {
            $config->setSortingSequence($this->session->get($config->getName()));
        }
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::store()
     */
    public function store(ConfigInterface $config)
    {
        $this->session->set($config->getName(), $config->getSortingSequence());
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::has()
     */
    public function has(ConfigInterface $config)
    {
        return $this->session->has($config->getName());
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::getAlias()
     */
    public function getAlias()
    {
        return 'session';
    }
}