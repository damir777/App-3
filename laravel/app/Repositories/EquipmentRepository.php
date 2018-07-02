<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Equipment;

class EquipmentRepository
{
    //get equipment
    public function getEquipment()
    {
        try
        {
            $equipment = Equipment::with('manufacturer', 'status')
                ->select('id', 'manufacturer_id', 'name', 'model', 'serial_number', 'status_id')->paginate(30);

            return ['status' => 1, 'data' => $equipment];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert equipment
    public function insertEquipment($code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number, $mass, $type,
        $purchase_date, $sale_date, $status, $notes)
    {
        try
        {
            //format purchase date
            $purchase_date = date('Y-m-d', strtotime($purchase_date));

            if ($sale_date)
            {
                //format sale date
                $sale_date = date('Y-m-d', strtotime($sale_date));
            }

            //start transaction
            DB::beginTransaction();

            $equipment = new Equipment;
            $equipment->code = $code;
            $equipment->manufacturer_id = $manufacturer;
            $equipment->name = $name;
            $equipment->model = $model;
            $equipment->manufacture_year = $manufacture_year;
            $equipment->serial_number = $serial_number;
            $equipment->mass = $mass;
            $equipment->equipment_type_id = $type;
            $equipment->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $equipment->sale_date = $sale_date;
            }

            $equipment->status_id = $status;
            $equipment->notes = $notes;
            $equipment->save();

            //call uploadPicture method from PictureRepository to upload picture
            $repo = new PictureRepository;
            $response = $repo->uploadPicture($picture, 'equipment');

            //if response status = 0 return error message
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            //update picture
            $equipment->picture = $response['data'];
            $equipment->save();

            //commit transaction
            DB::commit();

            //set insert equipment flash
            Session::flash('success_message', trans('main.equipment_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get equipment details
    public function getEquipmentDetails($id)
    {
        try
        {
            $equipment = Equipment::find($id);

            //if equipment doesn't exist return error message
            if (!$equipment)
            {
                return ['status' => 0];
            }

            //format purchase date
            $equipment->purchase_date = date('d.m.Y.', strtotime($equipment->purchase_date));

            if ($equipment->sale_date)
            {
                //format sale date
                $equipment->sale_date = date('d.m.Y.', strtotime($equipment->sale_date));
            }

            //set picture path
            $equipment->picture = URL::to('/').'/laravel/storage/app/public/equipment/'.$equipment->picture;

            return ['status' => 1, 'data' => $equipment];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update equipment
    public function updateEquipment($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number, $mass,
        $type, $purchase_date, $sale_date, $status, $notes, $new_picture)
    {
        try
        {
            //format purchase date
            $purchase_date = date('Y-m-d', strtotime($purchase_date));

            if ($sale_date)
            {
                //format sale date
                $sale_date = date('Y-m-d', strtotime($sale_date));
            }

            //start transaction
            DB::beginTransaction();

            $equipment = Equipment::find($id);
            $equipment->code = $code;
            $equipment->manufacturer_id = $manufacturer;
            $equipment->name = $name;
            $equipment->model = $model;
            $equipment->manufacture_year = $manufacture_year;
            $equipment->serial_number = $serial_number;
            $equipment->mass = $mass;
            $equipment->equipment_type_id = $type;
            $equipment->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $equipment->sale_date = $sale_date;
            }
            else
            {
                $equipment->sale_date = null;
            }

            $equipment->status_id = $status;
            $equipment->notes = $notes;
            $equipment->save();

            if ($new_picture == 'T')
            {
                //call uploadPicture method from PictureRepository to upload picture
                $repo = new PictureRepository;
                $upload = $repo->uploadPicture($picture, 'equipment');

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //call deletePicture method from PictureRepository to delete picture
                $repo = new PictureRepository;
                $delete = $repo->deletePicture($equipment->picture, 'equipment');

                //if response status = 0 return error message
                if ($delete['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update picture
                $equipment->picture = $upload['data'];
                $equipment->save();
            }

            //commit transaction
            DB::commit();

            //set update equipment flash
            Session::flash('success_message', trans('main.equipment_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
