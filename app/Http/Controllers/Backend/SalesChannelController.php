<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SalesChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SalesChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $channels = SalesChannel::query()->orderBy('sort_order');
            return DataTables::of($channels)
                ->addIndexColumn()
                ->addColumn('name_badge', fn($c) => '<span class="badge" style="background-color:'.e($c->color).';color:#fff;"><i class="'.e($c->icon ?? 'fas fa-tag').'"></i> '.e($c->name).'</span>')
                ->addColumn('commission_formatted', fn($c) => number_format((float)$c->commission_percent, 2).' %')
                ->addColumn('status_badge', fn($c) => $c->status
                    ? '<span class="badge bg-success">เปิดใช้งาน</span>'
                    : '<span class="badge bg-secondary">ปิด</span>')
                ->addColumn('action', function ($c) {
                    $edit = '<a href="'.route('backend.admin.sales-channels.edit', $c->id).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> แก้ไข</a>';
                    $del = '';
                    if ($c->slug !== 'walk_in') {
                        $del = ' <form action="'.route('backend.admin.sales-channels.destroy', $c->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'ลบช่องทางนี้?\');">'.csrf_field().method_field('DELETE').'<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> ลบ</button></form>';
                    }
                    return $edit.$del;
                })
                ->rawColumns(['name_badge', 'status_badge', 'action'])
                ->toJson();
        }
        return view('backend.sales-channels.index');
    }

    public function create()
    {
        return view('backend.sales-channels.create');
    }

    public function store(Request $request)
    {
        $request->merge(['status' => $request->has('status')]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:50|unique:sales_channels,slug',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'commission_percent' => 'required|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name'], '_');
        SalesChannel::create($data);

        return redirect()->route('backend.admin.sales-channels.index')->with('success', 'เพิ่มช่องทางการขายเรียบร้อย');
    }

    public function edit(SalesChannel $salesChannel)
    {
        return view('backend.sales-channels.edit', compact('salesChannel'));
    }

    public function update(Request $request, SalesChannel $salesChannel)
    {
        $request->merge(['status' => $request->has('status')]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:50|unique:sales_channels,slug,'.$salesChannel->id,
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'commission_percent' => 'required|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: $salesChannel->slug;
        $salesChannel->update($data);

        return redirect()->route('backend.admin.sales-channels.index')->with('success', 'อัปเดตช่องทางการขายเรียบร้อย');
    }

    public function destroy(SalesChannel $salesChannel)
    {
        if ($salesChannel->slug === 'walk_in') {
            return back()->with('error', 'ไม่สามารถลบช่องทางหน้าร้านได้');
        }
        $salesChannel->delete();
        return redirect()->route('backend.admin.sales-channels.index')->with('success', 'ลบช่องทางเรียบร้อย');
    }
}
