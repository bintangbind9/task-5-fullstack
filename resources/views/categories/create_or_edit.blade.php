@extends('layouts.master')
@section('title',$section_header)
@push('pages-style')
<style>
</style>
@endpush

@section('content')
    @include('layouts.alert')

    <div class="section-body">
            <h2 class="section-title">{{$section_header}}</h2>
            <p class="section-lead">Here, you can add or edit category for your article.</p>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Submit Category</h4>
                    <div class="card-header-action">
                      <a href="{{route('category.index')}}" class="btn btn-icon btn-warning" data-toggle="tooltip" title="Cancel"><i class="fas fa-times"></i></a>
                      <button class="btn btn-icon btn-primary" data-toggle="tooltip" title="Submit" onclick="$('#btn-category-submit').click();"><i class="fas fa-save"></i></button>
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
                    <form id="form-category-store" action="{{$url}}" method="POST">
                      @csrf
                      @method($method)
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                        <div class="col-sm-12 col-md-7">
                          <input type="text" class="form-control" name="name" value="{{old('name') ?? ( empty($category->name) ? '' : $category->name )}}" required>
                        </div>
                      </div>
                      <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                          <a href="{{route('category.index')}}" class="btn btn-warning">Cancel</a>
                          <button id="btn-category-submit" class="btn btn-primary">Submit</button>
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
<script>
</script>
@endpush

@push('after-script')
<script></script>
@endpush