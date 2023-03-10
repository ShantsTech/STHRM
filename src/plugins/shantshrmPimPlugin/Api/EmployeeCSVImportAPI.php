<?php
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 ShantsHRM Inc., http://www.hrm.shants-tech.com
 *
 * ShantsHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * ShantsHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

namespace ShantsHRM\Pim\Api;

use Exception;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Exception\BadRequestException;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Exception\CSVUploadFailedException;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\ORM\Exception\TransactionException;
use ShantsHRM\Pim\Service\PimCsvDataImportService;

class EmployeeCSVImportAPI extends Endpoint implements CollectionEndpoint
{
    use EntityManagerHelperTrait;

    public const PARAMETER_ATTACHMENT = 'attachment';
    public const PARAMETER_SUCCESS = 'success';
    public const PARAMETER_FAILED = 'failed';
    public const PARAMETER_FAILED_ROWS = 'failedRows';

    public const PARAM_RULE_IMPORT_FILE_FORMAT = ["text/csv", 'text/comma-separated-values', "application/csv", "application/vnd.ms-excel"];
    public const PARAM_RULE_IMPORT_FILE_EXTENSIONS = ["csv"];

    /**
     * @var null|PimCsvDataImportService
     */
    protected ?PimCsvDataImportService $pimCsvDataImportService = null;

    /**
     * @return PimCsvDataImportService
     */
    public function getPimCsvDataImportService(): PimCsvDataImportService
    {
        if (!$this->pimCsvDataImportService instanceof PimCsvDataImportService) {
            $this->pimCsvDataImportService = new PimCsvDataImportService();
        }
        return $this->pimCsvDataImportService;
    }


    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function create(): EndpointResult
    {
        $attachment = $this->getRequestParams()->getAttachment(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_ATTACHMENT
        );

        $this->beginTransaction();
        try {
            $result = $this->getPimCsvDataImportService()->import($attachment->getContent());
            $this->commitTransaction();
        } catch (CSVUploadFailedException $e) {
            $this->rollBackTransaction();
            throw new BadRequestException($e->getMessage());
        } catch (Exception $e) {
            $this->rollBackTransaction();
            throw new TransactionException($e);
        }

        return new EndpointResourceResult(
            ArrayModel::class,
            [],
            new ParameterBag([
                CommonParams::PARAMETER_TOTAL => $result['success'] + $result['failed'],
                self::PARAMETER_SUCCESS => $result['success'],
                self::PARAMETER_FAILED => $result['failed'],
                self::PARAMETER_FAILED_ROWS => $result['failedRows']
            ])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getAttachmentRule(),
        );
    }


    /**
     * @return ParamRule
     */
    private function getAttachmentRule(): ParamRule
    {
        return new ParamRule(
            self::PARAMETER_ATTACHMENT,
            new Rule(
                Rules::BASE_64_ATTACHMENT,
                [self::PARAM_RULE_IMPORT_FILE_FORMAT, self::PARAM_RULE_IMPORT_FILE_EXTENSIONS]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
