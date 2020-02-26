<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Helpers\FunctionHelper;

class ProductsPrices extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = null;
    
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_UAH = 'UAH';

    public static $currencies = [
        self::CURRENCY_USD,
        self::CURRENCY_EUR,
        self::CURRENCY_UAH
    ];

    protected $fillable = [
        'product_id',
        'currency',
        'amount',
    ];

    /**
     * @return array
     */
    public static function getCurrenciesRates()
    {
        $response = FunctionHelper::post('https://api.privatbank.ua/p24api/exchange_rates?json&date=' . date('d.m.Y'), []);
        $resData = [];
        $needCurr = self::$currencies;
        if ($response) {
            foreach ($response->exchangeRate as $currRate) {
                if (property_exists($currRate, 'currency') && in_array($currRate->currency, $needCurr)) {
                    $resData[$currRate->currency] = $currRate->saleRateNB;
                }
            }
        }

        return $resData;
    }
}
