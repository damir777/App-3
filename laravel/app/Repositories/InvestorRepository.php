<?php

namespace App\Repositories;

use Exception;
use App\Investor;
use App\City;

class InvestorRepository
{
    //insert investor
    public function insertInvestor($name, $country, $city_id, $city, $address)
    {
        try
        {
            if ($country == 1)
            {
                //set city
                $city = City::find($city_id)->name;
            }

            $investor = new Investor;
            $investor->name = $name;
            $investor->country_id = $country;
            $investor->city_id = $city_id;
            $investor->city = $city;
            $investor->address = $address;
            $investor->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get investors - select
    public function getInvestorsSelect()
    {
        try
        {
            //get investors
            $investors = Investor::select('id', 'name')->orderBy('name', 'asc')->get();

            //set investors array
            $investors_array = array();

            //loop through all investors
            foreach ($investors as $investor)
            {
                //add investor to investors array
                $investors_array[$investor->id] = $investor->name;
            }

            return ['status' => 1, 'data' => $investors_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
