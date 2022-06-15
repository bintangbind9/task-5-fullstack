@extends('layouts.master')
@section('title',$section_header)
@push('pages-style')

<!-- CSS Libraries -->
<link rel="stylesheet" href="{{asset('stisla/node_modules/css/jqvmap.min.css')}}">
<link rel="stylesheet" href="{{asset('stisla/node_modules/css/weather-icons.min.css')}}">
<link rel="stylesheet" href="{{asset('stisla/node_modules/css/weather-icons-wind.min.css')}}">
<link rel="stylesheet" href="{{asset('stisla/node_modules/css/summernote-bs4.css')}}">

<style>
</style>
@endpush

@section('content')
    @include('layouts.alert')

    <div class="section-body">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{count($posts) > 1 ? 'Articles' : 'Article'}}</h4>
                  </div>
                  <div class="card-body">
                    {{count($posts)}}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-newspaper"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{count($categories) > 1 ? 'Categories' : 'Category'}}</h4>
                  </div>
                  <div class="card-body">
                    {{count($categories)}}
                  </div>
                </div>
              </div>
            </div>
            @role(Constant::ROLE_ADMIN)
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="far fa-file"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total {{count($users) > 1 ? 'Users' : 'User' }}</h4>
                  </div>
                  <div class="card-body">
                    {{count($users)}}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-circle"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total {{count($roles) > 1 ? 'Roles' : 'Role'}}</h4>
                  </div>
                  <div class="card-body">
                    {{count($roles)}}
                  </div>
                </div>
              </div>
            </div>
            @endrole
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
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
              <form method="post" action="{{route('post.store_on_home')}}" class="needs-validation" novalidate="">
                @csrf
                @method('post')
                <div class="card">
                  <div class="card-header">
                    <h4>Quick Draft</h4>
                  </div>
                  <div class="card-body pb-0">
                    <input type="hidden" name="status" value="{{Constant::FALSE_CONDITION}}">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" class="form-control" value="{{old('title')}}" required>
                      <div class="invalid-feedback">
                        Please fill in the title
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Category</label>
                      <select name="category_id" class="form-control" required>
                        @foreach ($categories as $c)
                          <option value="{{$c->id}}" @if (old('category_id') == $c->id) selected @endif>{{$c->name}}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">
                        Please select the category
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Content</label>
                      <textarea name="content" class="summernote-simple">{{old('content')}}</textarea>
                    </div>
                  </div>
                  <div class="card-footer pt-0">
                    <button class="btn btn-primary">Save Draft</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Recent Posts</h4>
                </div>
                <div class="card-body">
                  @if (count($posts) > 0)
                    <ul class="list-unstyled list-unstyled-border">
                      @foreach ($posts as $post_no => $p)
                        <li class="media">
                          <div class="media-body">
                            <div class="float-right text-primary">
                              {{Carbon\Carbon::parse($p->created_at)->format(Constant::FORMAT_DATE_TIME)}} | 
                              <i class="fas {{$p->status == Constant::TRUE_CONDITION ? 'fa-check' : 'fa-sticky-note'}}"
                                data-toggle="tooltip"
                                title="{{$p->status == Constant::TRUE_CONDITION ? 'Published' : 'Draft'}}"
                                style="cursor:pointer;"></i>
                            </div>
                            <div class="media-title">{{$p->user->name}}</div>
                            <span class="text-small text-muted">{{$p->title}}</span>
                          </div>
                        </li>
                        @if ($post_no == 4)
                          @break
                        @endif
                      @endforeach
                    </ul>
                    <div class="text-center pt-1 pb-1">
                      <a href="{{route('post.index')}}" class="btn btn-primary btn-lg btn-round">
                        View All
                      </a>
                    </div>
                  @else
                    <div class="text-center pt-1 pb-1">
                      <span class="btn btn-primary btn-lg btn-round">Nothing to show</span>
                    </div>
                  @endif
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
<script src="{{asset('stisla/node_modules/js/jquery.simpleWeather.min.js')}}"></script>
<script src="{{asset('stisla/node_modules/js/Chart.min.js')}}"></script>
<script src="{{asset('stisla/node_modules/js/jquery.vmap.min.js')}}"></script>
<script src="{{asset('stisla/node_modules/js/jquery.vmap.world.js')}}"></script>
<script src="{{asset('stisla/node_modules/js/summernote-bs4.js')}}"></script>
<script src="{{asset('stisla/node_modules/js/jquery.chocolat.min.js')}}"></script>

<!-- Page Specific JS File -->
{{-- <script src="{{asset('stisla/js/page/index-0.js')}}"></script> --}}

<script></script>
@endpush

@push('after-script')
<script></script>
@endpush