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
 */

namespace ShantsHRM\Buzz\Api;

use ShantsHRM\Buzz\Dto\BuzzVideoURL\BuzzEmbeddedURL;
use ShantsHRM\Buzz\Exception\InvalidURLException;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;

class BuzzVideoURLValidationAPI extends Endpoint implements ResourceEndpoint
{
    public const PARAMETER_VIDEO_LINK = 'url';
    public const PARAMETER_VALID_VIDEO_LINK = 'valid';

    /**
     * @OA\Get(
     *     path="/api/v2/buzz/validation/links",
     *     tags={"Buzz/Validation"},
     *     @OA\PathParameter(
     *         name="link",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="url", type="string"),
     *                 @OA\Property(property="embeddedURL", type="string"),
     *             ),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     * )
     *
     * @inheritDoc
     * @throws InvalidURLException
     */
    public function getOne(): EndpointResult
    {
        $videoLink = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_VIDEO_LINK
        );

        $buzzEmbeddedURL = new BuzzEmbeddedURL($videoLink);

        $isValid = $buzzEmbeddedURL->isValidURL();
        $response = [
            self::PARAMETER_VALID_VIDEO_LINK => $isValid,
            'url' => null,
            'embeddedURL' => null,
        ];

        if ($isValid) {
            $response['url'] = $buzzEmbeddedURL->getURL();
            $response['embeddedURL'] = $buzzEmbeddedURL->getEmbeddedURL();
        }

        return new EndpointResourceResult(ArrayModel::class, $response);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getVideoValidationRule()
        );
    }

    /**
     * @return ParamRule
     */
    private function getVideoValidationRule(): ParamRule
    {
        return new ParamRule(
            self::PARAMETER_VIDEO_LINK,
            new Rule(Rules::REQUIRED),
            new Rule(Rules::STRING_TYPE),
        //TODO - length validation
        );
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
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
