<?php

namespace App\Actions\Cadastral;

use App\Models\Cadastral\Prop;
use App\Models\Cadastral\PropComponent;
use App\YandexGeocoder;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UploadProps
{
    /**
     * @param  array  $input
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function upload(array &$input)
    {
        Validator::make($input, [
            'props' => ['required', 'file', 'mimes:xlsx', 'max:51200'],
        ], __('api.errors.validation'))->validate();

        $xlsx = \SimpleXLSX::parseData($input['props']->get());

        $rows = $xlsx->rows();

        $offset = 0;

        foreach ($rows as $key => $value) {
            if ($value[0] == 'п/п') {
                $offset = $key + 1;

                break;
            }

            continue;
        }

        $rows = array_splice($rows, $offset);

        $geocoder = new YandexGeocoder();

        set_time_limit(0);

        foreach ($rows as $row) {
            $attributes = [];

            if (isset($row[1])) {
                $typeAttributes = [
                    'kind' => 'type',
                    'name' => trim($row[1]),
                ];

                $attributes['type_id'] = PropComponent::firstOrCreate($typeAttributes)->id;
            }

            if (isset($row[2])) {
                $attributes['inventory_number'] = trim($row[2]);
            }

            if (isset($row[4])) {
                $functionAttributes = [
                    'kind' => 'function',
                    'name' => trim($row[4]),
                ];

                if (isset($attributes['type_id'])) {
                    $functionAttributes['type_id'] = $attributes['type_id'];
                }

                $attributes['function_id'] = PropComponent::firstOrCreate($functionAttributes)->id;
            }

            if (isset($row[5])) {
                $attributes['function_description'] = trim($row[5]);
            }

            if (isset($row[6])) {
                $attributes['name'] = trim($row[6]);
            }

            if (isset($row[7])) {
                $attributes['size'] = ((float) $row[7]) ?: null;
            }

            if (isset($row[8])) {
                $attributes['walls'] = trim($row[8]);
            }

            if (isset($row[9])) {
                $attributes['entry_date'] = date(
                    'Y-m-d H:i:s',
                    strtotime($row[9])
                );
            }

            if (isset($row[10])) {
                $attributes['transaction_date'] = date(
                    'Y-m-d H:i:s',
                    strtotime($row[10])
                );
            }

            if (isset($row[11])) {
                $attributes['transaction_id'] = trim($row[11]);
            }

            if (isset($row[12])) {
                $attributes['objects_count'] = ((int) $row[12]) ?: null;
            }

            if (isset($row[13])) {
                $attributes['price_byn'] = ((float) $row[13]) ?: null;
            }

            if (isset($row[14])) {
                $attributes['price_sqm_byn'] = ((float) $row[14]) ?: null;
            }

            if (isset($row[15])) {
                $attributes['price_description'] = trim($row[15]);
            }

            if (isset($row[16])) {
                $attributes['price_usd'] = ((float) $row[16]) ?: null;
            }

            if (isset($row[17])) {
                $attributes['price_sqm_usd'] = ((float) $row[17]) ?: null;
            }

            if (isset($row[18])) {
                $attributes['price_eur'] = ((float) $row[18]) ?: null;
            }

            if (isset($row[19])) {
                $attributes['price_sqm_eur'] = ((float) $row[19]) ?: null;
            }

            if (isset($row[20])) {
                $attributes['contract_price_amount'] = ((float) $row[20]) ?: null;
            }

            if (isset($row[21])) {
                $attributes['contract_price_currency'] = trim($row[21]);
            }

            if (isset($row[22])) {
                $attributes['pieces_before_transaction'] = str_replace(
                    ['{', '}'], '', trim($row[22])
                );
            }

            if (isset($row[23])) {
                $attributes['pieces_after_transaction'] = str_replace(
                    ['{', '}'], '', trim($row[23])
                );
            }

            if (isset($row[24])) {
                $attributes['rooms'] = ((int) $row[24]) ?: null;
            }

            if (isset($row[25])) {
                $attributes['floor'] = ((int) $row[25]) ?: null;
            }

            if (isset($row[26])) {
                $attributes['capital_inventory_number'] = trim($row[26]);
            }

            if (isset($row[27])) {
                $attributes['capital_size'] = ((float) $row[27]) ?: null;
            }

            if (isset($row[28])) {
                $attributes['capital_function'] = trim($row[28]);
            }

            if (isset($row[29])) {
                $attributes['capital_function_description'] = trim($row[29]);
            }

            if (isset($row[30])) {
                $attributes['capital_name'] = trim($row[30]);
            }

            if (isset($row[31])) {
                $attributes['capital_ready_percentage'] = ((int) $row[31]) ?: null;
            }

            if (isset($row[32])) {
                $attributes['capital_floors'] = ((int) $row[32]) ?: null;
            }

            if (isset($row[33])) {
                $attributes['capital_underground_floors'] = ((int) $row[33]) ?: null;
            }

            if (isset($row[34])) {
                $attributes['extra_objects'] = trim($row[34]);
            }

            if (isset($row[35])) {
                $attributes['land_cadastral_number'] = trim($row[35]);
            }

            if (isset($row[36])) {
                $attributes['land_function'] = trim($row[36]);
            }

            if (isset($row[37])) {
                $attributes['land_size'] = ((float) $row[37]) ?: null;
            }

            if (isset($row[38])) {
                $attributes['ate_unique_number'] = trim($row[38]);
            }

            if (isset($row[39])) {
                $attributes['markers'] = trim($row[39]);
            }

            try {
                $prop = Prop::updateOrCreate([
                    'transaction_id' => $attributes['transaction_id'],
                ], $attributes);

                if (isset($row[3])) {
                    $geocoder->getAddress(trim($row[3]), $prop);
                }
            } catch (Throwable $e) {
                //

                continue;
            }
        }
    }
}