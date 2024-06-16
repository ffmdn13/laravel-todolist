@php
    $userId = auth()->user()->id;

    $lists = getLists($userId);
    $tags = getTags($userId);
    $notebooks = getNotebooks($userId);
@endphp

<aside class="border-end bg-light-subtle p-3">
    {{-- aside quick search and add new task start --}}
    <ul class="navbar-nav">
        <a href="" class="new-task-btn mb-2 d-flex align-items-center p-2 gap-1">
            <i data-feather="file-plus" class="new-task-btn-icon"></i> New task
        </a>

        <div class="search-bar mb-2 p-2 d-flex align-items-center gap-1" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i data-feather="search" class="search-bar-icon-link"></i>
            <span>Search</span>
        </div>
        {{-- aside search modal start --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- aside search modal end --}}
    </ul>
    {{-- aside quick search and add new task end --}}

    <hr style="opacity: 0.2;">

    {{-- aside commom menu items start --}}
    <ul class="navbar-nav">
        <li onclick="window.location.href='/dashboard'" class="nav-item px-2 d-flex align-items-center gap-1">
            <i data-feather="home" class="nav-item-icon"></i>
            <span class="font-link-size nav-link">Home</span>
        </li>
        <li onclick="window.location.href='/dashboard/task'" class="nav-item px-2 d-flex align-items-center gap-1">
            <i data-feather="file" class="nav-item-icon"></i>
            <span class="font-link-size nav-link d-block">Task</span>
            <span class="item-count ms-auto">5</span>
        </li>
        <li onclick="window.location.href='/dashboard/note'" class="nav-item px-2 d-flex align-items-center gap-1">
            <i data-feather="file-text" class="nav-item-icon"></i>
            <span class="font-link-size nav-link d-block">Note</span>
            <span class="item-count ms-auto">18</span>
        </li>
        <li onclick="window.location.href='/dashboard/shortcut'" class="nav-item px-2 d-flex align-items-center gap-1">
            <i data-feather="star" class="nav-item-icon"></i>
            <span class="font-link-size nav-link">Shortcut</span>
            <span class="item-count ms-auto">4</span>
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
            <a href="" class="ms-auto nav-item-icon-link" data-bs-toggle="modal" data-bs-target="#addListModal">
                <i data-feather="plus" class="nav-item-icon"></i>
            </a>

            <div class="modal fade" id="addListModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <h1 class="add-new-list-heading mb-3">📋 Add new list</h1>
                            <form action="/dashboard/lists/add" method="POST">
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
                <div onclick="window.location.href='/dashboard/lists/{{ $list->id }}/{{ $list->title }}'"
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
                    class="list-card color-{{ $tag->color }} p-2 d-flex align-items-center justify-content gap-1">
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
                <div onclick="window.location.href='/dashboard/notebooks/{{ $notebook->id }}/{{ $notebook->title }}'"
                    class="list-card p-2 d-flex align-items-center justify-cotent-between">
                    {{ $notebook->title }}
                </div>
            @endforeach
        </div>
    </ul>
    {{-- aside tags, list and notebooks end --}}

    <hr style="opacity: 0.2;">

    {{-- aside task today and 7 days start --}}

    <ul class="navbar-nav">
        <li onclick="window.location.href='/dashboard/today'" class="nav-item px-2 d-flex align-items-center gap-1">
            <i data-feather="sun" class="nav-item-icon"></i>
            <span class="font-link-size nav-link">Today</span>
            <span class="item-count ms-auto">3</span>
        </li>
        <li onclick="window.location.href='/dashboard/next7days'"
            class="nav-item px-2 d-flex align-items-center gap-1">
            <i data-feather="calendar" class="nav-item-icon"></i>
            <span class="font-link-size nav-link">Next 7 days</span>
            <span class="item-count ms-auto">5</span>
        </li>
    </ul>

    {{-- aside task todat and 7 days end --}}

    <hr style="opacity: 0.2;">

    <ul class="navbar-nav">
        <li onclick="window.location.href='/dashboard/complete'"
            class="nav-item mb-2 px-2 d-flex align-items-center gap-1">
            <i data-feather="check-circle" class="nav-item-icon"></i>
            <span class="font-link-size nav-link">Complete</span>
            <span class="item-count ms-auto">3</span>
        </li>
        <li onclick="window.location.href='/dashboard/trash'"
            class="nav-item mb-2 px-2 d-flex align-items-center gap-1">
            <i data-feather="trash" class="nav-item-icon"></i>
            <span class="font-link-size nav-link">Trash</span>
            <span class="item-count ms-auto">3</span>
        </li>
    </ul>
</aside>
