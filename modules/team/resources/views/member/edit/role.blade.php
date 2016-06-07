<div class="form-horizontal form-label-left">
    <div class="form-group">
        <div class="col-md-12">
            <ul class="employee-roles">
                @if (isset($employeeRoles) && count($employeeRoles)
                    @foreach ($employeeRoles as $employeeRole)
                        <li>

                        </li>
                    @endforeach
                @endif
            </ul>
            <p>
                <button type="button" class="btn-add">
                    <span>{{ trans('team::view.Change') }}</span>
                </button>
            </p>
        </div>
    </div>
</div>

