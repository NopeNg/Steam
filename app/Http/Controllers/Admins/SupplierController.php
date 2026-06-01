<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\SupplierProvider;
use App\Models\Game;
use App\Models\GameSupplierMapping;
use App\Services\SupplierManagerService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private SupplierManagerService $supplierManager;

    public function __construct(SupplierManagerService $supplierManager)
    {
        $this->supplierManager = $supplierManager;
    }

    // ============ SUPPLIER PROVIDERS ============

    public function index(Request $request)
    {
        $query = SupplierProvider::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('base_url', 'like', '%' . $search . '%');
            });
        }

        $suppliers = $query->orderBy('priority', 'desc')->orderBy('name')->paginate(15);

        return view('Admins.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('Admins.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:supplier_providers,code',
            'base_url' => 'required|url|max:500',
            'api_key' => 'nullable|string|max:500',
            'api_key_header' => 'required|string|max:100',
            'timeout' => 'required|integer|min:1|max:120',
            'priority' => 'required|integer|min:0|max:999',
            'purchase_endpoint' => 'required|string|max:255',
            'verify_endpoint' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'notes' => 'nullable|string',
            'headers' => 'nullable|json',
        ], [
            'name.required' => 'Tên nhà cung cấp không được để trống.',
            'code.required' => 'Mã code không được để trống.',
            'code.unique' => 'Mã code này đã tồn tại.',
            'base_url.required' => 'URL không được để trống.',
            'base_url.url' => 'URL không hợp lệ.',
            'timeout.min' => 'Timeout tối thiểu là 1 giây.',
            'priority.min' => 'Priority tối thiểu là 0.',
            'purchase_endpoint.required' => 'Endpoint mua key không được để trống.',
            'verify_endpoint.required' => 'Endpoint xác thực key không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'headers.json' => 'Headers phải là JSON hợp lệ.',
        ]);

        $data = $request->only([
            'name', 'code', 'base_url', 'api_key', 'api_key_header',
            'timeout', 'priority', 'purchase_endpoint', 'verify_endpoint', 'status', 'notes'
        ]);

        if ($request->filled('headers')) {
            $data['headers'] = json_decode($request->headers, true);
        }

        SupplierProvider::create($data);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Đã thêm nhà cung cấp "' . $request->name . '" thành công!');
    }

    public function edit($id)
    {
        $supplier = SupplierProvider::findOrFail($id);
        return view('Admins.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = SupplierProvider::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:supplier_providers,code,' . $id,
            'base_url' => 'required|url|max:500',
            'api_key' => 'nullable|string|max:500',
            'api_key_header' => 'required|string|max:100',
            'timeout' => 'required|integer|min:1|max:120',
            'priority' => 'required|integer|min:0|max:999',
            'purchase_endpoint' => 'required|string|max:255',
            'verify_endpoint' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'notes' => 'nullable|string',
            'headers' => 'nullable|json',
        ]);

        $data = $request->only([
            'name', 'code', 'base_url', 'api_key', 'api_key_header',
            'timeout', 'priority', 'purchase_endpoint', 'verify_endpoint', 'status', 'notes'
        ]);

        if ($request->filled('headers')) {
            $data['headers'] = json_decode($request->headers, true);
        } else {
            $data['headers'] = null;
        }

        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Đã cập nhật nhà cung cấp "' . $supplier->name . '" thành công!');
    }

    public function destroy($id)
    {
        $supplier = SupplierProvider::findOrFail($id);

        // Kiểm tra có mapping game không
        if ($supplier->gameMappings()->count() > 0) {
            return redirect()->back()->withErrors([
                'error' => 'Không thể xóa nhà cung cấp này vì đang có ' . $supplier->gameMappings()->count() . ' game liên kết. Hãy gỡ liên kết trước.'
            ]);
        }

        $supplier->delete();
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Đã xóa nhà cung cấp "' . $supplier->name . '" thành công!');
    }

    public function healthCheck($id)
    {
        $supplier = SupplierProvider::findOrFail($id);
        $result = $this->supplierManager->healthCheck($supplier);

        if ($result['success']) {
            return back()->with('success', 'Nhà cung cấp "' . $supplier->name . '" hoạt động tốt! Response: ' . json_encode($result['data']));
        }

        return back()->withErrors(['error' => 'Không thể kết nối đến "' . $supplier->name . '": ' . ($result['error'] ?? 'Unknown error')]);
    }

    public function toggleStatus($id)
    {
        $supplier = SupplierProvider::findOrFail($id);
        $newStatus = $supplier->status === 'Active' ? 'Inactive' : 'Active';
        $supplier->update(['status' => $newStatus]);

        return back()->with('success', 'Đã ' . ($newStatus === 'Active' ? 'kích hoạt' : 'vô hiệu hóa') . ' nhà cung cấp "' . $supplier->name . '" thành công!');
    }

    // ============ GAME-SUPPLIER MAPPING ============

    public function mappingIndex(Request $request)
    {
        $mappings = GameSupplierMapping::with(['game', 'supplierProvider'])
            ->orderBy('game_id')
            ->paginate(20);

        return view('Admins.suppliers.mapping', compact('mappings'));
    }

    public function mappingCreate()
    {
        $games = Game::orderBy('name')->get();
        $suppliers = SupplierProvider::where('status', 'Active')->orderBy('name')->get();
        return view('Admins.suppliers.mapping-create', compact('games', 'suppliers'));
    }

    public function mappingStore(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'supplier_provider_id' => 'required|exists:supplier_providers,id',
            'supplier_game_id' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ], [
            'game_id.required' => 'Vui lòng chọn game.',
            'supplier_provider_id.required' => 'Vui lòng chọn nhà cung cấp.',
            'supplier_provider_id.exists' => 'Nhà cung cấp không tồn tại.',
            'game_id.exists' => 'Game không tồn tại.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        // Kiểm tra trùng
        $exists = GameSupplierMapping::where('game_id', $request->game_id)
            ->where('supplier_provider_id', $request->supplier_provider_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Mapping này đã tồn tại.'])->withInput();
        }

        GameSupplierMapping::create($request->only([
            'game_id', 'supplier_provider_id', 'supplier_game_id', 'status'
        ]));

        return redirect()->route('admin.suppliers.mapping')
            ->with('success', 'Đã liên kết game với nhà cung cấp thành công!');
    }

    public function mappingEdit($id)
    {
        $mapping = GameSupplierMapping::with(['game', 'supplierProvider'])->findOrFail($id);
        $games = Game::orderBy('name')->get();
        $suppliers = SupplierProvider::where('status', 'Active')->orderBy('name')->get();
        return view('Admins.suppliers.mapping-edit', compact('mapping', 'games', 'suppliers'));
    }

    public function mappingUpdate(Request $request, $id)
    {
        $mapping = GameSupplierMapping::findOrFail($id);

        $request->validate([
            'game_id' => 'required|exists:games,id',
            'supplier_provider_id' => 'required|exists:supplier_providers,id',
            'supplier_game_id' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $exists = GameSupplierMapping::where('game_id', $request->game_id)
            ->where('supplier_provider_id', $request->supplier_provider_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Mapping này đã tồn tại.'])->withInput();
        }

        $mapping->update($request->only([
            'game_id', 'supplier_provider_id', 'supplier_game_id', 'status'
        ]));

        return redirect()->route('admin.suppliers.mapping')
            ->with('success', 'Đã cập nhật liên kết thành công!');
    }

    public function mappingDestroy($id)
    {
        $mapping = GameSupplierMapping::findOrFail($id);
        $mapping->delete();
        return redirect()->route('admin.suppliers.mapping')
            ->with('success', 'Đã xóa liên kết thành công!');
    }
}