<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Vehicle;

class VehicleRepository
{
    //get vehicles
    public function getVehicles()
    {
        try
        {
            $vehicles = Vehicle::with('manufacturer', 'status')
                ->select('id', 'manufacturer_id', 'name', 'model', 'register_number', 'status_id')->paginate(30);

            return ['status' => 1, 'data' => $vehicles];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert vehicle
    public function insertVehicle($code, $manufacturer, $name, $model, $picture, $manufacture_year, $mass, $type, $seats_number,
        $chassis_number, $fuel_type, $purchase_date, $sale_date, $start_mileage, $end_working_hours, $register_number, $register_date,
        $status, $notes)
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

            //format register date
            $register_date = date('Y-m-d', strtotime($register_date));

            //start transaction
            DB::beginTransaction();

            $vehicle = new Vehicle;
            $vehicle->code = $code;
            $vehicle->manufacturer_id = $manufacturer;
            $vehicle->name = $name;
            $vehicle->model = $model;
            $vehicle->manufacture_year = $manufacture_year;
            $vehicle->mass = $mass;
            $vehicle->vehicle_type_id = $type;
            $vehicle->seats_number = $seats_number;
            $vehicle->chassis_number = $chassis_number;
            $vehicle->fuel_type_id = $fuel_type;
            $vehicle->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $vehicle->sale_date = $sale_date;
            }

            $vehicle->start_mileage = $start_mileage;

            if ($end_working_hours)
            {
                $vehicle->end_working_hours = $end_working_hours;
            }

            $vehicle->register_number = $register_number;
            $vehicle->register_date = $register_date;
            $vehicle->status_id = $status;
            $vehicle->notes = $notes;
            $vehicle->save();

            //call uploadPicture method from PictureRepository to upload picture
            $repo = new PictureRepository;
            $response = $repo->uploadPicture($picture, 'vehicles');

            //if response status = 0 return error message
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            //update picture
            $vehicle->picture = $response['data'];
            $vehicle->save();

            //commit transaction
            DB::commit();

            //set insert vehicle flash
            Session::flash('success_message', trans('main.vehicle_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get vehicle details
    public function getVehicleDetails($id)
    {
        try
        {
            $vehicle = Vehicle::find($id);

            //if vehicle doesn't exist return error message
            if (!$vehicle)
            {
                return ['status' => 0];
            }

            //format purchase date
            $vehicle->purchase_date = date('d.m.Y.', strtotime($vehicle->purchase_date));

            if ($vehicle->sale_date)
            {
                //format sale date
                $vehicle->sale_date = date('d.m.Y.', strtotime($vehicle->sale_date));
            }

            //format register date
            $vehicle->register_date = date('d.m.Y.', strtotime($vehicle->register_date));

            //set picture path
            $vehicle->picture = URL::to('/').'/laravel/storage/app/public/vehicles/'.$vehicle->picture;

            return ['status' => 1, 'data' => $vehicle];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update vehicle
    public function updateVehicle($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $mass, $type, $seats_number,
        $chassis_number, $fuel_type, $purchase_date, $sale_date, $start_mileage, $end_working_hours, $register_number, $register_date,
        $status, $notes, $new_picture)
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

            //format register date
            $register_date = date('Y-m-d', strtotime($register_date));

            //start transaction
            DB::beginTransaction();

            $vehicle = Vehicle::find($id);
            $vehicle->code = $code;
            $vehicle->manufacturer_id = $manufacturer;
            $vehicle->name = $name;
            $vehicle->model = $model;
            $vehicle->manufacture_year = $manufacture_year;
            $vehicle->mass = $mass;
            $vehicle->vehicle_type_id = $type;
            $vehicle->seats_number = $seats_number;
            $vehicle->chassis_number = $chassis_number;
            $vehicle->fuel_type_id = $fuel_type;
            $vehicle->purchase_date = $purchase_date;

            if ($sale_date )
            {
                $vehicle->sale_date = $sale_date;
            }
            else
            {
                $vehicle->sale_date = null;
            }

            $vehicle->start_mileage = $start_mileage;

            if ($end_working_hours)
            {
                $vehicle->end_working_hours = $end_working_hours;
            }
            else
            {
                $vehicle->end_working_hours = null;
            }

            $vehicle->register_number = $register_number;
            $vehicle->register_date = $register_date;
            $vehicle->status_id = $status;
            $vehicle->notes = $notes;
            $vehicle->save();

            if ($new_picture == 'T')
            {
                //call uploadPicture method from PictureRepository to upload picture
                $repo = new PictureRepository;
                $upload = $repo->uploadPicture($picture, 'vehicles');

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //call deletePicture method from PictureRepository to delete picture
                $repo = new PictureRepository;
                $delete = $repo->deletePicture($vehicle->picture, 'vehicles');

                //if response status = 0 return error message
                if ($delete['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update picture
                $vehicle->picture = $upload['data'];
                $vehicle->save();
            }

            //commit transaction
            DB::commit();

            //set update vehicle flash
            Session::flash('success_message', trans('main.vehicle_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
