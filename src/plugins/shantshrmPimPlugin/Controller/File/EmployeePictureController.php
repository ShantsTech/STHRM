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

namespace ShantsHRM\Pim\Controller\File;

use ShantsHRM\Config\Config;
use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Core\Traits\ETagHelperTrait;
use ShantsHRM\Entity\EmpPicture;
use ShantsHRM\Framework\Http\BinaryFileResponse;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Pim\Service\EmployeePictureService;

class EmployeePictureController extends AbstractFileController
{
    use ETagHelperTrait;

    /**
     * @var EmployeePictureService|null
     */
    protected ?EmployeePictureService $employeePictureService = null;

    /**
     * @return EmployeePictureService
     */
    public function getEmployeePictureService(): EmployeePictureService
    {
        if (!$this->employeePictureService instanceof EmployeePictureService) {
            $this->employeePictureService = new EmployeePictureService();
        }
        return $this->employeePictureService;
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse|Response
     * @throws DaoException
     */
    public function handle(Request $request)
    {
        $empNumber = $request->attributes->get('empNumber');
        if (!is_null($empNumber)) {
            $empPicture = $this->getEmployeePictureService()->getEmpPictureByEmpNumber($empNumber);
            if ($empPicture instanceof EmpPicture) {
                $response = $this->getResponse();
                $response->setEtag($this->generateEtag($empPicture->getDecorator()->getPicture()));

                if (!$response->isNotModified($request)) {
                    $response->setContent($empPicture->getDecorator()->getPicture());
                    $this->setCommonHeaders($response, $empPicture->getFileType());
                }

                return $response;
            }
        }

        $publicPath = Config::get(Config::PUBLIC_DIR);
        $response = $this->getFileResponse(realpath($publicPath . '/images/default-photo.png'));
        $response->setAutoEtag();
        $this->setCommonHeaders($response, "image/png");
        $response->isNotModified($request);
        return $response;
    }

    private function setCommonHeaders($response, string $contentType)
    {
        $response->headers->set('Content-Type', $contentType);
        $response->setPublic();
        $response->setMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set('Pragma', 'Public');
    }
}
