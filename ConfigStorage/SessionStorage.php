<?php
namespace nvbooster\SortingManagerBundle\ConfigStorage;

use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;
use nvbooster\SortingManager\ConfigInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class SessionStorage implements ConfigStorageInterface
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
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