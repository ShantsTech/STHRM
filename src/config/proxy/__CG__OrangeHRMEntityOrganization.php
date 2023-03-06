<?php

namespace DoctrineProxies\__CG__\ShantsHRM\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Organization extends \ShantsHRM\Entity\Organization implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'name', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'taxId', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'registrationNumber', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'phone', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'fax', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'email', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'country', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'province', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'city', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'zipCode', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'street1', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'street2', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'note'];
        }

        return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'name', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'taxId', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'registrationNumber', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'phone', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'fax', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'email', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'country', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'province', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'city', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'zipCode', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'street1', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'street2', '' . "\0" . 'ShantsHRM\\Entity\\Organization' . "\0" . 'note'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Organization $proxy) {
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
    public function getTaxId(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxId', []);

        return parent::getTaxId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxId(?string $taxId): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTaxId', [$taxId]);

        parent::setTaxId($taxId);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationNumber(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationNumber', []);

        return parent::getRegistrationNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationNumber(?string $registrationNumber): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationNumber', [$registrationNumber]);

        parent::setRegistrationNumber($registrationNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone', []);

        return parent::getPhone();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone(?string $phone): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone', [$phone]);

        parent::setPhone($phone);
    }

    /**
     * {@inheritDoc}
     */
    public function getFax(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFax', []);

        return parent::getFax();
    }

    /**
     * {@inheritDoc}
     */
    public function setFax(?string $fax): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFax', [$fax]);

        parent::setFax($fax);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail(?string $email): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountry(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountry', []);

        return parent::getCountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setCountry(?string $country): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCountry', [$country]);

        parent::setCountry($country);
    }

    /**
     * {@inheritDoc}
     */
    public function getProvince(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProvince', []);

        return parent::getProvince();
    }

    /**
     * {@inheritDoc}
     */
    public function setProvince(?string $province): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setProvince', [$province]);

        parent::setProvince($province);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCity', []);

        return parent::getCity();
    }

    /**
     * {@inheritDoc}
     */
    public function setCity(?string $city): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCity', [$city]);

        parent::setCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function getZipCode(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getZipCode', []);

        return parent::getZipCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setZipCode(?string $zipCode): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setZipCode', [$zipCode]);

        parent::setZipCode($zipCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getStreet1(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStreet1', []);

        return parent::getStreet1();
    }

    /**
     * {@inheritDoc}
     */
    public function setStreet1(?string $street1): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStreet1', [$street1]);

        parent::setStreet1($street1);
    }

    /**
     * {@inheritDoc}
     */
    public function getStreet2(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStreet2', []);

        return parent::getStreet2();
    }

    /**
     * {@inheritDoc}
     */
    public function setStreet2(?string $street2): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStreet2', [$street2]);

        parent::setStreet2($street2);
    }

    /**
     * {@inheritDoc}
     */
    public function getNote(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNote', []);

        return parent::getNote();
    }

    /**
     * {@inheritDoc}
     */
    public function setNote(?string $note): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNote', [$note]);

        parent::setNote($note);
    }

}
