<div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm hover:shadow-lg transition duration-300">
    <div class="flex items-start gap-4">
        <div class="text-primary text-5xl leading-none flex-shrink-0">
            <span class="font-serif">"</span>
        </div>
        <div class="flex flex-col">
            <p class="text-gray-600 text-[15px] leading-7">{{ $item->review }}</p>
            <div class="flex items-center gap-1 mt-3 mb-6 text-yellow-400">
                @for ($i = 0; $i < 5; $i++)<i class="fa-solid fa-star"></i>@endfor
            </div>
        </div>
    </div>
    <div class="flex items-center gap-4">
        @if($item->image)
            <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
                 alt="{{ $item->name }}"
                 class="w-14 h-14 rounded-full object-cover border-2 border-gray-100">
        @else
            <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center border-2 border-gray-100">
                <i class="fa-solid fa-user text-primary/40 text-xl"></i>
            </div>
        @endif
        <div>
            <h4 class="text-primary font-extrabold text-lg leading-none">{{ $item->name }}</h4>
            <p class="text-gray-500 text-sm mt-2">{{ $item->role }}</p>
        </div>
    </div>
</div>
