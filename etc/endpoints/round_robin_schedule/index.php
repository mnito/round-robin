<?php

require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

function _add_access_control_headers(ResponseInterface $response)
{
    return $response->withHeader(
        'Access-Control-Allow-Origin', getenv('ACCESS_CONTROL_ALLOW_ORIGIN_HEADER_VALUE') ?: '*'
    )->withHeader(
        'Access-Control-Allow-Methods', 'GET, OPTIONS'
    )->withHeader(
        'Access-Control-Allow-Headers', '*'
    );
}

function _error(ResponseInterface $response, int $status, string $msg)
{
    $response->getBody()->write($msg."\n");
    return $response->withHeader("Content-Type", "text/markdown")->withStatus($status);
}

function _str_contains(string $s, $subs): bool
{
    return strpos($s, $subs) !== false;
}

function get_schedule(ServerRequestInterface $request, ResponseInterface $response = NULL): ResponseInterface
{
    if (empty($response)) {
        $response = new \GuzzleHttp\Psr7\Response();
    }

    $response = _add_access_control_headers($response);

    if (
        !empty($request->getHeaderLine('Accept'))
        && !(
            _str_contains($request->getHeaderLine('Accept'), '*/*')
            || _str_contains($request->getHeaderLine('Accept'), 'text/markdown')
        )
    ) {
        return _error($response, 406, "The only supported content type is text/markdown");
    }

    $params = $request->getQueryParams();

    if (empty($params['teams']) || !is_string($params['teams'])) {
        return _error($response, 422, "Teams must be provided as an array of strings");
    }

    if ((!is_numeric($params['rounds'] ?? '-1'))) {
        return _error($response, 422, "Rounds must be an integer");
    }

    if ((($params['shuffle'] ?? '') == 'false') && $params['shuffle_seed']) {
        return _error($response, 422, "Cannot set shuffle to false and shuffle seed");
    }

    $teams = explode(',', $params['teams']);
    $builder = new ScheduleBuilder($teams, $params['rounds'] ?? NULL ? (int) $params['rounds'] : NULL);

    if (($params['shuffle'] ?? '') == 'true' || (($params['shuffle_seed'] ?? NULL) != NULL)) {
        if (($params['shuffle_seed'] ?? NULL) != NULL && !is_numeric($params['shuffle_seed'])) {
            return _error($response, 422, "Shuffle seed must be an integer");
        }
        $builder->shuffle($params['shuffle_seed'] ?? NULL ? (int) $params['shuffle_seed'] : NULL);
    } else {
        $builder->doNotShuffle();
    }

    $schedule = $builder->build();

    $response->getBody()->write(
        schedule_to_markdown(
            $schedule,
            $params['name'] ?? NULL,
            $params['description'] ?? NULL
        )
    );

    return $response->withHeader(
        'Content-Type', 'text/markdown'
    )->withStatus(200);
}
