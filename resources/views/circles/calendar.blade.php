<x-app-layout>
        <!-- 以下のdivタグ内にカレンダーを表示 -->
        <div id='calendar'></div>

         <!--（ここから）追記1 -->
         <!-- カレンダー新規追加モーダル -->
         <div id="modal-add" class="modal hidden justify-center items-center fixed z-10 inset-0 h-full w-full bg-blue-500">
            <div class="modal-contents">
                <form method="POST" action="{{ route('create') }}">
                     @csrf
                    <input id="new-id" type="hidden" name="id" value="" />
                    <label for="event_title">タイトル</label>
                    <input id="new-event_title" class="input-title" type="text" name="event_title" value="" />
                    <label for="start_date">開始日時</label>
                    <input id="new-start_date" class="input-date" type="date" name="start_date" value="" />
                    <label for="end_date">終了日時</label>
                    <input id="new-end_date" class="input-date" type="date" name="end_date" value="" />
                    <label for="event_body" style="display: block">内容</label>
                    <textarea id="new-event_body" name="event_body" rows="3" value=""></textarea>
                    <label for="event_color">背景色</label>
                    <select id="new-event_color" name="event_color">
                    <option value="blue" selected>青</option>
                    <option value="green">緑</option>
                    </select>
                    <label for="upper">参加上限</label>
                    <input type="number" id="new-upper" name="upper"> 
                   
                    <button type="button" onclick="closeAddModal()">キャンセル</button>
                    <button type="submit">決定</button>
                </form>

            </div>
            
            
        </div>
<!-- （ここまで） -->

<!--（ここから）追記1 -->
        <!-- カレンダー編集モーダル -->
        <div id="modal-update" class="modal hidden justify-center items-center fixed z-10 inset-0 h-full w-full bg-black bg-opacity-5">
            <div class="modal-contents">
                <form method="POST" action="{{ route('update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" value="" />
                    <label for="event_title">タイトル</label>
                    <input class="input-title" type="text" id="event_title" name="event_title" value="" />
                    <label for="start_date">開始日時</label>
                    <input class="input-date" type="date" id="start_date" name="start_date" value="" />
                    <label for="end_date">終了日時</label>
                    <input class="input-date" type="date" id="end_date" name="end_date" value="" />
                    <label for="event_body" style="display: block">内容</label>
                    <textarea id="event_body" name="event_body" rows="3" value=""></textarea>
                    <label for="event_color">背景色</label>
                    <select id="event_color" name="event_color">
                        <option value="blue">青</option>
                        <option value="green">緑</option>
                    </select>
                    <label for="upper">参加上限</label>
                    <input class="upper" type=number id="upper" name="upper" value="" />
                    <label for="total_amount">合計金額</label>
                    <input type="number" id="total_amount" name="total_amount" value="" />
                    <p id="per_person_display">参加費用は設定されていません。</p>
                    <button type="button" onclick="closeUpdateModal()">キャンセル</button>
                    <button type="submit">決定</button>
                </form>
                <form method="POST" action="{{ route('events.register') }}">
                    @csrf
                    <input type="hidden" id="registerid" name="event_id" value="" />
                    <button type="" id="join-button" name="action" value="参加">参加</button>
                    <button type="submit" id="leave-button" name="action" value="不参加">不参加</button>
                 </form>
           <!--（ここから）追記 -->
           <form id="delete-form" method="post" action="{{ route('delete') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete-id" name="id" value="" />
                    <button class="delete" type="button" onclick="deleteEvent()">削除</button>
                </form>
            </div>
        </div>
        
<!-- （ここから）追記2 -->
            <style scoped>
                        //（ここから）追記
            /* 予定の上ではカーソルがポインターになる */
            .fc-event-title-container{
                cursor: pointer;
            }
            //（ここまで）
            
            /* モーダルのオーバーレイ */
            .modal{
                display: none; /* モーダル開くとflexに変更（ここの切り替えでモーダルの表示非表示をコントロール） */
                justify-content: center;
                align-items: center;
                position: absolute;
                z-index: 10; /* カレンダーの曜日表示がz-index=2のため、それ以上にする必要あり */
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                height: 100%;
                width: 100%;
                /* background-color: rgba(0,0,0,0.5); */
                background-color: blue;
            }
            /* モーダル */
            .modal-contents{
                background-color: white;
                padding: 20px;
            }

            /* 以下モーダル内要素のデザイン調整 */
            input{
                padding: 2px;
                border: 1px solid black;
                border-radius: 5px;
            }
            .input-title{
                display: block;
                width: 80%;
                margin: 0 0 20px;
            }
            .input-date{
                width: 27%;
                margin: 0 5px 20px 0;
            }
            textarea{
                display: block;
                width: 80%;
                margin: 0 0 20px;
                padding: 2px;
                border: 1px solid black;
                border-radius: 5px;
                resize: none;
            }
            select{
                display: block;
                width: 20%;
                margin: 0 0 20px;
                padding: 2px;
                border: 1px solid black;
                border-radius: 5px;
            }
            </style>
            <!--（ここまで） -->

</x-app-layout>   