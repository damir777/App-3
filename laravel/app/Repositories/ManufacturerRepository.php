<?php

namespace App\Repositories;

use Exception;
use App\Manufacturer;

class ManufacturerRepository
{
    //insert manufacturer
    public function insertManufacturer($name)
    {
        try
        {
            $manufacturer = new Manufacturer;
            $manufacturer->name = $name;
            $manufacturer->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get manufacturers - select
    public function getManufacturersSelect()
    {
        try
        {
            //get manufacturers
            $manufacturers = Manufacturer::select('id', 'name')->orderBy('name', 'asc')->get();

            //set manufacturers array
            $manufacturers_array = array();

            //loop through all manufacturers
            foreach ($manufacturers as $manufacturer)
            {
                //add manufacturer to manufacturers array
                $manufacturers_array[$manufacturer->id] = $manufacturer->name;
            }

            return ['status' => 1, 'data' => $manufacturers_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
