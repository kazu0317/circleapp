<?php
namespace App\Http\Controllers;
use App\Models\Event; // Model追加忘れずに
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class EventController extends Controller
{
     // カレンダー表示
     public function show(){
        return view("circles/calendar");
     }
        //（ここから）追記
    // DBから予定取得
    public function get(Request $request, Event $event){
        // バリデーション
        $request->validate([
            'start_date' => 'required|integer',
            'end_date' => 'required|integer'
        ]);
    
        // 現在カレンダーが表示している日付の期間
        $start_date = date('Y-m-d', $request->input('start_date') / 1000); // JSのタイムスタンプを秒に変換して日付に
        $end_date = date('Y-m-d', $request->input('end_date') / 1000);
    
        // 予定取得処理
        $events = $event->query()
            ->select(
                'id',
                'event_title as title',
                'event_body as description',
                'start_date as start',
                'end_date as end',
                'event_color as backgroundColor',
                'event_border_color as borderColor',
                'upper as upper',
                'per_person as per_person',
                'total_amount as total_amount'
            )
            ->where('end_date', '>', $start_date)
            ->where('start_date', '<', $end_date)
            ->get();
    
        // applications テーブルの件数を取得して追加
        foreach ($events as $event) {
            $event->application_count = \DB::table('applications')
                ->where('event_id', $event->id)
                ->where('status', 1)
                ->count();
        }
    
        return $events;
    }
//（ここまで）
//（ここから）追記
    // 新規予定追加
     public function create(Request $request, Event $event){
        // バリデーション（eventsテーブルの中でNULLを許容していないものをrequired）
        $request->validate([
            'event_title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'event_color' => 'required',
        ]);

        // 登録処理
        $event->event_title = $request->input('event_title');
        $event->event_body = $request->input('event_body');
        $event->start_date = $request->input('start_date');
        $event->end_date = date("Y-m-d", strtotime("{$request->input('end_date')} +1 day")); // FullCalendarが登録する終了日は仕様で1日ずれるので、その修正を行っている
        $event->event_color = $request->input('event_color');
        $event->event_border_color = $request->input('event_color');
        $event->upper= $request->input('upper');
        $event->save();

        // カレンダー表示画面にリダイレクトする
        return redirect(route("show"));
    }
//（ここまで）

//（ここから）追記
    // 予定の更新
    public function update(Request $request, Event $event)
    {
        $input = new Event();
        $input->event_title = $request->input('event_title');
        $input->event_body = $request->input('event_body');
        $input->start_date = $request->input('start_date');
        $input->end_date = date("Y-m-d", strtotime("{$request->input('end_date')} +1 day"));
        $input->event_color = $request->input('event_color');
        $input->event_border_color = $request->input('event_color');
        $input->upper = $request->input('upper');
        // total_amountの値が入力されている場合
        if ($request->filled('total_amount')) {
            $input->total_amount = $request->input('total_amount');
    
            // 応募者数を取得
            $applicationsCount = DB::table('applications')
                ->where('event_id', $request->input('id')) // 現在のイベントのID
                ->count();
    
            // 応募者数が0でない場合にper_personを計算
            if ($applicationsCount > 0) {
                $input->per_person = $request->input('total_amount') / $applicationsCount;
            } else {
                $input->per_person = 0; // 応募者がいない場合は0にする
            }
            
        }
    
        // 更新する予定をDBから検索し、内容を変更して保存
        $event->find($request->input('id'))->fill($input->attributesToArray())->save();
    
        // カレンダー表示画面にリダイレクトす9る
        return redirect(route('show'));
    }
        //（ここまで）

        //（ここから）追記
            // 予定の削除
    public function delete(Request $request, Event $event){
        // 削除する予定をDBから探し（find）、DBから物理削除する（delete）
        $event->find($request->input('id'))->delete();

        // カレンダー表示画面にリダイレクトする
        return redirect(route("show"));
    }
    //（ここまで）
    //練習会参加者登録
    public function register(Request $request, Application $application) {
        $userId = Auth::id(); // 現在のログイン中のユーザーID
        $eventId = $request->input('event_id'); // リクエストからイベントIDを取得
    
        // レコードが存在するかを確認
        $existingApplication = Application::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();
        if ($existingApplication) {
            // 既存のレコードがある場合、status を更新
            $previousStatus = $existingApplication->status; // 変更前のstatusを保存
    
            // status を変更
            $existingApplication->status = $request->input('action') === '参加' ? 1 : 0;
            $existingApplication->save();
    
            // status が変更される前後で join_number を調整（usersテーブル）
            $user = User::find($userId); // ユーザーを取得
    
            if ($previousStatus === 0 && $existingApplication->status === 1) {
                // 不参加 -> 参加の場合、ユーザーの join_number を +1
                $user->join_number += 1;
            } elseif ($previousStatus === 1 && $existingApplication->status === 0) {
                // 参加 -> 不参加の場合、ユーザーの join_number を -1
                $user->join_number -= 1;
            }
    
            // join_number が負にならないようにする（オプション）
            $user->join_number = max(0, $user->join_number);
    
            // ユーザーの join_number を保存
            $user->save();
        } else {
            // 新しいレコードを作成
            $newApplication = Application::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'status' => $request->input('action') === '参加' ? 1 : 0,
                'actually_participated' => false, // 追加: デフォルトで false にする
            ]);
    
            // 新しいレコードが作成された場合、ユーザーの join_number を +1 する（参加した場合）
            if ($newApplication->status === 1) {
                $user = User::find($userId); // ユーザーを取得
                $user->join_number += 1;
                $user->save();
            }
        }
    
        return redirect('/event/' . $eventId);
    }
    

    public function showParticipants(Event $event, Application $application) {
        // そのイベントに参加したユーザーの一覧を取得（statusが1のもの＝参加）
        $participants = Application::where('event_id', $event->id)
            ->where('status', 1)
            ->with('user')  // 参加者のユーザー情報も一緒に取得
            ->get();
    
        // 各参加者について過去の参加回数を追加
        $participants->each(function ($application) {
            // 各ユーザーが過去に参加した回数を取得
            $application->user->participation_count = Application::where('user_id', $application->user->id)
                ->where('status', 1) // 参加した状態のレコードのみ
                ->where('event_id', '!=', $application->event_id) // 現在のイベント以外
                ->count();
        });

    
        // ビューにデータを渡す
        return view('circles.applicationlist', compact('event', 'participants'));
    }
    


    public function showParsonDetail(Event $event) {

        // そのイベントに参加したユーザーの一覧を取得（statusが1のもの＝参加）
        $participants = Application::where('event_id', $event->id)
            ->where('status', 1)
            ->with('user')  // 参加者のユーザー情報も一緒に取得
            ->get();

        // ビューにデータを渡す
        return view('circles.applicationlist', compact('event', 'participants'));
    }
}