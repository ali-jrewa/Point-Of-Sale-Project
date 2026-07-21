@if(!empty($preview))
<div  class=" preview-toolbar">
    <a href="{{ $backUrl }}" class="btn-toolbar-action btn-back">
        <i class="fa fa-arrow-left"></i> Back to Reports
    </a>

    <form method="get" action="{{ request()->url() }}" class="report-range-form">
        <label>
            From:
            <input type="date" name="from" value="{{ request()->query('from', $data['from'] ?? '') }}">
        </label>
        <label>
            To:
            <input type="date" name="to" value="{{ request()->query('to', $data['to'] ?? '') }}">
        </label>
        <button type="submit" class="btn-toolbar-action btn-apply">Apply</button>
        <a href="{{ request()->url() }}" class="btn-toolbar-action btn-reset">Reset</a>
    </form>

    <a href="{{ $downloadUrl }}" class="btn-toolbar-action btn-download">
        <i class="fa fa-download"></i> Download PDF
    </a>
</div>
<style>
    .preview-toolbar {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 15px;
        font-family: sans-serif;
    }
    .btn-toolbar-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid transparent;
        transition: background-color 0.15s ease-in-out;
    }
    .btn-back {
        background: #0230a3;
        color: #fff;
        border-color: #ced4da;
    }
    .btn-back:hover { background: #f1f3f5; color: #212529; }
    .btn-download { background: #495057; color: #fff; }
    .btn-download:hover { background: #343a40; color: #fff; }
    .report-range-form { display: inline-flex; gap: 8px; align-items: center; }
    .report-range-form input[type="date"] { padding: 6px 8px; border-radius: 4px; border: 1px solid #ced4da; }
    .btn-apply { background: #198754; color: #fff; border: none; }
    .btn-reset { background: transparent; color: #212529; border: 1px solid #ced4da; padding: 6px 10px; border-radius: 4px; }
</style>
@endif
