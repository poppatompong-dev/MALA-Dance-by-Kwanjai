@extends('backend.master')

@section('title', 'คู่มือการใช้งาน')

@push('style')
    <style>
        .manual-shell {
            display: grid;
            grid-template-columns: minmax(220px, 300px) minmax(0, 1fr);
            gap: 16px;
        }

        .manual-list .list-group-item {
            border-color: #e5e7eb;
        }

        .manual-list .list-group-item.active {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }

        .manual-list .manual-title {
            font-weight: 700;
            line-height: 1.35;
        }

        .manual-list .manual-description {
            font-size: 13px;
            line-height: 1.45;
        }

        .manual-content {
            color: #1f2937;
            font-size: 16px;
            line-height: 1.75;
            overflow-wrap: anywhere;
        }

        .manual-content h1,
        .manual-content h2,
        .manual-content h3 {
            color: #111827;
            font-weight: 800;
            margin-top: 1.35rem;
            margin-bottom: .75rem;
        }

        .manual-content h1 {
            font-size: 1.75rem;
            margin-top: 0;
        }

        .manual-content h2 {
            font-size: 1.35rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: .35rem;
        }

        .manual-content h3 {
            font-size: 1.1rem;
        }

        .manual-content table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .manual-content th,
        .manual-content td {
            border: 1px solid #e5e7eb;
            padding: .5rem .65rem;
            vertical-align: top;
        }

        .manual-content th {
            background: #f9fafb;
        }

        .manual-content code {
            color: #9f1239;
            background: #fff1f2;
            border-radius: 4px;
            padding: .1rem .3rem;
        }

        .manual-content pre,
        .manual-fallback {
            color: #f8fafc;
            background: #111827;
            border-radius: 8px;
            padding: 1rem;
            white-space: pre-wrap;
        }

        .manual-content pre code {
            color: inherit;
            background: transparent;
            padding: 0;
        }

        @media (max-width: 991.98px) {
            .manual-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="manual-shell">
        <aside class="card manual-list">
            <div class="card-header">
                <h2 class="card-title mb-0">เลือกคู่มือ</h2>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($manuals as $key => $manual)
                    <a
                        href="{{ route('backend.admin.manuals.index', ['doc' => $key]) }}"
                        class="list-group-item list-group-item-action {{ $currentKey === $key ? 'active' : '' }}"
                    >
                        <div class="manual-title">{{ $manual['title'] }}</div>
                        <div class="manual-description {{ $currentKey === $key ? 'text-white-50' : 'text-muted' }}">
                            {{ $manual['description'] }}
                        </div>
                    </a>
                @endforeach
            </div>
        </aside>

        <article class="card">
            <div class="card-header">
                <h2 class="card-title mb-1">{{ $current['title'] }}</h2>
                <div class="text-muted">{{ $current['description'] }}</div>
                <small class="text-muted d-block mt-1">ไฟล์ต้นฉบับ: {{ $current['path'] }}</small>
            </div>
            <div class="card-body">
                <div class="manual-content">
                    {!! $content !!}
                </div>
            </div>
        </article>
    </div>
@endsection
