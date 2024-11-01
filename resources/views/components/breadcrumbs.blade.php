<div class="col-lg-12">
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ul class="bg-white breadcrumb px-20 mt-20 rounded rounded-5">
        @foreach($items as $item)
            <li class="breadcrumb-item text-white {{ $loop->last ? 'active' : '' }}">
                @if(!$loop->last)
                    <a class="text-black" href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                @else
                    <span class="text-primary-color">{{ $item['name'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
</div>
