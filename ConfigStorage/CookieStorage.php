<?php

namespace nvbooster\SortingManagerBundle\ConfigStorage;

use Symfony\Component\HttpFoundation\RequestStack;
use nvbooster\SortingManager\ConfigInterface;
use nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface;

/**
 * @author nvb <nvb@aproxima.ru>
 */
class CookieStorage implements ConfigStorageInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $updates;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->updates = [];
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::retrieve()
     */
    public function retrieve(ConfigInterface $config)
    {
        $data = false;
        $key = $this->getKey($config);
        if (key_exists($key, $this->updates)) {
            $data = $this->updates[$key];
        } elseif ($this->request->cookies->has($key)) {
            $data = $this->request->cookies->get($key);
        }

        if ($data && $sequence = $this->decodeData($data)) {
            $config->setSortingSequence($sequence);
        }
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::store()
     */
    public function store(ConfigInterface $config)
    {
        $key = $this->getKey($config);
        $this->updates[$key] = $this->encodeData($config->getSortingSequence());
        //var_dump(spl_object_hash($this));
        //var_dump($this->updates);

    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::has()
     */
    public function has(ConfigInterface $config)
    {
        $key = $this->getKey($config);

        return key_exists($key, $this->updates) ||
            $this->request->cookies->has($key);
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\SortingManager\ConfigStorage\ConfigStorageInterface::getAlias()
     */
    public function getAlias()
    {
        return 'cookie';
    }

    /**
     * @param ConfigInterface $config
     *
     * @return string
     */
    protected function getKey(ConfigInterface $config)
    {
        return 'sort_' . $config->getName();
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function encodeData($data)
    {
        return base64_encode(json_encode($data));
    }

    /**
     * @param string $string
     *
     * @return array
     */
    protected function decodeData($string)
    {
        return json_decode(base64_decode($string), 1);
    }

    /**
     * @return array
     */
    public function getUpdates()
    {
        //var_dump(spl_object_hash($this));
        //var_dump($this->updates);

        return $this->updates;
    }

}