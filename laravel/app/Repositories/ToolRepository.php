<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Tool;

class ToolRepository
{
    //get tools
    public function getTools()
    {
        try
        {
            $tools = Tool::with('manufacturer', 'status')
                ->select('id', 'manufacturer_id', 'name', 'model', 'serial_number', 'status_id')->paginate(30);

            return ['status' => 1, 'data' => $tools];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert tool
    public function insertTool($code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number, $mass, $type,
        $internal_code, $purchase_date, $sale_date, $status, $notes)
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

            $tool = new Tool;
            $tool->code = $code;
            $tool->manufacturer_id = $manufacturer;
            $tool->name = $name;
            $tool->model = $model;
            $tool->manufacture_year = $manufacture_year;
            $tool->serial_number = $serial_number;
            $tool->mass = $mass;
            $tool->tool_type_id = $type;
            $tool->internal_code = $internal_code;
            $tool->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $tool->sale_date = $sale_date;
            }

            $tool->status_id = $status;
            $tool->notes = $notes;
            $tool->save();

            //call uploadPicture method from PictureRepository to upload picture
            $repo = new PictureRepository;
            $response = $repo->uploadPicture($picture, 'tools');

            //if response status = 0 return error message
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            //update picture
            $tool->picture = $response['data'];
            $tool->save();

            //commit transaction
            DB::commit();

            //set insert tool flash
            Session::flash('success_message', trans('main.tool_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get tool details
    public function getToolDetails($id)
    {
        try
        {
            $tool = Tool::find($id);

            //if tool doesn't exist return error message
            if (!$tool)
            {
                return ['status' => 0];
            }

            //format purchase date
            $tool->purchase_date = date('d.m.Y.', strtotime($tool->purchase_date));

            if ($tool->sale_date)
            {
                //format sale date
                $tool->sale_date = date('d.m.Y.', strtotime($tool->sale_date));
            }

            //set picture path
            $tool->picture = URL::to('/').'/laravel/storage/app/public/tools/'.$tool->picture;

            return ['status' => 1, 'data' => $tool];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update tool
    public function updateTool($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number, $mass,
        $type, $internal_code, $purchase_date, $sale_date, $status, $notes, $new_picture)
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

            $tool = Tool::find($id);
            $tool->code = $code;
            $tool->manufacturer_id = $manufacturer;
            $tool->name = $name;
            $tool->model = $model;
            $tool->manufacture_year = $manufacture_year;
            $tool->serial_number = $serial_number;
            $tool->mass = $mass;
            $tool->tool_type_id = $type;
            $tool->internal_code = $internal_code;
            $tool->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $tool->sale_date = $sale_date;
            }
            else
            {
                $tool->sale_date = null;
            }

            $tool->status_id = $status;
            $tool->notes = $notes;
            $tool->save();

            if ($new_picture == 'T')
            {
                //call uploadPicture method from PictureRepository to upload picture
                $repo = new PictureRepository;
                $upload = $repo->uploadPicture($picture, 'tools');

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //call deletePicture method from PictureRepository to delete picture
                $repo = new PictureRepository;
                $delete = $repo->deletePicture($tool->picture, 'tools');

                //if response status = 0 return error message
                if ($delete['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update picture
                $tool->picture = $upload['data'];
                $tool->save();
            }

            //commit transaction
            DB::commit();

            //set update tool flash
            Session::flash('success_message', trans('main.tool_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
