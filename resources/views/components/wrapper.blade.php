@props(['title', 'class' => ''])
<div class="p-30">
    <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30 {{ $class }}"><h1
            class="text-primary-color">{{ $title }}</h1> {{ $slot }} </div>
</div>
