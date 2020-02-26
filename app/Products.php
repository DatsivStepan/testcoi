<?php

namespace App;

use Illuminate\Database\Eloquent\Model,    
    App\ProductsPrices;

class Products extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'user_id',
        'img_src',
    ];

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->img_src;
    }

    /**
     * @param string $curr
     * @param bool $withCurrncy
     * @return int
     */
    public function getPriceByCurr($curr, $withCurrncy = false)
    {
        $model = $this->pricesModels()->where('currency', $curr)->first();

        return ($model ? $model->amount : 0) . ($withCurrncy ? ' ' . $curr : '');
    }

    /**
     * @return App\User model
     */
    public function userModel()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * @return App\ProductsPrices model
     */
    public function pricesModels()
    {
        return $this->hasMany('App\ProductsPrices', 'product_id', 'id');
    }
}
