<?php
/**
 * ShantsHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 ShantsHRM Inc., http://www.hrm.shants-tech.com
 *
 * ShantsHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. ShantsHRM Inc
 * is the owner of the media / downloaded ShantsHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in ShantsHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that ShantsHRM Inc creates for
 * Customer shall remain vested in ShantsHRM Inc. Any rights not expressly granted herein are
 * reserved to ShantsHRM Inc.
 *
 * Please refer http://www.hrm.shants-tech.com/Files/ShantsHRM_Commercial_License.pdf for the license which includes terms and conditions on using this software.
 *
 */

namespace ShantsHRM\Core\Registration\Processor;

use DateTime;
use ShantsHRM\Admin\Service\OrganizationService;
use ShantsHRM\Admin\Traits\Service\UserServiceTrait;
use ShantsHRM\Config\Config;
use ShantsHRM\Core\Registration\Dao\RegistrationEventQueueDao;
use ShantsHRM\Core\Registration\Helper\SystemConfigurationHelper;
use ShantsHRM\Core\Registration\Service\RegistrationAPIClientService;
use ShantsHRM\Core\Traits\LoggerTrait;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\RegistrationEventQueue;
use Throwable;

abstract class AbstractRegistrationEventProcessor
{
    use LoggerTrait;
    use ConfigServiceTrait;
    use UserServiceTrait;
    use DateTimeHelperTrait;

    private RegistrationEventQueueDao $registrationEventQueueDao;
    private RegistrationAPIClientService $registrationAPIClientService;
    private OrganizationService $organizationService;

    /**
     * @return RegistrationEventQueueDao
     */
    public function getRegistrationEventQueueDao(): RegistrationEventQueueDao
    {
        return $this->registrationEventQueueDao ??= new RegistrationEventQueueDao();
    }

    /**
     * @return RegistrationAPIClientService
     */
    public function getRegistrationAPIClientService(): RegistrationAPIClientService
    {
        return $this->registrationAPIClientService ??= new RegistrationAPIClientService();
    }

    /**
     * @return OrganizationService
     */
    public function getOrganizationService(): OrganizationService
    {
        return $this->organizationService ??= new OrganizationService();
    }

    public function saveRegistrationEvent(): void
    {
        if ($this->getEventToBeSavedOrNot()) {
            $registrationEvent = $this->processRegistrationEventToSave($this->getDateTimeHelper()->getNow());
            $this->getRegistrationEventQueueDao()->saveRegistrationEvent($registrationEvent);
        }
    }

    /**
     * @return array
     */
    public function getRegistrationEventGeneralData(): array
    {
        $registrationData = [];
        try {
            $adminUser = $this->getUserService()->geUserDao()->getDefaultAdminUser();
            $adminEmployee = $adminUser->getEmployee();
            $language = $this->getConfigService()->getAdminLocalizationDefaultLanguage()
                ? $this->getConfigService()->getAdminLocalizationDefaultLanguage()
                : 'Not captured';
            $country = $this->getOrganizationService()->getOrganizationGeneralInformation()->getCountry()
                ? $this->getOrganizationService()->getOrganizationGeneralInformation()->getCountry()
                : null;
            $instanceIdentifier = $this->getConfigService()->getInstanceIdentifier();
            $organizationName = $this->getOrganizationService()->getOrganizationGeneralInformation()->getName();
            $systemDetailsHelper = new SystemConfigurationHelper();
            $systemDetails = $systemDetailsHelper->getSystemDetailsAsJson();
            $organizationEmail = '';
            $adminFirstName = '';
            $adminLastName = '';
            $adminContactNumber = '';
            $username = 'Not Captured';
            $timeZone = date_default_timezone_get();
            if ($adminEmployee instanceof Employee) {
                $organizationEmail = $adminEmployee->getWorkEmail();
                $adminFirstName = $adminEmployee->getFirstName();
                $adminLastName = $adminEmployee->getLastName();
                $adminContactNumber = $adminEmployee->getWorkTelephone();
            }

            return [
                'username' => $username,
                'email' => $organizationEmail,
                'telephone' => $adminContactNumber,
                'admin_first_name' => $adminFirstName,
                'admin_last_name' => $adminLastName,
                'timezone' => $timeZone,
                'language' => $language,
                'country' => $country,
                'organization_name' => $organizationName,
                'instance_identifier' => $instanceIdentifier,
                'system_details' => $systemDetails
            ];
        } catch (Throwable $e) {
            $this->getLogger()->error($e->getMessage());
            $this->getLogger()->error($e->getTraceAsString());
            return $registrationData;
        }
    }

    /**
     * @param DateTime $eventTime
     * @return RegistrationEventQueue
     */
    public function processRegistrationEventToSave(DateTime $eventTime): RegistrationEventQueue
    {
        $registrationData = $this->getEventData();
        $registrationEvent = new RegistrationEventQueue();
        $registrationEvent->setEventTime($eventTime);
        $registrationEvent->setEventType($this->getEventType());
        $registrationEvent->setPublished(false);
        $registrationEvent->setData($registrationData);
        return $registrationEvent;
    }

    public function publishRegistrationEvents(): void
    {
        if (Config::PRODUCT_MODE === Config::MODE_PROD) {
            $eventsToPublish = $this->getRegistrationEventQueueDao()
                ->getUnpublishedRegistrationEvents(RegistrationEventQueue::PUBLISH_EVENT_BATCH_SIZE);
            if ($eventsToPublish) {
                foreach ($eventsToPublish as $event) {
                    $postData = $this->getRegistrationEventPublishDataPrepared($event);
                    $result = $this->getRegistrationAPIClientService()->publishData($postData);
                    if ($result) {
                        $event->setPublished(true);
                        $event->setPublishTime(new DateTime());
                        $this->getRegistrationEventQueueDao()->saveRegistrationEvent($event);
                    }
                }
            }
        }
    }

    /**
     * @param RegistrationEventQueue $event
     * @return array
     */
    public function getRegistrationEventPublishDataPrepared(RegistrationEventQueue $event): array
    {
        $eventData = $event->getData();
        $eventData['type'] = $event->getEventType();
        $eventData['event_time'] = $event->getEventTime();
        return $eventData;
    }

    /**
     * @return int
     */
    abstract public function getEventType(): int;

    /**
     * @return array
     */
    abstract public function getEventData(): array;

    /**
     * @return bool
     */
    abstract public function getEventToBeSavedOrNot(): bool;
}
