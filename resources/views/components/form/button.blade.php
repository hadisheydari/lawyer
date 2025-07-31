@if($name === null || $name === 'submit')
    <div class="w-full text-center">
        <button type="{{$type}}"
                onclick="{{$action ?? ''}}"
                class="mt-10 text-blue-900 bg-gradient-to-r from-blue-300 via-blue-400 to-blue-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-24 py-3 text-center me-2 mb-2 "
        >
            {{$text}}
        </button>
    </div>

@endif
@if($name === 'create')
    <div class="w-full text-center">
        <a
                href="{{$action ?? ''}}"
                class="mt-10 text-blue-900 bg-gradient-to-r from-blue-300 via-blue-400 to-blue-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-base px-24 py-3 text-center me-2 mb-2 "
        >
            {{$text}}
        </a>
    </div>

@endif
@if($name === 'RoleInfo')
    <div class="w-full flex justify-center">
        <button type="button"
                onclick="{{$action ?? ''}}"
                class="text-blue-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg w-96 py-7 text-base flex justify-center items-center gap-3 dark:focus:ring-blue-600 dark:bg-blue-800 dark:border-blue-700 dark:text-white dark:hover:bg-blue-700 m-4">
            {{ $slot }}
            <span>{{$text}}</span>
        </button>
    </div>
@endif
