@extends('Players.Layouts.dashboard')

@section('title', 'Lịch sử giao dịch')

@section('dashboard_content')
<div class="space-y-4">
    <h2 class="text-white text-sm font-bold uppercase tracking-wider border-b border-gray-800 pb-2">Lịch sử hóa đơn mua hàng</h2>
    
    <table class="w-full text-left text-xs text-[#c7d5e0]">
        <thead>
            <tr class="bg-[#101822] text-white font-bold border-b border-gray-800">
                <th class="p-3">Mã Đơn</th>
                <th class="p-3">Ngày Mua</th>
                <th class="p-3">Hình Thức</th>
                <th class="p-3">Tổng Tiền</th>
                <th class="p-3">Trạng Thái</th>
            </tr>
        </thead>
     <tbody class="divide-y divide-gray-800">
    @forelse($orders as $order)
        <tr class="bg-[#171a21] hover:bg-[#202b37]">
            <td class="p-3 font-semibold text-sky-400 hover:text-white">
                <a href="{{ route('orders.detail', $order->id) }}">#ORD-{{ $order->id }}</a>
            </td>
            
            <td class="p-3">{{ $order->created_at->format('d/m/Y') }}</td>
            <td class="p-3">{{ $order->order_type }}</td>
            <td class="p-3 text-white font-medium">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
            <td class="p-3 {{ $order->status == 'Completed' ? 'text-emerald-400' : 'text-amber-400' }} font-semibold">
                {{ $order->status }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="p-4 text-center text-gray-500">Bạn chưa có giao dịch nào.</td>
        </tr>
    @endforelse
</tbody>
    </table>
</div>
@endsection