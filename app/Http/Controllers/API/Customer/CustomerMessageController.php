<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Requests\CustomerApp\Message\SendMailRequest;
use App\Models\MerchantNotification;
use App\Models\Message;
use App\Models\MessageThread;
use Illuminate\Http\Request;

class CustomerMessageController extends CustomerBaseController
{
    public function index()
    {
        $messages = MessageThread::select('messages.to', 'messages.from',
            'messages.text', 'messages.mark_as', 'message_threads.customer_id', 'message_threads.merchant_id',
            'messages.created_at', 'messages.thread_id')
            ->leftJoin('messages', 'messages.thread_id', '=', 'message_threads.id')
            ->where('message_threads.customer_id', '=', $this->getCustomerData()->id)
            ->where('message_threads.detail_id', '=', $this->getCustomerData()->detail_id)
            ->orderBy('messages.created_at', 'desc')
            ->get()
            ->unique('thread_id');

        $unreadMessages = $this->unreadMessages($this->getCustomerData());

        return response()->json([
            'messages' => $messages,
            'unreadMessages' => $unreadMessages,
        ]);
    }

    public function store(SendMailRequest $request)
    {
        $messageThread = new MessageThread();
        $messageThread->customer_id = $this->getCustomerData()->id;
        $messageThread->merchant_id = $request->admin_id;
        $messageThread->detail_id = $this->getCustomerData()->detail_id;
        $messageThread->save();

        $message = new Message();
        $message->thread_id = $messageThread->id;
        $message->to = 'merchant';
        $message->from = 'customer';
        $message->text = $request->text;
        $message->save();

        //region Notification
        $notification = new MerchantNotification();
        $notification->detail_id = $this->getCustomerData()->detail_id;
        $notification->notification_type = 'Messages';
        $notification->title = 'New message in the inbox';
        $notification->save();
        //endregion
        return response()->json([
            'messageThread' => $messageThread,
            'message' => $message,
            'notification' => $notification,
        ]);

    }

    public function show($id)
    {
        $messages = Message::select('id')
            ->where('thread_id', '=', $id)
            ->get();

        //region mark message as read
        foreach ($messages as $message) {
            $messageUpdate = Message::find($message->id);
            $messageUpdate->mark_as = 'read';
            $messageUpdate->save();
        }
        //endregion

        $this->data['messages'] = MessageThread::select('messages.to', 'messages.from',
            'messages.text', 'messages.mark_as', 'message_threads.customer_id', 'message_threads.merchant_id',
            'messages.created_at', 'messages.thread_id')
            ->leftJoin('messages', 'messages.thread_id', '=', 'message_threads.id')
            ->where('customer_id', '=', $this->getCustomerData()->id)
            ->where('detail_id', '=', $this->getCustomerData()->detail_id)
            ->where('messages.thread_id', '=', $id)
            ->orderBy('messages.created_at', 'asc')
            ->get();
        $this->data['unreadMessages'] = $this->unreadMessages($this->getCustomerData());

        return response()->json([
            'messages' => $this->data['messages'],
            'unreadMessages' => $this->data['unreadMessages'],
        ]);

    }

    public function update(Request $request, $id)
    {
        $message = new Message();
        $message->thread_id = $id;
        $message->to = 'merchant';
        $message->from = 'customer';
        $message->text = $request->message;
        $message->mark_as = 'unread';
        $message->save();

        //region Notification
        $notification = new MerchantNotification();
        $notification->detail_id = $this->getCustomerData()->detail_id;
        $notification->notification_type = 'Messages';
        $notification->title = 'New message in the inbox';
        $notification->save();
        //endregion
        return $this->sendResponse($message->text,'');
    }

    private function unreadMessages($customerValue)
    {
        $message = MessageThread::leftJoin('messages', 'messages.thread_id', '=', 'message_threads.id')
            ->where('message_threads.customer_id', '=', $customerValue->id)
            ->where('message_threads.detail_id', '=', $customerValue->detail_id)
            ->where('messages.to', '=', 'customer')
            ->where('messages.mark_as', '=', 'unread')
            ->count();
        return $this->sendResponse($message,'');
    }
}
