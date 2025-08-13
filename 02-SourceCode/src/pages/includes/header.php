<header class="bg-gray-800 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <h1 id="title" class="text-2xl font-bold text-white">{{ $title ?? 'Default Title' }}</h1>
        <select 
            name="language" 
            id="language" 
            class="bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer hover:bg-gray-600 transition-colors"
        >
            <option value="{{ $lang }}">{{ $langsTranslations[$lang] ?? $lang }}</option>
            @foreach($availableLanguages as $availableLang)
                @if($availableLang !== $lang)
                    <option value="{{ $availableLang }}">{{ $langsTranslations[$availableLang] ?? $availableLang }}</option>
                @endif
            @endforeach
        </select>
    </div>
</header>