@if(!empty($preview))
<div  class=" preview-toolbar">
    <a href="{{ $backUrl }}" class="btn-toolbar-action btn-back">
        <i class="fa fa-arrow-left"></i> Back to Reports
    </a>
    <a href="{{ $downloadUrl }}" class="btn-toolbar-action btn-download">
        <i class="fa fa-download"></i> Download PDF
    </a>
</div>
<style>
    .preview-toolbar {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 15px;
        font-family: sans-serif;
    }
    .btn-toolbar-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
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
    .btn-back:hover {
        background: #f1f3f5;
        color: #212529;
    }
    .btn-download {
        background: #495057;
        color: #fff;
    }
    .btn-download:hover {
        background: #343a40;
        color: #fff;
    }
</style>
@endif
