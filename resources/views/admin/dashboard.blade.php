@extends('layouts.app')

@section('content')
    <style>
        svg{
            filter: saturate(200%);


        }
        .zNews-item-one {
            transition: transform 0.3s ease;
        }

        .zNews-item-one:hover {
            transform: translateY(-10px);
        }
    </style>
    <div class="p-30">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-black pb-16"></h4>

            <div class="row rg-30">

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/common/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/charts.js') }}"></script>
    <script src="{{ asset('public/admin/js/admin-dashboard.js') }}?ver={{ env('VERSION' ,0) }}"></script>
@endpush
