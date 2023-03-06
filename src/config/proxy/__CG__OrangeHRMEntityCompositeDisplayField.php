<?php

namespace DoctrineProxies\__CG__\ShantsHRM\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class CompositeDisplayField extends \ShantsHRM\Entity\CompositeDisplayField implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\CompositeDisplayField' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\CompositeDisplayField' . "\0" . 'selectedCompositeDisplayFields', 'reportGroup', 'name', 'label', 'fieldAlias', 'sortable', 'sortOrder', 'sortField', 'elementType', 'elementProperty', 'width', 'exportable', 'textAlignmentStyle', 'isValueList', 'displayFieldGroup', 'defaultValue', 'encrypted', 'meta'];
        }

        return ['__isInitialized__', '' . "\0" . 'ShantsHRM\\Entity\\CompositeDisplayField' . "\0" . 'id', '' . "\0" . 'ShantsHRM\\Entity\\CompositeDisplayField' . "\0" . 'selectedCompositeDisplayFields', 'reportGroup', 'name', 'label', 'fieldAlias', 'sortable', 'sortOrder', 'sortField', 'elementType', 'elementProperty', 'width', 'exportable', 'textAlignmentStyle', 'isValueList', 'displayFieldGroup', 'defaultValue', 'encrypted', 'meta'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (CompositeDisplayField $proxy) {
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
    public function getReportGroup(): \ShantsHRM\Entity\ReportGroup
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReportGroup', []);

        return parent::getReportGroup();
    }

    /**
     * {@inheritDoc}
     */
    public function setReportGroup(\ShantsHRM\Entity\ReportGroup $reportGroup): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReportGroup', [$reportGroup]);

        parent::setReportGroup($reportGroup);
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
    public function getLabel(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLabel', []);

        return parent::getLabel();
    }

    /**
     * {@inheritDoc}
     */
    public function setLabel(string $label): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLabel', [$label]);

        parent::setLabel($label);
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldAlias(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFieldAlias', []);

        return parent::getFieldAlias();
    }

    /**
     * {@inheritDoc}
     */
    public function setFieldAlias(?string $fieldAlias): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFieldAlias', [$fieldAlias]);

        parent::setFieldAlias($fieldAlias);
    }

    /**
     * {@inheritDoc}
     */
    public function isSortable(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isSortable', []);

        return parent::isSortable();
    }

    /**
     * {@inheritDoc}
     */
    public function setSortable(bool $sortable): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSortable', [$sortable]);

        parent::setSortable($sortable);
    }

    /**
     * {@inheritDoc}
     */
    public function getSortOrder(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSortOrder', []);

        return parent::getSortOrder();
    }

    /**
     * {@inheritDoc}
     */
    public function setSortOrder(?string $sortOrder): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSortOrder', [$sortOrder]);

        parent::setSortOrder($sortOrder);
    }

    /**
     * {@inheritDoc}
     */
    public function getSortField(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSortField', []);

        return parent::getSortField();
    }

    /**
     * {@inheritDoc}
     */
    public function setSortField(?string $sortField): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSortField', [$sortField]);

        parent::setSortField($sortField);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementType(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElementType', []);

        return parent::getElementType();
    }

    /**
     * {@inheritDoc}
     */
    public function setElementType(string $elementType): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setElementType', [$elementType]);

        parent::setElementType($elementType);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementProperty(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElementProperty', []);

        return parent::getElementProperty();
    }

    /**
     * {@inheritDoc}
     */
    public function setElementProperty(string $elementProperty): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setElementProperty', [$elementProperty]);

        parent::setElementProperty($elementProperty);
    }

    /**
     * {@inheritDoc}
     */
    public function getWidth(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWidth', []);

        return parent::getWidth();
    }

    /**
     * {@inheritDoc}
     */
    public function setWidth(string $width): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWidth', [$width]);

        parent::setWidth($width);
    }

    /**
     * {@inheritDoc}
     */
    public function isExportable(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isExportable', []);

        return parent::isExportable();
    }

    /**
     * {@inheritDoc}
     */
    public function setExportable(bool $exportable): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setExportable', [$exportable]);

        parent::setExportable($exportable);
    }

    /**
     * {@inheritDoc}
     */
    public function getTextAlignmentStyle(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTextAlignmentStyle', []);

        return parent::getTextAlignmentStyle();
    }

    /**
     * {@inheritDoc}
     */
    public function setTextAlignmentStyle(?string $textAlignmentStyle): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTextAlignmentStyle', [$textAlignmentStyle]);

        parent::setTextAlignmentStyle($textAlignmentStyle);
    }

    /**
     * {@inheritDoc}
     */
    public function isValueList(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isValueList', []);

        return parent::isValueList();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsValueList(bool $isValueList): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsValueList', [$isValueList]);

        parent::setIsValueList($isValueList);
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayFieldGroup(): ?\ShantsHRM\Entity\DisplayFieldGroup
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisplayFieldGroup', []);

        return parent::getDisplayFieldGroup();
    }

    /**
     * {@inheritDoc}
     */
    public function setDisplayFieldGroup(?\ShantsHRM\Entity\DisplayFieldGroup $displayFieldGroup): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDisplayFieldGroup', [$displayFieldGroup]);

        parent::setDisplayFieldGroup($displayFieldGroup);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultValue(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDefaultValue', []);

        return parent::getDefaultValue();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultValue(?string $defaultValue): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDefaultValue', [$defaultValue]);

        parent::setDefaultValue($defaultValue);
    }

    /**
     * {@inheritDoc}
     */
    public function isEncrypted(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isEncrypted', []);

        return parent::isEncrypted();
    }

    /**
     * {@inheritDoc}
     */
    public function setEncrypted(bool $encrypted): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEncrypted', [$encrypted]);

        parent::setEncrypted($encrypted);
    }

    /**
     * {@inheritDoc}
     */
    public function isMeta(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isMeta', []);

        return parent::isMeta();
    }

    /**
     * {@inheritDoc}
     */
    public function setMeta(bool $meta): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMeta', [$meta]);

        parent::setMeta($meta);
    }

}