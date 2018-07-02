<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Session;
use App\Parking;

class ParkingRepository
{
    //get parking
    public function getParking()
    {
        try
        {
            $parking = Parking::select('id', 'name', 'address', 'status_id')->paginate(30);

            return ['status' => 1, 'data' => $parking];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert parking
    public function insertParking($name, $address, $status, $notes, $latitude, $longitude)
    {
        try
        {
            $parking = new Parking;
            $parking->name = $name;
            $parking->address = $address;
            $parking->status_id = $status;
            $parking->notes = $notes;
            $parking->latitude = $latitude;
            $parking->longitude = $longitude;
            $parking->save();

            //set insert parking flash
            Session::flash('success_message', trans('main.parking_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get parking details
    public function getParkingDetails($id)
    {
        try
        {
            $parking = Parking::find($id);

            //if parking doesn't exist return error message
            if (!$parking)
            {
                return array('status' => 0);
            }

            return ['status' => 1, 'data' => $parking];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update parking
    public function updateParking($id, $name, $address, $status, $notes, $latitude, $longitude)
    {
        try
        {
            $parking = Parking::find($id);
            $parking->name = $name;
            $parking->address = $address;
            $parking->status_id = $status;
            $parking->notes = $notes;
            $parking->latitude = $latitude;
            $parking->longitude = $longitude;
            $parking->save();

            //set update parking flash
            Session::flash('success_message', trans('main.parking_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
