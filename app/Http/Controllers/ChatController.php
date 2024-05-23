<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\HistoryChat;
use App\Models\HistoryDate;
use App\Models\HistoryTopic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPUnit\Framework\isEmpty;

class ChatController extends Controller
{
    public function index(Request $request){
        $history_topic = HistoryTopic::where('id', $request->topic_id)->where('user_id', auth()->user()->id)->first();
        $history_topic ? $history_chat = HistoryChat::where('history_topic_id', $history_topic->id)->get() : $history_chat = null;
        return Inertia::render('Dashboard', [
            'message' => $request->message,
            'answer' => $request->answer,
            'history_date' => HistoryDate::with(['topics' => fn ($query) => $query->orderByDesc('id')])->orderByDesc('date')->get(),
            'topic_id' => $request->topic_id,
            'history_chat' => $history_chat,
        ]);
    }

    public function chat(Request $request){
        $user_id = auth()->user()->id;
        $chat_messages = $request->chatMessages;

        $history_date = HistoryDate::where('user_id', $user_id)->whereDate('date', Carbon::today())->first();

        if(!$history_date){
            $history_date = HistoryDate::create([
                'date' => Carbon::today(),
                'user_id' => $user_id,
            ]);
        } else if($chat_messages) {
            $history_topic = HistoryTopic::where('id', $chat_messages[0]['topicID'])->first();
        }

        if (empty($chat_messages)) {
            $history_topic = HistoryTopic::create([
                'topics' => $request->message,
                'history_date_id' => $history_date->id,
                'user_id' => $user_id,
            ]);
        }

        $answer = Answer::whereRaw("MATCH(keyword) AGAINST(? IN NATURAL LANGUAGE MODE)", [$request->message])->first();

        HistoryChat::create([
            'question' => $request->message,
            'answer' => $answer ? $answer->name : "တောင်းပန်ပါတယ်၊ မင်းရဲ့မေးခွန်းကို ငါနားမလည်ဘူး။",
            'history_topic_id' => $history_topic->id,
        ]);

        return to_route('dashboard', [
            'message' => $request->message,
            'answer' => $answer ? $answer->name : "တောင်းပန်ပါတယ်၊ မင်းရဲ့မေးခွန်းကို ငါနားမလည်ဘူး။",
            'topic_id' => $history_topic->id,
        ]);
    }

    public function delete_topic(Request $request){
        $history_topic = HistoryTopic::where('id', $request->topic_id)->first();
        $history_topic_count = HistoryTopic::where('history_date_id', $history_topic->history_date_id)->count();
        if ($history_topic_count == 1) {
            HistoryDate::where('id', $history_topic->history_date_id)->delete();
        } else {
            HistoryTopic::where('id', $request->topic_id)->delete();
        }
        return to_route('dashboard');
    }

    public function update_topic(Request $request){
        HistoryTopic::where('id', $request->topic_id)->update([
            'topics' => $request->topic_name,
        ]);

        return to_route('dashboard');
    }
}
