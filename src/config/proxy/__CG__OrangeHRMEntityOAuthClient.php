<?php

namespace DoctrineProxies\__CG__\ShantsHRM\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class OAuthClient extends \ShantsHRM\Entity\OAuthClient implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'clientId', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'clientSecret', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'redirectUri', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'scope', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'grantTypes'];
        }

        return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'clientId', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'clientSecret', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'redirectUri', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'scope', '' . "\0" . 'ShantsHRM\\Entity\\OAuthClient' . "\0" . 'grantTypes'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (OAuthClient $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load(): void
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized(): bool
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized): void
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null): void
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer(): ?\Closure
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null): void
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner(): ?\Closure
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties(): array
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getClientId(): string
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getClientId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClientId', []);

        return parent::getClientId();
    }

    /**
     * {@inheritDoc}
     */
    public function setClientId(string $clientId): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClientId', [$clientId]);

        parent::setClientId($clientId);
    }

    /**
     * {@inheritDoc}
     */
    public function getClientSecret(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClientSecret', []);

        return parent::getClientSecret();
    }

    /**
     * {@inheritDoc}
     */
    public function setClientSecret(string $clientSecret): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClientSecret', [$clientSecret]);

        parent::setClientSecret($clientSecret);
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirectUri(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRedirectUri', []);

        return parent::getRedirectUri();
    }

    /**
     * {@inheritDoc}
     */
    public function setRedirectUri(string $redirectUri): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRedirectUri', [$redirectUri]);

        parent::setRedirectUri($redirectUri);
    }

    /**
     * {@inheritDoc}
     */
    public function getScope(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getScope', []);

        return parent::getScope();
    }

    /**
     * {@inheritDoc}
     */
    public function setScope(string $scope): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setScope', [$scope]);

        parent::setScope($scope);
    }

    /**
     * {@inheritDoc}
     */
    public function getGrantTypes(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGrantTypes', []);

        return parent::getGrantTypes();
    }

    /**
     * {@inheritDoc}
     */
    public function setGrantTypes(string $grantTypes): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGrantTypes', [$grantTypes]);

        parent::setGrantTypes($grantTypes);
    }

}
