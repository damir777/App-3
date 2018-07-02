<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Employee;
use App\Country;
use App\Status;
use App\MachineComponent;
use App\GeneralType;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Roles and admin
        |--------------------------------------------------------------------------
        */

        //insert user roles
        $roles = array(array('name' => 'Admin', 'display_name' => 'Administrator'), array('name' => 'Management',
            'display_name' => 'Uprava'), array('name' => 'HeadOfDepartment', 'display_name' => 'Voditelj odjela'),
            array('name' => 'HeadOfSite', 'display_name' => 'Voditelj gradilišta'), array('name' => 'Manager',
            'display_name' => 'Poslovođa'), array('name' => 'Employee', 'display_name' => 'Zaposlenik'), array('name' => 'Mechanic',
            'display_name' => 'Mehaničar'));

        Role::insert($roles);

        //insert admin
        $user = new User();
        $user->name = 'Damir Administrator';
        $user->email = 'damir071@live.com';
        $user->password = '$2a$06$/dy78APk1oUp6SwdQYuCXOBJFD5COnT7Zu9wEhTrMn9ZTCZ4kCVIi';
        $user->active = 'T';
        $user->save();

        //get role
        $role = Role::find(1);

        //attach admin role
        $user->attachRole($role);

        /*
        |--------------------------------------------------------------------------
        | Countries
        |--------------------------------------------------------------------------
        */

        //insert countries
        $countries = array(array('name' => 'Hrvatska'), array('name' => 'BIH'), array('name' => 'Srbija'),
            array('name' => 'Slovenija'), array('name' => 'Njemačka'));

        Country::insert($countries);

        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        //insert statuses
        $statuses = array(array('name' => 'Aktivan'), array('name' => 'Neaktivan'));

        Status::insert($statuses);

        /*
        |--------------------------------------------------------------------------
        | Machine components
        |--------------------------------------------------------------------------
        */

        //insert machine components
        $types = array(array('fluid' => 'T', 'filter' => 'T', 'name' => 'Motor'), array('fluid' => 'T', 'filter' => 'T', 'name' => 'Mjenjač'),
            array('fluid' => 'T', 'filter' => 'F', 'name' => 'Konverter'), array('fluid' => 'T', 'filter' => 'F', 'name' => 'Diferencijal'),
            array('fluid' => 'T', 'filter' => 'T', 'name' => 'Motor kupole'), array('fluid' => 'T', 'filter' => 'T', 'name' => 'Hidraulika'),
            array('fluid' => 'T', 'filter' => 'T', 'name' => 'Rashladna tekućina'),
            array('fluid' => 'T', 'filter' => 'F', 'name' => 'Planetar'), array('fluid' => 'F', 'filter' => 'T', 'name' => 'Zrak'),
            array('fluid' => 'T', 'filter' => 'T', 'name' => 'Hidr. motor gusjenice')
        );

        MachineComponent::insert($types);

        /*
        |--------------------------------------------------------------------------
        | Fuel types, contract types and work types
        |--------------------------------------------------------------------------
        */

        //insert general types (1 - machine types, 2 - tool types, 3 - equipment types, 4 - vehicle type, 5 - work types,
        //6 - fuel types, 7 - contract types, 8 - employee manipulation type, 9 - activity types)
        $types = array(array('type' => 5, 'name' => 'Administrator'), array('type' => 5, 'name' => 'Administrator mehanizacije'),
            array('type' => 5, 'name' => 'Administrator tehničke pripreme'),
            array('type' => 5, 'name' => 'Administrator za kadrovske i opće poslove'),
            array('type' => 5, 'name' => 'Direktor'),
            array('type' => 5, 'name' => 'Direktor sektora obrade tržišta'),
            array('type' => 5, 'name' => 'Direktor sektora ugovaranja'),
            array('type' => 5, 'name' => 'Geodetski tehničar'),
            array('type' => 5, 'name' => 'Građevinski radnik'),
            array('type' => 5, 'name' => 'Grupovođa - grupa 1'),
            array('type' => 5, 'name' => 'Grupovođa - grupa 2'),
            array('type' => 5, 'name' => 'Grupovođa - grupa 4'),
            array('type' => 5, 'name' => 'Grupovođa - grupa 5'),
            array('type' => 5, 'name' => 'Inženjer gradilišta'),
            array('type' => 5, 'name' => 'Inženjer tehničke pripreme'),
            array('type' => 5, 'name' => 'Inženjer zaštite okoliša'),
            array('type' => 5, 'name' => 'Knjigovođa'),
            array('type' => 5, 'name' => 'Mehaničar'),
            array('type' => 5, 'name' => 'Mehaničar - grupa 2'),
            array('type' => 5, 'name' => 'Mehaničar - grupa 4'),
            array('type' => 5, 'name' => 'Mehaničar - grupa 5'),
            array('type' => 5, 'name' => 'Mehaničar - zavarivač'),
            array('type' => 5, 'name' => 'Pomoćni građevinski radnik'),
            array('type' => 5, 'name' => 'Pomoćni radnik'),
            array('type' => 5, 'name' => 'Pomoćnik voditelja gradilišta'),
            array('type' => 5, 'name' => 'Poslovna tajnica'),
            array('type' => 5, 'name' => 'Poslovođa - grupa 5'),
            array('type' => 5, 'name' => 'Poslovođa - grupa 6'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - grupa 1'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - grupa 2'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - grupa 3'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - grupa 4'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - grupa 5'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - grupa 6'),
            array('type' => 5, 'name' => 'Rukovatelj građevinskim strojevima - vježbenik'),
            array('type' => 5, 'name' => 'Rukovoditelj odjela financija i općih poslova'),
            array('type' => 5, 'name' => 'Rukovoditelj sektora izgradnje'),
            array('type' => 5, 'name' => 'Skladištar'),
            array('type' => 5, 'name' => 'Spremačica - domaćica'),
            array('type' => 5, 'name' => 'Stručnjak zaštite na radu'),
            array('type' => 5, 'name' => 'Tehnički direktor'),
            array('type' => 5, 'name' => 'Voditelj gradilišta'),
            array('type' => 5, 'name' => 'Voditelj informatike'),
            array('type' => 5, 'name' => 'Voditelj odjela logističke pripreme'),
            array('type' => 5, 'name' => 'Voditelj projekta'),
            array('type' => 5, 'name' => 'Voditelj tehničke pripreme'),
            array('type' => 5, 'name' => 'Vozač teretnog vozila - grupa 2'),
            array('type' => 5, 'name' => 'Vozač teretnog vozila - grupa 3'),
            array('type' => 5, 'name' => 'Vozač teretnog vozila - grupa 4'),
            array('type' => 5, 'name' => 'Vozač teretnog vozila - grupa 5'),
            array('type' => 5, 'name' => 'Vozač teretnog vozila - grupa 6'),
            array('type' => 5, 'name' => 'Zidar'),
            array('type' => 5, 'name' => 'Zidar - grupovođa'),
            array('type' => 6, 'name' => 'Benzin'), array('type' => 6, 'name' => 'Dizel'),
            array('type' => 7, 'name' => 'Određeno'), array('type' => 7, 'name' => 'Neodređeno'),
            array('type' => 8, 'name' => 'Slobodni dani'), array('type' => 8, 'name' => 'Godišnji odmor'),
            array('type' => 8, 'name' => 'Bolovanje'),
            array('type' => 9, 'name' => 'Rušenje'), array('type' => 9, 'name' => 'Iskop'),
            array('type' => 9, 'name' => 'Utovar materijala'), array('type' => 9, 'name' => 'Transport stroja'),
            array('type' => 9, 'name' => 'Grijanje stroja'), array('type' => 9, 'name' => 'Kvar'), array('type' => 9, 'name' => 'Servis')
        );

        GeneralType::insert($types);

        /*
        |--------------------------------------------------------------------------
        | Insert employee for admin user
        |--------------------------------------------------------------------------
        */

        //insert admin employee
        $employee = new Employee();
        $employee->code = 1;
        $employee->name = 'Administrator';
        $employee->work_type_id = 1;
        $employee->contract_type_id = 58;
        $employee->picture = 'slika';
        $employee->sex = 'M';
        $employee->oib = 11111111111;
        $employee->birth_date = '2017-01-01';
        $employee->citizenship_id = 1;
        $employee->birth_city = 1;
        $employee->country_id = 1;
        $employee->city_id = 1;
        $employee->city = 'Ada';
        $employee->address = 'Adresa';
        $employee->phone = '111';
        $employee->contract_start_date = '2017-04-01';
        $employee->contract_end_date = '2017-04-01';
        $employee->user_id = 1;
        $employee->status_id = 1;
        $employee->save();
    }
}
