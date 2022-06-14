@extends('layouts.master')
@section('title',$section_header)
@push('pages-style')

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('stisla/node_modules/css/summernote-bs4.css')}}">
  <link rel="stylesheet" href="{{asset('stisla/node_modules/css/codemirror.css')}}">
  <link rel="stylesheet" href="{{asset('stisla/node_modules/css/duotone-dark.css')}}">
  <link rel="stylesheet" href="{{asset('stisla/node_modules/css/selectric.css')}}">

<style>
</style>
@endpush

@section('content')
    @include('layouts.alert')

    <div class="section-body">
            <h2 class="section-title">{{$section_header}}</h2>
            <p class="section-lead">Here, you can write the article with format what you want.</p>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Write Article</h4>
                    <div class="card-header-action">
                      <a href="{{route('post.index')}}" class="btn btn-icon btn-warning" data-toggle="tooltip" title="Cancel"><i class="fas fa-times"></i></a>
                      <button class="btn btn-icon btn-primary" data-toggle="tooltip" title="Submit" onclick="$('#btn-post-submit').click();"><i class="fas fa-save"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
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
                    <form id="form-post-store" action="{{$url}}" method="POST">
                      @csrf
                      @method($method)
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                        <div class="col-sm-12 col-md-7">
                          <input type="text" class="form-control" name="title" value="{{old('title') ?? ( empty($post->title) ? '' : $post->title )}}" required>
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                        <div class="col-sm-12 col-md-7">
                          <select class="form-control selectric" name="category_id" required>
                            @foreach ($categories as $c)
                              <option value="{{$c->id}}" @if ((old('category_id') ?? ( empty($post->category->id) ? null : $post->category->id )) == $c->id) selected @endif>{{$c->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Publish</label>
                        <label class="custom-switch">
                            <input type="checkbox" class="custom-switch-input"
                              @if ((old('status') ?? ( empty($post->status) ? Constant::FALSE_CONDITION : $post->status )) == Constant::TRUE_CONDITION) checked @endif>
                            <span class="custom-switch-indicator"></span>
                        </label>
                        <input type="hidden" name="status" value="{{ old('status') ?? ( empty($post->status) ? Constant::FALSE_CONDITION : $post->status ) }}">
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                        <div class="col-sm-12 col-md-7">
                          <textarea name="content" class="summernote">{{old('content') ?? ( empty($post->content) ? null : $post->content )}}</textarea>
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                          <a href="{{route('post.index')}}" class="btn btn-warning">Cancel</a>
                          <button id="btn-post-submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
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
  <!-- JS Libraies -->
  <script src="{{asset('stisla/node_modules/js/summernote-bs4.js')}}"></script>
  <script src="{{asset('stisla/node_modules/js/codemirror.js')}}"></script>
  <script src="{{asset('stisla/node_modules/js/javascript.js')}}"></script>
  <script src="{{asset('stisla/node_modules/js/jquery.selectric.min.js')}}"></script>

<script>
  $('input[type="checkbox"].custom-switch-input').on('change', function() {
    obj_stat = $('input[name="status"]');
    if ($(this).prop('checked')) {
      obj_stat.val('{{Constant::TRUE_CONDITION}}');
    } else {
      obj_stat.val('{{Constant::FALSE_CONDITION}}');
    }
  });
</script>
@endpush

@push('after-script')
<script></script>
@endpush