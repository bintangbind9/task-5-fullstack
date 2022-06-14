@extends('layouts.master')
@section('title',$section_header)
@push('pages-style')
<style>
</style>
@endpush

@section('content')
    @include('layouts.alert')

    @if ($errors->any())
      @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {{$error}}
            </div>
        </div>
      @endforeach
    @endif

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{$section_header}}</h4>
                <div class="card-header-action">
                    <a href="{{route('category.create')}}" class="btn btn-icon btn-primary" data-toggle="tooltip" title="Add"><i class="fas fa-plus"></i></a>
                </div>
            </div>

            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table-categories">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Creator</th>
                                    <th scope="col" class="nosort">Created At</th>
                                    <th scope="col" class="nosort">Updated At</th>
                                    <th scope="col" class="nosort nosearch">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $no => $c)
                                <tr id="tr_{{$c->id}}" data-id="{{$c->id}}">
                                    <td>{{$c->name}}</td>
                                    <td>{{$c->user->name}}</td>
                                    <td>{{Carbon\Carbon::parse($c->created_at)->format(Constant::FORMAT_DATE_TIME)}}</td>
                                    <td>{{Carbon\Carbon::parse($c->updated_at)->format(Constant::FORMAT_DATE_TIME)}}</td>
                                    <td>
                                        <a href="{{route('category.edit',$c->id)}}" class="btn btn-icon btn-primary" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-icon btn-outline-danger" data-toggle="tooltip" title="Delete"
                                            onclick="$('#form-delete-category-{{$c->id}}').submit();"><i class="fas fa-trash"></i></button>
                                        <form id="form-delete-category-{{$c->id}}" action="{{route('category.destroy',$c->id)}}" method="POST">
                                            @csrf
                                            @method(Constant::DELETE_METHOD)
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fas fa-question"></i></div>
                        <h2>Belum ada Category</h2>
                        <p class="lead">Semua Category Article akan tampil di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modal')
@endsection

@push('before-scripts')
<script></script>
@endpush

@push('page-script')
<script>
$(document).ready(function() {
    $('#table-categories').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": 'nosort' },
            { "searchable": false, "targets": 'nosearch' }
        ]
    });
});
</script>
@endpush

@push('after-script')
<script></script>
@endpush