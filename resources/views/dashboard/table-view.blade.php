@extends('layouts.main')

@section('main-title')
    {{ $title }}
@endsection

@section('additional-main-head')
    <link rel="stylesheet" href="/css/dashboard/table-view.css">

    {{-- trix editor cdn link --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endsection

@section('container')
    <main class="d-flex align-items-center justify-content-center min-vh-100 bg-light-subtle">
        <div class="d-flex gap-3">
            <a href="/dashboard/shortcut" class="pt-2 text-decoration-none align-self-start">
                <i data-feather="arrow-left" class="aspect-ratio icon-w-15"></i>
                Back
            </a>

            <form action="/dashboard/shortcut/view/action" class="view-item-container bg-white p-4" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="d-flex align-items-center justify-content-between">
                    <input type="text" name="title" class="view-item-title mb-2 border-0 bg-transparent w-100 p-0"
                        value="{{ $item->title }}">
                </div>

                <div>
                    <input type="hidden" id="x" placeholder="Description" name="description"
                        value="{{ $item->description }}">

                    <div class="d-flex flex-column-reverse">
                        <div class="d-flex align-items-center justify-content-between">
                            <trix-toolbar class="mt-2" id="trix-toolbar-1"></trix-toolbar>
                            <div>
                                <a href=""
                                    class="view-item-save-btn text-decoration-none border-0 d-flex align-items-center gap-1"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-feather="chevron-up" class="aspect-ratio icon-w-19 order-1"></i>
                                    Action
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action" value="save">Save</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action"
                                            value="delete">Delete</button>
                                    </li>
                                    <li class="dropdown-item">
                                        <button class="border-0 bg-transparent" name="action" value="shortcut">
                                            {{ $item->is_shortcut == 0 ? 'Add to shortcut' : 'Remove from shortcut' }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <trix-editor toolbar="trix-toolbar-1" input="x" class="custom-trix p-0 border-0 overflow-auto"
                            placeholder="Description"></trix-editor>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
