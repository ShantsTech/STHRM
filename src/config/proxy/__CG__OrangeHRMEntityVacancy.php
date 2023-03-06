<?php

namespace DoctrineProxies\__CG__\ShantsHRM\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Vacancy extends \ShantsHRM\Entity\Vacancy implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'jobTitle', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'hiringManager', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'name', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'description', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'numOfPositions', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'status', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'isPublished', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'definedTime', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'updatedTime', 'entityDecorator'];
        }

        return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'jobTitle', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'hiringManager', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'name', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'description', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'numOfPositions', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'status', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'isPublished', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'definedTime', '' . "\0" . 'ShantsHRM\\Entity\\Vacancy' . "\0" . 'updatedTime', 'entityDecorator'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Vacancy $proxy) {
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
    public function getId(): int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId(int $id): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', [$id]);

        parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getJobTitle(): \ShantsHRM\Entity\JobTitle
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getJobTitle', []);

        return parent::getJobTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function setJobTitle(\ShantsHRM\Entity\JobTitle $jobTitle): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setJobTitle', [$jobTitle]);

        parent::setJobTitle($jobTitle);
    }

    /**
     * {@inheritDoc}
     */
    public function getHiringManager(): ?\ShantsHRM\Entity\Employee
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHiringManager', []);

        return parent::getHiringManager();
    }

    /**
     * {@inheritDoc}
     */
    public function setHiringManager(?\ShantsHRM\Entity\Employee $hiringManager): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHiringManager', [$hiringManager]);

        parent::setHiringManager($hiringManager);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', [$name]);

        parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', []);

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(?string $description): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescription', [$description]);

        parent::setDescription($description);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumOfPositions(): ?int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumOfPositions', []);

        return parent::getNumOfPositions();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumOfPositions(?int $numOfPositions): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumOfPositions', [$numOfPositions]);

        parent::setNumOfPositions($numOfPositions);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(bool $status): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', [$status]);

        parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function isPublished(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPublished', []);

        return parent::isPublished();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsPublished(bool $isPublished): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsPublished', [$isPublished]);

        parent::setIsPublished($isPublished);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefinedTime(): \DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDefinedTime', []);

        return parent::getDefinedTime();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefinedTime(\DateTime $definedTime): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDefinedTime', [$definedTime]);

        parent::setDefinedTime($definedTime);
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedTime(): \DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUpdatedTime', []);

        return parent::getUpdatedTime();
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedTime(\DateTime $updatedTime): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUpdatedTime', [$updatedTime]);

        parent::setUpdatedTime($updatedTime);
    }

    /**
     * {@inheritDoc}
     */
    public function getDecorator(): object
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDecorator', []);

        return parent::getDecorator();
    }

}
