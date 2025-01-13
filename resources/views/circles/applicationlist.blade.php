<x-app-layout>
     <!-- イベントのタイトルなどを表示 -->
     <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold">{{ $event->name }} の参加者一覧</h1>
        
        <!-- 参加者がいない場合のメッセージ -->
        @if ($participants->isEmpty())
            <p class="text-gray-500">このイベントにはまだ参加者がいません。</p>
        @else
            <!-- 参加者リストのテーブル -->
            <table class="min-w-full bg-white border border-gray-300 mt-4">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">名前</th>
                        <th class="py-2 px-4 border-b">参加日時</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($participants as $participant)
                    <tr class="
                        @if ($participant->user->participation_count == 0)
                            bg-yellow-200
                        @elseif ($participant->user->participation_count == 1)
                            bg-green-200
                        @endif
                    ">
                        <td class="py-2 px-4 border-b">
                            <a href="profile/{{ $participant->user_id }}">{{ $participant->user->name }}</a>     
                        </td>
                        <td class="py-2 px-4 border-b">{{ $participant->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        @endif
    </div>

</x-app-layout> 