<?php
/*
 * <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year> <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
namespace Pluf\Core\Process\Http;

use Pluf\Scion\UnitTrackerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Logs all HTTP accesses
 *
 * The access log dose not handle exception. Be sure that the process flow handle the
 * exceptions and convert them into a valid respons.
 *
 * The response of the next unit must be a http response too
 *
 * @author Mostafa Barmshory (mostafa.barmshory@gmail.com)
 */
class AccessLog
{

    /**
     * following parameters may be used into the message format.
     *
     * - {Remote} – Remote host (client IP address)
     * - {UserId} – User identity, or dash, if none (often not used)
     * - {UserName} – Username, via HTTP authentication, or dash if not used
     * - {Timestamp} – Timestamp of when Apache received the HTTP request
     * - {Request} – The actual request itself from the client
     * - {Status} – The status code Apache returns in response to the request
     * - {RequestSize} – The size of the request in bytes.
     * - {Referrer} – Referrer header, or dash if not used (In other words, did they click a URL on another site to come to your site)
     * - {UserAgent} – User agent (contains information about the requester’s browser/OS/etc)
     */
    private string $format = "{Remote} {UserId} {UserName} {Timestamp} \"{Request}\" %>{Status} {RequestSize} \"{Referrer}\" \"{UserAgent}\"";

    public function __invoke(RequestInterface $request, UnitTrackerInterface $unitTracker, LoggerInterface $logger, $user = null)
    {
        $result = $unitTracker->next();
        $logger->info($this->format, [
            "Remote" => self::getIpAddress($request),
            "User" => $user,
            "Timestamp" => time(),
            "Request" => $request,
            "Status" => $result->getStatusCode(),
            "RequestSize" => $request->getHeader("Content-Length"),
            "Referrer" => $request->getHeader("Referrer"),
            "UserAgent" => $request->getHeader("User-Agent")
        ]);
        return $result;
    }

    /**
     * Gets parameter form the incoming request
     *
     * @param RequestInterface $request
     * @return string
     */
    public static function getIpAddress(RequestInterface $request): string
    {
        if (! ($request instanceof ServerRequestInterface)) {
            return "-";
        }

        $server = $request->getServerParams();

        // Check for shared Internet/ISP IP
        if (array_key_exists('HTTP_CLIENT_IP', $server)) {
            return $server['HTTP_CLIENT_IP'];
        }

        // Check for IP addresses passing through proxies
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $server)) {
            return $server['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_X_FORWARDED', $server)) {
            return $server['HTTP_X_FORWARDED'];
        }

        if (array_key_exists('HTTP_X_CLUSTER_CLIENT_IP', $server)) {
            return $server['HTTP_X_CLUSTER_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_FORWARDED_FOR', $server)) {
            return $server['HTTP_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_FORWARDED', $server)) {
            return $server['HTTP_FORWARDED'];
        }

        // Return unreliable IP address since all else failed
        return $server['REMOTE_ADDR'];
    }
}
