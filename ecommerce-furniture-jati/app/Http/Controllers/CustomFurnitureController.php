<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomFurniture;

class CustomFurnitureController extends Controller
{
    public function index()
    {
        $customFurnitures = CustomFurniture::all();
        return view('admin.kelolacustomefurniture',compact('customFurnitures'));
    }
    public function create()
    {
        return view('user.formcustom');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'type' => 'required|string',
            'size' => 'required|string',
            'finishing' => 'required|string',
            'deadline' => 'required|date',
            'description' => 'nullable|string',
        ]);

        CustomFurniture::create([
            'name' => $request->name,
            'phone_number' => $request->phone,
            'address' => $request->address,
            'type' => $request->type,
            'size' => $request->size,
            'finishing' => $request->finishing,
            'deadline' => $request->deadline,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Berhasil dikirim'], 200);
    }


    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $furniture = CustomFurniture::findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $path = $file->store('payment_proofs', 'public');
            $furniture->payment_proof = $path;
            $furniture->save();
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }


    public function verify($id)
    {
        $custom = CustomFurniture::findOrFail($id);
        $custom->status = 'verified';
        $custom->save();

        return redirect()->back()->with('success', 'Pesanan custom furniture berhasil diverifikasi.');
    }

    public function reject($id)
    {
        $custom = CustomFurniture::findOrFail($id);
        $custom->status = 'rejected';
        $custom->save();

        return redirect()->back()->with('success', 'Pesanan custom furniture telah ditolak.');
    }

}
