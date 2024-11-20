<div class="modal fade" id="create_task_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/tasks/store" class="form-submit-event modal-content" method="POST">
            @if (!Request::is('projects/tasks/draggable/*') && !Request::is('tasks/draggable'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="task_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_task', 'Create Task') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        @error('title')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">

                            <select class="form-select" name="status_id">
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" class="badge bg-label-{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_status_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('status_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">

                            <select class="form-select" name="priority_id">
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$status->color}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_priority_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                        @error('priority_id')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="task_start_date" name="start_date" class="form-control" value="">
                        @error('start_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="task_end_date" name="due_date" class="form-control" value="">
                        @error('due_date')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

              
                <div class="row" id="selectTaskUsers">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?> <span id="users_associated_with_project"></span><?php if (!empty($project_id)) { ?> (<?= get_label('users_associated_with_project', 'Users associated with project') ?> <b>{{$project->title}}</b>)

                            <?php } ?></label>
                        <div class="input-group">
                            <select id="" class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                <?php if (!empty($project_id)) { ?>
                                    @foreach($toSelectTaskUsers as $user)
                                    <?php
                                    $selected = '';
                                    // Check if task_accessibility is 'project_users' or if the user is the authenticated user
                                    if ($project->task_accessibility == 'project_users' || $user->id == getAuthenticatedUser()->id) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?> <span class="asterisk">*</span></label>
                        <textarea class="form-control" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>