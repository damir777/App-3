<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class xxTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('code')->unsigned();
            $table->string('name', 70);
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
        });

        Schema::create('machine_components', function (Blueprint $table) {
            $table->increments('id');
            $table->char('fluid', 1);
            $table->char('filter', 1);
            $table->string('name', 30);
        });

        Schema::create('investors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('country_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->string('city', 50);
            $table->string('address');
            $table->softDeletes();

            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('city_id')->references('id')->on('cities');
        });

        Schema::create('manufacturers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });

        Schema::create('general_types', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->unsigned();
            $table->string('name');
            $table->softDeletes();
        });

        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('code')->unsigned()->unique();
            $table->integer('manufacturer_id')->unsigned();
            $table->string('name');
            $table->string('model', 150);
            $table->string('picture', 40)->nullable();
            $table->smallInteger('manufacture_year')->unsigned();
            $table->string('serial_number', 150);
            $table->mediumInteger('mass')->unsigned();
            $table->integer('machine_type_id')->unsigned();
            $table->string('pin', 150);
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
            $table->mediumInteger('start_working_hours')->unsigned();
            $table->mediumInteger('end_working_hours')->unsigned()->nullable();
            $table->string('register_number', 15)->nullable();
            $table->date('register_date')->nullable();
            $table->date('certificate_end_date');
            $table->integer('status_id')->unsigned();
            $table->mediumText('notes')->nullable();
            $table->softDeletes();

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->foreign('machine_type_id')->references('id')->on('general_types');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->index('name');
        });

        Schema::create('tools', function (Blueprint $table) {
            $table->increments('id');
            //$table->mediumInteger('code')->unsigned()->unique();
            $table->mediumInteger('code')->unsigned()->nullable();
            $table->integer('manufacturer_id')->unsigned();
            $table->string('name');
            $table->string('model', 150);
            $table->string('picture', 40)->nullable();
            $table->smallInteger('manufacture_year')->unsigned();
            $table->string('serial_number', 150);
            $table->mediumInteger('mass')->unsigned();
            $table->integer('tool_type_id')->unsigned();
            $table->string('internal_code', 150);
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
            $table->integer('status_id')->unsigned();
            $table->mediumText('notes')->nullable();
            $table->softDeletes();

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->foreign('tool_type_id')->references('id')->on('general_types');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->index('name');
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('code')->unsigned()->unique();
            $table->integer('manufacturer_id')->unsigned();
            $table->string('name');
            $table->string('model', 150);
            $table->string('picture', 40)->nullable();
            $table->smallInteger('manufacture_year')->unsigned();
            $table->string('serial_number', 150);
            $table->mediumInteger('mass')->unsigned();
            $table->integer('equipment_type_id')->unsigned();
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
            $table->integer('status_id')->unsigned();
            $table->mediumText('notes')->nullable();
            $table->softDeletes();

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->foreign('equipment_type_id')->references('id')->on('general_types');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->index('name');
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('code')->unsigned()->unique();
            $table->integer('manufacturer_id')->unsigned();
            $table->string('name');
            $table->string('model', 150);
            $table->string('picture', 40)->nullable();
            $table->smallInteger('manufacture_year')->unsigned();
            $table->mediumInteger('mass')->unsigned();
            $table->integer('vehicle_type_id')->unsigned();
            $table->tinyInteger('seats_number')->unsigned();
            $table->string('chassis_number', 100);
            $table->integer('fuel_type_id')->unsigned();
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
            $table->mediumInteger('start_mileage')->unsigned();
            $table->mediumInteger('end_working_hours')->unsigned()->nullable();
            $table->string('register_number', 15);
            $table->date('register_date');
            $table->integer('status_id')->unsigned();
            $table->mediumText('notes')->nullable();
            $table->softDeletes();

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->foreign('vehicle_type_id')->references('id')->on('general_types');
            $table->foreign('fuel_type_id')->references('id')->on('general_types');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->index('name');
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 25)->unique();
            $table->string('name', 150);
            $table->integer('work_type_id')->unsigned();
            $table->integer('contract_type_id')->unsigned();
            $table->string('picture', 40)->nullable();
            $table->char('sex', 1);
            $table->string('oib', 13)->unique();
            $table->date('birth_date');
            $table->integer('citizenship_id')->unsigned();
            $table->string('birth_city', 50);
            $table->integer('country_id')->unsigned();
            $table->smallInteger('city_id')->unsigned();
            $table->string('city', 50);
            $table->string('address');
            $table->string('phone', 100);
            $table->date('contract_start_date');
            $table->date('contract_expire_date')->nullable();
            $table->date('medical_certificate_expire_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned();
            $table->softDeletes();

            $table->foreign('work_type_id')->references('id')->on('general_types');
            $table->foreign('contract_type_id')->references('id')->on('general_types');
            $table->foreign('citizenship_id')->references('id')->on('countries');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->index('name');
        });

        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('code')->unsigned();
            $table->string('name');
            $table->integer('country_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->string('city', 50);
            $table->string('address');
            $table->integer('investor_id')->unsigned();
            $table->date('start_date');
            $table->date('plan_end_date');
            $table->date('end_date')->nullable();
            $table->integer('project_manager_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->mediumText('notes')->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->softDeletes();

            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('investor_id')->references('id')->on('investors');
            $table->foreign('project_manager_id')->references('id')->on('employees');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::create('site_manipulations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('manipulator_id')->unsigned();
            $table->tinyInteger('resource_type')->unsigned();
            $table->integer('resource_id')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('manipulator_id')->references('id')->on('employees');
        });

        Schema::create('additional_manipulations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('manipulator_id')->unsigned();
            $table->tinyInteger('resource_type')->unsigned();
            $table->integer('resource_id')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('manipulator_id')->references('id')->on('employees');
        });

        Schema::create('parking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->integer('status_id')->unsigned();
            $table->mediumText('notes')->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->softDeletes();

            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::create('parking_manipulations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parking_id')->unsigned();
            $table->integer('manipulator_id')->unsigned();
            $table->tinyInteger('resource_type')->unsigned();
            $table->integer('resource_id')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->foreign('parking_id')->references('id')->on('parking');
            $table->foreign('manipulator_id')->references('id')->on('employees');
        });

        Schema::create('employee_manipulations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('manipulator_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->foreign('type_id')->references('id')->on('general_types');
            $table->foreign('manipulator_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('dwa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('machine_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->date('activity_date');
            $table->char('machine_checked', 1)->default('F');
            $table->char('damage', 1)->default('F');
            $table->integer('confirmation_head_of_site_id')->unsigned()->nullable();
            $table->integer('confirmation_manager_id')->unsigned()->nullable();

            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('creator_id')->references('id')->on('employees');
        });

        Schema::create('dwa_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dwa_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('tool_id')->nullable();
            $table->integer('activity_id')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->mediumInteger('start_working_hours')->unsigned();
            $table->mediumInteger('end_working_hours')->unsigned();

            $table->foreign('dwa_id')->references('id')->on('dwa');
            $table->foreign('creator_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('activity_id')->references('id')->on('general_types');
        });

        Schema::create('dwa_fuel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dwa_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->smallInteger('quantity')->unsigned();
            $table->string('invoice_number', 25)->nullable();

            $table->foreign('dwa_id')->references('id')->on('dwa');
            $table->foreign('creator_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('dwa_fluids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dwa_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('component_id')->unsigned();
            $table->smallInteger('quantity')->unsigned();

            $table->foreign('dwa_id')->references('id')->on('dwa');
            $table->foreign('creator_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('component_id')->references('id')->on('machine_components');
        });

        Schema::create('dwa_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dwa_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->integer('component_id')->unsigned();
            $table->tinyInteger('quantity')->unsigned();

            $table->foreign('dwa_id')->references('id')->on('dwa');
            $table->foreign('creator_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('component_id')->references('id')->on('machine_components');
        });

        Schema::create('dwa_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dwa_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('employee_id')->unsigned();
            $table->text('note');
            $table->string('photo', 40)->nullable();

            $table->foreign('dwa_id')->references('id')->on('dwa');
            $table->foreign('creator_id')->references('id')->on('employees');
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('problem_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->text('description');
            $table->string('photo', 40)->nullable();
            $table->dateTime('report_time');
            $table->integer('seen_employee_id')->unsigned()->nullable();
            $table->dateTime('seen_time')->nullable();

            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
