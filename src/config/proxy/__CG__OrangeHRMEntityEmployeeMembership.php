<?php

namespace DoctrineProxies\__CG__\ShantsHRM\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class EmployeeMembership extends \ShantsHRM\Entity\EmployeeMembership implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'employee', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'membership', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionFee', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionPaidBy', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionCurrency', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionCommenceDate', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionRenewalDate', 'entityDecorator'];
        }

        return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'employee', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'membership', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionFee', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionPaidBy', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionCurrency', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionCommenceDate', '' . "\0" . 'ShantsHRM\\Entity\\EmployeeMembership' . "\0" . 'subscriptionRenewalDate', 'entityDecorator'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (EmployeeMembership $proxy) {
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
    public function getEmployee(): \ShantsHRM\Entity\Employee
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmployee', []);

        return parent::getEmployee();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmployee(\ShantsHRM\Entity\Employee $employee): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmployee', [$employee]);

        parent::setEmployee($employee);
    }

    /**
     * {@inheritDoc}
     */
    public function getMembership(): \ShantsHRM\Entity\Membership
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMembership', []);

        return parent::getMembership();
    }

    /**
     * {@inheritDoc}
     */
    public function setMembership(\ShantsHRM\Entity\Membership $membership): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMembership', [$membership]);

        parent::setMembership($membership);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionFee(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubscriptionFee', []);

        return parent::getSubscriptionFee();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscriptionFee(?string $subscriptionFee): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubscriptionFee', [$subscriptionFee]);

        parent::setSubscriptionFee($subscriptionFee);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionPaidBy(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubscriptionPaidBy', []);

        return parent::getSubscriptionPaidBy();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscriptionPaidBy(?string $subscriptionPaidBy): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubscriptionPaidBy', [$subscriptionPaidBy]);

        parent::setSubscriptionPaidBy($subscriptionPaidBy);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionCurrency(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubscriptionCurrency', []);

        return parent::getSubscriptionCurrency();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscriptionCurrency(?string $subscriptionCurrency): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubscriptionCurrency', [$subscriptionCurrency]);

        parent::setSubscriptionCurrency($subscriptionCurrency);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionCommenceDate(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubscriptionCommenceDate', []);

        return parent::getSubscriptionCommenceDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscriptionCommenceDate(?\DateTime $subscriptionCommenceDate): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubscriptionCommenceDate', [$subscriptionCommenceDate]);

        parent::setSubscriptionCommenceDate($subscriptionCommenceDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscriptionRenewalDate(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubscriptionRenewalDate', []);

        return parent::getSubscriptionRenewalDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubscriptionRenewalDate(?\DateTime $subscriptionRenewalDate): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubscriptionRenewalDate', [$subscriptionRenewalDate]);

        parent::setSubscriptionRenewalDate($subscriptionRenewalDate);
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
