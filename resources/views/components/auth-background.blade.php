<div class="min-h-screen bg-gradient-to-r from-sky-900 via-blue-950 to-slate-900 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-[70vw] h-[70vh] m-[15vh] p-6 flex flex-col items-center justify-start overflow-hidden">

        <div class="text-blue-700 font-black text-5xl m-12 ">
            {{$text}}
        </div>

        <div class="flex flex-row w-full flex-1 items-center justify-center overflow-hidden">
            <div class="basis-1/2 mr-14">
                {{ $slot }}
            </div>
            <div class="basis-1/2 m-24">
                <img src="img/Auth/authImg.webp" alt="">
            </div>
        </div>

    </div>
</div>
