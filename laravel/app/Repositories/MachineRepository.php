<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Machine;
use App\MachineComponent;

class MachineRepository
{
    //get machines
    public function getMachines()
    {
        try
        {
            $machines = Machine::with('manufacturer', 'status')
                ->select('id', 'manufacturer_id', 'name', 'model', 'serial_number', 'status_id')->paginate(30);

            return ['status' => 1, 'data' => $machines];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert machine
    public function insertMachine($code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number, $mass, $type,
        $pin, $purchase_date, $sale_date, $start_working_hours, $end_working_hours, $register_number, $register_date, $certificate_end_date,
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

            if ($register_date)
            {
                //format register date
                $register_date = date('Y-m-d', strtotime($register_date));
            }

            //format certificate end date
            $certificate_end_date = date('Y-m-d', strtotime($certificate_end_date));

            //start transaction
            DB::beginTransaction();

            $machine = new Machine;
            $machine->code = $code;
            $machine->manufacturer_id = $manufacturer;
            $machine->name = $name;
            $machine->model = $model;
            $machine->manufacture_year = $manufacture_year;
            $machine->serial_number = $serial_number;
            $machine->mass = $mass;
            $machine->machine_type_id = $type;
            $machine->pin = $pin;
            $machine->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $machine->sale_date = $sale_date;
            }

            $machine->start_working_hours = $start_working_hours;

            if ($end_working_hours)
            {
                $machine->end_working_hours = $end_working_hours;
            }

            $machine->register_number = $register_number;
            $machine->register_date = $register_date;
            $machine->certificate_end_date = $certificate_end_date;
            $machine->status_id = $status;
            $machine->notes = $notes;
            $machine->save();

            //call uploadPicture method from PictureRepository to upload picture
            $repo = new PictureRepository;
            $response = $repo->uploadPicture($picture, 'machines');

            //if response status = 0 return error message
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            //update picture
            $machine->picture = $response['data'];
            $machine->save();

            //commit transaction
            DB::commit();

            //set insert machine flash
            Session::flash('success_message', trans('main.machine_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get machine details
    public function getMachineDetails($id)
    {
        try
        {
            $machine = Machine::find($id);

            //if machine doesn't exist return error message
            if (!$machine)
            {
                return ['status' => 0];
            }

            //format purchase date
            $machine->purchase_date = date('d.m.Y.', strtotime($machine->purchase_date));

            if ($machine->sale_date)
            {
                //format sale date
                $machine->sale_date = date('d.m.Y.', strtotime($machine->sale_date));
            }

            if ($machine->register_date)
            {
                //format register date
                $machine->register_date = date('d.m.Y.', strtotime($machine->register_date));
            }

            //format certificate end date
            $machine->certificate_end_date = date('d.m.Y.', strtotime($machine->certificate_end_date));

            //set picture path
            $machine->picture = URL::to('/').'/laravel/storage/app/public/machines/'.$machine->picture;

            return ['status' => 1, 'data' => $machine];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update machine
    public function updateMachine($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number, $mass,
        $type, $pin, $purchase_date, $sale_date, $start_working_hours, $end_working_hours, $register_number, $register_date,
        $certificate_end_date, $status, $notes, $new_picture)
    {
        try
        {
            //format purchase date
            $purchase_date = date('Y-m-d', strtotime($purchase_date));

            if ($sale_date )
            {
                //format sale date
                $sale_date = date('Y-m-d', strtotime($sale_date));
            }

            if ($register_date)
            {
                //format register date
                $register_date = date('Y-m-d', strtotime($register_date));
            }

            //format certificate end date
            $certificate_end_date = date('Y-m-d', strtotime($certificate_end_date));

            //start transaction
            DB::beginTransaction();

            $machine = Machine::find($id);
            $machine->code = $code;
            $machine->manufacturer_id = $manufacturer;
            $machine->name = $name;
            $machine->model = $model;
            $machine->manufacture_year = $manufacture_year;
            $machine->serial_number = $serial_number;
            $machine->mass = $mass;
            $machine->machine_type_id = $type;
            $machine->pin = $pin;
            $machine->purchase_date = $purchase_date;

            if ($sale_date)
            {
                $machine->sale_date = $sale_date;
            }
            else
            {
                $machine->sale_date = null;
            }

            $machine->start_working_hours = $start_working_hours;

            if ($end_working_hours)
            {
                $machine->end_working_hours = $end_working_hours;
            }
            else
            {
                $machine->end_working_hours = null;
            }

            $machine->register_number = $register_number;
            $machine->register_date = $register_date;
            $machine->certificate_end_date = $certificate_end_date;
            $machine->status_id = $status;
            $machine->notes = $notes;
            $machine->save();

            if ($new_picture == 'T')
            {
                //call uploadPicture method from PictureRepository to upload picture
                $repo = new PictureRepository;
                $upload = $repo->uploadPicture($picture, 'machines');

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //call deletePicture method from PictureRepository to delete picture
                $repo = new PictureRepository;
                $delete = $repo->deletePicture($machine->picture, 'machines');

                //if response status = 0 return error message
                if ($delete['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update picture
                $machine->picture = $upload['data'];
                $machine->save();
            }

            //commit transaction
            DB::commit();

            //set update machine flash
            Session::flash('success_message', trans('main.machine_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get machines - select
    public function getMachinesSelect($get_inactive = false)
    {
        try
        {
            //get machines
            $machines = Machine::select('id', 'name');

            if (!$get_inactive)
            {
                $machines->where('status_id', '=', 1);
            }

            $machines = $machines->get();

            //set machines array
            $machines_array = array();

            //add default option to machines array
            $machines_array[0] = trans('main.choose_machine');

            //loop through all machines
            foreach ($machines as $machine)
            {
                //add machine to machines array
                $machines_array[$machine->id] = $machine->name;
            }

            return ['status' => 1, 'data' => $machines_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get machine components
    public function getMachineComponents($type)
    {
        $components = MachineComponent::select('id', 'name');

        if ($type == 'fluid')
        {
            $components->where('fluid', '=', 'T');
        }
        else
        {
            $components->where('filter', '=', 'T');
        }

        $components = $components->get();

        return $components;
    }
}
