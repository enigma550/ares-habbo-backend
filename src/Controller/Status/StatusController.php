<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Controller\Status;

use App\Controller\BaseController;
use PHLAK\Config\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class StatusController
 *
 * @package App\Controller\Status
 */
class StatusController extends BaseController
{
    private const API_NAME = 'Ares API';

    private const API_VERSION = '1.0';

    /**
     *
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function getStatus(Request $request, Response $response): Response
    {
        $message = [
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'timestamp' => time()
        ];

        return $this->jsonResponse(
            $response,
            $message,
            200
        );
    }
}
