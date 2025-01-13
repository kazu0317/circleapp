<x-app-layout>
    <body>
        <h1 class="title">
            <p>{{ $user->name }}</p>
        </h1>
        <div class="content">
            <div class="content__post">
                <h2>Email</h2>
                <p>{{ $user->email }}</p>
                <h2>大学</h2>
                <p>{{ $user->univ }}</p> 
                        <h2>学年</h2>
                        <p>{{ $user->grade }}</p>
                            <h2>Hard_experience</h2>
                            <p>{{ $user->hard_experience }}</p>
                                <h2>Soft_experience</h2>
                                <p>{{ $user->soft_experience }}</p>
                                    <h2>Hobby</h2>
                                    <p>{{ $user->hobby }}</p>
             </div> 
        </div>   
        <div class="footer">
            <a href="javascript:history.back()">戻る</a>
        </div>
    </body>
</x-app-layout> 