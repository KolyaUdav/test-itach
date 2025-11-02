<?php

namespace App\Services\API;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PriceHandler
{
    const API_URL = 'https://dev2.a100.itach.by/api/v1/itach.azs/fuel/';
    const API_H_ACCEPT = 'application/json';
    const API_H_TOKEN = '9739ab9d35b975d4643c5a7d19b5ec62';

    const BX_PARAMS_MAP = [
        'CODE' => Order::FIELD_FUEL_TYPE,
        'PRICE' => Order::FIELD_COST_IN_TIME,

    ];

    public static function getPrices(): array
    {
        $result = self::sendRequest();
        $mappedResult = self::map($result);

        return $mappedResult;
    }

    public static function getPriceByCode(string $code, array $mappedItems): float
    {
        $item = array_filter($mappedItems, fn ($mItem) => $mItem[Order::FIELD_FUEL_TYPE] === $code);
        $item = array_shift($item);

        if (!$item) {
            throw new \Exception(config('errors.api_price.el_code_not_exists', 'Элемент с указанным кодом отсутствует'));
        }

        return (float)$item[Order::FIELD_COST_IN_TIME];
    }

    private static function map(array $bxResult): array
    {
        return array_map(function ($bxItem) {
            return [
                self::BX_PARAMS_MAP['CODE'] => $bxItem['CODE'],
                self::BX_PARAMS_MAP['PRICE'] => $bxItem['PRICE'],
            ];
        }, $bxResult);
    }

    private static function sendRequest(): array
    {
        $response = Http::acceptJson()
            ->withoutVerifying()
            ->withHeaders([
                'Accept' => self::API_H_ACCEPT,
                'BX-token' => self::API_H_TOKEN,
            ])
            ->get(self::API_URL);
        
        if ($response->ok()) {
            $result = @json_decode($response->body(), true);

            if (isset($result['status']) && $result['status'] === 'error') {
                throw new \Exception(config('errors.api_price.invalid_auth', 'Не удалось авторизоваться'));
            }

            if (isset($result['status']) && $result['status'] === 'success' && isset($result['data'])) {
                return $result['data'];
            }
        } else {
            throw new \Exception(config('errors.api_price.bad_request', 'Не удалось получить доступ к API'));
        }

        return [];
    }
}
