<?php

namespace App\Http\Controllers;

use App\Notifications\ApiNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Save notification in the database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {

        $user = Auth::user();

        $details = [
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'order_id' => $request->get('order')
        ];

        $user->notify(new ApiNotification($details));

        return $this->response('Successfully created notification!', 201);

    }

    /**
     * Returns unread notifications and mark these as read
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $notifications = Auth::user()->unreadNotifications;

       // Auth::user()->unreadNotifications->markAsRead();

        return response()->json(
            $notifications
        );
    }

    /**
     * Returns all notifications
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return response()->json(
            Auth::user()->notifications
        );
    }

    /**
     * Get notification by notifiable id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByNotifiableId($id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();

        return response()->json(
            $notification
        );
    }


    /**
     * Update notification status
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $notification = Auth::user()->notifications->where('id', $id)->first();

        if(is_null($notification)){
            return $this->response('Notification not found', 404);
        }

        if ($request->get('status') == 'read') {
            $notification->markAsRead();
            return $this->response('Successfully updated notification!', 201);
        } elseif ($request->get('status') == 'unread') {
            DB::table('notifications')->where('id', $id)->update(['read_at'=> null]);
            return $this->response('Successfully updated notification!', 201);
        }

        return $this->response('The status parameter must be read or unread', 400);

    }


    /**
     * Delete a notification
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $notification =  Auth::user()->notifications->where('id',$id)->first();

        if ($notification) {
            $notification->delete();
            return $this->response('Successfully deleted notification!', 201);
        }
        else {
            return $this->response('Notification not found', 404);
        }
    }
}
