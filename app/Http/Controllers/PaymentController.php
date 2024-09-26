<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Debt;
use App\Models\Enrollment;
use App\Models\Career;

class PaymentController extends Controller
{
    public function index()
    {
        return view('web.admin.payments.index');
    }
    public function show(Request $request)
    {
        Log::info('Entering show method');

        $search = $request->input('search');
        $productId = $request->input('product');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $query = Payment::with(['student', 'product']);

        if ($search) {
            $query->whereHas('student', function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%");
            });
        }

        if ($productId) {
            Log::info('Product ID for filtering: ' . $productId);
            $query->where('id_product', $productId);
        }

        // Filtro por fecha
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $payments = $query->paginate(10);
        $products = Product::all(); // Obtener todos los productos

        Log::info('Payments data: ', ['payments' => $payments->items()]);

        return view('web.admin.payments.show_payments', compact('payments', 'products'));
    }
    public function showProducts(Request $request)
    {
        Log::info('Entering showProducts method');

        $search = $request->input('search');
        $query = Product::query();

        if ($search) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        $products = $query->paginate(10);
        Log::info('Products data: ', ['products' => $products]);

        return view('web.admin.payments.show_products', compact('products'));
    }
    public function showDebts(Request $request)
    {
        Log::info('Entering showDebts method');

        $search = $request->input('search');
        $productId = $request->input('product');
        $query = Debt::with(['student', 'product']);

        if ($search) {
            $query->whereHas('student', function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%");
            });
        }

        if ($productId) {
            Log::info('Product ID for filtering: ' . $productId);
            $query->where('id_product', $productId);
        }

        $debts = $query->paginate(10);
        $products = Product::all(); // Obtener todos los productos

        Log::info('Debts data: ', ['debts' => $debts->items()]);

        return view('web.admin.payments.show_debts', compact('debts', 'products'));
    }
    public function storeProduct(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|between:0,999999.99',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.payments.show_products')->with('success', 'Producto agregado exitosamente.');
    }
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|between:0,999999.99',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.payments.show_products')->with('success', 'Producto actualizado exitosamente.');
    }
    public function destroyProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.payments.show_products')->with('success', 'Producto eliminado exitosamente.');
    }
    public function operatePayment(Request $request)
    {
        $search = $request->input('search');
        $careerId = $request->input('career');
        $query = Enrollment::with(['student', 'career']);

        if ($search) {
            $query->whereHas('student', function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%");
            });
        }

        if ($careerId) {
            $query->where('id_career', $careerId);
        }

        $enrollments = $query->paginate(10);
        $products = Product::all(); // Obtener todos los productos
        $careers = Career::all(); // Obtener todas las carreras

        return view('web.admin.payments.operate_payment', compact('enrollments', 'products', 'careers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|between:0,999999.99',
        ]);

        $product = Product::findOrFail($request->product_id);
        $monto_pagado = $request->price;
        $monto_total = $product->precio;

        $debtId = null;

        // Si el monto pagado es menor que el monto total, registrar la deuda
        if ($monto_pagado < $monto_total) {
            $monto_pendiente = $monto_total - $monto_pagado;

            $debt = Debt::create([
                'id_student' => $request->student_id,
                'id_product' => $request->product_id,
                'monto_pendiente' => $monto_pendiente,
            ]);

            $debtId = $debt->id;
        }

        // Registrar el pago
        Payment::create([
            'id_student' => $request->student_id,
            'id_product' => $request->product_id,
            'monto_pagado' => $monto_pagado,
            'fecha' => now(),
            'id_debt' => $debtId,
        ]);

        return redirect()->route('admin.payments.operate_payment')->with('success', 'Pago registrado exitosamente.');
    }
    public function payDebt(Request $request)
    {
        $request->validate([
            'debt_id' => 'required|exists:debts,id',
            'monto_pagado' => 'required|numeric|between:0,999999.99',
        ]);

        $debt = Debt::findOrFail($request->debt_id);
        $monto_pagado = $request->monto_pagado;
        $monto_pendiente = $debt->monto_pendiente;

        // Registrar el pago
        Payment::create([
            'id_student' => $debt->id_student,
            'id_product' => $debt->id_product,
            'monto_pagado' => $monto_pagado,
            'fecha' => now(),
            'id_debt' => $debt->id,
        ]);

        // Actualizar la deuda
        if ($monto_pagado >= $monto_pendiente) {
            // Actualizar el id_debt de los pagos asociados a null antes de eliminar la deuda
            Payment::where('id_debt', $debt->id)->update(['id_debt' => null]);
            $debt->delete();
        } else {
            $debt->monto_pendiente -= $monto_pagado;
            $debt->save();
        }

        return redirect()->route('admin.payments.show_debts')->with('success', 'Pago registrado exitosamente.');
    }
}