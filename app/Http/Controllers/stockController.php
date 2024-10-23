<?php

namespace App\Http\Controllers;

use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class stockController extends Controller
{
    public function viewStock(){
        $stocks = Stock::all();
        $manager_stocks=DB::table('manager_stock')->select('quantity','id')->get();

        return view('stock.index' ,compact('stocks'));
    }
    public function create()
    {
        return view('stock.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:stock,name',
            'quantity' => 'required|integer|min:0',
        ]);
    
        $validatedData['status'] = $request->has('status') ? 1 : 0;
    
        Stock::create($validatedData);

        return redirect()->route('stock.index')->with('status', 'Stock added successfully');
    }

    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        return view('stock.edit', compact('stock'));
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|unique:stock,name,' . $stock->id,
            'quantity' => 'required|integer|min:0',
        ]);
    
        $validatedData['status'] = $request->has('status') ? 1 : 0;
        $stock->update($validatedData);

        return redirect()->route('stock.index')->with('status', 'Stock updated successfully');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stock.index')->with('status', 'Stock deleted successfully');
    }
}
