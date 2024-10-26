@props(['id'])
<div class="table-responsive zTable-responsive">
    <table class="table zTable" id="{{$id}}">
        <thead>
        <tr>
           {{$slot}}
        </tr>
        </thead>
    </table>
</div>
