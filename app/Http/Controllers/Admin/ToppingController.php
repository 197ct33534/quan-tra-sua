<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToppingController extends Controller
{
    public function index(): View
    {
        $toppings = Topping::orderBy('name')->get();

        return view('admin.toppings.index', compact('toppings'));
    }

    public function create(): View
    {
        return view('admin.toppings.create', ['topping' => new Topping(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        Topping::create($data);

        return redirect()->route('admin.toppings.index')->with('success', 'Đã thêm topping.');
    }

    public function edit(Topping $topping): View
    {
        return view('admin.toppings.edit', compact('topping'));
    }

    public function update(Request $request, Topping $topping): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $topping->update($data);

        return redirect()->route('admin.toppings.index')->with('success', 'Đã cập nhật topping.');
    }

    public function destroy(Topping $topping): RedirectResponse
    {
        $topping->delete();

        return redirect()->route('admin.toppings.index')->with('success', 'Đã xóa topping.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
        ], [], ['name' => 'tên topping', 'price' => 'giá']);
    }
}
