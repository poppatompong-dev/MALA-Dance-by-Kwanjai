<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        abort_if(!auth()->user()->can('currency_view'), 403);
        if ($request->ajax()) {
            $currencies = Currency::latest()->get();
            return DataTables::of($currencies)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('code', fn($data) => $data->code)
                ->addColumn('symbol', fn($data) => $data->symbol
                    . ($data->active ? ' (สกุลเงินหลัก)' : ''))
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                    <button type="button" class="btn bg-gradient-primary btn-flat">จัดการ</button>
                    <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">เปิดเมนู</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" href="' . route('backend.admin.currencies.edit', $data->id) . '" ' . ' >
                    <i class="fas fa-edit"></i> แก้ไข
                </a> <div class="dropdown-divider"></div>
<form action="' . route('backend.admin.currencies.destroy', $data->id) . '"method="POST" style="display:inline;">
                   ' . csrf_field() . '
                    ' . method_field("DELETE") . '
<button type="submit" class="dropdown-item" onclick="return confirm(\'ยืนยันการลบสกุลเงินนี้?\')"><i class="fas fa-trash"></i> ลบ</button>
                  </form><div class="dropdown-divider"></div>
                   <a class="dropdown-item" onclick="return confirm(\'ตั้งเป็นสกุลเงินหลัก?\')" href="' . route('backend.admin.currencies.setDefault', $data->id) . '" ' . ' >
                    <i class="fas fa-edit"></i> ตั้งเป็นสกุลเงินหลัก
                </a>
                  </div>';
                })
                ->rawColumns(['name', 'code', 'symbol', 'action'])
                ->toJson();
        }
        return view('backend.settings.currencies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        abort_if(!auth()->user()->can('currency_create'), 403);
        return view('backend.settings.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        abort_if(!auth()->user()->can('currency_create'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:currencies,code',
            'symbol' => 'required|string'
        ]);
        $currency = Currency::create($request->only(['name', 'code', 'symbol']));

        return redirect()->route('backend.admin.currencies.index')->with('success', 'เพิ่มสกุลเงินเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        abort_if(!auth()->user()->can('currency_update'), 403);

        $currency = Currency::findOrFail($id);
        return view('backend.settings.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        abort_if(!auth()->user()->can('currency_update'), 403);
        $currency = Currency::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string'
        ]);
        $currency->update($request->only(['name', 'code', 'symbol']));
        return redirect()->route('backend.admin.currencies.index')->with('success', 'อัปเดตสกุลเงินเรียบร้อยแล้ว');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        abort_if(!auth()->user()->can('currency_delete'), 403);
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return redirect()->back()->with('success', 'ลบสกุลเงินเรียบร้อยแล้ว');
    }
    public function setDefault($id)
    {
        Currency::where('active', true)->update(['active' => false]);
        $currency = Currency::findOrFail($id);
        $currency->active = true;
        $currency->save();
        Cache::put('default_currency', $currency, 60 * 24);
        return redirect()->back()->with('success', 'ตั้งสกุลเงินหลักเรียบร้อยแล้ว');
    }
}
