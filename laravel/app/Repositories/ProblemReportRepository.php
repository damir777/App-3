<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\ProblemReport;

class ProblemReportRepository
{
    //get problem reports
    public function getReports()
    {
        try
        {
            $reports = ProblemReport::with('employee', 'seenEmployee')
                ->select('id', 'employee_id', 'description', 'photo', DB::raw('DATE_FORMAT(report_time, "%d.%m.%Y. %H:%i") AS time'),
                    'seen_employee_id', DB::raw('DATE_FORMAT(seen_time, "%d.%m.%Y. %H:%i") AS seen_time'))
                ->paginate(30);

            foreach ($reports as $report)
            {
                if ($report->photo)
                {
                    $report->photo = URL::to('/').'/laravel/storage/app/public/photos/'.$report->photo;
                }

                if ($report->seen_employee_id)
                {
                    $report->seen_employee = $report->seenEmployee->name;
                }
            }

            return ['status' => 1, 'data' => $reports];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //save problem
    public function saveProblem($description, $photo)
    {
        try
        {
            //call getUserEmployeeId method from EmployeeRepository to get employee id
            $repo = new EmployeeRepository;
            $employee_id = $repo->getUserEmployeeId();

            //start transaction
            DB::beginTransaction();

            $report = new ProblemReport();
            $report->employee_id = $employee_id;
            $report->description = $description;
            $report->report_time = DB::raw('NOW()');
            $report->save();

            if ($photo)
            {
                //call uploadPhoto method from PictureRepository to upload photo
                $repo = new PictureRepository;
                $upload = $repo->uploadPhoto($photo);

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //save photo
                $report->photo = $upload['data'];
                $report->save();
            }

            //commit transaction
            DB::commit();

            return ['status' => 1, 'success' => trans('main.problem_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get counter
    public function getCounter()
    {
        $counter = ProblemReport::whereNull('seen_time')->count();

        return $counter;
    }

    //seen report
    public function seenReport($report_id)
    {
        try
        {
            $report = ProblemReport::find($report_id);

            //if report doesn't exist return error message
            if (!$report)
            {
                return ['status' => 0];
            }

            //call getUserEmployeeId method from EmployeeRepository to get employee id
            $repo = new EmployeeRepository;
            $employee_id = $repo->getUserEmployeeId();

            $report->seen_employee_id = $employee_id;
            $report->seen_time = DB::raw('NOW()');
            $report->save();

            //set insert report flash
            Session::flash('success_message', trans('main.report_seen'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
