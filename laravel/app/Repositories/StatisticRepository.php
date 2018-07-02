<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Employee;

class StatisticRepository
{
    //get statistic
    public function getStatistic($work_type = false)
    {
        try
        {
            //set statistic array
            $statistic_array = [];

            $active_employees = Employee::where('status_id', '=', 1)->where('id', '!=', 1);

            if ($work_type)
            {
                $active_employees->where('work_type_id', '=', $work_type);
            }

            $active_employees = $active_employees->count();

            /******************************************************************************/

            $fixed_term_contract = Employee::where('status_id', '=', 1)->where('contract_type_id', '=', 57)->where('id', '!=', 1);

            if ($work_type)
            {
                $fixed_term_contract->where('work_type_id', '=', $work_type);
            }

            $fixed_term_contract = $fixed_term_contract->count();

            /******************************************************************************/

            $men = Employee::where('status_id', '=', 1)->where('sex', '=', 'M')->where('id', '!=', 1);

            if ($work_type)
            {
                $men->where('work_type_id', '=', $work_type);
            }

            $men = $men->count();

            $statistic_array['active_employees'] = $active_employees;
            $statistic_array['fixed_term_contract'] = $fixed_term_contract;
            $statistic_array['indefinite_contract'] = $active_employees - $fixed_term_contract;
            $statistic_array['men'] = $men;
            $statistic_array['women'] = $active_employees - $men;

            return ['status' => 1, 'data' => $statistic_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
