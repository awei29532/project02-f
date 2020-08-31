<?php

namespace App\Service\Games;

use DateTime;
use GuzzleHttp\Client;
use SimpleXMLElement;
use Exception;

class S128Cock implements GameInterface
{
    private string $apiHost;
    private string $apiKey;
    private string $gameHost;
    private string $agentCode;
    private string $gameCode;

    public function __construct(string $gameCode)
    {
        // TODO: extract out the configuration
        $this->gameCode = $gameCode;
        $this->apiHost = env('GAME_S128COCK_HOST');
        $this->apiKey = env('GAME_S128COCK_KEY');
        $this->agentCode = env('GAME_S128COCK_AGENT');
        $this->gameHost = env('GAME_S128COCK_GAME_HOST');
    }

    // TODO: Add a method of registering account.

    public function getBalance(string $playerId)
    {
        $apiResource = '/get_balance.aspx';
        $url = $this->apiHost . $apiResource;
        $postData = [
            'api_key' => $this->apiKey,
            'agent_code' => $this->agentCode,
            'login_id' => $playerId
        ];

        $responseContent = $this->sendPostRequest($url, $postData);

        ['status_code' => $statusCode, 'balance' => $balance] = $this->getDataListFromXmlString(
            $responseContent,
            ['status_code', 'balance']
        );

        // TODO: return false on error
        return (float)$balance;
    }

    public function deposit(string $playerId, float $amount): array
    {
        $apiResource = '/deposit.aspx';
        $url = $this->apiHost . $apiResource;
        // TODO: name
        // TODO: ref_no
        $postData = [
            'api_key' => $this->apiKey,
            'agent_code' => $this->agentCode,
            'login_id' => $playerId,
            'name' => $playerId,
            'amount' => $amount,
            'ref_no' => microtime()
        ];

        $responseContent = $this->sendPostRequest($url, $postData);

        ['status_code' => $statusCode, 'balance_close' => $balance] = $this->getDataListFromXmlString(
            $responseContent,
            ['status_code', 'balance_close']
        );

        // TODO: Define the return value on error

        return [
            'result' => $statusCode === '00',
            'balance' => (float)$balance
        ];
    }

    public function withdraw(string $playerId, float $amount = null)
    {
        $apiResource = '/withdraw.aspx';
        $url = $this->apiHost . $apiResource;

        if (!isset($amount)) {
            $amount = $this->getBalance($playerId);
        }

        $postData = [
            'api_key' => $this->apiKey,
            'agent_code' => $this->agentCode,
            'login_id' => $playerId,
            'amount' => $amount,
            'ref_no' => 'TEST' . (new DateTime())->format('YmdHisv')
        ];

        $responseContent = $this->sendPostRequest($url, $postData);
        ['status_code' => $statusCode, 'balance_close' => $balance] = $this->getDataListFromXmlString(
            $responseContent,
            ['status_code', 'balance_close']
        );

        // TODO: return false on error
        return (float)$balance;
    }

    public function launch(string $playerId)
    {
        $sessionId = $this->getSessionId($playerId);

        $gamePath = '/api/auth_login.aspx';
        $url = $this->gameHost . $gamePath;

        return [
            'url' => $url,
            'method' => 'POST',
            'body' => [
                'session_id' => $sessionId,
                'login_id' => $playerId
            ]
        ];
    }

    public function getGameCode(): string
    {
        return $this->gameCode;
    }

    private function getSessionId(string $playerId)
    {
        $apiResource = '/get_session_id.aspx';
        $url = $this->apiHost . $apiResource;
        $postData = [
            'api_key' => $this->apiKey,
            'agent_code' => $this->agentCode,
            'login_id' => $playerId,
            'name' => $playerId,
        ];

        $responseContent = $this->sendPostRequest($url, $postData);
        ['status_code' => $statusCode, 'session_id' => $sessionId] = $this->getDataListFromXmlString(
            $responseContent,
            ['status_code', 'session_id']
        );

        return $sessionId;
    }

    private function sendPostRequest(string $url, array $data)
    {
        $client = new Client();
        $response = $client->request(
            'POST',
            $url,
            ['form_params' => $data]
        );

        return (string)$response->getBody();
    }

    /**
     * @param string $xmlStr
     * @param array $tagNameList
     *
     * @return array|false return false on error
     */
    private function getDataListFromXmlString(string $xmlStr, array $tagNameList)
    {
        if (!$xmlElement = $this->getXmlElementFromString($xmlStr)) {
            return false;
        }

        $dataList = [];
        foreach ($tagNameList as $tagName) {
            $dataList[$tagName] = $this->fetchValueOfXmlElement($xmlElement, $tagName);
        }

        return $dataList;
    }

    /**
     * @param string $xmlStr
     *
     * @return SimpleXMLElement|false return false on error
     */
    private function getXmlElementFromString(string $xmlStr)
    {
        try {
            return new SimpleXMLElement($xmlStr);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param SimpleXMLElement $xmlElement
     * @param string $tagName
     *
     * @return string|null return null if the tag doesn't exist
     */
    private function fetchValueOfXmlElement(SimpleXMLElement $xmlElement, string $tagName)
    {
        $value = $xmlElement->{$tagName}[0];

        return isset($value) ? (string)$value : $value;
    }
}
