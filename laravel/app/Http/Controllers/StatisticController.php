<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StatisticRepository;
use App\Repositories\GeneralRepository;
use App\Repositories\EmployeeRepository;

class StatisticController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new StatisticRepository;
    }

    public function getStatistic()
    {
        //call getGeneralTypesSelect method from GeneralRepository to get work types - select
        $this->repo = new GeneralRepository;
        $work_types = $this->repo->getGeneralTypesSelect(5, 1);

        //call getStatistic method from StatisticRepository to get statistic
        $this->repo = new StatisticRepository;
        $statistic = $this->repo->getStatistic();

        //if response status = 0 return error message
        if ($work_types['status'] == 0 || $statistic['status'] == 0)
        {
            return view('errors.500');
        }

        return view('statistic', ['work_types' => $work_types['data'], 'statistic' => $statistic['data']]);
    }

    public function filterStatistic(Request $request)
    {
        $work_type = $request->work_type;

        //call getStatistic method from StatisticRepository to get statistic with work type filter
        $statistic = $this->repo->getStatistic($work_type);

        return response()->json($statistic);
    }
}
