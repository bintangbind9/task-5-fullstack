@extends('layouts.master')
@section('title',$section_header)
@push('pages-style')
<style>
</style>
@endpush

@section('content')
    @include('layouts.alert')

    <div class="section-body">
    </div>
@endsection

@section('modal')
@endsection

@push('before-scripts')
<script></script>
@endpush

@push('page-script')
<script></script>
@endpush

@push('after-script')
<script></script>
@endpush