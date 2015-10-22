<?php

namespace Rcm\SwitchUser\ApiController;

use Reliv\RcmApiLib\Model\ExceptionApiMessage;
use Reliv\RcmApiLib\Model\HttpStatusCodeApiMessage;

/**
 * Class RpcSwitchBackController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\Conference\ApiController
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RpcSwitchBackController extends BaseApiController
{
    /**
     * create
     *
     * @param array $data ['suUserPassword' => '{validPassword}']
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function create($data)
    {
        $service = $this->getSwitchUserService();

        $suUser = $service->getCurrentSuUser();

        if (!$this->isAllowed($suUser)) {
            return $this->getApiResponse(null, 401);
        }

        $suUserPassword = (
            isset($data['suUserPassword'])
            ? $data ['suUserPassword']
            : null
        );

        try {
            $result = $service->switchBack($suUserPassword);
        } catch (\Exception $exception) {
            return $this->getApiResponse(
                null,
                500,
                new ExceptionApiMessage($exception)
            );
        }

        if (empty($result)) {
            return $this->getApiResponse(
                null,
                406,
                new HttpStatusCodeApiMessage(406)
            );
        }

        $data = [
            'userId' => $suUser->getId(),
            'userName' => $suUser->getUsername(),
        ];

        return $this->getApiResponse($data);
    }
}