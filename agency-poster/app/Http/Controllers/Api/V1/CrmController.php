<?php

namespace App\Http\Controllers\Api\V1;

use Throwable;
use App\CrmObject;
use App\CrmManager;
use SimpleXMLElement;
use App\CrmUpdateStatus;
use App\JandexGeolocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

class CrmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'file' => [
                'required',
                'file',
                'max:51200',
                'mimetypes:text/xml',
            ],
        ]);

        $file = $request->file('file');

        if (! $file->isValid()) {
            return response()->json(['status' => false], 500);
        }

        try {
            $xml = new SimpleXMLElement($file->get());
        } catch (Throwable $e) {
            return response()->json(['status' => false], 500);
        }

        $crmUpdateStatus = CrmUpdateStatus::create([
            'user_id' => Auth::id(),
            'total' => count($xml->objects->object) + count($xml->managers->manager),
        ]);

        $crmUpdateStatus->errors = [];
        $crmUpdateStatus->save();

        $response = json_encode(['status' => true]);

        ignore_user_abort(true);
        ob_start();

        echo $response;

        $contentLocation = config('app.url')
            .'/api/v1/crm/update-statuses/'
            .$crmUpdateStatus->id;

        header('Connection: close', true, 202);
        header('Content-Type: application/json');
        header('Content-Length: '.strlen($response));
        header('Content-Location: '.$contentLocation);

        ob_end_flush();
        ob_flush();
        flush();

        if (session_id()) {
            session_write_close();
        }

        set_time_limit(0);

        $this->updateCrmManagers($crmUpdateStatus, $xml->managers);
        $this->updateCrmObjects($crmUpdateStatus, $xml->objects);

        $crmUpdateStatus->status = CrmUpdateStatus::UPDATED_STATUS;
        $crmUpdateStatus->save();
    }

    /**
     * @param  \App\CrmUpdateStatus  $crmUpdateStatus
     * @param  \SimpleXMLElement  $managers
     * @return void
     */
    protected function updateCrmManagers(CrmUpdateStatus $crmUpdateStatus, SimpleXMLElement $managers)
    {
        foreach ($managers->manager as $manager) {
            try {
                $id = (int) $manager['id'];

                $crmManager = CrmManager::find($id) ?: new CrmManager(['id' => $id]);

                $crmManager->type = trim((string) $manager->type) ?: null;
                $crmManager->kod = trim((string) $manager->kod) ?: null;
                $crmManager->name = trim((string) $manager->name) ?: null;
                $crmManager->surname = trim((string) $manager->surname) ?: null;
                $crmManager->sname = trim((string) $manager->sname) ?: null;

                $phones = $crmManager->phones;

                $phones = array_filter(
                    array_map(
                        function ($phone) {
                            $phone = str_replace(
                                ['8 (0162)'],
                                ['+375162'],
                                $phone
                            );

                            $res = '';
                            $len = strlen($phone);

                            for ($i = 0; $i < $len; $i++) {
                                if (preg_match('/\d/', $phone[$i])) {
                                    $res .= $phone[$i];
                                }
                            }

                            return $res;
                        },
                        explode(',', trim((string) $manager->phones))
                    )
                );

                $crmManager->phones = $phones ?: [];

                $crmManager->email = trim((string) $manager->email) ?: null;

                $crmManager->save();
            } catch (Throwable $e) {}

            $crmUpdateStatus->increment('updated');
            $crmUpdateStatus->save();
        }
    }

    /**
     * @param  \App\CrmUpdateStatus  $crmUpdateStatus
     * @param  \SimpleXMLElement  $objects
     * @return void
     */
    protected function updateCrmObjects(CrmUpdateStatus $crmUpdateStatus, SimpleXMLElement $objects)
    {
        foreach ($objects->object as $object) {
            try {
                $id = (int) $object['id'];

                $crmObject = CrmObject::find($id) ?: new CrmObject(['id' => $id]);

                $crmObject->transaction = trim((string) $object->transaction) ?: null;
                $crmObject->manager_id = (int) $object->manager_id ?: null;
                $crmObject->disabled = (int) $object->disabled == 1;
                $crmObject->category = trim((string) $object->category) ?: null;
                $crmObject->type = trim((string) $object->type) ?: null;
                $crmObject->title = trim((string) $object->title) ?: null;
                $crmObject->description = trim((string) $object->description) ?: null;
                $crmObject->lot = trim((string) $object->lot) ?: null;
                $crmObject->foto = trim((string) $object->foto) ?: null;
                $crmObject->ocenka = (int) $object->ocenka ?: null;
                $crmObject->vid = trim((string) $object->vid) ?: null;
                $crmObject->label = trim((string) $object->label) ?: null;

                $crmObject->price = (int) $object->prices->price ?: null;
                $crmObject->price_per_sqm = trim((string) $object->prices->price_per_sqm) ?: null;
                $crmObject->currency = trim((string) $object->prices->currency) ?: null;
                $crmObject->pricenow = (int) $object->prices->pricenow ?: null;

                $crmObject->region = trim((string) $object->place->region) ?: null;
                $crmObject->city = trim((string) $object->place->city) ?: null;
                $crmObject->realcity = trim((string) $object->place->realcity) ?: null;
                $crmObject->street = trim((string) $object->place->street) ?: null;
                $crmObject->microdistrict = trim((string) $object->place->microdistrict) ?: null;

                $coordinates = $crmObject->gps_coordinats;

                $coordinates = array_filter(
                    array_map(
                        function ($coordinate) {
                            return (float) str_replace(',', '.', $coordinate);
                        },
                        explode(',', trim((string) $object->place->gps_coordinats), 2)
                    )
                );

                $crmObject->gps_coordinats = $coordinates ?: [];

                $crmObject->num_home = trim((string) $object->place->num_home) ?: null;

                $crmObject->area_snb = (float) str_replace(',', '.', $object->square->area_snb) ?: null;
                $crmObject->area = (float) str_replace(',', '.', $object->square->area) ?: null;
                $crmObject->living_space = (float) str_replace(',', '.', $object->square->living_space) ?: null;
                $crmObject->kitchen_area = (float) str_replace(',', '.', $object->square->kitchen_area) ?: null;

                $crmObject->year_built = (int) $object->characteristics->year_built ?: null;
                $crmObject->wall_material = trim((string) $object->characteristics->wall_material) ?: null;
                $crmObject->MATROOF = trim((string) $object->characteristics->MATROOF) ?: null;
                $crmObject->floor_apartment = (int) $object->characteristics->floor_apartment ?: null;
                $crmObject->number_of_floors = (int) $object->characteristics->number_of_floors ?: null;
                $crmObject->rooms = (int) $object->characteristics->rooms ?: null;
                $crmObject->bathroom = trim((string) $object->characteristics->bathroom) ?: null;
                $crmObject->balcony = trim((string) $object->characteristics->balcony) ?: null;
                $crmObject->land_area = (float) str_replace(',', '.', $object->characteristics->land_area) ?: null;
                $crmObject->accomplishment = trim((string) $object->characteristics->accomplishment) ?: null;
                $crmObject->outbuildings = trim((string) $object->characteristics->outbuildings) ?: null;
                $crmObject->road = trim((string) $object->characteristics->road) ?: null;
                $crmObject->newflat = (int) $object->characteristics->newflat == 1;

                $crmObject->electricity = trim((string) $object->сommunications->electricity) ?: null;
                $crmObject->heating = trim((string) $object->сommunications->heating) ?: null;
                $crmObject->water_supply = trim((string) $object->сommunications->water_supply) ?: null;
                $crmObject->sewerage = trim((string) $object->сommunications->sewerage) ?: null;

                $crmObject->images = [];

                if (
                    ! $crmObject->disabled
                    && ! JandexGeolocation::find($crmObject->id)
                ) {
                    $geolocation = new JandexGeolocation([
                        'crm_object_id' => $crmObject->id,
                    ]);

                    $geolocation->findFor($crmObject);
                    $geolocation->save();
                }

                $crmObject->save();
            } catch (Throwable $e) {
                Log::error("CRM object (lot {$crmObject->lot}) update error: {$e->getMessage()}");
            }

            $crmUpdateStatus->increment('updated');
            $crmUpdateStatus->save();
        }
    }
}
