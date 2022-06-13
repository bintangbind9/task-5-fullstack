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
                    <a href="{{route('post.create')}}" class="btn btn-icon btn-primary" data-toggle="tooltip" title="Add"><i class="fas fa-plus"></i></a>
                </div>
            </div>

            <div class="card-body">
                @if($posts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="table-posts">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Author</th>
                                    <th scope="col" class="nosort nosearch" style="width:100px;">
                                        Publish
                                        <select id="search-stat" name="search-stat">
                                            <option value="">All</option>
                                            <option value="{{Constant::TRUE_CONDITION}}">Yes</option>
                                            <option value="{{Constant::FALSE_CONDITION}}">No</option>
                                        </select>
                                    </th>
                                    <th scope="col" class="nosort d-none">Publish</th>
                                    <th scope="col" class="nosort">Created At</th>
                                    <th scope="col" class="nosort">Updated At</th>
                                    <th scope="col" class="nosort nosearch">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $no => $p)
                                <tr id="tr_{{$p->id}}" data-id="{{$p->id}}">
                                    <td>{{$p->category->name}}</td>
                                    <td>{{$p->title}}</td>
                                    <td>{{$p->user->name}}</td>
                                    <td>
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                                data-url="{{route('post.update_stat',$p->id)}}"
                                                data-url-show="{{route('post.show',$p->id)}}"
                                                data-id="{{$p->id}}" @if ($p->status == Constant::TRUE_CONDITION) checked @endif>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>
                                    <td id="td-stat-{{$p->id}}" class="td-stat d-none">{{$p->status}}</td>
                                    <td>{{Carbon\Carbon::parse($p->created_at)->format(Constant::FORMAT_DATE_TIME)}}</td>
                                    <td>{{Carbon\Carbon::parse($p->updated_at)->format(Constant::FORMAT_DATE_TIME)}}</td>
                                    <td>
                                        <a href="{{route('post.edit',$p->id)}}" class="btn btn-icon btn-primary" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-icon btn-outline-danger" data-toggle="tooltip" title="Delete"
                                            onclick="$('#form-delete-post-{{$p->id}}').submit();"><i class="fas fa-trash"></i></button>
                                        <form id="form-delete-post-{{$p->id}}" action="{{route('post.destroy',$p->id)}}" method="POST">
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
                        <h2>Belum ada Artikel</h2>
                        <p class="lead">Semua artikel akan tampil di sini.</p>
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
/* Custom filtering function which will search data in column four based on status value */
$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var searchStat = $('#search-stat').val().trim();
    var dataStat = data[4].toString().trim(); // use data for the Status column
 
    if ((searchStat == dataStat || searchStat == "") && settings.nTable.id == 'table-posts') {
        return true;
    }
    return false;
});

$(document).ready(function() {
    var tablePosts = $('#table-posts').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": 'nosort' },
            { "searchable": false, "targets": 'nosearch' }
        ]
    });

    // Event listener to stat filtering inputs to redraw on input
    $('#search-stat').change(function () {
        tablePosts.draw();
    });

    function set_obj_val_by_get_post_url_show(url, obj_cb, obj_td_stat) {
        $.ajax({
            url: url,
            type: 'get',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function() {
                //
            },
            success: function (data) {
                if (data['success']) {
                    var stat_val = (data['success'] == '{{Constant::TRUE_CONDITION}}') ? true : false;
                    obj_cb.prop('checked', stat_val);
                    tablePosts.cell(obj_td_stat).data(data['success']);
                } else if (data['error']) {
                    console.log(data['message']);
                } else {
                    console.log('Whoops Something went wrong!!');
                }
            },
            error: function (data) {
                console.log(data.responseText);
            }
        });
    }

    $('#table-posts').on('change', 'input[type="checkbox"].custom-switch-input', function () {
        var obj_cb = $(this);
        var td_stat = obj_cb.closest('td').siblings('td.td-stat');
        var data_before = tablePosts.cell(td_stat).data();
        var stat = this.checked ? '{{Constant::TRUE_CONDITION}}' : '{{Constant::FALSE_CONDITION}}';
        var data_url = obj_cb.data('url');
        var data_url_show = obj_cb.data('url-show');
        $.ajax({
            url: data_url,
            type: 'put',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {status: stat},
            beforeSend: function() {
                //
            },
            success: function (data) {
                if (data['success']) {
                    var stat_val = (data['result'] == '{{Constant::TRUE_CONDITION}}') ? true : false;
                    obj_cb.prop('checked', stat_val);
                    tablePosts.cell(td_stat).data(data['result']);
                } else if (data['error']) {
                    var stat_val = (data['result'] == '{{Constant::TRUE_CONDITION}}') ? true : false;
                    obj_cb.prop('checked', stat_val);
                    tablePosts.cell(td_stat).data(data['result']);
                } else if (data['error_validation']) {
                    swal(data['error_validation'], {icon: 'error'});
                } else {
                    swal('Whoops Something went wrong!!', {icon: 'error'});
                }
            },
            error: function (data) {
                // swal(data.responseText);
                swal('Whoops Something went wrong!!', {icon: 'error'});
            },
            complete: function () {
                set_obj_val_by_get_post_url_show(data_url_show, obj_cb, td_stat);
            }
        });
    })
});
</script>
@endpush

@push('after-script')
<script></script>
@endpush