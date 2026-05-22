<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RewardRule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RewardRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Owner|Admin');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rules = RewardRule::query();
            return DataTables::of($rules)
                ->addIndexColumn()
                ->addColumn('status', fn($data) => $data->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
                ->addColumn('type_formatted', fn($data) => ucwords(str_replace('_', ' ', $data->type)))
                ->addColumn('benefit', function($data) {
                    if ($data->benefit_type == 'percent_discount') return $data->benefit_value . '%';
                    if ($data->benefit_type == 'fixed_discount') return '฿' . $data->benefit_value;
                    if ($data->benefit_type == 'bonus_points') return '+' . $data->benefit_value . ' Pts';
                    return $data->benefit_value;
                })
                ->addColumn('action', function ($data) {
                    $buttons = '<a href="'.route('backend.admin.reward-rules.edit', $data->id).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>';
                    $buttons .= ' <form action="'.route('backend.admin.reward-rules.destroy', $data->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this rule?\');">'.csrf_field().method_field('DELETE').'<button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button></form>';
                    return $buttons;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }
        return view('backend.reward-rules.index');
    }

    public function create()
    {
        return view('backend.reward-rules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'benefit_type' => 'required|string',
            'benefit_value' => 'required|numeric|min:0',
            'status' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'integer',
            'min_purchase' => 'numeric|min:0',
            'required_points' => 'integer|min:0',
            'customer_tier' => 'nullable|string',
            'usage_limit' => 'nullable|integer|min:1',
            'per_customer_limit' => 'nullable|integer|min:1',
            'is_stackable' => 'boolean',
            'coupon_code' => 'nullable|string|unique:reward_rules,coupon_code',
        ]);
        
        $data['status'] = $request->has('status');
        $data['is_stackable'] = $request->has('is_stackable');

        RewardRule::create($data);
        return redirect()->route('backend.admin.reward-rules.index')->with('success', 'Reward Rule created successfully');
    }

    public function edit(RewardRule $rewardRule)
    {
        return view('backend.reward-rules.edit', compact('rewardRule'));
    }

    public function update(Request $request, RewardRule $rewardRule)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'benefit_type' => 'required|string',
            'benefit_value' => 'required|numeric|min:0',
            'status' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'integer',
            'min_purchase' => 'numeric|min:0',
            'required_points' => 'integer|min:0',
            'customer_tier' => 'nullable|string',
            'usage_limit' => 'nullable|integer|min:1',
            'per_customer_limit' => 'nullable|integer|min:1',
            'is_stackable' => 'boolean',
            'coupon_code' => 'nullable|string|unique:reward_rules,coupon_code,'.$rewardRule->id,
        ]);

        $data['status'] = $request->has('status');
        $data['is_stackable'] = $request->has('is_stackable');

        $rewardRule->update($data);
        return redirect()->route('backend.admin.reward-rules.index')->with('success', 'Reward Rule updated successfully');
    }

    public function destroy(RewardRule $rewardRule)
    {
        $rewardRule->delete();
        return redirect()->route('backend.admin.reward-rules.index')->with('success', 'Reward Rule deleted successfully');
    }
}
