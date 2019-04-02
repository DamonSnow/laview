<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Resources\Schedule as ScheduleResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends ApiController
{
    /**
     * @param calendar
     * @param start
     * @param end
     * @info 获取某个条件下所有的计划
     */
    public function index()
    {
        //筛选条件暂定时间（between and），以及calendar
        $schedule = new Schedule();
        $schedule = $schedule->whereBetween('start_at', [Input::get('start'), Input::get('end')]);
        if (!empty(Input::get('calendar'))) {
            $schedule->where('calendar_id', Input::get('calendar'));
        }
        return ScheduleResource::collection($schedule->get());
    }

    public function getSchedules()
    {
        //筛选条件暂定时间（between and），以及calendar
        $schedule = new Schedule();
        $schedule = $schedule->whereBetween('start_at', [Input::get('start'), Input::get('end')]);
        if (!empty(Input::get('calendar'))) {
            $schedule->where('calendar_id', Input::get('calendar'));
        }
        return ScheduleResource::collection($schedule->get());
    }

    /**
     * @param id
     * @return json string
     * @info 获取某个计划的详细信息
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'calendar_id' => 'required',
            'title'       => 'required',
            'body'        => 'required',
            'start'       => 'required',
            'end'         => 'required',
            'category'    => 'required',
            'is_all_day'  => 'required',
            'status'      => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $schedule = Schedule::find($id);
            if ($schedule->calendar_id != $request->input('calendar_id')) $schedule->calendar_id = $request->input('calendar_id');
            if ($schedule->title != $request->input('title')) $schedule->title = $request->input('title');
            if ($schedule->body != $request->input('body')) $schedule->body = $request->input('body');
            if ($schedule->start_at != $request->input('start')) $schedule->start_at = $request->input('start');
            if ($schedule->end_at != $request->input('end')) $schedule->end_at = $request->input('end');
            if ($schedule->catg != $request->input('category')) $schedule->catg = $request->input('category');
            if ($schedule->is_all_day != $request->input('is_all_day')) $schedule->is_all_day = $request->input('is_all_day');
            if ($schedule->status != $request->input('status')) $schedule->status = $request->input('status');
            $schedule->save();
            DB::commit();
            return $this->success($schedule, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'calendar_id' => 'required',
            'title'       => 'required',
            'body'        => 'required',
            'start'       => 'required',
            'end'         => 'required',
            'category'    => 'required',
            'is_all_day'  => 'required',
            'status'      => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1002);
        }
        try {
            $schedule = Schedule::create([
                'calendar_id' => $request->input('calendar_id'),
                'title'       => $request->input('title'),
                'body'        => $request->input('body'),
                'start_at'    => $request->input('start'),
                'end_at'      => $request->input('end'),
                'catg'        => $request->input('category'),
                'is_all_day'  => $request->input('is_all_day'),
                'status'      => $request->input('status'),
            ]);
            return $this->success($schedule, 'success');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function updTimeRange($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end'   => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $schedule = Schedule::find($id);
            if ($schedule->start_at != $request->input('start')) $schedule->start_at = $request->input('start');
            if ($schedule->end_at != $request->input('end')) $schedule->end_at = $request->input('end');
            $schedule->updated_at = date('Y-m-d H:i:s');
            $schedule->save();
            DB::commit();
            return $this->success('update time range success', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $schedule = Schedule::find($id);

            $schedule->delete();
            DB::commit();
            return $this->success('delete schedule success', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
