<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ProblemReport;
use App\Repositories\ProblemReportRepository;

class ProblemReportsController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ProblemReportRepository();
    }

    //get reports
    public function getReports()
    {
        //call getReports method from ProblemReportRepository to get problem reports
        $reports = $this->repo->getReports();

        //if response status = 0 return error message
        if ($reports['status'] == 0)
        {
            return view('errors.500');
        }

        return view('problemReports.list', ['reports' => $reports['data']]);
    }

    //save problem
    public function saveProblem(Request $request)
    {
        $description = $request->description;
        $photo = $request->photo;

        //validate form inputs
        $validator = Validator::make($request->all(), ProblemReport::$saveProblemRules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //call saveProblem method from ProblemReportRepository to save problem
        $response = $this->repo->saveProblem($description, $photo);

        return response()->json($response);
    }

    //get counter
    public function getCounter()
    {
        //call getCounter method from ProblemReportRepository to get reports counter
        $data = $this->repo->getCounter();

        return response()->json($data);
    }

    //seen report
    public function seenReport(Request $request)
    {
        $report_id = $request->report_id;

        //call seenReport method from ProblemReportRepository to seen report
        $response = $this->repo->seenReport($report_id);

        return response()->json($response);
    }
}
