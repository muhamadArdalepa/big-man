<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a></li>
        @if(request()->route()->getname() != 'home')
        @foreach($breadcrumbs as $breadcrumb)
        @if(!$loop->last)
        <li class="breadcrumb-item text-sm text-white"><a class="opacity-5 text-white"
                href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a></li>
        @else
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">{{ $breadcrumb['label'] }}</li>
        @endif
        @endforeach
        @endif
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">{{ $title }}</h6>
</nav>