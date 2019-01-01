<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends ApiController
{
    /**
     * 获取用户未读消息数
     * @return mixed
     */
    public function count()
    {
        $data = Message::where('to_user', auth('api')->id())->where('status', 0)->count();
        return $this->success($data, 'success');
    }

    /**
     * 获取用户收到的消息内容，包括已读，未读和放入回收站的
     * @return mixed
     */
    public function index()
    {
        $data = [
            'unread' => [],
            'readed' => [],
            'trash'  => [],
        ];
        $messages = Message::where('to_user', auth('api')->id())->get();
        foreach ($messages as $item) {
            if ($item->status == 0) $data['unread'][] = ['title' => $item->title, 'create_time' => $item->created_at->format('Y-m-d H:i:s'), 'msg_id' => $item->id];
            if ($item->status == 1) $data['readed'][] = ['title' => $item->title, 'create_time' => $item->created_at->format('Y-m-d H:i:s'), 'msg_id' => $item->id];
            if ($item->status == -1) $data['trash'][] = ['title' => $item->title, 'create_time' => $item->created_at->format('Y-m-d H:i:s'), 'msg_id' => $item->id];

        }

        return $this->success($data, 'success');
    }

    /**
     * 获取某条信息的内容
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $data = Message::find($id);
        return $this->success($data->content, 'success');
    }

    /**
     * 更新某条信息的状态，状态编码为-1：回收，0:未读，1：已读
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        if (Message::find($id)) {
            if (Message::where('id', $id)->update(['status' => \request()->read])) {
                return $this->success(true, 'success');
            } else {
                return $this->setStatusCode('1010')->failed('信息状态更新失败');
            }
        } else {
            return $this->setStatusCode('1010')->failed('信息不存在');
        }
    }
}
