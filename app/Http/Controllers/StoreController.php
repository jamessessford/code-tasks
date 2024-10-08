<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreateRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Models\Store;
use Illuminate\View\View;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('stores.index', [
            'stores' => Store::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('stores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreateRequest $request)
    {
        $data = $request->validated();

        Store::create($data);

        return redirect(route('stores.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = Store::findOrFail($id);

        return view('stores.edit', ['store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $store = Store::findOrFail($id);

        $store->update($data);

        return redirect(route('stores.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void {}
}
