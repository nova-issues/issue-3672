<?php

namespace App\Nova\Fields;

use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;

class BookPurchase
{
    use ConditionallyLoadsAttributes;

    /**
     * Purchase type.
     *
     * @var string
     */
    protected $type;

    /**
     * Appends fields.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * Construct a new object.
     *
     * @param  string|null  $type
     */
    public function __construct($type = null)
    {
        $this->type = $type ?? 'personal';
    }

    /**
     * Appends with following fields.
     *
     * @param  array  $fields
     * @return $this
     */
    public function appends(array $fields)
    {
        $this->appends = $fields;

        return $this;
    }

    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Currency::make('Price')->rules(['required', 'numeric']),

            Select::make('Type')->options([
                'personal' => 'Personal',
                'gift' => 'Gift',
            ])->default(function ($request) {
                if ($request->isCreateOrAttachRequest()) {
                    return $this->type;
                }
            }),

            DateTime::make('Purchased At')
                ->default(function ($request) {
                    if ($request->isCreateOrAttachRequest()) {
                        return now()->second(0);
                    }
                })->incrementPickerMinuteBy(1),

            $this->merge($this->appends),
        ];
    }
}
