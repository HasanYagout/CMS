@props(['title'])
<div class="p-30" >
        <h1 class="text-primary-color">{{$title}}</h1>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            {{$slot}}
        </div>
</div>
