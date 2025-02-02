@php
    $userId = auth()->user()->id;
    $lists = getLists($userId);
    $tags = getTags($userId);

    $notebooks = getNotebooks($userId);
@endphp

<aside class="border-end bg-light-subtle py-3 px-3">
    <ul class="navbar-nav">

        {{-- profile setting section start --}}

        <li class="mb-3">
            <a href="/user/profile" class="text-decoration-none d-flex align-items-center gap-2" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="User profile"
                    class="user-profile icon-aspect-ratio rounded-circle">
                <div>
                    <p class="email-block text-nowrap overflow-x-hidden">{{ auth()->user()->email }}</p>
                    <p class="nickname-block text-nowrap overflow-x-hidden">{{ auth()->user()->nickname ?? '-' }}
                    </p>
                </div>
            </a>

            <ul class="dropdown-menu custom-dropdown-font-size">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-1" href="/dashboard/user/profile">
                        <i data-feather="user" class="icon-aspect-ratio icon-w-16"></i>My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-1" href="/dashboard/user/setting">
                        <i data-feather="settings" class="icon-aspect-ratio icon-w-16"></i>
                        Setting
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-1" href="/logout">
                        <i data-feather="log-out" class="icon-aspect-ratio icon-w-16"></i>
                        Sign out
                    </a>
                </li>
            </ul>
        </li>

        {{-- profile setting section end --}}

        {{-- add new task btn section start --}}
        <li class="mb-2">
            <a href="" class="new-task-btn d-flex align-items-center p-2 gap-1" data-bs-toggle="modal"
                data-bs-target="#sidebarCreateTask">
                <i data-feather="file-plus" class="new-task-btn-icon"></i> New task
            </a>
        </li>
        {{-- add new task btn section end --}}

    </ul>

    {{-- add new task btn modal section start --}}

    <div class="modal fade" id="sidebarCreateTask" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overview-dropdown-dark-theme rounded-0">
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h1 class="overview-add-task-title fs-5">📜 Add new task</h1>
                        <i data-feather="x" class="aspect-ratio icon-w-20" style="cursor: pointer;"
                            data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <form action="/dashboard/sidebar-new-task" method="POST">
                        <input type="text" name="title"
                            class="input-outline-off form-control mb-3 px-0 border-0 rounded-0 border-bottom bg-transparent"
                            placeholder="Title" aria-label="Title">
                        <select
                            class="input-outline-off border-0 border-bottom px-0 rounded-0 form-select mb-3 bg-transparent"
                            aria-label="Default select example" name="priority">
                            <option value="0" selected>⚪ None</option>
                            <option value="1">🟢 Low</option>
                            <option value="2">🔵 Medium</option>
                            <option value="3">🔴 High</option>
                        </select>
                        @csrf
                        <button class="overview-add-task-btn btn btn-primary border-0 mt-2" type="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- add new task btn modal section end --}}

    <hr style="opacity: 0.2;">

    <div class="click-callback">
        {{-- aside commom menu items start --}}
        <ul class="navbar-nav">
            <li onclick="window.location.href='/dashboard/task'"
                class="nav-item px-2 d-flex align-items-center gap-1 @if (request()->is('dashboard/task')) selected-route @endif">
                <i data-feather="file" class="nav-item-icon"></i>
                <span class="font-link-size nav-link d-block">Task</span>
            </li>
            <li onclick="window.location.href='/dashboard/note'"
                class="nav-item px-2 d-flex align-items-center gap-1 @if (request()->is('dashboard/note')) selected-route @endif">
                <i data-feather="file-text" class="nav-item-icon"></i>
                <span class="font-link-size nav-link d-block">Note</span>
            </li>
            <li onclick="window.location.href='/dashboard/shortcut'"
                class="nav-item px-2 d-flex align-items-center gap-1 @if (request()->is('dashboard/shortcut')) selected-route @endif">
                <i data-feather="star" class="nav-item-icon"></i>
                <span class="font-link-size nav-link">Shortcut</span>
            </li>
            <li onclick="window.location.href='/dashboard/today'"
                class="nav-item px-2 d-flex align-items-center gap-1 @if (request()->is('dashboard/today')) selected-route @endif">
                <i data-feather="sun" class="nav-item-icon"></i>
                <span class="font-link-size nav-link">Today</span>
            </li>
        </ul>
        {{-- aside common menu items end --}}

        <hr style="opacity: 0.2;">

        {{-- aside tag, lists and notebooks start --}}
        <ul class="navbar-nav">
            <li class="nav-item px-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1 flex-fill" data-bs-toggle="collapse"
                    data-bs-target="#listCollapse" aria-controls="collapseExample">
                    <i data-feather="chevron-down" class="nav-item-icon"></i>
                    <span class="font-link-size nav-link">Lists</span>
                </div>
                <a href="" class="ms-auto nav-item-icon-link" data-bs-toggle="modal"
                    data-bs-target="#addListModal">
                    <i data-feather="plus" class="nav-item-icon"></i>
                </a>

                <div class="modal fade" id="addListModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h1 class="add-new-list-heading mb-3">📋 Add new list</h1>
                                <form action="/dashboard/list/add" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control border-0 border-bottom"
                                            id="exampleFormControlInput1" name="title" placeholder="Title">
                                    </div>
                                    <button class="add-new-list-btn mt-2">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <div class="collapse" id="listCollapse">
                @foreach ($lists as $list)
                    <div onclick="window.location.href='/dashboard/list/{{ $list->id }}/{{ $list->title }}'"
                        class="list-card p-2 d-flex align-items-center justify-content-between">
                        {{ $list->title }}
                    </div>
                @endforeach
            </div>
            <li class="nav-item px-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1 flex-fill" data-bs-toggle="collapse"
                    data-bs-target="#tagsCollapse" aria-controls="collapseExample">
                    <i data-feather="chevron-down" class="nav-item-icon"></i>
                    <span class="font-link-size nav-link">Tags</span>
                </div>
                <a href="" class="ms-auto nav-item-icon-link" data-bs-toggle="modal"
                    data-bs-target="#addTagModal">
                    <i data-feather="plus" class="nav-item-icon"></i>
                </a>

                <div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h1 class="add-new-list-heading mb-3">#️⃣ Add new tag</h1>
                                <form action="/dashboard/tag/add" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control border-0 border-bottom"
                                            id="exampleFormControlInput1" placeholder="Tag title" name="title">
                                    </div>
                                    <select class="input-outline-off border-0 border-bottom form-select mb-2"
                                        aria-label="Default select example" name="color">
                                        <option value="black" selected>⚪ None</option>
                                        <option value="blue">🔵 Blue</option>
                                        <option value="green">🟢 Green</option>
                                        <option value="red">🔴 Red</option>
                                        <option value="cyan">🔵 Cyan</option>
                                        <option value="purple">🟣 purple</option>
                                        <option value="orange">🟠 Orange</option>
                                    </select>
                                    <button class="add-new-list-btn mt-2">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <div class="collapse" id="tagsCollapse">
                @foreach ($tags as $tag)
                    <div onclick="window.location.href='/dashboard/tag/{{ $tag->id }}/{{ $tag->title }}?clr={{ $tag->color }}'"
                        class="list-card color-{{ $tag->color }} p-2 d-flex align-items-center justify-content gap-1 @if (request()->is('dashboard/tag/{{ $tag->id }}/{{ $tag->title }}')) selected-route @endif">
                        <i data-feather="hash" class="icon-aspect-ratio collapse-tag-icon"></i>
                        {{ $tag->title }}
                    </div>
                @endforeach
            </div>
            <li class="nav-item px-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1 flex-fill" data-bs-toggle="collapse"
                    data-bs-target="#notebooksCollapse" aria-controls="collapseExample">
                    <i data-feather="chevron-down" class="nav-item-icon"></i>
                    <span class="font-link-size nav-link">Notebooks</span>
                </div>
                <a href="" class="ms-auto nav-item-icon-link" data-bs-toggle="modal"
                    data-bs-target="#addNotebookModal">
                    <i data-feather="plus" class="nav-item-icon"></i>
                </a>

                <div class="modal fade" id="addNotebookModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h1 class="add-new-list-heading mb-3">📔 Add new notebook</h1>
                                <form action="/dashboard/notebook/add" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control border-0 border-bottom"
                                            id="exampleFormControlInput1" placeholder="Title" name="title">
                                    </div>
                                    <button class="add-new-list-btn mt-2">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <div class="collapse" id="notebooksCollapse">
                @foreach ($notebooks as $notebook)
                    <div onclick="window.location.href='/dashboard/notebook/{{ $notebook->id }}/{{ $notebook->title }}'"
                        class="list-card p-2 d-flex align-items-center justify-cotent-between @if (request()->is('dashboard/notebook/{{ $notebook->id }}/{{ $notebook->title }}')) selected-route @endif">
                        {{ $notebook->title }}
                    </div>
                @endforeach
            </div>
        </ul>
        {{-- aside tags, list and notebooks end --}}

        <hr style="opacity: 0.2;">

        <ul class="navbar-nav">
            <li onclick="window.location.href='/dashboard/complete'"
                class="nav-item px-2 d-flex align-items-center gap-1 @if (request()->is('dashboard/complete')) selected-route @endif">
                <i data-feather="check-circle" class="nav-item-icon"></i>
                <span class="font-link-size nav-link">Complete</span>
            </li>
            <li onclick="window.location.href='/dashboard/trash'"
                class="nav-item mb-2 px-2 d-flex align-items-center gap-1 @if (request()->is('dashboard/trash')) selected-route @endif">
                <i data-feather="trash" class="nav-item-icon"></i>
                <span class="font-link-size nav-link">Trash</span>
            </li>
        </ul>
    </div>
</aside>
