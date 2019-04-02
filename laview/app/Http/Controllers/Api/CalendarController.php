<?php

namespace App\Http\Controllers\Api;

use App\Models\Calendar;
use App\Http\Resources\Calendar as CalendarResource;
use Illuminate\Http\Request;

class CalendarController extends ApiController
{
    /**
     * @info 获取日历类别
     */
    public function index()
    {
        $calendar = new Calendar();
        return CalendarResource::collection($calendar->get());
    }

    /**
     * @param id
     * @return json string
     * @info 获取某个日历类别的详细信息
     */
    public function show()
    {

    }
}
