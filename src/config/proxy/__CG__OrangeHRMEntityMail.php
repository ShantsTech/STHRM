<?php

namespace DoctrineProxies\__CG__\ShantsHRM\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Mail extends \ShantsHRM\Entity\Mail implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'toList', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'ccList', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'bccList', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'subject', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'body', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'createdAt', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'sentAt', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'status', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'contentType'];
        }

        return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'toList', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'ccList', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'bccList', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'subject', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'body', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'createdAt', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'sentAt', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'status', '' . "\0" . 'ShantsHRM\\Entity\\Mail' . "\0" . 'contentType'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Mail $proxy) {
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
    public function getId(): ?int
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
    public function getToList(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getToList', []);

        return parent::getToList();
    }

    /**
     * {@inheritDoc}
     */
    public function setToList(array $toList): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setToList', [$toList]);

        parent::setToList($toList);
    }

    /**
     * {@inheritDoc}
     */
    public function getCcList(): ?array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCcList', []);

        return parent::getCcList();
    }

    /**
     * {@inheritDoc}
     */
    public function setCcList(array $ccList): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCcList', [$ccList]);

        parent::setCcList($ccList);
    }

    /**
     * {@inheritDoc}
     */
    public function getBccList(): ?array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBccList', []);

        return parent::getBccList();
    }

    /**
     * {@inheritDoc}
     */
    public function setBccList(array $bccList): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBccList', [$bccList]);

        parent::setBccList($bccList);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubject', []);

        return parent::getSubject();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubject(?string $subject): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubject', [$subject]);

        parent::setSubject($subject);
    }

    /**
     * {@inheritDoc}
     */
    public function getBody(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBody', []);

        return parent::getBody();
    }

    /**
     * {@inheritDoc}
     */
    public function setBody(string $body): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBody', [$body]);

        parent::setBody($body);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedAt', []);

        return parent::getCreatedAt();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedAt', [$createdAt]);

        parent::setCreatedAt($createdAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getSentAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSentAt', []);

        return parent::getSentAt();
    }

    /**
     * {@inheritDoc}
     */
    public function setSentAt(?\DateTime $sentAt): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSentAt', [$sentAt]);

        parent::setSentAt($sentAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(?string $status): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', [$status]);

        parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContentType', []);

        return parent::getContentType();
    }

    /**
     * {@inheritDoc}
     */
    public function setContentType(?string $contentType): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContentType', [$contentType]);

        parent::setContentType($contentType);
    }

}
